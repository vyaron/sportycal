function loadCalendar(){
	scheduler.config.xml_date = "%Y-%m-%d %H:%i";
	scheduler.config.prevent_cache = true;
	scheduler.config.first_hour = 4;
	scheduler.locale.labels.section_location = "Location";
	scheduler.locale.labels.section_name = "Event Name";
	scheduler.config.details_on_create = true;
	scheduler.config.details_on_dblclick = true;
	scheduler.config.prevent_cache = true;

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
		type : "textarea",
		focus : true
	}, {
		name : "location",
		height : 43,
		type : "textarea",
		map_to : "location"
	}, {
		name : "time",
		height : 72,
		type : "time",
		map_to : "auto"
	} ];

	scheduler.init('scheduler_here', new Date(), "week");
	scheduler.load("/nm/calEvents/?id=" + gCalId, "json");
	var dp = new dataProcessor("/nm/calEvents/?id=" + gCalId);
	
	/*
	scheduler.init('scheduler_here', new Date(2009, 10, 1), "day");
	scheduler.load("/bundle/dhtmlxScheduler/samples/01_initialization_loading/data/events_json.php", "json");
	var dp = new dataProcessor("/bundle/dhtmlxScheduler/samples/01_initialization_loading/data/events_json.php");
	*/
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