<?php

/**
 *
 *
 * @since      1.2
 * @package    Member_Chi
 * @subpackage Member_Chi/includes/integrations
 * @author     Member Up <travis@memberup.co>
 */
class Member_Chi_Restrict_Content_Pro_Integration {

	private $team_hash;
	private $url;
	private $statuses;

	/**
	 * Member_Chi_Restrict_Content_Pro_Integration constructor.
	 */
	public function __construct() {
		$this->define_hooks();
		$this->team_hash = 'olejRejN';
		$this->url = 'https://chi.dev/integration/restrictcontentpro' . $this->team_hash;
		$this->statuses = array(
			'active',
			'expired',
			'cancelled',
			'pending',
			'free'
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
			'team_id' => $this->team_hash
		);

		switch ( $new_status ) {
			case 'cancelled':
				$body['event_type'] = 'membership_cancelled';
			break;

			case 'active':
				$body['event_type'] = 'membership_active';
			break;
			case 'free':
				$body['event_type'] = 'membership_free';
				break;
			default:
				$body['event_type'] = $new_status;
		}

		$this->url = 'https://chi.dev/api/integration/restrictcontentpro/' . $this->team_hash;

		$response = $this->post( $this->url, $body );

		error_log( print_r( $response, true ) );

		error_log( $this->url );

	}

	private function post( $url, $body, $args = array() ) {

		$body = json_encode( $body );

		$defaults = array(
			'body' => $body,
			'timeout' => '5',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array(),
			'cookies' => array(),
			'sslverify' => false,
		);

		$args = wp_parse_args( $args, $defaults );

		$response = wp_remote_post( $url, $args );

		return $response;
	}
}
