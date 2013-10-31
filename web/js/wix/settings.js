var gSettingAjax = null;

function refresh(){
	window.location.reload();
}

function createAccount(e){
	if (e) e.preventDefault();
	var popup = window.open(BASE_URL + '/nm/register/?isPopup=1', '', 'width=650,height=600,location=0,menubar=0,status=0,titlebar=0,toolbar=0');
	popup.focus();
}

function connect(e){
	if (e) e.preventDefault();
	var popup = window.open(BASE_URL + '/partner/login/?isPopup=1', '', 'width=650,height=600,location=0,menubar=0,status=0,titlebar=0,toolbar=0');
	popup.focus();
}

function disconnect(e){
	if (e) e.preventDefault();
	
	$.ajax({
		url : '/main/logout'
	}).always(refresh);
}

function upgrade(e){
	if (e) e.preventDefault();
	if (Wix) Wix.Settings.openBillingPage();
}

function createCalendar(e){
	if (e) e.preventDefault();
	var popup = window.open(BASE_URL + '/nm/calCreate/?isPopup=1', 'Create', 'width=980,height=870,scrollbars=1');
	popup.focus();
}

function editCalendar(e){
	if (e) e.preventDefault();
	
	var calId = $('input[name="cal_id"], select[name="cal_id"]').val();
	var popup = window.open(BASE_URL + '/nm/calEdit/id/' + calId + '/?isPopup=1', 'Edit', 'width=980,height=870,scrollbars=1');
	popup.focus();
}

function toggleEditCalendar(){
	var calId = $('select[name="cal_id"]').val();
	var editCalendar = $('#edit-calendar');

	if (calId) editCalendar.show();
	else editCalendar.hide();
}


function setValue(name, data){
	if (name) {
		$('input[name="' + name + '"]').val(data);
		$('#settings-form').submit();
	}
}

function refreshWidget(){
	var compId = Wix.Utils.getOrigCompId();
	Wix.Settings.refreshAppByCompIds(compId);
}

$(document).ready(function() {
	//ui
    $('.accordion').Accordion();
	
    $('.color-selector').each(function(i, el){
    	var jEl = $(el);
    	jEl.ColorPicker({startWithColor : jEl.attr('data-color')});
    });
    
    $('.slider').each(function(i, el){
    	var jEl = $(el);
    	jEl.Slider({
    	    type: "Value",
    	    value: jEl.attr('data-opacity')
    	});
    });
    
    $('.checkbox').each(function(i, el){
    	var jEl = $(el);
    	jEl.Checkbox({ checked: jEl.attr('data-checked') }); 
    });
    
    //form
    var settingsForm = $('#settings-form');
	settingsForm.submit(function(e){
		e.preventDefault();
		
		if (gSettingAjax) gSettingAjax.abort();
		
		gSettingAjax = $.ajax({
			url : BASE_URL + '/wix/update',
			type : 'PUT',
			dataType : 'json',
			data : settingsForm.serialize()
		}).always(refreshWidget);
	});
    
	//update values
    $(document).on("colorChanged", function(e, data){
    	setValue(data.type, data.selected_color);
	});
    
    $(document).on("slider.change", function(e, data){
    	setValue(data.type, data.value);
	});
    
    $(document).on("checkbox.change", function (event, data) {
    	setValue(data.type, data.checked);
    });
    
	$('#create-account').click(createAccount);
	$('.connect').click(connect);
	$('#disconnect').click(disconnect);
	$('#upgrade').click(upgrade);
	
	$('#create-calendar').click(createCalendar);
	$('#edit-calendar').click(editCalendar);
	
	//Toggle edit calendar button based on selected calendar
	var selectCalendar = $('select[name="cal_id"]');
	if (selectCalendar.length){
		selectCalendar.change(function(){
			toggleEditCalendar();
			settingsForm.submit();
		});
		toggleEditCalendar();
	}
});

/*
function refreshWidget(){
	var compId = Wix.Utils.getOrigCompId();
	Wix.Settings.refreshAppByCompIds(compId);
}

function refresh(){
	window.location.reload();
}

function openPopup(url, name, specs){
	var popup = window.open(url, name, specs);
	popup.focus();
}

function toggleEditBtn(){
	var calId = $('#settings-form [name="cal_id"]').val();
	var editBtn = $('#edit-btn');
	
	if (calId) editBtn.removeClass('hide');
	else editBtn.addClass('hide');
}

var gSettingAjax = null;
$(document).ready(function(){
	$('#login-btn').click(function(e){
		e.preventDefault();
		var popup = window.open(BASE_URL + '/nm/register/?isPopup=1', '', 'width=650,height=600,location=0,menubar=0,status=0,titlebar=0,toolbar=0');
		popup.focus();
	});
	
	$('#logout-btn').click(function(e){
		e.preventDefault();
		
		$.ajax({
			url : BASE_URL + '/main/logout'
		}).always(refresh);
	});
	
	$('#upgrade-btn').click(function(e){
		e.preventDefault();
		if (Wix) Wix.Settings.openBillingPage();
	});
	
	$('#reload-btn').click(refresh);
	
	$('#create-btn').click(function(e){
		e.preventDefault();
		var popup = window.open(BASE_URL + '/nm/calCreate/?isPopup=1', 'Create', 'width=980,height=870,scrollbars=1');
		popup.focus();
	});
	
	$('#settings-form [name="cal_id"]').change(toggleEditBtn);
	toggleEditBtn();
	
	$('#edit-btn').click(function(e){
		e.preventDefault();
		
		var calId = $('#settings-form [name="cal_id"]').val();
		var popup = window.open(BASE_URL + '/nm/calEdit/id/' + calId + '/?isPopup=1', 'Edit', 'width=980,height=870,scrollbars=1');
		popup.focus();
	});
	
	var settingsForm = $('#settings-form');
	settingsForm.submit(function(e){
		e.preventDefault();
		
		if (gSettingAjax) gSettingAjax.abort();
		
		gSettingAjax = $.ajax({
			url : BASE_URL + '/wix/update',
			type : 'PUT',
			dataType : 'json',
			data : settingsForm.serialize()
		}).always(refreshWidget);
	});
	
	$('#settings-form select').change(function(){
		settingsForm.submit();
	});
	
	$('#bg-opacity').click(function(){
		settingsForm.submit();
	});
	
	['line', 'bg', 'text'].forEach(function(name){
		$('#' + name + '-color-wrapper').colorpicker().on('hide', function(){
			settingsForm.submit();
		});
		
		$('#' + name + '-color').keyup(function(e){
			var val = $(this).val();
			if (/^#[0-9A-F]{6}$/i.test(val)) $('#' + name + '-color-wrapper').colorpicker('setValue', val);
		});
	});
});

*/