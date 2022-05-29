<?php
/**
 * ThemeREX Framework: Theme specific actions
 *
 * @package	themerex
 * @since	themerex 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'themerex_core_theme_setup' ) ) {
	add_action( 'themerex_action_before_init_theme', 'themerex_core_theme_setup', 11 );
	function themerex_core_theme_setup() {
		
		// Editor custom stylesheet - for user
		add_editor_style(themerex_get_file_url('css/editor-style.css'));	
		
		// Make theme available for translation
		// Translations can be filed in the /languages/ directory
		load_theme_textdomain( 'kidsplanet', themerex_get_folder_dir('languages') );


		/* Front and Admin actions and filters:
		------------------------------------------------------------------------ */

		if ( !is_admin() ) {
			
			/* Front actions and filters:
			------------------------------------------------------------------------ */

			// Get theme calendar (instead standard WP calendar) to support Events
			add_filter( 'get_calendar',						'themerex_get_calendar' );
	
			// Filters wp_title to print a neat <title> tag based on what is being viewed
			if (floatval(get_bloginfo('version')) < "4.1") {
				add_filter('wp_title',						'themerex_wp_title', 10, 2);
			}
			
			// Prepare logo text
			add_filter('themerex_filter_prepare_logo_text',	'themerex_prepare_logo_text', 10, 1);
	
			// Add class "widget_number_#' for each widget
			add_filter('dynamic_sidebar_params', 			'themerex_add_widget_number', 10, 1);

			// Frontend editor: Save post data
			add_action('wp_ajax_frontend_editor_save',		'themerex_callback_frontend_editor_save');

			// Frontend editor: Delete post
			add_action('wp_ajax_frontend_editor_delete', 	'themerex_callback_frontend_editor_delete');
	
			// Enqueue scripts and styles
			add_action('wp_enqueue_scripts', 				'themerex_core_frontend_scripts');
			add_action('wp_footer',		 					'themerex_core_frontend_scripts_inline', 9);
			add_filter('themerex_action_add_scripts_inline','themerex_core_add_scripts_inline');

			// Prepare theme core global variables
			add_action('themerex_action_prepare_globals',	'themerex_core_prepare_globals');

		}

		// Register theme specific nav menus
		themerex_register_theme_menus();

		// Register theme specific sidebars
		themerex_register_theme_sidebars();
	}
}




/* Theme init
------------------------------------------------------------------------ */

// Init theme template
function themerex_core_init_theme() {
	global $THEMEREX_GLOBALS;
	if (!empty($THEMEREX_GLOBALS['theme_inited'])) return;
	$THEMEREX_GLOBALS['theme_inited'] = true;

	// Load custom options from GET and post/page/cat options
	if (isset($_GET['set']) && $_GET['set']==1) {
		foreach ($_GET as $k=>$v) {
			if (themerex_get_theme_option($k, null) !== null) {
				setcookie($k, $v, 0, '/');
				$_COOKIE[$k] = $v;
			}
		}
	}

	// Get custom options from current category / page / post / shop / event
	themerex_load_custom_options();

	// Load skin
	$skin = themerex_esc(themerex_get_custom_option('theme_skin'));
	$THEMEREX_GLOBALS['theme_skin'] = $skin;
	if ( file_exists(themerex_get_file_dir('skins/'.($skin).'/skin.php')) ) {
		require_once( themerex_get_file_dir('skins/'.($skin).'/skin.php') );
	}

	// Fire init theme actions (after skin and custom options are loaded)
	do_action('themerex_action_init_theme');

	// Prepare theme core global variables
	do_action('themerex_action_prepare_globals');

	// Fire after init theme actions
	do_action('themerex_action_after_init_theme');
}


