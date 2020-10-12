<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://www.linkedin.com/in/mrbrazzi/
 * @since      1.0.0
 *
 * @package    Epic_Tap_Widgets
 * @subpackage Epic_Tap_Widgets/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Epic_Tap_Widgets
 * @subpackage Epic_Tap_Widgets/includes
 * @author     Alain Sanchez <luka.ghost@gmail.com>
 */
class Epic_Tap_Widgets_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'epic-tap-widgets',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
