<?php
/**
 * General Settings
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
			<span><?php esc_html_e( 'General Settings', 'essential-chat-support' ); ?></span>
		</h3>
	</div>

	<div class="inside">
		<table class="form-table ecs-tbl">
			<tbody>
				<tr>
					<th>
						<label for="ecs-enable-cw"><?php esc_html_e('Enable Chat Widget', 'essential-chat-support'); ?></label>
					</th>
					<td>
						<input type="checkbox" name="ecs_options[enable]" id="ecs-enable-cw" class="ecs-checkbox ecs-enable-cw" value="1" <?php checked( ecsl_get_option('enable'), 1 ); ?> /><br/>
						<span class="description"><?php esc_html_e('Check this checkbox to enable chatbox widget on front end.', 'essential-chat-support'); ?></span>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="submit" name="ecs_sett_submit" class="button button-primary right ecs-btn ecs-general-sett-submit" value="<?php esc_html_e('Save Changes', 'essential-chat-support'); ?>" />
					</td>
				</tr>
			</tbody>
		</table>
	</div><!-- end .inside -->
</div><!-- end .postbox -->