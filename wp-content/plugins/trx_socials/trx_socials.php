<?php
/**
 * Plugin Name: ThemeREX Socials
 * Description: Add widgets and shortcodes to display data from social networks
 * Plugin URI: https://themerex.net/?utm_source=wp-plugins&utm_campaign=plugin-uri&utm_medium=wp-dash
 * Author: ThemeREX
 * Version: 1.4.3
 * Author URI: https://themerex.net/?utm_source=trx_effects&utm_campaign=author-uri&utm_medium=wp-dash
 *
 * Text Domain: trx_socials
 * Domain Path: /languages/
 *
 * @package ThemeREX Socials
 * @category Core
 *
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) die( '-1' );

// Plugin's storage
if (!defined('TRX_SOCIALS_PLUGIN_DIR'))				define('TRX_SOCIALS_PLUGIN_DIR', plugin_dir_path(__FILE__));
if (!defined('TRX_SOCIALS_PLUGIN_URL'))				define('TRX_SOCIALS_PLUGIN_URL', plugin_dir_url(__FILE__));
if (!defined('TRX_SOCIALS_PLUGIN_BASE'))			define('TRX_SOCIALS_PLUGIN_BASE',dirname(plugin_basename(__FILE__)));

if (!defined('TRX_SOCIALS_PLUGIN_WIDGETS'))			define('TRX_SOCIALS_PLUGIN_WIDGETS', 'widgets/');
if (!defined('TRX_SOCIALS_PLUGIN_DIR_WIDGETS'))		define('TRX_SOCIALS_PLUGIN_DIR_WIDGETS', TRX_SOCIALS_PLUGIN_DIR.TRX_SOCIALS_PLUGIN_WIDGETS);

$TRX_SOCIALS_STORAGE = array(
	// Arguments to register widgets
	'widgets_args' => array(
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h5 class="widget_title">',
		'after_title'   => '</h5>',
	)
);


//-------------------------------------------------------
//-- Plugin init
//-------------------------------------------------------

// Load plugin's translation file
// Attention! It must be loaded before the first call of any translation function
if ( !function_exists( 'trx_socials_load_plugin_textdomain' ) ) {
	add_action( 'plugins_loaded', 'trx_socials_load_plugin_textdomain');
	function trx_socials_load_plugin_textdomain() {
		static $loaded = false;
		if ( $loaded ) return true;
		$domain = 'trx_socials';
		if ( is_textdomain_loaded( $domain ) && !is_a( $GLOBALS['l10n'][ $domain ], 'NOOP_Translations' ) ) return true;
		$loaded = true;
		load_plugin_textdomain( $domain, false, TRX_SOCIALS_PLUGIN_BASE . '/languages' );
	}
}
	
// Load required styles and scripts in the frontend
if ( !function_exists( 'trx_socials_load_scripts_front' ) ) {
	add_action( 'wp_enqueue_scripts', 'trx_socials_load_scripts_front' );
	function trx_socials_load_scripts_front() {
		wp_enqueue_style(  'trx_socials-icons', trx_socials_get_file_url('assets/css/font_icons/css/trx_socials_icons.css'), array(), null );
	}
}
	
// Add inline css rules
if ( !function_exists( 'trx_socials_enqueue_inline_css' ) ) {
	add_action( 'wp_footer', 'trx_socials_enqueue_inline_css' );
	function trx_socials_enqueue_inline_css() {
		trx_socials_show_layout(apply_filters('trx_socials_filter_inline_css', trx_socials_get_inline_css()), '<style type="text/css" id="trx_socials-inline-styles-inline-css">', '</style>');
	}
}

// Load required styles and scripts in the admin mode
if ( !function_exists( 'trx_socials_load_scripts_admin' ) ) {
	add_action( 'admin_enqueue_scripts', 'trx_socials_load_scripts_admin' );
	add_action( 'elementor/editor/before_enqueue_scripts', 'trx_socials_load_scripts_admin' );
	function trx_socials_load_scripts_admin() {
		wp_enqueue_style(  'trx_socials-icons', trx_socials_get_file_url('assets/css/font_icons/css/trx_socials_icons.css'), array(), null );
		wp_enqueue_style(  'trx_socials-admin', trx_socials_get_file_url('assets/css/trx_socials_admin.css'), array(), null );
		wp_enqueue_script( 'trx_socials-admin', trx_socials_get_file_url('assets/js/trx_socials_admin.js'), array('jquery'), null, true );
		wp_enqueue_script( 'trx_socials-utils', trx_socials_get_file_url('assets/js/trx_socials_utils.js'), array('jquery'), null, true );
	}
}

// Add variables in the admin mode
if ( !function_exists( 'trx_socials_localize_scripts_admin' ) ) {
	add_action( 'customize_controls_print_footer_scripts', 'trx_socials_localize_scripts_admin' );
	add_action( 'admin_footer', 'trx_socials_localize_scripts_admin' );
	add_action( 'wp_footer', 'trx_socials_localize_scripts_admin' );
	function trx_socials_localize_scripts_admin() {
		// Add variables into JS
		wp_localize_script( is_admin() ? 'trx_socials-admin' : 'trx_socials-widget_instagram_load',
							'TRX_SOCIALS_STORAGE',
							apply_filters('trx_socials_filter_localize_script_admin', array(
								// AJAX parameters
								'ajax_url'	=> esc_url(admin_url('admin-ajax.php')),
								'ajax_nonce'=> esc_attr(wp_create_nonce(admin_url('admin-ajax.php'))),
								// Site base url
								'site_url'	=> esc_url(get_site_url()),
								// Messages
								'msg_ajax_error' => addslashes(esc_html__('Invalid server answer!', 'trx_socials')),
								) )
							);
	}
}


// Plugin init (after init custom post types and after all other plugins)
if ( !function_exists('trx_socials_init') ) {
	add_action( 'init', 'trx_socials_init', 11 );
	function trx_socials_init() {
		// Add thumb sizes
		$thumb_sizes = apply_filters('trx_socials_filter_add_thumb_sizes', array(
			'trx_socials-thumb-avatar' => array(370, 370, true),
			)
		);
		$mult = trx_socials_get_retina_multiplier();
		foreach ($thumb_sizes as $k=>$v) {
			// Add Original dimensions
			add_image_size( $k, $v[0], $v[1], $v[2]);
			// Add Retina dimensions
			if ($mult > 1) add_image_size( $k.'-@retina', $v[0]*$mult, $v[1]*$mult, $v[2]);
		}
	}
}


//-------------------------------------------------------
//-- Plugin's options
//-------------------------------------------------------

// Add ThemeREX Socials item in the Appearance menu
if (!function_exists('trx_socials_add_menu_items')) {
	add_action( 'admin_menu', 'trx_socials_add_menu_items' );
	function trx_socials_add_menu_items() {
		add_theme_page(
			esc_html__('ThemeREX Socials', 'trx_socials'),	//page_title
			esc_html__('ThemeREX Socials', 'trx_socials'),	//menu_title
			'manage_options',								//capability
			'trx_socials_options',							//menu_slug
			'trx_socials_options_page_builder'				//callback
		);
	}
}

// Add ThemeREX Socials options
if (!function_exists('trx_socials_options_page_builder')) {
	function trx_socials_options_page_builder() {
		require_once TRX_SOCIALS_PLUGIN_DIR . 'includes/options.php';
	}
}

require_once TRX_SOCIALS_PLUGIN_DIR . "includes/file.php";
require_once TRX_SOCIALS_PLUGIN_DIR . "includes/html.php";
require_once TRX_SOCIALS_PLUGIN_DIR . "includes/media.php";

require_once TRX_SOCIALS_PLUGIN_DIR_WIDGETS . "widgets.php";
