scheduler.locale.labels.to = 'To';

scheduler.form_blocks.datetime = {
	render:function(sns) {
		var cfg = scheduler.config;
		
		var start = '<input type="text" class="datepicker"/> <input type="text" class="timepicker"/>';
		var end = '<input type="text" class="timepicker"/> <input type="text" class="datepicker"/>';
		
		var height = 30;
		
		var fullDateHtml = '';
		if (cfg.full_day) {
			fullDateHtml = '<br/><label class="inline checkbox"><input class="full_date" type="checkbox"/>&nbsp;' + this.locale.labels.full_day + '</label>';
			height += 40;
		}
		
		
		
		return "<div style='height:" + height + "px;padding-top:0px;font-size:inherit;' class='dhx_section_datetime dhx_cal_ltext'>"+start+"<span style='font-weight:normal; font-size:10pt;'>&nbsp;" + this.locale.labels.to + "&nbsp;</span>"+end+fullDateHtml+"</div>";
	},
	
	set_value:function(node,value,ev,config){
		var sectionEl = jQuery(node);
		
		var datepickers = sectionEl.find('.datepicker');
		var timepickers = sectionEl.find('.timepicker');
		var fullDateCheckbox = sectionEl.find('.full_date');
		
		//run one time!
		if (!node.is_prepare_events){
			//Set datepicker, timepicker
			var datepickerOptions = {
				dateFormat : 'mm/dd/yy'
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
		
		return {
			start_date : new Date(ev.start_date),
			end_date : new Date(ev.end_date)				
		};
	},
	focus:function(node){
		return;
		
		scheduler._focus(jQuery(node).find('.datepicker')[0]); 
	}
}