<div class="form-box">
	<h2>
		<span class="content"><strong>Log In</strong></span>
	</h2>
	
	<span class="content">
		<button class="fb-login"><span>Log in with facebook</span></button>
	
		<hr/>
		
		<form id="login-form" action="<?php echo url_for('partner/login') ?>" method="POST">
			<?php echo $form['_csrf_token']->render();?>
			
			<?php echo $form['email']->render(array('placeholder' => 'Email address', 'required'=>'required', 'minlength'=>'3', 'class' => 'block'));?>
			<?php echo $form['password']->render(array('placeholder' => 'Password', 'required'=>'required', 'class' => 'block'));?>
			
			<p class="help-block">
				Want to become a member? <a href="<?php echo url_for('/nm/register')?>">Sign up</a>
			</p>
			
			<div class="actions clearfix">
				<input type="submit" value="Log In" class="btn btn-success pull-right"/>
			</div>
		</form>
	</span>
</div>