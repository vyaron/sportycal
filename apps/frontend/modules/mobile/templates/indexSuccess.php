<?php
	if (!isset($backRow)) $backRow = null;
	include_partial('mobile/mainHeader',array('backRow' => $backRow, 'selectedTab' => 1)) 
?>

<?php if (true || $user && $user->isMaster()):?>
<!-- Banners -->
<a id="getFriendsBirthdayCalendar" class="banner blueGradiantBg" href="<?php echo url_for('/main/friendsBirthday/src/mobile')?>"><span>Get Friends Birthday Calendar</span></a>	    
<?php endif;?>


<?php
	$addClass = 'itemRowBg3';
	$h2 = 'London 2012';
	$href = '/category/2280/london-2012-calendars';
	$iconClass = 'ctgIcon_2280';
	$infoIconClass = 'itemArrowRight';
	include_partial('mobile/itemRow', array('addClass' => $addClass, 'href' => $href, 'h2' => $h2, 'h3' => '&nbsp;', 'iconClass' => $iconClass, 'infoIconClass' => $infoIconClass));
?>


<?php include_partial('mobile/categories',array('categories' => $categories, 'categoriesCount' => $categoriesCount, 'showIcon' =>true)) ?>

<?php if ($sponsCategoriesCount) : ?>
<div style="width:600px;margin:auto">
	<hr style="height:1px;color:#333333;margin-top:20px;margin-bottom:10px;"/>
	<p class="tGrayHead tal b">Sponsored Calendars</p>
	<div id="ctgSponsoredArea">
		<?php include_partial('mobile/categories',array('categories' => $sponsCategories, 'categoriesCount' => $sponsCategoriesCount)) ?>
	</div>
</div>
<?php endif ?>

