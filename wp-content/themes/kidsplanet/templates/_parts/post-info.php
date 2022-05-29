			<?php
			$info_parts = array_merge(array(
				'snippets' => false,	// For singular post/page/course/team etc.
				'date' => true,
				'author' => true,
				'terms' => false,
				'counters' => true,
				'tag' => 'div',			// 'p' for portfolio hovers 
				'shedule' => false,		// For single course
				'length' => false		// For single course
				), isset($info_parts) && is_array($info_parts) ? $info_parts : array());
			?>
			<<?php echo esc_attr($info_parts['tag']); ?> class="post_info">
				<?php
				$info_parts = array_merge(array(
					'snippets' => false,	// For singular post/page/course/team etc.
					'date' => true,
					'author' => true,
					'terms' => false,
					'counters' => true,
					'shedule' => false,		// For single course
					'length' => false		// For single course
					), isset($info_parts) && is_array($info_parts) ? $info_parts : array());
									
				if ($info_parts['date']) {
					?>
					<span class="post_info_item post_info_posted"><a href="<?php echo esc_url($post_data['post_link']); ?>" class="post_info_date<?php echo esc_attr($info_parts['snippets'] ? ' date updated' : ''); ?>"<?php themerex_show_layout($info_parts['snippets'] ? ' itemprop="datePublished" content="'.get_the_date('Y-m-d').'"' : ''); ?>><?php echo esc_html($post_data['post_date']); ?></a></span>
					<?php
				}
				if ($info_parts['author']) {
					?>
					<span class="post_info_item post_info_posted_by<?php themerex_show_layout($info_parts['snippets'] ? ' vcard' : ''); ?>"<?php themerex_show_layout($info_parts['snippets'] ? ' itemprop="author"' : ''); ?>><?php esc_html_e('by', 'kidsplanet'); ?> <a href="<?php echo esc_url($post_data['post_author_url']); ?>" class="post_info_author"><?php themerex_show_layout($post_data['post_author']); ?></a></span>
				<?php 
				}
				if ($info_parts['terms'] && !empty($post_data['post_terms'][$post_data['post_taxonomy']]->terms_links)) {
					?>
					<span class="post_info_item post_info_tags"><?php esc_html_e('in', 'kidsplanet'); ?> <?php echo join(', ', $post_data['post_terms'][$post_data['post_taxonomy']]->terms_links); ?></span>
					<?php
				}
				if ($info_parts['counters']) {
					?>
					<span class="post_info_item post_info_counters"><?php require(themerex_get_file_dir('templates/_parts/counters.php')); ?></span>
					<?php
				}
				if (is_single() && !themerex_get_global('blog_streampage') && ($post_data['post_edit_enable'] || $post_data['post_delete_enable'])) {
					?>
					<span class="frontend_editor_buttons">
						<?php if ($post_data['post_edit_enable']) { ?>
						<span class="post_info_item post_info_button post_info_button_edit"><a id="frontend_editor_icon_edit" class="icon-pencil" title="<?php esc_attr_e('Edit post', 'kidsplanet'); ?>" href="#"><?php esc_html_e('Edit', 'kidsplanet'); ?></a></span>
						<?php } ?>
						<?php if ($post_data['post_delete_enable']) { ?>
						<span class="post_info_item post_info_button post_info_button_delete"><a id="frontend_editor_icon_delete" class="icon-trash" title="<?php esc_attr_e('Delete post', 'kidsplanet'); ?>" href="#"><?php esc_html_e('Delete', 'kidsplanet'); ?></a></span>
						<?php } ?>
					</span>
					<?php
				}
				?>
			</<?php echo esc_attr($info_parts['tag']); ?>>
