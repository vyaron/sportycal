<!-- header -->
<?php
	if (!isset($backRow)) $backRow = null;
	
	$selectedTab = 2;
	if (isset($_GET['today'])) $selectedTab = 3;
	
	include_partial('mobile/mainHeader',array('backRow' => $backRow, 'selectedTab' => $selectedTab));

	if (count($categorys)){
		$resCount = count($categorys);
	} else if (count($events)){
		$resCount = count($events);
	} else {
		$resCount = 0;
	}
?>
<?php if (count($events) && !UserUtils::getUserTZ()):?>
	<script type="text/javascript" src="/js/getUserTZ.js"></script>
<?php endif;?>

<?php include_partial('mobile/search',array('txtSearch' => $txtSearch, 'fromDate' => $fromDate, 'toDate' => $toDate)) ?>

<?php if ($txtSearch || $fromDate || $toDate) :?>

	<p id="searchDesc">Looked for <?php echo ($txtSearch) ? ': ' . '<span class="strong">' . $txtSearch . '</span>' : 'Sport Events'?>         	
	<?php if ($fromDate) : ?>
		From:         	
		<span class="strong"><?php echo GeneralUtils::getDateForDisplay($fromDate)?></span>
	<?php endif; ?>
	<?php if ($toDate) : ?>
		To:
	    <span class="strong"> <?php echo GeneralUtils::getDateForDisplay($toDate)?></span>
	<?php endif; ?> - 
	<?php echo ($resCount) ? 'Found <span class="strong"> ' . $resCount . '</span>' . ' results' : '<span class="strong">No results found, please try again</span>'?></p>
<?php endif ?>

<?php
   	if (count($categorys)) include_partial('mobile/categories',array('categories' => $categorys, 'showIcon' => true));
   	else if (count($events)) include_partial('mobile/eventsRow',array('events' => $events));
?>

