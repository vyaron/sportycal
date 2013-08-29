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
		var popup = window.open(BASE_URL + '/partner/login/?isPopup=1', '', 'width=650,height=600,location=0,menubar=0,status=0,titlebar=0,toolbar=0');
		popup.focus();
	});
	
	$('#logout-btn').click(function(e){
		e.preventDefault();
		
		$.ajax({
			url : BASE_URL + '/main/logout'
		}).always(refresh);
	});
	
	$('#reload-btn').click(refresh);
	
	$('#create-btn').click(function(e){
		e.preventDefault();
		var popup = window.open(BASE_URL + '/nm/calCreate/?isPopup=1');
		popup.focus();
	});
	
	$('#settings-form [name="cal_id"]').change(toggleEditBtn);
	toggleEditBtn();
	
	$('#edit-btn').click(function(e){
		e.preventDefault();
		
		var calId = $('#settings-form [name="cal_id"]').val();
		var popup = window.open(BASE_URL + '/nm/calEdit/id/' + calId + '/?isPopup=1');
		popup.focus();
	});
	
	var settingsForm = $('#settings-form');
	settingsForm.submit(function(e){
		e.preventDefault();
		
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
});