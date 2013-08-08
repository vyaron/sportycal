<?php use_stylesheet('/css/neverMiss/contact.css');?>

<div class="container">
	
	<form id="contact-form" method="POST">
		<h2><span class="color-y">Contact</span> Us</h2>
		
		<?php echo $form['_csrf_token']->render();?>
		<input class="input-block-level" type="text" minlength="3" required="required" placeholder="Enter your full name" name="contact[sender_name]"/>
		<input class="input-block-level" type="email" minlength="3" required="required" placeholder="name@domain.com" name="contact[sender_email]"/>
		<input class="input-block-level" type="tel" minlength="7" placeholder="972-3-1234567" name="contact[phone]"/>
		<textarea class="input-block-level" rows="3" cols="3" placeholder="Enter your message here..." name="contact[message]" required="required"></textarea>
		
		<div class="clearfix mt10">
			<input type="submit" class="btn btn-success pull-right" value="Send"/>
		</div>
	</form>
</div>

<?php 
use_javascript('/bundle/jquery-plugin-validation/js/jquery.validate.min.js');
use_javascript('/js/neverMiss/contact.js');
?>