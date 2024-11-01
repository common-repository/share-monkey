<?php

/**
 * The shared functionality of the plugin.
 *
 * @link       https://www.linkedin.com/in/sabeerulhassan
 * @since      1.0.0
 *
 * @package    Share_Monkey
 * @subpackage Share_Monkey/shared
 */

/**
 * The shared functionality of the plugin.
 *
 * @package    Share_Monkey
 * @subpackage Share_Monkey/shared
 * @author     Hassan Jamal <hasanwow@gmail.com>
 */

 
class Share_Monkey_Shared {

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
     * Current plugin settings
     *
     * @since    1.0.0
     * @access   private
     * @var      array    $current_settings    Current plugin settings.
     */
	private $current_settings;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 * @param      array     $current_settings   Current settings.
	 */
	
	public function __construct( $plugin_name, $version, $current_settings) {

		$this->plugin_name      = $plugin_name;
		$this->version          = $version;
		$this->current_settings = $current_settings;

	}
	
	// The social share icon styles used both on front end and admin view

	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name.'-shared', SHARE_MONKEY_ROOT_URL . 'shared/css/share-monkey-shared.css', array(), $this->version, 'all' );

	}



	// The social share custom icon styles

	public function custom_icon_color() {
		
		$custom_bg_color = $this->current_settings['custom_bg_color'];
		$custom_text_color = $this->current_settings['custom_text_color'];
			
	?>
		<style type="text/css">
			.share_monkey_custom .share_monkey_bar_item{ background-color: <?php echo $custom_bg_color; ?>; color: <?php echo $custom_text_color; ?>; }
		</style>
	<?php
	
	}



	// The social share font styles if custom color is selected

	public function enqueue_font_styles() {
	
		wp_enqueue_style( $this->plugin_name.'-socicon', SHARE_MONKEY_ROOT_URL. 'shared/socicon/style.css', array(), $this->version, 'all' );
	
	}

}