// Prepare theme global variables
if ( !function_exists( 'themerex_core_prepare_globals' ) ) {
	function themerex_core_prepare_globals() {
		if (!is_admin()) {
			// AJAX Queries settings
			global $THEMEREX_GLOBALS;
			$THEMEREX_GLOBALS['ajax_nonce'] = wp_create_nonce('ajax_nonce');
			$THEMEREX_GLOBALS['ajax_url'] = admin_url('admin-ajax.php');
		
			// Logo text and slogan
			$THEMEREX_GLOBALS['logo_text'] = apply_filters('themerex_filter_prepare_logo_text', themerex_get_custom_option('logo_text'));
            if(!$THEMEREX_GLOBALS['logo_text'] || $THEMEREX_GLOBALS['logo_text'] == '') $THEMEREX_GLOBALS['logo_text'] = get_bloginfo('name');
			$slogan = themerex_get_custom_option('logo_slogan');
			if (!$slogan) $slogan = get_bloginfo ( 'description' );
			$THEMEREX_GLOBALS['logo_slogan'] = $slogan;
			
			// Logo image and icons from skin
			$logo_side   = themerex_get_logo_icon('logo_side');
			$logo_fixed  = themerex_get_logo_icon('logo_fixed');
			$logo_footer = themerex_get_logo_icon('logo_footer');
			$THEMEREX_GLOBALS['logo']        = themerex_get_logo_icon('logo');
			$THEMEREX_GLOBALS['logo_icon']   = themerex_get_logo_icon('logo_icon');
			$THEMEREX_GLOBALS['logo_side']   = $logo_side   ? $logo_side   : $THEMEREX_GLOBALS['logo'];
			$THEMEREX_GLOBALS['logo_fixed']  = $logo_fixed  ? $logo_fixed  : $THEMEREX_GLOBALS['logo'];
			$THEMEREX_GLOBALS['logo_footer'] = $logo_footer ? $logo_footer : $THEMEREX_GLOBALS['logo'];
	
			$shop_mode = '';
			if (themerex_get_custom_option('show_mode_buttons')=='yes')
				$shop_mode = themerex_get_value_gpc('themerex_shop_mode');
			if (empty($shop_mode))
				$shop_mode = themerex_get_custom_option('shop_mode', '');
			if (empty($shop_mode) || !is_archive())
				$shop_mode = 'thumbs';
			$THEMEREX_GLOBALS['shop_mode'] = $shop_mode;
		}
	}
}


// Return url for the uploaded logo image or (if not uploaded) - to image from skin folder
if ( !function_exists( 'themerex_get_logo_icon' ) ) {
	function themerex_get_logo_icon($slug) {
		$logo_icon = themerex_get_custom_option($slug);
		return $logo_icon;
	}
}


// Add menu locations
if ( !function_exists( 'themerex_register_theme_menus' ) ) {
	function themerex_register_theme_menus() {
		register_nav_menus(apply_filters('themerex_filter_add_theme_menus', array(
			'menu_main'		=> esc_html__('Main Menu', 'kidsplanet'),
			'menu_user'		=> esc_html__('User Menu', 'kidsplanet'),
			'menu_footer'	=> esc_html__('Footer Menu', 'kidsplanet'),
			'menu_side'		=> esc_html__('Side Menu', 'kidsplanet')
		)));
	}
}


// Register widgetized area
if ( !function_exists( 'themerex_register_theme_sidebars' ) ) {
    add_action('widgets_init', 'themerex_register_theme_sidebars');
	function themerex_register_theme_sidebars($sidebars=array()) {
		global $THEMEREX_GLOBALS;
		if (!is_array($sidebars)) $sidebars = array();
		// Custom sidebars
		$custom = themerex_get_theme_option('custom_sidebars');
		if (is_array($custom) && count($custom) > 0) {
			foreach ($custom as $i => $sb) {
				if (trim(chop($sb))=='') continue;
				$sidebars['sidebar_custom_'.($i)]  = $sb;
			}
		}
		$sidebars = apply_filters( 'themerex_filter_add_theme_sidebars', $sidebars );
        $registered = themerex_get_global('registered_sidebars');
        if (!is_array($registered)) $registered = array();
		if (is_array($sidebars) && count($sidebars) > 0) {
			foreach ($sidebars as $id=>$name) {
                if (isset($registered[$id])) continue;
                $registered[$id] = $name;
				register_sidebar( array(
					'name'          => $name,
					'id'            => $id,
					'before_widget' => '<aside id="%1$s" class="widget %2$s">',
					'after_widget'  => '</aside>',
					'before_title'  => '<h5 class="widget_title">',
					'after_title'   => '</h5>',
				) );
			}
		}
        themerex_set_global('registered_sidebars', $registered);
	}
}





