jQuery(document).ready(function(){
	jQuery('.delete-cal').click(function(e){
		e.preventDefault();
		
		var btn = $(this);
		var calId = btn.attr('data-cal-id');
		var deleteUrl = btn.attr('href');
		var deleteCalName = btn.attr('data-name');
		
		var deleteCalModal = jQuery('#delete-cal-modal');
		
		deleteCalModal.find('#delete-btn').attr('data-cal-id', calId);
		deleteCalModal.find('#delete-btn').attr('href', deleteUrl);
		deleteCalModal.find('#delete-cal-name').text(deleteCalName);
		
		deleteCalModal.modal();
	});
	
	jQuery('#delete-btn').click(function(e){
		e.preventDefault();
		
		var el = jQuery(this);
		var url = el.attr('href');
		var calId = el.attr('data-cal-id');
		jQuery.ajax({
			url : url,
			data : {id : calId},
			type : 'POST',
			dataType : 'json'
		}).done(function(res){
			jQuery('#cal_' + calId).addClass('cal-is-deleted').removeClass('cal-is-active');
			setGlobalAlert(res);
		}).fail(setGlobalAlert).always(function(){
			jQuery('#delete-cal-modal').modal('hide');
		});
	});
	
	
	jQuery('.cal-restore').click(function(e){
		e.preventDefault();
		var el = jQuery(this);
		var url = el.attr('href');
		var calId = el.attr('data-cal-id');
		jQuery.ajax({
			url : url,
			data : {id : calId},
			type : 'POST',
			dataType : 'json'
		}).done(function(res){
			el.parents('tr').removeClass('cal-is-deleted').addClass('cal-is-active');
			setGlobalAlert(res);
		}).fail(setGlobalAlert);
	});
});