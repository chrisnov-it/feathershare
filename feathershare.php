<?php
/**
 * Plugin Name:       FeatherShare
 * Plugin URI:        https://chrisnov.com/plugins/feathershare/
 * Description:       A clean, modular, and object-oriented plugin following WordPress coding standards.
 * Version:           1.1.0
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
if (!defined('WPINC')) {
    die;
}

/**
 * Current plugin version.
 */
define('FEATHERSHARE_VERSION', '1.1.0');

/**
 * Define constants for the plugin's main file, directory path, and URL.
 */
define('FEATHERSHARE_FILE', __FILE__);
define('FEATHERSHARE_DIR', plugin_dir_path(__FILE__));
define('FEATHERSHARE_URL', plugin_dir_url(__FILE__));

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-feathershare-activator.php
 */
function activate_feathershare() {
    require_once FEATHERSHARE_DIR . 'includes/class-feathershare-activator.php';
    FeatherShare_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-feathershare-deactivator.php
 */
function deactivate_feathershare() {
    require_once FEATHERSHARE_DIR . 'includes/class-feathershare-deactivator.php';
    FeatherShare_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_feathershare');
register_deactivation_hook(__FILE__, 'deactivate_feathershare');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require FEATHERSHARE_DIR . 'includes/class-feathershare.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_feathershare() {
    $plugin = new FeatherShare();
    $plugin->run();
}

run_feathershare();