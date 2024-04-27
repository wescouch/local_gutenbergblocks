<?php

namespace SiteLint\Auth;

use Exception;

/**
 * Class to communicate with SiteLint API.
 *
 * PHP version >=5.3
 *
 * @package    SiteLint
 * @author     <support@sitelint.com>
 * @copyright  since 2022 SiteLint.com
 * @version    Git: $Id$
 */
class Api
{
    private $config;
    private $appConfig;
    private $apiBaseUrl; 

    const OPTION_NAME = 'sitelint';

    /** URL paths for all used resources endpoints methods */
    const URL_CHECK_EMAIL = 'user/check-email',
        URL_LOGIN = 'user/login',
        URL_CREATE = 'user/signup',
        URL_WORKSPACES = 'workspaces/user',
        URL_TOKENS = 'api-token/workspaces',
        URL_REFRESH_ACCESS_TOKEN = 'auth/refreshAccessToken',
        URL_AUDITS = 'audits';
    

    public function __construct()
    {
        $this->config = file_get_contents(__DIR__ . '/../../public/config.json');
        $this->appConfig = json_decode($this->config, true);
        $this->apiBaseUrl = $this->appConfig['apiUrl'];
    }

    /**
     * Allows to create user.
     *
     * @param array $data
     * @return array
     */
    public function create($data)
    {
        return $this->post(self::URL_CREATE, $data);
    }

    /**
     * Allows to log in account and obtain user key.
     *
     * @param array $data
     * @return array
     */
    public function login($data)
    {
        return $this->post(self::URL_LOGIN, $data);
    }

    /**
     * Allows to log in account and obtain user key.
     *
     * @param array $data
     * @return array
     */
    public function checkEmail($email)
    {
        $queryParams = array(
            'email' => $email
        );

        return $this->get(self::URL_CHECK_EMAIL, $queryParams);
    }

    /**
     * Allows to log in account and obtain user key.
     *
     * @param array $data
     * @return array
     */
    public function workspaces($email)
    {
        $queryParams = array(
            'email' => $email
        );

        return $this->get(self::URL_WORKSPACES,  $queryParams);
    }

    /**
     * Allows to log in account and obtain user key.
     *
     * @param array $data
     * @return array
     */
    public function tokens($workspace)
    {
        $queryParams = array(
            "skip" => 0,
            "limit" => 0
        );

        return $this->get(self::URL_TOKENS . "/$workspace", $queryParams);
    }

    /**
     * Allows to log in account and obtain user key.
     *
     * @param array $data
     * @return array
     */
    public function audits($apiToken)
    {
        $queryParams = array(
            "auditTypes" => "accessibility,logs,performance,privacy,quality,security,seo",
            "statuses" => "error",
            "standardVersions" => "1.0,2.0,2.1",
            "standardLevels" => "A,AA,AAA,best_practices",
            "errors" => "true",
            "needsReview" => "true",
            "recommendations" => "true"
        );

        return $this->get(self::URL_AUDITS . "/$apiToken/last", $queryParams);
    }

    /**
     * Helper function to execute POST request.
     *
     * @param string $path request path
     * @param array $data optional POST data array
     * @return array|string array data or json encoded string of result
     * @throws Exception
     */
    private function post($path, $data)
    {
        $option = get_option(self::OPTION_NAME);
        $headers = ['Accept' => 'application/json', 'Content-Type' => 'application/json'];

        if (isset($option['accessToken'])) {
            $headers['Authorization'] = 'Bearer ' . $option['accessToken'];
        }

        $httpParams = [];

        $httpParams['httpversion'] = '1.1';
        $httpParams['headers'] = $headers;
        $httpParams['body'] = json_encode($data);
        $httpParams['sslverify'] = false;

        $response = wp_remote_post($this->apiBaseUrl . $path, $httpParams);

        if (is_wp_error($response)) {
            print_r($response);

            return NULL;
        }

        return $response;
    }

    /**
     * Helper function to execute POST request.
     *
     * @param string $path request path
     * @param array $data optional POST data array
     * @return array|string array data or json encoded string of result
     * @throws Exception
     */
    private function get($path, $query)
    {
        $option = get_option(self::OPTION_NAME);
        $headers = ['Accept' => 'application/json'];

        if (isset($option['accessToken'])) {
            $headers['Authorization'] = 'Bearer ' . $option['accessToken'];
        }

        $httpParams = [];

        $httpParams['httpversion'] = '1.1';
        $httpParams['headers'] = $headers;
        $httpParams['sslverify'] = false;
        $response = wp_remote_get($this->apiBaseUrl . $path . '?' . http_build_query($query), $httpParams);
        if (is_wp_error($response)) {
            print_r($response);

            return NULL;
        }

        $body = json_decode($response['body'], true);

        if (isset($body['status']) && $body['status'] == 403) {
            $this->refreshAccessToken();

            return $this->get($path, $query);
        }

        return $response;
    }

    private function refreshAccessToken()
    {
        $option = get_option(self::OPTION_NAME);

        if (isset($option['accessToken'])) {
            $headers['x-refresh-token'] = $option['refreshToken'];
        }

        $httpParams = [];

        $httpParams['httpversion'] = '1.1';
        $httpParams['headers'] = $headers;
        $httpParams['sslverify'] = false;
        $response = wp_remote_get($this->apiBaseUrl . self::URL_REFRESH_ACCESS_TOKEN, $httpParams);

        if (is_wp_error($response) || isset($body['error'])) {
            $this->deactivate();

            $page = sanitize_url($_SERVER['PHP_SELF']);
            $sec = '1';

            header("Refresh: $sec; url=$page");
        }

        if (isset($response['headers']['x-access-token'])) {
            $this->updateOptions([
                'accessToken' => $response['headers']['x-access-token']
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

    /**
     * @return array
     */
    private function getOptions()
    {
        return get_option(self::OPTION_NAME);
    }
}
