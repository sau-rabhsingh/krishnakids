<?php
/**
 * ThemeREX Framework: less manipulations
 *
 * @package	themerex
 * @since	themerex 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


// Theme init
if (!function_exists('themerex_less_theme_setup2')) {
	add_action( 'themerex_action_after_init_theme', 'themerex_less_theme_setup2' );
	function themerex_less_theme_setup2() {
		// Theme first run - compile and save css
		$theme_data = wp_get_theme();
		$slug = str_replace(' ', '_', trim(themerex_strtolower((string) $theme_data->get('Name'))));
		$option_name = 'themerex_'.strip_tags($slug).'_less_compiled';
		if (get_option($option_name, false) === false) {
			add_option($option_name, 1, '', 'yes');
			do_action('themerex_action_compile_less');
		} else if (!is_admin() && themerex_get_theme_option('debug_mode')=='yes') {
			global $THEMEREX_GLOBALS;
			$THEMEREX_GLOBALS['less_check_time'] = true;
			do_action('themerex_action_compile_less');
			$THEMEREX_GLOBALS['less_check_time'] = false;
		}
	}
}



/* LESS
-------------------------------------------------------------------------------- */

// Recompile all LESS files
if (!function_exists('themerex_compile_less')) {	
	function themerex_compile_less($list = array(), $recompile=true) {

        if (!function_exists('trx_utils_less_compiler')) return false;

        $success = true;

        // Less compiler
        $less_compiler = themerex_get_theme_setting('less_compiler');
        if ($less_compiler == 'no') return $success;

        // Generate map for the LESS-files
        $less_map = themerex_get_theme_setting('less_map');
        if (themerex_get_theme_option('debug_mode')=='no' || $less_compiler=='lessc') $less_map = 'no';

        // Get separator to split LESS-files
        $less_sep = $less_map!='no' ? '' : themerex_get_theme_setting('less_separator');

        // Prepare skin specific LESS-vars (colors, backgrounds, logo height, etc.)
        $vars = apply_filters('themerex_filter_prepare_less', '');

        // Collect .less files in parent and child themes
        if (empty($list)) {
            $list = themerex_collect_files(get_template_directory(), 'less');
            if (get_template_directory() != get_stylesheet_directory()) $list = array_merge($list, themerex_collect_files(get_stylesheet_directory(), 'less'));
        }
        // Prepare separate array with less utils (not compile it alone - only with main files)
        $utils = $less_map!='no' ? array() : '';
        $utils_time = 0;
        if (is_array($list) && count($list) > 0) {
            foreach($list as $k=>$file) {
                $fname = basename($file);
                if ($fname[0]=='_') {
                    if ($less_map!='no')
                        $utils[] = $file;
                    else
                        $utils .= themerex_fgc($file);
                    $list[$k] = '';
                    $tmp = filemtime($file);
                    if ($utils_time < $tmp) $utils_time = $tmp;
                }
            }
        }

        // Compile all .less files
        if (is_array($list) && count($list) > 0) {
            $success = trx_utils_less_compiler($list, array(
                    'compiler' => $less_compiler,
                    'map' => $less_map,
                    'utils' => $utils,
                    'utils_time' => $utils_time,
                    'vars' => $vars,
                    'separator' => $less_sep,
                    'check_time' => themerex_get_global('less_check_time')==true,
                    'compressed' => themerex_get_theme_option('debug_mode')=='no'
                )
            );
        }

        return $success;
	}
}
?>