<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'themerex_template_masonri_theme_setup' ) ) {
	add_action( 'themerex_action_before_init_theme', 'themerex_template_masonri_theme_setup', 1 );
	function themerex_template_masonri_theme_setup() {
		themerex_add_template(array(
			'layout' => 'masonri_3',
			'template' => 'masonri',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => esc_html__('masonri tile /3 columns/', 'kidsplanet'),
			'thumb_title'  => esc_html__('Medium images', 'kidsplanet'),
			'w'		 => 370,
			'h_crop' => 220,
			'h'      => null
		));
		// Add template specific scripts
		add_action('themerex_action_blog_scripts', 'themerex_template_masonri_add_scripts');
	}
}

// Add template specific scripts
if (!function_exists('themerex_template_masonri_add_scripts')) {
	//Handler of add_action('themerex_action_blog_scripts', 'themerex_template_masonri_add_scripts');
	function themerex_template_masonri_add_scripts($style) {
		if (in_array(themerex_substr($style, 0, 8), array('classic_', 'masonri_'))) {
			wp_enqueue_script( 'isotope', themerex_get_file_url('js/jquery.isotope.min.js'), array(), null, true );
		}
	}
}

// Template output
if ( !function_exists( 'themerex_template_masonri_output' ) ) {
	function themerex_template_masonri_output($post_options, $post_data) {
		$show_title = !in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote'));
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$columns = max(1, min(12, empty($post_options['columns_count']) 
									? (empty($parts[1]) ? 1 : (int) $parts[1])
									: $post_options['columns_count']
									));
		$tag = themerex_in_shortcode_blogger(true) ? 'div' : 'article';
		?>
		<div class="isotope_item isotope_item_<?php echo esc_attr($style); ?> isotope_item_<?php echo esc_attr($post_options['layout']); ?> isotope_column_<?php echo esc_attr($columns); ?>
					<?php
					if ($post_options['filters'] != '') {
						if ($post_options['filters']=='categories' && !empty($post_data['post_terms'][$post_data['post_taxonomy']]->terms_ids))
							echo ' flt_' . join(' flt_', $post_data['post_terms'][$post_data['post_taxonomy']]->terms_ids);
						else if ($post_options['filters']=='tags' && !empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_ids))
							echo ' flt_' . join(' flt_', $post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_ids);
					}
					?>">
			<<?php themerex_show_layout($tag); ?> class="post_item post_item_<?php echo esc_attr($style); ?> post_item_<?php echo esc_attr($post_options['layout']); ?>
				 <?php echo ' post_format_'.esc_attr($post_data['post_format']) 
					. ($post_options['number']%2==0 ? ' even' : ' odd') 
					. ($post_options['number']==0 ? ' first' : '') 
					. ($post_options['number']==$post_options['posts_on_page'] ? ' last' : '');
				?>">
				
				<?php

                if ($post_data['post_video'] || $post_data['post_audio'] || $post_data['post_thumb'] ||  $post_data['post_gallery']) { ?>
					<div class="post_featured">
						<?php require(themerex_get_file_dir('templates/_parts/post-featured.php')); ?>
					</div>
				<?php } ?>

				<div class="post_content isotope_item_content">
					
					<?php
                    if ($show_title) {
                        if (!isset($post_options['links']) || $post_options['links']) {
                            ?>
                            <h4 class="post_title"><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php themerex_show_layout($post_data['post_title']); ?></a></h4>
                        <?php
                        } else {
                            ?>
                            <h4 class="post_title"><?php themerex_show_layout($post_data['post_title']); ?></h4>
                        <?php
                        }
                    }
					if (!$post_data['post_protected'] && $post_options['info']) {
						$info_parts = array('counters'=>true, 'terms'=>true, 'author'=>true);
						require(themerex_get_file_dir('templates/_parts/post-info.php')); 
					}
					?>


				</div>				<!-- /.post_content -->
			</<?php themerex_show_layout($tag); ?>>	<!-- /.post_item -->
		</div>						<!-- /.isotope_item -->
		<?php
	}
}
?>