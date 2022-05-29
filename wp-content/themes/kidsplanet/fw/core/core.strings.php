<?php
/**
 * ThemeREX Framework: strings manipulations
 *
 * @package	themerex
 * @since	themerex 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Check multibyte functions
if ( ! defined( 'THEMEREX_MULTIBYTE' ) ) define( 'THEMEREX_MULTIBYTE', function_exists('mb_strlen') ? 'UTF-8' : false );

if (!function_exists('themerex_strlen')) {
	function themerex_strlen($text) {
		return THEMEREX_MULTIBYTE ? mb_strlen($text) : strlen($text);
	}
}

if (!function_exists('themerex_strpos')) {
	function themerex_strpos($text, $char, $from=0) {
		return THEMEREX_MULTIBYTE ? mb_strpos($text, $char, $from) : strpos($text, $char, $from);
	}
}

if (!function_exists('themerex_strrpos')) {
	function themerex_strrpos($text, $char, $from=0) {
		return THEMEREX_MULTIBYTE ? mb_strrpos($text, $char, $from) : strrpos($text, $char, $from);
	}
}

if (!function_exists('themerex_substr')) {
	function themerex_substr($text, $from, $len=-999999) {
		if ($len==-999999) { 
			if ($from < 0)
				$len = -$from; 
			else
				$len = themerex_strlen($text)-$from;
		}
		return THEMEREX_MULTIBYTE ? mb_substr($text, $from, $len) : substr($text, $from, $len);
	}
}

if (!function_exists('themerex_strtolower')) {
	function themerex_strtolower($text) {
		return THEMEREX_MULTIBYTE ? mb_strtolower($text) : strtolower($text);
	}
}

if (!function_exists('themerex_strtoupper')) {
	function themerex_strtoupper($text) {
		return THEMEREX_MULTIBYTE ? mb_strtoupper($text) : strtoupper($text);
	}
}

if (!function_exists('themerex_strtoproper')) {
	function themerex_strtoproper($text) { 
		$rez = ''; $last = ' ';
		for ($i=0; $i<themerex_strlen($text); $i++) {
			$ch = themerex_substr($text, $i, 1);
			$rez .= themerex_strpos(' .,:;?!()[]{}+=', $last)!==false ? themerex_strtoupper($ch) : themerex_strtolower($ch);
			$last = $ch;
		}
		return $rez;
	}
}

if (!function_exists('themerex_strrepeat')) {
	function themerex_strrepeat($str, $n) {
		$rez = '';
		for ($i=0; $i<$n; $i++)
			$rez .= $str;
		return $rez;
	}
}

if (!function_exists('themerex_strshort')) {
	function themerex_strshort($str, $maxlength, $add='...') {
		if ($maxlength < 0) 
			return '';
		if ($maxlength < 1 || $maxlength >= themerex_strlen($str)) 
			return strip_tags($str);
		$str = themerex_substr(strip_tags($str), 0, $maxlength - themerex_strlen($add));
		$ch = themerex_substr($str, $maxlength - themerex_strlen($add), 1);
		if ($ch != ' ') {
			for ($i = themerex_strlen($str) - 1; $i > 0; $i--)
				if (themerex_substr($str, $i, 1) == ' ') break;
			$str = trim(themerex_substr($str, 0, $i));
		}
		if (!empty($str) && themerex_strpos(',.:;-', themerex_substr($str, -1))!==false) $str = themerex_substr($str, 0, -1);
		return ($str) . ($add);
	}
}

// Clear string from spaces, line breaks and tags (only around text)
if (!function_exists('themerex_strclear')) {
	function themerex_strclear($text, $tags=array()) {
		if (empty($text)) return $text;
		if (!is_array($tags)) {
			if ($tags != '')
				$tags = explode($tags, ',');
			else
				$tags = array();
		}
		$text = trim(chop($text));
		if (is_array($tags) && count($tags) > 0) {
			foreach ($tags as $tag) {
				$open  = '<'.esc_attr($tag);
				$close = '</'.esc_attr($tag).'>';
				if (themerex_substr($text, 0, themerex_strlen($open))==$open) {
					$pos = themerex_strpos($text, '>');
					if ($pos!==false) $text = themerex_substr($text, $pos+1);
				}
				if (themerex_substr($text, -themerex_strlen($close))==$close) $text = themerex_substr($text, 0, themerex_strlen($text) - themerex_strlen($close));
				$text = trim(chop($text));
			}
		}
		return $text;
	}
}

// Return slug for the any title string
if (!function_exists('themerex_get_slug')) {
	function themerex_get_slug($title) {
		return themerex_strtolower(str_replace(array('\\','/','-',' ','.'), '_', $title));
	}
}

// Replace macros in the string
if (!function_exists('themerex_strmacros')) {
	function themerex_strmacros($str) {
		return str_replace(array("{{", "}}", "((", "))", "||"), array("<i>", "</i>", "<b>", "</b>", "<br>"), $str);
	}
}

// Unserialize string (try replace \n with \r\n)
if (!function_exists('themerex_unserialize')) {
    function themerex_unserialize($str) {
        if ( is_serialized($str) ) {
            try {
                $data = unserialize($str);
            } catch (Exception $e) {
                dcl($e->getMessage());
                $data = false;
            }
            if ($data===false) {
                try {
                    $data = unserialize(str_replace("\n", "\r\n", $str));
                } catch (Exception $e) {
                    dcl($e->getMessage());
                    $data = false;
                }
            }
            return $data;
        } else
            return $str;
    }
}
?>