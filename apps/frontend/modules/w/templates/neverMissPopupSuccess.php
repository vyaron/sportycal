<!DOCTYPE HTML>
<html>
<head>
<link rel="stylesheet" href="/widgets/neverMiss/css/main.css"/>
<style type="text/css">
	body{background-color: transparent;}
	#widget-bubble{position: absolute; left: 0; min-width: 300px; color: #666px; font-size: 10px;}
	#widget-bubble.bubble-top{bottom: 0;}
	
	.cal-link{width: 100px; height: 31px; line-height:31px; font-size:12px; float: left; display: block; margin-right: 7px; cursor: pointer; background: 0 bottom no-repeat url("/widgets/neverMiss/imgs/cal-link.png"); text-decoration: none; color: #333;}
	.cal-link:hover{color: #000;}
	.cal-link span{padding-left: 33px; background-position: 7px center; background-repeat: no-repeat; background-image: url("/widgets/neverMiss/imgs/cal-link.png"); display: block;}
	.cal-link.outlook span{background-position: 0 0;}
	.cal-link.ical span{background-position: 0 -31px;}
	.cal-link.google span{background-position: 0 -62px;}
	
	/*New Window*/
	.window-open{margin: 10px;}
	
	/*Bubble*/
	.speech-bubble {
	    position:relative;
	    width: 335px;
	    padding: 7px;
	    
	    background-color:#f2f2f2;
	    font: normal 12px "Segoe UI", Arial, Sans-serif;
	    -moz-border-radius: 7px;
	    -webkit-border-radius: 7px;
	    border-radius: 7px;
	    border: 1px solid #b3b3b3;
	    border-bottom-width: 2px; 
	    border-right-width: 2px; 
	    box-shadow:1px 1px 3px rgba(0,0,0,0.5); 
	}
	
	.speech-bubble.bottom-right, .speech-bubble.bottom-left{margin-top:25px;}
	.speech-bubble.top-right, .speech-bubble.top-left{margin-bottom:25px; position: absolute; bottom: 0;}

	.speech-bubble:before,
	.speech-bubble:after {
	    content: "\0020";
	    display:block;
	    position:absolute;
	    
	    width: 0;
	    height: 0;
	    overflow:hidden;
	    border: solid 4px transparent;
	}
	
	.speech-bubble:before {z-index:1;}
	.speech-bubble:after {z-index:2;}
	
	.speech-bubble.bottom-right:before, .speech-bubble.bottom-right:after,
	.speech-bubble.top-right:before, .speech-bubble.top-right:after{left:20px;}
	
	.speech-bubble.bottom-left:before, .speech-bubble.bottom-left:after,
	.speech-bubble.top-left:before, .speech-bubble.top-left:after{right:20px;}
	
	.speech-bubble.bottom-right:before, .speech-bubble.bottom-right:after, 
	.speech-bubble.bottom-left:before, .speech-bubble.bottom-left:after{
		border-top: 0;
	    border-bottom-color:#f2f2f2;
	    top:-4px;
	}
	
	.speech-bubble.top-right:before, .speech-bubble.top-right:after, 
	.speech-bubble.top-left:before, .speech-bubble.top-left:after{
		border-bottom: 0;
	    border-top-color:#f2f2f2;
	    bottom:-4px;
	}
	
	.speech-bubble.bottom-right:before, .speech-bubble.bottom-left:before{
		border-top: 0;
	    border-bottom-color:#b3b3b3;
	    top:-5px;
	}
	
	.speech-bubble.top-right:before, .speech-bubble.top-left:before{
		border-bottom: 0;
	    border-top-color:#b3b3b3;
	    bottom:-5px;
	}
    
    #mailinglist-form-loading, #mailinglist-form-success, #mailinglist-form-error{font-size: 14px; margin: 10px 0;}
    #mailinglist-form-loading{padding-left: 25px; background: url("/images/neverMiss/icons/ajax-loader-black.gif") no-repeat 0 center;}
    #mailinglist-form-success{color: green;}
    #mailinglist-form-error{color: red;}
    
    #close-btn{font-size: 7px; text-align:center; color: 666px; position: absolute; right: 0px; top: 0px; line-height:20px; width:20px; display:block; text-decoration: none; outline: none;}
</style>
<title>Widget bubble</title>
</head>

<body>
<div id="widget-bubble" class="<?php echo ($isBubble ? 'speech-bubble ' . $bubblePos : 'window-open');?>">
	<a id="close-btn" href="#" title="<?php echo __('Click here to close');?>">x</a>
	
	<p><?php echo __('Please click the calendar of your choice');?></p>
	<div class="clearfix">
		<a class="cal-link google" target="_blank" href="/cal/sub/id/<?php echo $calId?>/ct/google/ref/widget/cal.ics"><span>Outlook</span></a>
		<a class="cal-link outlook" target="_blank" href="/cal/sub/id/<?php echo $calId?>/ct/outlook/ref/widget/cal.ics"><span>iCal</span></a>
		<a class="cal-link ical" target="_blank" href="/cal/sub/id/<?php echo $calId?>/ct/any/ref/widget/cal.ics"><span>G Calendar</span></a>
		<!-- <a class="cal-link email" href="#">&nbsp;</a> -->
	</div>
	
	<div id="email-desc-wrapper" style="display:none;">
		<div id="mailinglist-form-loading">Loading...</div>
		<div id="mailinglist-form-success" style="display:none;">Success</div>
		<div id="mailinglist-form-error" style="display:none;">Error</div>
		<form id="mailinglist-form" method="POST" action="/mailinglist/subscribe/calId/<?php echo $calId;?>">
			<input id="mailinglist-tz" type="hidden" name="tz" value="0"/>
			<label>
				<?php echo __('Full name')?>:&nbsp;
				<input id="mailinglist-full-name" type="text" name="full_name" placeholder="Eliezer Ben-Yehuda"/>
			</label>
			<label>
				<?php echo __('Email')?>:&nbsp;
				<input id="mailinglist-email" type="email" name="email" placeholder="eliezer@gmail.com"/>
			</label>
			
			<input type="submit" class="btn btn-success" value="<?php echo __('Send');?>"/>
		</form>
	</div>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="/widgets/neverMiss/js/vendor/jquery-1.9.1.min.js"><\/script>');</script>
<script type="text/javascript">
var gSelected = null;

/*
function validateFullName(name) { 
    var re = /^[a-zA-Z ]+$/;
    return re.test(name);
}

function validateEmail(email) { 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
} 



function isMailinglistValid(){
	var fullName = jQuery('#mailinglist-full-name');
	var email = jQuery('#mailinglist-email');

	fullName.removeClass('error');
	email.removeClass('error');
	
	var isValid = true;

	if (! validateFullName(fullName.val())){
		fullName.addClass('error');
		isValid = false;
	}
	
	if (! validateEmail(email.val())){
		email.addClass('error');
		isValid = false;
	}

	jQuery('#mailinglist-form .error').first().focus();
	
	return isValid;
}


function prepareMailinglistForm(){
	jQuery('#mailinglist-full-name, #mailinglist-email').keyup(function(){jQuery(this).removeClass('error');});
	
	jQuery('#mailinglist-form').submit(function(e){
		e.preventDefault();

		if (isMailinglistValid()){
			var form = jQuery(this);
			jQuery.ajax({
				'url' : form.attr('action'),
				'type' : form.attr('method'),
				'dataType' : 'json',
				'data' : form.serialize(),
				'beforeSend' : function(){
					jQuery('#mailinglist-form-loading').show();
					form.hide();
				}
			}).done(function(res){
				jQuery('#mailinglist-form-loading').hide();
				if (res.success) jQuery('#mailinglist-form-success').show();
				else jQuery('#mailinglist-form-error').show();
			});
		}
	});
}


function showCalLink(e){
	e.preventDefault();

	if (gSelected) gSelected.removeClass('selected');
	
	var el = jQuery(this);
	el.addClass('selected');
	gSelected = el;

	var emailDescWrapper = jQuery('#email-desc-wrapper');
	var descWrapper = jQuery('#desc-wrapper');
	
	emailDescWrapper.hide();
	descWrapper.hide();
	
	if (el.hasClass('email')){
		jQuery('#mailinglist-form').show();
		jQuery('#mailinglist-form-loading').hide();
		jQuery('#mailinglist-form-success').hide();
		jQuery('#mailinglist-form-error').hide();

		jQuery('#mailinglist-full-name, #mailinglist-email').val('');

		emailDescWrapper.show();
	} else {
		jQuery('#link-desc').text(el.attr('data-desc'));
		jQuery('#continue-btn').attr('href', el.attr('data-href'));
		
		descWrapper.show();
	}
}


function setCalLinksEvents(){
	jQuery('.cal-link').click(showCalLink);
	jQuery('.cal-link').mouseenter(showCalLink);

	jQuery('#continue-btn').click(function(){
		window.setTimeout(function(){window.close();}, 1000);
	});
}


function getUserGmtMins(){
	var d = new Date();
	var jan = new Date(d.getFullYear(), 0, 1);
	var jul = new Date(d.getFullYear(), 6, 1);
	return  -Math.max(jan.getTimezoneOffset(), jul.getTimezoneOffset());
}

function setTimezone(){
	jQuery('#mailinglist-tz').val(getUserGmtMins());
}
*/
jQuery(document).ready(function(){
	var popupId = '<?php echo $popupId;?>';
	jQuery('#widget-bubble').hover(function(e){
		if (parent.postMessage) parent.postMessage(popupId + '@open', "*");
	}, function(){
		if (parent.postMessage) parent.postMessage(popupId + '@close', "*");
	});

	jQuery('#close-btn').click(function(e){
		e.preventDefault();
		
		if (parent.postMessage) parent.postMessage(popupId + '@force_close', "*");
		else if (window.parent != window) window.close();
	});
	
	//prepareMailinglistForm();
	//setCalLinksEvents();
	//setTimezone();
});

</script>
</body>
</html> 