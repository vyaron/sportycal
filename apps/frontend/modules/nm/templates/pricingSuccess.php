<?php 
use_stylesheet('http://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700');
use_stylesheet('/css/neverMiss/pricing.css');
?>

<h2 class="text-center"><span class="color-y">Upgrade</span> your account:</h2>

<div id="plan-boxs">
	<?php foreach (PartnerLicence::getPlans() as $key => $plan):?>
	<div class="plan-box plan-box-<?php echo $key;?>">
		<div class="title-wrapper"><h2><?php echo $plan['name'];?></h2></div>
		<?php if ($plan['max_subscribers'] == PartnerLicence::UNLIMITED):?>
		<div class="sub-wrapper"><strong><span class="big">Unlimited</span><br/>subscribers</strong></div>
		<?php else:?>
		<div class="sub-wrapper">up to <strong><span class="big"><?php echo $plan['max_subscribers'];?></span><br/>subscribers</strong></div>
		<?php endif;?>
		
		<div class="desc-wrapper">
			<h3>Extra features:</h3>
			<p><?php echo $plan['desc'];?></p>
		</div>
		
		<?php if ($plan['price']):?>
		<div class="price-wrapper"><strong>$<?php echo $plan['price'];?></strong>/ Month</div>
		<?php else:?>
		<div class="price-wrapper"><strong>Call</strong></div>
		<?php endif;?>
		
		<div class="btn-wrapper">
			<a class="btn btn-success" href="<?php echo url_for('/nm/checkout/?c=' . $key)?>">select &gt;&gt;</a>
		</div>
	</div>
	<?php endforeach;?>
</div>