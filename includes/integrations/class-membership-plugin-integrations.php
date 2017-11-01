<?php

/**
 *
 *
 * @since      1.2
 * @package    MemberScore
 * @subpackage MemberScore/includes/integrations
 * @author     Member Up <travis@memberup.co>
 */
class MemberScore_Membership_Plugin_Integrations {

	/**
	 * MemberScore_Membership_Plugin_Integrations constructor.
	 */
	public  function __construct() {
		$this->define_hooks();
	}

	/**
	 *
	 */
	public function define_hooks() {
		add_action( 'cmb2_init', array( $this, 'load_integrations' ) );
	}

	/**
	 *
	 */
	public function load_integrations() {

		require_once dirname( __FILE__ ) . '/class-membership-plugin-integration.php';

		if ( class_exists( 'RCP_Levels' ) ) {
			require_once dirname( __FILE__ ) . '/restrict-content-pro/class-restrict-content-pro-integration.php';
			new MemberScore_Restrict_Content_Pro_Integration();
		}

		if ( class_exists( 'WPComplete' ) ) {
			require_once dirname( __FILE__ ) . '/wp-complete/class-wp-complete-integration.php';
			new MemberScore_Wp_Complete_Integration();
		}

		if ( class_exists( 'WooCommerce' ) && is_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) ) {
			require_once dirname( __FILE__ ) . '/woocommerce-memberships/class-woocommerce-memberships-integration.php';
			new MemberScore_WooCommerce_Memberships_Integration();
		}

		if ( class_exists( 'bbPress' ) ) {
			require_once dirname( __FILE__ ) . '/bbpress/class-bbpress-integration.php';
			new MemberScore_BBPress_Integration();
		}

		if ( class_exists( 'MeprUser' ) ) {
			require_once dirname ( __FILE__ ) . '/memberpress/class-memberpress-integration.php';
			new MemberScore_MemberPress_Integration();
		}

	}

}
