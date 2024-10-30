( function( blocks, components, i18n, element ) {
	var el = element.createElement;
	var __ = i18n.__;
	var SelectControl = components.SelectControl;

	blocks.registerBlockType( 'smoobu-calendar/calendar', {
		title: __( 'Smoobu Calendar', 'smoobu-calendar' ),
		icon: 'calendar-alt',
		category: 'widgets',
		attributes: {
			property_id: {
                type: 'int',
			},
			layout: {
                type: 'string',
            }
		},
		edit: function( props ) {
            var property_id = props.attributes.property_id;
            var layout = props.attributes.layout;
			var properties_options = [];
			var layout_options = [];

            smoobu_calendar_lists.properties.forEach( element => {
                properties_options.push({
                    value: element.id,
                    label: element.name
                });
			});

			_.forEach( smoobu_calendar_lists.layouts, function( val, key ) {
                layout_options.push({
                    value: key,
                    label: val
                });
            });

			return (
				el( 'div', { },
					el(
						SelectControl,
						{
							label: __('Choose a Property', 'smoobu-calendar'),
							value: property_id,
							onChange: ( new_property_id ) => { props.setAttributes( { property_id: new_property_id } ); },
							options: properties_options
						}
					),
					el(
						SelectControl,
						{
							label: __('Choose Layout', 'smoobu-calendar'),
							value: layout,
							onChange: ( new_layout ) => { props.setAttributes( { layout: new_layout } ); },
							options: layout_options
						}
					)
				)
			);
		},

		save: function( props ) {
			return null;
		},
	} );
}(
	window.wp.blocks,
	window.wp.components,
	window.wp.i18n,
	window.wp.element
) );
