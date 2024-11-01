<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.linkedin.com/in/sabeerulhassan
 * @since      1.0.0
 *
 * @package    Share_Monkey
 * @subpackage Share_Monkey/admin
 *
 */
class Share_Monkey_Admin {

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
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     * @param      array    $current_settings   Current plugin settings.
     */

    public function __construct($plugin_name, $version, $current_settings) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->current_settings = $current_settings;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles($hook) {

        if($hook!="toplevel_page_share_monkey_settings"){
            return;
        }
        
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_style($this->plugin_name.'-admin', SHARE_MONKEY_ROOT_URL . 'admin/css/share-monkey-admin.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name.'-shared', SHARE_MONKEY_ROOT_URL . 'shared/css/share-monkey-shared.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name.'-fonts',  'https://fonts.googleapis.com/css?family=Nunito:400,700', array(), $this->version, 'all');
        
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts($hook) {

        if($hook!="toplevel_page_share_monkey_settings"){
            return;
        }

        wp_enqueue_script($this->plugin_name . 'sortable', SHARE_MONKEY_ROOT_URL . 'admin/js/jquery.fn.sortable.min.js', array('jquery'), false, true);
        wp_enqueue_script($this->plugin_name . 'notify', SHARE_MONKEY_ROOT_URL . 'admin/js/notify.min.js', array('jquery'), false, true);
        wp_enqueue_script($this->plugin_name, SHARE_MONKEY_ROOT_URL . 'admin/js/share-monkey-admin.js', array('jquery', 'wp-color-picker'), $this->version, false);
        wp_localize_script($this->plugin_name,'share_monkey_ajax',array('share_monkey_ajax_nonce' => wp_create_nonce('share_monkey_ajax_nonce')));

    }

    // adds a settings link under plugin name on plugins page

    public static function share_monkey_settings_link($links) {

        $share_monkey_setting_link = sprintf('<a href="%s">%s</a>', esc_url(add_query_arg(array('page' => 'share_monkey_options'), admin_url('options-general.php'))), __('Settings', 'share_monkey'));
        array_unshift($links, $share_monkey_setting_link);
        return $links;

    }

    // regsisters the settings page for the plugin

    public function share_monkey_add_setting_page() {

        $share_monkey_settings_page = add_menu_page(__('Share Monkey', 'share_monkey'), __('Share Monkey', 'share_monkey'), apply_filters('share_monkey_settings_capability', 'manage_options'), 'share_monkey_settings', array($this, 'share_monkey_options_page'),SHARE_MONKEY_ROOT_URL.'admin/img/share_monkey_icon.png');
        
    }

    // loads the html sections containg the settings 

    public function share_monkey_options_page() {
        require_once SHARE_MONKEY_ROOT_PATH . 'admin/partials/share-monkey-admin-display.php';
        share_monkey_settings_page($this->current_settings);
    }

    // handles ajax requests for updating plugin settings

    public function share_monkey_update_settings() {

        if (check_ajax_referer('share_monkey_ajax_nonce', 'nonce')) { // Checks nonce.

            $current_settings = get_option($this->options_name);

            $new_settings = $_POST['share_monkey_settings'];

            // validate settings before update 

            $errors = $this->validate_settings($new_settings);

            if(count($errors)==0)  {
                $merged_settings = wp_parse_args( $new_settings,  $current_settings);

                // updates the options and send back the result

                $result = update_option('share_monkey_settings', $merged_settings );

                if($result) {
                    wp_send_json_success(array('result' => 1, 'message' => array(__('Settings saved successfully','share_monkey'))));
                } else {
                    wp_send_json_success(array('result' => 1, 'message' => array(__('No changes made','share_monkey'))));
                }
            } else {
                wp_send_json_error(array('result'=>0,'message' => $errors));
            }
        }  else {
            wp_send_json_error(array('result'=>0,'message' => array(__('Not allowed','share_monkey'))));
        }
        die();
    }

    public function validate_settings($settings) {
        $errors = array();

        // validates selected social networks

        if(count($settings['items'])==0)
        {
            $errors[] = __('Please select at least 1 social network','share_monkey');
        }
        else {
            foreach ($settings['items'] as $value) {
                if(!in_array($value,array('facebook', 'twitter', 'google', 'pinterest', 'linkedin', 'whatsapp')))
                {
                    $errors[] = __('Invalid social network(s) found','share_monkey');
                    break;
                }
            }
        }

        //validate selected post types
        if(count($settings['show_on_types'])==0)
        {
            $errors[] = __('Please select at least 1 post type to show the share monkey bar','share_monkey');
        }
        else{
            $args = array(
                'public' => true,
            );
            $post_types = get_post_types($args, 'objects');
            $post_type_names = array();
            foreach($post_types as $post_type) {
                if ($post_type->name != 'attachment') {
                    $post_type_names[] = $post_type->name;
                }
            }
            foreach ($settings['show_on_types'] as $value) {
                if(!in_array($value,$post_type_names))
                {
                    $errors[] = __('Invalid post type(s) found','share_monkey');
                    break;
                }
            }
        }

        //validate icon style
        if(!in_array($settings['icon_style'],array('default','custom')))
        {
            $errors[] = __('Invalid icon style found','share_monkey');
        }
        else{
            if($settings['icon_style']=='custom')
            {
                 //validate custom bg color
                 $bg_color = sanitize_hex_color($settings['custom_bg_color']);
                 if(empty($bg_color))
                {
                    $errors[] = __('Provided custom bg color is invalid','share_monkey');
                }

                 //validate custom text color
                 $text_color = sanitize_hex_color($settings['custom_text_color']);
                 if(empty($text_color))
                {
                    $errors[] = __('Provided custom text color is invalid','share_monkey');
                }
                
            }
        }

        //validates selected areas to show
        
        if(count($settings['show_on_places'])==0)
        {
            $errors[] = __('Please select at least 1 place to show the share monkey bar','share_monkey');
        }
        else {
            foreach ($settings['show_on_places'] as $value) {
                if(!in_array($value, array('below_post_title', 'floating','after_post_content','inside_featured_image'))) {
                    $errors[] = __('Invalid places to show found','share_monkey');
                    break;
                }
            }
        }

        //validate sbutton size
        if(!in_array($settings['icon_size'],array( 'small', 'medium','large'))) {
            $errors[] = __('Invalid button size found','share_monkey');
        }

        // return the validated result

        return $errors;

    }

}
