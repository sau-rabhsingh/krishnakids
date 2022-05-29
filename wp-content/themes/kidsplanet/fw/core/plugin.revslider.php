<?php
/* Revolution Slider support functions
------------------------------------------------------------------------------- */

// Check if RevSlider installed and activated
if ( !function_exists( 'themerex_exists_revslider' ) ) {
	function themerex_exists_revslider() {
		return function_exists('rev_slider_shortcode');
	}
}
?>