/* Front actions and filters:
------------------------------------------------------------------------ */

//  Enqueue scripts and styles
if ( !function_exists( 'themerex_core_frontend_scripts' ) ) {
	function themerex_core_frontend_scripts() {
		global $THEMEREX_GLOBALS;
		
		// Enqueue styles
		//-----------------------------------------------------------------------------------------------------
		
		// Prepare custom fonts
		$fonts = themerex_get_list_fonts(false);
		$theme_fonts = array();
		$custom_fonts = themerex_get_custom_fonts();
		if (is_array($custom_fonts) && count($custom_fonts) > 0) {
			foreach ($custom_fonts as $s=>$f) {
				if (!empty($f['font-family']) && !themerex_is_inherit_option($f['font-family'])) $theme_fonts[$f['font-family']] = 1;
			}
		}
		// Prepare current skin fonts
		$theme_fonts = apply_filters('themerex_filter_used_fonts', $theme_fonts);
		// Link to selected fonts
		if (is_array($theme_fonts) && count($theme_fonts) > 0) {
			foreach ($theme_fonts as $font=>$v) {
				if (isset($fonts[$font])) {
					$font_name = ($pos=themerex_strpos($font,' ('))!==false ? themerex_substr($font, 0, $pos) : $font;
					$css = !empty($fonts[$font]['css']) 
						? $fonts[$font]['css'] 
						: themerex_get_protocol() . '://fonts.googleapis.com/css?family='
							.(!empty($fonts[$font]['link']) ? $fonts[$font]['link'] : str_replace(' ', '+', $font_name).':100,100italic,300,300italic,400,400italic,700,700italic')
							.(empty($fonts[$font]['link']) || themerex_strpos($fonts[$font]['link'], 'subset=')===false ? '&subset=latin,latin-ext,cyrillic,cyrillic-ext' : '');
					wp_enqueue_style( 'themerex-font-'.str_replace(' ', '-', $font_name), $css, array(), null );
				}
			}
		}
		
		// Fontello styles must be loaded before main stylesheet
		wp_enqueue_style( 'fontello-style',  themerex_get_file_url('css/fontello/css/fontello.css'),  array(), null);

		// Main stylesheet
		wp_enqueue_style( 'themerex-main-style', get_stylesheet_uri(), array(), null );
		
		// Animations
		if (themerex_get_theme_option('css_animation')=='yes')
			wp_enqueue_style( 'themerex-animation-style',	themerex_get_file_url('css/core.animation.css'), array(), null );

		// Theme skin stylesheet
		do_action('themerex_action_add_styles');
		
		// Theme customizer stylesheet and inline styles
		themerex_enqueue_custom_styles();

		// Responsive
		if (themerex_get_theme_option('responsive_layouts') == 'yes') {
			$suffix = themerex_param_is_off(themerex_get_custom_option('show_sidebar_outer')) ? '' : '-outer';
			wp_enqueue_style( 'themerex-responsive-style', themerex_get_file_url('css/responsive'.($suffix).'.css'), array(), null );
			do_action('themerex_action_add_responsive');
			if (themerex_get_custom_option('theme_skin')!='') {
				$css = apply_filters('themerex_filter_add_responsive_inline', '');
				if (!empty($css)) wp_add_inline_style( 'themerex-responsive-style', $css );
			}
		}


		// Enqueue scripts	
		//----------------------------------------------------------------------------------------------------------------------------
		
		// Load separate theme scripts
		wp_enqueue_script( 'superfish', themerex_get_file_url('js/superfish.min.js'), array('jquery'), null, true );
		if (themerex_get_theme_option('menu_slider')=='yes') {
			wp_enqueue_script( 'slidemenu-script', themerex_get_file_url('js/jquery.slidemenu.js'), array('jquery'), null, true );
		}

		wp_enqueue_script( 'themerex-core-utils-script', themerex_get_file_url('js/core.utils.js'), array('jquery'), null, true );
		wp_enqueue_script( 'themerex-core-init-script', themerex_get_file_url('js/core.init.js'), array('jquery'), null, true );

		// Media elements library	
		if (themerex_get_theme_option('use_mediaelement')=='yes') {
			wp_enqueue_style ( 'mediaelement' );
			wp_enqueue_style ( 'wp-mediaelement' );
			wp_enqueue_script( 'mediaelement' );
			wp_enqueue_script( 'wp-mediaelement' );
		} else {
			global $wp_styles, $wp_scripts;
			$wp_scripts->done[]	= 'mediaelement';
			$wp_scripts->done[]	= 'wp-mediaelement';
			$wp_styles->done[]	= 'mediaelement';
			$wp_styles->done[]	= 'wp-mediaelement';
		}
		
		// Video background
		if (themerex_get_custom_option('show_video_bg') == 'yes' && themerex_get_custom_option('video_bg_youtube_code') != '') {
			wp_enqueue_script( 'video-bg-script', themerex_get_file_url('js/jquery.tubular.1.0.js'), array('jquery'), null, true );
		}
			
		// Social share buttons
		if (is_singular() && !themerex_get_global('blog_streampage') && themerex_get_custom_option('show_share')!='hide') {
			wp_enqueue_script( 'social-share-script', themerex_get_file_url('js/social/social-share.js'), array('jquery'), null, true );
		}

		// Comments
		if ( is_singular() && !themerex_get_global('blog_streampage') && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply', false, array(), null, true );
		}

		// Custom panel
		if (themerex_get_theme_option('show_theme_customizer') == 'yes') {
			if (file_exists(themerex_get_file_dir('core/core.customizer/front.customizer.css')))
				wp_enqueue_style(  'themerex-customizer-style',  themerex_get_file_url('core/core.customizer/front.customizer.css'), array(), null );
			if (file_exists(themerex_get_file_dir('core/core.customizer/front.customizer.js')))
				wp_enqueue_script( 'themerex-customizer-script', themerex_get_file_url('core/core.customizer/front.customizer.js'), array(), null, true );
		}
		
		//Debug utils
		if (themerex_get_theme_option('debug_mode')=='yes') {
			wp_enqueue_script( 'themerex-core-debug-script', themerex_get_file_url('js/core.debug.js'), array(), null, true );
		}

		// Theme skin script
		do_action('themerex_action_add_scripts');
	}
}

