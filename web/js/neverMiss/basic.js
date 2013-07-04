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

function handleLoginRes(res){
	if (res.success && res.html){
		jQuery('#top-nav-wrapper').html(res.html);
	}
	
	setGlobalAlert(res);
}

function setFbLoginEvents(getLocation1, isInFlow1){
	var getLocation = false;
	if (getLocation1) getLocation = getLocation1;
	
	var isInFlow = false;
	if (isInFlow1) isInFlow = isInFlow1;
	
	jQuery('.fb-login').click(function(e){
		e.preventDefault();
		
		if (FB){
			FB.login(function(res) {
				if (res && res.authResponse){
					if (isInFlow){
						jQuery.ajax({
							url : '/main/fbLogin/?d=' + (new Date()).getTime(),
							dataType : 'json'
						}).done(handleLoginRes);
					} else {
						if (getLocation) window.location = "/main/fbLogin?gt=" + encodeURI(window.location);
						else window.location = "/main/fbLogin";
					}
				} else {
					setGlobalError('User cancelled login or did not fully authorize.');
				}
			}, {scope: 'email,user_birthday'});
		}
	});
}