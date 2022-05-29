<?php

/* Theme setup section
-------------------------------------------------------------------- */


// ONLY FOR PROGRAMMERS, NOT FOR CUSTOMER
// Framework settings
global $THEMEREX_GLOBALS;

$THEMEREX_GLOBALS['settings'] = array(
	
	'less_compiler'		=> 'lessc',								// no|lessc|less - Compiler for the .less
																// lessc - fast & low memory required, but .less-map, shadows & gradients not supprted
																// less  - slow, but support all features
	'less_nested'		=> false,								// Use nested selectors when compiling less - increase .css size, but allow using nested color schemes
	'less_prefix'		=> '',									// any string - Use prefix before each selector when compile less. For example: 'html '
	'less_separator'	=> '',	//'/*---LESS_SEPARATOR---*/',			// string - separator inside .less files to split it when compiling to reduce memory usage
	'less_map'			=> 'internal',							// no|internal|external - Generate map for .less files. 
																// Warning! You need more then 128Mb for PHP scripts on your server! Supported only if less_compiler=less
	
	'customizer_demo'	=> true,								// Show color customizer demo (if many color settings) or not (if only accent colors used)

	'socials_type'		=> 'icons',								// images|icons - Use this kind of pictograms for all socials: share, social profiles, team members socials, etc.
	'slides_type'		=> 'images'								// images|bg - Use image as slide's content or as slide's background

);



