<?php foreach ($events as $event): ?>
	<?php $eLocation = $event->getLocation()?>

	<div class="eventRow">
		<div class="fl">
			<?php echo $event->getName() . ' (' . $event->getDateForDisplay() . ' at ' . $eLocation . ')'?>
			<?php if ($isMasterOf):?>
				<?php echo $event->getInfoBubble('T', ESC_RAW) ?>
				<?php echo $event->getInfoBubble('H', ESC_RAW) ?>
			<?php else:?>
				<?php echo $event->getInfoBubble(null, ESC_RAW) ?>
			<?php endif;?>
			
		</div>
		<div class="cb"></div>
	</div>
<?php endforeach;?>


<div style="font-size:14px;text-align:center">
		<?php if ($cal->hasHours()) :?>
			<?php if (UserUtils::getUserTZ()):?>
				<?php echo __('Presented in Timezone:');?>
				<b><?php echo UserUtils::getUserTZ() ?></b>
			<?php endif?><br/>
			<?php echo __('Times will be converted to your exact timezone by your calendar');?>
			<br />
		<?php endif ?>
</div>
<div style="font-size:12px;text-align:center;margin-top:5px;color:#999999">
	<?php echo $cal->getNumEvents() . " Events" ?>
	<?php if ($cal->getUpdatedAt()):?>
		&nbsp;|&nbsp;
		Updated: <?php echo format_date($cal->getUpdatedAt(), 'D') ?>
	<?php endif ?>
</div>

<?php include_partial('global/infoBubble', array('mobile' => true)); ?>
