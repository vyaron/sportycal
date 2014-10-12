<?php use_stylesheet('/css/neverMiss/signUpToSave.css');?>
<div id="main" class="container">
	<div id="main-padding" class="clearfix">
		<div id="sign-up-box">
			<?php include_partial('nm/loginAndRegisterForm', array('loginForm' => $loginForm, 'registerForm' => $registerForm, 'isShowLogin' => $isShowLogin, 'legend' => 'to save', 'clientIsFromWix' => $clientIsFromWix)); ?>
		</div>
		<div id="preview-box">
			<h2>PREVIEW</h2>
			<div id="preview">
				<div id="widget"><?php echo sfOutputEscaperGetterDecorator::unescape($code);?></div>
			</div>
		</div>
	</div>
</div>