<?php use_stylesheet('/css/niceUI.css');?>
<style>

    #event_name, #event_location, #event_description{width:440px;}
    
    #event_starts_at_month, #event_starts_at_day, #event_starts_at_year, #event_starts_at_hour, #event_starts_at_minute, #event_ends_at_month, #event_ends_at_day, #event_ends_at_year, #event_ends_at_hour, #event_ends_at_minute{ display: none;}
    
    #countryCodesDropDown, #countryCodesDropDown{margin-bottom: none;}
    
    #countryCodesList, #languageCodesList{display: block; list-style: none; margin-bottom: 15px;}
    #countryCodesList li, #languageCodesList li{ display: block; float: left; margin: 2px 5px; background-color: #eee; padding: 3px;}
</style>
<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<div class="boxForm rounded10 niceUI">
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
          	<input type="submit" value="Save" class="btn btn-success" /> &nbsp;&nbsp;
          	<a href="<?php echo url_for('cal/show?id='.$form->getObject()->getCalId()) ?>">Cancel</a>
          </center>
        </td>
      </tr>
    </tfoot>
    <tbody>
      	<?php echo $form['_csrf_token']->render();?>
      	<?php echo $form['id']->render();?>
      	<?php echo $form['cal_id']->render();?>
      	<?php echo $form['name']->renderRow();?>
      	<?php echo $form['location']->renderRow();?>
      	<?php echo $form['description']->renderRow();?>
      	
      	<?php if ($user->isMaster()):?>
      	<?php echo $form['tz_custom']->renderRow(array(), 'Timezone custom');?>
      	<?php endif;?>
      	
      	<?php echo $form['tz']->renderRow(array(), 'Timezones');?>
      	<tr>
  			<th><label for="eventShowTime"><?php echo __('Specific Hour')?>&nbsp;&nbsp;</label></th>
  			<td><input id="eventShowTime" type="checkbox" checked="checked"/></td>
		</tr>
      	<tr>
  			<th><label>Starts at</label></th>
  			<td>
  				<?php if ($form['starts_at']->hasError()): ?>
			    <br /><?php echo $form['starts_at']->renderError() ?>
			    <?php endif; ?>
			    
  				<?php echo $form['starts_at']->render(array('id' => 'startDate', 'class' => 'eDatePick')); ?>
  				
  				<img class="eDatePickTrigger" src="<?php echo url_for('/images/icons/16date.png')?>"/>
  			</td>
		</tr>
		<tr>
  			<th><label>Ends at</label></th>
  			<td>
  				<?php if ($form['ends_at']->hasError()): ?>
			    <br /><?php echo $form['ends_at']->renderError() ?>
			    <?php endif; ?>
			    
  				<?php echo $form['ends_at']->render(array('id' => 'endDate', 'class' => 'eDatePick')); ?>

  				<img class="eDatePickTrigger" src="<?php echo url_for('/images/icons/16date.png')?>"/>
  			</td>
		</tr>
		
		<tr>
  			<th><label>Country Codes</label></th>
  			<td>
  				<input id="countryCodes" type="hidden" name="event[countryCodes]" value="<?php echo $countryCodes?>"/>
  				<select id="countryCodesDropDown">
  					<option value="">Add country filter</option>
  					<?php foreach (LocationTable::getCountryOptions() as $code => $name):?>
  					<option value="<?php echo $code?>"><?php echo $code . ' - ' . $name?></option>
  					<?php endforeach;?>
  				</select>
  			</td>
		</tr>
		<tr>
			<td></td>
			<td>
				<ul id="countryCodesList" class="clearfix"></ul>
			</td>
		</tr>
		<tr>
  			<th><label>Language Codes</label></th>
  			<td>
  				<input id="languageCodes" type="hidden" name="event[languageCodes]" value="<?php echo $languageCodes?>"/>
  				<select id="languageCodesDropDown">
  					<option value="">Add language filter</option>
  					<?php foreach (Languages::getLanguagesOptions() as $code => $name):?>
  					<option value="<?php echo $code?>"><?php echo ($code . ' - ' . $name)?></option>
  					<?php endforeach;?>
  				</select>
  			</td>
		</tr>
		<tr>
			<td></td>
			<td>
				<ul id="languageCodesList" class="clearfix"></ul>
			</td>
		</tr>
		<?php $i=1; foreach ($tags as $key => $values): if ($key == 'languageCodes' || $key == 'countryCodes') continue;?>
			 <tr id="dummy_custom_field">
				<th><label>Custom <?php echo $i;?></label></th>
				<td>
					<input name="event[custom][<?php echo $i;?>][name]" type="text" value="<?php echo $key;?>" /><br />
					<textarea name="event[custom][<?php echo $i;?>][values]" rows="3" cols="30"><?php echo implode(',', $values->getRawValue());?></textarea>
				</td>
			</tr>
		<?php $i++; endforeach;?>
		<tr id="addCustomFieldBtnWrapper">
  			<td colspan="2"><a id="addCustomFieldBtn" class="btn btn-small" href="#">[+]&nbsp;&nbsp;Add Custom field</a></td>
		</tr>
    </tbody>
  </table>
