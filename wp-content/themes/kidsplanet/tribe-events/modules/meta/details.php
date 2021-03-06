<?php
/**
 * Single Event Meta (Details) Template
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe-events/modules/meta/details.php
 *
 * @package TribeEventsCalendar
 */


$time_format = get_option( 'time_format', Tribe__Date_Utils::TIMEFORMAT );
$time_range_separator = tribe_get_option( 'timeRangeSeparator', ' - ' );

$start_datetime = tribe_get_start_date();
$start_date = tribe_get_start_date( null, false );
$start_time = tribe_get_start_date( null, false, $time_format );
$start_ts = tribe_get_start_date( null, false, Tribe__Date_Utils::DBDATEFORMAT );

$end_datetime = tribe_get_end_date();
$end_date = tribe_get_end_date( null, false );
$end_time = tribe_get_end_date( null, false, $time_format );
$end_ts = tribe_get_end_date( null, false, Tribe__Date_Utils::DBDATEFORMAT );

$cost = tribe_get_formatted_cost();
$website = tribe_get_event_website_link();
?>

<div class="tribe-events-meta-group tribe-events-meta-group-details">
	<h3 class="tribe-events-single-section-title"> <?php esc_html_e( 'Details', 'kidsplanet') ?> </h3>
	<dl>

		<?php
		do_action( 'tribe_events_single_meta_details_section_start' );

		// All day (multiday) events
		if ( tribe_event_is_all_day() && tribe_event_is_multiday() ) :
			?>

			<dt> <?php esc_html_e( 'Start:', 'kidsplanet') ?> </dt>
			<dd>
				<abbr class="tribe-events-abbr updated published dtstart" title="<?php echo esc_attr( $start_ts ) ?>"> <?php echo esc_html( $start_date ) ?> </abbr>
			</dd>

			<dt> <?php esc_html_e( 'End:', 'kidsplanet') ?> </dt>
			<dd>
				<abbr class="tribe-events-abbr dtend" title="<?php echo esc_attr( $end_ts ) ?>"> <?php echo esc_html( $end_date ) ?> </abbr>
			</dd>

		<?php
		// All day (single day) events
		elseif ( tribe_event_is_all_day() ):
			?>

			<dt> <?php esc_html_e( 'Date:', 'kidsplanet') ?> </dt>
			<dd>
				<abbr class="tribe-events-abbr updated published dtstart" title="<?php echo esc_attr( $start_ts ) ?>"> <?php echo esc_html( $start_date ) ?> </abbr>
			</dd>

		<?php
		// Multiday events
		elseif ( tribe_event_is_multiday() ) :
			?>

			<dt> <?php esc_html_e( 'Start:', 'kidsplanet') ?> </dt>
			<dd>
				<abbr class="tribe-events-abbr updated published dtstart" title="<?php echo esc_attr( $start_ts ) ?>"> <?php echo esc_html( $start_datetime ) ?> </abbr>
			</dd>

			<dt> <?php esc_html_e( 'End:', 'kidsplanet') ?> </dt>
			<dd>
				<abbr class="tribe-events-abbr dtend" title="<?php echo esc_attr( $end_ts ) ?>"> <?php echo esc_html( $end_datetime ) ?> </abbr>
			</dd>

		<?php
		// Single day events
		else :
			?>

            <dt> <?php esc_html_e( 'Start:', 'kidsplanet') ?> </dt>
            <dd>
                <abbr class="tribe-events-abbr updated published dtstart" title="<?php echo esc_attr( $start_ts ) ?>"> <?php echo esc_html( $start_datetime ) ?> </abbr>
            </dd>

            <dt> <?php esc_html_e( 'End:', 'kidsplanet') ?> </dt>
            <dd>
                <abbr class="tribe-events-abbr dtend" title="<?php echo esc_attr( $end_ts ) ?>"> <?php echo esc_html( $end_datetime ) ?> </abbr>
            </dd>

		<?php endif ?>

		<?php
		// Event Cost
		if ( ! empty( $cost ) ) : ?>

			<dt> <?php esc_html_e( 'Cost:', 'kidsplanet') ?> </dt>
			<dd class="tribe-events-event-cost"> <?php echo esc_html( $cost ); ?> </dd>
		<?php endif ?>

		<?php
		echo tribe_get_event_categories(
			get_the_id(), array(
				'before'       => '',
				'sep'          => ', ',
				'after'        => '',
				'label'        => null, // An appropriate plural/singular label will be provided
				'label_before' => '<dt>',
				'label_after'  => '</dt>',
				'wrap_before'  => '<dd class="tribe-events-event-categories">',
				'wrap_after'   => '</dd>'
			)
		);
		?>

		<?php echo tribe_meta_event_tags( sprintf( esc_html__( '%s Tags:', 'kidsplanet'), tribe_get_event_label_singular() ), ', ', false ) ?>

		<?php
		// Event Website
		if ( ! empty( $website ) ) : ?>

			<dt> <?php esc_html_e( 'Website:', 'kidsplanet') ?> </dt>
			<dd class="tribe-events-event-url"> <?php themerex_show_layout($website); ?> </dd>
		<?php endif ?>

		<?php do_action( 'tribe_events_single_meta_details_section_end' ) ?>
	</dl>
</div>