<?php

/**
 * @since      1.3
 * @package    Member_Chi
 * @subpackage Member_Chi/includes/integrations
 * @author     Member Up <travis@memberup.co>
 */
abstract class Member_Chi_Membership_Plugin_Integration {

	public function post( $url, $body, $args = array() ) {

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
