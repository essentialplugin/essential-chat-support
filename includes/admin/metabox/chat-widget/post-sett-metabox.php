<?php
/**
 * Handles Chat Widget metabox HTML
 *
 * @package Essential Chat Support
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Taking global variable
global $post;

// Taking some variables
$prefix			= ECSL_META_PREFIX;
$enable			= ecsl_get_option( 'enable' );
$selected_tab	= get_post_meta( $post->ID, $prefix.'tab', true );
$behaviour		= get_post_meta( $post->ID, $prefix.'behaviour', true );
$display_mode	= get_post_meta( $post->ID, $prefix.'display_mode', true );
$display_mode	= ! empty( $display_mode ) ? $display_mode : 'multi-mode';

// Add general reminder when module is disabled from setting page
if( ! $enable ) { ?>
	<div class="ecs-info ecs-no-margin ecs-no-radius"><i class="dashicons dashicons-warning"></i> <?php esc_html_e('Chat Widget is disabled from plugin setting page. Kindly enable it to use it.', 'essential-chat-support'); ?></div>
<?php } ?>

<div class="ecs-vtab-wrap ecs-cnt-wrap ecs-clearfix">
	<ul class="ecs-vtab-nav-wrap">
		<li class="ecs-vtab-nav ecs-active-vtab">
			<a href="#ecs_behaviour_sett"><i class="dashicons dashicons-welcome-view-site" aria-hidden="true"></i> <?php esc_html_e('Behaviour', 'essential-chat-support'); ?></a>
		</li>

		<li class="ecs-vtab-nav">
			<a href="#ecs_content_sett"><i class="dashicons dashicons-text-page" aria-hidden="true"></i> <?php esc_html_e('Content', 'essential-chat-support'); ?></a>
		</li>

		<li class="ecs-vtab-nav">
			<a href="#ecs_design_sett"><i class="dashicons dashicons-admin-customizer" aria-hidden="true"></i> <?php esc_html_e('Design', 'essential-chat-support'); ?></a>
		</li>

		<li class="ecs-vtab-nav">
			<a href="#ecs_advance_sett"><i class="dashicons dashicons-admin-settings" aria-hidden="true"></i> <?php esc_html_e('Advance', 'essential-chat-support'); ?></a>
		</li>
	</ul>

	<div class="ecs-vtab-cnt-wrp">
		<?php
			// Behaviour Settings
			include_once( ECSL_DIR . '/includes/admin/metabox/chat-widget/behaviour-metabox.php' );

			// Content Settings
			include_once( ECSL_DIR . '/includes/admin/metabox/chat-widget/content-metabox.php' );

			// Design Settings
			include_once( ECSL_DIR . '/includes/admin/metabox/chat-widget/design-metabox.php' );

			// Advance Settings
			include_once( ECSL_DIR . '/includes/admin/metabox/chat-widget/advance-metabox.php' );
		?>
	</div>
	<input type="hidden" value="<?php echo esc_attr( $selected_tab ); ?>" class="ecs-selected-tab" name="<?php echo esc_attr( $prefix ); ?>tab" />
</div>

<!-- Notice Message -->
<div class="ecs-notice-wrap ecs-meta-notify ecs-info ecs-hide"><?php esc_html_e('Changing the Chat Widget Display Mode will enable some settings in Content and Designs tab.', 'essential-chat-support'); ?></div>