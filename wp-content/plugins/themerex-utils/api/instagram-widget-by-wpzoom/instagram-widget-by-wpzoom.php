<?php
/**
 * Plugin support: Instagram Widget by WPZOOM
 *
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

// Check if plugin is installed and activated
if ( !function_exists( 'trx_utils_exists_instagram_widget_by_wpzoom' ) ) {
    function trx_utils_exists_instagram_widget_by_wpzoom() {
        return function_exists('zoom_instagram_widget_register');
    }
}


// One-click import support
//------------------------------------------------------------------------

// Check plugin in the required plugins
if ( !function_exists( 'trx_utils_instagram_widget_by_wpzoom_importer_required_plugins' ) ) {
    if (is_admin()) add_filter( 'trx_utils_filter_importer_required_plugins',  'trx_utils_instagram_widget_by_wpzoom_importer_required_plugins', 10, 2 );
    function trx_utils_instagram_widget_by_wpzoom_importer_required_plugins($not_installed='', $list='') {
        if (strpos($list, 'instagram-widget-by-wpzoom')!==false && !trx_utils_exists_instagram_widget_by_wpzoom() )
            $not_installed .= '<br>' . esc_html__('Instagram Widget by WPZOOM
', 'trx_utils');
        return $not_installed;
    }
}

// Set plugin's specific importer options
if ( !function_exists( 'trx_utils_instagram_widget_by_wpzoom_importer_set_options' ) ) {
    if (is_admin()) add_filter( 'trx_utils_filter_importer_options', 'trx_utils_instagram_widget_by_wpzoom_importer_set_options', 10, 1 );
    function trx_utils_instagram_widget_by_wpzoom_importer_set_options($options=array()) {
        if ( trx_utils_exists_instagram_widget_by_wpzoom() && in_array('instagram-widget-by-wpzoom', $options['required_plugins']) ) {
            $options['additional_options'][] = 'widget_wpzoom_instagram_widget';            // Add slugs to export options of this plugin
            $options['additional_options'][] = 'wpzoom-instagram-widget-settings';
        }
        return $options;
    }
}
