
// DEV
//var scRootUrl = 'http://sportYcal.local/widget/';
//var scBaseUrl = 'http://sportYcal.local';

// PROD
var scRootUrl = 'http://www.sportYcal.com/widget/';
var scBaseUrl = 'http://www.sportYcal.com';

var scCss ='';
scCss = '<sty'+'le type="text/css">';
scCss += '@import "' + scRootUrl + 'css/cleanslate.min.css";';
scCss += '@import "' + scRootUrl + 'css/scw_style.css";';
scCss += '</sty'+'le>';


document.write(scCss);

var gLoadingInterval 	= null;
var gItems 				= null;
var gScWidget			= null;

var gScwItemHeight 			= 70;
var gScwShownItemsNum 		= 3;
var gScwHeightOut 			= null;
var gScwHeightIn 			= null;
var gScwHeightExtraIn 		= 60;
var gScwHeightExtraOut 		= 97;


function scwInit(){
	//The widget main Div
	gScWidget = document.getElementById('scWidget');
	
	scwColWidth 		= scReqParam.widgetWidth;

	scwHeight = gScwItemHeight * gScwShownItemsNum;
	gScwHeightIn  = scwHeight + gScwHeightExtraIn;	
	gScwHeightOut = scwHeight + gScwHeightExtraOut;

	//HTML main
	var scWidgetHTML = '<div id="scwContent">';
	scWidgetHTML +=	'<div id="scwWrapperTL"></div><div id="scwWrapperTC" style="width:' + (scwColWidth) + 'px;"></div><div id="scwWrapperTR"></div><div class="cb"></div>';
    scWidgetHTML +=	'<div id="scwWrapperLC" style="height:' + (gScwHeightOut) + 'px;"></div>';
    scWidgetHTML +=	'<div id="scwWrapperCC" style="width:' + (scwColWidth) + 'px; ">';
    scWidgetHTML +=		'<div id="scwContentTitle" style="display:none">' + gScText.comming_events + "&nbsp;";
    scWidgetHTML +=			"<img src='" + scRootUrl + "images/star.png" + "' />";
    scWidgetHTML +=			"<br />";
    scWidgetHTML +=		'</div>';
	scWidgetHTML +=    	'<div id="scwContent">';
	
	// HTML - Content
    scWidgetHTML +=    		'<div id="scwContentInner" style="width:' + (scwColWidth) + 'px; " >';
    scWidgetHTML +=  			'<div id="scwLoading" style="" >';
    scWidgetHTML +=  				'<div id="scwLoadingInner" >';
    scWidgetHTML +=     	   			'<span id="txtLoadingTitle">Finding Sport Events</span><br/>';
    scWidgetHTML +=						"<img id='imgLoading' src='" + scRootUrl + "images/loading1.gif" + "' />";
    scWidgetHTML +=     	   			'<br/><span id="txtLoading">...</span><br/>';    
    scWidgetHTML +=     	   		'</div>';    
    scWidgetHTML +=     	   	'</div>';    
    scWidgetHTML +=  			'<div id="scwItemsContainer" style="display:none;height:' + (gScwHeightIn) + 'px;" >';
    scWidgetHTML +=     	   	'</div>';    
    scWidgetHTML +=        	'</div>';
	scWidgetHTML +=        '</div>';
	
	//HTML - bottom menu
	scWidgetHTML += 		'<div id="scwBottomMenu">';
	scWidgetHTML += 		'</div>';
	scWidgetHTML += 	'</div>';
	
	//SC Logo
	scWidgetHTML +=	'<div id="scwWrapperRC" style="height:' + (gScwHeightOut) + 'px;"></div><div class="cb"></div>';
	scWidgetHTML +=	'<div id="scwWrapperBL"></div>';
	scWidgetHTML +=	'<div id="scwWrapperBC" style="width:' + (scwColWidth) + 'px;">';
	scWidgetHTML +=	'	<a href="http://sportYcal.com" target="_blank"><div id="scwLogo">';	
	scWidgetHTML +=	'	</div></a>';	
	scWidgetHTML +=	'</div>';
	scWidgetHTML +=	'<div id="scwWrapperBR"></div><div class="cb"></div>';	
	//scWidgetHTML +=	'<div id="scwWrapperBL"></div><div id="scwWrapperBC" style="width:' + (scwColWidth) + 'px;"><div id="scwLogo"></div></div><div id="scwWrapperBR"></div><div class="cb"></div>';
	scWidgetHTML +=	'<div id="scriptHolder"></div>';	

	
	gScWidget.innerHTML = scWidgetHTML;

	gLoadingInterval = window.setInterval(progressLoading, 300);

	scwGoGetSportEvents();
	
}

