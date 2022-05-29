<?php


/**
 * Theme sprecific functions and definitions
 */


/* Theme setup section
------------------------------------------------------------------- */

// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) ) $content_width = 1170; /* pixels */

// Add theme specific actions and filters
// Attention! Function were add theme specific actions and filters handlers must have priority 1
if ( !function_exists( 'themerex_theme_setup' ) ) {
	add_action( 'themerex_action_before_init_theme', 'themerex_theme_setup', 1 );
	function themerex_theme_setup() {

		// Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );

		// Enable support for Post Thumbnails
		add_theme_support( 'post-thumbnails' );

		// Custom header setup
		add_theme_support( 'custom-header', array('header-text'=>false));

		// Custom backgrounds setup
		add_theme_support( 'custom-background');

		// Supported posts formats
		add_theme_support( 'post-formats', array('gallery', 'video', 'audio', 'link', 'quote', 'image', 'status', 'aside', 'chat') );

		// Autogenerate title tag
		add_theme_support('title-tag');

		// Add user menu
		add_theme_support('nav-menus');

		// WooCommerce Support
		add_theme_support( 'woocommerce' );

		// Register theme menus
		add_filter( 'themerex_filter_add_theme_menus',		'themerex_add_theme_menus' );

		// Register theme sidebars
		add_filter( 'themerex_filter_add_theme_sidebars',	'themerex_add_theme_sidebars' );

		// Set options for importer
		add_filter( 'themerex_filter_importer_options',		'themerex_set_importer_options' );

        // Add tags to the head
        add_action('wp_head', 'themerex_head_add_page_meta', 1);

        // Add theme specified classes into the body
        add_filter( 'body_class', 							'themerex_body_classes' );

        // Gutenberg support
        add_theme_support( 'align-wide' );

	}
}


// Add/Remove theme nav menus
if ( !function_exists( 'themerex_add_theme_menus' ) ) {
	//Handler of add_filter( 'themerex_filter_add_theme_menus', 'themerex_add_theme_menus' );
	function themerex_add_theme_menus($menus) {
		return $menus;
	}
}


// Add theme specific widgetized areas
if ( !function_exists( 'themerex_add_theme_sidebars' ) ) {
	//Handler of add_filter( 'themerex_filter_add_theme_sidebars',	'themerex_add_theme_sidebars' );
	function themerex_add_theme_sidebars($sidebars=array()) {
		if (is_array($sidebars)) {
			$theme_sidebars = array(
				'sidebar_main'		=> esc_html__( 'Main Sidebar', 'kidsplanet'),
				'sidebar_outer'		=> esc_html__( 'Outer Sidebar', 'kidsplanet'),
				'sidebar_footer'	=> esc_html__( 'Footer Sidebar', 'kidsplanet')
			);
			if (themerex_exists_woocommerce()) {
				$theme_sidebars['sidebar_cart']  = esc_html__( 'WooCommerce Cart Sidebar', 'kidsplanet');
			}
			$sidebars = array_merge($theme_sidebars, $sidebars);
		}
		return $sidebars;
	}
}

// Add data to the head and to the beginning of the body
//------------------------------------------------------------------------

// Add theme specified classes to the body tag
if ( !function_exists('themerex_body_classes') ) {
    function themerex_body_classes( $classes ) {
        $classes[] = 'themerex_body';
        $classes[] = 'body_style_' . esc_attr(themerex_get_custom_option('body_style'));
        $classes[] = 'body_' . (themerex_get_custom_option('body_filled')=='yes' ? 'filled' : 'transparent');
        $classes[] = 'theme_skin_' . esc_attr(themerex_esc(themerex_get_custom_option('theme_skin')));
        $classes[] = 'article_style_' . esc_attr(themerex_get_custom_option('article_style'));

        $blog_style = themerex_get_custom_option(is_singular() && !themerex_get_global('blog_streampage') ? 'single_style' : 'blog_style');
        $classes[] = 'layout_' . esc_attr($blog_style);
        $classes[] = 'template_' . esc_attr(themerex_get_template_name($blog_style));

        $top_panel_position = themerex_get_custom_option('top_panel_position');
        if (!themerex_param_is_off($top_panel_position)) {
            $classes[] = 'top_panel_show';
            $classes[] = 'top_panel_' . esc_attr($top_panel_position);
        } else
            $classes[] = 'top_panel_hide';
        $classes[] = esc_attr(themerex_get_sidebar_class());

        if (themerex_get_custom_option('show_video_bg')=='yes' && (themerex_get_custom_option('video_bg_youtube_code')!='' || themerex_get_custom_option('video_bg_url')!=''))
            $classes[] = 'video_bg_show';

        return $classes;
    }
}

// Return text for the Privacy Policy checkbox
if ( ! function_exists('themerex_get_privacy_text' ) ) {
    function themerex_get_privacy_text() {
        $page = get_option( 'wp_page_for_privacy_policy' );
        $privacy_text = themerex_get_theme_option( 'privacy_text' );
        return apply_filters( 'themerex_filter_privacy_text', wp_kses_post(
                $privacy_text
                . ( ! empty( $page ) && ! empty( $privacy_text )
                    // Translators: Add url to the Privacy Policy page
                    ? ' ' . sprintf( esc_html__( 'For further details on handling user data, see our %s', 'kidsplanet' ),
                        '<a href="' . esc_url( get_permalink( $page ) ) . '" target="_blank">'
                        . esc_html__( 'Privacy Policy', 'kidsplanet' )
                        . '</a>' )
                    : ''
                )
            )
        );
    }
}

