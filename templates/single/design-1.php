<?php
/**
 * Template for Single Agent Design 1
 *
 * @package Essential Chat Support
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div class="ecs-wrap ecs-contact-btn-wrap <?php echo esc_attr( $contact_wrap_cls ); ?>">
	<div class="ecs-open-chat ecs-contact-<?php echo esc_attr( $post->ID ); ?> ecs-contact-btn-inr" data-href="<?php echo $whatsapp_url; // WPCS: input var ok. ?>">
		<div class="ecs-contact-icon"></div>
		<div class="ecs-contact-text-wrap">
			<span class="ecs-contact-title"><?php echo wp_kses_post( $chat_title ); ?></span>
		</div>
	</div>
</div>