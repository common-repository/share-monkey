<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.linkedin.com/in/sabeerulhassan
 * @since      1.0.0
 *
 * @package    Share_Monkey
 * @subpackage Share_Monkey/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Share_Monkey
 * @subpackage Share_Monkey/public
 * @author     Hassan Jamal <hasanwow@gmail.com>
 */
class Share_Monkey_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Current settings
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $current_settings    Current settings saved on the db.
	 */
	private $current_settings;

	/**
	 * Public view 
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $public_view    Holds instance of public view class
	 */
	private $public_view;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 * @param      array     $current_settings   Current settings.
	 * 
	 */
	public function __construct( $plugin_name, $version, $current_settings) {

		$this->plugin_name      = $plugin_name;
		$this->version          = $version;
		$this->current_settings = $current_settings;

		/**
		 * The class responsible for printing the share icons bar on front end
		 */
		require_once SHARE_MONKEY_ROOT_PATH . 'public/partials/class-share-monkey-public-display.php';

		// Instanciate the public view class
		
		$this->public_view = new Share_Monkey_Public_View($current_settings);

	}

	

	// Appends the social icons bar above the content below the title

	public function show_above_content($content)
	{
		return $this->public_view->generate_share_monkey_bar("share_monkey_above_content").$content;
	}



	// Appends the social icons bar after the content
	
	public function show_after_content($content)
	{
		return $content.$this->public_view->generate_share_monkey_bar("share_monkey_below_title");
	}



	// Adds the social icons bar to the footer to make it sticky on the left side

	public function show_floating()
	{
		echo $this->public_view->generate_share_monkey_bar("share_monkey_floating");
	}



	// Appends the social icons bar after the featured image if present
	
	public function show_inside_featured_image($html)
	{
		return $html.$this->public_view->generate_share_monkey_bar("share_monkey_inside_featured");
	}



	// Registers the shortcode so that the social icons bar can be included inside content or widgets too

	public function register_shortcodes()
	{
		add_shortcode( 'share_monkey',  array($this->public_view, 'generate_share_monkey_bar' ));
	}

}
