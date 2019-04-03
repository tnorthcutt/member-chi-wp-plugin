<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://memberscore.io
 * @since             0.1.0
 * @package           MemberScore
 *
 * @wordpress-plugin
 * Plugin Name:       MemberScore
 * Plugin URI:        https://memberscore.io
 * Description:       This plugin connects your site to the MemberScore app at https://app.memberscore.io
 * Version:           1.4.0
 * Author:            Travis Northcutt
 * Author URI:        https://memberup.co
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       member-score
 * Domain Path:       /languages
 * GitHub URI:        tnorthcutt/member-score-wp-plugin
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-member-score-activator.php
 */
function activate_member_score() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-member-score-activator.php';
	MemberScore_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-member-score-deactivator.php
 */
function deactivate_member_score() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-member-score-deactivator.php';
	MemberScore_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_member_score' );
register_deactivation_hook( __FILE__, 'deactivate_member_score' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-member-score.php';

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
 * The main function that returns MemberScore
 *
 * The main function responsible for returning the one true MemberScore
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Inspired by code from Easy Digital Downloads
 *
 * Example: <?php $member_score = member_score(); ?>
 *
 * @since 1.2
 * @return object|MemberScore one true MemberScore Instance.
 */
function member_score() {
	return MemberScore::instance();
}

// Get Member Score Running.
member_score();
