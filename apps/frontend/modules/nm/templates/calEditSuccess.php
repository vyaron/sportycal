<?php 

//jquery-ui.min.css
use_stylesheet('/bundle/jquery/themes/base/minified/jquery-ui.min.css');
use_stylesheet('/bundle/jquery/themes/base/minified/jquery.ui.datepicker.min.css');
use_stylesheet('/bundle/jquery-timepicker/css/jquery.timepicker.css');

use_stylesheet('/bundle/dhtmlxScheduler/codebase/dhtmlxscheduler.css');
use_stylesheet('/bundle/dhtmlxScheduler/codebase/dhtmlxscheduler_dhx_terrace.css');

use_stylesheet('/bundle/dhtmlxScheduler/codebase/ext/dhtmlxscheduler_datetime.css');
use_stylesheet('/bundle/dhtmlxScheduler/sources/ext/dhtmlxscheduler_inevermiss_quick_info.css');

use_stylesheet('/bundle/jquery-file-upload/css/jquery.fileupload-ui.css');
use_stylesheet('/css/neverMiss/calEdit.css');
?>

<div class="container">

<?php include_partial('formError', array('form' => $form)) ?>

<h2>Edit Your Calendar</h2>

<?php if ($tzFullName):?>
<h5>Timezone: <?php echo $tzFullName;?></h5>
<?php endif;?>

<div class="form-actions">
	<a class="continue-btn btn btn-success pull-right" href="#">Continue</a>
</div>


<div id="scheduler_here" class="dhx_cal_container" style="width: 100%; height:500px; line-height:normal;">
	<div class="dhx_cal_navline">
		<div class="dhx_cal_prev_button">&nbsp;</div>
		<div class="dhx_cal_next_button">&nbsp;</div>
		<div class="dhx_cal_today_button"></div>
		<div class="cal_import_button">Import</div>
		<div class="dhx_cal_date"></div>
		<div class="dhx_cal_tab" name="day_tab" style="right: 204px;"></div>
		<div class="dhx_cal_tab" name="week_tab" style="right: 140px;"></div>
		<div class="dhx_cal_tab" name="month_tab" style="right: 76px;"></div>
	</div>
	<div class="dhx_cal_header"></div>
	<div class="dhx_cal_data"></div>
</div>

<a id="clear-events" href="#clear-events-modal" role="button" data-toggle="modal"><i class="icon-trash"></i> Clear all Events</a>
 
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
		<p>Choose the file that contains your events. iNeverMiss Calendar can import event information in iCal:</p>
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


<form id="cal-form" method="POST">
	<?php echo $form['_csrf_token']->render();?>
	<input id="cal-id" type="hidden" name="id" value="<?php echo $cal->getId();?>">
	<fieldset>
		<legend>Calendar Information:</legend>
		
		<div class="row">
			<div class="span2"><?php echo $form['name']->renderLabel();?></div>
			<div class="span4"><?php echo $form['name']->render(array('class' => 'span4', 'placeholder' => 'Enter calendar name', 'required'=>'required', 'minlength'=>'3'));?></div>
		</div>
		<div class="row">
			<div class="span2"><?php echo $form['description']->renderLabel();?></div>
			<div class="span4"><?php echo $form['description']->render(array('class' => 'span4', 'placeholder' => 'Enter description'));?></div>
		</div>
		<div class="row">
			<div class="span2">Time Zone</div>
			<div class="span4"><?php echo $form['tz']->render(array('class' => 'span4'));?></div>
		</div>

		<div class="form-actions clearfix">
			<input type="submit" class="btn btn-success pull-right" value="Continue"/>
		</div>
	</fieldset>
</form>

</div>
<?php 
use_javascript('/bundle/jquery/js/jquery-ui-1.10.3.custom.min.js');
use_javascript('/bundle/jquery-timepicker/js/jquery.timepicker.min.js');

use_javascript('/bundle/dhtmlxScheduler/codebase/dhtmlxscheduler.js');

use_javascript('/bundle/dhtmlxScheduler/codebase/ext/dhtmlxscheduler_dhx_terrace.js');
//use_javascript('/bundle/dhtmlxScheduler/codebase/ext/dhtmlxscheduler_quick_info.js');
use_javascript('/bundle/dhtmlxScheduler/codebase/ext/dhtmlxscheduler_recurring.js');
use_javascript('/bundle/dhtmlxScheduler/codebase/ext/dhtmlxscheduler_minical.js');

use_javascript('/bundle/dhtmlxScheduler/sources/ext/dhtmlxscheduler_inevermiss_quick_info.js');
use_javascript('/bundle/dhtmlxScheduler/sources/ext/dhtmlxscheduler_datetime.js');

use_javascript('/bundle/jquery-plugin-validation/js/jquery.validate.min.js');
use_javascript('/bundle/jquery-file-upload/js/jquery.iframe-transport.js');
use_javascript('/bundle/jquery-file-upload/js/jquery.fileupload.js');
use_javascript('/js/neverMiss/calEdit.js');
?>