// Default Theme Options
if ( !function_exists( 'themerex_options_settings_theme_setup' ) ) {
	add_action( 'themerex_action_before_init_theme', 'themerex_options_settings_theme_setup', 2 );	// Priority 1 for add themerex_filter handlers
	function themerex_options_settings_theme_setup() {
		global $THEMEREX_GLOBALS;

        // Clear all saved Theme Options on first theme run
        add_action('after_switch_theme', 'themerex_options_reset');

		// Settings 
		$socials_type = themerex_get_theme_setting('socials_type');
				
		// Prepare arrays 
		$THEMEREX_GLOBALS['options_params'] = array(
			'list_fonts'		=> array('$themerex_get_list_fonts' => ''),
			'list_fonts_styles'	=> array('$themerex_get_list_fonts_styles' => ''),
			'list_socials' 		=> array('$themerex_get_list_socials' => ''),
			'list_icons' 		=> array('$themerex_get_list_icons' => ''),
			'list_posts_types' 	=> array('$themerex_get_list_posts_types' => ''),
			'list_categories' 	=> array('$themerex_get_list_categories' => ''),
			'list_menus'		=> array('$themerex_get_list_menus' => ''),
			'list_sidebars'		=> array('$themerex_get_list_sidebars' => ''),
			'list_positions' 	=> array('$themerex_get_list_sidebars_positions' => ''),
			'list_skins'		=> array('$themerex_get_list_skins' => ''),
			'list_color_schemes'=> array('$themerex_get_list_color_schemes' => ''),
			'list_bg_tints'		=> array('$themerex_get_list_bg_tints' => ''),
			'list_body_styles'	=> array('$themerex_get_list_body_styles' => ''),
			'list_header_styles'=> array('$themerex_get_list_templates_header' => ''),
			'list_blog_styles'	=> array('$themerex_get_list_templates_blog' => ''),
			'list_single_styles'=> array('$themerex_get_list_templates_single' => ''),
			'list_article_styles'=> array('$themerex_get_list_article_styles' => ''),
			'list_animations_in' => array('$themerex_get_list_animations_in' => ''),
			'list_animations_out'=> array('$themerex_get_list_animations_out' => ''),
			'list_filters'		=> array('$themerex_get_list_portfolio_filters' => ''),
			'list_hovers'		=> array('$themerex_get_list_hovers' => ''),
			'list_hovers_dir'	=> array('$themerex_get_list_hovers_directions' => ''),
			'list_sliders' 		=> array('$themerex_get_list_sliders' => ''),
			'list_revo_sliders'	=> array('$themerex_get_list_revo_sliders' => ''),
			'list_bg_image_positions' => array('$themerex_get_list_bg_image_positions' => ''),
			'list_popups' 		=> array('$themerex_get_list_popup_engines' => ''),
			'list_gmap_styles' 	=> array('$themerex_get_list_googlemap_styles' => ''),
			'list_yes_no' 		=> array('$themerex_get_list_yesno' => ''),
			'list_on_off' 		=> array('$themerex_get_list_onoff' => ''),
			'list_show_hide' 	=> array('$themerex_get_list_showhide' => ''),
			'list_sorting' 		=> array('$themerex_get_list_sortings' => ''),
			'list_ordering' 	=> array('$themerex_get_list_orderings' => ''),
			'list_locations' 	=> array('$themerex_get_list_dedicated_locations' => '')
			);


		// Theme options array
		$THEMEREX_GLOBALS['options'] = apply_filters('themerex_filter_options', array(

		
		//###############################
		//#### Customization         #### 
		//###############################
		'partition_customization' => array(
					"title" => esc_html__('Customization', 'kidsplanet'),
					"start" => "partitions",
					"override" => "category,services_group,page,post",
					"icon" => "iconadmin-cog-alt",
					"type" => "partition"
					),
		
		
		// Customization -> Body Style
		//-------------------------------------------------
		
		'customization_body' => array(
					"title" => esc_html__('Body style', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"icon" => 'iconadmin-picture',
					"start" => "customization_tabs",
					"type" => "tab"
					),
		
		'info_body_1' => array(
					"title" => esc_html__('Body parameters', 'kidsplanet'),
					"desc" => esc_html__('Select body style, skin and color scheme for entire site. You can override this parameters on any page, post or category', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"type" => "info"
					),
					
		'body_style' => array(
					"title" => esc_html__('Body style', 'kidsplanet'),
					"desc" => wp_kses_data( esc_html__('Select body style:', 'kidsplanet'))
								. ' <br>'
												.wp_kses_data( sprintf(esc_html__("%s boxed %s - if you want use background color and/or image", 'kidsplanet'), '<b>','</b>') )
								. ',<br>'
												. wp_kses_data( sprintf(esc_html__('%s wide %s - page fill whole window with centered content', 'kidsplanet'), '<b>','</b>'))
								. ',<br>' . wp_kses_data(sprintf(esc_html__('%s fullwide %s - page content stretched on the full width of the window (with few left and right paddings)', 'kidsplanet'), '<b>','</b>'))
								. ',<br>' . wp_kses_data(sprintf(esc_html__('%s fullscreen %s - page content fill whole window without any paddings', 'kidsplanet'), '<b>','</b>')),
				"override" => "category,services_group,post,page",
				"std" => "wide",
				"options" => $THEMEREX_GLOBALS['options_params']['list_body_styles'],
				"dir" => "horizontal",
				"type" => "radio"
				),

		'theme_skin' => array(
					"title" => esc_html__('Select theme skin', 'kidsplanet'),
					"desc" => esc_html__('Select skin for the theme decoration', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"std" => "kidsplanet",
					"options" => $THEMEREX_GLOBALS['options_params']['list_skins'],
					"type" => "select"
					),

		"body_scheme" => array(
					"title" => esc_html__('Color scheme', 'kidsplanet'),
					"desc" => esc_html__('Select predefined color scheme for the entire page', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"std" => "original",
					"dir" => "horizontal",
					"options" => $THEMEREX_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),
		
		'body_filled' => array(
					"title" => esc_html__('Fill body', 'kidsplanet'),
					"desc" => esc_html__('Fill the page background with the solid color or leave it transparend to show background image (or video background)', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		'info_body_2' => array(
					"title" => esc_html__('Background color and image', 'kidsplanet'),
					"desc" => esc_html__('Color and image for the site background', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"type" => "info"
					),

		'bg_custom' => array(
					"title" => esc_html__('Use custom background',  'kidsplanet'),
					"desc" => esc_html__("Use custom color and/or image as the site background", 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
		
		'bg_color' => array(
					"title" => esc_html__('Background color',  'kidsplanet'),
					"desc" => esc_html__('Body background color',  'kidsplanet'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "#ffffff",
					"type" => "color"
					),

		'bg_pattern' => array(
					"title" => esc_html__('Background predefined pattern',  'kidsplanet'),
					"desc" => esc_html__('Select theme background pattern (first case - without pattern)',  'kidsplanet'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "",
					"options" => array(
						0 => themerex_get_file_url('images/spacer.png'),
						1 => themerex_get_file_url('images/bg/pattern_1.jpg'),
						2 => themerex_get_file_url('images/bg/pattern_2.jpg'),
						3 => themerex_get_file_url('images/bg/pattern_3.jpg'),
						4 => themerex_get_file_url('images/bg/pattern_4.jpg'),
						5 => themerex_get_file_url('images/bg/pattern_5.jpg')
					),
					"style" => "list",
					"type" => "images"
					),
		
		'bg_pattern_custom' => array(
					"title" => esc_html__('Background custom pattern',  'kidsplanet'),
					"desc" => esc_html__('Select or upload background custom pattern. If selected - use it instead the theme predefined pattern (selected in the field above)',  'kidsplanet'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),
		
		'bg_image' => array(
					"title" => esc_html__('Background predefined image',  'kidsplanet'),
					"desc" => esc_html__('Select theme background image (first case - without image)',  'kidsplanet'),
					"override" => "category,services_group,post,page",
					"std" => "",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"options" => array(
						0 => themerex_get_file_url('images/spacer.png'),
						1 => themerex_get_file_url('images/bg/image_1_thumb.jpg'),
						2 => themerex_get_file_url('images/bg/image_2_thumb.jpg'),
						3 => themerex_get_file_url('images/bg/image_3_thumb.jpg')
					),
					"style" => "list",
					"type" => "images"
					),
		
		'bg_image_custom' => array(
					"title" => esc_html__('Background custom image',  'kidsplanet'),
					"desc" => esc_html__('Select or upload background custom image. If selected - use it instead the theme predefined image (selected in the field above)',  'kidsplanet'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),
		
		'bg_image_custom_position' => array( 
					"title" => esc_html__('Background custom image position',  'kidsplanet'),
					"desc" => esc_html__('Select custom image position',  'kidsplanet'),
					"override" => "category,services_group,post,page",
					"std" => "left_top",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"options" => array(
						'left_top' => "Left Top",
						'center_top' => "Center Top",
						'right_top' => "Right Top",
						'left_center' => "Left Center",
						'center_center' => "Center Center",
						'right_center' => "Right Center",
						'left_bottom' => "Left Bottom",
						'center_bottom' => "Center Bottom",
						'right_bottom' => "Right Bottom",
					),
					"type" => "select"
					),
		
		'bg_image_load' => array(
					"title" => esc_html__('Load background image', 'kidsplanet'),
					"desc" => esc_html__('Always load background images or only for boxed body style', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"std" => "boxed",
					"size" => "medium",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"options" => array(
						'boxed' => esc_html__('Boxed', 'kidsplanet'),
						'always' => esc_html__('Always', 'kidsplanet')
					),
					"type" => "switch"
					),

		
		'info_body_3' => array(
					"title" => esc_html__('Video background', 'kidsplanet'),
					"desc" => esc_html__('Parameters of the video, used as site background', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"type" => "info"
					),

		'show_video_bg' => array(
					"title" => esc_html__('Show video background',  'kidsplanet'),
					"desc" => esc_html__("Show video as the site background", 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		'video_bg_youtube_code' => array(
					"title" => esc_html__('Youtube code for video bg',  'kidsplanet'),
					"desc" => esc_html__("Youtube code of video", 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_video_bg' => array('yes')
					),
					"std" => "",
					"type" => "text"
					),

		'video_bg_url' => array(
					"title" => esc_html__('Local video for video bg',  'kidsplanet'),
					"desc" => esc_html__("URL to video-file (uploaded on your site)", 'kidsplanet'),
					"readonly" =>false,
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_video_bg' => array('yes')
					),
					"before" => array(	'title' => esc_html__('Choose video', 'kidsplanet'),
										'action' => 'media_upload',
										'multiple' => false,
										'linked_field' => '',
										'type' => 'video',
										'captions' => array('choose' => esc_html__( 'Choose Video', 'kidsplanet'),
															'update' => esc_html__( 'Select Video', 'kidsplanet')
														)
								),
					"std" => "",
					"type" => "media"
					),

		'video_bg_overlay' => array(
					"title" => esc_html__('Use overlay for video bg', 'kidsplanet'),
					"desc" => esc_html__('Use overlay texture for the video background', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_video_bg' => array('yes')
					),
					"std" => "no",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
		
		
		
		
		
		// Customization -> Header
		//-------------------------------------------------
		
		'customization_header' => array(
					"title" => esc_html__("Header", 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"icon" => 'iconadmin-window',
					"type" => "tab"),
		
		"info_header_1" => array(
					"title" => esc_html__('Top panel', 'kidsplanet'),
					"desc" => esc_html__('Top panel settings. It include user menu area (with contact info, cart button, language selector, login/logout menu and user menu) and main menu area (with logo and main menu).', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"type" => "info"),
		
		"top_panel_style" => array(
					"title" => esc_html__('Top panel style', 'kidsplanet'),
					"desc" => esc_html__('Select desired style of the page header', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"std" => "header_1",
					"options" => $THEMEREX_GLOBALS['options_params']['list_header_styles'],
					"style" => "list",
					"type" => "images"),

		"top_panel_image" => array(
					"title" => esc_html__('Top panel image', 'kidsplanet'),
					"desc" => esc_html__('Select default background image of the page header (if not single post or featured image for current post is not specified)', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'top_panel_style' => array('header_7')
					),
					"std" => "",
					"type" => "media"),
		
		"top_panel_position" => array( 
					"title" => esc_html__('Top panel position', 'kidsplanet'),
					"desc" => esc_html__('Select position for the top panel with logo and main menu', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"std" => "above",
					"options" => array(
						'hide'  => esc_html__('Hide', 'kidsplanet'),
						'above' => esc_html__('Above slider', 'kidsplanet'),
						'below' => esc_html__('Below slider', 'kidsplanet'),
						'over'  => esc_html__('Over slider', 'kidsplanet')
					),
					"type" => "checklist"),

		"top_panel_scheme" => array(
					"title" => esc_html__('Top panel color scheme', 'kidsplanet'),
					"desc" => esc_html__('Select predefined color scheme for the top panel', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"std" => "original",
					"dir" => "horizontal",
					"options" => $THEMEREX_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),
		
		"show_page_title" => array(
					"title" => esc_html__('Show Page title', 'kidsplanet'),
					"desc" => esc_html__('Show post/page/category title', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_breadcrumbs" => array(
					"title" => esc_html__('Show Breadcrumbs', 'kidsplanet'),
					"desc" => esc_html__('Show path to current category (post, page)', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"breadcrumbs_max_level" => array(
					"title" => esc_html__('Breadcrumbs max nesting', 'kidsplanet'),
					"desc" => esc_html__("Max number of the nested categories in the breadcrumbs (0 - unlimited)", 'kidsplanet'),
					"dependency" => array(
						'show_breadcrumbs' => array('yes')
					),
					"std" => "0",
					"min" => 0,
					"max" => 100,
					"step" => 1,
					"type" => "spinner"),

		
		
		
		"info_header_2" => array( 
					"title" => esc_html__('Main menu style and position', 'kidsplanet'),
					"desc" => esc_html__('Select the Main menu style and position', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"type" => "info"),
		
		"menu_main" => array( 
					"title" => esc_html__('Select main menu',  'kidsplanet'),
					"desc" => esc_html__('Select main menu for the current page',  'kidsplanet'),
					"override" => "category,services_group,post,page",
					"std" => "default",
					"options" => $THEMEREX_GLOBALS['options_params']['list_menus'],
					"type" => "select"),
		
		"menu_attachment" => array( 
					"title" => esc_html__('Main menu attachment', 'kidsplanet'),
					"desc" => esc_html__('Attach main menu to top of window then page scroll down', 'kidsplanet'),
					"std" => "fixed",
					"options" => array(
						"fixed"=>esc_html__("Fix menu position", 'kidsplanet'),
						"none"=>esc_html__("Don't fix menu position", 'kidsplanet')
					),
					"dir" => "vertical",
					"type" => "radio"),

		"menu_slider" => array( 
					"title" => esc_html__('Main menu slider', 'kidsplanet'),
					"desc" => esc_html__('Use slider background for main menu items', 'kidsplanet'),
					"std" => "yes",
					"type" => "switch",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no']),

		"menu_animation_in" => array( 
					"title" => esc_html__('Submenu show animation', 'kidsplanet'),
					"desc" => esc_html__('Select animation to show submenu ', 'kidsplanet'),
					"std" => "bounceIn",
					"type" => "select",
					"options" => $THEMEREX_GLOBALS['options_params']['list_animations_in']),

		"menu_animation_out" => array( 
					"title" => esc_html__('Submenu hide animation', 'kidsplanet'),
					"desc" => esc_html__('Select animation to hide submenu ', 'kidsplanet'),
					"std" => "fadeOutDown",
					"type" => "select",
					"options" => $THEMEREX_GLOBALS['options_params']['list_animations_out']),
		
		"menu_relayout" => array( 
					"title" => esc_html__('Main menu relayout', 'kidsplanet'),
					"desc" => esc_html__('Allow relayout main menu if window width less then this value', 'kidsplanet'),
					"std" => 960,
					"min" => 320,
					"max" => 1024,
					"type" => "spinner"),
		
		"menu_responsive" => array( 
					"title" => esc_html__('Main menu responsive', 'kidsplanet'),
					"desc" => esc_html__('Allow responsive version for the main menu if window width less then this value', 'kidsplanet'),
					"std" => 640,
					"min" => 320,
					"max" => 1024,
					"type" => "spinner"),
		
		"menu_width" => array( 
					"title" => esc_html__('Submenu width', 'kidsplanet'),
					"desc" => esc_html__('Width for dropdown menus in main menu', 'kidsplanet'),
					"step" => 5,
					"std" => "",
					"min" => 180,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"),
		
		
		
		"info_header_3" => array(
					"title" => esc_html__("User's menu area components", 'kidsplanet'),
					"desc" => esc_html__("Select parts for the user's menu area", 'kidsplanet'),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"show_top_panel_top" => array(
					"title" => esc_html__('Show user menu area', 'kidsplanet'),
					"desc" => esc_html__('Show user menu area on top of page', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"menu_user" => array(
					"title" => esc_html__('Select user menu',  'kidsplanet'),
					"desc" => esc_html__('Select user menu for the current page',  'kidsplanet'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "default",
					"options" => $THEMEREX_GLOBALS['options_params']['list_menus'],
					"type" => "select"),
		
		"show_languages" => array(
					"title" => esc_html__('Show language selector', 'kidsplanet'),
					"desc" => esc_html__('Show language selector in the user menu (if WPML plugin installed and current page/post has multilanguage version)', 'kidsplanet'),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_login" => array( 
					"title" => esc_html__('Show Login/Logout buttons', 'kidsplanet'),
					"desc" => esc_html__('Show Login and Logout buttons in the user menu area', 'kidsplanet'),
                    "override" => "page",
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_bookmarks" => array(
					"title" => esc_html__('Show bookmarks', 'kidsplanet'),
					"desc" => esc_html__('Show bookmarks selector in the user menu', 'kidsplanet'),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_socials" => array( 
					"title" => esc_html__('Show Social icons', 'kidsplanet'),
					"desc" => esc_html__('Show Social icons in the user menu area', 'kidsplanet'),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
        "show_phone" => array(
                "title" => esc_html__('Show phone number', 'kidsplanet'),
                "desc" => esc_html__('Show phone number in the user menu area', 'kidsplanet'),
                "override" => "page",
                "dependency" => array(
                    'show_top_panel_top' => array('yes'),
                ),
                "std" => "no",
                "options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
                "type" => "switch"),
		

		
		"info_header_4" => array( 
					"title" => esc_html__("Table of Contents (TOC)", 'kidsplanet'),
					"desc" => esc_html__("Table of Contents for the current page. Automatically created if the page contains objects with id starting with 'toc_'", 'kidsplanet'),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"menu_toc" => array( 
					"title" => esc_html__('TOC position', 'kidsplanet'),
					"desc" => esc_html__('Show TOC for the current page', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"std" => "float",
					"options" => array(
						'hide'  => esc_html__('Hide', 'kidsplanet'),
						'fixed' => esc_html__('Fixed', 'kidsplanet'),
						'float' => esc_html__('Float', 'kidsplanet')
					),
					"type" => "checklist"),
		
		"menu_toc_home" => array(
					"title" => esc_html__('Add "Home" into TOC', 'kidsplanet'),
					"desc" => esc_html__('Automatically add "Home" item into table of contents - return to home page of the site', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'menu_toc' => array('fixed','float')
					),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"menu_toc_top" => array( 
					"title" => esc_html__('Add "To Top" into TOC', 'kidsplanet'),
					"desc" => esc_html__('Automatically add "To Top" item into table of contents - scroll to top of the page', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'menu_toc' => array('fixed','float')
					),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		
		
		
		'info_header_5' => array(
					"title" => esc_html__('Main logo', 'kidsplanet'),
					"desc" => esc_html__("Select or upload logos for the site's header and select it position", 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"type" => "info"
					),

		'logo' => array(
					"title" => esc_html__('Logo image', 'kidsplanet'),
					"desc" => esc_html__('Main logo image', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"std" => "",
					"type" => "media"
					),

		'logo_fixed' => array(
					"title" => esc_html__('Logo image (fixed header)', 'kidsplanet'),
					"desc" => esc_html__('Logo image for the header (if menu is fixed after the page is scrolled)', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"divider" => false,
					"std" => "",
					"type" => "media"
					),

		'logo_text' => array(
					"title" => esc_html__('Logo text', 'kidsplanet'),
					"desc" => esc_html__('Logo text - display it after logo image', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"std" => '',
					"type" => "text"
					),

		'logo_slogan' => array(
					"title" => esc_html__('Logo slogan', 'kidsplanet'),
					"desc" => esc_html__('Logo slogan - display it under logo image (instead the site tagline)', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"std" => '',
					"type" => "text"
					),

		'logo_height' => array(
					"title" => esc_html__('Logo height', 'kidsplanet'),
					"desc" => esc_html__('Height for the logo in the header area', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"step" => 1,
					"std" => '',
					"min" => 10,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"
					),

		'logo_offset' => array(
					"title" => esc_html__('Logo top offset', 'kidsplanet'),
					"desc" => esc_html__('Top offset for the logo in the header area', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"step" => 1,
					"std" => '',
					"min" => 0,
					"max" => 99,
					"mask" => "?99",
					"type" => "spinner"
					),
		
		
		
		
		
		
		
		// Customization -> Slider
		//-------------------------------------------------
		
		"customization_slider" => array( 
					"title" => esc_html__('Slider', 'kidsplanet'),
					"icon" => "iconadmin-picture",
					"override" => "category,services_group,page",
					"type" => "tab"),
		
		"info_slider_1" => array(
					"title" => esc_html__('Main slider parameters', 'kidsplanet'),
					"desc" => esc_html__('Select parameters for main slider (you can override it in each category and page)', 'kidsplanet'),
					"override" => "category,services_group,page",
					"type" => "info"),
					
		"show_slider" => array(
					"title" => esc_html__('Show Slider', 'kidsplanet'),
					"desc" => esc_html__('Do you want to show slider on each page (post)', 'kidsplanet'),
					"override" => "category,services_group,page",
					"std" => "no",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"slider_display" => array(
					"title" => esc_html__('Slider display', 'kidsplanet'),
					"desc" => esc_html__('How display slider: boxed (fixed width and height), fullwide (fixed height) or fullscreen', 'kidsplanet'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => "fullwide",
					"options" => array(
						"boxed"=>esc_html__("Boxed", 'kidsplanet'),
						"fullwide"=>esc_html__("Fullwide", 'kidsplanet'),
						"fullscreen"=>esc_html__("Fullscreen", 'kidsplanet')
					),
					"type" => "checklist"),
		
		"slider_height" => array(
					"title" => esc_html__("Height (in pixels)", 'kidsplanet'),
					"desc" => esc_html__("Slider height (in pixels) - only if slider display with fixed height.", 'kidsplanet'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => '',
					"min" => 100,
					"step" => 10,
					"type" => "spinner"),
		
		"slider_engine" => array(
					"title" => esc_html__('Slider engine', 'kidsplanet'),
					"desc" => esc_html__('What engine use to show slider?', 'kidsplanet'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => "revo",
					"options" => $THEMEREX_GLOBALS['options_params']['list_sliders'],
					"type" => "radio"),
		
		"slider_alias" => array(
					"title" => esc_html__('Revolution Slider: Select slider',  'kidsplanet'),
					"desc" => esc_html__("Select slider to show (if engine=revo in the field above)", 'kidsplanet'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('revo')
					),
					"std" => "",
					"options" => $THEMEREX_GLOBALS['options_params']['list_revo_sliders'],
					"type" => "select"),
		
		"slider_category" => array(
					"title" => esc_html__('Posts Slider: Category to show', 'kidsplanet'),
					"desc" => esc_html__('Select category to show in Flexslider (ignored for Revolution and Royal sliders)', 'kidsplanet'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "",
					"options" => themerex_array_merge(array(0 => esc_html__('- Select category -', 'kidsplanet')), $THEMEREX_GLOBALS['options_params']['list_categories']),
					"type" => "select",
					"multiple" => true,
					"style" => "list"),
		
		"slider_posts" => array(
					"title" => esc_html__('Posts Slider: Number posts or comma separated posts list',  'kidsplanet'),
					"desc" => esc_html__("How many recent posts display in slider or comma separated list of posts ID (in this case selected category ignored)", 'kidsplanet'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "5",
					"type" => "text"),
		
		"slider_orderby" => array(
					"title" => esc_html__("Posts Slider: Posts order by",  'kidsplanet'),
					"desc" => esc_html__("Posts in slider ordered by date (default), comments, views, author rating, users rating, random or alphabetically", 'kidsplanet'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "date",
					"options" => $THEMEREX_GLOBALS['options_params']['list_sorting'],
					"type" => "select"),
		
		"slider_order" => array(
					"title" => esc_html__("Posts Slider: Posts order", 'kidsplanet'),
					"desc" => esc_html__('Select the desired ordering method for posts', 'kidsplanet'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "desc",
					"options" => $THEMEREX_GLOBALS['options_params']['list_ordering'],
					"size" => "big",
					"type" => "switch"),
					
		"slider_interval" => array(
					"title" => esc_html__("Posts Slider: Slide change interval", 'kidsplanet'),
					"desc" => esc_html__("Interval (in ms) for slides change in slider", 'kidsplanet'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => 7000,
					"min" => 100,
					"step" => 100,
					"type" => "spinner"),
		
		"slider_pagination" => array(
					"title" => esc_html__("Posts Slider: Pagination", 'kidsplanet'),
					"desc" => esc_html__("Choose pagination style for the slider", 'kidsplanet'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "no",
					"options" => array(
						'no'   => esc_html__('None', 'kidsplanet'),
						'yes'  => esc_html__('Dots', 'kidsplanet'),
						'over' => esc_html__('Titles', 'kidsplanet')
					),
					"type" => "checklist"),
		
		"slider_infobox" => array(
					"title" => esc_html__("Posts Slider: Show infobox", 'kidsplanet'),
					"desc" => esc_html__("Do you want to show post's title, reviews rating and description on slides in slider", 'kidsplanet'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "slide",
					"options" => array(
						'no'    => esc_html__('None',  'kidsplanet'),
						'slide' => esc_html__('Slide', 'kidsplanet'),
						'fixed' => esc_html__('Fixed', 'kidsplanet')
					),
					"type" => "checklist"),
					
		"slider_info_category" => array(
					"title" => esc_html__("Posts Slider: Show post's category", 'kidsplanet'),
					"desc" => esc_html__("Do you want to show post's category on slides in slider", 'kidsplanet'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"slider_info_reviews" => array(
					"title" => esc_html__("Posts Slider: Show post's reviews rating", 'kidsplanet'),
					"desc" => esc_html__("Do you want to show post's reviews rating on slides in slider", 'kidsplanet'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"slider_info_descriptions" => array(
					"title" => esc_html__("Posts Slider: Show post's descriptions", 'kidsplanet'),
					"desc" => esc_html__("How many characters show in the post's description in slider. 0 - no descriptions", 'kidsplanet'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => 0,
					"min" => 0,
					"step" => 10,
					"type" => "spinner"),
		
		
		
		
		
		// Customization -> Sidebars
		//-------------------------------------------------
		
		"customization_sidebars" => array( 
					"title" => esc_html__('Sidebars', 'kidsplanet'),
					"icon" => "iconadmin-indent-right",
					"override" => "category,services_group,post,page",
					"type" => "tab"),
		
		"info_sidebars_1" => array( 
					"title" => esc_html__('Custom sidebars', 'kidsplanet'),
					"desc" => esc_html__('In this section you can create unlimited sidebars. You can fill them with widgets in the menu Appearance - Widgets', 'kidsplanet'),
					"type" => "info"),
		
		"custom_sidebars" => array(
					"title" => esc_html__('Custom sidebars',  'kidsplanet'),
					"desc" => esc_html__('Manage custom sidebars. You can use it with each category (page, post) independently',  'kidsplanet'),
					"std" => "",
					"cloneable" => true,
					"type" => "text"),
		
		"info_sidebars_2" => array(
					"title" => esc_html__('Main sidebar', 'kidsplanet'),
					"desc" => esc_html__('Show / Hide and select main sidebar', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"type" => "info"),
		
		'show_sidebar_main' => array( 
					"title" => esc_html__('Show main sidebar',  'kidsplanet'),
					"desc" => esc_html__('Select position for the main sidebar or hide it',  'kidsplanet'),
					"override" => "category,services_group,post,page",
					"std" => "right",
					"options" => $THEMEREX_GLOBALS['options_params']['list_positions'],
					"dir" => "horizontal",
					"type" => "checklist"),

		"sidebar_main_scheme" => array(
					"title" => esc_html__("Color scheme", 'kidsplanet'),
					"desc" => esc_html__('Select predefined color scheme for the main sidebar', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_main' => array('left', 'right')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $THEMEREX_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),
		
		"sidebar_main" => array( 
					"title" => esc_html__('Select main sidebar',  'kidsplanet'),
					"desc" => esc_html__('Select main sidebar content',  'kidsplanet'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_main' => array('left', 'right')
					),
					"std" => "sidebar_main",
					"options" => $THEMEREX_GLOBALS['options_params']['list_sidebars'],
					"type" => "select"),
		
		"info_sidebars_3" => array(
					"title" => esc_html__('Outer sidebar', 'kidsplanet'),
					"desc" => esc_html__('Show / Hide and select outer sidebar (sidemenu, logo, etc.', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"type" => "info"),
		
		'show_sidebar_outer' => array( 
					"title" => esc_html__('Show outer sidebar',  'kidsplanet'),
					"desc" => esc_html__('Select position for the outer sidebar or hide it',  'kidsplanet'),
					"override" => "category,services_group,post,page",
					"std" => "hide",
					"options" => $THEMEREX_GLOBALS['options_params']['list_positions'],
					"dir" => "horizontal",
					"type" => "checklist"),

		"sidebar_outer_scheme" => array(
					"title" => esc_html__("Color scheme", 'kidsplanet'),
					"desc" => esc_html__('Select predefined color scheme for the outer sidebar', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_outer' => array('left', 'right')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $THEMEREX_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),
		
		"sidebar_outer_show_logo" => array( 
					"title" => esc_html__('Show Logo', 'kidsplanet'),
					"desc" => esc_html__('Show Logo in the outer sidebar', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_outer' => array('left', 'right')
					),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"sidebar_outer_show_socials" => array( 
					"title" => esc_html__('Show Social icons', 'kidsplanet'),
					"desc" => esc_html__('Show Social icons in the outer sidebar', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_outer' => array('left', 'right')
					),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"sidebar_outer_show_menu" => array( 
					"title" => esc_html__('Show Menu', 'kidsplanet'),
					"desc" => esc_html__('Show Menu in the outer sidebar', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_outer' => array('left', 'right')
					),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"menu_side" => array(
					"title" => esc_html__('Select menu',  'kidsplanet'),
					"desc" => esc_html__('Select menu for the outer sidebar',  'kidsplanet'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_outer' => array('left', 'right'),
						'sidebar_outer_show_menu' => array('yes')
					),
					"std" => "default",
					"options" => $THEMEREX_GLOBALS['options_params']['list_menus'],
					"type" => "select"),
		
		"sidebar_outer_show_widgets" => array( 
					"title" => esc_html__('Show Widgets', 'kidsplanet'),
					"desc" => esc_html__('Show Widgets in the outer sidebar', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_outer' => array('left', 'right')
					),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"sidebar_outer" => array( 
					"title" => esc_html__('Select outer sidebar',  'kidsplanet'),
					"desc" => esc_html__('Select outer sidebar content',  'kidsplanet'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'sidebar_outer_show_widgets' => array('yes'),
						'show_sidebar_outer' => array('left', 'right')
					),
					"std" => "sidebar_outer",
					"options" => $THEMEREX_GLOBALS['options_params']['list_sidebars'],
					"type" => "select"),
		
		
		
		
		// Customization -> Footer
		//-------------------------------------------------
		
		'customization_footer' => array(
					"title" => esc_html__("Footer", 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"icon" => 'iconadmin-window',
					"type" => "tab"),
		
		
		"info_footer_1" => array(
					"title" => esc_html__("Footer components", 'kidsplanet'),
					"desc" => esc_html__("Select components of the footer, set style and put the content for the user's footer area", 'kidsplanet'),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"show_sidebar_footer" => array(
					"title" => esc_html__('Show footer sidebar', 'kidsplanet'),
					"desc" => esc_html__('Select style for the footer sidebar or hide it', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"sidebar_footer_scheme" => array(
					"title" => esc_html__("Color scheme", 'kidsplanet'),
					"desc" => esc_html__('Select predefined color scheme for the footer', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $THEMEREX_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),
		
		"sidebar_footer" => array( 
					"title" => esc_html__('Select footer sidebar',  'kidsplanet'),
					"desc" => esc_html__('Select footer sidebar for the blog page',  'kidsplanet'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => "sidebar_footer",
					"options" => $THEMEREX_GLOBALS['options_params']['list_sidebars'],
					"type" => "select"),
		
		"sidebar_footer_columns" => array( 
					"title" => esc_html__('Footer sidebar columns',  'kidsplanet'),
					"desc" => esc_html__('Select columns number for the footer sidebar',  'kidsplanet'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => 3,
					"min" => 1,
					"max" => 6,
					"type" => "spinner"),


            "info_footer_2" => array(
                "title" => esc_html__("Aditional footer components", 'kidsplanet'),
                "desc" => esc_html__("Select components of the footer, set style and put the content for the user's footer area", 'kidsplanet'),
                "override" => "category,services_group,page,post",
                "type" => "info"),

            "show_sidebar_top_footer" => array(
                "title" => esc_html__('Show footer top sidebar', 'kidsplanet'),
                "desc" => esc_html__('Select style for the footer top sidebar or hide it', 'kidsplanet'),
                "override" => "category,services_group,post,page",
                "std" => "no",
                "options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
                "type" => "switch"),

            "sidebar_footer_top_scheme" => array(
                "title" => esc_html__("Color scheme top sidebar", 'kidsplanet'),
                "desc" => esc_html__('Select predefined color scheme for the footer top sidebar', 'kidsplanet'),
                "override" => "category,services_group,post,page",
                "dependency" => array(
                    'show_sidebar_top_footer' => array('yes')
                ),
                "std" => "original",
                "dir" => "horizontal",
                "options" => $THEMEREX_GLOBALS['options_params']['list_color_schemes'],
                "type" => "checklist"),

            "sidebar_top_footer" => array(
                "title" => esc_html__('Select footer top sidebar',  'kidsplanet'),
                "desc" => esc_html__('Select footer top sidebar for the blog page',  'kidsplanet'),
                "override" => "category,services_group,post,page",
                "dependency" => array(
                    'show_sidebar_top_footer' => array('yes')
                ),
                "std" => "sidebar_top_footer",
                "options" => $THEMEREX_GLOBALS['options_params']['list_sidebars'],
                "type" => "select"),

		"info_footer_3" => array(
					"title" => esc_html__('Testimonials in Footer', 'kidsplanet'),
					"desc" => esc_html__('Select parameters for Testimonials in the Footer', 'kidsplanet'),
					"override" => "category,services_group,page,post",
					"type" => "info"),

		"show_testimonials_in_footer" => array(
					"title" => esc_html__('Show Testimonials in footer', 'kidsplanet'),
					"desc" => esc_html__('Show Testimonials slider in footer. For correct operation of the slider (and shortcode testimonials) you must fill out Testimonials posts on the menu "Testimonials"', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"testimonials_scheme" => array(
					"title" => esc_html__("Color scheme", 'kidsplanet'),
					"desc" => esc_html__('Select predefined color scheme for the testimonials area', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_testimonials_in_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $THEMEREX_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),

		"testimonials_count" => array( 
					"title" => esc_html__('Testimonials count', 'kidsplanet'),
					"desc" => esc_html__('Number testimonials to show', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_testimonials_in_footer' => array('yes')
					),
					"std" => 3,
					"step" => 1,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),
		
		
		"info_footer_4" => array(
					"title" => esc_html__('Twitter in Footer', 'kidsplanet'),
					"desc" => esc_html__('Select parameters for Twitter stream in the Footer (you can override it in each category and page)', 'kidsplanet'),
					"override" => "category,services_group,page,post",
					"type" => "info"),

		"show_twitter_in_footer" => array(
					"title" => esc_html__('Show Twitter in footer', 'kidsplanet'),
					"desc" => esc_html__('Show Twitter slider in footer. For correct operation of the slider (and shortcode twitter) you must fill out the Twitter API keys on the menu "Appearance - Theme Options - Socials"', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"twitter_scheme" => array(
					"title" => esc_html__("Color scheme", 'kidsplanet'),
					"desc" => esc_html__('Select predefined color scheme for the twitter area', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_twitter_in_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $THEMEREX_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),

		"twitter_count" => array( 
					"title" => esc_html__('Twitter count', 'kidsplanet'),
					"desc" => esc_html__('Number twitter to show', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_twitter_in_footer' => array('yes')
					),
					"std" => 3,
					"step" => 1,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),


		"info_footer_5" => array(
					"title" => esc_html__('Google map parameters', 'kidsplanet'),
					"desc" => esc_html__('Select parameters for Google map (you can override it in each category and page)', 'kidsplanet'),
					"override" => "category,services_group,page,post",
					"type" => "info"),
					
		"show_googlemap" => array(
					"title" => esc_html__('Show Google Map', 'kidsplanet'),
					"desc" => esc_html__('Do you want to show Google map on each page (post)', 'kidsplanet'),
					"override" => "category,services_group,page,post",
					"std" => "no",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"googlemap_height" => array(
					"title" => esc_html__("Map height", 'kidsplanet'),
					"desc" => esc_html__("Map height (default - in pixels, allows any CSS units of measure)", 'kidsplanet'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => 400,
					"min" => 100,
					"step" => 10,
					"type" => "spinner"),
		
		"googlemap_address" => array(
					"title" => esc_html__('Address to show on map',  'kidsplanet'),
					"desc" => esc_html__("Enter address to show on map center", 'kidsplanet'),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_latlng" => array(
					"title" => esc_html__('Latitude and Longtitude to show on map',  'kidsplanet'),
					"desc" => esc_html__("Enter coordinates (separated by comma) to show on map center (instead of address)", 'kidsplanet'),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_title" => array(
					"title" => esc_html__('Title to show on map',  'kidsplanet'),
					"desc" => esc_html__("Enter title to show on map center", 'kidsplanet'),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_description" => array(
					"title" => esc_html__('Description to show on map',  'kidsplanet'),
					"desc" => esc_html__("Enter description to show on map center", 'kidsplanet'),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_zoom" => array(
					"title" => esc_html__('Google map initial zoom',  'kidsplanet'),
					"desc" => esc_html__("Enter desired initial zoom for Google map", 'kidsplanet'),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => 16,
					"min" => 1,
					"max" => 20,
					"step" => 1,
					"type" => "spinner"),
		
		"googlemap_style" => array(
					"title" => esc_html__('Google map style',  'kidsplanet'),
					"desc" => esc_html__("Select style to show Google map", 'kidsplanet'),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => 'style1',
					"options" => $THEMEREX_GLOBALS['options_params']['list_gmap_styles'],
					"type" => "select"),
		
		"googlemap_marker" => array(
					"title" => esc_html__('Google map marker',  'kidsplanet'),
					"desc" => esc_html__("Select or upload png-image with Google map marker", 'kidsplanet'),
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => '',
					"type" => "media"),
		
		
		
		"info_footer_6" => array(
					"title" => esc_html__("Contacts area", 'kidsplanet'),
					"desc" => esc_html__("Show/Hide contacts area in the footer", 'kidsplanet'),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"show_contacts_in_footer" => array(
					"title" => esc_html__('Show Contacts in footer', 'kidsplanet'),
					"desc" => esc_html__('Show contact information area in footer: site logo, contact info and large social icons', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"contacts_scheme" => array(
					"title" => esc_html__("Color scheme", 'kidsplanet'),
					"desc" => esc_html__('Select predefined color scheme for the contacts area', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $THEMEREX_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),

		'logo_footer' => array(
					"title" => esc_html__('Logo image for footer', 'kidsplanet'),
					"desc" => esc_html__('Logo image in the footer (in the contacts area)', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),
		
		'logo_footer_height' => array(
					"title" => esc_html__('Logo height', 'kidsplanet'),
					"desc" => esc_html__('Height for the logo in the footer area (in the contacts area)', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"step" => 1,
					"std" => 30,
					"min" => 10,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"
					),
		
		
		
		"info_footer_7" => array(
					"title" => esc_html__("Copyright and footer menu", 'kidsplanet'),
					"desc" => esc_html__("Show/Hide copyright area in the footer", 'kidsplanet'),
					"override" => "category,services_group,page,post",
					"type" => "info"),

		"show_copyright_in_footer" => array(
					"title" => esc_html__('Show Copyright area in footer', 'kidsplanet'),
					"desc" => esc_html__('Show area with copyright information, footer menu and small social icons in footer', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"std" => "plain",
					"options" => array(
						'none' => esc_html__('Hide', 'kidsplanet'),
						'text' => esc_html__('Text', 'kidsplanet'),
						'menu' => esc_html__('Text and menu', 'kidsplanet'),
						'socials' => esc_html__('Text and Social icons', 'kidsplanet')
					),
					"type" => "checklist"),

		"copyright_scheme" => array(
					"title" => esc_html__("Color scheme", 'kidsplanet'),
					"desc" => esc_html__('Select predefined color scheme for the copyright area', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_copyright_in_footer' => array('text', 'menu', 'socials')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $THEMEREX_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),
		
		"menu_footer" => array( 
					"title" => esc_html__('Select footer menu',  'kidsplanet'),
					"desc" => esc_html__('Select footer menu for the current page',  'kidsplanet'),
					"override" => "category,services_group,post,page",
					"std" => "default",
					"dependency" => array(
						'show_copyright_in_footer' => array('menu')
					),
					"options" => $THEMEREX_GLOBALS['options_params']['list_menus'],
					"type" => "select"),

		"footer_copyright" => array(
					"title" => esc_html__('Footer copyright text',  'kidsplanet'),
					"desc" => esc_html__("Copyright text to show in footer area (bottom of site)", 'kidsplanet'),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_copyright_in_footer' => array('text', 'menu', 'socials')
					),
					"std" => "Ancora &copy; {Y} All Rights Reserved ",
					"rows" => "10",
					"type" => "editor"),




		// Customization -> Other
		//-------------------------------------------------
		
		'customization_other' => array(
					"title" => esc_html__('Other', 'kidsplanet'),
					"override" => "category,services_group,page,post",
					"icon" => 'iconadmin-cog',
					"type" => "tab"
					),


		'info_other_0' => array(
					"title" => esc_html__('Widgets Block Editor', 'kidsplanet'),
					"desc" => wp_kses_data( __('Put here your custom CSS and JS code', 'kidsplanet') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"
				),

		"disable_widgets_block_editor" => array(
					"title" => esc_html__('Disable new Widgets Block Editor', 'kidsplanet'),
					"desc" => wp_kses_data( __('Attention! If after the update to WordPress 5.8+ you are having trouble editing widgets or working in Customizer - disable new Widgets Block Editor (used in WordPress 5.8+ instead of a classic widgets panel)', 'kidsplanet') ),
					"std" => "no",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
				),

		'info_other_1' => array(
					"title" => esc_html__('Theme customization other parameters', 'kidsplanet'),
					"desc" => esc_html__('Animation parameters and responsive layouts for the small screens', 'kidsplanet'),
					"type" => "info"
					),

		'show_theme_customizer' => array(
					"title" => esc_html__('Show Theme customizer', 'kidsplanet'),
					"desc" => esc_html__('Do you want to show theme customizer in the right panel? Your website visitors will be able to customise it yourself.', 'kidsplanet'),
					"std" => "no",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		"customizer_demo" => array(
					"title" => esc_html__('Theme customizer panel demo time', 'kidsplanet'),
					"desc" => esc_html__('Timer for demo mode for the customizer panel (in milliseconds: 1000ms = 1s). If 0 - no demo.', 'kidsplanet'),
					"dependency" => array(
						'show_theme_customizer' => array('yes')
					),
					"std" => "0",
					"min" => 0,
					"max" => 10000,
					"step" => 500,
					"type" => "spinner"),
		
		'css_animation' => array(
					"title" => esc_html__('Extended CSS animations', 'kidsplanet'),
					"desc" => esc_html__('Do you want use extended animations effects on your site?', 'kidsplanet'),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		'remember_visitors_settings' => array(
					"title" => esc_html__("Remember visitor's settings", 'kidsplanet'),
					"desc" => esc_html__('To remember the settings that were made by the visitor, when navigating to other pages or to limit their effect only within the current page', 'kidsplanet'),
					"std" => "no",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
					
		'responsive_layouts' => array(
					"title" => esc_html__('Responsive Layouts', 'kidsplanet'),
					"desc" => esc_html__('Do you want use responsive layouts on small screen or still use main layout?', 'kidsplanet'),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

            'privacy_text' => array(
                "title" => esc_html__("Text with Privacy Policy link", 'kidsplanet'),
                "desc"  => wp_kses_data( esc_html__("Specify text with Privacy Policy link for the checkbox 'I agree ...'", 'kidsplanet') ),
                "std"   => wp_kses_post( esc_html__( 'I agree that my submitted data is being collected and stored.', 'kidsplanet') ),
                "type"  => "text"
            ),
		
		
		
		
		//###############################
		//#### Blog and Single pages #### 
		//###############################
		"partition_blog" => array(
					"title" => esc_html__('Blog &amp; Single', 'kidsplanet'),
					"icon" => "iconadmin-docs",
					"override" => "category,services_group,post,page",
					"type" => "partition"),
		
		
		
		// Blog -> Stream page
		//-------------------------------------------------
		
		'blog_tab_stream' => array(
					"title" => esc_html__('Stream page', 'kidsplanet'),
					"start" => 'blog_tabs',
					"icon" => "iconadmin-docs",
					"override" => "category,services_group,post,page",
					"type" => "tab"),
		
		"info_blog_1" => array(
					"title" => esc_html__('Blog streampage parameters', 'kidsplanet'),
					"desc" => esc_html__('Select desired blog streampage parameters (you can override it in each category)', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"type" => "info"),
		
		"blog_style" => array(
					"title" => esc_html__('Blog style', 'kidsplanet'),
					"desc" => esc_html__('Select desired blog style', 'kidsplanet'),
					"override" => "category,services_group,page",
					"std" => "excerpt",
					"options" => $THEMEREX_GLOBALS['options_params']['list_blog_styles'],
					"type" => "select"),
		
		"hover_style" => array(
					"title" => esc_html__('Hover style', 'kidsplanet'),
					"desc" => esc_html__('Select desired hover style (only for Blog style = Portfolio)', 'kidsplanet'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored')
					),
					"std" => "square effect_shift",
					"options" => $THEMEREX_GLOBALS['options_params']['list_hovers'],
					"type" => "select"),
		
		"hover_dir" => array(
					"title" => esc_html__('Hover dir', 'kidsplanet'),
					"desc" => esc_html__('Select hover direction (only for Blog style = Portfolio and Hover style = Circle or Square)', 'kidsplanet'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored'),
						'hover_style' => array('square','circle')
					),
					"std" => "left_to_right",
					"options" => $THEMEREX_GLOBALS['options_params']['list_hovers_dir'],
					"type" => "select"),
		
		"article_style" => array(
					"title" => esc_html__('Article style', 'kidsplanet'),
					"desc" => esc_html__('Select article display method: boxed or stretch', 'kidsplanet'),
					"override" => "category,services_group,page",
					"std" => "stretch",
					"options" => $THEMEREX_GLOBALS['options_params']['list_article_styles'],
					"size" => "medium",
					"type" => "switch"),
		
		"dedicated_location" => array(
					"title" => esc_html__('Dedicated location', 'kidsplanet'),
					"desc" => esc_html__('Select location for the dedicated content or featured image in the "excerpt" blog style', 'kidsplanet'),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'blog_style' => array('excerpt')
					),
					"std" => "default",
					"options" => $THEMEREX_GLOBALS['options_params']['list_locations'],
					"type" => "select"),
		
		"show_filters" => array(
					"title" => esc_html__('Show filters', 'kidsplanet'),
					"desc" => esc_html__('What taxonomy use for filter buttons', 'kidsplanet'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored')
					),
					"std" => "hide",
					"options" => $THEMEREX_GLOBALS['options_params']['list_filters'],
					"type" => "checklist"),
		
		"blog_sort" => array(
					"title" => esc_html__('Blog posts sorted by', 'kidsplanet'),
					"desc" => esc_html__('Select the desired sorting method for posts', 'kidsplanet'),
					"override" => "category,services_group,page",
					"std" => "date",
					"options" => $THEMEREX_GLOBALS['options_params']['list_sorting'],
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_order" => array(
					"title" => esc_html__('Blog posts order', 'kidsplanet'),
					"desc" => esc_html__('Select the desired ordering method for posts', 'kidsplanet'),
					"override" => "category,services_group,page",
					"std" => "desc",
					"options" => $THEMEREX_GLOBALS['options_params']['list_ordering'],
					"size" => "big",
					"type" => "switch"),
		
		"posts_per_page" => array(
					"title" => esc_html__('Blog posts per page',  'kidsplanet'),
					"desc" => esc_html__('How many posts display on blog pages for selected style. If empty or 0 - inherit system wordpress settings',  'kidsplanet'),
					"override" => "category,services_group,page",
					"std" => "12",
					"mask" => "?99",
					"type" => "text"),
		
		"post_excerpt_maxlength" => array(
					"title" => esc_html__('Excerpt maxlength for streampage',  'kidsplanet'),
					"desc" => esc_html__('How many characters from post excerpt are display in blog streampage (only for Blog style = Excerpt). 0 - do not trim excerpt.',  'kidsplanet'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('excerpt', 'portfolio', 'grid', 'square', 'related')
					),
					"std" => "250",
					"mask" => "?9999",
					"type" => "text"),
		
		"post_excerpt_maxlength_masonry" => array(
					"title" => esc_html__('Excerpt maxlength for classic and masonry',  'kidsplanet'),
					"desc" => esc_html__('How many characters from post excerpt are display in blog streampage (only for Blog style = Classic or Masonry). 0 - do not trim excerpt.',  'kidsplanet'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('masonry', 'classic')
					),
					"std" => "150",
					"mask" => "?9999",
					"type" => "text"),
		
		
		
		
		// Blog -> Single page
		//-------------------------------------------------
		
		'blog_tab_single' => array(
					"title" => esc_html__('Single page', 'kidsplanet'),
					"icon" => "iconadmin-doc",
					"override" => "category,services_group,post,page",
					"type" => "tab"),
		
		
		"info_single_1" => array(
					"title" => esc_html__('Single (detail) pages parameters', 'kidsplanet'),
					"desc" => esc_html__('Select desired parameters for single (detail) pages (you can override it in each category and single post (page))', 'kidsplanet'),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"single_style" => array(
					"title" => esc_html__('Single page style', 'kidsplanet'),
					"desc" => esc_html__('Select desired style for single page', 'kidsplanet'),
					"override" => "category,services_group,page,post",
					"std" => "single-standard",
					"options" => $THEMEREX_GLOBALS['options_params']['list_single_styles'],
					"dir" => "horizontal",
					"type" => "radio"),

		"icon" => array(
					"title" => esc_html__('Select post icon', 'kidsplanet'),
					"desc" => esc_html__('Select icon for output before post/category name in some layouts', 'kidsplanet'),
					"override" => "services_group,page,post",
					"std" => "",
					"options" => $THEMEREX_GLOBALS['options_params']['list_icons'],
					"style" => "select",
					"type" => "icons"
					),
		
		"show_featured_image" => array(
					"title" => esc_html__('Show featured image before post',  'kidsplanet'),
					"desc" => esc_html__("Show featured image (if selected) before post content on single pages", 'kidsplanet'),
					"override" => "category,services_group,page,post",
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_title" => array(
					"title" => esc_html__('Show post title', 'kidsplanet'),
					"desc" => esc_html__('Show area with post title on single pages', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_title_on_quotes" => array(
					"title" => esc_html__('Show post title on links, chat, quote, status', 'kidsplanet'),
					"desc" => esc_html__('Show area with post title on single and blog pages in specific post formats: links, chat, quote, status', 'kidsplanet'),
					"override" => "category,services_group,page",
					"std" => "no",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_info" => array(
					"title" => esc_html__('Show post info', 'kidsplanet'),
					"desc" => esc_html__('Show area with post info on single pages', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_text_before_readmore" => array(
					"title" => esc_html__('Show text before "Read more" tag', 'kidsplanet'),
					"desc" => esc_html__('Show text before "Read more" tag on single pages', 'kidsplanet'),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"show_post_author" => array(
					"title" => esc_html__('Show post author details',  'kidsplanet'),
					"desc" => esc_html__("Show post author information block on single post page", 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_tags" => array(
					"title" => esc_html__('Show post tags',  'kidsplanet'),
					"desc" => esc_html__("Show tags block on single post page", 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_related" => array(
					"title" => esc_html__('Show related posts',  'kidsplanet'),
					"desc" => esc_html__("Show related posts block on single post page", 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"post_related_count" => array(
					"title" => esc_html__('Related posts number',  'kidsplanet'),
					"desc" => esc_html__("How many related posts showed on single post page", 'kidsplanet'),
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"override" => "category,services_group,post,page",
					"std" => "2",
					"step" => 1,
					"min" => 2,
					"max" => 8,
					"type" => "spinner"),

		"post_related_columns" => array(
					"title" => esc_html__('Related posts columns',  'kidsplanet'),
					"desc" => esc_html__("How many columns used to show related posts on single post page. 1 - use scrolling to show all related posts", 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "2",
					"step" => 1,
					"min" => 1,
					"max" => 4,
					"type" => "spinner"),
		
		"post_related_sort" => array(
					"title" => esc_html__('Related posts sorted by', 'kidsplanet'),
					"desc" => esc_html__('Select the desired sorting method for related posts', 'kidsplanet'),
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "date",
					"options" => $THEMEREX_GLOBALS['options_params']['list_sorting'],
					"type" => "select"),
		
		"post_related_order" => array(
					"title" => esc_html__('Related posts order', 'kidsplanet'),
					"desc" => esc_html__('Select the desired ordering method for related posts', 'kidsplanet'),
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "desc",
					"options" => $THEMEREX_GLOBALS['options_params']['list_ordering'],
					"size" => "big",
					"type" => "switch"),
		
		"show_post_comments" => array(
					"title" => esc_html__('Show comments',  'kidsplanet'),
					"desc" => esc_html__("Show comments block on single post page", 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		
		// Blog -> Other parameters
		//-------------------------------------------------
		
		'blog_tab_other' => array(
					"title" => esc_html__('Other parameters', 'kidsplanet'),
					"icon" => "iconadmin-newspaper",
					"override" => "category,services_group,page",
					"type" => "tab"),
		
		"info_blog_other_1" => array(
					"title" => esc_html__('Other Blog parameters', 'kidsplanet'),
					"desc" => esc_html__('Select excluded categories, substitute parameters, etc.', 'kidsplanet'),
					"type" => "info"),
		
		"exclude_cats" => array(
					"title" => esc_html__('Exclude categories', 'kidsplanet'),
					"desc" => esc_html__('Select categories, which posts are exclude from blog page', 'kidsplanet'),
					"std" => "",
					"options" => $THEMEREX_GLOBALS['options_params']['list_categories'],
					"multiple" => true,
					"style" => "list",
					"type" => "select"),
		
		"blog_pagination" => array(
					"title" => esc_html__('Blog pagination', 'kidsplanet'),
					"desc" => esc_html__('Select type of the pagination on blog streampages', 'kidsplanet'),
					"std" => "pages",
					"override" => "category,services_group,page",
					"options" => array(
						'pages'    => esc_html__('Standard page numbers', 'kidsplanet'),
						'viewmore' => esc_html__('"View more" button', 'kidsplanet'),
						'infinite' => esc_html__('Infinite scroll', 'kidsplanet')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_pagination_style" => array(
					"title" => esc_html__('Blog pagination style', 'kidsplanet'),
					"desc" => esc_html__('Select pagination style for standard page numbers', 'kidsplanet'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_pagination' => array('pages')
					),
					"std" => "pages",
					"options" => array(
						'pages'  => esc_html__('Page numbers list', 'kidsplanet'),
						'slider' => esc_html__('Slider with page numbers', 'kidsplanet')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_counters" => array(
					"title" => esc_html__('Blog counters', 'kidsplanet'),
					"desc" => esc_html__('Select counters, displayed near the post title', 'kidsplanet'),
					"override" => "category,services_group,page",
					"std" => "comments",
					"options" => array(
						'views' => esc_html__('Views', 'kidsplanet'),
						'likes' => esc_html__('Likes', 'kidsplanet'),
						'rating' => esc_html__('Rating', 'kidsplanet'),
						'comments' => esc_html__('Comments', 'kidsplanet')
					),
					"dir" => "vertical",
					"multiple" => true,
					"type" => "checklist"),
		
		"close_category" => array(
					"title" => esc_html__("Post's category announce", 'kidsplanet'),
					"desc" => esc_html__('What category display in announce block (over posts thumb) - original or nearest parental', 'kidsplanet'),
					"override" => "category,services_group,page",
					"std" => "parental",
					"options" => array(
						'parental' => esc_html__('Nearest parental category', 'kidsplanet'),
						'original' => esc_html__("Original post's category", 'kidsplanet')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"show_date_after" => array(
					"title" => esc_html__('Show post date after', 'kidsplanet'),
					"desc" => esc_html__('Show post date after N days (before - show post age)', 'kidsplanet'),
					"override" => "category,services_group,page",
					"std" => "30",
					"mask" => "?99",
					"type" => "text"),
		
		
		
		
		
		//###############################
		//#### Reviews               #### 
		//###############################
		"partition_reviews" => array(
					"title" => esc_html__('Reviews', 'kidsplanet'),
					"icon" => "iconadmin-newspaper",
					"override" => "category,services_group,services_group",
					"type" => "partition"),
		
		"info_reviews_1" => array(
					"title" => esc_html__('Reviews criterias', 'kidsplanet'),
					"desc" => esc_html__('Set up list of reviews criterias. You can override it in any category.', 'kidsplanet'),
					"override" => "category,services_group,services_group",
					"type" => "info"),
		
		"show_reviews" => array(
					"title" => esc_html__('Show reviews block',  'kidsplanet'),
					"desc" => esc_html__("Show reviews block on single post page and average reviews rating after post's title in stream pages", 'kidsplanet'),
					"override" => "category,services_group,services_group",
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"reviews_max_level" => array(
					"title" => esc_html__('Max reviews level',  'kidsplanet'),
					"desc" => esc_html__("Maximum level for reviews marks", 'kidsplanet'),
					"std" => "5",
					"options" => array(
						'5'=>esc_html__('5 stars', 'kidsplanet'),
						'10'=>esc_html__('10 stars', 'kidsplanet'),
						'100'=>esc_html__('100%', 'kidsplanet')
					),
					"type" => "radio",
					),
		
		"reviews_style" => array(
					"title" => esc_html__('Show rating as',  'kidsplanet'),
					"desc" => esc_html__("Show rating marks as text or as stars/progress bars.", 'kidsplanet'),
					"std" => "stars",
					"options" => array(
						'text' => esc_html__('As text (for example: 7.5 / 10)', 'kidsplanet'),
						'stars' => esc_html__('As stars or bars', 'kidsplanet')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"reviews_criterias_levels" => array(
					"title" => esc_html__('Reviews Criterias Levels', 'kidsplanet'),
					"desc" => esc_html__('Words to mark criterials levels. Just write the word and press "Enter". Also you can arrange words.', 'kidsplanet'),
					"std" => esc_html__("bad,poor,normal,good,great", 'kidsplanet'),
					"type" => "tags"),
		
		"reviews_first" => array(
					"title" => esc_html__('Show first reviews',  'kidsplanet'),
					"desc" => esc_html__("What reviews will be displayed first: by author or by visitors. Also this type of reviews will display under post's title.", 'kidsplanet'),
					"std" => "author",
					"options" => array(
						'author' => esc_html__('By author', 'kidsplanet'),
						'users' => esc_html__('By visitors', 'kidsplanet')
						),
					"dir" => "horizontal",
					"type" => "radio"),
		
		"reviews_second" => array(
					"title" => esc_html__('Hide second reviews',  'kidsplanet'),
					"desc" => esc_html__("Do you want hide second reviews tab in widgets and single posts?", 'kidsplanet'),
					"std" => "show",
					"options" => $THEMEREX_GLOBALS['options_params']['list_show_hide'],
					"size" => "medium",
					"type" => "switch"),
		
		"reviews_can_vote" => array(
					"title" => esc_html__('What visitors can vote',  'kidsplanet'),
					"desc" => esc_html__("What visitors can vote: all or only registered", 'kidsplanet'),
					"std" => "all",
					"options" => array(
						'all'=>esc_html__('All visitors', 'kidsplanet'),
						'registered'=>esc_html__('Only registered', 'kidsplanet')
					),
					"dir" => "horizontal",
					"type" => "radio"),
		
		"reviews_criterias" => array(
					"title" => esc_html__('Reviews criterias',  'kidsplanet'),
					"desc" => esc_html__('Add default reviews criterias.',  'kidsplanet'),
					"override" => "category,services_group,services_group",
					"std" => "",
					"cloneable" => true,
					"type" => "text"),

		// Don't remove this parameter - it used in admin for store marks
		"reviews_marks" => array(
					"std" => "",
					"type" => "hidden"),
		





		//###############################
		//#### Media                #### 
		//###############################
		"partition_media" => array(
					"title" => esc_html__('Media', 'kidsplanet'),
					"icon" => "iconadmin-picture",
					"override" => "category,services_group,post,page",
					"type" => "partition"),
		
		"info_media_1" => array(
					"title" => esc_html__('Media settings', 'kidsplanet'),
					"desc" => esc_html__('Set up parameters to show images, galleries, audio and video posts', 'kidsplanet'),
					"override" => "category,services_group,services_group",
					"type" => "info"),
					
		"retina_ready" => array(
					"title" => esc_html__('Image dimensions', 'kidsplanet'),
					"desc" => esc_html__('What dimensions use for uploaded image: Original or "Retina ready" (twice enlarged)', 'kidsplanet'),
					"std" => "1",
					"size" => "medium",
					"options" => array(
						"1" => esc_html__("Original", 'kidsplanet'),
						"2" => esc_html__("Retina", 'kidsplanet')
					),
					"type" => "switch"),
		
		"substitute_gallery" => array(
					"title" => esc_html__('Substitute standard Wordpress gallery', 'kidsplanet'),
					"desc" => esc_html__('Substitute standard Wordpress gallery with our slider on the single pages', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"gallery_instead_image" => array(
					"title" => esc_html__('Show gallery instead featured image', 'kidsplanet'),
					"desc" => esc_html__('Show slider with gallery instead featured image on blog streampage and in the related posts section for the gallery posts', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"gallery_max_slides" => array(
					"title" => esc_html__('Max images number in the slider', 'kidsplanet'),
					"desc" => esc_html__('Maximum images number from gallery into slider', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'gallery_instead_image' => array('yes')
					),
					"std" => "5",
					"min" => 2,
					"max" => 10,
					"type" => "spinner"),
		
		"popup_engine" => array(
					"title" => esc_html__('Popup engine to zoom images', 'kidsplanet'),
					"desc" => esc_html__('Select engine to show popup windows with images and galleries', 'kidsplanet'),
					"std" => "magnific",
					"options" => $THEMEREX_GLOBALS['options_params']['list_popups'],
					"type" => "select"),
		
		"substitute_audio" => array(
					"title" => esc_html__('Substitute audio tags', 'kidsplanet'),
					"desc" => esc_html__('Substitute audio tag with source from soundcloud to embed player', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"substitute_video" => array(
					"title" => esc_html__('Substitute video tags', 'kidsplanet'),
					"desc" => esc_html__('Substitute video tags with embed players or leave video tags unchanged (if you use third party plugins for the video tags)', 'kidsplanet'),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"use_mediaelement" => array(
					"title" => esc_html__('Use Media Element script for audio and video tags', 'kidsplanet'),
					"desc" => esc_html__('Do you want use the Media Element script for all audio and video tags on your site or leave standard HTML5 behaviour?', 'kidsplanet'),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		
		
		//###############################
		//#### Socials               #### 
		//###############################
		"partition_socials" => array(
					"title" => esc_html__('Socials', 'kidsplanet'),
					"icon" => "iconadmin-users",
					"override" => "category,services_group,page",
					"type" => "partition"),
		
		"info_socials_1" => array(
					"title" => esc_html__('Social networks', 'kidsplanet'),
					"desc" => esc_html__("Social networks list for site footer and Social widget", 'kidsplanet'),
					"type" => "info"),
		
		"social_icons" => array(
					"title" => esc_html__('Social networks',  'kidsplanet'),
					"desc" => esc_html__('Select icon and write URL to your profile in desired social networks.',  'kidsplanet'),
					"std" => array(array('url'=>'', 'icon'=>'')),
					"cloneable" => true,
					"size" => "small",
					"style" => $socials_type,
					"options" => $socials_type=='images' ? $THEMEREX_GLOBALS['options_params']['list_socials'] : $THEMEREX_GLOBALS['options_params']['list_icons'],
					"type" => "socials"),
		
		"info_socials_2" => array(
			"title" => esc_html__('Share buttons', 'kidsplanet'),
			"desc" => wp_kses_data(  esc_html__("Add button's code for each social share network.",'kidsplanet')).'<br>'
					.wp_kses_data(  esc_html__("In share url you can use next macro:",'kidsplanet')).'<br>'
					.wp_kses_data(  sprintf(esc_html__("%s - share post (page) URL,",'kidsplanet'), '<b>{url}</b>')).'<br>'
					.wp_kses_data(  sprintf(esc_html__("%s - post title,",'kidsplanet'), '<b>{title}</b>')).'<br>'
					.wp_kses_data(  sprintf(esc_html__("%s - post image,",'kidsplanet'), '<b>{image}</b>')).'<br>'
					.wp_kses_data(  sprintf(esc_html__("%s - post description (if supported)",'kidsplanet'), '<b>{descr}</b>')).'<br>'
					.wp_kses_data(  esc_html__("For example:",'kidsplanet')).'<br>'
					.wp_kses_data(  sprintf(esc_html__("%s share string: %s",'kidsplanet'), '<b>Facebook</b>','<em>http://www.facebook.com/sharer.php?u={link}&amp;t={title}</em>' )).'<br>'
					.wp_kses_data(  sprintf(esc_html__("%s share string: %s",'kidsplanet'), '<b>Delicious</b>','<em>http://delicious.com/save?url={link}&amp;title={title}&amp;note={descr}</em>' )),
		"override" => "category,services_group,page,custom",
		"type" => "info"),
		
		"show_share" => array(
					"title" => esc_html__('Show social share buttons',  'kidsplanet'),
					"desc" => esc_html__("Show social share buttons block", 'kidsplanet'),
					"override" => "category,services_group,page",
					"std" => "horizontal",
					"options" => array(
						'hide'		=> esc_html__('Hide', 'kidsplanet'),
						'vertical'	=> esc_html__('Vertical', 'kidsplanet'),
						'horizontal'=> esc_html__('Horizontal', 'kidsplanet')
					),
					"type" => "checklist"),

		"show_share_counters" => array(
					"title" => esc_html__('Show share counters',  'kidsplanet'),
					"desc" => esc_html__("Show share counters after social buttons", 'kidsplanet'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"share_caption" => array(
					"title" => esc_html__('Share block caption',  'kidsplanet'),
					"desc" => esc_html__('Caption for the block with social share buttons',  'kidsplanet'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => esc_html__('Share:', 'kidsplanet'),
					"type" => "text"),
		
		"share_buttons" => array(
					"title" => esc_html__('Share buttons',  'kidsplanet'),
					"desc" => wp_kses_data(__('Select icon and write share URL for desired social networks.<br><b>Important!</b> If you leave text field empty - internal theme link will be used (if present).',  'kidsplanet')),
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => array(array('url'=>'', 'icon'=>'')),
					"cloneable" => true,
					"size" => "small",
					"style" => $socials_type,
					"options" => $socials_type=='images' ? $THEMEREX_GLOBALS['options_params']['list_socials'] : $THEMEREX_GLOBALS['options_params']['list_icons'],
					"type" => "socials"),
		
		
		"info_socials_3" => array(
			"title" => esc_html__('Twitter API keys', 'kidsplanet'),
			"desc" => esc_html__("Put to this section Twitter API 1.1 keys.",'kidsplanet').'<br>'
			.sprintf( esc_html__("You can take them after registration your application in %shttps://apps.twitter.com/%s",'kidsplanet'), '<strong>', '</strong>'),
			"type" => "info"),
		
		"twitter_username" => array(
					"title" => esc_html__('Twitter username',  'kidsplanet'),
					"desc" => esc_html__('Your login (username) in Twitter',  'kidsplanet'),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_consumer_key" => array(
					"title" => esc_html__('Consumer Key',  'kidsplanet'),
					"desc" => esc_html__('Twitter API Consumer key',  'kidsplanet'),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_consumer_secret" => array(
					"title" => esc_html__('Consumer Secret',  'kidsplanet'),
					"desc" => esc_html__('Twitter API Consumer secret',  'kidsplanet'),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_token_key" => array(
					"title" => esc_html__('Token Key',  'kidsplanet'),
					"desc" => esc_html__('Twitter API Token key',  'kidsplanet'),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_token_secret" => array(
					"title" => esc_html__('Token Secret',  'kidsplanet'),
					"desc" => esc_html__('Twitter API Token secret',  'kidsplanet'),
					"divider" => false,
					"std" => "",
					"type" => "text"),

            "info_socials_4" => array(
                "title" => esc_html__('Login via Social network', 'kidsplanet'),
                "desc" => esc_html__("Settings for the Login via Social networks", 'kidsplanet'),
                "type" => "info"),

            "social_login" => array(
                "title" => esc_html__('Social plugin shortcode',  'kidsplanet'),
                "desc" => esc_html__('Social plugin shortcode like [plugin_shortcode]',  'kidsplanet'),
                "divider" => false,
                "std" => "",
                "type" => "text"),
		
		
		
		//###############################
		//#### Contact info          #### 
		//###############################
		"partition_contacts" => array(
					"title" => esc_html__('Contact info', 'kidsplanet'),
					"icon" => "iconadmin-mail",
					"type" => "partition"),
		
		"info_contact_1" => array(
					"title" => esc_html__('Contact information', 'kidsplanet'),
					"desc" => esc_html__('Company address, phones and e-mail', 'kidsplanet'),
					"type" => "info"),
		
		"contact_info" => array(
					"title" => esc_html__('Contacts in the header', 'kidsplanet'),
					"desc" => esc_html__('String with contact info in the left side of the site header', 'kidsplanet'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"contact_open_hours" => array(
					"title" => esc_html__('Open hours in the header', 'kidsplanet'),
					"desc" => esc_html__('String with open hours in the site header', 'kidsplanet'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-clock'),
					"type" => "text"),
		
		"contact_email" => array(
					"title" => esc_html__('Contact form email', 'kidsplanet'),
					"desc" => esc_html__('E-mail for send contact form and user registration data', 'kidsplanet'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-mail'),
					"type" => "text"),
		
		"contact_address_1" => array(
					"title" => esc_html__('Company address (part 1)', 'kidsplanet'),
					"desc" => esc_html__('Company country, post code and city', 'kidsplanet'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"contact_address_2" => array(
					"title" => esc_html__('Company address (part 2)', 'kidsplanet'),
					"desc" => esc_html__('Street and house number', 'kidsplanet'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"contact_phone" => array(
					"title" => esc_html__('Phone', 'kidsplanet'),
					"desc" => esc_html__('Phone number', 'kidsplanet'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-phone'),
					"type" => "text"),
		
		"contact_fax" => array(
					"title" => esc_html__('Fax', 'kidsplanet'),
					"desc" => esc_html__('Fax number', 'kidsplanet'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-phone'),
					"type" => "text"),
		
		"info_contact_2" => array(
					"title" => esc_html__('Contact and Comments form', 'kidsplanet'),
					"desc" => esc_html__('Maximum length of the messages in the contact form shortcode and in the comments form', 'kidsplanet'),
					"type" => "info"),
		
		"message_maxlength_contacts" => array(
					"title" => esc_html__('Contact form message', 'kidsplanet'),
					"desc" => esc_html__("Message's maxlength in the contact form shortcode", 'kidsplanet'),
					"std" => "1000",
					"min" => 0,
					"max" => 10000,
					"step" => 100,
					"type" => "spinner"),
		
		"message_maxlength_comments" => array(
					"title" => esc_html__('Comments form message', 'kidsplanet'),
					"desc" => esc_html__("Message's maxlength in the comments form", 'kidsplanet'),
					"std" => "1000",
					"min" => 0,
					"max" => 10000,
					"step" => 100,
					"type" => "spinner"),
		
		"info_contact_3" => array(
					"title" => esc_html__('Default mail function', 'kidsplanet'),
					"desc" => esc_html__('What function you want to use for sending mail: the built-in Wordpress or standard PHP function? Attention! Some plugins may not work with one of them and you always have the ability to switch to alternative.', 'kidsplanet'),
					"type" => "info"),
		
		"mail_function" => array(
					"title" => esc_html__("Mail function", 'kidsplanet'),
					"desc" => esc_html__("What function you want to use for sending mail?", 'kidsplanet'),
					"std" => "wp_mail",
					"size" => "medium",
					"options" => array(
						'wp_mail' => esc_html__('WP mail', 'kidsplanet'),
						'mail' => esc_html__('PHP mail', 'kidsplanet')
					),
					"type" => "switch"),
		
		
		
		
		
		
		
		//###############################
		//#### Search parameters     #### 
		//###############################
		"partition_search" => array(
					"title" => esc_html__('Search', 'kidsplanet'),
					"icon" => "iconadmin-search",
					"type" => "partition"),
		
		"info_search_1" => array(
					"title" => esc_html__('Search parameters', 'kidsplanet'),
					"desc" => esc_html__('Enable/disable AJAX search and output settings for it', 'kidsplanet'),
					"type" => "info"),
		
		"show_search" => array(
					"title" => esc_html__('Show search field', 'kidsplanet'),
					"desc" => esc_html__('Show search field in the top area and side menus', 'kidsplanet'),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"use_ajax_search" => array(
					"title" => esc_html__('Enable AJAX search', 'kidsplanet'),
					"desc" => esc_html__('Use incremental AJAX search for the search field in top of page', 'kidsplanet'),
					"dependency" => array(
						'show_search' => array('yes')
					),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"ajax_search_min_length" => array(
					"title" => esc_html__('Min search string length',  'kidsplanet'),
					"desc" => esc_html__('The minimum length of the search string',  'kidsplanet'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"std" => 4,
					"min" => 3,
					"type" => "spinner"),
		
		"ajax_search_delay" => array(
					"title" => esc_html__('Delay before search (in ms)',  'kidsplanet'),
					"desc" => esc_html__('How much time (in milliseconds, 1000 ms = 1 second) must pass after the last character before the start search',  'kidsplanet'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"std" => 500,
					"min" => 300,
					"max" => 1000,
					"step" => 100,
					"type" => "spinner"),
		
		"ajax_search_types" => array(
					"title" => esc_html__('Search area', 'kidsplanet'),
					"desc" => esc_html__('Select post types, what will be include in search results. If not selected - use all types.', 'kidsplanet'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"std" => "",
					"options" => $THEMEREX_GLOBALS['options_params']['list_posts_types'],
					"multiple" => true,
					"style" => "list",
					"type" => "select"),
		
		"ajax_search_posts_count" => array(
					"title" => esc_html__('Posts number in output',  'kidsplanet'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => esc_html__('Number of the posts to show in search results',  'kidsplanet'),
					"std" => 5,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),
		
		"ajax_search_posts_image" => array(
					"title" => esc_html__("Show post's image", 'kidsplanet'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => esc_html__("Show post's thumbnail in the search results", 'kidsplanet'),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"ajax_search_posts_date" => array(
					"title" => esc_html__("Show post's date", 'kidsplanet'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => esc_html__("Show post's publish date in the search results", 'kidsplanet'),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"ajax_search_posts_author" => array(
					"title" => esc_html__("Show post's author", 'kidsplanet'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => esc_html__("Show post's author in the search results", 'kidsplanet'),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"ajax_search_posts_counters" => array(
					"title" => esc_html__("Show post's counters", 'kidsplanet'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => esc_html__("Show post's counters (views, comments, likes) in the search results", 'kidsplanet'),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		
		
		
		//###############################
		//#### Service               #### 
		//###############################
		
		"partition_service" => array(
					"title" => esc_html__('Service', 'kidsplanet'),
					"icon" => "iconadmin-wrench",
					"type" => "partition"),
		
		"info_service_1" => array(
					"title" => esc_html__('Theme functionality', 'kidsplanet'),
					"desc" => esc_html__('Basic theme functionality settings', 'kidsplanet'),
					"type" => "info"),
		
		"use_ajax_views_counter" => array(
					"title" => esc_html__('Use AJAX post views counter', 'kidsplanet'),
					"desc" => esc_html__('Use javascript for post views count (if site work under the caching plugin) or increment views count in single page template', 'kidsplanet'),
					"std" => "no",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"allow_editor" => array(
					"title" => esc_html__('Frontend editor',  'kidsplanet'),
					"desc" => esc_html__("Allow authors to edit their posts in frontend area)", 'kidsplanet'),
					"std" => "no",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"admin_add_filters" => array(
			"title" => esc_html__('Additional filters in the admin panel', 'kidsplanet'),
			"desc" => wp_kses_data( esc_html__('Show additional filters (on post formats, tags and categories) in admin panel page "Posts".', 'kidsplanet')).  '<br>'.
			wp_kses_data( esc_html__('Attention! If you have more than 2.000-3.000 posts, enabling this option may cause slow load of the "Posts" page! If you encounter such slow down, simply open Appearance - Theme Options - Service and set "No" for this option.', 'kidsplanet')),
			"std" => "yes",
			"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
			"type" => "switch"),

		"show_overriden_taxonomies" => array(
					"title" => esc_html__('Show overriden options for taxonomies', 'kidsplanet'),
					"desc" => esc_html__('Show extra column in categories list, where changed (overriden) theme options are displayed.', 'kidsplanet'),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"show_overriden_posts" => array(
					"title" => esc_html__('Show overriden options for posts and pages', 'kidsplanet'),
					"desc" => esc_html__('Show extra column in posts and pages list, where changed (overriden) theme options are displayed.', 'kidsplanet'),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"admin_dummy_data" => array(
					"title" => esc_html__('Enable Dummy Data Installer', 'kidsplanet'),
					"desc" => esc_html__('Show "Install Dummy Data" in the menu "Appearance". <b>Attention!</b> When you install dummy data all content of your site will be replaced!', 'kidsplanet'),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"admin_dummy_timeout" => array(
					"title" => esc_html__('Dummy Data Installer Timeout',  'kidsplanet'),
					"desc" => esc_html__('Web-servers set the time limit for the execution of php-scripts. By default, this is 30 sec. Therefore, the import process will be split into parts. Upon completion of each part - the import will resume automatically! The import process will try to increase this limit to the time, specified in this field.',  'kidsplanet'),
					"std" => 1200,
					"min" => 30,
					"max" => 1800,
					"type" => "spinner"),
		
		"admin_emailer" => array(
					"title" => esc_html__('Enable Emailer in the admin panel', 'kidsplanet'),
					"desc" => esc_html__('Allow to use ThemeREX Emailer for mass-volume e-mail distribution and management of mailing lists in "Tools - Emailer"', 'kidsplanet'),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"debug_mode" => array(
					"title" => esc_html__('Debug mode', 'kidsplanet'),
					"desc" => wp_kses_data( sprintf(esc_html__('In debug mode we are using unpacked scripts and styles, else - using minified scripts and styles (if present). %sAttention!%s If you have modified the source code in the js or css files, regardless of this option will be used latest (modified) version stylesheets and scripts. You can re-create minified versions of files using on-line services (for example http://yui.2clics.net/) or utility <b>yuicompressor-x.y.z.jar</b>', 'kidsplanet'), '<b>', '</b>')),
					"std" => "no",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

        "info_service_2" => array(
                "title" => esc_html__('API Keys', 'kidsplanet'),
                "desc" => esc_html__('API Keys for some Web services', 'kidsplanet'),
                "type" => "info"),
        'api_google' => array(
                "title" => esc_html__('Google API Key', 'kidsplanet'),
                "desc" => esc_html__("Insert Google API Key for browsers into the field above to generate Google Maps", 'kidsplanet'),
                "std" => "",
                "type" => "text"),
            )
		);
	}
}


// Update all temporary vars (start with $themerex_) in the Theme Options with actual lists
if ( !function_exists( 'themerex_options_settings_theme_setup2' ) ) {
	add_action( 'themerex_action_after_init_theme', 'themerex_options_settings_theme_setup2', 1 );
	function themerex_options_settings_theme_setup2() {
			global $THEMEREX_GLOBALS;
			// Replace arrays with actual parameters
			$lists = array();
			if (count($THEMEREX_GLOBALS['options']) > 0) {
				foreach ($THEMEREX_GLOBALS['options'] as $k=>$v) {
					if (isset($v['options']) && is_array($v['options']) && count($v['options']) > 0) {
						foreach ($v['options'] as $k1=>$v1) {
							if (themerex_substr($k1, 0, 10) == '$themerex_' || themerex_substr($v1, 0, 10) == '$themerex_') {
								$list_func = themerex_substr(themerex_substr($k1, 0, 10) == '$themerex_' ? $k1 : $v1, 1);
								unset($THEMEREX_GLOBALS['options'][$k]['options'][$k1]);
								if (isset($lists[$list_func]))
									$THEMEREX_GLOBALS['options'][$k]['options'] = themerex_array_merge($THEMEREX_GLOBALS['options'][$k]['options'], $lists[$list_func]);
								else {
									if (function_exists($list_func)) {
										$THEMEREX_GLOBALS['options'][$k]['options'] = $lists[$list_func] = themerex_array_merge($THEMEREX_GLOBALS['options'][$k]['options'], $list_func == 'themerex_get_list_menus' ? $list_func(true) : $list_func());
								   	} else
								   		echo sprintf(esc_html__('Wrong function name %s in the theme options array', 'kidsplanet'), $list_func);
								}
							}
						}
					}
				}
			}
	}
}

// Reset old Theme Options while theme first run
if ( !function_exists( 'themerex_options_reset' ) ) {
    function themerex_options_reset($clear=true) {
        $theme_slug = str_replace(' ', '_', trim(themerex_strtolower(get_stylesheet())));
        $option_name = themerex_get_global('options_prefix') . '_' . trim($theme_slug) . '_options_reset';
        // Prepare demo data
        if (is_dir(THEMEREX_THEME_PATH . 'demo/')) {
            $demo_url = THEMEREX_THEME_PATH . 'demo/';
        } else {
            $demo_url = esc_url(themerex_get_protocol() . '://demofiles.ancorathemes.com/kidsplanet/'); // Demo-site domain
        }
        $txt = themerex_fgc($demo_url . 'default/templates_options.txt');

        if (get_option($option_name, false) === false) {
            if ($clear) {
                // Remove Theme Options from WP Options
                global $wpdb;
                $wpdb->query( $wpdb->prepare("DELETE FROM {$wpdb->options} WHERE option_name LIKE %s","themerex_%"
                )
                );
                // Add Templates Options

                if ($txt) {
                    $data = themerex_unserialize($txt);
                    // Replace upload url in options
                    if (is_array($data) && count($data) > 0) {
                        foreach ($data as $k => $v) {
                            if (is_array($v) && count($v) > 0) {
                                foreach ($v as $k1 => $v1) {
                                    $v[$k1] = themerex_replace_uploads_url(themerex_replace_uploads_url($v1, 'uploads'), 'imports');
                                }
                            }
                            add_option($k, $v, '', 'yes');
                        }
                    }
                }
            }
            add_option($option_name, 1, '', 'yes');
        }
    }
}
?>