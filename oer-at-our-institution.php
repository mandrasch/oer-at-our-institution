<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://matthias-andrasch.de
 * @since             1.0.0
 * @package           Oer_At_Our_Institution
 *
 * @wordpress-plugin
 * Plugin Name:       OER at our institution
 * Plugin URI:        https://oerhoernchen.de
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Matthias Andrasch
 * Author URI:        https://matthias-andrasch.de
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       oer-at-our-institution
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'OER_AT_OUR_INSTITUTION_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-oer-at-our-institution-activator.php
 */
function activate_oer_at_our_institution() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-oer-at-our-institution-activator.php';
	Oer_At_Our_Institution_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-oer-at-our-institution-deactivator.php
 */
function deactivate_oer_at_our_institution() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-oer-at-our-institution-deactivator.php';
	Oer_At_Our_Institution_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_oer_at_our_institution' );
register_deactivation_hook( __FILE__, 'deactivate_oer_at_our_institution' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-oer-at-our-institution.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_oer_at_our_institution() {

	$plugin = new Oer_At_Our_Institution();
	$plugin->run();

}
run_oer_at_our_institution();
