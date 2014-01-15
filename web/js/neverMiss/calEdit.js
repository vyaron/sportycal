function refreshOpenerWidget(){
	if (opener && opener.refreshWidget) opener.refreshWidget();
}

function reloadCalendar(){
	scheduler.clearAll();
	scheduler.load("/nm/calEvents/?id=" + gCalId, 'json');
}

function isAllDay(ev){
	return (scheduler.date.time_part(ev.start_date)===0 && scheduler.date.time_part(ev.end_date)===0) ? true : false;
}

function setEventList(){
	refreshOpenerWidget();
	
	var html = '';
	
	var events = [];
	jQuery.extend(events, scheduler.get_visible_events());
	
	var minDate = new Date(scheduler._min_date);
	if (events.length){
		var dayKeys = [];
		var dayKeyToEvents = [];
		
		while(minDate < scheduler._max_date){
			//var dayKey = minDate.getDate() + '.' + minDate.getMonth() + '.' + minDate.getFullYear();
			var dayKey = $.datepicker.formatDate( "dd M yy", minDate);
			dayKeys.push(dayKey); // Saving the order
			
			var nextDate = new Date(minDate.getFullYear(), minDate.getMonth(), minDate.getDate()+1);
			for ( var i = 0; i < events.length; i++) {
				var event = events[i];
				if ((event.start_date >= minDate && event.start_date < nextDate)
					|| (event.start_date <= minDate && event.end_date > minDate)){
					if (! (dayKey in dayKeyToEvents)) dayKeyToEvents[dayKey] = [];
					
					dayKeyToEvents[dayKey].push(event);
				}
			}
			
			minDate = nextDate;
		}
		
		
		for ( var i = 0; i < dayKeys.length; i++) {
			if (dayKeys[i] in dayKeyToEvents){
				html += '<li><strong>' + dayKeys[i] + '</strong>';
				
				dayKeyToEvents[dayKeys[i]].sort(function(ev1, ev2){
					return isAllDay(ev1) ? -1 : (scheduler.date.time_part(ev1.start_date) - scheduler.date.time_part(ev2.start_date));
				});
				
				for ( var j = 0; j < dayKeyToEvents[dayKeys[i]].length; j++) {
					var ev = dayKeyToEvents[dayKeys[i]][j];
					
					var t = '';
					if (!(isAllDay(ev))) t = scheduler.date.to_fixed(ev.start_date.getHours()) +':'+ scheduler.date.to_fixed(ev.start_date.getMinutes()) + '&nbsp;';
					
					html += '<div><a data-event-id="' + dayKeyToEvents[dayKeys[i]][j].id + '" href="#">' + t + dayKeyToEvents[dayKeys[i]][j].text + '</a></div>';
				}
				
				html += '</li>';
			}
		}
		

	} else {
		html = '<li>No Events...</li>';
	}
	
	jQuery('#event-list').html(html);
}

function changeCalView(e){
	e.preventDefault();
	
	$('.cal-btn-view').removeClass('btn-success');
	
	var btn = $(this);
	btn.addClass('btn-success');
	
	var viewType = btn.attr('data-type');
	scheduler.updateView(null, viewType);
}

function showMaxEventsModal(){
	jQuery('#maxEventsModal').modal();
}