/////////////////////////////////////////### Functions Starts ###///////////////////////////////////////////////////////////

/*
function pa(obj){
	console.log(obj);
}
*/

function scwGetAjaxObject() {
	  var xmlhttp = null;
	  if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
	    xmlhttp=new XMLHttpRequest();
	  } else   { // code for IE6, IE5
	    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	  return xmlhttp;
}


function scwAddLoadEvent(func) { 
    var oldonload = window.onload; 
    if (typeof window.onload != 'function') { 
        window.onload = func; 
    } else { 
        window.onload = function() { 
            if (oldonload) { 
                oldonload(); 
            } 
            func();
        }
    } 
}

var gScwKeepImgs = new Array();

function scwImgPreLoader(imgSrc) {
	var img = new Image();
	img.src = imgSrc;
	gScwKeepImgs.push(img);
	
	imgSrc = scwConvertImageName(imgSrc, true);

	var img = new Image();
	img.src = imgSrc;
	gScwKeepImgs.push(img);
	
}

function scwImgsPreLoader() {
	
	var imgsSrc = [scRootUrl + "images/" + "cal" + "1.gif",
	              scRootUrl + "images/" + "clock" + "1.png",
	              scRootUrl + "images/" + "date" + "1.gif",
	              scRootUrl + "images/" + "close" + "1.gif",
	              scRootUrl + "images/" + "details" + "1.png",
	              scRootUrl + "images/links/" + "ticket" + "1.png"

	              ];
	
    for (i=0; i<imgsSrc.length; i++) {
    	imgSrc = imgsSrc[i]; 
    	scwImgPreLoader(imgSrc);
    }
    
	/*
    var divImgContainer = document.getElementById("scwWidget");
    var imgs = divImgContainer.getElementsByTagName("img");
     imageObj = new Image();
     var i;
     for (i=0; i<imgs.length; i++) {
    	// alert(imgs[i].src);
        imageObj.src = scwConvertImageName(imgs[i].src, true);
     }*/
} 


function scwAnimate(img, isHover) {

	if (typeof(img) == 'string') {
		img = document.getElementById(img);
	} else {
	}
	img.src = scwConvertImageName(img.src, isHover);
}

function scwConvertImageName(imgSrc, isHover) {
	var posDot = imgSrc.lastIndexOf(".");
	imgSrc = imgSrc.substr(0, posDot-1) + ((isHover)? '2' : '1') + imgSrc.substr(posDot);
	return imgSrc;
}

function scwDateFormat(strDateTime) {
	
	var parts = strDateTime.split(" ");
	strDate = parts[0];
	
	var parts = strDate.split("-");
	var strDate1 = parts[1] + "-" + parts[2] + "-" + parts[0];
	
	//pa(strDate1);
	
	//var date = new Date(strDate1);
	
	return strDate1;
}

function scwTimeFormat(strDateTime) {

	var parts = strDateTime.split(" ");
	strTime = parts[1];

	var parts = strTime.split(":");
	var strTime1 = parts[0] + ":" + parts[1];
	
	//pa(strTime1);
	
	//var date = new Date(strDate1);
	
	return strTime1;
}

function scwGetItemById(itemId) {
	var itemsCount 			= gItems.length;
	for(var i=0; i<itemsCount; i++) {
		var item = gItems[i];
		if (item.id == itemId) return item;
	} 
	return null;
}

function scwGetEventDetailsHtml(item) {
	var html = '';
	//var linkWebSiteHtml = scwGetLinkHtml(item, 'website');

	html += "<a href='" + item.calUrl + "' target='_blank'>";
	html += 	"<img id='imgEventCal_" + item.id + "' align='left' class='' src='" + scRootUrl + "images/" + "cal" + "1.gif" + "' title='" + "Part of Calendar: " + item.cal +  "' onmouseover='scwAnimate(this, true)' onmouseout='scwAnimate(this, false)'  />";
	html += 	"&nbsp;&nbsp;";
	html +=		"<span onmouseover='scwAnimate(\"imgEventCal_" + item.id + "\", true)' onmouseout='scwAnimate(\"imgEventCal_" + item.id + "\", false)' >Calendar: <b>" + item.cal + "</b> (contains: " + item.calNumEvents +  " events)</span>";
	html += "</a>";
	
	html += "<br/>";
	html += "&nbsp;&nbsp;Category: <b>" + item.ctgPath + "</b>";
	html += "<br/>";
	
	return html;	
}


