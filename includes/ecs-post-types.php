<?php
/**
 * Register Post types functionality
 *
 * @package Essential Chat Support
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Function to register post types
 * 
 * @since 1.0
 */
function ecsl_register_post_types () {

	/***** Contact Post Type *****/
	$ecs_contact_labels = apply_filters( 'ecs_contact_post_labels', array(
							'name'					=> __( 'WhatsApp Contacts', 'essential-chat-support' ),
							'singular_name'			=> __( 'WhatsApp Contact', 'essential-chat-support' ),
							'add_new'				=> __( 'Add New Contact', 'essential-chat-support' ),
							'add_new_item'			=> __( 'Add New Contact', 'essential-chat-support' ),
							'edit_item'				=> __( 'Edit Contact', 'essential-chat-support' ),
							'new_item'				=> __( 'New Contact', 'essential-chat-support' ),
							'all_items'				=> __( 'All Contacts', 'essential-chat-support' ),
							'view_item'				=> __( 'View Contacts', 'essential-chat-support' ),
							'search_items'			=> __( 'Search Contacts', 'essential-chat-support' ),
							'not_found'				=> __( 'No Contacts Found', 'essential-chat-support' ),
							'not_found_in_trash'	=> __( 'No Contacts Found in Trash', 'essential-chat-support' ),
							'parent_item_colon'		=> '',
							'featured_image'		=> __( 'Profile Image', 'essential-chat-support' ),
							'set_featured_image'	=> __( 'Set Profile image', 'essential-chat-support' ),
							'remove_featured_image'	=> __( 'Remove Profile image', 'essential-chat-support' ),
							'use_featured_image'	=> __( 'Use as profile image', 'essential-chat-support' ),
							'insert_into_item'		=> __( 'Insert into profile', 'essential-chat-support' ),
							'uploaded_to_this_item'	=> __( 'Uploaded to this profile', 'essential-chat-support' ),
							'menu_name'				=> __( 'WhatsApp Chat', 'essential-chat-support' ),
						));

	$ecs_contact_args = array(
						'labels'				=> $ecs_contact_labels,
						'public'				=> false,
						'show_ui'				=> true,
						'show_in_menu'			=> true,
						'query_var'				=> false,
						'exclude_from_search'	=> true,
						'rewrite'				=> false,
						'capability_type'		=> 'post',
						'hierarchical'			=> false,
						'supports'				=> apply_filters( 'ecs_contact_post_supports', array('title', 'thumbnail') ),
						'menu_icon'				=> 'dashicons-format-chat',
					);

	// Register Contacts post type
	register_post_type( ECSL_CONTACT_POST_TYPE, apply_filters('ecs_contact_post_args', $ecs_contact_args) );


	/***** Chat Widget Post Type *****/
	$ecs_chat_widget_labels = apply_filters( 'ecs_chat_widget_post_labels', array(
								'name'					=> __( 'WhatsApp Chat Widgets', 'essential-chat-support' ),
								'singular_name'			=> __( 'WhatsApp Chat Widget', 'essential-chat-support' ),
								'add_new'				=> __( 'Add New Chat Widget', 'essential-chat-support' ),
								'add_new_item'			=> __( 'Add New Chat Widget', 'essential-chat-support' ),
								'edit_item'				=> __( 'Edit Chat Widget', 'essential-chat-support' ),
								'new_item'				=> __( 'New Chat Widget', 'essential-chat-support' ),
								'all_items'				=> __( 'All Chat Widgets', 'essential-chat-support' ),
								'view_item'				=> __( 'View Chat Widgets', 'essential-chat-support' ),
								'search_items'			=> __( 'Search Chat Widgets', 'essential-chat-support' ),
								'not_found'				=> __( 'No Chat Widgets Found', 'essential-chat-support' ),
								'not_found_in_trash'	=> __( 'No Chat Widgets Found in Trash', 'essential-chat-support' ),
								'parent_item_colon'		=> '',
								'featured_image'		=> __( 'Profile Image', 'essential-chat-support' ),
								'set_featured_image'	=> __( 'Set Profile image', 'essential-chat-support' ),
								'remove_featured_image'	=> __( 'Remove Profile image', 'essential-chat-support' ),
								'use_featured_image'	=> __( 'Use as profile image', 'essential-chat-support' ),
								'insert_into_item'		=> __( 'Insert into profile', 'essential-chat-support' ),
								'uploaded_to_this_item'	=> __( 'Uploaded to this profile', 'essential-chat-support' ),
								'menu_name'				=> __( 'WhatsApp Chat', 'essential-chat-support' ),
							));

	$ecs_chat_widget_args = array(
							'labels'				=> $ecs_chat_widget_labels,
							'public'				=> false,
							'show_ui'				=> true,
							'show_in_menu'			=> 'edit.php?post_type='.ECSL_CONTACT_POST_TYPE,
							'query_var'				=> false,
							'exclude_from_search'	=> true,
							'rewrite'				=> false,
							'capability_type'		=> 'post',
							'hierarchical'			=> false,
							'supports'				=> apply_filters( 'ecs_chat_widget_post_supports', array('title') ),
							'menu_icon'				=> 'dashicons-format-chat',
						);

	// Register Chat Widget post type
	register_post_type( ECSL_CW_POST_TYPE, apply_filters('ecs_chat_widget_post_args', $ecs_chat_widget_args) );
}

