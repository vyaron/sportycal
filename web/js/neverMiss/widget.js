function updateWidgetData(){
	var el = jQuery(this);

	var attrName = 'data-' + el.attr('name');
	var val = el.val();
	
	var widgetDivEl = jQuery('.nm-follow');
	widgetDivEl.attr(attrName, val);
	
	for (var i=0; i< widgetDivEl[0].attributes.length; i++){
		if (widgetDivEl[0].attributes[i].value == 'default' || !widgetDivEl[0].attributes[i].value) widgetDivEl.removeAttr(widgetDivEl[0].attributes[i].name);
	}

	
	widgetDivEl.html('');
	
	
	var copyJsCode = jQuery('#copy-js-code');
	var newVal = widgetDivEl[0].outerHTML + copyJsCode.text().match('<script>.+?</script>')[0];
	copyJsCode.text(newVal);
	
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

function styleChanged(){
	if (this.value == 'list'){
		jQuery('#customize-option-color-yellow').hide();
		jQuery('#customize-prop-btn-size').hide();
		jQuery('#customize-prop-size').show();
		jQuery('#customize-option-color-default').click();
		jQuery('#widget').addClass('list');
	} else {
		if (this.value == 'only_icon') jQuery('#customize-prop-btn-size .customize-prop').addClass('customize-prop-icon');
		else jQuery('#customize-prop-btn-size .customize-prop').removeClass('customize-prop-icon');
		
		jQuery('#customize-option-color-yellow').show();
		jQuery('#customize-prop-btn-size').show();
		jQuery('#customize-prop-size').hide();
		jQuery('#widget').removeClass('list');
	}
}

jQuery(document).ready(function(){
	setEmailFormEvent();
	
	jQuery('#widget-form input, #language').change(updateWidgetData);
	jQuery('#widget-form input[name="btn-style"]').change(styleChanged);
	jQuery('.customize-option').click(function(e){
		var el = jQuery(this);
		
		var name = el.attr('data-name');
		var value = el.attr('data-value');
		
		jQuery('.customize-option[data-name="' + name +'"] .customize-option-checkbox').removeClass('checked');
		
		jQuery('#widget-form input[name="' + name +'"][value="' + value + '"]').prop('checked', true).change();
		
		el.find('.customize-option-checkbox').addClass('checked');
	});
	
	jQuery('#copy-js-code').focus(function(){
		jQuery(this).select();
	});

	jQuery('#promote-link input').click(function(){
		this.select();
	});
});