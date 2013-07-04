(function(){
	var self = window, document = window.document;
	//var setTimeout = window.setTimeout, setInterval = window.setInterval;
	
	var NEVER_MISS = 'neverMiss';
	var NEVER_MISS_WEBSITE = '<?php echo sfConfig::get('app_domain_full');?>/w/';
	var NEVER_MISS_WIDGET_URL = NEVER_MISS_WEBSITE + 'neverMissBtn';
	var NEVER_MISS_WIDGET_BUBBLE_URL = NEVER_MISS_WEBSITE + 'neverMissPopup';
	
	var BUBBLE_WIDTH = 360;
	var BUBBLE_HEIGHT = 270;
	
	var getWindowSize = function(){
		var w = window;
		var d = document;
		var e = d.documentElement;
		var g = d.getElementsByTagName('body')[0];
		var x = w.innerWidth || e.clientWidth || g.clientWidth;
		var y = w.innerHeight|| e.clientHeight|| g.clientHeight;
		
		return {x : x, y: y};
	};
	
	var isBubbleTop = function(el){
		var wSize = getWindowSize();
		return ((wSize.y - (el.offsetTop + BUBBLE_HEIGHT)) > 0) ? false : true;
	};
	
	var getElementsByClassName = function (className, tag, elm){
		if (document.getElementsByClassName) {
			getElementsByClassName = function (className, tag, elm) {
				elm = elm || document;
				var elements = elm.getElementsByClassName(className),
					nodeName = (tag)? new RegExp("\\b" + tag + "\\b", "i") : null,
					returnElements = [],
					current;
				for(var i=0, il=elements.length; i<il; i+=1){
					current = elements[i];
					if(!nodeName || nodeName.test(current.nodeName)) {
						returnElements.push(current);
					}
				}
				return returnElements;
			};
		}
		else if (document.evaluate) {
			getElementsByClassName = function (className, tag, elm) {
				tag = tag || "*";
				elm = elm || document;
				var classes = className.split(" "),
					classesToCheck = "",
					xhtmlNamespace = "http://www.w3.org/1999/xhtml",
					namespaceResolver = (document.documentElement.namespaceURI === xhtmlNamespace)? xhtmlNamespace : null,
					returnElements = [],
					elements,
					node;
				for(var j=0, jl=classes.length; j<jl; j+=1){
					classesToCheck += "[contains(concat(' ', @class, ' '), ' " + classes[j] + " ')]";
				}
				try	{
					elements = document.evaluate(".//" + tag + classesToCheck, elm, namespaceResolver, 0, null);
				}
				catch (e) {
					elements = document.evaluate(".//" + tag + classesToCheck, elm, null, 0, null);
				}
				while ((node = elements.iterateNext())) {
					returnElements.push(node);
				}
				return returnElements;
			};
		}
		else {
			getElementsByClassName = function (className, tag, elm) {
				tag = tag || "*";
				elm = elm || document;
				var classes = className.split(" "),
					classesToCheck = [],
					elements = (tag === "*" && elm.all)? elm.all : elm.getElementsByTagName(tag),
					current,
					returnElements = [],
					match;
				for(var k=0, kl=classes.length; k<kl; k+=1){
					classesToCheck.push(new RegExp("(^|\\s)" + classes[k] + "(\\s|$)"));
				}
				for(var l=0, ll=elements.length; l<ll; l+=1){
					current = elements[l];
					match = false;
					for(var m=0, ml=classesToCheck.length; m<ml; m+=1){
						match = classesToCheck[m].test(current.className);
						if (!match) {
							break;
						}
					}
					if (match) {
						returnElements.push(current);
					}
				}
				return returnElements;
			};
		}
		return getElementsByClassName(className, tag, elm);
	};
	
	
	function toggleBubble(id){
		var iframeEl = document.getElementById(id);
		if (iframeEl) iframeEl.style.display == 'block' ? hideBubble(id, true) : showBubble(id);
		
	}
	
	function showBubble(id){
		var iframeEl = document.getElementById(id);
		if (iframeEl) {
			iframeEl.style.display = 'block';
			
			if (gHideInterval){
				window.clearInterval(gHideInterval);
				gHideInterval = null;
			}
		}
	}
	
	var gHideInterval = null;
	function hideBubble(id, fast){
		var hideFunc = function (){
			iframeEl = document.getElementById(id);
			iframeEl.style.display = 'none';
		};
		
		if (fast) hideFunc();
		else gHideInterval = window.setInterval(hideFunc, 3000);

	}

	function injectIframe(els){
		for ( var i = 0; i < els.length; i++) {
			var el = els[i];
			
			var isShowTop = isBubbleTop(el);

			el.innerHTML = '';
			
			var calId = el.getAttribute('data-cal-id');
			var language = el.getAttribute('data-language');
			var isMobile = el.getAttribute('data-is-mobile');

			var d = new Date();
			var t = d.getTime() + i;
			
			var id = NEVER_MISS + '_' + t;
			var id_bubble = 'b_' + id;
			
			var iframes = '<div style="position: relative;"><iframe id="' + id +'" src="' + NEVER_MISS_WIDGET_URL + '/calId/' + calId + '/popupId/' + id_bubble + (language ? ('/language/' + language) : '') + (isMobile ? ('/isMobile/' + isMobile) : '') + '" frameborder="0" border="0" style="border: medium none; overflow: hidden; height: 40px; width: 160px;" scrolling="no" title="Never Miss"></iframe>';
			if (!isMobile) iframes += '<iframe id="' + id_bubble +'" src="' + NEVER_MISS_WIDGET_BUBBLE_URL + '/calId/' + calId + '/isBubble/true/bubbleTop/'+ isShowTop + '/popupId/' + id_bubble + (language ? ('/language/' + language) : '') + '" frameborder="0" border="0" style="border: medium none; overflow: hidden; height: ' + BUBBLE_HEIGHT + 'px; width: '+ BUBBLE_WIDTH +'px; position:absolute; left:0; '+ ((isShowTop) ? 'bottom:30' : 'top:20') +'px; z-index:9999; display:none;" scrolling="no" title="Never Miss"></iframe></div>';

			el.innerHTML = iframes;
		}
	}
	
	function load(){
		var els = getElementsByClassName('nm-follow');
		injectIframe(els);
	}
	
	load();
	
	
	//TODO: add Event one time only!
	var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
	var eventer = window[eventMethod];
	var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";
	// Listen to message from child window
	eventer(messageEvent,function(e) {
		if (e.data.indexOf('neverMiss') >= 0){
			var parts = e.data.split('@');
			var id = parts[0];
			var action = parts[1];
			
			if (action == 'open') showBubble(id);
			else if (action == 'close') hideBubble(id);
			else if (action == 'toggle') toggleBubble(id);
			else if (action == 'force_close') hideBubble(id, true);
		}
	},false);
	
	
	self.iNeverMiss = {
		reload : load
	};
})();
