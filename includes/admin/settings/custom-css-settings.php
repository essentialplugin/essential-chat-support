<?php
/**
 * Custom CSS Settings
 *
 * @package Essential Chat Support
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div class="postbox">
	<div class="postbox-header">
		<h3 class="hndle">
			<span><?php esc_html_e( 'Custom CSS Settings', 'essential-chat-support' ); ?></span>
		</h3>
	</div>

	<div class="inside">
		<table class="form-table ecs-tbl">
			<tbody>
				<tr>
					<th scope="row">
						<label for="ecs-custom-css"><?php esc_html_e('Custom CSS', 'essential-chat-support'); ?></label>
					</th>
					<td>
						<textarea name="ecs_options[custom_css]" class="large-text ecs-custom-css ecs-code-editor" id="ecs-custom-css" rows="15"><?php echo esc_textarea( ecsl_get_option('custom_css') ); ?></textarea>
						<span class="description"><?php esc_html_e('Enter custom CSS to override plugin CSS. Sometime !important will work.', 'essential-chat-support'); ?></span>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="submit" name="ecs_sett_submit" class="button button-primary right ecs-btn ecs-post-type-sett-submit" value="<?php esc_html_e('Save Changes', 'essential-chat-support'); ?>" />
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>