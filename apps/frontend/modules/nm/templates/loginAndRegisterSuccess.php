<?php use_stylesheet('/css/neverMiss/loginAndRegister.css');?>
<div class="container">
	<div id="form-box-center">
		<?php include_partial('nm/loginAndRegisterForm', array('loginForm' => $loginForm, 'registerForm' => $registerForm, 'isShowLogin' => $isShowLogin)); ?>
	</div>
</div>
