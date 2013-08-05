function numberWithCommas(x) {
    x = x.toString();
    var pattern = /(-?\d+)(\d{3})/;
    while (pattern.test(x))
        x = x.replace(pattern, "$1,$2");
    return x;
}
/*
function getPlayerSize(){
	var playerEl = jQuery('#player-wrapper');
	var playerWidth = playerEl.width();
	var playerHeight = playerWidth * (9/16);

	return {width : playerWidth, height : playerHeight};
}


function updatePlayerSize(){
	if (gPlayer){
		var playerSize = getPlayerSize();
		
		//gPlayer.container.hide();
		gPlayer.options.videoWidth = playerSize.width;
		gPlayer.options.videoHeight = playerSize.height;
		gPlayer.setPlayerSize(playerSize.width, playerSize.height);
		gPlayer.media.setVideoSize(playerSize.width, playerSize.height);
		gPlayer.setControlsSize();

		//gPlayer.container.show();

	}
}
*/

var gPlayer = null;
jQuery(document).ready(function(){
	var calDownCounter = jQuery('#cal-down-counter');
	window.setInterval(function(){
		var val = parseInt(calDownCounter.text().replace(',', ''));
		calDownCounter.text(numberWithCommas(val + Math.floor(Math.random() * 5)));
	}, Math.floor((Math.random() + 0.3) * 2000));
	
	
	var playeEl = jQuery('#player');
	/*
	var playerSize = getPlayerSize();
	
	playeEl.attr({
		width : playerSize.width,
		height : playerSize.height
	});
	*/
	gPlayer = new MediaElementPlayer(playeEl, {features : ['playpause','progress','fullscreen']});
	
	//Set player resize event
	/*
	var supportsOrientationChange = "onorientationchange" in window;
    var orientationEvent = "resize";
	var delayTime = 0;
	
	if (supportsOrientationChange){
		orientationEvent = 'orientationchange';
		delayTime = 1000;
	}
	
	
	if (window.addEventListener){
		window.addEventListener(orientationEvent, function(){
			window.setTimeout(updatePlayerSize, delayTime);
		}, false);
	} else {
		window.attachEvent(orientationEvent, function(){
			window.setTimeout(updatePlayerSize, delayTime);
		}, false);
	}
	*/
});