<?php use_stylesheet('/css/neverMiss/contact.css');?>


<div class="stamp-box">
	<div class="stamp-box-top"></div>
	<div class="stamp-box-center">
		<div class="stamp-box-content">
			<div class="row-fluid">
				<div class="span6">
					<h2><span class="color-y">Contact Us</span></h2>
					
					<hr/>
					
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
				</div>
				<div class="span6">
					<h2>MAILING ADDRESS</h2>
					
					<hr/>
					
					<p>
					<a href="mailto://info@jivygroup.com">info@jivygroup.com</a>
					</p>
					
					<p>
					<address>
						+972 (9) 9544-551<br/>
						6 Maskit St., Herzeliya Pituach<br/>
						Israel 46733<br/>
						P.O.Box 4192
					</address>
					</p>
					
					<a href="http://goo.gl/maps/AcY27" target="_blank" title="open map">
						<img src="/images/neverMiss/contact/map.jpg" width="320" height="272"/>
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="stamp-box-bottom"></div>
</div>

<?php 
use_javascript('/bundle/jquery-plugin-validation/js/jquery.validate.min.js');
use_javascript('/js/neverMiss/contact.js');
?>