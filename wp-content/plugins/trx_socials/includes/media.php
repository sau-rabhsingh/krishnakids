<?php
/**
 * Media utilities
 *
 * @package ThemeREX Socials
 * @since v1.0.0
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) die( '-1' );


// Get image sizes from image url (if image in the uploads folder)
if (!function_exists('trx_socials_getimagesize')) {
	function trx_socials_getimagesize($url, $echo=false) {

		$img_size = false;

		// It's a local file
		if ( strpos( $url, '//' ) === false ) {
			
			if ( file_exists( $url ) ) {
				$img_size = getimagesize($url);
			}

		// It's a remote file (url given)
		} else {

			// Remove scheme from url
			$url = trx_socials_remove_protocol($url);
		
			// Get upload path & dir
			$upload_info = wp_upload_dir();

			// Where check file
			$locations = array(
				'uploads' => array(
					'dir' => $upload_info['basedir'],
					'url' => trx_socials_remove_protocol($upload_info['baseurl'])
					),
				'child' => array(
					'dir' => get_stylesheet_directory(),
					'url' => trx_socials_remove_protocol(get_stylesheet_directory_uri())
					),
				'theme' => array(
					'dir' => get_template_directory(),
					'url' => trx_socials_remove_protocol(get_template_directory_uri())
					)
				);
			
			foreach($locations as $key=>$loc) {
				// Check if $img_url is local.
				if ( false === strpos($url, $loc['url']) ) continue;
				
				// Get path of image.
				$img_path = str_replace($loc['url'], $loc['dir'], $url);

				// Check if img path exists, and is an image indeed.
				if ( !file_exists($img_path)) continue;
		
				// Get image size
				$img_size = getimagesize($img_path);
				break;
			}
		}
		
		if ($echo && $img_size!==false && !empty($img_size[3])) {
			echo ' '.trim($img_size[3]);
		}
		
		return $img_size;
	}
}

// Return image size name with @retina modifier (if need)
if (!function_exists('trx_socials_get_thumb_size')) {
	function trx_socials_get_thumb_size( $ts ) {
		$retina = trx_socials_get_retina_multiplier() > 1 ? '-@retina' : '';
		return apply_filters(
					'trx_socials_filter_get_thumb_size',
					( in_array( $ts, array( 'full', 'post-thumbnail' ) ) || strpos( $ts, 'trx_socials-thumb-' ) === 0
						? ''
						: 'trx_socials-thumb-'
					)
					. $ts . $retina);
	}
}

// Clear thumb sizes from image name
if (!function_exists('trx_socials_clear_thumb_size')) {
	function trx_socials_clear_thumb_size( $url, $remove_protocol = true ) {
		$pi = pathinfo($url);
		if ( $remove_protocol ) {
			$pi['dirname'] = trx_socials_remove_protocol($pi['dirname']);
		}
		$parts = explode('-', $pi['filename']);
		$suff = explode('x', $parts[count($parts)-1]);
		if (count($suff)==2 && (int) $suff[0] > 0 && (int) $suff[1] > 0) {
			array_pop($parts);
			$url = $pi['dirname'] . '/' . join('-', $parts) . '.' . $pi['extension'];
		}
		return $url;
	}
}

// Add thumb sizes to image name
if (!function_exists('trx_socials_add_thumb_size')) {
	function trx_socials_add_thumb_size( $url, $thumb_size, $check_exists = true ) {

		if ( empty( $url ) ) return '';

		$pi = pathinfo( $url );

		// Remove image sizes from filename
		$parts = explode( '-', $pi['filename'] );
		$suff = explode( 'x', $parts[ count( $parts ) - 1 ] );
		if ( count( $suff ) == 2 && (int) $suff[0] > 0 && (int) $suff[1] > 0) {
			array_pop( $parts );
		}
		$url = $pi['dirname'] . '/' . join( '-', $parts ) . '.' . $pi['extension'];

		// Add new image sizes
		global $_wp_additional_image_sizes;
		if ( isset( $_wp_additional_image_sizes[$thumb_size] ) && is_array( $_wp_additional_image_sizes[$thumb_size] ) ) {
			if ( empty( $_wp_additional_image_sizes[ $thumb_size ]['height'] ) || empty( $_wp_additional_image_sizes[ $thumb_size ]['crop'] ) ) {
				$image_id = trx_socials_attachment_url_to_postid( $url );
				if ( is_numeric( $image_id ) && (int) $image_id > 0 ) {
					$attach = wp_get_attachment_image_src( $image_id, $thumb_size );
					if ( ! empty( $attach[0] ) ) {
						$pi = pathinfo( $attach[0] );
						$pi['dirname'] = trx_socials_remove_protocol( $pi['dirname'] );
						$parts = explode( '-', $pi['filename'] );
					}
				}
			} else {
				$parts[] = intval( $_wp_additional_image_sizes[ $thumb_size ]['width'] ) . 'x' . intval( $_wp_additional_image_sizes[ $thumb_size ]['height'] );
			}
		}
		$pi['filename'] = join( '-', $parts );
		$new_url = trx_socials_remove_protocol( $pi['dirname'] . '/' . $pi['filename'] . '.' . $pi['extension'] );

		// Check exists
		if ( $check_exists ) {
			$uploads_info = wp_upload_dir();
			$uploads_url = trx_socials_remove_protocol( $uploads_info['baseurl'] );
			$uploads_dir = $uploads_info['basedir'];
			if ( strpos( $new_url, $uploads_url ) !== false ) {
				if ( ! file_exists( str_replace( $uploads_url, $uploads_dir, $new_url ) ) ) {
					$new_url = trx_socials_remove_protocol( $url );
				}
			} else {
				$new_url = trx_socials_remove_protocol( $url );
			}
		}
		return $new_url;
	}
}

// Return all thumbnails sizes
if (!function_exists('trx_socials_get_list_thumbnail_sizes') ){
	function trx_socials_get_list_thumbnail_sizes() {
		$list = array();
		$thumbnails = get_intermediate_image_sizes();
		$list['full'] = esc_html__('Full size', 'trx_socials');
		foreach ($thumbnails as $thumbnail ) {
			if( !empty($GLOBALS['_wp_additional_image_sizes'][$thumbnail]) ){
				$width = $GLOBALS['_wp_additional_image_sizes'][$thumbnail]['width'];
				$height = $GLOBALS['_wp_additional_image_sizes'][$thumbnail]['height'];
			} else {
				$width = get_option($thumbnail . '_size_w', '');
				$height = get_option($thumbnail . '_size_h', '');
			}
			$list[$thumbnail] = $thumbnail . ' (' . $width . 'x' . $height . ')';
		}
		return $list;
	}
}

// Return thumb dimensions by thumb size name
if (!function_exists('trx_socials_get_thumb_dimensions')) {
	function trx_socials_get_thumb_dimensions($thumb_size) {
		$dim = array('width' => 0, 'height' => 0);
		global $_wp_additional_image_sizes;
		if ( isset( $_wp_additional_image_sizes ) && count( $_wp_additional_image_sizes ) && in_array( $thumb_size, array_keys( $_wp_additional_image_sizes ) ) ) {
			$dim['width']  = intval( $_wp_additional_image_sizes[$thumb_size]['width'] );
			$dim['height'] = intval( $_wp_additional_image_sizes[$thumb_size]['height'] );
		}
		return $dim;
	}
}

// Return image size multiplier
if (!function_exists('trx_socials_get_retina_multiplier')) {
	function trx_socials_get_retina_multiplier($force_retina=0) {
		return 1;
	}
}

// Return 'no-image'
if (!function_exists('trx_socials_get_no_image')) {
	function trx_socials_get_no_image($img='css/images/no-image.jpg') {
		return apply_filters('trx_socials_filter_no_image', trx_socials_get_file_url($img));
	}
}


// Return video player layout
if (!function_exists('trx_socials_get_video_layout')) {
	function trx_socials_get_video_layout($args=array()) {
		$args = array_merge(array(
			'link' => '',					// Link to the video on Youtube or Vimeo
			'embed' => '',					// Embed code instead link
			'cover' => '',					// URL or ID of the cover image
			'cover_size' => 'masonry-big',	// Thumb size of the cover image
			'show_cover' => true,			// Show cover image or only add classes
			'popup' => false,				// Open video in the popup window or insert instead cover image (default)
			'autoplay' => false,			// Make video autoplay
			'mute' => false,				// Mute video
			'class' => '',					// Additional classes for slider container
			'id' => ''						// ID of the slider container
			), $args);

		if ( empty($args['embed']) && empty($args['link']) ) {
			return '';
		}
		if ( empty($args['cover']) ) {
			$args['popup'] = false;
		}
		if ( empty($args['id']) ) {
			$args['id'] = trx_socials_generate_id( 'sc_video_' );
		}

		$output = '<div'
					. ( !empty($args['id']) ? ' id="' . esc_attr($args['id']) . '"' : '' )
					. ' class="trx_socials_video_player' 
								. (!empty($args['cover']) ? ' with_cover hover_play' : ' without_cover')
								. (!empty($args['autoplay']) && !empty($args['mute']) ? ' with_video_autoplay' : '')
								. (!empty($args['class']) ? ' ' . esc_attr($args['class']) : '')
							. '"'
					. '>';
		$args['embed'] = trx_socials_get_embed_layout(array(
														'link' => $args['link'],
														'embed' => $args['embed']
													));
		if ( ! empty($args['cover']) ) {
			$args['cover'] = trx_socials_get_attachment_url($args['cover'], 
										apply_filters('trx_socials_filter_video_cover_thumb_size', trx_socials_get_thumb_size($args['cover_size'])));
			if ( ! empty($args['cover'])) {
				$args['embed'] = trx_socials_make_video_autoplay($args['embed']);
				if ( $args['show_cover'] ) {
					$attr = trx_socials_getimagesize($args['cover']);
					$output .= '<img src="' . esc_url($args['cover']) . '" alt="' . esc_attr__("Video cover", 'trx_socials') . '"' . (!empty($attr[3]) ? ' '.trim($attr[3]) : '').'>';
				}
				$output .= apply_filters('trx_socials_filter_video_mask',
								'<div class="video_mask"></div>'
								. ($args['popup']
										? '<a class="video_hover trx_socials_popup_link" href="#'.esc_attr($args['id']).'_popup"></a>'
										: '<div class="video_hover" data-video="'.esc_attr($args['embed']).'"></div>'
								),
								$args);
			}
		}
		if ( empty($args['cover']) && !empty($args['autoplay']) ) {
			$args['embed'] = trx_socials_make_video_autoplay( $args['embed'], $args['mute'] );
		}
		if ( empty($args['popup']) ) {
			$output .= '<div class="video_embed video_frame">'
							. (empty($args['cover']) ? $args['embed'] : '')
						. '</div>';
		}
		$output .= '</div>';
		// Add popup
		if ( ! empty($args['popup']) ) {
			// Attention! Don't remove comment <!-- .sc_layouts_popup --> - it used to split output on parts in the sc_promo
			$output .= '<!-- .sc_layouts_popup -->'
						. '<div' . ( !empty($args['id']) ? ' id="' . esc_attr($args['id']) . '_popup"' : '' ) . ' class="sc_layouts_popup">'
							. '<div'
								. ( !empty($args['id']) ? ' id="' . esc_attr($args['id']) . '_popup_player"' : '' )
								. ' class="trx_socials_video_player without_cover'
											. (!empty($args['class']) ? ' ' . esc_attr($args['class']) : '')
										. '"'
							. '>'
								. '<div class="video_embed video_frame">'
									. str_replace(array('wp-video', 'src='), array('trx_socials_video', 'data-src='), $args['embed'])
								. '</div>'
							. '</div>'
						. '</div>';
		}
		return apply_filters('trx_socials_filter_video_layout', $output, $args);
	}
}


// Return embeded code layout
if (!function_exists('trx_socials_get_embed_layout')) {
	function trx_socials_get_embed_layout($args=array()) {
		$args = array_merge(array(
			'link' => '',					// Link to the video on Youtube or Vimeo
			'embed' => '',					// Embed code instead link
			), $args);

		if (empty($args['embed']) && empty($args['link'])) {
			return '';
		}
		if ( ! empty($args['embed']) ) {
			$args['embed'] = str_replace("`", '"', $args['embed']);
		} else {
			global $wp_embed;
			if (is_object($wp_embed)) {
				$args['embed'] = do_shortcode( $wp_embed->run_shortcode( '[embed]' . trim( $args['link'] ) . '[/embed]' ) );
				if ( strpos( $args['embed'], '<iframe' ) !== false ) {
					$dim = apply_filters( 'trx_socials_filter_video_dimensions', array(
						'width'  => trx_socials_get_tag_attrib( $args['embed'], '<iframe>', 'width' ),
						'height' => trx_socials_get_tag_attrib( $args['embed'], '<iframe>', 'height' )
					) );
					if ( $dim['width'] > 0 ) {
						$args['embed'] = trx_socials_set_tag_attrib( $args['embed'], '<iframe>', 'width', $dim['width'] );
						$args['embed'] = trx_socials_set_tag_attrib( $args['embed'], '<iframe>', 'height', $dim['height'] );
					}
				} else if ( strpos( $args['embed'], '<video' ) === false ) {
					$args['embed'] = apply_filters( 'trx_socials_filter_embed_video_link',
										'<video src="' . esc_url( $args['link'] ) . '" controls="controls" loop="loop"></video>',
										$args
										);
				}
			}
		}
		return apply_filters('trx_socials_filter_embed_layout', $args['embed'], $args);
	}
}



// Return image url by attachment ID
if (!function_exists('trx_socials_get_attachment_url')) {
	function trx_socials_get_attachment_url($image_id, $size='full') {
		if (is_numeric( $image_id ) && (int) $image_id > 0 ) {
			$attach = wp_get_attachment_image_src($image_id, $size);
			$image_id = empty( $attach[0] ) ? '' : $attach[0];
		} else {
			$image_id = trx_socials_add_thumb_size($image_id, $size);
		}
		return $image_id;
	}
}

// Return attachment id by url
if (!function_exists('trx_socials_attachment_url_to_postid')) {
	function trx_socials_attachment_url_to_postid($url) {
		static $images = array();
		if ( ! isset( $images[$url] ) ) {
			$images[$url] = attachment_url_to_postid( trx_socials_clear_thumb_size( $url, false ) );
		}
		return $images[$url];
	}
}

// Return the media caption for the specified id
if (!function_exists( 'trx_socials_get_attachment_caption' ) ) {
	function trx_socials_get_attachment_caption( $id ) {
		$caption = '';
		if ( is_numeric( $id ) && (int) $id > 0 ) {
			$meta = get_post_meta( intval( $id ) );
			$alt = '';
			if ( ! empty( $meta['_wp_attachment_image_alt'][0] ) ) {
				$caption = $meta['_wp_attachment_image_alt'][0];
			} else if ( ! empty( $meta['_wp_attachment_metadata'][0] ) ) {
				$meta = trx_socials_unserialize( $meta['_wp_attachment_metadata'][0] );
				if ( ! empty( $meta['image_meta']['caption'] ) ) {
					$caption = $meta['image_meta']['caption'];
				}
			}
		}
		return $caption;
	}
}


// Add 'autoplay' feature in the video
if (!function_exists('trx_socials_make_video_autoplay')) {
	function trx_socials_make_video_autoplay($video) {
		if ( strpos($video, '<video') !== false ) {
			if ( strpos( $video, 'autoplay' ) === false ) {
				$video = str_replace(
									'<video',
									'<video autoplay="autoplay" onloadeddata="' . ( $muted ? 'this.muted=true;' : '' ) . 'this.play();"'
										. ( $muted
												? ' muted="muted" loop="loop" playsinline="playsinline"'
												: ' controls="controls"'
											),
									$video
									);
				if ( $muted ) {
					$video = str_replace( 'controls="controls"', '', $video );
				}
			}
		} else if ( strpos($video, '<iframe') !== false ) {
			$video = preg_replace_callback(
				'/(<iframe.+src=[\'"])([^\'"]+)([\'"][^>]*>)/Uix',
				function($matches) {
					if ( ! empty( $matches[2] ) && strpos( $matches[2], 'autoplay=1' ) === false ) {
						$matches[2] .= ( strpos($matches[2], '?') !== false ? '&' : '?' ) . 'autoplay=1';
					}
					return ( strpos( $matches[1], 'autoplay"' ) === false && strpos( $matches[1], 'autoplay;' ) === false
							&& strpos( $matches[3], 'autoplay"' ) === false && strpos( $matches[3], 'autoplay;' ) === false
								? ( strpos( $matches[1], ' allow="' ) !== false
									? str_replace( ' allow="', ' allow="autoplay;', $matches[1] )
									: str_replace( '<iframe ', '<iframe allow="autoplay" ', $matches[1] )
									)
								: $matches[1]
							)
							. $matches[2] . $matches[3];
				},
				$video);
			if ( $muted ) {
				$video = str_replace(
							'autoplay=1',
							'autoplay=1'
								. '&muted=1'
								. '&background=1'
								. '&autohide=1'
								. '&playsinline=1'
								. '&loop=1'
								. '&enablejsapi=1'
								. '&feature=oembed'
								. '&controls=0'
								. '&showinfo=0'
								. '&modestbranding=1'
								. '&wmode=transparent'
								. '&origin=' . urlencode( esc_url( home_url() ) )
								. '&widgetid=1',
							$video
						);
			}
		}
		return $video;
	}
}
