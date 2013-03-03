function toggleReminder(){
	var addReminder = $('addReminder');
	var reminder = $('reminder');
	
	if (addReminder.checked){
		reminder.removeProperty('disabled');
	} else {
		reminder.setProperty('disabled', 'disabled');
	}
}

function getReminder(){
	var reminderVal = null;
	
	var addReminder = $('addReminder');
	if (addReminder && addReminder.checked){
		var reminder = $('reminder');
		if (reminder){
			reminderVal = $('reminder').get('value');
		}
	} else if (addReminder){
		reminderVal = 0;
	}
	
	return reminderVal;
}

function reportThankYou(type) {
	reportIntel('ThankYou Share' , type, gCalDownLabel, 1);
	return true;	
}

function hideCalPopup(){
	var popupEl = document.getElementById("calDownPopup");
	if (popupEl){
		popupEl.setAttribute('class', 'hidden');
	}

	return false;
}

function showCalPopup(id){
	var contentEls = document.getElementsByClassName('calDownPopupContent');
	if (contentEls && contentEls.length){
		for (var i=0; i<contentEls.length;i++){
			if (contentEls[i].id == id){
				contentEls[i].setAttribute('class', 'calDownPopupContent');
			} else {
				contentEls[i].setAttribute('class', 'calDownPopupContent hidden');
			}
		}
	}
	
	var popupEl = document.getElementById("calDownPopup");
	if (popupEl){
		popupEl.removeAttribute('class');
	}
}

function toggleLoading(toShow) {
    toggleIt('imgLoading', toShow);
}

function showHelp() {
	showCalPopup("divHelp");
}

function showCalUrl() {
	showCalPopup("divCalUrl");
}

function showCalMobile(){
	showCalPopup("divMobile");
	
	return false;
}

function showUnsubscribe() {
	showCalPopup("divUnsubscribe");
}


function toggleInfo(infoId, show) {
    var info = document.getElementById(infoId);
    
    if (show)  info.style.display = 'block';
    else       info.style.display = 'none';
}

function generateUserCalId(calId, ctgId, birthdayCalUserId, calType, reminder1) {
	var reminder = getReminder();

	var userCalId = null;
    var xmlhttp = getAjaxObject();
    if (xmlhttp) {
    	var params = "calId=" + calId + "&ctgId=" + ctgId + "&bc=" + birthdayCalUserId + "&calType=" + calType + "&r=" + Math.random();
    	if (reminder != null && reminder >= 0) params += '&reminder=' + reminder;
    	
        xmlhttp.open("POST","/index.php/admin/updateStats",false);
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xmlhttp.send(params);
        
        userCalId = xmlhttp.responseText;
    }
    return userCalId;
	
}

function replaceInCalUrl(className, strSearch, strReplace, isId) {
	var links = [];
	
	if (isId) {
		var l = document.getElementById(className);
		if (l) links = [l];
	} else {
		links = document.getElementsByClassName(className);
	}
	
    for (var i = 0; i < links.length; i++){
		var link = links[i];
		
		// not sure why, but saw a case that link was not there...
        if (link.href) {
            link.href = link.href.replace(strSearch, strReplace);
        } else {
	        // anyIcal is special, its in a span
	        link.innerHTML = link.innerHTML.replace(strSearch, strReplace);
        }

    }

}

function setFbShareEvent(){
	//reportThankYou('facebook');
	var fbShareBtn = $('fbShareBtn');
	if (fbShareBtn){
		fbShareBtn.addEvent('click', function(e){
			e.stop();
			
			if (typeof FB != 'undefined'){
				var url = fbShareBtn.getProperty('href');
				
				FB.ui({
		        	method: 'feed',
		        	link : url
		        }, function(res){
		        	//INTEL
		        	reportThankYou('facebook');
		        });
			}
		});
	}
}

window.addEvent('domready', function(){
	var addReminder = $('addReminder');
	if (addReminder){
		toggleReminder();
		addReminder.addEvent('click', toggleReminder);
	}
	
	setFbShareEvent();
});