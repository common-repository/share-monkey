<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.linkedin.com/in/sabeerulhassan
 * @since      1.0.0
 *
 * @package    Share_Monkey
 * @subpackage Share_Monkey/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Share_Monkey
 * @subpackage Share_Monkey/includes
 * @author     Hassan Jamal <hasanwow@gmail.com>
 */
class Share_Monkey_Activator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate()
    {

        $share_monkey_settings = get_option('share_monkey_settings');


        // If no options yet adds the initial settings to the database

        if (!isset($share_monkey_settings) || !is_array($share_monkey_settings)) {

            // Defaults
            $share_monkey_settings = array(
                'show_on_types' => array('post'),
                'items' => array('facebook', 'twitter', 'google', 'pinterest', 'linkedin', 'whatsapp'),
                'icon_size' => 'medium',
                'icon_style' => 'default',
                'custom_bg_color' => '#0085b6',
                'custom_text_color' => '#ffffff',
                'show_on_places' => array('floating')
            );

            update_option('share_monkey_settings', $share_monkey_settings);

        }
        
    }

}
?>