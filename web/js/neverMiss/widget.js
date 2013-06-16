jQuery(document).ready(function(){
	setFbLoginEvents(true);
	
	jQuery('#language').change(function(){
		var lang = jQuery(this).val();

		var copyJsCode = jQuery('#copy-js-code');
		var newVal = copyJsCode.val().replace(/(data-language=")(\w+)/, '$1' + lang);
		copyJsCode.text(newVal);
		
		jQuery('.nm-follow').attr('data-language', lang);
		if (iNeverMiss && iNeverMiss.reload) iNeverMiss.reload();
	});
	
	jQuery('#register-form').validate({
		errorPlacement: function(error, element) {
			if (element.attr("name") == "register[agree]"){
				error.insertAfter(element.parent());
		    } else {
		        error.insertAfter(element);
		    }
		},
		rules: {
			'register[password]': "required",
			'register[confirm_password]': {
				equalTo: "#register_password"
			}
		}, 
		messages : {
			'register[confirm_password]' : 'Passwords don\'t match.'
		}
	});
	
	jQuery('#copy-js-code').focus(function(){
		jQuery(this).select();
	});
});