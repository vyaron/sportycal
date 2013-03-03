<?php 
	slot('title',sprintf('%s Calendars', $category->getName()));
	slot('keywords', Category::getAsKeywords($category, $subCtgs));

	if (empty($ctgLinks)) $ctgLinks = array();
?>

<div id="divCtgNav">
    <span class="ctgNav"><?php echo $category->getCategoryPathAsNavigation(ESC_RAW) ?></span>
    <?php echo image_tag($category->getImagePathSub(), 'class="imgCtgSub" alt="'.$category->getName().' Calendars"')?>


    <?php if (UserUtils::userISMasterOf($category)) :?>
        <?php include_partial('admin/ctgAdminLinks',array('category' => $category, 'ctgLinks' => $ctgLinks, 'parentCtg'=>$category->getParentCategory())) ?>
    <?php endif;?>

</div>


<?php if (UserUtils::getPartner() && UserUtils::getPartner()->isToto()) :?>
	<?php echo HebrewUtils::getDescForWinnerCtg()?>
<?php endif?>

<?php 
//Utils::pa($subCtgs);
?>

<?php include_partial('global/categoriesSub',array('categories' => $subCtgs, 'categoriesCount' => $subCtgsCount, 'fromFbApp' => $fromFbApp)) ?>

<?php if ($aggCal): ?>
	<br/><br/>
	<h2><?php echo __('Download Entire League Calendar');?></h2>
<?php endif ?>

<?php include_partial('global/cals',array('cals' => $cals, 'calsCount' => $calsCount, 'fromFbApp' => $fromFbApp)) ?>

<?php if (UserUtils::userISMasterOf($category)): ?>
	<div class="ctgSubActions hidden">
		<a class="ctgSubActionEdit" href="#" title="Edit"></a>
		<a class="ctgSubActionDelete" href="#" title="Delete"></a>
		<a class="ctgSubActionRevive" href="#" title="Rvive"></a>
		<a class="ctgSubActionAdd" href="#" title="Add"></a>
		<div class="cb"></div>
	</div>
	<?php sfContext::getInstance()->getResponse()->addStylesheet('ctgSubActions.css'); ?>
	<?php sfContext::getInstance()->getResponse()->addJavascript('ctgSubActions.js'); ?>
<?php endif;?>
