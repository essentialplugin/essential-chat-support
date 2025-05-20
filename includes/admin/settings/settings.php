<?php
/**
 * Settings Page
 *
 * @package Essential Chat Support
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Plugin settings tab
$sett_tab	= ecsl_settings_tab();
$tab		= isset( $_GET['tab'] ) ? ecsl_clean( $_GET['tab'] ) : 'general';

// If no valid tab is there
if( ! isset( $sett_tab[ $tab ] ) ) {
	ecsl_display_message( 'error' );
	return;
} ?>

<div class="wrap">

	<h2><?php esc_html_e( 'Essential Chat Support - Settings', 'essential-chat-support' ); ?></h2>

	<?php
	// Reset message
	if( ! empty( $_POST['ecs_reset_settings'] ) ) {
		ecsl_display_message( 'reset' );
	}

	// Success message
	if( isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true' ) {
		ecsl_display_message( 'update' );
	}

	settings_errors( 'ecs_sett_error' );
	?>

	<h2 class="nav-tab-wrapper">
		<?php foreach ( $sett_tab as $tab_key => $tab_val ) {
			$tab_url 		= add_query_arg( array( 'post_type' => ECSL_CONTACT_POST_TYPE, 'page' => 'ecs-settings', 'tab' => $tab_key ), admin_url('edit.php') );
			$active_tab_cls = ($tab == $tab_key) ? 'nav-tab-active' : '';
		?>
			<a class="nav-tab <?php echo esc_attr( $active_tab_cls ); ?>" href="<?php echo esc_url( $tab_url ); ?>"><?php echo esc_html( $tab_val ); ?></a>
		<?php } ?>
	</h2>

	<div class="ecs-sett-wrap ecs-settings ecs-pad-top-20">

		<!-- Plugin reset settings form -->
		<form action="" method="post" id="ecs-reset-sett-form" class="ecs-right ecs-reset-sett-form">
			<input type="submit" class="button button-primary ecs-btn ecs-reset-sett ecs-resett-sett-btn ecs-reset-sett" name="ecs_reset_settings" id="ecs-reset-sett" value="<?php esc_html_e( 'Reset All Settings', 'essential-chat-support' ); ?>" />
		</form>

		<form action="options.php" method="POST" id="ecs-settings-form" class="ecs-settings-form">

			<?php settings_fields( 'ecs_plugin_options' ); ?>

			<div class="textright ecs-clearfix">
				<input type="submit" name="ecs_settings_submit" class="button button-primary right ecs-btn ecs-sett-submit" value="<?php esc_html_e('Save Changes', 'essential-chat-support'); ?>" />
			</div>

			<div class="metabox-holder">
				<div class="post-box-container">
					<div class="meta-box-sortables ui-sortable">
						<?php
						// Setting files
						switch ( $tab ) {
							case 'general':
								include_once( ECSL_DIR . '/includes/admin/settings/general-settings.php' );
								break;

							case 'display_rule':
								include_once( ECSL_DIR . '/includes/admin/settings/display-rule-settings.php' );
								break;

							case 'woo_product_tab':
								include_once( ECSL_DIR . '/includes/admin/settings/woo-product-tab-settings.php' );
								break;

							case 'custom_css':
								include_once( ECSL_DIR . '/includes/admin/settings/custom-css-settings.php' );
								break;

							default:
								do_action( 'ecs_sett_panel_' . $tab );
								do_action( 'ecs_sett_panel', $tab );
								break;
						} ?>
					</div><!-- end .meta-box-sortables -->
				</div><!-- end .post-box-container -->
			</div><!-- end .metabox-holder -->

		</form><!-- end .ecs-settings-form -->

	</div><!-- end .ecs-sett-wrap -->
</div><!-- end .wrap -->