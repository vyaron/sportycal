<?php 
if (!isset($isSponseredCtg)) $isSponseredCtg = false;
?>
<div class="rootCtgs">
	<?php foreach ($categories as $category):?>
		<?php $calsCount = $category->getCalsCount() ?>
		<?php if ($calsCount || UserUtils::userISMasterOf($category)):?>
			<a class="rootCtg rootCtgAnimate" href="<?php echo url_for($category->getUrl(ESC_RAW)) ?>">
				<div class="rootCtgIcon" style="background-image: url(<?php echo ($isSponseredCtg) ? $category->getImagePath() : $category->getImagePathSub(1);?>);"></div>
				<h4><?php echo $category->getName(ESC_RAW); ?></h4>
				<h5>
				<?php if ($calsCount || UserUtils::userISMasterOf($category)) : ?>
					[ <?php echo $calsCount . " " . __('calendars') ?> ]
				<?php else: ?>
					[ <?php echo __('coming soon');?> ]
				<?php endif; ?>
				</h5>
			</a>
		<?php endif;?>
	<?php endforeach;?>

	<div class="cb"></div>
</div>
