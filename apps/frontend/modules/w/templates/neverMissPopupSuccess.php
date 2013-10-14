<!DOCTYPE HTML>
<html>
<head>
<link rel="stylesheet" href="/widgets/neverMiss/css/main.css"/>
<style type="text/css">
	body{background-color: transparent;}

	#widget-bubble{position: absolute; left: 0; width: 215px; color: #b2b2b2; font-size: 10px; margin:3px; box-shadow:0px 0px 3px rgba(0,0,0,0.5); background-color: #fff;}
	#widget-bubble.bubble-top{bottom: 0;}
	
	#widget-bubble.bottom-left, #widget-bubble.bottom-right{margin-top: 7px;}
	#widget-bubble.top-left, #widget-bubble.top-right{margin-bottom: 7px;}
	
	.arrow{width: 7px; height: 7px; background-repeat: no-repeat; background-image: url("/widgets/neverMiss/imgs/arrows.png"); position: absolute; z-index: 2;}
	
	.bottom-right .arrow, .bottom-left .arrow{top:-7px; background-position: 0 0;}
	.top-right .arrow, .top-left .arrow{bottom:-7px; background-position: 0 bottom;}
	.bottom-right .arrow, .top-right .arrow{left:14px;}
	.bottom-left .arrow, .top-left .arrow{right:14px;}
	
	.title-wrapper{background-color: #e5e5e5; height: 12px;}
	.title{color:#666666; font-size:10px; line-height:10px; margin-left:12px; float: left;}
	a.credit{font-size:7px; line-height:10px; margin-right:3px; text-decoration:none; color: #b2b2b2; float: right;}
	
	.cal-link{width: 71px; height: 40px; border-left: 1px solid #e5e5e5; color:#b2b2b2; font-size: 8px; display: block; float: left; text-decoration: none;}
	.cal-link:first-child{border-left: none;}
	
	.cal-link{background-position: 14px 20px; background-repeat: no-repeat;}
	
	.cal-link.google{background-image: url("/widgets/eventList/imgs/google.png");}
	.cal-link.ical{background-image: url("/widgets/eventList/imgs/ical.png");}
	.cal-link.outlook{background-image: url("/widgets/eventList/imgs/outlook.png");}
	
	.cal-link span{margin-left: 14px; margin-top: 5px; display: block;}
	
	/*New Window*/
	.window-open{margin: 10px;}
    
    /*RTL*/
    .rtl{direction: rtl; text-align: right;}
	.rtl .title{float: right; margin-left: 0; margin-right: 3px;}
	.rtl a.credit{float: left; margin-right: 0; margin-left: 14px;}
	.rtl .cal-link{direction: ltr; text-align: left;}
</style>
<title>Widget bubble</title>
</head>

<body>
<div id="widget-bubble" class="<?php echo ($isBubble ? 'speech-bubble ' . $bubblePos : 'window-open');?><?php echo ($isRTL ? ' rtl' : '');?>">
	<div class="arrow">&nbsp;</div>
	<div class="title-wrapper clearfix">
		<div class="title"><?php echo __('choose your calendar');?></div>
		<a class="credit" title="<?php echo __('powered by inevermiss.net');?>" href="<?php echo sfConfig::get('app_domain_full');?>" target="_blank"><?php echo __('powered by inevermiss.net');?></a>
	</div>
	<div class="clearfix">
		<a class="cal-link google" target="_blank" href="/cal/sub<?php echo $calId ? '/id/' . $calId : '';?><?php echo $ctgId ? '/ctgId/' . $ctgId : '';?><?php echo $ref ? '/ref/' . $ref : '';?>/ct/outlook/cal.ics"><span>Outlook</span></a>
		<a class="cal-link outlook" target="_blank" href="/cal/sub<?php echo $calId ? '/id/' . $calId : '';?><?php echo $ctgId ? '/ctgId/' . $ctgId : '';?><?php echo $ref ? '/ref/' . $ref : '';?>/ct/any/cal.ics"><span>iCal</span></a>
		<a class="cal-link ical" target="_blank" href="/cal/sub<?php echo $calId ? '/id/' . $calId : '';?><?php echo $ctgId ? '/ctgId/' . $ctgId : '';?><?php echo $ref ? '/ref/' . $ref : '';?>/ct/google/cal.ics"><span>G Calendar</span></a>
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