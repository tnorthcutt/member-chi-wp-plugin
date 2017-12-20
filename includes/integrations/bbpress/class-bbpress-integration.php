<?php

/**
 * @since      1.2
 * @package    MemberScore
 * @subpackage MemberScore/includes/integrations
 * @author     Member Up <travis@memberup.co>
 */
class MemberScore_BBPress_Integration extends MemberScore_Membership_Plugin_Integration {

	private $url;
	private $statuses;

	/**
	 * MemberScore_bbPress_Integration constructor.
	 */
	public function __construct() {
		parent::construct();

		$this->define_hooks();
		$this->url = $this->app_url . '/integration/bbpress/' . $this->team_hash . '?api_token=' . $this->api_key;
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
		add_action( 'bbp_new_topic', array( $this, 'new_topic' ), 10, 4 );
		add_action( 'bbp_new_reply', array( $this, 'new_reply' ), 10, 7 );
	}

	/**
	 * @param int $topic_id
	 * @param int $forum_id
	 * @param $anonymous_data
	 * @param int $topic_author
	 */
	public function new_topic( $topic_id, $forum_id, $anonymous_data, $topic_author ) {

		$user = get_userdata( $topic_author );

		$body = array(
			'email' => $user->user_email,
			'wp_id' => $topic_author,
			'event_type' => 'bbpress.topic.created',
		);

		$response = $this->post( $this->url, $body );

		error_log( print_r( $response, true ) );

		error_log( $this->url );

	}

	/**
	 * @param int $reply_id
	 * @param int $topic_id
	 * @param int $forum_id
	 * @param $anonymous_data
	 * @param int $reply_author
	 * @param bool $false
	 * @param int $reply_to
	 */
	public function new_reply( $reply_id, $topic_id, $forum_id, $anonymous_data, $reply_author, $false, $reply_to ) {

		$user = get_userdata( $reply_author );

		$body = array(
			'email' => $user->user_email,
			'wp_id' => $reply_author,
			'reply_id' => $reply_id,
			'topic' => $topic_id,
			'event_type' => 'bbpress.topic.replied',
		);

		$response = $this->post( $this->url, $body );

		error_log( print_r( $response, true ) );

		error_log( $this->url );

	}
}
