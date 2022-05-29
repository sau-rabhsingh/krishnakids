<?php
/**
 * ThemeREX Framework: Team post type settings
 *
 * @package	themerex
 * @since	themerex 1.0
 */

// Theme init
if (!function_exists('themerex_team_theme_setup')) {
	add_action( 'themerex_action_before_init_theme', 'themerex_team_theme_setup' );
	function themerex_team_theme_setup() {

		// Add item in the admin menu
        add_filter('trx_utils_filter_override_options',							'themerex_team_add_override_options');

		// Save data from override options
		add_action('save_post',								'themerex_team_save_data');
		
		// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
		add_filter('themerex_filter_get_blog_type',			'themerex_team_get_blog_type', 9, 2);
		add_filter('themerex_filter_get_blog_title',		'themerex_team_get_blog_title', 9, 2);
		add_filter('themerex_filter_get_current_taxonomy',	'themerex_team_get_current_taxonomy', 9, 2);
		add_filter('themerex_filter_is_taxonomy',			'themerex_team_is_taxonomy', 9, 2);
		add_filter('themerex_filter_get_stream_page_title',	'themerex_team_get_stream_page_title', 9, 2);
		add_filter('themerex_filter_get_stream_page_link',	'themerex_team_get_stream_page_link', 9, 2);
		add_filter('themerex_filter_get_stream_page_id',	'themerex_team_get_stream_page_id', 9, 2);
		add_filter('themerex_filter_query_add_filters',		'themerex_team_query_add_filters', 9, 2);
		add_filter('themerex_filter_detect_inheritance_key','themerex_team_detect_inheritance_key', 9, 1);

		// Extra column for team members lists
		if (themerex_get_theme_option('show_overriden_posts')=='yes') {
			add_filter('manage_edit-team_columns',			'themerex_post_add_options_column', 9);
			add_filter('manage_team_posts_custom_column',	'themerex_post_fill_options_column', 9, 2);
		}

		// Meta box fields
		global $THEMEREX_GLOBALS;
		$THEMEREX_GLOBALS['team_override_options'] = array(
			'id' => 'team-override-options',
			'title' => esc_html__('Team Member Details', 'kidsplanet'),
			'page' => 'team',
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				"team_member_position" => array(
					"title" => esc_html__('Position',  'kidsplanet'),
					"desc" => esc_html__("Position of the team member", 'kidsplanet'),
					"class" => "team_member_position",
					"std" => "",
					"type" => "text"),
				"team_member_email" => array(
					"title" => esc_html__("E-mail",  'kidsplanet'),
					"desc" => esc_html__("E-mail of the team member - need to take Gravatar (if registered)", 'kidsplanet'),
					"class" => "team_member_email",
					"std" => "",
					"type" => "text"),
				"team_member_link" => array(
					"title" => esc_html__('Link to profile',  'kidsplanet'),
					"desc" => esc_html__("URL of the team member profile page (if not this page)", 'kidsplanet'),
					"class" => "team_member_link",
					"std" => "",
					"type" => "text"),
				"team_member_socials" => array(
					"title" => esc_html__("Social links",  'kidsplanet'),
					"desc" => esc_html__("Links to the social profiles of the team member", 'kidsplanet'),
					"class" => "team_member_email",
					"std" => "",
					"type" => "social")
			)
		);
		
		if (function_exists('themerex_require_data')) {
			// Prepare type "Team"
			themerex_require_data( 'post_type', 'team', array(
				'label'               => esc_html__( 'Team member', 'kidsplanet'),
				'description'         => esc_html__( 'Team Description', 'kidsplanet'),
				'labels'              => array(
					'name'                => esc_html_x( 'Team', 'Post Type General Name', 'kidsplanet'),
					'singular_name'       => esc_html_x( 'Team member', 'Post Type Singular Name', 'kidsplanet'),
					'menu_name'           => esc_html__( 'Team', 'kidsplanet'),
					'parent_item_colon'   => esc_html__( 'Parent Item:', 'kidsplanet'),
					'all_items'           => esc_html__( 'All Team', 'kidsplanet'),
					'view_item'           => esc_html__( 'View Item', 'kidsplanet'),
					'add_new_item'        => esc_html__( 'Add New Team member', 'kidsplanet'),
					'add_new'             => esc_html__( 'Add New', 'kidsplanet'),
					'edit_item'           => esc_html__( 'Edit Item', 'kidsplanet'),
					'update_item'         => esc_html__( 'Update Item', 'kidsplanet'),
					'search_items'        => esc_html__( 'Search Item', 'kidsplanet'),
					'not_found'           => esc_html__( 'Not found', 'kidsplanet'),
					'not_found_in_trash'  => esc_html__( 'Not found in Trash', 'kidsplanet'),
				),
				'supports'            => array( 'title', 'excerpt', 'editor', 'author', 'thumbnail', 'comments', 'custom-fields'),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'menu_icon'			  => 'dashicons-admin-users',
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => true,
				'menu_position'       => 25,
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'query_var'           => true,
				'capability_type'     => 'page',
				'rewrite'             => true
				)
			);
			
			// Prepare taxonomy for team
			themerex_require_data( 'taxonomy', 'team_group', array(
				'post_type'			=> array( 'team' ),
				'hierarchical'      => true,
				'labels'            => array(
					'name'              => esc_html_x( 'Team Group', 'taxonomy general name', 'kidsplanet'),
					'singular_name'     => esc_html_x( 'Group', 'taxonomy singular name', 'kidsplanet'),
					'search_items'      => esc_html__( 'Search Groups', 'kidsplanet'),
					'all_items'         => esc_html__( 'All Groups', 'kidsplanet'),
					'parent_item'       => esc_html__( 'Parent Group', 'kidsplanet'),
					'parent_item_colon' => esc_html__( 'Parent Group:', 'kidsplanet'),
					'edit_item'         => esc_html__( 'Edit Group', 'kidsplanet'),
					'update_item'       => esc_html__( 'Update Group', 'kidsplanet'),
					'add_new_item'      => esc_html__( 'Add New Group', 'kidsplanet'),
					'new_item_name'     => esc_html__( 'New Group Name', 'kidsplanet'),
					'menu_name'         => esc_html__( 'Team Group', 'kidsplanet'),
				),
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'team_group' ),
				)
			);
		}
	}
}

