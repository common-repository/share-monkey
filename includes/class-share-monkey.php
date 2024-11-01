<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.linkedin.com/in/sabeerulhassan
 * @since      1.0.0
 *
 * @package    Share_Monkey
 * @subpackage Share_Monkey/includes
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
 * @package    Share_Monkey
 * @subpackage Share_Monkey/includes
 * @author     Hassan Jamal <hasanwow@gmail.com>
 */
class Share_Monkey {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Share_Monkey_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

    /**
     * Current plugin settings
     *
     * @since    1.0.0
     * @access   private
     * @var      array    $current_settings    Current plugin settings.
     */
	private $current_settings;
	
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

		if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
			$this->version = PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.0.0';
		}

		$this->plugin_name = 'share-monkey';

		// read the settings once use throughout the plugin

		$this->current_settings = get_option('share_monkey_settings');

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_shared_hooks();

		// loads the translation file for the current locale
		
		load_textdomain('share_monkey', SHARE_MONKEY_ROOT_PATH.'languages/' . get_locale() . '.mo');
		
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Share_Monkey_Loader. Orchestrates the hooks of the plugin.
	 * - Share_Monkey_i18n. Defines internationalization functionality.
	 * - Share_Monkey_Admin. Defines all hooks for the admin area.
	 * - Share_Monkey_Public. Defines all hooks for the public side of the site.
	 * - Share_Monkey_Shared. Defines common hooks for the public and admin.
	 * 
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
		require_once SHARE_MONKEY_ROOT_PATH . 'includes/class-share-monkey-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once SHARE_MONKEY_ROOT_PATH . 'includes/class-share-monkey-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once SHARE_MONKEY_ROOT_PATH . 'admin/class-share-monkey-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once SHARE_MONKEY_ROOT_PATH . 'public/class-share-monkey-public.php';

		/**
		 * The class responsible for defining all shared actions 
		 */
		require_once SHARE_MONKEY_ROOT_PATH . 'shared/class-share-monkey-shared.php';


		$this->loader = new Share_Monkey_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Share_Monkey_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Share_Monkey_i18n();

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

		// Instanciate the main admin class

		$plugin_admin = new Share_Monkey_Admin( $this->get_plugin_name(), $this->get_version(), $this->get_current_settings() );

		// Admin specific styles
		
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );

		// Admin specific scripts
        
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		
		// Registers the ajax function
       
        $this->loader->add_action( 'wp_ajax_share_monkey_update_settings', $plugin_admin, 'share_monkey_update_settings' );

		// Registers the settings page link on the admin menu

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'share_monkey_add_setting_page' );

		// Adds the plugin settings link under plugin name on plugins page
        
        $this->loader->add_filter( 'plugin_action_links_share-monkey/share-monkey.php' , $plugin_admin, 'share_monkey_settings_link' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		// Instanciate the main public class

		$plugin_public = new Share_Monkey_Public( $this->get_plugin_name(), $this->get_version(), $this->get_current_settings() );

		// Checks from settings where the user wants the social share icons to be displayed and adds the appropriate hooks

		$show_on_places = $this->current_settings['show_on_places'];

		if( is_array($show_on_places)) {

			foreach ($show_on_places as $place) {
				switch ($place) {
					case 'below_post_title':
						$this->loader->add_filter( 'the_content', $plugin_public, 'show_above_content' ,10,2);
					break;
					case 'floating':
						$this->loader->add_action( 'wp_footer', $plugin_public, 'show_floating' );
					break;
					case 'after_post_content':
						$this->loader->add_filter( 'the_content', $plugin_public, 'show_after_content');
					break;
					case 'inside_featured_image':
						$this->loader->add_filter( 'post_thumbnail_html', $plugin_public, 'show_inside_featured_image');
					break;
					default:
						# code...
					break;
				}
			}
			
		}

		// Adds the function to register shortcodes

		$this->loader->add_action( 'init', $plugin_public, 'register_shortcodes' );

	}

	/**
	 * Register all of the shared hooks
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_shared_hooks() {

		// Instanciate the main shared class

		$plugin_shared = new Share_Monkey_Shared( $this->get_plugin_name(), $this->get_version(), $this->get_current_settings() );
		
		// Loads the shared styles both on the admin and public 

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_shared, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_shared, 'enqueue_styles' );
		
		// Loads the shared font styles both on the admin and public 

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_shared, 'enqueue_font_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_shared, 'enqueue_font_styles' );

		// Adds custom color styles on admin page

		$this->loader->add_action( 'admin_head', $plugin_shared, 'custom_icon_color' );

		// Adds custom color styles on public pages only if custom option is selected

		if($this->get_current_settings()['icon_style']=='custom') {

			$this->loader->add_action('wp_head', $plugin_shared, 'custom_icon_color');

		}	

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
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Current plugin settings
	 *
	 * @since     1.0.0
	 * @return    string    Current plugin settings
	 */
	public function get_current_settings() {
		return $this->current_settings;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Share_Monkey_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
