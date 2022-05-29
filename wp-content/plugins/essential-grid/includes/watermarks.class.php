<?php
/**
 * @package   Essential_Grid
 * @author    ThemePunch <info@themepunch.com>
 * @link      http://www.themepunch.com/essential/
 * @copyright 2021 ThemePunch
 */

if( !defined( 'ABSPATH') ) exit();

class Essential_Grid_Watermarks {

	/**
	 * watermarks option name
	 */
	const ESG_WATERMARKS_OPTION = 'tp_eg_watermarks';
	/**
	 * @var string 
	 */
	private $upload_path = '/essential-grid/watermarks/';

	/**
	 * @var array  default option values
	 */
	private $_defaults = array(
		'watermarks-enabled' => 'false',
		'watermarks-library' => 'GD',
		'watermarks-quality' => '92',
		'watermarks-type' => 'image',
		'watermarks-image' => '',
		'watermarks-text' => 'Watermarked',
		'watermarks-text-repeat' => 'true',
		'watermarks-font' => 'Arial.ttf',
		'watermarks-color' => '#FFFFFF',
		'watermarks-size' => '24',
		'watermarks-angle' => '30',
		'watermarks-opacity' => '50',
		'watermarks-position-top' => 5,
		'watermarks-position-left' => 5,
		'watermarks-position-width' => 35,
		'watermarks-position-height' => 35,
	);

	/**
	 * @var array  fonts list
	 */
	private $_fonts = array(
		'Arial.ttf' => 'Arial',
		'Arial_Black.ttf' => 'Arial Black',
		'Comic_Sans_MS.ttf' => 'Comic Sans MS',
		'Courier_New.ttf' => 'Courier New',
		'Georgia.ttf' => 'Georgia',
		'Impact.ttf' => 'Impact',
		'Tahoma.ttf' => 'Tahoma',
		'Times_New_Roman.ttf' => 'Times New Roman',
		'Trebuchet_MS.ttf' => 'Trebuchet MS',
		'Verdana.ttf' => 'Verdana',
	);

	protected static $instance = null;

	/**
	 * @return Essential_Grid_Watermarks
	 */
	public static function get_instance()
	{
		if ( is_null( static::$instance ) ) {
			static::$instance = new Essential_Grid_Watermarks();
		}

		return static::$instance;
	}
	
	protected function __construct()
	{
		require_once ABSPATH . WPINC . '/class-wp-image-editor.php';
		require_once ABSPATH . WPINC . '/class-wp-image-editor-gd.php';
		require_once ABSPATH . WPINC . '/class-wp-image-editor-imagick.php';

		require_once ESG_PLUGIN_PATH . '/includes/watermarks.gd.class.php';
		require_once ESG_PLUGIN_PATH . '/includes/watermarks.imagick.class.php';
		
		$this->addActions();
		$this->addFilters();
	}

	/**
	 * add actions
	 */
	protected function addActions()
	{
		//js
		add_action('essgrid_enqueue_admin_scripts', array($this, 'enqueueAdminScripts'));
	}

	/**
	 * add filters
	 */
	protected function addFilters()
	{
		//global settings
		add_filter('essgrid_global_settings_menu', array($this, 'addGlobalSettingsMenu'), 10, 1);
		add_filter('essgrid_global_settings_content', array($this, 'addGlobalSettingsContent'), 10, 1);

		//grid edit page
		add_filter('essgrid_grid_create_menu', array($this, 'addGridCreateMenu'), 10, 1);
		add_filter('essgrid_grid_create_settings', array($this, 'addGridCreateSettings'), 10, 2);
		
		//set item skin media source before render
		//check the sources and apply watermark if needed
		add_filter('essgrid_item_skin_set_media_sources', array($this, 'applyWatermark'), 10, 3);
		
	}
	
	public function enqueueAdminScripts()
	{
		global $esg_dev_mode;
		
		if ($esg_dev_mode) {
			// DEV VERSION
			wp_enqueue_script('esg-watermarks-script', plugins_url('assets/js/modules/dev/watermarks.js', ESG_PLUGIN_ADMIN_PATH . '/index.php'), array('jquery'), Essential_Grid::VERSION);
		}
	}

