<?php use_stylesheet('/css/neverMiss/licenceWizard.css');?>

<div class="container">
	<iframe id="wizard-iframe" src="https://users.licensario.com/licenses/get?paymentPlanId=<?php echo $paymentPlanCode;?>&apiKey=<?php echo sfConfig::get('app_licensario_apiKey');?>&externalUserId=<?php echo $externalUserId;?>&wizardToken=<?php echo $token;?>&terminateIfLicenseExists=false&uiContainer=iframe&disableCache=<?php echo time();?>" style="width: 100%; border: 0;"></iframe>
</div>

<!-- https://users.licensario.com/licenses/get?paymentPlanId=GOLD0f0dcd9122
	&apiKey=b3448e470128a1fa1e1999f85e2bf1f95e4aa7067f76431645937b1470419926
	&externalUserId=P443
	&callbackUrl=http%3A//inevermiss.local
	&terminateIfLicenseExists=false
	&wizardToken=71456c3bff7e27f8b12769e9ba6c9786
	&uiContainer=iframe
	&disableCache=359439 -->

<script>
var gExternalUserId = '<?php echo $externalUserId;?>';
var gPaymentPlanCode = '<?php echo $paymentPlanCode;?>';
var gApiKey = '<?php echo sfConfig::get('app_licensario_apiKey');?>';
var gToken = '<?php echo $token;?>';
var gCallbackUrl = '<?php echo sfConfig::get('app_domain_full');?>';
</script>

<?php 
use_javascript('https://users.licensario.com/assets/api/api-1.1.js');
use_javascript('/js/neverMiss/pricing.js');
?>