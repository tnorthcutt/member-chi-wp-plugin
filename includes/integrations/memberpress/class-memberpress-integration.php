<?php

/**
 * @since      1.2
 * @package    MemberScore
 * @subpackage MemberScore/includes/integrations
 * @author     Member Up <travis@memberup.co>
 */
class MemberScore_MemberPress_Integration extends MemberScore_Membership_Plugin_Integration {

	private $url;
	private $statuses;

	/**
	 * MemberScore_MemberPress_Integration constructor.
	 */
	public function __construct() {
		parent::construct();

		$this->define_hooks();
		$this->url = $this->app_url . '/integration/memberpress/' . $this->team_hash;
		$this->statuses = array(
			'active',
			'expired',
			'cancelled',
			'pending',
			'free',
		);
	}

	/**
	 *
	 */
	private function define_hooks() {
	}

}
