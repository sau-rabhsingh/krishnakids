<?php
/**
 * Widget: Instagram (WPBakery support)
 *
 * @package ThemeREX Socials
 * @since v1.3.0
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) die( '-1' );


// Add [trx_widget_instagram] in the VC shortcodes list
if (!function_exists('trx_socials_widget_instagram_reg_shortcodes_vc')) {
	function trx_socials_widget_instagram_reg_shortcodes_vc() {
		
		if ( ! function_exists('vc_lean_map') ) return;
		
		vc_lean_map("trx_widget_instagram", 'trx_socials_widget_instagram_reg_shortcodes_vc_params');
		class WPBakeryShortCode_Trx_Widget_Instagram extends WPBakeryShortCode {}
	}
	add_action('init', 'trx_socials_widget_instagram_reg_shortcodes_vc', 20);
}


// Return params
if (!function_exists('trx_socials_widget_instagram_reg_shortcodes_vc_params')) {
	function trx_socials_widget_instagram_reg_shortcodes_vc_params() {
		return apply_filters('trx_socials_sc_map', array(
				"base" => "trx_widget_instagram",
				"name" => esc_html__("Widget: Instagram", 'trx_socials'),
				"description" => wp_kses_data( __("Display the latest photos from instagram account by hashtag", 'trx_socials') ),
				"category" => esc_html__('ThemeREX', 'trx_socials'),
				"icon" => 'icon_trx_widget_instagram',
				"class" => "trx_widget_instagram",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Widget title", 'trx_socials'),
						"description" => wp_kses_data( __("Title of the widget", 'trx_socials') ),
						"admin_label" => true,
						'edit_field_class' => 'vc_col-sm-4',
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "demo",
						"heading" => esc_html__('Demo mode', 'trx_socials'),
						"description" => wp_kses_data( __('Show demo images', 'trx_socials') ),
						'edit_field_class' => 'vc_col-sm-4',
						"std" => "0",
						'save_always' => true,
						"value" => array(
							esc_html__( "Demo mode", 'trx_socials' ) => "1"
						),
						"type" => "checkbox"
					),
					array(
						"param_name" => "demo_thumb_size",
						"heading" => esc_html__("Thumb size", 'trx_socials'),
						"description" => wp_kses_data( __("Select a thumb size to show images", 'trx_socials') ),
						'edit_field_class' => 'vc_col-sm-4',
						"std" => apply_filters( 'trx_socials_filter_thumb_size',
												trx_socials_get_thumb_size( 'avatar' ),
												'trx_socials_widget_instagram',
												array()
											),
						"value" => array_flip( trx_socials_get_list_thumbnail_sizes() ),
						'dependency' => array(
							'element' => 'demo',
							'value' => '1'
						),
						"type" => "dropdown"
					),
					array(
						'type'        => 'param_group',
						'param_name'  => 'demo_files',
						'heading'     => esc_html__( 'Demo images', 'trx_socials' ),
						'description' => wp_kses_data( __( 'Specify values for each media item', 'trx_socials' ) ),
						'value'       => urlencode(
							json_encode(
								apply_filters(
									'trx_socials_sc_param_group_value', array(
										array(
											'title' => '',
											'image' => '',
											'video' => '',
										),
									), 'trx_widget_instagram'
								)
							)
						),
						'params'      => apply_filters(
							'trx_socials_sc_param_group_params', array_merge(
								array(
									array(
										'param_name'  => 'image',
										'heading'     => esc_html__( 'Image', 'trx_socials' ),
										'description' => wp_kses_data( __( 'Select or upload an image or write URL from other site', 'trx_socials' ) ),
										'admin_label' => true,
										'edit_field_class' => 'vc_col-sm-6',
										'type'        => 'attach_image',
									),
									array(
										"param_name" => "video",
										"heading" => esc_html__("Video URL", 'trx_socials'),
										"description" => wp_kses_data( __("Specify URL of the demo video", 'trx_socials') ),
										'admin_label' => true,
										'edit_field_class' => 'vc_col-sm-6',
										"type" => "textfield"
									),
								)
							), 'trx_widget_instagram'
						),
						'dependency' => array(
							'element' => 'demo',
							'value' => '1'
						),
					),
					array(
						"param_name" => "hashtag",
						"heading" => esc_html__("Hashtag or Username", 'trx_socials'),
						"description" => wp_kses_data( __("Hashtag to filter your photos", 'trx_socials') ),
						'edit_field_class' => 'vc_col-sm-6',
						"class" => "",
						'dependency' => array(
							'element' => 'demo',
							'is_empty' => true
						),
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Number of photos", 'trx_socials'),
						"description" => wp_kses_data( __("How many photos to be displayed?", 'trx_socials') ),
						'edit_field_class' => 'vc_col-sm-6',
						'dependency' => array(
							'element' => 'demo',
							'is_empty' => true
						),
						"class" => "",
						"value" => "8",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'trx_socials'),
						"description" => wp_kses_data( __("Columns number", 'trx_socials') ),
						'edit_field_class' => 'vc_col-sm-6',
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns_gap",
						"heading" => esc_html__("Columns gap", 'trx_socials'),
						"description" => wp_kses_data( __("Gap between images", 'trx_socials') ),
						'edit_field_class' => 'vc_col-sm-6',
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "links",
						"heading" => esc_html__("Link images to", 'trx_socials'),
						"description" => wp_kses_data( __("Where to send a visitor after clicking on the picture", 'trx_socials') ),
						'edit_field_class' => 'vc_col-sm-6',
						"class" => "",
						"std" => "instagram",
						"value" => array_flip(trx_socials_get_list_sc_instagram_redirects()),
						"type" => "dropdown"
					),
					array(
						"param_name" => "follow",
						"heading" => esc_html__('Show button "Follow me"', 'trx_socials'),
						"description" => wp_kses_data( __('Add button "Follow me" after images', 'trx_socials') ),
						'edit_field_class' => 'vc_col-sm-6',
						'save_always' => true,
						"std" => "1",
						"value" => array("Show Follow Me" => "1" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "follow_link",
						"heading" => esc_html__("Follow link", 'trx_socials'),
						"description" => wp_kses_data( __("URL for the Follow link", 'trx_socials') ),
						'edit_field_class' => 'vc_col-sm-6',
						"class" => "",
						"value" => "",
						'dependency' => array(
							'element' => 'follow',
							'value' => '1'
						),
						"type" => "textfield"
					),
					array(
						"param_name" => "id",
						"heading" => esc_html__("Element ID", 'trx_socials'),
						"description" => wp_kses_data( __("ID for current element", 'trx_socials') ),
						'group' => esc_html__( 'ID &amp; Class', 'trx_socials' ),
						"admin_label" => true,
						"type" => "textfield"
					),
					array(
						"param_name" => "class",
						"heading" => esc_html__("Element CSS class", 'trx_socials'),
						"description" => wp_kses_data( __("CSS class for current element", 'trx_socials') ),
						'group' => esc_html__( 'ID &amp; Class', 'trx_socials' ),
						"admin_label" => true,
						"type" => "textfield"
					),
					array(
						'param_name' => 'css',
						'heading' => esc_html__( 'CSS box', 'trx_socials' ),
						'group' => esc_html__( 'Design Options', 'trx_socials' ),
						'type' => 'css_editor'
					)
				)
			), 'trx_widget_instagram');
	}
}
