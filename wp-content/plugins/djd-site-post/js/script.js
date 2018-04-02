function ProcessFormAjax() {

	var errorNotice = jQuery('#error'),
		successNotice = jQuery('#success'),
		refresher = jQuery('#refresher'),
		form = jQuery('#site_post_form'),
		submit = jQuery('#submit');
	
	var theLanguage = jQuery('html').attr('lang');
	if (theLanguage == 'de-DE') {
		var btnMsg = 'Aktualisieren';
	} else {
		var btnMsg = 'Update';
	}
	
	jQuery("label#quiz_error").hide();
	if (jQuery("input#djd_quiz").val() !== jQuery("input#djd_quiz_hidden").val()) {
		jQuery("label#quiz_error").show();
		jQuery("input#djd_quiz").focus();
		return false;
	}
	if(jQuery( "#djdsitepostcontent" ).length){
		var ed = tinyMCE.get('djdsitepostcontent');
		ed.setProgressState(1);
		tinyMCE.get('djdsitepostcontent').save();
	}
	
	var newPostForm = jQuery(this).serialize();
	
	var captcha_response = grecaptcha.getResponse();
	var captcha_container = jQuery('#g-recaptcha');
	var captcha_error = jQuery('#g-recaptcha_error');
	captcha_error.hide();
	captcha_container.removeClass('error');
	
	if(captcha_response.length == 0){
	    //console.log('no captcha');
	    captcha_error.show();
	    captcha_container.addClass('error');
	    return false;
	}else{
		//console.log('yes captcha');
	    jQuery('#loading').show;
		jQuery.ajax({
			type:"POST",
			url: jQuery(this).attr('action'),
			data: newPostForm,
			success:function(response){
				if(jQuery( "#djdsitepostcontent" ).length){
					ed.setProgressState(0);
				}
				jQuery('#loading').hide;
	            if(response == "success") {
					successNotice.show();
					refresher.show();
					form.hide();
					jQuery(window).scrollTop(0);
					submit.html(btnMsg);
				} else {
					errorNotice.show();
				}
			}
		});
	}
	return false;
}
