<form id="login-form" action="<?php echo url_for('partner/login') ?>" method="POST">
	<fieldset>
		<legend class="clearfix ">
			<span class="pull-left">Login:</span>
			<span class="pull-right">
				<a class="fb-login" href="#"><span>Log In</span></a>
			</span>
		</legend>
		
		<?php echo $form['_csrf_token']->render();?>
		
		<?php echo $form['email']->renderLabel();?>
		<?php echo $form['email']->render(array('placeholder' => 'name@domain.com', 'required'=>'required', 'minlength'=>'3'));?>
		
		<?php echo $form['password']->renderLabel();?>
		<?php echo $form['password']->render(array('required'=>'required'));?>
		
		<?php echo $form['email']->renderError();?>
		
		<?php //echo $form ?>
		<input class="btn btn-success pull-right" type="submit" value="Login" />
	</fieldset>
</form>