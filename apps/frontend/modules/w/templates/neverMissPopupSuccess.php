<!DOCTYPE HTML>
<html>
<head>
<link rel="stylesheet" href="/widgets/neverMiss/css/main.css"/>
<style type="text/css">
body{background-color: transparent;}
	#widget-bubble.bubble-top{position: absolute; left: 0; bottom: 0;}
	
	.cal-link{display: block; float: left; margin-right: 5px; width: 30px; height: 30px; border: 2px dashed transparent; text-decoration: none; background-repeat: no-repeat; background-position: center center; outline: none;}
	.cal-link:hover, .cal-link.selected{border-color: #ccc;}
	.cal-link.google{background-image: url('/widgets/neverMiss/imgs/google.png');}
	.cal-link.outlook{background-image: url('/widgets/neverMiss/imgs/outlook.png');}
	.cal-link.ical{background-image: url('/widgets/neverMiss/imgs/ical.png');}
	.cal-link.email{background-image: url('/widgets/neverMiss/imgs/email.png');}
	
	/*New Window*/
	.window-open{margin: 10px;}
	
	/*Bubble*/
	.speech-bubble {
	    position:relative;
	    width: 170px;
	    padding: 10px;
	    /*margin: 3em;*/
	    
	    background-color:#FFF;
	    color: #666;
	    font: normal 12px "Segoe UI", Arial, Sans-serif;
	    -moz-border-radius: 10px;
	    -webkit-border-radius: 10px;
	    border-radius: 10px;
	    border: 10px solid #ccc;
	}
	
	.speech-bubble.bubble-top{margin-bottom: 25px;}
	.speech-bubble.bubble-buttom{margin-top:25px;}

	.speech-bubble:before,
	.speech-bubble:after {
	    content: "\0020";
	    display:block;
	    position:absolute;
	    left:3px;
	    z-index:2;
	    width: 0;
	    height: 0;
	    overflow:hidden;
	    border: solid 20px transparent;
	}
	
	.speech-bubble.bubble-top:before, .speech-bubble.bubble-top:after{
	    border-bottom: 0;
	    border-top-color:#fff;
	    bottom:-20px;
	}
	
	.speech-bubble.bubble-buttom:before, .speech-bubble.bubble-buttom:after{
	    border-top: 0;
	    border-bottom-color:#FFF;
	    top:-20px;
	}
	
	.speech-bubble:before {
	 	z-index:1;
	}
	
	.speech-bubble.bubble-top:before{
		bottom:-30px;
	    border-top-color:#ccc;
	}
	
	.speech-bubble.bubble-buttom:before{
	    top:-30px;
	    border-bottom-color:#ccc;
	}

    
    #mailinglist-form-loading, #mailinglist-form-success, #mailinglist-form-error{font-size: 14px; margin: 10px 0;}
    #mailinglist-form-loading{padding-left: 25px; background: url("/images/neverMiss/icons/ajax-loader-black.gif") no-repeat 0 center;}
    #mailinglist-form-success{color: green;}
    #mailinglist-form-error{color: red;}
</style>
<title>Widget bubble</title>
</head>

<body>
<div id="widget-bubble" class="<?php echo ($isBubble) ? 'speech-bubble' : 'window-open'?> <?php echo ($bubbleTop) ? 'bubble-top' : 'bubble-buttom';?>">
	<div class="clearfix">
		<a class="cal-link google" href="#" data-href="/cal/sub/id/<?php echo $calId?>/ct/google/ref/widget/cal.ics" data-desc="<?php echo __('Download to Google calendar');?>">&nbsp;</a>
		<a class="cal-link outlook" href="#" data-href="/cal/sub/id/<?php echo $calId?>/ct/outlook/ref/widget/cal.ics" data-desc="<?php echo __('Download to Outlook calendar');?>">&nbsp;</a>
		<a class="cal-link ical" href="#" data-href="/cal/sub/id/<?php echo $calId?>/ct/any/ref/widget/cal.ics" data-desc="<?php echo __('Copy iCal link');?>">&nbsp;</a>
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
	
	<div id="desc-wrapper" style="display:none;">
		<p id="link-desc"></p>
		<a id="continue-btn" class="btn btn-success" href="#" target="_blank"><?php echo __('Continue');?></a>
	</div>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="/widgets/neverMiss/js/vendor/jquery-1.9.1.min.js"><\/script>');</script>
<script type="text/javascript">
var gSelected = null;

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

jQuery(document).ready(function(){
	var popupId = '<?php echo $popupId;?>';
	jQuery('#widget-bubble').hover(function(e){
		if (parent.postMessage) parent.postMessage(popupId + '@open', "*");
	}, function(){
		if (parent.postMessage) parent.postMessage(popupId + '@close', "*");
	});
	
	prepareMailinglistForm();
	setCalLinksEvents();
	setTimezone();
});

</script>
</body>
</html> 