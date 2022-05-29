<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'themerex_template_portfolio_theme_setup' ) ) {
	add_action( 'themerex_action_before_init_theme', 'themerex_template_portfolio_theme_setup', 1 );
	function themerex_template_portfolio_theme_setup() {
		themerex_add_template(array(
			'layout' => 'portfolio_2',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => esc_html__('Portfolio tile (with hovers, different height) /2 columns/', 'kidsplanet'),
			'thumb_title'  => esc_html__('Large image', 'kidsplanet'),
			'w'		 => 870,
			'h_crop' => 490,
			'h'		 => null
		));
		themerex_add_template(array(
			'layout' => 'portfolio_3',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => esc_html__('Portfolio tile /3 columns/', 'kidsplanet'),
			'thumb_title'  => esc_html__('Medium image', 'kidsplanet'),
			'w'		 => 390,
			'h_crop' => 220,
			'h'		 => null
		));
		themerex_add_template(array(
			'layout' => 'portfolio_4',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => esc_html__('Portfolio tile /4 columns/', 'kidsplanet'),
			'thumb_title'  => esc_html__('Small image', 'kidsplanet'),
			'w'		 => 300,
			'h_crop' => 170,
			'h'		 => null
		));
		themerex_add_template(array(
			'layout' => 'grid_2',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'container_classes' => 'no_margins',
			'title'  => esc_html__('Grid tile (with hovers, equal height) /2 columns/', 'kidsplanet'),
			'thumb_title'  => esc_html__('Large image (crop)', 'kidsplanet'),
			'w'		 => 870,
			'h' 	 => 490
		));
		themerex_add_template(array(
			'layout' => 'grid_3',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'container_classes' => 'no_margins',
			'title'  => esc_html__('Grid tile /3 columns/', 'kidsplanet'),
			'thumb_title'  => esc_html__('Medium image (crop)', 'kidsplanet'),
			'w'		 => 390,
			'h'		 => 220
		));
		themerex_add_template(array(
			'layout' => 'grid_4',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'container_classes' => 'no_margins',
			'title'  => esc_html__('Grid tile /4 columns/', 'kidsplanet'),
			'thumb_title'  => esc_html__('Small image (crop)', 'kidsplanet'),
			'w'		 => 300,
			'h'		 => 170
		));
		themerex_add_template(array(
			'layout' => 'square_2',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'container_classes' => 'no_margins',
			'title'  => esc_html__('Square tile (with hovers, width=height) /2 columns/', 'kidsplanet'),
			'thumb_title'  => esc_html__('Large square image (crop)', 'kidsplanet'),
			'w'		 => 870,
			'h' 	 => 870
		));
		themerex_add_template(array(
			'layout' => 'square_3',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'container_classes' => 'no_margins',
			'title'  => esc_html__('Square tile /3 columns/', 'kidsplanet'),
			'thumb_title'  => esc_html__('Medium square image (crop)', 'kidsplanet'),
			'w'		 => 390,
			'h'		 => 390
		));
		themerex_add_template(array(
			'layout' => 'square_4',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'container_classes' => 'no_margins',
			'title'  => esc_html__('Square tile /4 columns/', 'kidsplanet'),
			'thumb_title'  => esc_html__('Small square image (crop)', 'kidsplanet'),
			'w'		 => 300,
			'h'		 => 300
		));
		themerex_add_template(array(
			'layout' => 'colored_1',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'need_terms' => true,
			'title'  => esc_html__('Colored excerpt', 'kidsplanet'),
			'thumb_title'  => esc_html__('Small square image (crop)', 'kidsplanet'),
			'w'		 => 300,
			'h'		 => 300
		));
		themerex_add_template(array(
			'layout' => 'colored_2',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'need_terms' => true,
			'title'  => esc_html__('Colored tile (with hovers, width=height) /2 columns/', 'kidsplanet'),
			'thumb_title'  => esc_html__('Large square image (crop)', 'kidsplanet'),
			'w'		 => 870,
			'h' 	 => 870
		));
		themerex_add_template(array(
			'layout' => 'colored_3',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'need_terms' => true,
			'title'  => esc_html__('Colored tile /3 columns/', 'kidsplanet'),
			'thumb_title'  => esc_html__('Medium square image (crop)', 'kidsplanet'),
			'w'		 => 390,
			'h'		 => 390
		));
		themerex_add_template(array(
			'layout' => 'colored_4',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'need_terms' => true,
			'title'  => esc_html__('Colored tile /4 columns/', 'kidsplanet'),
			'thumb_title'  => esc_html__('Small square image (crop)', 'kidsplanet'),
			'w'		 => 300,
			'h'		 => 300
		));
		themerex_add_template(array(
			'layout' => 'short_2',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'need_terms' => true,
			'container_classes' => 'no_margins',
			'title'  => esc_html__('Short info /2 columns/', 'kidsplanet'),
			'thumb_title'  => esc_html__('Large square image (crop)', 'kidsplanet'),
			'w'		 => 870,
			'h' 	 => 870
		));
		themerex_add_template(array(
			'layout' => 'short_3',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'need_terms' => true,
			'container_classes' => 'no_margins',
			'title'  => esc_html__('Short info /3 columns/', 'kidsplanet'),
			'thumb_title'  => esc_html__('Medium square image (crop)', 'kidsplanet'),
			'w'		 => 390,
			'h'		 => 390
		));
		themerex_add_template(array(
			'layout' => 'short_4',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'need_terms' => true,
			'container_classes' => 'no_margins',
			'title'  => esc_html__('Short info /4 columns/', 'kidsplanet'),
			'thumb_title'  => esc_html__('Small square image (crop)', 'kidsplanet'),
			'w'		 => 300,
			'h'		 => 300
		));
		// Add template specific scripts
		add_action('themerex_action_blog_scripts', 'themerex_template_portfolio_add_scripts');
	}
}

