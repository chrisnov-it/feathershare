<?php
/**
 * Plugin Name:       FeatherShare
 * Plugin URI:        https://chrisnov.com/plugins/feathershare/
 * Description:       A clean, modular, and object-oriented plugin following WordPress coding standards.
 * Version:           1.2
 * Requires at least: 5.0
 * Requires PHP:      7.4
 * Author:            Reynov Christian
 * Author URI:        https://chrisnov.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       feathershare
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Current plugin version.
 */
define( 'FEATHERSHARE_VERSION', '1.2' );

/**
 * Define constants for the plugin's main file, directory path, and URL.
 */
define( 'FEATHERSHARE_FILE', __FILE__ );
define( 'FEATHERSHARE_DIR', plugin_dir_path( __FILE__ ) );
define( 'FEATHERSHARE_URL', plugin_dir_url( __FILE__ ) );

/**
 * Runs during plugin activation.
 *
 * @since 1.0.0
 * @return void
 */
function activate_feathershare() {
	require_once FEATHERSHARE_DIR . 'includes/class-feathershare-activator.php';
	FeatherShare_Activator::activate();
}

/**
 * Runs during plugin deactivation.
 *
 * @since 1.0.0
 * @return void
 */
function deactivate_feathershare() {
	require_once FEATHERSHARE_DIR . 'includes/class-feathershare-deactivator.php';
	FeatherShare_Deactivator::deactivate();
}

register_activation_hook( FEATHERSHARE_FILE, 'activate_feathershare' );
register_deactivation_hook( FEATHERSHARE_FILE, 'deactivate_feathershare' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require FEATHERSHARE_DIR . 'includes/class-feathershare.php';

/**
 * Begins execution of the plugin.
 *
 * @since 1.0.0
 * @return void
 */
function run_feathershare() {
	$plugin = new FeatherShare();
	$plugin->run();
}

run_feathershare();