// Action to register post types
add_action( 'init', 'ecsl_register_post_types' );

/**
 * Function to update post message for Essential Chat Support post types
 * 
 * @since 1.0
 */
function ecsl_post_updated_messages( $messages ) {

	global $post;

	// Contact Post Type
	$messages[ECSL_CONTACT_POST_TYPE] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => __( 'Contact updated.', 'essential-chat-support' ),
		2 => __( 'Custom field updated.', 'essential-chat-support' ),
		3 => __( 'Custom field deleted.', 'essential-chat-support' ),
		4 => __( 'Contact updated.', 'essential-chat-support' ),
		5 => isset( $_GET['revision'] ) ? sprintf( __( 'Contact restored to revision from %s', 'essential-chat-support' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => __( 'Contact published.', 'essential-chat-support' ),
		7 => __( 'Contact saved.', 'essential-chat-support' ),
		8 => __( 'Contact submitted. ', 'essential-chat-support' ),
		9 => sprintf( __( 'Contact scheduled for: <strong>%1$s</strong>.', 'essential-chat-support' ),
			date_i18n( 'M j, Y @ G:i', strtotime($post->post_date) ) ),
		10 => __( 'Contact draft updated.', 'essential-chat-support' ),
	);

	// Chat Widget Post Type
	$messages[ECSL_CW_POST_TYPE] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => __( 'Chat Widget updated.', 'essential-chat-support' ),
		2 => __( 'Custom field updated.', 'essential-chat-support' ),
		3 => __( 'Custom field deleted.', 'essential-chat-support' ),
		4 => __( 'Chat Widget updated.', 'essential-chat-support' ),
		5 => isset( $_GET['revision'] ) ? sprintf( __( 'Chat Widget restored to revision from %s', 'essential-chat-support' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => __( 'Chat Widget published.', 'essential-chat-support' ),
		7 => __( 'Chat Widget saved.', 'essential-chat-support' ),
		8 => __( 'Chat Widget submitted. ', 'essential-chat-support' ),
		9 => sprintf( __( 'Chat Widget scheduled for: <strong>%1$s</strong>.', 'essential-chat-support' ),
			date_i18n( 'M j, Y @ G:i', strtotime($post->post_date) ) ),
		10 => __( 'Chat Widget draft updated.', 'essential-chat-support' ),
	);

	return $messages;
}

// Filter to update essential chat support post message
add_filter( 'post_updated_messages', 'ecsl_post_updated_messages' );