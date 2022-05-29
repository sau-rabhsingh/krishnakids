/* global jQuery:false */

jQuery(document).ready(function() {
    "use strict";
    if (typeof THEMEREX_GLOBALS == 'undefined') var THEMEREX_GLOBALS = {};
	THEMEREX_GLOBALS['media_frame'] = null;
	THEMEREX_GLOBALS['media_link'] = '';
});

function themerex_show_media_manager(el) {
	"use strict";

	THEMEREX_GLOBALS['media_link'] = jQuery(el);
	// If the media frame already exists, reopen it.
	if ( THEMEREX_GLOBALS['media_frame'] ) {
		THEMEREX_GLOBALS['media_frame'].open();
		return false;
	}

	// Create the media frame.
	THEMEREX_GLOBALS['media_frame'] = wp.media({
		// Set the title of the modal.
		title: THEMEREX_GLOBALS['media_link'].data('choose'),
		// Tell the modal to show only images.
		library: {
			type: 'image'
		},
		// Multiple choise
		multiple: THEMEREX_GLOBALS['media_link'].data('multiple')===true ? 'add' : false,
		// Customize the submit button.
		button: {
			// Set the text of the button.
			text: THEMEREX_GLOBALS['media_link'].data('update'),
			// Tell the button not to close the modal, since we're
			// going to refresh the page when the image is selected.
			close: true
		}
	});

	// When an image is selected, run a callback.
	THEMEREX_GLOBALS['media_frame'].on( 'select', function(selection) {
		"use strict";
		// Grab the selected attachment.
		var field = jQuery("#"+THEMEREX_GLOBALS['media_link'].data('linked-field')).eq(0);
		var attachment = '';
		if (THEMEREX_GLOBALS['media_link'].data('multiple')===true) {
			THEMEREX_GLOBALS['media_frame'].state().get('selection').map( function( att ) {
				attachment += (attachment ? "\n" : "") + att.toJSON().url;
			});
			var val = field.val();
			attachment = val + (val ? "\n" : '') + attachment;
		} else {
			attachment = THEMEREX_GLOBALS['media_frame'].state().get('selection').first().toJSON().url;
		}
		field.val(attachment);
		field.trigger('change');
	});

	// Finally, open the modal.
	THEMEREX_GLOBALS['media_frame'].open();
	return false;
}
