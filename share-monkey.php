<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.linkedin.com/in/sabeerulhassan
 * @since             1.0.0
 * @package           Share_Monkey
 *
 * @wordpress-plugin
 * Plugin Name:       Share Monkey
 * Plugin URI:        https://www.wordpress.org/plugins/share-monkey
 * Description:       Adding share icons to your website is fun with Share Monkey. It’s simple and easily customizable.
 * Version:           1.0.0
 * Author:            Hassan Jamal
 * Author URI:        https://www.linkedin.com/in/sabeerulhassan
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       share_monkey
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/* defines the Root path of the plugin */

define('SHARE_MONKEY_ROOT_PATH',    dirname(__FILE__).'/');


/* defines the Root URL of the plugin */

define('SHARE_MONKEY_ROOT_URL',    plugin_dir_url( __FILE__ ).'/');

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-share-monkey-activator.php
 */
function activate_share_monkey() {
	require_once SHARE_MONKEY_ROOT_PATH . 'includes/class-share-monkey-activator.php';
	Share_Monkey_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-share-monkey-deactivator.php
 */
function deactivate_share_monkey() {
	require_once SHARE_MONKEY_ROOT_PATH . 'includes/class-share-monkey-deactivator.php';
	Share_Monkey_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_share_monkey' );
register_deactivation_hook( __FILE__, 'deactivate_share_monkey' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require_once SHARE_MONKEY_ROOT_PATH . 'includes/class-share-monkey.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_share_monkey() {

	$plugin = new Share_Monkey();
	$plugin->run();

}

// Bootstrap the plugin

run_share_monkey();
