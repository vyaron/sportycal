<div class="container">
	<a class="btn plan-btn" href="#" data-plan-id="SILVERb1679504e7">Silver</a>
	<a class="btn plan-btn" href="#" data-plan-id="GOLD0f0dcd9122">Gold</a>
	<a class="btn plan-btn" href="#" data-plan-id="PLATINUM7bef4d179d">Platinum</a>
	<a class="btn plan-btn" href="#">All</a>
	
	<div class="row">
		<div class="span6"><?php include_partial('loginForm', array('form' => $loginForm)); ?></div>
		<div class="span6"><?php include_partial('nm/registerForm', array('form' => $registerForm)); ?></div>
	</div>
</div>



<script>
var gApiKey = '<?php echo sfConfig::get('app_licensario_apiKey');?>';
</script>
<?php 
use_javascript('/bundle/jquery-plugin-validation/js/jquery.validate.min.js');
use_javascript('https://users.licensario.com/assets/api/api-1.1.js');
use_javascript('/js/neverMiss/pricing.js');
?>