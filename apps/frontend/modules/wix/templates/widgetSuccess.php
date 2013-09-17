<?php 
use_stylesheet('/bundle/custom-scrollbar-plugin/css/jquery.mCustomScrollbar.css');
use_stylesheet('/css/wix/widget.css');
?>

<style type="text/css">
body{color: <?php echo $textColor;?>;}
#widget-wrapper{background-color: <?php echo $bgColor;?>; background-color: rgba(<?php echo implode(',', Utils::hex2rgb($bgColor));?>, <?php echo $bgOpacity;?>);}
.upcoming-wrapper h4, .upcoming-wrapper > ul > li, .upcoming-wrapper > ul > li > ul > li{border-color: <?php echo $lineColor;?>}
</style>

<div id="widget-wrapper">
	<div id="btns">
		<a class="btn btn-mini <?php echo $isReachedMaxSubscribers ? 'disabled' : '';?>" target="_blank" <?php if (!$isReachedMaxSubscribers):?>href="/cal/sub/id/<?php echo $calId?>/ct/outlook/ref/widget/cal.ics"<?php endif;?>><span>Outlook</span></a>
		<a class="btn btn-mini <?php echo $isReachedMaxSubscribers ? 'disabled' : '';?>" target="_blank" <?php if (!$isReachedMaxSubscribers):?>href="/cal/sub/id/<?php echo $calId?>/ct/any/ref/widget/cal.ics"<?php endif;?>><span>iCal</span></a>
		<a class="btn btn-mini <?php echo $isReachedMaxSubscribers ? 'disabled' : '';?>" target="_blank" <?php if (!$isReachedMaxSubscribers):?>href="/cal/sub/id/<?php echo $calId?>/ct/google/ref/widget/cal.ics"<?php endif;?>><span>G Calendar</span></a>
	</div>
	
	<?php if (count($dayKeyOrder)):?>
	<div class="upcoming-wrapper">
		<h4><?php echo __('Upcoming Events');?>:</h4>
		<ul>
			<?php foreach ($dayKeyOrder as $dayKey):?>
			<li>
				<h5><?php echo $dayKey;?></h5>
				<ul>
					<?php foreach ($dayKey2Events[$dayKey] as $event):?>
					<li title="<?php echo $event->getName();?>"><?php echo (!Event::isAllDay($event)) ? $event->getStartTimeForDisplay() . ' ' : '';?><?php echo $event->getName();?></li>
					<?php endforeach;?>
				</ul>
			</li>
			<?php endforeach;?>
		</ul>
	</div>
	<?php endif;?>
</div>

<?php 
use_javascript('/bundle/custom-scrollbar-plugin/js/minified/jquery.mousewheel.min.js');
use_javascript('/bundle/custom-scrollbar-plugin/js/minified/jquery.mCustomScrollbar.min.js');
use_javascript('/js/wix/widget.js');
?>