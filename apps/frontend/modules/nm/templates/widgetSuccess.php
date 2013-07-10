<?php use_stylesheet('/css/neverMiss/widget.css');?>

<div class="container">

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
  					<?php foreach (NeverMissWidget::$LANGUAGES_OPTIONS as $value => $name):?>
  					<option value="<?php echo $value;?>"<?php echo ($value == $language) ? ' selected="selected"' : ''?>><?php echo $name;?></option>
  					<?php endforeach;?>
				</select>
				
				<label for="btn-style">Button Style:</label>
				<select id="btn-style" class="span6" name="btn-style">
  					<option value="<?php echo NeverMissWidget::DEFAULT_VALUE;?>">Default</option>
  					<option value="only_icon">Only icon</option>
				</select>
				
				<label for="btn-size">Button Size:</label>
				<select id="btn-size" class="span6" name="btn-size">
  					<option value="<?php echo NeverMissWidget::DEFAULT_VALUE;?>">Default</option>
  					<option value="small">Small</option>
				</select>
				
				<label for="color">Color:</label>
				<select id="color" class="span6" name="color">
  					<option value="<?php echo NeverMissWidget::DEFAULT_VALUE;?>">Default</option>
  					<option value="dark">Dark</option>
				</select>
				
				<label for="copy-js-code"><?php echo __('Copy this code to your site (iframe)');?>:</label>
				<textarea id="copy-js-code" spellcheck="false" class="span6"><?php echo $code;?></textarea>
				
				<div class="row mt10">
					<div class="span6 clearfix">
						<a class="btn btn-small pull-left" href="<?php echo url_for('nm/calEdit/?id=' . $calId);?>" title="Edit your calendar"><i class="icon-arrow-left"></i> Back</a>
						<a class="btn btn-success pull-right" href="<?php echo url_for('nm/calList');?>"><i class="icon-list icon-yellow"></i> Finish</a>
					</div>
				</div>
				
			</fieldset>
		</form>
		<?php else:?>
			<?php include_partial('nm/registerForm', array('form' => $form, 'isShowLogin' => true, 'backUrl' => url_for('nm/calEdit/?id=' . $calId), 'legend' => 'Register to save')); ?>
		<?php endif;?>
	</div>
	<div class="span6">
		<legend>Desktop Preview:</legend>
		<?php echo sfOutputEscaperGetterDecorator::unescape($code);?>
		<br/>
		<br/>
		<legend>Mobile Preview:</legend>
		<div data-is-mobile="true" data-language="en" data-cal-id="<?php echo $calId;?>" class="nm-follow"></div>
		
		<?php if ($user):?>
		<br/>
		<br/>
		<legend>Subscribe By Mail:</legend>
		<form id="email-form">
			<p>Send me an email with the calendars.<br/>That I could send it to my contacts.</p>
			<fieldset>
				<input type="hidden" name="calId" value="<?php echo $calId;?>"/>
				<textarea name="message" class="span6" placeholder="Type your message..."><?php echo __('Please click the calendar of your choice');?></textarea><br/>
				<input class="btn pull-right" type="submit" value="send"/>
			</fieldset>
		</form>
		<?php endif;?>
	</div>
</div>

</div>
<?php 
use_javascript('/bundle/jquery-plugin-validation/js/jquery.validate.min.js');
use_javascript('/js/neverMiss/widget.js');
?>