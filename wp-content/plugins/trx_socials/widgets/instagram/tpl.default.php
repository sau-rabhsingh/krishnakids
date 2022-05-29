<?php
/**
 * Template of the Widget "Instagram"
 *
 * @package ThemeREX Socials
 * @since v1.0.0
 */

$args = get_query_var('trx_socials_args_widget_instagram');
extract($args);

// Before widget (defined by themes)
trx_socials_show_layout($before_widget);
			
// Widget title if one was input (before and after defined by themes)
trx_socials_show_layout($title, $before_title, $after_title);

$resp = trx_socials_widget_instagram_get_recent_photos(array(
		'demo' => ! empty($demo) ? $demo : 0,
		'demo_files' => ! empty($demo_files) ? $demo_files : array(),
		'demo_thumb_size' => ! empty($demo_thumb_size) ? $demo_thumb_size : '',
		'media' => ! empty($media) ? $media : 'all',
		'hashtag' =>  ! empty($hashtag) ? $hashtag : '',
		'count' => max(1, (int) $count)
));
	
// Widget body
?><div class="widget_instagram_wrap">
	<div class="widget_instagram_images widget_instagram_images_columns_<?php
				echo esc_attr($columns);
				if ( ! empty( $columns_gap ) ) {
					echo ' ' . esc_attr( trx_socials_add_inline_css_class( 'margin-right:-' . trx_socials_prepare_css_value( $columns_gap ) ) );
				}
				?>"<?php
		// If images are not available from server side - add params to get images from client side
		if ( empty($resp['data']) || ! is_array($resp['data']) || count($resp['data']) == 0 ) {
			global $TRX_SOCIALS_STORAGE;
			if ( empty($TRX_SOCIALS_STORAGE['instagram_hash']) ) $TRX_SOCIALS_STORAGE['instagram_hash'] = array();
			if ( empty($TRX_SOCIALS_STORAGE['instagram_hash'][$hashtag]) ) $TRX_SOCIALS_STORAGE['instagram_hash'][$hashtag] = 0;
			$TRX_SOCIALS_STORAGE['instagram_hash'][$hashtag]++;
			$hash = md5( $hashtag . '-' . $TRX_SOCIALS_STORAGE['instagram_hash'][$hashtag] );
			set_transient( sprintf( 'trx_socials_instagram_args_%s', $hash ), $args, 60 );       // Store to the cache for 60s
			?>
			data-instagram-load="1"
			data-instagram-hash="<?php echo esc_attr( $hash ); ?>"
			data-instagram-hashtag="<?php echo esc_attr( $hashtag ); ?>"
			<?php
		}
	?>><?php
		// If images are available from server side
		if ( ! empty($resp['data']) && is_array($resp['data']) && count($resp['data']) > 0 ) {
			$user = '';
			$total = 0;
			foreach( $resp['data'] as $v ) {
				$total++;
				if ( empty($user) && !empty($v['user']['username']) ) {
					$user = $v['user']['username'];
				}
				$class = trx_socials_add_inline_css_class(
								'width:'.round(100/$columns, 4).'%;'
								. ( ! empty( $columns_gap )
									? 'padding: 0 ' . trx_socials_prepare_css_value( $columns_gap ) . ' ' . trx_socials_prepare_css_value( $columns_gap ) . ' 0;'
									: ''
									),
								'',
								'.widget_instagram_images_item_wrap'
								);
				$thumb_size = apply_filters( 'trx_socials_filter_instagram_thumb_size', 'standard_resolution' );
				trx_socials_show_layout(
					apply_filters(
						'trx_socials_filter_instagram_thumb_item',
						sprintf(
							'<div class="widget_instagram_images_item_wrap %6$s">'
								. ( $links != 'none' && ( $v['type'] != 'video' || $links == 'instagram' )
									? '<a href="%5$s"' . ( $links == 'instagram' ? ' target="_blank"' : '' )
									: '<div'
									)
								. ' title="%4$s"'
								. ' rel="magnific"'
								. ' class="widget_instagram_images_item widget_instagram_images_item_type_'.esc_attr($v['type'])
									. ( $v['type'] == 'video' && ! empty( $v['images'][$thumb_size]['url'] )	// && $links != 'none'
											? ' ' . trx_socials_add_inline_css_class('background-image: url('.esc_url($v['images']['standard_resolution']['url']).');')
											: ''
										)
									. '"'
							. '>'
							   		. ($v['type'] == 'video' && ! empty( $v['videos'] )
						   				? trx_socials_get_video_layout(array(
																			'link' => ! empty( $v['videos'][$thumb_size]['url'] ) ? $v['videos'][$thumb_size]['url'] : '',
																			'cover' => ! empty( $v['images'][$thumb_size]['url'] ) && $links != 'none'
																						? $v['images'][$thumb_size]['url']
																						: '',
																			'show_cover' => false,	//$links != 'none',
																			'popup' => $links == 'popup'
																			))
							   			: '<img src="%1$s" width="%2$d" height="%3$d" alt="%4$s">'
							   			)
							   		. '<span class="widget_instagram_images_item_counters">'
										. ( isset( $v['likes']['count'] ) && $v['likes']['count'] >= 0
											? '<span class="widget_instagram_images_item_counter_likes trx_socials_icon-heart' . (empty($v['likes']['count']) ? '-empty' : '') . '">'
												. esc_attr($v['likes']['count'])
												. '</span>'
											: '' )
										. ( isset( $v['comments']['count'] ) && $v['comments']['count'] >= 0
											? '<span class="widget_instagram_images_item_counter_comments trx_socials_icon-comment' . (empty($v['comments']['count']) ? '-empty' : '') . '">'
												. esc_attr($v['comments']['count'])
												. '</span>'
											: '' )
							   		. '</span>'
								. ($links != 'none' && ($v['type'] != 'video' || $links == 'instagram')
									? '</a>'
									: '</div>'
									)
							. '</div>',
							! empty( $v['images'][$thumb_size]['url'] ) ? esc_url($v['images'][$thumb_size]['url']) : '',
							! empty( $v['images'][$thumb_size]['width'] ) ? $v['images'][$thumb_size]['width'] : '',
							! empty( $v['images'][$thumb_size]['height'] ) ? $v['images'][$thumb_size]['height'] : '',
							! empty( $v['caption']['text'] ) ? esc_attr( $v['caption']['text'] ) : '',
							empty($demo) && $links == 'instagram'
								? esc_url( $v['link'] )
								: ( ! empty( $v['images'][$thumb_size]['url'] )
									? $v['images'][$thumb_size]['url']
									: '' ),
							$class
						),
						$v,
						$args
					)
				);
				if ( $total >= $count ) break;
			}
		} else {
			wp_enqueue_script( 'trx_socials-widget_instagram_load', trx_socials_get_file_url(TRX_SOCIALS_PLUGIN_WIDGETS . 'instagram/instagram_load.js'), array('jquery'), null, true );
		}
	?></div><?php	

	// Button 'Follow me' under images
	if ( $follow && ( ! empty( $follow_link ) || ( empty( $demo ) && ( ! empty( $hashtag ) || ! empty( $user ) ) ) ) ) {
		$url = ! empty( $follow_link )
				? esc_url( $follow_link )
				: 'https://www.instagram.com/'
						. ( ! empty( $hashtag ) && $hashtag[0] == '#'
							? 'explore/tags/' . substr( $hashtag, 1 )			// Get output by hashtag
							: trim( ! empty( $hashtag ) ? $hashtag : $user )	// Get output by username
							)
						. '/';
		?><div class="widget_instagram_follow_link_wrap"><a href="<?php echo esc_url($url); ?>"
					class="<?php echo esc_attr(apply_filters('trx_socials_filter_widget_instagram_link_classes', 'widget_instagram_follow_link sc_button', $args)); ?>"
					target="_blank"><?php
			if ( ! empty( $hashtag ) && $hashtag[0] == '#' ) {
				esc_html_e('View more', 'trx_socials');
			} else {
				esc_html_e('Follow Me', 'trx_socials');
			}
		?></a></div><?php
	}
?></div><?php	

// After widget (defined by themes)
trx_socials_show_layout($after_widget);