<?php

/**
 * @since      1.2
 * @package    Member_Chi
 * @subpackage Member_Chi/includes/integrations
 * @author     Member Up <travis@memberup.co>
 */
class Member_Chi_WooCommerce_Memberships_Integration extends Member_Chi_Membership_Plugin_Integration {

	private $team_hash;
	private $url;
	private $statuses;

	/**
	 * Member_Chi_WooCommerce_Memberships_Integration constructor.
	 */
	public function __construct() {
		$this->define_hooks();
		$this->team_hash = 'olejRejN';
		$this->url = 'https://chi.dev/integration/woocommerce-memberships/   ' . $this->team_hash;
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
			'event_type' => 'membership_access_granted',
		);

		$this->url = 'https://chi.dev/api/integration/woocommerce-memberships/' . $this->team_hash;

		$response = $this->post( $this->url, $body );

		error_log( print_r( $response, true ) );

		error_log( $this->url );

	}

	/**
	 * @param WC_Memberships_Membership_Plan $membership_plan
	 * @param array $args
	 */
	public function membership_cancelled( $user_membership_id ) {

		$user_membership = get_postdata( $user_membership_id );
		$user = get_userdata( $user_membership->post_author );

		$body = array(
			'email' => $user->user_email,
			'wp_id' => $user_membership->post_author,
			'user_membership_id' => $user_membership_id,
			'event_type' => 'membership_cancelled',
		);

		$this->url = 'https://chi.dev/api/integration/woocommerce-memberships/' . $this->team_hash;

		$response = $this->post( $this->url, $body );

		error_log( print_r( $response, true ) );

		error_log( $this->url );

	}

	/**
	 * @param WC_Memberships_Membership_Plan $membership_plan
	 * @param array $args
	 */
	public function membership_status_changed( $user_membership, $old_status, $new_status ) {

		$user = get_userdata( $user_membership->user_id );

		$body = array(
			'email' => $user->user_email,
			'wp_id' => $user_membership->user_id,
			'user_membership_id' => $user_membership->id,
			'event_type' => $new_satus,
		);

		$this->url = 'https://chi.dev/api/integration/woocommerce-memberships/' . $this->team_hash;

		$response = $this->post( $this->url, $body );

		error_log( print_r( $response, true ) );

		error_log( $this->url );

	}

}