	/**
	 * get watermarks options defaults
	 * @return array
	 */
	public function getDefaults()
	{
		return $this->_defaults;
	}
	
	/**
	 * get watermarks options
	 * @return array
	 */
	public function getOptions()
	{
		$options = get_option(self::ESG_WATERMARKS_OPTION, array());
		return array_merge($this->_defaults, $options);
	}

	/**
	 * save options
	 * @param array $options
	 * @return bool
	 */
	public function saveOptions($options)
	{
		return update_option(self::ESG_WATERMARKS_OPTION, $options);
	}

	/**
	 * check requirements
	 * @return array
	 */
	public function checkRequirements()
	{
		$path = $this->getUploadPath();

		return array(
			'upload' => $path && wp_mkdir_p($path),
			'GD' => Essential_Grid_Watermarks_GD::test(),
			'GD_freetype' => Essential_Grid_Watermarks_GD::isFreetype(),
			'Imagick' => Essential_Grid_Watermarks_Imagick::test(),
		);
	}
	
	/**
	 * check if text watermark is available
	 * @return array
	 */
	public function isTextAvailable()
	{
		$options = $this->getOptions();
		if ('GD' !== $options['watermarks-library']) return true;

		$requirements = $this->checkRequirements();
		return $requirements['GD'] && $requirements['GD_freetype'];
	}

	/**
	 * get upload path
	 * @return bool|string
	 */
	public function getUploadPath()
	{
		$upload_dir = wp_upload_dir();
		if ($upload_dir['error'] != false) return false;

		return $upload_dir['basedir'] . $this->upload_path;
	}
	
	/**
	 * get upload url
	 * @return bool|string
	 */
	public function getUploadUrl()
	{
		$upload_dir = wp_upload_dir();
		if ($upload_dir['error'] != false) return false;

		return $upload_dir['baseurl'] . $this->upload_path;
	}

	/**
	 * get fonts
	 * @return array
	 */
	public function getFonts()
	{
		return $this->_fonts;
	}
	
	/**
	 * get fonts path
	 * @return string
	 */
	public function getFontsPath()
	{
		return ESG_PLUGIN_ADMIN_PATH . '/assets/font';
	}
	
	/**
	 * get image editor object
	 * @param string $file
	 * @return Essential_Grid_Watermarks_GD | Essential_Grid_Watermarks_Imagick | bool
	 */
	public function getImageEditor($file)
	{
		$options = $this->getOptions();
		$imageEditorClass = 'Essential_Grid_Watermarks_' . $options['watermarks-library'];
		if (class_exists($imageEditorClass) && $imageEditorClass::test())
			return new $imageEditorClass($file);
		
		return false;
	}

	/**
	 * @param mixed $value
	 * @return string
	 */
	protected function esgRequirementsCheckmark($value)
	{
		if ($value) {
			return '<span class="material-icons material-icons-outlined esg-color-green">check_circle</span>';
		} else {
			return '<span class="material-icons material-icons-outlined esg-color-red">highlight_off</span>';
		}
	}

	/**
	 * is watermarks enabled
	 * check enabled option and selected image library
	 * @return bool
	 */
	public function isEnabled()
	{
		$options = $this->getOptions();
		if ($options['watermarks-enabled'] === 'false') return false;

		$requirements = $this->checkRequirements();
		if (!$requirements['upload'] || !$requirements[$options['watermarks-library']]) return false;

		return true;
	}

	/**
	 * add grid create menu item
	 * @param $str
	 * @return string
	 */
	public function addGridCreateMenu($str)
	{
		if (!$this->isEnabled()) return $str;
		
		return $str 
			. '<li data-toshow="esg-watermarks-settings" class="esg-watermarks-settings">'
			. '<i class="eg-icon-tint"></i><p>' . esc_html('Watermarks', ESG_TEXTDOMAIN) . '</p>'
			. '</li>';
	}
	
