<?php use_stylesheet('/css/neverMiss/widget.css');?>

<?php include_partial('formError', array('form' => $form)) ?>

<h2>Widget wizard</h2>
<div class="row">
	<div class="span6">
		<?php if ($user):?>
		<form method="GET" action="/cal/neverMissEdit">
			<fieldset>
				<legend>Custom widget:</legend>
				
				<label for="name">Calendar ID:</label>
				<input class="span6" type="text" name="name" placeholder="Enter Calendar ID" value="<?php echo $calId;?>" disabled="disabled"/>
				
				<label for="style">Style:</label>
				<select class="span6" name="style" disabled="disabled">
					<option value="1">Light</option>
					<option value="2">Dark</option>
				</select>
				
				<label for="style">Lang:</label>
				<select class="span6" name="style" disabled="disabled">
  					<?php foreach (LocationTable::getCountryOptions() as $location):?>
  					<option value="<?php echo $location->getCountry()?>"><?php echo $location->getCountry()?></option>
  					<?php endforeach;?>
				</select>
				
				<div class="row clearfix">
					<input type="submit" class="btn pull-right" value="<?php echo __('Apply');?>"/>
				</div>
				
				<label for="copy-js-code"><?php echo __('Copy this code to your site (iframe)');?>:</label>
				<textarea id="copy-js-code" spellcheck="false" class="span6">&lt;div class=&quot;nm-follow&quot; data-cal-id=&quot;<?php echo $calId;?>&quot; &gt;&lt;/div&gt; &lt;script&gt;(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return;js = d.createElement(s); js.id = id;js.src = &quot;//sportycal.local/neverMissWidget/js/all.js&quot;;fjs.parentNode.insertBefore(js, fjs);}(document, 'script', 'never-miss-jssdk'));&lt;/script&gt;</textarea>
			</fieldset>
		</form>
		<?php else:?>
		<form method="POST">
			<?php echo $form['_csrf_token']->render();?>
			<fieldset>
				<legend>Register:</legend>
				<div class="row">
					<div class="span2"><?php echo $form['full_name']->renderLabel();?></div>
					<div class="span4"><?php echo $form['full_name']->render(array('class' => 'span4', 'placeholder' => 'Enter your full name'));?></div>
				</div>
				<div class="row">
					<div class="span2"><?php echo $form['email']->renderLabel();?></div>
					<div class="span4"><?php echo $form['email']->render(array('class' => 'span4', 'placeholder' => 'example@site.com'));?></div>
				</div>
				<div class="row">
					<div class="span2"><?php echo $form['password']->renderLabel();?></div>
					<div class="span4"><?php echo $form['password']->render(array('class' => 'span4', 'placeholder' => 'Enter password'));?></div>
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
							<input type="checkbox" name="register[agree]"/>&nbsp;
							<span><?php echo __('I agree');?></span>&nbsp;
							<a target="_blank" href="/main/downCalTerms"><?php echo __('Terms & Conditions');?></a>
						</label>
					</div>
				</div>
				<div class="row">
					<div class="span6">
						<input type="submit" value="register" class="btn btn-success pull-right"/>
					</div>
				</div>
			</fieldset>
		</form>
		<?php endif;?>
	</div>
	<div class="span6">
		<legend>Preview:</legend>
		<div class="nm-follow" data-cal-id="<?php echo $calId;?>" style="float:left; position: relative;"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//sportycal.local/neverMissWidget/js/all.js";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'never-miss-jssdk'));</script>
	</div>
</div>

<?php use_javascript('/bundle/bootstrap/js/bootstrap.min.js')?>
<?php use_javascript('/js/neverMiss/widget.js')?>