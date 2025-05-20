(function($) {

	var ecs_meta_notify_timer;

	/* Initialize Select 2 */
	if( $('.ecs-select2').length > 0 ) {
		$('.ecs-select2').select2({
			allowClear		: true,
			closeOnSelect	: false,
			language		: {
								inputTooShort : function() {
									return ECSLAdmin.select2_input_too_short;
								},
								removeAllItems : function() {
									return ECSLAdmin.select2_remove_all_items;
								},
								removeItem : function() {
									return ECSLAdmin.select2_remove_item;
								},
								searching : function() {
									return ECSLAdmin.select2_searching;
								}
							}
		});
	}

	/* Color Picker */
	if( $('.ecs-colorpicker').length > 0 ) {
		$('.ecs-colorpicker').wpColorPicker({width:260});
	}

	/* Vertical Tab */
	$( document ).on( "click", ".ecs-vtab-nav a", function() {

		$(".ecs-vtab-nav").removeClass('ecs-active-vtab');
		$(this).parent('.ecs-vtab-nav').addClass("ecs-active-vtab");

		var selected_tab = $(this).attr("href");
		$('.ecs-vtab-cnt').hide();

		/* Show the selected tab content */
		$(selected_tab).show();

		/* Pass selected tab */
		$('.ecs-selected-tab').val(selected_tab);
		return false;
	});

	/* Remain selected tab for user */
	if( $('.ecs-selected-tab').length > 0 ) {
		
		var sel_tab = $('.ecs-selected-tab').val();
		
		if( typeof(sel_tab) !== 'undefined' && sel_tab != '' && $(sel_tab).length > 0 ) {
			$('.ecs-vtab-nav [href="'+sel_tab+'"]').click();
		} else {
			$('.ecs-vtab-nav:first-child a').click();
		}
	}

	/* On change of checkbox */
	$( document ).on( 'change', '.ecs-show-hide', function() {

		var prefix		= $(this).attr('data-prefix');
		var inp_type	= $(this).attr('type');
		var showlabel	= $(this).attr('data-label');

		if(typeof(showlabel) == 'undefined' || showlabel == '' ) {
			showlabel = $(this).val();
		}

		if( prefix ) {
			showlabel = prefix +'-'+ showlabel;
			$('.ecs-show-hide-row-'+prefix).hide();
			$('.ecs-show-for-all-'+prefix).show();
		} else {
			$('.ecs-show-hide-row').hide();
			$('.ecs-show-for-all').show();
		}

		$('.ecs-show-if-'+showlabel).hide();
		$('.ecs-hide-if-'+showlabel).hide();

		if( inp_type == 'checkbox' || inp_type == 'radio' ) {
			if( $(this).is(":checked") ) {
				$('.ecs-show-if-'+showlabel).show();
			} else {
				$('.ecs-hide-if-'+showlabel).show();
			}
		} else {
			$('.ecs-show-if-'+showlabel).show();
		}
	});

	/* Reset Settings Button */
	$( document ).on( 'click', '.ecs-reset-sett', function() {
		var ans;
		ans = confirm(ECSLAdmin.reset_msg);

		if(ans) {
			return true;
		} else {
			return false;
		}
	});

	/* Click to Copy the Text */
	$(document).on('click', '.ecs-copy-clipboard', function() {
		var copyText = $(this);
		copyText.select();
		document.execCommand("copy");
	});

	/* Save Data on Ctrl + S - Start */
	if( adminpage == 'post-php' ) {
		var ecs_save_btn = $('#publish');
	} else {
		var ecs_save_btn = $('.ecs-sett-submit');
	}

	if( ecs_save_btn.length > 0 ) {
		$(window).on('keydown', function(event) {
			if ( (event.ctrlKey || event.metaKey) && (String.fromCharCode(event.which).toLowerCase() == 's') ) {

				event.preventDefault();
				ecs_save_btn.trigger('click');
				return;
			}
		});
	}
	/* Save Data on Ctrl + S - End */

	/* Show notice box on `Display Mode` */
	$(document).on('change', '.ecs-display-mode', function() {

		var anim_bottom = '50px';
		if( ECSLAdmin.is_mobile == 1 ) {
			anim_bottom = 0;
		}

		$('.ecs-meta-notify').show().animate({bottom: anim_bottom}, 500);

		clearTimeout(ecs_meta_notify_timer);
		ecs_meta_notify_timer = setTimeout(function() {
									$('.ecs-meta-notify').fadeOut( "slow", function() {
										jQuery(this).css({bottom:''});
									});
								}, 5000);
	});

	/* Initialize Select 2 with Ajax */
	if( $('.ecs-post-title-sugg').length > 0 ) {

		/* Ajax suggest post title based on post type */
		$('.ecs-post-title-sugg').each(function() {

			var cls_ele			= $(this).closest('#ecs-cw-details');
			var meta_data		= $(this).attr('data-meta');
			var nonce			= $(this).attr('data-nonce');
			var post_type_attr	= $(this).attr('data-post-type');
			var predefined		= $(this).attr('data-predefined');

			$(this).select2({
				ajax: {
					url				: ajaxurl,
					dataType		: 'json',
					delay			: 500,
					data			: function ( params ) {
										var search_term	= $.trim( params.term );
										var post_type	= post_type_attr;
										var meta_data	= meta_data ? meta_data : cls_ele.find('.ecs-display-type').val();

										delay: 0;

										return {
											action		: 'ecsl_post_title_sugg',
											search		: search_term,
											post_type	: post_type,
											meta_data	: meta_data,
											nonce		: nonce
										};
									},
					processResults	: function( data ) {
										var options = [];

										if( predefined ) {
											options = JSON.parse( predefined );
											options = $.makeArray(options);
										}

										if ( data ) {
											$.each( data, function( index, text ) {
												options.push( { id: text[0], text: text[1]  } );
											});
										}
										return {
											results: options
										};
									},
					cache			: true
				},
				minimumInputLength		: 1,
				maximumSelectionLength	: 3,
				allowClear				: true,
			});
		});
	}

	/* WP Code Editor */
	if( ECSLAdmin.code_editor == 1 && ECSLAdmin.syntax_highlighting == 1 ) {
		jQuery('.ecs-code-editor').each( function() {

			var cur_ele		= jQuery(this);
			var data_mode	= cur_ele.attr('data-mode');
			data_mode		= data_mode ? data_mode : 'css';

			if( cur_ele.hasClass('ecs-code-editor-initialized') ) {
				return;
			}

			var editorSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {};
			editorSettings.codemirror = _.extend(
				{},
				editorSettings.codemirror,
				{
					indentUnit	: 2,
					tabSize		: 2,
					mode		: data_mode,
				}
			);
			var editor = wp.codeEditor.initialize( cur_ele, editorSettings );
			cur_ele.addClass('ecs-code-editor-initialized');

			editor.codemirror.on( 'change', function( codemirror ) {
				cur_ele.val( codemirror.getValue() ).trigger( 'change' );
			});
		});
	}
})(jQuery);