(function(){
	var self = window, document = window.document;
	//var setTimeout = window.setTimeout, setInterval = window.setInterval;
	
	var NEVER_MISS = 'neverMiss';
	var NEVER_MISS_WEBSITE = '<?php echo sfConfig::get('app_domain_full');?>/w/';
	var NEVER_MISS_WIDGET_URL = NEVER_MISS_WEBSITE + 'neverMissBtn';
	var NEVER_MISS_WIDGET_BUBBLE_URL = NEVER_MISS_WEBSITE + 'neverMissPopup';
	
	var BTN_WIDTH = 160;
	var BTN_HEIGHT = 40;
	
	var BUBBLE_WIDTH = 360;
	var BUBBLE_HEIGHT = 130;
	
	function getWindowSize(){
		var w = window;
		var d = document;
		var e = d.documentElement;
		var g = d.getElementsByTagName('body')[0];
		var x = w.innerWidth || e.clientWidth || g.clientWidth;
		var y = w.innerHeight|| e.clientHeight|| g.clientHeight;
		
		return {x : x, y: y};
	};
	
	
	function getOffsetTop(el){
		return (el) ? el.offsetTop + getOffsetTop(el.offsetParent) : 0;
	}
	
	function getOffsetLeft(el){
		if (!el) return 0;
		
		return (el.offsetLeft > 0) ? el.offsetLeft : getOffsetLeft(el.offsetParent);
	}
	
	//top-right, top-left, bottom-right, bottom-left
	function getBubbleClassPos(el){
		var wSize = getWindowSize();
		
		var offsetTop = getOffsetTop(el);
		var offsetLeft = getOffsetLeft(el);
		
		var yPos = ((wSize.y - (offsetTop + BUBBLE_HEIGHT)) > 0) ? 'bottom' : 'top';
		var xPos = ((wSize.x - (offsetLeft + BUBBLE_WIDTH)) > 0) ? 'right' : 'left';
		
		return yPos + '-' + xPos;
	}
	
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
	
	
	var gHideInterval = {};
	function toggleBubble(id){
		var iframeEl = document.getElementById(id);
		if (iframeEl) iframeEl.style.display == 'block' ? hideBubble(id, true) : showBubble(id);
		
	}
	
	function clearHideInterval(id){
		if (id in gHideInterval){
			window.clearInterval(gHideInterval[id]);
			gHideInterval[id] = null;
		}
	}
	
	function showBubble(id){
		clearHideInterval(id);
		
		var iframeEl = document.getElementById(id);
		if (iframeEl) {
			iframeEl.style.display = 'block';
		}
	}
	
	
	function hideBubble(id, fast){
		clearHideInterval(id);
		
		var hideFunc = function (){
			iframeEl = document.getElementById(id);
			iframeEl.style.display = 'none';
		};

		if (fast) hideFunc();
		else gHideInterval[id] = window.setInterval(hideFunc, 500);

	}

	function injectIframe(els){
		for ( var i = 0; i < els.length; i++) {
			var el = els[i];
			
			el.innerHTML = '';
			
			var calId = el.getAttribute('data-cal-id');
			var language = el.getAttribute('data-language');
			var isMobile = el.getAttribute('data-is-mobile');
			
			var bubbleClassPos = getBubbleClassPos(el);
			var isTop = (bubbleClassPos.indexOf('top') >= 0) ? true : false;
			var isRight = (bubbleClassPos.indexOf('right') >= 0) ? true : false;
			
			var d = new Date();
			var t = d.getTime() + i;
			
			var id = NEVER_MISS + '_' + t;
			var id_bubble = 'b_' + id;
			
			var iframes = '<div style="position: relative; height: ' + BTN_HEIGHT + 'px; width: ' + BTN_WIDTH + 'px;"><iframe id="' + id +'" src="' + NEVER_MISS_WIDGET_URL + '/calId/' + calId + '/popupId/' + id_bubble + (language ? ('/language/' + language) : '') + (isMobile ? ('/isMobile/' + isMobile) : '') + '" frameborder="0" border="0" style="border: medium none; overflow: hidden; height: ' + BTN_HEIGHT + 'px; width: ' + BTN_WIDTH + 'px;" scrolling="no" title="Never Miss"></iframe>';
			if (!isMobile) iframes += '<iframe id="' + id_bubble +'" src="' + NEVER_MISS_WIDGET_BUBBLE_URL + '/calId/' + calId + '/isBubble/true/bubblePos/'+ bubbleClassPos + '/popupId/' + id_bubble + (language ? ('/language/' + language) : '') + '" frameborder="0" border="0" style="border: medium none; overflow: hidden; height: ' + BUBBLE_HEIGHT + 'px; width: '+ BUBBLE_WIDTH +'px; position:absolute; '+ ((isRight) ? 'left:0' : 'right:0') +'; '+ ((isTop) ? 'bottom:30' : 'top:20') +'px; z-index:9999; display:none;" scrolling="no" title="Never Miss"></iframe></div>';

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
