<?php
/**
 * ThemeREX Framework: messages subsystem
 *
 * @package	themerex
 * @since	themerex 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('themerex_messages_theme_setup')) {
	add_action( 'themerex_action_before_init_theme', 'themerex_messages_theme_setup' );
	function themerex_messages_theme_setup() {
		// Core messages strings
		add_filter('themerex_action_add_scripts_inline', 'themerex_messages_add_scripts_inline');
	}
}


/* Session messages
------------------------------------------------------------------------------------- */

if (!function_exists('themerex_get_error_msg')) {
	function themerex_get_error_msg() {
		global $THEMEREX_GLOBALS;
		return !empty($THEMEREX_GLOBALS['error_msg']) ? $THEMEREX_GLOBALS['error_msg'] : '';
	}
}

if (!function_exists('themerex_set_error_msg')) {
	function themerex_set_error_msg($msg) {
		global $THEMEREX_GLOBALS;
		$msg2 = themerex_get_error_msg();
		$THEMEREX_GLOBALS['error_msg'] = $msg2 . ($msg2=='' ? '' : '<br />') . ($msg);
	}
}

if (!function_exists('themerex_get_success_msg')) {
	function themerex_get_success_msg() {
		global $THEMEREX_GLOBALS;
		return !empty($THEMEREX_GLOBALS['success_msg']) ? $THEMEREX_GLOBALS['success_msg'] : '';
	}
}

if (!function_exists('themerex_set_success_msg')) {
	function themerex_set_success_msg($msg) {
		global $THEMEREX_GLOBALS;
		$msg2 = themerex_get_success_msg();
		$THEMEREX_GLOBALS['success_msg'] = $msg2 . ($msg2=='' ? '' : '<br />') . ($msg);
	}
}

if (!function_exists('themerex_get_notice_msg')) {
	function themerex_get_notice_msg() {
		global $THEMEREX_GLOBALS;
		return !empty($THEMEREX_GLOBALS['notice_msg']) ? $THEMEREX_GLOBALS['notice_msg'] : '';
	}
}

if (!function_exists('themerex_set_notice_msg')) {
	function themerex_set_notice_msg($msg) {
		global $THEMEREX_GLOBALS;
		$msg2 = themerex_get_notice_msg();
		$THEMEREX_GLOBALS['notice_msg'] = $msg2 . ($msg2=='' ? '' : '<br />') . ($msg);
	}
}


/* System messages (save when page reload)
------------------------------------------------------------------------------------- */
if (!function_exists('themerex_set_system_message')) {
	function themerex_set_system_message($msg, $status='info', $hdr='') {
		update_option('themerex_message', array('message' => $msg, 'status' => $status, 'header' => $hdr));
	}
}

if (!function_exists('themerex_get_system_message')) {
	function themerex_get_system_message($del=false) {
		$msg = get_option('themerex_message', false);
		if (!$msg)
			$msg = array('message' => '', 'status' => '', 'header' => '');
		else if ($del)
			themerex_del_system_message();
		return $msg;
	}
}

if (!function_exists('themerex_del_system_message')) {
	function themerex_del_system_message() {
		delete_option('themerex_message');
	}
}


/* Messages strings
------------------------------------------------------------------------------------- */

if (!function_exists('themerex_messages_add_scripts_inline')) {
	function themerex_messages_add_scripts_inline($vars=array()) {
        // Strings for translation
        $vars["strings"] = array(
            'bookmark_add' => esc_html__('Add the bookmark', 'kidsplanet'),
            'bookmark_added' => esc_html__('Current page has been successfully added to the bookmarks. You can see it in the right panel on the tab \'Bookmarks\'', 'kidsplanet'),
            'bookmark_del' => esc_html__('Delete this bookmark', 'kidsplanet'),
            'bookmark_title' => esc_html__('Enter bookmark title', 'kidsplanet'),
            'bookmark_exists' => esc_html__('Current page already exists in the bookmarks list', 'kidsplanet'),
            'search_error' => esc_html__('Error occurs in AJAX search! Please, type your query and press search icon for the traditional search way.', 'kidsplanet'),
            'email_confirm' => esc_html__('On the e-mail address "%s" we sent a confirmation email. Please, open it and click on the link.', 'kidsplanet'),
            'reviews_vote' => esc_html__('Thanks for your vote! New average rating is:', 'kidsplanet'),
            'reviews_error' => esc_html__('Error saving your vote! Please, try again later.', 'kidsplanet'),
            'error_like' => esc_html__('Error saving your like! Please, try again later.', 'kidsplanet'),
            'error_global' => esc_html__('Global error text', 'kidsplanet'),
            'name_empty' => esc_html__('The name can\'t be empty', 'kidsplanet'),
            'name_long' => esc_html__('Too long name', 'kidsplanet'),
            'email_empty' => esc_html__('Too short (or empty) email address', 'kidsplanet'),
            'email_long' => esc_html__('Too long email address', 'kidsplanet'),
            'email_not_valid' => esc_html__('Invalid email address', 'kidsplanet'),
            'subject_empty' => esc_html__('The subject can\'t be empty', 'kidsplanet'),
            'subject_long' => esc_html__('Too long subject', 'kidsplanet'),
            'text_empty' => esc_html__('The message text can\'t be empty', 'kidsplanet'),
            'text_long' => esc_html__('Too long message text', 'kidsplanet'),
            'send_complete' => esc_html__("Send message complete!", 'kidsplanet'),
            'send_error' => esc_html__('Transmit failed!', 'kidsplanet'),
            'login_empty' => esc_html__('The Login field can\'t be empty', 'kidsplanet'),
            'login_long' => esc_html__('Too long login field', 'kidsplanet'),
            'login_success' => esc_html__('Login success! The page will be reloaded in 3 sec.', 'kidsplanet'),
            'login_failed' => esc_html__('Login failed!', 'kidsplanet'),
            'password_empty' => esc_html__('The password can\'t be empty and shorter then 4 characters', 'kidsplanet'),
            'password_long' => esc_html__('Too long password', 'kidsplanet'),
            'password_not_equal' => esc_html__('The passwords in both fields are not equal', 'kidsplanet'),
            'registration_success' => esc_html__('Registration success! Please log in!', 'kidsplanet'),
            'registration_failed' => esc_html__('Registration failed!', 'kidsplanet'),
            'geocode_error' => esc_html__('Geocode was not successful for the following reason:', 'kidsplanet'),
            'googlemap_not_avail' => esc_html__('Google map API not available!', 'kidsplanet'),
            'editor_save_success' => esc_html__("Post content saved!", 'kidsplanet'),
            'editor_save_error' => esc_html__("Error saving post data!", 'kidsplanet'),
            'editor_delete_post' => esc_html__("You really want to delete the current post?", 'kidsplanet'),
            'editor_delete_post_header' => esc_html__("Delete post", 'kidsplanet'),
            'editor_delete_success' => esc_html__("Post deleted!", 'kidsplanet'),
            'editor_delete_error' => esc_html__("Error deleting post!", 'kidsplanet'),
            'editor_caption_cancel' => esc_html__('Cancel', 'kidsplanet'),
            'editor_caption_close' => esc_html__('Close', 'kidsplanet')
        );
        return $vars;
	}
}
?>