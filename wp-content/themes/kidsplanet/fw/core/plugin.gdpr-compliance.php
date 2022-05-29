<?php
/* The GDPR Framework support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('themerex_gdpr_compliance_theme_setup')) {
    add_action( 'themerex_action_before_init_theme', 'themerex_gdpr_compliance_theme_setup', 1 );
    function themerex_gdpr_compliance_theme_setup() {
        if (is_admin()) {
            add_filter( 'themerex_filter_required_plugins', 'themerex_gdpr_compliance_required_plugins' );
        }
    }
}

// Check if Instagram Widget installed and activated
if ( !function_exists( 'themerex_exists_gdpr_compliance' ) ) {
    function themerex_exists_gdpr_compliance() {
        return defined( 'WP_GDPR_C_SLUG' );
    }
}

// Filter to add in the required plugins list
if ( !function_exists( 'themerex_gdpr_compliance_required_plugins' ) ) {
    //Handler of add_filter('themerex_filter_required_plugins',    'themerex_gdpr_compliance_required_plugins');
    function themerex_gdpr_compliance_required_plugins($list=array()) {
        if (in_array('wp-gdpr-compliance', (array)themerex_get_global('required_plugins')))
            $list[] = array(
                'name'         => esc_html__('Cookie Information', 'kidsplanet'),
                'slug'         => 'wp-gdpr-compliance',
                'required'     => false
            );
        return $list;
    }
}