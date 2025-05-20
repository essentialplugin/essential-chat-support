<?php
/**
 * Display Rule Settings
 *
 * @package Essential Chat Support
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Taking some variables
$display_locations	= ecsl_display_locations();
$chatbox_glob_locs	= ecsl_get_option( 'chatbox_glob_locs', array() );
?>

<div class="postbox">
	<div class="postbox-header">
		<h3 class="hndle">
			<span><?php esc_html_e( 'Display Rule Settings', 'essential-chat-support' ); ?></span>
		</h3>
	</div>

	<div class="inside">
		<table class="form-table ecs-tbl">
			<tbody>
				<tr>
					<th scope="row">
						<label for="ecs-chatbox-glob-locs"><?php esc_html_e('Global Display', 'essential-chat-support'); ?></label>
					</th>
					<td>
						<div class="ecs-loop-row-wrap">
							<?php if( ! empty( $display_locations ) ) {
								foreach ( $display_locations as $location_key => $location_val ) {

									// Taking some variables
									$chat_widget_id		= isset( $chatbox_glob_locs[$location_key] )	? $chatbox_glob_locs[$location_key] : '';
									$chat_widget_post 	= ! empty( $chat_widget_id )					? get_post( $chat_widget_id )		: '';
									$chat_widget_title	= ! empty( $chat_widget_post->post_title )		? $chat_widget_post->post_title		: __('Post', 'essential-chat-support');
								?>
									<div class="ecs-loop-row-inr">
										<div class="ecs-loop-grid">
											<label><?php echo esc_html( $location_val ); ?></label>
											<select name="ecs_options[chatbox_glob_locs][<?php echo esc_attr( $location_key ); ?>]" class="ecs-select2 ecs-post-title-sugg ecs-chat-widget" data-placeholder="<?php esc_html_e('Search Chat Widget', 'essential-chat-support'); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce('ecs-post-title-sugg') ); ?>" data-post-type="<?php echo esc_attr( ECSL_CW_POST_TYPE ); ?>">
												<option></option>
												<?php if( $chat_widget_post ) { ?>
													<option value="<?php echo esc_attr( $chat_widget_post->ID ); ?>" selected="selected"><?php echo esc_html( $chat_widget_title ." - #". $chat_widget_post->ID ); ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
							<?php } } ?>
						</div>
						<br/>
						<span class="description"><?php esc_html_e('Select chat widget to display on various locations.', 'essential-chat-support'); ?></span>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="submit" name="ecs_sett_submit" class="button button-primary right ecs-btn ecs-post-type-sett-submit" value="<?php esc_html_e('Save Changes', 'essential-chat-support'); ?>" />
					</td>
				</tr>
			</tbody>
		</table>
	</div><!-- end .inside -->
</div><!-- end .postbox -->