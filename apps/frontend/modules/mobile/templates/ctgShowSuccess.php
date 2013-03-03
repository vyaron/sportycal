<?php 
	slot('title', sprintf('%s Calendars', $category->getName()));
	slot('keywords', $category->getCategoryPathAsKeywords());
?>

<!-- header -->
<?php
	if (!isset($backRow)) $backRow = null;
	include_partial('mobile/mainHeader',array('backRow' => $backRow, 'selectedTab' => 0)) 
?>

<?php if ($calsCount == 1 && $subCtgsCount): ?>
	<h2 id="downloadEntireLeague">Download Entire League Calendar</h2>
<?php endif ?>
<?php include_partial('mobile/cals',array('cals' => $cals, 'calsCount' => $calsCount)) ?>

<h1 id="mainTitle"><?php echo $category->getName();?></h1>
<?php include_partial('mobile/categories',array('categories' => $subCtgs, 'categoriesCount' => $subCtgsCount)) ?>


