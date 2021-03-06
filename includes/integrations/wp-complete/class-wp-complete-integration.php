<?php

/**
 * @since      1.3
 * @package    MemberScore
 * @subpackage MemberScore/includes/integrations
 * @author     Member Up <travis@memberup.co>
 */
class MemberScore_Wp_Complete_Integration extends MemberScore_Membership_Plugin_Integration {

	private $url;
	private $statuses;

	/**
	 * MemberScore_Wp_Complete_Integration constructor.
	 */
	public function __construct() {
		parent::construct();

		$this->define_hooks();
		$this->url = $this->app_url . '/integration/wpcomplete/' . $this->team_hash . '?api_token=' . $this->api_key;
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
		add_action( 'wpcomplete_mark_completed', array( $this, 'mark_completed' ), 10, 1 );

		/**
		 * We could also log an event for marking incomplete, but we're going to pass on this for now
		 * In the future, if there's really a need for this, we can add it.
		 *
		 * add_action( 'wpcomplete_mark_incomplete', array( $this, 'mark_incomplete' ), 10, 1 );
		 */
	}


	/**
	 * @param array $args An array from WP Complete, consisting of:
	 *      user_id
	 *      post_id
	 *      button_id
	 *      post_status
	 *
	 */
	public function mark_completed( $args ) {

		$user = get_userdata( $args['user_id'] );

		$body = array(
			'email' => $user->user_email,
			'wp_id' => $args['user_id'],
			'team_id' => $this->team_hash,
			'event_type' => 'wpcomplete.post.complete',
			'post_id' => $args['post_id'],
			'button_id' => $args['button_id'],
		);

		$response = $this->post( $this->url, $body );

	}

	/**
	 * @param array $args An array from WP Complete, consisting of:
	 *      user_id
	 *      post_id
	 *      button_id
	 *      post_status
	 *
	 */
	public function mark_incomplete( $args ) {

		$user = get_userdata( $args['user_id'] );

		$body = array(
			'email' => $user->user_email,
			'wp_id' => $args['user_id'],
			'team_id' => $this->team_hash,
			'event_type' => 'wpcomplete.post.incomplete',
			'post_id' => $args['post_id'],
			'button_id' => $args['button_id'],
		);

		$response = $this->post( $this->url, $body );

	}

}
