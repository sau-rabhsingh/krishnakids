<?php
/**
 * HTML & CSS utilities
 *
 * @package ThemeREX Socials
 * @since v1.0.0
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) die( '-1' );

// Output string with the html layout (if not empty)
// (put it between 'before' and 'after' tags)
// Attention! This string may contain layout formed in any plugin (widgets or shortcodes output) and not require escaping to prevent damage!
if ( !function_exists('trx_socials_show_layout') ) {
	function trx_socials_show_layout($str, $before='', $after='') {
		if (trim($str) != '') {
			printf("%s%s%s", $before, $str, $after);
		}
	}
}

// Return value for the style attr
if (!function_exists('trx_socials_prepare_css_value')) {
	function trx_socials_prepare_css_value($val) {
		if ( '' != $val ) {
			$parts = explode( ' ', trim( $val ) );
			foreach( $parts as $k => $v ) {
				$ed = substr( $v, -1 );
				if ( '0' <= $ed && $ed <= '9' ) {
					$parts[ $k ] .= 'px';
				}
			}
			$val = join( ' ', $parts );
		}
		return $val;
	}
}


// Add dynamic CSS and return class for it
if (!function_exists('trx_socials_add_inline_css_class')) {
	function trx_socials_add_inline_css_class($css, $suffix='') {
		$class_name = sprintf('trx_socials_inline_%d', mt_rand());
		global $TRX_SOCIALS_STORAGE;
		$TRX_SOCIALS_STORAGE['inline_css'] = (!empty($TRX_SOCIALS_STORAGE['inline_css']) ? $TRX_SOCIALS_STORAGE['inline_css'] : '') 
											. sprintf('.%s%s{%s}', $class_name, !empty($suffix) ? (substr($suffix, 0, 1) != ':' ? ' ' : '') . esc_attr($suffix) : '', $css);
		return $class_name;
	}
}

// Add dynamic CSS to insert it to the footer
if ( !function_exists('trx_socials_add_inline_css') ) {
	function trx_socials_add_inline_css($css) {
		global $TRX_SOCIALS_STORAGE;
		$TRX_SOCIALS_STORAGE['inline_css'] = (!empty($TRX_SOCIALS_STORAGE['inline_css']) ? $TRX_SOCIALS_STORAGE['inline_css'] : '') 
											. $css;
	}
}

// Return dynamic CSS to insert it to the footer
if ( !function_exists('trx_socials_get_inline_css') ) {
	function trx_socials_get_inline_css($clear=false) {
		global $TRX_SOCIALS_STORAGE;
		$rez = '';
        if (!empty($TRX_SOCIALS_STORAGE['inline_css'])) {
        	$rez = $TRX_SOCIALS_STORAGE['inline_css'];
        	if ($clear) $TRX_SOCIALS_STORAGE['inline_css'] = '';
        }
        return $rez;
	}
}

// Add dynamic HTML to insert it to the footer
if ( !function_exists('trx_socials_add_inline_html') ) {
	function trx_socials_add_inline_html($html) {
		global $TRX_SOCIALS_STORAGE;
		$TRX_SOCIALS_STORAGE['inline_html'] = (!empty($TRX_SOCIALS_STORAGE['inline_html']) ? $TRX_SOCIALS_STORAGE['inline_html'] : '')
											. $html;
	}
}

// Return dynamic HTML to insert it to the footer
if ( !function_exists('trx_socials_get_inline_html') ) {
	function trx_socials_get_inline_html() {
		global $TRX_SOCIALS_STORAGE;
		return !empty($TRX_SOCIALS_STORAGE['inline_html']) ? $TRX_SOCIALS_STORAGE['inline_html'] : '';
	}
}


// Return current site protocol
if (!function_exists('trx_socials_get_protocol')) {
	function trx_socials_get_protocol() {
		return is_ssl() ? 'https' : 'http';
	}
}

// Return url without protocol
if (!function_exists('trx_socials_remove_protocol')) {
	function trx_socials_remove_protocol($url, $complete=false) {
		$url = preg_replace('/http[s]?:'.($complete ? '\\/\\/' : '').'/', '', $url);
		return $url;
	}
}

// Check if string is URL
if (!function_exists('trx_socials_is_url')) {
	function trx_socials_is_url($url) {
		return strpos($url, '://')!==false;
	}
}

// Add parameters to URL
if (!function_exists('trx_socials_add_to_url')) {
	function trx_socials_add_to_url($url, $prm) {
		if (is_array($prm) && count($prm) > 0) {
			$separator = strpos($url, '?')===false ? '?' : '&';
			foreach ($prm as $k=>$v) {
				$url .= $separator . urlencode($k) . '=' . urlencode($v);
				$separator = '&';
			}
		}
		return $url;
	}
}



/* HTML
-------------------------------------------------------------------------------- */

// Generate value for the attribute 'id'
if ( ! function_exists('trx_socials_generate_id') ) {
	function trx_socials_generate_id($prefix) {
		return $prefix . str_replace('.', '', mt_rand());
	}
}

// Return first tag from text
if (!function_exists('trx_socials_get_tag')) {
	function trx_socials_get_tag($text, $tag_start, $tag_end='') {
		$val = '';
		if (($pos_start = strpos($text, $tag_start))!==false) {
			$pos_end = $tag_end ? strpos($text, $tag_end, $pos_start) : false;
			if ($pos_end===false) {
				$tag_end = substr($tag_start, 0, 1) == '<' ? '>' : ']';
				$pos_end = strpos($text, $tag_end, $pos_start);
			}
			$val = substr($text, $pos_start, $pos_end+strlen($tag_end)-$pos_start);
		}
		return $val;
	}
}