if ( !function_exists( 'themerex_team_settings_theme_setup2' ) ) {
	add_action( 'themerex_action_before_init_theme', 'themerex_team_settings_theme_setup2', 3 );
	function themerex_team_settings_theme_setup2() {
		// Add post type 'team' and taxonomy 'team_group' into theme inheritance list
		themerex_add_theme_inheritance( array('team' => array(
			'stream_template' => 'blog-team',
			'single_template' => 'single-team',
			'taxonomy' => array('team_group'),
			'taxonomy_tags' => array(),
			'post_type' => array('team'),
			'override' => 'page'
			) )
		);
	}
}


// Add override options
if (!function_exists('themerex_team_add_override_options')) {
	//Handler of add_filter('trx_utils_filter_override_options', 'themerex_team_add_override_options');
	function themerex_team_add_override_options($boxes = array()) {
        $boxes[] = array_merge(themerex_get_global('team_override_options'), array('callback' => 'themerex_team_show_override_options'));
        return $boxes;
	}
}

// Callback function to show fields in override options
if (!function_exists('themerex_team_show_override_options')) {
	function themerex_team_show_override_options() {
		global $post, $THEMEREX_GLOBALS;

		// Use nonce for verification
		$data = get_post_meta($post->ID, 'team_data', true);
		$fields = $THEMEREX_GLOBALS['team_override_options']['fields'];
		?>
		<input type="hidden" name="override_options_team_nonce" value="<?php echo wp_create_nonce(basename(__FILE__)); ?>" />
		<table class="team_area">
		<?php
		if (is_array($fields) && count($fields) > 0) {
			foreach ($fields as $id=>$field) { 
				$meta = isset($data[$id]) ? $data[$id] : '';
				?>
				<tr class="team_field <?php echo esc_attr($field['class']); ?>" valign="top">
					<td><label for="<?php echo esc_attr($id); ?>"><?php echo esc_html($field['title']); ?></label></td>
					<td>
						<?php
						if ($id == 'team_member_socials') {
							$socials_type = themerex_get_theme_setting('socials_type');
							$social_list = themerex_get_theme_option('social_icons');
							if (is_array($social_list) && count($social_list) > 0) {
								foreach ($social_list as $soc) {
									if ($socials_type == 'icons') {
										$parts = explode('-', $soc['icon'], 2);
                                        $sn = isset($sn) ? $sn : '';
										$sn = isset($parts[1]) ? $parts[1] : $sn;
									} else {
										$sn = basename($soc['icon']);
										$sn = themerex_substr($sn, 0, themerex_strrpos($sn, '.'));
										if (($pos=themerex_strrpos($sn, '_'))!==false)
											$sn = themerex_substr($sn, 0, $pos);
									}   
									$link = isset($meta[$sn]) ? $meta[$sn] : '';
									?>
									<label for="<?php echo esc_attr(($id).'_'.($sn)); ?>"><?php echo esc_html(themerex_strtoproper($sn)); ?></label><br>
									<input type="text" name="<?php echo esc_attr($id); ?>[<?php echo esc_attr($sn); ?>]" id="<?php echo esc_attr(($id).'_'.($sn)); ?>" value="<?php echo esc_attr($link); ?>" size="30" /><br>
									<?php
								}
							}
						} else {
							?>
							<input type="text" name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($meta); ?>" size="30" />
							<?php
						}
						?>
						<br><small><?php echo esc_html($field['desc']); ?></small>
					</td>
				</tr>
				<?php
			}
		}
		?>
		</table>
		<?php
	}
}


