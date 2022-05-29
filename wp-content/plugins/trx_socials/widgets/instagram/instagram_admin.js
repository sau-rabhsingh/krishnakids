/**
 * Widget Instagram
 *
 * @package ThemeREX Socials
 * @since v1.0.0
 */

/* global jQuery:false */
/* global TRX_SOCIALS_STORAGE:false */

jQuery(document).ready(function() {
	"use strict";
	// Callback for get access token
	jQuery('.trx_socials_api_instagram_get_access_token').on('click', function() {
		window.location.href = TRX_SOCIALS_STORAGE['api_instagram_get_code_uri'];
	});
});