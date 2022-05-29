<?php
/**
 * Add function to widgets_init that will load our widget.
 */
add_action( 'widgets_init', 'themerex_widget_top10_load' );

/**
 * Register our widget.
 */
function themerex_widget_top10_load() {
	register_widget('themerex_widget_top10');
}

/**
 * Top10 Widget class.
 */
class themerex_widget_top10 extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function __construct() {
		/* Widget settings. */
		$widget_ops = array('classname' => 'widget_top10', 'description' => __('Top 10 posts by average reviews marks (by author and users)', 'trx_utils'));

		/* Widget control settings. */
		$control_ops = array('width' => 200, 'height' => 250, 'id_base' => 'themerex_widget_top10');

		/* Create the widget. */
		parent::__construct( 'themerex_widget_top10', __('ThemeREX - Top 10 Posts', 'trx_utils'), $widget_ops, $control_ops );

		// Add thumb sizes into list
        if (function_exists('themerex_add_thumb_sizes')) {
            themerex_add_thumb_sizes(array('layout' => 'widgets', 'w' => 75, 'h' => 75, 'h_crop' => 75, 'title' => __('Widgets', 'trx_utils')));
        }
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget($args, $instance) {
		extract($args);

		global $post;

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', isset($instance['title']) ? $instance['title'] : '');
		$title_tabs = array(
			apply_filters('widget_title', isset($instance['title_author']) ? $instance['title_author'] : ''),
			apply_filters('widget_title', isset($instance['title_users']) ? $instance['title_users'] : '')
		);
		$number = isset($instance['number']) ? (int) $instance['number'] : '';

		$show_date = isset($instance['show_date']) ? (int) $instance['show_date'] : 0;
		$show_image = isset($instance['show_image']) ? (int) $instance['show_image'] : 0;
		$show_author = isset($instance['show_author']) ? (int) $instance['show_author'] : 0;
		$show_counters = isset($instance['show_counters']) ? (int) $instance['show_counters'] : 0;
		$show_counters = $show_counters==2 ? 'stars' : ($show_counters==1 ? 'rating' : '');
		
		$post_type = isset($instance['post_type']) ? $instance['post_type'] : 'post';
		$category = isset($instance['category']) ? (int) $instance['category'] : 0;
		$taxonomy = themerex_get_taxonomy_categories_by_post_type($post_type);

		$tabs = array();
		
		$reviews_first_author = themerex_get_theme_option('reviews_first')=='author';
		$reviews_second_hide = themerex_get_theme_option('reviews_second')=='hide';

		$rnd = str_replace('.', '', mt_rand());

		for ($i=0; $i<2; $i++) {
			
			if ($i==0 && !$reviews_first_author && $reviews_second_hide) continue;
			if ($i==1 && $reviews_first_author && $reviews_second_hide) continue;
			
			$post_rating = 'reviews_avg'.($i==0 ? '' : '2');
			
			$args = array(
				'post_type' => $post_type,
				'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish',
				'post_password' => '',
				'posts_per_page' => $number,
				'ignore_sticky_posts' => true,
				'order' => 'DESC',
				'orderby' => 'meta_value_num',
				'meta_key' => $post_rating
			);
			if ($category > 0) {
				if ($taxonomy=='category')
					$args['cat'] = $category;
				else {
					$args['tax_query'] = array(
						array(
							'taxonomy' => $taxonomy,
							'field' => 'id',
							'terms' => $category
						)
					);
				}
			}
			$ex = themerex_get_theme_option('exclude_cats');
			if (!empty($ex)) {
				$args['category__not_in'] = explode(',', $ex);
			}
			
			$q = new WP_Query($args); 
			
			if ($q->have_posts()) {
				$post_number = 0;
				$output = '';
				while ($q->have_posts()) { $q->the_post();
					$post_number++;
					require(themerex_get_file_dir('templates/_parts/widgets-posts.php'));
					if ($post_number >= $number) break;
				}
				$tabs[] = array('title' => $title_tabs[$i], 'content' => $output);
			}
		}

		wp_reset_postdata();

		
		if (count($tabs) > 0) {
			
			if (count($tabs) > 1) wp_enqueue_script('jquery-ui-tabs', false, array('jquery','jquery-ui-core'), null, true);
	
			/* Before widget (defined by themes). */			
			echo ($before_widget);
			
			/* Display the widget title if one was input (before and after defined by themes). */
			if ($title) echo ($before_title) . ($title) . ($after_title);
	
			echo count($tabs) == 1 ? $tabs[0]['content'] : themerex_do_shortcode('[trx_tabs style="1"][trx_tab title="'.esc_attr($tabs[0]['title']).'"]'.($tabs[0]['content']).'[/trx_tab][trx_tab title="'.esc_attr($tabs[1]['title']).'"]'.($tabs[1]['content']).'[/trx_tab][/trx_tabs]');
			
			/* After widget (defined by themes). */
			echo ($after_widget);
		}
	}

