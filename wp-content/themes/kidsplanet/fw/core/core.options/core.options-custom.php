<?php
/**
 * ThemeREX Framework: Theme options custom fields
 *
 * @package	themerex
 * @since	themerex 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'themerex_options_custom_theme_setup' ) ) {
	add_action( 'themerex_action_before_init_theme', 'themerex_options_custom_theme_setup' );
	function themerex_options_custom_theme_setup() {

		if ( is_admin() ) {
			add_action("admin_enqueue_scripts",	'themerex_options_custom_load_scripts');
		}
		
	}
}

// Load required styles and scripts for custom options fields
if ( !function_exists( 'themerex_options_custom_load_scripts' ) ) {
	//Handler of add_action("admin_enqueue_scripts", 'themerex_options_custom_load_scripts');
	function themerex_options_custom_load_scripts() {
		wp_enqueue_script( 'themerex-options-custom-script',	themerex_get_file_url('core/core.options/js/core.options-custom.js'), array(), null, true );
	}
}


// Show theme specific fields in Post (and Page) options
function themerex_show_custom_field($id, $field, $value) {
	$output = '';
	switch ($field['type']) {
		case 'reviews':
			$output .= '<div class="reviews_block">' . trim(themerex_reviews_get_markup($field, $value, true)) . '</div>';
			break;

		case 'mediamanager':
			wp_enqueue_media( );
			$output .= '<a id="'.esc_attr($id).'" class="button mediamanager"
				data-param="' . esc_attr($id) . '"
				data-choose="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_attr__( 'Choose Images', 'kidsplanet') : esc_attr__( 'Choose Image', 'kidsplanet')).'"
				data-update="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_attr__( 'Add to Gallery', 'kidsplanet') : esc_attr__( 'Choose Image', 'kidsplanet')).'"
				data-multiple="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? 'true' : 'false').'"
				data-linked-field="'.esc_attr($field['media_field_id']).'"
				onclick="themerex_show_media_manager(this); return false;"
				>' . (isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Choose Images', 'kidsplanet') : esc_html__( 'Choose Image', 'kidsplanet')) . '</a>';
			break;
	}
	return apply_filters('themerex_filter_show_custom_field', $output, $id, $field, $value);
}
?>