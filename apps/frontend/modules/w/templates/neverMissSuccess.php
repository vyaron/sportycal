(function(){
	var self = window, document = window.document;
	//var setTimeout = window.setTimeout, setInterval = window.setInterval;
	
	var LANGUAGE_HEBREW = '<?php echo NeverMissWidget::LANGUAGE_HEBREW;?>';
	
	var NEVER_MISS = 'neverMiss';
	var NEVER_MISS_WEBSITE = '<?php echo sfConfig::get('app_domain_full');?>/w/';
	var NEVER_MISS_WIDGET_URL = NEVER_MISS_WEBSITE + 'neverMissBtn';
	var NEVER_MISS_WIDGET_BUBBLE_URL = NEVER_MISS_WEBSITE + 'neverMissPopup';
	
	var BUBBLE_PREFIX = 'b_';
	
	var BUBBLE_WIDTH = 360;
	var BUBBLE_HEIGHT = 130;
	
	var isRTL = false;
	var btn_width = 160;
	var btn_height = 40;
	var upcoming_height = 0;
	
	function setBtnSize(btnStyle, btnSize, upcoming){
		if (btnSize == 'small'){
			btn_width = (btnStyle == 'only_icon') ? 20 :88;
			btn_height = 20;
		} else {
			btn_width = (btnStyle == 'only_icon') ? 36 :160;
			btn_height = 36;
		}
		
		if (upcoming){
			btn_width = Math.max(btn_width, 150);
			upcoming_height = 43 +  (upcoming * 51);
		}
	}
	
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
		
		if (isRTL && ((wSize.x - (offsetLeft - BUBBLE_WIDTH)) > 0)) xPos = 'left'; 
		
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
		setBubblePos(id);
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
	
	function setBubblePos(id){
		var btnEl = document.getElementById(id.substr(BUBBLE_PREFIX.length));
		var iframeEl = document.getElementById(id);
		
		if (btnEl && iframeEl){
			var bubbleClassPos = getBubbleClassPos(btnEl);
			var isTop = (bubbleClassPos.indexOf('top') >= 0) ? true : false;
			var isRight = (bubbleClassPos.indexOf('right') >= 0) ? true : false;
			
			var bubbleStartPos = (Math.floor(btn_height/2 - 20));
			if (isRTL) bubbleStartPos -= 8; //box-shadow + 1px border
			
			var left = 'auto';
			var right = 'auto';
			var top = 'auto';
			var bottom = 'auto';
			
			if (isRight) left = bubbleStartPos + 'px';
			else right = bubbleStartPos + 'px';
			
			if (isTop) bottom = (btn_height + upcoming_height) + 'px';
			else top = btn_height + 'px';
			
			//console.log('left:' + left + ', right:' + right + ', top:' + top + ', bottom:' + bottom);
			//Change bubble position
			iframeEl.style.left = left;
			iframeEl.style.right = right;
			iframeEl.style.top = top;
			iframeEl.style.bottom = bottom;
			
			//change bubble source - reload the iframe if needed
			var src = iframeEl.src.replace(/(bubblePos\/)(\w+).+?(\/)/, '$1' + bubbleClassPos + '/');
			if (src != iframeEl.src) iframeEl.src = src;
		}
	}
	
	function injectIframe(els){
		for ( var i = 0; i < els.length; i++) {
			var el = els[i];
			
			el.innerHTML = '';
			
			var calId = el.getAttribute('data-cal-id');
			
			var language = el.getAttribute('data-language');
			var btnStyle = el.getAttribute('data-btn-style');
			var btnSize = el.getAttribute('data-btn-size');
			var color = el.getAttribute('data-color');
			var upcoming = parseInt(el.getAttribute('data-upcoming'));
			
			var isMobile = el.getAttribute('data-is-mobile');
			
			isRTL = (language == LANGUAGE_HEBREW) ? true : false;
			
			setBtnSize(btnStyle, btnSize, upcoming);
			
			var bubbleClassPos = getBubbleClassPos(el);
			
			var d = new Date();
			var t = d.getTime() + i;
			
			var id = NEVER_MISS + '_' + t;
			var id_bubble = BUBBLE_PREFIX + id;
			
			var iframes = '<div style="position: relative; height: ' + (btn_height + upcoming_height) + 'px; width: ' + btn_width + 'px;"><iframe id="' + id +'" src="' + NEVER_MISS_WIDGET_URL + '/calId/' + calId + '/popupId/' + id_bubble + (language ? ('/language/' + language) : '') + (btnStyle ? ('/btnStyle/' + btnStyle) : '') + (btnSize ? ('/btnSize/' + btnSize) : '') + (color ? ('/color/' + color) : '') + (upcoming ? ('/upcoming/' + upcoming) : '') + (isMobile ? ('/isMobile/' + isMobile) : '') + '" frameborder="0" border="0" style="border: medium none; overflow: hidden; height: ' + (btn_height + upcoming_height) + 'px; width: ' + btn_width + 'px;" scrolling="no" title="Never Miss"></iframe>';
			if (!isMobile) iframes += '<iframe id="' + id_bubble +'" src="' + NEVER_MISS_WIDGET_BUBBLE_URL + '/calId/' + calId + '/isBubble/true/bubblePos/'+ bubbleClassPos + '/popupId/' + id_bubble + (language ? ('/language/' + language) : '') + '" frameborder="0" border="0" style="border: medium none; overflow: hidden; height: ' + BUBBLE_HEIGHT + 'px; width: '+ BUBBLE_WIDTH +'px; position:absolute; z-index:9999; display:none;" scrolling="no" title="Never Miss"></iframe></div>';

			el.innerHTML = iframes;
		}
	}
	
	function load(){
		for (var i in gHideInterval){
			clearHideInterval(i);
		}
		gHideInterval = {};
		
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
