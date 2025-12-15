=== FeatherShare ===
Contributors: reynovchristian
Tags: social sharing, subscription form, facebook, twitter, linkedin, whatsapp, telegram, messenger, email, reddit, threads
Requires at least: 5.0
Tested up to: 6.5
Stable tag: 1.1.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A clean, modular WordPress plugin that adds social sharing buttons and subscription functionality to your content.

== Description ==

FeatherShare is a lightweight, customizable WordPress plugin that enhances your content by adding social sharing buttons and a subscription form. With support for 9 major social platforms, your visitors can easily share your content, and with the subscription form, you can grow your audience.

= Features =

* **Social Sharing Buttons**: Add sharing buttons for Twitter, Facebook, LinkedIn, Threads, WhatsApp, Telegram, Messenger, Email, and Reddit
* **Copy Link Button**: Let users quickly copy the post URL to their clipboard with visual feedback
* **Button Style Control**: Customize button appearance with shape (circle, square, rounded), size (small, medium, large), and optional text labels
* **Subscription Form**: Collect email addresses with an AJAX-powered subscription form
* **Admin Settings**: Configure the Messenger button, button appearance, and subscription form settings
* **Lightweight & Fast**: Only loads assets when needed for optimal performance
* **Secure**: Implements WordPress security best practices including nonces and data validation
* **Accessible**: Full accessibility support with ARIA labels and screen reader text
* **Responsive**: Works on all device sizes

= Shortcodes =

* `[feathershare_subscribe]` - Displays the subscription form

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/feathershare` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings->FeatherShare screen to configure the plugin
4. Add social sharing buttons appear automatically on single post pages
5. Use the shortcode `[feathershare_subscribe]` to display the subscription form anywhere

== Frequently Asked Questions ==

= How do I enable the Messenger button? =

Go to Settings > FeatherShare in your WordPress admin panel. Check "Enable Messenger Share Button" and enter your Facebook App ID.

= Where do the social sharing buttons appear? =

The social sharing buttons automatically appear at the end of single post content.

= How do I display the subscription form? =

Use the shortcode `[feathershare_subscribe]` in any post, page, or widget that supports shortcodes.

= How do I customize the button appearance? =

Go to Settings > FeatherShare and find the "Button Appearance" section. You can choose button shape (circle, square, rounded), size (small, medium, large), and toggle text labels.

== Screenshots ==

1. Social sharing buttons displayed at the end of a post
2. Subscription form with responsive design
3. Admin settings panel for configuring the Messenger button
4. Button appearance settings with style and size options

== Changelog ==

= 1.1.0 =
* Added Copy Link button with clipboard functionality and visual feedback
* Added Button Style Control: shape (circle, square, rounded), size (small, medium, large)
* Added option to show/hide text labels on buttons
* Improved accessibility with ARIA labels
* Added security attributes (rel="noopener noreferrer") to share links
* Fixed deprecated get_page_by_title() for WordPress 6.2+ compatibility
* Fixed asset path issues
* Added CSS for Pinterest, VK, and XING networks

= 1.0.0 =
* Initial release
* Social sharing buttons for 9 platforms
* AJAX-powered subscription form
* Admin settings for Messenger integration

== Upgrade Notice ==

= 1.1.0 =
New features: Copy Link button, customizable button styles, and improved WordPress 6.2+ compatibility.