function scwGetLinkHtml(item, linkType) {
	var html = '';
	if (!item.links) return html;
	var links = item.links;
	
	var linksCount 			= links.length;
	for(var i=0; i<linksCount; i++) {
		var link = links[i];
		
		if (link.type == linkType) {
			html = "<a href='" + link.url + "' target='_blank'>";
			html += 	"<img id='imgBuyTicket_" + linkType + item.id + "' class='' src='" + scRootUrl + "images/links/" + linkType + "1.png" + "' title='" + link.name + "' onmouseover='scwAnimate(this, true)' onmouseout='scwAnimate(this, false)'  />";			
			//html += 	"&nbsp;&nbsp;<label for='imgBuyTicket_" + item.id + "' onmouseover='scwAnimate(\"imgBuyTicket_" + item.id + "\", true)' onmouseout='scwAnimate(\"imgBuyTicket_" + item.id + "\", false)'>";			
			//html += 	gScText.buyTicket + "</label>";			
			html += "</a>";
			break;
		}
		
	}
		
	return html;	
}

function scwToggleIt(id, toShow) {
	var e = document.getElementById(id);
	if (!e) return;
    if (toShow) e.style.display = '';
    else        e.style.display = 'none';
}


function scwToggleDetails(toShow, itemId) {

	var item = scwGetItemById(itemId);
	var boxDetailsId 		= 'boxEventDetails_' + item.id;
	var boxDetailsInnerId 	= 'boxEventDetailsInner_' + item.id;
	
	if (toShow) {
		var boxDetailsInner 			= document.getElementById(boxDetailsInnerId);
		itemHtml 						= scwGetEventDetailsHtml(item);
		boxDetailsInner.innerHTML 		= itemHtml;
	}
	
	scwToggleIt(boxDetailsId, toShow);
}

function scwGetItemHtml(item) {
	
	//var dateStarts = dateFormat(item.starts, "dddd, mmmm dS, yyyy, h:MM:ss TT");
	
	var dateStarts = scwDateFormat(item.starts);	
	var timeStarts = scwTimeFormat(item.starts);

	var buyTicketHtml = scwGetLinkHtml(item, 'ticket');
	var detailsHtml   = scwGetEventDetailsHtml(item);
	
	var html = "<div class='scwItem'>";

	html += 			"<div style='float:right'>";	
	html += 				buyTicketHtml;
	html += 			"</div>";

	html += 		"<img align='left' class='scwItemImg' src='" + scBaseUrl + item.ctgImgPath + "' title='" + item.name + "' onmouseover='scwAnimate(this, true)' onmouseout='scwAnimate(this, false)'  />";
	html += 		"<p class='scwItemName'>" + item.name + "</p>";


	html += 		"<p class='scwItemDate'>";
	html += 			"<img align='left' id='imgEventDate_" + item.id + "' class='' src='" + scRootUrl + "images/date1.gif" + "' title='Date' onmouseover='scwAnimate(this, true)' onmouseout='scwAnimate(this, false)'  />";	
	html += 			"&nbsp;&nbsp;<label for='imgEventDate_" + item.id + "' onmouseover='scwAnimate(\"imgEventDate_" + item.id + "\", true)' onmouseout='scwAnimate(\"imgEventDate_" + item.id + "\", false)'>" + dateStarts + "</label>";

	html += 			"&nbsp;&nbsp;&nbsp;&nbsp;";	
	html += 			"<img id='imgEventTime_" + item.id + "' class='' src='" + scRootUrl + "images/clock1.png" + "' title='Time' onmouseover='scwAnimate(this, true)' onmouseout='scwAnimate(this, false)'  />";	
	html += 			"&nbsp;&nbsp;<label for='imgEventTime_" + item.id + "' onmouseover='scwAnimate(\"imgEventTime_" + item.id + "\", true)' onmouseout='scwAnimate(\"imgEventTime_" + item.id + "\", false)'>" + timeStarts + "</label>";

	html += 			"&nbsp;&nbsp;&nbsp;&nbsp;";	
	html += 			"<img id='imgEventDetails_" + item.id + "' class='linkEventDetails' src='" + scRootUrl + "images/details1.png" + "' title='Details' onmouseover='scwAnimate(this, true);' onmouseout='scwAnimate(this, false);' onclick='scwToggleDetails(true, \"" + item.id + "\");' />";	
	html += 			"&nbsp;&nbsp;<label for='imgEventDetails_" + item.id + "' class='imgEventDetails' onmouseover='scwAnimate(\"imgEventDetails_" + item.id + "\", true);' onmouseout='scwAnimate(\"imgEventDetails_" + item.id + "\", false);' onclick='scwToggleDetails(true, \"" + item.id + "\");'>" + gScText.details + "</label>";

	//html += 			"&nbsp;&nbsp;&nbsp;&nbsp;";
	html += 		"</p>";
	
	html +=			"<div class='cb'></div>";
	html +=			"<div id='boxEventDetails_" + item.id + "' class='boxEventDetails' style='display:none;'  >";
	html += 			"<img class='imgCloseEventDetails' src='" + scRootUrl + "images/close1.gif" + "' title='Close' onmouseover='scwAnimate(this, true);' onmouseout='scwAnimate(this, false);' onclick='scwToggleDetails(false, \"" + item.id + "\");' />";	
	html +=				"<div id='boxEventDetailsInner_" + item.id + "' class='boxEventDetailsInner' >";
	html +=				"</div>";	
	html +=			"</div>";	
	html +=			"<div class='cb'></div>";
	html +=    "</div>";
	
	return html;
	
}


