<?php
/**
 * Public Class
 * Handles the public side functionality of plugin
 *
 * @package Essential Chat Support
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class ECSL_Public {

	function __construct() {

		// Add Action to display render agent
		add_action( 'wp_footer', array( $this, 'ecsl_render_chatbox' ) );

		$woo_enable	= ecsl_get_option( 'woo_enable' );

		// If WooCommerce Essential Chat is enable
		if ( ! empty( $woo_enable ) ) {

			// WooCommerce Product Tabs
			add_filter( 'woocommerce_product_tabs', array( $this, 'ecsl_render_woo_pdt_tab' ) );
		}
	}

	/**
	 * Function to add chatbox to site
	 * 
	 * @since 1.0
	 **/
	function ecsl_render_chatbox() {

		// Taking global variable
		global $post, $product;

		// Taking some variables
		$enable	= ecsl_get_option( 'enable' );

		// Check global enable or not
		if( ! $enable ) {
			return false;
		}

		// Get chatbox id
		$chatbox_id	= ecsl_get_chatbox_id();
		$chatbox_id	= apply_filters( 'ecs_render_chatbox', $chatbox_id, $post );

		// Return if no chatbox is set
		if( empty( $chatbox_id ) ) {
			return false;
		}

		// Taking some variable
		$prefix		= ECSL_META_PREFIX;
		$advance	= get_post_meta( $chatbox_id, $prefix.'advance', true );
		$display_on	= isset( $advance['display_on'] ) ? $advance['display_on'] : 'every_device';

		if( ( ! wp_is_mobile() && $display_on == 'mobile_only' )
			|| ( wp_is_mobile() && $display_on == 'desktop_only' ) ) {
			return false;
		}

		// Taking some meta
		$header_file		= '';
		$footer_file		= '';
		$template			= '';
		$behaviour			= get_post_meta( $chatbox_id, $prefix.'behaviour', true );
		$display_mode		= get_post_meta( $chatbox_id, $prefix.'display_mode', true );
		$display_mode		= ! empty( $display_mode )					? $display_mode						: 'multi-mode';
		$display_type		= ! empty( $behaviour['display_type'] )		? $behaviour['display_type']		: 'both';
		$position			= ! empty( $behaviour['position'] )			? $behaviour['position']			: 'right-bottom';
		$include_contacts	= ! empty( $behaviour['include_contacts'] )	? $behaviour['include_contacts']	: array();

		// Taking some pre-filled tag data
		$price			= '';
		$regular_price	= '';
		$sku			= '';
		$current_url	= ecsl_get_current_page_url();
		$post_id		= isset( $post->ID )			? $post->ID					: '';
		$post_title		= isset( $post->post_title )	? $post->post_title			: '';
		$post_name		= isset( $post->post_name )		? $post->post_name			: '';

		if( class_exists('WooCommerce') && ! empty( $product ) ) {
			$price			= ! empty( $product )	? $product->get_price()			: '';
			$regular_price	= ! empty( $product )	? $product->get_regular_price()	: '';
			$sku			= ! empty( $product )	? $product->get_sku()			: '';
		}

		// If Display Mode is `Single Mode` is there
		if( $display_mode == 'single-mode' ) {

			// Taking some data
			$limit			= 1;
			$template		= 'design-1';

			// Taking content meta
			$content		= get_post_meta( $chatbox_id, $prefix.'content', true );
			$chat_title		= isset( $content['chat_title'] ) ? $content['chat_title'] : __('WhatsApp Live Chat', 'essential-chat-support');
			$design_file	= ECSL_DIR."/templates/single/{$template}.php";

		} else { // Else Display Mode is `Multi Mode` is there

			// Taking some data
			$limit				= 3;
			$chatbox_classes	= " ecs-{$position} ecs-style-1";

			// Taking content meta
			$content			= get_post_meta( $chatbox_id, $prefix.'content', true );
			$main_title			= isset( $content['main_title'] )		? $content['main_title']		: __('Start a Conversation', 'essential-chat-support');
			$sub_title			= isset( $content['sub_title'] )		? $content['sub_title']			: '';
			$notice_msg			= isset( $content['notice_msg'] )		? $content['notice_msg']		: '';
			$toggle_btn_text	= isset( $content['toggle_btn_text'] )	? $content['toggle_btn_text']	: '';

			// Taking File Path in variables
			$header_file	= ECSL_DIR. "/templates/chatbox/header.php";
			$design_file	= ECSL_DIR. "/templates/chatbox/body.php";
			$footer_file	= ECSL_DIR. "/templates/chatbox/footer.php";
		}

		// Query Parameter
		$contact_args = array (
			'post_type'				=> ECSL_CONTACT_POST_TYPE,
			'post_status'			=> array( 'publish' ),
			'orderby'				=> 'date',
			'order'					=> 'DESC',
			'posts_per_page'		=> $limit,
			'post__in'				=> $include_contacts,
			'ignore_sticky_posts'	=> true,
			'no_found_rows'			=> true,
		);

		// Meta Query for `Status`
		$contact_args['meta_query'] = array(
										'relation' => 'AND',
										array(
											'key'		=> $prefix.'status',
											'value'		=> 1,
											'compare'	=> '=',
										),
									);

		// Type Meta Query for `Agent` & `Group`
		if( $display_type != 'both' ) {
			$contact_args['meta_query'][] = array(
											'key'		=> $prefix.'type',
											'value'		=> $display_type,
											'compare'	=> '=',
										);
		}

		// WP Query
		$query = new WP_Query( $contact_args );

		// Enqueue Public Script
		wp_enqueue_script( 'ecs-public-script' );

		// Print Style
		$style = $this->ecsl_generate_agent_style( $chatbox_id );
		echo "<style type='text/css'>".wp_strip_all_tags( $style )."</style>"; // Note that esc_html() cannot be used because `div &gt; span` is not interpreted properly.

		// If post is there
		if ( $query->have_posts() ) {

			// Header File
			if( $header_file ) {
				include( $header_file );
			}

			while ( $query->have_posts() ) : $query->the_post();

				// Taking some variables
				$type				= get_post_meta( $post->ID, $prefix.'type', true );
				$featured_img		= ecsl_get_featured_image( $post->ID, 'thumbnail', ECSL_URL ."assets/images/person-placeholder.png" );
				$contact_name		= ! empty( $post->post_title )	? $post->post_title		: ucfirst( $type ).' - '.$post->ID;
				$contact_wrap_cls	= " ecs-online ecs-{$type}-wrap ecs-{$position}";
				$contact_wrap_cls	.= ! empty( $template )			? " ecs-{$template}"	: '';

				// If Contact type is `Group`
				if( $type == 'group' ) {

					// Taking some data
					$group	= get_post_meta( $post->ID, $prefix.'group', true );
					$id		= ! empty( $group['id'] ) ? $group['id'] : '';

					// If Group ID is not there then skip
					if( ! $id ) {
						continue;
					}

					// Taking some variables
					$contact_desc	= ! empty( $group['description'] ) ? $group['description']	: '';
					$whatsapp_url	= ECSL_GROUP_API . esc_attr( $id );

				} else { // Else Contact type is `Agent`

					// Taking some data
					$agent				= get_post_meta( $post->ID, $prefix.'agent', true );
					$whatsapp_number	= ! empty( $agent['whatsapp_number'] )	? $agent['whatsapp_number'] : '';
					$country_code		= isset( $agent['country_code'] )		? $agent['country_code']	: '';

					// If WhatsApp Number & Country Code are not there
					if( ! $whatsapp_number || ! $country_code ) {
						continue;
					}

					// Taking some variables
					$custom_message			= false;
					$contact_desc			= isset( $agent['designation'] )			? $agent['designation']			: '';
					$availability_status	= isset( $agent['availability_status'] )	? $agent['availability_status']	: '';

					// Custom Message
					if( ! empty( $agent['custom_message'] ) ) {

						$custom_message	= str_replace( "\n", "%0a", $agent['custom_message'] );
						$custom_message	= str_replace(
											array('{ID}', '{title}', '{slug}', '{url}', '{price}', '{regular_price}', '{sku}'),
											array( $post_id, $post_title, $post_name, $current_url, $price, $regular_price, $sku ),
											$custom_message
										);
					}

					$whatsapp_url = add_query_arg(
										array(
											'phone'	=> esc_attr( $country_code . $whatsapp_number ),
											'text'	=> ecsl_clean_html( $custom_message ),
										),
										ECSL_API
									);
				}

				// Design File
				if( $design_file ) {
					include( $design_file );
				}

			endwhile;

			// Footer File
			if( $footer_file ) {
				include( $footer_file );
			}

			wp_reset_postdata(); // Reset WP Query
		}
	}

	/**
	 * Function to create agent style
	 * 
	 * @since 1.0
	 */
	function ecsl_generate_agent_style( $chatbox_id ) {

		// Taking some data
		$style			= '';
		$prefix			= ECSL_META_PREFIX;
		$design			= get_post_meta( $chatbox_id, $prefix.'design', true );
		$display_mode	= get_post_meta( $chatbox_id, $prefix.'display_mode', true );
		$theme_bg_clr	= isset( $design['theme_bg_clr'] )		? $design['theme_bg_clr']	: '#095e54';
		$theme_text_clr	= isset( $design['theme_text_clr'] )	? $design['theme_text_clr']	: '#ffffff';

		// If `Display Mode` is `Single Mode`
		if( $display_mode == 'single-mode' ) {

			$style .= ".ecs-contact-btn-wrap .ecs-contact-btn-inr{background-color: ".esc_attr( $theme_bg_clr ).";}";
			$style .= ".ecs-contact-btn-wrap .ecs-contact-icon{color: ".esc_attr( $theme_text_clr ).";}";
			$style .= ".ecs-contact-btn-wrap .ecs-contact-title{color: ".esc_attr( $theme_text_clr ).";}";

		} elseif( $display_mode == 'multi-mode' ) { // If `Display Mode` is `Single Mode`

			// Taking some variables
			$chatbox_bg_clr		= isset( $design['chatbox_bg_clr'] )	? $design['chatbox_bg_clr']		: '#e5ddd5';
			$chatbox_text_clr	= isset( $design['chatbox_text_clr'] )	? $design['chatbox_text_clr']	: '#666666';
			$tooltip_bg_clr		= isset( $design['tooltip_bg_clr'] )	? $design['tooltip_bg_clr']		: '#efefef';
			$tooltip_text_clr	= isset( $design['tooltip_text_clr'] )	? $design['tooltip_text_clr']	: '#43474e';
			$online_border_clr	= isset( $design['online_border_clr'] )	? $design['online_border_clr']	: '#2db742';

			$style	.= ".ecs-chatbox-wrp .ecs-chatbox .ecs-ctbx-cnt-wrp{background-color: ".esc_attr( $chatbox_bg_clr )."; color: ".esc_attr( $chatbox_text_clr ).";}";
			$style	.= ".ecs-chatbox-wrp .ecs-chatbox .ecs-ctbx-heading, .ecs-btn-popup .ecs-ctbx-tgl-icon{background-color: ".esc_attr( $theme_bg_clr )."; color: ".esc_attr( $theme_text_clr ).";}";
			$style	.= ".ecs-chatbox-wrp .ecs-chatbox .ecs-ctbx-heading:before, .ecs-chatbox-wrp .ecs-btn-popup .ecs-ctbx-tgl-icon:before{color: ".esc_attr( $theme_text_clr ).";}";
			$style	.= ".ecs-chatbox .ecs-online .ecs-item-inr{border-color: ".esc_attr( $online_border_clr ).";}";
			$style	.= ".ecs-online .ecs-item-inr:hover{-webkit-box-shadow: 0 0 0 1px ".esc_attr( $online_border_clr )."; -moz-box-shadow: 0 0 0 1px ".esc_attr( $online_border_clr )."; box-shadow: 0 0 0 1px ".esc_attr( $online_border_clr ).";}";
			$style	.= ".ecs-chatbox-wrp .ecs-btn-popup .ecs-ctbx-tgl-txt{background-color: ".esc_attr( $tooltip_bg_clr )."; color: ".esc_attr( $tooltip_text_clr ).";}";
		}

		return $style;
	}

	/**
	 * Add Essential Chat tab to Woocommerce Product Page
	 * 
	 * @since 1.0
	 */
	function ecsl_render_woo_pdt_tab( $tabs ) {

		// Taking some variables
		$prefix			= ECSL_META_PREFIX;
		$woo_enable		= ecsl_get_option( 'woo_enable' );
		$woo_tab_shrt	= ecsl_get_option( 'woo_tab_shrt' );

		if( ! empty( $woo_enable ) && ! empty( $woo_tab_shrt ) ) {
			$tabs['ecs_tab'] = array(
								'title'		=> ecsl_get_option( 'woo_tab_text' ),
								'callback'	=> 'ecsl_woo_product_tab',
								'priority'	=> 30,
							);
		}

		return $tabs;
	}
}

$ecsl_public = new ECSL_Public();