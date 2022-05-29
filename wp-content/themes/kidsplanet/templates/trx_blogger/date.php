<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'themerex_template_date_theme_setup' ) ) {
	add_action( 'themerex_action_before_init_theme', 'themerex_template_date_theme_setup', 1 );
	function themerex_template_date_theme_setup() {
		themerex_add_template(array(
			'layout' => 'date',
			'mode'   => 'blogger',
			'title'  => esc_html__('Blogger layout: Timeline', 'kidsplanet')
			));
	}
}

// Template output
if ( !function_exists( 'themerex_template_date_output' ) ) {
	function themerex_template_date_output($post_options, $post_data) {
		if (themerex_param_is_on($post_options['scroll'])) themerex_enqueue_slider();
        if (function_exists('themerex_reviews_theme_setup')) {
            require(themerex_get_file_dir('templates/_parts/reviews-summary.php'));
        }
		?>
		
		<div class="post_item sc_blogger_item
			<?php themerex_show_layout($post_options['number'] == $post_options['posts_on_page'] && !themerex_param_is_on($post_options['loadmore']) ? ' sc_blogger_item_last' : ''); ?>"
			<?php themerex_show_layout($post_options['dir'] == 'horizontal' ? ' style="width:'.(100/$post_options['posts_on_page']).'%"' : ''); ?>>
			<div class="sc_blogger_date">
				<span class="day_month"><?php themerex_show_layout($post_data['post_date_part1']); ?></span>
				<span class="year"><?php themerex_show_layout($post_data['post_date_part2']); ?></span>
			</div>

			<div class="post_content">
				<h6 class="post_title sc_title sc_blogger_title">
					<?php echo (!isset($post_options['links']) || $post_options['links'] ? '<a href="' . esc_url($post_data['post_link']) . '">' : ''); ?>
					<?php themerex_show_layout($post_data['post_title']); ?>
					<?php echo (!isset($post_options['links']) || $post_options['links'] ? '</a>' : ''); ?>
				</h6>
				
				<?php themerex_show_layout($reviews_summary); ?>
	
				<?php if (themerex_param_is_on($post_options['info'])) { ?>
				<div class="post_info">
					<span class="post_info_item post_info_posted_by"><?php esc_html_e('by', 'kidsplanet'); ?> <a href="<?php echo esc_url($post_data['post_author_url']); ?>" class="post_info_author"><?php echo esc_html($post_data['post_author']); ?></a></span>
					<span class="post_info_item post_info_counters">
						<?php themerex_show_layout($post_options['orderby']=='comments' || $post_options['counters']=='comments' ? esc_html__('Comments', 'kidsplanet') : esc_html__('Views', 'kidsplanet')); ?>
						<span class="post_info_counters_number"><?php themerex_show_layout($post_options['orderby']=='comments' || $post_options['counters']=='comments' ? $post_data['post_comments'] : $post_data['post_views']); ?></span>
					</span>
				</div>
				<?php } ?>

			</div>	<!-- /.post_content -->
		
		</div>		<!-- /.post_item -->

		<?php
		if ($post_options['number'] == $post_options['posts_on_page'] && themerex_param_is_on($post_options['loadmore'])) {
		?>
			<div class="load_more"<?php themerex_show_layout($post_options['dir'] == 'horizontal' ? ' style="width:'.(100/$post_options['posts_on_page']).'%"' : ''); ?>></div>
		<?php
		}
	}
}
?>