//  Enqueue Swiper Slider scripts and styles
if ( !function_exists( 'themerex_enqueue_slider' ) ) {
	function themerex_enqueue_slider($engine='all') {
		if ($engine=='all' || $engine=='swiper') {
			wp_enqueue_style( 'swiperslider-style', 				themerex_get_file_url('js/swiper/swiper.css'), array(), null );
			wp_enqueue_script( 'swiperslider-script', 			themerex_get_file_url('js/swiper/swiper.js'), array('jquery'), null, true );
			wp_enqueue_script( 'swiperslider-scrollbar-script',	themerex_get_file_url('js/swiper/swiper.scrollbar.js'), array('jquery'), null, true );
		}
	}
}

//  Enqueue Messages scripts and styles
if ( !function_exists( 'themerex_enqueue_messages' ) ) {
	function themerex_enqueue_messages() {
		wp_enqueue_style( 'themerex-messages-style',		themerex_get_file_url('js/core.messages/core.messages.css'), array(), null );
		wp_enqueue_script( 'themerex-messages-script',	themerex_get_file_url('js/core.messages/core.messages.js'),  array('jquery'), null, true );
	}
}

//  Enqueue Portfolio hover scripts and styles
if ( !function_exists( 'themerex_enqueue_portfolio' ) ) {
	function themerex_enqueue_portfolio($hover='') {
		wp_enqueue_style( 'themerex-portfolio-style',  themerex_get_file_url('css/core.portfolio.css'), array(), null );
		if (themerex_strpos($hover, 'effect_dir')!==false)
			wp_enqueue_script( 'hoverdir', themerex_get_file_url('js/hover/jquery.hoverdir.js'), array(), null, true );
	}
}

