<?php 
use_stylesheet('/css/wix/settings.css');
use_stylesheet('/bundle/colorpicker/css/colorpicker.css');
?>

<div class="container">
	<div id="app-info">
		<div class="clearfix">
			<div id="logo">&nbsp;</div>
			<p>Allow users to see events created by you in their personal calendars. Your users will never miss a sale, a promotion, a show or an event</p>
		</div>
		
		<hr/>
		
		<div class="clearfix">
			<?php if ($user):?>
			<p>Hi, <?php echo $user->getFullName();?> <button id="logout-btn" class="btn btn-small pull-right">logout</button></p>
			<?php else:?>
			<p><button id="login-btn" class="btn btn-success btn-small pull-right">Login / Sign up</button></p>
			<?php endif;?>
		</div>
	</div>

	<form id="settings-form">
		<input type="hidden" name="instance" value="<?php echo $wix->getInstanceCode();?>"/>
		<input type="hidden" name="compId" value="<?php echo $wix->getCompCode();?>"/>
		
		<div class="accordion" id="accordion2">
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne"> Calendar setup</a>
				</div>
				<div id="collapseOne" class="accordion-body collapse in">
					<div class="accordion-inner">
						
						<div class="mb15 clearfix">
							<button id="reload-btn" class="btn pull-right"><i class="icon-refresh"></i></button>
						</div>
						
						<?php if ($user): $calsCount = count($cals);?>
						<div class="controls controls-row">
							<label class="span3">Calendar: </label>
							<?php if ($calsCount === 0):?>
								<div class="span6">
									<button id="create-btn" class="btn btn-primary btn-block mb15">Create</button>
								</div>
							<?php elseif ($calsCount === 1): ?>
								<input type="hidden" name="cal_id" value="<?php echo $calId;?>"/>
								<div class="span6">
									<span><?php echo $cals[0]->getName();?></span>
									<button id="edit-btn" class="btn btn-small pull-right">Edit</button>
								</div>
							<?php else:?>
								<select id="cal-id" name="cal_id" class="span6">
									<option value="">&nbsp;</option>
									<?php foreach ($cals as $cal):?>
									<option value="<?php echo $cal->getId();?>" <?php echo ($cal->getId() == $calId) ? 'selected="selected"' : '';?>><?php echo $cal->getName();?></option>
									<?php endforeach;?>
								</select>
								<button id="edit-btn" class="btn btn-small pull-right">Edit</button>
							<?php endif;?>
						</div>
						<?php endif;?>
					</div>
				</div>
			</div>
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo"> Layout</a>
				</div>
				<div id="collapseTwo" class="accordion-body collapse">
					<div class="accordion-inner">
						<div class="controls controls-row">
							<label class="span3">Displayed Events number: </label>
							<select name="upcoming" class="span1">
								<?php foreach (Wix::$UPCOMING_OPTIONS as $val => $name):?>
								<option value="<?php echo $val;?>" <?php echo ($val == $upcoming) ? 'selected="selected"' : '';?>><?php echo $name;?></option>
								<?php endforeach;?>
							</select>
						</div>
						<div class="controls controls-row">
							<label class="span3">Color: </label>
							<div class="span6">
								<div id="line-color-wrapper" data-color="<?php echo $lineColor;?>" class="input-append color">
									<input id="line-color" name="line_color" value="<?php echo $lineColor;?>" type="text" class="span2">
									<span class="add-on"><i style="background-color: <?php echo $lineColor;?>;"></i></span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
	var BASE_URL = '<?php echo sfConfig::get('app_domain_full');?>';
</script>
<?php 
use_javascript('/bundle/bootstrap/js/bootstrap.min.js');
use_javascript('/bundle/colorpicker/js/bootstrap-colorpicker.js');
use_javascript('/js/wix/settings.js');
?>