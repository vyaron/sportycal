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
	'bgIsTransparent' => $bgIsTransparent
));?>

<?php 
use_javascript('//sslstatic.wix.com/services/js-sdk/1.19.0/js/Wix.js');
use_javascript('/js/wix/widget.js');
?>