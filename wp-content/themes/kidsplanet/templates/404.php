<?php
/*
 * The template for displaying "Page 404"
*/

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'themerex_template_404_theme_setup' ) ) {
	add_action( 'themerex_action_before_init_theme', 'themerex_template_404_theme_setup', 1 );
	function themerex_template_404_theme_setup() {
		themerex_add_template(array(
			'layout' => '404',
			'mode'   => 'internal',
			'title'  => 'Page 404',
			'theme_options' => array(
				'article_style' => 'stretch'
			),
			'w'		 => null,
			'h'		 => null
			));
	}
}

// Template output
if ( !function_exists( 'themerex_template_404_output' ) ) {
	function themerex_template_404_output() {
		?>
            <article class="post_item post_item_404">
                <div class="post_content">
                    <div class="vc_row wpb_row vc_row-fluid">
                        <div class="wpb_column vc_column_container vc_col-sm-12">
                            <div class="vc_column-inner ">
                                <div class="wpb_wrapper">
                                    <div class="columns_wrap sc_columns columns_nofluid sc_columns_count_2">
                                        <div class="column-1_2 sc_column_item sc_column_item_1 odd first">
                                            <figure class="sc_image  sc_image_shape_square">
                                                <?php
                                                $attach = themerex_get_file_url('images/toys.png');
                                                ?>
                                                <img src="<?php themerex_show_layout($attach); ?>" alt="<?php esc_attr_e('img', 'kidsplanet'); ?>">
                                            </figure>
                                        </div>
                                        <div class="column-1_2 sc_column_item sc_column_item_2 even">
                                            <h3 class="sc_title sc_title_regular">
                                                <?php esc_html_e('Sorry! Error 404!', 'kidsplanet') ?>
                                            </h3>
                                            <h3 class="sc_title sc_title_regular">
                                                <?php echo sprintf(
                                                                esc_html__('The requested page %s cannot be found%s.', 'kidsplanet'),
                                                                '<span class="accent1">',
                                                                '</span>'
                                                        )
                                                ?>
                                            </h3>
                                            <div class="wpb_text_column wpb_content_element ">
                                                <div class="wpb_wrapper">
                                                    <p>
                                                        <?php echo wp_kses_data(__("Can't find what you need? Take a moment and do a search below or<br> start from our homepage.", 'kidsplanet')) ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="search_wrap search_style_regular search_state_fixed search_ajax inited">
                                                <div class="search_form_wrap">
                                                    <form role="search" method="get" class="search_form" action="<?php echo esc_url( home_url( '/' ) ) ?>">
                                                        <button type="submit" class="search_submit icon-search" title="<?php esc_attr_e('Start search', 'kidsplanet') ?>"></button>
                                                        <input type="text" class="search_field" placeholder="<?php esc_attr_e('Search', 'kidsplanet') ?>" value="" name="s" title="<?php esc_attr_e('Search', 'kidsplanet') ?>">
                                                    </form>
                                                </div>
                                                <div class="search_results widget_area scheme_original"><a class="search_results_close icon-cancel"></a>
                                                    <div class="search_results_content"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
		<?php
	}
}
?>