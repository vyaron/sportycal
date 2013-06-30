function numberWithCommas(x) {
    x = x.toString();
    var pattern = /(-?\d+)(\d{3})/;
    while (pattern.test(x))
        x = x.replace(pattern, "$1,$2");
    return x;
}

function getPlayerSize(){
	var playerEl = jQuery('#player-wrapper');
	var playerWidth = playerEl.width();
	var playerHeight = playerWidth * (9/16);

	return {width : playerWidth, height : playerHeight};
}

function updatePlayerSize(){
	if (gPlayer){
		var playerSize = getPlayerSize();
		
		gPlayer.options.videoWidth = playerSize.width;
		gPlayer.options.videoHeight = playerSize.height;
		gPlayer.setPlayerSize(playerSize.width, playerSize.height);
		gPlayer.media.setVideoSize(playerSize.width, playerSize.height);
		gPlayer.setControlsSize();
	}
}

var gPlayer = null;
jQuery(document).ready(function(){
	var calDownCounter = jQuery('#cal-down-counter');
	window.setInterval(function(){
		var val = parseInt(calDownCounter.text().replace(',', ''));
		calDownCounter.text(numberWithCommas(val + Math.floor(Math.random() * 5)));
	}, Math.floor((Math.random() + 0.3) * 2000));
	
	var playerSize =getPlayerSize();
	var playeEl = jQuery('#player');
	playeEl.attr({
		width : playerSize.width,
		height : playerSize.height
	});
	gPlayer = new MediaElementPlayer(playeEl, {features : ['playpause','progress','fullscreen']});
	
	//Set player resize event
	var supportsOrientationChange = "onorientationchange" in window,
    orientationEvent = supportsOrientationChange ? "orientationchange" : "resize";
	window.addEventListener(orientationEvent, updatePlayerSize, false);
});