<?php if (!isset($events)) $events = array(); ?>
<nav id="events">
	<?php foreach ($events as $event):?>
		<?php 
			$cal = $event->getCal();
			
			$addClass = 'itemRowBg2';
			$h2 = $event->getName();
			$h3 = $event->getDateForDisplay();
			
			if ($cal) {
				$iconClass = 'ctgIcon_' . $cal->getRootCategoryId();
				$a = array('html' =>  $cal->getName(), 'href' => $cal->getUrl(ESC_RAW));
			} else {
				$iconClass = null;
				$a = null;
			}

			include_partial('mobile/itemRow', array('addClass' => $addClass, 'h2' => $h2, 'h3' => $h3, 'a' => $a, 'iconClass' => $iconClass));
		?>
	<?php endforeach;?>
</nav>
