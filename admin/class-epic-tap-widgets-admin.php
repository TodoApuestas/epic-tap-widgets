<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.linkedin.com/in/mrbrazzi/
 * @since      1.0.0
 *
 * @package    Epic_Tap_Widgets
 * @subpackage Epic_Tap_Widgets/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Epic_Tap_Widgets
 * @subpackage Epic_Tap_Widgets/admin
 * @author     Alain Sanchez <luka.ghost@gmail.com>
 */
class Epic_Tap_Widgets_Admin {

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
	 * @param      string    $epic_tap_widgets       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $epic_tap_widgets, $version ) {

		$this->epic_tap_widgets = $epic_tap_widgets;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

//		wp_enqueue_style( $this->epic_tap_widgets, plugin_dir_url( __FILE__ ) . 'css/epic-tap-widgets-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

//		wp_enqueue_script( $this->epic_tap_widgets, plugin_dir_url( __FILE__ ) . 'js/epic-tap-widgets-admin.js', array( 'jquery' ), $this->version, false );

	}

    public function register_widget() {
        register_widget('Epic_Articulos_Relacionados_Widget');
        register_widget('Epic_Banner_Promo_Widget');
        register_widget('Epic_Bonos_Sin_Deposito_Widget');
        register_widget('Epic_Casas_Apuestas_Widget');
        register_widget('Epic_Chat_Slim_Widget');
        unregister_widget("Epic_ConcursosTAP_Widget");
        register_widget('Epic_Concursos_Widget');
        unregister_widget('WP_Widget_Search');
        register_widget('Epic_Contact_Us_Widget');
        unregister_widget('WP_Widget_Links');
        register_widget('Epic_Enlaces_Widget');
        register_widget('Epic_FB_Like_Box_Widget');
        register_widget('Epic_Newsletter_Widget');
        register_widget('Epic_Nuestros_Tipsters_Widget');
        register_widget('Epic_Promociones_Destacadas_Widget');
        register_widget('Epic_Random_News_Widget');
        unregister_widget('WP_Widget_Recent_Posts');
        register_widget('Epic_Recent_Posts_Widget');
        unregister_widget('WP_Widget_Search');
        register_widget('Epic_Search_Form_Widget');
        register_widget('Epic_Social_Links_Widget');
        unregister_widget('WP_Widget_Tag_Cloud');
        register_widget('Epic_Tags_Cloud_Widget');
        register_widget('Epic_Tipsters_Blog_Widget');
        register_widget('Epic_Twitter_Timeline_Widget');
    }

}
