<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://chrisnov.com
 * @since      1.0.0
 *
 * @package    FeatherShare
 * @subpackage FeatherShare/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    FeatherShare
 * @subpackage FeatherShare/includes
 * @author     Reynov Christian <contact@chrisnov.com>
 */
class FeatherShare {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      FeatherShare_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
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
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'FEATHERSHARE_VERSION' ) ) {
			$this->version = FEATHERSHARE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'feathershare';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - FeatherShare_Loader. Orchestrates the hooks of the plugin.
	 * - FeatherShare_i18n. Defines internationalization functionality.
	 * - FeatherShare_Admin. Defines all hooks for the admin area.
	 * - FeatherShare_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once FEATHERSHARE_DIR . 'includes/class-feathershare-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once FEATHERSHARE_DIR . 'includes/class-feathershare-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once FEATHERSHARE_DIR . 'admin/class-feathershare-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once FEATHERSHARE_DIR . 'public/class-feathershare-public.php';

		/**
		 * The class responsible for defining social sharing functionality.
		 */
		require_once FEATHERSHARE_DIR . 'includes/class-social-sharing.php';

		/**
		 * The class responsible for defining subscription functionality.
		 */
		require_once FEATHERSHARE_DIR . 'includes/class-subscription-handler.php';

		$this->loader = new FeatherShare_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the FeatherShare_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new FeatherShare_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new FeatherShare_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'initialize_settings' );
		$this->loader->add_filter( 'plugin_action_links_' . plugin_basename( FEATHERSHARE_FILE ), $plugin_admin, 'add_action_links' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new FeatherShare_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// Initialize social sharing functionality
		$social_sharing = new Social_Sharing( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_filter( 'the_content', $social_sharing, 'add_sharing_buttons' );
		$this->loader->add_action( 'wp_enqueue_scripts', $social_sharing, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $social_sharing, 'enqueue_scripts' );

		// Initialize subscription functionality
		$subscription_handler = new Subscription_Handler( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $subscription_handler, 'enqueue_scripts' );
		$this->loader->add_filter( 'the_content', $subscription_handler, 'maybe_display_form_after_content' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    FeatherShare_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}