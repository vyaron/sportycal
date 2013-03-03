<?php 

?>

<?php sfContext::getInstance()->getResponse()->addJavascript('InfoBubble.class.js'); ?>

<div id="infoBubble" class="hidden">
	<div id="infoBubbleBgMobile"></div>
	<div id="infoBubblePadding">
		<a id="infoBubbleClose" href="#"><?php echo __('[x] Close');?></a>
		<div id="infoBubbleContent"></div>
		<div class="tar">
			<a class="tGray" href="http://sportYcal.com/l/sportwiser" target="_blank">
				<img src="/images/partner/sportwiser.png" alt="SportWiser.com" />
			</a>
		</div>
		
	</div>
</div>

<script type="text/javascript">
window.addEvent('domready', function(){
	var eventStatIcons = $$('.infoBubble');
	if (eventStatIcons){
		eventStatIcons.each(function(eventStatIcon){
			var ib = new InfoBubble(eventStatIcon, {
				mobile : <?php echo (isset($mobile) && $mobile) ? 'true' : 'false'?>
			});
		});
	}
});
</script>