	/**
	 * Update the widget settings.
	 */
	function update($new_instance, $old_instance) {
		$instance = $old_instance;

		/* Strip tags for title and comments count to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['title_author'] = strip_tags($new_instance['title_author']);
		$instance['title_users'] = strip_tags($new_instance['title_users']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['show_date'] = (int) $new_instance['show_date'];
		$instance['show_image'] = (int) $new_instance['show_image'];
		$instance['show_author'] = (int) $new_instance['show_author'];
		$instance['show_counters'] = (int) $new_instance['show_counters'];
		$instance['category'] = (int) $new_instance['category'];
		$instance['post_type'] = strip_tags( $new_instance['post_type'] );

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form($instance) {
		// Set up some default widget settings
		$instance = wp_parse_args( (array) $instance, array(
			'title' => '',
			'title_author' => '',
			'title_users' => '',
			'number' => '4',
			'show_date' => '1',
			'show_image' => '1',
			'show_author' => '1',
			'show_counters' => '1',
			'category'=>'0',
			'post_type' => 'post'
			)
		);
		$title = $instance['title'];
		$title_author = $instance['title_author'];
		$title_users = $instance['title_users'];
		$number = (int) $instance['number'];
		$show_date = (int) $instance['show_date'];
		$show_image = (int) $instance['show_image'];
		$show_author = (int) $instance['show_author'];
		$show_counters = (int) $instance['show_counters'];
		$post_type = $instance['post_type'];
		$category = (int) $instance['category'];

		$posts_types = themerex_get_list_posts_types(false);
		$categories = themerex_get_list_terms(false, themerex_get_taxonomy_categories_by_post_type($post_type));
		?>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Widget title:', 'trx_utils'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($title); ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title_author')); ?>"><?php _e('Author rating tab title:', 'trx_utils'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id('title_author')); ?>" name="<?php echo esc_attr($this->get_field_name('title_author')); ?>" value="<?php echo esc_attr($title_author); ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title_users')); ?>"><?php _e('Users rating tab title:', 'trx_utils'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id('title_users')); ?>" name="<?php echo esc_attr($this->get_field_name('title_users')); ?>" value="<?php echo esc_attr($title_users); ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('post_type')); ?>"><?php _e('Post type:', 'trx_utils'); ?></label>
			<select id="<?php echo esc_attr($this->get_field_id('post_type')); ?>" name="<?php echo esc_attr($this->get_field_name('post_type')); ?>" style="width:100%;" onchange="themerex_admin_change_post_type(this);">
			<?php
				if (is_array($posts_types) && count($posts_types) > 0) {
					foreach ($posts_types as $type => $type_name) {
						echo '<option value="'.esc_attr($type).'"'.($post_type==$type ? ' selected="selected"' : '').'>'.esc_html($type_name).'</option>';
					}
				}
			?>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('category')); ?>"><?php _e('Category:', 'trx_utils'); ?></label>
			<select id="<?php echo esc_attr($this->get_field_id('category')); ?>" name="<?php echo esc_attr($this->get_field_name('category')); ?>" style="width:100%;">
				<option value="0"><?php _e('-- Any category --', 'trx_utils'); ?></option>
			<?php
				if (is_array($categories) && count($categories) > 0) {
					foreach ($categories as $cat_id => $cat_name) {
						echo '<option value="'.esc_attr($cat_id).'"'.($category==$cat_id ? ' selected="selected"' : '').'>'.esc_html($cat_name).'</option>';
					}
				}
			?>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php _e('Number posts to show:', 'trx_utils'); ?></label>
			<input type="text" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" value="<?php echo esc_attr($number); ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('show_image')); ?>_1"><?php _e('Show post image:', 'trx_utils'); ?></label><br />
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_image')); ?>_1" name="<?php echo esc_attr($this->get_field_name('show_image')); ?>" value="1" <?php echo ($show_image==1 ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_image')); ?>_1"><?php _e('Show', 'trx_utils'); ?></label>
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_image')); ?>_0" name="<?php echo esc_attr($this->get_field_name('show_image')); ?>" value="0" <?php echo ($show_image==0 ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_image')); ?>_0"><?php _e('Hide', 'trx_utils'); ?></label>
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('show_author')); ?>_1"><?php _e('Show post author:', 'trx_utils'); ?></label><br />
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_author')); ?>_1" name="<?php echo esc_attr($this->get_field_name('show_author')); ?>" value="1" <?php echo ($show_author==1 ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_author')); ?>_1"><?php _e('Show', 'trx_utils'); ?></label>
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_author')); ?>_0" name="<?php echo esc_attr($this->get_field_name('show_author')); ?>" value="0" <?php echo ($show_author==0 ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_author')); ?>_0"><?php _e('Hide', 'trx_utils'); ?></label>
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('show_date')); ?>_1"><?php _e('Show post date:', 'trx_utils'); ?></label><br />
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_date')); ?>_1" name="<?php echo esc_attr($this->get_field_name('show_date')); ?>" value="1" <?php echo ($show_date==1 ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_date')); ?>_1"><?php _e('Show', 'trx_utils'); ?></label>
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_date')); ?>_0" name="<?php echo esc_attr($this->get_field_name('show_date')); ?>" value="0" <?php echo ($show_date==0 ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_date')); ?>_0"><?php _e('Hide', 'trx_utils'); ?></label>
		</p>


		<p>
			<label for="<?php echo esc_attr($this->get_field_id('show_counters')); ?>_1"><?php _e('Show post counters:', 'trx_utils'); ?></label><br />
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_counters')); ?>_2" name="<?php echo esc_attr($this->get_field_name('show_counters')); ?>" value="2" <?php echo ($show_counters==2 ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_counters')); ?>_2"><?php _e('As stars', 'trx_utils'); ?></label>
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_counters')); ?>_1" name="<?php echo esc_attr($this->get_field_name('show_counters')); ?>" value="1" <?php echo ($show_counters==1 ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_counters')); ?>_1"><?php _e('As icon', 'trx_utils'); ?></label>
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_counters')); ?>_0" name="<?php echo ($this->get_field_name('show_counters')); ?>" value="0" <?php echo ($show_counters==0 ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_counters')); ?>_0"><?php _e('Hide', 'trx_utils'); ?></label>
		</p>

	<?php
	}
}

?>