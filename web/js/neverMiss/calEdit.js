function reloadCalendar(){
	scheduler.clearAll();
	scheduler.load("/nm/calEvents/?id=" + gCalId, 'json');
}

function loadCalendar(){
	scheduler.config.touch = "force";
	scheduler.config.xml_date = "%Y-%m-%d %H:%i";
	scheduler.config.prevent_cache = true;
	//scheduler.config.first_hour = 4;
	scheduler.locale.labels.section_location = "Location";
	scheduler.locale.labels.section_name = "Event Name";
	scheduler.config.details_on_create = true;
	scheduler.config.details_on_dblclick = true;
	scheduler.config.multi_day = true;
	
	scheduler.config.event_duration = 60; //specify event duration in munites for auto end time
	scheduler.config.auto_end_date = true;
	
	scheduler.config.full_day = true;
	
	scheduler.config.buttons_right=["dhx_save_btn","dhx_cancel_btn"];
	scheduler.config.buttons_left=["dhx_delete_btn"];
	
	//scheduler.config.server_utc = true; //convert server side dates from utc to local timezone, and backward during data sending to server;
	
	/*
	// Recurring events
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
	
	scheduler.config.lightbox.sections = [ {
		name : "name",
		height : 30,
		map_to : "text",
		type : "textarea",
		focus : true
	}, {
		name : "description",
		height : 130,
		map_to : "details",
		type : "textarea"
	}, {
		name : "location",
		height : 43,
		type : "textarea",
		map_to : "location"
	}, /*{
		name : "recurring",
		type : "recurring",
		map_to : "rec_type",
		button : "recurring"
	},*/ {
		name : "time",
		height : 72,
		type : "time",
		map_to : "auto"
	}];
	
	scheduler.init('scheduler_here', new Date(), "month");
	scheduler.load("/nm/calEvents/?id=" + gCalId, 'json');
	var dp = new dataProcessor("/nm/calEvents/?id=" + gCalId);
	
	dp.init(scheduler);
}

function setCalImportEvents(){
	jQuery('.cal_import_button').click(function(e){
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


var gCalId = null;
jQuery(document).ready(function(){
	gCalId = jQuery('#cal-id').val();
	loadCalendar();
	
	setCalImportEvents();
	
	jQuery('#cal-form').validate();
	
	jQuery('.continue-btn').click(function(e){
		e.preventDefault();
		jQuery('#cal-form').submit();
	});
});