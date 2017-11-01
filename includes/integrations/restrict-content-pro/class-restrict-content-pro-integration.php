<?php

/**
 * @since      1.2
 * @package    MemberScore
 * @subpackage MemberScore/includes/integrations
 * @author     Member Up <travis@memberup.co>
 */
class MemberScore_Restrict_Content_Pro_Integration extends MemberScore_Membership_Plugin_Integration {

	private $url;
	private $statuses;

	/**
	 * MemberScore_Restrict_Content_Pro_Integration constructor.
	 */
	public function __construct() {
		parent::construct();

		$this->define_hooks();
		$this->url = $this->app_url . '/integration/restrictcontentpro/' . $this->team_hash;
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
		add_action( 'rcp_set_status', array( $this, 'status_change' ), 10, 4 );
	}


	/**
	 * @param string $new_status
	 * @param int $rcp_member_id
	 * @param string $old_status
	 * @param Rcp_Member $rcp_member
	 */
	public function status_change( $new_status, $rcp_member_id, $old_status, $rcp_member ) {
		$body = array(
			'email' => $rcp_member->user_email,
			'wp_id' => $rcp_member->ID,
			'team_id' => $this->team_hash,
			'old_status' => $old_status,
		);

		$body['event_type'] = 'restrictcontentpro.membership.' . $new_status;

		// If this is a new subscription, set the join date.
		if ( '' === $old_status ) {
			$body['date_join'] = time();
		} elseif ( 'expired' === $new_status ) {
			$body['date_expiration'] = time();
		}

		$response = $this->post( $this->url, $body );

		error_log( print_r( $response, true ) );

		error_log( $this->url );

	}

}
