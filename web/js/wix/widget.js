function calcWidgetHeight(){
	var maxHeight = $(window).height() - 32;
	$("#widget-wrapper")
		.css('height', maxHeight)
		.mCustomScrollbar("update");
}

$(document).ready(function(){
	$("#widget-wrapper").mCustomScrollbar();

	calcWidgetHeight();
	$(window).resize(calcWidgetHeight);
	
	/*
	Wix.addEventListener(Wix.Events.SETTINGS_UPDATED, function(message) {
		window.location.reload();
	});
	*/
});