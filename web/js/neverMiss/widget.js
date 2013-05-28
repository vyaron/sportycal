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
	
	jQuery('#copy-js-code').focus(function(){
		jQuery(this).select();
	});
});