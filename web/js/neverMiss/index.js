function numberWithCommas(x) {
    x = x.toString();
    var pattern = /(-?\d+)(\d{3})/;
    while (pattern.test(x))
        x = x.replace(pattern, "$1,$2");
    return x;
}

jQuery(document).ready(function(){
	var calDownCounter = jQuery('#cal-down-counter');
	window.setInterval(function(){
		var val = parseInt(calDownCounter.text().replace(',', ''));
		calDownCounter.text(numberWithCommas(val + Math.floor(Math.random() * 5)));
	}, Math.floor((Math.random() + 0.3) * 2000));
});