<?php
//####################################################
//#### Inheritance system (for internal use only) #### 
//####################################################

// Add item to the inheritance settings
if ( !function_exists( 'themerex_add_theme_inheritance' ) ) {
	function themerex_add_theme_inheritance($options, $append=true) {
		global $THEMEREX_GLOBALS;
		if (!isset($THEMEREX_GLOBALS["inheritance"])) $THEMEREX_GLOBALS["inheritance"] = array();
		$THEMEREX_GLOBALS['inheritance'] = $append 
			? themerex_array_merge($THEMEREX_GLOBALS['inheritance'], $options) 
			: themerex_array_merge($options, $THEMEREX_GLOBALS['inheritance']);
	}
}



// Return inheritance settings
if ( !function_exists( 'themerex_get_theme_inheritance' ) ) {
	function themerex_get_theme_inheritance($key = '') {
		global $THEMEREX_GLOBALS;
		return $key ? $THEMEREX_GLOBALS['inheritance'][$key] : $THEMEREX_GLOBALS['inheritance'];
	}
}



// Detect inheritance key for the current mode
if ( !function_exists( 'themerex_detect_inheritance_key' ) ) {
	function themerex_detect_inheritance_key() {
		static $inheritance_key = '';
		if (!empty($inheritance_key)) return $inheritance_key;
		$inheritance_key = apply_filters('themerex_filter_detect_inheritance_key', '');
		return $inheritance_key;
	}
}


// Return key for override parameter
if ( !function_exists( 'themerex_get_override_key' ) ) {
	function themerex_get_override_key($value, $by) {
		$key = '';
		$inheritance = themerex_get_theme_inheritance();
		if (!empty($inheritance) && is_array($inheritance)) {
			foreach ($inheritance as $k=>$v) {
				if (!empty($v[$by]) && in_array($value, $v[$by])) {
					$key = $by=='taxonomy' 
						? $value
						: (!empty($v['override']) ? $v['override'] : $k);
					break;
				}
			}
		}
		return $key;
	}
}


// Return taxonomy (for categories) by post_type from inheritance array
if ( !function_exists( 'themerex_get_taxonomy_categories_by_post_type' ) ) {
	function themerex_get_taxonomy_categories_by_post_type($value) {
		$key = '';
		$inheritance = themerex_get_theme_inheritance();
		if (!empty($inheritance) && is_array($inheritance)) {
			foreach ($inheritance as $k=>$v) {
				if (!empty($v['post_type']) && in_array($value, $v['post_type'])) {
					$key = !empty($v['taxonomy']) ? $v['taxonomy'][0] : '';
					break;
				}
			}
		}
		return $key;
	}
}


// Return taxonomy (for tags) by post_type from inheritance array
if ( !function_exists( 'themerex_get_taxonomy_tags_by_post_type' ) ) {
	function themerex_get_taxonomy_tags_by_post_type($value) {
		$key = '';
		$inheritance = themerex_get_theme_inheritance();
		if (!empty($inheritance) && is_array($inheritance)) {
			foreach($inheritance as $k=>$v) {
				if (!empty($v['post_type']) && in_array($value, $v['post_type'])) {
					$key = !empty($v['taxonomy_tags']) ? $v['taxonomy_tags'][0] : '';
					break;
				}
			}
		}
		return $key;
	}
}
?>