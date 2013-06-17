<?php if (sfConfig::get('app_domain_isNeverMiss')) decorate_with('neverMiss.php'); ?>

<div style="font-size: 14px; color:#555555; width: 220px; margin: 10px auto; text-align: center;">
	<img src="/images/layout/404Error.png"/><br/><br/>
	Amm.... sorry.
	<br/><br/>
	Lets try again:
	<br/><br/>
	<a href="<?php echo sfConfig::get('app_domain_full');?>" style="text-decoration: underline; color:black">Back to <?php echo sfConfig::get('app_domain_name');?></a>
</div>