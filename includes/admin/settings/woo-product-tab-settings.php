<?php
/**
 * WooCommerce Settings
 *
 * @package Essential Chat Support
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$woo_enable		= ecsl_get_option('woo_enable');
$woo_tab_text	= ecsl_get_option('woo_tab_text');
$woo_tab_shrt	= ecsl_get_option('woo_tab_shrt');
?>

<div class="postbox">
	<div class="postbox-header">
		<h3 class="hndle">
			<span><?php esc_html_e( 'WooCommerce Product Tab Settings', 'essential-chat-support' ); ?></span>
		</h3>
	</div>

	<div class="inside">
		<table class="form-table ecs-tbl">
			<tbody>
				<tr>
					<th>
						<label for="ecs-woo-enable"><?php esc_html_e( 'Enable', 'essential-chat-support' ); ?></label>
					</th>
					<td>
						<input type="checkbox" name="ecs_options[woo_enable]" id="ecs-woo-enable" class="ecs-checkbox ecs-woo-enable" value="1" <?php checked( $woo_enable, 1 ); ?> /><br/>
						<span class="description"><?php esc_html_e('Check this box to enable essential chat support tab for WooCommerce product page.', 'essential-chat-support'); ?></span>
					</td>
				</tr>
				<tr>
					<th>
						<label for="ecs-woo-tab-txt"><?php esc_html_e( 'Tab Text', 'essential-chat-support' ); ?></label>
					</th>
					<td>
						<input type="text" name="ecs_options[woo_tab_text]" id="ecs-woo-tab-txt" class="regular-text ecs-woo-tab-txt" value="<?php echo esc_attr( $woo_tab_text ); ?>" /><br/>
						<span class="description"><?php esc_html_e('Enter essential chat support tab name. Default is `Essential Chat Support`.', 'essential-chat-support'); ?></span>
					</td>
				</tr>
				<tr>
					<th>
						<label for="ecs-woo-tab-shrt"><?php esc_html_e('Shortcode', 'essential-chat-support'); ?></label>
					</th>
					<td>
						<textarea name="ecs_options[woo_tab_shrt]" id="ecs-woo-tab-shrt" class="large-text ecs-woo-tab-shrt"><?php echo esc_textarea( $woo_tab_shrt ); ?></textarea><br/>
						<span class="description"><?php esc_html_e('Enter plugin shortcode to display. You can enter multiple shortcodes. e.g.', 'essential-chat-support'); ?> <span class="ecs-copy-clipboard ecs-shortcode-preview">[ecs_contact]</span></span>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="submit" name="ecs_sett_submit" class="button button-primary right ecs-btn ecs-woo-sett-submit" value="<?php esc_html_e('Save Changes', 'essential-chat-support'); ?>" />
					</td>
				</tr>
			</tbody>
		</table>
	</div><!-- end .inside -->
</div><!-- end .postbox -->