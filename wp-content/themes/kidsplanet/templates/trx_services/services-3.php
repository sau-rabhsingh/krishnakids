<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'themerex_template_services_3_theme_setup' ) ) {
	add_action( 'themerex_action_before_init_theme', 'themerex_template_services_3_theme_setup', 1 );
	function themerex_template_services_3_theme_setup() {
		themerex_add_template(array(
			'layout' => 'services-3',
			'template' => 'services-3',
			'mode'   => 'services',
			'need_columns' => true,
			'title'  => esc_html__('Services /Style 3/', 'kidsplanet'),
			'thumb_title'  => esc_html__('Small square image (crop)', 'kidsplanet'),
			'w'		 => 300,
			'h'		 => 300
		));
	}
}

// Template output
if ( !function_exists( 'themerex_template_services_3_output' ) ) {
	function themerex_template_services_3_output($post_options, $post_data) {
		$show_title = true;
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$columns = max(1, min(12, empty($parts[1]) ? (!empty($post_options['columns_count']) ? $post_options['columns_count'] : 1) : (int) $parts[1]));
		if (themerex_param_is_on($post_options['slider'])) {
			?><div class="swiper-slide" data-style="<?php echo esc_attr($post_options['tag_css_wh']); ?>" style="<?php echo esc_attr($post_options['tag_css_wh']); ?>"><div class="sc_services_item_wrap"><?php
		} else if ($columns > 1) {
			?><div class="column-1_<?php echo esc_attr($columns); ?> column_padding_bottom"><?php
		}
		?>
			<div<?php themerex_show_layout($post_options['tag_id'] ? ' id="'.esc_attr($post_options['tag_id']).'"' : ''); ?>
				class="sc_services_item sc_services_item_<?php echo esc_attr($post_options['number']) . ($post_options['number'] % 2 == 1 ? ' odd' : ' even') . ($post_options['number'] == 1 ? ' first' : '') . (!empty($post_options['tag_class']) ? ' '.esc_attr($post_options['tag_class']) : ''); ?>"
				<?php themerex_show_layout(($post_options['tag_css']!='' ? ' style="'.esc_attr($post_options['tag_css']).'"' : '')
					. (!themerex_param_is_off($post_options['tag_animation']) ? ' data-animation="'.esc_attr(themerex_get_animation_classes($post_options['tag_animation'])).'"' : '')); ?>>
				<?php
                if ($show_title) {
                    ?><h3 class="sc_services_item_title"><?php themerex_show_layout($post_data['post_title']); ?></h3><?php
                }
				if ((!isset($post_options['links']) || $post_options['links']) && !empty($post_data['post_link'])) {
					?><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php
				}
				if ($post_data['post_icon'] && $post_options['tag_type']=='icons') {
					echo themerex_do_shortcode('[trx_icon icon="'.esc_attr($post_data['post_icon']).'" shape="round"]');
				} else {
					?>
					<div class="sc_services_item_featured post_featured">
						<?php require(themerex_get_file_dir('templates/_parts/post-featured.php')); ?>
					</div>
					<?php
				}

				?>
                <div class="sc_services_item_description">
                    <?php
                    if ($post_data['post_protected']) {
                        themerex_show_layout($post_data['post_excerpt']);
                    } else {
                        if ($post_data['post_excerpt']) {
                            echo in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status')) ? $post_data['post_excerpt'] : '<p>'.trim(themerex_strshort($post_data['post_excerpt'], isset($post_options['descr']) ? $post_options['descr'] : themerex_get_custom_option('post_excerpt_maxlength_masonry'))).'</p>';
                        }

                    }
                    ?>
                </div>
                    <?php
                    if ((!isset($post_options['links']) || $post_options['links']) && !empty($post_data['post_link'])) {
                    ?></a><?php
            }
                    ?>
			</div>
		<?php
		if (themerex_param_is_on($post_options['slider'])) {
			?></div></div><?php
		} else if ($columns > 1) {
			?></div><?php
		}
	}
}
?>