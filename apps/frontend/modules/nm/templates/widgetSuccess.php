<?php 
use_stylesheet('http://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700');
use_stylesheet('/css/neverMiss/widget.css');
?>

<div class="container">

	<div class="clearfix">
		<div id="customize-wrapper" class="col-left">
			<h2><span class="color-y">Customize</span> widget</h2>
			
			<hr/>
			
			<div id="customize" class="clearfix">
				<from id="widget-form">
					<div style="display: none;">
						<input type="radio" name="btn-style" value="default" checked="checked"/>default
						<input type="radio" name="btn-style" value="only_icon"/>only_icon
						<input type="radio" name="btn-style" value="list"/>list
						
						<input type="radio" name="color" value="default" checked="checked"/>default
						<input type="radio" name="color" value="dark"/>dark
						<input type="radio" name="color" value="yellow"/>yellow
						
						<input type="radio" name="btn-size" value="default" checked="checked"/>default
						<input type="radio" name="btn-size" value="small"/>small
					</div>
				
					<div class="customize-prop clearfix">
						<div class="customize-label">Style</div>
						<div class="customize-option" data-name="btn-style" data-value="only_icon">
							<div class="customize-option-title">Icon</div>
							<div class="customize-option-icon customize-option-icon-icon">&nbsp;</div>
							<div class="customize-option-checkbox">&nbsp;</div>
						</div>
						<div class="customize-option" data-name="btn-style" data-value="default">
							<div class="customize-option-title">Button</div>
							<div class="customize-option-icon customize-option-icon-button">&nbsp;</div>
							<div class="customize-option-checkbox checked">&nbsp;</div>
						</div>
						<div class="customize-option" data-name="btn-style" data-value="list">
							<div class="customize-option-title">Event List</div>
							<div class="customize-option-icon customize-option-icon-list">&nbsp;</div>
							<div class="customize-option-checkbox">&nbsp;</div>
						</div>
					</div>
					
					<hr/>
					
					<div id="customize-prop-color" class="customize-prop clearfix">
						<div class="customize-label">Color</div>
						<div id="customize-option-color-yellow" class="customize-option" data-name="color" data-value="yellow">
							<div class="customize-option-icon customize-option-icon-yellow">&nbsp;</div>
							<div class="customize-option-checkbox">&nbsp;</div>
						</div>
						<div id="customize-option-color-default" class="customize-option" data-name="color" data-value="default">

							<div class="customize-option-icon customize-option-icon-default">&nbsp;</div>
							<div class="customize-option-checkbox checked">&nbsp;</div>
						</div>
						<div class="customize-option" data-name="color" data-value="dark">
							<div class="customize-option-icon customize-option-icon-dark">&nbsp;</div>
							<div class="customize-option-checkbox">&nbsp;</div>
						</div>
					</div>
					
					<hr/>
					
					<div id="customize-prop-btn-size">
						<div class="customize-prop clearfix">
							<div class="customize-label">Size</div>
							<div class="customize-option"  data-name="btn-size" data-value="small">
								<div class="customize-option-title">
									<span class="customize-option-title-btn">83x24</span>
									<span class="customize-option-title-icon">24x24</span>
								</div>
								<div class="customize-option-icon customize-option-icon-size-small">&nbsp;</div>
								<div class="customize-option-checkbox">&nbsp;</div>
							</div>
							<div class="customize-option"  data-name="btn-size" data-value="default">
								<div class="customize-option-title">
									<span class="customize-option-title-btn">128x37</span>
									<span class="customize-option-title-icon">37x37</span>
								</div>
								<div class="customize-option-icon customize-option-icon-size-default">&nbsp;</div>
								<div class="customize-option-checkbox checked">&nbsp;</div>
							</div>
						</div>
						
						<hr/>
					</div>
					
					<div id="customize-prop-size" style="display: none;">
						<div class="customize-prop-padding">
							<div class="clearfix">
								<label class="customize-label">Title</label>
								<input id="title" type="text" name="title"/>
							</div>
							<div class="clearfix">
								<div class="customize-prop-size-wrapper">
									<label class="customize-label">Width</label>
									<input type="text" name="width" value="200"/>
								</div>
								<div class="customize-prop-size-wrapper">
									<label class="customize-label">Height</label>
									<input type="text" name="height" value="350"/>
								</div>
							</div>
						</div>
						
						<hr/>
					</div>
					
					<div class="customize-prop-padding">
						<div class="clearfix">
							<label class="customize-label">Language</label>
							<select id="language" name="language">
								<option selected="selected" value="en">English</option>
								<option value="he">Hebrew</option>
							</select>
						</div>
					</div>
				</from>
			</div>
			
			<hr/>
		</div>
		<div id="preview-wrapper" class="col-right">
			<h2>Preview</h2>
			
			<hr/>
			
			<div id="preview" class="content">
				<div id="widget"><?php echo sfOutputEscaperGetterDecorator::unescape($code);?></div>
			</div>
			
			<hr/>
		</div>
	</div>
	
	<div id="code-wrapper" class="clearfix">
		<div class="col-left">
			<h2><span class="color-y">Copy &amp; paste</span> this code</h2>
			<p>Copy and paste this code into your website:</p>
		</div>
		<div class="col-right">
			<div class="content">
				<textarea id="copy-js-code" spellcheck="false"><?php echo $code;?></textarea>
			</div>
		</div>
	</div>
	
	<hr/>


	<?php if (false):?>
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
	<?php endif;?>

	<div class="clearfix mt15">
		<a class="pull-left" href="<?php echo url_for('nm/calEdit/?id=' . $calId);?>" title="Edit your calendar">Edit Calendar</a>
		<a class="btn btn-success pull-right" href="<?php echo url_for('nm/calList');?>">Next &gt;&gt;</a>
	</div>

</div>
<?php 
use_javascript('/bundle/jquery-plugin-validation/js/jquery.validate.min.js');
use_javascript('/js/neverMiss/widget.js');
?>