//  Enqueue Charts and Diagrams scripts and styles
if ( !function_exists( 'themerex_enqueue_diagram' ) ) {
	function themerex_enqueue_diagram($type='all') {
		if ($type=='all' || $type=='pie') wp_enqueue_script( 'diagram-chart-script',	themerex_get_file_url('js/diagram/chart.min.js'), array(), null, true );
		if ($type=='all' || $type=='arc') wp_enqueue_script( 'diagram-raphael-script',	themerex_get_file_url('js/diagram/diagram.raphael.min.js'), array(), 'no-compose', true );
	}
}

// Enqueue Theme Popup scripts and styles
// Link must have attribute: data-rel="popup" or data-rel="popup[gallery]"
if ( !function_exists( 'themerex_enqueue_popup' ) ) {
	function themerex_enqueue_popup($engine='') {
		if ($engine=='pretty' || (empty($engine) && themerex_get_theme_option('popup_engine')=='pretty')) {
			wp_enqueue_style(  'prettyphoto-style',	themerex_get_file_url('js/prettyphoto/css/prettyPhoto.css'), array(), null );
			wp_enqueue_script( 'prettyphoto-script',	themerex_get_file_url('js/prettyphoto/jquery.prettyPhoto.min.js'), array('jquery'), 'no-compose', true );
		} else if ($engine=='magnific' || (empty($engine) && themerex_get_theme_option('popup_engine')=='magnific')) {
			wp_enqueue_style(  'magnific-style',	themerex_get_file_url('js/magnific/magnific-popup.css'), array(), null );
			wp_enqueue_script( 'magnific-script',themerex_get_file_url('js/magnific/jquery.magnific-popup.min.js'), array('jquery'), '', true );
		} else if ($engine=='internal' || (empty($engine) && themerex_get_theme_option('popup_engine')=='internal')) {
			themerex_enqueue_messages();
		}
	}
}

//  Add inline scripts in the footer hook
if ( !function_exists( 'themerex_core_frontend_scripts_inline' ) ) {
	function themerex_core_frontend_scripts_inline() {
        $vars = themerex_get_global('js_vars');
        if (empty($vars) || !is_array($vars)) $vars = array();
        wp_localize_script('themerex-core-init-script', 'THEMEREX_GLOBALS', apply_filters('themerex_action_add_scripts_inline', $vars));
	}
}

