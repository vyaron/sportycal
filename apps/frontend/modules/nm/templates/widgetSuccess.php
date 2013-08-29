<?php 
use_stylesheet('http://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700');
use_stylesheet('/css/neverMiss/widget.css');
?>

<div class="container">

<!-- <h2>Widget</h2> -->
<div class="row">
	<div class="span6">
		<?php if ($user):?>
		<h2><span class="color-y">Custom</span> widget</h2>
		<?php include_partial('nm/widgetForm', array('language' => $language));?>
		<?php else:?>
			<?php include_partial('nm/loginAndRegisterForm', array('loginForm' => $loginForm, 'registerForm' => $registerForm, 'isShowLogin' => $isShowLogin, 'backUrl' => url_for('/nm/calCreate'), 'legend' => 'to save')); ?>
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