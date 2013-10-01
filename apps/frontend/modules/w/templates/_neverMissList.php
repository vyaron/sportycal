<?php 
use_stylesheet('http://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700');
use_stylesheet('/bundle/custom-scrollbar-plugin/css/jquery.mCustomScrollbar.css');
use_stylesheet('/widgets/eventList/css/main.css');

$ref = Utils::iff($ref, null);
$calId = Utils::iff($calId, null);
$ctgId = Utils::iff($ctgId, null);
$language = Utils::iff($language, null);
$isRTL = Utils::iff($isRTL, null);
$isDark = Utils::iff($isDark, null);
$isMobile = Utils::iff($isMobile, null);

$lineColor = Utils::iff($lineColor, null);
$textColor = Utils::iff($textColor, null);
$bgColor = Utils::iff($bgColor, null);
$bgOpacity = Utils::iff($bgOpacity, null);
?>
<style type="text/css">
	<?php if ($isDark):?>
	body, #footer{background-color: #1a1a1a;}
	body{color: #cbcbcb;}
	#btns-title, .cal-btn{color: #666;}
	.cal-btn:hover{color: #fff; background-color: #000;}
	#btns-title{background-color: #333;}
	.cal-btn{border-left-color: #333;}
	.mCSB_scrollTools .mCSB_draggerRail{background: #333;}
	<?php endif;?>
	
	<?php if ($isRTL):?>
	body{direction: rtl; text-align: right;}
	h1, #events li{margin-right: 0; margin-left: 30px;}
	h1, #events li, #btns-title{padding-left: 0; padding-right: 16px;}
	.mCustomScrollBox > .mCSB_scrollTools{right: auto; left: 2px;}
	#events li .date{float: right;}
	#events li .time{float: left;}
	.cal-btn span{margin-left: 0; margin-right: 16px; background-position: right 20px;}
	<?php endif;?>
	
	<?php if ($lineColor):?>
	#events li, h1{border-bottom-color: <?php echo $lineColor;?>;}
	.mCSB_scrollTools .mCSB_dragger .mCSB_dragger_bar, .mCSB_scrollTools .mCSB_dragger:hover .mCSB_dragger_bar{background-color: <?php echo $lineColor;?>;}
	<?php endif;?>
	
	<?php if ($textColor):?>
	body{color: <?php echo $textColor;?>;}
	.cal-btn, .cal-btn:hover{color: <?php echo $textColor;?>;}
	<?php endif;?>
	
	<?php if ($bgColor):?>
	body, #footer, .cal-btn:hover{background-color: <?php echo $bgColor;?>;}
	<?php endif;?>
	
	<?php if ($bgOpacity):?>
	body, #footer, .cal-btn:hover{background-color: rgba(<?php echo implode(',', Utils::hex2rgb($bgColor));?>, <?php echo $bgOpacity;?>);}
	<?php endif;?>
</style>


<div id="container">
	<h1><?php echo __('My Events');?></h1>
	
	<ul id="events" style="height: 100px;">
		<?php foreach ($events as $event):?>
		<li>
			<div class="clearfix">
				<div class="date"><?php echo date('y.m.d', strtotime($event->getStartsAt()))?></div>
				<div class="time"><?php echo $event->isAllDay() ? '' : $event->getStartTimeForDisplay();?></div>
			</div>
			<div class="name"><?php echo $event->getName();?></div>
		</li>
		<?php endforeach;?>
	</ul>

	<div id="footer">
		<div id="btns-title"><?php echo $isMobile ? '&nbsp;' : __('choose your calendar');?></div>
		<div class="clearfix <?php echo $isReachedMaxSubscribers ? 'disabled' : '';?>" <?php  echo $isReachedMaxSubscribers ? 'title="' .  __('Reached subscriptions limit') . '"' : '';?>>
			<?php if ($isMobile) :?>
				<a class="cal-btn mobile" target="_blank" <?php if (!$isReachedMaxSubscribers):?>href="/cal/sub<?php echo $calId ? '/id/' . $calId : '';?><?php echo $ctgId ? '/ctgId/' . $ctgId : '';?>/ct/mobile<?php echo $ref ? '/ref/' . $ctgId : '';?>/cal.ics"<?php endif;?>><span><?php echo __('DOWNLOAD TO CALENDAR');?></span></a>
			<?php else:?>
				<a class="cal-btn outlook" target="_blank" <?php if (!$isReachedMaxSubscribers):?>href="/cal/sub<?php echo $calId ? '/id/' . $calId : '';?><?php echo $ctgId ? '/ctgId/' . $ctgId : '';?>/ct/outlook<?php echo $ref ? '/ref/' . $ctgId : '';?>/cal.ics"<?php endif;?>><span><?php echo __('Outlook')?></span></a>
				<a class="cal-btn ical" target="_blank" <?php if (!$isReachedMaxSubscribers):?>href="/cal/sub/id/<?php echo $calId?>/ct/any<?php echo $ref ? '/ref/' . $ctgId : '';?>/cal.ics"<?php endif;?>><span><?php echo __('iCal');?></span></a>
				<a class="cal-btn google" target="_blank" <?php if (!$isReachedMaxSubscribers):?>href="/cal/sub/id/<?php echo $calId?>/ct/google<?php echo $ref ? '/ref/' . $ctgId : '';?>/cal.ics"<?php endif;?>><span><?php echo __('Google');?></span></a>
			<?php endif;?>
		</div>
	</div>
</div>

<?php 
use_javascript('/bundle/custom-scrollbar-plugin/js/minified/jquery.mousewheel.min.js');
use_javascript('/bundle/custom-scrollbar-plugin/js/minified/jquery.mCustomScrollbar.min.js');
use_javascript('/widgets/eventList/js/main.js');
?>