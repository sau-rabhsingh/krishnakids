<?php
/**
 * @package   Essential_Grid
 * @author    ThemePunch <info@themepunch.com>
 * @link      http://www.themepunch.com/essential/
 * @copyright 2021 ThemePunch
 */

if( !defined( 'ABSPATH') ) exit();

class Essential_Grid_Watermarks_Imagick extends WP_Image_Editor_Imagick {

	/**
	 * @var Essential_Grid_Watermarks
	 */
	protected $watermarks;
	/**
	 * @var Essential_Grid_Base
	 */
	protected $base;

	public function __construct($file)
	{
		parent::__construct($file);
		$this->watermarks = Essential_Grid_Watermarks::get_instance();
		$this->base = new Essential_Grid_Base();
	}
	
	/**
	 * @param array $args
	 * @return bool
	 */
	public static function test( $args = [] ) {
		return parent::test( $args );
	}

	/**
	 * @param string $mime_type
	 * @return bool
	 */
	public static function supports_mime_type( $mime_type ) {
		/**
		 * Todo: Check here if we can deal with the webp ?
		 */
		return parent::supports_mime_type( $mime_type );
	}

	public function getImage()
	{
		return $this->image;
	}

	/**
	 * @param mixed $grid_id
	 * @param array $params
	 * @return mixed
	 */
	public function apply_watermark($grid_id, $params)
	{
		$is_override_defaults = $this->base->getVar($params, 'watermarks-override-defaults', 'false');
		if ($is_override_defaults === 'false') {
			$params = $this->watermarks->getOptions();
		}

		$watermark_file = $this->watermarks->getFileData($grid_id, $params, $this->file);
		if (is_file($watermark_file['path'])) return $watermark_file['url'];

		$type = Essential_Grid_Base::getVar($params, 'watermarks-type', 'image');
		switch ($type) {
			case 'image':
				return $this->_apply_image_watermark($grid_id, $params);
			case 'text':
				return $this->_apply_text_watermark($grid_id, $params);
		}

		return new WP_Error('esg_watermark_imagick_type_error', __('Wrong watermark type.', ESG_TEXTDOMAIN));
	}

	/**
	 * @param mixed $grid_id
	 * @param array $params
	 * @return mixed
	 */
	protected function _apply_image_watermark($grid_id, $params)
	{
		$watermark_file = get_attached_file( $this->base->getVar($params, 'watermarks-image', '') );
		if (!$watermark_file || !is_file($watermark_file)) {
			return new WP_Error('esg_watermark_gd_file_missing', __('Error loading watermark image.', ESG_TEXTDOMAIN));
		}

		$watermark_ext = strtolower(pathinfo($watermark_file, PATHINFO_EXTENSION));
		$watermark_imagick = new Essential_Grid_Watermarks_Imagick($watermark_file);
		$loaded = $watermark_imagick->load();
		if (is_wp_error($loaded)) return $loaded;

		$defaults = $this->watermarks->getDefaults();
		$image_size = $this->get_size();
		$watermark_size = $watermark_imagick->get_size();

		$position = array(
			'width' => $this->base->getVar($params, 'watermarks-position-width', $defaults['watermarks-position-width']),
			'height' => $this->base->getVar($params, 'watermarks-position-height', $defaults['watermarks-position-height']),
			'top' => $this->base->getVar($params, 'watermarks-position-top', $defaults['watermarks-position-top']),
			'left' => $this->base->getVar($params, 'watermarks-position-left', $defaults['watermarks-position-left']),
		);

		//watermark dimensions and offset
		$new_width = $image_size['width'] / 100 * $position['width'];
		$new_height = $image_size['height'] / 100 * $position['height'];
		$new_top = $image_size['height'] / 100 * $position['top'];
		$new_left = $image_size['width'] / 100 * $position['left'];

		//resize watermark
		$watermark_imagick->resize($new_width, $new_height);
		$watermark_size = $watermark_imagick->get_size();

		//add offset if new watermark dimensions not equal to calculated
		if ($new_width != $watermark_size['width']) {
			$new_left += ($new_width - $watermark_size['width']) / 2;
		}
		if ($new_height != $watermark_size['height']) {
			$new_top += ($new_height - $watermark_size['height']) / 2;
		}

		if ($watermark_imagick->getImage()->getImageColorspace() != $this->image->getImageColorspace()) {
			$watermark_imagick->getImage()->transformimagecolorspace($this->image->getImageColorspace());
		}
		
		try {
			$img = $watermark_imagick->getImage();
			if ('png' !== $watermark_ext) {
				$img->setImageFormat('png32');
				$img->setImageAlpha($this->base->getVar($params, 'watermarks-opacity', $defaults['watermarks-opacity']) / 100);
			}
			$this->image->compositeImage($img, Imagick::COMPOSITE_OVER, $new_left, $new_top);

			$img->clear();
			$img->destroy();
			$watermark_imagick->getImage()->clear();
			$watermark_imagick->getImage()->destroy();
		} catch ( Exception $e ) {
			return new WP_Error('esg_watermark_imagick_apply', __('Error applying watermark image.', ESG_TEXTDOMAIN));
		}
		
		$watermark_file = $this->watermarks->getFileData($grid_id, $params, $this->file);
		$watermark_options = $this->watermarks->getOptions();
		$this->set_quality($watermark_options['watermarks-quality']);
		$result = $this->save($watermark_file['path']);
		if (is_wp_error($result)) return $result;

		return $watermark_file['url'];
	}

