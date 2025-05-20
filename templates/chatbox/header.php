<?php
/**
 * Template for Chatbox Header
 *
 * @package Essential Chat Support
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div class="ecs-wrap ecs-chatbox-wrp ecs-toggle-wrap <?php echo esc_attr( $chatbox_classes ); ?>">
	<div class="ecs-wrap ecs-btn-popup">
		<?php if( ! empty( $toggle_btn_text) ) { ?>
			<div class="ecs-ctbx-tgl-txt"><?php echo wp_kses_post( $toggle_btn_text ); ?></div>
		<?php } ?>
		<div class="ecs-ctbx-tgl-icon"></div>
	</div>

	<div class="ecs-chatbox">
		<div class="ecs-ctbx-heading">
			<?php if( ! empty( $main_title ) ){ ?>
			<div class="ecs-ctbx-title"><?php echo wp_kses_post( $main_title ); ?></div>
			<?php } ?>

			<?php if( ! empty( $sub_title ) ) { ?>
			<div class="ecs-ctbx-stitle"><?php echo wp_kses_post( $sub_title ); ?></div>
			<?php } ?>
		</div>

		<div class="ecs-ctbx-cnt-wrp ecs-filtr-js">
			<?php if( ! empty( $notice_msg ) ) { ?>
			<div class="ecs-ctbx-notice"><?php echo wp_kses_post( $notice_msg ); ?></div>
			<?php } ?>
			<div class="ecs-ctbx-cnt-inr ecs-filtr-cnt-js">