function scwSetItems(items) {
	//alert(items);
	var itemsCount 			= items.length;
	var itemsHtml			= '';
	for(var i=0; i<itemsCount; i++) {
		var item = items[i];
		
		scwImgPreLoader(scBaseUrl + item.ctgImgPath);
		
		var itemHtml = scwGetItemHtml(item);
		itemsHtml += itemHtml;
	} 
	var scwItemsContainer 			= document.getElementById('scwItemsContainer');
	scwItemsContainer.innerHTML 	= itemsHtml;
}


function scwHandleSportEvents(strJson) {

//	if (gScwAjaxHttp && gScwAjaxHttp.readyState == 4 && gScwAjaxHttp.status == 200)    {

		//var strJson = gScwAjaxHttp.responseText;
//alert("Response: " + strJson);		
		if (strJson){
			var items = eval('(' + strJson + ')');
			//alert(items);
			// Set items
			gItems = items;
			
//alert("Found: " + items.length + " Events");
			
			scwSetItems(items);
			scwToggleIt('scwLoading', false);
			scwToggleIt('scwContentTitle', true);
			scwToggleIt('scwItemsContainer', true);
			clearInterval(gLoadingInterval);
			
		}
		
		if (!gItems || !gItems.length) {
			scwToggleIt('scWidget', false);
			scwToggleIt('sportYcalBox', false);
			
			
		} else if (gItems.length < 3){
			
			scwHeight = gScwItemHeight * gItems.length;
			gScwHeightIn  = scwHeight + gScwHeightExtraIn;	
			gScwHeightOut = scwHeight + gScwHeightExtraOut;

			document.getElementById('scwItemsContainer').style.height 	= gScwHeightIn + "px";
 			document.getElementById('scwWrapperLC').style.height 		= gScwHeightOut + "px";
 			document.getElementById('scwWrapperRC').style.height 		= gScwHeightOut + "px";
		}

//    }

}

var gScwAjaxHttp = null;
function scwGoGetSportEvents() {
	// TEST ULRS:
	//var url 	= scRootUrl + '/js/sportYcal-data.php';
	
	var url 	= scBaseUrl + '/event/find';
	
	var params 	=  "?r=" + Math.random();
	if (scReqParam.locationId) 	params 		+= "&locId=" + scReqParam.locationId;
	params 		+= "&ref=" + scReqParam.partnerId;
	
	url += params;

	var scriptHolder = document.getElementById('scriptHolder');

	var newScript = document.createElement('script');
	newScript.type = 'text/javascript';
	newScript.src = url;
	scriptHolder.appendChild(newScript);
	
//	if (scReqParam.DEBUG) {
//		alert("OPENED");
//	}
   	  	
}

function scwSetLoadingText(specific) {
	var txtLoading = document.getElementById('txtLoading');
	
	if (specific) {
		txtLoading.innerHTML = specific;
	} else {
		if (txtLoading.innerHTML.length < 10)	txtLoading.innerHTML += ".";
	}
}

gScwLoadingState = true;
function progressLoading() {
	scwAnimate('imgLoading', gScwLoadingState);
	scwSetLoadingText(null);
	gScwLoadingState = !gScwLoadingState;
}


scwAddLoadEvent(scwInit);
scwAddLoadEvent(scwImgsPreLoader);




