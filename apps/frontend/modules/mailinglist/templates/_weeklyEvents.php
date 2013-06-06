<!DOCTYPE HTML>
<html>
<body style="margin:0; padding:0;">

<div style="background:#eae5da;">
	<img src="<?php echo $mailinglist->getIntelUrl()?>" style="width:1px; height:1px; border:none;"/>
	<div style="width: 630px; margin: 0 auto; padding-top:10px;">
		<a href="<?php echo sfConfig::get('app_domain_full')?>" title="Open iNeverMiss Website" target="_blank" style="text-decoration: none; border: none;">
			<img src="<?php echo sfConfig::get('app_domain_full')?>/images/mailinglist/logo.png" style="width:146px; height:64px; border:none;"/>
		</a>
	</div>
	<div style="width: 630px; margin: 0 auto; background: #fff; border: 1px solid darkgray;">
		<div style="padding:10px;">
			<div style="float:left">
				<h2>Weekly Events</h2>
			</div>
			<div style="float:right;"><?php echo date('M d')?> - <?php echo date('M d', strtotime('+6 day'))?>, <?php echo date('Y')?></div>
			<div style="clear:both;"></div>
			
			<p>Here are some interesting things going on in this week:</p>
	
			<?php $date = null; foreach ($mailinglist->getEvents() as $event): $dateParts = explode(' ', $event['starts_at']);?>
				<?php if ($date != $dateParts[0]): $date = $dateParts[0];?>
					<br/>
					<h3><?php echo date('d l M Y', strtotime($date));?></h3>
				<?php endif;?>
				
				<p><?php echo ($event['isAllDay'] ? 'All Day' : $event['time']) . ': ' . $event['name'];?></p>
			<?php endforeach;?>
		</div>
	</div>
	
	<div style="padding-bottom:10px;">
		<p style="text-align: center; font-size: 10px;">If you'd like to unsubscribe and stop receiving events email <a href="<?php echo sfConfig::get('app_domain_full');?>/mailinglist/unsubscribe/h/<?php echo $mailinglist->getHash();?>">Unsubscribe</a></p>
	</div>
</div>	
	
	
	
</body>
</html> 