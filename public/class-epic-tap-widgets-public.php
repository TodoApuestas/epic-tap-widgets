<?php

/**
 * @link       http://www.linkedin.com/in/mrbrazzi/
 * @since      1.0.0
 *
 * @package    Epic_Tap_Widgets
 * @subpackage Epic_Tap_Widgets/public
 */

/**
 * @package    Epic_Tap_Widgets
 * @subpackage Epic_Tap_Widgets/public
 * @author     Alain Sanchez <luka.ghost@gmail.com>
 */
class Epic_Tap_Widgets_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $epic_tap_widgets    The ID of this plugin.
	 */
	private $epic_tap_widgets;

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
	 * @param      string    $epic_tap_widgets       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $epic_tap_widgets, $version ) {

		$this->epic_tap_widgets = $epic_tap_widgets;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Epic_Tap_Widgets_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Epic_Tap_Widgets_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->epic_tap_widgets, plugin_dir_url( __FILE__ ) . 'css/epic-tap-widgets-public.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Epic_Tap_Widgets_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Epic_Tap_Widgets_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

//		wp_enqueue_script( $this->epic_tap_widgets, plugin_dir_url( __FILE__ ) . 'js/epic-tap-widgets-public.js', array( 'jquery' ), $this->version, false );

	}

}
