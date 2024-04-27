<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for SiteLint plugin
 * so that it is ready for translation.
 *
 * @link       https://sitelint.com
 * @since      1.0.0
 *
 * @package    SiteLint
 * @subpackage SiteLint/includes
 */

class Plugin_Name_i18n {

	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'sitelint',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
