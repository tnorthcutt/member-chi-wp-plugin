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
		if ( class_exists( 'RCP_Levels' ) ) {
			require_once dirname(__FILE__) . '/restrict-content-pro/class-member-chi-restrict-content-pro-integration.php';
			new Member_Chi_Restrict_Content_Pro_Integration();
		}
	}

}
