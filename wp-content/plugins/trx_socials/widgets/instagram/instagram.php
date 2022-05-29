<?php
/**
 * Widget: Instagram
 *
 * @package ThemeREX Socials
 * @since v1.0.0
 */

// Load widget
if (!function_exists('trx_socials_widget_instagram_load')) {
	add_action( 'widgets_init', 'trx_socials_widget_instagram_load' );
	function trx_socials_widget_instagram_load() {
		register_widget('trx_socials_widget_instagram');
	}
}

// Widget Class
class trx_socials_widget_instagram extends TRX_Socials_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'widget_instagram', 'description' => esc_html__('Last Instagram photos from anywhere', 'trx_socials') );
		parent::__construct( 'trx_socials_widget_instagram', esc_html__('ThemeREX Socials: Instagram Feed', 'trx_socials'), $widget_ops );
	}

	// Show widget
	function widget( $args, $instance ) {

		$title = apply_filters('widget_title', isset($instance['title']) ? $instance['title'] : '' );
		$demo = isset($instance['demo']) ? $instance['demo'] : 0;
		$demo_files = isset($instance['demo_files']) ? $instance['demo_files'] : array();
		$demo_thumb_size = ! empty($instance['demo_thumb_size'])
								? $instance['demo_thumb_size']
								: apply_filters( 'trx_socials_filter_thumb_size',
													trx_socials_get_thumb_size('avatar'),
													'trx_socials_widget_instagram',
													$instance
												);
		$links = isset($instance['links']) ? $instance['links'] : 'instagram';
		$follow = isset($instance['follow']) ? $instance['follow'] : 0;
		$follow_link = isset($instance['follow_link']) ? $instance['follow_link'] : '';
		$hashtag = isset($instance['hashtag']) ? $instance['hashtag'] : '';
		$count = isset($instance['count']) ? $instance['count'] : 1;
		if ( ! empty($demo) ) {
			if ( $links == 'instagram' ) {
				$links = 'popup';
			}
			if ( ! empty( $demo_files ) && is_string( $demo_files ) ) {
				// If images list from Gutenbers
				if ( strpos( $demo_files, '"image_url":' ) !== false ) {
					$demo_files = json_decode( $demo_files, true );

				// Else - images from widget or shortcode
				} else {
					$tmp = explode( '|', $demo_files );
					$demo_files = array();
					foreach( $tmp as $item ) {
						if ( ! empty( $item ) ) {
							$demo_files[] = array( 'image' => $item );
						}
					}
				}
			}
			if ( ! is_array( $demo_files ) ) {
				$demo_files = array();
			}
			$count = count( $demo_files );			
		}
		$columns = isset($instance['columns']) ? max( 1, min( $count, (int) $instance['columns'] ) ) : 1;
		$columns_gap = isset($instance['columns_gap']) ? max( 0, $instance['columns_gap'] ) : 0;

		trx_socials_get_template_part(TRX_SOCIALS_PLUGIN_WIDGETS . 'instagram/tpl.default.php',
									'trx_socials_args_widget_instagram', 
									apply_filters('trx_socials_filter_widget_args',
												array_merge($args, compact('title',
																			'links',
																			'follow',
																			'follow_link',
																			'demo',
																			'demo_files',
																			'demo_thumb_size',
																			'hashtag',
																			'count',
																			'columns',
																			'columns_gap')
															),
												$instance,
												'trx_socials_widget_instagram')
									);
	}

	// Update the widget settings.
	function update( $new_instance, $instance ) {
		$instance = array_merge($instance, $new_instance);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['follow'] = !empty($new_instance['follow']) ? 1 : 0;
		$instance['follow_link'] = ! empty( $new_instance['follow_link'] ) ? strip_tags( $new_instance['follow_link'] ) : '';
		$instance['demo'] = !empty($new_instance['demo']) ? 1 : 0;
		$instance['demo_files'] = strip_tags( $new_instance['demo_files'] );
		$instance['demo_thumb_size'] = ! empty( $new_instance['demo_thumb_size'] ) ? strip_tags( $new_instance['demo_thumb_size'] ) : '';
		$instance['links'] = strip_tags( $new_instance['links'] );
		$instance['hashtag'] = strip_tags( $new_instance['hashtag'] );
		$instance['count'] = (int) $new_instance['count'];
		$instance['columns'] = min($instance['count'], (int) $new_instance['columns']);
		$instance['columns_gap'] = max(0, $new_instance['columns_gap']);
		return apply_filters('trx_socials_filter_widget_args_update', $instance, $new_instance, 'trx_socials_widget_instagram');
	}

	// Displays the widget settings controls on the widget panel.
	function form( $instance ) {
		
		// Set up some default widget settings
		$instance = wp_parse_args( (array) $instance, apply_filters('trx_socials_filter_widget_args_default', array(
			'title' => '', 
			'follow' => 0,
			'follow_link' => '',
			'demo' => 0,
			'demo_files' => '',
			'demo_thumb_size' => apply_filters( 'trx_socials_filter_thumb_size',
													trx_socials_get_thumb_size( 'avatar' ),
													'trx_socials_widget_instagram',
													$instance
												), 
			'links' => 'instagram',
			'hashtag' => '', 
			'count' => 8,
			'columns' => 4,
			'columns_gap' => 0
			), 'trx_socials_widget_instagram')
		);
		
		do_action('trx_socials_action_before_widget_fields', $instance, 'trx_socials_widget_instagram');
		
		$this->show_field(array('name' => 'title',
								'title' => __('Title:', 'trx_socials'),
								'value' => $instance['title'],
								'type' => 'text'));
		
		do_action('trx_socials_action_after_widget_title', $instance, 'trx_socials_widget_instagram');
		
		$this->show_field(array('name' => 'demo',
								'title' => __('Demo mode', 'trx_socials'),
								'description' => __('Show demo images', 'trx_socials'),
								'value' => (int) $instance['demo'],
								'type' => 'checkbox'));

		$this->show_field(array('name' => 'demo_thumb_size',
								'title' => __('Thumb size', 'trx_socials'),
								'description' => __('Select a thumb size to show images', 'trx_socials'),
								'value' => $instance['demo_thumb_size'],
								'options' => is_admin() ? trx_socials_get_list_thumbnail_sizes() : array(),
								'dependency' => array(
									'demo' => '1'
								),
								'type' => 'select'));

		$this->show_field(array('name' => 'demo_files',
								'title' => __('Demo images', 'trx_socials'),
								'dependency' => array(
									'demo' => '1'
								),
								'value' => $instance['demo_files'],
								'multiple' => true,
								'type' => 'image'));

		$this->show_field(array('name' => 'hashtag',
								'title' => __('Hash tag:', 'trx_socials'),
								'description' => __('Filter photos by hashtag. If empty - display all recent photos', 'trx_socials'),
								'value' => $instance['hashtag'],
								'dependency' => array(
									'demo' => '0'
								),
								'type' => 'text'));
		
		$this->show_field(array('name' => 'count',
								'title' => __('Number of photos:', 'trx_socials'),
								'value' => max(1, min(30, (int) $instance['count'])),
								'dependency' => array(
									'demo' => '0'
								),
								'type' => 'text'));
		
		$this->show_field(array('name' => 'columns',
								'title' => __('Columns:', 'trx_socials'),
								'value' => max(1, min(12, (int) $instance['columns'])),
								'type' => 'text'));
		
		$this->show_field(array('name' => 'columns_gap',
								'title' => __('Columns gap:', 'trx_socials'),
								'value' => max(0, (int) $instance['columns_gap']),
								'type' => 'text'));
		
		$this->show_field(array('name' => 'links',
								'title' => __('Link images to:', 'trx_socials'),
								'description' => __('Where to send a visitor after clicking on the picture', 'trx_socials'),
								'value' => $instance['links'],
								'options' => trx_socials_get_list_sc_instagram_redirects(),
								'type' => 'select'));
		
		$this->show_field(array('name' => 'follow',
								'title' => __('Show button "Follow me"', 'trx_socials'),
								'description' => __('Add button "Follow me" after images', 'trx_socials'),
								'value' => (int) $instance['follow'],
								'type' => 'checkbox'));

		$this->show_field(array('name' => 'follow_link',
								'title' => __('Follow link', 'trx_socials'),
								'value' => $instance['follow_link'],
								'dependency' => array(
									'follow' => '1'
								),
								'type' => 'text'));

		do_action('trx_socials_action_after_widget_fields', $instance, 'trx_socials_widget_instagram');
	}
}

	
// Load required styles and scripts in the frontend
if ( !function_exists( 'trx_socials_widget_instagram_load_scripts_front' ) ) {
	add_action("wp_enqueue_scripts", 'trx_socials_widget_instagram_load_scripts_front');
	function trx_socials_widget_instagram_load_scripts_front() {
		wp_enqueue_style(  'trx_socials-widget_instagram', trx_socials_get_file_url(TRX_SOCIALS_PLUGIN_WIDGETS. 'instagram/instagram.css'), array(), null );
	}
}

