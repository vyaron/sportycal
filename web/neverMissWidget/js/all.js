(function(){
	var self = window, document = window.document;
	//var setTimeout = window.setTimeout, setInterval = window.setInterval;
	
	var NEVER_MISS = 'neverMiss';
	var NEVER_MISS_WEBSITE = 'http://never-miss.local/';
	var NEVER_MISS_WIDGET_URL = NEVER_MISS_WEBSITE + '/widget.php';
	var NEVER_MISS_WIDGET_BUBBLE_URL = NEVER_MISS_WEBSITE + '/widget_bubble.php';

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
		iframeEl = document.getElementById(id);
		if (iframeEl) {
			iframeEl.style.display = (iframeEl.style.display == 'block') ? 'none' : 'block';
			iframeEl.contentWindow.document.getElementById('continue-btn').onclick = function(){
				iframeEl.style.display = 'none';
			};
		}
	}

	function injectIframe(els){
		for ( var i = 0; i < els.length; i++) {
			var el = els[i];
			var calId = el.getAttribute('data-cal-id');
			
			params = '';
			if (calId) params += '&calId=' + calId;
			
			params = params.substring(1, params.length);
			
			var d = new Date();
			var t = d.getTime() + i;
			
			var id = NEVER_MISS + '_' + t;
			var id_bubble = NEVER_MISS + '_b_' + t;
			
			var iframes = '<iframe id="' + id +'" src="' + NEVER_MISS_WIDGET_URL + '?' + params + '" frameborder="0" border="0" style="border: medium none; overflow: hidden; height: 22px; width: 47px;" scrolling="no" title="Never Miss"></iframe>';
			//iframes += '<iframe id="' + id_bubble +'" src="' + NEVER_MISS_WIDGET_BUBBLE_URL + '?' + params + '" frameborder="0" border="0" style="border: medium none; overflow: hidden; height: 200px; width: 200px; position:absolute; left:0; top:30px; z-index:999; display:none;" scrolling="no" title="Never Miss"></iframe>';
			el.innerHTML = iframes;
			
			/*
			iframeEl = document.getElementById(id);
			if (iframeEl) iframeEl.onload = function(){
				iframeEl.contentWindow.document.getElementById('never-miss-btn').onclick = function(){toggleBubble(id_bubble);};
			};
			*/
		}
	}

	var els = getElementsByClassName('nm-follow');
	injectIframe(els);
})();
