function toggleRegisterLogin(e){
	if (e) e.preventDefault();
	
	var loginFormWrapper = jQuery('#login-form-wrapper');
	var registerFormWrapper = jQuery('#register-form-wrapper');
	if (loginFormWrapper.hasClass('hide')){
		loginFormWrapper.removeClass('hide');
		registerFormWrapper.addClass('hide');
	} else {
		loginFormWrapper.addClass('hide');
		registerFormWrapper.removeClass('hide');
	}
	
}


function reloadLoginRegister(){
	if (opener){
		opener.location.reload();
		window.close();
	} else {
		window.location.reload();
	}
}

function handleRegisterForm(){
	var registerForm = jQuery('#register-form');
	
	//Set validate
	registerForm.validate({
		errorPlacement: function(error, element) {
			if (element.attr("name") == "register[agree]"){
				error.insertAfter(element.parent());
		    } else {
		        error.insertAfter(element);
		    }
		}
		/*,
		rules: {
			'register[password]': "required",
			'register[confirm_password]': {
				equalTo: "#register_password"
			}
		}, 
		messages : {
			'register[confirm_password]' : 'Passwords don\'t match.'
		}*/
	});
	
	//set ajax submit
	registerForm.submit(function(e){
		e.preventDefault();
		
		registerForm.find('[type=submit]').addClass('loading');
		
		if (registerForm.valid()){
			jQuery.ajax({
				url : '/nm/register' + (gWixInstance ? '/?wixInstance=' + gWixInstance : ''),
				type : 'POST',
				dataType : 'json',
				data : registerForm.serialize()
			}).done(function(res){
				registerForm.find('[type="submit"]').removeClass('loading');
				
				if (res){
					if (res.errors) registerForm.data('validator').showErrors(res.errors);
					else if (res.success) reloadLoginRegister();
					
					if (!res.success) setGlobalAlert(res);
				}
			});
		}
	});
	
}

function handleLoginForm(){
	var loginForm = jQuery('#login-form');
	loginForm.validate();
	loginForm.submit(function(e){
		e.preventDefault();
		
		loginForm.find('[type=submit]').addClass('loading');
		
		if (loginForm.valid()){
			jQuery.ajax({
				url : '/partner/login' + (gWixInstance ? '/?wixInstance=' + gWixInstance : ''),
				type : 'POST',
				dataType : 'json',
				data : loginForm.serialize()
			}).done(function(res){
				loginForm.find('[type=submit]').removeClass('loading');
				
				//setGlobalAlert(res);
				
				if (res){
					if (res.errors) loginForm.data('validator').showErrors(res.errors);
					else if (res.success) reloadLoginRegister();
					
					if (!res.success) setGlobalAlert(res);
				}
			});
		}
	});
}

function setFbLoginEvents(){
	jQuery('.fb-login').click(function(e){
		e.preventDefault();
		
		if (FB){
			jQuery(this).addClass('loading');
			
			FB.login(function(res) {
				jQuery(this).removeClass('loading');
				
				if (res && res.authResponse){
					jQuery.ajax({
						url : '/main/fbLogin/?d=' + (new Date()).getTime(),
						dataType : 'json'
					}).done(function(res){
						if (!res.success) setGlobalAlert(res);
						reloadLoginRegister();
					});
				} else {
					setGlobalError('User cancelled login or did not fully authorize.');
				}
			}, {scope: 'email,user_birthday'});
		}
	});
}

jQuery(document).ready(function(){
	setFbLoginEvents();
	handleRegisterForm();
	handleLoginForm();
	
	jQuery('.toggle-login-register').click(toggleRegisterLogin);
});