	/**
	 * @param mixed $grid_id
	 * @param array $params
	 * @return mixed
	 */
	protected function _apply_text_watermark($grid_id, $params)
	{
		$defaults = $this->watermarks->getDefaults();

		$text = $this->base->getVar($params, 'watermarks-text', $defaults['watermarks-text']);
		$text_repeat = $this->base->getVar($params, 'watermarks-text-repeat', $defaults['watermarks-text-repeat']);
		$color = $this->base->getVar($params, 'watermarks-color', $defaults['watermarks-color']);
		$size = $this->base->getVar($params, 'watermarks-size', $defaults['watermarks-size']);
		$angle = - $this->base->getVar($params, 'watermarks-angle', $defaults['watermarks-angle']);
		$font = $this->base->getVar($params, 'watermarks-font', $defaults['watermarks-font']);
		$font_path = $this->watermarks->getFontsPath() . '/' . $font;

		if (empty($text)) {
			return new WP_Error('esg_watermark_gd_text_error', __('Empty watermark text.', ESG_TEXTDOMAIN));
		}

		$processColor = ESGColorpicker::process($color, false);
		switch ($processColor[1]) {
			case 'hex' :
				$processColor = ESGColorpicker::processRgba($processColor[0], 1);
				$processColor = ESGColorpicker::rgbValues($processColor, 4);
				break;
			case 'rgb' :
				$processColor = ESGColorpicker::rgbValues($processColor[0], 4);
				break;
			case 'rgba' :
				$processColor = ESGColorpicker::rgbValues($processColor[0], 4);
				break;
			default:
				return new WP_Error('esg_watermark_gd_color_error', __('Error process watermark text color.', ESG_TEXTDOMAIN));
		}
		$alpha = floatval($processColor[3]);
		$imageColor = new ImagickPixel('rgba('.$processColor[0].','.$processColor[1].','.$processColor[2].', '.$processColor[3].')');
		
		$textImage = new ImagickDraw();
		$textImage->setFont($font_path);
		$textImage->setFontSize($size);
		$textImage->setFillColor($imageColor);
		$textImage->rotate($angle);

		$imageSize = $this->get_size();
		
		$watermarkImage = new Imagick();
		$watermarkImage->newImage($imageSize['width'], $imageSize['height'], 'none');
		
		$tSize = $this->image->queryFontMetrics($textImage, $text);
		$angleSin = sin(deg2rad($angle));
		$angleCos = cos(deg2rad($angle));
		$tHeight = abs($tSize['textWidth'] * $angleSin) + abs($tSize['textHeight'] * $angleCos);
		$tWidth = abs($tSize['textWidth'] * $angleCos) + abs($tSize['textHeight'] * $angleSin);
		
		if('true' === $text_repeat) {
			$difX = $tWidth/2;
			$difY = $tHeight/2;
			$margin = 30;
			
			$x = -$difX;
			$y = -$difY;
			while($x < $imageSize['width'] + $difX) {
				while($y < $imageSize['height'] + $difY) {
					$watermarkImage->annotateImage($textImage, $x, $y, $angle, $text);
					$y += $tHeight + $margin;
				}
				$x += $tWidth + $margin;
				$y = -$difY;
			}
		} else {
			$angle = $angle % 360;
			if ($angle < 0 ) $angle += 360;
			$x = $imageSize['width'] / 2 - $tWidth / 2;
			$y = $imageSize['height'] / 2 + ($angle > 180 ? $tHeight : -$tHeight) / 2;
			$watermarkImage->annotateImage($textImage, $x, $y, $angle, $text);
		}
		
		if ($watermarkImage->getImageColorspace() != $this->image->getImageColorspace()) {
			$watermarkImage->transformimagecolorspace($this->image->getImageColorspace());
		}

		try {
			$watermarkImage->setImageFormat('png32');
			$this->image->compositeImage($watermarkImage, Imagick::COMPOSITE_OVER, 0, 0);
			
			$watermarkImage->destroy();
			$imageColor->destroy();
			$textImage->destroy();
		} catch ( Exception $e ) {
			return new WP_Error('esg_watermark_imagick_apply', __('Error applying watermark image.', ESG_TEXTDOMAIN));
		}
		
		$watermark_file = $this->watermarks->getFileData($grid_id, $params, $this->file);
		$watermark_options = $this->watermarks->getOptions();
		$this->set_quality($watermark_options['watermarks-quality']);
		$result = $this->save($watermark_file['path']);
		if (is_wp_error($result)) return $result;

		return $watermark_file['url'];
	}
}
