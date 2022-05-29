<?php
/* Instagram Widget by WPZOOM support functions
------------------------------------------------------------------------------- */

// Check if Instagram Widget installed and activated
if ( !function_exists( 'themerex_exists_instagram_widget_by_wpzoom' ) ) {
    function themerex_exists_instagram_widget_by_wpzoom() {
        return function_exists('zoom_instagram_widget_register');
    }
}