// Load required styles and scripts for the admin
if ( !function_exists( 'trx_socials_widget_instagram_load_scripts_admin' ) ) {
	add_action("admin_enqueue_scripts", 'trx_socials_widget_instagram_load_scripts_admin');
	function trx_socials_widget_instagram_load_scripts_admin() {
		wp_enqueue_script( 'trx_socials-widget_instagram', trx_socials_get_file_url(TRX_SOCIALS_PLUGIN_WIDGETS . 'instagram/instagram_admin.js'), array('jquery'), null, true );
	}
}

// Localize admin scripts
if ( !function_exists( 'trx_socials_widget_instagram_localize_script_admin' ) ) {
	add_action("trx_socials_filter_localize_script_admin", 'trx_socials_widget_instagram_localize_script_admin');
	function trx_socials_widget_instagram_localize_script_admin( $vars ) {
		$nonce = get_transient( 'trx_socials_instagram_nonce' );
		if ( empty( $nonce ) ) {
			$nonce = md5( mt_rand() );
			set_transient( 'trx_socials_instagram_nonce', $nonce, 60*60 );
		}
		$client_id  = get_option('trx_socials_api_instagram_client_id');
		$vars['api_instagram_get_code_uri'] = 'https://api.instagram.com/oauth/authorize/'
												. '?client_id=' . urlencode( trx_socials_widget_instagram_get_client_id() )
												. '&scope=user_profile,user_media'		//basic,public_content
												. '&response_type=code'
												. '&redirect_uri=' . urlencode( trx_socials_widget_instagram_rest_get_redirect_uri() )
												. '&state=' . urlencode( $nonce . ( empty( $client_id ) ? '|' . trx_socials_widget_instagram_rest_get_return_url() : '' ) );
		return $vars;
	}
}

