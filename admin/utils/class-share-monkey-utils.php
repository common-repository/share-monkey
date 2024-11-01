<?php
/**
 * Utility functions to be used on admin view
 *
 * @link       https://www.linkedin.com/in/sabeerulhassan
 * @since      1.0.0
 *
 * @package    Share_Monkey
 * @subpackage Share_Monkey/admin/utils
 */
class Share_Monkey_Utils

{
	/**
	 * Populates settings fields with multiple checkboxes
	 *
	 * @since    1.0.0
	 */
	public static function settings_field_multiple_checkbox($name, $title, $description, $checkbox_items, $current_values) {

		?>
		<div class="share_monkey_field">
			<div class="share_monkey_left">
				<h3><?php esc_html_e($title,'share_monkey'); ?></h3>
				<p class="share_monkey_description">
					<?php esc_html_e($description,'share_monkey'); ?>
				</p>
			</div>
			<div class="share_monkey_right">
			<?php
				foreach($checkbox_items as $checkbox_key => $checkbox_value) {
					if (in_array($checkbox_value['id'], $current_values)) {
						$checked = 'checked="checked"';
					} else {
						$checked = '';
					}
			?>
					<label for="<?php echo 'share_monkey_settings[' . $name . '][' . $checkbox_value['id'] . ']'; ?>">
						<input 
							type="checkbox" 
							name="<?php echo 'share_monkey_settings[' . $name . '][]'; ?>" 
							id="<?php echo 'share_monkey_settings[' . $name . '][' . $checkbox_value['id'] . ']'; ?>" 
							value="<?php echo $checkbox_value['id']; ?>" 
							<?php echo $checked; ?> />
						<span class="share_monkey_label"><?php echo $checkbox_value['label']; ?></span>
					</label>
			<?php } ?>
			</div>
				
		</div>

			<?php

	}

	/**
	 * Populates settings fields with radio boxes
	 *
	 * @since    1.0.0
	 */
	public static

	function settings_field_radiobox($name, $title, $description, $radiobox_items, $current_value) {
		
		?>
		<div class="share_monkey_field">

			<div class="share_monkey_left">
				<h3><?php esc_html_e($title,'share_monkey'); ?></h3>
				<p class="share_monkey_description"><?php esc_html_e($description,'share_monkey'); ?></p>
			</div>

			<div class="share_monkey_right">
			<?php 
				foreach($radiobox_items as $radiobox_key => $radiobox_value) {
					if ($radiobox_value['id'] == $current_value) {
						$checked = 'checked="checked"';
					} else {
						$checked = '';
					}
			?>
					<label for="<?php echo 'share_monkey_settings[' . $name . '][' . $radiobox_value['id'] . ']'; ?>">
						<input 
							type="radio" 
							name="<?php echo 'share_monkey_settings[' . $name . ']'; ?>" 
							id="<?php echo 'share_monkey_settings[' . $name . '][' . $radiobox_value['id'] . ']'; ?>" 
							value="<?php echo $radiobox_value['id']; ?>" 
							<?php echo $checked; ?> />
						<span class="share_monkey_label"><?php echo $radiobox_value['label']; ?></span>
					</label>
			<?php
				}
			?>
			</div>

		</div>

		<?php

	}

	/**
	 * Populates settings fields with text input
	 *
	 * @since    1.0.0
	 */
	public static function settings_field_input_text($name, $title, $description, $current_value) {
	?>
		<div class="share_monkey_field">
			<div class="share_monkey_left">
				<h3><?php esc_html_e($title,'share_monkey'); ?></h3>
				<p class="share_monkey_description"><?php esc_html_e($description,'share_monkey'); ?></p>
			</div>
			<div class="share_monkey_right">
				<input 
					type="text" 
					name="<?php echo 'share_monkey_settings[' . $name . ']'; ?>" 
					id="<?php echo 'share_monkey_settings[' . $name . ']'; ?>" 
					value="<?php echo $current_value; ?>" />
			</div>
		</div>
		
		<?php
	}

}

?>
