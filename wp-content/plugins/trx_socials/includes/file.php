<?php
/**
 * Filesystem utilities
 *
 * @package ThemeREX Socials
 * @since v1.0.0
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) die( '-1' );

//define( 'TRX_SOCIALS_REMOTE_USER_AGENT', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:84.0) Gecko/20100101 Firefox/84.0' );
define( 'TRX_SOCIALS_REMOTE_USER_AGENT', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:84.0) Gecko/20100101 Firefox/84.0 AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.112 Safari/537.36' );


/* Check if file/folder present in the child theme and return path (url) to it. 
   Else - path (url) to file in the main theme dir
------------------------------------------------------------------------------------- */
if (!function_exists('trx_socials_get_file_dir')) {	
	function trx_socials_get_file_dir($file, $return_url=false) {
		if ($file[0]=='/') $file = substr($file, 1);
		$theme_dir = get_template_directory().'/'.TRX_SOCIALS_PLUGIN_BASE.'/';
		$theme_url = get_template_directory_uri().'/'.TRX_SOCIALS_PLUGIN_BASE.'/';
		$child_dir = get_stylesheet_directory().'/'.TRX_SOCIALS_PLUGIN_BASE.'/';
		$child_url = get_stylesheet_directory_uri().'/'.TRX_SOCIALS_PLUGIN_BASE.'/';
		$dir = '';
		if (file_exists(($child_dir).($file)))
			$dir = ($return_url ? $child_url : $child_dir) . ($file);
		else if (file_exists(($theme_dir).($file)))
			$dir = ($return_url ? $theme_url : $theme_dir) . ($file);
		else if (file_exists(TRX_SOCIALS_PLUGIN_DIR . ($file)))
			$dir = ($return_url ? TRX_SOCIALS_PLUGIN_URL : TRX_SOCIALS_PLUGIN_DIR) . ($file);
		return apply_filters( $return_url ? 'trx_socials_get_file_url' : 'trx_socials_get_file_dir', $dir, $file );
	}
}

if (!function_exists('trx_socials_get_file_url')) {	
	function trx_socials_get_file_url($file) {
		return trx_socials_get_file_dir($file, true);
	}
}

// Get text from specified file via HTTP (cURL)
if (!function_exists('trx_socials_remote_get')) {	
	function trx_socials_remote_get( $file, $args = array() ) {
		$args = array_merge(
					array(
						'method'     => 'GET',
						'timeout'    => -1,
						'user-agent' => TRX_SOCIALS_REMOTE_USER_AGENT
					),
					is_array( $args ) ? $args : array( 'timeout' => $args )
				);
		// Set timeout as half of the PHP execution time
		if ( $args['timeout'] < 1 ) $args['timeout'] = round( 0.5 * max( 30, ini_get( 'max_execution_time' ) ) );
		// Add current protocol (if not specified)
		if ( substr( $file, 0, 2 ) == '//' ) $file = trx_socials_get_protocol() . ':' . $file;
		// Do request and get a response
		$response = wp_remote_get( $file, $args );
		// Check the response code and return response body if OK
		return !is_wp_error($response) && isset($response['response']['code']) && $response['response']['code']==200
					? $response['body']
					: '';
	}
}

// Get text from specified file via HTTP (cURL)
if (!function_exists('trx_socials_remote_post')) {	
	function trx_socials_remote_post( $file, $args, $vars = array() ) {
		$args = array_merge(
					array(
						'method'     => 'POST',
						'timeout'    => -1,
						'user-agent' => TRX_SOCIALS_REMOTE_USER_AGENT
					),
					is_array( $args ) ? $args : array( 'timeout' => $args )
				);
		// Add variables to the request body
		if ( is_array( $vars ) && count( $vars ) > 0 ) {
			$args['body'] = $vars;
		}
		// Set timeout as half of the PHP execution time
		if ( $args['timeout'] < 1 ) $args['timeout'] = round( 0.5 * max( 30, ini_get( 'max_execution_time' ) ) );
		// Add current protocol (if not specified)
		if ( substr( $file, 0, 2 ) == '//' ) $file = trx_socials_get_protocol() . ':' . $file;
		// Do request and get a response
		$response = wp_remote_post( $file, $args );
		// Check the response code and return response body if OK
		return !is_wp_error($response) && isset($response['response']['code']) && $response['response']['code']==200
					? $response['body']
					: '';
	}
}

