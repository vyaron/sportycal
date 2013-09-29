<?php 
$href = url_for('w/neverMissPopup/?calid=' . $calId . ($language ? ('&language=' . $language) : ''));

if ($isReachedMaxSubscribers) $href = null;
else if ($isMobile) $href = '/cal/sub' . ($calId ? '/id/' . $calId : '') . ($ctgId ? '/ctgId/' . $ctgId : '') . ($ref ? '/ref/' . $ref : '') . '/ct/mobile/cal.ics';


$target = '_self';
if ($isMobile) $target = Utils::clientIsAndroid() ? '_blank' : 'attachment';
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Never Miss Widget</title>
</head>
<link rel="stylesheet" href="/widgets/neverMiss/css/main.css"/>
<style>
/*
.never-miss-btn, .never-miss-mobile-btn, .never-miss-disabled-btn{background: url('/widgets/neverMiss/imgs/btn.png') no-repeat 0 0; display: block; height:36px; width:159px; text-decoration: none;}
.never-miss-mobile-btn{background: url('/widgets/neverMiss/imgs/mobile-btn.png') no-repeat 0 0;}
.never-miss-disabled-btn{cursor: not-allowed; opacity:0.3; filter:alpha(opacity=30);}
*/

.never-miss-btn{background: url('/widgets/neverMiss/imgs/btn.png') no-repeat 0 0; display: block; height:46px; width:164px; text-decoration: none;}
.never-miss-btn.dark{background-image:  url('/widgets/neverMiss/imgs/btn-dark.png');}

.never-miss-btn.small{background-image: url('/widgets/neverMiss/imgs/btn-small.png'); width: 82px; height: 23px;}
.never-miss-btn.dark.small{background-image:  url('/widgets/neverMiss/imgs/btn-dark-small.png');}

.never-miss-btn-txt, .never-miss-btn-small-txt{color: #fff;}
.never-miss-btn-txt{font-size: 13px; line-height: 14px; margin-left: 55px; margin-top:9px;}
.never-miss-btn-small-txt{font-size: 8px; line-height: 23px; margin-left: 26px; margin-top:0px;}

.never-miss-btn.small .never-miss-btn-small-txt, .never-miss-btn .never-miss-btn-txt{display: inline-block;}
.never-miss-btn .never-miss-btn-small-txt, .never-miss-btn.small .never-miss-btn-txt, .never-miss-btn.only_icon .never-miss-btn-txt, .never-miss-btn.only_icon .never-miss-btn-small-txt{display: none;}

/*
.never-miss-btn.mobile{background-image: url('/widgets/neverMiss/imgs/mobile-btn.png');}
.never-miss-btn.mobile.dark{background-image: url('/widgets/neverMiss/imgs/mobile-btn-dark.png');}

.never-miss-btn.mobile.small{background-image: url('/widgets/neverMiss/imgs/mobile-btn-small.png');}
.never-miss-btn.mobile.dark.small{background-image: url('/widgets/neverMiss/imgs/mobile-btn-dark-small.png');}
*/

.never-miss-btn.only_icon{background-image: url('/widgets/neverMiss/imgs/only-icon.png'); width: 46px; height: 46px;}
.never-miss-btn.only_icon.small{background-image: url('/widgets/neverMiss/imgs/only-icon-small.png'); width: 23px; height: 23px;}
.never-miss-btn.only_icon.dark{background-image: url('/widgets/neverMiss/imgs/only-icon-dark.png');}
.never-miss-btn.only_icon.dark.small{background-image: url('/widgets/neverMiss/imgs/only-icon-dark-small.png');}

.never-miss-btn.disabled{cursor: not-allowed; opacity:0.3; filter:alpha(opacity=30);}

.upcoming-wrapper{font-size: 12px; padding: 10px; background-color: #fff;}
.upcoming-wrapper h4, .upcoming-wrapper h5{margin: 0;}
.upcoming-wrapper h4{font-size: 14px; border-bottom: 3px solid #FFC229; padding-bottom: 7px;}
.upcoming-wrapper h5{font-size: 13px;}
.upcoming-wrapper ul{margin: 0; padding: 0; list-style: none;}
.upcoming-wrapper > ul > li{border-top: 2px solid #FFC229; padding: 10px 0;}
.upcoming-wrapper > ul > li:FIRST-CHILD {border-top:none;}

.rtl{text-align: right; direction: rtl;}
.rtl .never-miss-btn-txt{font-size: 17px; margin-left: 0; margin-right: 12px; margin-top: 15px;}
.rtl .never-miss-btn-small-txt{font-size: 14px; margin-left: 0; margin-right: 10px;}
</style>
<body class="<?php echo ($isRTL ? 'rtl' : '');?>">

<?php if ($src):?>
<a class="never-miss-btn<?php echo ($isReachedMaxSubscribers) ? ' disabled' : '';?>" <?php echo $href ? 'href="' . $href . '"' : '';?> target="<?php echo $target;?>"<?php echo ($isReachedMaxSubscribers) ? ' title="' .  __('Reached subscriptions limit') . '"' : '';?> style="display: block; background: 0 0 no-repeat url('<?php echo $src?>'); width: <?php echo $width;?>px; height: <?php echo $height;?>px;"></a>
<?php else:?>
<a class="never-miss-btn<?php echo ($isReachedMaxSubscribers) ? ' disabled' : '';?><?php echo ($isMobile) ? ' mobile' : '';?><?php echo ($btnStyle) ? " $btnStyle" : '';?><?php echo ($btnSize) ? " $btnSize" : '';?><?php echo ($color) ? " $color" : '';?>" <?php echo $href ? 'href="' . $href . '"' : '';?> target="<?php echo $target;?>"<?php echo ($isReachedMaxSubscribers) ? ' title="' .  __('Reached subscriptions limit') . '"' : '';?>>
	<span class="never-miss-btn-small-txt"><?php echo __('DOWNLOAD');?></span>
	<span class="never-miss-btn-txt"><?php echo __('DOWNLOAD TO CALENDAR');?></span>
</a>
<?php endif;?>

<?php if (count($dayKeyOrder)):?>
<div class="upcoming-wrapper">
	<h4><?php echo __('Upcoming Events');?>:</h4>
	<ul>
		<?php foreach ($dayKeyOrder as $dayKey):?>
		<li>
			<h5><?php echo $dayKey;?></h5>
			<ul>
				<?php foreach ($dayKey2Events[$dayKey] as $event):?>
				<li title="<?php echo $event->getName();?>"><?php echo (!Event::isAllDay($event)) ? $event->getStartTimeForDisplay() . ' ' : '';?><?php echo Utils::substr($event->getName(), 10);?></li>
				<?php endforeach;?>
			</ul>
		</li>
		<?php endforeach;?>
	</ul>
</div>
<?php endif;?>

<?php if($isMobile):?>
<iframe name="attachment" style="width: 1px; height: 1px; border: 0;"></iframe>
<?php elseif (!$isReachedMaxSubscribers && !$isMobile):?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="/widgets/neverMiss/js/vendor/jquery-1.9.1.min.js"><\/script>');</script>
<script type="text/javascript">
var gWindow = null;
jQuery(document).ready(function(){
	var popupId = '<?php echo $popupId;?>';
	jQuery('.never-miss-btn').hover(function(e){
		if (parent.postMessage) parent.postMessage(popupId + '@open', "*");
	}, function(){
		if (parent.postMessage) parent.postMessage(popupId + '@close', "*");
	});
	
	jQuery('.never-miss-btn').click(function(e){
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