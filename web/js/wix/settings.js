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