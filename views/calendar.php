<?php
/**
 * Calendar view
 *
 * @package smoobu-calendar
 */

do_action( 'smoobu_before_calendar_view', $property_id, $layout );
?>

<div class="smoobu-calendar" data-property-id="<?php echo esc_attr( $property_id ); ?>" data-layout="<?php echo esc_attr( $layout ); ?>"></div>

<?php
do_action( 'smoobu_after_calendar_view', $property_id, $layout );
