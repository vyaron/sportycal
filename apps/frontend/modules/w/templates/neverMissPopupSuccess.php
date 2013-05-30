<!DOCTYPE HTML>
<html>
<head>
<link rel="stylesheet" href="/widgets/neverMiss/css/main.css"/>
<style type="text/css">
body{background-color: transparent;}
	.cal-link{display: block; float: left; margin-right: 5px; width: 30px; height: 30px; border: 2px dashed transparent; text-decoration: none; background-repeat: no-repeat; background-position: center center; outline: none;}
	.cal-link:hover, .cal-link.selected{border-color: #ccc;}
	.cal-link.google{background-image: url('/widgets/neverMiss/imgs/google.png');}
	.cal-link.outlook{background-image: url('/widgets/neverMiss/imgs/outlook.png');}
	.cal-link.ical{background-image: url('/widgets/neverMiss/imgs/ical.png');}
	
	/*New Window*/
	.window-open{margin: 10px;}
	
	/*Bubble*/
	.speech-bubble {
	    position:relative;
	    width: 150px;
	    padding: 10px;
	    /*margin: 3em;*/
	    margin-top:25px;
	    background-color:#FFF;
	    color: #666;
	    font: normal 12px "Segoe UI", Arial, Sans-serif;
	    -moz-border-radius: 10px;
	    -webkit-border-radius: 10px;
	    border-radius: 10px;
	    border: 10px solid #ccc;
	}
	
	.speech-bubble:before,
	.speech-bubble:after {
	    content: "\0020";
	    display:block;
	    position:absolute;
	    top:-20px;
	    left:3px;
	    z-index:2;
	    width: 0;
	    height: 0;
	    overflow:hidden;
	    border: solid 20px transparent;
	    border-top: 0;
	    border-bottom-color:#FFF;
	}
	.speech-bubble:before {
	    top:-30px;
	    z-index:1;
	    border-bottom-color:#ccc;
	}
    
</style>
<title>Widget bubble</title>
</head>

<body>
<div id="widget-bubble" class="<?php echo ($isBubble) ? 'speech-bubble' : 'window-open'?>">
	<div class="clearfix">
		<a class="cal-link google" href="#" data-href="/cal/sub/id/<?php echo $calId?>/ct/google/ref/widget/cal.ics" data-desc="<?php echo __('Download to Google calendar');?>">&nbsp;</a>
		<a class="cal-link outlook" href="#" data-href="/cal/sub/id/<?php echo $calId?>/ct/outlook/ref/widget/cal.ics" data-desc="<?php echo __('Download to Outlook calendar');?>">&nbsp;</a>
		<a class="cal-link ical" href="#" data-href="/cal/sub/id/<?php echo $calId?>/ct/any/ref/widget/cal.ics" data-desc="<?php echo __('Copy iCal link');?>">&nbsp;</a>
	</div>
	
	<div id="desc-wrapper" style="display:none;">
		<p id="link-desc"></p>
		<a id="continue-btn" class="btn btn-success" href="#" target="_blank"><?php echo __('Continue');?></a>
	</div>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="/widgets/neverMiss/js/vendor/jquery-1.9.1.min.js"><\/script>');</script>
<script type="text/javascript">
var gSelected = null;
jQuery(document).ready(function(){
	jQuery('.cal-link').click(function(e){
		e.preventDefault();

		if (gSelected) gSelected.removeClass('selected');
		
		var el = jQuery(this);
		el.addClass('selected');
		gSelected = el;
		
		jQuery('#link-desc').text(el.attr('data-desc'));
		jQuery('#continue-btn').attr('href', el.attr('data-href'));
		
		jQuery('#desc-wrapper').show();
	});

	jQuery('#continue-btn').click(function(){
		window.setTimeout(function(){window.close();}, 1000);
	});
});
</script>
</body>
</html> 