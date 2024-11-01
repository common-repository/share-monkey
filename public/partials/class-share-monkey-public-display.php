<?php

/**
 * The public-facing view of the plugin.
 *
 * @link       https://www.linkedin.com/in/sabeerulhassan
 * @since      1.0.0
 *
 * @package    Share_Monkey
 * @subpackage Share_Monkey/public
 *
 * @package    Share_Monkey
 * @subpackage Share_Monkey/public
 * @author     Hassan Jamal <hasanwow@gmail.com>
 */

class Share_Monkey_Public_View {

	/**
	 * Current settings
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $current_settings    Current settings saved on the db.
	 */
	private $current_settings;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      array     $current_settings   Current settings.
	 * 
	 */
	public function __construct( $current_settings) {

        $this->current_settings = $current_settings;

	}

	
    public function generate_share_monkey_bar($class_name) {
            
        /* If $class_name is not empty (i.e. not called by shortcode) 
        and the current page's post type is not saved in the settings return empty string. */

        if(!empty($class_name) && !in_array(get_post_type(),$this->current_settings['show_on_types'])) {
            return "";
        }

        // If $class_name is empty, it's called by shortcode

        if(empty($class_name)) {
            $class_name = 'share_monkey_inside_content';
        }
        
        // Reads the settings and adds appropriate styles to the bar for css styling

        $size    = 'share_monkey_'.$this->current_settings['icon_size'];
        $style   = '';
        $socicon = false;

        if($this->current_settings['icon_style']=='custom') {
            $style = 'share_monkey_custom ';
            $socicon = true;
        } else {
            $style = 'share_monkey_default';
        }

        // Preparing data to generate social share links

        global $post;

        $title = get_the_title($post);
        $link = get_the_permalink($post);
        $media = get_the_post_thumbnail_url($post);

        // The list containing social share icons

        $bar_html = "<ul class='share_monkey_bar $class_name $size $style '>";

        // Iterates though all the social networks in saved in the settings and add it as an li tag

        foreach ($this->current_settings['items'] as $item) {
            
            $bar_html .= '<li class="share_monkey_bar_item share_monkey_bar_item_'.$item.($socicon? " socicon-$item":"").'">';
            $bar_html .= '<a target="_blank" href="'.$this->link_for_network($item,$title,$link,$media).'">';
            $bar_html .= '</a>';
            $bar_html .= '</li>';
            
        }
        $bar_html .= '</ul>';

        // returns the generated html
        
        return $bar_html;

    }

    // Returns the share url for various social networks

    private function link_for_network($item,$title,$link,$media='') {

        $link = urlencode($link);
        $title = urlencode($title);
        $media = urlencode($media);
        
        switch ( $item ) {
            case 'facebook':
                $url = "https://www.facebook.com/sharer/sharer.php?u=$link";
            break;
            case 'twitter':
                $url = "https://twitter.com/intent/tweet?text=$title&url=$link";
            break;
            case 'google':
                $url = "https://plus.google.com/share?url=$link";
            break;
            case 'pinterest':
                $url = "https://pinterest.com/pin/create/button/?url=$link&media=$media&description=$title";
            break;
            case 'linkedin':
                $url = "https://www.linkedin.com/shareArticle?mini=true&url=$link";
            break;
            case 'whatsapp':
                $url = "https://wa.me/?text=$title $link";
            break;
        }
        
        return $url;
        
    }

}