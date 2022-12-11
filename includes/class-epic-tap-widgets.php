<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.linkedin.com/in/mrbrazzi/
 * @since      1.0.0
 *
 * @package    Epic_Tap_Widgets
 * @subpackage Epic_Tap_Widgets/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Epic_Tap_Widgets
 * @subpackage Epic_Tap_Widgets/includes
 * @author     Alain Sanchez <luka.ghost@gmail.com>
 */
class Epic_Tap_Widgets {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Epic_Tap_Widgets_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $epic_tap_widgets    The string used to uniquely identify this plugin.
	 */
	protected $epic_tap_widgets;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'EPIC_TAP_WIDGETS_VERSION' ) ) {
			$this->version = EPIC_TAP_WIDGETS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->epic_tap_widgets = 'epic-tap-widgets';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Epic_Tap_Widgets_Loader. Orchestrates the hooks of the plugin.
	 * - Epic_Tap_Widgets_i18n. Defines internationalization functionality.
	 * - Epic_Tap_Widgets_Admin. Defines all hooks for the admin area.
	 * - Epic_Tap_Widgets_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-epic-tap-widgets-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-epic-tap-widgets-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-epic-tap-widgets-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-epic-tap-widgets-public.php';

        /**
         *  Load Articulos Relacionados Widget
         */
        require plugin_dir_path( dirname( __FILE__ ) ) . '/includes/widgets/widget-articulos-relacionados.php';

        /**
         *  Load Banner Promo Widget
         */
        require plugin_dir_path( dirname( __FILE__ ) ) . '/includes/widgets/widget-banner-promo.php';

        /**
         *  Load Bonos sin Deposito Widget
         */
        require plugin_dir_path( dirname( __FILE__ ) ) . '/includes/widgets/widget-bonos-sin-deposito.php';

        /**
         *  Load Casas Apuestas Widget
         */
        require plugin_dir_path( dirname( __FILE__ ) ) . '/includes/widgets/widget-casas-apuestas.php';

        /**
         *  Load Casas Apuestas Widget (Top)
         */
        require plugin_dir_path( dirname( __FILE__ ) ) . '/includes/widgets/widget-casas-apuestas-top.php';

        /**
         *  Load Chat Slim Widget
         */
        require plugin_dir_path( dirname( __FILE__ ) ) . '/includes/widgets/widget-chat-slim.php';

        /**
         *  Load Concursos Widget
         */
        require plugin_dir_path( dirname( __FILE__ ) ) . '/includes/widgets/widget-concursos.php';

        /**
         *  Load Contact Us Widget
         */
        require plugin_dir_path( dirname( __FILE__ ) ) . '/includes/widgets/widget-contact-us.php';

        /**
         *  Load Enlaces Widget
         */
        require plugin_dir_path( dirname( __FILE__ ) ) . '/includes/widgets/widget-enlaces.php';

        /**
         *  Load Facebook Like Box Widget
         */
        require plugin_dir_path( dirname( __FILE__ ) ) . '/includes/widgets/widget-facebox-like-box.php';

        /**
         *  Load Newsletter Widget
         */
        require plugin_dir_path( dirname( __FILE__ ) ) . '/includes/widgets/widget-newsletter.php';

        /**
         *  Load Nuestros Tipsters Widget
         */
        require plugin_dir_path( dirname( __FILE__ ) ) . '/includes/widgets/widget-nuestros-tipsters.php';

        /**
         *  Load Promociones Destacadas Widget
         */
        require plugin_dir_path( dirname( __FILE__ ) ) . '/includes/widgets/widget-promociones-destacadas.php';

        /**
         *  Load Random Nesw Widget
         */
        require plugin_dir_path( dirname( __FILE__ ) ) . '/includes/widgets/widget-random-news.php';

        /**
         *  Load Recent Post Widget
         */
        require plugin_dir_path( dirname( __FILE__ ) ) . '/includes/widgets/widget-recent-post.php';

        /**
         *  Load Search Form Widget
         */
        require plugin_dir_path( dirname( __FILE__ ) ) . '/includes/widgets/widget-search-form.php';

        /**
         *  Load Social Links Widget
         */
//        require plugin_dir_path( dirname( __FILE__ ) ) . '/includes/widgets/widget-social-links.php';

        /**
         *  Load Tags Cloud Widget
         */
        require plugin_dir_path( dirname( __FILE__ ) ) . '/includes/widgets/widget-tags-cloud.php';

        /**
         *  Load Tipsters Blog Widget
         */
        require plugin_dir_path( dirname( __FILE__ ) ) . '/includes/widgets/widget-tipsters-blog.php';

        /**
         *  Load Twitter Timeline Widget
         */
        require plugin_dir_path( dirname( __FILE__ ) ) . '/includes/widgets/widget-twitter-timeline.php';

		$this->loader = new Epic_Tap_Widgets_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Epic_Tap_Widgets_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Epic_Tap_Widgets_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Epic_Tap_Widgets_Admin( $this->get_epic_tap_widgets(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action('widgets_init', $plugin_admin, 'register_widget');

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Epic_Tap_Widgets_Public( $this->get_epic_tap_widgets(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_epic_tap_widgets() {
		return $this->epic_tap_widgets;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Epic_Tap_Widgets_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @register_widget('Epic_Promociones_Destacadas_Widget');since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