	/**
	 * add grid create settings section
	 * @param string $str
	 * @param mixed $grid
	 * @return string
	 */
	public function addGridCreateSettings($str, $grid)
	{
		if (!$this->isEnabled()) return $str;

		//get global options and turn off watermarks by default
		$options = $this->getOptions();
		$options['watermarks-enabled'] = 'false';
		$options['watermarks-override-defaults'] = 'false';
		
		//load options from grid
		if (isset($grid['params']['watermarks-enabled'])) $options = array_merge($options, $grid['params']);
		
		$content =
			'<div id="esg-watermarks-settings" class="esg-settings-container">'
				
				. $this->getStatusSectionHtml($options['watermarks-enabled'])
				
				. '<div>'
					. '<div class="eg-cs-tbc-left"><esg-llabel><span>' . esc_html('Use Defaults', ESG_TEXTDOMAIN) .'</span></esg-llabel></div>'
					. '<div class="eg-cs-tbc">'
						. '<label for="watermarks-override-defaults">' . esc_html('Override Default Settings', ESG_TEXTDOMAIN) . '</label><!--'
						. '--><span class="esg-display-inline-block"><input type="radio" name="watermarks-override-defaults" value="true" ' . checked($options['watermarks-override-defaults'], 'true', false) . ' />' . esc_html('On', ESG_TEXTDOMAIN) . '</span><div class="space18"></div><!--'
						. '--><span class="esg-display-inline-block"><input type="radio" name="watermarks-override-defaults" value="false" ' . checked($options['watermarks-override-defaults'], 'false', false) . ' />' . esc_html('Off', ESG_TEXTDOMAIN) . '</span>'
					. '</div>'
				. '</div>'
			
				. $this->getWatermarkSectionHtml($options, 'Watermark Settings')
				
		. '</div>';

		return $str . $content;
	}
	
	/**
	 * add global settings menu item
	 * @param $str
	 * @return string
	 */
	public function addGlobalSettingsMenu($str)
	{
		return $str 
			. '<li data-toshow="esg-watermarks-settings" class="esg-watermarks-settings">'
			. '<i class="material-icons">water_drop</i><p>' . esc_html('Watermarks', ESG_TEXTDOMAIN) . '</p>'
			. '</li>';
	}
	
	/**
	 * add global settings content section
	 * @param $str
	 * @return string
	 */
	public function addGlobalSettingsContent($str)
	{
		$options = $this->getOptions();
		$requirements = $this->checkRequirements();
		
		$options_library = '';
		if ($requirements['Imagick']) {
			$options_library .= '<option ' . ($options['watermarks-library'] == 'Imagick' ? 'selected="selected" ' : '') . ' value="Imagick">' . esc_html('ImageMagick', ESG_TEXTDOMAIN) . '</option>';
		}
		if ($requirements['GD']) {
			$options_library .= '<option ' . ($options['watermarks-library'] == 'GD' ? 'selected="selected" ' : '') . ' value="GD">' . esc_html('GD', ESG_TEXTDOMAIN) . '</option>';
		}
		
		$content = 
			'<div id="esg-watermarks-settings" class="esg-settings-container">'

				. '<div>'
					. '<div class="eg-cs-tbc-left"><esg-llabel><span>' . esc_html('Requirements', ESG_TEXTDOMAIN) . '</span></esg-llabel></div>'
					. '<div class="eg-cs-tbc">'
						. '<label>' . esc_html('File System', ESG_TEXTDOMAIN) . '</label>'
						. '<ul class="esg-display-inline-block esg-margin-0">'
							. '<li>' . $this->esgRequirementsCheckmark($requirements['upload']) . ' Upload dir writable</li>'
						. '</ul>'
						. '<div class="div13"></div>'
						. '<label>' . esc_html('Image Libraries', ESG_TEXTDOMAIN) . '</label>'
						. '<ul class="esg-display-inline-block esg-margin-0">'
							. '<li>' . $this->esgRequirementsCheckmark($requirements['GD']) . ' GD</li>'
							. '<li>' . $this->esgRequirementsCheckmark($requirements['GD_freetype']) . ' GD FreeType ( Required for text watermarks )</li>'
							. '<li>' . $this->esgRequirementsCheckmark($requirements['Imagick']) . ' ImageMagick</li>'
						. '</ul>'
					. '</div>'
				. '</div>'

				. $this->getStatusSectionHtml($options['watermarks-enabled'])
				
				. '<div>'
					. '<div class="eg-cs-tbc-left"><esg-llabel><span>' . esc_html('Settings', ESG_TEXTDOMAIN) . '</span></esg-llabel></div>'
					. '<div class="eg-cs-tbc">'
						. '<label for="watermarks-library">' . esc_html('Image Library', ESG_TEXTDOMAIN) . '</label><!--'
						. '--><select name="watermarks-library">'
							. '<option value="">' . esc_html('- Select -', ESG_TEXTDOMAIN) . '</option>'
							. $options_library
						. '</select>'
						. '<div class="div5"></div>'
						. '<label></label><span class="esgs-info">' . esc_html('Select PHP library to process images', ESG_TEXTDOMAIN) . '</span>'
						. '<div class="div13"></div>'
						
						. '<label for="watermarks-quality">' . esc_html('JPEG quality', ESG_TEXTDOMAIN) . '</label><!--'
						. '--><input class="esg-w-100" type="number" name="watermarks-quality" value="' . esc_attr($options['watermarks-quality']) . '" min="1" max="100" step="1">'
						. '<div class="div5"></div>'
						. '<label></label><span class="esgs-info">' . esc_html('Set JPEG quality. 1 = worst quality / smaller size, 100 = best quality / bigger size', ESG_TEXTDOMAIN) . '</span>'
					. '</div>'
				. '</div>'
				
				. $this->getWatermarkSectionHtml($options)
				
			. '</div>';
		
		return $str . $content;
	}

