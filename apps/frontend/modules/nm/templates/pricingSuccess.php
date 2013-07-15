<?php 
use_stylesheet('http://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700');
use_stylesheet('/css/neverMiss/pricing.css');
?>

<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="JYBUS3ZPFX7G8">
<input type="image" src="https://www.paypalobjects.com/en_US/IL/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>



<!--  
<script src="https://raw.github.com/paypal/JavaScriptButtons/master/dist/paypal-button.min.js?merchant=CLZLCXKGKCJKW"
    data-button="subscribe"
    data-name="My product"
    data-amount="25.00"
    data-recurrence="1"
    data-period="M"
></script>
-->

<!-- 
<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_xclick-subscriptions">
<input type="hidden" name="business" value="il.mrbit@gmail.com">
<input type="hidden" name="lc" value="GB">
<input type="hidden" name="item_name" value="Basic Account">
<input type="hidden" name="item_number" value="A">
<input type="hidden" name="no_note" value="1">
<input type="hidden" name="src" value="1">
<input type="hidden" name="a3" value="25.00">
<input type="hidden" name="p3" value="1">
<input type="hidden" name="t3" value="M">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="bn" value="PP-SubscriptionsBF:btn_subscribeCC_LG.gif:NonHostedGuest">
<input type="image" src="https://www.sandbox.paypal.com/en_US/GB/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
<img alt="" border="0" src="https://www.sandbox.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1">
</form>
-->

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
			<a class="btn btn-success" href="<?php url_for('/nm/licenceWizard/?c=' . $key)?>">select &gt;&gt;</a>
		</div>
	</div>
	<?php endforeach;?>
</div>

<!-- 
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="JMYLXJREUEML8">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
 -->