// Return attrib from tag
if (!function_exists('trx_socials_get_tag_attrib')) {
	function trx_socials_get_tag_attrib($text, $tag, $attr) {
		$val = '';
		if (($pos_start = strpos($text, substr($tag, 0, strlen($tag)-1)))!==false) {
			$pos_end = strpos($text, substr($tag, -1, 1), $pos_start);
			$pos_attr = strpos($text, ' '.($attr).'=', $pos_start);
			if ($pos_attr!==false && $pos_attr<$pos_end) {
				$pos_attr += strlen($attr)+3;
				$pos_quote = strpos($text, substr($text, $pos_attr-1, 1), $pos_attr);
				$val = substr($text, $pos_attr, $pos_quote-$pos_attr);
			}
		}
		return $val;
	}
}

// Set (change) attrib from tag
if (!function_exists('trx_socials_set_tag_attrib')) {
	function trx_socials_set_tag_attrib($text, $tag, $attr, $val) {
		if (($pos_start = strpos($text, substr($tag, 0, strlen($tag)-1)))!==false) {
			$pos_end = strpos($text, substr($tag, -1, 1), $pos_start);
			$pos_attr = strpos($text, $attr.'=', $pos_start);
			if ($pos_attr!==false && $pos_attr<$pos_end) {
				$pos_attr += strlen($attr)+2;
				$pos_quote = strpos($text, substr($text, $pos_attr-1, 1), $pos_attr);
				$text = substr($text, 0, $pos_attr) . trim($val) . substr($text, $pos_quote);
			} else {
				$text = substr($text, 0, $pos_end) . ' ' . esc_attr($attr) . '="' . esc_attr($val) . '"' . substr($text, $pos_end);
			}
		}
		return $text;
	}
}




/* GET, POST and SESSION utilities
-------------------------------------------------------------------------------- */

// Strip slashes if Magic Quotes is on
if (!function_exists('trx_socials_stripslashes')) {
	function trx_socials_stripslashes($val) {
		static $magic = 0;
		if ($magic === 0) {
			$magic = version_compare(phpversion(), '5.4', '>=')
					|| (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()==1) 
					|| (function_exists('get_magic_quotes_runtime') && get_magic_quotes_runtime()==1) 
					|| strtolower(ini_get('magic_quotes_sybase'))=='on';
		}
		if (is_array($val)) {
			foreach($val as $k=>$v)
				$val[$k] = trx_socials_stripslashes($v);
		} else
			$val = $magic ? stripslashes(trim($val)) : trim($val);
		return $val;
	}
}

// Return GET or POST value
if (!function_exists('trx_socials_get_value_gp')) {
	function trx_socials_get_value_gp($name, $defa='') {
		if (isset($_GET[$name]))		$rez = $_GET[$name];
		else if (isset($_POST[$name]))	$rez = $_POST[$name];
		else							$rez = $defa;
		return trx_socials_stripslashes($rez);
	}
}

// Return GET or POST or COOKIE value
if (!function_exists('trx_socials_get_value_gpc')) {
	function trx_socials_get_value_gpc($name, $defa='') {
		if (isset($_GET[$name]))		 $rez = $_GET[$name];
		else if (isset($_POST[$name]))	 $rez = $_POST[$name];
		else if (isset($_COOKIE[$name])) $rez = $_COOKIE[$name];
		else							 $rez = $defa;
		return trx_socials_stripslashes($rez);
	}
}


// Get GET, POST, SESSION value and save it (if need)
if (!function_exists('trx_socials_get_value_gps')) {
	function trx_socials_get_value_gps($name, $defa='') {
		if (isset($_GET[$name]))		  $rez = $_GET[$name];
		else if (isset($_POST[$name]))	  $rez = $_POST[$name];
		else if (isset($_SESSION[$name])) $rez = $_SESSION[$name];
		else							  $rez = $defa;
		return trx_socials_stripslashes($rez);
	}
}

// Save value to the session
if (!function_exists('trx_socials_set_session_value')) {
	function trx_socials_set_session_value($name, $value) {
		if (!session_id()) try { session_start(); } catch (Exception $e) {}
		$_SESSION[$name] = $value;
	}
}

// Delete value from session
if (!function_exists('trx_socials_del_session_value')) {
	function trx_socials_del_session_value($name) {
		if (!session_id()) try { session_start(); } catch (Exception $e) {}
		unset($_SESSION[$name]);
	}
}


	
/* AJAX utilities
------------------------------------------------------------------------------------- */

// Verify nonce and exit if it's not valid
if ( ! function_exists( 'trx_socials_verify_nonce' ) ) {
	function trx_socials_verify_nonce( $nonce = 'nonce', $mask = '' ) {
		if ( empty( $mask ) ) {
			$mask = admin_url('admin-ajax.php');
		}
		if ( ! wp_verify_nonce( trx_socials_get_value_gp( $nonce ), $mask ) ) {
			trx_socials_forbidden();
		}
	}
}

// Exit with default code (200)
if ( ! function_exists( 'trx_socials_exit' ) ) {
	function trx_socials_exit( $message = '', $title = '', $code = 200 ) {
		wp_die( $message, $title, array( 'response' => $code, 'exit' => empty( $message ) && empty( $title ) ) );
	}
}

// Exit with code 403
if ( ! function_exists( 'trx_socials_forbidden' ) ) {
	function trx_socials_forbidden( $message = '', $title = '' ) {
		trx_socials_exit( $message, $title, 403 );
	}
}

// Send ajax response and exit
if ( ! function_exists( 'trx_socials_ajax_response' ) ) {
	function trx_socials_ajax_response( $response ) {
		echo wp_json_encode( $response );
		wp_die( '', '', array( 'exit' => true ) );
	}
}
