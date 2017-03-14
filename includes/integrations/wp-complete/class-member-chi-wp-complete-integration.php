<?php

/**
 * @since      1.3
 * @package    Member_Chi
 * @subpackage Member_Chi/includes/integrations
 * @author     Member Up <travis@memberup.co>
 */
class Member_Chi_Wp_Complete_Integration extends Member_Chi_Membership_Plugin_Integration {

	private $team_hash;
	private $url;
	private $statuses;

	/**
	 * Member_Chi_Wp_Complete_Integration constructor.
	 */
	public function __construct() {
		$this->define_hooks();
		$this->team_hash = 'olejRejN';
		$this->url = 'https://chi.dev/integration/wpcomplete' . $this->team_hash;
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
		do_action( 'wpcomplete_mark_completed', array('user_id' => get_current_user_id(), 'post_id' => $post_id, 'button_id' => $unique_button_id, 'post_status' => $post_status ) );
		add_action( 'wpcomplete_mark_completed', array( $this, 'mark_completed' ), 10, 1 );
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
			'wp_id' => $$args['user_id'],
			'team_id' => $this->team_hash,
			'event_type' => 'post.complete',
		);

		$response = $this->post( $this->url, $body );

		error_log( print_r( $response, true ) );

		error_log( $this->url );
	}


}
