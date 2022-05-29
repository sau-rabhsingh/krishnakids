<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'themerex_template_no_articles_theme_setup' ) ) {
	add_action( 'themerex_action_before_init_theme', 'themerex_template_no_articles_theme_setup', 1 );
	function themerex_template_no_articles_theme_setup() {
		themerex_add_template(array(
			'layout' => 'no-articles',
			'mode'   => 'internal',
			'title'  => esc_html__('No articles found', 'kidsplanet'),
			'w'		 => null,
			'h'		 => null
		));
	}
}

// Template output
if ( !function_exists( 'themerex_template_no_articles_output' ) ) {
	function themerex_template_no_articles_output($post_options, $post_data) {
		?>
		<article class="post_item">
			<div class="post_content">
				<h2 class="post_title"><?php esc_html_e('No posts found', 'kidsplanet'); ?></h2>
				<p><?php esc_html_e( 'Sorry, but nothing matched your search criteria.', 'kidsplanet'); ?></p>
				<p><?php echo wp_kses_data(sprintf(__('Go back, or return to <a href="%s">%s</a> home page to choose a new page.', 'kidsplanet'), esc_url(home_url( '/' )), get_bloginfo())); ?>
				<br><?php esc_html_e('Please report any broken links to our team.', 'kidsplanet'); ?></p>
				<?php echo themerex_do_shortcode('[trx_search open="fixed"]'); ?>
			</div>	<!-- /.post_content -->
		</article>	<!-- /.post_item -->
		<?php
	}
}
?>