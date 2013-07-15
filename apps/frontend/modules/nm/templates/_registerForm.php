<?php 
$backUrl = Utils::iff($backUrl, null);
$isShowLogin = Utils::iff($isShowLogin, false);
$legend = Utils::iff($legend, 'Register');
?>

<h2><span class="color-y">Register</span> <?php echo $legend ? $legend : '';?>:</h2>

<?php if ($isShowLogin):?>
<div id="register-login-wrapper" class="clearfix">
	<a class="fb-login pull-left" href="#"><span>Log In</span></a>
	<a class="register-login-btn pull-left" href="<?php echo url_for('partner/login');?>">Login</a>
</div>
<?php endif;?>

<form id="register-form" class="form-horizontal" method="POST">
	<?php echo $form['_csrf_token']->render();?>

	<div class="control-group">
		<?php echo $form['full_name']->renderLabel(null, array('class' => 'control-label'));?>
		<div class="controls">
			<?php echo $form['full_name']->render(array('placeholder' => 'Enter your full name', 'required'=>'required', 'minlength'=>'3'));?>
		</div>
	</div>
	<div class="control-group">
		<?php echo $form['email']->renderLabel(null, array('class' => 'control-label'));?>
		<div class="controls">
			<?php echo $form['email']->render(array('placeholder' => 'example@site.com', 'required'=>'required', 'type'=>'email'));?>
		</div>
	</div>
	<div class="control-group">
		<?php echo $form['password']->renderLabel(null, array('class' => 'control-label'));?>
		<div class="controls">
			<?php echo $form['password']->render(array('placeholder' => 'Enter password', 'required'=>'required', 'minlength'=>'7'));?>
		</div>
	</div>
	<div class="control-group">
		<?php echo $form['confirm_password']->renderLabel(null, array('class' => 'control-label'));?>
		<div class="controls">
			<?php echo $form['confirm_password']->render(array('placeholder' => 'ReEnter password'));?>
		</div>
	</div>
	<div class="control-group">
		<?php echo $form['company_name']->renderLabel(null, array('class' => 'control-label'));?>
		<div class="controls">
			<?php echo $form['company_name']->render(array('placeholder' => 'Enter companey name', 'required'=>'required'));?>
		</div>
	</div>
	<div class="control-group">
		<?php echo $form['website']->renderLabel(null, array('class' => 'control-label'));?>
		<div class="controls">
			<?php echo $form['website']->render(array('placeholder' => 'http://www.site.com'));?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">&nbsp;</div>
		<div class="controls">
			<label class="inline">
				<input type="checkbox" name="register[agree]" required="required"/>&nbsp;
				<span><?php echo __('I agree');?></span>&nbsp;
				<a target="_blank" href="/main/downCalTerms"><?php echo __('Terms & Conditions');?></a>
			</label>
		</div>
	</div>
	
	<hr/>
	
	<div class="clearfix">
		<?php if ($backUrl):?>
		<a class="btn btn-success pull-left" href="<?php echo $backUrl;?>" title="Edit your calendar">&lt;&lt;</a>
		<?php endif;?>
		<input type="submit" value="Register" class="btn btn-success pull-right"/>
	</div>
</form>