<?php
/**
 * ThemeREX Framework: templates and thumbs management
 *
 * @package	themerex
 * @since	themerex 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('themerex_templates_theme_setup')) {
	add_action( 'themerex_action_before_init_theme', 'themerex_templates_theme_setup' );
	function themerex_templates_theme_setup() {

		// Add custom thumb sizes into media manager
		add_filter( 'image_size_names_choose', 'themerex_show_thumb_sizes');
	}
}



/* Templates
-------------------------------------------------------------------------------- */

// Add template (layout name)
if (!function_exists('themerex_add_template')) {
	function themerex_add_template($tpl) {
		global $THEMEREX_GLOBALS;
		if (empty($tpl['mode']))						$tpl['mode'] = 'blog';
		if (empty($tpl['template']))					$tpl['template'] = $tpl['layout'];
		if (empty($tpl['need_content']))				$tpl['need_content'] = false;
		if (empty($tpl['need_terms']))					$tpl['need_terms'] = false;
		if (empty($tpl['need_columns']))				$tpl['need_columns'] = false;
		if (empty($tpl['need_isotope']))				$tpl['need_isotope'] = false;
		if (!isset($tpl['h_crop']) && isset($tpl['h']))	$tpl['h_crop'] = $tpl['h'];
		if (!isset($THEMEREX_GLOBALS['registered_templates'])) $THEMEREX_GLOBALS['registered_templates'] = array();
		$THEMEREX_GLOBALS['registered_templates'][$tpl['layout']] = $tpl;
		if (!empty($tpl['thumb_title']))
			themerex_add_thumb_sizes( $tpl );
		else 
			$tpl['thumb_title'] = '';
	}
}

// Return template file name
if (!function_exists('themerex_get_template_name')) {
	function themerex_get_template_name($layout_name) {
		global $THEMEREX_GLOBALS;
		return $THEMEREX_GLOBALS['registered_templates'][$layout_name]['template'];
	}
}

// Return true, if template required content
if (!function_exists('themerex_get_template_property')) {
	function themerex_get_template_property($layout_name, $what) {
		global $THEMEREX_GLOBALS;
		return !empty($THEMEREX_GLOBALS['registered_templates'][$layout_name][$what]) ? $THEMEREX_GLOBALS['registered_templates'][$layout_name][$what] : '';
	}
}

// Return template output function name
if (!function_exists('themerex_get_template_function_name')) {
	function themerex_get_template_function_name($layout_name) {
		global $THEMEREX_GLOBALS;
		return 'themerex_template_'.str_replace(array('-', '.'), '_', $THEMEREX_GLOBALS['registered_templates'][$layout_name]['template']).'_output';
	}
}


/* Thumbs
-------------------------------------------------------------------------------- */

// Add image dimensions with layout name
if (!function_exists('themerex_add_thumb_sizes')) {
	function themerex_add_thumb_sizes($sizes) {
		global $THEMEREX_GLOBALS;
		if (!isset($sizes['h_crop']))		$sizes['h_crop'] =  isset($sizes['h']) ? $sizes['h'] : null;
		if (empty($sizes['thumb_title']))	$sizes['thumb_title'] = themerex_strtoproper($sizes['layout']);
		$thumb_slug = themerex_get_slug($sizes['thumb_title']);
		if (empty($THEMEREX_GLOBALS['thumb_sizes'][$thumb_slug])) {
			if (empty($THEMEREX_GLOBALS['thumb_sizes'])) $THEMEREX_GLOBALS['thumb_sizes'] = array();
			$THEMEREX_GLOBALS['thumb_sizes'][$thumb_slug] = $sizes;
			add_image_size( $thumb_slug, $sizes['w'], $sizes['h'], $sizes['h']!=null );
			if ($sizes['h']!=$sizes['h_crop']) {
				add_image_size( $thumb_slug.'_crop', $sizes['w'], $sizes['h_crop'], true );
			}
		}
	}
}

// Return image dimensions
if (!function_exists('themerex_get_thumb_sizes')) {
	function themerex_get_thumb_sizes($opt) {
		$opt = array_merge(array(
			'layout' => 'excerpt'
		), $opt);
		global $THEMEREX_GLOBALS;
		$thumb_slug = empty($THEMEREX_GLOBALS['registered_templates'][$opt['layout']]['thumb_title']) ? '' : themerex_get_slug($THEMEREX_GLOBALS['registered_templates'][$opt['layout']]['thumb_title']);
		$rez = $thumb_slug ? $THEMEREX_GLOBALS['thumb_sizes'][$thumb_slug] : array('w'=>null, 'h'=>null, 'h_crop'=>null);
		return $rez;
	}
}

// Show custom thumb sizes into media manager sizes list
if (!function_exists('themerex_show_thumb_sizes')) {
	function themerex_show_thumb_sizes( $sizes ) {
		global $THEMEREX_GLOBALS;
		$thumb_sizes = $THEMEREX_GLOBALS['thumb_sizes'];
		if (is_array($thumb_sizes) && count($thumb_sizes) > 0) {
			$rez = array();
			foreach ($thumb_sizes as $k=>$v)
				$rez[$k] = !empty($v['thumb_title']) ? $v['thumb_title'] : $k;
			$sizes = array_merge( $sizes, $rez);
		}
		return $sizes;
	}
}

// AJAX callback: Get attachment url
if ( !function_exists( 'themerex_callback_get_attachment_url' ) ) {
	function themerex_callback_get_attachment_url() {
		global $_REQUEST;
		
		if ( !wp_verify_nonce( $_REQUEST['nonce'], 'ajax_nonce' ) )
			wp_die();
	
		$response = array('error'=>'');
		
		$id = (int) themerex_get_value_gpc('attachment_id');
		
		$response['data'] = wp_get_attachment_url($id);
		
		echo json_encode($response);
		wp_die();
	}
}
?>