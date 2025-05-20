<?php
/**
* Handles Advance Tab metabox HTML
*
* @package Essential Chat Support
* @since 1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Taking advance tab data
$display_on_data	= ecsl_display_on_options();
$advance			= get_post_meta( $post->ID, $prefix.'advance', true );
$display_on			= ! empty( $advance['display_on'] ) ? esc_attr( $advance['display_on'] ) : 'every_device';
?>

<div id="ecs_advance_sett" class="ecs-vtab-cnt ecs-advance-sett ecs-clearfix">

	<div class="ecs-tab-info-wrap">
		<div class="ecs-tab-title"><?php esc_html_e('Advance Settings', 'essential-chat-support'); ?></div>
		<span class="ecs-tab-desc"><?php esc_html_e('Choose Chat Widget advance settings.', 'essential-chat-support'); ?></span>
	</div>

	<table class="form-table ecs-tbl">
		<tbody>
			<tr>
				<th>
					<label for="ecs-displa-on"><?php esc_html_e('Display On', 'essential-chat-support'); ?></label>
				</th>
				<td>
					<select name="<?php echo esc_attr( $prefix ); ?>advance[display_on]" class="ecs-select ecs-displa-on" id="ecs-displa-on">
						<?php if( ! empty( $display_on_data ) ) {
							foreach ( $display_on_data as $display_on_key => $display_on_val ) { ?>
								<option value="<?php echo esc_attr( $display_on_key ); ?>" <?php selected( $display_on_key, $display_on ); ?>><?php echo esc_html( $display_on_val ); ?></option>
							<?php }
						} ?>
					</select><br/>
					<span class="description"><?php esc_html_e('Select chat widget device visibility.', 'essential-chat-support'); ?></span>
				</td>
			</tr>
		</tbody>
	</table>
</div><!-- end .ecs-advance-sett -->