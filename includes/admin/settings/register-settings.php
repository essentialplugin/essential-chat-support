<?php
/**
 * Setting Class
 *
 * Handles the Admin side setting options functionality of module
 *
 * @package Essential Chat Support
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Get settings tab
 * 
 * @since 1.0
 */
function ecsl_settings_tab() {

	// Plugin settings tab
	$sett_tabs = array(
					'general'		=> __( 'General', 'essential-chat-support' ),
					'display_rule'	=> __( 'Display Rules', 'essential-chat-support' ),
				);

	// If WooCommerce is exists
	if( class_exists('WooCommerce') ) {
		$sett_tabs[ 'woo_product_tab' ] = __( 'WooCommerce Product Tab', 'essential-chat-support' );
	}

	$sett_tabs[ 'custom_css' ] = __( 'Custom CSS', 'essential-chat-support' );

	return apply_filters( 'ecs_settings_tab', (array)$sett_tabs );
}

/**
 * Function to register plugin settings
 * 
 * @since 1.0
 */
function ecsl_register_settings() {

	// Reset default settings
	if( ! empty( $_POST['ecs_reset_settings'] ) ) {
		ecsl_default_settings();
	}

	register_setting( 'ecs_plugin_options', 'ecs_options', 'ecsl_validate_options' );
}

// Action to register plugin settings
add_action( 'admin_init', 'ecsl_register_settings' );

/**
 * Validate Settings Options
 * 
 * @since 1.0
 */
function ecsl_validate_options( $input ) {

	global $ecs_options;

	$input = $input ? $input : array();

	// Pull out the tab and section
	if ( isset ( $_POST['_wp_http_referer'] ) ) {
		parse_str( $_POST['_wp_http_referer'], $referrer );
	}

	$tab = isset( $referrer['tab'] ) ? ecsl_clean( $referrer['tab'] ) : 'general';

	// Run a general sanitization for the tab for special fields
	$input = apply_filters( 'ecs_sett_sanitize_'.$tab, $input );

	// Run a general sanitization for the custom created tab
	$input = apply_filters( 'ecs_sett_sanitize', $input, $tab );

	// Making merge of old and new input values
	$input = array_merge( $ecs_options, $input );

	return $input;
}

/**
 * Filter to validate General settings
 * 
 * @since 1.0
 */
function ecsl_sanitize_general_sett( $input ) {

	$input['enable'] = isset( $input['enable'] ) ? 1 : 0;

	return $input;
}
add_filter( 'ecs_sett_sanitize_general', 'ecsl_sanitize_general_sett' );

/**
 * Filter to validate Display Rules settings
 * 
 * @since 1.0
 */
function ecsl_sanitize_display_rule_sett( $input ) {

	$input['chatbox_glob_locs'] = ! empty( $input['chatbox_glob_locs'] ) ? ecsl_clean( $input['chatbox_glob_locs'] ) : array();

	return $input;
}
add_filter( 'ecs_sett_sanitize_display_rule', 'ecsl_sanitize_display_rule_sett' );

/**
 * Filter to validate WooCommerce Product Tab settings
 * 
 * @since 1.0
 */
function ecsl_sanitize_woo_product_tab_sett( $input ) {

	$input['woo_enable']	= ! empty( $input['woo_enable'] )	? 1 : 0;
	$input['woo_tab_text']	= ! empty( $input['woo_tab_text'] )	? ecsl_clean( $input['woo_tab_text'] )				: __('Essential Chat Support', 'essential-chat-support');
	$input['woo_tab_shrt']	= ! empty( $input['woo_tab_shrt'] )	? sanitize_textarea_field( $input['woo_tab_shrt'] )	: '';

	return $input;
}
add_filter( 'ecs_sett_sanitize_woo_product_tab', 'ecsl_sanitize_woo_product_tab_sett' );

/**
 * Filter to validate Custom CSS settings
 * 
 * @since 1.0
 */
function ecsl_sanitize_custom_css_sett( $input ) {

	$input['custom_css'] = isset( $input['custom_css'] ) ? sanitize_textarea_field( $input['custom_css'] ) : '';

	return $input;
}
add_filter( 'ecs_sett_sanitize_custom_css', 'ecsl_sanitize_custom_css_sett' );