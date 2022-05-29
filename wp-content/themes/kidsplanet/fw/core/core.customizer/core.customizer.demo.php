<div class="to_demo_wrap">
	<a href="" class="to_demo_pin iconadmin-pin" title="<?php esc_attr_e('Pin/Unpin demo-block by the right side of the window', 'kidsplanet'); ?>"></a>
	<div class="to_demo_body_wrap">
		<div class="to_demo_body">
			<h1 class="to_demo_header"><?php esc_html_e('Header with', 'kidsplanet'); ?> <span class="to_demo_header_link"><?php esc_html_e('inner link', 'kidsplanet'); ?></span> <?php esc_html_e('and it', 'kidsplanet'); ?> <span class="to_demo_header_hover"><?php esc_html_e('hovered state', 'kidsplanet'); ?></span></h1>
			<p class="to_demo_info"><?php esc_html_e('Posted', 'kidsplanet'); ?> <span class="to_demo_info_link">12 <?php esc_html_e('May', 'kidsplanet'); ?>, 2015</span> <?php esc_html_e('by', 'kidsplanet'); ?> <span class="to_demo_info_hover"><?php esc_html_e('Author name hovered', 'kidsplanet'); ?></span>.</p>
			<p class="to_demo_text"><?php esc_html_e('This is default post content. Colors of each text element are set based on the color you choose below.', 'kidsplanet'); ?></p>
			<p class="to_demo_text"><span class="to_demo_text_link"><?php esc_html_e('link example', 'kidsplanet'); ?></span> <?php esc_html_e('and', 'kidsplanet'); ?> <span class="to_demo_text_hover"><?php esc_html_e('hovered link', 'kidsplanet'); ?></span></p>

			<?php 
			if (is_array($THEMEREX_GLOBALS['custom_colors']) && count($THEMEREX_GLOBALS['custom_colors']) > 0) {
				foreach ($THEMEREX_GLOBALS['custom_colors'] as $slug=>$scheme) { 
					?>
					<h3 class="to_demo_header"><?php esc_html_e('Accent colors', 'kidsplanet'); ?></h3>
					<?php if (isset($scheme['accent1'])) { ?>
						<div class="to_demo_columns3"><p class="to_demo_text"><span class="to_demo_accent1"><?php esc_html_e('accent', 'kidsplanet'); ?>1 <?php esc_html_e('example', 'kidsplanet'); ?></span> <?php esc_html_e('and', 'kidsplanet'); ?> <span class="to_demo_accent1_hover"><?php esc_html_e('hovered accent', 'kidsplanet'); ?>1</span></p></div>
					<?php } ?>
					<?php if (isset($scheme['accent2'])) { ?>
						<div class="to_demo_columns3"><p class="to_demo_text"><span class="to_demo_accent2"><?php esc_html_e('accent', 'kidsplanet'); ?>2 <?php esc_html_e('example', 'kidsplanet'); ?></span> <?php esc_html_e('and', 'kidsplanet'); ?> <span class="to_demo_accent2_hover"><?php esc_html_e('hovered accent', 'kidsplanet'); ?>2</span></p></div>
					<?php } ?>
					<?php if (isset($scheme['accent3'])) { ?>
						<div class="to_demo_columns3"><p class="to_demo_text"><span class="to_demo_accent3"><?php esc_html_e('accent', 'kidsplanet'); ?>3 <?php esc_html_e('example', 'kidsplanet'); ?></span> <?php esc_html_e('and', 'kidsplanet'); ?> <span class="to_demo_accent3_hover"><?php esc_html_e('hovered accent', 'kidsplanet'); ?>3</span></p></div>
					<?php } ?>
                    <?php if (isset($scheme['accent4'])) { ?>
                        <div class="to_demo_columns4"><p class="to_demo_text"><span class="to_demo_accent4">accent3 example</span> and <span class="to_demo_accent4_hover">hovered accent3</span></p></div>
                    <?php } ?>

					<h3 class="to_demo_header"><?php esc_html_e('Inverse colors (on accented backgrounds)', 'kidsplanet'); ?></h3>
					<?php if (isset($scheme['accent1'])) { ?>
						<div class="to_demo_columns3 to_demo_accent1_bg to_demo_inverse_block">
							<h4 class="to_demo_accent1_hover_bg to_demo_inverse_dark"><?php esc_html_e('Accented block header', 'kidsplanet'); ?></h4>
							<div>
								<p class="to_demo_inverse_light"><?php esc_html_e('Posted', 'kidsplanet'); ?> <span class="to_demo_inverse_link">12 <?php esc_html_e('May', 'kidsplanet'); ?>, 2015</span> <?php esc_html_e('by', 'kidsplanet'); ?> <span class="to_demo_inverse_hover"><?php esc_html_e('Author name hovered', 'kidsplanet'); ?></span>.</p>
								<p class="to_demo_inverse_text"><?php esc_html_e('This is a inversed colors example for the normal text', 'kidsplanet'); ?></p>
								<p class="to_demo_inverse_text"><span class="to_demo_inverse_link"><?php esc_html_e('link example', 'kidsplanet'); ?></span> <?php esc_html_e('and', 'kidsplanet'); ?> <span class="to_demo_inverse_hover"><?php esc_html_e('hovered link', 'kidsplanet'); ?></span></p>
							</div>
						</div>
					<?php } ?>
					<?php if (isset($scheme['accent2'])) { ?>
						<div class="to_demo_columns3 to_demo_accent2_bg to_demo_inverse_block">
							<h4 class="to_demo_accent2_hover_bg to_demo_inverse_dark"><?php esc_html_e('Accented block header', 'kidsplanet'); ?></h4>
							<div>
								<p class="to_demo_inverse_light"><?php esc_html_e('Posted', 'kidsplanet'); ?> <span class="to_demo_inverse_link">12 <?php esc_html_e('May', 'kidsplanet'); ?>, 2015</span> <?php esc_html_e('by', 'kidsplanet'); ?> <span class="to_demo_inverse_hover"><?php esc_html_e('Author name hovered', 'kidsplanet'); ?></span>.</p>
								<p class="to_demo_inverse_text"><?php esc_html_e('This is a inversed colors example for the normal text', 'kidsplanet'); ?></p>
								<p class="to_demo_inverse_text"><span class="to_demo_inverse_link"><?php esc_html_e('link example', 'kidsplanet'); ?></span> <?php esc_html_e('and', 'kidsplanet'); ?> <span class="to_demo_inverse_hover"><?php esc_html_e('hovered link', 'kidsplanet'); ?></span></p>
							</div>
						</div>
					<?php } ?>
					<?php if (isset($scheme['accent3'])) { ?>
						<div class="to_demo_columns3 to_demo_accent3_bg to_demo_inverse_block">
							<h4 class="to_demo_accent3_hover_bg to_demo_inverse_dark"><?php esc_html_e('Accented block header', 'kidsplanet'); ?></h4>
							<div>
								<p class="to_demo_inverse_light"><?php esc_html_e('Posted', 'kidsplanet'); ?> <span class="to_demo_inverse_link">12 <?php esc_html_e('May', 'kidsplanet'); ?>, 2015</span> <?php esc_html_e('by', 'kidsplanet'); ?> <span class="to_demo_inverse_hover"><?php esc_html_e('Author name hovered', 'kidsplanet'); ?></span>.</p>
								<p class="to_demo_inverse_text"><?php esc_html_e('This is a inversed colors example for the normal text', 'kidsplanet'); ?></p>
								<p class="to_demo_inverse_text"><span class="to_demo_inverse_link"><?php esc_html_e('link example', 'kidsplanet'); ?></span> <?php esc_html_e('and', 'kidsplanet'); ?> <span class="to_demo_inverse_hover"><?php esc_html_e('hovered link', 'kidsplanet'); ?></span></p>
							</div>
						</div>
					<?php } ?>
                    <?php if (isset($scheme['accent4'])) { ?>
                        <div class="to_demo_columns3 to_demo_accent4_bg to_demo_inverse_block">
                            <h4 class="to_demo_accent4_hover_bg to_demo_inverse_dark"><?php esc_html_e('Accented block header', 'kidsplanet'); ?></h4>
                            <div>
                                <p class="to_demo_inverse_light"><?php esc_html_e('Posted', 'kidsplanet'); ?> <span class="to_demo_inverse_link">12 <?php esc_html_e('May', 'kidsplanet'); ?>, 2015</span> <?php esc_html_e('by', 'kidsplanet'); ?> <span class="to_demo_inverse_hover"><?php esc_html_e('Author name hovered', 'kidsplanet'); ?></span>.</p>
                                <p class="to_demo_inverse_text"><?php esc_html_e('This is a inversed colors example for the normal text', 'kidsplanet'); ?></p>
                                <p class="to_demo_inverse_text"><span class="to_demo_inverse_link"><?php esc_html_e('link example', 'kidsplanet'); ?></span> <?php esc_html_e('and', 'kidsplanet'); ?> <span class="to_demo_inverse_hover"><?php esc_html_e('hovered link', 'kidsplanet'); ?></span></p>
                            </div>
                        </div>
                    <?php } ?>
					<?php 
					break;
				}
			}
			?>

			<h3 class="to_demo_header"><?php esc_html_e('Alternative colors used to decorate highlight blocks and form fields', 'kidsplanet'); ?></h3>
			<div class="to_demo_columns2">
				<div class="to_demo_alter_block">
					<h4 class="to_demo_alter_header"><?php esc_html_e('Highlight block header', 'kidsplanet'); ?></h4>
					<p class="to_demo_alter_text"><?php esc_html_e('This is a plain text in the highlight block. This is a plain text in the highlight block.', 'kidsplanet'); ?></p>
					<p class="to_demo_alter_text"><span class="to_demo_alter_link"><?php esc_html_e('link example', 'kidsplanet'); ?></span> <?php esc_html_e('and', 'kidsplanet'); ?> <span class="to_demo_alter_hover"><?php esc_html_e('hovered link', 'kidsplanet'); ?></span></p>
				</div>
			</div>
			<div class="to_demo_columns2">
				<div class="to_demo_form_fields">
					<h4 class="to_demo_header"><?php esc_html_e('Form field', 'kidsplanet'); ?></h4>
					<input type="text" class="to_demo_field" value="Input field example">
					<h4 class="to_demo_header"><?php esc_html_e('Form field focused', 'kidsplanet'); ?></h4>
					<input type="text" class="to_demo_field_focused" value="Focused field example">
				</div>
			</div>
		</div>
	</div>
</div>
