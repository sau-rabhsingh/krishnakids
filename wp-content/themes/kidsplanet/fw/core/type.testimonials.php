<?php
/**
 * ThemeREX Framework: Testimonial post type settings
 *
 * @package	themerex
 * @since	themerex 1.0
 */

// Theme init
if (!function_exists('themerex_testimonial_theme_setup')) {
	add_action( 'themerex_action_before_init_theme', 'themerex_testimonial_theme_setup' );
	function themerex_testimonial_theme_setup() {
	
		// Add item in the admin menu
		add_filter('trx_utils_filter_override_options',			'themerex_testimonial_add_override_options');

		// Save data from override options
		add_action('save_post',				'themerex_testimonial_save_data');

		// Meta box fields
		global $THEMEREX_GLOBALS;
		$THEMEREX_GLOBALS['testimonial_override_options'] = array(
			'id' => 'testimonial-override-options',
			'title' => esc_html__('Testimonial Details', 'kidsplanet'),
			'page' => 'testimonial',
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				"testimonial_author" => array(
					"title" => esc_html__('Testimonial author',  'kidsplanet'),
					"desc" => esc_html__("Name of the testimonial's author", 'kidsplanet'),
					"class" => "testimonial_author",
					"std" => "",
					"type" => "text"),
				"testimonial_position" => array(
					"title" => esc_html__("Author's position",  'kidsplanet'),
					"desc" => esc_html__("Position of the testimonial's author", 'kidsplanet'),
					"class" => "testimonial_author",
					"std" => "",
					"type" => "text"),
				"testimonial_email" => array(
					"title" => esc_html__("Author's e-mail",  'kidsplanet'),
					"desc" => esc_html__("E-mail of the testimonial's author - need to take Gravatar (if registered)", 'kidsplanet'),
					"class" => "testimonial_email",
					"std" => "",
					"type" => "text"),
				"testimonial_link" => array(
					"title" => esc_html__('Testimonial link',  'kidsplanet'),
					"desc" => esc_html__("URL of the testimonial source or author profile page", 'kidsplanet'),
					"class" => "testimonial_link",
					"std" => "",
					"type" => "text")
			)
		);
		
		if (function_exists('themerex_require_data')) {
			// Prepare type "Testimonial"
			themerex_require_data( 'post_type', 'testimonial', array(
				'label'               => esc_html__( 'Testimonial', 'kidsplanet'),
				'description'         => esc_html__( 'Testimonial Description', 'kidsplanet'),
				'labels'              => array(
					'name'                => esc_html_x( 'Testimonials', 'Post Type General Name', 'kidsplanet'),
					'singular_name'       => esc_html_x( 'Testimonial', 'Post Type Singular Name', 'kidsplanet'),
					'menu_name'           => esc_html__( 'Testimonials', 'kidsplanet'),
					'parent_item_colon'   => esc_html__( 'Parent Item:', 'kidsplanet'),
					'all_items'           => esc_html__( 'All Testimonials', 'kidsplanet'),
					'view_item'           => esc_html__( 'View Item', 'kidsplanet'),
					'add_new_item'        => esc_html__( 'Add New Testimonial', 'kidsplanet'),
					'add_new'             => esc_html__( 'Add New', 'kidsplanet'),
					'edit_item'           => esc_html__( 'Edit Item', 'kidsplanet'),
					'update_item'         => esc_html__( 'Update Item', 'kidsplanet'),
					'search_items'        => esc_html__( 'Search Item', 'kidsplanet'),
					'not_found'           => esc_html__( 'Not found', 'kidsplanet'),
					'not_found_in_trash'  => esc_html__( 'Not found in Trash', 'kidsplanet'),
				),
				'supports'            => array( 'title', 'editor', 'author', 'thumbnail'),
				'hierarchical'        => false,
				'public'              => false,
				'show_ui'             => true,
				'menu_icon'			  => 'dashicons-cloud',
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => true,
				'menu_position'       => 26,
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'capability_type'     => 'page',
				)
			);
			
			// Prepare taxonomy for testimonial
			themerex_require_data( 'taxonomy', 'testimonial_group', array(
				'post_type'			=> array( 'testimonial' ),
				'hierarchical'      => true,
				'labels'            => array(
					'name'              => esc_html_x( 'Testimonials Group', 'taxonomy general name', 'kidsplanet'),
					'singular_name'     => esc_html_x( 'Group', 'taxonomy singular name', 'kidsplanet'),
					'search_items'      => esc_html__( 'Search Groups', 'kidsplanet'),
					'all_items'         => esc_html__( 'All Groups', 'kidsplanet'),
					'parent_item'       => esc_html__( 'Parent Group', 'kidsplanet'),
					'parent_item_colon' => esc_html__( 'Parent Group:', 'kidsplanet'),
					'edit_item'         => esc_html__( 'Edit Group', 'kidsplanet'),
					'update_item'       => esc_html__( 'Update Group', 'kidsplanet'),
					'add_new_item'      => esc_html__( 'Add New Group', 'kidsplanet'),
					'new_item_name'     => esc_html__( 'New Group Name', 'kidsplanet'),
					'menu_name'         => esc_html__( 'Testimonial Group', 'kidsplanet'),
				),
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'testimonial_group' ),
				)
			);
		}
	}
}


