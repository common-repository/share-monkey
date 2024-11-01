<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.linkedin.com/in/sabeerulhassan
 * @since      1.0.0
 *
 * @package    Share_Monkey
 * @subpackage Share_Monkey/admin/partials
 */
?>
<?php

// Loading utility class
require_once SHARE_MONKEY_ROOT_PATH.'admin/utils/class-share-monkey-utils.php';

// Prints the settings page
function share_monkey_settings_page($share_monkey_settings) {

?>
	<div class="share_monkey_admin_wrapper">

		<h2>
			<?php esc_html_e('Share Monkey Settings', 'share_monkey'); ?>
		</h2>

		<p>
			<?php esc_html_e('Customize how & which share icons will be displayed.', 'share_monkey'); ?>
		</p>

		<div class="share_monkey_settings_field">
        	<button class="share_monkey_submit_btn"><?php esc_html_e('Save Changes', 'share_monkey'); ?></button>
		</div>

		<!-- Settings field for social icons -->
        <div class="share_monkey_settings_field share_monkey_social_icons">
            <div class="share_monkey_social_icons_list_wrap">

				<!-- Iterating and printing all the available social icons -->
				<div class="share_monkey_bar_wrap share_monkey_left">
					<h3>
						<?php esc_html_e('Available social networks', 'share_monkey'); ?>
					</h3>
					<ul id="share_monkey_social_icons_all" class="share_monkey_bar share_monkey_default">
						<?php
						$items = array('facebook','twitter','google','pinterest','linkedin','whatsapp');
						foreach($items as $item): ?>
							<li class="share_monkey_bar_item share_monkey_bar_item_<?php echo $item; ?>  socicon-<?php echo $item; ?>" data-id="<?php echo $item; ?>">
								<span class="share_monkey_remove">x</span>
							</li>
						<?php endforeach;
						?>	
					</ul>
					<p>
						<?php _e('Drag &amp; drop the icons you want displayed in the pages, to the <strong>icons at right</strong>', 'share_monkey'); ?>
					</p>
				</div>
				<div class="share_monkey_bar_wrap share_monkey_right">
					<?php

					// Displaying currently selected social networks along with the selected styles

					$size = 'share_monkey_'.$share_monkey_settings['icon_size'];

					if($share_monkey_settings['icon_style']=='custom') {
						$style = 'share_monkey_custom ';
					} else {
						$style = 'share_monkey_default';
					}

					?>
					<h3>
						<?php esc_html_e('Selected social networks', 'share_monkey'); ?>
					</h3>
					<ul id="share_monkey_social_icons_selected" class="share_monkey_bar <?php echo $size.' '.$style; ?>">
						<?php
						foreach($share_monkey_settings['items'] as $item): ?>
							<li class="share_monkey_bar_item share_monkey_bar_item_<?php echo $item; ?> socicon-<?php echo $item; ?>" data-id="<?php echo $item; ?>">
								<span class="share_monkey_remove">x</span>
							</li>
						<?php endforeach;
						?>
					</ul>
					<p>
						* <?php esc_html_e('These icons will be displayed on your pages.', 'share_monkey'); ?>
					</p>
					<p>
						* <?php _e('This is a <strong>live preview</strong> of how the icons will appear.', 'share_monkey'); ?>
					</p>
					<p>
						* <?php esc_html_e('Mouse over and click the \'x\' to remove icons.', 'share_monkey'); ?>
					</p>
					
				</div>
				
            </div>
            <p class="share_monkey_error_min share_monkey_error_text">
				<?php esc_html_e('Please select at least 1 social network', 'share_monkey'); ?>
            </p>
		</div>
		
		<!-- Rest of the settings fields goes inside a form tag -->

		<form action="javascript:;" id="share_monkey_admin_form">

			<!-- Settings field for selecting post types to show the sharing icons -->

            <div class="share_monkey_settings_field share_monkey_show_on_types">
				<?php
				
				// gets currently registered post types

				$args = array(
					'public' => true,
				);
				$post_types = get_post_types($args, 'objects');
				$checkbox_items = array();
				foreach($post_types as $post_type) {
					if ($post_type->name != 'attachment') {
						$checkbox_items[] = array(
							'id' => $post_type->name,
							'label' => $post_type->label
						);
					}
				}

				// populates checkboxes for post type selection

				Share_Monkey_Utils::settings_field_multiple_checkbox('show_on_types', 'Post Types','Show share icons on these post types', $checkbox_items, $share_monkey_settings['show_on_types']);
				?>
				<p class="share_monkey_error_min share_monkey_error_text">
					<?php esc_html_e('Please select at least 1 post type to show the share monkey bar', 'share_monkey'); ?>
                </p>
			</div>

			<!-- Settings field for selecting icon size -->

    		<div class="share_monkey_settings_field">
				<?php
				
				// available icon sizes

				$radiobox_items = array(
					array(
						'id' => 'small',
						'label' => 'Small'
					) ,
					array(
						'id' => 'medium',
						'label' => 'Medium'
					) ,
					array(
						'id' => 'large',
						'label' => 'Large'
					)
				);

				// populates radio boxes for icon size selection

				Share_Monkey_Utils::settings_field_radiobox('icon_size', 'Icon Size', 'Icon size', $radiobox_items, $share_monkey_settings['icon_size']);
				?>
			</div>
			
			<!-- Settings field for icon style -->

    		<div class="share_monkey_settings_field share_monkey_icon_style">
        		<?php
				$radiobox_items = array(
					array(
						'id' => 'default',
						'label' => 'Default Style'
					) ,
					array(
						'id' => 'custom',
						'label' => 'Custom Color'
					)
				);

				// populates radio boxes for icon style selection

				Share_Monkey_Utils::settings_field_radiobox('icon_style', 'Icon Style', 'Select icon style (default colors or custom color for all icons)', $radiobox_items, $share_monkey_settings['icon_style']);
				?>
					<!-- If custom style selected shows background color selection  -->

					<div class="share_monkey_settings_field share_monkey_custom_bg_color share_monkey_color_field" <?php if($share_monkey_settings['icon_style']=='default') echo ' style="display:none"'; ?>>
						<?php
							Share_Monkey_Utils::settings_field_input_text('custom_bg_color', 'Icon Background', 'Select custom bg color for all icons', $share_monkey_settings['custom_bg_color']);
						?>
						<p class="share_monkey_error_color share_monkey_error_text">
							<?php esc_html_e('Please choose or enter a valid color code', 'share_monkey'); ?>
						</p>
					</div>

					<!-- If custom style selected shows text color selection  -->
					
					<div class="share_monkey_settings_field share_monkey_custom_text_color share_monkey_color_field" <?php if($share_monkey_settings['icon_style']=='default') echo ' style="display:none"'; ?>>
						<?php
							Share_Monkey_Utils::settings_field_input_text('custom_text_color', 'Icon Color', 'Select custom text color for all icons', $share_monkey_settings['custom_text_color']);
						?>
						<p class="share_monkey_error_color share_monkey_error_text">
							<?php esc_html_e('Please choose or enter a valid color code', 'share_monkey'); ?>
						</p>
					</div>
			</div>
			
			<!-- Settings field for selecting page areas to show sharing icons -->
    
    		<div class="share_monkey_settings_field share_monkey_show_on_places">
				<?php
				$checkbox_items = array(
					array(
						'id' => 'below_post_title',
						'label' => 'Below the post title'
					) ,
					array(
						'id' => 'floating',
						'label' => 'Floating on the left edge of page'
					) ,
					array(
						'id' => 'after_post_content',
						'label' => 'After the post content'
					) ,
					array(
						'id' => 'inside_featured_image',
						'label' => 'Inside the featured image'
					)
				);

				// populate checkboxes for area selection

				Share_Monkey_Utils::settings_field_multiple_checkbox('show_on_places', 'Areas', 'Show the share icons on these places', $checkbox_items, $share_monkey_settings['show_on_places']);
				?>
				<p class="share_monkey_error_min share_monkey_error_text">
					<?php esc_html_e('Please select at least 1 place to show the share monkey bar', 'share_monkey'); ?>
				</p>
			</div>

			<!-- Takes all the selected social networks and put inside the following div as hidden inputs -->
			
			<div id="share_monkey_selected_items"></div>

		</form>
		
		<!-- End of form, ajax submit button -->

    	<div class="share_monkey_settings_field">
        	<button class="share_monkey_submit_btn"><?php esc_html_e('Save Changes', 'share_monkey'); ?></button>
		</div>
		
	</div>

	<?php

}
