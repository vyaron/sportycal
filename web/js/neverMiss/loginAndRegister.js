function handleAfterLoginRes(res){
	if (res && res.success) window.location.href = '/nm/licenceWizard/?c=' + gCode;
}

function setLogin(){
	setFbLoginEvents(false,true);

	var form = jQuery('#login-form');
	form.validate();
	
	form.submit(function(e){
		e.preventDefault();
		
		var formEl = jQuery(this);
		if (formEl.valid()){
			jQuery.ajax({
				url : '/partner/login/?d=' + (new Date()).getTime(),
				type : 'POST',
				dataType : 'json',
				data : formEl.serializeArray()
			}).done(handleLoginRes);
		}
	});
}

function setRegister(){
	var form = jQuery('#register-form');
	form.validate();
	
	form.submit(function(e){
		e.preventDefault();

		var formEl = jQuery(this);
		if (formEl.valid()){
			jQuery.ajax({
				url : '/frontend_dev.php/nm/register/?d=' + (new Date()).getTime(),
				type : 'POST',
				dataType : 'json',
				data : formEl.serializeArray()
			}).done(handleLoginRes);
		}
	});
}

jQuery(document).ready(function(){
	setLogin();
	setRegister();
});