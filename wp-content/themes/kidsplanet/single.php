<?php
/**
Template Name: Single post
 */
get_header(); 

if (empty($single_style)) $single_style = themerex_get_custom_option('single_style');

while ( have_posts() ) { the_post();

	// Move themerex_set_post_views to the javascript - counter will work under cache system
	if (themerex_get_custom_option('use_ajax_views_counter')=='no') {
        do_action('trx_utils_filter_set_post_views', get_the_ID());
	}

	themerex_show_post_layout(
		array(
			'layout' => $single_style,
			'sidebar' => !themerex_param_is_off(themerex_get_custom_option('show_sidebar_main')),
			'content' => themerex_get_template_property($single_style, 'need_content'),
			'terms_list' => themerex_get_template_property($single_style, 'need_terms')
		)
	);

}

get_footer();
?>