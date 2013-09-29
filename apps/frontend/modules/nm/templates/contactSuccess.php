<?php use_stylesheet('/css/neverMiss/contact.css');?>


<div class="stamp-box">
	<div class="stamp-box-top"></div>
	<div class="stamp-box-center">
		<div class="stamp-box-content">
			<div class="hidden-phone">
				<div class="row-fluid">
					<div class="span6">
						<h2><span class="color-y">Contact Us</span></h2>
					</div>
					<div class="span6">
						<h2>MAILING ADDRESS</h2>
					</div>
				</div>
				
				<hr/>
			</div>
			
			<div class="row-fluid">
				
				<div class="span6">
					<div class="visible-phone">
						<h2><span class="color-y">Contact Us</span></h2>
						<hr/>
					</div>
					
					
					<form id="contact-form" method="POST">
						<div class="form">
							<?php echo $form['_csrf_token']->render();?>
							<input class="input-block-level" type="text" minlength="3" required="required" placeholder="Your Name" name="contact[sender_name]"/>
							<input class="input-block-level" type="email" minlength="3" required="required" placeholder="E-mail" name="contact[sender_email]"/>
							<input class="input-block-level" type="tel" minlength="7" placeholder="Phone" name="contact[phone]"/>
							<textarea class="input-block-level" rows="3" cols="3" placeholder="Message" name="contact[message]" required="required"></textarea>
							
							<div class="clearfix mt10">
								<input type="submit" class="btn btn-success pull-right" value="Send"/>
							</div>
						</div>
						<div class="loading-img"></div>
					</form>
				</div>
				<div class="span6">
					<div class="visible-phone">
						<h2>MAILING ADDRESS</h2>
						<hr/>
					</div>
					
					<p>iNeverMiss (Tipical LTD)</p>
					
					<p>
					<a href="mailto://<?php echo sfConfig::get('app_gmail_username')?>"><?php echo sfConfig::get('app_gmail_username')?></a>
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