<?php use_stylesheet('/css/neverMiss/widget.css');?>

<?php include_partial('formError', array('form' => $form)) ?>

<!-- <h2>Widget</h2> -->
<div class="row">
	<div class="span6">
		<?php if ($user):?>
		<form id="widget-form" method="GET">
			<fieldset>
				<legend>Custom widget:</legend>
				
				<label for="language">Language:</label>
				<select id="language" class="span6" name="language">
  					<?php foreach ($languagesOptions as $value => $name):?>
  					<option value="<?php echo $value;?>"<?php echo ($value == $language) ? ' selected="selected"' : ''?>><?php echo $name;?></option>
  					<?php endforeach;?>
				</select>
				
				<label for="copy-js-code"><?php echo __('Copy this code to your site (iframe)');?>:</label>
				<textarea id="copy-js-code" spellcheck="false" class="span6"><?php echo $code;?></textarea>
				
				<div class="row mt10">
					<div class="span6 clearfix">
						<a class="btn btn-small pull-left" href="<?php echo url_for('nm/calEdit/?id=' . $calId);?>" title="Edit your calendar"><i class="icon-arrow-left"></i> Back</a>
						<a class="btn btn-success pull-right" href="<?php echo url_for('nm/calList');?>"><i class="icon-list icon-white"></i> Finish</a>
					</div>
				</div>
				
			</fieldset>
		</form>
		<?php else:?>
		<form id="register-form" method="POST">
			<?php echo $form['_csrf_token']->render();?>
			<fieldset>
				<legend class="clearfix">
					<span class="pull-left">Register to save:</span>
					
					<span id="register-login-wrapper" class="pull-right">
						<a class="fb-login" href="#"><span>Log In</span></a>&nbsp;|&nbsp;
						<a href="<?php echo url_for('partner/login');?>">Login</a>
					</span>
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
					<div class="span4"><?php echo $form['company_name']->render(array('class' => 'span4', 'placeholder' => 'Enter companey name'));?></div>
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
						<a class="btn btn-small pull-left" href="<?php echo url_for('nm/calEdit/?id=' . $calId);?>" title="Edit your calendar"><i class="icon-arrow-left"></i> Back</a>
						<input type="submit" value="Register" class="btn btn-success pull-right"/>
					</div>
				</div>
			</fieldset>
		</form>
		<?php endif;?>
	</div>
	<div class="span6">
		<legend>Desktop Preview:</legend>
		<?php echo sfOutputEscaperGetterDecorator::unescape($code);?>
		<br/>
		<br/>
		<legend>Mobile Preview:</legend>
		<div data-is-mobile="true" data-language="en" data-cal-id="<?php echo $calId;?>" class="nm-follow"></div>
	</div>
</div>

<?php 
use_javascript('/bundle/jquery-plugin-validation/js/jquery.validate.min.js');
use_javascript('/js/neverMiss/widget.js');
?>