// Return text for the "I agree ..." checkbox
if ( ! function_exists( 'themerex_trx_utils_privacy_text' ) ) {
    add_filter( 'trx_utils_filter_privacy_text', 'themerex_trx_utils_privacy_text' );
    function themerex_trx_utils_privacy_text( $text='' ) {
        return themerex_get_privacy_text();
    }
}

//------------------------------------------------------------------------
// One-click import support
//------------------------------------------------------------------------

// Set theme specific importer options
if ( ! function_exists( 'themerex_importer_set_options' ) ) {
    add_filter( 'trx_utils_filter_importer_options', 'themerex_importer_set_options', 9 );
    function themerex_importer_set_options( $options=array() ) {
        if ( is_array( $options ) ) {
            // Save or not installer's messages to the log-file
            $options['debug'] = false;
            // Prepare demo data
            if ( is_dir( THEMEREX_THEME_PATH . 'demo/' ) ) {
                $options['demo_url'] = THEMEREX_THEME_PATH . 'demo/';
            } else {
                $options['demo_url'] = esc_url( themerex_get_protocol().'://demofiles.ancorathemes.com/kidsplanet/' ); // Demo-site domain
            }

            // Required plugins
            $options['required_plugins'] =  array(
                'essential-grid',
                'revslider',
                'the-events-calendar',
                'js_composer',
                'elegro-payment',
                'woocommerce',
                'instagram-widget-by-wpzoom'
            );

            $options['theme_slug'] = 'themerex';

            // Set number of thumbnails to regenerate when its imported (if demo data was zipped without cropped images)
            // Set 0 to prevent regenerate thumbnails (if demo data archive is already contain cropped images)
            $options['regenerate_thumbnails'] = 3;
            // Default demo
            $options['files']['default']['title'] = esc_html__( 'Kids Planet Demo', 'kidsplanet' );
            $options['files']['default']['domain_dev'] = esc_url(themerex_get_protocol().'://kidsplanet.ancorathemes.com'); // Developers domain
            $options['files']['default']['domain_demo']= esc_url(themerex_get_protocol().'://kidsplanet.ancorathemes.com'); // Demo-site domain

        }
        return $options;
    }
}


add_filter( 'tribe_events_embedded_map_style', 'modify_embedded_map_inline_styles' );
function modify_embedded_map_inline_styles() {
    return 'width: 100%; height: 250px';
}

// Add page meta to the head
if (!function_exists('themerex_head_add_page_meta')) {
    //Handler of add_action('wp_head', 'themerex_head_add_page_meta', 1);
    function themerex_head_add_page_meta() {
        ?>
        <meta charset="<?php bloginfo( 'charset' ); ?>" />
        <?php
        if (themerex_get_theme_option('responsive_layouts') == 'yes') {
            ?>
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
            <?php
        }
        if (floatval(get_bloginfo('version')) < "4.1") {
            ?>
            <title><?php wp_title( '|', true, 'right' ); ?></title>
            <?php
        }
        ?>
        <link rel="profile" href="//gmpg.org/xfn/11" />
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
        <?php
    }
}


/* GET, POST, COOKIE, SESSION manipulations
----------------------------------------------------------------------------------------------------- */

// Strip slashes if Magic Quotes is on
if (!function_exists('themerex_stripslashes')) {
    function themerex_stripslashes($val) {
        static $magic = 0;
        if ($magic === 0) {
            $magic = version_compare(phpversion(), '5.4', '>=')
                || (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()==1)
                || (function_exists('get_magic_quotes_runtime') && get_magic_quotes_runtime()==1)
                || strtolower(ini_get('magic_quotes_sybase'))=='on';
        }
        if (is_array($val)) {
            foreach($val as $k=>$v)
                $val[$k] = themerex_stripslashes($v);
        } else
            $val = $magic ? stripslashes(trim($val)) : trim($val);
        return $val;
    }
}


// Add class trx_utils_activated
if(!function_exists('trx_utils_add_body_class')) {
    if(!function_exists ( 'trx_utils_require_shortcode')){
        add_filter( 'body_class', 'trx_utils_add_body_class' );
        function trx_utils_add_body_class($classes){
            $classes[] = 'default_theme';
            return $classes;
        }
    }
}

// Add theme required plugins
if ( !function_exists( 'themerex_add_trx_utils' ) ) {
    add_filter( 'trx_utils_active', 'themerex_add_trx_utils' );
    function themerex_add_trx_utils($enable=true) {
        return true;
    }
}

/**
 * Fire the wp_body_open action.
 *
 * Added for backwards compatibility to support pre 5.2.0 WordPress versions.
 */
if ( ! function_exists( 'wp_body_open' ) ) {
    function wp_body_open() {
        /**
         * Triggered after the opening <body> tag.
         */
        do_action('wp_body_open');
    }
}

/* Include framework core files
------------------------------------------------------------------- */
// If now is WP Heartbeat call - skip loading theme core files
if (!isset($_POST['action']) || $_POST['action']!="heartbeat") {
	require_once( get_template_directory().'/fw/loader.php' );
}
?>