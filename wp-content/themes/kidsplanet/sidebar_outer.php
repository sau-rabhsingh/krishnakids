<?php
/**
 * The Sidebar Outer containing the outer (left or right) widget areas.
 */

$sidebar_show   = themerex_get_custom_option('show_sidebar_outer');
$sidebar_scheme = themerex_get_custom_option('sidebar_outer_scheme');
$sidebar_name   = themerex_get_custom_option('sidebar_outer');

if (!themerex_param_is_off($sidebar_show) && is_active_sidebar($sidebar_name)) {
	?>
	<div class="sidebar_outer widget_area scheme_<?php echo esc_attr($sidebar_scheme); ?>" role="complementary">
		<div class="sidebar_outer_inner widget_area_inner">
			<div class="sidebar_outer_logo_wrap">
			<?php
			if (themerex_get_custom_option('sidebar_outer_show_logo')=='yes') {
				?>
				<div class="sidebar_outer_logo">
					<?php require( themerex_get_file_dir('templates/_parts/logo.php') ); ?>
				</div>
				<?php
			}
			if (themerex_get_custom_option('sidebar_outer_show_socials')=='yes') {
				?>
				<div class="sidebar_outer_socials">
					<?php echo themerex_do_shortcode('[trx_socials size="tiny" shape="round"][/trx_socials]'); ?>
				</div>
				<?php
			}
			?>
			</div>
			<?php
			if (themerex_get_custom_option('sidebar_outer_show_menu')=='yes') {
				if (empty($THEMEREX_GLOBALS['menu_side']))	$THEMEREX_GLOBALS['menu_side'] = themerex_get_nav_menu('menu_side');
				if (!empty($THEMEREX_GLOBALS['menu_side']))	{
					?>
					<div class="sidebar_outer_menu">
						<?php themerex_show_layout($THEMEREX_GLOBALS['menu_side']); ?>
						<span class="sidebar_outer_menu_buttons">
						<a href="#" class="sidebar_outer_menu_responsive_button icon-down"><?php esc_html_e('Select menu item', 'kidsplanet'); ?></a>
						<?php if (themerex_get_custom_option('sidebar_outer_show_widgets')=='yes') { ?>
						<a href="#" class="sidebar_outer_widgets_button icon-book-open"></a>
						<?php } ?>
						</span>
					</div>
					<?php
				}
			}
			if (themerex_get_custom_option('sidebar_outer_show_widgets')=='yes') {
				?>
				<div class="sidebar_outer_widgets">
					<?php
					ob_start();
					do_action( 'before_sidebar' );
					global $THEMEREX_GLOBALS;
					$THEMEREX_GLOBALS['current_sidebar'] = 'outer';
                    if ( is_active_sidebar( $sidebar_name ) ) {
                        dynamic_sidebar( $sidebar_name );
                    }
					do_action( 'after_sidebar' );
					$out = ob_get_contents();
					ob_end_clean();
					themerex_show_layout(chop(preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $out)));
					?>
				</div> <!-- /.sidebar_outer_widgets -->
				<?php
			}
			?>
		</div> <!-- /.sidebar_outer_inner -->
	</div> <!-- /.sidebar_outer -->
	<?php
}
?>