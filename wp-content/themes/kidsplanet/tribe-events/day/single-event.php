<?php
/**
 * List View Single Event
 * This file contains one event in the list view
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/list/single-event.php
 *
 * @package TribeEventsCalendar
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
    wp_die( '-1' );
}

// Setup an array of venue details for use later in the template
$venue_details = tribe_get_venue_details();

// Venue microformats
$has_venue_address = ( ! empty( $venue_details['address'] ) ) ? ' location' : '';

// Organizer
$organizer = tribe_get_organizer();

?>

<!-- Event Cost -->
<?php if ( tribe_get_cost() ) : ?>
    <div class="tribe-events-event-cost">
        <span><?php echo tribe_get_cost( null, true ); ?></span>
    </div>
<?php endif; ?>


<!-- Event Image -->
<?php echo tribe_event_featured_image( null, 'medium' ) ?>

<!-- Event Title -->
<?php do_action( 'tribe_events_before_the_event_title' ) ?>
<h2 class="tribe-events-list-event-title entry-title summary">
    <a class="url" href="<?php echo esc_url( tribe_get_event_link() ); ?>" title="<?php the_title() ?>" rel="bookmark">
        <?php the_title() ?>
    </a>
</h2>
<?php do_action( 'tribe_events_after_the_event_title' ) ?>

<!-- Event Meta -->
<?php do_action( 'tribe_events_before_the_meta' ) ?>
<div class="tribe-events-event-meta vcard">
    <div class="author <?php themerex_show_layout($has_venue_address); ?>">

        <!-- Schedule & Recurrence Details -->
        <div class="updated published time-details column-1_3">
            <?php
            if (tribe_get_start_date( null, false, '' ) == tribe_get_end_date( null, false, '' )) {
                echo tribe_get_start_date( null, false, 'F j, Y' );
            } else {
                echo tribe_get_start_date( null, false, 'F j' );
                echo ' - '.tribe_get_end_date( null, false, 'F j, Y' );
            }
            ?>
        </div>
        <div class="updated published time-details2 column-1_3">
            <?php echo tribe_get_start_date( null, false, 'l' );?><br>
            <?php echo tribe_get_start_time(); ?> -
            <?php echo tribe_get_end_time(); ?>
        </div>
        <?php if ( $venue_details ) : ?>
            <!-- Venue Display Info -->
            <div class="tribe-events-venue-details column-1_3">
                <?php echo implode( ', ', $venue_details ); ?>
            </div> <!-- .tribe-events-venue-details -->
        <?php endif; ?>

    </div>
</div><!-- .tribe-events-event-meta -->
<?php do_action( 'tribe_events_after_the_meta' ) ?>

<!-- Event Content -->
<?php do_action( 'tribe_events_before_the_content' ) ?>
<div class="tribe-events-list-event-description tribe-events-content description entry-summary">
    <?php the_excerpt() ?>
    <a href="<?php echo esc_url( tribe_get_event_link() ); ?>" class="tribe-events-read-more" rel="bookmark"><?php esc_html_e( 'Find out more', 'kidsplanet') ?> &raquo;</a>
</div><!-- .tribe-events-list-event-description -->
<?php do_action( 'tribe_events_after_the_content' ) ?>
