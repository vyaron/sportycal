var scDownloadWidget = new Class({
	options : null,
	
	//ROOT_URL : 'http://sportycal.local/',
	ROOT_URL : 'http://www.sportycal.com/',
	
	CAL_ID : null,
	CTG_ID : null,
	REF : null,
	LABEL : null,
	USER_IP : null,
	
	wrapperEl : null,
	
	HASH_STRING : 'USERCAL',
	
	userCalId : null,
	
    initialize: function(options){
        this.options = options;
        
        if (this.options.SCDW_CAL_ID){
        	this.CAL_ID = this.options.SCDW_CAL_ID;
        }
        
        if (this.options.SCDW_CTG_ID){
        	this.CTG_ID = this.options.SCDW_CTG_ID;
        }
        
        if (this.options.SCDW_REF){
        	this.REF = this.options.SCDW_REF;
        }

        if (this.options.SCDW_LABEL){
        	this.LABEL = this.options.SCDW_LABEL;
        }
        
        if (this.options.USER_IP){
        	this.USER_IP = this.options.USER_IP;
        }
        
        if (this.options.el){
        	this.wrapperEl = document.id(this.options.el);
        	this.wrapperEl.set('html', this.options.SCDW_HTML_TEMPALATE);
        	
        	this.setIconClickEvents();
        	this.setHelpClickEvent();
        	
        	//this.userCalId = this.getHashKey(30);
        }
        
       this.setEventsBtnEvent();
       
       if (!spdwClientIsMobile){
    	   this.setMobilePopupEvent();
    	   //this.prepareMobilePopup();
       }
       
    },
    
    setMobilePopupEvent : function(){
    	var _this = this;
    	
    	var scdwPopupClose = this.wrapperEl.getElement('.scdwPopupClose');
    	scdwPopupClose.addEvent('click', function(e){
    		e.stop();
    		var scdwPopup = _this.wrapperEl.getElement('.scdwPopup');
    		scdwPopup.addClass('hidden');
    	});
    },
    
    prepareMobilePopup : function(){
    	var MARGIN = 18;
    	
    	var coord = this.wrapperEl.getCoordinates();
    	
    	var scdwPopup = this.wrapperEl.getElement('.scdwPopup');
    	scdwPopup.setStyles({
    		height : coord.height,
    		width : coord.width
    	});
    	
    	var scdwPopupContentWrapper = this.wrapperEl.getElement('.scdwPopupContentWrapper');
    	scdwPopupContentWrapper.setStyles({
    		height : coord.height - MARGIN,
    		width : coord.width - MARGIN
    	});
    	
    	var scdwPopupContent = this.wrapperEl.getElement('.scdwPopupContent');
    	scdwPopupContent.setStyle('height', coord.height - (20 + MARGIN));
    	
    	scdwPopup.removeClass('hidden');
    },
    
    setEventsBtnEvent : function(){
    	 var scdwShowEventsBtn = this.wrapperEl.getElement('.scdwShowEventsBtn');
    	 if (scdwShowEventsBtn){
    		 scdwShowEventsBtn.addEvent('click', function(e){
    			 e.stop();
    			 
    			 var wrapper = scdwShowEventsBtn.getParent('.scdwEventsWrapper');
    			 wrapper.removeClass('hideEvents');    			
    		 });
    	 }
    	 
    	 var scdwHideEventsBtn = this.wrapperEl.getElement('.scdwHideEventsBtn');
    	 if (scdwHideEventsBtn){
    		 scdwHideEventsBtn.addEvent('click', function(e){
    			 e.stop();
    			 
    			 var wrapper = scdwHideEventsBtn.getParent('.scdwEventsWrapper');
    			 wrapper.addClass('hideEvents');    			
    		 });
    	 }
    },
    
    setHelpClickEvent : function(){
    	var _this = this;
    	
    	var scdbHelp = this.wrapperEl.getElement('.scdbHelp');
    	if (scdbHelp){
    		scdbHelp.addEvent('click', function(e){
    			e.stop();
    			_this.wrapperEl.addClass('scdwShowHelp');
    		});
    	}
    	
    	var scdwHelpClose = this.wrapperEl.getElement('.scdwHelpClose');
    	if (scdwHelpClose){
    		scdwHelpClose.addEvent('click', function(e){
    			e.stop();
    			_this.wrapperEl.removeClass('scdwShowHelp');
    		});
    	}
    },
    
    
    getRandomNum : function(lbound, ubound) {
    	return (Math.floor(Math.random() * (ubound - lbound)) + lbound);
	},
    
	getRandomChar : function(){
    	var numberChars = "0123456789";
    	var lowerChars = "abcdefghijklmnopqrstuvwxyz";
    	var upperChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    	//var otherChars = "`~!@#$%^&*()-_=+[{]}\\|;:'\",<.>/? ";
    	
    	var charSet = numberChars + lowerChars + upperChars;
    	
    	var char = charSet.charAt(this.getRandomNum(0, charSet.length));
    	
    	return char;
    	
    },
    
    getHashKey : function(length){
    	var hashKey = '';
    	
    	if (this.REF){
    		hashKey += 'R_' + this.REF + '_';
    	}
    	
    	if (this.CAL_ID){
    		hashKey += 'CA_' + this.CAL_ID + '_';
    	}
    	
    	if (this.CTG_ID){
    		hashKey += 'CT_' + this.CTG_ID + '_';
    	}
    	
    	if (this.USER_IP){
    		hashKey += 'I_' + this.USER_IP.replace(/\./g, '') + '_';
    	}
    	
    	for (var i=0; i < length; i++){
    		hashKey += this.getRandomChar();
    	}
    	
    	return hashKey;
    },
    
    sendUserCalHashKey : function(calType){
    	var src = this.ROOT_URL + 'admin/UpdateStats?calType=' + calType + '&hash=' + this.userCalId;
    	
    	if (this.CAL_ID){
    		src += '&calId=' + this.CAL_ID;
    	}
    	
    	if (this.CTG_ID){
    		src += '&ctgId=' + this.CTG_ID;
    	}
    	
    	if (this.REF){
    		src += '&ref=' + this.REF;
    	}

    	if (this.LABEL){
    		src += '&label=' + this.LABEL;
    	}
    	
    	var jsEl = new Element('script', {
    		'type' : 'text/javascript',
    		'src' : src
    	});

    	jsEl.inject(this.wrapperEl);
    },
    
    setIconClickEvents : function(){
    	var iconsBtns = this.wrapperEl.getElements('.scdbIconWrapper');
    	if (iconsBtns){
    		var _this = this;
    		iconsBtns.each(function(btn){
    			btn.addEvent('click', function(e){
    				var href = btn.href;
    				var calType = btn.getAttribute('calType');
    				
    				if (calType == 'mobile' && !spdwClientIsMobile){
    					_this.prepareMobilePopup();
    					
    					e.stop();
    					return;
    				}
    				
    				if (href.indexOf(_this.HASH_STRING) >= 0){
    					_this.userCalId = _this.getHashKey(30);
    					
    					href = href.replace(_this.HASH_STRING, _this.userCalId);
    					btn.set('href', href);
    					
    					_this.sendUserCalHashKey(calType);
    				}
    				
    				if (calType == 'any' && e){
    					e.stop();
    					
    					var scdbTopWrapper  = _this.wrapperEl.getElement('.scdbTopWrapper');
    					if (scdbTopWrapper && scdbTopWrapper.hasClass('show_scdbTitleWrapper')){
    						scdbTopWrapper.removeClass('show_scdbTitleWrapper');
    						
    						var scdbLink = scdbTopWrapper.getElement('.scdbLink');
    						if (scdbLink){
    							scdbLink.set('value', href);
    							scdbLink.focus();
    							scdbLink.select();
    						}
    					} else if (scdbTopWrapper){
    						scdbTopWrapper.addClass('show_scdbTitleWrapper');
    					}
    					//
    				}
    			});
    		});
    	}
    	//scdbIconWrapper
    }
});



//DeBug
/*
function cl(mix){
	console.log(mix);
}
*/