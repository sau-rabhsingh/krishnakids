<?php
/**
 * ThemeREX Framework: return lists
 *
 * @package themerex
 * @since themerex 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }



// Return styles list
if ( !function_exists( 'themerex_get_list_styles' ) ) {
	function themerex_get_list_styles($from=1, $to=2, $prepend_inherit=false) {
		$list = array();
		for ($i=$from; $i<=$to; $i++)
			$list[$i] = sprintf(esc_html__('Style %d', 'kidsplanet'), $i);
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}


// Return list of the animations
if ( !function_exists( 'themerex_get_list_animations' ) ) {
	function themerex_get_list_animations($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_animations']))
			$list = $THEMEREX_GLOBALS['list_animations'];
		else {
			$list = array();
			$list['none']			= esc_html__('- None -',	'kidsplanet');
			$list['bounced']		= esc_html__('Bounced',		'kidsplanet');
			$list['flash']			= esc_html__('Flash',		'kidsplanet');
			$list['flip']			= esc_html__('Flip',		'kidsplanet');
			$list['pulse']			= esc_html__('Pulse',		'kidsplanet');
			$list['rubberBand']		= esc_html__('Rubber Band',	'kidsplanet');
			$list['shake']			= esc_html__('Shake',		'kidsplanet');
			$list['swing']			= esc_html__('Swing',		'kidsplanet');
			$list['tada']			= esc_html__('Tada',		'kidsplanet');
			$list['wobble']			= esc_html__('Wobble',		'kidsplanet');
			$THEMEREX_GLOBALS['list_animations'] = $list = apply_filters('themerex_filter_list_animations', $list);
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}


// Return list of the enter animations
if ( !function_exists( 'themerex_get_list_animations_in' ) ) {
	function themerex_get_list_animations_in($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_animations_in']))
			$list = $THEMEREX_GLOBALS['list_animations_in'];
		else {
			$list = array();
			$list['none']			= esc_html__('- None -',	'kidsplanet');
			$list['bounceIn']		= esc_html__('Bounce In',			'kidsplanet');
			$list['bounceInUp']		= esc_html__('Bounce In Up',		'kidsplanet');
			$list['bounceInDown']	= esc_html__('Bounce In Down',		'kidsplanet');
			$list['bounceInLeft']	= esc_html__('Bounce In Left',		'kidsplanet');
			$list['bounceInRight']	= esc_html__('Bounce In Right',		'kidsplanet');
			$list['fadeIn']			= esc_html__('Fade In',				'kidsplanet');
			$list['fadeInUp']		= esc_html__('Fade In Up',			'kidsplanet');
			$list['fadeInDown']		= esc_html__('Fade In Down',		'kidsplanet');
			$list['fadeInLeft']		= esc_html__('Fade In Left',		'kidsplanet');
			$list['fadeInRight']	= esc_html__('Fade In Right',		'kidsplanet');
			$list['fadeInUpBig']	= esc_html__('Fade In Up Big',		'kidsplanet');
			$list['fadeInDownBig']	= esc_html__('Fade In Down Big',	'kidsplanet');
			$list['fadeInLeftBig']	= esc_html__('Fade In Left Big',	'kidsplanet');
			$list['fadeInRightBig']	= esc_html__('Fade In Right Big',	'kidsplanet');
			$list['flipInX']		= esc_html__('Flip In X',			'kidsplanet');
			$list['flipInY']		= esc_html__('Flip In Y',			'kidsplanet');
			$list['lightSpeedIn']	= esc_html__('Light Speed In',		'kidsplanet');
			$list['rotateIn']		= esc_html__('Rotate In',			'kidsplanet');
			$list['rotateInUpLeft']		= esc_html__('Rotate In Down Left',	'kidsplanet');
			$list['rotateInUpRight']	= esc_html__('Rotate In Up Right',	'kidsplanet');
			$list['rotateInDownLeft']	= esc_html__('Rotate In Up Left',	'kidsplanet');
			$list['rotateInDownRight']	= esc_html__('Rotate In Down Right', 'kidsplanet');
			$list['rollIn']				= esc_html__('Roll In',			'kidsplanet');
			$list['slideInUp']			= esc_html__('Slide In Up',		'kidsplanet');
			$list['slideInDown']		= esc_html__('Slide In Down',	'kidsplanet');
			$list['slideInLeft']		= esc_html__('Slide In Left',	'kidsplanet');
			$list['slideInRight']		= esc_html__('Slide In Right',	'kidsplanet');
			$list['zoomIn']				= esc_html__('Zoom In',			'kidsplanet');
			$list['zoomInUp']			= esc_html__('Zoom In Up',		'kidsplanet');
			$list['zoomInDown']			= esc_html__('Zoom In Down',	'kidsplanet');
			$list['zoomInLeft']			= esc_html__('Zoom In Left',	'kidsplanet');
			$list['zoomInRight']		= esc_html__('Zoom In Right',	'kidsplanet');
			$THEMEREX_GLOBALS['list_animations_in'] = $list = apply_filters('themerex_filter_list_animations_in', $list);
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}


// Return list of the out animations
if ( !function_exists( 'themerex_get_list_animations_out' ) ) {
	function themerex_get_list_animations_out($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_animations_out']))
			$list = $THEMEREX_GLOBALS['list_animations_out'];
		else {
			$list = array();
			$list['none']			= esc_html__('- None -',	'kidsplanet');
			$list['bounceOut']		= esc_html__('Bounce Out',			'kidsplanet');
			$list['bounceOutUp']	= esc_html__('Bounce Out Up',		'kidsplanet');
			$list['bounceOutDown']	= esc_html__('Bounce Out Down',		'kidsplanet');
			$list['bounceOutLeft']	= esc_html__('Bounce Out Left',		'kidsplanet');
			$list['bounceOutRight']	= esc_html__('Bounce Out Right',	'kidsplanet');
			$list['fadeOut']		= esc_html__('Fade Out',			'kidsplanet');
			$list['fadeOutUp']		= esc_html__('Fade Out Up',			'kidsplanet');
			$list['fadeOutDown']	= esc_html__('Fade Out Down',		'kidsplanet');
			$list['fadeOutLeft']	= esc_html__('Fade Out Left',		'kidsplanet');
			$list['fadeOutRight']	= esc_html__('Fade Out Right',		'kidsplanet');
			$list['fadeOutUpBig']	= esc_html__('Fade Out Up Big',		'kidsplanet');
			$list['fadeOutDownBig']	= esc_html__('Fade Out Down Big',	'kidsplanet');
			$list['fadeOutLeftBig']	= esc_html__('Fade Out Left Big',	'kidsplanet');
			$list['fadeOutRightBig']= esc_html__('Fade Out Right Big',	'kidsplanet');
			$list['flipOutX']		= esc_html__('Flip Out X',			'kidsplanet');
			$list['flipOutY']		= esc_html__('Flip Out Y',			'kidsplanet');
			$list['hinge']			= esc_html__('Hinge Out',			'kidsplanet');
			$list['lightSpeedOut']	= esc_html__('Light Speed Out',		'kidsplanet');
			$list['rotateOut']		= esc_html__('Rotate Out',			'kidsplanet');
			$list['rotateOutUpLeft']	= esc_html__('Rotate Out Down Left',	'kidsplanet');
			$list['rotateOutUpRight']	= esc_html__('Rotate Out Up Right',		'kidsplanet');
			$list['rotateOutDownLeft']	= esc_html__('Rotate Out Up Left',		'kidsplanet');
			$list['rotateOutDownRight']	= esc_html__('Rotate Out Down Right',	'kidsplanet');
			$list['rollOut']			= esc_html__('Roll Out',		'kidsplanet');
			$list['slideOutUp']			= esc_html__('Slide Out Up',		'kidsplanet');
			$list['slideOutDown']		= esc_html__('Slide Out Down',	'kidsplanet');
			$list['slideOutLeft']		= esc_html__('Slide Out Left',	'kidsplanet');
			$list['slideOutRight']		= esc_html__('Slide Out Right',	'kidsplanet');
			$list['zoomOut']			= esc_html__('Zoom Out',			'kidsplanet');
			$list['zoomOutUp']			= esc_html__('Zoom Out Up',		'kidsplanet');
			$list['zoomOutDown']		= esc_html__('Zoom Out Down',	'kidsplanet');
			$list['zoomOutLeft']		= esc_html__('Zoom Out Left',	'kidsplanet');
			$list['zoomOutRight']		= esc_html__('Zoom Out Right',	'kidsplanet');
			$THEMEREX_GLOBALS['list_animations_out'] = $list = apply_filters('themerex_filter_list_animations_out', $list);
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}

// Return classes list for the specified animation
if (!function_exists('themerex_get_animation_classes')) {
	function themerex_get_animation_classes($animation, $speed='normal', $loop='none') {
		return themerex_param_is_off($animation) ? '' : 'animated '.esc_attr($animation).' '.esc_attr($speed).(!themerex_param_is_off($loop) ? ' '.esc_attr($loop) : '');
	}
}


// Return list of categories
if ( !function_exists( 'themerex_get_list_categories' ) ) {
	function themerex_get_list_categories($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_categories']))
			$list = $THEMEREX_GLOBALS['list_categories'];
		else {
			$list = array();
			$args = array(
				'type'                     => 'post',
				'child_of'                 => 0,
				'parent'                   => '',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 0,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'taxonomy'                 => 'category',
				'pad_counts'               => false );
			$taxonomies = get_categories( $args );
			if (is_array($taxonomies) && count($taxonomies) > 0) {
				foreach ($taxonomies as $cat) {
					$list[$cat->term_id] = $cat->name;
				}
			}
			$THEMEREX_GLOBALS['list_categories'] = $list;
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}


// Return list of taxonomies
if ( !function_exists( 'themerex_get_list_terms' ) ) {
	function themerex_get_list_terms($prepend_inherit=false, $taxonomy='category') {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_taxonomies_'.($taxonomy)]))
			$list = $THEMEREX_GLOBALS['list_taxonomies_'.($taxonomy)];
		else {
			$list = array();
			$args = array(
				'child_of'                 => 0,
				'parent'                   => '',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 0,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'taxonomy'                 => $taxonomy,
				'pad_counts'               => false );
			$taxonomies = get_terms( $taxonomy, $args );
			if (is_array($taxonomies) && count($taxonomies) > 0) {
				foreach ($taxonomies as $cat) {
					$list[$cat->term_id] = $cat->name;
				}
			}
			$THEMEREX_GLOBALS['list_taxonomies_'.($taxonomy)] = $list;
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}

// Return list of post's types
if ( !function_exists( 'themerex_get_list_posts_types' ) ) {
	function themerex_get_list_posts_types($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_posts_types']))
			$list = $THEMEREX_GLOBALS['list_posts_types'];
		else {
			$list = array();
			// Return only theme inheritance supported post types
			$THEMEREX_GLOBALS['list_posts_types'] = $list = apply_filters('themerex_filter_list_post_types', $list);
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}


// Return list post items from any post type and taxonomy
if ( !function_exists( 'themerex_get_list_posts' ) ) {
	function themerex_get_list_posts($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'post',
			'post_status'		=> 'publish',
			'taxonomy'			=> 'category',
			'taxonomy_value'	=> '',
			'posts_per_page'	=> -1,
			'orderby'			=> 'post_date',
			'order'				=> 'desc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));

		global $THEMEREX_GLOBALS;
		$hash = 'list_posts_'.($opt['post_type']).'_'.($opt['taxonomy']).'_'.($opt['taxonomy_value']).'_'.($opt['orderby']).'_'.($opt['order']).'_'.($opt['return']).'_'.($opt['posts_per_page']);
		if (isset($THEMEREX_GLOBALS[$hash]))
			$list = $THEMEREX_GLOBALS[$hash];
		else {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'kidsplanet');
			$args = array(
				'post_type' => $opt['post_type'],
				'post_status' => $opt['post_status'],
				'posts_per_page' => $opt['posts_per_page'],
				'ignore_sticky_posts' => true,
				'orderby'	=> $opt['orderby'],
				'order'		=> $opt['order']
			);
			if (!empty($opt['taxonomy_value'])) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => $opt['taxonomy'],
						'field' => (int) $opt['taxonomy_value'] > 0 ? 'id' : 'slug',
						'terms' => $opt['taxonomy_value']
					)
				);
			}
			$posts = get_posts( $args );
			if (is_array($posts) && count($posts) > 0) {
				foreach ($posts as $post) {
					$list[$opt['return']=='id' ? $post->ID : $post->post_title] = $post->post_title;
				}
			}
			$THEMEREX_GLOBALS[$hash] = $list;
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}


// Return list of registered users
if ( !function_exists( 'themerex_get_list_users' ) ) {
	function themerex_get_list_users($prepend_inherit=false, $roles=array('administrator', 'editor', 'author', 'contributor', 'shop_manager')) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_users']))
			$list = $THEMEREX_GLOBALS['list_users'];
		else {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'kidsplanet');
			$args = array(
				'orderby'	=> 'display_name',
				'order'		=> 'ASC' );
			$users = get_users( $args );
			if (is_array($users) && count($users) > 0) {
				foreach ($users as $user) {
					$accept = true;
					if (is_array($user->roles)) {
						if (is_array($user->roles) && count($user->roles) > 0) {
							$accept = false;
							foreach ($user->roles as $role) {
								if (in_array($role, $roles)) {
									$accept = true;
									break;
								}
							}
						}
					}
					if ($accept) $list[$user->user_login] = $user->display_name;
				}
			}
			$THEMEREX_GLOBALS['list_users'] = $list;
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}


// Return slider engines list, prepended inherit (if need)
if ( !function_exists( 'themerex_get_list_sliders' ) ) {
	function themerex_get_list_sliders($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_sliders']))
			$list = $THEMEREX_GLOBALS['list_sliders'];
		else {
			$list = array();
			if (themerex_exists_revslider())
				$list["revo"] = esc_html__("Layer slider (Revolution)", 'kidsplanet');
			$list["swiper"] = esc_html__("Posts slider (Swiper)", 'kidsplanet');
			$THEMEREX_GLOBALS['list_sliders'] = $list = apply_filters('themerex_filter_list_sliders', $list);
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}


// Return Revo Sliders list, prepended inherit (if need)
if ( !function_exists( 'themerex_get_list_revo_sliders' ) ) {
	function themerex_get_list_revo_sliders($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_revo_sliders']))
			$list = $THEMEREX_GLOBALS['list_revo_sliders'];
		else {
			$list = array();
			if (themerex_exists_revslider()) {
				global $wpdb;
                // Attention! The use of wpdb->prepare() is not required
                // because the query does not use external data substitution
                $rows = $wpdb->get_results( "SELECT alias, title FROM " . esc_sql($wpdb->prefix) . "revslider_sliders" );
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$list[$row->alias] = $row->title;
					}
				}
			}
			$THEMEREX_GLOBALS['list_revo_sliders'] = $list = apply_filters('themerex_filter_list_revo_sliders', $list);
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}


// Return slider controls list, prepended inherit (if need)
if ( !function_exists( 'themerex_get_list_slider_controls' ) ) {
	function themerex_get_list_slider_controls($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_slider_controls']))
			$list = $THEMEREX_GLOBALS['list_slider_controls'];
		else {
			$list = array(
				'no' => esc_html__('None', 'kidsplanet'),
				'side' => esc_html__('Side', 'kidsplanet'),
				'bottom' => esc_html__('Bottom', 'kidsplanet'),
				'pagination' => esc_html__('Pagination', 'kidsplanet')
			);
			$THEMEREX_GLOBALS['list_slider_controls'] = $list = apply_filters('themerex_filter_list_slider_controls', $list);
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}


// Return slider controls classes
if ( !function_exists( 'themerex_get_slider_controls_classes' ) ) {
	function themerex_get_slider_controls_classes($controls) {
		if (themerex_param_is_off($controls))	$classes = 'sc_slider_nopagination sc_slider_nocontrols';
		else if ($controls=='bottom')				$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_bottom';
		else if ($controls=='pagination')			$classes = 'sc_slider_pagination sc_slider_pagination_bottom sc_slider_nocontrols';
		else										$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_side';
		return $classes;
	}
}

// Return list with popup engines
if ( !function_exists( 'themerex_get_list_popup_engines' ) ) {
	function themerex_get_list_popup_engines($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_popup_engines']))
			$list = $THEMEREX_GLOBALS['list_popup_engines'];
		else {
			$list = array();
			$list["pretty"] = esc_html__("Pretty photo", 'kidsplanet');
			$list["magnific"] = esc_html__("Magnific popup", 'kidsplanet');
			$THEMEREX_GLOBALS['list_popup_engines'] = $list = apply_filters('themerex_filter_list_popup_engines', $list);
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}

// Return menus list, prepended inherit
if ( !function_exists( 'themerex_get_list_menus' ) ) {
	function themerex_get_list_menus($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_menus']))
			$list = $THEMEREX_GLOBALS['list_menus'];
		else {
			$list = array();
			$list['default'] = esc_html__("Default", 'kidsplanet');
			$menus = wp_get_nav_menus();
			if (is_array($menus) && count($menus) > 0) {
				foreach ($menus as $menu) {
					$list[$menu->slug] = $menu->name;
				}
			}
			$THEMEREX_GLOBALS['list_menus'] = $list;
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}

// Return custom sidebars list, prepended inherit and main sidebars item (if need)
if ( !function_exists( 'themerex_get_list_sidebars' ) ) {
	function themerex_get_list_sidebars($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_sidebars'])) {
			$list = $THEMEREX_GLOBALS['list_sidebars'];
		} else {
			$list = isset($THEMEREX_GLOBALS['registered_sidebars']) ? $THEMEREX_GLOBALS['registered_sidebars'] : array();
			$THEMEREX_GLOBALS['list_sidebars'] = $list;
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}

// Return sidebars positions
if ( !function_exists( 'themerex_get_list_sidebars_positions' ) ) {
	function themerex_get_list_sidebars_positions($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_sidebars_positions']))
			$list = $THEMEREX_GLOBALS['list_sidebars_positions'];
		else {
			$list = array();
			$list['none']  = esc_html__('Hide',  'kidsplanet');
			$list['left']  = esc_html__('Left',  'kidsplanet');
			$list['right'] = esc_html__('Right', 'kidsplanet');
			$THEMEREX_GLOBALS['list_sidebars_positions'] = $list;
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}

// Return sidebars class
if ( !function_exists( 'themerex_get_sidebar_class' ) ) {
	function themerex_get_sidebar_class() {
		$sb_main = themerex_get_custom_option('show_sidebar_main');
		$sb_outer = themerex_get_custom_option('show_sidebar_outer');
		return (themerex_param_is_off($sb_main) || !is_active_sidebar(themerex_get_custom_option('sidebar_main')) ? 'sidebar_hide' : 'sidebar_show sidebar_'.($sb_main))
				. ' ' . (themerex_param_is_off($sb_outer) ? 'sidebar_outer_hide' : 'sidebar_outer_show sidebar_outer_'.($sb_outer));
	}
}

// Return body styles list, prepended inherit
if ( !function_exists( 'themerex_get_list_body_styles' ) ) {
	function themerex_get_list_body_styles($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_body_styles']))
			$list = $THEMEREX_GLOBALS['list_body_styles'];
		else {
			$list = array();
			$list['boxed']		= esc_html__('Boxed',		'kidsplanet');
			$list['wide']		= esc_html__('Wide',		'kidsplanet');
			$list['fullwide']	= esc_html__('Fullwide',	'kidsplanet');
			$list['fullscreen']	= esc_html__('Fullscreen',	'kidsplanet');
			$THEMEREX_GLOBALS['list_body_styles'] = $list = apply_filters('themerex_filter_list_body_styles', $list);
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}

// Return skins list, prepended inherit
if ( !function_exists( 'themerex_get_list_skins' ) ) {
	function themerex_get_list_skins($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_skins']))
			$list = $THEMEREX_GLOBALS['list_skins'];
		else
			$THEMEREX_GLOBALS['list_skins'] = $list = themerex_get_list_folders("skins");
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}

// Return css-themes list
if ( !function_exists( 'themerex_get_list_themes' ) ) {
	function themerex_get_list_themes($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_themes']))
			$list = $THEMEREX_GLOBALS['list_themes'];
		else
			$THEMEREX_GLOBALS['list_themes'] = $list = themerex_get_list_files("css/themes");
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}

// Return templates list, prepended inherit
if ( !function_exists( 'themerex_get_list_templates' ) ) {
	function themerex_get_list_templates($mode='') {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_templates_'.($mode)]))
			$list = $THEMEREX_GLOBALS['list_templates_'.($mode)];
		else {
			$list = array();
			if (is_array($THEMEREX_GLOBALS['registered_templates']) && count($THEMEREX_GLOBALS['registered_templates']) > 0) {
				foreach ($THEMEREX_GLOBALS['registered_templates'] as $k=>$v) {
					if ($mode=='' || themerex_strpos($v['mode'], $mode)!==false)
						$list[$k] = !empty($v['icon']) 
									? $v['icon'] 
									: (!empty($v['title']) 
										? $v['title'] 
										: themerex_strtoproper($v['layout'])
										);
				}
			}
			$THEMEREX_GLOBALS['list_templates_'.($mode)] = $list;
		}
		return $list;
	}
}

// Return blog styles list, prepended inherit
if ( !function_exists( 'themerex_get_list_templates_blog' ) ) {
	function themerex_get_list_templates_blog($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_templates_blog']))
			$list = $THEMEREX_GLOBALS['list_templates_blog'];
		else {
			$list = themerex_get_list_templates('blog');
			$THEMEREX_GLOBALS['list_templates_blog'] = $list;
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}

// Return blogger styles list, prepended inherit
if ( !function_exists( 'themerex_get_list_templates_blogger' ) ) {
	function themerex_get_list_templates_blogger($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_templates_blogger']))
			$list = $THEMEREX_GLOBALS['list_templates_blogger'];
		else {
			$list = themerex_array_merge(themerex_get_list_templates('blogger'), themerex_get_list_templates('blog'));
			$THEMEREX_GLOBALS['list_templates_blogger'] = $list;
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}

// Return single page styles list, prepended inherit
if ( !function_exists( 'themerex_get_list_templates_single' ) ) {
	function themerex_get_list_templates_single($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_templates_single']))
			$list = $THEMEREX_GLOBALS['list_templates_single'];
		else {
			$list = themerex_get_list_templates('single');
			$THEMEREX_GLOBALS['list_templates_single'] = $list;
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}

// Return header styles list, prepended inherit
if ( !function_exists( 'themerex_get_list_templates_header' ) ) {
	function themerex_get_list_templates_header($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_templates_header']))
			$list = $THEMEREX_GLOBALS['list_templates_header'];
		else {
			$list = themerex_get_list_templates('header');
			$THEMEREX_GLOBALS['list_templates_header'] = $list;
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}

// Return article styles list, prepended inherit
if ( !function_exists( 'themerex_get_list_article_styles' ) ) {
	function themerex_get_list_article_styles($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_article_styles']))
			$list = $THEMEREX_GLOBALS['list_article_styles'];
		else {
			$list = array();
			$list["boxed"]   = esc_html__('Boxed', 'kidsplanet');
			$list["stretch"] = esc_html__('Stretch', 'kidsplanet');
			$THEMEREX_GLOBALS['list_article_styles'] = $list;
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}

// Return button styles list, prepended inherit
if ( !function_exists( 'themerex_get_list_button_styles' ) ) {
	function themerex_get_list_button_styles($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_button_styles']))
			$list = $THEMEREX_GLOBALS['list_button_styles'];
		else {
			$list = array();
			$list["custom"]	= esc_html__('Custom', 'kidsplanet');
			$list["link"] 	= esc_html__('As links', 'kidsplanet');
			$list["menu"] 	= esc_html__('As main menu', 'kidsplanet');
			$list["user"] 	= esc_html__('As user menu', 'kidsplanet');
			$THEMEREX_GLOBALS['list_button_styles'] = $list;
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}

// Return post-formats filters list, prepended inherit
if ( !function_exists( 'themerex_get_list_post_formats_filters' ) ) {
	function themerex_get_list_post_formats_filters($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_post_formats_filters']))
			$list = $THEMEREX_GLOBALS['list_post_formats_filters'];
		else {
			$list = array();
			$list["no"]      = esc_html__('All posts', 'kidsplanet');
			$list["thumbs"]  = esc_html__('With thumbs', 'kidsplanet');
			$list["reviews"] = esc_html__('With reviews', 'kidsplanet');
			$list["video"]   = esc_html__('With videos', 'kidsplanet');
			$list["audio"]   = esc_html__('With audios', 'kidsplanet');
			$list["gallery"] = esc_html__('With galleries', 'kidsplanet');
			$THEMEREX_GLOBALS['list_post_formats_filters'] = $list;
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}

// Return portfolio filters list, prepended inherit
if ( !function_exists( 'themerex_get_list_portfolio_filters' ) ) {
	function themerex_get_list_portfolio_filters($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_portfolio_filters']))
			$list = $THEMEREX_GLOBALS['list_portfolio_filters'];
		else {
			$list = array();
			$list["hide"] = esc_html__('Hide', 'kidsplanet');
			$list["tags"] = esc_html__('Tags', 'kidsplanet');
			$list["categories"] = esc_html__('Categories', 'kidsplanet');
			$THEMEREX_GLOBALS['list_portfolio_filters'] = $list;
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}

// Return hover styles list, prepended inherit
if ( !function_exists( 'themerex_get_list_hovers' ) ) {
	function themerex_get_list_hovers($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_hovers']))
			$list = $THEMEREX_GLOBALS['list_hovers'];
		else {
			$list = array();
			$list['circle effect1']  = esc_html__('Circle Effect 1',  'kidsplanet');
			$list['circle effect2']  = esc_html__('Circle Effect 2',  'kidsplanet');
			$list['circle effect3']  = esc_html__('Circle Effect 3',  'kidsplanet');
			$list['circle effect4']  = esc_html__('Circle Effect 4',  'kidsplanet');
			$list['circle effect5']  = esc_html__('Circle Effect 5',  'kidsplanet');
			$list['circle effect6']  = esc_html__('Circle Effect 6',  'kidsplanet');
			$list['circle effect7']  = esc_html__('Circle Effect 7',  'kidsplanet');
			$list['circle effect8']  = esc_html__('Circle Effect 8',  'kidsplanet');
			$list['circle effect9']  = esc_html__('Circle Effect 9',  'kidsplanet');
			$list['circle effect10'] = esc_html__('Circle Effect 10',  'kidsplanet');
			$list['circle effect11'] = esc_html__('Circle Effect 11',  'kidsplanet');
			$list['circle effect12'] = esc_html__('Circle Effect 12',  'kidsplanet');
			$list['circle effect13'] = esc_html__('Circle Effect 13',  'kidsplanet');
			$list['circle effect14'] = esc_html__('Circle Effect 14',  'kidsplanet');
			$list['circle effect15'] = esc_html__('Circle Effect 15',  'kidsplanet');
			$list['circle effect16'] = esc_html__('Circle Effect 16',  'kidsplanet');
			$list['circle effect17'] = esc_html__('Circle Effect 17',  'kidsplanet');
			$list['circle effect18'] = esc_html__('Circle Effect 18',  'kidsplanet');
			$list['circle effect19'] = esc_html__('Circle Effect 19',  'kidsplanet');
			$list['circle effect20'] = esc_html__('Circle Effect 20',  'kidsplanet');
			$list['square effect1']  = esc_html__('Square Effect 1',  'kidsplanet');
			$list['square effect2']  = esc_html__('Square Effect 2',  'kidsplanet');
			$list['square effect3']  = esc_html__('Square Effect 3',  'kidsplanet');
			$list['square effect5']  = esc_html__('Square Effect 5',  'kidsplanet');
			$list['square effect6']  = esc_html__('Square Effect 6',  'kidsplanet');
			$list['square effect7']  = esc_html__('Square Effect 7',  'kidsplanet');
			$list['square effect8']  = esc_html__('Square Effect 8',  'kidsplanet');
			$list['square effect9']  = esc_html__('Square Effect 9',  'kidsplanet');
			$list['square effect10'] = esc_html__('Square Effect 10',  'kidsplanet');
			$list['square effect11'] = esc_html__('Square Effect 11',  'kidsplanet');
			$list['square effect12'] = esc_html__('Square Effect 12',  'kidsplanet');
			$list['square effect13'] = esc_html__('Square Effect 13',  'kidsplanet');
			$list['square effect14'] = esc_html__('Square Effect 14',  'kidsplanet');
			$list['square effect15'] = esc_html__('Square Effect 15',  'kidsplanet');
			$list['square effect_dir']   = esc_html__('Square Effect Dir',   'kidsplanet');
			$list['square effect_shift'] = esc_html__('Square Effect Shift', 'kidsplanet');
			$list['square effect_book']  = esc_html__('Square Effect Book',  'kidsplanet');
			$list['square effect_more']  = esc_html__('Square Effect More',  'kidsplanet');
			$list['square effect_fade']  = esc_html__('Square Effect Fade',  'kidsplanet');
			$THEMEREX_GLOBALS['list_hovers'] = $list = apply_filters('themerex_filter_portfolio_hovers', $list);
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}

// Return extended hover directions list, prepended inherit
if ( !function_exists( 'themerex_get_list_hovers_directions' ) ) {
	function themerex_get_list_hovers_directions($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_hovers_directions']))
			$list = $THEMEREX_GLOBALS['list_hovers_directions'];
		else {
			$list = array();
			$list['left_to_right'] = esc_html__('Left to Right',  'kidsplanet');
			$list['right_to_left'] = esc_html__('Right to Left',  'kidsplanet');
			$list['top_to_bottom'] = esc_html__('Top to Bottom',  'kidsplanet');
			$list['bottom_to_top'] = esc_html__('Bottom to Top',  'kidsplanet');
			$list['scale_up']      = esc_html__('Scale Up',  'kidsplanet');
			$list['scale_down']    = esc_html__('Scale Down',  'kidsplanet');
			$list['scale_down_up'] = esc_html__('Scale Down-Up',  'kidsplanet');
			$list['from_left_and_right'] = esc_html__('From Left and Right',  'kidsplanet');
			$list['from_top_and_bottom'] = esc_html__('From Top and Bottom',  'kidsplanet');
			$THEMEREX_GLOBALS['list_hovers_directions'] = $list = apply_filters('themerex_filter_portfolio_hovers_directions', $list);
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}


// Return list of the label positions in the custom forms
if ( !function_exists( 'themerex_get_list_label_positions' ) ) {
	function themerex_get_list_label_positions($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_label_positions']))
			$list = $THEMEREX_GLOBALS['list_label_positions'];
		else {
			$list = array();
			$list['top']	= esc_html__('Top',		'kidsplanet');
			$list['bottom']	= esc_html__('Bottom',		'kidsplanet');
			$list['left']	= esc_html__('Left',		'kidsplanet');
			$list['over']	= esc_html__('Over',		'kidsplanet');
			$THEMEREX_GLOBALS['list_label_positions'] = $list = apply_filters('themerex_filter_label_positions', $list);
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}


// Return list of the bg image positions
if ( !function_exists( 'themerex_get_list_bg_image_positions' ) ) {
	function themerex_get_list_bg_image_positions($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_bg_image_positions']))
			$list = $THEMEREX_GLOBALS['list_bg_image_positions'];
		else {
			$list = array();
			$list['left top']	  = esc_html__('Left Top', 'kidsplanet');
			$list['center top']   = esc_html__("Center Top", 'kidsplanet');
			$list['right top']    = esc_html__("Right Top", 'kidsplanet');
			$list['left center']  = esc_html__("Left Center", 'kidsplanet');
			$list['center center']= esc_html__("Center Center", 'kidsplanet');
			$list['right center'] = esc_html__("Right Center", 'kidsplanet');
			$list['left bottom']  = esc_html__("Left Bottom", 'kidsplanet');
			$list['center bottom']= esc_html__("Center Bottom", 'kidsplanet');
			$list['right bottom'] = esc_html__("Right Bottom", 'kidsplanet');
			$THEMEREX_GLOBALS['list_bg_image_positions'] = $list;
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}


// Return list of the bg image repeat
if ( !function_exists( 'themerex_get_list_bg_image_repeats' ) ) {
	function themerex_get_list_bg_image_repeats($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_bg_image_repeats']))
			$list = $THEMEREX_GLOBALS['list_bg_image_repeats'];
		else {
			$list = array();
			$list['repeat']	  = esc_html__('Repeat', 'kidsplanet');
			$list['repeat-x'] = esc_html__('Repeat X', 'kidsplanet');
			$list['repeat-y'] = esc_html__('Repeat Y', 'kidsplanet');
			$list['no-repeat']= esc_html__('No Repeat', 'kidsplanet');
			$THEMEREX_GLOBALS['list_bg_image_repeats'] = $list;
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}


// Return list of the bg image attachment
if ( !function_exists( 'themerex_get_list_bg_image_attachments' ) ) {
	function themerex_get_list_bg_image_attachments($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_bg_image_attachments']))
			$list = $THEMEREX_GLOBALS['list_bg_image_attachments'];
		else {
			$list = array();
			$list['scroll']	= esc_html__('Scroll', 'kidsplanet');
			$list['fixed']	= esc_html__('Fixed', 'kidsplanet');
			$list['local']	= esc_html__('Local', 'kidsplanet');
			$THEMEREX_GLOBALS['list_bg_image_attachments'] = $list;
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}


// Return list of the bg tints
if ( !function_exists( 'themerex_get_list_bg_tints' ) ) {
	function themerex_get_list_bg_tints($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_bg_tints']))
			$list = $THEMEREX_GLOBALS['list_bg_tints'];
		else {
			$list = array();
			$list['white']	= esc_html__('White', 'kidsplanet');
			$list['light']	= esc_html__('Light', 'kidsplanet');
			$list['dark']	= esc_html__('Dark', 'kidsplanet');
			$THEMEREX_GLOBALS['list_bg_tints'] = $list = apply_filters('themerex_filter_bg_tints', $list);
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}

// Return custom fields types list, prepended inherit
if ( !function_exists( 'themerex_get_list_field_types' ) ) {
	function themerex_get_list_field_types($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_field_types']))
			$list = $THEMEREX_GLOBALS['list_field_types'];
		else {
			$list = array();
			$list['text']     = esc_html__('Text',  'kidsplanet');
			$list['textarea'] = esc_html__('Text Area', 'kidsplanet');
			$list['password'] = esc_html__('Password',  'kidsplanet');
			$list['radio']    = esc_html__('Radio',  'kidsplanet');
			$list['checkbox'] = esc_html__('Checkbox',  'kidsplanet');
			$list['select']   = esc_html__('Select',  'kidsplanet');
			$list['button']   = esc_html__('Button', 'kidsplanet');
			$THEMEREX_GLOBALS['list_field_types'] = $list = apply_filters('themerex_filter_field_types', $list);
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}

// Return Google map styles
if ( !function_exists( 'themerex_get_list_googlemap_styles' ) ) {
	function themerex_get_list_googlemap_styles($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_googlemap_styles']))
			$list = $THEMEREX_GLOBALS['list_googlemap_styles'];
		else {
			$list = array();
			$list['default'] = esc_html__('Default', 'kidsplanet');
			$list['simple'] = esc_html__('Simple', 'kidsplanet');
			$list['greyscale'] = esc_html__('Greyscale', 'kidsplanet');
			$list['greyscale2'] = esc_html__('Greyscale 2', 'kidsplanet');
			$list['invert'] = esc_html__('Invert', 'kidsplanet');
			$list['dark'] = esc_html__('Dark', 'kidsplanet');
			$list['style1'] = esc_html__('Custom style 1', 'kidsplanet');
			$list['style2'] = esc_html__('Custom style 2', 'kidsplanet');
			$list['style3'] = esc_html__('Custom style 3', 'kidsplanet');
			$THEMEREX_GLOBALS['list_googlemap_styles'] = $list = apply_filters('themerex_filter_googlemap_styles', $list);
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}

// Return iconed classes list
if ( !function_exists( 'themerex_get_list_icons' ) ) {
	function themerex_get_list_icons($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_icons']))
			$list = $THEMEREX_GLOBALS['list_icons'];
		else
			$THEMEREX_GLOBALS['list_icons'] = $list = themerex_parse_icons_classes(themerex_get_file_dir("css/fontello/css/fontello-codes.css"));
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}

// Return socials list
if ( !function_exists( 'themerex_get_list_socials' ) ) {
	function themerex_get_list_socials($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_socials']))
			$list = $THEMEREX_GLOBALS['list_socials'];
		else
			$THEMEREX_GLOBALS['list_socials'] = $list = themerex_get_list_files("images/socials", "png");
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}

// Return flags list
if ( !function_exists( 'themerex_get_list_flags' ) ) {
	function themerex_get_list_flags($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_flags']))
			$list = $THEMEREX_GLOBALS['list_flags'];
		else
			$THEMEREX_GLOBALS['list_flags'] = $list = themerex_get_list_files("images/flags", "png");
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}

// Return list with 'Yes' and 'No' items
if ( !function_exists( 'themerex_get_list_yesno' ) ) {
	function themerex_get_list_yesno($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_yesno']))
			$list = $THEMEREX_GLOBALS['list_yesno'];
		else {
			$list = array();
			$list["yes"] = esc_html__("Yes", 'kidsplanet');
			$list["no"]  = esc_html__("No", 'kidsplanet');
			$THEMEREX_GLOBALS['list_yesno'] = $list;
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}

// Return list with 'On' and 'Of' items
if ( !function_exists( 'themerex_get_list_onoff' ) ) {
	function themerex_get_list_onoff($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_onoff']))
			$list = $THEMEREX_GLOBALS['list_onoff'];
		else {
			$list = array();
			$list["on"] = esc_html__("On", 'kidsplanet');
			$list["off"] = esc_html__("Off", 'kidsplanet');
			$THEMEREX_GLOBALS['list_onoff'] = $list;
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}

// Return list with 'Show' and 'Hide' items
if ( !function_exists( 'themerex_get_list_showhide' ) ) {
	function themerex_get_list_showhide($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_showhide']))
			$list = $THEMEREX_GLOBALS['list_showhide'];
		else {
			$list = array();
			$list["show"] = esc_html__("Show", 'kidsplanet');
			$list["hide"] = esc_html__("Hide", 'kidsplanet');
			$THEMEREX_GLOBALS['list_showhide'] = $list;
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}

// Return list with 'Ascending' and 'Descending' items
if ( !function_exists( 'themerex_get_list_orderings' ) ) {
	function themerex_get_list_orderings($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_orderings']))
			$list = $THEMEREX_GLOBALS['list_orderings'];
		else {
			$list = array();
			$list["asc"] = esc_html__("Ascending", 'kidsplanet');
			$list["desc"] = esc_html__("Descending", 'kidsplanet');
			$THEMEREX_GLOBALS['list_orderings'] = $list;
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}

// Return list with 'Horizontal' and 'Vertical' items
if ( !function_exists( 'themerex_get_list_directions' ) ) {
	function themerex_get_list_directions($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_directions']))
			$list = $THEMEREX_GLOBALS['list_directions'];
		else {
			$list = array();
			$list["horizontal"] = esc_html__("Horizontal", 'kidsplanet');
			$list["vertical"] = esc_html__("Vertical", 'kidsplanet');
			$THEMEREX_GLOBALS['list_directions'] = $list;
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}

// Return list with item's shapes
if ( !function_exists( 'themerex_get_list_shapes' ) ) {
	function themerex_get_list_shapes($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_shapes']))
			$list = $THEMEREX_GLOBALS['list_shapes'];
		else {
			$list = array();
			$list["round"]  = esc_html__("Round", 'kidsplanet');
			$list["square"] = esc_html__("Square", 'kidsplanet');
			$THEMEREX_GLOBALS['list_shapes'] = $list;
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}

// Return list with item's sizes
if ( !function_exists( 'themerex_get_list_sizes' ) ) {
	function themerex_get_list_sizes($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_sizes']))
			$list = $THEMEREX_GLOBALS['list_sizes'];
		else {
			$list = array();
            $list["small"]  = esc_html__("Small", 'kidsplanet');
            $list["tiny"]   = esc_html__("Tiny", 'kidsplanet');
			$list["medium"] = esc_html__("Medium", 'kidsplanet');
			$list["large"]  = esc_html__("Large", 'kidsplanet');
			$THEMEREX_GLOBALS['list_sizes'] = $list;
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}


// Return list with float items
if ( !function_exists( 'themerex_get_list_floats' ) ) {
	function themerex_get_list_floats($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_floats']))
			$list = $THEMEREX_GLOBALS['list_floats'];
		else {
			$list = array();
			$list["none"] = esc_html__("None", 'kidsplanet');
			$list["left"] = esc_html__("Float Left", 'kidsplanet');
			$list["right"] = esc_html__("Float Right", 'kidsplanet');
			$THEMEREX_GLOBALS['list_floats'] = $list;
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}

// Return list with alignment items
if ( !function_exists( 'themerex_get_list_alignments' ) ) {
	function themerex_get_list_alignments($justify=false, $prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_alignments']))
			$list = $THEMEREX_GLOBALS['list_alignments'];
		else {
			$list = array();
			$list["none"] = esc_html__("None", 'kidsplanet');
			$list["left"] = esc_html__("Left", 'kidsplanet');
			$list["center"] = esc_html__("Center", 'kidsplanet');
			$list["right"] = esc_html__("Right", 'kidsplanet');
			if ($justify) $list["justify"] = esc_html__("Justify", 'kidsplanet');
			$THEMEREX_GLOBALS['list_alignments'] = $list;
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}

// Return sorting list items
if ( !function_exists( 'themerex_get_list_sortings' ) ) {
	function themerex_get_list_sortings($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_sortings']))
			$list = $THEMEREX_GLOBALS['list_sortings'];
		else {
			$list = array();
			$list["date"] = esc_html__("Date", 'kidsplanet');
			$list["title"] = esc_html__("Alphabetically", 'kidsplanet');
			$list["views"] = esc_html__("Popular (views count)", 'kidsplanet');
			$list["comments"] = esc_html__("Most commented (comments count)", 'kidsplanet');
			$list["author_rating"] = esc_html__("Author rating", 'kidsplanet');
			$list["users_rating"] = esc_html__("Visitors (users) rating", 'kidsplanet');
			$list["random"] = esc_html__("Random", 'kidsplanet');
			$THEMEREX_GLOBALS['list_sortings'] = $list = apply_filters('themerex_filter_list_sortings', $list);
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}

// Return list with columns widths
if ( !function_exists( 'themerex_get_list_columns' ) ) {
	function themerex_get_list_columns($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_columns']))
			$list = $THEMEREX_GLOBALS['list_columns'];
		else {
			$list = array();
			$list["none"] = esc_html__("None", 'kidsplanet');
			$list["1_1"] = esc_html__("100%", 'kidsplanet');
			$list["1_2"] = esc_html__("1/2", 'kidsplanet');
			$list["1_3"] = esc_html__("1/3", 'kidsplanet');
			$list["2_3"] = esc_html__("2/3", 'kidsplanet');
			$list["1_4"] = esc_html__("1/4", 'kidsplanet');
			$list["3_4"] = esc_html__("3/4", 'kidsplanet');
			$list["1_5"] = esc_html__("1/5", 'kidsplanet');
			$list["2_5"] = esc_html__("2/5", 'kidsplanet');
			$list["3_5"] = esc_html__("3/5", 'kidsplanet');
			$list["4_5"] = esc_html__("4/5", 'kidsplanet');
			$list["1_6"] = esc_html__("1/6", 'kidsplanet');
			$list["5_6"] = esc_html__("5/6", 'kidsplanet');
			$list["1_7"] = esc_html__("1/7", 'kidsplanet');
			$list["2_7"] = esc_html__("2/7", 'kidsplanet');
			$list["3_7"] = esc_html__("3/7", 'kidsplanet');
			$list["4_7"] = esc_html__("4/7", 'kidsplanet');
			$list["5_7"] = esc_html__("5/7", 'kidsplanet');
			$list["6_7"] = esc_html__("6/7", 'kidsplanet');
			$list["1_8"] = esc_html__("1/8", 'kidsplanet');
			$list["3_8"] = esc_html__("3/8", 'kidsplanet');
			$list["5_8"] = esc_html__("5/8", 'kidsplanet');
			$list["7_8"] = esc_html__("7/8", 'kidsplanet');
			$list["1_9"] = esc_html__("1/9", 'kidsplanet');
			$list["2_9"] = esc_html__("2/9", 'kidsplanet');
			$list["4_9"] = esc_html__("4/9", 'kidsplanet');
			$list["5_9"] = esc_html__("5/9", 'kidsplanet');
			$list["7_9"] = esc_html__("7/9", 'kidsplanet');
			$list["8_9"] = esc_html__("8/9", 'kidsplanet');
			$list["1_10"]= esc_html__("1/10", 'kidsplanet');
			$list["3_10"]= esc_html__("3/10", 'kidsplanet');
			$list["7_10"]= esc_html__("7/10", 'kidsplanet');
			$list["9_10"]= esc_html__("9/10", 'kidsplanet');
			$list["1_11"]= esc_html__("1/11", 'kidsplanet');
			$list["2_11"]= esc_html__("2/11", 'kidsplanet');
			$list["3_11"]= esc_html__("3/11", 'kidsplanet');
			$list["4_11"]= esc_html__("4/11", 'kidsplanet');
			$list["5_11"]= esc_html__("5/11", 'kidsplanet');
			$list["6_11"]= esc_html__("6/11", 'kidsplanet');
			$list["7_11"]= esc_html__("7/11", 'kidsplanet');
			$list["8_11"]= esc_html__("8/11", 'kidsplanet');
			$list["9_11"]= esc_html__("9/11", 'kidsplanet');
			$list["10_11"]= esc_html__("10/11", 'kidsplanet');
			$list["1_12"]= esc_html__("1/12", 'kidsplanet');
			$list["5_12"]= esc_html__("5/12", 'kidsplanet');
			$list["7_12"]= esc_html__("7/12", 'kidsplanet');
			$list["10_12"]= esc_html__("10/12", 'kidsplanet');
			$list["11_12"]= esc_html__("11/12", 'kidsplanet');
			$THEMEREX_GLOBALS['list_columns'] = $list = apply_filters('themerex_filter_list_columns', $list);
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}

// Return list of locations for the dedicated content
if ( !function_exists( 'themerex_get_list_dedicated_locations' ) ) {
	function themerex_get_list_dedicated_locations($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_dedicated_locations']))
			$list = $THEMEREX_GLOBALS['list_dedicated_locations'];
		else {
			$list = array();
			$list["default"] = esc_html__('As in the post defined', 'kidsplanet');
			$list["center"]  = esc_html__('Above the text of the post', 'kidsplanet');
			$list["left"]    = esc_html__('To the left the text of the post', 'kidsplanet');
			$list["right"]   = esc_html__('To the right the text of the post', 'kidsplanet');
			$list["alter"]   = esc_html__('Alternates for each post', 'kidsplanet');
			$THEMEREX_GLOBALS['list_dedicated_locations'] = $list = apply_filters('themerex_filter_list_dedicated_locations', $list);
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}

// Return post-format name
if ( !function_exists( 'themerex_get_post_format_name' ) ) {
	function themerex_get_post_format_name($format, $single=true) {
		$name = '';
		if ($format=='gallery')		$name = $single ? esc_html__('gallery', 'kidsplanet') : esc_html__('galleries', 'kidsplanet');
		else if ($format=='video')	$name = $single ? esc_html__('video', 'kidsplanet') : esc_html__('videos', 'kidsplanet');
		else if ($format=='audio')	$name = $single ? esc_html__('audio', 'kidsplanet') : esc_html__('audios', 'kidsplanet');
		else if ($format=='image')	$name = $single ? esc_html__('image', 'kidsplanet') : esc_html__('images', 'kidsplanet');
		else if ($format=='quote')	$name = $single ? esc_html__('quote', 'kidsplanet') : esc_html__('quotes', 'kidsplanet');
		else if ($format=='link')	$name = $single ? esc_html__('link', 'kidsplanet') : esc_html__('links', 'kidsplanet');
		else if ($format=='status')	$name = $single ? esc_html__('status', 'kidsplanet') : esc_html__('statuses', 'kidsplanet');
		else if ($format=='aside')	$name = $single ? esc_html__('aside', 'kidsplanet') : esc_html__('asides', 'kidsplanet');
		else if ($format=='chat')	$name = $single ? esc_html__('chat', 'kidsplanet') : esc_html__('chats', 'kidsplanet');
		else						$name = $single ? esc_html__('standard', 'kidsplanet') : esc_html__('standards', 'kidsplanet');
		return apply_filters('themerex_filter_list_post_format_name', $name, $format);
	}
}

// Return post-format icon name (from Fontello library)
if ( !function_exists( 'themerex_get_post_format_icon' ) ) {
	function themerex_get_post_format_icon($format) {
		$icon = 'icon-';
		if ($format=='gallery')		$icon .= 'pictures';
		else if ($format=='video')	$icon .= 'video';
		else if ($format=='audio')	$icon .= 'note';
		else if ($format=='image')	$icon .= 'picture';
		else if ($format=='quote')	$icon .= 'quote';
		else if ($format=='link')	$icon .= 'link';
		else if ($format=='status')	$icon .= 'comment';
		else if ($format=='aside')	$icon .= 'doc-text';
		else if ($format=='chat')	$icon .= 'chat';
		else						$icon .= 'book-open';
		return apply_filters('themerex_filter_list_post_format_icon', $icon, $format);
	}
}

// Return fonts styles list, prepended inherit
if ( !function_exists( 'themerex_get_list_fonts_styles' ) ) {
	function themerex_get_list_fonts_styles($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_fonts_styles']))
			$list = $THEMEREX_GLOBALS['list_fonts_styles'];
		else {
			$list = array();
			$list['i'] = esc_html__('I', 'kidsplanet');
			$list['u'] = esc_html__('U', 'kidsplanet');
			$THEMEREX_GLOBALS['list_fonts_styles'] = $list;
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}

// Return Google fonts list
if ( !function_exists( 'themerex_get_list_fonts' ) ) {
	function themerex_get_list_fonts($prepend_inherit=false) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['list_fonts']))
			$list = $THEMEREX_GLOBALS['list_fonts'];
		else {
			$list = array();
			$list = themerex_array_merge($list, themerex_get_list_font_faces());
			// Google and custom fonts list:
			$list['Advent Pro'] = array('family'=>'sans-serif');
			$list['Alegreya Sans'] = array('family'=>'sans-serif');
			$list['Arimo'] = array('family'=>'sans-serif');
			$list['Asap'] = array('family'=>'sans-serif');
			$list['Averia Sans Libre'] = array('family'=>'cursive');
			$list['Averia Serif Libre'] = array('family'=>'cursive');
			$list['Bree Serif'] = array('family'=>'serif',);
			$list['Cabin'] = array('family'=>'sans-serif');
			$list['Cabin Condensed'] = array('family'=>'sans-serif');
			$list['Caudex'] = array('family'=>'serif');
			$list['Comfortaa'] = array('family'=>'cursive');
			$list['Cousine'] = array('family'=>'sans-serif');
			$list['Crimson Text'] = array('family'=>'serif');
			$list['Cuprum'] = array('family'=>'sans-serif');
			$list['Dosis'] = array('family'=>'sans-serif');
			$list['Economica'] = array('family'=>'sans-serif');
			$list['Exo'] = array('family'=>'sans-serif');
			$list['Expletus Sans'] = array('family'=>'cursive');
			$list['Karla'] = array('family'=>'sans-serif');
			$list['Lato'] = array('family'=>'sans-serif');
			$list['Lekton'] = array('family'=>'sans-serif');
			$list['Lobster Two'] = array('family'=>'cursive');
			$list['Maven Pro'] = array('family'=>'sans-serif');
			$list['Merriweather'] = array('family'=>'serif');
			$list['Montserrat'] = array('family'=>'sans-serif');
			$list['Neuton'] = array('family'=>'serif');
			$list['Noticia Text'] = array('family'=>'serif');
			$list['Old Standard TT'] = array('family'=>'serif');
			$list['Open Sans'] = array('family'=>'sans-serif');
			$list['Orbitron'] = array('family'=>'sans-serif');
			$list['Oswald'] = array('family'=>'sans-serif');
			$list['Overlock'] = array('family'=>'cursive');
			$list['Oxygen'] = array('family'=>'sans-serif');
			$list['PT Serif'] = array('family'=>'serif');
			$list['Puritan'] = array('family'=>'sans-serif');
			$list['Raleway'] = array('family'=>'sans-serif');
			$list['Roboto'] = array('family'=>'sans-serif');
			$list['Roboto Slab'] = array('family'=>'sans-serif');
			$list['Roboto Condensed'] = array('family'=>'sans-serif');
			$list['Rosario'] = array('family'=>'sans-serif');
			$list['Share'] = array('family'=>'cursive');
			$list['Signika'] = array('family'=>'sans-serif');
			$list['Signika Negative'] = array('family'=>'sans-serif');
			$list['Source Sans Pro'] = array('family'=>'sans-serif');
			$list['Tinos'] = array('family'=>'serif');
			$list['Ubuntu'] = array('family'=>'sans-serif');
			$list['Vollkorn'] = array('family'=>'serif');
			$THEMEREX_GLOBALS['list_fonts'] = $list = apply_filters('themerex_filter_list_fonts', $list);
		}
		return $prepend_inherit ? themerex_array_merge(array('inherit' => esc_html__("Inherit", 'kidsplanet')), $list) : $list;
	}
}

// Return Custom font-face list
if ( !function_exists( 'themerex_get_list_font_faces' ) ) {
	function themerex_get_list_font_faces($prepend_inherit=false) {
		static $list = false;
		if (is_array($list)) return $list;
		$list = array();
		$dir = themerex_get_folder_dir("css/font-face");
        if ( is_dir($dir) ) {
            $files = glob(sprintf("%s/*", $dir), GLOB_ONLYDIR);
            if ( is_array($files) ) {
                foreach ($files as $file) {
                    $file_name = basename($file);
                    if ( substr($file_name, 0, 1) == '.' || ! is_dir( ($dir) . '/' . ($file_name) ) )
                        continue;
                    $css = file_exists( ($dir) . '/' . ($file_name) . '/' . ($file_name) . '.css' )
                        ? themerex_get_file_url("css/font-face/".($file_name).'/'.($file_name).'.css')
                        : (file_exists( ($dir) . '/' . ($file_name) . '/stylesheet.css' )
                            ? themerex_get_file_url("css/font-face/".($file_name).'/stylesheet.css')
                            : '');
                    if ($css != '')
                        $list[$file_name.' ('.esc_html__('uploaded font', 'kidsplanet').')'] = array('css' => $css);
                }
            }
        }
		return $list;
	}
}
?>