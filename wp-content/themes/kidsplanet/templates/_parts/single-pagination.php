<?php
if ($post_data['post_type'] == 'lesson') {
	themerex_show_layout(themerex_get_lessons_links($parent_id, $post_data['post_id'], array(
		'header' => esc_html__('Course Content', 'kidsplanet'),
		'show_prev_next' => true
		)));
} else {
	wp_link_pages( array( 
		'before' => '<nav class="pagination_single" role="navigation"><span class="pager_pages">' . esc_html__( 'Pages:', 'kidsplanet') . '</span>',
		'after' => '</nav>',
		'link_before' => '<span class="pager_numbers">',
		'link_after' => '</span>'
		)
	); 
}
?>