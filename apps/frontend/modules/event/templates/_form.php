<style>

    #event_name {
        width:500px;
    }
    #event_location {
        width:500px;
    }
    #event_description {
        width:500px;
    }
    
    #event_starts_at_month, #event_starts_at_day, #event_starts_at_year, #event_starts_at_hour, #event_starts_at_minute, #event_ends_at_month, #event_ends_at_day, #event_ends_at_year, #event_ends_at_hour, #event_ends_at_minute{ display: none;}
    
</style>
<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<div class="boxForm rounded10">
<form id="eventForm" action="<?php echo url_for('event/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table style="text-align:left">
    <tfoot>
      <tr>
        <td colspan="2">
          <center>
          	<br/>
          	<input type="submit" value="Save" /> &nbsp;&nbsp;
          	<a href="<?php echo url_for('cal/show?id='.$form->getObject()->getCalId()) ?>">Cancel</a>
          </center>
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form ?>
      	<tr>
  			<th><label for="eventShowTime"><?php echo __('Time / No time?')?>&nbsp;&nbsp;</label></th>
  			<td><input id="eventShowTime" type="checkbox" checked="checked"/></td>
		</tr>
      	<tr>
  			<th><label>Starts at</label></th>
  			<td><input id="startDate" class="eDatePick" type="text" name="event[starts_at]"/><img class="eDatePickTrigger" src="<?php echo url_for('/images/icons/16date.png')?>"/></td>
		</tr>
		<tr>
  			<th><label>Ends at</label></th>
  			<td><input id="endDate" class="eDatePick" type="text" name="event[ends_at]" /><img class="eDatePickTrigger" src="<?php echo url_for('/images/icons/16date.png')?>"/></td>
		</tr>
    </tbody>
  </table>
</form>
</div>

<?php sfContext::getInstance()->getResponse()->addJavascript('datepicker/datepicker.js'); ?>
<script type="text/javascript">
var gDatePicker = null;

var gCurrStartDate = '<?php echo (!empty($startsAt)) ? $startsAt : ''?>';
var gCurrEndDate = '<?php echo (!empty($endsAt)) ? $endsAt : ''?>';

/*
function setEndDate(dateVal){
	var showEventTime = true;
	var eventShowTime = $('eventShowTime');
	if (eventShowTime){
		showEventTime = eventShowTime.get('checked');
	}

	var dateFormat = 'd-m-Y H:i';
	if (!showEventTime) {
		dateFormat = 'd-m-Y';
	}

	
	var endDate = $('endDate');
	
	gCurrEndDate = dateVal; // + (60 * 60);
	endDate.set('value', gCurrEndDate);
	
	var endDateDatePicker = endDate.getParent().getElement('.eDatePick:not(#endDate)');
	if (endDateDatePicker){
		var eDate = new Date(gCurrEndDate * 1000);
		
		var endDateDatePickerVal = gDatePicker.format(eDate, dateFormat);
		endDateDatePicker.set('value', endDateDatePickerVal);
	}
}

function updateDates(){
	var startDate = $('startDate');
	var endDate = $('endDate');
	
	if (startDate && endDate){
		var startDateVal = startDate.get('value');
		if (startDateVal){
			startDateVal = startDateVal.toInt();
			if (gCurrStartDate != startDateVal && startDateVal>0){
				setEndDate(startDateVal);
				
				gCurrStartDate = startDateVal;
			}
		}
		
		var endDateVal = endDate.get('value');
		if (endDateVal){
			endDateVal = endDateVal.toInt();
			
			if (gCurrEndDate != endDateVal && gCurrEndDate>0){
				if (endDateVal < gCurrStartDate){
					alert('Incorrect End Date');
					setEndDate(gCurrStartDate);
				} else {
					gCurrEndDate = endDateVal;
				}
			}
		}
		
	}
}
*/
function showTime(){
	var showEventTime = true;
	var eventShowTime = $('eventShowTime');
	if (eventShowTime){
		showEventTime = eventShowTime.get('checked');
	}

	return showEventTime;
}

function getFormat(){
	var showEventTime = showTime();

	var dateFormat = 'd-m-Y H:i';
	if (!showEventTime) {
		dateFormat = 'd-m-Y';
	}

	return dateFormat;
}

