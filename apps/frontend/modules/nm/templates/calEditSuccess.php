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

<h2><span class="color-y">Edit</span> Your Calendar. <a class="pull-right continue-btn btn btn-success hidden-popup" href="#"><span>Continue</span></a></h2>

<table id="cal-edit-wrapper">
	<tr>
		<td>
			<form id="cal-form" method="POST">
				<?php echo $form['_csrf_token']->render();?>
				<input id="cal-id" type="hidden" name="id" value="<?php echo $cal->getId();?>">
				<table>
					<tr>
						<td><?php echo $form['name']->render(array('placeholder' => 'Enter calendar name here', 'required'=>'required', 'minlength'=>'3', 'autofocus' => "autofocus"));?></td>
						<td class="clearfix">
							<?php echo $form['description']->render(array('placeholder' => 'Optional description'));?>
<!-- 							<input id="cal_description" type="text" name="cal[description]" placeholder="OPTIONAL DESCRIPTION"/> -->
							<i id="desc-info" class="icon-question-sign"></i>
						</td>
						<td><?php echo $form['tz']->render();?></td>
						<td class="pl0">
							<div class="visible-popup">
								<a id="save-btn" class="pull-right continue-btn btn btn-success ml15" href="#" style="visibility:hidden;"><span>Save</span></a>
							</div>
						</td>
					</tr>
				</table>
			</form>
			
			<hr class="visible-popup"/>
			

			<h4 class="hidden-phone clearfix">Add events to your calendar by clicking on a day / import from other calenders <a id="cal-import-btn" class="btn btn-mini pull-right" href="#">Import</a></h4>
		</td>
		<td class="right-col hidden-popup">&nbsp;</td>
	</tr>
	<tr class="hidden-phone">
		<td>
			<div id="scheduler_here" class="dhx_cal_container clearfix" style="line-height:normal;">
				<div id="cal-header" class="dhx_cal_navline">
					<div class="dhx_cal_prev_button">&nbsp;</div>
					<div class="dhx_cal_date"></div>
					<div class="dhx_cal_next_button">&nbsp;</div>
				</div>
				<div class="dhx_cal_header">Ido</div>
				<div class="dhx_cal_data"></div>
				<div class="cb"></div>
			</div>
			
			
			
			<div id="cal-bottom-btns" class="clearfix">
				<a id="cal-today-btn" class="btn btn-mini pull-left" data-type="today" href="#">Today</a>
				
				<a class="btn btn-mini cal-btn-view btn-success pull-left" data-type="month" href="#">Month</a>
				<a class="btn btn-mini cal-btn-view pull-left" data-type="week" href="#">Week</a>
				<a class="btn btn-mini cal-btn-view pull-left" data-type="day" href="#">Day</a>

				<a id="clear-events" href="#clear-events-modal" role="button" data-toggle="modal">Clear all Events</a>
			</div>
		</td>
		<td class="right-col hidden-popup">
			<div id="events-wrapper">
				<h3>My Events</h3>
				<ul id="event-list"></ul>
			</div>
			<a class="continue-btn btn btn-success" href="#"><span>Continue</span></a>
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
		<button id="clear-events-submit" class="btn btn-primary">Yes</button>
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

//use_javascript('/bundle/dhtmlxScheduler/sources/dhtmlxscheduler.js');
use_javascript('/bundle/dhtmlxScheduler/codebase/dhtmlxscheduler.js');

//use_javascript('/bundle/dhtmlxScheduler/sources/ext/dhtmlxscheduler_dhx_terrace.js');
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
