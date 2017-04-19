<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Member_Chi
 * @subpackage Member_Chi/admin
 * @author     Member Up <travis@memberup.co>
 */
class Member_Chi_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Member_Chi_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Member_Chi_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/member-chi-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Member_Chi_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Member_Chi_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/member-chi-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function user_upload() {
		if ( isset( $_GET['memberchi_wp_users_upload'] ) ) {
			if ( ! wp_verify_nonce( $_GET['memberchi_wp_users_upload'], 'csv_export' ) ) {
				wp_die( 'Something went wrong, please try again.' );
			}
			$users = get_users(
				array(
					'fields' => array(
						'ID',
						'user_email',
					),
				)
			);

			// Split the user list into batches of 100
			$batches = array_chunk( $users, 100 );

			foreach ( $batches as $batch_key => $batch ) {
				// Schedule batches in 1 hour intervals
				wp_schedule_single_event( time() + ( 3600 * ( $batch_key + 1 ) ), 'member_chi_user_upload_add_batch', $batch );
			}
		} // End if().
	}

	public function member_chi_user_upload( $batch ) {
			// @TODO Make API call to upload users
	}

	public function csv_export() {
		if ( isset( $_GET['memberchi_wp_users_export'] ) ) {
			if ( ! wp_verify_nonce( $_GET['memberchi_wp_users_export'], 'csv_export' ) ) {
				wp_die( 'Something went wrong, please try again.' );
			}
			$users = get_users(
				array(
					'fields' => array(
						'ID',
						'user_email',
					),
				)
			);

			// Set header row values
			$csv_fields = array();
			$csv_fields[] = 'ID';
			$csv_fields[] = 'User Email';
			$output_filename = 'WordPress-Users.csv';
			$output_handle = @fopen( 'php://output', 'w' );

			header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
			header( 'Content-Description: File Transfer' );
			header( 'Content-type: text/csv' );
			header( 'Content-Disposition: attachment; filename=' . $output_filename );
			header( 'Expires: 0' );
			header( 'Pragma: public' );

			// Insert header row
			fputcsv( $output_handle, $csv_fields );

			// Parse results to csv format
			foreach ( $users as $user ) {
				$lead_array = (array) $user; // Cast the Object to an array
				// Add row to file
				fputcsv( $output_handle, $lead_array );
			}

			// Close output file stream
			fclose( $output_handle );
			die();

			wp_safe_redirect( menu_page_url( 'member_chi_options', false ) );
			exit;
		} // End if().
	}
}
