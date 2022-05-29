<?php
/**
 * ThemeREX Framework
 *
 * @package themerex
 * @since themerex 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Framework directory path from theme root
if ( ! defined( 'THEMEREX_FW_DIR' ) )		define( 'THEMEREX_FW_DIR', '/fw/' );

// Theme timing
if ( ! defined( 'THEMEREX_START_TIME' ) )	define( 'THEMEREX_START_TIME', microtime());			// Framework start time
if ( ! defined( 'THEMEREX_START_MEMORY' ) )	define( 'THEMEREX_START_MEMORY', memory_get_usage());	// Memory usage before core loading
if ( ! defined( 'THEMEREX_THEME_PATH' ) )	define( 'THEMEREX_THEME_PATH',	trailingslashit( get_template_directory() ) );

// Global variables storage
global $THEMEREX_GLOBALS;
$THEMEREX_GLOBALS = array();

/* Theme setup section
-------------------------------------------------------------------- */
if ( !function_exists( 'themerex_loader_theme_setup' ) ) {
	add_action( 'after_setup_theme', 'themerex_loader_theme_setup', 20 );
	function themerex_loader_theme_setup() {
		// Before init theme
		do_action('themerex_action_before_init_theme');

		// Load current values for main theme options
		themerex_load_main_options();

		// Theme core init - only for admin side. In frontend it called from header.php
		if ( is_admin() ) {
			themerex_core_init_theme();
		}
	}
}


/* Include core parts
------------------------------------------------------------------------ */

// Manual load important libraries before load all rest files
// core.strings must be first - we use themerex_str...() in the themerex_get_file_dir()
require_once( (file_exists(get_stylesheet_directory().(THEMEREX_FW_DIR).'core/core.strings.php') ? get_stylesheet_directory() : get_template_directory()).(THEMEREX_FW_DIR).'core/core.strings.php' );
// core.files must be first - we use themerex_get_file_dir() to include all rest parts
require_once( (file_exists(get_stylesheet_directory().(THEMEREX_FW_DIR).'core/core.files.php') ? get_stylesheet_directory() : get_template_directory()).(THEMEREX_FW_DIR).'core/core.files.php' );

// Include core files
themerex_autoload_folder( 'core' );

// Include custom theme files
themerex_autoload_folder( 'includes' );

// Include theme templates
themerex_autoload_folder( 'templates' );
?>