<?php
/**
 * Functions File
 *
 * @package Essential Chat Support
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Update default settings
 * 
 * @since 1.0
 */
function ecsl_default_settings() {

	global $ecs_options;

	$ecs_options = array(
					'enable'		=> 1,
					'woo_enable'	=> 0,
					'custom_css'	=> '',
					'woo_tab_shrt'	=> '',
					'woo_tab_text'	=> __('Essential Chat Support', 'essential-chat-support'),
				);

	$default_options = apply_filters( 'ecs_options_default_values', $ecs_options );

	// Update default options
	update_option( 'ecs_options', $default_options );

	// Overwrite global variable when option is update	
	$ecs_options = ecsl_get_settings( 'ecs_options' );
}

/**
 * Get Settings From Option Page
 * Handles to return all settings value
 * 
 * @since 1.0
 */
function ecsl_get_settings() {

	$options = get_option('ecs_options');

	$settings = is_array( $options ) ? $options : array();

	return $settings;
}

/**
 * Get an option
 * Looks to see if the specified setting exists, returns default if not
 * 
 * @since 1.0
 */
function ecsl_get_option( $key = '', $default = false ) {

	global $ecs_options;

	$value = ! empty( $ecs_options[ $key ] ) ? $ecs_options[ $key ] : $default;
	$value = apply_filters( 'ecs_get_option', $value, $key, $default );

	return apply_filters( 'ecs_get_option_' . $key, $value, $key, $default );
}

/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 * 
 * @since 1.0
 */
function ecsl_clean( $var ) {
	if ( is_array( $var ) ) {
		return array_map( 'ecsl_clean', $var );
	} else {
		$data = is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
		return wp_unslash( $data );
	}
}

/**
 * Sanitize number value and return fallback value if it is blank
 * 
 * @since 1.0
 */
function ecsl_clean_number( $var, $fallback = null, $type = 'int' ) {

	$var = trim( $var );
	$var = is_numeric( $var ) ? $var : 0;

	if ( $type == 'number' ) {
		$data = intval( $var );
	} else if ( $type == 'abs' ) {
		$data = abs( $var );
	} else if ( $type == 'float' ) {
		$data = (float)$var;
	} else {
		$data = absint( $var );
	}

	return ( empty( $data ) && isset( $fallback ) ) ? $fallback : $data;
}

/**
 * Sanitize color value and return fallback value if it is blank
 * 
 * @since 1.0
 */
function ecsl_clean_color( $color, $fallback = null ) {

	if ( false === strpos( $color, 'rgba' ) ) {
		
		$data = sanitize_hex_color( $color );

	} else {

		$red	= 0;
		$green	= 0;
		$blue	= 0;
		$alpha	= 0.5;

		// By now we know the string is formatted as an rgba color so we need to further sanitize it.
		$color = str_replace( ' ', '', $color );
		sscanf( $color, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );
		$data = 'rgba('.$red.','.$green.','.$blue.','.$alpha.')';
	}

	return ( empty( $data ) && $fallback ) ? $fallback : $data;
}

/**
 * Clean Html Tags
 * Allow only WordPress Post supported HTML tags.
 * 
 * @since 1.0
 */
function ecsl_clean_html( $data = array() ) {

	if ( is_array( $data ) ) {

		$data = array_map('ecsl_clean_html', $data);

	} elseif ( is_string( $data ) ) {

		$data = trim( $data );
		$data = wp_filter_post_kses( $data );
	}

	return $data;
}

/**
 * Function to add array after specific key
 * 
 * @since 1.0
 */
function ecsl_add_array( &$array, $value, $index, $from_last = false ) {

	if( is_array( $array ) && is_array( $value ) ) {

		if( $from_last ) {
			$total_count	= count( $array );
			$index			= ( ! empty( $total_count ) && ( $total_count > $index ) ) ? ( $total_count - $index ) : $index;
		}
		
		$split_arr	= array_splice( $array, max( 0, $index ) );
		$array		= array_merge( $array, $value, $split_arr );
	}

	return $array;
}

/**
 * Function get display mode options
 * 
 * @since 1.0
 */
function ecsl_get_display_modes() {

	$display_modes = array(
		'multi-mode'	=> __('Multi Mode', 'essential-chat-support'),
		'single-mode'	=> __('Single Mode', 'essential-chat-support'),
	);

	return apply_filters( 'ecs_get_display_modes', $display_modes );
}

