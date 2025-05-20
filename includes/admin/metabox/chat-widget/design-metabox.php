<?php
/**
* Handles Design Tab metabox HTML
*
* @package Essential Chat Support
* @since 1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Taking design tab data
$design				= get_post_meta( $post->ID, $prefix.'design', true );
$theme_bg_clr		= ! empty( $design['theme_bg_clr'] )		? $design['theme_bg_clr']		: '#095e54';
$theme_text_clr		= ! empty( $design['theme_text_clr'] )		? $design['theme_text_clr']		: '#ffffff';
$chatbox_bg_clr		= ! empty( $design['chatbox_bg_clr'] )		? $design['chatbox_bg_clr']		: '#e5ddd5';
$chatbox_text_clr	= ! empty( $design['chatbox_text_clr'] )	? $design['chatbox_text_clr']	: '#666666';
$tooltip_bg_clr		= ! empty( $design['tooltip_bg_clr'] )		? $design['tooltip_bg_clr']		: '#efefef';
$tooltip_text_clr	= ! empty( $design['tooltip_text_clr'] )	? $design['tooltip_text_clr']	: '#43474e';
$online_border_clr	= ! empty( $design['online_border_clr'] )	? $design['online_border_clr']	: '#2db742';
?>

<div id="ecs_design_sett" class="ecs-vtab-cnt ecs-design-sett ecs-clearfix">

	<div class="ecs-tab-info-wrap">
		<div class="ecs-tab-title"><?php esc_html_e('Design Settings', 'essential-chat-support'); ?></div>
		<span class="ecs-tab-desc"><?php esc_html_e('Choose Chat Widget design settings.', 'essential-chat-support'); ?></span>
	</div>

	<table class="form-table ecs-tbl">
		<tbody>
			<tr>
				<th>
					<label for="ecs-theme-bg-clr"><?php esc_html_e('Theme Background Color', 'essential-chat-support'); ?></label>
				</th>
				<td>
					<input type="text" name="<?php echo esc_attr( $prefix ); ?>design[theme_bg_clr]" value="<?php echo esc_attr( $theme_bg_clr ); ?>" class="ecs-colorpicker ecs-theme-bg-clr" id="ecs-theme-bg-clr" data-default-color="#095e54" /><br/>
					<span class="description"><?php esc_html_e('Choose theme background color.', 'essential-chat-support'); ?></span>
				</td>
			</tr>
			<tr>
				<th>
					<label for="ecs-theme-txt-clr"><?php esc_html_e('Theme Text Color', 'essential-chat-support'); ?></label>
				</th>
				<td>
					<input type="text" name="<?php echo esc_attr( $prefix ); ?>design[theme_text_clr]" value="<?php echo esc_attr( $theme_text_clr ); ?>" class="ecs-colorpicker ecs-theme-txt-clr" id="ecs-theme-txt-clr" data-default-color="#ffffff" /><br/>
					<span class="description"><?php esc_html_e('Choose theme text color.', 'essential-chat-support'); ?></span>
				</td>
			</tr>
			<!-- Start - Multi Mode Settings -->
			<tr class="ecs-show-hide-row-ecs-dm ecs-show-if-ecs-dm-multi-mode" style="<?php if( $display_mode == 'single-mode' ) { echo 'display: none;'; } ?>">
				<td colspan="2" class="ecs-no-padding">
					<table class="form-table">
						<tr>
							<th colspan="2">
								<div class="ecs-sub-sett-title"><i class="dashicons dashicons-admin-generic"></i> <?php esc_html_e('Chatbox Color Settings', 'essential-chat-support'); ?></div>
							</th>
						</tr>
						<tr>
							<th>
								<label for="ecs-chatbox-bg-clr"><?php esc_html_e('Chatbox Background Color', 'essential-chat-support'); ?></label>
							</th>
							<td>
								<input type="text" name="<?php echo esc_attr( $prefix ); ?>design[chatbox_bg_clr]" value="<?php echo esc_attr( $chatbox_bg_clr ); ?>" class="ecs-colorpicker ecs-chatbox-bg-clr" id="ecs-chatbox-bg-clr" data-default-color="#e5ddd5" /><br/>
								<span class="description"><?php esc_html_e('Choose chatbox background color.', 'essential-chat-support'); ?></span>
							</td>
						</tr>
						<tr>
							<th>
								<label for="ecs-chatbox-text-clr"><?php esc_html_e('Chatbox Text Color', 'essential-chat-support'); ?></label>
							</th>
							<td>
								<input type="text" name="<?php echo esc_attr( $prefix ); ?>design[chatbox_text_clr]" value="<?php echo esc_attr( $chatbox_text_clr ); ?>" class="ecs-colorpicker ecs-chatbox-text-clr" id="ecs-chatbox-text-clr" data-default-color="#666666" /><br/>
								<span class="description"><?php esc_html_e('Choose chatbox text color.', 'essential-chat-support'); ?></span>
							</td>
						</tr>
						<tr>
							<th>
								<label for="ecs-tooltip-bg-clr"><?php esc_html_e('Tooltip Background Color', 'essential-chat-support'); ?></label>
							</th>
							<td>
								<input type="text" name="<?php echo esc_attr( $prefix ); ?>design[tooltip_bg_clr]" value="<?php echo esc_attr( $tooltip_bg_clr ); ?>" class="ecs-colorpicker ecs-tooltip-bg-clr" id="ecs-tooltip-bg-clr" data-default-color="#efefef" /><br/>
								<span class="description"><?php esc_html_e('Choose tooltip background color.', 'essential-chat-support'); ?></span>
							</td>
						</tr>
						<tr>
							<th>
								<label for="ecs-tooltip-text-clr"><?php esc_html_e('Tooltip Text Color', 'essential-chat-support'); ?></label>
							</th>
							<td>
								<input type="text" name="<?php echo esc_attr( $prefix ); ?>design[tooltip_text_clr]" value="<?php echo esc_attr( $tooltip_text_clr ); ?>" class="ecs-colorpicker ecs-tooltip-text-clr" id="ecs-tooltip-text-clr" data-default-color="#43474e" /><br/>
								<span class="description"><?php esc_html_e('Choose tooltip text color.', 'essential-chat-support'); ?></span>
							</td>
						</tr>
						<tr>
							<th>
								<label for="ecs-chatbox-online-border-clr"><?php esc_html_e('Online Agent Border Color', 'essential-chat-support'); ?></label>
							</th>
							<td>
								<input type="text" name="<?php echo esc_attr( $prefix ); ?>design[online_border_clr]" value="<?php echo esc_attr( $online_border_clr ); ?>" class="ecs-colorpicker ecs-chatbox-online-border-clr" id="ecs-chatbox-online-border-clr" data-default-color="#2db742" /><br/>
								<span class="description"><?php esc_html_e('Choose online agent border color.', 'essential-chat-support'); ?></span>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<!-- End - Multi Mode Settings -->
		</tbody>
	</table>
</div><!-- end .ecs-design-sett -->