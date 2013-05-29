function setFbLoginEvents(){
	jQuery('.fb-login').click(function(e){
		e.preventDefault();
		
		if (FB){
			FB.login(function(res) {
				if (res.authResponse) window.location = "/main/fbLogin?gt=" + encodeURI(window.location);
			}, {scope: 'email,user_birthday'});
		}
	});
}


jQuery(document).ready(function(){
	setFbLoginEvents();
	
	jQuery('#register-form').validate({rules: {
		'register[password]': "required",
		'register[confirm_password]': {
			equalTo: "#register_password"
		}
	}});
	
	jQuery('#copy-js-code').focus(function(){
		jQuery(this).select();
	});
});