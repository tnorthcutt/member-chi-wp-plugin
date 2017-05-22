<?php

/**
 * @since      1.3
 * @package    Member_Chi
 * @subpackage Member_Chi/includes/integrations
 * @author     Member Up <travis@memberup.co>
 */
abstract class Member_Chi_Membership_Plugin_Integration {

	protected $team_hash;
	protected $app_url;

	public function construct() {
		$this->team_hash = member_chi_get_option( '_member_chi_team_id' );
		$this->app_url = member_chi_get_app_url();
	}

	public function post( $url, $body, $args = array() ) {

		$body = json_encode( $body );

		$defaults = array(
			'body' => $body,
			'timeout' => '5',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array(
				'Content-Type' => 'application/json'
			),
			'cookies' => array(),
			'sslverify' => false,
		);

		$args = wp_parse_args( $args, $defaults );

		$response = wp_remote_post( $url, $args );

		return $response;
	}

}