/**
 * Function get display type options
 * 
 * @since 1.0
 */
function ecsl_get_display_types() {

	$display_types = array(
		'both'	=> __('Both', 'essential-chat-support'),
		'agent'	=> __('Agent', 'essential-chat-support'),
		'group'	=> __('Group', 'essential-chat-support'),
	);

	return apply_filters( 'ecs_get_display_types', $display_types );
}

/**
 * Function get position options
 * 
 * @since 1.0
 */
function ecsl_get_positions() {

	$positions = array(
		'right-bottom'	=> __('Right Bottom', 'essential-chat-support'),
		'left-bottom'	=> __('Left Bottom', 'essential-chat-support'),
	);

	return apply_filters( 'ecs_get_positions', $positions );
}

/**
 * Function to get display on options
 * 
 * @since 1.0
 */
function ecsl_display_on_options() {

	$display_on_opts = array(
						'every_device'	=> __('Every Device', 'essential-chat-support'),
						'desktop_only'	=> __('Desktop Only', 'essential-chat-support'),
						'mobile_only'	=> __('Mobile Only', 'essential-chat-support'),
					);

	return apply_filters( 'ecs_display_on_options', $display_on_opts );
}

/**
 * Function to display location.
 * 
 * @since 1.0
 */
function ecsl_display_locations( $type = 'all', $all = true, $exclude = array() ) {

	$locations		= array();
	$exclude		= array_merge( array('attachment', 'revision', 'nav_menu_item'), $exclude);
	$all_post_types	= ecsl_get_post_types();
	$post_types		= array();

	foreach ( $all_post_types as $post_type => $post_data ) {
		if( $all ) {
			$type_label = __( 'All', 'essential-chat-support' ) .' '. $post_data;
		} else {
			$type_label = $post_data;
		}

		$locations[ $post_type ] = $type_label;
	}

	if ( 'global' != $type ) {
		
		$glocations = array(
			'is_front_page'	=> __( 'Front Page', 'essential-chat-support' ),
			'is_search'		=> __( 'Search Results', 'essential-chat-support' ),
			'is_404'		=> __( '404 Error Page', 'essential-chat-support' ),
			'is_archive'	=> __( 'All Archives', 'essential-chat-support' ),
			'all'			=> __( 'Whole Site', 'essential-chat-support' ),
		);

		$locations = array_merge( $locations, $glocations );	
	}

	// Exclude some post type or location
	if( ! empty( $exclude ) ) {
		foreach ($exclude as $location_key) {
			unset( $locations[ $location_key ] );
		}
	}

	return $locations;
}

/**
 * Function to get registered post types
 * 
 * @since 1.0
 */
function ecsl_get_post_types( $args = array(), $exclude_post = array() ) {     

	$post_types 		= array();
	$args       		= ( ! empty($args) && is_array($args) ) ? $args : array( 'public' => true );
	$default_post_types = get_post_types( $args, 'name' );
	$exclude_post 		= ! empty($exclude_post) ? (array) $exclude_post : array();

	if( ! empty( $default_post_types ) ) {
		foreach ($default_post_types as $post_type_key => $post_data) {
			if( ! in_array( $post_type_key, $exclude_post ) ) {
				$post_types[$post_type_key] = $post_data->label;
			}
		}
	}

	return apply_filters( 'ecs_get_post_types', $post_types );
}

/**
 * Function to display message, norice etc
 * 
 * @since 1.0
 */
function ecsl_display_message( $type = 'update', $msg = '', $echo = 1 ) {

	switch ( $type ) {
		case 'reset':
			$msg = ! empty( $msg ) ? $msg : __( 'All settings reset successfully.', 'essential-chat-support');
			$msg_html = '<div id="message" class="updated notice notice-success is-dismissible">
							<p><strong>' . $msg . '</strong></p>
						</div>';
			break;

		case 'error':
			$msg = ! empty( $msg ) ? $msg : __( 'Sorry, Something happened wrong.', 'essential-chat-support');
			$msg_html = '<div id="message" class="error notice is-dismissible">
							<p><strong>' . $msg . '</strong></p>
						</div>';
			break;

		default:
			$msg = ! empty( $msg ) ? $msg : __('Your changes saved successfully.', 'essential-chat-support');
			$msg_html = '<div id="message" class="updated notice notice-success is-dismissible">
							<p><strong>'. $msg .'</strong></p>
						</div>';
			break;
	}

	if( $echo ) {
		echo wp_kses_post( $msg_html );
	} else {
		return wp_kses_post( $msg_html );
	}
}

