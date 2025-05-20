<?php
/**
 * Script Class
 * Handles the script and style functionality of plugin
 *
 * @package Essential Chat Support
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class ECSL_Script {

	function __construct() {

		// Action to add style on frontend
		add_action( 'wp_enqueue_scripts', array($this, 'ecsl_front_end_style_script') );

		// Action to add style in backend
		add_action( 'admin_enqueue_scripts', array($this, 'ecsl_admin_style_script') );

		// Action to add custom css in head
		add_action( 'wp_head', array($this, 'ecsl_add_custom_css'), 20 );
	}

	/**
	 * Function to add style at front side
	 * 
 	 * @since 1.0
 	 */
	function ecsl_front_end_style_script() {

		/* Registering Styles */
		// FontAwesome
		if( ! wp_style_is( 'wpos-font-awesome', 'registered' ) ) {
			wp_register_style( 'wpos-font-awesome', ECSL_URL.'assets/css/font-awesome.min.css', array(), ECSL_VERSION );
		}

		// Public Style
		wp_register_style( 'ecs-public-style', ECSL_URL."assets/css/ecs-public.css", array(), ECSL_VERSION );

		// Enquque Styles
		wp_enqueue_style('wpos-font-awesome');	// FontAwesome
		wp_enqueue_style( 'ecs-public-style' );	// Public


		/* Registering Scripts */

		// Registering Public Script
		wp_register_script( 'ecs-public-script', ECSL_URL."assets/js/ecs-public.js", array('jquery'), ECSL_VERSION, true );
	}

	/**
	 * Enqueue admin styles & scripts
	 * 
	 * @since 1.0
	 */
	function ecsl_admin_style_script( $hook ) {

		global $typenow, $post_type, $wp_version;

		/* Styles */
		// Registring Select 2 Style
		if( ! wp_style_is( 'select2', 'registered' ) ) {
			wp_register_style( 'select2', ECSL_URL.'assets/css/select2.min.css', array(), ECSL_VERSION );
		}

		// Registering admin style
		wp_register_style( 'ecs-admin-style', ECSL_URL.'assets/css/ecs-admin.css', array(), ECSL_VERSION );


		/* Scripts */
		// Registring select 2 script
		if( ! wp_script_is( 'select2', 'registered' ) ) {
			wp_register_script( 'select2', ECSL_URL.'assets/js/select2.min.js', array('jquery'), ECSL_VERSION, true );
		}

		// Registering admin script
		wp_register_script( 'ecs-admin-script', ECSL_URL.'assets/js/ecs-admin.js', array('jquery'), ECSL_VERSION, true );
		wp_localize_script( 'ecs-admin-script', 'ECSLAdmin', array(
														'is_mobile' 				=> wp_is_mobile() ? 1 : 0,
														'code_editor'				=> ( version_compare( $wp_version, '4.9' ) >= 0 )				? 1 : 0,
														'syntax_highlighting'		=> ( 'false' === wp_get_current_user()->syntax_highlighting )	? 0 : 1,
														'reset_msg'					=> esc_js( __( 'Click OK to reset all options. All settings will be lost!', 'essential-chat-support' ) ),
														'select2_input_too_short'	=> esc_js( __( 'Search popup by its name or ID', 'essential-chat-support' ) ),
														'select2_remove_all_items'	=> esc_js( __( 'Remove all items', 'essential-chat-support' ) ),
														'select2_remove_item'		=> esc_js( __( 'Remove item', 'essential-chat-support' ) ),
														'select2_searching'			=> esc_js( __( 'Searching…', 'essential-chat-support' ) ),
													));

		// If Post type `ecs_chat_widget` is there
		if( $typenow == ECSL_CW_POST_TYPE ) {

			wp_enqueue_style('wp-color-picker');	// Color Picker CSS
			wp_enqueue_script('wp-color-picker');	// Color Picker JS
		}

		// If Post types `ecs_contacts` & `ecs_chat_widget` are there
		if( $typenow == ECSL_CONTACT_POST_TYPE || $typenow == ECSL_CW_POST_TYPE ) {

			/* Enqueue Style */
			wp_enqueue_style('select2');			// Select2
			wp_enqueue_style('ecs-admin-style');	// Admin style

			/* Enqueue Script */
			wp_enqueue_script('select2');			// Select2
			wp_enqueue_script('ecs-admin-script');	// Admin script
		}

		// If Setting page is there and check WordPress version then initialize code editor
		if( $hook == ECSL_CONTACT_POST_TYPE.'_page_ecs-settings' && isset( $_GET['tab'] ) && $_GET['tab'] == 'custom_css' && version_compare( $wp_version, '4.9' ) >= 0 ) {

			// WP CSS Code Editor
			wp_enqueue_code_editor( array(
				'type'			=> 'text/css',
				'codemirror'	=> array(
									'indentUnit'	=> 2,
									'tabSize'		=> 2,
									'lint'			=> false,
								),
			));
		}
	}

	/**
	 * Function to add custom css
	 * 
	 * @since 1.0
	 */
	function ecsl_add_custom_css() {

		$custom_css = ecsl_get_option('custom_css');

		if( ! empty( $custom_css ) ) {
			$css  = '<style type="text/css">' . "\n";
			$css .= wp_strip_all_tags( $custom_css ); // Note that esc_html() cannot be used because `div &gt; span` is not interpreted properly.
			$css .= "\n" . '</style>' . "\n";

			echo $css; // WPCS: input var ok.
		}
	}
}

$ecsl_script = new ECSL_Script();