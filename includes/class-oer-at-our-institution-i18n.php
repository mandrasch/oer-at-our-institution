<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://matthias-andrasch.de
 * @since      1.0.0
 *
 * @package    Oer_At_Our_Institution
 * @subpackage Oer_At_Our_Institution/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Oer_At_Our_Institution
 * @subpackage Oer_At_Our_Institution/includes
 * @author     Matthias Andrasch <info@matthias-andrasch.de>
 */
class Oer_At_Our_Institution_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'oer-at-our-institution',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
