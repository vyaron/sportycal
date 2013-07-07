<?php use_stylesheet('/css/neverMiss/loginAndRegister.css');?>

<div class="container">
    <div id="forms-wrapper">
    	<div><?php include_partial('loginForm', array('form' => $loginForm));?></div>
		<div><?php include_partial('nm/registerForm', array('form' => $registerForm));?></div>
    </div>
</div>

<script>
var gCode = '<?php echo $code;?>';
</script>

<?php 
use_javascript('/bundle/jquery-plugin-validation/js/jquery.validate.min.js');
use_javascript('/js/neverMiss/loginAndRegister.js');
?>