// Save data from override options
if (!function_exists('themerex_team_save_data')) {
	//Handler of add_action('save_post', 'themerex_team_save_data');
	function themerex_team_save_data($post_id) {
		// verify nonce
		if (!isset($_POST['override_options_team_nonce']) || !wp_verify_nonce($_POST['override_options_team_nonce'], basename(__FILE__))) {
			return $post_id;
		}

		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		// check permissions
		if ($_POST['post_type']!='team' || !current_user_can('edit_post', $post_id)) {
			return $post_id;
		}

		global $THEMEREX_GLOBALS;

		$data = array();

		$fields = $THEMEREX_GLOBALS['team_override_options']['fields'];

		// Post type specific data handling
		if (is_array($fields) && count($fields) > 0) {
			foreach ($fields as $id=>$field) {
                $social_temp = array();
                if (isset($_POST[$id])) {
                    if (is_array($_POST[$id]) && count($_POST[$id]) > 0) {
                        foreach ($_POST[$id] as $sn=>$link) {
                            $social_temp[$sn] = stripslashes($link);
                        }
                        $data[$id] = $social_temp;
					} else {
						$data[$id] = stripslashes($_POST[$id]);
					}
				}
			}
		}

		update_post_meta($post_id, 'team_data', $data);
	}
}



// Return true, if current page is team member page
if ( !function_exists( 'themerex_is_team_page' ) ) {
	function themerex_is_team_page() {
		return get_query_var('post_type')=='team' || is_tax('team_group') || (is_page() && themerex_get_template_page_id('blog-team')==get_the_ID());
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'themerex_team_detect_inheritance_key' ) ) {
	//Handler of add_filter('themerex_filter_detect_inheritance_key',	'themerex_team_detect_inheritance_key', 9, 1);
	function themerex_team_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return themerex_is_team_page() ? 'team' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'themerex_team_get_blog_type' ) ) {
	//Handler of add_filter('themerex_filter_get_blog_type',	'themerex_team_get_blog_type', 9, 2);
	function themerex_team_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if ($query && $query->is_tax('team_group') || is_tax('team_group'))
			$page = 'team_category';
		else if ($query && $query->get('post_type')=='team' || get_query_var('post_type')=='team')
			$page = $query && $query->is_single() || is_single() ? 'team_item' : 'team';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'themerex_team_get_blog_title' ) ) {
	//Handler of add_filter('themerex_filter_get_blog_title',	'themerex_team_get_blog_title', 9, 2);
	function themerex_team_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( themerex_strpos($page, 'team')!==false ) {
			if ( $page == 'team_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'team_group' ), 'team_group', OBJECT);
				$title = $term->name;
			} else if ( $page == 'team_item' ) {
				$title = themerex_get_post_title();
			} else {
				$title = esc_html__('All team', 'kidsplanet');
			}
		}

		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'themerex_team_get_stream_page_title' ) ) {
	//Handler of add_filter('themerex_filter_get_stream_page_title',	'themerex_team_get_stream_page_title', 9, 2);
	function themerex_team_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (themerex_strpos($page, 'team')!==false) {
			if (($page_id = themerex_team_get_stream_page_id(0, $page=='team' ? 'blog-team' : $page)) > 0)
				$title = themerex_get_post_title($page_id);
			else
				$title = esc_html__('All team', 'kidsplanet');
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'themerex_team_get_stream_page_id' ) ) {
	//Handler of add_filter('themerex_filter_get_stream_page_id',	'themerex_team_get_stream_page_id', 9, 2);
	function themerex_team_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (themerex_strpos($page, 'team')!==false) $id = themerex_get_template_page_id('blog-team');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'themerex_team_get_stream_page_link' ) ) {
	//Handler of add_filter('themerex_filter_get_stream_page_link',	'themerex_team_get_stream_page_link', 9, 2);
	function themerex_team_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (themerex_strpos($page, 'team')!==false) {
			$id = themerex_get_template_page_id('blog-team');
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'themerex_team_get_current_taxonomy' ) ) {
	//Handler of add_filter('themerex_filter_get_current_taxonomy',	'themerex_team_get_current_taxonomy', 9, 2);
	function themerex_team_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( themerex_strpos($page, 'team')!==false ) {
			$tax = 'team_group';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'themerex_team_is_taxonomy' ) ) {
	//Handler of add_filter('themerex_filter_is_taxonomy',	'themerex_team_is_taxonomy', 9, 2);
	function themerex_team_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query && $query->get('team_group')!='' || is_tax('team_group') ? 'team_group' : '';
	}
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'themerex_team_query_add_filters' ) ) {
	//Handler of add_filter('themerex_filter_query_add_filters',	'themerex_team_query_add_filters', 9, 2);
	function themerex_team_query_add_filters($args, $filter) {
		if ($filter == 'team') {
			$args['post_type'] = 'team';
		}
		return $args;
	}
}
?>