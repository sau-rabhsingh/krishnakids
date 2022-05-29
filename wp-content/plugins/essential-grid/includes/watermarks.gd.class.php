<?php
/**
 * @package   Essential_Grid
 * @author    ThemePunch <info@themepunch.com>
 * @link      http://www.themepunch.com/essential/
 * @copyright 2021 ThemePunch
 */

if( !defined( 'ABSPATH') ) exit();

class Essential_Grid_Watermarks_GD extends WP_Image_Editor_GD {

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
	 * is free type enabled
	 * @return bool
	 */
	public static function isFreetype() {
		if (!self::test()) return false;
		$gdinfo = gd_info();
		return $gdinfo['FreeType Support'];
	}
	
	/**
	 * Checks to see if editor supports the mime-type specified.
	 * @param string $mime_type
	 * @return bool
	 */
	public static function supports_mime_type( $mime_type ) {
		$image_types = imagetypes();
		switch ( $mime_type ) {
			case 'image/jpeg':
				return ( $image_types & IMG_JPG ) != 0;
			case 'image/png':
				return ( $image_types & IMG_PNG ) != 0;
			case 'image/gif':
				return ( $image_types & IMG_GIF ) != 0;
		}

		return false;
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

		//make the destination image respect transparancy
		imagealphablending($this->image, true);
		
		$type = Essential_Grid_Base::getVar($params, 'watermarks-type', 'image');
		switch ($type) {
			case 'image':
				return $this->_apply_image_watermark($grid_id, $params);
			case 'text':
				if (!self::isFreetype()) return new WP_Error('esg_watermark_gd_freetype_error', __('GD Free Type is not enabled.', ESG_TEXTDOMAIN));
				return $this->_apply_text_watermark($grid_id, $params);
		}
		
		return new WP_Error('esg_watermark_gd_type_error', __('Wrong watermark type.', ESG_TEXTDOMAIN));
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
		$watermark_gd = new Essential_Grid_Watermarks_GD($watermark_file);
		$loaded = $watermark_gd->load();
		if (is_wp_error($loaded)) return $loaded;

		$defaults = $this->watermarks->getDefaults();
		$image_size = $this->get_size();
		$watermark_size = $watermark_gd->get_size();
		
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
		$watermark_gd->resize($new_width, $new_height);
		$watermark_size = $watermark_gd->get_size();

		//add offset if new watermark dimensions not equal to calculated
		if ($new_width != $watermark_size['width']) {
			$new_left += ($new_width - $watermark_size['width']) / 2;
		}
		if ($new_height != $watermark_size['height']) {
			$new_top += ($new_height - $watermark_size['height']) / 2;
		}
		
		// params for image copy
		// png = imagecopy
		// others = imagecopymerge
		$image_copy_params = [
			$this->image,
			$watermark_gd->getImage(),
			$new_left,
			$new_top,
			0,
			0,
			$watermark_size['width'],
			$watermark_size['height'],
		];
		if ('png' === $watermark_ext) {
			$image_copy_func = 'imagecopy';
		} else {
			$image_copy_func = 'imagecopymerge';
			$image_copy_params[]  = $this->base->getVar($params, 'watermarks-opacity', $defaults['watermarks-opacity']);
		}
		$result = call_user_func_array($image_copy_func, $image_copy_params);
		if ($result !== true) return new WP_Error('esg_watermark_gd_error', __('Error applying watermark image.', ESG_TEXTDOMAIN));

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
		$angle = $this->base->getVar($params, 'watermarks-angle', $defaults['watermarks-angle']);
		$font = $this->base->getVar($params, 'watermarks-font', $defaults['watermarks-font']);
		$font_path = $this->watermarks->getFontsPath() . '/' . $font;
		
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
		$alpha = 127 - intval(floatval($processColor[3]) * 127);
		$colorIdentifier = imagecolorallocatealpha($this->image, $processColor[0], $processColor[1], $processColor[2], $alpha);
		
		//text box corners - bottom left, bottom right, top right, top left
		//[x,y,x,y,x,y,x,y]
		$tSize = imagettfbbox($size, $angle, $font_path, $text);
		$tWidth = $tSize[4] - $tSize[0];
		$tHeight = $tSize[5] - $tSize[1];
		$imageSize = $this->get_size();

		if('true' === $text_repeat) {
			$difX = abs($tWidth)/2;
			$difY = abs($tHeight)/2;
			$margin = 30;

			$x = -$difX;
			$y = -$difY;
			while($x < $imageSize['width'] + $difX) {
				while($y < $imageSize['height'] + $difY) {
					imagettftext($this->image, $size, $angle, $x, $y, $colorIdentifier, $font_path, $text);
					$y += abs($tHeight) + $margin;
				}
				$x += abs($tWidth) + $margin;
				$y = -$difY;
			}
		} else {
			$x = $imageSize['width'] / 2 - $tWidth / 2;
			$y = $imageSize['height'] / 2 - $tHeight / 2;
			imagettftext($this->image, $size, $angle, $x, $y, $colorIdentifier, $font_path, $text);
		}

		$watermark_file = $this->watermarks->getFileData($grid_id, $params, $this->file);
		$watermark_options = $this->watermarks->getOptions();
		$this->set_quality($watermark_options['watermarks-quality']);
		$result = $this->save($watermark_file['path']);
		if (is_wp_error($result)) return $result;

		return $watermark_file['url'];
	}
}
