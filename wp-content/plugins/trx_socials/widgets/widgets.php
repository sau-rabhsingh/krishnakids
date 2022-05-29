<?php
/**
 * ThemeREX Socials: Widgets
 *
 * @package ThemeREX Socials
 * @since v1.0.0
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) die( '-1' );


/* Widgets utilities
------------------------------------------------------------------------------------- */

// Prepare widgets args - substitute id and class in parameter 'before_widget'
if ( ! function_exists( 'trx_socials_prepare_widgets_args' ) ) {
	function trx_socials_prepare_widgets_args( $id, $class, $args=false ) {
		global $TRX_SOCIALS_STORAGE;
		static $widgets = array();
		$widgets[$id] = ( ! isset( $widgets[$id] ) ? 0 : $widgets[$id] ) + 1;
		$args = $args === false
				? $TRX_SOCIALS_STORAGE['widgets_args']
				: array_merge( $args, $TRX_SOCIALS_STORAGE['widgets_args'] );
		if ( ! empty( $args['widget_id'] ) ) {
			$id .= $widgets[$id] > 1
						? sprintf( '-%d', $widgets[$args['widget_id']] )
						: '';
			$args['widget_id'] = $id;
		}
		if ( ! empty( $args['before_widget'] ) ) {
			$args['before_widget'] = str_replace(
										array('%1$s', '%2$s'),
										array($id, $class),
										$args['before_widget']
									);
		}
		return $args;
	}
}


// Widget class
//--------------------------------------------------------------------