</form>
</div>

<table style="display: none;">
	<tr id="dummy_custom_field">
		<th><label>Custom {CUSTOM_FIELD_NUM}</label></th>
		<td>
			<input name="event[custom][{CUSTOM_FIELD_NUM}][name]" type="text" placeholder="Enter key: CIDS" /><br />
			<textarea name="event[custom][{CUSTOM_FIELD_NUM}][values]" rows="3" cols="30" placeholder="Enter values with comma separator: 123,444,5556"></textarea>
		</td>
	</tr>
</table>

<?php sfContext::getInstance()->getResponse()->addJavascript('datepicker/datepicker.js'); ?>
<script type="text/javascript">
var gDatePicker = null;

var gCurrStartDate = '<?php echo (!empty($startsAt)) ? $startsAt : ''?>';
var gCurrEndDate = '<?php echo (!empty($endsAt)) ? $endsAt : ''?>';

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
		gDatePicker.options.allowEmpty = true;
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

function updateTagsInput(name){
	var el = $(name);
	if (el){
		var newValue = '';

		var liEls = $$('#' + name + 'List li');
		if (liEls.length){
			liEls.each(function(li){
				newValue += li.getAttribute('data-value') + ',';
			});

			newValue = newValue.substr(0, newValue.length - 1);
		}

		el.set('value', newValue);
	}
}

function setTagListItem(name, val){
	var liEl = new Element('li', {
		'data-value' : val,
		'text' : val + '  '
	});
	
	var removeEl = new Element('a', {
		'text' : '[X]',
		'href' : '#'
	});

	removeEl.inject(liEl);
	liEl.inject($(name + 'List'));

	removeEl.addEvent('click', function(e){
		e.stop();
		liEl.destroy();
		updateTagsInput(name);
	});
}

function setTagListItems(name){
	var el = $(name);
	if (el){
		var valStr = el.get('value');
		if (valStr){
			var vals = valStr.split(',');
			for ( var i = 0; i < vals.length; i++) {
				setTagListItem(name, vals[i]);
			}
		}
	}
}

function setTagDropDownEvent(name){
	setTagListItems(name);
	
	$(name + 'DropDown').addEvent('change', function(e){
		var val = this.get('value');
		if (val) setTagListItem(name, val);

		this.set('value', '');
		updateTagsInput(name);
	});
}

function setCustomFieldBtnEvent(){
	var addCustomFieldBtn = $('addCustomFieldBtn');
	if (addCustomFieldBtn){
		addCustomFieldBtn.addEvent('click', function(e){
			e.stop();

			var dummyEl = $('dummy_custom_field');
			var beforeEl = $('addCustomFieldBtnWrapper');
			
			if (dummyEl && beforeEl){
				var htmlStr = dummyEl.get('html').trim();
				var customFieldsCount = $$('#eventForm .customFields').length;

				htmlStr = htmlStr.replace(/{CUSTOM_FIELD_NUM}/g, (customFieldsCount + 1));

				var trEl = new Element('tr', {
					'class' : 'customFields',
					'html' : htmlStr
				});

				trEl.inject(beforeEl, 'before');
			}
		});
	}
}

function scrollToFirstError(){
	var els = $$('.error_list');
	if (els.length) new Fx.Scroll(window).toElement(els[0]);
}

window.addEvent('domready', function(){
	scrollToFirstError();
	
	setTagDropDownEvent('countryCodes');
	setTagDropDownEvent('languageCodes');
	
	setCustomFieldBtnEvent();
	
	var eventShowTime = $('eventShowTime');
	eventShowTime.addEvent('change', setDatePickers);

	setDatePickers();

	//Edit mode - got string time from server
	if (gCurrStartDate && gCurrEndDate) setDates(gCurrStartDate, gCurrEndDate);
	
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
		eventForm.addEvent('submit', function(e){
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