	/**
	 * get status section html
	 * @param string $enabled
	 * @return string
	 */
	protected function getStatusSectionHtml($enabled)
	{
		return
			'<div>'
				. '<div class="eg-cs-tbc-left"><esg-llabel><span>' . esc_html('Status', ESG_TEXTDOMAIN) .'</span></esg-llabel></div>'
				. '<div class="eg-cs-tbc">'
					. '<label for="watermarks-enabled">' . esc_html('Enable Watermarks', ESG_TEXTDOMAIN) . '</label><!--'
					. '--><span class="esg-display-inline-block"><input type="radio" name="watermarks-enabled" value="true" ' . checked($enabled, 'true', false) . ' />' . esc_html('On', ESG_TEXTDOMAIN) . '</span><div class="space18"></div><!--'
					. '--><span class="esg-display-inline-block"><input type="radio" name="watermarks-enabled" value="false" ' . checked($enabled, 'false', false) . ' />' . esc_html('Off', ESG_TEXTDOMAIN) . '</span>'
				. '</div>'
			. '</div>';
	}

	/**
	 * get watermark settings section html
	 * @param array $options
	 * @param string $section_title
	 * @return string
	 */
	protected function getWatermarkSectionHtml($options, $section_title = 'Default Watermark')
	{
		$globalOptions = $this->getOptions();
		
		//disable text option
		if (!$this->isTextAvailable()) {
			$options['watermarks-type'] = 'image';
		}
		
		$fonts = $this->getFonts();
		$options_fonts = '';
		foreach ($fonts as $key => $val) {
			$options_fonts .= '<option ' . ($options['watermarks-font'] == $key ?  'selected="selected" ' : '') . ' value="' . $key . '">' . $val . '</option>';
		}

		$handle_style = 'width:' . ($options['watermarks-position-width'] * 2) . 'px;'
			. 'height:' . ($options['watermarks-position-height'] * 2) . 'px;'
			. 'top:' . ($options['watermarks-position-top'] * 2) . 'px;'
			. 'left:' . ($options['watermarks-position-left'] * 2) . 'px;';

		$image_src = wp_get_attachment_image_src($options['watermarks-image'], 'large');
		$image_src = !empty($image_src) ? $image_src[0] : '';
		
		return
			'<div class="esg-watermarks-container">'
				. '<div class="eg-cs-tbc-left"><esg-llabel><span>' . esc_html($section_title, ESG_TEXTDOMAIN) . '</span></esg-llabel></div>'
				. '<span class="eg-cs-tbc">'

					. '<label for="watermarks-type">' . esc_html('Watermark Type', ESG_TEXTDOMAIN) . '</label><!--'
					. '--><span class="esg-watermarks-type esg-display-inline-block"><input type="radio" name="watermarks-type" value="image" ' . checked($options['watermarks-type'], 'image', false) . ' />' . esc_html('Image', ESG_TEXTDOMAIN) . '</span><div class="space18"></div><!--'
					. '--><span class="esg-watermarks-type esg-display-inline-block"><input type="radio" name="watermarks-type" value="text" ' . checked($options['watermarks-type'], 'text', false) . ' data-library="' . esc_attr($globalOptions['watermarks-library']) . '" data-freetype="' . ($this->isTextAvailable() ? 'true' : 'false') . '" />' . esc_html('Text', ESG_TEXTDOMAIN) . '</span>'
					. '<div class="div13"></div>'
		
					. '<div class="esg-watermarks-options-container esg-watermarks-type-image esg-display-none">'
						. '<label for="watermarks-image">' . esc_html('Watermark Image', ESG_TEXTDOMAIN) . '</label><!--'
						. '--><button class="esg-btn esg-purple esg-watermarks-image-add" data-setto="watermarks_image">' . esc_html('Choose Image', ESG_TEXTDOMAIN) . '</button><div class="space18"></div>'
						. '<button class="esg-btn esg-red esg-watermarks-image-clear" data-setto="watermarks_image">' . esc_html('Remove Image', ESG_TEXTDOMAIN) . '</button>'
						. '<div class="div5"></div>'
						. '<label></label><span class="esgs-info">' . esc_html('Set watermark image', ESG_TEXTDOMAIN) . '</span>'
						. '<div class="div5"></div>'
						. '<img id="watermarks_image-img" class="watermarks-image-holder-wrap-div eg-photoshop-bg esg-display-' . ($options['watermarks-image'] ? 'block' : 'none') . '" src="' . $image_src . '">'
						. '<input type="hidden" id="watermarks_image" name="watermarks-image" value="' . esc_attr($options['watermarks-image']) . '">'
						. '<div class="div13"></div>'

						. '<label>' . esc_html('Opacity', ESG_TEXTDOMAIN) . '</label><!--'
						. '--><span id="watermarks-slider-opacity" class="slider-settings esg-watermarks-slider" data-input="opacity" data-min="0" data-max="100" data-step="1"></span><div class="space18"></div><!--'
						. '--><input class="input-settings-small" type="text" name="watermarks-opacity" value="' . esc_attr($options['watermarks-opacity']) . '" /> %'
						. '<div class="div5"></div>'
						. '<label></label><span class="esgs-info">' . esc_html('Opacity is not applied to png images.', ESG_TEXTDOMAIN) . '</span>'
						. '<div class="div13"></div>'

						. '<label for="watermarks-position">' . esc_html('Position', ESG_TEXTDOMAIN) . '</label>'
						. '<div class="esg-watermarks-position-container">'
						. '<div class="esg-watermarks-position-handle" style="' . $handle_style . '"></div>'
						. '</div>'
						. '<span class="space18"></span>'
						. '<div class="esg-watermarks-position-description">'
						. 'Width: <span class="esg-watermarks-position-width">' . esc_html($options['watermarks-position-width']) . '</span>%'
						. '<div class="div5"></div>'
						. 'Height: <span class="esg-watermarks-position-height">' . esc_html($options['watermarks-position-height']) . '</span>%'
						. '<div class="div5"></div>'
						. 'Top: <span class="esg-watermarks-position-top">' . esc_html($options['watermarks-position-top']) . '</span>%'
						. '<div class="div5"></div>'
						. 'Left: <span class="esg-watermarks-position-left">' . esc_html($options['watermarks-position-left']) . '</span>%'
						. '<div class="div5"></div>'
						. '</div>'
						. '<input type="hidden" name="watermarks-position-width" value="' . esc_attr($options['watermarks-position-width']) . '">'
						. '<input type="hidden" name="watermarks-position-height" value="' . esc_attr($options['watermarks-position-height']) . '">'
						. '<input type="hidden" name="watermarks-position-top" value="' . esc_attr($options['watermarks-position-top']) . '">'
						. '<input type="hidden" name="watermarks-position-left" value="' . esc_attr($options['watermarks-position-left']) . '">'
						. '<div class="div13"></div>'
			
					. '</div>'
		
					. '<div class="esg-watermarks-options-container esg-watermarks-type-text esg-display-none">'

						. '<label for="watermarks-text">' . esc_html('Watermark Text', ESG_TEXTDOMAIN) . '</label><!--'
						. '--><input type="text" name="watermarks-text" value="' . esc_attr($options['watermarks-text']) . '">'
						. '<div class="div13"></div>'

						. '<label for="watermarks-text-repeat">' . esc_html('Repeat Text', ESG_TEXTDOMAIN) . '</label><!--'
						. '--><span class="esg-display-inline-block"><input type="radio" name="watermarks-text-repeat" value="true" ' . checked($options['watermarks-text-repeat'], 'true', false) . ' />' . esc_html('On', ESG_TEXTDOMAIN) . '</span><div class="space18"></div><!--'
						. '--><span class="esg-display-inline-block"><input type="radio" name="watermarks-text-repeat" value="false" ' . checked($options['watermarks-text-repeat'], 'false', false) . ' />' . esc_html('Off', ESG_TEXTDOMAIN) . '</span>'
						. '<div class="div13"></div>'
		
						. '<label for="watermarks-font">' . esc_html('Font', ESG_TEXTDOMAIN) . '</label><!--'
						. '--><select name="watermarks-font">'
						. '<option value="">' . esc_html('- Select -', ESG_TEXTDOMAIN) . '</option>'
						. $options_fonts
						. '</select>'
						. '<div class="div13"></div>'
		
						. '<label>' . esc_html('Color', ESG_TEXTDOMAIN) . '</label><!--'
						. '--><input type="text" name="watermarks-color" data-mode="basic" value="' . esc_attr($options['watermarks-color']) . '">'
						. '<div class="div13"></div>'
					
						. '<label>' . esc_html('Font size', ESG_TEXTDOMAIN) . '</label><!--'
						. '--><span id="watermarks-slider-size" class="slider-settings esg-watermarks-slider" data-input="size" data-min="8" data-max="128" data-step="2"></span><div class="space18"></div><!--'
						. '--><input class="input-settings-small" type="text" name="watermarks-size" value="' . esc_attr($options['watermarks-size']) . '" /> pt'
						. '<div class="div5"></div>'
						. '<label></label><span class="esgs-info">' . esc_html('Text might be not rendered with too big font size. Try to lower the size in this case.', ESG_TEXTDOMAIN) . '</span>'
						. '<div class="div13"></div>'
					
						. '<label>' . esc_html('Rotation Angle', ESG_TEXTDOMAIN). '</label><!--'
						. '--><span id="watermarks-slider-angle" class="slider-settings esg-watermarks-slider" data-input="angle" data-min="0" data-max="360" data-step="1"></span><div class="space18"></div><!--'
						. '--><input class="input-settings-small" type="text" name="watermarks-angle" value="' . esc_attr($options['watermarks-angle']) . '" /> &angle;'
						. '<div class="div13"></div>'

					. '</div>'

				. '</div>'
			. '</div>';
	}

