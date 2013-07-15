<?php 

//jquery-ui.min.css
use_stylesheet('/bundle/jquery/themes/base/minified/jquery-ui.min.css');
use_stylesheet('/bundle/jquery/themes/base/minified/jquery.ui.datepicker.min.css');
use_stylesheet('/bundle/jquery-timepicker/css/jquery.timepicker.css');

use_stylesheet('/bundle/dhtmlxScheduler/codebase/dhtmlxscheduler.css');
use_stylesheet('/bundle/dhtmlxScheduler/codebase/dhtmlxscheduler_dhx_terrace.css');

use_stylesheet('/bundle/dhtmlxScheduler/inevermiss/dhtmlxscheduler_datetime.css');
//use_stylesheet('/bundle/dhtmlxScheduler/inevermiss/dhtmlxscheduler_inevermiss_quick_info.css');

use_stylesheet('/bundle/jquery-file-upload/css/jquery.fileupload-ui.css');
use_stylesheet('/css/neverMiss/calEdit.css');
?>

<div class="container">

<?php include_partial('formError', array('form' => $form)) ?>

<h2><span class="color-y">Edit</span> Your Calendar. <a class="pull-right continue-btn btn btn-success" href="#">Continue</a></h2>

<?php if ($tzFullName):?>
<h5>Timezone: <?php echo $tzFullName;?></h5>
<?php endif;?>

<table id="cal-edit-wrapper">
	<tr>
		<td>
			<form id="cal-form" method="POST">
				<?php echo $form['_csrf_token']->render();?>
				<input id="cal-id" type="hidden" name="id" value="<?php echo $cal->getId();?>">
				
				<table>
					<tr>
						<td><?php echo $form['name']->render(array('placeholder' => 'ENTER CALENDAR NAME HERE', 'required'=>'required', 'minlength'=>'3', 'autofocus' => "autofocus"));?></td>
						<td><input id="cal_description" type="text" name="cal[description]" placeholder="OPTIONAL DESCRIPTION"/></td>
						<td><?php echo $form['tz']->render();?></td>
					</tr>
				</table>
			</form>
			
			<h4>Add events to your calendar by clicking on a day</h4>
		</td>
		<td class="right-col">&nbsp;</td>
	</tr>
	<tr class="hidden-phone">
		<td>
			<div id="scheduler_here" class="dhx_cal_container" style="line-height:normal;">
				<div id="cal-header" class="dhx_cal_navline">
					<div class="dhx_cal_prev_button">&nbsp;</div>
					<div class="dhx_cal_date"></div>
					<div class="dhx_cal_next_button">&nbsp;</div>
				</div>
				<div class="dhx_cal_header">Ido</div>
				<div class="dhx_cal_data"></div>
			</div>
			<div id="cal-bottom-btns" class="clearfix">
				<a class="cal-btn cal-btn-view selected pull-left" data-type="month" href="#">Month</a>
				<a class="cal-btn cal-btn-view pull-left" data-type="week" href="#">Week</a>
				<a class="cal-btn cal-btn-view pull-left" data-type="day" href="#">Day</a>
				
				<a id="cal-today-btn" class="cal-btn pull-left" data-type="today" href="#">Today</a>
				<a id="cal-import-btn" class="cal-btn pull-left" href="#">Import</a>
				
				<a id="clear-events" href="#clear-events-modal" role="button" data-toggle="modal"><i class="icon-trash"></i> Clear all Events</a>
			</div>
		</td>
		<td class="right-col">
			<div id="events-wrapper">
				<h3>My Events</h3>
				<ul id="event-list"></ul>
			</div>
			<a class="continue-btn btn btn-success" href="#">Continue</a>
		</td>
	</tr>
</table>
 
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

<div id="cal-import-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h3 id="myModalLabel">Import calendar</h3>
	</div>
	<div class="modal-body">
		<p>Choose the file that contains your events.<br/>iNeverMiss Calendar can import event information in iCal:</p>
		<span class="btn btn-success fileinput-button">
			<span id="ical-fileupload-loading-label" style="display:none;"><i class="icon-loading icon-yellow"></i> Uploading...</span>
			<span id="ical-fileupload-label" ><i class="icon-plus icon-yellow"></i> Select Calendar *.ics</span>
			<input id="ical-fileupload" type="file" name="file" data-url="<?php echo url_for('nm/importCal/?id=' . $cal->getId())?>" accept="text/calendar"/>
		</span>

	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
	</div>
</div>

</div>
<?php 
use_javascript('/bundle/jquery/js/jquery-ui-1.10.3.custom.min.js');
use_javascript('/bundle/jquery-timepicker/js/jquery.timepicker.min.js');

use_javascript('/bundle/dhtmlxScheduler/codebase/dhtmlxscheduler.js');

use_javascript('/bundle/dhtmlxScheduler/codebase/ext/dhtmlxscheduler_dhx_terrace.js');
//use_javascript('/bundle/dhtmlxScheduler/codebase/ext/dhtmlxscheduler_quick_info.js');
use_javascript('/bundle/dhtmlxScheduler/codebase/ext/dhtmlxscheduler_recurring.js');
//use_javascript('/bundle/dhtmlxScheduler/codebase/ext/dhtmlxscheduler_minical.js');

//use_javascript('/bundle/dhtmlxScheduler/inevermiss/dhtmlxscheduler_inevermiss_quick_info.js');
use_javascript('/bundle/dhtmlxScheduler/inevermiss/dhtmlxscheduler_datetime.js');

use_javascript('/bundle/jquery-plugin-validation/js/jquery.validate.min.js');
use_javascript('/bundle/jquery-file-upload/js/jquery.iframe-transport.js');
use_javascript('/bundle/jquery-file-upload/js/jquery.fileupload.js');
use_javascript('/js/neverMiss/calEdit.js');
?>