function loadCalendar(){
	scheduler.xy.nav_height = 53;
	scheduler.xy.lightbox_additional_height = 70;
	
	scheduler.locale.labels.full_day = 'All Day';
	scheduler.locale.labels.section_location = "Location";
	scheduler.locale.labels.section_name = "Event Name";
	//scheduler.locale.labels.new_event = "TYPE THE EVENT NAME HERE";
	
	//scheduler.config.touch = "force";
	scheduler.config.xml_date = "%Y-%m-%d %H:%i";

	
	scheduler.xy.scroll_width = 0;
	
	scheduler.config.prevent_cache = true;
	scheduler.config.first_hour = 10;
	
	scheduler.config.details_on_create = true;
	scheduler.config.details_on_dblclick = true;
	scheduler.config.multi_day = true;
	
	scheduler.config.event_duration = 60; //specify event duration in munites for auto end time
	scheduler.config.auto_end_date = true;
	
	scheduler.config.full_day = true;
	scheduler.config.reminder = true;
	
	scheduler.config.buttons_right=["dhx_save_btn","dhx_cancel_btn"];
	scheduler.config.buttons_left=["dhx_delete_btn"];
	
	scheduler.config.time_step = 30;
	
	scheduler.config.wide_form=false;
	
	//scheduler.config.server_utc = true; //convert server side dates from utc to local timezone, and backward during data sending to server;
	
	
	// Recurring events
	/*
	scheduler.attachEvent("onTemplatesReady", function(){
		var lightbox_form = scheduler.getLightbox(); // this will generate lightbox form
		var inputs = lightbox_form.getElementsByTagName('input');
		var date_of_end = null;
		for (var i=0; i<inputs.length; i++) {
			if (inputs[i].name == "date_of_end") {
				date_of_end = inputs[i];
				break;
			}
		}

		var repeat_end_date_format = scheduler.date.date_to_str("%d.%m.%Y");
		var show_minical = function(){
			if (scheduler.isCalendarVisible())
				scheduler.destroyCalendar();
			else {
				scheduler.renderCalendar({
					position:date_of_end,
					date:scheduler._date,
					navigation:true,
					handler:function(date,calendar) {
						date_of_end.value = repeat_end_date_format(date);
						scheduler.destroyCalendar();
					}
				});
			}
		};
		date_of_end.onclick = show_minical;
	});
	*/
	
	//Lightbox (event details) - hide delete on new event
	scheduler.attachEvent("onLightbox", function (id){
		var box = this.getLightbox();
		var deleteBtn = jQuery(box).find('.dhx_delete_btn_set');
		
		if (this._new_event) deleteBtn.hide();
		else deleteBtn.show();
	});
	
	scheduler.attachEvent("onClick", function(id, e){
		if (id) scheduler.showLightbox(id);
		
		return false;
	});
	
	scheduler.attachEvent("onEmptyClick", function (date, e){
		var src = e.target|| e.srcElement;
		if (this.config.readonly) return;
		var name = src.className.split(" ")[0];
		
		switch(name){
			case "dhx_scale_holder":
			case "dhx_scale_holder_now":
			case "dhx_month_body":
			case "dhx_wa_day_data":
			case "dhx_marked_timespan":
				//If lightbox not open allready (event drag)
				if (!this._lightbox_id) this.addEventNow(date,null,e);
				break;
		}
	});
	
	
	scheduler.attachEvent('onXLE', setEventList);
	scheduler.attachEvent('onViewChange', setEventList);
	scheduler.attachEvent('onEventIdChange', setEventList);
	scheduler.attachEvent('onEventChanged', setEventList);
	scheduler.attachEvent('onEventDeleted', setEventList);
	
	scheduler.attachEvent('onViewChange', function(){
		if (jQuery('#scheduler_here .dhx_now, #scheduler_here .dhx_scale_holder_now').length) jQuery('#cal-today-btn').css('visibility' , 'hidden');
		else jQuery('#cal-today-btn').css('visibility' , 'visible');
	});

	
	jQuery('#event-list').on('click', 'a[data-event-id]', function(e){
		e.preventDefault();
		var eventId = jQuery(this).attr('data-event-id');
		scheduler.showLightbox(eventId);
	});
	
	scheduler.config.lightbox.sections = [ {
		name : "name",
		placeholder : "Type the event name here",
		height : 22,
		map_to : "text",
		type : "text",
		focus : true
	}, {
		name : "time",
		height : 110,
		type : "datetime",
		map_to : "auto"
	}, {
		name : "description",
		placeholder : "Description",
		height : 52,
		map_to : "details",
		type : "textarea"
	}, {
		name : "location",
		placeholder : "Location",
		height : 22,
		type : "text",
		map_to : "location"
	},/* {
		name : "recurring",
		type : "recurring",
		map_to : "rec_type",
		button : "recurring"
	}*/];
	
	//Overide core functions
	scheduler.form_blocks.text = {	
		render : function(sns){
			var height=(sns.height||"130")+"px";
			return "<div class='dhx_cal_ltext " + sns.name +"' style='height:"+height+";'><input type='text'" + (sns.placeholder ? " placeholder='" + sns.placeholder + "'" : "") + "/></div>";
		},
		set_value : scheduler.form_blocks.textarea.set_value,
		get_value : scheduler.form_blocks.textarea.get_value,
		focus:  scheduler.form_blocks.textarea.focus
	};
	
	scheduler.form_blocks.textarea.render = function(sns){
		var height=(sns.height||"130")+"px";
		return "<div class='dhx_cal_ltext " + sns.name +"' style='height:"+height+";'><textarea" + (sns.placeholder ? " placeholder='" + sns.placeholder + "'" : "") + "></textarea></div>";
	};
	
	scheduler.init('scheduler_here', new Date(), "month");
	scheduler.load("/nm/calEvents/?id=" + gCalId, 'json');
	var dp = new dataProcessor("/nm/calEvents/?id=" + gCalId);
	
	dp.init(scheduler);
	
	//add event - add maxEvent
	scheduler.addEventNowOld = scheduler.addEventNow;
	scheduler.addEventNow = function(start,end,e){
		var isReachedMaxEvents = false;
		if (scheduler._events){
			var count = 0;
			for (key in scheduler._events){
				count++;
				if (gMaxEvents != 'UNLIMITED' && count >= gMaxEvents) {
					isReachedMaxEvents = true;
					break;
				}
			}
		}
		if (!isReachedMaxEvents) this.addEventNowOld(start,end,e);
		else showMaxEventsModal();
	};
	
	//lightbox - don't POST on textarea enter
	scheduler.getLightbox().onkeydown=function(e){
		switch((e||event).keyCode){
			case scheduler.keys.edit_save:
				var originalTarget = e.srcElement ? e.srcElement : (e.originalTarget ? e.originalTarget : null);
				
				if ((e||event).shiftKey || (originalTarget && originalTarget.type == 'textarea')) return;
				scheduler.save_lightbox();
				break;
			case scheduler.keys.edit_cancel:
				scheduler.cancel_lightbox();
				break;
			default:
				break;
		}
	};
	
	
	$('#cal-today-btn').click(function(e){
		e.preventDefault();
		
		try {
			scheduler._click.dhx_cal_today_button();
		} catch (e) {
			// TODO: handle exception
		}
		
	});
	$('.cal-btn-view').click(changeCalView);
}

