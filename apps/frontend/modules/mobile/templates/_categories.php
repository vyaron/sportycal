<?php //Utils::pp($categories);?>

<?php 

if (!isset($showIcon)) $showIcon = false;


?>

<nav id="ctgs">
	<?php foreach ($categories as $ctg):?>
		<?php
			$addClass = 'itemRowBg1';
			$h2 = $ctg->getName();
			$h3 = ($ctg->getCalsCount()) ? '[' . $ctg->getCalsCount() . ' calendars]' : '[ coming soon ]';
			$href = url_for($ctg->getUrl(ESC_RAW));
			$iconClass = ($showIcon) ? 'ctgIcon_' . $ctg->getRootCategoryId() : null;
			$infoIconClass = 'itemArrowRight';
			include_partial('mobile/itemRow', array('addClass' => $addClass, 'href' => $href, 'h2' => $h2, 'h3' => $h3, 'iconClass' => $iconClass, 'infoIconClass' => $infoIconClass));
		?>
	<?php endforeach;?>
</nav>
