<?php
/**
 * Plugin Name: Essential Chat Support
 * Plugin URI: https://www.essentialplugin.com/wordpress-plugin/essential-chat-support/
 * Description: Connect, Interact and Offer support to your customers directly as well as build trust and increase loyalty with WhatsApp from any where.
 * Text Domain: essential-chat-support
 * Domain Path: /languages/
 * Author: Essential Plugin
 * Author URI: https://www.essentialplugin.com/
 * Version: 1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( ! defined( 'ECSL_VERSION' ) ) {
	define( 'ECSL_VERSION', '1.0.1' ); // Version of plugin
}

if( ! defined( 'ECSL_DIR' ) ) {
	define( 'ECSL_DIR', dirname( __FILE__ ) ); // Plugin dir
}

if( ! defined( 'ECSL_URL' ) ) {
	define( 'ECSL_URL', plugin_dir_url( __FILE__ ) ); // Plugin url
}

if( ! defined( 'ECSL_PLUGIN_BASENAME' ) ) {
	define( 'ECSL_PLUGIN_BASENAME', plugin_basename( __FILE__ ) ); // Plugin base name
}

if( ! defined( 'ECSL_CONTACT_POST_TYPE' ) ) {
	define( 'ECSL_CONTACT_POST_TYPE', 'ecs_contacts' ); // Plugin post type for contacts
}

if( ! defined( 'ECSL_CW_POST_TYPE' ) ) {
	define( 'ECSL_CW_POST_TYPE', 'ecs_chat_widget' ); // Plugin post type for chat widget
}

if( ! defined( 'ECSL_META_PREFIX' ) ) {
	define( 'ECSL_META_PREFIX', '_ecs_wa_' ); // Plugin metabox prefix
}

if( ! defined( 'ECSL_GROUP_API' ) ) {
	define( 'ECSL_GROUP_API', 'https://chat.whatsapp.com/' ); /* Define Group API */
}

/* Set For Mobile and Desktop Visitor */
if( wp_is_mobile() ) {
	define( 'ECSL_API', 'whatsapp://send' );
} else {
	define( 'ECSL_API', 'https://web.whatsapp.com/send' );
}

/**
 * Load Text Domain and do stuff once all plugin is loaded
 * This gets the plugin ready for translation
 * 
 * @since 1.0
 */
function ecsl_load_textdomain() {

	global $wp_version;

	// Set filter for plugin's languages directory
	$ecsl_lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
	$ecsl_lang_dir = apply_filters( 'ecsl_languages_directory', $ecsl_lang_dir );

	// Traditional WordPress plugin locale filter.
	$get_locale = get_locale();

	if ( $wp_version >= 4.7 ) {
		$get_locale = get_user_locale();
	}

	// Traditional WordPress plugin locale filter
	$locale = apply_filters( 'plugin_locale',  $get_locale, 'essential-chat-support' );
	$mofile = sprintf( '%1$s-%2$s.mo', 'essential-chat-support', $locale );

	// Setup paths to current locale file
	$mofile_global  = WP_LANG_DIR . '/plugins/' . basename( ECSL_DIR ) . '/' . $mofile;

	if ( file_exists( $mofile_global ) ) { // Look in global /wp-content/languages/plugin-name folder
		load_textdomain( 'essential-chat-support', $mofile_global );
	} else { // Load the default language files
		load_plugin_textdomain( 'essential-chat-support', false, $ecsl_lang_dir );
	}
}

// Plugin loaded action
add_action('plugins_loaded', 'ecsl_load_textdomain');

/**
 * Activation Hook
 * 
 * Register plugin activation hook.
 * 
 * @since 1.0
 */
register_activation_hook( __FILE__, 'ecsl_install' );

/**
 * Deactivation Hook
 * Register plugin deactivation hook.
 * 
 * @since 1.0
 */
register_deactivation_hook( __FILE__, 'ecsl_uninstall');

/**
 * Plugin Setup (On Activation)
 * 
 * Does the initial setup,
 * stest default values for the plugin options.
 * 
 * @since 1.0
 */
function ecsl_install() {  

	// Register Post Type
	ecsl_register_post_types();

	// Get settings for the plugin
	$ecs_options = get_option( 'ecs_options' );

	if( empty( $ecs_options ) ) { // Check plugin version option

		// Set default settings
		ecsl_default_settings();

		// Update plugin version to option
		update_option( 'ecsl_plugin_version', '1.0', false );
	}

	// IMP need to flush rules for custom registered post type
	flush_rewrite_rules();

	// Deactivate Premium Version
	if( is_plugin_active('essential-chat-support-pro/essential-chat-support-pro.php') ) {
		add_action('update_option_active_plugins', 'ecsl_deactivate_pro_version');
	}
}

/**
 * Plugin On Deactivation
 * Delete plugin options and etc.
 * 
 * @since 1.0
 */
function ecsl_uninstall() {
	// Uninstall functionality
}

/**
 * Deactivate free plugin
 * 
 * @since 1.0
 */
function ecsl_deactivate_pro_version() {
	deactivate_plugins('essential-chat-support-pro/essential-chat-support-pro.php', true);
}

/**
 * Function to display admin notice of activated plugin.
 * 
 * @since 1.0
 */
function ecsl_admin_notice() {

	global $pagenow;

	// If premium plugin is active and free plugin exist
	if( $pagenow == 'plugins.php' ) {

		$dir				= WP_PLUGIN_DIR . '/essential-chat-support-pro/essential-chat-support-pro.php';
		$notice_link		= add_query_arg( array('message' => 'ecsl-plugin-notice'), admin_url('plugins.php') );
		$notice_transient   = get_transient( 'ecsl_install_notice' );

		if( $notice_transient == false && file_exists( $dir ) && current_user_can( 'install_plugins' ) ) {
			echo '<div class="updated notice" style="position:relative;">
					<p>
						<strong>'.sprintf( __('Thank you for activating %s', 'essential-chat-support'), 'Essential Chat Support').'</strong>.<br/>
						'.sprintf( __('It looks like you had PRO version %s of this plugin activated. To avoid conflicts the extra version has been deactivated and we recommend you delete it.', 'essential-chat-support'), '<strong>(<em>Essential Chat Support Pro</em>)</strong>' ).'
					</p>
					<a href="'.esc_url( $notice_link ).'" class="notice-dismiss" style="text-decoration:none;"></a>
				</div>';
		}
	}
}

// Action to display notice
add_action( 'admin_notices', 'ecsl_admin_notice');

global $ecs_options;

// Function File
require_once( ECSL_DIR . '/includes/ecs-functions.php' );
$ecs_options = ecsl_get_settings( 'ecs_options' );

// Post Type File
require_once( ECSL_DIR . '/includes/ecs-post-types.php' );

// Script Class File
require_once( ECSL_DIR . '/includes/class-ecs-script.php' );

// Shortcode File
require_once( ECSL_DIR . '/includes/shortcode/ecs-contact-shrt.php' );

// Public Class File
require_once( ECSL_DIR . '/includes/class-ecs-public.php' );

// Load Admin side file only
if( is_admin() ) {

	// Plugin Settings
	require_once( ECSL_DIR . '/includes/admin/settings/register-settings.php' );

	// Admin Class File
	require_once( ECSL_DIR . '/includes/admin/class-ecs-admin.php' );
}