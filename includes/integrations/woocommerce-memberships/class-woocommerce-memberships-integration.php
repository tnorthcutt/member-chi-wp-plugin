<?php

/**
 * @since      1.2
 * @package    MemberScore
 * @subpackage MemberScore/includes/integrations
 * @author     Member Up <travis@memberup.co>
 */
class MemberScore_WooCommerce_Memberships_Integration extends MemberScore_Membership_Plugin_Integration {

	private $url;
	private $statuses;

	/**
	 * MemberScore_WooCommerce_Memberships_Integration constructor.
	 */
	public function __construct() {
		parent::construct();

		$this->define_hooks();
		$this->url = $this->app_url . '/integration/woocommerce-memberships/' . $this->team_hash;
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
		// Fires after a user has been granted membership access, either from purchase, from programmatically creating memberships, or from admin action.
		add_action( 'wc_memberships_user_membership_saved', array( $this, 'membership_saved' ), 10, 2 );

		// Fires right after a user membership has been cancelled by the customer.
		add_action( 'wc_memberships_cancelled_user_membership', array( $this, 'membership_cancelled' ) );

		// Fires when user membership status is updated.
		add_action( 'wc_memberships_user_membership_status_changed', array( $this, 'membership_status_changed' ), 10, 3 );
	}

	/**
	 * @param WC_Memberships_Membership_Plan $membership_plan
	 * @param array $args
	 */
	public function membership_saved( $membership_plan, $args ) {

		$user = get_userdata( $args['user_id'] );

		$body = array(
			'email' => $user->user_email,
			'wp_id' => $args['user_id'],
			'membership_plan' => $membership_plan->id,
			'user_membership_id' => $args['user_membership_id'],
		);

		if ( true === $args['is_update'] ) {
			$body['event_type'] = 'woocommerce.membership.updated';
		} else {
			$body['event_type'] = 'woocommerce.membership.new';
			$body['date_join'] = time();
		}

		$response = $this->post( $this->url, $body );

		error_log( print_r( $response, true ) );

		error_log( $this->url );

	}

	/**
	 * @param int $user_membership_id
	 */
	public function membership_cancelled( $user_membership_id ) {

		$user_membership = get_post( $user_membership_id );
		$user = get_userdata( $user_membership->post_author );

		$body = array(
			'email' => $user->user_email,
			'wp_id' => $user_membership->post_author,
			'user_membership_id' => $user_membership_id,
			'event_type' => 'woocommerce.membership.cancelled',
		);

		$response = $this->post( $this->url, $body );

		error_log( print_r( $response, true ) );

		error_log( $this->url );

	}

	/**
	 * @param WC_Memberships_User_Membership $user_membership
	 * @param string $old_status Old status, without the wcm- prefix
	 * @param string $new_status New status, without the wcm- prefix
	 */
	public function membership_status_changed( $user_membership, $old_status, $new_status ) {

		$user = get_userdata( $user_membership->user_id );

		$body = array(
			'email' => $user->user_email,
			'wp_id' => $user_membership->user_id,
			'user_membership_id' => $user_membership->id,
			'event_type' => $new_status,
			'old_status' => $old_status,
		);

		if ( 'expired' === $new_status ) {
			$body['date_expired'] = time();
		}

		$response = $this->post( $this->url, $body );

		error_log( print_r( $response, true ) );

		error_log( $this->url );

	}

}