// Add template specific scripts
if (!function_exists('themerex_template_portfolio_add_scripts')) {
	//Handler of add_action('themerex_action_blog_scripts', 'themerex_template_portfolio_add_scripts');
	function themerex_template_portfolio_add_scripts($style) {
		if (themerex_substr($style, 0, 10) == 'portfolio_' || themerex_substr($style, 0, 5) == 'grid_' || themerex_substr($style, 0, 7) == 'square_' || themerex_substr($style, 0, 6) == 'short_' || themerex_substr($style, 0, 8) == 'colored_') {
			wp_enqueue_script( 'isotope', themerex_get_file_url('js/jquery.isotope.min.js'), array(), null, true );
			if ($style != 'colored_1')  {
				wp_enqueue_script( 'hoverdir', themerex_get_file_url('js/hover/jquery.hoverdir.js'), array(), null, true );
				wp_enqueue_style( 'themerex-portfolio-style', themerex_get_file_url('css/core.portfolio.css'), array(), null );
			}
		}
	}
}

// Template output
if ( !function_exists( 'themerex_template_portfolio_output' ) ) {
	function themerex_template_portfolio_output($post_options, $post_data) {
		$show_title = !in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote'));
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$columns = max(1, min(12, empty($post_options['columns_count']) 
									? (empty($parts[1]) ? 1 : (int) $parts[1])
									: $post_options['columns_count']
									));
		$tag = themerex_in_shortcode_blogger(true) ? 'div' : 'article';
		if ($post_options['hover']=='square effect4') $post_options['hover']='square effect5';
		$link_start = !isset($post_options['links']) || $post_options['links'] ? '<a href="'.esc_url($post_data['post_link']).'">' : '';
		$link_end = !isset($post_options['links']) || $post_options['links'] ? '</a>' : '';

		if ($style == 'colored_1' && $columns==1) {				// colored excerpt style (1 column)
			?>
			<div class="isotope_item isotope_item_colored isotope_item_colored_1 isotope_column_1
						<?php
						if ($post_options['filters'] != '') {
							if ($post_options['filters']=='categories' && !empty($post_data['post_terms'][$post_data['post_taxonomy']]->terms_ids))
								echo ' flt_' . join(' flt_', $post_data['post_terms'][$post_data['post_taxonomy']]->terms_ids);
							else if ($post_options['filters']=='tags' && !empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_ids))
								echo ' flt_' . join(' flt_', $post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_ids);
						}
						?>">
				<<?php themerex_show_layout($tag); ?> class="post_item post_item_colored post_item_colored_1
					<?php echo 'post_format_'.esc_attr($post_data['post_format']) 
						. ($post_options['number']%2==0 ? ' even' : ' odd') 
						. ($post_options['number']==0 ? ' first' : '') 
						. ($post_options['number']==$post_options['posts_on_page'] ? ' last' : '');
					?>">
	
					<div class="post_content isotope_item_content">
						<div class="post_featured img">
							<?php
							themerex_show_layout(($link_start) . ($post_data['post_thumb']) . ($link_end));

                            if (function_exists('themerex_reviews_theme_setup')) {
                                require(themerex_get_file_dir('templates/_parts/reviews-summary.php'));
                            }
							$new = themerex_get_custom_option('mark_as_new', '', $post_data['post_id'], $post_data['post_type']);						// !!!!!! Get option from specified post
							if ($new && $new > date('Y-m-d')) {
								?><div class="post_mark_new"><?php esc_html_e('NEW', 'kidsplanet'); ?></div><?php
							}
							?>
						</div>
		
						<div class="post_content clearfix">
							<h4 class="post_title"><?php themerex_show_layout(($link_start) . ($post_data['post_title']) . ($link_end)); ?></h4>
							<div class="post_category">
								<?php
								if (!empty($post_data['post_terms'][$post_data['post_taxonomy']]->terms_links))
									echo join(', ', $post_data['post_terms'][$post_data['post_taxonomy']]->terms_links);
								?>
							</div>
							<?php themerex_show_layout($reviews_summary); ?>
							<?php if (themerex_substr($style, 0, 6) != 'short_') { ?>
								<div class="post_descr">
									<?php
									if ($post_data['post_protected']) {
										themerex_show_layout(($link_start) . ($post_data['post_excerpt']) . ($link_end));
									} else {
										if ($style=='colored_1') {
											if ($post_data['post_link'] != '')
												echo '<div class="post_buttons">';
											if ($post_data['post_link'] != '') {
												?>
												<div class="post_button"><?php echo themerex_do_shortcode('[trx_button size="small" link="'.esc_url($post_data['post_link']).'"]'.esc_html__('LEARN MORE', 'kidsplanet').'[/trx_button]'); ?></div>
												<?php
											}
											if ($post_data['post_link'] != '')
												echo '</div>';
										}
									}
									?>
								</div>
							<?php } ?>
						</div>
					</div>				<!-- /.post_content -->
				</<?php themerex_show_layout($tag); ?>>	<!-- /.post_item -->
			</div>						<!-- /.isotope_item -->
			<?php

		} else {										// All rest portfolio styles (portfolio, grid, square, colored) with 2 and more columns

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
					<?php echo 'post_format_'.esc_attr($post_data['post_format']) 
						. ($post_options['number']%2==0 ? ' even' : ' odd') 
						. ($post_options['number']==0 ? ' first' : '') 
						. ($post_options['number']==$post_options['posts_on_page'] ? ' last' : '');
					?>">
	
					<div class="post_content isotope_item_content ih-item colored<?php
									themerex_show_layout(($post_options['hover'] ? ' '.esc_attr($post_options['hover']) : '')
										.($post_options['hover_dir'] ? ' '.esc_attr($post_options['hover_dir']) : '')); ?>">
						<?php
						if ($post_options['hover'] == 'circle effect1') {
							?><div class="spinner"></div><?php
						}
						if ($post_options['hover'] == 'square effect4') {
							?><div class="mask1"></div><div class="mask2"></div><?php
						}
						if ($post_options['hover'] == 'circle effect8') {
							?><div class="img-container"><?php
						}
						?>
						<div class="post_featured img">
							<?php
							themerex_show_layout(($link_start) . ($post_data['post_thumb']) . ($link_end));
							
							if ($style=='colored_1') {
                                if (function_exists('themerex_reviews_theme_setup')) {
                                    require(themerex_get_file_dir('templates/_parts/reviews-summary.php'));
                                }
								$new = themerex_get_custom_option('mark_as_new', '', $post_data['post_id'], $post_data['post_type']);						// !!!!!! Get option from specified post
								if ($new && $new > date('Y-m-d')) {
									?><div class="post_mark_new"><?php esc_html_e('NEW', 'kidsplanet'); ?></div><?php
								}
								?>
								<h4 class="post_title"><?php themerex_show_layout(($link_start) . ($post_data['post_title']) . ($link_end)); ?></h4>
								<div class="post_descr">
									<?php
									$category = !empty($post_data['post_terms'][$post_data['post_taxonomy']]->terms) 
												? ($post_data['post_terms'][$post_data['post_taxonomy']]->terms[0]->link ? '<a href="'.esc_url($post_data['post_terms'][$post_data['post_taxonomy']]->terms[0]->link).'">' : '')
													. ($post_data['post_terms'][$post_data['post_taxonomy']]->terms[0]->name)
													. ($post_data['post_terms'][$post_data['post_taxonomy']]->terms[0]->link ? '</a>' : '')
												: '';
									?>
									<div class="post_category"><?php themerex_show_layout($category); ?></div>
									<?php themerex_show_layout($reviews_summary); ?>
								</div>
								<?php
							}
							?>
						</div>
						<?php
						if ($post_options['hover'] == 'circle effect8') {
							?>
							</div>	<!-- .img-container -->
							<div class="info-container">
							<?php
						}
						?>
	
						<div class="post_info_wrap info"><div class="info-back">
	
							<?php
							if ($show_title) {
								?><h4 class="post_title"><?php themerex_show_layout(($link_start) . ($post_data['post_title']) . ($link_end)); ?></h4><?php
							}
							?>
	
							<div class="post_descr">
							<?php
								if ($post_data['post_protected']) {
									themerex_show_layout(($link_start) . ($post_data['post_excerpt']) . ($link_end));
								} else {
									if (!$post_data['post_protected'] && $post_options['info']) {
										$info_parts = array('counters'=>true, 'terms'=>false, 'author' => false, 'tag' => 'p');
										require(themerex_get_file_dir('templates/_parts/post-info.php')); 
									}
									if ($post_data['post_excerpt']) {
										echo in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status')) 
											? ( ($link_start) . ($post_data['post_excerpt']) . ($link_end) )
											: '<p>' . ($link_start) 
												. (themerex_strpos($post_options['hover'], 'square')!=false
													? $post_options['hover'].strip_tags($post_data['post_excerpt'])
													: trim(themerex_strshort($post_data['post_excerpt'], isset($post_options['descr']) ? $post_options['descr'] : themerex_get_custom_option('post_excerpt_maxlength_masonry'))) 
													)
												. ($link_end) . '</p>';
									}
									if ($post_data['post_link'] != '' && !empty($post_options['readmore'])) {
										?><p class="post_buttons"><?php
										if ($style=='colored_1') {
											?><span class="post_button"><?php echo themerex_do_shortcode('[trx_button size="small" link="'.esc_url($post_data['post_link']).'"]'.esc_html__('Learn more', 'kidsplanet').'[/trx_button]'); ?></span><?php
										} else if (!themerex_param_is_off($post_options['readmore']) && !in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status'))) {
											?><a href="<?php echo esc_url($post_data['post_link']); ?>" class="post_readmore"><span class="post_readmore_label"><?php themerex_show_layout($post_options['readmore']); ?></span></a><?php
										}
										?></p><?php
									}
								}
							?>
							</div>
						</div></div>	<!-- /.info-back /.info -->
						<?php if ($post_options['hover'] == 'circle effect8') { ?>
						</div>			<!-- /.info-container -->
						<?php } ?>
					</div>				<!-- /.post_content -->
				</<?php themerex_show_layout($tag); ?>>	<!-- /.post_item -->
			</div>						<!-- /.isotope_item -->
			<?php
		}
	}
}
?>