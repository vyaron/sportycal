jQuery(document).ready(function(){
	var form = jQuery('#contact-form');
	
	form.validate();
	
	form.submit(function(e){
		e.preventDefault();
		
		var formEl = jQuery(this);
		if (formEl.valid()){
			formEl.addClass('loading');
			jQuery.ajax({
				url : '/nm/contact/?d=' + (new Date()).getTime(),
				type : 'POST',
				dataType : 'json',
				data : formEl.serializeArray()
			}).done(function(res){
				formEl.removeClass('loading');
				setGlobalAlert(res);
			});
		}
	});
});