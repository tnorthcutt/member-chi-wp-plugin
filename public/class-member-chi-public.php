<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://memberup.co
 * @since      1.0.0
 *
 * @package    Member_Chi
 * @subpackage Member_Chi/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Member_Chi
 * @subpackage Member_Chi/public
 * @author     Member Up <travis@memberup.co>
 */
class Member_Chi_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of the plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Member_Chi_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Member_Chi_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/member-chi-public.css', array(),
			$this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Member_Chi_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Member_Chi_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/member-chi-public.js',
			array( 'jquery' ), $this->version, false );

		// Set up empty options array
		$options = array();

		// Get logged in user
		$user = wp_get_current_user();

		// Don't load tracking if no logged in user or getting user info otherwise fails
		if ( $options['wp_id'] = $user->ID == 0 ) {
			return;
		}

		// Are we in debug mode?
		$debug = member_chi_get_option( '_member_chi_debug' ) ? true : false;

		// Use appropriate options based on debug mode (or not)
		$options['api_key'] = $debug ? member_chi_get_option( '_member_chi_dev_api_key' ) : member_chi_get_option( '_member_chi_api_key' );
		$options['url']     = $debug ? 'https://chi.dev/0.1/userevents.js' : 'https://chi.memberup.co/0.1/userevents.js';

		// Set other options we need
		$options['team_id'] = member_chi_get_option( '_member_chi_team_id' );
		$options['email']   = $user->user_email;

		// Pass data to js
		wp_localize_script( $this->plugin_name, 'options', $options );

		// Load js
		wp_enqueue_script( $this->plugin_name );

	}

}
