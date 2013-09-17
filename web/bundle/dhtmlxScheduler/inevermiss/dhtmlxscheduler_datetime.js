scheduler.locale.labels.from = 'From';
scheduler.locale.labels.to = 'To';

scheduler.config.default_reminder = 1;

scheduler.form_blocks.datetime = {
	render:function(sns) {
		var cfg = scheduler.config;
		
		var start = '<input type="text" class="datepicker"/> <input type="text" class="timepicker"/>';
		var end = '<input type="text" class="datepicker"/> <input type="text" class="timepicker"/>';
		
		var height = 70;
		
		var fullDateHtml = '';
		if (cfg.full_day) {
			fullDateHtml = '<label class="full_date_wrapper pull-right" class="inline checkbox">' + this.locale.labels.full_day + '&nbsp;<input class="full_date" type="checkbox"/></label>';
		}
		
		var reminderHtml = '';
		if (cfg.reminder) {
			reminderHtml = '<select class="reminder pull-right" name="reminder" title="reminder"><option value="1">on start</option><option value="60">1 Hours</option><option value="240">4 Hours</option><option value="1440">1 Day</option></select>';
		}

		return "<div style='height:" + height + "px; font-size:inherit;' class='dhx_section_datetime dhx_cal_ltext'><label>" + this.locale.labels.from + "</label>"+start+fullDateHtml+"<br/><label>" + this.locale.labels.to + "</label>"+end+reminderHtml+"</div>";
		return html;
	},
	
	set_value:function(node,value,ev,config){
		var sectionEl = jQuery(node);
		
		var datepickers = sectionEl.find('.datepicker');
		var timepickers = sectionEl.find('.timepicker');
		var fullDateCheckbox = sectionEl.find('.full_date');
		
		var reminder = sectionEl.find('.reminder');
		
		if (ev.reminder) ev.event_reminder = ev.reminder;
		reminder.val(ev.event_reminder ? ev.event_reminder : scheduler.config.default_reminder);
		
		//run one time!
		if (!node.is_prepare_events){
			//Set datepicker, timepicker
			var datepickerOptions = {
				dateFormat : 'dd M yy'
			};
			
			if (ev.start_date) datepickerOptions.defaultDate = ev.start_date;
			datepickerOptions.onClose = function(selectedDate){
				jQuery(datepickers[1]).datepicker('setDate', selectedDate);
			};
			jQuery(datepickers[0]).datepicker(datepickerOptions);
			
			if (ev.end_date) datepickerOptions.defaultDate = ev.end_date;
			datepickerOptions.onClose = function(selectedDate){
				if (jQuery(datepickers[0]).datepicker('getDate') > jQuery(datepickers[1]).datepicker('getDate')) jQuery(datepickers[0]).datepicker('setDate', selectedDate);
			};
			jQuery(datepickers[1]).datepicker(datepickerOptions);
			
			
			var timepickerOptions = {
				timeFormat: 'H:i'
			};
			
			//TODO - validation + more autocomplete logic
			timepickers.timepicker(timepickerOptions);
			jQuery(timepickers[0]).on('changeTime', function(){
				var startTime = jQuery(timepickers[0]).timepicker('getTime');
				jQuery(timepickers[1]).timepicker('setTime', scheduler.date.add(startTime, (2 * scheduler.config.time_step),"minute"));
			});
			
			jQuery(timepickers[1]).on('changeTime', function(){
				var startTime = jQuery(timepickers[0]).timepicker('getTime');
				var endTime = jQuery(timepickers[1]).timepicker('getTime');
				var differentDates = jQuery(datepickers[1]).datepicker('getDate') > jQuery(datepickers[0]).datepicker('getDate');
				
				if (endTime < startTime && !differentDates) jQuery(timepickers[0]).timepicker('setTime', scheduler.date.add(endTime, (-2 * scheduler.config.time_step),"minute"));
			});
			
			
			
			//Set full date checkbox event
			fullDateCheckbox.change(function(e){
				var startDate = jQuery(datepickers[0]).datepicker('getDate');
				var endDate = jQuery(datepickers[1]).datepicker('getDate');

				if (jQuery(this).is(':checked')) {
					if (startDate.getDate() == endDate.getDate() && startDate.getMonth() == endDate.getMonth() && startDate.getFullYear() == endDate.getFullYear()) jQuery(datepickers[1]).datepicker('setDate', scheduler.date.add(startDate, 1,"day"));
					timepickers.hide();
				} else timepickers.show();
			});
			
			node.is_prepare_events = true;
		}
		
		//Set datepicker, timepicker values
		jQuery(datepickers[0]).datepicker('setDate', ev.start_date);
		jQuery(datepickers[1]).datepicker('setDate', ev.end_date);

		jQuery(timepickers[0]).timepicker('setTime', ev.start_date);
		jQuery(timepickers[1]).timepicker('setTime', ev.end_date);
		
		//Set full_date value
		if (scheduler.date.time_part(ev.start_date)===0 && scheduler.date.time_part(ev.end_date)===0) fullDateCheckbox.attr('checked', 'checked');
		else fullDateCheckbox.removeAttr('checked');
		
		fullDateCheckbox.change();
	},
	
	get_value:function(node, ev, config) {
		var sectionEl = jQuery(node);
		
		var datepickers = sectionEl.find('.datepicker');
		var timepickers = sectionEl.find('.timepicker');
		
		var startDatePickerDate = jQuery(datepickers[0]).datepicker('getDate');
		var endDatePickerDate = jQuery(datepickers[1]).datepicker('getDate');
		var startTimePickerDate = jQuery(timepickers[0]).timepicker('getTime');
		var endTimePickerDate = jQuery(timepickers[1]).timepicker('getTime');
		
		var isFullDayEvent = false;
		if (isFullDayEvent = sectionEl.find('.full_date:checked').length){
			ev.start_date = new Date(startDatePickerDate.getFullYear(), startDatePickerDate.getMonth(), startDatePickerDate.getDate(), 0, 0, 0, 0);
			ev.end_date = new Date(endDatePickerDate.getFullYear(), endDatePickerDate.getMonth(), endDatePickerDate.getDate(), 0, 0, 0, 0);
		} else {
			ev.start_date = new Date(startDatePickerDate.getFullYear(), startDatePickerDate.getMonth(), startDatePickerDate.getDate(), startTimePickerDate.getHours(), startTimePickerDate.getMinutes(), 0, 0);
			ev.end_date = new Date(endDatePickerDate.getFullYear(), endDatePickerDate.getMonth(), endDatePickerDate.getDate(), endTimePickerDate.getHours(), endTimePickerDate.getMinutes(), 0, 0);
		}
		
		
		if (ev.end_date <= ev.start_date){
			if (isFullDayEvent)  ev.end_date=scheduler.date.add(ev.start_date,1,"day");
			else ev.end_date=scheduler.date.add(ev.start_date,scheduler.config.time_step,"minute");
		}
		
		var reminder = sectionEl.find('.reminder').val();
		
		ev.reminder = reminder ? reminder : null;
		
		return {
			start_date : new Date(ev.start_date),
			end_date : new Date(ev.end_date)
		};
	},
	focus:function(node){
		return;
		
		scheduler._focus(jQuery(node).find('.datepicker')[0]); 
	}
};