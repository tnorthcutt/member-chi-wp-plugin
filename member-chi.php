<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://memberup.co
 * @since             0.1.0
 * @package           Member_Chi
 *
 * @wordpress-plugin
 * Plugin Name:       Member Chi
 * Plugin URI:        https://memberchi.com
 * Description:       This plugin connects your site to the Chi app at https://app.memberchi.com
 * Version:           1.1.1
 * Author:            Member Chi
 * Author URI:        https://memberchi.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       member-chi
 * Domain Path:       /languages
 * GitHub URI:        tnorthcutt/member-chi-wp-plugin
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-member-chi-activator.php
 */
function activate_member_chi() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-member-chi-activator.php';
	Member_Chi_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-member-chi-deactivator.php
 */
function deactivate_member_chi() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-member-chi-deactivator.php';
	Member_Chi_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_member_chi' );
register_deactivation_hook( __FILE__, 'deactivate_member_chi' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-member-chi.php';

/**
 * Use composer autoload.
 */
require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

/**
 * Use GitHub updater plugin
 * https://github.com/FacetWP/github-updater-lite
 */
include( dirname( __FILE__ ) . '/lib/github-updater.php' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.1.0
 */
function run_member_chi() {
	$plugin = new Member_Chi();
}
run_member_chi();
