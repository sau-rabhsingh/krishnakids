<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'themerex_template_single_team_theme_setup' ) ) {
	add_action( 'themerex_action_before_init_theme', 'themerex_template_single_team_theme_setup', 1 );
	function themerex_template_single_team_theme_setup() {
		themerex_add_template(array(
			'layout' => 'single-team',
			'mode'   => 'team',
			'need_content' => true,
			'need_terms' => true,
			'title'  => esc_html__('Single Team member', 'kidsplanet'),
			'thumb_title'  => esc_html__('Large image (crop)', 'kidsplanet'),
			'w'		 => 870,
			'h'		 => 490
		));
	}
}

// Template output
if ( !function_exists( 'themerex_template_single_team_output' ) ) {
	function themerex_template_single_team_output($post_options, $post_data) {
		$post_data['post_views']++;
		$show_title = themerex_get_custom_option('show_post_title')=='yes';
		$title_tag = themerex_get_custom_option('show_page_title')=='yes' ? 'h3' : 'h1';

		themerex_open_wrapper('<article class="' 
				. join(' ', get_post_class('itemscope'
					. ' post_item post_item_single_team'
					. ' post_featured_' . esc_attr($post_options['post_class'])
					. ' post_format_' . esc_attr($post_data['post_format'])))
				. '"'
				. ' itemscope itemtype="//schema.org/Article'
				. '">');

		if ($show_title && $post_options['location'] == 'center' && themerex_get_custom_option('show_page_title')=='no') {
			?>
			<<?php echo esc_attr($title_tag); ?> itemprop="name" class="post_title entry-title"><span class="post_icon <?php echo esc_attr($post_data['post_icon']); ?>"></span><?php themerex_show_layout($post_data['post_title']); ?></<?php echo esc_attr($title_tag); ?>>
			<?php 
		}

		if (!$post_data['post_protected'] && (
			!empty($post_options['dedicated']) ||
			(themerex_get_custom_option('show_featured_image')=='yes' && $post_data['post_thumb'])
		)) {
			?>
			<section class="post_featured">
			<?php
			if (!empty($post_options['dedicated'])) {
				themerex_show_layout($post_options['dedicated']);
			} else {
				themerex_enqueue_popup();
				?>
				<div class="post_thumb" data-image="<?php echo esc_url($post_data['post_attachment']); ?>" data-title="<?php echo esc_attr($post_data['post_title']); ?>">
					<a class="hover_icon hover_icon_view" href="<?php echo esc_url($post_data['post_attachment']); ?>" title="<?php echo esc_attr($post_data['post_title']); ?>"><?php themerex_show_layout($post_data['post_thumb']); ?></a>
				</div>
				<?php 
			}
			?>
			</section>
			<?php
		}
			
		
		if ($show_title && $post_options['location'] != 'center' && themerex_get_custom_option('show_page_title')=='no') {
			?>
			<<?php echo esc_attr($title_tag); ?> itemprop="name" class="post_title entry-title"><span class="post_icon <?php echo esc_attr($post_data['post_icon']); ?>"></span><?php themerex_show_layout($post_data['post_title']); ?></<?php echo esc_attr($title_tag); ?>>
			<?php 
		}

		themerex_open_wrapper('<section class="post_content'.(!$post_data['post_protected'] && $post_data['post_edit_enable'] ? ' '.esc_attr('post_content_editor_present') : '').'" itemprop="articleBody">');
			
		// Post content
		if ($post_data['post_protected']) { 
			themerex_show_layout($post_data['post_excerpt']);
			echo get_the_password_form(); 
		} else {
            if(function_exists('themerex_reviews_wrapper')){
                themerex_show_layout(themerex_gap_wrapper(themerex_reviews_wrapper($post_data['post_content'])));
            } else {
                themerex_show_layout($post_data['post_content']);
            }
			require(themerex_get_file_dir('templates/_parts/single-pagination.php'));
			if ( themerex_get_custom_option('show_post_tags') == 'yes' && !empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links)) {
				?>
				<div class="post_info post_info_bottom">
					<span class="post_info_item post_info_tags"><?php esc_html_e('Tags:', 'kidsplanet'); ?> <?php echo join(', ', $post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links); ?></span>
				</div>
				<?php 
			}
		} 
			
		if (!$post_data['post_protected'] && $post_data['post_edit_enable']) {
			require(themerex_get_file_dir('templates/_parts/editor-area.php'));
		}

		themerex_close_wrapper();
			
		if (!$post_data['post_protected']) {
			require(themerex_get_file_dir('templates/_parts/share.php'));
		}

		themerex_close_wrapper();

		if (!$post_data['post_protected']) {
			require(themerex_get_file_dir('templates/_parts/related-posts.php'));
			require(themerex_get_file_dir('templates/_parts/comments.php'));
		}

		require(themerex_get_file_dir('templates/_parts/views-counter.php'));
	}
}
?>