function updateWidgetData(){
	var el = jQuery(this);

	var attrName = 'data-' + el.attr('name');
	var val = el.val();
	
	var regExp = new RegExp('(' + attrName + '=")(\\w+)');

	var copyJsCode = jQuery('#copy-js-code');

	var newVal = copyJsCode.val().replace(regExp, '$1' + val);
	copyJsCode.text(newVal);
	
	jQuery('.nm-follow').attr(attrName, val);
	if (iNeverMiss && iNeverMiss.reload) iNeverMiss.reload();
}

function setEmailFormEvent(){
	var form = jQuery('#email-form');
	
	form.validate();
	
	form.submit(function(e){
		e.preventDefault();
		
		var formEl = jQuery(this);
		if (formEl.valid()){
			jQuery.ajax({
				url : '/nm/subscribeByMail/?d=' + (new Date()).getTime(),
				type : 'POST',
				dataType : 'json',
				data : formEl.serializeArray()
			}).done(handleLoginRes);
		}
	});
}

jQuery(document).ready(function(){
	setFbLoginEvents(true);
	setEmailFormEvent();
	
	jQuery('#language, #btn-style, #btn-size, #color').change(updateWidgetData);
	
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