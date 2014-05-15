function calcWidgetHeight(){
	var windowHeight = $(window).height();
	var headerHeight = 63;
	var footerHeight = 55;
	
	var maxHeight = Math.max(0,  windowHeight - headerHeight - footerHeight - 30);
	
	$("#events")
		.css('height', maxHeight)
		.mCustomScrollbar("update");
}

function showDesc(e){
	if (e) e.preventDefault();
	
	$(this).parent().addClass('open');
	calcWidgetHeight();
}

function hideDesc(e){
	if (e) e.preventDefault();
	
	$(this).parent().removeClass('open');
	calcWidgetHeight();
}

$(document).ready(function(){
	$("#events").mCustomScrollbar();
	
	$('.desc-open-btn').click(showDesc);
	$('.desc-close-btn').click(hideDesc);

    var ref = document.referrer;
    if (ref){
        ref = encodeURIComponent(ref);
        $('.cal-btn').each(function(e, el){
            var href = $(el).attr('href');
            if (href) $(el).attr('href', href + '?website=' + ref);
        });
    }

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