//  Add inline scripts in the footer
if (!function_exists('themerex_core_add_scripts_inline')) {
	function themerex_core_add_scripts_inline($vars = array()) {

        $msg = themerex_get_system_message(true);
        if (!empty($msg['message'])) themerex_enqueue_messages();

        // AJAX parameters
        $vars['ajax_url'] = esc_url(themerex_get_global('ajax_url'));
        $vars['ajax_nonce'] = esc_attr(themerex_get_global('ajax_nonce'));
        $vars['ajax_nonce_editor'] = esc_attr(wp_create_nonce('themerex_editor_nonce'));

        // AJAX posts counter
        $vars['use_ajax_views_counter'] = is_singular() && themerex_get_theme_option('use_ajax_views_counter')=='yes';
        if (is_singular() && themerex_get_theme_option('use_ajax_views_counter')=='yes') {
            $vars['post_id'] = (int) get_the_ID();
            $vars['views'] = (int) apply_filters('trx_utils_filter_get_post_views', 0, get_the_ID()) + 1;
        }

        // Site base url
        $vars['site_url'] = esc_url(get_site_url());

        // VC frontend edit mode
        $vars['vc_edit_mode'] = function_exists('themerex_vc_is_frontend') && themerex_vc_is_frontend();

        // Theme base font
        $vars['theme_font'] = themerex_get_custom_font_settings('p', 'font-family');

        // Theme skin
        $vars['theme_skin'] = esc_attr(themerex_get_custom_option('theme_skin'));
        $vars['theme_skin_color'] = themerex_get_scheme_color('text_dark');
        $vars['theme_skin_bg_color'] = themerex_get_scheme_color('bg_color');

        // Slider height
        $vars['slider_height'] = max(100, themerex_get_custom_option('slider_height'));

        // System message
        $vars['system_message']    = $msg;

        // User logged in
        $vars['user_logged_in']    = is_user_logged_in();

        // Show table of content for the current page
        $vars['toc_menu'] = themerex_get_custom_option('menu_toc');
        $vars['toc_menu_home'] = themerex_get_custom_option('menu_toc')!='hide' && themerex_get_custom_option('menu_toc_home')=='yes';
        $vars['toc_menu_top'] = themerex_get_custom_option('menu_toc')!='hide' && themerex_get_custom_option('menu_toc_top')=='yes';

        // Fix main menu
        $vars['menu_fixed'] = themerex_get_theme_option('menu_attachment')=='fixed';

        // Use responsive version for main menu
        $vars['menu_relayout'] = max(0, (int) themerex_get_theme_option('menu_relayout'));
        $vars['menu_responsive'] = themerex_get_theme_option('responsive_layouts') == 'yes' ? max(0, (int) themerex_get_theme_option('menu_responsive')) : 0;
        $vars['menu_slider'] = themerex_get_theme_option('menu_slider')=='yes';

        // Right panel demo timer
        $vars['demo_time'] = themerex_get_theme_option('show_theme_customizer')=='yes' ? max(0, (int) themerex_get_theme_option('customizer_demo')) : 0;

        // Video and Audio tag wrapper
        $vars['media_elements_enabled'] = themerex_get_theme_option('use_mediaelement')=='yes';

        // Use AJAX search
        $vars['ajax_search_enabled'] = themerex_get_theme_option('use_ajax_search')=='yes';
        $vars['ajax_search_min_length']    = min(3, themerex_get_theme_option('ajax_search_min_length'));
        $vars['ajax_search_delay'] = min(200, max(1000, themerex_get_theme_option('ajax_search_delay')));

        // Use CSS animation
        $vars['css_animation'] = themerex_get_theme_option('css_animation')=='yes';
        $vars['menu_animation_in'] = themerex_get_theme_option('menu_animation_in');
        $vars['menu_animation_out'] = themerex_get_theme_option('menu_animation_out');

        // Popup windows engine
        $vars['popup_engine'] = themerex_get_theme_option('popup_engine');

        // E-mail mask
        $vars['email_mask'] = '^([a-zA-Z0-9_\\-]+\\.)*[a-zA-Z0-9_\\-]+@[a-z0-9_\\-]+(\\.[a-z0-9_\\-]+)*\\.[a-z]{2,6}$';

        // Messages max length
        $vars['contacts_maxlength']    = intval(themerex_get_theme_option('message_maxlength_contacts'));
        $vars['comments_maxlength']    = intval(themerex_get_theme_option('message_maxlength_comments'));

        // Remember visitors settings
        $vars['remember_visitors_settings']    = themerex_get_theme_option('remember_visitors_settings')=='yes';

        // Internal vars - do not change it!
        // Flag for review mechanism
        $vars['admin_mode'] = false;
        // Max scale factor for the portfolio and other isotope elements before relayout
        $vars['isotope_resize_delta'] = 0.3;
        // jQuery object for the message box in the form
        $vars['error_message_box'] = null;
        // Waiting for the viewmore results
        $vars['viewmore_busy'] = false;
        $vars['video_resize_inited'] = false;
        $vars['top_panel_height'] = 0;

        return $vars;
	}
}


