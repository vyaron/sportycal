<nav id="cals">
<?php foreach ($cals as $cal):?>
	<?php
		$addClass = 'itemRowBg1';
		$h2 = $cal->getName();
		$h3 = ($cal->getNumEvents()) ? $cal->getNumEvents() . ' Events' : '&nbsp;';
		$href = url_for($cal->getUrl(ESC_RAW));
		$iconClass = ($cal->isAggregated()) ? 'calIconAll' : 'calIconOne';
		$infoIconClass = 'itemArrowRight';
		include_partial('mobile/itemRow', array('addClass' => $addClass, 'href' => $href, 'h2' => $h2, 'h3' => $h3, 'iconClass' => $iconClass, 'infoIconClass' => $infoIconClass));
	?>
<?php endforeach;?>
</nav>