// Detect folder location (in the child theme or in the main theme)
if (!function_exists('trx_socials_get_folder_dir')) {	
	function trx_socials_get_folder_dir($folder, $return_url=false) {
		if ($folder[0]=='/') $folder = substr($folder, 1);
		$theme_dir = get_template_directory().'/'.TRX_SOCIALS_PLUGIN_BASE.'/';
		$theme_url = get_template_directory_uri().'/'.TRX_SOCIALS_PLUGIN_BASE.'/';
		$child_dir = get_stylesheet_directory().'/'.TRX_SOCIALS_PLUGIN_BASE.'/';
		$child_url = get_stylesheet_directory_uri().'/'.TRX_SOCIALS_PLUGIN_BASE.'/';
		$dir = '';
		if (($filtered_dir = apply_filters('trx_socials_filter_get_theme_folder_dir', '', TRX_SOCIALS_PLUGIN_BASE.'/'.($folder), $return_url)) != '')
			$dir = $filtered_dir;
		else if ($theme_dir != $child_dir && is_dir(($child_dir).($folder)))
			$dir = ($return_url ? $child_url : $child_dir).($folder);
		else if (is_dir(($theme_dir).($folder)))
			$dir = ($return_url ? $theme_url : $theme_dir).($folder);
		else if (is_dir((TRX_SOCIALS_PLUGIN_DIR).($folder)))
			$dir = ($return_url ? TRX_SOCIALS_PLUGIN_URL : TRX_SOCIALS_PLUGIN_DIR).($folder);
		return apply_filters( 'trx_socials_filter_get_folder_dir', $dir, $folder, $return_url );
	}
}

if (!function_exists('trx_socials_get_folder_url')) {	
	function trx_socials_get_folder_url($folder) {
		return trx_socials_get_folder_dir($folder, true);
	}
}

// Return file extension from full name/path
if (!function_exists('trx_socials_get_file_ext')) {	
	function trx_socials_get_file_ext($file) {
		$ext = pathinfo($file, PATHINFO_EXTENSION);
		return empty($ext) ? '' : $ext;
	}
}

// Return file name from full name/path
if (!function_exists('trx_socials_get_file_name')) {	
	function trx_socials_get_file_name($file, $without_ext=true) {
		$parts = pathinfo($file);
		return !empty($parts['filename']) && $without_ext ? $parts['filename'] : $parts['basename'];
	}
}


// Include part of template with specified parameters
if (!function_exists('trx_socials_get_template_part')) {	
	function trx_socials_get_template_part($file, $args_name='', $args=array()) {
		static $fdirs = array();
		if (!is_array($file))
			$file = array($file);
		foreach ($file as $f) {
			if (!empty($fdirs[$f]) || ($fdirs[$f] = trx_socials_get_file_dir($f)) != '') { 
				if (!empty($args_name) && !empty($args))
					set_query_var($args_name, $args);
				include $fdirs[$f];
				break;
			}
		}
	}
}


// Unserialize string
if ( ! function_exists('trx_socials_unserialize') ) {
	function trx_socials_unserialize($str) {
		if ( !empty($str) && is_serialized($str) ) {
			// If serialized data content unrecoverable object (base class for this object is not exists) - skip this string
			if ( true || ! preg_match( '/O:[0-9]+:"([^"]*)":[0-9]+:{/', $str, $matches ) || empty( $matches[1] ) || class_exists( $matches[1] ) ) {
				try {
					$data = unserialize($str);
				} catch (Exception $e) {
					dcl($e->getMessage());
					$data = false;
				}
				if ($data===false) {
					try {
						$str = preg_replace_callback (
									'!s:(\d+):"(.*?)";!',
									function( $match ) {
										return ( $match[1] == strlen( $match[2] ) ) 
													? $match[0] 
													: 's:' . strlen( $match[2] ) . ':"' . $match[2] . '";';
									},
									$str
								);
						$data = unserialize($str);
					} catch (Exception $e) {
						dcl($e->getMessage());
						$data = false;
					}
				}
				return $data;
			} else {
				return $str;
			}
		} else {
			return $str;
		}
	}
}
