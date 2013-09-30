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
			}).done(setGlobalAlert);
		}
	});
}

jQuery(document).ready(function(){
	setEmailFormEvent();
	
	jQuery('#language, #btn-style, #btn-size, #color, #upcoming').change(updateWidgetData);
	jQuery('#btn-style').change(function(e){
		if (jQuery(this).val() == 'list'){
			jQuery('#desktop-widget').css({
				'padding-left' : '135px',
				'padding-top' : '20px'
			});
			
			jQuery('#btn-size-wrapper').hide();
			jQuery('#upcoming-wrapper').show();
		} else {
			jQuery('#desktop-widget').removeAttr('style');
			jQuery('#btn-size-wrapper').show();
			jQuery('#upcoming-wrapper').hide();
		}
	});
	
	jQuery('#copy-js-code').focus(function(){
		jQuery(this).select();
	});
});