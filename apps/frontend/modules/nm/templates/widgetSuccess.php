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
  					<?php foreach ($languagesOptions as $value => $name):?>
  					<option value="<?php echo $value;?>"<?php echo ($value == $language) ? ' selected="selected"' : ''?>><?php echo $name;?></option>
  					<?php endforeach;?>
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
			<?php include_partial('nm/registerForm', array('form' => $form, 'isShowLogin' => true, 'backUrl' => url_for('nm/calEdit/?id=' . $calId))); ?>
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

</div>
<?php 
use_javascript('/bundle/jquery-plugin-validation/js/jquery.validate.min.js');
use_javascript('/js/neverMiss/widget.js');
?>