function setCalImportEvents(){
	jQuery('#cal-import-btn').click(function(e){
		e.preventDefault();
		
		jQuery('#ical-fileupload-label').show();
    	jQuery('#ical-fileupload-loading-label').hide();
		jQuery('#cal-import-modal').modal();
	});
	
	var ajaxUploadFile = null;
	jQuery('#ical-fileupload').fileupload({
        //dataType: 'json',
        add: function (e, data) {
        	ajaxUploadFile = data.submit();
        },
        
        start: function(){
        	jQuery('#ical-fileupload-label').hide();
        	jQuery('#ical-fileupload-loading-label').show();
        },
        
        done: function (e, data) {
        	jQuery('#cal-import-modal').modal('hide');

        	var res = jQuery.parseJSON(data.result);
        	
        	if (res.success){
        		setGlobalSuccess(res.msg);
        		reloadCalendar();
        		
        		if (res.isReachedMaxEvents) showMaxEventsModal();
        	} else {
        		setGlobalError(res.msg);
        	}
        }
    });
	
	//cancel btns
	jQuery('#cal-import-modal [aria-hidden="true"]').click(function(){
		if (ajaxUploadFile) {
			ajaxUploadFile.abort();
			ajaxUploadFile = null;
		}
	});
}


function showSaveBtn(){
	$('#save-btn').css('visibility', 'visible');
}

function hideSaveBtn(){
	$('#save-btn').css('visibility', 'hidden');
}

var gCalId = null;
jQuery(document).ready(function(){
	var calForm = jQuery('#cal-form');
	
	//WIX - Save calendar on create
	if (opener && !jQuery('#cal_name').val()){
		var data = {};
		data['cal[_csrf_token]'] = jQuery('#cal__csrf_token').val();
		data['cal[tz]'] = jQuery('#cal_tz').val();
		data['cal[name]'] = 'New Calendar';
		
		jQuery.ajax({
			url : '',
			type : 'PUT',
			dataType : 'json',
			data : data
		}).always(function(res){
			if (opener.refreshWidget) opener.refreshWidget();
			opener.refresh();		});
	}
	
	$('#desc-info').tooltip({
		title : 'This description will be added to all the events in your calendar',
		trigger : 'click hover',
		placement : 'right'
	});

	
	gCalId = jQuery('#cal-id').val();
	loadCalendar();
	
	setCalImportEvents();
	
	calForm.validate();
	calForm.submit(function(e){
		e.preventDefault();
		
		if (calForm.valid()){
			jQuery.ajax({
				url : '',
				type : 'PUT',
				dataType : 'json',
				data : calForm.serialize()
			}).always(function(res){
				if (res && res.success){
					if (opener){
						hideSaveBtn();
						if (opener.refreshWidget) opener.refreshWidget();
						opener.refresh();
						//window.close();
					} else {
						window.location.href = '/nm/widget/calId/' + gCalId;
					}
				} else {
					setGlobalAlert(res);
				}
				
				jQuery('.continue-btn').removeClass('loading');
			});
		} else {
			jQuery('.continue-btn').removeClass('loading');
		}
	});
	
	
	jQuery('.continue-btn').click(function(e){
		e.preventDefault();
		
		$(this).addClass('loading');
		
		jQuery('#cal-form').submit();
	});
	
	jQuery('#clear-events-submit').click(function(e){
		e.preventDefault();
		
		jQuery.ajax({
			url : '/nm/calEventsClear',
			data : {id : gCalId}
		}).always(function(res){
			setGlobalAlert(res);
			
			//TODO: replace with scheduler.clearAll() + recalcolate cells size
			window.location.reload();
		});
	});
	
	if (opener){
		jQuery('#cal_tz').change(showSaveBtn);
		jQuery('#cal_name,#cal_description').keyup(showSaveBtn);

		jQuery('.upgrade-link').click(function(e){
			e.preventDefault();
			window.opener.upgrade(e);
			window.close();
		});
	}
});