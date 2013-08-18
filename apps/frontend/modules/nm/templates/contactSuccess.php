<?php use_stylesheet('/css/neverMiss/contact.css');?>

<div class="container">
	<div id="contact-box" class="clearfix">
		<h2><span class="color-y">Contact</span> Us</h2>
		<form id="contact-form" method="POST">
			<div class="form">
				<?php echo $form['_csrf_token']->render();?>
				<input class="input-block-level" type="text" minlength="3" required="required" placeholder="Enter your full name" name="contact[sender_name]"/>
				<input class="input-block-level" type="email" minlength="3" required="required" placeholder="name@domain.com" name="contact[sender_email]"/>
				<input class="input-block-level" type="tel" minlength="7" placeholder="972-3-1234567" name="contact[phone]"/>
				<textarea class="input-block-level" rows="3" cols="3" placeholder="Enter your message here..." name="contact[message]" required="required"></textarea>
				
				<div class="clearfix mt10">
					<input type="submit" class="btn btn-success pull-right" value="Send"/>
				</div>
			</div>
			<div class="loading-img"></div>
		</form>
		<div id="contact-info">
			<h4 class="mt0">Info</h4>
			<ul class="unstyled">
				<li><i class="icon-info-sign"></i> +972 (9) 9544-551</li>
				<li><i class="icon-envelope"></i> <a href="mailto://support@inevermiss.net" title="send mail">support@inevermiss.net</a></li>
				<li><i class="icon-home"></i> 6 Maskit St.<br/>Herzeliya Pituach, Israel 46733</li>
				<li><i class="icon-inbox"></i> P.O.Box 4192</li>
			</ul>
		</div>
	</div>
</div>

<?php 
use_javascript('/bundle/jquery-plugin-validation/js/jquery.validate.min.js');
use_javascript('/js/neverMiss/contact.js');
?>