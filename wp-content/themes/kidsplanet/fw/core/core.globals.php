<?php
/**
 * ThemeREX Framework: global variables storage
 *
 * @package	themerex
 * @since	themerex 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get global variable
if (!function_exists('themerex_get_global')) {
	function themerex_get_global($var_name) {
		global $THEMEREX_GLOBALS;
		return isset($THEMEREX_GLOBALS[$var_name]) ? $THEMEREX_GLOBALS[$var_name] : '';
	}
}


// Set global variable
if (!function_exists('themerex_set_global')) {
	function themerex_set_global($var_name, $value) {
		global $THEMEREX_GLOBALS;
		$THEMEREX_GLOBALS[$var_name] = $value;
	}
}

// Inc/Dec global variable with specified value
if (!function_exists('themerex_inc_global')) {
	function themerex_inc_global($var_name, $value=1) {
		global $THEMEREX_GLOBALS;
		$THEMEREX_GLOBALS[$var_name] += $value;
	}
}

// Concatenate global variable with specified value
if (!function_exists('themerex_concat_global')) {
	function themerex_concat_global($var_name, $value) {
		global $THEMEREX_GLOBALS;
		$THEMEREX_GLOBALS[$var_name] .= $value;
	}
}

// Get global array element
if (!function_exists('themerex_get_global_array')) {
	function themerex_get_global_array($var_name, $key) {
		global $THEMEREX_GLOBALS;
		return isset($THEMEREX_GLOBALS[$var_name][$key]) ? $THEMEREX_GLOBALS[$var_name][$key] : '';
	}
}

// Set global array element
if (!function_exists('themerex_set_global_array')) {
	function themerex_set_global_array($var_name, $key, $value) {
		global $THEMEREX_GLOBALS;
		if (!isset($THEMEREX_GLOBALS[$var_name])) $THEMEREX_GLOBALS[$var_name] = array();
		$THEMEREX_GLOBALS[$var_name][$key] = $value;
	}
}

// Inc/Dec global array element with specified value
if (!function_exists('themerex_inc_global_array')) {
	function themerex_inc_global_array($var_name, $key, $value=1) {
		global $THEMEREX_GLOBALS;
		$THEMEREX_GLOBALS[$var_name][$key] += $value;
	}
}

// Concatenate global array element with specified value
if (!function_exists('themerex_concat_global_array')) {
	function themerex_concat_global_array($var_name, $key, $value) {
		global $THEMEREX_GLOBALS;
		$THEMEREX_GLOBALS[$var_name][$key] .= $value;
	}
}

// Check if theme variable is set
if (!function_exists('themerex_storage_isset')) {
    function themerex_storage_isset($var_name, $key='', $key2='') {
        global $THEMEREX_GLOBALS;
        if (!empty($key) && !empty($key2))
            return isset($THEMEREX_GLOBALS[$var_name][$key][$key2]);
        else if (!empty($key))
            return isset($THEMEREX_GLOBALS[$var_name][$key]);
        else
            return isset($THEMEREX_GLOBALS[$var_name]);
    }
}

// Merge two-dim array element
if (!function_exists('themerex_storage_merge_array')) {
    function themerex_storage_merge_array($var_name, $key, $arr) {
        global $THEMEREX_GLOBALS;
        if (!isset($THEMEREX_GLOBALS[$var_name])) $THEMEREX_GLOBALS[$var_name] = array();
        if (!isset($THEMEREX_GLOBALS[$var_name][$key])) $THEMEREX_GLOBALS[$var_name][$key] = array();
        $THEMEREX_GLOBALS[$var_name][$key] = array_merge($THEMEREX_GLOBALS[$var_name][$key], $arr);
    }
}
?>