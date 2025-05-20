<?php
/**
 * Chat Widget Settings
 * 
 * @package Essential Chat Support
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$display_rule_link = add_query_arg( array( 'post_type' => ECSL_CONTACT_POST_TYPE, 'page' => 'ecs-settings', 'tab' => 'display_rule'), admin_url('edit.php') );
?>

<div class="ecs-chat-widget-sett ecs-cnt-wrap">
	<div class="ecs-cw-sett-btn-wrp">
		<a class="button button-large button-primary ecs-btn ecs-btn-large" href="<?php echo esc_url( $display_rule_link ); ?>" target="_blank"><?php esc_html_e('Display Rule', 'essential-chat-support'); ?></a>
	</div>
</div>