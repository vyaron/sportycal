<?php 
$backUrl = Utils::iff($backUrl, null);
$isShowLogin = Utils::iff($isShowLogin, false);
$legend = Utils::iff($legend, false);
?>


<div class="form-box">
	<h2>
		<span class="content"><strong>Sign Up</strong> <?php echo $legend ? $legend : '';?></span>
	</h2>
	
	<span class="content">
		<button class="fb-login"><span>Sign up with facebook</span></button>
	
		<hr/>
		
		<form id="register-form" method="POST">
			<?php echo $form['_csrf_token']->render();?>
			
			<?php echo $form['full_name']->render(array('placeholder' => 'Full name', 'required'=>'required', 'minlength'=>'3', 'class' => 'block'));?>
			<?php echo $form['email']->render(array('placeholder' => 'Email Address', 'required'=>'required', 'type'=>'email', 'class' => 'block'));?>
			<?php echo $form['password']->render(array('placeholder' => 'Password', 'required'=>'required', 'minlength'=>'7', 'class' => 'block'));?>
			<?php echo $form['company_name']->render(array('placeholder' => 'Companey name', 'required'=>'required', 'class' => 'block'));?>
			<?php echo $form['website']->render(array('placeholder' => 'website: http://www.site.com', 'class' => 'block'));?>
			
			<p class="help-block">
				Already a member? <a href="<?php echo url_for('/partner/login');?>">Log in</a><br/>
				By signing up, you agree to our <a href="<?php echo url_for('/nm/terms/?nlo=1');?>" target="_blank">Terms of Use</a>
			</p>
			
			<div class="actions clearfix">
				<?php if ($backUrl):?>
				<a class="btn btn-success pull-left" href="<?php echo $backUrl;?>" title="Edit your calendar">&lt;&lt;</a>
				<?php endif;?>
				<input type="submit" value="Sign up" class="btn btn-success pull-right"/>
			</div>
		</form>
	</span>
</div>
