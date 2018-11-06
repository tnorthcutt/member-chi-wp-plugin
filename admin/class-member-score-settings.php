<?php

/**
 * Class MemberScore_Settings
 *
 * @package    MemberScore
 * @subpackage MemberScore/settings
 * @author     Member Up <travis@memberup.co>
 */
class MemberScore_Settings {

	/**
	 * Option key, and option page slug
	 * @var string
	 */
	private $key = 'member_score_options';

	/**
	 * Options page metabox id
	 * @var string
	 */
	private $metabox_id = 'member_score_option_metabox';

	/**
	 * Options Page title
	 * @var string
	 */
	protected $title = '';

	/**
	 * Options Page hook
	 * @var string
	 */
	protected $options_page = '';

	/**
	 * Holds an instance of the object
	 *
	 * @var MemberScore_Settings
	 **/
	private static $instance = null;

	/**
	 * Constructor
	 * @since 0.1.0
	 */
	private function __construct() {
		// Set our title
		$this->title = __( 'MemberScore', 'member-score' );
	}

	/**
	 * Returns the running object
	 *
	 * @return MemberScore_Settings
	 **/
	public static function get_instance() {
		if( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->hooks();
		}
		return self::$instance;
	}

	/**
	 * Initiate our hooks
	 * @since 0.1.0
	 */
	public function hooks() {
		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'cmb2_admin_init', array( $this, 'add_options_page_metabox' ) );
	}


	/**
	 * Register our setting to WP
	 * @since  0.1.0
	 */
	public function init() {
		register_setting( $this->key, $this->key );
	}

	/**
	 * Add menu options page
	 * @since 0.1.0
	 */
	public function add_options_page() {
		$this->options_page = add_options_page( $this->title, $this->title, 'manage_options', $this->key, array( $this, 'admin_page_display' ) );

		// Include CMB CSS in the head to avoid FOUC
		add_action( "admin_print_styles-{$this->options_page}", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );
	}

	/**
	 * Admin page markup. Mostly handled by CMB2
	 * @since  0.1.0
	 */
	public function admin_page_display() {
		?>
		<div class="wrap cmb2-options-page <?php echo $this->key; ?>">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<div class="cmb-row cmb-type-text cmb2-id--member-score-api-key table-layout" data-fieldtype="text">
				<div class="cmb-th">
					<label for="_member_score_api_key">Export WP Users</label>
				</div>
				<div class="cmb-td">
					<a href="<?php echo wp_nonce_url( menu_page_url( $this->key, false ), 'csv_export', 'member_score_wp_users_upload' ); ?>" class="button-secondary">Send to MemberScore</a>
					<a href="<?php echo wp_nonce_url( menu_page_url( $this->key, false ), 'csv_export', 'member_score_wp_users_export' ); ?>" class="button-secondary">Export to CSV</a>
				</div>
			</div>
			<?php cmb2_metabox_form( $this->metabox_id, $this->key ); ?>
		</div>
		<?php
	}

	/**
	 * Add the options metabox to the array of metaboxes
	 * @since  0.1.0
	 */
	function add_options_page_metabox() {

		$prefix = '_member_score_';

		// hook in our save notices
		add_action( "cmb2_save_options-page_fields_{$this->metabox_id}", array( $this, 'settings_notices' ), 10, 2 );

		$cmb = new_cmb2_box( array(
			'id'         => $this->metabox_id,
			'hookup'     => false,
			'cmb_styles' => false,
			'show_on'    => array(
				// These are important, don't remove
				'key'   => 'options-page',
				'value' => array( $this->key, )
			),
		) );

		$cmb->add_field( array(
			'name' => __( 'API Key', 'member-score' ),
			'id' => $prefix . 'api_key',
			'type' => 'text',
		) );

		$cmb->add_field( array(
			'name' => __( 'Dev API Key', 'member-score' ),
			'id' => $prefix . 'dev_api_key',
			'type' => 'text',
		) );

		$cmb->add_field( array(
			'name' => __( 'Team ID', 'member-score' ),
			'id' => $prefix . 'team_id',
			'type' => 'text',
		) );

		$cmb->add_field( array(
			'name' => __( 'Debug mode', 'member-score' ),
			'id' => $prefix . 'debug',
			'type' => 'checkbox',
		) );

	}

	/**
	 * Register settings notices for display
	 *
	 * @since  0.1.0
	 * @param  int   $object_id Option key
	 * @param  array $updated   Array of updated fields
	 * @return void
	 */
	public function settings_notices( $object_id, $updated ) {
		if ( $object_id !== $this->key || empty( $updated ) ) {
			return;
		}

		add_settings_error( $this->key . '-notices', '', __( 'Settings updated.', 'member-score' ), 'updated' );
		settings_errors( $this->key . '-notices' );
	}

	/**
	 * Public getter method for retrieving protected/private variables
	 * @since  0.1.0
	 * @param  string  $field Field to retrieve
	 * @return mixed          Field value or exception is thrown
	 *
	 * @throws
	 */
	public function __get( $field ) {
		// Allowed fields to retrieve
		if ( in_array( $field, array( 'key', 'metabox_id', 'title', 'options_page' ), true ) ) {
			return $this->{$field};
		}

		throw new Exception( 'Invalid property: ' . $field );
	}

}

/**
 * Helper function to get/return the MemberScore_Settings object
 * @since  0.1.0
 * @return MemberScore_Settings object
 */
function member_score_settings() {
	return MemberScore_Settings::get_instance();
}

/**
 * Wrapper function around cmb2_get_option
 * @since  0.1.0
 * @param  string  $key Options array key
 * @return mixed        Option value
 */
function member_score_get_option( $key = '' ) {
	return cmb2_get_option( member_score_settings()->key, $key );
}

function member_score_get_app_url() {
    return ( member_score_get_option( '_member_score_debug' ) ? 'https://chi.test' : 'https://app.memberscore.io' );
}

// Get it started
member_score_settings();
