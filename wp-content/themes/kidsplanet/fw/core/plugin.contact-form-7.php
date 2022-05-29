<?php
/* Contact Form 7 support functions
------------------------------------------------------------------------------- */


// Check if Instagram Widget installed and activated
if ( !function_exists( 'themerex_exists_contact_form_7' ) ) {
    function themerex_exists_contact_form_7() {
        return defined( 'Contact Form 7' );
    }
}

