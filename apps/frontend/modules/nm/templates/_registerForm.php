<?php 
$backUrl = Utils::iff($backUrl, null);
$isShowLogin = Utils::iff($isShowLogin, false);
?>
<form id="register-form" method="POST">
	<fieldset>
		<?php echo $form['_csrf_token']->render();?>
		<legend class="clearfix">
			<span class="pull-left">Register to save:</span>
			
			<?php if ($isShowLogin):?>
			<span id="register-login-wrapper" class="pull-right">
				<a class="fb-login" href="#"><span>Log In</span></a>&nbsp;|&nbsp;
				<a href="<?php echo url_for('partner/login');?>">Login</a>
			</span>
			<?php endif;?>
		</legend>
		<div class="row">
			<div class="span6">
				
			</div>
		</div>
		
		
		<div class="row">
			<div class="span2"><?php echo $form['full_name']->renderLabel();?></div>
			<div class="span4"><?php echo $form['full_name']->render(array('class' => 'span4', 'placeholder' => 'Enter your full name', 'required'=>'required', 'minlength'=>'3'));?></div>
		</div>
		<div class="row">
			<div class="span2"><?php echo $form['email']->renderLabel();?></div>
			<div class="span4"><?php echo $form['email']->render(array('class' => 'span4', 'placeholder' => 'example@site.com', 'required'=>'required', 'type'=>'email'));?></div>
		</div>
		<div class="row">
			<div class="span2"><?php echo $form['password']->renderLabel();?></div>
			<div class="span4"><?php echo $form['password']->render(array('class' => 'span4', 'placeholder' => 'Enter password', 'required'=>'required', 'minlength'=>'7'));?></div>
		</div>
		<div class="row">
			<div class="span2"><?php echo $form['confirm_password']->renderLabel();?></div>
			<div class="span4"><?php echo $form['confirm_password']->render(array('class' => 'span4', 'placeholder' => 'ReEnter password'));?></div>
		</div>
		<div class="row">
			<div class="span2"><?php echo $form['company_name']->renderLabel();?></div>
			<div class="span4"><?php echo $form['company_name']->render(array('class' => 'span4', 'placeholder' => 'Enter companey name', 'required'=>'required'));?></div>
		</div>
		<div class="row">
			<div class="span2"><?php echo $form['website']->renderLabel();?></div>
			<div class="span4"><?php echo $form['website']->render(array('class' => 'span4', 'placeholder' => 'http://www.site.com'));?></div>
		</div>
		<div class="row">
			<div class="span6">
				<label class="inline">
					<input type="checkbox" name="register[agree]" required="required"/>&nbsp;
					<span><?php echo __('I agree');?></span>&nbsp;
					<a target="_blank" href="/main/downCalTerms"><?php echo __('Terms & Conditions');?></a>
				</label>
			</div>
		</div>
		<div class="row mt10">
			<div class="span6">
				<?php if ($backUrl):?>
				<a class="btn btn-small pull-left" href="<?php echo $backUrl;?>" title="Edit your calendar"><i class="icon-arrow-left"></i> Back</a>
				<?php endif;?>
				<input type="submit" value="Register" class="btn btn-success pull-right"/>
			</div>
		</div>
	</fieldset>
</form>