<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://sitelint.com
 * @since      1.0.0
 *
 * @package    SiteLint
 * @subpackage SiteLint/admin
 */

if (!defined('ABSPATH')) {
    exit(); // Exit if accessed directly
}

define('__SITELINT_ROOT__', dirname(dirname(__FILE__)));
require_once __SITELINT_ROOT__ . '/shared/services/Api.php';

use SiteLint\Auth\Api;

class SiteLintAdmin
{
    const OPTION_NAME = 'sitelint';

    protected static $instance = null;

    private $plugin_name;
    private $message = null;
    private $formAction = null;
    private $email = null;

    /**
     * The version of SiteLint plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of SiteLint plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of SiteLint plugin.
     * @param      string    $version    The version of SiteLint plugin.
     */

    public function __construct($plugin_name = 'sitelint', $version = '1.0.0')
    {
        require_once __DIR__ . '/../includes/functions.php';

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public static function get_instance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'css/styles.css?c=' . filemtime(plugin_dir_path(__FILE__) . 'css/styles.css'),
            false,
            filemtime(plugin_dir_path(__FILE__) . 'css/styles.css')
        );
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'scripts/app.js?c=' . filemtime(plugin_dir_path(__FILE__) . 'scripts/app.js'),
            false,
            filemtime(plugin_dir_path(__FILE__) . 'scripts/app.js')
        );
    }

    public function renderAdminPage()
    {
        $this->render('partials/sitelint-admin-display.php', [
            'domain' => $this->plugin_name,
            'options' => $this->getOptions(),
            'message' => $this->message,
            'formAction' => $this->formAction,
            'email' => $this->email
        ]);
    }

    private function render($template, $vars = [])
    {
        call_user_func_array(function () use ($template, $vars) {
            extract($vars);
            include $template;
        }, []);
    }

    public function addMenuItems()
    {
        $capability = sitelint_get_publish_cap();

        add_menu_page(
            __('SiteLint', 'sitelint'),
            __('SiteLint', 'sitelint'),
            $capability,
            $this->plugin_name,
            [$this, 'renderAdminPage'],
            'dashicons-welcome-view-site'
        );
    }

    public function handleInAdminHeader()
    {
        if (isset($_GET['page']) && $_GET['page'] !== 'sitelint') {
            return;
        }

        remove_all_actions('admin_notices');
        remove_all_actions('all_admin_notices');

        add_action('admin_notices', function () {
            // echo 'Custom notice';
        });
    }

    public function performAction()
    {
        $options = $this->getOptions();
        $action = null;

        if (isset($_POST['_action']) === false && isset($options['apiToken']) === false) {
            return;
        }

        if (isset($_POST['_action']) === true) {
            $action = sanitize_text_field((string) $_POST['_action']);
        }

        $api = new Api();

        if ($action === 'checkEmail') {
            $response = $api->checkEmail(sanitize_email($_POST['email']));

            echo esc_html($response['body']);
            exit();
        }

        if ($action == null && isset($options['apiToken'])) {
            $audits = $api->audits($options['apiToken']);
            $body = json_decode($audits['body'], true);
            $this->updateOptions([
                'audits' => $body
            ]);

            return;
        }

        switch ($action) {
            case 'login':
            case 'register':
                $this->formAction = $action;
                $data = [
                    'email' => sanitize_email($_POST['email']),
                ];

                if (isset($_POST['password'])) {
                    $data['password'] = sanitize_text_field($_POST['password']);
                }

                if (isset($_POST['displayName'])) {
                    $data['displayName'] = sanitize_user($_POST['displayName']);
                }

                if (isset($_POST['aesKey'])) {
                    $data['cryptData'] = [
                        'aesKey' => sanitize_text_field($_POST['aesKey']),
                        'publicKey' => sanitize_text_field($_POST['publicKey']),
                        'privateKey' => sanitize_text_field($_POST['privateKey'])
                    ];
                }

                if (isset($_POST['createdVia'])) {
                    $data['createdVia'] = sanitize_text_field($_POST['createdVia']);
                }

                if (isset($_POST['timezone'])) {
                    $data['timezone'] = sanitize_text_field($_POST['timezone']);
                }

                if (isset($_POST['timezoneOffset'])) {
                    $data['timezoneOffset'] = sanitize_text_field($_POST['timezoneOffset']);
                }

                if (isset($_POST['userLanguage'])) {
                    $data['userLanguage'] = sanitize_text_field($_POST['userLanguage']);
                }

                if (isset($_POST['deriveKey'])) {
                    $data['deriveKey'] = sanitize_text_field($_POST['deriveKey']);
                }

                try {
                    $response = $this->formAction === 'login' ? $api->login($data) : $api->create($data);
                    $body = json_decode($response['body'], true);
                    if (isset($body['app']['user']) && $body['app']['user']['authenticated']) {
                        if (isset($body['app']['token'])) {
                            $this->activate(
                                $body['app']['token']['accessToken'],
                                $body['app']['token']['refreshToken'],
                                sanitize_email($_POST['email']),
                                $body['app']['user']['lastUsedWorkspace']
                            );

                            $apiTokens = $api->tokens($body['app']['user']['lastUsedWorkspace']);

                            $apiTokensBody = json_decode($apiTokens['body'], true);

                            $workspaces = $api->workspaces(sanitize_email($_POST['email']));
                            $workspaces = json_decode($workspaces['body'], true);

                            $this->updateOptions([
                                'workspaces' => $workspaces['workspaces'],
                                'workspace' => $body['app']['user']['lastUsedWorkspace'],
                                'apiToken' => $apiTokensBody['tokens'][0]['tokenId'] . '-' . $apiTokensBody['tokens'][0]['prefix'],
                            ]);

                            $audits = $api->audits($apiTokensBody['tokens'][0]['tokenId'] . '-' . $apiTokensBody['tokens'][0]['prefix']);

                            $auditsBody = json_decode($audits['body'], true);

                            $this->updateOptions([
                                'audits' => $auditsBody,
                            ]);
                        } else {
                            $access_token = '';
                            $refresh_token = '';

                            foreach ($response['headers'] as $key => $header) {
                                if ($key === 'x-access-token') {
                                    $access_token = $header;
                                }

                                if ($key === 'x-refresh-token') {
                                    $refresh_token = $header;
                                }
                            }

                            $this->activate($access_token, $refresh_token, sanitize_email($_POST['email']));

                            $workspaces = $api->workspaces(sanitize_email($_POST['email']));
                            $body = json_decode($workspaces['body'], true);
                            if (isset($body['workspaces'])) {
                                $this->updateOptions([
                                    'workspaces' => $body['workspaces'],
                                ]);
                            }
                        }
                    } else {
                        $this->message = $body['app']['description'];
                        $this->email = sanitize_email($_POST['email']);
                    }
                } catch (Exception $e) {
                    print_r($e);
                    $this->message = $e->getMessage();
                    $this->email = sanitize_email($_POST['email']);
                }
                break;
            case 'loadWorkspaceTokens':
                $this->updateOptions([
                    'workspace' => sanitize_text_field($_POST['workspace']),
                ]);
                $tokens = $api->tokens(sanitize_text_field($_POST['workspace']));
                $body = json_decode($tokens['body'], true);
                $this->updateOptions([
                    'apiTokens' => $body['tokens'],
                ]);
                break;
            case 'updateToken':
                $this->updateOptions([
                    'apiToken' => sanitize_text_field($_POST['apiToken']),
                ]);

                $audits = $api->audits(sanitize_text_field($_POST['apiToken']));
                $body = json_decode($audits['body'], true);
                $this->updateOptions([
                    'audits' => $body
                ]);

                break;
            case 'clearToken':
                $this->updateOptions([
                    'apiToken' => null,
                    'audits' => null,
                ]);
                $workspaces = $api->workspaces($options['email']);
                $body = json_decode($workspaces['body'], true);
                if (isset($body['workspaces'])) {
                    $this->updateOptions([
                        'workspaces' => $body['workspaces'],
                    ]);
                }

                if (isset($options['workspace'])) {
                    $tokens = $api->tokens($options['workspace']);
                    $body = json_decode($tokens['body'], true);
                    $this->updateOptions([
                        'apiTokens' => $body['tokens'],
                    ]);
                }
                break;
            case 'clearWorkspace':
                $this->updateOptions([
                    'workspace' => null,
                    'apiToken' => null,
                    'audits' => null,
                ]);
                $workspaces = $api->workspaces($options['email']);
                $body = json_decode($workspaces['body'], true);
                if (isset($body['workspaces'])) {
                    $this->updateOptions([
                        'workspaces' => $body['workspaces'],
                    ]);
                }
                break;
            case 'allowSiteLint':
                $key = $options['active'] ? false : true;
                $this->updateOptions([
                    'active' => $key
                ]);
                break;
            case 'addSiteLintLogo':
                $key = $options['addLogo'] ? false : true;
                $this->updateOptions([
                    'addLogo' => $key
                ]);
                break;
            case 'disable':
                $this->deactivate();
                break;
            default:
                $this->message = 'Invalid action';
                break;
        }
    }

    private function activate($accessToken, $refreshToken, $email, $workspace = null)
    {
        $this->updateOptions([
            'active' => true,
            'accessToken' => (string) $accessToken,
            'refreshToken' => (string) $refreshToken,
            'apiToken' => null,
            'apiTokens' => null,
            'workspace' => null,
            'workspaces' => null,
            'email' => (string) $email,
            'audits' => null,
            'addLogo' => true
        ]);

        if ($workspace !== null) {
            $this->updateOptions([
                'workspace' => $workspace,
            ]);
        }
    }

    private function deactivate()
    {
        $this->updateOptions([
            'active' => false,
            'accessToken' => null,
            'refreshToken' => null,
            'apiToken' => null,
            'apiTokens' => null,
            'workspace' => null,
            'workspaces' => null,
            'email' => null,
            'audits' => null,
            'addLogo' => false
        ]);
    }

    private function updateOptions(array $options)
    {
        $current = $this->getOptions();
        foreach ($options as $key => $option) {
            $current[$key] = $option;
        }
        update_option(self::OPTION_NAME, $current);
    }

    private function getOptions()
    {
        return get_option(self::OPTION_NAME);
    }
}
