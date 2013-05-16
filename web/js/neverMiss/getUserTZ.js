function getUserGmtMins(){
	var d = new Date();
	var jan = new Date(d.getFullYear(), 0, 1);
	var jul = new Date(d.getFullYear(), 6, 1);
	return  -Math.max(jan.getTimezoneOffset(), jul.getTimezoneOffset());
}

jQuery(document).ready(function(){
	jQuery.ajax({
		url : '/main/saveTZ',
		method : 'POST',
		data : {tz: getUserGmtMins()},
		type : 'json'
	}).done(function(){
		
	});
});