<?php
/**
 * Template for Shortcode Design 1
 *
 * @package Essential Chat Support
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>
<div class="ecs-contact-wrp <?php echo esc_attr( $css_class ); ?>">
	<div class="ecs-contact-inr ecs-open-chat esc-contact-<?php echo esc_attr( $post->ID ); ?>" data-href="<?php echo $whatsapp_url; // WPCS: input var ok. ?>">
		<div class="ecs-contact-avatar">
			<div class="ecs-contact-img">
				<img src="<?php echo esc_url( $featured_img ); ?>" alt="<?php the_title_attribute(); ?>" />
			</div>
		</div>

		<div class="ecs-list-cnt">
			<?php if( ! empty( $contact_name ) ) { ?>
				<div class="ecs-contact-name"><?php echo wp_kses_post( $contact_name ); ?></div>
			<?php }

			if( ! empty( $contact_desc ) ) { ?>
				<div class="ecs-contact-desc"><?php echo wp_kses_post( $contact_desc ); ?></div>
			<?php } ?>
		</div>
	</div>
</div>