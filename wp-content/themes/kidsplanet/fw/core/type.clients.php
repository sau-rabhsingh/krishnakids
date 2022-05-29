<?php
/**
 * ThemeREX Framework: Clients post type settings
 *
 * @package	themerex
 * @since	themerex 1.0
 */

// Theme init
if (!function_exists('themerex_clients_theme_setup')) {
	add_action( 'themerex_action_before_init_theme', 'themerex_clients_theme_setup' );
	function themerex_clients_theme_setup() {

		// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
		add_filter('themerex_filter_get_blog_type',			'themerex_clients_get_blog_type', 9, 2);
		add_filter('themerex_filter_get_blog_title',		'themerex_clients_get_blog_title', 9, 2);
		add_filter('themerex_filter_get_current_taxonomy',	'themerex_clients_get_current_taxonomy', 9, 2);
		add_filter('themerex_filter_is_taxonomy',			'themerex_clients_is_taxonomy', 9, 2);
		add_filter('themerex_filter_get_stream_page_title',	'themerex_clients_get_stream_page_title', 9, 2);
		add_filter('themerex_filter_get_stream_page_link',	'themerex_clients_get_stream_page_link', 9, 2);
		add_filter('themerex_filter_get_stream_page_id',	'themerex_clients_get_stream_page_id', 9, 2);
		add_filter('themerex_filter_query_add_filters',		'themerex_clients_query_add_filters', 9, 2);
		add_filter('themerex_filter_detect_inheritance_key','themerex_clients_detect_inheritance_key', 9, 1);

		// Extra column for clients lists
		if (themerex_get_theme_option('show_overriden_posts')=='yes') {
			add_filter('manage_edit-clients_columns',			'themerex_post_add_options_column', 9);
			add_filter('manage_clients_posts_custom_column',	'themerex_post_fill_options_column', 9, 2);
		}

		if (function_exists('themerex_require_data')) {
			// Prepare type "Clients"
			themerex_require_data( 'post_type', 'clients', array(
				'label'               => esc_html__( 'Clients', 'kidsplanet'),
				'description'         => esc_html__( 'Clients Description', 'kidsplanet'),
				'labels'              => array(
					'name'                => esc_html_x( 'Clients', 'Post Type General Name', 'kidsplanet'),
					'singular_name'       => esc_html_x( 'Client', 'Post Type Singular Name', 'kidsplanet'),
					'menu_name'           => esc_html__( 'Clients', 'kidsplanet'),
					'parent_item_colon'   => esc_html__( 'Parent Item:', 'kidsplanet'),
					'all_items'           => esc_html__( 'All Clients', 'kidsplanet'),
					'view_item'           => esc_html__( 'View Item', 'kidsplanet'),
					'add_new_item'        => esc_html__( 'Add New Client', 'kidsplanet'),
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
			
			// Prepare taxonomy for clients
			themerex_require_data( 'taxonomy', 'clients_group', array(
				'post_type'			=> array( 'clients' ),
				'hierarchical'      => true,
				'labels'            => array(
					'name'              => esc_html_x( 'Clients Group', 'taxonomy general name', 'kidsplanet'),
					'singular_name'     => esc_html_x( 'Group', 'taxonomy singular name', 'kidsplanet'),
					'search_items'      => esc_html__( 'Search Groups', 'kidsplanet'),
					'all_items'         => esc_html__( 'All Groups', 'kidsplanet'),
					'parent_item'       => esc_html__( 'Parent Group', 'kidsplanet'),
					'parent_item_colon' => esc_html__( 'Parent Group:', 'kidsplanet'),
					'edit_item'         => esc_html__( 'Edit Group', 'kidsplanet'),
					'update_item'       => esc_html__( 'Update Group', 'kidsplanet'),
					'add_new_item'      => esc_html__( 'Add New Group', 'kidsplanet'),
					'new_item_name'     => esc_html__( 'New Group Name', 'kidsplanet'),
					'menu_name'         => esc_html__( 'Clients Group', 'kidsplanet'),
				),
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'clients_group' ),
				)
			);
		}
	}
}

if ( !function_exists( 'themerex_clients_settings_theme_setup2' ) ) {
	add_action( 'themerex_action_before_init_theme', 'themerex_clients_settings_theme_setup2', 3 );
	function themerex_clients_settings_theme_setup2() {
		// Add post type 'clients' and taxonomy 'clients_group' into theme inheritance list
		themerex_add_theme_inheritance( array('clients' => array(
			'stream_template' => 'blog-clients',
			'single_template' => 'single-client',
			'taxonomy' => array('clients_group'),
			'taxonomy_tags' => array(),
			'post_type' => array('clients'),
			'override' => 'page'
			) )
		);
	}
}


if (!function_exists('themerex_clients_after_theme_setup')) {
	add_action( 'themerex_action_after_init_theme', 'themerex_clients_after_theme_setup' );
	function themerex_clients_after_theme_setup() {
		// Update fields in the override options
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['post_override_options']) && $THEMEREX_GLOBALS['post_override_options']['page']=='clients') {
			// Meta box fields
			$THEMEREX_GLOBALS['post_override_options']['title'] = esc_html__('Client Options', 'kidsplanet');
			$THEMEREX_GLOBALS['post_override_options']['fields'] = array(
				"mb_partition_clients" => array(
					"title" => esc_html__('Clients', 'kidsplanet'),
					"override" => "page,post",
					"divider" => false,
					"icon" => "iconadmin-users",
					"type" => "partition"),
				"mb_info_clients_1" => array(
					"title" => esc_html__('Client details', 'kidsplanet'),
					"override" => "page,post",
					"divider" => false,
					"desc" => esc_html__('In this section you can put details for this client', 'kidsplanet'),
					"class" => "course_meta",
					"type" => "info"),
				"client_name" => array(
					"title" => esc_html__('Contact name',  'kidsplanet'),
					"desc" => esc_html__("Name of the contacts manager", 'kidsplanet'),
					"override" => "page,post",
					"class" => "client_name",
					"std" => '',
					"type" => "text"),
				"client_position" => array(
					"title" => esc_html__('Position',  'kidsplanet'),
					"desc" => esc_html__("Position of the contacts manager", 'kidsplanet'),
					"override" => "page,post",
					"class" => "client_position",
					"std" => '',
					"type" => "text"),
				"client_show_link" => array(
					"title" => esc_html__('Show link',  'kidsplanet'),
					"desc" => esc_html__("Show link to client page", 'kidsplanet'),
					"override" => "page,post",
					"class" => "client_show_link",
					"std" => "no",
					"options" => themerex_get_list_yesno(),
					"type" => "switch"),
				"client_link" => array(
					"title" => esc_html__('Link',  'kidsplanet'),
					"desc" => esc_html__("URL of the client's site. If empty - use link to this page", 'kidsplanet'),
					"override" => "page,post",
					"class" => "client_link",
					"std" => '',
					"type" => "text")
			);
		}
	}
}


