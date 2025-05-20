(function($) {

	"use strict";

	/* Contact Shortcode click event to open chat */
	$(".ecs-open-chat").on("click", function() {

		var url = $(this).attr('data-href');

		if( url != '' ) {
			window.open(url, '_blank');
		}
	});

	/* Chatbox Toogle click event to open popup */
	$(".ecs-btn-popup").on("click", function() {
		if ( $(".ecs-chatbox").hasClass("ecs-active") ) { /* Close Chatbox */

			$(".ecs-chatbox").removeClass("ecs-active");
			$(this).removeClass("ecs-active");

		} else {

			$(this).addClass("ecs-active");
			$(".ecs-chatbox").addClass("ecs-active");
		}
	});

	/* Esc key press to close the chat toggle */
	$(document).keyup(function(e) {
		if ( e.keyCode == 27 ) {
			if ( $(".ecs-chatbox").hasClass("ecs-active") ) {
				$(".ecs-btn-popup").trigger('click');
			}
		}
	});
})(jQuery);