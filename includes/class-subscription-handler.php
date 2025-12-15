<?php

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
			'public' => false,
			'publicly_queryable' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'subscription' ),
			'capability_type' => 'post',
			'has_archive' => false,
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array( 'title' ),
			'menu_icon' => 'dashicons-email',
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
		$title = get_option('feathershare_subscription_title', __( 'Subscribe to our Newsletter', 'feathershare' ));
		$description = get_option('feathershare_subscription_description', __( 'Get the latest posts delivered right to your inbox.', 'feathershare' ));
		$button_text = get_option('feathershare_subscription_button_text', __( 'Subscribe', 'feathershare' ));

		// Generate nonce for security
		$nonce = wp_create_nonce( 'feathershare_subscribe_nonce' );

		// Build the form HTML
		$form = '<div class="feathershare-subscription-form">';
		$form .= '<h3>' . esc_html($title) . '</h3>';
		$form .= '<p>' . wp_kses_post($description) . '</p>';
		$form .= '<div class="feathershare-subscription-message"></div>';
		$form .= '<form id="feathershare-subscription-form" method="post">';
		$form .= '<input type="hidden" name="feathershare_subscribe_nonce" value="' . $nonce . '" />';
		$form .= '<p><label for="feathershare_email" class="screen-reader-text">' . __( 'Email Address:', 'feathershare' ) . '</label>';
		$form .= '<input type="email" id="feathershare_email" name="feathershare_email" placeholder="' . __('Enter your email', 'feathershare') . '" required /></p>';
		$form .= '<p><input type="submit" name="feathershare_subscribe_submit" value="' . esc_attr($button_text) . '" /></p>';
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
		$placement = get_option('feathershare_subscription_placement', 'manual');

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
		// Verify nonce for security
		if ( ! isset( $_POST['feathershare_subscribe_nonce'] ) || 
			 ! wp_verify_nonce( $_POST['feathershare_subscribe_nonce'], 'feathershare_subscribe_nonce' ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed', 'feathershare' ) ) );
		}

		// Validate email
		if ( ! isset( $_POST['feathershare_email'] ) || empty( $_POST['feathershare_email'] ) ) {
			wp_send_json_error( array( 'message' => __( 'Please enter a valid email address', 'feathershare' ) ) );
		}

		$email = sanitize_email( $_POST['feathershare_email'] );
		
		if ( ! is_email( $email ) ) {
			wp_send_json_error( array( 'message' => __( 'Please enter a valid email address', 'feathershare' ) ) );
		}

		// Check if email already exists (using WP_Query instead of deprecated get_page_by_title)
		$existing_query = new WP_Query( array(
			'post_type'      => 'feathershare_subscription',
			'post_status'    => 'publish',
			'title'          => $email,
			'posts_per_page' => 1,
			'fields'         => 'ids',
		) );
		
		if ( $existing_query->have_posts() ) {
			wp_reset_postdata();
			wp_send_json_success( array( 'message' => __( 'Thank you for subscribing!', 'feathershare' ) ) );
		}
		wp_reset_postdata();

		// Create post to store subscription
		$post_data = array(
			'post_title'   => $email,
			'post_content' => '',
			'post_status'  => 'publish',
			'post_type'    => 'feathershare_subscription',
		);

		$post_id = wp_insert_post( $post_data );

		if ( $post_id && ! is_wp_error( $post_id ) ) {
			wp_send_json_success( array( 'message' => __( 'Thank you for subscribing!', 'feathershare' ) ) );
		} else {
			// Log the error for debugging
			if ( is_wp_error( $post_id ) ) {
				error_log( 'FeatherShare Subscription Error: ' . $post_id->get_error_message() );
			} else {
				error_log( 'FeatherShare Subscription Error: Unknown error occurred during post creation' );
			}
			wp_send_json_error( array( 'message' => __( 'An error occurred. Please try again.', 'feathershare' ) ) );
		}
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
			
			// Localize the script with AJAX URL
			wp_localize_script( 
				$this->plugin_name . '-subscription', 
				'feathershare_ajax', 
				array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) 
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
		
		$subscribers = $wpdb->get_col( $wpdb->prepare(
			"SELECT post_title FROM {$wpdb->posts} WHERE post_type = %s AND post_status = %s",
			'feathershare_subscription',
			'publish'
		) );
		
		return $subscribers;
	}
}