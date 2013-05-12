<style>
#copyJsCode{resize: none;}
</style>
<form method="GET" action="/cal/neverMissEdit">
	<fieldset>
		<legend>Never Miss widget wizard:</legend>
		<div class="row">
			<div class="span6">
				<label for="name">Calendar ID:</label>
				<input class="span6" type="text" name="name" placeholder="Enter Calendar ID" value="<?php echo $calId;?>"/>
				<span class="help-block">This name apear in subscipe application (Outlook, Google Calendar..)</span>
				
				
				<!-- 
				<div id="jsCodeWrapper" class="pb10" style="display:none;">
					<label for="copyJsCode"><?php echo __('Copy this code to your site (iframe)');?>:</label>
					<textarea id="copyJsCode" rows="3" cols="3" spellcheck="false" class="span6">&lt;div class=&quot;nm-follow&quot; data-cal-id=&quot;<?php echo $calId;?>&quot; style=&quot;float:left&quot;&gt;&lt;/div&gt; &lt;script&gt;(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return;js = d.createElement(s); js.id = id;js.src = &quot;//sportycal.local/neverMissWidget/js/all.js&quot;;fjs.parentNode.insertBefore(js, fjs);}(document, 'script', 'never-miss-jssdk'));&lt;/script&gt;</textarea>
				</div>
				
				
				<label id="dwTermsWrapper"><input id="iAgree" type="checkbox" /> <?php echo __('I agree');?>&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="/main/downCalTerms"><?php echo __('Terms & Conditions');?></a></label>
				 -->
			</div>
			<div class="span6">
				<div class="nm-follow" data-cal-id="<?php echo $calId;?>" style="float:left"></div>
				<script>(function(d, s, id) {
				  var js, fjs = d.getElementsByTagName(s)[0];
				  if (d.getElementById(id)) return;
				  js = d.createElement(s); js.id = id;
				  js.src = "//sportycal.local/neverMissWidget/js/all.js";
				  fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'never-miss-jssdk'));</script>
			</div>
		</div>
		
		<div class="row">
			<div class="span6 clearfix">
				<input type="submit" class="btn pull-right" value="<?php echo __('Apply');?>"/>
			</div>
		</div>
	</fieldset>
</form>

<form <?php echo url_for('cal/neverMissRegister') ?> method="POST">
	<fieldset>
		<legend>Register:</legend>
		<div class="row">
			<div class="span2">
				<label for="register[full_name]">Full Name:</label>
			</div>
			<div class="span4">
				<input id="register[full_name]" class="span4" name="register[full_name]" type="text" placeholder="Enter your full name"/>
			</div>
		</div>
		<div class="row">
			<div class="span2">
				<label for="register[email]">Email:</label>
			</div>
			<div class="span4">
				<input id="register[email]" class="span4" name="register[email]" type="text" placeholder="Enter your email"/>
			</div>
		</div>
		<div class="row">
			<div class="span2">
				<label for="register[password]">Password:</label>
			</div>
			<div class="span4">
				<input id="register[password]" class="span4" name="register[password]" type="password" placeholder="Enter password"/>
			</div>
		</div>
		<div class="row">
			<div class="span2">
				<label for="register[password_confirm]">Confirm Password:</label>
			</div>
			<div class="span4">
				<input id="register[password_confirm]" class="span4" name="register[password_confirm]" type="password" placeholder="Re-enter password"/>
			</div>
		</div>
		<div class="row">
			<div class="span2">
				<label for="register[company_name]">Company name:</label>
			</div>
			<div class="span4">
				<input id="register[company_name]" class="span4" name="register[company_name]" type="text" placeholder="Enter company name"/>
			</div>
		</div>
		<div class="row">
			<div class="span2">
				<label for="register[website]">Website:</label>
			</div>
			<div class="span4">
				<input id="register[website]" class="span4" name="register[website]" type="text" placeholder="Enter webiste"/>
			</div>
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

<script type="text/javascript" src="/bundle/jquery/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript">
/*
jQuery(document).ready(function(){
	jQuery('#copyJsCode').focus(function(e){
		e.preventDefault();
		copyJsCode.select();
	});

	jQuery('#iAgree').click(function(e){
		if (!IS_USER_LOGED_IN) {
			e.preventDefault();
			e.stopPropagation();
			alert('Login!!!');
		} else {
			var jsCodeWrapper = jQuery('#jsCodeWrapper');

			if (jQuery(this).is(':checked')){
				jsCodeWrapper.show();
			} else {
				jsCodeWrapper.hide();
			}
		}	
	});
});
*/
</script>
