<!DOCTYPE HTML>
<html>
<head>
<title>Never Miss Widget</title>
</head>
<link rel="stylesheet" href="/widgets/neverMiss/css/main.css"/>
<style>
#never-miss-btn, #never-miss-mobile-btn, #never-miss-disabled-btn{background: url('/widgets/neverMiss/imgs/btn.png') no-repeat 0 0; display: block; height:36px; width:159px; text-decoration: none;}
#never-miss-mobile-btn{background: url('/widgets/neverMiss/imgs/mobile-btn.png') no-repeat 0 0;}

#never-miss-disabled-btn{cursor: not-allowed; opacity:0.3; filter:alpha(opacity=30);}
</style>
<body>
<?php if ($isReachedMaxSubscribers):?>
<a id="never-miss-disabled-btn" title="<?php echo __('You have the maximum number Subscriptions');?>">&nbsp</a>
<?php elseif ($isMobile):?>
<a id="never-miss-mobile-btn" target="<?php echo Utils::clientIsAndroid() ? '_blank' : 'attachment';?>" href="/cal/sub/id/<?php echo $calId;?>/ct/mobile/ref/widget/cal.ics">&nbsp;</a>
<iframe name="attachment" style="width: 1px; height: 1px; border: 0;"></iframe>
<?php else:?>
<a id="never-miss-btn" href="<?php echo url_for('w/neverMissPopup/?calid=' . $calId . ($language ? ('&language=' . $language) : ''))?>">&nbsp;</a>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="/widgets/neverMiss/js/vendor/jquery-1.9.1.min.js"><\/script>');</script>
<script type="text/javascript">
var gWindow = null;
jQuery(document).ready(function(){
	var popupId = '<?php echo $popupId;?>';
	jQuery('#never-miss-btn').hover(function(e){
		if (parent.postMessage) parent.postMessage(popupId + '@open', "*");
	}, function(){
		if (parent.postMessage) parent.postMessage(popupId + '@close', "*");
	});
	
	jQuery('#never-miss-btn').click(function(e){
		e.preventDefault();

		if (parent.postMessage) {
			parent.postMessage(popupId + '@toggle', "*");
		} else {
			//Old Browser open in new window
			if (gWindow) {
				gWindow.close();
				gWindow = null;
			} else {
				gWindow = window.open(this.href, "Never Miss", "directories=0,titlebar=0,toolbar=0,location=0,status=0,menubar=0,scrollbars=no,resizable=no,width=350,height=170");
				gWindow.focus();
			}
		}
	});
});
</script>
<?php endif;?>
</body>
</html>