// Add override options
if (!function_exists('themerex_testimonial_add_override_options')) {
	//Handler of add_filter('trx_utils_filter_override_options', 'themerex_testimonial_add_override_options');
	function themerex_testimonial_add_override_options($boxes = array()) {
        $boxes[] = array_merge(themerex_get_global('testimonial_override_options'), array('callback' => 'themerex_testimonial_show_override_options'));
        return $boxes;
	}
}

// Callback function to show fields in override options
if (!function_exists('themerex_testimonial_show_override_options')) {
	function themerex_testimonial_show_override_options() {
		global $post, $THEMEREX_GLOBALS;

		// Use nonce for verification
		echo '<input type="hidden" name="override_options_testimonial_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
		
		$data = get_post_meta($post->ID, 'testimonial_data', true);
	
		$fields = $THEMEREX_GLOBALS['testimonial_override_options']['fields'];
		?>
		<table class="testimonial_area">
		<?php
		if (is_array($fields) && count($fields) > 0) {
			foreach ($fields as $id=>$field) { 
				$meta = isset($data[$id]) ? $data[$id] : '';
				?>
				<tr class="testimonial_field <?php echo esc_attr($field['class']); ?>" valign="top">
					<td><label for="<?php echo esc_attr($id); ?>"><?php echo esc_html($field['title']); ?></label></td>
					<td><input type="text" name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($meta); ?>" size="30" />
						<br><small><?php echo esc_html($field['desc']); ?></small></td>
				</tr>
				<?php
			}
		}
		?>
		</table>
		<?php
	}
}


// Save data from override options
if (!function_exists('themerex_testimonial_save_data')) {
	//Handler of add_action('save_post', 'themerex_testimonial_save_data');
	function themerex_testimonial_save_data($post_id) {
		// verify nonce
		if (!isset($_POST['override_options_testimonial_nonce']) || !wp_verify_nonce($_POST['override_options_testimonial_nonce'], basename(__FILE__))) {
			return $post_id;
		}

		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		// check permissions
		if ($_POST['post_type']!='testimonial' || !current_user_can('edit_post', $post_id)) {
			return $post_id;
		}

		global $THEMEREX_GLOBALS;

		$data = array();

		$fields = $THEMEREX_GLOBALS['testimonial_override_options']['fields'];

		// Post type specific data handling
		if (is_array($fields) && count($fields) > 0) {
			foreach ($fields as $id=>$field) { 
				if (isset($_POST[$id])) 
					$data[$id] = stripslashes($_POST[$id]);
			}
		}

		update_post_meta($post_id, 'testimonial_data', $data);
	}
}
?>