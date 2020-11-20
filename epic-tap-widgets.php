<?php

/**
 * @link              http://www.linkedin.com/in/mrbrazzi/
 * @since             1.0.0
 * @package           Epic_Tap_Widgets
 *
 * @wordpress-plugin
 * Plugin Name:       Epic Tap Widgets
 * Plugin URI:        https://www.wordpress.org/plugins/epic-tap-widgets/
 * Description:       Widgets collection for TodoApuestas's blog network.
 * Version:           1.2.2
 * Author:            Alain Sanchez <luka.ghost@gmail.com>
 * Author URI:        http://www.linkedin.com/in/mrbrazzi/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       epic-tap-widgets
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/mrbrazzi/epic-tap-widgets
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'EPIC_TAP_WIDGETS_VERSION', '1.2.2' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-epic-tap-widgets-activator.php
 */
function activate_epic_tap_widgets() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-epic-tap-widgets-activator.php';
	Epic_Tap_Widgets_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-epic-tap-widgets-deactivator.php
 */
function deactivate_epic_tap_widgets() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-epic-tap-widgets-deactivator.php';
	Epic_Tap_Widgets_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_epic_tap_widgets' );
register_deactivation_hook( __FILE__, 'deactivate_epic_tap_widgets' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-epic-tap-widgets.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_epic_tap_widgets() {

	$plugin = new Epic_Tap_Widgets();
	$plugin->run();

}
run_epic_tap_widgets();