/**
 * Function to return wheather chatbox is active or not.
 * 
 * @since 1.0
 */
function ecsl_get_chatbox_id() {

	global $post, $ecs_chatbox_ids;

	$prefix				= ECSL_META_PREFIX;
	$chatbox_glob_locs	= ecsl_get_option( 'chatbox_glob_locs' );
	$ecs_post_type		= isset( $post->post_type ) ? $post->post_type : '';
	$custom_location	= false;
	$ecs_chatbox_ids	= '';

	// Post Type Wise
	if( is_singular() && ! empty( $chatbox_glob_locs[ $ecs_post_type ] ) ) {
		return $ecs_chatbox_ids = $chatbox_glob_locs[ $ecs_post_type ];
	}

	// Checking custom locations
	if( is_search() ) {
		$custom_location = "is_search";
	} else if( is_404() ) {
		$custom_location = "is_404";
	} else if( is_archive() ) {
		$custom_location = "is_archive";
	} else if( is_front_page() ) {
		$custom_location = "is_front_page";
	}

	if( $custom_location && ! empty( $chatbox_glob_locs[ $custom_location ] ) ) {
		return $ecs_chatbox_ids = $chatbox_glob_locs[ $custom_location ];
	}

	// Whole Website
	if( ! empty( $chatbox_glob_locs['all'] ) ) {
		return $ecs_chatbox_ids = $chatbox_glob_locs['all'];
	}

	return $ecs_chatbox_ids;
}

/**
 * Function to get current page URL
 * 
 * @since 1.0
 */
function ecsl_get_current_page_url( $args = array() ) {

	$curent_page_url = home_url( add_query_arg( null, null ) );

	// Remove Query Args
	if( isset( $args['remove_args'] ) ) {
		$curent_page_url = remove_query_arg( $args['remove_args'], $curent_page_url );
	}

	return apply_filters( 'ecs_get_current_page_url', $curent_page_url );
}

/**
 * Function to Print the shortcode on WooCommerce Product Single Page
 * 
 * @since 1.0
 */
function ecsl_woo_product_tab() {

	global $post;

	// Taking some variables
	$prefix			= ECSL_META_PREFIX;
	$woo_tab_shrt	= ecsl_get_option( 'woo_tab_shrt' );

	// Print shortcode
	echo do_shortcode( wpautop( wptexturize( $woo_tab_shrt ) ) );
}

/**
 * Function to get post featured image
 *
 * @since 1.0
 */
function ecsl_get_featured_image( $post_id = '', $size = 'full', $default_img = false ) {

	$size	= ! empty( $size ) ? $size : 'full';
	$image	= wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $size );

	if( ! empty( $image ) ) {
		$image = isset( $image[0] ) ? $image[0] : '';
	}

	// Getting default image
	if( $default_img && empty( $image ) ) {
		return $default_img;
	}

	return $image;
}

/**
 * Function to get country phone codes
 * 
 * @since 1.0
 */
