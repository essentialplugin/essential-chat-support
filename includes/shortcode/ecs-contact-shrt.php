<?php
/**
 * Contact Shortcode `ecs_contact`
 * 
 * @package Essential Chat Support
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Function to handle Contact shortcode
 * 
 * @since 1.0
 */
function ecsl_render_contact_shortcode( $atts, $content ) {

	// Taking some globals
	global $post, $product;

	$atts = shortcode_atts( array(
		'id'		=> '',
		'type'		=> '',
		'grid'		=> 2,
		'limit'		=> 15,
	), $atts, 'ecs_contact');

	$atts['grid']	= ecsl_clean_number( $atts['grid'], 2 );
	$atts['limit']	= ecsl_clean_number( $atts['limit'], 15, 'number' );
	$atts['posts']	= ! empty( $atts['id'] )	? explode( ',', $atts['id'] )	: array();
	$atts['type']	= isset( $atts['type'] )	? ecsl_clean( $atts['type'] )	: '';

	extract( $atts );

	// Taking variables
	$prefix = ECSL_META_PREFIX;

	// Taking pre-filled tag data
	$current_url	= ecsl_get_current_page_url();
	$post_id		= isset( $post->ID )			? $post->ID			: '';
	$post_title		= isset( $post->post_title )	? $post->post_title	: '';
	$post_name		= isset( $post->post_name )		? $post->post_name	: '';
	$price			= '';
	$regular_price	= '';
	$sku			= '';

	if( class_exists('WooCommerce') && ! empty( $product ) ) {
		$price			= ! empty( $product )	? $product->get_price()			: '';
		$regular_price	= ! empty( $product )	? $product->get_regular_price()	: '';
		$sku			= ! empty( $product )	? $product->get_sku()			: '';
	}

	// Query Parameter
	$args = array (
		'post_type'				=> ECSL_CONTACT_POST_TYPE,
		'post_status'			=> array( 'publish' ),
		'orderby'				=> 'date',
		'order'					=> 'DESC',
		'posts_per_page'		=> $limit,
		'post__in'				=> $posts,
		'ignore_sticky_posts'	=> 1,
		'no_found_rows'			=> 1,
	);

	// Meta Query for `Status`
	$args['meta_query'] = array(
							'relation' => 'AND',
							array(
								'key'		=> $prefix.'status',
								'value'		=> 1,
								'compare'	=> '=',
							),
						);

	// Meta Query for `Type`
	if( $type == 'agent' || $type == 'group' ) {
		$args['meta_query'][] = array(
								'key'		=> $prefix.'type',
								'value'		=> $type,
								'compare'	=> '=',
							);
	}

	// WP Query
	$query = new WP_Query( $args );

	// Enqueue Public Script
	wp_enqueue_script( 'ecs-public-script' );

	ob_start();

	// If post is there
	if ( $query->have_posts() ) {

		include( ECSL_DIR. '/templates/grid/loop-start.php' ); // Loop End File

		while ( $query->have_posts() ) : $query->the_post();

			// Taking some variables
			$icon_class		= "fa-fa-users";
			$featured_img	= ecsl_get_featured_image( $post->ID, 'thumbnail', ECSL_URL ."assets/images/person-placeholder.png" );
			$type			= get_post_meta( $post->ID, $prefix.'type', true );
			$type			= ! empty( $type ) ? $type : 'agent';
			$contact_name	= ! empty( $post->post_title ) ? $post->post_title : ucfirst( $type ).' - '.$post->ID;

			// CSS class
			$css_class = "ecs-grid ecs-online ecs-{$type}-wrap ecs-icol-{$grid} ecs-icolumns";

			// If contact type is `Group`
			if( $type == 'group' ) {

				// Taking some group data
				$icon_class	= "fa-fa-whatsapp";
				$group		= get_post_meta( $post->ID, $prefix.'group', true );
				$id			= ! empty( $group['id'] ) ? $group['id'] : '';

				// If Group ID is not there then skip
				if( ! $id ) {
					continue;
				}

				// Taking some variables
				$contact_desc	= ! empty( $group['description'] ) ? $group['description']	: '';
				$whatsapp_url	= ECSL_GROUP_API . esc_attr( $id );

			} else { // Else contact type is `Agent`

				// Taking some agent data
				$agent				= get_post_meta( $post->ID, $prefix.'agent', true );
				$whatsapp_number	= ! empty( $agent['whatsapp_number'] )	? $agent['whatsapp_number'] : '';
				$country_code		= isset( $agent['country_code'] )		? $agent['country_code']	: '';

				// If WhatsApp Number & Country Code are not there
				if( ! $whatsapp_number || ! $country_code ) {
					continue;
				}

				// Taking some variables
				$contact_desc			= isset( $agent['designation'] )			? $agent['designation']				: '';
				$availability_status	= isset( $agent['availability_status'] )	? $agent['availability_status']		: '';

				// Custom Message
				$custom_message	= false;
				
				if( ! empty( $agent['custom_message'] ) ) {

					$custom_message	= str_replace( "\n", "%0a", $agent['custom_message'] );
					$custom_message	= str_replace(
										array('{ID}', '{title}', '{slug}', '{url}', '{price}', '{regular_price}', '{sku}'),
										array( $post_id, $post_title, $post_name, $current_url, $price, $regular_price, $sku ),
										$custom_message
									);
				}

				$whatsapp_url	= add_query_arg(
									array(
										'phone'	=> esc_attr( $country_code . $whatsapp_number ),
										'text'	=> ecsl_clean_html( $custom_message ),
									),
									ECSL_API
								);
			}

			// Design File
			include( ECSL_DIR. "/templates/grid/design-1.php" );

		endwhile;

		include( ECSL_DIR. '/templates/grid/loop-end.php' ); // Loop End File
	}

	wp_reset_postdata(); // Reset WP Query

	$content .= ob_get_clean();
	return $content;
}

// Contact List Shortcode
add_shortcode( 'ecs_contact', 'ecsl_render_contact_shortcode' );