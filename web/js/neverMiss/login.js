jQuery(document).ready(function(){
	setFbLoginEvents(false);
	jQuery('#login-form').validate();
	jQuery('#login-form').submit(function(){
		jQuery('.error_list').remove();
	});
});