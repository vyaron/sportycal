<?php 
use_stylesheet('http://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700');
use_stylesheet('/css/neverMiss/widget.css');
?>

<div class="container">

<?php include_partial('formError', array('form' => $form)) ?>

<!-- <h2>Widget</h2> -->
<div class="row">
	<div class="span6">
		<?php if ($user):?>
		<h2><span class="color-y">Custom</span> widget</h2>
		
		<form id="widget-form" class="form-horizontal" method="GET">
			<div class="control-group">
				<label class="control-label" for="language">Language:</label>
				<div class="controls">
					<select id="language" name="language">
	  					<?php foreach (NeverMissWidget::$LANGUAGES_OPTIONS as $value => $name):?>
	  					<option value="<?php echo $value;?>"<?php echo ($value == $language) ? ' selected="selected"' : ''?>><?php echo $name;?></option>
	  					<?php endforeach;?>
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="btn-style">Button Style:</label>
				<div class="controls">
					<select id="btn-style" name="btn-style">
	  					<option value="<?php echo NeverMissWidget::DEFAULT_VALUE;?>">Default</option>
	  					<option value="only_icon">Only icon</option>
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="btn-size">Button Size:</label>
				<div class="controls">
					<select id="btn-size" name="btn-size">
	  					<option value="<?php echo NeverMissWidget::DEFAULT_VALUE;?>">Default</option>
	  					<option value="small">Small</option>
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="color">Color:</label>
				<div class="controls">
					<select id="color" name="color">
	  					<option value="<?php echo NeverMissWidget::DEFAULT_VALUE;?>">Default</option>
	  					<option value="dark">Dark</option>
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="upcoming">Upcoming Events:</label>
				<div class="controls">
					<select id="upcoming" name="upcoming">
	  					<option value="0">None</option>
	  					<?php for ($i=1; $i <= 5; $i++):?>
	  					<option value="<?php echo $i;?>"><?php echo $i;?></option>
	  					<?php endfor;?>
					</select>
				</div>
			</div>
		</form>
		<?php else:?>
			<?php include_partial('nm/registerForm', array('form' => $form, 'isShowLogin' => true, 'backUrl' => url_for('nm/calEdit/?id=' . $calId), 'legend' => ' to save')); ?>
		<?php endif;?>
	</div>
	<div class="span6">
		<div id="widget-bg">
			<div id="desktop-widget"><?php echo sfOutputEscaperGetterDecorator::unescape($code);?></div>
		</div>
	</div>
</div>

<hr/>

<div class="row">
	<div class="span6">
		<h2 class="mt0"><span class="color-y">Copy &amp; paste</span> this code</h2>
	</div>
	<div class="span6">
		<p>Copy and paste this code into your website:</p>
		<textarea id="copy-js-code" spellcheck="false"><?php echo $code;?></textarea>
	</div>
</div>

<hr/>

<div class="row">
	<div class="span6">
		<h2 class="mt0"><span class="color-y">Subscribe</span> By Mail</h2>
	</div>
	<div class="span6">
		<form id="email-form" method="POST">
			<input type="hidden" name="calId" value="<?php echo $calId;?>"/>

			<p>Sends an email with the calendars that you can send to your users</p>
			<textarea id="message" name="message" class="span6" placeholder="Type your message..."><?php echo __('Please click the calendar of your choice');?></textarea><br/>
			
			<div class="clearfix">
				<input class="btn btn-small pull-right" type="submit" value="send"/>
			</div>
		</form>
	</div>
</div>
		
<hr/>

<div class="clearfix mt15">
	<a class="btn btn-success pull-left" href="<?php echo url_for('nm/calEdit/?id=' . $calId);?>" title="Edit your calendar">&lt;&lt;</a>
	<a class="btn btn-success pull-right" href="<?php echo url_for('nm/calList');?>">Next &gt;&gt;</a>
</div>

</div>
<?php 
use_javascript('/bundle/jquery-plugin-validation/js/jquery.validate.min.js');
use_javascript('/js/neverMiss/widget.js');
?>