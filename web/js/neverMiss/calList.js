jQuery(document).ready(function(){
	jQuery('.delete-cal').click(function(e){
		e.preventDefault();
		
		var btn = $(this);
		var deleteUrl = btn.attr('href');
		var deleteCalName = btn.attr('data-name');
		
		var deleteCalModal = jQuery('#delete-cal-modal');
		
		deleteCalModal.find('#delete-btn').attr('href', deleteUrl);
		deleteCalModal.find('#delete-cal-name').text(deleteCalName);
		
		deleteCalModal.modal();
	});
});