//  Enqueue Custom styles (main Theme options settings)
if ( !function_exists( 'themerex_enqueue_custom_styles' ) ) {
	function themerex_enqueue_custom_styles() {
		// Custom stylesheet
		$custom_css = '';
		wp_enqueue_style( 'themerex-custom-style', $custom_css ? $custom_css : themerex_get_file_url('css/custom-style.css'), array(), null );
		// Custom inline styles
		wp_add_inline_style( 'themerex-custom-style', themerex_prepare_custom_styles() );
	}
}

// Add class "widget_number_#' for each widget
if ( !function_exists( 'themerex_add_widget_number' ) ) {
	//Handler of add_filter('dynamic_sidebar_params', 'themerex_add_widget_number', 10, 1);
	function themerex_add_widget_number($prm) {
		global $THEMEREX_GLOBALS;
		if (is_admin()) return $prm;
		static $num=0, $last_sidebar='', $last_sidebar_id='', $last_sidebar_columns=0, $last_sidebar_count=0, $sidebars_widgets=array();
		$cur_sidebar = !empty($THEMEREX_GLOBALS['current_sidebar']) ? $THEMEREX_GLOBALS['current_sidebar'] : 'undefined';
		if (count($sidebars_widgets) == 0)
			$sidebars_widgets = wp_get_sidebars_widgets();
		if ($last_sidebar != $cur_sidebar) {
			$num = 0;
			$last_sidebar = $cur_sidebar;
			$last_sidebar_id = $prm[0]['id'];
			$last_sidebar_columns = max(1, (int) themerex_get_custom_option('sidebar_'.($cur_sidebar).'_columns'));
			$last_sidebar_count = count($sidebars_widgets[$last_sidebar_id]);
		}
		$num++;
		$prm[0]['before_widget'] = str_replace(' class="', ' class="widget_number_'.esc_attr($num).($last_sidebar_columns > 1 ? ' column-1_'.esc_attr($last_sidebar_columns) : '').' ', $prm[0]['before_widget']);
		return $prm;
	}
}


// Filters wp_title to print a neat <title> tag based on what is being viewed.
if ( !function_exists( 'themerex_wp_title' ) ) {
	// Handler of add_filter( 'wp_title', 'themerex_wp_title', 10, 2 );
	function themerex_wp_title( $title, $sep ) {
		global $page, $paged;
		if ( is_feed() ) return $title;
		// Add the blog name
		$title .= get_bloginfo( 'name' );
		// Add the blog description for the home/front page.
		if ( is_home() || is_front_page() ) {
			$site_description = themerex_get_custom_option('logo_slogan');
			if (empty($site_description)) 
				$site_description = get_bloginfo( 'description', 'display' );
			if ( $site_description )
				$title .= " $sep $site_description";
		}
		// Add a page number if necessary:
		if ( $paged >= 2 || $page >= 2 )
			$title .= " $sep " . sprintf( esc_html__( 'Page %s', 'kidsplanet'), max( $paged, $page ) );
		return $title;
	}
}

