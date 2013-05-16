<?php 
use_stylesheet('/bundle/dhtmlxScheduler/codebase/dhtmlxscheduler.css');
use_stylesheet('/bundle/dhtmlxScheduler/codebase/dhtmlxscheduler_dhx_terrace.css');
use_stylesheet('/css/neverMiss/calEdit.css');
?>

<h2>Edit Your Calendar</h2>

<div id="scheduler_here" class="dhx_cal_container" style="width: 100%; height: 100%; min-height:500px; line-height:normal;">
	<div class="dhx_cal_navline">
		<div class="dhx_cal_prev_button">&nbsp;</div>
		<div class="dhx_cal_next_button">&nbsp;</div>
		<div class="dhx_cal_today_button"></div>
		<div class="dhx_cal_date"></div>
		<div class="dhx_cal_tab" name="day_tab" style="right: 204px;"></div>
		<div class="dhx_cal_tab" name="week_tab" style="right: 140px;"></div>
		<div class="dhx_cal_tab" name="month_tab" style="right: 76px;"></div>
	</div>
	<div class="dhx_cal_header"></div>
	<div class="dhx_cal_data"></div>
</div>

<a id="clear-events" href="#clear-events-modal" role="button" data-toggle="modal"><i class="icon-trash"></i> Clear all Events</a>
 
<!-- Modal -->
<div id="clear-events-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h3 id="myModalLabel">Clear Events</h3>
	</div>
	<div class="modal-body">
		<p>Are you sure you want to delete all events from calendar?</p>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">No</button>
		<a class="btn btn-primary" href="<?php echo url_for('nm/calEventsClear/?id=' . $cal->getId()) ?>">Yes</a>
	</div>
</div>


<form id="cal-form" method="POST">
	<input id="cal-id" type="hidden" name="id" value="<?php echo $cal->getId();?>">
	<fieldset>
		<legend>Calendar Information:</legend>
		<div class="row">
			<div class="span2">
				<label for="name">Name:</label>
			</div>
			<div class="span6">
				<input class="span6" type="text" name="name" placeholder="Enter Calendar Name" value="<?php echo $cal->getName();?>"/>
				<span class="help-block">This name apear in subscipe application (Outlook, Google Calendar..)</span>
			</div>
		</div>
		
		<div class="row">
			<div class="span2">
				<label for="description">Description:</label>
			</div>
			<div class="span6">
				<textarea class="span6" type="text" name="description" placeholder="Enter Calendar Description"><?php echo $cal->getDescription();?></textarea>
			</div>
		</div>
		
		<div class="form-actions clearfix">
			<input type="submit" class="btn btn-success pull-right" value="Continue"/>
		</div>
	</fieldset>
</form>

<?php 
use_javascript('/bundle/bootstrap/js/bootstrap.min.js');
use_javascript('/bundle/dhtmlxScheduler/codebase/dhtmlxscheduler.js');
use_javascript('/bundle/dhtmlxScheduler/codebase/ext/dhtmlxscheduler_dhx_terrace.js');
use_javascript('/bundle/dhtmlxScheduler/codebase/ext/dhtmlxscheduler_quick_info.js');
use_javascript('/bundle/dhtmlxScheduler/codebase/ext/dhtmlxscheduler_recurring.js');
use_javascript('/bundle/dhtmlxScheduler/codebase/ext/dhtmlxscheduler_minical.js');
use_javascript('/js/neverMiss/calEdit.js');
?>
