jQuery(document).ready(function(){
	var calDownCounter = jQuery('#cal-down-counter');
	window.setInterval(function(){
		var val = parseInt(calDownCounter.text().replace(',', ''));
		calDownCounter.text(val + Math.floor(Math.random() * 5));
	}, Math.floor((Math.random() + 0.3) * 2000));
});