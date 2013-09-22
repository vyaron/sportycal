<?php 
use_stylesheet('http://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700');
use_stylesheet('/css/neverMiss/pricing.css');
?>

<div class="container">
	<h2><span class="color-y">Upgrade</span> your account</h2>

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
				
				<p><?php echo PartnerLicence::getCalendarsForDisplay($plan['max_calendars'])?></p>
				<p><?php echo PartnerLicence::getEventsForDisplay($plan['max_events'], $key == PartnerLicence::PLAN_C)?></p>
			</div>
			
			<div class="content">
				<h3>PRICE</h3>
				
				<p class="price">$<?php echo $plan['price'] ? number_format($plan['price'], 2) . '/Month' : 'Free';?></p>
				
				<div class="btn-wrapper">
					<a class="btn btn-success btn-large btn-block" href="<?php echo PartnerLicence::getPlanUrl($key)?>"><?php echo $plan['price'] ? 'select' : 'Start Now!';?></a>
				</div>
			</div>
		</div>
		<?php endforeach;?>
	</div>
	
	<div class="clearfix">
		<p class="pull-right">Need more subscribers? <a class="btn btn-success" href="<?php echo url_for('/nm/contact')?>">call us</a></p>
	</div>

</div>

