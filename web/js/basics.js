function cl(i){
	console.log(i);
}

function toggleIt(id, toShow) {
    if (toShow) document.getElementById(id).style.display = 'inline';
    else        document.getElementById(id).style.display = 'none';
}

function getAjaxObject() {
	  var xmlhttp = null;

	  if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
	  
	    xmlhttp=new XMLHttpRequest();
	  
	  } else   { // code for IE6, IE5
	    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }

	  return xmlhttp;
	  
}

function setCookie(name, value, expireDays)  {
	var exdate	= new Date();
	exdate.setDate(exdate.getDate() + expireDays);
	
	document.cookie = name + "=" + escape(value) + ((expireDays == null) ? "" : ";expires=" + exdate.toUTCString());
}

	function setWatchedMovieCookie() {
		setCookie('demoWatched', true);
		$('watchDemoMovie').setStyle('display', 'none');
//		$('footerPlayVideoImg').setStyle('display', 'none');
		//$('watchDemoMovie').hide();
		//$('footerPlayVideoImg').hide();
	}

	function isUserWatchedMovie() {
		var watched = getCookie('demoWatched');
		if (watched != null && watched != "" && watched) {
			return true;
		}
		return false;
	}
	
	function setPromotionBannerCookie() {
		setCookie('clickOnPromotionBanner', true);
		var iPadBanner = $('iPadBanner');
		if (iPadBanner){
			iPadBanner.addClass('hidden');
		}
	}
	
	function isUserClickOnPromotionBanner() {
		return false;
		
		var watched = getCookie('clickOnPromotionBanner');
		if (watched != null && watched != "" && watched) {
			return true;
		}
		return false;
	}
	
	function hideFriendsBdBanner(){
		var friendBirthdayBannerWrapper = $('friendBirthdayBannerWrapper');
		if (friendBirthdayBannerWrapper){
			friendBirthdayBannerWrapper.addClass('hidden');
		}
	}
	
	function setFriendBirthdayBannerCookie() {
		setCookie('clickOnFriendBirthdayBanner', true);
		hideFriendsBdBanner();
	}
	
	function isUserClickOnFriendBirthdayBanner() {
		//return false;
		var watched = getCookie('clickOnFriendBirthdayBanner');
		if (watched != null && watched != "" && watched) {
			return true;
		}
		return false;
	}

	function getCookie(name) {
		var value = "";
		if (document.cookie.length > 0)   {
			var start	= document.cookie.indexOf(name + "=");
			if (start != -1) {
				start 	= start + name.length + 1;
				var end		= document.cookie.indexOf(";", start);
				if (end == -1) end = document.cookie.length;
				value = unescape(document.cookie.substring(start,end));
			}
		}
		return value;
	}


	
function addLoadEvent(func) { 
    var oldonload = window.onload; 
    if (typeof window.onload != 'function') { 
        window.onload = func; 
    } else { 
        window.onload = function() { 
            if (oldonload) { 
                oldonload(); 
            } 
            func();
        };
    } 
}

showPopup = function(e){
	var popupBG = $('popupBG');
	popupBG.addEvent('click', hidePopup);

	var popupWrapper = $('popupWrapper');
	if (popupWrapper && popupWrapper.hasClass('vhidden')) {
		popupWrapper.removeClass('vhidden');
	}
	var popupCloseBtn = $$('.popupCloseBtn');
	popupCloseBtn.addEvent('click', function(e){
		e.stop();
		hidePopup();
	});

};

hidePopup = function(e){
	//e.stop();
	var popupWrapper = $('popupWrapper');
	popupWrapper.addClass('vhidden');
	var popupBG = $('popupBG');
	popupBG.removeEvent('click');
};

//Add getElementsByClassName func
onload = function(){
	if (document.getElementsByClassName == undefined) {
		document.getElementsByClassName = function(className){
			var hasClassName = new RegExp("(?:^|\\s)" + className + "(?:$|\\s)");
			var allElements = document.getElementsByTagName("*");
			var results = [];
	
			var element;
			for (var i = 0; (element = allElements[i]) != null; i++) {
				var elementClass = element.className;
				if (elementClass && elementClass.indexOf(className) != -1 && hasClassName.test(elementClass))
					results.push(element);
			}
	
			return results;
		};
	}
};

function isNumeric(mix){
	var r = true;
	
	if (typeOf(mix) == "string"){
		mix.split('').each(function(i) {
			if ('0123456789.'.indexOf(i) == -1) {
				r = false;
			}
		});
		
		if (mix.length == 0){
			r = false;
		}
	} else if (!(typeOf(mix) == "number")){
		r = false;
	}
	
	return r;
}


function reportIntel(section, action, label, value, ctgId, calId) {
	//alert("Reporting Event - section: " + section + " action: " + action + " label: " + label + " value: " + value);
	_gaq.push(['_trackEvent', section, action, label, value]);
	
	var params = "s=" + section + "&a=" + action + "&l=" + label + "&v=" + value + "&r=" + Math.random();
	
	if (ctgId) params += '&ctg=' + ctgId;
	if (calId) params += '&cl=' + calId;
	
    var xmlhttp = getAjaxObject();
    if (xmlhttp) {
        xmlhttp.open("POST","/s/intel",false);
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xmlhttp.send(params);
        
        //alert(xmlhttp.responseText);
    }
	
	
}

function preloadImage(imgSrc){
	var img = new Image;
	img.src = imgSrc;
}