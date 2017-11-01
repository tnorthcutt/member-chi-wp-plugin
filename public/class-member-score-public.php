<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://memberup.co
 * @since      0.1.0
 *
 * @package    MemberScore
 * @subpackage MemberScore/public
 * @author     Member Up <travis@memberup.co>
 */
class MemberScore_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1.0
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
	 * @since    0.1.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/member-score-public.css', array(),
			$this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_scripts() {
		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/member-score-public.js',
			array( 'jquery' ), $this->version, false );

		// Set up empty options array
		$options = array();

		// Get logged in user
		$user = wp_get_current_user();

		// Don't load tracking if no logged in user or getting user info otherwise fails
		if ( ( $options['wp_id'] = $user->ID ) == 0 ) {
			return;
		}

		// Are we in debug mode?
		$options['debug'] = member_score_get_option( '_member_score_debug' ) ? true : false;

		// Use appropriate options based on debug mode (or not)
		$options['api_key'] = $options['debug'] ? member_score_get_option( '_member_score_dev_api_key' ) : member_score_get_option( '_member_score_api_key' );
		$options['url']     = member_score_get_app_url() . '/0.1/userevents.js';

		// Set other options we need
		$options['team_id']        = member_score_get_option( '_member_score_team_id' );
		$options['email']          = $user->user_email;
		$options['joined_at']      = member_score_joined_at( $user );
		$options['will_expire_at'] = member_score_expires_at( $user );


		// Pass data to js
		wp_localize_script( $this->plugin_name, 'options', $options );

		// Load js
		wp_enqueue_script( $this->plugin_name );

	}

}

/**
 * @param $user WP_User
 * @return string
 */
function member_score_joined_at($user) {
	if ( class_exists( 'RCP_Member' ) ) {
		$member = new RCP_Member( $user );

		// returns date as a string in the format 2016-08-18 20:24:13
		return $member->get_joined_date();
	}

	// For MemberPress, we need to get the oldest transaction for a user, which represents their join date
	if ( class_exists( 'MeprUser' ) ) {
		$member = new MeprUser( $user->ID );
		$transactions = $member->transactions();
		$oldest = end($transactions);

		// returns date as a string in the format 2016-08-18 20:24:13
		return $oldest->created_at;
	}
	return '';
}

function member_score_expires_at($user) {
	if ( class_exists( 'RCP_Member' ) ) {
		$member = new RCP_Member( $user );
		// returns date as a string in the format 2016-08-18 20:24:13
		return $member->get_expiration_date();
	}
	return '';
}