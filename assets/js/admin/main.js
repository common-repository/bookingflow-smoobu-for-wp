(function($) {
	"use strict";
	
	var layout_selector                   = $('#smoobu-calendar-layout');
	var connection_check_selector         = $('#smoobu_api_check_connection');
	var api_key_input_selector            = $('#smoobu_api_key');
	var connection_check_message_selector = $('.smoobu-connection-message');
	var accordion_selector                = $('.smoobu-accordion');
	var color_picker_selector             = $('.smoobu-color-picker');
	var calendar_selector                 = $('.smoobu-calendar');

	var update_calendar_preview = function( active_name = '', color = '' ) {
		var params = {};

		calendar_selector.css( 'opacity', '0.7' );

		// get all styling parameters.
		params['smoobu_custom_styling_border_shadow'] = $('input[name="smoobu_custom_styling_border_shadow"]').prop('checked');

		$('input[type="text"][name^="smoobu_custom_styling_"]').each(function(i, obj) {
			var obj_name = obj.name;

			if ( obj_name === active_name ) {
				params[obj_name] = color;
			} else {
				params[obj_name] = obj.value;
			}
		});

		// add action and security token.
		params['action'] = 'get_calendar_styling';
		params['security'] = smoobu_calendar_ajax.styling_nonce

		// ajax call.
		$.post(
			smoobu_calendar_ajax.ajaxurl,
			params,
			function(data) {
				var styling = JSON.parse(data);

				if ( styling.status === 'OK' ) {
					$('#smoobu-calendar-css-theme-' + smoobu_calendar_ajax.theme + '-inline-css').html( styling.css )
				}

				calendar_selector.css( 'opacity', '1' );
			}
		);
	}

	var smoobu_admin = {
		onReady : function() {
			// initialize accordion.
			accordion_selector.accordion({
				header:      "h2",
				heightStyle: "content"
			});
			accordion_selector.last().accordion("option", "icons", false);

			// layout selection in Smoobu Calendar -> My Properties.
			layout_selector.change(function() {
				var layout = $(this).val();

				$('.smoobu-layout-code').each(function() {
					var code = '[smoobu_calendar property_id="' + $(this).data('property-id') + '" layout="' + layout + '"]';

					$(this).html(code);
				});
			});

			// Smoobu API connection check in Smoobu Calendar -> General Settings.
			connection_check_selector.click(function() {
				connection_check_selector.closest('td').css( 'opacity', '0.7' );
				
				// ajax call.
				$.post(
					smoobu_calendar_ajax.ajaxurl,
					{
						action: 'check_api_connection',
						api_key: api_key_input_selector.val(),
						security: smoobu_calendar_ajax.connection_check_nonce,
					},
					function(data) {
						var check = JSON.parse(data);

						if ( check.status === 'OK' ) {
							connection_check_message_selector.html( smoobu_calendar_ajax.connection_success );

							connection_check_message_selector.removeClass( 'red-text' );
							connection_check_message_selector.addClass( 'green-text' );
						} else {
							connection_check_message_selector.html( smoobu_calendar_ajax.connection_error + '<b>' + check.message + '</b>' );

							connection_check_message_selector.removeClass( 'green-text' );
							connection_check_message_selector.addClass( 'red-text' );
						}

						connection_check_selector.closest('td').css( 'opacity', '1' );
					}
				);
			});

			// initialize color picker for custom styling.
			color_picker_selector.wpColorPicker({
				change: function( event, ui ) {
					var color = ui.color.toString();
					
					update_calendar_preview( event.target.name, color );
				}
			});

			// initialize datepicker for styling settings page.
			calendar_selector.datepicker({
				numberOfMonths: 1,
				minDate: 0,
				maxDate: 730
			});

			// call calendar preview on non-color-picker inputs change.
			$('input[name^="smoobu_custom_styling_"]').change(function() {
				update_calendar_preview();
			});
		}
    }
            
    $(document).ready( smoobu_admin.onReady );
})(jQuery);
