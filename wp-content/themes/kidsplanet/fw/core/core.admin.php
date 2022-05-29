<?php
/**
 * ThemeREX Framework: Admin functions
 *
 * @package	themerex
 * @since	themerex 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* Admin actions and filters:
------------------------------------------------------------------------ */

if (is_admin()) {

	/* Theme setup section
	-------------------------------------------------------------------- */
	
	if ( !function_exists( 'themerex_admin_theme_setup' ) ) {
		add_action( 'themerex_action_before_init_theme', 'themerex_admin_theme_setup', 11 );
		function themerex_admin_theme_setup() {
			if ( is_admin() ) {
				add_action("admin_footer",			'themerex_admin_prepare_scripts', 9);
				add_action("admin_enqueue_scripts",	'themerex_admin_load_scripts');
				add_action('tgmpa_register',		'themerex_admin_register_plugins');

				// AJAX: Get terms for specified post type
				add_action('wp_ajax_themerex_admin_change_post_type', 		'themerex_callback_admin_change_post_type');
				add_action('wp_ajax_nopriv_themerex_admin_change_post_type','themerex_callback_admin_change_post_type');
			}
		}
	}
	
	// Load required styles and scripts for admin mode
	if ( !function_exists( 'themerex_admin_load_scripts' ) ) {
		//Handler of add_action("admin_enqueue_scripts", 'themerex_admin_load_scripts');
		function themerex_admin_load_scripts() {
            if (themerex_get_theme_option('debug_mode')=='yes') {
			    wp_enqueue_script( 'themerex-debug-script', themerex_get_file_url('js/core.debug.js'), array('jquery'), null, true );
            }
				wp_enqueue_style( 'themerex-admin-style', themerex_get_file_url('css/core.admin.css'), array(), null );
				wp_enqueue_script( 'themerex-admin-script', themerex_get_file_url('js/core.admin.js'), array('jquery'), null, true );
			if (themerex_strpos(add_query_arg(array()), 'widgets.php')!==false) {
				wp_enqueue_style( 'fontello-style', themerex_get_file_url('css/fontello-admin/css/fontello-admin.css'), array(), null );
				wp_enqueue_style( 'fontello-animations-style', themerex_get_file_url('css/fontello-admin/css/animation.css'), array(), null );
			}
		}
	}
	
	// Prepare required styles and scripts for admin mode
	if ( !function_exists( 'themerex_admin_prepare_scripts' ) ) {
		//Handler of add_action("admin_head", 'themerex_admin_prepare_scripts');
		function themerex_admin_prepare_scripts() {
            $vars = themerex_get_global('js_vars');
            if (empty($vars) || !is_array($vars)) $vars = array();
            $vars = array_merge($vars, array(
                'admin_mode' => true,
                'ajax_nonce' => wp_create_nonce('ajax_nonce'),
                'ajax_url' => admin_url('admin-ajax.php'),
                'user_logged_in' => true
            ));
            wp_localize_script('themerex-admin-script', 'THEMEREX_GLOBALS', apply_filters('themerex_action_add_scripts_inline', $vars));
		}
	}
	
	// AJAX: Get terms for specified post type
	if ( !function_exists( 'themerex_callback_admin_change_post_type' ) ) {
		//Handler of add_action('wp_ajax_themerex_admin_change_post_type', 		'themerex_callback_admin_change_post_type');
		//Handler of add_action('wp_ajax_nopriv_themerex_admin_change_post_type',	'themerex_callback_admin_change_post_type');
		function themerex_callback_admin_change_post_type() {
			if ( !wp_verify_nonce( $_REQUEST['nonce'], 'ajax_nonce' ) )
				wp_die();
			$post_type = themerex_get_value_gpc('post_type');
			$terms = themerex_get_list_terms(false, themerex_get_taxonomy_categories_by_post_type($post_type));
			$terms = themerex_array_merge(array(0 => esc_html__('- Select category -', 'kidsplanet')), $terms);
			$response = array(
				'error' => '',
				'data' => array(
					'ids' => array_keys($terms),
					'titles' => array_values($terms)
				)
			);
			echo json_encode($response);
			wp_die();
		}
	}

	// Return current post type in dashboard
	if ( !function_exists( 'themerex_admin_get_current_post_type' ) ) {
		function themerex_admin_get_current_post_type() {
			global $post, $typenow, $current_screen;
			if ( $post && $post->post_type )							//we have a post so we can just get the post type from that
				return $post->post_type;
			else if ( $typenow )										//check the global $typenow — set in admin.php
				return $typenow;
			else if ( $current_screen && $current_screen->post_type )	//check the global $current_screen object — set in sceen.php
				return $current_screen->post_type;
			else if ( isset( $_REQUEST['post_type'] ) )					//check the post_type querystring
				return sanitize_key( $_REQUEST['post_type'] );
			else if ( isset( $_REQUEST['post'] ) ) {					//lastly check the post id querystring
				$post = get_post( sanitize_key( $_REQUEST['post'] ) );
				return !empty($post->post_type) ? $post->post_type : '';
			} else														//we do not know the post type!
				return '';
		}
	}
	
	// Register optional plugins
	if ( !function_exists( 'themerex_admin_register_plugins' ) ) {
		function themerex_admin_register_plugins() {

			$plugins = apply_filters('themerex_filter_required_plugins', array(
				array(
					'name' 		=> esc_html__('Themerex Utilities', 'kidsplanet'),
					'slug' 		=> 'themerex-utils',
					'version'	=> '2.3.4',
					'source'	=> themerex_get_file_dir('plugins/themerex-utils.zip'),
					'required' 	=> true
				),
				array(
					'name' 		=> esc_html__('WPBakery Page Builder', 'kidsplanet'),
					'slug' 		=> 'js_composer',
					'version'	=> '6.7.0',
					'source'	=> themerex_get_file_dir('plugins/js_composer.zip'),
					'required' 	=> false
				),
				array(
					'name' 		=> esc_html__('Revolution Slider', 'kidsplanet'),
					'slug' 		=> 'revslider',
					'version'	=> '6.5.11',
					'source'	=> themerex_get_file_dir('plugins/revslider.zip'),
					'required' 	=> false
				),
				array(
					'name' 		=> esc_html__('WooCommerce', 'kidsplanet'),
					'slug' 		=> 'woocommerce',
					'required' 	=> false
				),
                array(
                    'name' 		=> esc_html__('Essential Grid', 'kidsplanet'),
                    'slug' 		=> 'essential-grid',
                    'version'	=> '3.0.13',
                    'source'	=> themerex_get_file_dir('plugins/essential-grid.zip'),
                    'required' 	=> false
                ),
				array(
					'name' 		=> esc_html__('Contact Form 7', 'kidsplanet'),
					'slug' 		=> 'contact-form-7',
					'required' 	=> false
				),
                array(
                    'name' 		=> esc_html__('Tribe Events Calendar', 'kidsplanet'),
                    'slug' 		=> 'the-events-calendar',
                    'required' 	=> false
                ),
                array(
                    'name' 		=> esc_html__('ThemeREX Updater', 'kidsplanet'),
                    'slug' 		=> 'trx_updater',
                    'version'   => '1.9.6',
                    'source'	=> themerex_get_file_dir('plugins/trx_updater.zip'),
                    'required' 	=> false
                ),
					 array(
						'name' 		=> esc_html__('ThemeREX Socials', 'kidsplanet'),
						'slug' 		=> 'trx_socials',
						'version'   => '1.4.3',
						'source'	=> themerex_get_file_dir('plugins/trx_socials.zip'),
						'required' 	=> false
				  ),
                array(
                    'name' 		=> esc_html__('Elegro Payment', 'kidsplanet'),
                    'slug' 		=> 'elegro-payment',
                    'required' 	=> false
                ),
                array(
                    'name'   => esc_html__('Cookie Information', 'kidsplanet'),
                    'slug'   => 'wp-gdpr-compliance',
                    'required'  => false
                )
			));
            $config = array(
                'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
                'default_path' => '',                      // Default absolute path to bundled plugins.
                'menu'         => 'tgmpa-install-plugins', // Menu slug.
                'parent_slug'  => 'themes.php',            // Parent menu slug.
                'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
                'has_notices'  => true,                    // Show admin notices or not.
                'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
                'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
                'is_automatic' => true,                    // Automatically activate plugins after installation or not.
                'message'      => ''                       // Message to output right before the plugins table.
            );
	
			tgmpa( $plugins, $config );
		}
	}

	require_once( themerex_get_file_dir('lib/tgm/class-tgm-plugin-activation.php') );
}

?>