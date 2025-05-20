<?php
/**
 * Admin Class
 *
 * Handles the Admin side functionality of plugin
 *
 * @package Essential Chat Support
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class ECSL_Admin {

	function __construct() {

		// Action to add admin menu
		add_action( 'admin_menu', array( $this, 'ecsl_register_menu' ) );

		// Action to add metabox
		add_action( 'add_meta_boxes', array( $this, 'ecsl_add_agent_metabox' ) );

		// Action to save contact post type meta
		add_action( 'save_post_'.ECSL_CONTACT_POST_TYPE, array( $this, 'ecsl_save_contact_metabox_value' ) );

		// Action to save chat widget post type meta
		add_action( 'save_post_'.ECSL_CW_POST_TYPE, array( $this, 'ecsl_save_cw_metabox_value' ) );

		// Action to add custom column at contact listing
		add_filter( 'manage_'.ECSL_CONTACT_POST_TYPE.'_posts_columns', array( $this, 'ecsl_contact_posts_columns' ) );

		// Action to add custom column at chat widget listing
		add_filter( 'manage_'.ECSL_CW_POST_TYPE.'_posts_columns', array( $this, 'ecsl_chat_widget_posts_columns' ) );

		// Action to add custom column data for contact post type
		add_action('manage_'.ECSL_CONTACT_POST_TYPE.'_posts_custom_column', array( $this, 'ecsl_contact_post_columns_data' ), 10, 2);

		// Action to add custom column data for chat widget post type
		add_action('manage_'.ECSL_CW_POST_TYPE.'_posts_custom_column', array( $this, 'ecsl_chat_widget_post_columns_data' ), 10, 2);

		// Action to get post suggestion
		add_action( 'wp_ajax_ecsl_post_title_sugg', array( $this, 'ecsl_post_title_sugg' ) );
	}

	/**
	 * Function to add menu
	 * 
	 * @since 1.0
	 */
	function ecsl_register_menu() {

		// Register Setting page
		add_submenu_page( 'edit.php?post_type='.ECSL_CONTACT_POST_TYPE, __('Settings - Essential Chat Support', 'essential-chat-support'), __('Settings', 'essential-chat-support'), 'manage_options', 'ecs-settings', array( $this, 'ecsl_settings_page' ) );
	}

	/**
	 * Getting Started Page Html
	 * 
	 * @since 1.0
	 */
	function ecsl_settings_page() {		
		include_once( ECSL_DIR . '/includes/admin/settings/settings.php' );
	}

	/**
	 * Function to register metabox
	 * 
	 * @since 1.0
	 */
	function ecsl_add_agent_metabox() {

		// Contact Metabox
		add_meta_box( 'ecs-contact-details', __( 'Contact Details', 'essential-chat-support' ), array( $this, 'ecsl_contact_meta_box_content' ), ECSL_CONTACT_POST_TYPE, 'normal', 'high' );

		// Contact Shortcode Display
		add_meta_box( 'ecs-shortcode', __( 'Contact Shortcode', 'essential-chat-support' ), array( $this, 'ecsl_shortcode_meta_box_content'), ECSL_CONTACT_POST_TYPE, 'side', 'low' );

		// Chat Widget Metabox
		add_meta_box( 'ecs-cw-details', __( 'Chat Widget Details', 'essential-chat-support' ), array( $this, 'ecsl_cw_meta_box_content' ), ECSL_CW_POST_TYPE, 'normal', 'high' );

		// Add metabox in chat widget post type
		add_meta_box( 'ecs-chat-widget-side', __( 'Chat Widget Settings', 'popup-anything-on-click' ), array($this, 'ecsl_cw_settings_content'), ECSL_CW_POST_TYPE, 'side', 'default' );
	}

	/**
	 * Function to handle contact metabox content
	 * 
	 * @since 1.0
	 */
	function ecsl_contact_meta_box_content() {
		include_once( ECSL_DIR .'/includes/admin/metabox/contacts/post-sett-metabox.php');
	}

	/**
	 * Function to handle chat widget metabox content
	 * 
	 * @since 1.0
	 */
	function ecsl_cw_meta_box_content() {
		include_once( ECSL_DIR .'/includes/admin/metabox/chat-widget/post-sett-metabox.php');
	}

	/**
	 * Function to handle chat widget settings
	 * 
	 * @since 1.0
	 */
	function ecsl_cw_settings_content() {
		include_once( ECSL_DIR .'/includes/admin/metabox/chat-widget/settings.php');
	}

	/**
	 * Function to handle copy shortcode metabox content
	 * 
	 * @since 1.0
	 */
	function ecsl_shortcode_meta_box_content( $post ) {
		echo '<div class="ecs-shortcode-preview ecs-copy-clipboard">[ecs_contact id="'.esc_attr( $post->ID ).'"]</div>';
	}

	/**
	 * Function to save contacts metabox values
	 * 
	 * @since 1.0
	 */
	function ecsl_save_contact_metabox_value( $post_id ) {

		// Taking global variable
		global $post_type;

		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )							// Check Autosave
		|| ( empty( $_POST['post_ID'] ) || absint( $_POST['post_ID'] ) != $post_id )	// Check Revision
		|| ( $post_type != ECSL_CONTACT_POST_TYPE ) )									// Check if correct post type
		{
			return $post_id;
		}

		// Taking some data
		$prefix	= ECSL_META_PREFIX;
		$type	= ! empty( $_POST[$prefix.'type'] )		? ecsl_clean( $_POST[$prefix.'type'] )	: 'agent';
		$status	= ! empty( $_POST[$prefix.'status'] )	? 1 : 0;

		// Agent Settings
		$agent							= array();
		$agent['designation']			= isset( $_POST[$prefix.'agent']['designation'] )			? ecsl_clean( $_POST[$prefix.'agent']['designation'] )					: '';
		$agent['availability_status']	= isset( $_POST[$prefix.'agent']['availability_status'] )	? ecsl_clean( $_POST[$prefix.'agent']['availability_status'] )			: '';
		$agent['custom_message']		= isset( $_POST[$prefix.'agent']['custom_message'] )		? sanitize_textarea_field( $_POST[$prefix.'agent']['custom_message'] )	: '';
		$agent['country_code']			= isset( $_POST[$prefix.'agent']['country_code'] )			? ecsl_clean_number( $_POST[$prefix.'agent']['country_code'] )			: '';
		$agent['whatsapp_number']		= isset( $_POST[$prefix.'agent']['whatsapp_number'] )		? ecsl_clean_number( $_POST[$prefix.'agent']['whatsapp_number'], '' )	: '';

		// Group Settings
		$group					= array();
		$group['id']			= isset( $_POST[$prefix.'group']['id'] )			? ecsl_clean( $_POST[$prefix.'group']['id'] )			: '';
		$group['description']	= isset( $_POST[$prefix.'group']['description'] )	? ecsl_clean( $_POST[$prefix.'group']['description'] )	: '';

		// Update Meta
		update_post_meta( $post_id, $prefix.'type', $type );
		update_post_meta( $post_id, $prefix.'status', $status );
		update_post_meta( $post_id, $prefix.'agent', $agent );
		update_post_meta( $post_id, $prefix.'group', $group );
	}

	/**
	 * Function to save chat widget metabox values
	 * 
	 * @since 1.0
	 */
	function ecsl_save_cw_metabox_value( $post_id ) {

		// Taking global variable
		global $post_type;

		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )							// Check Autosave
		|| ( empty( $_POST['post_ID'] ) || absint( $_POST['post_ID'] ) != $post_id )	// Check Revision
		|| ( $post_type != ECSL_CW_POST_TYPE ) )										// Check if correct post type
		{
			return $post_id;
		}

		// Taking some variables
		$prefix			= ECSL_META_PREFIX;
		$tab			= isset( $_POST[$prefix.'tab'] )			? ecsl_clean( $_POST[$prefix.'tab'] )			: '';
		$display_mode	= isset( $_POST[$prefix.'display_mode'] )	? ecsl_clean( $_POST[$prefix.'display_mode'] )	: '';

		// Behaviour Tab Settings
		$behaviour						= array();
		$behaviour['include_contacts']	= isset( $_POST[$prefix.'behaviour']['include_contacts'] )	? ecsl_clean( $_POST[$prefix.'behaviour']['include_contacts'] )	: array();
		$behaviour['display_type']		= isset( $_POST[$prefix.'behaviour']['display_type'] )		? ecsl_clean( $_POST[$prefix.'behaviour']['display_type'] )		: 'both';
		$behaviour['position']			= isset( $_POST[$prefix.'behaviour']['position'] )			? ecsl_clean( $_POST[$prefix.'behaviour']['position'] )			: 'right-bottom';

		// Content Tab Settings
		$content					= array();
		$content['sub_title']		= isset( $_POST[$prefix.'content']['sub_title'] )		? ecsl_clean( $_POST[$prefix.'content']['sub_title'] )			: '';
		$content['notice_msg']		= isset( $_POST[$prefix.'content']['notice_msg'] )		? ecsl_clean( $_POST[$prefix.'content']['notice_msg'] )			: '';
		$content['toggle_btn_text']	= isset( $_POST[$prefix.'content']['toggle_btn_text'] )	? ecsl_clean( $_POST[$prefix.'content']['toggle_btn_text'] )	: '';
		$content['main_title']		= ! empty( $_POST[$prefix.'content']['main_title'] )	? ecsl_clean( $_POST[$prefix.'content']['main_title'] )			: __('Start a Conversation', 'essential-chat-support');
		$content['chat_title']		= ! empty( $_POST[$prefix.'content']['chat_title'] )	? ecsl_clean( $_POST[$prefix.'content']['chat_title'] )			: __('WhatsApp Live Chat', 'essential-chat-support');

		// Design Tab Settings
		$design							= array();
		$design['theme_bg_clr']			= ! empty( $_POST[$prefix.'design']['theme_bg_clr'] )		? ecsl_clean_color( $_POST[$prefix.'design']['theme_bg_clr'] )		: '#095e54';
		$design['theme_text_clr']		= ! empty( $_POST[$prefix.'design']['theme_text_clr'] )		? ecsl_clean_color( $_POST[$prefix.'design']['theme_text_clr'] )	: '#ffffff';
		$design['chatbox_bg_clr']		= ! empty( $_POST[$prefix.'design']['chatbox_bg_clr'] )		? ecsl_clean_color( $_POST[$prefix.'design']['chatbox_bg_clr'] )	: '#e5ddd5';
		$design['chatbox_text_clr']		= ! empty( $_POST[$prefix.'design']['chatbox_text_clr'] )	? ecsl_clean_color( $_POST[$prefix.'design']['chatbox_text_clr'] )	: '#666666';
		$design['tooltip_bg_clr']		= ! empty( $_POST[$prefix.'design']['tooltip_bg_clr'] )		? ecsl_clean_color( $_POST[$prefix.'design']['tooltip_bg_clr'] )	: '#efefef';
		$design['tooltip_text_clr']		= ! empty( $_POST[$prefix.'design']['tooltip_text_clr'] )	? ecsl_clean_color( $_POST[$prefix.'design']['tooltip_text_clr'] )	: '#43474e';
		$design['online_border_clr']	= ! empty( $_POST[$prefix.'design']['online_border_clr'] )	? ecsl_clean_color( $_POST[$prefix.'design']['online_border_clr'] )	: '#2db742';

		// Advance Tab Settings
		$advance				= array();
		$advance['display_on']	= isset( $_POST[$prefix.'advance']['display_on'] ) ? ecsl_clean( $_POST[$prefix.'advance']['display_on'] ) : 'every_device';

		// Update post meta
		update_post_meta( $post_id, $prefix.'tab', $tab );
		update_post_meta( $post_id, $prefix.'display_mode', $display_mode );
		update_post_meta( $post_id, $prefix.'behaviour', $behaviour );
		update_post_meta( $post_id, $prefix.'content', $content );
		update_post_meta( $post_id, $prefix.'design', $design );
		update_post_meta( $post_id, $prefix.'advance', $advance );
	}

	/**
	 * Add custom column to contact listing page
	 * 
	 * @since 1.0
	 */
	function ecsl_contact_posts_columns( $columns ) {

		$new_columns['ecs_type']		= esc_html__( 'Type', 'essential-chat-support' );
		$new_columns['ecs_status']		= esc_html__( 'Status', 'essential-chat-support' );
		$new_columns['ecs_image']		= esc_html__( 'Profile Image', 'essential-chat-support' );
		$new_columns['ecs_shortcode']	= esc_html__( 'Shortcode', 'essential-chat-support' );

		$columns = ecsl_add_array( $columns, $new_columns, 1, true );

		return $columns;
	}

	/**
	 * Add custom column to chat widget listing page
	 * 
	 * @since 1.0
	 */
	function ecsl_chat_widget_posts_columns( $columns ) {

		$new_columns['ecs_display_mode']	= esc_html__( 'Display Mode', 'essential-chat-support' );
		$new_columns['ecs_display_type']	= esc_html__( 'Display Contact Type', 'essential-chat-support' );
		$new_columns['ecs_display_on']		= esc_html__( 'Display On', 'essential-chat-support' );

		$columns = ecsl_add_array( $columns, $new_columns, 1, true );

		return $columns;
	}

	/**
	 * Add custom column data for contact post type
	 * 
	 * @since 1.0
	 */
	function ecsl_contact_post_columns_data( $column, $post_id ) {

		global $post;

		// Taking some variable
		$prefix = ECSL_META_PREFIX;

		switch ( $column ) {

			case 'ecs_type':

				$type = get_post_meta( $post_id, $prefix.'type', true );

				echo esc_html( ucfirst( $type ) );
				break;

			case 'ecs_status':

				$status = get_post_meta( $post_id, $prefix.'status', true );
				$status = empty( $status ) ? __('Deactive', 'essential-chat-support') : __('Active', 'essential-chat-support');

				echo esc_html( $status );
				break;

			case 'ecs_image':

				$image = ecsl_get_featured_image( $post_id, 'thumbnail' );

				if( $image ) {
					echo '<img class="ecs-avatar-image" height="40" width="40" src="'.esc_url( $image ).'" alt="" />';
				} else {
					echo '--';
				}

				break;

			case 'ecs_shortcode':

				echo '<div class="ecs-copy-clipboard ecs-shortcode-preview">[ecs_contact id="'.esc_attr( $post_id ).'"]</div>';
				break;
		}
	}

	/**
	 * Add custom column data for chat widget post type
	 * 
	 * @since 1.0
	 */
	function ecsl_chat_widget_post_columns_data( $column, $post_id ) {

		global $post;

		// Taking some variable
		$prefix	= ECSL_META_PREFIX;

		switch ( $column ) {

			case 'ecs_display_mode':

				$displa_mode_arr	= ecsl_get_display_modes();
				$display_mode		= get_post_meta( $post_id, $prefix.'display_mode', true );
				$display_mode		= isset( $displa_mode_arr[ $display_mode ] ) ? $displa_mode_arr[ $display_mode ] : $display_mode;

				echo esc_html( $display_mode );
				break;

			case 'ecs_display_type':

				$behaviour			= get_post_meta( $post_id, $prefix.'behaviour', true );
				$display_type_arr	= ecsl_get_display_types();
				$display_type		= isset( $display_type_arr[ $behaviour['display_type'] ] ) ? $display_type_arr[ $behaviour['display_type'] ] : __('Both', 'essential-chat-support');

				echo esc_html( $display_type );
				break;

			case 'ecs_display_on':

				$display_on_data	= ecsl_display_on_options();
				$advance			= get_post_meta( $post_id, $prefix.'advance', true );
				$display_on			= isset( $advance['display_on'] )			? $advance['display_on']			: 'every_device';
				$display_on			= isset( $display_on_data[ $display_on ] )	? $display_on_data[ $display_on ]	: $display_on;

				echo esc_html( $display_on );
				break;
		}
	}

	/**
	 * Function to get post suggestion based on search input
	 * 
	 * @since 1.0
	 */
	function ecsl_post_title_sugg() {

		$return		= array();
		$prefix		= ECSL_META_PREFIX;
		$post_type	= isset( $_GET['post_type'] )	? ecsl_clean( $_GET['post_type'] )	: ECSL_CONTACT_POST_TYPE;
		$search		= isset( $_GET['search'] )		? ecsl_clean( $_GET['search'] )		: '';
		$nonce		= isset( $_GET['nonce'] )		? ecsl_clean( $_GET['nonce'] )		: '';
		$meta_data	= isset( $_GET['meta_data'] )	? ecsl_clean( $_GET['meta_data'] )	: '';

		// Verify Nonce
		if( $search && wp_verify_nonce( $nonce, 'ecs-post-title-sugg' ) ) {

			$args	= array(
						's'					=> $search,
						'post_type'			=> $post_type,
						'post_status'		=> array('publish'),
						'order'				=> 'ASC',
						'orderby'			=> 'title',
						'posts_per_page'	=> 20
					);

			// If number is passed
			if( is_numeric( $search ) ) {
				$args['s'] = false;
				$args['p'] = $search;
			}

			// If meta query is set
			if( $post_type == ECSL_CONTACT_POST_TYPE && $meta_data != 'both' ) {
				$args['meta_query']	= array(
										array(
											'key'		=> $prefix.'type',
											'value'		=> $meta_data,
											'compare'	=> '=',
										)
									);
			}

			$search_query = get_posts( $args );

			if( $search_query ) :

				foreach ( $search_query as $search_data ) {
					
					$post_id	= ! empty( $search_data->ID )			? ecsl_clean_number( $search_data->ID )			: 0;
					$post_title	= ! empty( $search_data->post_title )	? ecsl_clean_html( $search_data->post_title )	: __('Post', 'essential-chat-support');
					$post_title	= $post_title . " - #" . $post_id;

					$return[]	= array( $post_id, $post_title );
				}

			endif;
		}

		wp_send_json( $return );
	}
}

$ecsl_admin = new ECSL_Admin();