<?php

$show_all_counters = !isset($post_options['counters']);
$counters_tag = is_single() ? 'span' : 'a';
 
if ($show_all_counters || themerex_strpos($post_options['counters'], 'views')!==false && function_exists('themerex_reviews_theme_setup')) {
	?>
	<<?php themerex_show_layout($counters_tag); ?> class="post_counters_item post_counters_views icon-eye" title="<?php echo sprintf(esc_attr__('Views - %s', 'kidsplanet'), $post_data['post_views']); ?>" href="<?php echo esc_url($post_data['post_link']); ?>"><?php themerex_show_layout($post_data['post_views']); ?></<?php themerex_show_layout($counters_tag); ?>>
	<?php
}

if ($show_all_counters || themerex_strpos($post_options['counters'], 'comments')!==false) {
	?>
	<a class="post_counters_item post_counters_comments icon-comment" title="<?php echo sprintf(esc_attr__('Comments - %s', 'kidsplanet'), $post_data['post_comments']); ?>" href="<?php echo esc_url($post_data['post_comments_link']); ?>"><span class="post_counters_number"><?php themerex_show_layout($post_data['post_comments']); ?></span></a>
	<?php 
}
 
$rating = $post_data['post_reviews_'.(themerex_get_theme_option('reviews_first')=='author' ? 'author' : 'users')];
if ($rating > 0 && ($show_all_counters || themerex_strpos($post_options['counters'], 'rating')!==false)) { 
	?>
	<<?php themerex_show_layout($counters_tag); ?> class="post_counters_item post_counters_rating icon-star" title="<?php echo sprintf(esc_attr__('Rating - %s', 'kidsplanet'), $rating); ?>" href="<?php echo esc_url($post_data['post_link']); ?>"><span class="post_counters_number"><?php themerex_show_layout($rating); ?></span></<?php themerex_show_layout($counters_tag); ?>>
	<?php
}

if ($show_all_counters || themerex_strpos($post_options['counters'], 'likes')!==false && function_exists('themerex_reviews_theme_setup')) {
	// Load core messages
	themerex_enqueue_messages();
	$likes = isset($_COOKIE['themerex_likes']) ? sanitize_text_field($_COOKIE['themerex_likes']) : '';
	$allow = themerex_strpos($likes, ','.($post_data['post_id']).',')===false;
	?>
	<a class="post_counters_item post_counters_likes icon-heart-empty <?php themerex_show_layout($allow ? 'enabled' : 'disabled'); ?>" title="<?php echo esc_attr($allow ? esc_attr__('Like', 'kidsplanet') : esc_attr__('Dislike', 'kidsplanet')); ?>" href="#"
		data-postid="<?php echo esc_attr($post_data['post_id']); ?>"
		data-likes="<?php echo esc_attr($post_data['post_likes']); ?>"
		data-title-like="<?php esc_attr_e('Like', 'kidsplanet'); ?>"
		data-title-dislike="<?php esc_attr_e('Dislike', 'kidsplanet'); ?>"><span class="post_counters_number"><?php themerex_show_layout($post_data['post_likes']); ?></span></a>
	<?php
}

if (is_single() && themerex_strpos($post_options['counters'], 'markup')!==false) {
	?>
	<meta itemprop="interactionCount" content="User<?php echo esc_attr(themerex_strpos($post_options['counters'],'comments')!==false ? 'Comments' : 'PageVisits'); ?>:<?php echo esc_attr(themerex_strpos($post_options['counters'], 'comments')!==false ? $post_data['post_comments'] : $post_data['post_views']); ?>" />
	<?php
}
?>