	/**
	 * apply watermark to media sources if needed
	 * @param array $sources
	 * @param mixed $grid_id
	 * @param array $grid_params
	 * @return mixed
	 */
	public function applyWatermark($sources, $grid_id, $grid_params)
	{
		//no grid id
		if (empty($grid_id)) return $sources;
		//cant be converted to string
		if (!(method_exists($grid_id, '__toString') || (is_scalar($grid_id) && !is_null($grid_id)))) return $sources;
		
		$is_enabled = Essential_Grid_Base::getVar($grid_params, 'watermarks-enabled', 'false');
		if ($is_enabled !== 'true') return $sources;

		foreach ($sources as $key => $val) {
			if (empty($val) || !is_string($val)) continue;
			
			$editor = $this->getImageEditor($val);
			$loaded = $editor->load();
			if (is_wp_error($loaded)) continue;
			
			$watermarked = $editor->apply_watermark($grid_id, $grid_params);
			//process error?
			if (empty($watermarked) || is_wp_error($watermarked)) continue;
			
			$sources[$key] = $watermarked;
		}
		
		return $sources;
	}

	/**
	 * @param mixed $grid_id
	 * @param array $params
	 * @param string $file
	 * @return bool|array
	 */
	public function getFileData($grid_id, $params, $file)
	{
		$file_new = $grid_id . '/' . md5(serialize($params)) . '/' . basename($file);

		return [
			'path' => $this->getUploadPath() . $file_new,
			'url' => $this->getUploadUrl() . $file_new,
		];
	}
	
}
