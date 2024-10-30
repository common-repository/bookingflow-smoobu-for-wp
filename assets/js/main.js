(function($) {
	"use strict";
	
	var calendar_selector = $('.smoobu-calendar');

	// to avoid calling the same function all over again during resize.
	var debounce;

	// function to rearrange rows/cols dynamically.
	var rearrangeCalendars = function() {
		calendar_selector.each(function() {
			var datepicker_layout = [];
			var layout            = $(this).data('layout');
			var rows              = layout[0];
			var cols              = layout[1];
			var container_width   = $(this).parent().width();
			var itself_width      = $(this).width();
			var smallest_width    = '';

			// reduce amount of cols on mobile/tablet to avoid layout breaks.
			// 1 column usually takes ~270px.
			if ( container_width < ( 270 * cols ) || itself_width < ( 270 * cols ) ) {
				datepicker_layout[0] = rows;

				if ( itself_width < container_width ) {
					smallest_width = itself_width;
				} else {
					smallest_width = container_width;
				}
				datepicker_layout[1] = Math.floor( smallest_width / 270 );

				if ( datepicker_layout[1] < 1 ) {
					datepicker_layout[1] = 1;
				}
			} else {
				datepicker_layout = layout;
			}

			$(this).datepicker('option', 'numberOfMonths', datepicker_layout);
		})
	}

	var smoobu = {
		onReady : function() {
			calendar_selector.each(function() {
				var layout          = $(this).data('layout');

				$(this).datepicker({
					numberOfMonths: layout,
					minDate: 0,
					maxDate: 730,
					beforeShowDay: function(date){
						var attributes_name = 'smoobu_calendar_attributes_' + $(this).data('property-id');
	
						var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
						return [ eval(attributes_name).busy_days.indexOf(string) == -1 ]
					}
				});
			});

			rearrangeCalendars();
		},
		onResize : function() {
			clearTimeout(debounce);

			debounce = setTimeout(function() {
				rearrangeCalendars();
			}, 250);
		}
    }
            
	$(document).ready( smoobu.onReady );
	$(window).resize( smoobu.onResize );
})(jQuery);