function ecsl_country_phone_codes() {

	return apply_filters( 'ecs_country_phone_codes', array(
			'93'	=> __('Afghanistan (+93)', 'essential-chat-support'),
			'355'	=> __('Albania (+355)', 'essential-chat-support'),
			'213'	=> __('Algeria (+213)', 'essential-chat-support'),
			'684'	=> __('American Samoa (+684)', 'essential-chat-support'),
			'376'	=> __('Andorra (+376)', 'essential-chat-support'),
			'244'	=> __('Angola (+244)', 'essential-chat-support'),
			'1264'	=> __('Anguilla (+1264)', 'essential-chat-support'),
			'672'	=> __('Antarctica (+672)', 'essential-chat-support'),
			'1268'	=> __('Antigua and Barbuda (+1268)', 'essential-chat-support'),
			'54'	=> __('Argentina (+54)', 'essential-chat-support'),
			'374'	=> __('Armenia (+374)', 'essential-chat-support'),
			'297'	=> __('Aruba (+297)', 'essential-chat-support'),
			'61'	=> __('Australia (+61)', 'essential-chat-support'),
			'43'	=> __('Austria (+43)', 'essential-chat-support'),
			'994'	=> __('Azerbaijan (+994)', 'essential-chat-support'),
			'1242'	=> __('Bahamas (+1242)', 'essential-chat-support'),
			'973'	=> __('Bahrain (+973)', 'essential-chat-support'),
			'880'	=> __('Bangladesh (+880)', 'essential-chat-support'),
			'1246'	=> __('Barbados (+1246)', 'essential-chat-support'),
			'375'	=> __('Belarus (+375)', 'essential-chat-support'),
			'32'	=> __('Belgium (+32)', 'essential-chat-support'),
			'501'	=> __('Belgium (+501)', 'essential-chat-support'),
			'229'	=> __('Benin (+229)', 'essential-chat-support'),
			'1441'	=> __('Bermuda (+1441)', 'essential-chat-support'),
			'975'	=> __('Bhutan (+975)', 'essential-chat-support'),
			'591'	=> __('Bolivia (+591)', 'essential-chat-support'),
			'387'	=> __('Bosnia and Herzegovina (+387)', 'essential-chat-support'),
			'267'	=> __('Botswana (+267)', 'essential-chat-support'),
			'55'	=> __('Brazil (+55)', 'essential-chat-support'),
			'246'	=> __('British Indian Ocean Territory (+246)', 'essential-chat-support'),
			'1284'	=> __('British Virgin Islands (+1284)', 'essential-chat-support'),
			'673'	=> __('Brunei (+673)', 'essential-chat-support'),
			'359'	=> __('Bulgaria (+359)', 'essential-chat-support'),
			'226'	=> __('Burkina Faso (+226)', 'essential-chat-support'),
			'257'	=> __('Burundi (+257)', 'essential-chat-support'),
			'855'	=> __('Cambodia (+855)', 'essential-chat-support'),
			'237'	=> __('Cameroon (+237)', 'essential-chat-support'),
			'1'		=> __('Canada (+1)', 'essential-chat-support'),
			'238'	=> __('Cape Verde (+238)', 'essential-chat-support'),
			'1345'	=> __('Cayman Islands (+1345)', 'essential-chat-support'),
			'236'	=> __('Central African Republic (+236)', 'essential-chat-support'),
			'235'	=> __('Chad (+235)', 'essential-chat-support'),
			'56'	=> __('Chile (+56)', 'essential-chat-support'),
			'86'	=> __('China (+86)', 'essential-chat-support'),
			'61'	=> __('Christmas Island (+61)', 'essential-chat-support'),
			'57'	=> __('Colombia (+57)', 'essential-chat-support'),
			'269'	=> __('Comoros (+269)', 'essential-chat-support'),
			'682'	=> __('Cook Islands (+682)', 'essential-chat-support'),
			'506'	=> __('Costa Rica (+506)', 'essential-chat-support'),
			'385'	=> __('Croatia (+385)', 'essential-chat-support'),
			'53'	=> __('Cuba (+53)', 'essential-chat-support'),
			'599'	=> __('Curacao (+599)', 'essential-chat-support'),
			'357'	=> __('Cyprus (+357)', 'essential-chat-support'),
			'420'	=> __('Czech Republic (+420)', 'essential-chat-support'),
			'243'	=> __('Democratic Republic of the Congo (+243)', 'essential-chat-support'),
			'45'	=> __('Denmark (+45)', 'essential-chat-support'),
			'253'	=> __('Djibouti (+253)', 'essential-chat-support'),
			'767'	=> __('Dominica (+767)', 'essential-chat-support'),
			'1'		=> __('Dominican Republic (+1)', 'essential-chat-support'),
			'670'	=> __('East Timor (+670)', 'essential-chat-support'),
			'593'	=> __('Ecuador (+593)', 'essential-chat-support'),
			'20'	=> __('Egypt (+20)', 'essential-chat-support'),
			'503'	=> __('El Salvador (+503)', 'essential-chat-support'),
			'240'	=> __('Equatorial Guinea (+240)', 'essential-chat-support'),
			'291'	=> __('Eritrea (+291)', 'essential-chat-support'),
			'372'	=> __('Estonia (+372)', 'essential-chat-support'),
			'251'	=> __('Ethiopia (+251)', 'essential-chat-support'),
			'500'	=> __('Falkland Islands (+500)', 'essential-chat-support'),
			'298'	=> __('Faroe Islands (+298)', 'essential-chat-support'),
			'679'	=> __('Fiji (+679)', 'essential-chat-support'),
			'358'	=> __('Finland (+358)', 'essential-chat-support'),
			'33'	=> __('France (+33)', 'essential-chat-support'),
			'689'	=> __('French Polynesia (+689)', 'essential-chat-support'),
			'241'	=> __('Gabon (+241)', 'essential-chat-support'),
			'220'	=> __('Gambia (+220)', 'essential-chat-support'),
			'995'	=> __('Georgia (+995)', 'essential-chat-support'),
			'49'	=> __('Germany (+49)', 'essential-chat-support'),
			'233'	=> __('Ghana (+233)', 'essential-chat-support'),
			'350'	=> __('Gibraltar (+350)', 'essential-chat-support'),
			'30'	=> __('Greece (+30)', 'essential-chat-support'),
			'299'	=> __('Greenland (+299)', 'essential-chat-support'),
			'1473'	=> __('Grenada (+1473)', 'essential-chat-support'),
			'1'		=> __('Guam (+1)', 'essential-chat-support'),
			'502'	=> __('Guatemala (+502)', 'essential-chat-support'),
			'44'	=> __('Guernsey (+44)', 'essential-chat-support'),
			'224'	=> __('Guinea (+224)', 'essential-chat-support'),
			'245'	=> __('Guinea-Bissau (+245)', 'essential-chat-support'),
			'592'	=> __('Guyana (+592)', 'essential-chat-support'),
			'509'	=> __('Haiti (+509)', 'essential-chat-support'),
			'504'	=> __('Honduras (+504)', 'essential-chat-support'),
			'852'	=> __('Hong Kong (+852)', 'essential-chat-support'),
			'36'	=> __('Hungary (+36)', 'essential-chat-support'),
			'354'	=> __('Iceland (+354)', 'essential-chat-support'),
			'91'	=> __('India (+91)', 'essential-chat-support'),
			'62'	=> __('Indonesia (+62)', 'essential-chat-support'),
			'98'	=> __('Iran (+98)', 'essential-chat-support'),
			'964'	=> __('Iraq (+964)', 'essential-chat-support'),
			'353'	=> __('Ireland (+353)', 'essential-chat-support'),
			'44'	=> __('Isle of Man (+44)', 'essential-chat-support'),
			'972'	=> __('Israel (+972)', 'essential-chat-support'),
			'39'	=> __('Italy (+39)', 'essential-chat-support'),
			'225'	=> __('Ivory Coast (+225)', 'essential-chat-support'),
			'876'	=> __('Jamaica (+876)', 'essential-chat-support'),
			'81'	=> __('Japan (+81)', 'essential-chat-support'),
			'44'	=> __('Jersey (+44)', 'essential-chat-support'),
			'962'	=> __('Jordan (+962)', 'essential-chat-support'),
			'77'	=> __('Kazakhstan (+77)', 'essential-chat-support'),
			'254'	=> __('Kenya (+254)', 'essential-chat-support'),
			'686'	=> __('Kiribati (+686)', 'essential-chat-support'),
			'383'	=> __('Kosovo (+383)', 'essential-chat-support'),
			'965'	=> __('Kuwait (+965)', 'essential-chat-support'),
			'996'	=> __('Kyrgyzstan (+996)', 'essential-chat-support'),
			'856'	=> __('Laos (+856)', 'essential-chat-support'),
			'371'	=> __('Latvia (+371)', 'essential-chat-support'),
			'961'	=> __('Lebanon (+961)', 'essential-chat-support'),
			'266'	=> __('Lesotho (+266)', 'essential-chat-support'),
			'231'	=> __('Liberia (+231)', 'essential-chat-support'),
			'218'	=> __('Libya (+218)', 'essential-chat-support'),
			'423'	=> __('Liechtenstein (+423)', 'essential-chat-support'),
			'370'	=> __('Lithuania (+370)', 'essential-chat-support'),
			'352'	=> __('Luxembourg (+352)', 'essential-chat-support'),
			'853'	=> __('Macau (+853)', 'essential-chat-support'),
			'389'	=> __('Macedonia (+389)', 'essential-chat-support'),
			'265'	=> __('Malawi (+265)', 'essential-chat-support'),
			'60'	=> __('Malaysia (+60)', 'essential-chat-support'),
			'960'	=> __('Maldives (+960)', 'essential-chat-support'),
			'223'	=> __('Mali (+223)', 'essential-chat-support'),
			'356'	=> __('Malta (+356)', 'essential-chat-support'),
			'692'	=> __('Marshall Islands (+692)', 'essential-chat-support'),
			'222'	=> __('Mauritania (+222)', 'essential-chat-support'),
			'230'	=> __('Mauritius (+230)', 'essential-chat-support'),
			'262'	=> __('Mayotte (+262)', 'essential-chat-support'),
			'52'	=> __('Mexico (+52)', 'essential-chat-support'),
			'691'	=> __('Micronesia (+691)', 'essential-chat-support'),
			'373'	=> __('Moldova (+373)', 'essential-chat-support'),
			'377'	=> __('Monaco (+377)', 'essential-chat-support'),
			'976'	=> __('Mongolia (+976)', 'essential-chat-support'),
			'382'	=> __('Montenegro (+382)', 'essential-chat-support'),
			'1'		=> __('Montserrat (+1)', 'essential-chat-support'),
			'212'	=> __('Morocco (+212)', 'essential-chat-support'),
			'258'	=> __('Mozambique (+258)', 'essential-chat-support'),
			'95'	=> __('Myanmar (+95)', 'essential-chat-support'),
			'264'	=> __('Namibia (+264)', 'essential-chat-support'),
			'674'	=> __('Nauru (+674)', 'essential-chat-support'),
			'977'	=> __('Nepal (+977)', 'essential-chat-support'),
			'31'	=> __('Netherlands (+31)', 'essential-chat-support'),
			'599'	=> __('Netherlands Antilles (+599)', 'essential-chat-support'),
			'687'	=> __('New Caledonia (+687)', 'essential-chat-support'),
			'64'	=> __('New Zealand (+64)', 'essential-chat-support'),
			'505'	=> __('Nicaragua (+505)', 'essential-chat-support'),
			'227'	=> __('Niger (+227)', 'essential-chat-support'),
			'234'	=> __('Nigeria (+234)', 'essential-chat-support'),
			'683'	=> __('Niue (+683)', 'essential-chat-support'),
			'850'	=> __('North Korea (+850)', 'essential-chat-support'),
			'1'		=> __('Northern Mariana Islands (+1)', 'essential-chat-support'),
			'47'	=> __('Norway (+47)', 'essential-chat-support'),
			'968'	=> __('Oman (+968)', 'essential-chat-support'),
			'92'	=> __('Pakistan (+92)', 'essential-chat-support'),
			'680'	=> __('Palau (+680)', 'essential-chat-support'),
			'970'	=> __('Palestine (+970)', 'essential-chat-support'),
			'507'	=> __('Panama (+507)', 'essential-chat-support'),
			'675'	=> __('Papua New Guinea (+675)', 'essential-chat-support'),
			'595'	=> __('Paraguay (+595)', 'essential-chat-support'),
			'51'	=> __('Peru (+51)', 'essential-chat-support'),
			'63'	=> __('Philippines (+63)', 'essential-chat-support'),
			'64'	=> __('Pitcairn (+64)', 'essential-chat-support'),
			'48'	=> __('Poland (+48)', 'essential-chat-support'),
			'351'	=> __('Portugal (+351)', 'essential-chat-support'),
			'1'		=> __('Puerto Rico (+1)', 'essential-chat-support'),
			'974'	=> __('Qatar (+974)', 'essential-chat-support'),
			'242'	=> __('Republic of the Congo (+242)', 'essential-chat-support'),
			'262'	=> __('Reunion (+262)', 'essential-chat-support'),
			'40'	=> __('Romania (+40)', 'essential-chat-support'),
			'7'		=> __('Russia (+7)', 'essential-chat-support'),
			'250'	=> __('Rwanda (+250)', 'essential-chat-support'),
			'590'	=> __('Saint Barthelemy (+590)', 'essential-chat-support'),
			'290'	=> __('Saint Helena (+290)', 'essential-chat-support'),
			'1869'	=> __('Saint Kitts and Nevis (+1869)', 'essential-chat-support'),
			'1758'	=> __('Saint Lucia (+1758)', 'essential-chat-support'),
			'590'	=> __('Saint Martin (+590)', 'essential-chat-support'),
			'508'	=> __('Saint Pierre and Miquelon (+508)', 'essential-chat-support'),
			'1784'	=> __('Saint Vincent and the Grenadines (+1784)', 'essential-chat-support'),
			'685'	=> __('Samoa (+685)', 'essential-chat-support'),
			'378'	=> __('San Marino (+378)', 'essential-chat-support'),
			'239'	=> __('Sao Tome and Principe (+239)', 'essential-chat-support'),
			'966'	=> __('Saudi Arabia (+966)', 'essential-chat-support'),
			'221'	=> __('Senegal (+221)', 'essential-chat-support'),
			'381'	=> __('Serbia (+381)', 'essential-chat-support'),
			'248'	=> __('Seychelles (+248)', 'essential-chat-support'),
			'232'	=> __('Sierra Leone (+232)', 'essential-chat-support'),
			'65'	=> __('Singapore (+65)', 'essential-chat-support'),
			'599'	=> __('Sint Maarten (+599)', 'essential-chat-support'),
			'421'	=> __('Slovakia (+421)', 'essential-chat-support'),
			'677'	=> __('Solomon Islands (+677)', 'essential-chat-support'),
			'252'	=> __('Somalia (+252)', 'essential-chat-support'),
			'27'	=> __('South Africa (+27)', 'essential-chat-support'),
			'82'	=> __('South Korea (+82)', 'essential-chat-support'),
			'211'	=> __('South Sudan (+211)', 'essential-chat-support'),
			'34'	=> __('Spain (+34)', 'essential-chat-support'),
			'94'	=> __('Sri Lanka (+94)', 'essential-chat-support'),
			'249'	=> __('Sudan (+249)', 'essential-chat-support'),
			'597'	=> __('Suriname (+597)', 'essential-chat-support'),
			'47'	=> __('Svalbard and Jan Mayen (+47)', 'essential-chat-support'),
			'268'	=> __('Swaziland (+268)', 'essential-chat-support'),
			'46'	=> __('Sweden (+46)', 'essential-chat-support'),
			'41'	=> __('Switzerland (+41)', 'essential-chat-support'),
			'963'	=> __('Syria (+963)', 'essential-chat-support'),
			'886'	=> __('Taiwan (+886)', 'essential-chat-support'),
			'992'	=> __('Tajikistan (+992)', 'essential-chat-support'),
			'255'	=> __('Tanzania (+255)', 'essential-chat-support'),
			'66'	=> __('Thailand (+66)', 'essential-chat-support'),
			'228'	=> __('Togo (+228)', 'essential-chat-support'),
			'690'	=> __('Tokelau (+690)', 'essential-chat-support'),
			'676'	=> __('Tonga (+676)', 'essential-chat-support'),
			'868'	=> __('Trinidad and Tobago (+868)', 'essential-chat-support'),
			'216'	=> __('Tunisia (+216)', 'essential-chat-support'),
			'90'	=> __('Turkey (+90)', 'essential-chat-support'),
			'993'	=> __('Turkmenistan (+993)', 'essential-chat-support'),
			'1'		=> __('Turks and Caicos Islands (+1)', 'essential-chat-support'),
			'688'	=> __('Tuvalu (+688)', 'essential-chat-support'),
			'1'		=> __('U.S. Virgin Islands (+1)', 'essential-chat-support'),
			'256'	=> __('Uganda (+256)', 'essential-chat-support'),
			'380'	=> __('Ukraine (+380)', 'essential-chat-support'),
			'971'	=> __('United Arab Emirates (+971)', 'essential-chat-support'),
			'44'	=> __('United Kingdom (+44)', 'essential-chat-support'),
			'1'		=> __('United States (+1)', 'essential-chat-support'),
			'598'	=> __('Uruguay (+598)', 'essential-chat-support'),
			'998'	=> __('Uzbekistan (+998)', 'essential-chat-support'),
			'678'	=> __('Vanuatu (+678)', 'essential-chat-support'),
			'379'	=> __('Vatican (+379)', 'essential-chat-support'),
			'58'	=> __('Venezuela (+58)', 'essential-chat-support'),
			'84'	=> __('Vietnam (+84)', 'essential-chat-support'),
			'681'	=> __('Wallis and Futuna (+681)', 'essential-chat-support'),
			'212'	=> __('Western Sahara (+212)', 'essential-chat-support'),
			'967'	=> __('Yemen (+967)', 'essential-chat-support'),
			'260'	=> __('Zambia (+260)', 'essential-chat-support'),
			'263'	=> __('Zimbabwe (+263)', 'essential-chat-support'),
	));
}