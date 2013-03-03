var gScWidget = {};

function pa(obj){
	console.log(obj);
}

function subStrAndDots(str, num){
	if (str.length > num){
		return str.substr(0, num) + '...';
	} else {
		return str;
	}
}

function hideElById(id){
	var el = gGpiWidget.getElement('#' + id);
	if (!el.hasClass('hidden')){
		el.addClass('hidden');
	}
}

function showElById(id){
	var el = gGpiWidget.getElement('#' + id);
	if (el.hasClass('hidden')){
		el.removeClass('hidden');
	}
}

function visibleElById(id){
	var el = gGpiWidget.getElement('#' + id);
	if (el.hasClass('invisible')){
		el.removeClass('invisible');
	}
}

function invisibleElById(id){
	var el = gGpiWidget.getElement('#' + id);
	if (!el.hasClass('invisible')){
		el.addClass('invisible');
	}
}



function setPopup(){
	
	var spPopup 		= $('spPopup');
	var spSelectCtg 	= spPopup.getElement('#spSelectCtg');
	
	spSelectCtg.set('html', '');
	gData.each(function(ctg) {
		var optCtg = new Element('option', {'value' : ctg.id, 'html' : ctg.name});
		optCtg.inject(spSelectCtg);
	});

	
	spSelectCtg.fireEvent('change');
	
	return;
	
	gGpitwListWidth = dummy_list.getStyle('width').toInt();
	
	var typeNum = 0;
	gTypesHash.each(function(type){

		if (!type.name || !gData || !gData.items || !gData.items[type.name] || !gData.items[type.name].length){
			return;
		}
		
		//Create Tab type
		var tabHtml = new Element('h3', {'html' : gLang[type.key]});
		
		var tabEl = new Element('div', {
			'id' 	: 'tab_' + type.key,
			'class' : 'gpitwTab'
		});
		
		tabHtml.inject(tabEl);
		tabEl.inject(gGpitwTabs);
		
		setTabEvents(tabEl, typeNum);
		
		//Create list type
		var cloneList = new Element('div',{
			'id' : 'gpiwList_' + type.key,
			'class' : dummy_list.getProperty('class'),
			'style' : dummy_list.getProperty('style'),
			'html' : dummy_list.get('html')
		});
		type.htmlEl = cloneList;
		cloneList.inject(gGpitwListsWrapper);
		
		//Fixed IE BUG
		cloneList.setStyle('left', (gGpitwListWidth * typeNum));

		setListBtnsEvent(type);

		setItems(type);
		typeNum++;
	});
	
	gElCB.inject(gGpitwListsWrapper);
	
	setTabBgEffect(gTabNumSelected);
	
	setTitle();
	
	showViewById('gpiwListsView');
	
	gFooter.addClass('gpiShowFooterMsg');
//	hideElById('gpiwLoading');
//	
//	showElById('gpiwListsView');
}

function handleTargetUrl(){
	if (!gData) return;
	var gpitwPartnerTargetUrl1 = gGpiWidget.getElement('#gpitwPartnerTargetUrl1');
	var gpitwPartnerTargetUrl2 = gGpiWidget.getElement('#gpitwPartnerTargetUrl2');
	
	if (gData.partner && gData.partner.target_url) {
		if (gpitwPartnerTargetUrl1) gpitwPartnerTargetUrl1.set('href', gData.partner.target_url);
		if (gpitwPartnerTargetUrl2) gpitwPartnerTargetUrl2.set('href', gData.partner.target_url);
	} else 	if (gData.dest && gData.dest.dest_url) {
		if (gpitwPartnerTargetUrl1) gpitwPartnerTargetUrl1.set('href', gData.dest.dest_url);
		if (gpitwPartnerTargetUrl2) gpitwPartnerTargetUrl2.set('href', gData.dest.dest_url);
	}
}

function onPopupClose() {
	var spPopup 		= $('spPopup');	
    spPopup.addClass('spHidden');	
}

function onCtgSelect() {
	//alert("change: " + this.value);
	var selectedCtgId = this.value;
	$$('.spIcalLink').each(function(spIcalLink) {
		var href 		= spIcalLink.get('href');
		var regExp1 		= /ctgId\/[^\/]+\//;
		var regExp2			= /ctgId%2FCTG_ID%2F/;
		var ctgInUrl 		= 'ctgId/' + selectedCtgId + '/';
		href				= href.replace(regExp1, ctgInUrl);
		href				= href.replace(regExp2, ctgInUrl);
		//alert(href);
		spIcalLink.set('href', href);
	});
}


gSetData = function(){
	gData = gResData;
	//alert("gSetData: " + gData);
	
	//if (!gHasLists){
		setPopup();
	//}
	//handleTargetUrl();
	
	//gGpiwJunk.set('html','');
};

var gClickedCalen = null;

window.addEvent('domready', function(){
//alert('1');
//alert(gHTML);	
	var spPopup 		= $('spPopup');
	var calens = $$('.calendarius');

	calens.each(function(calen){
		//calen.set('html', gHTML);
		//var spTriger = calen.getElement('.spTriger');
		
		//alert(calen.getAttribute('section'));
		//spTriger.set('section', calen.get('section'));
		
		calen.addEvent('click', function(){

			// only support one at a time
			if (gClickedCalen) return;
			var sectionAlias = this.get('section');
			//alert(eId + ' click');

			var spLoading = new Element('div', {
			    'class': 'spLoading',
			    'title': 'Loading Calendars...'
			});
			spLoading.inject(this);
			
			//this.set('html', spLoading);
			//var scrriptHolder = $('scScriptHolder');
			gClickedCalen = this;

			var urlGetData = 'getData.php?ref=' + SC_REF + '&ctgAlias=' + encodeURIComponent(sectionAlias);
			//alert('Loading JS: ' + urlGetData);
			var myScript = Asset.javascript(urlGetData, {
			    //id: 'myScript',
			    onLoad: function(){
			        //alert('getData.php is loaded!');
			        gSetData();
			        gClickedCalen.set('html', '');
			        gClickedCalen = null;
			        spPopup.removeClass('spHidden');
			        
			    }
			});
			
			
		});

	
	});
	gHTML = null;
	
	// Prepare popup
	var spSelectCtg 	= spPopup.getElement('#spSelectCtg');
	var spClose 		= spPopup.getElement('.spClose');
	
	spSelectCtg.addEvents({
		'change'	: onCtgSelect
	});
	spClose.addEvents({
		'click'	: onPopupClose
	});
	
	return;

});