function setDatePickers(){
	var showEventTime = showTime();
	var dateFormat = getFormat();

	if (gDatePicker){
		gDatePicker.options.timePicker = showEventTime;
		gDatePicker.options.format = dateFormat;
	} else {
		//create Date picker
		gDatePicker = new DatePicker('.eDatePick', {
			pickerClass: 'datepicker_dashboard',
			toggleElements : '.eDatePickTrigger',
			timePicker: showEventTime, 
			format: dateFormat,
			onClose : setDates,
			minDate : {date: '01-01-2006', format: 'd-m-Y'},
			maxDate : {date: '01-01-2016', format: 'd-m-Y'},
			allowEmpty: true
		});
	}
}

function setDates(startStr, endStr){
	var startDate = $('startDate');
	var endDate = $('endDate');
	
	if (!(startStr && endStr)){
		startStr = startDate.getParent().getElement('.eDatePick:not(#startDate)').get('value');
		endStr = endDate.getParent().getElement('.eDatePick:not(#endDate)').get('value');
	}
	
	if (startDate && endDate){
		var dateFormat = getFormat();

		//Fix unformat bug (auto complete H:i:s from local pc time)
		if (dateFormat == 'd-m-Y H:i'){
			addToStr = ':00';
		} else {
			addToStr = ' 00:00:00';
		}
		
		sDate = gDatePicker.unformat(startStr + addToStr, 'd-m-Y H:i:s');
		eDate = gDatePicker.unformat(endStr + addToStr, 'd-m-Y H:i:s');

		sDate = (sDate.getTime() / 1000).toInt();
		eDate = (eDate.getTime() / 1000).toInt();

		//Copy Starts At to Ends At
		if ((gCurrStartDate > 0 && gCurrStartDate != sDate) || (eDate < sDate)){
			endStr = startStr;
			eDate = sDate;
		}

		startDate.set('value', sDate);
		var startDateDatePicker = startDate.getParent().getElement('.eDatePick:not(#startDate)');
		if (startDateDatePicker){
			startDateDatePicker.set('value', startStr);
		}
		
		endDate.set('value', eDate);
		var endDateDatePicker = endDate.getParent().getElement('.eDatePick:not(#endDate)');
		if (endDateDatePicker){
			endDateDatePicker.set('value', endStr);
		}

		gCurrStartDate = sDate;
		gCurrEndDate = eDate;
	}
}

window.addEvent('domready', function(){
	var eventShowTime = $('eventShowTime');
	eventShowTime.addEvent('change', setDatePickers);
	
	setDatePickers();

	//Edit mode - got string time from server
	setDates(gCurrStartDate, gCurrEndDate);

	//Ido : datePicker used 'change' event on this elements
	var startDate = $('startDate');
	if (startDate){
		var startDateVisual = startDate.getParent().getElement('.eDatePick:not(#startDate)');
		startDateVisual.addEvent('blur', setDates);
	}

	var endDate = $('endDate');
	if (endDate){
		var endDateVisual = endDate.getParent().getElement('.eDatePick:not(#endDate)');
		endDateVisual.addEvent('blur', setDates);
	}

	//Send time as string
	var eventForm = $('eventForm');
	if (eventForm){
		eventForm.addEvent('submit', function(){
			var showEventTime = true;
			var eventShowTime = $('eventShowTime');
			if (eventShowTime){
				showEventTime = eventShowTime.get('checked');
			}

			var dateFormat = 'Y-m-d H:i:s';
			if (!showEventTime) {
				dateFormat = 'Y-m-d';
			}
			
			var startDate = $('startDate');
			var endDate = $('endDate');
			if (startDate && endDate){
				var startDateVal = startDate.get('value');
				if (startDateVal){
					startDateVal = startDateVal.toInt() * 1000;
					var sDateStr = gDatePicker.format(new Date(startDateVal), dateFormat);
					startDate.set('value', sDateStr);
				}

				var endDateVal = endDate.get('value');
				if (endDateVal){
					endDateVal = endDateVal.toInt() * 1000;
					var eDateStr = gDatePicker.format(new Date(endDateVal), dateFormat);
					endDate.set('value', eDateStr);
				}
			}
		});
	}
	
});
</script>