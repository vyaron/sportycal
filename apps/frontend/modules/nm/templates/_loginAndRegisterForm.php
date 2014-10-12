<?php 
$backUrl = Utils::iff($backUrl, null);
$legend = Utils::iff($legend, false);
?>
<div id="login-form-wrapper" <?php echo $isShowLogin ? '' : 'class="hide"';?>>
	<?php include_partial('nm/loginForm', array('form' => $loginForm, 'backUrl' => $backUrl)); ?>
</div>
<div id="register-form-wrapper" <?php echo $isShowLogin ? 'class="hide"' : '';?>>
	<?php include_partial('nm/registerForm', array('form' => $registerForm, 'backUrl' => $backUrl, 'legend' => $legend, 'clientIsFromWix' => $clientIsFromWix)); ?>
</div>

<?php
use_javascript('/bundle/jquery-plugin-validation/js/jquery.validate.min.js'); 
use_javascript('/js/neverMiss/loginAndRegister.js');
?>