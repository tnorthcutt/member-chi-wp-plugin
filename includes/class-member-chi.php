<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      0.1.0
 * @package    Member_Chi
 * @subpackage Member_Chi/includes
 * @author     Member Up <travis@memberup.co>
 */
class Member_Chi {

	/**
	 * @var
	 */
	private static $instance;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    0.1.0
	 */
	public function __construct() {

		$this->plugin_name = 'member-chi';
		$this->version = '1.0';
	}

	/**
	 * Main Member_Chi Instance.
	 *
	 * Insures that only one instance of Member_Chi exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since 1.2
	 * @static
	 * @staticvar array $instance
	 * @uses Member_Chi::includes() Include the required files.
	 * @see Member_Chi()
	 * @return object|Member_Chi The one true Member_Chi
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Easy_Digital_Downloads ) ) {
			self::$instance = new Member_Chi;

			self::$instance->includes();
			self::$instance->set_locale();
			self::$instance->define_admin_hooks();
			self::$instance->define_public_hooks();

			self::$instance->integrations = new Member_Chi_Membership_Plugin_Integrations();

		}
		return self::$instance;
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Member_Chi_i18n. Defines internationalization functionality.
	 * - Member_Chi_Admin. Defines all hooks for the admin area.
	 * - Member_Chi_Public. Defines all hooks for the public side of the site.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function includes() {

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-member-chi-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-member-chi-admin.php';

		/**
		 * The class responsible for the plugin's settings
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-member-chi-settings.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-member-chi-public.php';

		/**
		 * The class responsible for handling membership plugin integrations
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/integrations/class-member-chi-membership-plugin-integrations.php';

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Member_Chi_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Member_Chi_i18n();

		add_action( 'plugins_loaded', array($plugin_i18n, 'load_plugin_textdomain') );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Member_Chi_Admin( $this->get_plugin_name(), $this->get_version() );

		add_action( 'admin_enqueue_scripts', array($plugin_admin, 'enqueue_styles') );
		add_action( 'admin_enqueue_scripts', array($plugin_admin, 'enqueue_scripts') );

		$this->loader->add_action( 'admin_init', $plugin_admin, 'user_upload' );
		$this->loader->add_action( 'member_chi_user_upload_add_batch', $plugin_admin, 'member_chi_user_upload', 10, 3 );
		add_action( 'admin_init', array($plugin_admin, 'csv_export') );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Member_Chi_Public( $this->get_plugin_name(), $this->get_version() );

		add_action( 'wp_enqueue_scripts', array($plugin_public, 'enqueue_styles') );
		add_action( 'wp_enqueue_scripts', array($plugin_public, 'enqueue_scripts') );

	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     0.1.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     0.1.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
