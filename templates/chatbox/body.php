<?php
/**
 * Template for Chatbox Body
 *
 * @package Essential Chat Support
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>
<div class="ecs-item-wrp <?php echo esc_attr( $contact_wrap_cls ); ?>">
	<div class="ecs-item-inr ecs-open-chat esc-item-<?php echo esc_attr( $post->ID ); ?>" data-href="<?php echo $whatsapp_url; // WPCS: input var ok. ?>">
		<div class="ecs-contact-avatar">
			<div class="ecs-contact-img" style="background-image:url(<?php echo esc_url( $featured_img ); ?>);"></div>
		</div>

		<div class="ecs-item-cnt">
			<?php if( ! empty( $contact_name ) ) { ?>
				<div class="ecs-contact-name"><?php echo wp_kses_post( $contact_name ); ?></div>
			<?php }

			if( ! empty( $contact_desc ) ) { ?>
				<div class="ecs-contact-desc"><?php echo wp_kses_post( $contact_desc ); ?></div>
			<?php } ?>
		</div>
	</div>
</div>