// Add main menu classes
if ( !function_exists( 'themerex_add_mainmenu_classes' ) ) {
	// Handler of add_filter('wp_nav_menu_objects', 'themerex_add_mainmenu_classes', 10, 2);
	function themerex_add_mainmenu_classes($items, $args) {
		if (is_admin()) return $items;
		if ($args->menu_id == 'mainmenu' && themerex_get_theme_option('menu_colored')=='yes' && is_array($items) && count($items) > 0) {
			foreach($items as $k=>$item) {
				if ($item->menu_item_parent==0) {
					if ($item->type=='taxonomy' && $item->object=='category') {
						$cur_tint = themerex_taxonomy_get_inherited_property('category', $item->object_id, 'bg_tint');
						if (!empty($cur_tint) && !themerex_is_inherit_option($cur_tint))
							$items[$k]->classes[] = 'bg_tint_'.esc_attr($cur_tint);
					}
				}
			}
		}
		return $items;
	}
}


// Save post data from frontend editor
if ( !function_exists( 'themerex_callback_frontend_editor_save' ) ) {
	function themerex_callback_frontend_editor_save() {
		global $_REQUEST;

		if ( !wp_verify_nonce( $_REQUEST['nonce'], 'themerex_editor_nonce' ) )
			wp_die();

		$response = array('error'=>'');

		parse_str(themerex_get_value_gp('data'), $output);
		$post_id = $output['frontend_editor_post_id'];

		if ( themerex_get_theme_option("allow_editor")=='yes' && (current_user_can('edit_posts', $post_id) || current_user_can('edit_pages', $post_id)) ) {
			if ($post_id > 0) {
				$title   = stripslashes($output['frontend_editor_post_title']);
				$content = stripslashes($output['frontend_editor_post_content']);
				$excerpt = stripslashes($output['frontend_editor_post_excerpt']);
				$rez = wp_update_post(array(
					'ID'           => $post_id,
					'post_content' => $content,
					'post_excerpt' => $excerpt,
					'post_title'   => $title
				));
				if ($rez == 0) 
					$response['error'] = esc_html__('Post update error!', 'kidsplanet');
			} else {
				$response['error'] = esc_html__('Post update error!', 'kidsplanet');
			}
		} else
			$response['error'] = esc_html__('Post update denied!', 'kidsplanet');
		
		echo json_encode($response);
		wp_die();
	}
}

// Delete post from frontend editor
if ( !function_exists( 'themerex_callback_frontend_editor_delete' ) ) {
	function themerex_callback_frontend_editor_delete() {
		global $_REQUEST;

		if ( !wp_verify_nonce( $_REQUEST['nonce'], 'themerex_editor_nonce' ) )
			wp_die();

		$response = array('error'=>'');
		
		$post_id = themerex_get_value_gpc('post_id');

		if ( themerex_get_theme_option("allow_editor")=='yes' && (current_user_can('delete_posts', $post_id) || current_user_can('delete_pages', $post_id)) ) {
			if ($post_id > 0) {
				$rez = wp_delete_post($post_id);
				if ($rez === false) 
					$response['error'] = esc_html__('Post delete error!', 'kidsplanet');
			} else {
				$response['error'] = esc_html__('Post delete error!', 'kidsplanet');
			}
		} else
			$response['error'] = esc_html__('Post delete denied!', 'kidsplanet');

		echo json_encode($response);
		wp_die();
	}
}


// Prepare logo text
if ( !function_exists( 'themerex_prepare_logo_text' ) ) {
	function themerex_prepare_logo_text($text) {
		$text = str_replace(array('[', ']'), array('<span class="theme_accent">', '</span>'), $text);
		$text = str_replace(array('{', '}'), array('<strong>', '</strong>'), $text);
		return $text;
	}
}

// Show content with the html layout (if not empty)
if ( !function_exists('themerex_show_layout') ) {
    function themerex_show_layout($str, $before='', $after='') {
        if ($str != '') {
            printf("%s%s%s", $before, $str, $after);
        }
    }
}
?>