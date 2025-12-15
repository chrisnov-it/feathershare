<?php

/**
 * The social sharing functionality of the plugin.
 *
 * @link       https://chrisnov.com
 * @since      1.0.0
 *
 * @package    FeatherShare
 * @subpackage FeatherShare/includes
 */

/**
 * The social sharing functionality of the plugin.
 *
 * Defines the social sharing buttons that are added to post content.
 *
 * @package    FeatherShare
 * @subpackage FeatherShare/includes
 * @author     Reynov Christian <contact@chrisnov.com>
 */
class Social_Sharing {

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
	}

	/**
	 * Add social sharing buttons to the content.
	 *
	 * @since    1.0.0
	 * @param    string    $content    The content of the post.
	 * @return   string    The content with social sharing buttons appended.
	 */
	public function add_sharing_buttons( $content ) {
		// Only add sharing buttons to single post pages
		if ( is_single() && in_the_loop() && is_main_query() ) {
			return $content . $this->generate_sharing_buttons();
		}
		
		return $content;
	}

	/**
	 * Get the SVG markup for a social icon.
	 *
	 * @since    1.0.0
	 * @param    string    $icon_name    The name of the icon.
	 * @return   string    The SVG markup.
	 */
	private function get_feathershare_svg_icon( $icon_name ) {
		$icons = array(
			'twitter'  => '<svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z"/></svg>',
			'facebook' => '<svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M22.675 0H1.325C.593 0 0 .593 0 1.325v21.351C0 23.407.593 24 1.325 24H12.82v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12V24h6.116c.732 0 1.325-.593 1.325-1.325V1.325C24 .593 23.407 0 22.675 0Z"/></svg>',
			'linkedin' => '<svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.225 0z"/></svg>',
			'threads'  => '<svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 5.332c-3.684 0-6.668 2.985-6.668 6.668 0 3.684 2.984 6.668 6.668 6.668 3.684 0 6.668-2.984 6.668-6.668 0-3.683-2.984-6.668-6.668-6.668m0 11.336c-2.579 0-4.668-2.089-4.668-4.668s2.089-4.668 4.668-4.668 4.668 2.089 4.668 4.668-2.089 4.668-4.668 4.668M2.25 5.832C2.25 3.596 3.596 2.25 5.832 2.25h12.336c2.236 0 3.582 1.346 3.582 3.582v12.336c0 2.236-1.346 3.582-3.582 3.582H5.832C3.596 21.75 2.25 20.404 2.25 18.168V5.832Z"/></svg>',
			'whatsapp' => '<svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12.04 2.01A10.01 10.01 0 0 0 2.03 12.05a10.01 10.01 0 0 0 10.01 10.01 10.01 10.01 0 0 0 10.01-10.01A10.01 10.01 0 0 0 12.04 2.01zM17.6 14.85c-.23-.12-1.36-.67-1.57-.74-.21-.08-.36-.12-.52.12-.15.23-.59.74-.73.89-.13.15-.27.16-.5.05-.23-.12-1-.36-1.89-1.17-.7-.6-1.17-1.34-1.31-1.57-.15-.23-.02-.36.1-.48.11-.11.23-.27.35-.4.12-.12.16-.21.24-.35.08-.15.04-.27-.02-.39-.06-.12-.52-1.25-.71-1.7-.19-.45-.38-.39-.52-.4-.13-.01-.27-.01-.42-.01-.15 0-.39.06-.59.27-.2.21-.77.75-.77 1.82s.79 2.11.89 2.26c.12.15 1.55 2.35 3.76 3.3.54.23.97.36 1.3.47.54.16 1.04.14 1.42.08.42-.06 1.36-.55 1.55-.97.19-.41.19-.77.13-.89-.05-.12-.2-.18-.41-.3z"/></svg>',
			'telegram' => '<svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.102.03.28.008.413l-1.964 9.242c-.04.203-.12.33-.22.368-.1.038-.22.013-.304-.038l-3.132-2.304-1.51 1.452c-.168.168-.312.312-.5.312-.188 0-.312-.144-.312-.432v-3.168l5.532-4.956c.238-.214.12-.33-.104-.192l-6.888 4.32-3.228-1.008c-.2-.06-.276-.192-.156-.312.12-.12.276-.168.432-.12l9.924 3.6z"/></svg>',
			'messenger'=> '<svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 0C5.373 0 0 5.14 0 11.462C0 17.785 5.373 24 12 24c1.845 0 3.59-.443 5.125-1.24L22.938 24l-1.43-3.867c1.06-1.587 1.682-3.45 1.682-5.45C23.19 5.14 17.818 0 12 0zm1.43 14.86L9.31 11.62l-4.02 3.24L9.31 3.14l4.12 3.24 4.02-3.24-4.02 11.72z"/></svg>',
			'email'    => '<svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M22 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6zm-2 0-8 5-8-5h16zm0 12H4V8l8 5 8-5v10z"/></svg>',
			'reddit'   => '<svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 0C5.383 0 0 5.383 0 12s5.383 12 12 12 12-5.383 12-12S18.617 0 12 0zm5.832 13.168c0 .43-.35.78-.78.78h-2.554c-.275 0-.513-.14-.65-.364-.137-.225-.123-.51.033-.718.892-1.23.92-2.99.067-4.28-1.133-1.715-3.248-2.73-5.62-2.73-2.37 0-4.485 1.015-5.62 2.73-.853 1.29-.825 3.05.066 4.28.156.207.17.493.034.718-.138.224-.376.364-.65.364H2.95c-.43 0-.78-.35-.78-.78s.35-.78.78-.78h2.553c.01 0 .02-.004.028-.005.08-.11.12-.24.11-.37-.01-.15-.07-.28-.17-.38-.99-1.48-1.02-3.6.08-5.16 1.45-2.08 3.9-3.32 6.5-3.32s5.05 1.24 6.5 3.32c1.1 1.56 1.07 3.68.08 5.16-.1.1-.16.23-.17.38-.01.13.03.26.11.37.01.001.018.005.028.005h2.554c.43 0 .78.35.78.78zm-3.15-3.45c-.61 0-1.1.49-1.1 1.1s.49 1.1 1.1 1.1 1.1-.49 1.1-1.1-.49-1.1-1.1-1.1zm-5.36 0c-.61 0-1.1.49-1.1 1.1s.49 1.1 1.1 1.1 1.1-.49 1.1-1.1-.49-1.1-1.1-1.1zm.21 5.34c1.51.83 3.3.83 4.81 0 .15-.08.32.04.32.21 0 .1-.06.18-.15.22-1.73 1-3.82 1-5.55 0-.09-.04-.15-.12-.15-.22 0-.17.17-.29.32-.21z"/></svg>',
			'vk'       => '<svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M13.168 23.418c-.303.242-.51.32-.623.32-.33 0-.56-.22-.56-.718V15.21L8.23 23.418c-.275.65-.495.848-.923.848-.198 0-.363-.05-.51-.16l-3.3-.018c-.22 0-.384-.11-.494-.32 0 0-.01-.01-.01-.01-.11-.21-.033-.418.198-.648l4.323-5.07-4.223-4.53c-.276-.287-.33-.485-.165-.617.165-.132.407-.12.583-.12h3.498c.34 0 .56.165.68.406l2.938 3.895 2.058-4.302c.208-.406.395-.582.835-.582h3.752c.373 0 .55.12.55.45 0 .11-.05.24-.16.38l-3.13 3.53 3.62 4.41c.34.407.373.68.132.86-.242.18-.56.23-.704.23h-3.52z"/></svg>',
			'pinterest'=> '<svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 0C5.373 0 0 5.373 0 12c0 5.1 3.156 9.42 7.5 11.25.031-.313.063-1.032.281-1.938.219-.906.938-3.968.938-3.968s-.25-.5-.25-1.219c0-1.156.688-2.031 1.531-2.031.719 0 1.063.531 1.063 1.188 0 .719-.469 1.781-.719 2.781-.219.844.406 1.531 1.25 1.531 1.5 0 2.625-1.594 2.625-3.875 0-2.031-1.438-3.5-3.594-3.5-2.438 0-3.844 1.813-3.844 3.656 0 .688.25 1.438.594 1.844.063.063.063.125.031.219-.094.313-.313.938-.344 1.031-.031.125-.094.156-.25.094-1.031-.406-1.688-1.625-1.688-2.844 0-2.25 1.656-4.281 4.781-4.281 2.531 0 4.469 1.875 4.469 4.156 0 2.531-1.594 4.563-3.781 4.563-1.25 0-2.188-.969-1.875-2.125.344-1.281.969-2.656.969-2.656s.219-.875-.531-.875c-.906 0-1.594.938-1.594 2.125 0 .813.281 1.375.281 1.375s-1 4.188-1.188 4.938c-.281 1.156-.031 2.5.063 2.75.094.25.438.344.656.219 1.031-.594 1.375-2.156 1.375-2.156s.594-2.344.719-2.813c.25-.938.75-1.75 1.563-1.75 1.656 0 2.938 1.75 2.938 4.313 0 1.594-.5 2.813-1.125 3.625-1.531 1.938-4.313 2.906-6.656 2.906C5.373 24 0 18.627 0 12 0 5.373 5.373 0 12 0Z"/></svg>',
			'xing'     => '<svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M1.29 0h4.42l3.68 6.42L13.07 0h4.42L10.8 7.9l7.2 12.8H13.6l-3.8-6.8-3.5 6.8H1.8l7.4-12.8L1.28 0z"/></svg>',
			'copylink' => '<svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M13.723 18.654l-3.61 3.609c-2.316 2.315-6.063 2.315-8.378 0-1.12-1.118-1.735-2.606-1.735-4.188 0-1.582.615-3.07 1.734-4.189l4.866-4.865c2.355-2.355 6.114-2.262 8.377 0 .453.453.81.973 1.089 1.527l-1.593 1.592c-.18-.613-.5-1.189-.964-1.652-1.448-1.448-3.93-1.51-5.439-.001l-.001.002-4.867 4.865c-1.5 1.499-1.5 3.941 0 5.44 1.517 1.517 3.958 1.488 5.442 0l2.405-2.405c.96.437 1.996.66 3.074.66zm5.556-16.934c-2.315-2.315-6.062-2.315-8.377 0l-3.61 3.609c.96-.437 1.996-.66 3.074-.66l2.405-2.404c1.484-1.488 3.925-1.517 5.442 0 1.499 1.499 1.499 3.941 0 5.44l-4.867 4.865-.001.002c-1.509 1.509-3.991 1.447-5.439-.001-.464-.463-.784-1.039-.964-1.652l-1.593 1.592c.28.554.636 1.074 1.089 1.527 2.263 2.262 6.022 2.355 8.377 0l4.866-4.865c1.119-1.119 1.734-2.607 1.734-4.189s-.616-3.07-1.736-4.189z"/></svg>',
			'check'    => '<svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>',
		);

		return isset( $icons[ $icon_name ] ) ? $icons[ $icon_name ] : '';
	}

	/**
	 * Generate the HTML for social sharing buttons.
	 *
	 * @since    1.0.0
	 * @return   string    The HTML for the social sharing buttons.
	 */
	private function generate_sharing_buttons() {
		$post_url = get_permalink();
		$post_title = get_the_title();
		
		if (empty($post_url) || empty($post_title)) {
			$post_url = get_home_url();
			$post_title = get_bloginfo('name');
		}

		// Get button style settings
		$button_style = get_option( 'feathershare_button_style', 'circle' );
		$button_size = get_option( 'feathershare_button_size', 'medium' );
		$show_labels = get_option( 'feathershare_show_labels', 0 );
		$enable_copy_link = get_option( 'feathershare_enable_copy_link', 1 );

		// Build CSS classes for the container
		$container_classes = 'feathershare-social-buttons';
		$container_classes .= ' feathershare-style-' . esc_attr( $button_style );
		$container_classes .= ' feathershare-size-' . esc_attr( $button_size );
		if ( $show_labels ) {
			$container_classes .= ' feathershare-with-labels';
		}

		$sharing_buttons = '<div class="' . esc_attr( $container_classes ) . '" data-url="' . esc_attr( $post_url ) . '">';

		$encoded_url = urlencode($post_url);
		$encoded_title = urlencode($post_title);

		// Master list of all available social networks
		$all_networks = array(
			'facebook'  => array( 'url' => 'https://www.facebook.com/sharer/sharer.php?u=' . $encoded_url, 'label' => 'Facebook' ),
			'twitter'   => array( 'url' => 'https://twitter.com/intent/tweet?url=' . $encoded_url . '&text=' . $encoded_title, 'label' => 'X (Twitter)' ),
			'linkedin'  => array( 'url' => 'https://www.linkedin.com/shareArticle?mini=true&url=' . $encoded_url . '&title=' . $encoded_title, 'label' => 'LinkedIn' ),
			'threads'   => array( 'url' => 'https://www.threads.net/intent/post?text=' . $encoded_title . '%20' . $encoded_url, 'label' => 'Threads' ),
			'whatsapp'  => array( 'url' => 'https://wa.me/?text=' . $encoded_title . '%20' . $encoded_url, 'label' => 'WhatsApp' ),
			'telegram'  => array( 'url' => 'https://t.me/share/url?url=' . $encoded_url . '&text=' . $encoded_title, 'label' => 'Telegram' ),
			'reddit'    => array( 'url' => 'https://reddit.com/submit?url=' . $encoded_url . '&title=' . $encoded_title, 'label' => 'Reddit' ),
			'pinterest' => array( 'url' => 'https://pinterest.com/pin/create/button/?url=' . $encoded_url . '&description=' . $encoded_title, 'label' => 'Pinterest' ),
			'vk'        => array( 'url' => 'https://vk.com/share.php?url=' . $encoded_url, 'label' => 'VK' ),
			'xing'      => array( 'url' => 'https://www.xing.com/spi/shares/new?url=' . $encoded_url, 'label' => 'XING' ),
			'email'     => array( 'url' => 'mailto:?subject=' . rawurlencode($post_title) . '&body=' . rawurlencode($post_url), 'label' => 'Email' ),
		);

		// Get the enabled networks from settings
		$enabled_networks = get_option('feathershare_social_networks', array('facebook' => 1, 'twitter' => 1, 'linkedin' => 1)); // Default

		foreach ($all_networks as $network => $data) {
			// Check if the network is enabled in the settings
			if ( ! empty( $enabled_networks[$network] ) ) {
				/* translators: %s: Social network name */
				$aria_label = sprintf( __( 'Share on %s', 'feathershare' ), $data['label'] );
				$sharing_buttons .= '<a href="' . esc_url( $data['url'] ) . '" target="_blank" rel="noopener noreferrer" class="feathershare-' . esc_attr( $network ) . '" aria-label="' . esc_attr( $aria_label ) . '">';
				$sharing_buttons .= $this->get_feathershare_svg_icon($network);
				$sharing_buttons .= '<span class="screen-reader-text">' . esc_html( $data['label'] ) . '</span>';
				// Add label text if enabled
				if ( $show_labels ) {
					$sharing_buttons .= '<span class="feathershare-label">' . esc_html( $data['label'] ) . '</span>';
				}
				$sharing_buttons .= '</a>';
			}
		}
		
		$enable_messenger = get_option( 'feathershare_enable_messenger', 0 );
		$facebook_app_id = get_option( 'feathershare_facebook_app_id', '' );
		
		if ( $enable_messenger && ! empty( $facebook_app_id ) ) {
			$messenger_url = 'https://www.facebook.com/dialog/send?link=' . $encoded_url . '&app_id=' . esc_attr( $facebook_app_id ) . '&redirect_uri=' . $encoded_url;
			$aria_label_messenger = __( 'Share on Messenger', 'feathershare' );
			$sharing_buttons .= '<a href="' . esc_url( $messenger_url ) . '" target="_blank" rel="noopener noreferrer" class="feathershare-messenger" aria-label="' . esc_attr( $aria_label_messenger ) . '">';
			$sharing_buttons .= $this->get_feathershare_svg_icon('messenger');
			$sharing_buttons .= '<span class="screen-reader-text">' . esc_html__( 'Messenger', 'feathershare' ) . '</span>';
			// Add label text if enabled
			if ( $show_labels ) {
				$sharing_buttons .= '<span class="feathershare-label">' . esc_html__( 'Messenger', 'feathershare' ) . '</span>';
			}
			$sharing_buttons .= '</a>';
		}

		// Add Copy Link button if enabled
		if ( $enable_copy_link ) {
			$copy_label = __( 'Copy Link', 'feathershare' );
			$copied_label = __( 'Copied!', 'feathershare' );
			$sharing_buttons .= '<button type="button" class="feathershare-copy-link" aria-label="' . esc_attr( $copy_label ) . '" data-copied-text="' . esc_attr( $copied_label ) . '">';
			$sharing_buttons .= '<span class="feathershare-copy-icon">' . $this->get_feathershare_svg_icon('copylink') . '</span>';
			$sharing_buttons .= '<span class="feathershare-check-icon">' . $this->get_feathershare_svg_icon('check') . '</span>';
			$sharing_buttons .= '<span class="screen-reader-text">' . esc_html( $copy_label ) . '</span>';
			if ( $show_labels ) {
				$sharing_buttons .= '<span class="feathershare-label feathershare-copy-label">' . esc_html( $copy_label ) . '</span>';
			}
			$sharing_buttons .= '</button>';
		}
		
		$sharing_buttons .= '</div>';
		
		return $sharing_buttons;
	}


	/**
	 * Register the stylesheets for the social sharing buttons.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		// Only enqueue on single post pages
		if ( is_single() ) {
			wp_enqueue_style( $this->plugin_name . '-social-sharing', FEATHERSHARE_URL . 'public/css/social-sharing.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the social sharing buttons.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		// Only enqueue on single post pages
		if ( is_single() ) {
			wp_enqueue_script( $this->plugin_name . '-social-sharing', FEATHERSHARE_URL . 'public/js/social-sharing.js', array( 'jquery' ), $this->version, true );
		}
	}
}