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
		$this->url = $this->app_url . '/integration/memberpress/' . $this->team_hash . '?api_token=' . $this->api_key;
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
		add_action( 'mepr-event-subscription-stopped', array( $this, 'subscription_stopped' ), 10, 1 );
		add_action( 'mepr-event-subscription-expired', array( $this, 'subscription_expired' ), 10, 1 );
	}

	/**
	 * @param $event MeprEvent
	 */
	public function subscription_stopped( $event ) {
		$subscription = $event->get_data();
		$user_id      = $subscription->user_id;
		$user         = get_user_by( 'id', $user_id );

		$body = array(
			'email'   => $user->user_email,
			'wp_id'   => $user->ID,
			'team_id' => $this->team_hash,
		);

		$body['event_type'] = 'memberpress.membership.cancelled';

		$response = $this->post( $this->url, $body );
	}

	/**
	 * @param $event MeprEvent
	 */
	public function subscription_expired( $event ) {
		$subscription = $event->get_data();
		$user_id      = $subscription->user_id;
		$user         = get_user_by( 'id', $user_id );

		$body = array(
			'email'           => $user->user_email,
			'wp_id'           => $user->id,
			'team_id'         => $this->team_hash,
			'date_expiration' => time(),
		);

		$body['event_type'] = 'memberpress.membership.expired';

		$response = $this->post( $this->url, $body );
	}

}
