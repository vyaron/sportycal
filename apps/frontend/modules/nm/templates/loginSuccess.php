<div class="row">
	<div style="width: 220px; margin: 0 auto;">
		<form id="login-form" action="<?php echo url_for('partner/login') ?>" method="POST">
			<fieldset>
				<legend class="clearfix ">
					<span class="pull-left">Login:</span>
					
					<span class="pull-right">
						<a class="fb-login" href="#"><span>Log In</span></a>
					</span>
				</legend>

				<?php echo $form ?>
				<input class="btn btn-success pull-right" type="submit" value="Login" />
			</fieldset>
		</form>
	</div>
</div>
<?php use_javascript('/js/neverMiss/login.js');?>


