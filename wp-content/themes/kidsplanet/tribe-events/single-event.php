<?php
/**
 * Single Event Template
 * A single event. This displays the event title, description, meta, and
 * optionally, the Google map for the event.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/single-event.php
 *
 * @package TribeEventsCalendar
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( '-1' );
}

$events_label_singular = tribe_get_event_label_singular();
$events_label_plural = tribe_get_event_label_plural();
$venue_details = tribe_get_venue_details();
$event_id = get_the_ID();

?>

<div id="tribe-events-content" class="tribe-events-single vevent hentry">

	<p class="tribe-events-back">
		<a href="<?php echo esc_url( tribe_get_events_link() ); ?>"> <?php printf( esc_html__( '&laquo; All %s', 'kidsplanet'), $events_label_plural ); ?></a>
	</p>

	<!-- Notices -->
	<?php tribe_the_notices() ?>


	<!-- Event header -->
	<div id="tribe-events-header" <?php tribe_events_the_header_attributes() ?>>
		<!-- Navigation -->
		<h3 class="tribe-events-visuallyhidden"><?php printf( esc_html__( '%s Navigation', 'kidsplanet'), $events_label_singular ); ?></h3>
		<ul class="tribe-events-sub-nav">
			<li class="tribe-events-nav-previous"><?php tribe_the_prev_event_link( '<span>&laquo;</span> %title%' ) ?></li>
			<li class="tribe-events-nav-next"><?php tribe_the_next_event_link( '%title% <span>&raquo;</span>' ) ?></li>
		</ul>
		<!-- .tribe-events-sub-nav -->
	</div>
	<!-- #tribe-events-header -->

	<?php while ( have_posts() ) :  the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<!-- Event featured image, but exclude link -->
			<?php echo tribe_event_featured_image( $event_id, 'full', false ); ?>
            <?php the_title( '<h2 class="tribe-events-single-event-title summary entry-title">', '</h2>' ); ?>

            <div class="tribe-events-schedule updated published tribe-clearfix">
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
                        <?php echo implode( '', $venue_details ); ?>
                    </div> <!-- .tribe-events-venue-details -->
                <?php endif; ?>

                <?php if ( tribe_get_cost() ) : ?>
                    <span class="tribe-events-divider">|</span>
                    <span class="tribe-events-cost"><?php echo tribe_get_cost( null, true ) ?></span>
                <?php endif; ?>
            </div>

			<!-- Event content -->
			<?php do_action( 'tribe_events_single_event_before_the_content' ) ?>
			<div class="tribe-events-single-event-description tribe-events-content entry-content description">
				<?php the_content(); ?>
			</div>
            <div class="tribe-events-single-event-description tribe-events-excerpt entry-content description">
                <h5><?php esc_html_e('Description', 'kidsplanet'); ?></h5>
                <?php the_excerpt(); ?>
            </div>
			<!-- .tribe-events-single-event-description -->
			<?php do_action( 'tribe_events_single_event_after_the_content' ) ?>
			<!-- Event meta -->
			<?php do_action( 'tribe_events_single_event_before_the_meta' ) ?>
			<?php
			/**
			 * The tribe_events_single_event_meta() function has been deprecated and has been
			 * left in place only to help customers with existing meta factory customizations
			 * to transition: if you are one of those users, please review the new meta templates
			 * and make the switch!
			 */
			if ( ! apply_filters( 'tribe_events_single_event_meta_legacy_mode', false ) ) {
				tribe_get_template_part( 'modules/meta' );
			} else {
				echo tribe_events_single_event_meta();
			}
			?>
			<?php do_action( 'tribe_events_single_event_after_the_meta' ) ?>
		</div> <!-- #post-x -->
		<?php if ( get_post_type() == Tribe__Events__Main::POSTTYPE && tribe_get_option( 'showComments', false ) ) comments_template() ?>
	<?php endwhile; ?>

	<!-- Event footer -->
	<div id="tribe-events-footer">
		<!-- Navigation -->
		<h3 class="tribe-events-visuallyhidden"><?php printf( esc_html__( '%s Navigation', 'kidsplanet'), $events_label_singular ); ?></h3>
		<ul class="tribe-events-sub-nav">
			<li class="tribe-events-nav-previous"><?php tribe_the_prev_event_link( '<span>&laquo;</span> %title%' ) ?></li>
			<li class="tribe-events-nav-next"><?php tribe_the_next_event_link( '%title% <span>&raquo;</span>' ) ?></li>
		</ul>
		<!-- .tribe-events-sub-nav -->
	</div>
	<!-- #tribe-events-footer -->

</div><!-- #tribe-events-content -->
