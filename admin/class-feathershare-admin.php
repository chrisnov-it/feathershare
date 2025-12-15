<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://chrisnov.com
 * @since      1.0.0
 *
 * @package    FeatherShare
 * @subpackage FeatherShare/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    FeatherShare
 * @subpackage FeatherShare/admin
 * @author     Reynov Christian <contact@chrisnov.com>
 */
class FeatherShare_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;

		// Hook for CSV export
		add_action( 'admin_post_feathershare_export_subscribers', array( $this, 'handle_export_subscribers_csv' ) );
	}

	/**
	 * Register the admin menu for the plugin.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {
		add_options_page(
			'FeatherShare Settings',
			'FeatherShare',
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_plugin_admin_page' )
		);
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		include_once 'partials/' . $this->plugin_name . '-admin-display.php';
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {
		$settings_link = array(
			'<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">Settings</a>',
		);
		return array_merge( $settings_link, $links );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, FEATHERSHARE_URL . 'admin/css/feathershare-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, FEATHERSHARE_URL . 'admin/js/feathershare-admin.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Initialize the settings for the plugin.
	 *
	 * @since    1.0.0
	 */
	public function initialize_settings() {
		// Add the main settings section
		add_settings_section(
			'feathershare_general_section',
			'Subscriber Management',
			array( $this, 'render_general_section_text' ),
			$this->plugin_name
		);

		// Add the social networks section
		add_settings_section(
			'feathershare_networks_section',
			'Manage Social Networks',
			array( $this, 'render_networks_section_text' ),
			$this->plugin_name
		);

		// Add Button Appearance section
		add_settings_section(
			'feathershare_appearance_section',
			'Button Appearance',
			array( $this, 'render_appearance_section_text' ),
			$this->plugin_name
		);

		// Add Subscription Form section
		add_settings_section(
			'feathershare_subscription_section',
			'Subscription Form Settings',
			array( $this, 'render_subscription_section_text' ),
			$this->plugin_name
		);

		add_settings_field(
			'feathershare_social_networks',
			'Enabled Share Buttons',
			array( $this, 'render_social_networks_field' ),
			$this->plugin_name,
			'feathershare_networks_section'
		);

		add_settings_field(
			'feathershare_enable_messenger',
			'Enable Messenger Share Button',
			array( $this, 'render_enable_messenger_field' ),
			$this->plugin_name,
			'feathershare_networks_section'
		);

		add_settings_field(
			'feathershare_facebook_app_id',
			'Facebook App ID',
			array( $this, 'render_facebook_app_id_field' ),
			$this->plugin_name,
			'feathershare_networks_section'
		);

		// Button Appearance Fields
		add_settings_field(
			'feathershare_button_style',
			'Button Shape',
			array( $this, 'render_button_style_field' ),
			$this->plugin_name,
			'feathershare_appearance_section'
		);

		add_settings_field(
			'feathershare_button_size',
			'Button Size',
			array( $this, 'render_button_size_field' ),
			$this->plugin_name,
			'feathershare_appearance_section'
		);

		add_settings_field(
			'feathershare_show_labels',
			'Show Labels',
			array( $this, 'render_show_labels_field' ),
			$this->plugin_name,
			'feathershare_appearance_section'
		);

		add_settings_field(
			'feathershare_enable_copy_link',
			'Enable Copy Link Button',
			array( $this, 'render_enable_copy_link_field' ),
			$this->plugin_name,
			'feathershare_appearance_section'
		);

		// Subscription Fields
		add_settings_field(
			'feathershare_subscription_title',
			'Form Title',
			array( $this, 'render_subscription_title_field' ),
			$this->plugin_name,
			'feathershare_subscription_section'
		);

		add_settings_field(
			'feathershare_subscription_description',
			'Form Description',
			array( $this, 'render_subscription_description_field' ),
			$this->plugin_name,
			'feathershare_subscription_section'
		);

		add_settings_field(
			'feathershare_subscription_button_text',
			'Button Text',
			array( $this, 'render_subscription_button_text_field' ),
			$this->plugin_name,
			'feathershare_subscription_section'
		);

		add_settings_field(
			'feathershare_subscription_placement',
			'Form Placement',
			array( $this, 'render_subscription_placement_field' ),
			$this->plugin_name,
			'feathershare_subscription_section'
		);

		// Register the settings
		register_setting( $this->plugin_name, 'feathershare_social_networks', array( $this, 'sanitize_social_networks' ) );
		register_setting( $this->plugin_name, 'feathershare_enable_messenger', array( 'type' => 'boolean', 'sanitize_callback' => 'rest_sanitize_boolean' ) );
		register_setting( $this->plugin_name, 'feathershare_facebook_app_id', array( 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field' ) );
		register_setting( $this->plugin_name, 'feathershare_button_style', array( 'type' => 'string', 'sanitize_callback' => 'sanitize_key', 'default' => 'circle' ) );
		register_setting( $this->plugin_name, 'feathershare_button_size', array( 'type' => 'string', 'sanitize_callback' => 'sanitize_key', 'default' => 'medium' ) );
		register_setting( $this->plugin_name, 'feathershare_show_labels', array( 'type' => 'boolean', 'sanitize_callback' => 'rest_sanitize_boolean', 'default' => false ) );
		register_setting( $this->plugin_name, 'feathershare_enable_copy_link', array( 'type' => 'boolean', 'sanitize_callback' => 'rest_sanitize_boolean', 'default' => true ) );
		register_setting( $this->plugin_name, 'feathershare_subscription_title', array( 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field' ) );
		register_setting( $this->plugin_name, 'feathershare_subscription_description', array( 'type' => 'string', 'sanitize_callback' => 'wp_kses_post' ) );
		register_setting( $this->plugin_name, 'feathershare_subscription_button_text', array( 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field' ) );
		register_setting( $this->plugin_name, 'feathershare_subscription_placement', array( 'type' => 'string', 'sanitize_callback' => 'sanitize_key' ) );
	}

	public function render_general_section_text() {
		$export_url = add_query_arg(
			array(
				'action' => 'feathershare_export_subscribers',
				'_wpnonce' => wp_create_nonce('feathershare_export_nonce')
			), 
			admin_url('admin-post.php')
		);

		echo '<p>Manage your FeatherShare subscribers.</p>';
		echo '<a href="' . esc_url($export_url) . '" class="button button-primary">Export Subscribers to CSV</a>';
	}

	public function render_networks_section_text() {
		echo '<p>Select the social networks you want to enable.</p>';
	}

	public function render_appearance_section_text() {
		echo '<p>Customize the appearance of your share buttons.</p>';
	}

	public function render_button_style_field() {
		$current_style = get_option('feathershare_button_style', 'circle');
		$styles = array(
			'circle'  => 'Circle (Default)',
			'square'  => 'Square',
			'rounded' => 'Rounded Square',
		);

		foreach ($styles as $key => $label) {
			echo '<label style="display: inline-block; margin-right: 15px;">';
			echo '<input type="radio" name="feathershare_button_style" value="' . esc_attr($key) . '" ' . checked($key, $current_style, false) . ' /> ';
			echo esc_html($label);
			echo '</label>';
		}
	}

	public function render_button_size_field() {
		$current_size = get_option('feathershare_button_size', 'medium');
		$sizes = array(
			'small'  => 'Small (32px)',
			'medium' => 'Medium (40px)',
			'large'  => 'Large (48px)',
		);

		foreach ($sizes as $key => $label) {
			echo '<label style="display: inline-block; margin-right: 15px;">';
			echo '<input type="radio" name="feathershare_button_size" value="' . esc_attr($key) . '" ' . checked($key, $current_size, false) . ' /> ';
			echo esc_html($label);
			echo '</label>';
		}
	}

	public function render_show_labels_field() {
		$show_labels = get_option('feathershare_show_labels', 0);
		echo '<input type="checkbox" name="feathershare_show_labels" id="feathershare_show_labels" value="1" ' . checked(1, $show_labels, false) . ' />';
		echo '<label for="feathershare_show_labels"> Show text labels next to icons (e.g., "Facebook", "Twitter")</label>';
	}

	public function render_enable_copy_link_field() {
		$enable_copy_link = get_option('feathershare_enable_copy_link', 1);
		echo '<input type="checkbox" name="feathershare_enable_copy_link" id="feathershare_enable_copy_link" value="1" ' . checked(1, $enable_copy_link, false) . ' />';
		echo '<label for="feathershare_enable_copy_link"> Add a "Copy Link" button that copies the post URL to clipboard</label>';
	}

	public function render_subscription_section_text() {
		echo '<p>Customize the appearance and placement of the subscription form.</p>';
	}

	public function render_subscription_title_field() {
		$value = get_option('feathershare_subscription_title', 'Subscribe to our Newsletter');
		echo '<input type="text" name="feathershare_subscription_title" value="' . esc_attr($value) . '" class="regular-text" />';
	}

	public function render_subscription_description_field() {
		$value = get_option('feathershare_subscription_description', 'Get the latest posts delivered right to your inbox.');
		echo '<textarea name="feathershare_subscription_description" rows="4" class="large-text">' . esc_textarea($value) . '</textarea>';
	}

	public function render_subscription_button_text_field() {
		$value = get_option('feathershare_subscription_button_text', 'Subscribe');
		echo '<input type="text" name="feathershare_subscription_button_text" value="' . esc_attr($value) . '" class="regular-text" />';
	}

	public function render_subscription_placement_field() {
		$current_placement = get_option('feathershare_subscription_placement', 'manual');
		$placements = array(
			'manual' => 'Manual (using shortcode [feathershare_subscribe])',
			'after_content' => 'Automatically after every post',
		);

		foreach ($placements as $key => $label) {
			echo '<label style="display: block; margin-bottom: 5px;">';
			echo '<input type="radio" name="feathershare_subscription_placement" value="' . esc_attr($key) . '" ' . checked($key, $current_placement, false) . ' /> ';
			echo esc_html($label);
			echo '</label>';
		}
	}

	public function render_social_networks_field() {
		$options = get_option('feathershare_social_networks', array('facebook' => 1, 'twitter' => 1, 'linkedin' => 1));

		$all_networks = array(
			'facebook'  => 'Facebook',
			'twitter'   => 'X (Twitter)',
			'linkedin'  => 'LinkedIn',
			'threads'   => 'Threads',
			'whatsapp'  => 'WhatsApp',
			'telegram'  => 'Telegram',
			'reddit'    => 'Reddit',
			'pinterest' => 'Pinterest',
			'vk'        => 'VK',
			'xing'      => 'XING',
			'email'     => 'Email',
		);

		foreach ($all_networks as $id => $label) {
			$checked = isset($options[$id]) ? $options[$id] : 0;
			echo '<label style="display: block; margin-bottom: 5px;">';
			echo '<input type="checkbox" name="feathershare_social_networks[' . $id . ']" value="1" ' . checked(1, $checked, false) . ' /> ';
			echo esc_html($label);
			echo '</label>';
		}
	}

	public function sanitize_social_networks( $input ) {
		$new_input = array();
		if( ! empty( $input ) && is_array( $input ) ) {
			foreach ( $input as $key => $value ) {
				$new_input[ sanitize_key( $key ) ] = ( $value == 1 ? 1 : 0 );
			}
		}
		return $new_input;
	}

	/**
	 * Render the enable messenger field.
	 *
	 * @since    1.0.0
	 */
	public function render_enable_messenger_field() {
		$enable_messenger = get_option( $this->plugin_name . '_enable_messenger', 0 );
		echo '<input type="checkbox" name="' . $this->plugin_name . '_enable_messenger" id="' . $this->plugin_name . '_enable_messenger" value="1" ' . checked( 1, $enable_messenger, false ) . ' />';
		echo '<label for="' . $this->plugin_name . '_enable_messenger">Check to enable the Messenger share button (requires Facebook App ID below)</label>';
	}

	/**
	 * Render the Facebook App ID field.
	 *
	 * @since    1.0.0
	 */
	public function render_facebook_app_id_field() {
		$facebook_app_id = get_option( $this->plugin_name . '_facebook_app_id', '' );
		echo '<input type="text" name="' . $this->plugin_name . '_facebook_app_id" id="' . $this->plugin_name . '_facebook_app_id" value="' . esc_attr( $facebook_app_id ) . '" class="regular-text" />';
	}

	/**
	 * Handle the CSV export of subscribers.
	 *
	 * @since    1.0.0
	 */
	public function handle_export_subscribers_csv() {
		// Check for nonce security
		if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'feathershare_export_nonce' ) ) {
			wp_die( 'Security check failed!' );
		}

		// Check for user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'You do not have permission to export subscribers.' );
		}

		// Get subscribers
		require_once FEATHERSHARE_DIR . 'includes/class-subscription-handler.php';
		$subscription_handler = new Subscription_Handler($this->plugin_name, $this->version);
		$subscribers = $subscription_handler->get_subscribers();

		$filename = 'feathershare-subscribers-' . date('Y-m-d') . '.csv';

		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="' . $filename . '"');

		$output = fopen('php://output', 'w');

		// Add header row
		fputcsv($output, array('Email'));

		// Add data rows
		if ( ! empty($subscribers) ) {
			foreach ($subscribers as $subscriber) {
				fputcsv($output, array($subscriber));
			}
		}

		fclose($output);
		exit;
	}
}