// Return Client ID from Instagram Application
if ( !function_exists( 'trx_socials_widget_instagram_get_client_id' ) ) {
	function trx_socials_widget_instagram_get_client_id() {
		$id = get_option('trx_socials_api_instagram_client_id');
		if ( empty( $id ) ) {
			$id = '106292364902857';
		}
		return $id;
	}
}

// Return Client Secret from Instagram Application
if ( !function_exists( 'trx_socials_widget_instagram_get_client_secret' ) ) {
	function trx_socials_widget_instagram_get_client_secret() {
		return get_option('trx_socials_api_instagram_client_secret');
	}
}


// Return list of the instagram redirects
if ( !function_exists( 'trx_socials_get_list_sc_instagram_redirects' ) ) {
	function trx_socials_get_list_sc_instagram_redirects() {
		return apply_filters('trx_socials_filter_get_list_sc_instagram_redirects', array(
			'none' => esc_html__('No links', 'trx_socials'),
			'popup' => esc_html__('Popup', 'trx_socials'),
			'instagram' => esc_html__('Instagram', 'trx_socials')
		));
	}
}



// trx_widget_instagram
//-------------------------------------------------------------
/*
[trx_widget_instagram id="unique_id" title="Widget title" count="6" columns="3" hashtag="my_hash"]
*/
if ( !function_exists( 'trx_socials_sc_widget_instagram' ) ) {
	function trx_socials_sc_widget_instagram($atts, $content=null){	
		$atts = array_merge(array(
			// Individual params
			"title" => "",
			'count'	=> 8,
			'columns' => 4,
			'columns_gap' => 0,
			'demo' => 0,
			'demo_files' => '',
			'demo_thumb_size' => apply_filters( 'trx_socials_filter_thumb_size',
													trx_socials_get_thumb_size( 'avatar' ),
													'trx_socials_widget_instagram',
													$atts
												),
			'hashtag' => '',
			'links' => 'instagram',
			'follow' => 0,
			'follow_link' => '',
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
			), $atts);

		// Add custom class from CSS (if called from VC)
		if ( ! empty( $atts['css'] )
			&& strpos( $atts['css'], '.vc_custom_' ) !== false
			&& defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' )
			&& function_exists( 'vc_shortcode_custom_css_class' )
		) {
			$atts['class'] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG,
											( ! empty($atts['class'] ) ? $atts['class'] . ' ' : '' ) . vc_shortcode_custom_css_class( $atts['css'], ' ' ),
											'trx_widget_instagram',
											$atts
										);
			$atts['css'] = '';
		}

		// Prepare demo_files (if called from VC)
		if (function_exists('vc_param_group_parse_atts') && !is_array($atts['demo_files'])) {
			$atts['demo_files'] = (array) vc_param_group_parse_atts( $atts['demo_files'] );
		}

		extract($atts);

		$type = 'trx_socials_widget_instagram';
		$output = '';
		if ( (int) $atts['count'] > 0 ) {
			global $wp_widget_factory;
			if ( is_object( $wp_widget_factory ) && isset( $wp_widget_factory->widgets, $wp_widget_factory->widgets[ $type ] ) ) {
				$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
								. ' class="widget_area sc_widget_instagram' 
									. (!empty($class) ? ' ' . esc_attr($class) : '') 
								. '"'
							. ($css ? ' style="'.esc_attr($css).'"' : '')
						. '>';
				ob_start();
				the_widget( $type, $atts, trx_socials_prepare_widgets_args($id ? $id.'_widget' : 'widget_instagram', 'widget_instagram') );
				$output .= ob_get_contents();
				ob_end_clean();
				$output .= '</div>';
			}
		}
		return apply_filters('trx_socials_sc_output', $output, 'trx_widget_instagram', $atts, $content);
	}
	add_shortcode("trx_widget_instagram", "trx_socials_sc_widget_instagram");
}

require_once trx_socials_get_file_dir(TRX_SOCIALS_PLUGIN_WIDGETS . "instagram/instagram_rest_api.php");
require_once trx_socials_get_file_dir(TRX_SOCIALS_PLUGIN_WIDGETS . "instagram/instagram_vc.php");
