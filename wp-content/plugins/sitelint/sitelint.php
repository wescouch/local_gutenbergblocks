<?php
/**
 *
 * @package   SiteLint
 * @author    SiteLint <support@sitelint.com>
 * @license   GPL-2.0+
 * @link      https://www.sitelint.com
 * @copyright 2022 SiteLint.com
 *
 * Plugin Name:       SiteLint
 * Description:       SiteLint - official plugin. Accessibility, SEO, Performance, Security, Privacy, Technical issues in one place. Client-side & real-time checker.
 * Version:           1.4.0
 * Author:            SiteLint
 * Author URI:        https://www.sitelint.com
 * Text Domain:       sitelint
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Currently plugin version. Use SemVer - https://semver.org
 */
define('SITELINT_VERSION', '1.4.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/sitelint-audits-activator.php
 */
function activate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/sitelint-audits-activator.php';
	Plugin_Name_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/sitelint-audits-deactivator.php
 */
function deactivate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/sitelint-audits-deactivator.php';
	Plugin_Name_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_plugin_name' );
register_deactivation_hook( __FILE__, 'deactivate_plugin_name' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/sitelint-audits.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_sitelint() {

	$plugin = new SiteLintAudits();
	$plugin->run();

}

run_sitelint();
