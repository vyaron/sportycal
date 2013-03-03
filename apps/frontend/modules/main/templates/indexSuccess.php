
<?php
	slot('keywords', Category::getAsKeywords(null, $categories));
?>

<!-- 

<div class="rootCtgs" style="width:215px;">
	<a class="rootCtg" href="/category/775/euro-2012-calendars">
		<div class="rootCtgIcon" style="background-image: url(<?php echo url_for('/images/categories/EURO2012.png');?>);"></div>
		<h4>EURO 2012</h4>
	</a>
	
	<a class="rootCtg" href="/category/2280/london-2012-calendars">
		<div class="rootCtgIcon" style="background-image: url(<?php echo url_for('/images/categories/London2012.png');?>);"></div>
		<h4>London 2012</h4>
	</a>
	
	<div class="cb"></div>
</div>
-->
<br/><br/><br/>

<div id="ctgMainArea">
	<?php include_partial('global/categories',array('categories' => $categories, 'categoriesCount' => $categoriesCount)) ?>
</div>
<br/>
<a href="javascript:scroll(0,0)">
<?php echo __('Top');?>
</a>

<?php if ($sponsCategoriesCount) : ?>
<div style="width:650px; margin:0 auto">
	<hr style="height:1px;color:#333333;margin-top:20px;margin-bottom:10px;"/>
	<p class="tGrayHead tal b" style="margin-left: 55px;"><?php echo __('Sponsored Calendars');?></p>
	<div id="ctgSponsoredArea" class="mt20">
		<?php include_partial('global/categories',array('categories' => $sponsCategories, 'categoriesCount' => $sponsCategoriesCount, 'isSponseredCtg' => true)) ?>
	</div>
</div>
<?php endif ?>
<?php sfContext::getInstance()->getResponse()->addJavascript('homepage.js'); ?>
<?php //sfContext::getInstance()->getResponse()->addJavascript('detectTimezone.js'); ?>

