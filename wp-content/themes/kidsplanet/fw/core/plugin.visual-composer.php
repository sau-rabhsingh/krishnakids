<?php
/* WPBakery Page Builder support functions
------------------------------------------------------------------------------- */

// Check if WPBakery Page Builder installed and activated
if ( !function_exists( 'themerex_exists_visual_composer' ) ) {
	function themerex_exists_visual_composer() {
		return class_exists('Vc_Manager');
	}
}

// Check if WPBakery Page Builder in frontend editor mode
if ( !function_exists( 'themerex_vc_is_frontend' ) ) {
	function themerex_vc_is_frontend() {
		return (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true')
			|| (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline');
	}
}
?>