=== FeatherShare ===
Contributors: reynovchristian
Tags: social sharing, subscription form, performance, lightweight, optimized, sharing buttons, newsletter
Requires at least: 5.0
Tested up to: 6.5
Stable tag: 1.3
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

FeatherShare is a high-performance, ultra-lightweight WordPress plugin designed for modern websites where speed and efficiency are paramount.

== Description ==

**FeatherShare** is not just another social sharing plugin. It's a performance-focused solution built to enhance your website's interactivity without the typical bloat. Many sharing plugins slow down your PageSpeed Insights scores with heavy external libraries and redundant scripts; FeatherShare reverses this trend by using optimized inline SVGs and intelligent conditional loading.

Whether you're looking to boost your content's reach across 9+ major social platforms or grow your mailing list with a seamless AJAX subscription form, FeatherShare provides the tools you need in a package that feels as light as a feather.

== Features ==

*   **Optimized Performance**: Achieve 90+ PageSpeed Insights scores. We use inline SVGs and combined assets to minimize HTTP requests.
*   **Intelligent Loading**: Assets are only loaded on pages where they're actually used (e.g., single posts or pages with shortcodes).
*   **Social Sharing Buttons**: Twitter/X, Facebook, LinkedIn, Threads, WhatsApp, Telegram, Messenger, Email, Reddit, and Pinterest.
*   **Modern Clipboard API**: A professional "Copy Link" button with instant visual feedback and fallback support.
*   **Customizable Design**: Choose between Circle, Square, or Rounded shapes. Adjust sizes from Small to Large and toggle text labels.
*   **AJAX Subscription Form**: Grow your audience without page reloads. Includes security nonces and built-in email validation.
*   **Object-Oriented Architecture**: Built using a clean, modular boilerplate that follows modern WordPress coding standards.
*   **Privacy First**: No external tracking, no bloated third-party APIs. Your data stays yours.

== Shortcodes ==

*   `[feathershare_subscribe]` - Displays the customizable newsletter subscription form.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/feathershare` directory.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Navigate to **Settings > FeatherShare** to configure your buttons and subscription form.
4. Social sharing buttons appear automatically on single post pages.

== Frequently Asked Questions ==

= How does FeatherShare help my SEO/PSI scores? =
Unlike traditional plugins that load multiple CSS and JS files on every single page, FeatherShare consolidates assets and uses unique conditional loading logic. If a page doesn't need sharing buttons, it loads absolutely zero assets from this plugin.

= How do I enable the Messenger button? =
Go to Settings > FeatherShare. Check "Enable Messenger Share Button" and provide your Facebook App ID to initiate the native Messenger dialog.

= Can I customize the form text? =
Yes! All subscription form strings (Title, Description, Button Text) can be customized directly within the FeatherShare settings page.

== Changelog ==

= 1.3 =
*   **Performance Overhaul**: Consolidated multiple JS and CSS files into single high-performance assets (`feathershare.js` and `feathershare.css`).
*   **Conditional Loading 2.0**: Refined asset enqueuing logic to ensure zero impact on non-single post pages.
*   **Code Cleanup**: Removed redundant public asset files and streamlined the core loader class.
*   **Improved Localization**: Fixed potential JS translation issues by unifying the localization handle.

= 1.2 =
*   Refactored folder structure for better organization.
*   Added SVG icons for major social platforms to eliminate image requests.
*   Added CSS support for mobile-first responsive layouts.

= 1.1.0 =
*   Added Copy Link functionality with visual feedback.
*   Introduced Button Style Controls (Shape & Size).
*   Fixed WordPress 6.2+ compatibility issues.

= 1.0.0 =
*   Initial release with core sharing and subscription functionality.

== Upgrade Notice ==

= 1.3 =
Critical performance update. This version significantly reduces HTTP requests and improves PageSpeed Insight scores through asset consolidation.