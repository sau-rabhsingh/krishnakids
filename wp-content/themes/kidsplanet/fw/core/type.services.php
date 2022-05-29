<?php
/**
 * ThemeREX Framework: Services post type settings
 *
 * @package	themerex
 * @since	themerex 1.0
 */

// Theme init
if (!function_exists('themerex_services_theme_setup')) {
	add_action( 'themerex_action_before_init_theme', 'themerex_services_theme_setup' );
	function themerex_services_theme_setup() {
		
		// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
		add_filter('themerex_filter_get_blog_type',			'themerex_services_get_blog_type', 9, 2);
		add_filter('themerex_filter_get_blog_title',		'themerex_services_get_blog_title', 9, 2);
		add_filter('themerex_filter_get_current_taxonomy',	'themerex_services_get_current_taxonomy', 9, 2);
		add_filter('themerex_filter_is_taxonomy',			'themerex_services_is_taxonomy', 9, 2);
		add_filter('themerex_filter_get_stream_page_title',	'themerex_services_get_stream_page_title', 9, 2);
		add_filter('themerex_filter_get_stream_page_link',	'themerex_services_get_stream_page_link', 9, 2);
		add_filter('themerex_filter_get_stream_page_id',	'themerex_services_get_stream_page_id', 9, 2);
		add_filter('themerex_filter_query_add_filters',		'themerex_services_query_add_filters', 9, 2);
		add_filter('themerex_filter_detect_inheritance_key','themerex_services_detect_inheritance_key', 9, 1);

		// Extra column for services lists
		if (themerex_get_theme_option('show_overriden_posts')=='yes') {
			add_filter('manage_edit-services_columns',			'themerex_post_add_options_column', 9);
			add_filter('manage_services_posts_custom_column',	'themerex_post_fill_options_column', 9, 2);
		}

		if (function_exists('themerex_require_data')) {
			// Prepare type "Team"
			themerex_require_data( 'post_type', 'services', array(
				'label'               => esc_html__( 'Service item', 'kidsplanet'),
				'description'         => esc_html__( 'Service Description', 'kidsplanet'),
				'labels'              => array(
					'name'                => esc_html_x( 'Services', 'Post Type General Name', 'kidsplanet'),
					'singular_name'       => esc_html_x( 'Service item', 'Post Type Singular Name', 'kidsplanet'),
					'menu_name'           => esc_html__( 'Services', 'kidsplanet'),
					'parent_item_colon'   => esc_html__( 'Parent Item:', 'kidsplanet'),
					'all_items'           => esc_html__( 'All Services', 'kidsplanet'),
					'view_item'           => esc_html__( 'View Item', 'kidsplanet'),
					'add_new_item'        => esc_html__( 'Add New Service', 'kidsplanet'),
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
				'menu_icon'			  => 'dashicons-info',
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
			themerex_require_data( 'taxonomy', 'services_group', array(
				'post_type'			=> array( 'services' ),
				'hierarchical'      => true,
				'labels'            => array(
					'name'              => esc_html_x( 'Services Group', 'taxonomy general name', 'kidsplanet'),
					'singular_name'     => esc_html_x( 'Group', 'taxonomy singular name', 'kidsplanet'),
					'search_items'      => esc_html__( 'Search Groups', 'kidsplanet'),
					'all_items'         => esc_html__( 'All Groups', 'kidsplanet'),
					'parent_item'       => esc_html__( 'Parent Group', 'kidsplanet'),
					'parent_item_colon' => esc_html__( 'Parent Group:', 'kidsplanet'),
					'edit_item'         => esc_html__( 'Edit Group', 'kidsplanet'),
					'update_item'       => esc_html__( 'Update Group', 'kidsplanet'),
					'add_new_item'      => esc_html__( 'Add New Group', 'kidsplanet'),
					'new_item_name'     => esc_html__( 'New Group Name', 'kidsplanet'),
					'menu_name'         => esc_html__( 'Services Group', 'kidsplanet'),
				),
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'services_group' ),
				)
			);
		}
	}
}

if ( !function_exists( 'themerex_services_settings_theme_setup2' ) ) {
	add_action( 'themerex_action_before_init_theme', 'themerex_services_settings_theme_setup2', 3 );
	function themerex_services_settings_theme_setup2() {
		// Add post type 'services' and taxonomy 'services_group' into theme inheritance list
		themerex_add_theme_inheritance( array('services' => array(
			'stream_template' => 'blog-services',
			'single_template' => 'single-services',
			'taxonomy' => array('services_group'),
			'taxonomy_tags' => array(),
			'post_type' => array('services'),
			'override' => 'page'
			) )
		);
	}
}



// Return true, if current page is services page
if ( !function_exists( 'themerex_is_services_page' ) ) {
	function themerex_is_services_page() {
		return get_query_var('post_type')=='services' || is_tax('services_group') || (is_page() && themerex_get_template_page_id('blog-services')==get_the_ID());
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'themerex_services_detect_inheritance_key' ) ) {
	//Handler of add_filter('themerex_filter_detect_inheritance_key',	'themerex_services_detect_inheritance_key', 9, 1);
	function themerex_services_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return themerex_is_services_page() ? 'services' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'themerex_services_get_blog_type' ) ) {
	//Handler of add_filter('themerex_filter_get_blog_type',	'themerex_services_get_blog_type', 9, 2);
	function themerex_services_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if ($query && $query->is_tax('services_group') || is_tax('services_group'))
			$page = 'services_category';
		else if ($query && $query->get('post_type')=='services' || get_query_var('post_type')=='services')
			$page = $query && $query->is_single() || is_single() ? 'services_item' : 'services';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'themerex_services_get_blog_title' ) ) {
	//Handler of add_filter('themerex_filter_get_blog_title',	'themerex_services_get_blog_title', 9, 2);
	function themerex_services_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( themerex_strpos($page, 'services')!==false ) {
			if ( $page == 'services_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'services_group' ), 'services_group', OBJECT);
				$title = $term->name;
			} else if ( $page == 'services_item' ) {
				$title = themerex_get_post_title();
			} else {
				$title = esc_html__('All services', 'kidsplanet');
			}
		}
		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'themerex_services_get_stream_page_title' ) ) {
	//Handler of add_filter('themerex_filter_get_stream_page_title',	'themerex_services_get_stream_page_title', 9, 2);
	function themerex_services_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (themerex_strpos($page, 'services')!==false) {
			if (($page_id = themerex_services_get_stream_page_id(0, $page=='services' ? 'blog-services' : $page)) > 0)
				$title = themerex_get_post_title($page_id);
			else
				$title = esc_html__('All services', 'kidsplanet');
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'themerex_services_get_stream_page_id' ) ) {
	//Handler of add_filter('themerex_filter_get_stream_page_id',	'themerex_services_get_stream_page_id', 9, 2);
	function themerex_services_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (themerex_strpos($page, 'services')!==false) $id = themerex_get_template_page_id('blog-services');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'themerex_services_get_stream_page_link' ) ) {
	//Handler of add_filter('themerex_filter_get_stream_page_link',	'themerex_services_get_stream_page_link', 9, 2);
	function themerex_services_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (themerex_strpos($page, 'services')!==false) {
			$id = themerex_get_template_page_id('blog-services');
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'themerex_services_get_current_taxonomy' ) ) {
	//Handler of add_filter('themerex_filter_get_current_taxonomy',	'themerex_services_get_current_taxonomy', 9, 2);
	function themerex_services_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( themerex_strpos($page, 'services')!==false ) {
			$tax = 'services_group';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'themerex_services_is_taxonomy' ) ) {
	//Handler of add_filter('themerex_filter_is_taxonomy',	'themerex_services_is_taxonomy', 9, 2);
	function themerex_services_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query && $query->get('services_group')!='' || is_tax('services_group') ? 'services_group' : '';
	}
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'themerex_services_query_add_filters' ) ) {
	//Handler of add_filter('themerex_filter_query_add_filters',	'themerex_services_query_add_filters', 9, 2);
	function themerex_services_query_add_filters($args, $filter) {
		if ($filter == 'services') {
			$args['post_type'] = 'services';
		}
		return $args;
	}
}
?>