/**
 * Admin utilities (for internal use only!)
 *
 * @package ThemeREX Socials
 * @since v1.0.0
 */

/* global jQuery:false */
/* global TRX_SOCIALS_STORAGE:false */

(function() {

	"use strict";

	if (typeof TRX_SOCIALS_STORAGE == 'undefined') window.TRX_SOCIALS_STORAGE = {};

	// Prepare media selector params
	TRX_SOCIALS_STORAGE['media_id'] = '';
	TRX_SOCIALS_STORAGE['media_frame'] = [];
	TRX_SOCIALS_STORAGE['media_link'] = [];


	jQuery(document).ready(function() {

		// Media selector
		jQuery('#customize-theme-controls:not(.inited_sms)'
				+',.widget-liquid-right:not(.inited_sms)'
				+',.widgets-holder-wrap:not(.inited_sms)'
				+',.widget_field_type_image:not(.inited_sms)'
				+',#elementor-panel:not(.inited_sms)'
			)
			.addClass('inited_sms')
			.on('click', '.trx_socials_media_selector', function(e) {
				trx_socials_show_media_manager(this);
				e.preventDefault();
				return false;
			})
			.on( 'keydown', '.trx_socials_media_selector_preview > .trx_socials_media_selector_preview_image', function(e) {
				// If 'Enter' or 'Space' is pressed - remove image
				if ( [ 13, 32 ].indexOf( e.which ) >= 0 ) {
					jQuery( this ).trigger('click');
					e.preventDefault();
					return false;
				}
				return true;
			} )
			.on('click', '.trx_socials_media_selector_preview > .trx_socials_media_selector_preview_image', function(e) {
				var image = jQuery(this),
					preview = image.parent(),
					button = preview.siblings('.trx_socials_media_selector'),
					field = jQuery('#'+button.data('linked-field'));
				if (field.length === 0) return true;
				if (button.data('multiple') == 1) {
					var val = field.val().split('|');
					val.splice(image.index(), 1);
					field.val(val.join('|'));
					image.remove();
				} else {
					field.val('');
					image.remove();
				}
				preview.toggleClass('trx_socials_media_selector_preview_with_image', preview.find('> .trx_socials_media_selector_preview_image').length > 0);
				e.preventDefault();
				return false;
			});
	
		// Show WP Media manager to select image
		// -------------------------------------------------------------------------------------
		function trx_socials_show_media_manager(el) {
		
			TRX_SOCIALS_STORAGE['media_id'] = jQuery(el).attr('id');
			TRX_SOCIALS_STORAGE['media_link'][TRX_SOCIALS_STORAGE['media_id']] = jQuery(el);
			// If the media frame already exists, reopen it.
			if ( TRX_SOCIALS_STORAGE['media_frame'][TRX_SOCIALS_STORAGE['media_id']] ) {
				TRX_SOCIALS_STORAGE['media_frame'][TRX_SOCIALS_STORAGE['media_id']].open();
				return false;
			}
		
			// Create the media frame
			var type = TRX_SOCIALS_STORAGE['media_link'][TRX_SOCIALS_STORAGE['media_id']].data('type') 
							? TRX_SOCIALS_STORAGE['media_link'][TRX_SOCIALS_STORAGE['media_id']].data('type') 
							: 'image';
			var args = {
				// Set the title of the modal.
				title: TRX_SOCIALS_STORAGE['media_link'][TRX_SOCIALS_STORAGE['media_id']].data('choose'),
				// Multiple choise
				multiple: TRX_SOCIALS_STORAGE['media_link'][TRX_SOCIALS_STORAGE['media_id']].data('multiple')==1 
							? 'add' 
							: false,
				// Customize the submit button.
				button: {
					// Set the text of the button.
					text: TRX_SOCIALS_STORAGE['media_link'][TRX_SOCIALS_STORAGE['media_id']].data('update'),
					// Tell the button not to close the modal, since we're
					// going to refresh the page when the image is selected.
					close: true
				}
			};
			// Allow sizes and filters for the images
			if (type == 'image') {
				args['frame'] = 'post';
			}
			// Tell the modal to show only selected post types
			if (type == 'image' || type == 'audio' || type == 'video') {
				args['library'] = {
					type: type
				};
			}
			TRX_SOCIALS_STORAGE['media_frame'][TRX_SOCIALS_STORAGE['media_id']] = wp.media(args);
		
			// When an image is selected, run a callback.
			TRX_SOCIALS_STORAGE['media_frame'][TRX_SOCIALS_STORAGE['media_id']].on( 'insert select', function(selection) {
				// Grab the selected attachment.
				var field = jQuery("#"+TRX_SOCIALS_STORAGE['media_link'][TRX_SOCIALS_STORAGE['media_id']].data('linked-field')).eq(0);
				var attachment = null, attachment_url = '';
				if (TRX_SOCIALS_STORAGE['media_link'][TRX_SOCIALS_STORAGE['media_id']].data('multiple')==1) {
					TRX_SOCIALS_STORAGE['media_frame'][TRX_SOCIALS_STORAGE['media_id']].state().get('selection').map( function( att ) {
						attachment_url += (attachment_url ? "|" : "") + att.toJSON().url;
					});
					var val = field.val();
					attachment_url = val + (val ? "|" : '') + attachment_url;
				} else {
					attachment = TRX_SOCIALS_STORAGE['media_frame'][TRX_SOCIALS_STORAGE['media_id']].state().get('selection').first().toJSON();
					attachment_url = attachment.url;
					var sizes_selector = jQuery('.media-modal-content .attachment-display-settings select.size');
					if (sizes_selector.length > 0) {
						var size = trx_socials_get_listbox_selected_value(sizes_selector.get(0));
						if (size !== '') attachment_url = attachment.sizes[size].url;
					}
				}
				// Display images in the preview area
				var preview = field.siblings('.trx_socials_media_selector_preview');
				if (preview.length === 0) {
					jQuery('<span class="trx_socials_media_selector_preview"></span>').insertAfter(field);
					preview = field.siblings('.trx_socials_media_selector_preview');
				}
				if (preview.length !== 0) preview.find('.trx_socials_media_selector_preview_image').remove();
				var images = attachment_url.split("|");
				for (var i=0; i < images.length; i++) {
					if (preview.length !== 0) {
						var ext = trx_socials_get_file_ext(images[i]);
						preview.append('<span class="trx_socials_media_selector_preview_image" tabindex="0">'
										+ (ext=='gif' || ext=='jpg' || ext=='jpeg' || ext=='png' 
												? '<img src="'+images[i]+'">'
												: '<a href="'+images[i]+'">'+trx_socials_get_file_name(images[i])+'</a>'
											)
										+ '</span>');
					}
				}
				preview.toggleClass('trx_socials_media_selector_preview_with_image', preview.find('> .trx_socials_media_selector_preview_image').length > 0);
				// Update field
				field.val(attachment_url).trigger('change');
			});
		
			// Finally, open the modal.
			TRX_SOCIALS_STORAGE['media_frame'][TRX_SOCIALS_STORAGE['media_id']].open();
			return false;
		}
	});

})();