// Return true, if current page is clients page
if ( !function_exists( 'themerex_is_clients_page' ) ) {
	function themerex_is_clients_page() {
		return get_query_var('post_type')=='clients' || is_tax('clients_group') || (is_page() && themerex_get_template_page_id('blog-clients')==get_the_ID());
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'themerex_clients_detect_inheritance_key' ) ) {
	//Handler of add_filter('themerex_filter_detect_inheritance_key',	'themerex_clients_detect_inheritance_key', 9, 1);
	function themerex_clients_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return themerex_is_clients_page() ? 'clients' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'themerex_clients_get_blog_type' ) ) {
	//Handler of add_filter('themerex_filter_get_blog_type',	'themerex_clients_get_blog_type', 9, 2);
	function themerex_clients_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if ($query && $query->is_tax('clients_group') || is_tax('clients_group'))
			$page = 'clients_category';
		else if ($query && $query->get('post_type')=='clients' || get_query_var('post_type')=='clients')
			$page = $query && $query->is_single() || is_single() ? 'clients_item' : 'clients';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'themerex_clients_get_blog_title' ) ) {
	//Handler of add_filter('themerex_filter_get_blog_title',	'themerex_clients_get_blog_title', 9, 2);
	function themerex_clients_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( themerex_strpos($page, 'clients')!==false ) {
			if ( $page == 'clients_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'clients_group' ), 'clients_group', OBJECT);
				$title = $term->name;
			} else if ( $page == 'clients_item' ) {
				$title = themerex_get_post_title();
			} else {
				$title = esc_html__('All clients', 'kidsplanet');
			}
		}

		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'themerex_clients_get_stream_page_title' ) ) {
	//Handler of add_filter('themerex_filter_get_stream_page_title',	'themerex_clients_get_stream_page_title', 9, 2);
	function themerex_clients_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (themerex_strpos($page, 'clients')!==false) {
			if (($page_id = themerex_clients_get_stream_page_id(0, $page=='clients' ? 'blog-clients' : $page)) > 0)
				$title = themerex_get_post_title($page_id);
			else
				$title = esc_html__('All clients', 'kidsplanet');
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'themerex_clients_get_stream_page_id' ) ) {
	//Handler of add_filter('themerex_filter_get_stream_page_id',	'themerex_clients_get_stream_page_id', 9, 2);
	function themerex_clients_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (themerex_strpos($page, 'clients')!==false) $id = themerex_get_template_page_id('blog-clients');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'themerex_clients_get_stream_page_link' ) ) {
	//Handler of add_filter('themerex_filter_get_stream_page_link',	'themerex_clients_get_stream_page_link', 9, 2);
	function themerex_clients_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (themerex_strpos($page, 'clients')!==false) {
			$id = themerex_get_template_page_id('blog-clients');
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'themerex_clients_get_current_taxonomy' ) ) {
	//Handler of add_filter('themerex_filter_get_current_taxonomy',	'themerex_clients_get_current_taxonomy', 9, 2);
	function themerex_clients_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( themerex_strpos($page, 'clients')!==false ) {
			$tax = 'clients_group';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'themerex_clients_is_taxonomy' ) ) {
	//Handler of add_filter('themerex_filter_is_taxonomy',	'themerex_clients_is_taxonomy', 9, 2);
	function themerex_clients_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query && $query->get('clients_group')!='' || is_tax('clients_group') ? 'clients_group' : '';
	}
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'themerex_clients_query_add_filters' ) ) {
	//Handler of add_filter('themerex_filter_query_add_filters',	'themerex_clients_query_add_filters', 9, 2);
	function themerex_clients_query_add_filters($args, $filter) {
		if ($filter == 'clients') {
			$args['post_type'] = 'clients';
		}
		return $args;
	}
}
?>