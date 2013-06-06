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
	
	jQuery('#register-form').validate({
		errorPlacement: function(error, element) {
			if (element.attr("name") == "register[agree]"){
				error.insertAfter(element.parent());
		    } else {
		        error.insertAfter(element);
		    }
		},
		rules: {
			/*
			'register[agree]': {
				required : true,
				errorPlacement : function(err, el){
					console.log(err);
					console.log(el);
				}
			},
			*/
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