<?php
/**
 * Handles Contacts metabox HTML
 *
 * @package Essential Chat Support
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;

// Taking some variable
$prefix			= ECSL_META_PREFIX;
$country_array	= ecsl_country_phone_codes();
$status			= get_post_meta( $post->ID, $prefix.'status', true );
$type			= get_post_meta( $post->ID, $prefix.'type', true );
$type			= ! empty( $type ) ? $type : 'agent';

// Taking agent meta
$agent					= get_post_meta( $post->ID, $prefix.'agent', true );
$country_code			= isset( $agent['country_code'] )			? $agent['country_code']		: '';
$whatsapp_number		= isset( $agent['whatsapp_number'] )		? $agent['whatsapp_number']		: '';
$designation			= isset( $agent['designation'] )			? $agent['designation']			: '';
$availability_status	= isset( $agent['availability_status'] )	? $agent['availability_status']	: '';
$custom_message			= isset( $agent['custom_message'] )			? $agent['custom_message']		: __("Hello,\nI have visited your site and I need on this {title} - {url}", 'essential-chat-support');

// Taking group meta
$group				= get_post_meta( $post->ID, $prefix.'group', true );
$group_id			= isset( $group['id'] )				? $group['id']			: '';
$group_description	= isset( $group['description'] )	? $group['description']	: '';
?>

<table class="form-table ecs-tbl">
	<tbody>
		<tr>
			<th>
				<label for="ecs-type"><?php esc_html_e('Contact Type', 'essential-chat-support'); ?></label>
			</th>
			<td>
				<select name="<?php echo esc_attr( $prefix ); ?>type" class="ecs-select ecs-show-hide ecs-type" id="ecs-type">
					<option value="agent" <?php selected( $type, 'agent' ); ?>><?php esc_html_e('Agent', 'essential-chat-support'); ?></option>
					<option value="group" <?php selected( $type, 'group' ); ?>><?php esc_html_e('Group', 'essential-chat-support'); ?></option>
				</select><br/>
				<span class="description"><?php esc_html_e('Select contact type.', 'essential-chat-support'); ?></span>
			</td>
		</tr>
		<tr>
			<th>
				<label for="ecs-status"><?php esc_html_e('Status', 'essential-chat-support'); ?></label>
			</th>
			<td>
				<select name="<?php echo esc_attr( $prefix ); ?>status" class="ecs-select ecs-status" id="ecs-status">
					<option value="1" <?php selected( $status, 1 ); ?>><?php esc_html_e('Active', 'essential-chat-support'); ?></option>
					<option value="0" <?php selected( $status, 0 ); ?>><?php esc_html_e('Deactive', 'essential-chat-support'); ?></option>
				</select><br/>
				<span class="description"><?php esc_html_e('Select contact status.', 'essential-chat-support'); ?></span>
			</td>
		</tr>

		<!-- Start - Agent Type Settings -->
		<tr class="ecs-show-hide-row ecs-show-if-agent" style="<?php if( $type == 'group' ) { echo 'display: none;'; } ?>">
			<td colspan="2" class="ecs-no-padding">
				<table class="form-table">
					<tr>
						<th>
							<label for="ecs-whatapp-number"><?php esc_html_e('WhatsApp Number', 'essential-chat-support'); ?></label>
						</th>
						<td>
							<select name="<?php echo esc_attr( $prefix ); ?>agent[country_code]" class="ecs-select2 ecs-country-code" id="ecs-country-code" data-placeholder="<?php esc_html_e('Select Country Code', 'essential-chat-support'); ?>">
								<option value=""></option>
								<?php if( ! empty( $country_array ) ) {
								foreach ($country_array as $country_key => $country_value) { ?>
									<option value="<?php echo esc_attr( $country_key ); ?>" <?php selected( $country_code, $country_key ); ?>><?php echo esc_html( $country_value ); ?></option>
								<?php } } ?>
							</select>
							<input type="text" class="ecs-text regular-text ecs-number-input ecs-whatapp-number" id="ecs-whatapp-number" name="<?php echo esc_attr( $prefix ); ?>agent[whatsapp_number]" value="<?php echo esc_attr( $whatsapp_number ); ?>" /><br/>
							<span class="description"><?php esc_html_e('Select country code and enter WhatsApp number.', 'essential-chat-support')?></span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="ecs-designation"><?php esc_html_e('Designation', 'essential-chat-support'); ?></label>
						</th>
						<td>
							<input type="text" name="<?php echo esc_attr( $prefix ); ?>agent[designation]" value="<?php echo esc_attr( $designation ); ?>" class="ecs-text large-text ecs-designation" id="ecs-designation" /><br/>
							<span class="description"><?php esc_html_e('Enter agent designation. e.g. Customer Support','essential-chat-support')?></span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="ecs-availability-status"><?php esc_html_e('Availability Status', 'essential-chat-support'); ?></label>
						</th>
						<td>
							<select name="<?php echo esc_attr( $prefix ); ?>agent[availability_status]" class="ecs-select ecs-availability-status ecs-show-hide" id="ecs-availability-status" data-prefix="ecs-aval-status">
								<option value="online" <?php selected( $availability_status, 'online' ); ?>><?php esc_html_e('Always Available Online', 'essential-chat-support'); ?></option>
							</select><br/>
							<span class="description"><?php esc_html_e( 'Select agent availability status.', 'essential-chat-support' ); ?></span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="ecs-custom-msg"><?php esc_html_e('Custom Message', 'essential-chat-support'); ?></label>
						</th>
						<td>
							<textarea name="<?php echo esc_attr( $prefix ); ?>agent[custom_message]" class="ecs-textarea large-text ecs-custom-msg" id="ecs-custom-msg"><?php echo esc_textarea( $custom_message ); ?></textarea><br/>
							<span class="description"><?php esc_html_e( 'Enter WhatsApp predefined message e.g. Hello, I have visited your site and I need help from you.', 'essential-chat-support' ); ?></span><br/>
							<span class="description"><?php echo sprintf( __( 'Format your messages from <a href="%s" target="_blank">here</a>.', 'essential-chat-support'), 'https://faq.whatsapp.com/general/chats/how-to-format-your-messages/'); ?></span><br/>
							<div class="ecs-code-tag-wrap">
								<code class="ecs-copy-clipboard">{ID}</code> - <span class="description"><?php esc_html_e('Display current page ID.', 'essential-chat-support'); ?></span><br/>
								<code class="ecs-copy-clipboard">{title}</code> - <span class="description"><?php esc_html_e('Display current page title.', 'essential-chat-support'); ?></span><br/>
								<code class="ecs-copy-clipboard">{slug}</code> - <span class="description"><?php esc_html_e('Display current page slug.', 'essential-chat-support'); ?></span><br/>
								<code class="ecs-copy-clipboard">{url}</code> - <span class="description"><?php esc_html_e('Display current page URL.', 'essential-chat-support'); ?></span><br/>
								<?php if( class_exists('WooCommerce') ) { ?>
									<code class="ecs-copy-clipboard">{price}</code> - <span class="description"><?php esc_html_e('Display product current price.', 'essential-chat-support'); ?></span><br/>
									<code class="ecs-copy-clipboard">{regular_price}</code> - <span class="description"><?php esc_html_e('Display product regular price.', 'essential-chat-support'); ?></span><br/>
									<code class="ecs-copy-clipboard">{sku}</code> - <span class="description"><?php esc_html_e('Display product sku.', 'essential-chat-support'); ?></span><br/>
								<?php } ?>
							</div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<!-- End - Agent Type Settings -->

		<!-- Start - Group Type Settings -->
		<tr class="ecs-show-hide-row ecs-show-if-group" style="<?php if( $type == 'agent' ) { echo 'display: none;'; } ?>">
			<td colspan="2" class="ecs-no-padding">
				<table class="form-table">
					<tr>
						<th>
							<label for="ecs-group-id"><?php esc_html_e('Group ID', 'essential-chat-support'); ?></label>
						</th>
						<td>
							<input type="text" name="<?php echo esc_attr( $prefix ); ?>group[id]" value="<?php echo esc_attr( $group_id ); ?>" class="ecs-text large-text ecs-group-id" id="ecs-group-id" /><br />
							<span class="description"><?php esc_html_e('Enter WhatsApp group ID.', 'essential-chat-support'); ?></span><br/>
							<span class="description"><?php echo sprintf( __('You can find group ID like this. Open WhatsApp Application > Navigate to Group > Get into the Group Info > Click on Invite via link > Add the suffix part of the link. Please refer this %simage%s.', 'essential-chat-support'), '<a href="'.ECSL_URL.'assets/images/group-id.png" target="_blank">', '</a>' ); ?></span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="ecs-group-desc"><?php esc_html_e('Group Description', 'essential-chat-support'); ?></label>
						</th>
						<td>
							<input type="text" name="<?php echo esc_attr( $prefix ); ?>group[description]" value="<?php echo esc_attr( $group_description ); ?>" class="ecs-text large-text ecs-group-desc" id="ecs-group-desc" /><br/>
							<span class="description"><?php esc_html_e('Enter WhatsApp group description.', 'essential-chat-support'); ?></span>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<!-- End - Group Type Settings -->
	</tbody>
</table><!-- end .ecs-tbl -->