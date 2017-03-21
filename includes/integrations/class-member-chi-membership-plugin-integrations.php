<?php

/**
 *
 *
 * @since      1.2
 * @package    Member_Chi
 * @subpackage Member_Chi/includes/integrations
 * @author     Member Up <travis@memberup.co>
 */
class Member_Chi_Membership_Plugin_Integrations {

	/**
	 * Member_Chi_Membership_Plugin_Integrations constructor.
	 */
	public  function __construct() {
		$this->define_hooks();
	}

	/**
	 *
	 */
	public function define_hooks() {
		add_action( 'plugins_loaded', array( $this, 'load_integrations' ) );
	}

	/**
	 *
	 */
	public function load_integrations() {

		require_once dirname( __FILE__ ) . '/class-member-chi-membership-plugin-integration.php';
		if ( class_exists( 'RCP_Levels' ) ) {
			require_once dirname( __FILE__ ) . '/restrict-content-pro/class-member-chi-restrict-content-pro-integration.php';
			new Member_Chi_Restrict_Content_Pro_Integration();
		}

		if ( class_exists( 'WPComplete' ) ) {
			require_once dirname( __FILE__ ) . '/wp-complete/class-member-chi-wp-complete-integration.php';
			new Member_Chi_Wp_Complete_Integration();
		}

		if ( class_exists( 'WooCommerce' ) && is_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) ) {
			require_once dirname( __FILE__ ) . '/woocommerce-memberships/class-member-chi-woocommerce-memberships-integration.php';
			new Member_Chi_WooCommerce_Memberships_Integration();
		}

		if ( class_exists( 'bbPress' ) ) {
			require_once dirname( __FILE__ ) . '/bbpress/class-member-chi-bbpress-integration.php';
			new Member_Chi_BBPress_Integration();
		}

	}

}
