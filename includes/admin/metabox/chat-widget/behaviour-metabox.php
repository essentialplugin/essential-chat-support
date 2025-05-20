<?php
/**
* Handles Behaviour Tab metabox HTML
*
* @package Essential Chat Support
* @since 1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Taking behaviour tab data
$display_mode_arr	= ecsl_get_display_modes();
$display_type_arr	= ecsl_get_display_types();
$position_arr		= ecsl_get_positions();
$display_type		= ! empty( $behaviour['display_type'] )		? $behaviour['display_type']		: 'both';
$position			= ! empty( $behaviour['position'] )			? $behaviour['position']			: 'right-bottom';
$include_contacts	= ! empty( $behaviour['include_contacts'] )	? $behaviour['include_contacts']	: array();
$all_contacts_link	= add_query_arg( array('post_type' => ECSL_CONTACT_POST_TYPE ), admin_url('edit.php') );

// Include contacts args
$inc_contact_args = array(
	'post_type'			=> ECSL_CONTACT_POST_TYPE,
	'post_status'		=> array('any', 'inherit', 'draft'),
	'post__in'			=> $include_contacts,
	'posts_per_page'	=> -1,
);

// Get contact post type data
$include_contacts_post	= ( ! empty( $include_contacts ) ) ? get_posts( $inc_contact_args ) : '';
?>

<div id="ecs_behaviour_sett" class="ecs-vtab-cnt ecs-behaviour-sett ecs-clearfix">
	
	<div class="ecs-tab-info-wrap">
		<div class="ecs-tab-title"><?php esc_html_e('Behaviour Settings', 'essential-chat-support'); ?></div>
		<span class="ecs-tab-desc"><?php esc_html_e('Choose Chat Widget behaviour settings.', 'essential-chat-support'); ?></span>
	</div>

	<table class="form-table ecs-tbl">
		<tbody>
			<tr>
				<th>
					<label for="ecs-display-mode"><?php esc_html_e('Display Mode', 'essential-chat-support'); ?></label>
				</th>
				<td>
					<select name="<?php echo esc_attr( $prefix ); ?>display_mode" class="ecs-select ecs-show-hide ecs-display-mode" id="ecs-display-mode" data-prefix="ecs-dm">
						<?php if( ! empty( $display_mode_arr ) ) {
							foreach ($display_mode_arr as $display_mode_key => $display_mode_val) { ?>
								<option value="<?php echo esc_attr( $display_mode_key ); ?>" <?php selected( $display_mode_key, $display_mode ); ?>><?php echo esc_html( $display_mode_val ); ?></option>
							<?php }
						} ?>
					</select><br/>
					<span class="description"><?php esc_html_e('Select chat widget display mode.', 'essential-chat-support'); ?></span>
				</td>
			</tr>
			<tr>
				<th>
					<label for="ecs-display-type"><?php esc_html_e('Display Contact Type', 'essential-chat-support'); ?></label>
				</th>
				<td>
					<select name="<?php echo esc_attr( $prefix ); ?>behaviour[display_type]" class="ecs-select ecs-display-type" id="ecs-display-type">
						<?php if( ! empty( $display_type_arr ) ) {
							foreach ($display_type_arr as $display_type_key => $display_type_val) { ?>
								<option value="<?php echo esc_attr( $display_type_key ); ?>" <?php selected( $display_type_key, $display_type ); ?>><?php echo esc_html( $display_type_val ); ?></option>
							<?php }
						} ?>
					</select><br/>
					<span class="description"><?php esc_html_e('Select chat widget display type.', 'essential-chat-support'); ?></span>
				</td>
			</tr>
			<tr>
				<th>
					<label for="ecs-position"><?php esc_html_e('Position', 'essential-chat-support'); ?></label>
				</th>
				<td>
					<select name="<?php echo esc_attr( $prefix ); ?>behaviour[position]" class="ecs-select ecs-position" id="ecs-position">
						<?php if( ! empty( $position_arr ) ) {
							foreach ($position_arr as $position_key => $position_val) { ?>
								<option value="<?php echo esc_attr( $position_key ); ?>" <?php selected( $position_key, $position ); ?>><?php echo esc_html( $position_val ); ?></option>
							<?php }
						} ?>
					</select><br/>
					<span class="description"><?php esc_html_e('Select chat widget position.', 'essential-chat-support'); ?></span>
				</td>
			</tr>
			<tr>
				<th>
					<label for="ecs-include-contacts"><?php esc_html_e('Include Contacts', 'essential-chat-support'); ?></label>
				</th>
				<td>
					<select name="<?php echo esc_attr( $prefix ); ?>behaviour[include_contacts][]" class="ecs-select2 ecs-select2-mul ecs-post-title-sugg ecs-include-contacts" id="ecs-include-contacts" data-placeholder="<?php esc_html_e('Search Contacts', 'essential-chat-support'); ?>" data-nonce="<?php echo wp_create_nonce('ecs-post-title-sugg'); ?>" data-post-type="<?php echo ECSL_CONTACT_POST_TYPE; ?>" multiple="multiple" data-meta="" style="width: 100%;">
						<option></option>
						<?php if( ! empty( $include_contacts_post ) ) {
							foreach ( $include_contacts_post as $include_contact_key => $include_contact_val ) {

								// Taking some variables
								$option_name = $include_contact_val->post_title ." - (#{$include_contact_val->ID})";
								$option_name .= ( $include_contact_val->post_status != 'publish' ) ? " &mdash; (".ucfirst($include_contact_val->post_status).") " : '';
							?>
								<option value="<?php echo esc_attr( $include_contact_val->ID ); ?>" <?php selected( in_array( $include_contact_val->ID, $include_contacts ), true ); ?>><?php echo esc_html( $option_name ); ?></option>
							<?php }
						} ?>
					</select><br/>
					<span class="description"><?php echo sprintf( __( 'Choose particular contacts to show. Leave empty for default behaviour. You can add new contact from %shere%s.', 'essential-chat-support'), '<a href="'.esc_url( $all_contacts_link ).'" target="_blank">', '</a>' ); ?></span><br/>
					<span class="description"><?php esc_html_e('Note: Lite version will display only three contacts for Multi Mode display mode.', 'essential-chat-support'); ?></span>
				</td>
			</tr>
		</tbody>
	</table>
</div><!-- end .ecs-behaviour-sett -->