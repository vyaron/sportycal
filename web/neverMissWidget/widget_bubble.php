<?php 
if (isset($_GET['calId'])){
	$calId = $_GET['calId'];
} else {
	echo 'ERROR';
	die();
}
?>
<!DOCTYPE HTML>
<html>
<head>
<link rel="stylesheet" href="css/main.css"/>
<style type="text/css">
body{background-color: transparent;}
	.cal-link{display: block; float: left; margin-right: 5px; width: 28px; height: 28px; border: 1px solid transparent; text-decoration: none; background-repeat: no-repeat; background-position: 0 0;}
	.cal-link:hover, .cal-link.selected{border-color: gray;}
	.cal-link.google{background-image: url('imgs/google.png');}
	.cal-link.outlook{background-image: url('imgs/outlook.png');}
	.cal-link.ical{background-image: url('imgs/ical.png');}
	
	#widget-bubble{width: 250px; padding: 10px; margin: 10px auto; border: 1px solid #ddd; -moz-border-radius: 7px;
    -webkit-border-radius: 7px;
    border-radius: 7px;}
</style>
<title>Widget bubble</title>
</head>

<body>
<div id="widget-bubble" class="speech-bubble1">
	<div class="clearfix">
		<a class="cal-link google" href="#" data-href="http://sportYcal.com/cal/sub/id/<?php echo $calId?>/ct/google/ref/widget/cal.ics" data-desc="Download to Google calendar">&nbsp;</a>
		<a class="cal-link outlook" href="#" data-href="http://sportYcal.com/cal/sub/id/<?php echo $calId?>/ct/outlook/ref/widget/cal.ics" data-desc="Download to Outlook calendar">&nbsp;</a>
		<a class="cal-link ical" href="#" data-href="http://sportYcal.com/cal/sub/id/<?php echo $calId?>/ct/any/ref/widget/cal.ics" data-desc="Copy iCal link">&nbsp;</a>
	</div>
	
	<div id="desc-wrapper" style="display:none;">
		<p id="link-desc"></p>
		<a id="continue-btn" class="btn btn-success" href="#" target="_blank">Continue</a>
	</div>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.9.1.min.js"><\/script>');</script>
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