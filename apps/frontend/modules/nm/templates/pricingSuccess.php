<?php 
use_stylesheet('/css/neverMiss/pricing.css');
?>

<div class="container">
	<h2><strong class="color-y">Upgrade</strong> your account</h2>

	<div id="plan-boxs" class="clearfix">
		<?php foreach (PartnerLicence::getPlans() as $key => $plan):?>
		<div class="plan-box plan-box-<?php echo $key;?>">
			<div class="title-wrapper"><strong><?php echo $plan['name'];?></strong> Account</div>
			
			<div class="content details-wrapper clearfix">
				<h3>SUBSCRIBERS</h3>
				
				<p class="small">up to</p>
				<p class="subscribers"><?php echo number_format($plan['max_subscribers']);?></p>
				<p>subscribers</p>
				
				<hr/>
				
				<h3>FEATURES</h3>
				
				<div class="features-wrapper">
					<p><?php echo PartnerLicence::getCalendarsForDisplay($plan['max_calendars'])?></p>
					<p><?php echo PartnerLicence::getEventsForDisplay($plan['max_events'])?></p>
					
					<?php if ($key == PartnerLicence::PLAN_D):?>
					<p>no iNeverMiss credit</p>
					<p>personal account manager</p>
					<?php endif;?>
				</div>
			</div>
			
			<div class="content">
				<h3>PRICE</h3>
				
				<p class="price"><?php echo $plan['price'] ? '$' . number_format($plan['price'], 2) . '/Month' : 'Free';?></p>
				
				<div class="btn-wrapper">
					<a class="btn btn-success btn-large btn-block" href="<?php echo PartnerLicence::getPlanUrl($key)?>"><?php echo $plan['price'] ? 'select' : 'Start Now!';?></a>
				</div>
			</div>
		</div>
		<?php endforeach;?>
	</div>
	
	<div class="clearfix">
		<a class="btn btn-success pull-right" href="<?php echo url_for('/nm/contact')?>">call us</a>
		<div class="call-us-text">Need more subscribers?</div>
	</div>

</div>

