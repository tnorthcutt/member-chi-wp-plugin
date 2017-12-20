<?php

/**
 * @since      1.2
 * @package    MemberScore
 * @subpackage MemberScore/includes/integrations
 * @author     Member Up <travis@memberup.co>
 */
class MemberScore_MemberMouse_Integration extends MemberScore_Membership_Plugin_Integration {

	private $url;
	private $statuses;

	/**
	 * MemberScore_MemberMouse_Integration constructor.
	 */
	public function __construct() {
		parent::construct();

		$this->define_hooks();
		$this->url = $this->app_url . '/integration/membermouse/' . $this->team_hash . '?api_token=' . $this->api_key;
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
		add_action( 'mm_member_status_change', array( $this, 'status_change' ), 10, 1 );
	}

    public function status_change( $member_data ) {
	    error_log(print_r($member_data, true));

	    $new_status = $member_data['status'];
        $old_status = $member_data['last_status_name'];

        switch($new_status)
        {
            case 1:
                $new_status = 'active';
                break;
            case 2:
                $new_status = 'cancelled';
                break;
            case 8:
                $new_status = 'expired';
                break;
            default:
                return;
        }

        $body = array(
            'email'      => $member_data['email'],
            'wp_id'      => $member_data['member_id'],
            'team_id'    => $this->team_hash,
            'old_status' => $old_status,
        );

        $body['event_type'] = 'membermouse.membership.' . $new_status;

        // If this is a new subscription, set the join date.
        if ( '' === $old_status ) {
            $body['date_join'] = time();
        } elseif ( 'expired' === $new_status ) {
            $body['date_expiration'] = time();
        }

        $response = $this->post( $this->url, $body );

        error_log( print_r( 'Response code: ' . $response['response']['code'], true ) );

        error_log( print_r( 'Body:' ) );
        error_log( print_r( $body, true ) );
	}
}
