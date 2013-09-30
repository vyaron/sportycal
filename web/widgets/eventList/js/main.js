function calcWidgetHeight(){
	var windowHeight = $(window).height();
	var headerHeight = 63;
	var footerHeight = 55;
	
	var maxHeight = Math.max(0,  windowHeight - headerHeight - footerHeight - 30);
	
	$("#events")
		.css('height', maxHeight)
		.mCustomScrollbar("update");
}

$(document).ready(function(){
	$("#events").mCustomScrollbar();
	
	calcWidgetHeight();
	$(window).resize(calcWidgetHeight);
});


//
//$(document).ready(function(){
//	$("#widget-wrapper").mCustomScrollbar();
//
//	calcWidgetHeight();
//	$(window).resize(calcWidgetHeight);
//	
//	/*
//	Wix.addEventListener(Wix.Events.SETTINGS_UPDATED, function(message) {
//		window.location.reload();
//	});
//	*/
//});