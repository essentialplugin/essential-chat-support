<?php
/**
* Handles Content Tab metabox HTML
*
* @package Essential Chat Support
* @since 1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Taking content tab data
$content			= get_post_meta( $post->ID, $prefix.'content', true );
$sub_title			= isset( $content['sub_title'] )		? $content['sub_title']			: __('Hi! Click one of our members below to chat on WhatsApp', 'essential-chat-support');
$notice_msg			= isset( $content['notice_msg'] )		? $content['notice_msg']		: __('The team typically replies in a few minutes.', 'essential-chat-support');
$toggle_btn_text	= isset( $content['toggle_btn_text'] )	? $content['toggle_btn_text']	: __('Need Help? Chat with us', 'essential-chat-support');
$main_title			= ! empty( $content['main_title'] )		? $content['main_title']		: __('Start a Conversation', 'essential-chat-support');
$chat_title			= ! empty( $content['chat_title'] )		? $content['chat_title']		: __('WhatsApp Live Chat', 'essential-chat-support');
?>

<div id="ecs_content_sett" class="ecs-vtab-cnt ecs-content-sett ecs-clearfix">
	
	<div class="ecs-tab-info-wrap">
		<div class="ecs-tab-title"><?php esc_html_e('Content Settings', 'essential-chat-support'); ?></div>
		<span class="ecs-tab-desc"><?php esc_html_e('Choose Chat Widget content settings.', 'essential-chat-support'); ?></span>
	</div>

	<table class="form-table ecs-tbl">
		<tbody>
			<!-- Start - Multi Mode Settings -->
			<tr class="ecs-show-hide-row-ecs-dm ecs-show-if-ecs-dm-multi-mode" style="<?php if( $display_mode == 'single-mode' ) { echo 'display: none;'; } ?>">
				<td colspan="2" class="ecs-no-padding">
					<table class="form-table">
						<tr>
							<th>
								<label for="ecs-toggle-btn-tooltip"><?php esc_html_e('Toggle Button Tooltip', 'essential-chat-support'); ?></label>
							</th>
							<td>
								<input type="text" name="<?php echo esc_attr( $prefix ); ?>content[toggle_btn_text]" value="<?php echo esc_attr( $toggle_btn_text ); ?>" class="ecs-text large-text ecs-toggle-btn-tooltip" id="ecs-toggle-btn-tooltip" /><br/>
								<span class="description"><?php esc_html_e('Enter chat widget toggle button tooltip. e.g. Need Help? Chat with us.', 'essential-chat-support'); ?></span>
							</td>
						</tr>
						<tr>
							<th>
								<label for="ecs-widget-main-title"><?php esc_html_e('Main Title', 'essential-chat-support'); ?></label>
							</th>
							<td>
								<input type="text" name="<?php echo esc_attr( $prefix ); ?>content[main_title]" value="<?php echo esc_attr( $main_title ); ?>" class="ecs-text large-text ecs-widget-main-title" id="ecs-widget-main-title" /><br/>
								<span class="description"><?php esc_html_e('Enter chat widget header main title. e.g. Start a Conversation', 'essential-chat-support'); ?></span>
							</td>
						</tr>
						<tr>
							<th>
								<label for="ecs-widget-sub-title"><?php esc_html_e('Sub Title', 'essential-chat-support'); ?></label>
							</th>
							<td>
								<input type="text" name="<?php echo esc_attr( $prefix ); ?>content[sub_title]" value="<?php echo esc_attr( $sub_title ); ?>" class="ecs-text large-text ecs-widget-sub-title" id="ecs-widget-sub-title" /><br/>
								<span class="description"><?php esc_html_e('Enter chat widget header sub title. e.g. Hi! Click one of our members below to chat on WhatsApp', 'essential-chat-support'); ?></span>
							</td>
						</tr>
						<tr>
							<th>
								<label for="ecs-widget-notice-msg"><?php esc_html_e('Notice Message', 'essential-chat-support'); ?></label>
							</th>
							<td>
								<input type="text" name="<?php echo esc_attr( $prefix ); ?>content[notice_msg]" value="<?php echo esc_attr( $notice_msg ); ?>" class="ecs-text large-text ecs-widget-notice-msg" id="ecs-widget-notice-msg" /><br/>
								<span class="description"><?php esc_html_e('Enter chat widget notice message. e.g. The team typically replies in a few minutes.', 'essential-chat-support'); ?></span>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<!-- End - Multi Mode Settings -->

			<!-- Start - Single Mode Settings -->
			<tr class="ecs-show-hide-row-ecs-dm ecs-show-if-ecs-dm-single-mode" style="<?php if( $display_mode == 'multi-mode' ) { echo 'display: none;'; } ?>">
				<td colspan="2" class="ecs-no-padding">
					<table class="form-table">
						<tr>
							<th>
								<label for="ecs-chat-title"><?php esc_html_e('Chat Title', 'essential-chat-support'); ?></label>
							</th>
							<td>
								<input type="text" name="<?php echo esc_attr( $prefix ); ?>content[chat_title]" value="<?php echo esc_attr( $chat_title ); ?>" class="ecs-text large-text ecs-chat-title" id="ecs-chat-title" /><br/>
								<span class="description"><?php esc_html_e('Enter chat title for single mode.', 'essential-chat-support'); ?></span>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<!-- End - Single Mode Settings -->
		</tbody>
	</table>
</div><!-- end .ecs-content-sett -->