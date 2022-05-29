<?php

// Width and height params
if ( !function_exists( 'themerex_vc_width' ) ) {
	function themerex_vc_width($w='') {
		return array(
			"param_name" => "width",
			"heading" => __("Width", 'trx_utils'),
			"description" => __("Width (in pixels or percent) of the current element", 'trx_utils'),
			"group" => __('Size &amp; Margins', 'trx_utils'),
			"value" => $w,
			"type" => "textfield"
		);
	}
}
if ( !function_exists( 'themerex_vc_height' ) ) {
	function themerex_vc_height($h='') {
		return array(
			"param_name" => "height",
			"heading" => __("Height", 'trx_utils'),
			"description" => __("Height (only in pixels) of the current element", 'trx_utils'),
			"group" => __('Size &amp; Margins', 'trx_utils'),
			"value" => $h,
			"type" => "textfield"
		);
	}
}

// Load scripts and styles for VC support
if ( !function_exists( 'themerex_shortcodes_vc_scripts_admin' ) ) {
	//add_action( 'admin_enqueue_scripts', 'themerex_shortcodes_vc_scripts_admin' );
	function themerex_shortcodes_vc_scripts_admin() {
		// Include CSS 
		wp_enqueue_style ( 'shortcodes_vc-style', trx_utils_get_file_url('shortcodes/shortcodes_vc_admin.css'), array(), null );
		// Include JS
		wp_enqueue_script( 'shortcodes_vc-script', trx_utils_get_file_url('shortcodes/shortcodes_vc_admin.js'), array(), null, true );
	}
}

// Load scripts and styles for VC support
if ( !function_exists( 'themerex_shortcodes_vc_scripts_front' ) ) {
	//add_action( 'wp_enqueue_scripts', 'themerex_shortcodes_vc_scripts_front' );
	function themerex_shortcodes_vc_scripts_front() {
		if (themerex_vc_is_frontend()) {
			// Include CSS 
			wp_enqueue_style ( 'shortcodes_vc-style', trx_utils_get_file_url('shortcodes/shortcodes_vc_front.css'), array(), null );
            // Include JS
            wp_enqueue_script( 'shortcodes_vc-script', trx_utils_get_file_url('shortcodes/shortcodes_vc_front.js'), array('jquery'), null, true );
		}
	}
}

// Add init script into shortcodes output in VC frontend editor
if ( !function_exists( 'themerex_shortcodes_vc_add_init_script' ) ) {
	//add_filter('themerex_shortcode_output', 'themerex_shortcodes_vc_add_init_script', 10, 4);
	function themerex_shortcodes_vc_add_init_script($output, $tag='', $atts=array(), $content='') {
		if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') && (isset($_POST['action']) && $_POST['action']=='vc_load_shortcode')
				&& ( isset($_POST['shortcodes'][0]['tag']) && $_POST['shortcodes'][0]['tag']==$tag )
		) {
			if (themerex_strpos($output, 'themerex_vc_init_shortcodes')===false) {
				$id = "themerex_vc_init_shortcodes_".str_replace('.', '', mt_rand());
				$output .= '
					<script id="'.esc_attr($id).'">
						try {
							themerex_init_post_formats();
							themerex_init_shortcodes(jQuery("body").eq(0));
							themerex_scroll_actions();
						} catch (e) { };
					</script>
				';
			}
		}
		return $output;
	}
}

