function getUserGmtMins(){
	var d = new Date();
	var jan = new Date(d.getFullYear(), 0, 1);
	var jul = new Date(d.getFullYear(), 6, 1);
	return  -Math.max(jan.getTimezoneOffset(), jul.getTimezoneOffset());
	/*
	var d = new Date();
	var gmtMins = -d.getTimezoneOffset();
	if (gmtMins == 0) gmtMins = '0';
	
	return gmtMins;
	*/
}

window.addEvent('domready', function(){
	var gmtMins = getUserGmtMins();
	var req = new Request({
		url : '/main/saveTZ',
		onSuccess : function(res){
			var res = JSON.decode(res);
			if (res.status){
				location.reload(true);
			}
		}
	}).post('tz=' + gmtMins);
});