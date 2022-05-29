<?php
/**
 * Skin file for the theme.
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('themerex_action_skin_theme_setup')) {
	add_action( 'themerex_action_init_theme', 'themerex_action_skin_theme_setup', 1 );
	function themerex_action_skin_theme_setup() {

		// Add skin fonts in the used fonts list
		add_filter('themerex_filter_used_fonts',			'themerex_filter_skin_used_fonts');
		// Add skin fonts (from Google fonts) in the main fonts list (if not present).
		add_filter('themerex_filter_list_fonts',			'themerex_filter_skin_list_fonts');

		// Add skin stylesheets
		add_action('themerex_action_add_styles',			'themerex_action_skin_add_styles');
		// Add skin inline styles
		add_filter('themerex_filter_add_styles_inline',		'themerex_filter_skin_add_styles_inline');
		// Add skin responsive styles
		add_action('themerex_action_add_responsive',		'themerex_action_skin_add_responsive');
		// Add skin responsive inline styles
		add_filter('themerex_filter_add_responsive_inline',	'themerex_filter_skin_add_responsive_inline');

		// Add skin scripts
		add_action('themerex_action_add_scripts',			'themerex_action_skin_add_scripts');
		// Add skin scripts inline
		add_filter('themerex_action_add_scripts_inline',	'themerex_action_skin_add_scripts_inline');

		// Add skin less files into list for compilation
		add_filter('themerex_filter_compile_less',			'themerex_filter_skin_compile_less');

		// Add color schemes
		themerex_add_color_scheme('original', array(

			'title'					=> esc_html__('Original', 'kidsplanet'),

			// Accent colors
			'accent1'				=> '#ff8454',
			'accent1_hover'			=> '#ff8454',
			'accent2'				=> '#80c3d2',
			'accent2_hover'			=> '#80c3d2',
			'accent3'				=> '#f0cd3a',
			'accent3_hover'			=> '#f0cd3a',
            'accent4'				=> '#88c87b',
            'accent4_hover'			=> '#88c87b',
			
			// Headers, text and links colors
			'text'					=> '#a6a6a6',   //
			'text_light'			=> '#acb4b6',
			'text_dark'				=> '#454a4c',
			'inverse_text'			=> '#ffffff',   //
			'inverse_light'			=> '#ffffff',
			'inverse_dark'			=> '#4e7bae',   // 1-st socials
			'inverse_link'			=> '#2c9ecc',   //2-nd  socials
			'inverse_hover'			=> '#e2564b',   //1-st social in footer
			
			// Whole block border and background
			'bd_color'				=> '#f4f4f4',   //
			'bg_color'				=> '#ffffff',   //
			'bg_image'				=> themerex_get_file_url('images/bg_blue.jpg'),
			'bg_image_position'		=> 'left top',
			'bg_image_repeat'		=> 'repeat',
			'bg_image_attachment'	=> 'scroll',
			'bg_image2'				=> '',
			'bg_image2_position'	=> 'left top',
			'bg_image2_repeat'		=> 'repeat',
			'bg_image2_attachment'	=> 'scroll',
		
			// Alternative blocks (submenu items, form's fields, etc.)
			'alter_text'			=> '#ffffff',
			'alter_light'			=> '#acb4b6',
			'alter_dark'			=> '#232a34',
			'alter_link'			=> '#3a3f41',   // copyright bg
			'alter_hover'			=> '#5e6365',   //
			'alter_bd_color'		=> '#eaeaea',   //
			'alter_bd_hover'		=> '#e6e6e6',   //
			'alter_bg_color'		=> '#f2f3f4',   //
			'alter_bg_hover'		=> '#f0f0f0',
			'alter_bg_image'			=> themerex_get_file_url('images/bg_red.jpg'),
			'alter_bg_image_position'	=> 'left top',
			'alter_bg_image_repeat'		=> 'repeat',
			'alter_bg_image_attachment'	=> 'scroll',
			)
		);

		// Add Custom fonts
		themerex_add_custom_font('h1', array(
			'title'			=> esc_html__('Heading 1', 'kidsplanet'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '3.889em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0',
			'margin-bottom'	=> '0'
			)
		);
		themerex_add_custom_font('h2', array(
			'title'			=> esc_html__('Heading 2', 'kidsplanet'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '2.5em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0',
			'margin-bottom'	=> '0'
			)
		);
		themerex_add_custom_font('h3', array(
			'title'			=> esc_html__('Heading 3', 'kidsplanet'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1.944em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0',
			'margin-bottom'	=> '0'
			)
		);
		themerex_add_custom_font('h4', array(
			'title'			=> esc_html__('Heading 4', 'kidsplanet'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1.389em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0',
			'margin-bottom'	=> '0'
			)
		);
		themerex_add_custom_font('h5', array(
			'title'			=> esc_html__('Heading 5', 'kidsplanet'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1.111em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0',
			'margin-bottom'	=> '0'
			)
		);
		themerex_add_custom_font('h6', array(
			'title'			=> esc_html__('Heading 6', 'kidsplanet'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1em',
			'margin-top'	=> '0',
			'margin-bottom'	=> '0'
			)
		);
		themerex_add_custom_font('p', array(
			'title'			=> esc_html__('Text', 'kidsplanet'),
			'description'	=> '',
			'font-family'	=> 'Oswald',
			'font-size' 	=> '18px',
			'font-weight'	=> '300',
			'font-style'	=> '',
			'line-height'	=> '1.667em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1em'
			)
		);
		themerex_add_custom_font('link', array(
			'title'			=> esc_html__('Links', 'kidsplanet'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> ''
			)
		);
		themerex_add_custom_font('info', array(
			'title'			=> esc_html__('Post info', 'kidsplanet'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1em',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.333em',
			'margin-top'	=> '',
			'margin-bottom'	=> '0.65em'
			)
		);
		themerex_add_custom_font('menu', array(
			'title'			=> esc_html__('Main menu items', 'kidsplanet'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '1.45em',
			'margin-bottom'	=> '1.45em'
			)
		);
		themerex_add_custom_font('submenu', array(
			'title'			=> esc_html__('Dropdown menu items', 'kidsplanet'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '',
			'margin-bottom'	=> ''
			)
		);
		themerex_add_custom_font('logo', array(
			'title'			=> esc_html__('Logo', 'kidsplanet'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '2.8571em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1em',
			'margin-top'	=> '0',
			'margin-bottom'	=> '0'
			)
		);
		themerex_add_custom_font('button', array(
			'title'			=> esc_html__('Buttons', 'kidsplanet'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.3em'
			)
		);
		themerex_add_custom_font('input', array(
			'title'			=> esc_html__('Input fields', 'kidsplanet'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '2.7em'
			)
		);

	}
}





//------------------------------------------------------------------------------
// Skin's fonts
//------------------------------------------------------------------------------

// Add skin fonts in the used fonts list
if (!function_exists('themerex_filter_skin_used_fonts')) {
	//Handler of add_filter('themerex_filter_used_fonts', 'themerex_filter_skin_used_fonts');
	function themerex_filter_skin_used_fonts($theme_fonts) {
        $theme_fonts['Oswald'] = 1;
        $theme_fonts['Roboto Condensed'] = 1;
		return $theme_fonts;
	}
}

// Add skin fonts (from Google fonts) in the main fonts list (if not present).
// To use custom font-face you not need add it into list in this function
// How to install custom @font-face fonts into the theme?
// All @font-face fonts are located in "theme_name/css/font-face/" folder in the separate subfolders for the each font. Subfolder name is a font-family name!
// Place full set of the font files (for each font style and weight) and css-file named stylesheet.css in the each subfolder.
// Create your @font-face kit by using Fontsquirrel @font-face Generator (http://www.fontsquirrel.com/fontface/generator)
// and then extract the font kit (with folder in the kit) into the "theme_name/css/font-face" folder to install
if (!function_exists('themerex_filter_skin_list_fonts')) {
	//Handler of add_filter('themerex_filter_list_fonts', 'themerex_filter_skin_list_fonts');
	function themerex_filter_skin_list_fonts($list) {
        if (!isset($list['Oswald']))	$list['Oswald'] = array(
            'family'=>'sans-serif',
            'link'=>'Oswald:400,300'
        );
        if (!isset($list['Roboto Condensed']))	$list['Roboto Condensed'] = array(
            'family'=>'sans-serif',
            'link'=>'Roboto+Condensed:400'
        );
		return $list;
	}
}



//------------------------------------------------------------------------------
// Skin's stylesheets
//------------------------------------------------------------------------------
// Add skin stylesheets
if (!function_exists('themerex_action_skin_add_styles')) {
	//Handler of add_action('themerex_action_add_styles', 'themerex_action_skin_add_styles');
	function themerex_action_skin_add_styles() {
		// Add stylesheet files
		wp_enqueue_style( 'themerex-skin-style', themerex_get_file_url('skin.css'), array(), null );
		if (file_exists(themerex_get_file_dir('skin.customizer.css')))
			wp_enqueue_style( 'themerex-skin-customizer-style', themerex_get_file_url('skin.customizer.css'), array(), null );
	}
}

// Add skin inline styles
if (!function_exists('themerex_filter_skin_add_styles_inline')) {
	//Handler of add_filter('themerex_filter_add_styles_inline', 'themerex_filter_skin_add_styles_inline');
	function themerex_filter_skin_add_styles_inline($custom_style) {
		// Todo: add skin specific styles in the $custom_style to override
		return $custom_style;	
	}
}

// Add skin responsive styles
if (!function_exists('themerex_action_skin_add_responsive')) {
	//Handler of add_action('themerex_action_add_responsive', 'themerex_action_skin_add_responsive');
	function themerex_action_skin_add_responsive() {
		$suffix = themerex_param_is_off(themerex_get_custom_option('show_sidebar_outer')) ? '' : '-outer';
		if (file_exists(themerex_get_file_dir('skin.responsive'.($suffix).'.css'))) 
			wp_enqueue_style( 'theme-skin-responsive-style', themerex_get_file_url('skin.responsive'.($suffix).'.css'), array(), null );
	}
}

// Add skin responsive inline styles
if (!function_exists('themerex_filter_skin_add_responsive_inline')) {
	//Handler of add_filter('themerex_filter_add_responsive_inline', 'themerex_filter_skin_add_responsive_inline');
	function themerex_filter_skin_add_responsive_inline($custom_style) {
		return $custom_style;	
	}
}

// Add skin.less into list files for compilation
if (!function_exists('themerex_filter_skin_compile_less')) {
	//Handler of add_filter('themerex_filter_compile_less', 'themerex_filter_skin_compile_less');
	function themerex_filter_skin_compile_less($files) {
		if (file_exists(themerex_get_file_dir('skin.less'))) {
		 	$files[] = themerex_get_file_dir('skin.less');
		}
		return $files;	
	}
}



//------------------------------------------------------------------------------
// Skin's scripts
//------------------------------------------------------------------------------

// Add skin scripts
if (!function_exists('themerex_action_skin_add_scripts')) {
	//Handler of add_action('themerex_action_add_scripts', 'themerex_action_skin_add_scripts');
	function themerex_action_skin_add_scripts() {
		if (file_exists(themerex_get_file_dir('skin.js')))
			wp_enqueue_script( 'themerex-skin-script', themerex_get_file_url('skin.js'), array(), null );
		if (themerex_get_theme_option('show_theme_customizer') == 'yes' && file_exists(themerex_get_file_dir('skin.customizer.js')))
			wp_enqueue_script( 'themerex-skin-customizer-script', themerex_get_file_url('skin.customizer.js'), array(), null );
	}
}

// Add skin scripts inline
if (!function_exists('themerex_action_skin_add_scripts_inline')) {
	//Handler of add_action('themerex_action_add_scripts_inline', 'themerex_action_skin_add_scripts_inline');
	function themerex_action_skin_add_scripts_inline($vars=array()) {
		// Todo: add skin specific scripts
        return $vars;
	}
}
?>