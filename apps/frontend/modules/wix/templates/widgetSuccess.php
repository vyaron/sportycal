<?php include_partial('w/neverMissList', array(
	'calId' => $calId, 
	'events' => $events, 
	'isMobile' => $isMobile,
	'isReachedMaxSubscribers' => $isReachedMaxSubscribers,
	
	'title' => $title,
	'lineColor' => $lineColor,
	'textColor' => $textColor,
	'bgColor' => $bgColor,
	'bgOpacity' => $bgOpacity,
	'bgIsTransparent' => $bgIsTransparent,
	
	'cal' => $cal,
	'partner' => $partner
));?>

<?php 
//use_javascript('//sslstatic.wix.com/services/js-sdk/1.24.0/js/Wix.js');
//use_javascript('/js/wix/widget.js');
?>