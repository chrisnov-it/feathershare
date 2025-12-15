<?php

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The subscription functionality of the plugin.
 *
 * @link       https://chrisnov.com
 * @since      1.0.0
 *
 * @package    FeatherShare
 * @subpackage FeatherShare/includes
 */

/**
 * The subscription functionality of the plugin.
 *
 * Defines the subscription form and processing functionality.
 *
 * @package    FeatherShare
 * @subpackage FeatherShare/includes
 * @author     Reynov Christian <contact@chrisnov.com>
 */
class Subscription_Handler {

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

		// Register the shortcode
		add_shortcode( 'feathershare_subscribe', array( $this, 'display_subscription_form' ) );

		// Handle form submission via AJAX
		add_action( 'wp_ajax_feathershare_subscribe', array( $this, 'process_subscription_form' ) );
		add_action( 'wp_ajax_nopriv_feathershare_subscribe', array( $this, 'process_subscription_form' ) );

		// Create custom post type for subscriptions
		add_action( 'init', array( $this, 'create_subscription_post_type' ) );
	}

	/**
	 * Create a custom post type for storing subscriptions.
	 *
	 * @since    1.0.0
	 */
	public function create_subscription_post_type() {
		$args = array(
			'public'              => false,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'query_var'           => true,
			'rewrite'             => array( 'slug' => 'subscription' ),
			'has_archive'         => false,
			'hierarchical'        => false,
			'menu_position'       => null,
			'supports'            => array( 'title' ),
			'menu_icon'           => 'dashicons-email',
			'map_meta_cap'        => true,
			'capability_type'     => 'feathershare_subscription',
			'capabilities'        => array(
				'edit_post'              => 'manage_options',
				'read_post'              => 'manage_options',
				'delete_post'            => 'manage_options',
				'edit_posts'             => 'manage_options',
				'edit_others_posts'      => 'manage_options',
				'delete_posts'           => 'manage_options',
				'publish_posts'          => 'manage_options',
				'read_private_posts'     => 'manage_options',
				'delete_private_posts'   => 'manage_options',
				'delete_published_posts' => 'manage_options',
				'delete_others_posts'    => 'manage_options',
				'edit_private_posts'     => 'manage_options',
				'edit_published_posts'   => 'manage_options',
				'create_posts'           => 'manage_options',
			),
		);

		register_post_type( 'feathershare_subscription', $args );
	}

	/**
	 * Display the subscription form.
	 *
	 * @since    1.0.0
	 * @param    array    $atts    Shortcode attributes.
	 * @return   string   The subscription form HTML.
	 */
	public function display_subscription_form( $atts ) {
		// Get custom text from options, with defaults
		$title       = get_option( 'feathershare_subscription_title', __( 'Subscribe to our Newsletter', 'feathershare' ) );
		$description = get_option( 'feathershare_subscription_description', __( 'Get the latest posts delivered right to your inbox.', 'feathershare' ) );
		$button_text = get_option( 'feathershare_subscription_button_text', __( 'Subscribe', 'feathershare' ) );

		// Build the form HTML
		$form = '<div class="feathershare-subscription-form">';
		$form .= '<h3>' . esc_html( $title ) . '</h3>';
		$form .= '<p>' . wp_kses_post( $description ) . '</p>';
		$form .= '<div class="feathershare-subscription-message"></div>';
		$form .= '<form id="feathershare-subscription-form" method="post">';
		$form .= wp_nonce_field( 'feathershare_subscribe', 'feathershare_subscribe_nonce', true, false );
		$form .= '<p><label for="feathershare_email" class="screen-reader-text">' . esc_html__( 'Email Address:', 'feathershare' ) . '</label>';
		$form .= '<input type="email" id="feathershare_email" name="feathershare_email" placeholder="' . esc_attr__( 'Enter your email', 'feathershare' ) . '" required /></p>';
		$form .= '<p><input type="submit" name="feathershare_subscribe_submit" value="' . esc_attr( $button_text ) . '" /></p>';
		$form .= '</form>';
		$form .= '</div>';

		return $form;
	}

	/**
	 * Maybe display the subscription form after the content.
	 *
	 * @since    1.0.0
	 * @param    string    $content    The content of the post.
	 * @return   string    The content with the form appended, if applicable.
	 */
	public function maybe_display_form_after_content( $content ) {
		$placement = get_option( 'feathershare_subscription_placement', 'manual' );

		if ( $placement === 'after_content' && is_single() && in_the_loop() && is_main_query() ) {
			$content .= $this->display_subscription_form( array() );
		}

		return $content;
	}

	/**
	 * Process the subscription form submission.
	 *
	 * @since    1.0.0
	 */
	public function process_subscription_form() {
		// Only accept POST requests.
		if ( 'POST' !== $_SERVER['REQUEST_METHOD'] ) {
			wp_send_json_error( array( 'message' => __( 'Invalid request method.', 'feathershare' ) ), 405 );
		}

		// Verify nonce for security.
		if ( ! check_ajax_referer( 'feathershare_subscribe', 'feathershare_subscribe_nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'feathershare' ) ), 403 );
		}

		// Validate email
		if ( ! isset( $_POST['feathershare_email'] ) || empty( $_POST['feathershare_email'] ) ) {
			wp_send_json_error( array( 'message' => __( 'Please enter a valid email address.', 'feathershare' ) ), 400 );
		}

		$email = sanitize_email( wp_unslash( $_POST['feathershare_email'] ) );
		$email = strtolower( $email );
		
		if ( ! is_email( $email ) ) {
			wp_send_json_error( array( 'message' => __( 'Please enter a valid email address.', 'feathershare' ) ), 400 );
		}

		// Check if email already exists.
		$existing_ids = get_posts(
			array(
				'post_type'      => 'feathershare_subscription',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'no_found_rows'  => true,
				'meta_query'     => array(
					array(
						'key'     => 'feathershare_email',
						'value'   => $email,
						'compare' => '=',
					),
				),
			)
		);

		// Return the same message to avoid email enumeration.
		if ( ! empty( $existing_ids ) ) {
			wp_send_json_success( array( 'message' => __( 'Thank you for subscribing!', 'feathershare' ) ) );
		}

		// Create post to store subscription
		$post_data = array(
			'post_title'   => $email,
			'post_content' => '',
			'post_status'  => 'publish',
			'post_type'    => 'feathershare_subscription',
		);

		$post_id = wp_insert_post( $post_data, true );

		if ( is_wp_error( $post_id ) ) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				error_log( 'FeatherShare Subscription Error: ' . $post_id->get_error_message() );
			}
			wp_send_json_error( array( 'message' => __( 'An error occurred. Please try again.', 'feathershare' ) ), 500 );
		}

		update_post_meta( $post_id, 'feathershare_email', $email );
		wp_send_json_success( array( 'message' => __( 'Thank you for subscribing!', 'feathershare' ) ) );
	}

	/**
	 * Register the JavaScript for the subscription form.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		// Only enqueue on pages where the subscription form will be displayed
		global $post;
		
		$should_enqueue = false;
		
		// Check if shortcode is present in content
		if ( is_singular() && isset( $post->post_content ) && has_shortcode( $post->post_content, 'feathershare_subscribe' ) ) {
			$should_enqueue = true;
		}
		
		// Also check if auto-placement after content is enabled for single posts
		$placement = get_option( 'feathershare_subscription_placement', 'manual' );
		if ( $placement === 'after_content' && is_single() ) {
			$should_enqueue = true;
		}
		
		if ( $should_enqueue ) {
			wp_enqueue_script( 
				$this->plugin_name . '-subscription', 
				FEATHERSHARE_URL . 'public/js/subscription-handler.js', 
				array( 'jquery' ), 
				$this->version, 
				true 
			);

			// Localize the script with AJAX settings + i18n strings.
			wp_localize_script(
				$this->plugin_name . '-subscription',
				'feathershareSubscribe',
				array(
					'ajaxUrl' => admin_url( 'admin-ajax.php' ),
					'action'  => 'feathershare_subscribe',
					'i18n'    => array(
						'invalidEmail' => __( 'Please enter a valid email address.', 'feathershare' ),
						'processing'   => __( 'Processing...', 'feathershare' ),
						'genericError' => __( 'An error occurred. Please try again.', 'feathershare' ),
					),
				)
			);
		}
	}

	/**
	 * Get all subscribers.
	 *
	 * @since    1.0.0
	 * @return   array    Array of subscriber emails.
	 */
	public function get_subscribers() {
		global $wpdb;

		$subscribers = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT pm.meta_value FROM {$wpdb->posts} p INNER JOIN {$wpdb->postmeta} pm ON pm.post_id = p.ID WHERE p.post_type = %s AND p.post_status = %s AND pm.meta_key = %s ORDER BY p.ID DESC",
				'feathershare_subscription',
				'publish',
				'feathershare_email'
			)
		);

		if ( empty( $subscribers ) ) {
			$subscribers = $wpdb->get_col(
				$wpdb->prepare(
					"SELECT post_title FROM {$wpdb->posts} WHERE post_type = %s AND post_status = %s ORDER BY ID DESC",
					'feathershare_subscription',
					'publish'
				)
			);
		}

		return array_filter( array_map( 'sanitize_email', (array) $subscribers ) );
	}
}
