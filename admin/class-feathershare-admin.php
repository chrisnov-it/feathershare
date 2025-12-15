<?php

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
			esc_html__( 'FeatherShare Settings', 'feathershare' ),
			esc_html__( 'FeatherShare', 'feathershare' ),
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
		$settings_url  = admin_url( 'options-general.php?page=' . $this->plugin_name );
		$settings_link = array(
			sprintf(
				'<a href="%s">%s</a>',
				esc_url( $settings_url ),
				esc_html__( 'Settings', 'feathershare' )
			),
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
			esc_html__( 'Subscriber Management', 'feathershare' ),
			array( $this, 'render_general_section_text' ),
			$this->plugin_name
		);

		// Add the social networks section
		add_settings_section(
			'feathershare_networks_section',
			esc_html__( 'Manage Social Networks', 'feathershare' ),
			array( $this, 'render_networks_section_text' ),
			$this->plugin_name
		);

		// Add Button Appearance section
		add_settings_section(
			'feathershare_appearance_section',
			esc_html__( 'Button Appearance', 'feathershare' ),
			array( $this, 'render_appearance_section_text' ),
			$this->plugin_name
		);

		// Add Subscription Form section
		add_settings_section(
			'feathershare_subscription_section',
			esc_html__( 'Subscription Form Settings', 'feathershare' ),
			array( $this, 'render_subscription_section_text' ),
			$this->plugin_name
		);

		add_settings_field(
			'feathershare_social_networks',
			esc_html__( 'Enabled Share Buttons', 'feathershare' ),
			array( $this, 'render_social_networks_field' ),
			$this->plugin_name,
			'feathershare_networks_section'
		);

		add_settings_field(
			'feathershare_enable_messenger',
			esc_html__( 'Enable Messenger Share Button', 'feathershare' ),
			array( $this, 'render_enable_messenger_field' ),
			$this->plugin_name,
			'feathershare_networks_section'
		);

		add_settings_field(
			'feathershare_facebook_app_id',
			esc_html__( 'Facebook App ID', 'feathershare' ),
			array( $this, 'render_facebook_app_id_field' ),
			$this->plugin_name,
			'feathershare_networks_section'
		);

		// Button Appearance Fields
		add_settings_field(
			'feathershare_button_style',
			esc_html__( 'Button Shape', 'feathershare' ),
			array( $this, 'render_button_style_field' ),
			$this->plugin_name,
			'feathershare_appearance_section'
		);

		add_settings_field(
			'feathershare_button_size',
			esc_html__( 'Button Size', 'feathershare' ),
			array( $this, 'render_button_size_field' ),
			$this->plugin_name,
			'feathershare_appearance_section'
		);

		add_settings_field(
			'feathershare_show_labels',
			esc_html__( 'Show Labels', 'feathershare' ),
			array( $this, 'render_show_labels_field' ),
			$this->plugin_name,
			'feathershare_appearance_section'
		);

		add_settings_field(
			'feathershare_enable_copy_link',
			esc_html__( 'Enable Copy Link Button', 'feathershare' ),
			array( $this, 'render_enable_copy_link_field' ),
			$this->plugin_name,
			'feathershare_appearance_section'
		);

		// Subscription Fields
		add_settings_field(
			'feathershare_subscription_title',
			esc_html__( 'Form Title', 'feathershare' ),
			array( $this, 'render_subscription_title_field' ),
			$this->plugin_name,
			'feathershare_subscription_section'
		);

		add_settings_field(
			'feathershare_subscription_description',
			esc_html__( 'Form Description', 'feathershare' ),
			array( $this, 'render_subscription_description_field' ),
			$this->plugin_name,
			'feathershare_subscription_section'
		);

		add_settings_field(
			'feathershare_subscription_button_text',
			esc_html__( 'Button Text', 'feathershare' ),
			array( $this, 'render_subscription_button_text_field' ),
			$this->plugin_name,
			'feathershare_subscription_section'
		);

		add_settings_field(
			'feathershare_subscription_placement',
			esc_html__( 'Form Placement', 'feathershare' ),
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
			),
			admin_url( 'admin-post.php' )
		);
		$export_url = wp_nonce_url( $export_url, 'feathershare_export_nonce' );

		echo '<p>' . esc_html__( 'Manage your FeatherShare subscribers.', 'feathershare' ) . '</p>';
		echo '<a href="' . esc_url( $export_url ) . '" class="button button-primary">' . esc_html__( 'Export Subscribers to CSV', 'feathershare' ) . '</a>';
	}

	public function render_networks_section_text() {
		echo '<p>' . esc_html__( 'Select the social networks you want to enable.', 'feathershare' ) . '</p>';
	}

	public function render_appearance_section_text() {
		echo '<p>' . esc_html__( 'Customize the appearance of your share buttons.', 'feathershare' ) . '</p>';
	}

	public function render_button_style_field() {
		$current_style = get_option( 'feathershare_button_style', 'circle' );
		$styles = array(
			'circle'  => __( 'Circle (Default)', 'feathershare' ),
			'square'  => __( 'Square', 'feathershare' ),
			'rounded' => __( 'Rounded Square', 'feathershare' ),
		);

		foreach ( $styles as $key => $label ) {
			echo '<label style="display: inline-block; margin-right: 15px;">';
			echo '<input type="radio" name="feathershare_button_style" value="' . esc_attr( $key ) . '" ' . checked( $key, $current_style, false ) . ' /> ';
			echo esc_html( $label );
			echo '</label>';
		}
	}

	public function render_button_size_field() {
		$current_size = get_option( 'feathershare_button_size', 'medium' );
		$sizes = array(
			'small'  => __( 'Small (32px)', 'feathershare' ),
			'medium' => __( 'Medium (40px)', 'feathershare' ),
			'large'  => __( 'Large (48px)', 'feathershare' ),
		);

		foreach ( $sizes as $key => $label ) {
			echo '<label style="display: inline-block; margin-right: 15px;">';
			echo '<input type="radio" name="feathershare_button_size" value="' . esc_attr( $key ) . '" ' . checked( $key, $current_size, false ) . ' /> ';
			echo esc_html( $label );
			echo '</label>';
		}
	}

	public function render_show_labels_field() {
		$show_labels = get_option( 'feathershare_show_labels', 0 );
		echo '<input type="checkbox" name="feathershare_show_labels" id="feathershare_show_labels" value="1" ' . checked( 1, $show_labels, false ) . ' />';
		echo '<label for="feathershare_show_labels"> ' . esc_html__( 'Show text labels next to icons (e.g., "Facebook", "Twitter")', 'feathershare' ) . '</label>';
	}

	public function render_enable_copy_link_field() {
		$enable_copy_link = get_option( 'feathershare_enable_copy_link', 1 );
		echo '<input type="checkbox" name="feathershare_enable_copy_link" id="feathershare_enable_copy_link" value="1" ' . checked( 1, $enable_copy_link, false ) . ' />';
		echo '<label for="feathershare_enable_copy_link"> ' . esc_html__( 'Add a "Copy Link" button that copies the post URL to clipboard', 'feathershare' ) . '</label>';
	}

	public function render_subscription_section_text() {
		echo '<p>' . esc_html__( 'Customize the appearance and placement of the subscription form.', 'feathershare' ) . '</p>';
	}

	public function render_subscription_title_field() {
		$value = get_option( 'feathershare_subscription_title', __( 'Subscribe to our Newsletter', 'feathershare' ) );
		echo '<input type="text" name="feathershare_subscription_title" value="' . esc_attr($value) . '" class="regular-text" />';
	}

	public function render_subscription_description_field() {
		$value = get_option( 'feathershare_subscription_description', __( 'Get the latest posts delivered right to your inbox.', 'feathershare' ) );
		echo '<textarea name="feathershare_subscription_description" rows="4" class="large-text">' . esc_textarea($value) . '</textarea>';
	}

	public function render_subscription_button_text_field() {
		$value = get_option( 'feathershare_subscription_button_text', __( 'Subscribe', 'feathershare' ) );
		echo '<input type="text" name="feathershare_subscription_button_text" value="' . esc_attr($value) . '" class="regular-text" />';
	}

	public function render_subscription_placement_field() {
		$current_placement = get_option( 'feathershare_subscription_placement', 'manual' );
		$placements = array(
			'manual'        => __( 'Manual (using shortcode [feathershare_subscribe])', 'feathershare' ),
			'after_content' => __( 'Automatically after every post', 'feathershare' ),
		);

		foreach ( $placements as $key => $label ) {
			echo '<label style="display: block; margin-bottom: 5px;">';
			echo '<input type="radio" name="feathershare_subscription_placement" value="' . esc_attr( $key ) . '" ' . checked( $key, $current_placement, false ) . ' /> ';
			echo esc_html( $label );
			echo '</label>';
		}
	}

	public function render_social_networks_field() {
		$options = get_option( 'feathershare_social_networks', array( 'facebook' => 1, 'twitter' => 1, 'linkedin' => 1 ) );

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

		foreach ( $all_networks as $id => $label ) {
			$checked = isset( $options[ $id ] ) ? $options[ $id ] : 0;
			echo '<label style="display: block; margin-bottom: 5px;">';
			echo '<input type="checkbox" name="feathershare_social_networks[' . esc_attr( $id ) . ']" value="1" ' . checked( 1, $checked, false ) . ' /> ';
			echo esc_html( $label );
			echo '</label>';
		}
	}

	public function sanitize_social_networks( $input ) {
		$new_input = array();
		if ( ! empty( $input ) && is_array( $input ) ) {
			foreach ( $input as $key => $value ) {
				$new_input[ sanitize_key( $key ) ] = ( 1 === (int) $value ? 1 : 0 );
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
		echo '<label for="' . $this->plugin_name . '_enable_messenger">' . esc_html__( 'Check to enable the Messenger share button (requires Facebook App ID below)', 'feathershare' ) . '</label>';
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
	 * Get subscriber emails for export.
	 *
	 * @since 1.1.0
	 * @return string[]
	 */
	private function get_subscribers_for_export() {
		global $wpdb;

		$emails = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT pm.meta_value FROM {$wpdb->posts} p INNER JOIN {$wpdb->postmeta} pm ON pm.post_id = p.ID WHERE p.post_type = %s AND p.post_status = %s AND pm.meta_key = %s ORDER BY p.ID DESC",
				'feathershare_subscription',
				'publish',
				'feathershare_email'
			)
		);

		if ( empty( $emails ) ) {
			$emails = $wpdb->get_col(
				$wpdb->prepare(
					"SELECT post_title FROM {$wpdb->posts} WHERE post_type = %s AND post_status = %s ORDER BY ID DESC",
					'feathershare_subscription',
					'publish'
				)
			);
		}

		return array_filter( array_map( 'sanitize_email', (array) $emails ) );
	}

	/**
	 * Handle the CSV export of subscribers.
	 *
	 * @since    1.0.0
	 */
	public function handle_export_subscribers_csv() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to export subscribers.', 'feathershare' ) );
		}

		check_admin_referer( 'feathershare_export_nonce' );

		$subscribers = $this->get_subscribers_for_export();
		$filename    = sanitize_file_name( 'feathershare-subscribers-' . wp_date( 'Y-m-d' ) . '.csv' );

		nocache_headers();
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename="' . $filename . '"' );

		$output = fopen( 'php://output', 'w' );
		if ( false === $output ) {
			wp_die( esc_html__( 'Unable to generate CSV.', 'feathershare' ) );
		}

		fputcsv( $output, array( 'Email' ) );
		foreach ( (array) $subscribers as $subscriber ) {
			fputcsv( $output, array( $subscriber ) );
		}

		fclose( $output );
		exit;
	}
}
