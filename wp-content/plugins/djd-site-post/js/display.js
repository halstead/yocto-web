(function ($) {
 	"use strict";
	$(function () {
		// Place your public-facing JavaScript here
		
		//alert('hooper5');
				//
		// var MediaLibraryUploadedFilter = wp.media.view.AttachmentFilters.extend({
		// 	id: 'media-attachment-uploaded-filter',
		//
		// 	createFilters: function() {
		//
		// 		var filters = {};
		//
		// 		filters.all = {
		// 			// Todo: String not strictly correct.
		// 			//text:  wp.media.view.l10n.allMediaItems,
		// 			text:  wp.media.view.l10n.uploadedToThisPost,
		// 			props: {
		// 				status:  null,
		// 				type:    'image',
		// 				//uploadedTo: null,
		// 				uploadedTo: wp.media.view.settings.post.id,
		// 				orderby: 'menuOrder',
		// 				order:   'ASC'
		// 			},
		// 			priority: 10
		// 		};
		//
		// 		filters.uploaded = {
		// 			//text:  wp.media.view.l10n.uploadedToThisPost,
		// 			text:  wp.media.view.l10n.allMediaItems,
		// 			props: {
		// 				status:  null,
		// 				type:    null,
		// 				//uploadedTo: wp.media.view.settings.post.id,
		// 				uploadedTo: null,
		// 				orderby: 'date',
		// 				order:   'DESC'
		// 			},
		// 			priority: 20
		// 		};
		//
		// 		filters.unattached = {
		// 			text:  wp.media.view.l10n.unattached,
		// 			props: {
		// 				status:     null,
		// 				uploadedTo: 0,
		// 				type:       'image',
		// 				orderby:    'menuOrder',
		// 				order:      'ASC'
		// 			},
		// 			priority: 30
		// 		};
		//
		// 		this.filters = filters;
		// 	}
		// });

		/**
		 * Extend and override wp.media.view.AttachmentsBrowser
		 * to include our new filter
		 */
		// var AttachmentsBrowser = wp.media.view.AttachmentsBrowser;
// 		wp.media.view.AttachmentsBrowser = wp.media.view.AttachmentsBrowser.extend({
// 			createToolbar: function() {
//
// 				// Make sure to load the original toolbar
// 				AttachmentsBrowser.prototype.createToolbar.call( this );
//
// 				this.toolbar.set(
// 					'MediaLibraryUploadedFilter',
// 					new MediaLibraryUploadedFilter({
// 						controller: this.controller,
// 						model:      this.collection.props,
// 						priority:   -100
// 					})
// 					.render()
// 				);
// 			}
// 		});
// 		// custom media uploader
// 	    custom_uploader = wp.media({
// 			title: 'Insert image',
// 			library : {
// 				// uncomment the next line if you want to attach image to the current post
// 				uploadedTo : wp.media.view.settings.post.id,
// 				type : 'image',
// 				orderby: 'menuOrder',
// 				order: 'ASC'
// 			},
// 			button: {
// 				text: 'Use this image' // button label text
// 			},
// 			state: 'library',
// 			filters: 'all',
// 			multiple: false, // for multiple image selection set to true
// 			// frame: "post",
// 			// state: "insert"
// 		}).on('select', function() { // it also has "open" and "close" events
// 			var attachment = custom_uploader.state().get('selection').first().toJSON();
// 			$(button).removeClass('button').html('<img class="true_pre_image" src="' + attachment.url + '" style="max-width:95%;display:block;" />').next().val(attachment.id).next().show();
// 			/* if you sen multiple to true, here is some code for getting the image IDs
// 			var attachments = frame.state().get('selection'),
// 			    attachment_ids = new Array(),
// 			    i = 0;
// 			attachments.each(function(attachment) {
//  				attachment_ids[i] = attachment['id'];
// 				console.log( attachment );
// 				i++;
// 			});
// 			*/
// 		}).close();
		//});

			/*
	 * Remove image event   button
	 */
	$('body').on('click', '.meta_field_remove_image_button', function(){
		$(this).hide().prev().val('').prev().addClass('button').html('Upload image');
		return false;
	});

});
}(jQuery));


		
		
		/*
		* Select/Upload image(s) event
		*/
		
		// var file_frame;
//
// 		$('body').on('click', '.meta_field_upload_image_button', function(e){
// 			e.preventDefault();
//  			console.log('block builder media event 3');
//
// 			if ( file_frame ) {
// 				file_frame.open();
// 				return;
// 			}
//
// 			file_frame = wp.media.frames.file_frame = wp.media({
// 				title: $( this ).data( 'uploader_title' ),
// 				button: {
// 					text: $( this ).data( 'uploader_button_text' ),
// 				},
// 				multiple: false // set this to true for multiple file selection
// 			});
//
// 			file_frame.on( 'select', function() {
// 				attachment = file_frame.state().get('selection').first().toJSON();
//
// 				// do something with the file here
// 				$( '#frontend-button' ).hide();
// 				$( '#frontend-image' ).attr('src', attachment.url);
// 			});
//
// 			file_frame.open();
				
 			// add media filter
		