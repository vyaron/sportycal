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
	
	//scheduler.config.server_utc = true; //convert server side dates from utc to local timezone, and backward during data sending to server;

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
	}, {
		name : "recurring",
		type : "recurring",
		map_to : "rec_type",
		button : "recurring"
	}, {
		name : "time",
		height : 72,
		type : "time",
		map_to : "auto"
	}];
	
	scheduler.init('scheduler_here', new Date(), "week");
	scheduler.load("/nm/calEvents/?id=" + gCalId, 'json');
	var dp = new dataProcessor("/nm/calEvents/?id=" + gCalId);
	
	dp.init(scheduler);
}

var gCalId = null;
jQuery(document).ready(function(){
	gCalId = jQuery('#cal-id').val();
	loadCalendar();
	/*
	jQuery('#cal-form').submit(function(e){
		e.preventDefault();
		
		var formEl = jQuery(this);
		
		jQuery.ajax({
			url : formEl.attr('action'),
			type : formEl.attr('method'),
			dataType : 'json',
			data : formEl.serialize()
		}).done(function(){
			alert('yeyy');
		});
	});
	*/
	
});