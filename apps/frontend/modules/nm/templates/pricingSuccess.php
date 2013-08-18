<?php 
use_stylesheet('http://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700');
use_stylesheet('/css/neverMiss/pricing.css');
?>

<div class="container">
	<h2><span class="color-y">Upgrade</span> your account</h2>
</div>

<div id="plan-boxs">
	<?php foreach (PartnerLicence::getPlans() as $key => $plan):?>
	<div class="plan-box plan-box-<?php echo $key;?>">
		<div class="title-wrapper"><h2><?php echo $plan['name'];?></h2></div>
		<?php if ($plan['max_subscribers'] == PartnerLicence::UNLIMITED):?>
		<div class="sub-wrapper"><strong><span class="big">Unlimited</span><br/>subscribers</strong></div>
		<?php else:?>
		<div class="sub-wrapper">up to <strong><span class="big"><?php echo number_format($plan['max_subscribers']);?></span><br/>subscribers</strong></div>
		<?php endif;?>
		
		<div class="desc-wrapper">
			<h3>FEATURES:</h3>
			<?php if ($plan['max_events'] == PartnerLicence::UNLIMITED):?>
			<p>unlimited calendars</p>
			<?php else:?>
			<p><?php echo $plan['max_calendars'];?> calendars</p>
			<?php endif;?>
			
			<?php if ($plan['max_events'] == PartnerLicence::UNLIMITED):?>
			<p>unlimited events</p>
			<?php else:?>
			<p><?php echo number_format($plan['max_events']);?> events</p>
			<?php endif;?>
		</div>
		
		<?php if ($plan['price']):?>
		<div class="price-wrapper"><strong>$<?php echo $plan['price'];?></strong>/ Month</div>
		<?php elseif ($plan['price'] === 0):?>
		<div class="price-wrapper"><strong>Free</strong></div>
		<?php else:?>
		<div class="price-wrapper"><strong>Call</strong></div>
		<?php endif;?>
		
		<div class="btn-wrapper">
			<a class="btn btn-success" href="<?php echo PartnerLicence::getPlanUrl($key)?>">select &gt;&gt;</a>
		</div>
	</div>
	<?php endforeach;?>
</div>

<div class="container clearfix">
	<p class="pull-right">Need more subscribers? <a class="btn btn-success" href="<?php echo url_for('/nm/contact')?>">call us</a></p>
</div>