// Prevent simultaneous editing of posts for Gutenberg and other PageBuilders (VC, Elementor)
if ( ! function_exists( 'trx_utils_gutenberg_disable_cpt' ) ) {
    add_action( 'current_screen', 'trx_utils_gutenberg_disable_cpt' );
    function trx_utils_gutenberg_disable_cpt() {
        $safe_pb = array('vc');
        if ( !empty($safe_pb) && function_exists( 'the_gutenberg_project' ) && function_exists( 'register_block_type' ) ) {
            $current_post_type = get_current_screen()->post_type;
            $disable = false;
            if ( !$disable && in_array('vc', $safe_pb) && function_exists('vc_editor_post_types') ) {
                $post_types = vc_editor_post_types();
                $disable = is_array($post_types) && in_array($current_post_type, $post_types);
            }
            if ( $disable ) {
                remove_filter( 'replace_editor', 'gutenberg_init' );
                remove_action( 'load-post.php', 'gutenberg_intercept_edit_post' );
                remove_action( 'load-post-new.php', 'gutenberg_intercept_post_new' );
                remove_action( 'admin_init', 'gutenberg_add_edit_link_filters' );
                remove_filter( 'admin_url', 'gutenberg_modify_add_new_button_url' );
                remove_action( 'admin_print_scripts-edit.php', 'gutenberg_replace_default_add_new_button' );
                remove_action( 'admin_enqueue_scripts', 'gutenberg_editor_scripts_and_styles' );
                remove_filter( 'screen_options_show_screen', '__return_false' );
            }
        }
    }
}


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'themerex_shortcodes_vc_theme_setup' ) ) {
	//if ( themerex_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'themerex_action_before_init_theme', 'themerex_shortcodes_vc_theme_setup', 20 );
	else
		add_action( 'themerex_action_after_init_theme', 'themerex_shortcodes_vc_theme_setup' );
	function themerex_shortcodes_vc_theme_setup() {
		if (themerex_shortcodes_is_used()) {
			// Set VC as main editor for the theme
			vc_set_as_theme( true );
			
			// Enable VC on follow post types
			vc_set_default_editor_post_types( array('page', 'team') );
			
			// Disable frontend editor
			//vc_disable_frontend();

			// Load scripts and styles for VC support
			add_action( 'wp_enqueue_scripts',		'themerex_shortcodes_vc_scripts_front');
			add_action( 'admin_enqueue_scripts',	'themerex_shortcodes_vc_scripts_admin' );

			// Add init script into shortcodes output in VC frontend editor
			add_filter('themerex_shortcode_output', 'themerex_shortcodes_vc_add_init_script', 10, 4);

			// Remove standard VC shortcodes
			vc_remove_element("vc_button");
			vc_remove_element("vc_posts_slider");
			vc_remove_element("vc_gmaps");
			vc_remove_element("vc_teaser_grid");
			vc_remove_element("vc_progress_bar");
			vc_remove_element("vc_facebook");
			vc_remove_element("vc_tweetmeme");
			vc_remove_element("vc_googleplus");
			vc_remove_element("vc_facebook");
			vc_remove_element("vc_pinterest");
			vc_remove_element("vc_message");
			vc_remove_element("vc_posts_grid");
			vc_remove_element("vc_carousel");
			vc_remove_element("vc_flickr");
			vc_remove_element("vc_tour");
			vc_remove_element("vc_separator");
			vc_remove_element("vc_single_image");
			vc_remove_element("vc_cta_button");
//			vc_remove_element("vc_accordion");
//			vc_remove_element("vc_accordion_tab");
			vc_remove_element("vc_toggle");
			vc_remove_element("vc_tabs");
			vc_remove_element("vc_tab");
			vc_remove_element("vc_images_carousel");
			
			// Remove standard WP widgets
			vc_remove_element("vc_wp_archives");
			vc_remove_element("vc_wp_calendar");
			vc_remove_element("vc_wp_categories");
			vc_remove_element("vc_wp_custommenu");
			vc_remove_element("vc_wp_links");
			vc_remove_element("vc_wp_meta");
			vc_remove_element("vc_wp_pages");
			vc_remove_element("vc_wp_posts");
			vc_remove_element("vc_wp_recentcomments");
			vc_remove_element("vc_wp_rss");
			vc_remove_element("vc_wp_search");
			vc_remove_element("vc_wp_tagcloud");
			vc_remove_element("vc_wp_text");
			
			global $THEMEREX_GLOBALS;
			
			$THEMEREX_GLOBALS['vc_params'] = array(
				
				// Common arrays and strings
				'category' => __("ThemeREX shortcodes", 'trx_utils'),
			
				// Current element id
				'id' => array(
					"param_name" => "id",
					"heading" => __("Element ID", 'trx_utils'),
					"description" => __("ID for current element", 'trx_utils'),
					"group" => __('ID &amp; Class', 'trx_utils'),
					"value" => "",
					"type" => "textfield"
				),
			
				// Current element class
				'class' => array(
					"param_name" => "class",
					"heading" => __("Element CSS class", 'trx_utils'),
					"description" => __("CSS class for current element", 'trx_utils'),
					"group" => __('ID &amp; Class', 'trx_utils'),
					"value" => "",
					"type" => "textfield"
				),

				// Current element animation
				'animation' => array(
					"param_name" => "animation",
					"heading" => __("Animation", 'trx_utils'),
					"description" => __("Select animation while object enter in the visible area of page", 'trx_utils'),
					"group" => __('ID &amp; Class', 'trx_utils'),
					"class" => "",
					"value" => array_flip($THEMEREX_GLOBALS['sc_params']['animations']),
					"type" => "dropdown"
				),
			
				// Current element style
				'css' => array(
					"param_name" => "css",
					"heading" => __("CSS styles", 'trx_utils'),
					"description" => __("Any additional CSS rules (if need)", 'trx_utils'),
					"group" => __('ID &amp; Class', 'trx_utils'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
			
				// Margins params
				'margin_top' => array(
					"param_name" => "top",
					"heading" => __("Top margin", 'trx_utils'),
					"description" => __("Top margin (in pixels).", 'trx_utils'),
					"group" => __('Size &amp; Margins', 'trx_utils'),
					"value" => "",
					"type" => "textfield"
				),
			
				'margin_bottom' => array(
					"param_name" => "bottom",
					"heading" => __("Bottom margin", 'trx_utils'),
					"description" => __("Bottom margin (in pixels).", 'trx_utils'),
					"group" => __('Size &amp; Margins', 'trx_utils'),
					"value" => "",
					"type" => "textfield"
				),
			
				'margin_left' => array(
					"param_name" => "left",
					"heading" => __("Left margin", 'trx_utils'),
					"description" => __("Left margin (in pixels).", 'trx_utils'),
					"group" => __('Size &amp; Margins', 'trx_utils'),
					"value" => "",
					"type" => "textfield"
				),
				
				'margin_right' => array(
					"param_name" => "right",
					"heading" => __("Right margin", 'trx_utils'),
					"description" => __("Right margin (in pixels).", 'trx_utils'),
					"group" => __('Size &amp; Margins', 'trx_utils'),
					"value" => "",
					"type" => "textfield"
				)
			);
	
	
	
			// Accordion
			//-------------------------------------------------------------------------------------
			vc_map( array(
				"base" => "trx_accordion",
				"name" => __("Accordion", 'trx_utils'),
				"description" => __("Accordion items", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				'icon' => 'icon_trx_accordion',
				"class" => "trx_sc_collection trx_sc_accordion",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_accordion_item'),	// Use only|except attributes to limit child shortcodes (separate multiple values with comma)
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Accordion style", 'trx_utils'),
						"description" => __("Select style for display accordion", 'trx_utils'),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip(themerex_get_list_styles(1, 2)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "counter",
						"heading" => __("Counter", 'trx_utils'),
						"description" => __("Display counter before each accordion title", 'trx_utils'),
						"class" => "",
						"value" => array("Add item numbers before each element" => "on" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "initial",
						"heading" => __("Initially opened item", 'trx_utils'),
						"description" => __("Number of initially opened item", 'trx_utils'),
						"class" => "",
						"value" => 1,
						"type" => "textfield"
					),
					array(
						"param_name" => "icon_closed",
						"heading" => __("Icon while closed", 'trx_utils'),
						"description" => __("Select icon for the closed accordion item from Fontello icons set", 'trx_utils'),
						"class" => "",
						"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_opened",
						"heading" => __("Icon while opened", 'trx_utils'),
						"description" => __("Select icon for the opened accordion item from Fontello icons set", 'trx_utils'),
						"class" => "",
						"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css'],
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				),
				'default_content' => '
					[trx_accordion_item title="' . __( 'Item 1 title', 'trx_utils') . '"][/trx_accordion_item]
					[trx_accordion_item title="' . __( 'Item 2 title', 'trx_utils') . '"][/trx_accordion_item]
				',
				"custom_markup" => '
					<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
						%content%
					</div>
					<div class="tab_controls">
						<button class="add_tab" title="'.__("Add item", 'trx_utils').'">'.__("Add item", 'trx_utils').'</button>
					</div>
				',
				'js_view' => 'VcTrxAccordionView'
			) );
			
			
			vc_map( array(
				"base" => "trx_accordion_item",
				"name" => __("Accordion item", 'trx_utils'),
				"description" => __("Inner accordion item", 'trx_utils'),
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_accordion_item',
				"as_child" => array('only' => 'trx_accordion'), 	// Use only|except attributes to limit parent (separate multiple values with comma)
				"as_parent" => array('except' => 'trx_accordion'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => __("Title", 'trx_utils'),
						"description" => __("Title for current accordion item", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon_closed",
						"heading" => __("Icon while closed", 'trx_utils'),
						"description" => __("Select icon for the closed accordion item from Fontello icons set", 'trx_utils'),
						"class" => "",
						"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_opened",
						"heading" => __("Icon while opened", 'trx_utils'),
						"description" => __("Select icon for the opened accordion item from Fontello icons set", 'trx_utils'),
						"class" => "",
						"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['css']
				),
			  'js_view' => 'VcTrxAccordionTabView'
			) );

			class WPBakeryShortCode_Trx_Accordion extends THEMEREX_VC_ShortCodeAccordion {}
			class WPBakeryShortCode_Trx_Accordion_Item extends THEMEREX_VC_ShortCodeAccordionItem {}
			
			
			
			
			
			
			// Anchor
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_anchor",
				"name" => __("Anchor", 'trx_utils'),
				"description" => __("Insert anchor for the TOC (table of content)", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				'icon' => 'icon_trx_anchor',
				"class" => "trx_sc_single trx_sc_anchor",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "icon",
						"heading" => __("Anchor's icon", 'trx_utils'),
						"description" => __("Select icon for the anchor from Fontello icons set", 'trx_utils'),
						"class" => "",
						"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => __("Short title", 'trx_utils'),
						"description" => __("Short title of the anchor (for the table of content)", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => __("Long description", 'trx_utils'),
						"description" => __("Description for the popup (then hover on the icon). You can use:<br>'{{' and '}}' - to make the text italic,<br>'((' and '))' - to make the text bold,<br>'||' - to insert line break", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "url",
						"heading" => __("External URL", 'trx_utils'),
						"description" => __("External URL for this TOC item", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "separator",
						"heading" => __("Add separator", 'trx_utils'),
						"description" => __("Add separator under item in the TOC", 'trx_utils'),
						"class" => "",
						"value" => array("Add separator" => "yes" ),
						"type" => "checkbox"
					),
					$THEMEREX_GLOBALS['vc_params']['id']
				),
			) );
			
			class WPBakeryShortCode_Trx_Anchor extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			// Audio
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_audio",
				"name" => __("Audio", 'trx_utils'),
				"description" => __("Insert audio player", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				'icon' => 'icon_trx_audio',
				"class" => "trx_sc_single trx_sc_audio",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "url",
						"heading" => __("URL for audio file", 'trx_utils'),
						"description" => __("Put here URL for audio file", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "image",
						"heading" => __("Cover image", 'trx_utils'),
						"description" => __("Select or upload image or write URL from other site for audio cover", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "title",
						"heading" => __("Title", 'trx_utils'),
						"description" => __("Title of the audio file", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "author",
						"heading" => __("Author", 'trx_utils'),
						"description" => __("Author of the audio file", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "controls",
						"heading" => __("Controls", 'trx_utils'),
						"description" => __("Show/hide controls", 'trx_utils'),
						"class" => "",
						"value" => array("Hide controls" => "hide" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "autoplay",
						"heading" => __("Autoplay", 'trx_utils'),
						"description" => __("Autoplay audio on page load", 'trx_utils'),
						"class" => "",
						"value" => array("Autoplay" => "on" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", 'trx_utils'),
						"description" => __("Select block alignment", 'trx_utils'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css'],
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				),
			) );
			
			class WPBakeryShortCode_Trx_Audio extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Block
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_block",
				"name" => __("Block container", 'trx_utils'),
				"description" => __("Container for any block ([section] analog - to enable nesting)", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				'icon' => 'icon_trx_block',
				"class" => "trx_sc_collection trx_sc_block",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "dedicated",
						"heading" => __("Dedicated", 'trx_utils'),
						"description" => __("Use this block as dedicated content - show it before post title on single page", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array(__('Use as dedicated content', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", 'trx_utils'),
						"description" => __("Select block alignment", 'trx_utils'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => __("Columns emulation", 'trx_utils'),
						"description" => __("Select width for columns emulation", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['columns']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "pan",
						"heading" => __("Use pan effect", 'trx_utils'),
						"description" => __("Use pan effect to show section content", 'trx_utils'),
						"group" => __('Scroll', 'trx_utils'),
						"class" => "",
						"value" => array(__('Content scroller', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scroll",
						"heading" => __("Use scroller", 'trx_utils'),
						"description" => __("Use scroller to show section content", 'trx_utils'),
						"group" => __('Scroll', 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array(__('Content scroller', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scroll_dir",
						"heading" => __("Scroll direction", 'trx_utils'),
						"description" => __("Scroll direction (if Use scroller = yes)", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"group" => __('Scroll', 'trx_utils'),
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['dir']),
						'dependency' => array(
							'element' => 'scroll',
							'not_empty' => true
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "scroll_controls",
						"heading" => __("Scroll controls", 'trx_utils'),
						"description" => __("Show scroll controls (if Use scroller = yes)", 'trx_utils'),
						"class" => "",
						"group" => __('Scroll', 'trx_utils'),
						'dependency' => array(
							'element' => 'scroll',
							'not_empty' => true
						),
						"value" => array(__('Show scroll controls', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scheme",
						"heading" => __("Color scheme", 'trx_utils'),
						"description" => __("Select color scheme for this block", 'trx_utils'),
						"group" => __('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['schemes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Fore color", 'trx_utils'),
						"description" => __("Any color for objects in this section", 'trx_utils'),
						"group" => __('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", 'trx_utils'),
						"description" => __("Any background color for this section", 'trx_utils'),
						"group" => __('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => __("Background image URL", 'trx_utils'),
						"description" => __("Select background image from library for this section", 'trx_utils'),
						"group" => __('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => __("Overlay", 'trx_utils'),
						"description" => __("Overlay color opacity (from 0.0 to 1.0)", 'trx_utils'),
						"group" => __('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => __("Texture", 'trx_utils'),
						"description" => __("Texture style from 1 to 11. Empty or 0 - without texture.", 'trx_utils'),
						"group" => __('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_size",
						"heading" => __("Font size", 'trx_utils'),
						"description" => __("Font size of the text (default - in pixels, allows any CSS units of measure)", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_weight",
						"heading" => __("Font weight", 'trx_utils'),
						"description" => __("Font weight of the text", 'trx_utils'),
						"class" => "",
						"value" => array(
							__('Default', 'trx_utils') => 'inherit',
							__('Thin (100)', 'trx_utils') => '100',
							__('Light (300)', 'trx_utils') => '300',
							__('Normal (400)', 'trx_utils') => '400',
							__('Bold (700)', 'trx_utils') => '700'
						),
						"type" => "dropdown"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => __("Container content", 'trx_utils'),
						"description" => __("Content for section container", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css'],
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Block extends THEMEREX_VC_ShortCodeCollection {}
			
			
			
			
			
			
			// Blogger
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_blogger",
				"name" => __("Blogger", 'trx_utils'),
				"description" => __("Insert posts (pages) in many styles from desired categories or directly from ids", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				'icon' => 'icon_trx_blogger',
				"class" => "trx_sc_single trx_sc_blogger",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Output style", 'trx_utils'),
						"description" => __("Select desired style for posts output", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['blogger_styles']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "filters",
						"heading" => __("Show filters", 'trx_utils'),
						"description" => __("Use post's tags or categories as filter buttons", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['filters']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "hover",
						"heading" => __("Hover effect", 'trx_utils'),
						"description" => __("Select hover effect (only if style=Portfolio)", 'trx_utils'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['hovers']),
						'dependency' => array(
							'element' => 'style',
							'value' => array('portfolio_2','portfolio_3','portfolio_4','grid_2','grid_3','grid_4','square_2','square_3','square_4','short_2','short_3','short_4','colored_2','colored_3','colored_4')
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "hover_dir",
						"heading" => __("Hover direction", 'trx_utils'),
						"description" => __("Select hover direction (only if style=Portfolio and hover=Circle|Square)", 'trx_utils'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['hovers_dir']),
						'dependency' => array(
							'element' => 'style',
							'value' => array('portfolio_2','portfolio_3','portfolio_4','grid_2','grid_3','grid_4','square_2','square_3','square_4','short_2','short_3','short_4','colored_2','colored_3','colored_4')
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "location",
						"heading" => __("Dedicated content location", 'trx_utils'),
						"description" => __("Select position for dedicated content (only for style=excerpt)", 'trx_utils'),
						"class" => "",
						'dependency' => array(
							'element' => 'style',
							'value' => array('excerpt')
						),
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['locations']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "dir",
						"heading" => __("Posts direction", 'trx_utils'),
						"description" => __("Display posts in horizontal or vertical direction", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"std" => "horizontal",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['dir']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "rating",
						"heading" => __("Show rating stars", 'trx_utils'),
						"description" => __("Show rating stars under post's header", 'trx_utils'),
						"group" => __('Details', 'trx_utils'),
						"class" => "",
						"value" => array(__('Show rating', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "info",
						"heading" => __("Show post info block", 'trx_utils'),
						"description" => __("Show post info block (author, date, tags, etc.)", 'trx_utils'),
						"class" => "",
						"std" => 'yes',
						"value" => array(__('Show info', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "descr",
						"heading" => __("Description length", 'trx_utils'),
						"description" => __("How many characters are displayed from post excerpt? If 0 - don't show description", 'trx_utils'),
						"group" => __('Details', 'trx_utils'),
						"class" => "",
						"value" => 0,
						"type" => "textfield"
					),
					array(
						"param_name" => "links",
						"heading" => __("Allow links to the post", 'trx_utils'),
						"description" => __("Allow links to the post from each blogger item", 'trx_utils'),
						"group" => __('Details', 'trx_utils'),
						"class" => "",
						"std" => 'yes',
						"value" => array(__('Allow links', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "readmore",
						"heading" => __("More link text", 'trx_utils'),
						"description" => __("Read more link text. If empty - show 'More', else - used as link text", 'trx_utils'),
						"group" => __('Details', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "title",
						"heading" => __("Title", 'trx_utils'),
						"description" => __("Title for the block", 'trx_utils'),
						"admin_label" => true,
						"group" => __('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => __("Subtitle", 'trx_utils'),
						"description" => __("Subtitle for the block", 'trx_utils'),
						"group" => __('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => __("Description", 'trx_utils'),
						"description" => __("Description for the block", 'trx_utils'),
						"group" => __('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					array(
						"param_name" => "link",
						"heading" => __("Button URL", 'trx_utils'),
						"description" => __("Link URL for the button at the bottom of the block", 'trx_utils'),
						"group" => __('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link_caption",
						"heading" => __("Button caption", 'trx_utils'),
						"description" => __("Caption for the button at the bottom of the block", 'trx_utils'),
						"group" => __('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "post_type",
						"heading" => __("Post type", 'trx_utils'),
						"description" => __("Select post type to show", 'trx_utils'),
						"group" => __('Query', 'trx_utils'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['posts_types']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => __("Post IDs list", 'trx_utils'),
						"description" => __("Comma separated list of posts ID. If set - parameters above are ignored!", 'trx_utils'),
						"group" => __('Query', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "cat",
						"heading" => __("Categories list", 'trx_utils'),
						"description" => __("Select category. If empty - show posts from any category or from IDs list", 'trx_utils'),
						'dependency' => array(
							'element' => 'ids',
							'is_empty' => true
						),
						"group" => __('Query', 'trx_utils'),
						"class" => "",
						"value" => array_flip(themerex_array_merge(array(0 => __('- Select category -', 'trx_utils')), $THEMEREX_GLOBALS['sc_params']['categories'])),
						"type" => "dropdown"
					),
					array(
						"param_name" => "count",
						"heading" => __("Total posts to show", 'trx_utils'),
						"description" => __("How many posts will be displayed? If used IDs - this parameter ignored.", 'trx_utils'),
						'dependency' => array(
							'element' => 'ids',
							'is_empty' => true
						),
						"admin_label" => true,
						"group" => __('Query', 'trx_utils'),
						"class" => "",
						"value" => 3,
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => __("Columns number", 'trx_utils'),
						"description" => __("How many columns used to display posts?", 'trx_utils'),
						'dependency' => array(
							'element' => 'dir',
							'value' => 'horizontal'
						),
						"group" => __('Query', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => __("Offset before select posts", 'trx_utils'),
						"description" => __("Skip posts before select next part.", 'trx_utils'),
						'dependency' => array(
							'element' => 'ids',
							'is_empty' => true
						),
						"group" => __('Query', 'trx_utils'),
						"class" => "",
						"value" => 0,
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => __("Post order by", 'trx_utils'),
						"description" => __("Select desired posts sorting method", 'trx_utils'),
						"class" => "",
						"group" => __('Query', 'trx_utils'),
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['sorting']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => __("Post order", 'trx_utils'),
						"description" => __("Select desired posts order", 'trx_utils'),
						"class" => "",
						"group" => __('Query', 'trx_utils'),
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['ordering']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "only",
						"heading" => __("Select posts only", 'trx_utils'),
						"description" => __("Select posts only with reviews, videos, audios, thumbs or galleries", 'trx_utils'),
						"class" => "",
						"group" => __('Query', 'trx_utils'),
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['formats']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "scroll",
						"heading" => __("Use scroller", 'trx_utils'),
						"description" => __("Use scroller to show all posts", 'trx_utils'),
						"group" => __('Scroll', 'trx_utils'),
						"class" => "",
						"value" => array(__('Use scroller', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "controls",
						"heading" => __("Show slider controls", 'trx_utils'),
						"description" => __("Show arrows to control scroll slider", 'trx_utils'),
						"group" => __('Scroll', 'trx_utils'),
						"class" => "",
						"value" => array(__('Show controls', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css'],
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				),
			) );
			
			class WPBakeryShortCode_Trx_Blogger extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			// Br
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_br",
				"name" => __("Line break", 'trx_utils'),
				"description" => __("Line break or Clear Floating", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				'icon' => 'icon_trx_br',
				"class" => "trx_sc_single trx_sc_br",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "clear",
						"heading" => __("Clear floating", 'trx_utils'),
						"description" => __("Select clear side (if need)", 'trx_utils'),
						"class" => "",
						"value" => "",
						"value" => array(
							__('None', 'trx_utils') => 'none',
							__('Left', 'trx_utils') => 'left',
							__('Right', 'trx_utils') => 'right',
							__('Both', 'trx_utils') => 'both'
						),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Trx_Br extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Button
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_button",
				"name" => __("Button", 'trx_utils'),
				"description" => __("Button with link", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				'icon' => 'icon_trx_button',
				"class" => "trx_sc_single trx_sc_button",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "content",
						"heading" => __("Caption", 'trx_utils'),
						"description" => __("Button caption", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
                    array(
                        "param_name" => "bgcolor",
                        "heading" => __("Button's theme color", 'trx_utils'),
                        "description" => __("Select button's theme color", 'trx_utils'),
                        "class" => "",
                        "value" => array(
                            __('Accent1', 'trx_utils') => 'accent1',
                            __('Accent1', 'trx_utils') => 'accent1',
                            __('Accent2', 'trx_utils') => 'accent2',
                            __('Accent3', 'trx_utils') => 'accent3',
                            __('Accent4', 'trx_utils') => 'accent4'
                        ),
                        "type" => "dropdown"
                    ),
					array(
						"param_name" => "type",
						"heading" => __("Button's shape", 'trx_utils'),
						"description" => __("Select button's shape", 'trx_utils'),
						"class" => "",
						"value" => array(
							__('Square', 'trx_utils') => 'square',
							__('Round', 'trx_utils') => 'round'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "style",
						"heading" => __("Button's style", 'trx_utils'),
						"description" => __("Select button's style", 'trx_utils'),
						"class" => "",
						"value" => array(
							__('Filled', 'trx_utils') => 'filled',
							__('Border', 'trx_utils') => 'border'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "size",
						"heading" => __("Button's size", 'trx_utils'),
						"description" => __("Select button's size", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Small', 'trx_utils') => 'small',
							__('Medium', 'trx_utils') => 'medium',
							__('Large', 'trx_utils') => 'large'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon",
						"heading" => __("Button's icon", 'trx_utils'),
						"description" => __("Select icon for the title from Fontello icons set", 'trx_utils'),
						"class" => "",
						"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_style",
						"heading" => __("Button's color scheme", 'trx_utils'),
						"description" => __("Select button's color scheme", 'trx_utils'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['button_styles']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Button's text color", 'trx_utils'),
						"description" => __("Any color for button's caption", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Button's backcolor", 'trx_utils'),
						"description" => __("Any color for button's background", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "align",
						"heading" => __("Button's alignment", 'trx_utils'),
						"description" => __("Align button to left, center or right", 'trx_utils'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "link",
						"heading" => __("Link URL", 'trx_utils'),
						"description" => __("URL for the link on button click", 'trx_utils'),
						"class" => "",
						"group" => __('Link', 'trx_utils'),
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "target",
						"heading" => __("Link target", 'trx_utils'),
						"description" => __("Target for the link on button click", 'trx_utils'),
						"class" => "",
						"group" => __('Link', 'trx_utils'),
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "popup",
						"heading" => __("Open link in popup", 'trx_utils'),
						"description" => __("Open link target in popup window", 'trx_utils'),
						"class" => "",
						"group" => __('Link', 'trx_utils'),
						"value" => array(__('Open in popup', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "rel",
						"heading" => __("Rel attribute", 'trx_utils'),
						"description" => __("Rel attribute for the button's link (if need", 'trx_utils'),
						"class" => "",
						"group" => __('Link', 'trx_utils'),
						"value" => "",
						"type" => "textfield"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css'],
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_Button extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Call to Action block
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_call_to_action",
				"name" => __("Call to Action", 'trx_utils'),
				"description" => __("Insert call to action block in your page (post)", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				'icon' => 'icon_trx_call_to_action',
				"class" => "trx_sc_collection trx_sc_call_to_action",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Block's style", 'trx_utils'),
						"description" => __("Select style to display this block", 'trx_utils'),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip(themerex_get_list_styles(1, 2)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", 'trx_utils'),
						"description" => __("Select block alignment", 'trx_utils'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "accent",
						"heading" => __("Accent", 'trx_utils'),
						"description" => __("Fill entire block with Accent1 color from current color scheme", 'trx_utils'),
						"class" => "",
						"value" => array("Fill with Accent1 color" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "custom",
						"heading" => __("Custom", 'trx_utils'),
						"description" => __("Allow get featured image or video from inner shortcodes (custom) or get it from shortcode parameters below", 'trx_utils'),
						"class" => "",
						"value" => array("Custom content" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "image",
						"heading" => __("Image", 'trx_utils'),
						"description" => __("Image to display inside block", 'trx_utils'),
				        'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "video",
						"heading" => __("URL for video file", 'trx_utils'),
						"description" => __("Paste URL for video file to display inside block", 'trx_utils'),
				        'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "title",
						"heading" => __("Title", 'trx_utils'),
						"description" => __("Title for the block", 'trx_utils'),
						"admin_label" => true,
						"group" => __('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => __("Subtitle", 'trx_utils'),
						"description" => __("Subtitle for the block", 'trx_utils'),
						"group" => __('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => __("Description", 'trx_utils'),
						"description" => __("Description for the block", 'trx_utils'),
						"group" => __('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					array(
						"param_name" => "link",
						"heading" => __("Button URL", 'trx_utils'),
						"description" => __("Link URL for the button at the bottom of the block", 'trx_utils'),
						"group" => __('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link_caption",
						"heading" => __("Button caption", 'trx_utils'),
						"description" => __("Caption for the button at the bottom of the block", 'trx_utils'),
						"group" => __('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link2",
						"heading" => __("Button 2 URL", 'trx_utils'),
						"description" => __("Link URL for the second button at the bottom of the block", 'trx_utils'),
						"group" => __('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link2_caption",
						"heading" => __("Button 2 caption", 'trx_utils'),
						"description" => __("Caption for the second button at the bottom of the block", 'trx_utils'),
						"group" => __('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css'],
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Call_To_Action extends THEMEREX_VC_ShortCodeCollection {}


			
			
			
			
			// Chat
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_chat",
				"name" => __("Chat", 'trx_utils'),
				"description" => __("Chat message", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				'icon' => 'icon_trx_chat',
				"class" => "trx_sc_container trx_sc_chat",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => __("Item title", 'trx_utils'),
						"description" => __("Title for current chat item", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "photo",
						"heading" => __("Item photo", 'trx_utils'),
						"description" => __("Select or upload image or write URL from other site for the item photo (avatar)", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "link",
						"heading" => __("Link URL", 'trx_utils'),
						"description" => __("URL for the link on chat title click", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => __("Chat item content", 'trx_utils'),
						"description" => __("Current chat item content", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css'],
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				),
				'js_view' => 'VcTrxTextContainerView'
			
			) );
			
			class WPBakeryShortCode_Trx_Chat extends THEMEREX_VC_ShortCodeContainer {}
			
			
			
			
			
			
			// Columns
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_columns",
				"name" => __("Columns", 'trx_utils'),
				"description" => __("Insert columns with margins", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				'icon' => 'icon_trx_columns',
				"class" => "trx_sc_columns",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_column_item'),
				"params" => array(
					array(
						"param_name" => "count",
						"heading" => __("Columns count", 'trx_utils'),
						"description" => __("Number of the columns in the container.", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "2",
						"type" => "textfield"
					),
					array(
						"param_name" => "fluid",
						"heading" => __("Fluid columns", 'trx_utils'),
						"description" => __("To squeeze the columns when reducing the size of the window (fluid=yes) or to rebuild them (fluid=no)", 'trx_utils'),
						"class" => "",
						"value" => array(__('Fluid columns', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css'],
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				),
				'default_content' => '
					[trx_column_item][/trx_column_item]
					[trx_column_item][/trx_column_item]
				',
				'js_view' => 'VcTrxColumnsView'
			) );
			
			
			vc_map( array(
				"base" => "trx_column_item",
				"name" => __("Column", 'trx_utils'),
				"description" => __("Column item", 'trx_utils'),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_column_item",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_column_item',
				"as_child" => array('only' => 'trx_columns'),
				"as_parent" => array('except' => 'trx_columns'),
				"params" => array(
					array(
						"param_name" => "span",
						"heading" => __("Merge columns", 'trx_utils'),
						"description" => __("Count merged columns from current", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", 'trx_utils'),
						"description" => __("Alignment text in the column", 'trx_utils'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Fore color", 'trx_utils'),
						"description" => __("Any color for objects in this column", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", 'trx_utils'),
						"description" => __("Any background color for this column", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => __("URL for background image file", 'trx_utils'),
						"description" => __("Select or upload image or write URL from other site for the background", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => __("Column's content", 'trx_utils'),
						"description" => __("Content of the current column", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxColumnItemView'
			) );
			
			class WPBakeryShortCode_Trx_Columns extends THEMEREX_VC_ShortCodeColumns {}
			class WPBakeryShortCode_Trx_Column_Item extends THEMEREX_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Contact form
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_contact_form",
				"name" => __("Contact form", 'trx_utils'),
				"description" => __("Insert contact form", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				'icon' => 'icon_trx_contact_form',
				"class" => "trx_sc_collection trx_sc_contact_form",
				"content_element" => true,
				"is_container" => true,
				"as_parent" => array('only' => 'trx_form_item'),
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "custom",
						"heading" => __("Custom", 'trx_utils'),
						"description" => __("Use custom fields or create standard contact form (ignore info from 'Field' tabs)", 'trx_utils'),
						"class" => "",
						"value" => array(__('Create custom form', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "style",
						"heading" => __("Style", 'trx_utils'),
						"description" => __("Select style of the contact form", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(themerex_get_list_styles(1, 2)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "action",
						"heading" => __("Action", 'trx_utils'),
						"description" => __("Contact form action (URL to handle form data). If empty - use internal action", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", 'trx_utils'),
						"description" => __("Select form alignment", 'trx_utils'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
                        "param_name" => "title",
                        "heading" => __("Title", 'trx_utils'),
                        "description" => __("Title for the block", 'trx_utils'),
                        "admin_label" => true,
                        "group" => __('Captions', 'trx_utils'),
                        "class" => "",
                        "value" => "",
                        "type" => "textfield"
                    ),
                    array(
                        "param_name" => "button_text",
                        "heading" => __("Button text", 'trx_utils'),
                        "description" => __("Text for submit button", 'trx_utils'),
                        "admin_label" => true,
                        "group" => __('Captions', 'trx_utils'),
                        "class" => "",
                        "value" => "",
                        "type" => "textfield"
                    ),
                    array(
                        "param_name" => "button_size",
                        "heading" => __("Button size", 'trx_utils'),
                        "description" => __("Button size", 'trx_utils'),
                        "admin_label" => true,
                        "class" => "",
                        "value" => array(
                            __('Small', 'trx_utils') => 'sc_button_size_small',
                            __('Large', 'trx_utils') => 'sc_button_size_large'
                        ),
                        "type" => "dropdown"
                    ),
					array(
						"param_name" => "subtitle",
						"heading" => __("Subtitle", 'trx_utils'),
						"description" => __("Subtitle for the block", 'trx_utils'),
						"group" => __('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => __("Description", 'trx_utils'),
						"description" => __("Description for the block", 'trx_utils'),
						"group" => __('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css'],
					themerex_vc_width(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			
			vc_map( array(
				"base" => "trx_form_item",
				"name" => __("Form item (custom field)", 'trx_utils'),
				"description" => __("Custom field for the contact form", 'trx_utils'),
				"class" => "trx_sc_item trx_sc_form_item",
				'icon' => 'icon_trx_form_item',
				//"allowed_container_element" => 'vc_row',
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => false,
				"as_child" => array('only' => 'trx_contact_form'), // Use only|except attributes to limit parent (separate multiple values with comma)
				"params" => array(
					array(
						"param_name" => "type",
						"heading" => __("Type", 'trx_utils'),
						"description" => __("Select type of the custom field", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['field_types']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "name",
						"heading" => __("Name", 'trx_utils'),
						"description" => __("Name of the custom field", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "value",
						"heading" => __("Default value", 'trx_utils'),
						"description" => __("Default value of the custom field", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "options",
						"heading" => __("Options", 'trx_utils'),
						"description" => __("Field options. For example: big=My daddy|middle=My brother|small=My little sister", 'trx_utils'),
						'dependency' => array(
							'element' => 'type',
							'value' => array('radio','checkbox','select')
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "label",
						"heading" => __("Label", 'trx_utils'),
						"description" => __("Label for the custom field", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "label_position",
						"heading" => __("Label position", 'trx_utils'),
						"description" => __("Label position relative to the field", 'trx_utils'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['label_positions']),
						"type" => "dropdown"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css'],
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Contact_Form extends THEMEREX_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_Form_Item extends THEMEREX_VC_ShortCodeItem {}
			
			
			
			
			
			
			
			// Content block on fullscreen page
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_content",
				"name" => __("Content block", 'trx_utils'),
				"description" => __("Container for main content block (use it only on fullscreen pages)", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				'icon' => 'icon_trx_content',
				"class" => "trx_sc_collection trx_sc_content",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "scheme",
						"heading" => __("Color scheme", 'trx_utils'),
						"description" => __("Select color scheme for this block", 'trx_utils'),
						"group" => __('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['schemes']),
						"type" => "dropdown"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => __("Container content", 'trx_utils'),
						"description" => __("Content for section container", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css'],
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom']
				)
			) );
			
			class WPBakeryShortCode_Trx_Content extends THEMEREX_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Countdown
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_countdown",
				"name" => __("Countdown", 'trx_utils'),
				"description" => __("Insert countdown object", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				'icon' => 'icon_trx_countdown',
				"class" => "trx_sc_single trx_sc_countdown",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "date",
						"heading" => __("Date", 'trx_utils'),
						"description" => __("Upcoming date (format: yyyy-mm-dd)", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "time",
						"heading" => __("Time", 'trx_utils'),
						"description" => __("Upcoming time (format: HH:mm:ss)", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "style",
						"heading" => __("Style", 'trx_utils'),
						"description" => __("Countdown style", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(themerex_get_list_styles(1, 2)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", 'trx_utils'),
						"description" => __("Align counter to left, center or right", 'trx_utils'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css'],
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Countdown extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Dropcaps
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_dropcaps",
				"name" => __("Dropcaps", 'trx_utils'),
				"description" => __("Make first letter of the text as dropcaps", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				'icon' => 'icon_trx_dropcaps',
				"class" => "trx_sc_single trx_sc_dropcaps",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Style", 'trx_utils'),
						"description" => __("Dropcaps style", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(themerex_get_list_styles(1, 4)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "content",
						"heading" => __("Paragraph text", 'trx_utils'),
						"description" => __("Paragraph with dropcaps content", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			
			) );
			
			class WPBakeryShortCode_Trx_Dropcaps extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Emailer
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_emailer",
				"name" => __("E-mail collector", 'trx_utils'),
				"description" => __("Collect e-mails into specified group", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				'icon' => 'icon_trx_emailer',
				"class" => "trx_sc_single trx_sc_emailer",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "group",
						"heading" => __("Group", 'trx_utils'),
						"description" => __("The name of group to collect e-mail address", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "open",
						"heading" => __("Opened", 'trx_utils'),
						"description" => __("Initially open the input field on show object", 'trx_utils'),
						"class" => "",
						"value" => array(__('Initially opened', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", 'trx_utils'),
						"description" => __("Align field to left, center or right", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css'],
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Emailer extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Gap
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_gap",
				"name" => __("Gap", 'trx_utils'),
				"description" => __("Insert gap (fullwidth area) in the post content", 'trx_utils'),
				"category" => __('Structure', 'trx_utils'),
				'icon' => 'icon_trx_gap',
				"class" => "trx_sc_collection trx_sc_gap",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"params" => array(
					/*
					array(
						"param_name" => "content",
						"heading" => __("Gap content", 'trx_utils'),
						"description" => __("Gap inner content", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					)
					*/
				)
			) );
			
			class WPBakeryShortCode_Trx_Gap extends THEMEREX_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Googlemap
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_googlemap",
				"name" => __("Google map", 'trx_utils'),
				"description" => __("Insert Google map with desired address or coordinates", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				'icon' => 'icon_trx_googlemap',
				"class" => "trx_sc_collection trx_sc_googlemap",
				"content_element" => true,
				"is_container" => true,
				"as_parent" => array('only' => 'trx_googlemap_marker'),
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "zoom",
						"heading" => __("Zoom", 'trx_utils'),
						"description" => __("Map zoom factor", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "16",
						"type" => "textfield"
					),
					array(
						"param_name" => "style",
						"heading" => __("Style", 'trx_utils'),
						"description" => __("Map custom style", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['googlemap_styles']),
						"type" => "dropdown"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css'],
					themerex_vc_width('100%'),
					themerex_vc_height(240),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			vc_map( array(
				"base" => "trx_googlemap_marker",
				"name" => __("Googlemap marker", 'trx_utils'),
				"description" => __("Insert new marker into Google map", 'trx_utils'),
				"class" => "trx_sc_collection trx_sc_googlemap_marker",
				'icon' => 'icon_trx_googlemap_marker',
				//"allowed_container_element" => 'vc_row',
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => true,
				"as_child" => array('only' => 'trx_googlemap'), // Use only|except attributes to limit parent (separate multiple values with comma)
				"params" => array(
					array(
						"param_name" => "address",
						"heading" => __("Address", 'trx_utils'),
						"description" => __("Address of this marker", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "latlng",
						"heading" => __("Latitude and Longtitude", 'trx_utils'),
						"description" => __("Comma separated marker's coorditanes (instead Address)", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "title",
						"heading" => __("Title", 'trx_utils'),
						"description" => __("Title for this marker", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "content",
						"heading" => __("Description", 'trx_utils'),
						"description" => __("Description for this marker", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					array(
						"param_name" => "point",
						"heading" => __("URL for marker image file", 'trx_utils'),
						"description" => __("Select or upload image or write URL from other site for this marker. If empty - use default marker", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					$THEMEREX_GLOBALS['vc_params']['id']
				)
			) );
			
			class WPBakeryShortCode_Trx_Googlemap extends THEMEREX_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_Googlemap_Marker extends THEMEREX_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			
			
			// Highlight
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_highlight",
				"name" => __("Highlight text", 'trx_utils'),
				"description" => __("Highlight text with selected color, background color and other styles", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				'icon' => 'icon_trx_highlight',
				"class" => "trx_sc_single trx_sc_highlight",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "type",
						"heading" => __("Type", 'trx_utils'),
						"description" => __("Highlight type", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array(
								__('Custom', 'trx_utils') => 0,
								__('Type 1', 'trx_utils') => 1,
								__('Type 2', 'trx_utils') => 2,
								__('Type 3', 'trx_utils') => 3
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Text color", 'trx_utils'),
						"description" => __("Color for the highlighted text", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", 'trx_utils'),
						"description" => __("Background color for the highlighted text", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "font_size",
						"heading" => __("Font size", 'trx_utils'),
						"description" => __("Font size for the highlighted text (default - in pixels, allows any CSS units of measure)", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "content",
						"heading" => __("Highlight text", 'trx_utils'),
						"description" => __("Content for highlight", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_Highlight extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			// Icon
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_icon",
				"name" => __("Icon", 'trx_utils'),
				"description" => __("Insert the icon", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				'icon' => 'icon_trx_icon',
				"class" => "trx_sc_single trx_sc_icon",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "icon",
						"heading" => __("Icon", 'trx_utils'),
						"description" => __("Select icon class from Fontello icons set", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Text color", 'trx_utils'),
						"description" => __("Icon's color", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", 'trx_utils'),
						"description" => __("Background color for the icon", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_shape",
						"heading" => __("Background shape", 'trx_utils'),
						"description" => __("Shape of the icon background", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('None', 'trx_utils') => 'none',
							__('Round', 'trx_utils') => 'round',
							__('Square', 'trx_utils') => 'square'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_style",
						"heading" => __("Icon's color scheme", 'trx_utils'),
						"description" => __("Select icon's color scheme", 'trx_utils'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['button_styles']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "font_size",
						"heading" => __("Font size", 'trx_utils'),
						"description" => __("Icon's font size", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_weight",
						"heading" => __("Font weight", 'trx_utils'),
						"description" => __("Icon's font weight", 'trx_utils'),
						"class" => "",
						"value" => array(
							__('Default', 'trx_utils') => 'inherit',
							__('Thin (100)', 'trx_utils') => '100',
							__('Light (300)', 'trx_utils') => '300',
							__('Normal (400)', 'trx_utils') => '400',
							__('Bold (700)', 'trx_utils') => '700'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => __("Icon's alignment", 'trx_utils'),
						"description" => __("Align icon to left, center or right", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "link",
						"heading" => __("Link URL", 'trx_utils'),
						"description" => __("Link URL from this icon (if not empty)", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['css'],
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				),
			) );
			
			class WPBakeryShortCode_Trx_Icon extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Image
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_image",
				"name" => __("Image", 'trx_utils'),
				"description" => __("Insert image", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				'icon' => 'icon_trx_image',
				"class" => "trx_sc_single trx_sc_image",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "url",
						"heading" => __("Select image", 'trx_utils'),
						"description" => __("Select image from library", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "align",
						"heading" => __("Image alignment", 'trx_utils'),
						"description" => __("Align image to left or right side", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "shape",
						"heading" => __("Image shape", 'trx_utils'),
						"description" => __("Shape of the image: square or round", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Square', 'trx_utils') => 'square',
							__('Round', 'trx_utils') => 'round'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => __("Title", 'trx_utils'),
						"description" => __("Image's title", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon",
						"heading" => __("Title's icon", 'trx_utils'),
						"description" => __("Select icon for the title from Fontello icons set", 'trx_utils'),
						"class" => "",
						"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "link",
						"heading" => __("Link", 'trx_utils'),
						"description" => __("The link URL from the image", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css'],
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Image extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Infobox
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_infobox",
				"name" => __("Infobox", 'trx_utils'),
				"description" => __("Box with info or error message", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				'icon' => 'icon_trx_infobox',
				"class" => "trx_sc_container trx_sc_infobox",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Style", 'trx_utils'),
						"description" => __("Infobox style", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array(
								__('Regular', 'trx_utils') => 'regular',
								__('Info', 'trx_utils') => 'info',
								__('Success', 'trx_utils') => 'success',
								__('Error', 'trx_utils') => 'error',
								__('Result', 'trx_utils') => 'result'
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "closeable",
						"heading" => __("Closeable", 'trx_utils'),
						"description" => __("Create closeable box (with close button)", 'trx_utils'),
						"class" => "",
						"value" => array(__('Close button', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "icon",
						"heading" => __("Custom icon", 'trx_utils'),
						"description" => __("Select icon for the infobox from Fontello icons set. If empty - use default icon", 'trx_utils'),
						"class" => "",
						"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Text color", 'trx_utils'),
						"description" => __("Any color for the text and headers", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", 'trx_utils'),
						"description" => __("Any background color for this infobox", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => __("Message text", 'trx_utils'),
						"description" => __("Message for the infobox", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css'],
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				),
				'js_view' => 'VcTrxTextContainerView'
			) );
			
			class WPBakeryShortCode_Trx_Infobox extends THEMEREX_VC_ShortCodeContainer {}
			
			
			
			
			
			
			
			// Line
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_line",
				"name" => __("Line", 'trx_utils'),
				"description" => __("Insert line (delimiter)", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				"class" => "trx_sc_single trx_sc_line",
				'icon' => 'icon_trx_line',
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Style", 'trx_utils'),
						"description" => __("Line style", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array(
								__('Solid', 'trx_utils') => 'solid',
								__('Dashed', 'trx_utils') => 'dashed',
								__('Dotted', 'trx_utils') => 'dotted',
								__('Double', 'trx_utils') => 'double',
								__('Shadow', 'trx_utils') => 'shadow'
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Line color", 'trx_utils'),
						"description" => __("Line color", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css'],
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Line extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// List
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_list",
				"name" => __("List", 'trx_utils'),
				"description" => __("List items with specific bullets", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				"class" => "trx_sc_collection trx_sc_list",
				'icon' => 'icon_trx_list',
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_list_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Bullet's style", 'trx_utils'),
						"description" => __("Bullet's style for each list item", 'trx_utils'),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['list_styles']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Color", 'trx_utils'),
						"description" => __("List items color", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "icon",
						"heading" => __("List icon", 'trx_utils'),
						"description" => __("Select list icon from Fontello icons set (only for style=Iconed)", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						'dependency' => array(
							'element' => 'style',
							'value' => array('iconed')
						),
						"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_color",
						"heading" => __("Icon color", 'trx_utils'),
						"description" => __("List icons color", 'trx_utils'),
						"class" => "",
						'dependency' => array(
							'element' => 'style',
							'value' => array('iconed')
						),
						"value" => "",
						"type" => "colorpicker"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css'],
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				),
				'default_content' => '
					[trx_list_item]' . __( 'Item 1', 'trx_utils') . '[/trx_list_item]
					[trx_list_item]' . __( 'Item 2', 'trx_utils') . '[/trx_list_item]
				'
			) );
			
			
			vc_map( array(
				"base" => "trx_list_item",
				"name" => __("List item", 'trx_utils'),
				"description" => __("List item with specific bullet", 'trx_utils'),
				"class" => "trx_sc_single trx_sc_list_item",
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => false,
				'icon' => 'icon_trx_list_item',
				"as_child" => array('only' => 'trx_list'), // Use only|except attributes to limit parent (separate multiple values with comma)
				"as_parent" => array('except' => 'trx_list'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => __("List item title", 'trx_utils'),
						"description" => __("Title for the current list item (show it as tooltip)", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => __("Link URL", 'trx_utils'),
						"description" => __("Link URL for the current list item", 'trx_utils'),
						"admin_label" => true,
						"group" => __('Link', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "target",
						"heading" => __("Link target", 'trx_utils'),
						"description" => __("Link target for the current list item", 'trx_utils'),
						"admin_label" => true,
						"group" => __('Link', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "color",
						"heading" => __("Color", 'trx_utils'),
						"description" => __("Text color for this item", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "icon",
						"heading" => __("List item icon", 'trx_utils'),
						"description" => __("Select list item icon from Fontello icons set (only for style=Iconed)", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_color",
						"heading" => __("Icon color", 'trx_utils'),
						"description" => __("Icon color for this item", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "content",
						"heading" => __("List item text", 'trx_utils'),
						"description" => __("Current list item content", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			
			) );
			
			class WPBakeryShortCode_Trx_List extends THEMEREX_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_List_Item extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			
			
			// Number
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_number",
				"name" => __("Number", 'trx_utils'),
				"description" => __("Insert number or any word as set of separated characters", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				"class" => "trx_sc_single trx_sc_number",
				'icon' => 'icon_trx_number',
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "value",
						"heading" => __("Value", 'trx_utils'),
						"description" => __("Number or any word to separate", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", 'trx_utils'),
						"description" => __("Select block alignment", 'trx_utils'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css'],
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Number extends THEMEREX_VC_ShortCodeSingle {}


			
			
			
			
			
			// Parallax
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_parallax",
				"name" => __("Parallax", 'trx_utils'),
				"description" => __("Create the parallax container (with asinc background image)", 'trx_utils'),
				"category" => __('Structure', 'trx_utils'),
				'icon' => 'icon_trx_parallax',
				"class" => "trx_sc_collection trx_sc_parallax",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "gap",
						"heading" => __("Create gap", 'trx_utils'),
						"description" => __("Create gap around parallax container (not need in fullscreen pages)", 'trx_utils'),
						"class" => "",
						"value" => array(__('Create gap', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "dir",
						"heading" => __("Direction", 'trx_utils'),
						"description" => __("Scroll direction for the parallax background", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array(
								__('Up', 'trx_utils') => 'up',
								__('Down', 'trx_utils') => 'down'
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "speed",
						"heading" => __("Speed", 'trx_utils'),
						"description" => __("Parallax background motion speed (from 0.0 to 1.0)", 'trx_utils'),
						"class" => "",
						"value" => "0.3",
						"type" => "textfield"
					),
					array(
						"param_name" => "scheme",
						"heading" => __("Color scheme", 'trx_utils'),
						"description" => __("Select color scheme for this block", 'trx_utils'),
						"group" => __('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['schemes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Text color", 'trx_utils'),
						"description" => __("Select color for text object inside parallax block", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Backgroud color", 'trx_utils'),
						"description" => __("Select color for parallax background", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => __("Background image", 'trx_utils'),
						"description" => __("Select or upload image or write URL from other site for the parallax background", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_image_x",
						"heading" => __("Image X position", 'trx_utils'),
						"description" => __("Parallax background X position (in percents)", 'trx_utils'),
						"class" => "",
						"value" => "50%",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_video",
						"heading" => __("Video background", 'trx_utils'),
						"description" => __("Paste URL for video file to show it as parallax background", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_video_ratio",
						"heading" => __("Video ratio", 'trx_utils'),
						"description" => __("Specify ratio of the video background. For example: 16:9 (default), 4:3, etc.", 'trx_utils'),
						"class" => "",
						"value" => "16:9",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => __("Overlay", 'trx_utils'),
						"description" => __("Overlay color opacity (from 0.0 to 1.0)", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => __("Texture", 'trx_utils'),
						"description" => __("Texture style from 1 to 11. Empty or 0 - without texture.", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => __("Content", 'trx_utils'),
						"description" => __("Content for the parallax container", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css'],
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Parallax extends THEMEREX_VC_ShortCodeCollection {}
			
			
			
			
			
			
			// Popup
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_popup",
				"name" => __("Popup window", 'trx_utils'),
				"description" => __("Container for any html-block with desired class and style for popup window", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				'icon' => 'icon_trx_popup',
				"class" => "trx_sc_collection trx_sc_popup",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					/*
					array(
						"param_name" => "content",
						"heading" => __("Container content", 'trx_utils'),
						"description" => __("Content for popup container", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['css'],
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Popup extends THEMEREX_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Price
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_price",
				"name" => __("Price", 'trx_utils'),
				"description" => __("Insert price with decoration", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				'icon' => 'icon_trx_price',
				"class" => "trx_sc_single trx_sc_price",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "money",
						"heading" => __("Money", 'trx_utils'),
						"description" => __("Money value (dot or comma separated)", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "currency",
						"heading" => __("Currency symbol", 'trx_utils'),
						"description" => __("Currency character", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "$",
						"type" => "textfield"
					),
					array(
						"param_name" => "period",
						"heading" => __("Period", 'trx_utils'),
						"description" => __("Period text (if need). For example: monthly, daily, etc.", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", 'trx_utils'),
						"description" => __("Align price to left or right side", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['css'],
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Price extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Price block
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_price_block",
				"name" => __("Price block", 'trx_utils'),
				"description" => __("Insert price block with title, price and description", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				'icon' => 'icon_trx_price_block',
				"class" => "trx_sc_single trx_sc_price_block",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => __("Title", 'trx_utils'),
						"description" => __("Block title", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => __("Link URL", 'trx_utils'),
						"description" => __("URL for link from button (at bottom of the block)", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link_text",
						"heading" => __("Link text", 'trx_utils'),
						"description" => __("Text (caption) for the link button (at bottom of the block). If empty - button not showed", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon",
						"heading" => __("Icon", 'trx_utils'),
						"description" => __("Select icon from Fontello icons set (placed before/instead price)", 'trx_utils'),
						"class" => "",
						"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "money",
						"heading" => __("Money", 'trx_utils'),
						"description" => __("Money value (dot or comma separated)", 'trx_utils'),
						"admin_label" => true,
						"group" => __('Money', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "currency",
						"heading" => __("Currency symbol", 'trx_utils'),
						"description" => __("Currency character", 'trx_utils'),
						"admin_label" => true,
						"group" => __('Money', 'trx_utils'),
						"class" => "",
						"value" => "$",
						"type" => "textfield"
					),
					array(
						"param_name" => "period",
						"heading" => __("Period", 'trx_utils'),
						"description" => __("Period text (if need). For example: monthly, daily, etc.", 'trx_utils'),
						"admin_label" => true,
						"group" => __('Money', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "scheme",
						"heading" => __("Color scheme", 'trx_utils'),
						"description" => __("Select color scheme for this block", 'trx_utils'),
						"group" => __('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['schemes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", 'trx_utils'),
						"description" => __("Align price to left or right side", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "content",
						"heading" => __("Description", 'trx_utils'),
						"description" => __("Description for this price block", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css'],
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_PriceBlock extends THEMEREX_VC_ShortCodeSingle {}

			
			
			
			
			// Quote
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_quote",
				"name" => __("Quote", 'trx_utils'),
				"description" => __("Quote text", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				'icon' => 'icon_trx_quote',
				"class" => "trx_sc_single trx_sc_quote",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
                    array(
                        "param_name" => "style",
                        "heading" => __("Style", 'trx_utils'),
                        "description" => __("Select style quote", 'trx_utils'),
                        "class" => "",
                        "value" => array(
                            __('Light', 'trx_utils') => "light",
                            __('Dark', 'trx_utils') => "dark"
                        ),
                        "type" => "dropdown"
                    ),
					array(
						"param_name" => "cite",
						"heading" => __("Quote cite", 'trx_utils'),
						"description" => __("URL for the quote cite link", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "title",
						"heading" => __("Title (author)", 'trx_utils'),
						"description" => __("Quote title (author name)", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "content",
						"heading" => __("Quote content", 'trx_utils'),
						"description" => __("Quote content", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css'],
					themerex_vc_width(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_Quote extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Reviews
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_reviews",
				"name" => __("Reviews", 'trx_utils'),
				"description" => __("Insert reviews block in the single post", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				'icon' => 'icon_trx_reviews',
				"class" => "trx_sc_single trx_sc_reviews",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "align",
						"heading" => __("Alignment", 'trx_utils'),
						"description" => __("Align counter to left, center or right", 'trx_utils'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css'],
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Reviews extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Search
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_search",
				"name" => __("Search form", 'trx_utils'),
				"description" => __("Insert search form", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				'icon' => 'icon_trx_search',
				"class" => "trx_sc_single trx_sc_search",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Style", 'trx_utils'),
						"description" => __("Select style to display search field", 'trx_utils'),
						"class" => "",
						"value" => array(
							__('Regular', 'trx_utils') => "regular",
							__('Flat', 'trx_utils') => "flat"
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "state",
						"heading" => __("State", 'trx_utils'),
						"description" => __("Select search field initial state", 'trx_utils'),
						"class" => "",
						"value" => array(
							__('Fixed', 'trx_utils')  => "fixed",
							__('Opened', 'trx_utils') => "opened",
							__('Closed', 'trx_utils') => "closed"
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => __("Title", 'trx_utils'),
						"description" => __("Title (placeholder) for the search field", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => __("Search &hellip;", 'trx_utils'),
						"type" => "textfield"
					),
					array(
						"param_name" => "ajax",
						"heading" => __("AJAX", 'trx_utils'),
						"description" => __("Search via AJAX or reload page", 'trx_utils'),
						"class" => "",
						"value" => array(__('Use AJAX search', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css'],
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Search extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Section
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_section",
				"name" => __("Section container", 'trx_utils'),
				"description" => __("Container for any block ([block] analog - to enable nesting)", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				"class" => "trx_sc_collection trx_sc_section",
				'icon' => 'icon_trx_block',
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "dedicated",
						"heading" => __("Dedicated", 'trx_utils'),
						"description" => __("Use this block as dedicated content - show it before post title on single page", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array(__('Use as dedicated content', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", 'trx_utils'),
						"description" => __("Select block alignment", 'trx_utils'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => __("Columns emulation", 'trx_utils'),
						"description" => __("Select width for columns emulation", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['columns']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "pan",
						"heading" => __("Use pan effect", 'trx_utils'),
						"description" => __("Use pan effect to show section content", 'trx_utils'),
						"group" => __('Scroll', 'trx_utils'),
						"class" => "",
						"value" => array(__('Content scroller', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scroll",
						"heading" => __("Use scroller", 'trx_utils'),
						"description" => __("Use scroller to show section content", 'trx_utils'),
						"group" => __('Scroll', 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array(__('Content scroller', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scroll_dir",
						"heading" => __("Scroll and Pan direction", 'trx_utils'),
						"description" => __("Scroll and Pan direction (if Use scroller = yes or Pan = yes)", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"group" => __('Scroll', 'trx_utils'),
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['dir']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "scroll_controls",
						"heading" => __("Scroll controls", 'trx_utils'),
						"description" => __("Show scroll controls (if Use scroller = yes)", 'trx_utils'),
						"class" => "",
						"group" => __('Scroll', 'trx_utils'),
						'dependency' => array(
							'element' => 'scroll',
							'not_empty' => true
						),
						"value" => array(__('Show scroll controls', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scheme",
						"heading" => __("Color scheme", 'trx_utils'),
						"description" => __("Select color scheme for this block", 'trx_utils'),
						"group" => __('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['schemes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Fore color", 'trx_utils'),
						"description" => __("Any color for objects in this section", 'trx_utils'),
						"group" => __('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", 'trx_utils'),
						"description" => __("Any background color for this section", 'trx_utils'),
						"group" => __('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => __("Background image URL", 'trx_utils'),
						"description" => __("Select background image from library for this section", 'trx_utils'),
						"group" => __('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => __("Overlay", 'trx_utils'),
						"description" => __("Overlay color opacity (from 0.0 to 1.0)", 'trx_utils'),
						"group" => __('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => __("Texture", 'trx_utils'),
						"description" => __("Texture style from 1 to 11. Empty or 0 - without texture.", 'trx_utils'),
						"group" => __('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_size",
						"heading" => __("Font size", 'trx_utils'),
						"description" => __("Font size of the text (default - in pixels, allows any CSS units of measure)", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_weight",
						"heading" => __("Font weight", 'trx_utils'),
						"description" => __("Font weight of the text", 'trx_utils'),
						"class" => "",
						"value" => array(
							__('Default', 'trx_utils') => 'inherit',
							__('Thin (100)', 'trx_utils') => '100',
							__('Light (300)', 'trx_utils') => '300',
							__('Normal (400)', 'trx_utils') => '400',
							__('Bold (700)', 'trx_utils') => '700'
						),
						"type" => "dropdown"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => __("Container content", 'trx_utils'),
						"description" => __("Content for section container", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css'],
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Section extends THEMEREX_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Skills
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_skills",
				"name" => __("Skills", 'trx_utils'),
				"description" => __("Insert skills diagramm", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				'icon' => 'icon_trx_skills',
				"class" => "trx_sc_collection trx_sc_skills",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_skills_item'),
				"params" => array(
					array(
						"param_name" => "max_value",
						"heading" => __("Max value", 'trx_utils'),
						"description" => __("Max value for skills items", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "100",
						"type" => "textfield"
					),
					array(
						"param_name" => "type",
						"heading" => __("Skills type", 'trx_utils'),
						"description" => __("Select type of skills block", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Bar', 'trx_utils') => 'bar',
							__('Pie chart', 'trx_utils') => 'pie',
							__('Counter', 'trx_utils') => 'counter',
							__('Arc', 'trx_utils') => 'arc'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "layout",
						"heading" => __("Skills layout", 'trx_utils'),
						"description" => __("Select layout of skills block", 'trx_utils'),
						"admin_label" => true,
						'dependency' => array(
							'element' => 'type',
							'value' => array('counter','bar','pie')
						),
						"class" => "",
						"value" => array(
							__('Rows', 'trx_utils') => 'rows',
							__('Columns', 'trx_utils') => 'columns'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "dir",
						"heading" => __("Direction", 'trx_utils'),
						"description" => __("Select direction of skills block", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['dir']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "style",
						"heading" => __("Counters style", 'trx_utils'),
						"description" => __("Select style of skills items (only for type=counter)", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(themerex_get_list_styles(1, 4)),
						'dependency' => array(
							'element' => 'type',
							'value' => array('counter')
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => __("Columns count", 'trx_utils'),
						"description" => __("Skills columns count (required)", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "color",
						"heading" => __("Color", 'trx_utils'),
						"description" => __("Color for all skills items", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", 'trx_utils'),
						"description" => __("Background color for all skills items (only for type=pie)", 'trx_utils'),
						'dependency' => array(
							'element' => 'type',
							'value' => array('pie')
						),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "border_color",
						"heading" => __("Border color", 'trx_utils'),
						"description" => __("Border color for all skills items (only for type=pie)", 'trx_utils'),
						'dependency' => array(
							'element' => 'type',
							'value' => array('pie')
						),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", 'trx_utils'),
						"description" => __("Align skills block to left or right side", 'trx_utils'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "arc_caption",
						"heading" => __("Arc caption", 'trx_utils'),
						"description" => __("Arc caption - text in the center of the diagram", 'trx_utils'),
						'dependency' => array(
							'element' => 'type',
							'value' => array('arc')
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "pie_compact",
						"heading" => __("Pie compact", 'trx_utils'),
						"description" => __("Show all skills in one diagram or as separate diagrams", 'trx_utils'),
						'dependency' => array(
							'element' => 'type',
							'value' => array('pie')
						),
						"class" => "",
						"value" => array(__('Show all skills in one diagram', 'trx_utils') => 'on'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "pie_cutout",
						"heading" => __("Pie cutout", 'trx_utils'),
						"description" => __("Pie cutout (0-99). 0 - without cutout, 99 - max cutout", 'trx_utils'),
						'dependency' => array(
							'element' => 'type',
							'value' => array('pie')
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "title",
						"heading" => __("Title", 'trx_utils'),
						"description" => __("Title for the block", 'trx_utils'),
						"admin_label" => true,
						"group" => __('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => __("Subtitle", 'trx_utils'),
						"description" => __("Subtitle for the block", 'trx_utils'),
						"group" => __('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => __("Description", 'trx_utils'),
						"description" => __("Description for the block", 'trx_utils'),
						"group" => __('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					array(
						"param_name" => "link",
						"heading" => __("Button URL", 'trx_utils'),
						"description" => __("Link URL for the button at the bottom of the block", 'trx_utils'),
						"group" => __('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link_caption",
						"heading" => __("Button caption", 'trx_utils'),
						"description" => __("Caption for the button at the bottom of the block", 'trx_utils'),
						"group" => __('Captions', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css'],
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			
			vc_map( array(
				"base" => "trx_skills_item",
				"name" => __("Skill", 'trx_utils'),
				"description" => __("Skills item", 'trx_utils'),
				"show_settings_on_create" => true,
				"class" => "trx_sc_single trx_sc_skills_item",
				"content_element" => true,
				"is_container" => false,
				"as_child" => array('only' => 'trx_skills'),
				"as_parent" => array('except' => 'trx_skills'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => __("Title", 'trx_utils'),
						"description" => __("Title for the current skills item", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "value",
						"heading" => __("Value", 'trx_utils'),
						"description" => __("Value for the current skills item", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "50",
						"type" => "textfield"
					),
					array(
						"param_name" => "color",
						"heading" => __("Color", 'trx_utils'),
						"description" => __("Color for current skills item", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", 'trx_utils'),
						"description" => __("Background color for current skills item (only for type=pie)", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "border_color",
						"heading" => __("Border color", 'trx_utils'),
						"description" => __("Border color for current skills item (only for type=pie)", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "style",
						"heading" => __("Counter style", 'trx_utils'),
						"description" => __("Select style for the current skills item (only for type=counter)", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(themerex_get_list_styles(1, 4)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon",
						"heading" => __("Counter icon", 'trx_utils'),
						"description" => __("Select icon from Fontello icons set, placed before counter (only for type=counter)", 'trx_utils'),
						"class" => "",
						"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Skills extends THEMEREX_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_Skills_Item extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Slider
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_slider",
				"name" => __("Slider", 'trx_utils'),
				"description" => __("Insert slider", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				'icon' => 'icon_trx_slider',
				"class" => "trx_sc_collection trx_sc_slider",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_slider_item'),
				"params" => array_merge(array(
					array(
						"param_name" => "engine",
						"heading" => __("Engine", 'trx_utils'),
						"description" => __("Select engine for slider. Attention! Swiper is built-in engine, all other engines appears only if corresponding plugings are installed", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['sliders']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => __("Float slider", 'trx_utils'),
						"description" => __("Float slider to left or right side", 'trx_utils'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "custom",
						"heading" => __("Custom slides", 'trx_utils'),
						"description" => __("Make custom slides from inner shortcodes (prepare it on tabs) or prepare slides from posts thumbnails", 'trx_utils'),
						"class" => "",
						"value" => array(__('Custom slides', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					)
					),
					themerex_exists_revslider() ? array(
					array(
						"param_name" => "alias",
						"heading" => __("Revolution slider alias or Royal Slider ID", 'trx_utils'),
						"description" => __("Alias for Revolution slider or Royal slider ID", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						'dependency' => array(
							'element' => 'engine',
							'value' => array('revo','royal')
						),
						"value" => "",
						"type" => "textfield"
					)) : array(), array(
					array(
						"param_name" => "cat",
						"heading" => __("Categories list", 'trx_utils'),
						"description" => __("Select category. If empty - show posts from any category or from IDs list", 'trx_utils'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array_flip(themerex_array_merge(array(0 => __('- Select category -', 'trx_utils')), $THEMEREX_GLOBALS['sc_params']['categories'])),
						"type" => "dropdown"
					),
					array(
						"param_name" => "count",
						"heading" => __("Swiper: Number of posts", 'trx_utils'),
						"description" => __("How many posts will be displayed? If used IDs - this parameter ignored.", 'trx_utils'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => __("Swiper: Offset before select posts", 'trx_utils'),
						"description" => __("Skip posts before select next part.", 'trx_utils'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => __("Swiper: Post sorting", 'trx_utils'),
						"description" => __("Select desired posts sorting method", 'trx_utils'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['sorting']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => __("Swiper: Post order", 'trx_utils'),
						"description" => __("Select desired posts order", 'trx_utils'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['ordering']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => __("Swiper: Post IDs list", 'trx_utils'),
						"description" => __("Comma separated list of posts ID. If set - parameters above are ignored!", 'trx_utils'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "controls",
						"heading" => __("Swiper: Show slider controls", 'trx_utils'),
						"description" => __("Show arrows inside slider", 'trx_utils'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(__('Show controls', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "pagination",
						"heading" => __("Swiper: Show slider pagination", 'trx_utils'),
						"description" => __("Show bullets or titles to switch slides", 'trx_utils'),
						"group" => __('Details', 'trx_utils'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(
								__('Dots', 'trx_utils') => 'yes',
								__('Side Titles', 'trx_utils') => 'full',
								__('Over Titles', 'trx_utils') => 'over',
								__('None', 'trx_utils') => 'no'
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "titles",
						"heading" => __("Swiper: Show titles section", 'trx_utils'),
						"description" => __("Show section with post's title and short post's description", 'trx_utils'),
						"group" => __('Details', 'trx_utils'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(
								__('Not show', 'trx_utils') => "no",
								__('Show/Hide info', 'trx_utils') => "slide",
								__('Fixed info', 'trx_utils') => "fixed"
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "descriptions",
						"heading" => __("Swiper: Post descriptions", 'trx_utils'),
						"description" => __("Show post's excerpt max length (characters)", 'trx_utils'),
						"group" => __('Details', 'trx_utils'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "links",
						"heading" => __("Swiper: Post's title as link", 'trx_utils'),
						"description" => __("Make links from post's titles", 'trx_utils'),
						"group" => __('Details', 'trx_utils'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(__('Titles as a links', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "crop",
						"heading" => __("Swiper: Crop images", 'trx_utils'),
						"description" => __("Crop images in each slide or live it unchanged", 'trx_utils'),
						"group" => __('Details', 'trx_utils'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(__('Crop images', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "autoheight",
						"heading" => __("Swiper: Autoheight", 'trx_utils'),
						"description" => __("Change whole slider's height (make it equal current slide's height)", 'trx_utils'),
						"group" => __('Details', 'trx_utils'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(__('Autoheight', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "interval",
						"heading" => __("Swiper: Slides change interval", 'trx_utils'),
						"description" => __("Slides change interval (in milliseconds: 1000ms = 1s)", 'trx_utils'),
						"group" => __('Details', 'trx_utils'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "5000",
						"type" => "textfield"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css'],
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				))
			) );
			
			
			vc_map( array(
				"base" => "trx_slider_item",
				"name" => __("Slide", 'trx_utils'),
				"description" => __("Slider item - single slide", 'trx_utils'),
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => false,
				'icon' => 'icon_trx_slider_item',
				"as_child" => array('only' => 'trx_slider'),
				"as_parent" => array('except' => 'trx_slider'),
				"params" => array(
					array(
						"param_name" => "src",
						"heading" => __("URL (source) for image file", 'trx_utils'),
						"description" => __("Select or upload image or write URL from other site for the current slide", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Slider extends THEMEREX_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_Slider_Item extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Socials
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_socials",
				"name" => __("Social icons", 'trx_utils'),
				"description" => __("Custom social icons", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				'icon' => 'icon_trx_socials',
				"class" => "trx_sc_collection trx_sc_socials",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_social_item'),
				"params" => array_merge(array(
					array(
						"param_name" => "type",
						"heading" => __("Icon's type", 'trx_utils'),
						"description" => __("Type of the icons - images or font icons", 'trx_utils'),
						"class" => "",
						"std" => themerex_get_theme_setting('socials_type'),
						"value" => array(
							__('Icons', 'trx_utils') => 'icons',
							__('Images', 'trx_utils') => 'images'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "size",
						"heading" => __("Icon's size", 'trx_utils'),
						"description" => __("Size of the icons", 'trx_utils'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['sizes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "shape",
						"heading" => __("Icon's shape", 'trx_utils'),
						"description" => __("Shape of the icons", 'trx_utils'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['shapes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "socials",
						"heading" => __("Manual socials list", 'trx_utils'),
						"description" => __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebooc.com/my_profile. If empty - use socials from Theme options.", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "custom",
						"heading" => __("Custom socials", 'trx_utils'),
						"description" => __("Make custom icons from inner shortcodes (prepare it on tabs)", 'trx_utils'),
						"class" => "",
						"value" => array(__('Custom socials', 'trx_utils') => 'yes'),
						"type" => "checkbox"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css'],
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				))
			) );
			
			
			vc_map( array(
				"base" => "trx_social_item",
				"name" => __("Custom social item", 'trx_utils'),
				"description" => __("Custom social item: name, profile url and icon url", 'trx_utils'),
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => false,
				'icon' => 'icon_trx_social_item',
				"as_child" => array('only' => 'trx_socials'),
				"as_parent" => array('except' => 'trx_socials'),
				"params" => array(
					array(
						"param_name" => "name",
						"heading" => __("Social name", 'trx_utils'),
						"description" => __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "url",
						"heading" => __("Your profile URL", 'trx_utils'),
						"description" => __("URL of your profile in specified social network", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon",
						"heading" => __("URL (source) for icon file", 'trx_utils'),
						"description" => __("Select or upload image or write URL from other site for the current social icon", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					)
				)
			) );
			
			class WPBakeryShortCode_Trx_Socials extends THEMEREX_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_Social_Item extends THEMEREX_VC_ShortCodeSingle {}
			

			
			
			
			
			
			// Table
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_table",
				"name" => __("Table", 'trx_utils'),
				"description" => __("Insert a table", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				'icon' => 'icon_trx_table',
				"class" => "trx_sc_container trx_sc_table",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "align",
						"heading" => __("Cells content alignment", 'trx_utils'),
						"description" => __("Select alignment for each table cell", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "content",
						"heading" => __("Table content", 'trx_utils'),
						"description" => __("Content, created with any table-generator", 'trx_utils'),
						"class" => "",
						"value" => "Paste here table content, generated on one of many public internet resources, for example: http://www.impressivewebs.com/html-table-code-generator/ or http://html-tables.com/",
						"type" => "textarea_html"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css'],
					themerex_vc_width(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				),
				'js_view' => 'VcTrxTextContainerView'
			) );
			
			class WPBakeryShortCode_Trx_Table extends THEMEREX_VC_ShortCodeContainer {}
			
			
			
			
			
			
			
			// Tabs
			//-------------------------------------------------------------------------------------
			
			$tab_id_1 = 'sc_tab_'.time() . '_1_' . rand( 0, 100 );
			$tab_id_2 = 'sc_tab_'.time() . '_2_' . rand( 0, 100 );
			vc_map( array(
				"base" => "trx_tabs",
				"name" => __("Tabs", 'trx_utils'),
				"description" => __("Tabs", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				'icon' => 'icon_trx_tabs',
				"class" => "trx_sc_collection trx_sc_tabs",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_tab'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Tabs style", 'trx_utils'),
						"description" => __("Select style of tabs items", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(themerex_get_list_styles(1, 2)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "initial",
						"heading" => __("Initially opened tab", 'trx_utils'),
						"description" => __("Number of initially opened tab", 'trx_utils'),
						"class" => "",
						"value" => 1,
						"type" => "textfield"
					),
					array(
						"param_name" => "scroll",
						"heading" => __("Scroller", 'trx_utils'),
						"description" => __("Use scroller to show tab content (height parameter required)", 'trx_utils'),
						"class" => "",
						"value" => array("Use scroller" => "yes" ),
						"type" => "checkbox"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css'],
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				),
				'default_content' => '
					[trx_tab title="' . __( 'Tab 1', 'trx_utils') . '" tab_id="'.esc_attr($tab_id_1).'"][/trx_tab]
					[trx_tab title="' . __( 'Tab 2', 'trx_utils') . '" tab_id="'.esc_attr($tab_id_2).'"][/trx_tab]
				',
				"custom_markup" => '
					<div class="wpb_tabs_holder wpb_holder vc_container_for_children">
						<ul class="tabs_controls">
						</ul>
						%content%
					</div>
				',
				'js_view' => 'VcTrxTabsView'
			) );
			
			
			vc_map( array(
				"base" => "trx_tab",
				"name" => __("Tab item", 'trx_utils'),
				"description" => __("Single tab item", 'trx_utils'),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_tab",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_tab',
				"as_child" => array('only' => 'trx_tabs'),
				"as_parent" => array('except' => 'trx_tabs'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => __("Tab title", 'trx_utils'),
						"description" => __("Title for current tab", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "tab_id",
						"heading" => __("Tab ID", 'trx_utils'),
						"description" => __("ID for current tab (required). Please, start it from letter.", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['css']
				),
			  'js_view' => 'VcTrxTabView'
			) );
			class WPBakeryShortCode_Trx_Tabs extends THEMEREX_VC_ShortCodeTabs {}
			class WPBakeryShortCode_Trx_Tab extends THEMEREX_VC_ShortCodeTab {}
			
			
			
			
			
			
			
			// Title
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_title",
				"name" => __("Title", 'trx_utils'),
				"description" => __("Create header tag (1-6 level) with many styles", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				'icon' => 'icon_trx_title',
				"class" => "trx_sc_single trx_sc_title",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "content",
						"heading" => __("Title content", 'trx_utils'),
						"description" => __("Title content", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					array(
						"param_name" => "type",
						"heading" => __("Title type", 'trx_utils'),
						"description" => __("Title type (header level)", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Header 1', 'trx_utils') => '1',
							__('Header 2', 'trx_utils') => '2',
							__('Header 3', 'trx_utils') => '3',
							__('Header 4', 'trx_utils') => '4',
							__('Header 5', 'trx_utils') => '5',
							__('Header 6', 'trx_utils') => '6'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "style",
						"heading" => __("Title style", 'trx_utils'),
						"description" => __("Title style: only text (regular) or with icon/image (iconed)", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Regular', 'trx_utils') => 'regular',
							__('Underline', 'trx_utils') => 'underline',
							__('Divider', 'trx_utils') => 'divider',
							__('With icon (image)', 'trx_utils') => 'iconed',
                            __('With dots', 'trx_utils') => 'dotted'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", 'trx_utils'),
						"description" => __("Title text alignment", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "font_size",
						"heading" => __("Font size", 'trx_utils'),
						"description" => __("Custom font size. If empty - use theme default", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_weight",
						"heading" => __("Font weight", 'trx_utils'),
						"description" => __("Custom font weight. If empty or inherit - use theme default", 'trx_utils'),
						"class" => "",
						"value" => array(
							__('Default', 'trx_utils') => 'inherit',
							__('Thin (100)', 'trx_utils') => '100',
							__('Light (300)', 'trx_utils') => '300',
							__('Normal (400)', 'trx_utils') => '400',
							__('Semibold (600)', 'trx_utils') => '600',
							__('Bold (700)', 'trx_utils') => '700',
							__('Black (900)', 'trx_utils') => '900'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Title color", 'trx_utils'),
						"description" => __("Select color for the title", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "icon",
						"heading" => __("Title font icon", 'trx_utils'),
						"description" => __("Select font icon for the title from Fontello icons set (if style=iconed)", 'trx_utils'),
						"class" => "",
						"group" => __('Icon &amp; Image', 'trx_utils'),
						'dependency' => array(
							'element' => 'style',
							'value' => array('iconed')
						),
						"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "image",
						"heading" => __("or image icon", 'trx_utils'),
						"description" => __("Select image icon for the title instead icon above (if style=iconed)", 'trx_utils'),
						"class" => "",
						"group" => __('Icon &amp; Image', 'trx_utils'),
						'dependency' => array(
							'element' => 'style',
							'value' => array('iconed')
						),
						"value" => $THEMEREX_GLOBALS['sc_params']['images'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "picture",
						"heading" => __("or select uploaded image", 'trx_utils'),
						"description" => __("Select or upload image or write URL from other site (if style=iconed)", 'trx_utils'),
						"group" => __('Icon &amp; Image', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "image_size",
						"heading" => __("Image (picture) size", 'trx_utils'),
						"description" => __("Select image (picture) size (if style=iconed)", 'trx_utils'),
						"group" => __('Icon &amp; Image', 'trx_utils'),
						"class" => "",
						"value" => array(
							__('Small', 'trx_utils') => 'small',
							__('Medium', 'trx_utils') => 'medium',
							__('Large', 'trx_utils') => 'large'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "position",
						"heading" => __("Icon (image) position", 'trx_utils'),
						"description" => __("Select icon (image) position (if style=iconed)", 'trx_utils'),
						"group" => __('Icon &amp; Image', 'trx_utils'),
						"class" => "",
						"value" => array(
							__('Top', 'trx_utils') => 'top',
							__('Left', 'trx_utils') => 'left'
						),
						"type" => "dropdown"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css'],
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_Title extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Toggles
			//-------------------------------------------------------------------------------------
				
			vc_map( array(
				"base" => "trx_toggles",
				"name" => __("Toggles", 'trx_utils'),
				"description" => __("Toggles items", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				'icon' => 'icon_trx_toggles',
				"class" => "trx_sc_collection trx_sc_toggles",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_toggles_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Toggles style", 'trx_utils'),
						"description" => __("Select style for display toggles", 'trx_utils'),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip(themerex_get_list_styles(1, 2)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "counter",
						"heading" => __("Counter", 'trx_utils'),
						"description" => __("Display counter before each toggles title", 'trx_utils'),
						"class" => "",
						"value" => array("Add item numbers before each element" => "on" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "icon_closed",
						"heading" => __("Icon while closed", 'trx_utils'),
						"description" => __("Select icon for the closed toggles item from Fontello icons set", 'trx_utils'),
						"class" => "",
						"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_opened",
						"heading" => __("Icon while opened", 'trx_utils'),
						"description" => __("Select icon for the opened toggles item from Fontello icons set", 'trx_utils'),
						"class" => "",
						"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				),
				'default_content' => '
					[trx_toggles_item title="' . __( 'Item 1 title', 'trx_utils') . '"][/trx_toggles_item]
					[trx_toggles_item title="' . __( 'Item 2 title', 'trx_utils') . '"][/trx_toggles_item]
				',
				"custom_markup" => '
					<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
						%content%
					</div>
					<div class="tab_controls">
						<button class="add_tab" title="'.__("Add item", 'trx_utils').'">'.__("Add item", 'trx_utils').'</button>
					</div>
				',
				'js_view' => 'VcTrxTogglesView'
			) );
			
			
			vc_map( array(
				"base" => "trx_toggles_item",
				"name" => __("Toggles item", 'trx_utils'),
				"description" => __("Single toggles item", 'trx_utils'),
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_toggles_item',
				"as_child" => array('only' => 'trx_toggles'),
				"as_parent" => array('except' => 'trx_toggles'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => __("Title", 'trx_utils'),
						"description" => __("Title for current toggles item", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "open",
						"heading" => __("Open on show", 'trx_utils'),
						"description" => __("Open current toggle item on show", 'trx_utils'),
						"class" => "",
						"value" => array("Opened" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "icon_closed",
						"heading" => __("Icon while closed", 'trx_utils'),
						"description" => __("Select icon for the closed toggles item from Fontello icons set", 'trx_utils'),
						"class" => "",
						"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_opened",
						"heading" => __("Icon while opened", 'trx_utils'),
						"description" => __("Select icon for the opened toggles item from Fontello icons set", 'trx_utils'),
						"class" => "",
						"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTogglesTabView'
			) );
			class WPBakeryShortCode_Trx_Toggles extends THEMEREX_VC_ShortCodeToggles {}
			class WPBakeryShortCode_Trx_Toggles_Item extends THEMEREX_VC_ShortCodeTogglesItem {}
			
			
			
			
			
			
			// Twitter
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "trx_twitter",
				"name" => __("Twitter", 'trx_utils'),
				"description" => __("Insert twitter feed into post (page)", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				'icon' => 'icon_trx_twitter',
				"class" => "trx_sc_single trx_sc_twitter",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "user",
						"heading" => __("Twitter Username", 'trx_utils'),
						"description" => __("Your username in the twitter account. If empty - get it from Theme Options.", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "consumer_key",
						"heading" => __("Consumer Key", 'trx_utils'),
						"description" => __("Consumer Key from the twitter account", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "consumer_secret",
						"heading" => __("Consumer Secret", 'trx_utils'),
						"description" => __("Consumer Secret from the twitter account", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "token_key",
						"heading" => __("Token Key", 'trx_utils'),
						"description" => __("Token Key from the twitter account", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "token_secret",
						"heading" => __("Token Secret", 'trx_utils'),
						"description" => __("Token Secret from the twitter account", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => __("Tweets number", 'trx_utils'),
						"description" => __("Number tweets to show", 'trx_utils'),
						"class" => "",
						"divider" => true,
						"value" => 3,
						"type" => "textfield"
					),
					array(
						"param_name" => "controls",
						"heading" => __("Show arrows", 'trx_utils'),
						"description" => __("Show control buttons", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['yes_no']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "interval",
						"heading" => __("Tweets change interval", 'trx_utils'),
						"description" => __("Tweets change interval (in milliseconds: 1000ms = 1s)", 'trx_utils'),
						"class" => "",
						"value" => "7000",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", 'trx_utils'),
						"description" => __("Alignment of the tweets block", 'trx_utils'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "autoheight",
						"heading" => __("Autoheight", 'trx_utils'),
						"description" => __("Change whole slider's height (make it equal current slide's height)", 'trx_utils'),
						"class" => "",
						"value" => array("Autoheight" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scheme",
						"heading" => __("Color scheme", 'trx_utils'),
						"description" => __("Select color scheme for this block", 'trx_utils'),
						"group" => __('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['schemes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", 'trx_utils'),
						"description" => __("Any background color for this section", 'trx_utils'),
						"group" => __('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => __("Background image URL", 'trx_utils'),
						"description" => __("Select background image from library for this section", 'trx_utils'),
						"group" => __('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => __("Overlay", 'trx_utils'),
						"description" => __("Overlay color opacity (from 0.0 to 1.0)", 'trx_utils'),
						"group" => __('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => __("Texture", 'trx_utils'),
						"description" => __("Texture style from 1 to 11. Empty or 0 - without texture.", 'trx_utils'),
						"group" => __('Colors and Images', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css'],
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				),
			) );
			
			class WPBakeryShortCode_Trx_Twitter extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Video
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_video",
				"name" => __("Video", 'trx_utils'),
				"description" => __("Insert video player", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				'icon' => 'icon_trx_video',
				"class" => "trx_sc_single trx_sc_video",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "url",
						"heading" => __("URL for video file", 'trx_utils'),
						"description" => __("Paste URL for video file", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "ratio",
						"heading" => __("Ratio", 'trx_utils'),
						"description" => __("Select ratio for display video", 'trx_utils'),
						"class" => "",
						"value" => array(
							__('16:9', 'trx_utils') => "16:9",
							__('4:3', 'trx_utils') => "4:3"
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "autoplay",
						"heading" => __("Autoplay video", 'trx_utils'),
						"description" => __("Autoplay video on page load", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array("Autoplay" => "on" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", 'trx_utils'),
						"description" => __("Select block alignment", 'trx_utils'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "image",
						"heading" => __("Cover image", 'trx_utils'),
						"description" => __("Select or upload image or write URL from other site for video preview", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_image",
						"heading" => __("Background image", 'trx_utils'),
						"description" => __("Select or upload image or write URL from other site for video background. Attention! If you use background image - specify paddings below from background margins to video block in percents!", 'trx_utils'),
						"group" => __('Background', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_top",
						"heading" => __("Top offset", 'trx_utils'),
						"description" => __("Top offset (padding) from background image to video block (in percent). For example: 3%", 'trx_utils'),
						"group" => __('Background', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_bottom",
						"heading" => __("Bottom offset", 'trx_utils'),
						"description" => __("Bottom offset (padding) from background image to video block (in percent). For example: 3%", 'trx_utils'),
						"group" => __('Background', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_left",
						"heading" => __("Left offset", 'trx_utils'),
						"description" => __("Left offset (padding) from background image to video block (in percent). For example: 20%", 'trx_utils'),
						"group" => __('Background', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_right",
						"heading" => __("Right offset", 'trx_utils'),
						"description" => __("Right offset (padding) from background image to video block (in percent). For example: 12%", 'trx_utils'),
						"group" => __('Background', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css'],
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Video extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Zoom
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_zoom",
				"name" => __("Zoom", 'trx_utils'),
				"description" => __("Insert the image with zoom/lens effect", 'trx_utils'),
				"category" => __('Content', 'trx_utils'),
				'icon' => 'icon_trx_zoom',
				"class" => "trx_sc_single trx_sc_zoom",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "effect",
						"heading" => __("Effect", 'trx_utils'),
						"description" => __("Select effect to display overlapping image", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Lens', 'trx_utils') => 'lens',
							__('Zoom', 'trx_utils') => 'zoom'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "url",
						"heading" => __("Main image", 'trx_utils'),
						"description" => __("Select or upload main image", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "over",
						"heading" => __("Overlaping image", 'trx_utils'),
						"description" => __("Select or upload overlaping image", 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", 'trx_utils'),
						"description" => __("Float zoom to left or right side", 'trx_utils'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_image",
						"heading" => __("Background image", 'trx_utils'),
						"description" => __("Select or upload image or write URL from other site for zoom background. Attention! If you use background image - specify paddings below from background margins to video block in percents!", 'trx_utils'),
						"group" => __('Background', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_top",
						"heading" => __("Top offset", 'trx_utils'),
						"description" => __("Top offset (padding) from background image to zoom block (in percent). For example: 3%", 'trx_utils'),
						"group" => __('Background', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_bottom",
						"heading" => __("Bottom offset", 'trx_utils'),
						"description" => __("Bottom offset (padding) from background image to zoom block (in percent). For example: 3%", 'trx_utils'),
						"group" => __('Background', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_left",
						"heading" => __("Left offset", 'trx_utils'),
						"description" => __("Left offset (padding) from background image to zoom block (in percent). For example: 20%", 'trx_utils'),
						"group" => __('Background', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_right",
						"heading" => __("Right offset", 'trx_utils'),
						"description" => __("Right offset (padding) from background image to zoom block (in percent). For example: 12%", 'trx_utils'),
						"group" => __('Background', 'trx_utils'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css'],
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Zoom extends THEMEREX_VC_ShortCodeSingle {}
			

			do_action('themerex_action_shortcodes_list_vc');
			
			
			if (false && themerex_exists_woocommerce()) {
			
				// WooCommerce - Cart
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "woocommerce_cart",
					"name" => __("Cart", 'trx_utils'),
					"description" => __("WooCommerce shortcode: show cart page", 'trx_utils'),
					"category" => __('WooCommerce', 'trx_utils'),
					'icon' => 'icon_trx_wooc_cart',
					"class" => "trx_sc_alone trx_sc_woocommerce_cart",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => false,
					"params" => array(
						array(
							"param_name" => "dummy",
							"heading" => __("Dummy data", 'trx_utils'),
							"description" => __("Dummy data - not used in shortcodes", 'trx_utils'),
							"class" => "",
							"value" => "",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Woocommerce_Cart extends THEMEREX_VC_ShortCodeAlone {}
			
			
				// WooCommerce - Checkout
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "woocommerce_checkout",
					"name" => __("Checkout", 'trx_utils'),
					"description" => __("WooCommerce shortcode: show checkout page", 'trx_utils'),
					"category" => __('WooCommerce', 'trx_utils'),
					'icon' => 'icon_trx_wooc_checkout',
					"class" => "trx_sc_alone trx_sc_woocommerce_checkout",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => false,
					"params" => array(
						array(
							"param_name" => "dummy",
							"heading" => __("Dummy data", 'trx_utils'),
							"description" => __("Dummy data - not used in shortcodes", 'trx_utils'),
							"class" => "",
							"value" => "",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Woocommerce_Checkout extends THEMEREX_VC_ShortCodeAlone {}
			
			
				// WooCommerce - My Account
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "woocommerce_my_account",
					"name" => __("My Account", 'trx_utils'),
					"description" => __("WooCommerce shortcode: show my account page", 'trx_utils'),
					"category" => __('WooCommerce', 'trx_utils'),
					'icon' => 'icon_trx_wooc_my_account',
					"class" => "trx_sc_alone trx_sc_woocommerce_my_account",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => false,
					"params" => array(
						array(
							"param_name" => "dummy",
							"heading" => __("Dummy data", 'trx_utils'),
							"description" => __("Dummy data - not used in shortcodes", 'trx_utils'),
							"class" => "",
							"value" => "",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Woocommerce_My_Account extends THEMEREX_VC_ShortCodeAlone {}
			
			
				// WooCommerce - Order Tracking
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "woocommerce_order_tracking",
					"name" => __("Order Tracking", 'trx_utils'),
					"description" => __("WooCommerce shortcode: show order tracking page", 'trx_utils'),
					"category" => __('WooCommerce', 'trx_utils'),
					'icon' => 'icon_trx_wooc_order_tracking',
					"class" => "trx_sc_alone trx_sc_woocommerce_order_tracking",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => false,
					"params" => array(
						array(
							"param_name" => "dummy",
							"heading" => __("Dummy data", 'trx_utils'),
							"description" => __("Dummy data - not used in shortcodes", 'trx_utils'),
							"class" => "",
							"value" => "",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Woocommerce_Order_Tracking extends THEMEREX_VC_ShortCodeAlone {}
			
			
				// WooCommerce - Shop Messages
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "shop_messages",
					"name" => __("Shop Messages", 'trx_utils'),
					"description" => __("WooCommerce shortcode: show shop messages", 'trx_utils'),
					"category" => __('WooCommerce', 'trx_utils'),
					'icon' => 'icon_trx_wooc_shop_messages',
					"class" => "trx_sc_alone trx_sc_shop_messages",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => false,
					"params" => array(
						array(
							"param_name" => "dummy",
							"heading" => __("Dummy data", 'trx_utils'),
							"description" => __("Dummy data - not used in shortcodes", 'trx_utils'),
							"class" => "",
							"value" => "",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Shop_Messages extends THEMEREX_VC_ShortCodeAlone {}
			
			
				// WooCommerce - Product Page
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "product_page",
					"name" => __("Product Page", 'trx_utils'),
					"description" => __("WooCommerce shortcode: display single product page", 'trx_utils'),
					"category" => __('WooCommerce', 'trx_utils'),
					'icon' => 'icon_trx_product_page',
					"class" => "trx_sc_single trx_sc_product_page",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "sku",
							"heading" => __("SKU", 'trx_utils'),
							"description" => __("SKU code of displayed product", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "id",
							"heading" => __("ID", 'trx_utils'),
							"description" => __("ID of displayed product", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "posts_per_page",
							"heading" => __("Number", 'trx_utils'),
							"description" => __("How many products showed", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "post_type",
							"heading" => __("Post type", 'trx_utils'),
							"description" => __("Post type for the WP query (leave 'product')", 'trx_utils'),
							"class" => "",
							"value" => "product",
							"type" => "textfield"
						),
						array(
							"param_name" => "post_status",
							"heading" => __("Post status", 'trx_utils'),
							"description" => __("Display posts only with this status", 'trx_utils'),
							"class" => "",
							"value" => array(
								__('Publish', 'trx_utils') => 'publish',
								__('Protected', 'trx_utils') => 'protected',
								__('Private', 'trx_utils') => 'private',
								__('Pending', 'trx_utils') => 'pending',
								__('Draft', 'trx_utils') => 'draft'
							),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Product_Page extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Product
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "product",
					"name" => __("Product", 'trx_utils'),
					"description" => __("WooCommerce shortcode: display one product", 'trx_utils'),
					"category" => __('WooCommerce', 'trx_utils'),
					'icon' => 'icon_trx_product',
					"class" => "trx_sc_single trx_sc_product",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "sku",
							"heading" => __("SKU", 'trx_utils'),
							"description" => __("Product's SKU code", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "id",
							"heading" => __("ID", 'trx_utils'),
							"description" => __("Product's ID", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Product extends THEMEREX_VC_ShortCodeSingle {}
			
			
				// WooCommerce - Best Selling Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "best_selling_products",
					"name" => __("Best Selling Products", 'trx_utils'),
					"description" => __("WooCommerce shortcode: show best selling products", 'trx_utils'),
					"category" => __('WooCommerce', 'trx_utils'),
					'icon' => 'icon_trx_best_selling_products',
					"class" => "trx_sc_single trx_sc_best_selling_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => __("Number", 'trx_utils'),
							"description" => __("How many products showed", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", 'trx_utils'),
							"description" => __("How many columns per row use for products output", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Best_Selling_Products extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Recent Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "recent_products",
					"name" => __("Recent Products", 'trx_utils'),
					"description" => __("WooCommerce shortcode: show recent products", 'trx_utils'),
					"category" => __('WooCommerce', 'trx_utils'),
					'icon' => 'icon_trx_recent_products',
					"class" => "trx_sc_single trx_sc_recent_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => __("Number", 'trx_utils'),
							"description" => __("How many products showed", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", 'trx_utils'),
							"description" => __("How many columns per row use for products output", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => __("Order by", 'trx_utils'),
							"description" => __("Sorting order for products output", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'trx_utils') => 'date',
								__('Title', 'trx_utils') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => __("Order", 'trx_utils'),
							"description" => __("Sorting order for products output", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($THEMEREX_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Recent_Products extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Related Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "related_products",
					"name" => __("Related Products", 'trx_utils'),
					"description" => __("WooCommerce shortcode: show related products", 'trx_utils'),
					"category" => __('WooCommerce', 'trx_utils'),
					'icon' => 'icon_trx_related_products',
					"class" => "trx_sc_single trx_sc_related_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "posts_per_page",
							"heading" => __("Number", 'trx_utils'),
							"description" => __("How many products showed", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", 'trx_utils'),
							"description" => __("How many columns per row use for products output", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => __("Order by", 'trx_utils'),
							"description" => __("Sorting order for products output", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'trx_utils') => 'date',
								__('Title', 'trx_utils') => 'title'
							),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Related_Products extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Featured Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "featured_products",
					"name" => __("Featured Products", 'trx_utils'),
					"description" => __("WooCommerce shortcode: show featured products", 'trx_utils'),
					"category" => __('WooCommerce', 'trx_utils'),
					'icon' => 'icon_trx_featured_products',
					"class" => "trx_sc_single trx_sc_featured_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => __("Number", 'trx_utils'),
							"description" => __("How many products showed", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", 'trx_utils'),
							"description" => __("How many columns per row use for products output", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => __("Order by", 'trx_utils'),
							"description" => __("Sorting order for products output", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'trx_utils') => 'date',
								__('Title', 'trx_utils') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => __("Order", 'trx_utils'),
							"description" => __("Sorting order for products output", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($THEMEREX_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Featured_Products extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Top Rated Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "top_rated_products",
					"name" => __("Top Rated Products", 'trx_utils'),
					"description" => __("WooCommerce shortcode: show top rated products", 'trx_utils'),
					"category" => __('WooCommerce', 'trx_utils'),
					'icon' => 'icon_trx_top_rated_products',
					"class" => "trx_sc_single trx_sc_top_rated_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => __("Number", 'trx_utils'),
							"description" => __("How many products showed", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", 'trx_utils'),
							"description" => __("How many columns per row use for products output", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => __("Order by", 'trx_utils'),
							"description" => __("Sorting order for products output", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'trx_utils') => 'date',
								__('Title', 'trx_utils') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => __("Order", 'trx_utils'),
							"description" => __("Sorting order for products output", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($THEMEREX_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Top_Rated_Products extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Sale Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "sale_products",
					"name" => __("Sale Products", 'trx_utils'),
					"description" => __("WooCommerce shortcode: list products on sale", 'trx_utils'),
					"category" => __('WooCommerce', 'trx_utils'),
					'icon' => 'icon_trx_sale_products',
					"class" => "trx_sc_single trx_sc_sale_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => __("Number", 'trx_utils'),
							"description" => __("How many products showed", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", 'trx_utils'),
							"description" => __("How many columns per row use for products output", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => __("Order by", 'trx_utils'),
							"description" => __("Sorting order for products output", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'trx_utils') => 'date',
								__('Title', 'trx_utils') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => __("Order", 'trx_utils'),
							"description" => __("Sorting order for products output", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($THEMEREX_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Sale_Products extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Product Category
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "product_category",
					"name" => __("Products from category", 'trx_utils'),
					"description" => __("WooCommerce shortcode: list products in specified category(-ies)", 'trx_utils'),
					"category" => __('WooCommerce', 'trx_utils'),
					'icon' => 'icon_trx_product_category',
					"class" => "trx_sc_single trx_sc_product_category",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => __("Number", 'trx_utils'),
							"description" => __("How many products showed", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", 'trx_utils'),
							"description" => __("How many columns per row use for products output", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => __("Order by", 'trx_utils'),
							"description" => __("Sorting order for products output", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'trx_utils') => 'date',
								__('Title', 'trx_utils') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => __("Order", 'trx_utils'),
							"description" => __("Sorting order for products output", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($THEMEREX_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						),
						array(
							"param_name" => "category",
							"heading" => __("Categories", 'trx_utils'),
							"description" => __("Comma separated category slugs", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "operator",
							"heading" => __("Operator", 'trx_utils'),
							"description" => __("Categories operator", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('IN', 'trx_utils') => 'IN',
								__('NOT IN', 'trx_utils') => 'NOT IN',
								__('AND', 'trx_utils') => 'AND'
							),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Product_Category extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "products",
					"name" => __("Products", 'trx_utils'),
					"description" => __("WooCommerce shortcode: list all products", 'trx_utils'),
					"category" => __('WooCommerce', 'trx_utils'),
					'icon' => 'icon_trx_products',
					"class" => "trx_sc_single trx_sc_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "skus",
							"heading" => __("SKUs", 'trx_utils'),
							"description" => __("Comma separated SKU codes of products", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "ids",
							"heading" => __("IDs", 'trx_utils'),
							"description" => __("Comma separated ID of products", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", 'trx_utils'),
							"description" => __("How many columns per row use for products output", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => __("Order by", 'trx_utils'),
							"description" => __("Sorting order for products output", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'trx_utils') => 'date',
								__('Title', 'trx_utils') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => __("Order", 'trx_utils'),
							"description" => __("Sorting order for products output", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($THEMEREX_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Products extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
				// WooCommerce - Product Attribute
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "product_attribute",
					"name" => __("Products by Attribute", 'trx_utils'),
					"description" => __("WooCommerce shortcode: show products with specified attribute", 'trx_utils'),
					"category" => __('WooCommerce', 'trx_utils'),
					'icon' => 'icon_trx_product_attribute',
					"class" => "trx_sc_single trx_sc_product_attribute",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => __("Number", 'trx_utils'),
							"description" => __("How many products showed", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", 'trx_utils'),
							"description" => __("How many columns per row use for products output", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => __("Order by", 'trx_utils'),
							"description" => __("Sorting order for products output", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'trx_utils') => 'date',
								__('Title', 'trx_utils') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => __("Order", 'trx_utils'),
							"description" => __("Sorting order for products output", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($THEMEREX_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						),
						array(
							"param_name" => "attribute",
							"heading" => __("Attribute", 'trx_utils'),
							"description" => __("Attribute name", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "filter",
							"heading" => __("Filter", 'trx_utils'),
							"description" => __("Attribute value", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Product_Attribute extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Products Categories
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "product_categories",
					"name" => __("Product Categories", 'trx_utils'),
					"description" => __("WooCommerce shortcode: show categories with products", 'trx_utils'),
					"category" => __('WooCommerce', 'trx_utils'),
					'icon' => 'icon_trx_product_categories',
					"class" => "trx_sc_single trx_sc_product_categories",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "number",
							"heading" => __("Number", 'trx_utils'),
							"description" => __("How many categories showed", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", 'trx_utils'),
							"description" => __("How many columns per row use for categories output", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => __("Order by", 'trx_utils'),
							"description" => __("Sorting order for products output", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'trx_utils') => 'date',
								__('Title', 'trx_utils') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => __("Order", 'trx_utils'),
							"description" => __("Sorting order for products output", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($THEMEREX_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						),
						array(
							"param_name" => "parent",
							"heading" => __("Parent", 'trx_utils'),
							"description" => __("Parent category slug", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => "date",
							"type" => "textfield"
						),
						array(
							"param_name" => "ids",
							"heading" => __("IDs", 'trx_utils'),
							"description" => __("Comma separated ID of products", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "hide_empty",
							"heading" => __("Hide empty", 'trx_utils'),
							"description" => __("Hide empty categories", 'trx_utils'),
							"class" => "",
							"value" => array("Hide empty" => "1" ),
							"type" => "checkbox"
						)
					)
				) );
				
				class WPBakeryShortCode_Products_Categories extends THEMEREX_VC_ShortCodeSingle {}
			
				/*
			
				// WooCommerce - Add to cart
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "add_to_cart",
					"name" => __("Add to cart", 'trx_utils'),
					"description" => __("WooCommerce shortcode: Display a single product price + cart button", 'trx_utils'),
					"category" => __('WooCommerce', 'trx_utils'),
					'icon' => 'icon_trx_add_to_cart',
					"class" => "trx_sc_single trx_sc_add_to_cart",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "id",
							"heading" => __("ID", 'trx_utils'),
							"description" => __("Product's ID", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "sku",
							"heading" => __("SKU", 'trx_utils'),
							"description" => __("Product's SKU code", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "quantity",
							"heading" => __("Quantity", 'trx_utils'),
							"description" => __("How many item add", 'trx_utils'),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "show_price",
							"heading" => __("Show price", 'trx_utils'),
							"description" => __("Show price near button", 'trx_utils'),
							"class" => "",
							"value" => array("Show price" => "true" ),
							"type" => "checkbox"
						),
						array(
							"param_name" => "class",
							"heading" => __("Class", 'trx_utils'),
							"description" => __("CSS class", 'trx_utils'),
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "style",
							"heading" => __("CSS style", 'trx_utils'),
							"description" => __("CSS style for additional decoration", 'trx_utils'),
							"class" => "",
							"value" => "",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Add_To_Cart extends THEMEREX_VC_ShortCodeSingle {}
				*/
			}

		}
	}
}
?>