if ( ! class_exists( 'TRX_Socials_Widget' ) ) {

	class TRX_Socials_Widget extends WP_Widget {

		function __construct( $class, $title, $params ) {
			parent::__construct($class, $title, $params);
		}

		// Show one field in the widget's form
		function show_field( $params = array() ) {
			$params = array_merge( array(
										'type' => 'text',		// Field's type
										'name' => '',			// Field's name
										'title' => '',			// Title
										'description' => '',	// Description
										'class' => '',			// Additional classes
										'class_button' => '',	// Additional classes for button in mediamanager
										'multiple' => false,	// Allow select multiple images
										'rows' => 5,			// Number of rows in textarea
										'options' => array(),	// Options for select, checklist, radio, switch
										'params' => array(),	// Additional params for icons, etc.
										'label' => '',			// Alternative label for checkbox
										'value' => ''			// Field's value
										),
										$params
									);
			?><div class="widget_field_type_<?php echo esc_attr($params['type']);
					if (!empty($params['dir'])) echo ' widget_field_dir_'.esc_attr($params['dir']);
			?>"><?php
				if (!empty($params['title'])) {
					?><label class="widget_field_title"<?php if ($params['type']!='info') echo ' for="'.esc_attr($this->get_field_id($params['name'])).'"'; ?>><?php
						echo wp_kses_post($params['title']);
					?></label><?php
				}
				if (!empty($params['description'])) {
					?><div class="widget_field_description"><?php echo wp_kses_post($params['description']); ?></div>
					<?php
				}
				
				if ($params['type'] == 'select') {
					?><select id="<?php echo esc_attr($this->get_field_id($params['name'])); ?>"
							name="<?php echo esc_attr($this->get_field_name($params['name'])); ?>"
							class="widgets_param_fullwidth<?php if (!empty($params['class'])) echo ' '.esc_attr($params['class']); ?>"
					><?php
					if (is_array($params['options']) && count($params['options']) > 0) {
						foreach ($params['options'] as $slug => $name) {
							echo '<option value="'.esc_attr($slug).'"'.($slug==$params['value'] ? ' selected="selected"' : '').'>'
									. esc_html($name)
								. '</option>';
						}
					}
					?></select><?php

				} else if (in_array($params['type'], array('radio', 'switch'))) {
					if (is_array($params['options']) && count($params['options']) > 0) {
						?><div class="widgets_param_box<?php
							if (!empty($params['class'])) echo ' class="'.esc_attr($params['class']).'"';
						?>"><?php
						foreach ($params['options'] as $slug => $name) {
							?><label><input type="radio"
										id="<?php echo esc_attr($this->get_field_id($params['name']).'_'.$slug); ?>"
										name="<?php echo esc_attr($this->get_field_name($params['name'])); ?>"
										value="<?php echo esc_attr($slug); ?>"
										<?php if ($params['value']==$slug) echo ' checked="checked"'; ?> />
							<?php echo esc_html($name); ?></label> <?php
						}
						?></div><?php
					}

				} else if ($params['type'] == 'checkbox') {
					?><label<?php if (!empty($params['class'])) echo ' class="'.esc_attr($params['class']).'"'; ?>><?php
						?><input type="checkbox" id="<?php echo esc_attr($this->get_field_id($params['name'])); ?>" 
									name="<?php echo esc_attr($this->get_field_name($params['name'])); ?>" 
									value="1" <?php echo (1==$params['value'] ? ' checked="checked"' : ''); ?> /><?php
							echo esc_html(!empty($params['label']) ? $params['label'] : $params['title']);
					?></label><?php

				} else if ($params['type'] == 'checklist') {
					?><span class="widgets_param_box<?php
									if (!empty($params['class'])) echo ' '.esc_attr($params['class']);
									?>"
							data-field_name="<?php echo esc_attr($this->get_field_name($params['name'])); ?>[]">
						<?php 
						foreach ($params['options'] as $slug => $name) {
							?><label><input type="checkbox"
										value="<?php echo esc_attr($slug); ?>" 
										name="<?php echo esc_attr($this->get_field_name($params['name'])); ?>[]"
										<?php if (strpos(','.$params['value'].',', ','.$slug.',')!==false) echo ' checked="checked"'; ?>><?php
								echo esc_html($name);
							?></label><?php
						}
					?></span><?php
	
				} else if ($params['type'] == 'color') {
					?><input type="text"
							id="<?php echo esc_attr($this->get_field_id($params['name'])); ?>" 
							name="<?php echo esc_attr($this->get_field_name($params['name'])); ?>"
							value="<?php echo esc_attr($params['value']); ?>"
							class="trx_socials_color_selector<?php if (!empty($params['class'])) echo ' '.esc_attr($params['class']); ?>"
					/><?php

				} else if ( in_array( $params['type'], array('image', 'media', 'video', 'audio') ) ) {
					?><input type="hidden"
							id="<?php echo esc_attr($this->get_field_id($params['name'])); ?>" 
							class="trx_socials_image_selector_field"
							name="<?php echo esc_attr($this->get_field_name($params['name'])); ?>"
							<?php if (!empty($params['class'])) echo ' class="'.esc_attr($params['class']).'"'; ?>
							value="<?php echo esc_attr($params['value']); ?>"
					/><?php

					wp_enqueue_media( );

					$title   = esc_html__( 'Choose Images', 'trx_socials' );
					$images  = explode('|', $params['value']);

					?><span class="trx_socials_media_selector_preview trx_socials_media_selector_preview_<?php
						echo ( ! empty( $params['multiple'] ) ? 'multiple' : 'single' )
								. ( is_array( $images ) && count( $images ) > 0 ? ' trx_socials_media_selector_preview_with_image' : '' );
					?>"><?php
					if ( is_array( $images ) ) {
						foreach ( $images as $img ) {
							if ( ! empty( $img ) ) {
								?><span class="trx_socials_media_selector_preview_image" tabindex="0"><?php
									echo in_array( trx_socials_get_file_ext( $img ), array('gif', 'jpg', 'jpeg', 'png' ) )
											? '<img src="' . esc_url( $img ) . '" alt="' . esc_attr__( "Selected image", 'trx_socials' ) . '">'
											: '<a href="' . esc_attr( $img ) . '">' . esc_html( basename( $img ) ) . '</a>';
								?></span><?php
							}
						}
					}
					?></span><?php

					?><input type="button"
						id="<?php echo esc_attr( $this->get_field_id( $params['name'] ) . '_button' ); ?>"
						class="button mediamanager trx_socials_media_selector<?php
							echo ! empty($params['class_button']) ? ' '.esc_attr($params['class_button']) : '';
						?>"
						data-choose="<?php echo esc_attr( $title ); ?>"
						data-update="<?php echo esc_attr( $title ); ?>"
						data-multiple="<?php echo esc_attr(!empty($params['multiple']) ? '1' : '0'); ?>"
						data-type="<?php echo esc_attr(!empty($params['type']) ? $params['type'] : 'image'); ?>"
						data-linked-field="<?php echo esc_attr( $this->get_field_id( $params['name'] ) ); ?>"
						value="<?php echo esc_attr( $title ); ?>"
					><?php

				} else if ($params['type'] == 'textarea') {
					?><textarea id="<?php echo esc_attr($this->get_field_id($params['name'])); ?>" 
							name="<?php echo esc_attr($this->get_field_name($params['name'])); ?>"
							rows="<?php echo esc_attr($params['rows']); ?>"
							class="widgets_param_fullwidth<?php if (!empty($params['class'])) echo ' '.esc_attr($params['class']); ?>"><?php
								echo esc_html($params['value']);
					?></textarea><?php
	
				} else if ($params['type'] == 'text') {
					?><input type="text"
							id="<?php echo esc_attr($this->get_field_id($params['name'])); ?>" 
							name="<?php echo esc_attr($this->get_field_name($params['name'])); ?>"
							value="<?php echo esc_attr($params['value']); ?>"
							class="widgets_param_fullwidth<?php if (!empty($params['class'])) echo ' '.esc_attr($params['class']); ?>" /><?php
				}
				?>
			</div><?php
		}
	
	}
}

require_once TRX_SOCIALS_PLUGIN_DIR_WIDGETS . "instagram/instagram.php";
