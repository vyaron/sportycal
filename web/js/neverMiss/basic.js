function cl(mix){
	if (console && console.log) console.log(mix);
}

function setGlobalAlert(res){
	if (res){
		if (res.success) setGlobalSuccess(res.msg);
		else setGlobalError(res.msg);
		
		jQuery('html, body').animate({
	         scrollTop: 0 //jQuery("#alerts").offset().top
	     }, 2000);
	}
}

function setGlobalError(content){
	var elStr = jQuery('#DUMMY_GLOBAL_ERROR').html();
	elStr = elStr.replace('{CONTENT}', content);
	
	jQuery('#alerts').html(elStr);
	
	jQuery('html, body').animate({scrollTop: 0}, 2000);
}

function setGlobalSuccess(content){
	var elStr = jQuery('#DUMMY_GLOBAL_SUCCESS').html();
	elStr = elStr.replace('{CONTENT}', content);
	
	jQuery('#alerts').html(elStr);
	jQuery('html, body').animate({scrollTop: 0}, 2000);
}

function setFbLoginEvents(getLocation1){
	var getLocation = false;
	if (getLocation1) getLocation = getLocation1;
	
	jQuery('.fb-login').click(function(e){
		e.preventDefault();
		
		if (FB){
			FB.login(function(res) {
				if (res.authResponse) {
					if (getLocation) window.location = "/main/fbLogin?gt=" + encodeURI(window.location);
					else window.location = "/main/fbLogin";
				}
			}, {scope: 'email,user_birthday'});
		}
	});
}