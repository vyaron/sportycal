var Toto = new Class({
	Implements: Options,
	
	options : {
		apiCtgUrl : '/index.php/category/api',
		apiCalUrl : '/index.php/cal/api',
		ref : '',
		rootCtgId : '',

		sportsId : 'sports',
		factoryId : 'factory',
		teamsId : 'teams',
		contBtnId : 'contBtn',

		txtEvents : 'משחקים',
		txtIn : ' - ',
	},

	
	haveTeams : true,
	currCalRowEl : null,
	currCal : null,
	
	currCalId : null,
	currSportCtgId : null,
	
	currCalKey : null,
	cals : {},
	
	MAX_SLIDE_STEP : 4,
	
    initialize: function(options){
		this.setOptions(options);
        
		//window.fbAsyncInit = this.changeFbCanvasSize.bind(this);
		
		this.getSports(this.options.rootCtgId);
		
  		this.prepareUI();  		
    },
    
    scrollTop : function(){
    	//Not working on Facebook scroller
		new Fx.Scroll($(document.body), {
		    offset: {
		        x: 0,
		        y: 0
		    }
		}).toTop();
		
		//Fb Scroll To Top
		try {
			FB.Canvas.scrollTo(0,0);
		} catch (e) {
			
		}
    },
    
    fbSahre : function(e){
    	if (e){
    		e.stop();
    	}
    	
    	try {
    		FB.ui({
	    		method: 'feed',
	    		name: 'WinnerTime',
	    		link : 'http://www.sportycal.com/l/totoFanPageTab',
	    		picture: 'http://www.sportycal.com/images/toto/icon.jpg',
	    		caption: 'יומן אירועי הספורט שלך!',
	    		description: 'מהיום מורידים את אירועי הספורט החמים של ווינר ישירות ליומן האישי',
	    		actions: [{name  : 'שלח טופס' , link : 'http://www.sportycal.com/l/winner-from-share'}]
    		}, function(res){
    			//cl(res);
    		});
		} catch (e) {
			
		}
    },
    
    changeFbCanvasSize : function(){
    	var content = $('content');
    	if ( $('content')){
    		var size = content.getSize();
    		
    		try {
    			FB.Canvas.setSize({height: Math.max(size.y, 820)});
    		} catch (e) {
    		}
    	}
    },
    
    prepareUI : function(){
    	var contBtn1 = $('contBtn1');
    	if (contBtn1){
    		contBtn1.addEvent('click', function(e){
    			e.stop();
    			this.hideStep('totoStep_1');
    			this.showStep('totoStep_2', true);
    			
    			this.scrollTop();
    		}.bind(this));
    	}
    	
    	
        //Dropdowns
    	var sports = $(this.options.sportsId);
		if (sports){
			sports.addEvent('change', function(){
				this.hideStep('totoStep_2_3');
				
				var val = sports.get('value');
				this.currSportCtgId = val;
				this.getFactories(val);
			}.bind(this));
		}

		var factory = $(this.options.factoryId);
		if (factory){
			factory.addEvent('change', function(){
				this.hideStep('totoStep_2_3');
				
				var val = factory.get('value');
				if (this.haveTeams){
					this.getTeams(val);
				} else if (val){
					this.currCalId = val;
					this.getCals(this.bulidCals);
				}
			}.bind(this));
		}

		var teams = $(this.options.teamsId);
		if (teams){
			teams.addEvent('change', function(){
				this.hideStep('totoStep_2_3');
				
				var val = teams.get('value');
				if (val){
					this.currCalId = val;
					this.getCals(this.bulidCals);
				}
			}.bind(this));
		}

		//continue btn
		/*
		var contBtn = $(this.options.contBtnId);
		if (contBtn){
			contBtn.addEvent('click', function(e){
				e.stop();

				this.getCals(this.bulidCals);
				
			}.bind(this));
		}
		*/

		var calDown = $$('.calDown, #calDownOnMobile');
		if (calDown){
			calDown.each(function(el){
				el.addEvent('click', function(e){
					//e.stop();
					this.setUserCal(el, e);
				}.bind(this));
			}.bind(this));
		}
		
		var calDownPopupClose = $('calDownPopupClose');
		if (calDownPopupClose){
			calDownPopupClose.addEvent('click', hideCalPopup);
		}
		
		var helpIcon = $('helpIcon');
		if (helpIcon){
			helpIcon.addEvent('click', showHelp);
		}
		
		
		var backBtn = $('backBtn');
		if (backBtn){
			backBtn.addEvent('click', function(e){
				e.stop();
				
				this.hideStep('totoStep_3');
				this.showStep('totoStep_2', true);
			}.bind(this));
		}
		
		var shareBtn = $('shareBtn');
		if (shareBtn){
			shareBtn.addEvent('click', this.fbSahre.bind(this));
		}
    },
    
    showStep : function(setpId, fast){
    	var el = $(setpId);
    	if (el && el.hasClass('hide')){
    		el.removeClass('hide');
    		
    		if (!fast){
    			var myFx = new Fx.Slide(el, {onComplete : this.changeFbCanvasSize.bind(this)});

        		myFx.hide();
        		myFx.slideIn();
    		}
    	}
    	
    },
    
    hideStep : function(setpId, hideParents1){
    	var hideParents = true;
    	if (typeof hideParents1 != 'undefined'){
    		hideParents = hideParents1;
    	}
    	
    	var el = $(setpId);
    	if (el){
    		el.addClass('hide');
    		
    		if (hideParents){
    			var idParts = el.getProperty('id').split('_');
        		if (idParts.length == 3){
        			for ( var i = idParts[2].toInt(); i < this.MAX_SLIDE_STEP; i++) {
    					var nextStepId = idParts[0] + '_' + idParts[1] + '_' + (i+1);
    					this.hideStep(nextStepId, false);
    				}
        		}
    		}
    		
    		
    		
    		/*
    		var myFx = new Fx.Slide(el);
    		myFx.slideOut();
    		*/
    	}
    	
    },
    
    showPopup : function(type, href){
    	switch (type) {
		case 'any':
			showCalUrl();
			
			var calLink_any = $('calLink_any');
			if (calLink_any && href){
				calLink_any.set('text', href);
			}
			break;
		case 'mobile':
			showCalMobile();
			break;
		default:
			break;
		}
    },
    
	setUserCal : function(el, e){
		var href = '';
		
		var hasUserCalId = false;
		if (el.getProperty('href').indexOf('USERCAL') < 0){
			hasUserCalId = true;
			href = el.getProperty('href');
		}
		
		if (this.currCalKey in this.cals){
			this.currCal = this.cals[this.currCalKey];
		}
		
		if (this.currCal && !hasUserCalId){
			var calId = this.currCal.id;
			var ctgId = this.currCal.category_id;
			
			if (calId){
				ctgId = '';
			} else {
				calId = '';
			}
			
			var calType = el.get('data-cal-type');

			var userCalId = generateUserCalId(calId, ctgId, '', calType);

			if (userCalId && userCalId > 0){
				href = el.get('href');
				href = href.replace('USERCAL', userCalId);

				el.set('href', href);
			} else {
				e.stop();
			}
		}
		
		var type = el.getProperty('data-open-popup');
		if (type){
			e.stop();
			this.showPopup(type, href);
		} else {
			this.scrollTop();
			this.hideStep('totoStep_2');
			this.showStep('totoStep_3', true);
		}
	},

	handleCalDown : function(){
		if (this.currCalKey){
			this.showStep('totoStep_2_4');
			
			if (this.currCalKey in this.cals){
				var currCal = this.cals[this.currCalKey];
				
				//Set cal down links
				if (currCal.any_url){
					var calDownAny = $('calDownAny');
					if (calDownAny){
						calDownAny.setProperty('href', currCal.any_url);
					}
		        }
				
		        if (currCal.mobile_url){
					var calDownMobile = $$('[data-cal-type="mobile"]');
					if (calDownMobile.length){
						calDownMobile.setProperty('href', currCal.mobile_url);
					}
		        }

		        if (currCal.outlook_url){
					var calDownOutlook = $('calDownOutlook');
					if (calDownOutlook){
						calDownOutlook.setProperty('href', currCal.outlook_url);
					}
		        }

		        if (currCal.google_url){
					var calDownGoogle = $('calDownGoogle');
					if (calDownGoogle){
						calDownGoogle.setProperty('href', currCal.google_url);
					}
		        }
		        
		        //set events count
		        var evnetsCount = $('evnetsCount');
		        if (evnetsCount){
		        	evnetsCount.set('text', currCal.events_count);
		        }
		        
		        //Set updated at
		        var eventsLastUpdate = $('eventsLastUpdate');
		        if (eventsLastUpdate){
		        	eventsLastUpdate.set('text', currCal.update_at);
		        }
			}
		}
	},
	
	buildCal : function(cal, calName){
		if (cal){
			var dummyEl = $('dummy_cal');
			if (dummyEl){
				var calEl = new Element('div', {
					'class' : 'calRow',
					'html' : dummyEl.get('html')
				});
				
				
				
				var calId = '0';
				if (cal.id){
					calId = cal.id;
				}
				
				var key = calId + '_' + cal.category_id;
				this.cals[key] = cal;
				
				calEl.setProperty('data-cal-key', key);
				
				//Set Cal Name
				var name = cal.name;
				if (calName){
					name = calName;
				}
				
				var nameEl = calEl.getElement('.calName');
				if (nameEl){
					nameEl.set('text', name);
				}
				
				/*
				var calEventsCount = calEl.getElement('.calEventsCount');
				if (calEventsCount){
					calEventsCount.set('text', cal.events_count + ' ' + this.options.txtEvents);
				}
				*/
				
				//nextEvent
				if (cal.nextEvent && cal.nextEvent.diff){
					var dummy_nextEvent = $('dummy_nextEvent');
					var nextEventEl = new Element('div', {
						'class' : 'nextEvent',
						//TODO: replace with bubble
						'title' : cal.nextEvent.name,
						'html' : dummy_nextEvent.get('html')
					});
					
					if (cal.nextEvent.diff.h){
						var h1Val = Math.floor(cal.nextEvent.diff.h.toInt() / 10);
						var h2Val = cal.nextEvent.diff.h.toInt() % 10;
						
						var dh1El = nextEventEl.getElement('.dh1');
						if (dh1El){
							dh1El.set('text', h1Val);
						}
						
						var dh2El = nextEventEl.getElement('.dh2');
						if (dh2El){
							dh2El.set('text', h2Val);
						}
					}
					
					if (cal.nextEvent.diff.i){
						var i1Val = Math.floor(cal.nextEvent.diff.i.toInt() / 10);
						var i2Val = cal.nextEvent.diff.i.toInt() % 10;
						
						var di1El = nextEventEl.getElement('.di1');
						if (di1El){
							di1El.set('text', i1Val);
						}
						
						var di2El = nextEventEl.getElement('.di2');
						if (di2El){
							di2El.set('text', i2Val);
						}
					}
					
					var calRowC = calEl.getElement('.calRowC');
					if (calRowC){
						nextEventEl.inject(calRowC, 'top');
					}
				}
				
				var calsList = $('calsList');
				if (calsList){
					calEl.inject(calsList);
				}

				calEl.addEvent('click', function(e){
					if (e){
						e.stop();
					}
					
					if (this.currCalKey){
						var currSelCal = $$('.calRow[data-cal-key="' + this.currCalKey + '"]');
						if (currSelCal.length){
							currSelCal[0].removeClass('calRowSel');
						}
					}
					
					calEl.addClass('calRowSel');
					
					this.currCalKey = calEl.getProperty('data-cal-key');
					this.handleCalDown(this.currCalKey);
				}.bind(this));
			}
		}
	},
	
	bulidCals : function(res){
		var calsList = $('calsList');
		if (calsList){
			calsList.set('html', '');
		}
		
		if (res){
			var teamName = $(this.options.factoryId).getSelected()[0].get('text');
			
			//Root Cal
			if (res.rootCal){
				var calName = res.rootCal.name;
				if (this.haveTeams){
					calName += ' ' + this.options.txtIn + teamName;
				}

				this.buildCal(res.rootCal, calName);
				
				if (this.haveTeams){
					if (res.aggregatedCal){
						this.buildCal(res.aggregatedCal);
					}
				}
				

				if (res.cals){
					for (factoryName in res.cals){
						var cal = res.cals[factoryName];
						
						var calName = cal.name;
						if (this.haveTeams){
							calName += ' ' + this.options.txtIn + factoryName;
						}
						
						this.buildCal(cal, calName);
					}
				}
			}
			
			this.showStep('totoStep_2_3');
			
			//Show cal down step
			if ($$('#calsList .calRow').length == 1){
				$$('#calsList .calRow')[0].fireEvent('click');
			}
		}
	},
	
	clearEls : function(ids){
		for ( var i = 0; i < ids.length; i++) {
			var id = ids[i];
			
			var el = $(id);
			if (el){
				el.set('html', '<option value="" selected="selected">' + el.getProperty('data-default-param') +  '</option>');
				el.setProperty('disabled', 'disabled');
			}
		}
	},
	
    getCtgs : function(id, cbFunc){
		var _this = this;

		if (id){
			var req = new Request.JSON({
	    		async : false,
	        	url : _this.options.apiCtgUrl,
	    		onSuccess : cbFunc.bind(_this)
	        });
	    	req.get({
	        	'ref' : _this.options.ref,
	        	'ctgId' : id,
	        	'min' : true
	        });
		}
    },
    
    getCals : function(cbFunc){
		var _this = this;

		if (this.currCalId && this.currSportCtgId){
			
			
			var req = new Request.JSON({
	    		async : false,
	        	url : _this.options.apiCalUrl,
	    		onSuccess : cbFunc.bind(_this)
	        });
	    	req.get({
	        	'ref' : _this.options.ref,
	        	'calId' : this.currCalId,
	        	'sportCtgId' : this.currSportCtgId,
	        	'totoCals' : true
	        });
		}
    },
	
	getSports : function(ctgId){
		this.clearEls([this.options.sportsId, this.options.factoryId, this.options.teamsId]);
		this.getCtgs(ctgId, this.setSports);
	},
	
	getFactories : function(ctgId){
		this.clearEls([this.options.factoryId, this.options.teamsId]);
		this.getCtgs(ctgId, this.setFactories);
	},

	getTeams : function(ctgId){
		this.clearEls([this.options.teamsId]);
		this.getCtgs(ctgId, this.setTeams);
	},
	
	sortByName : function (a,b){
		var nameA = a.name.toLowerCase();
		var nameB = b.name.toLowerCase();
		
		if (nameA < nameB){
			return -1;
		} else if (nameA > nameB){
			return 1;
		} else {
			return 0;
		}
	},
	
	setItems : function(items, elId){
		var prop = 'subCategories';
		if (!(items && items[prop] && items[prop].length)){
			prop = 'cals';
			
			if (elId == this.options.factoryId) {
				this.hideTeams();
				this.haveTeams = false;
			}

			//this.cals = items[prop];
		} else if (elId == this.options.factoryId) {
			this.showTeams();
			this.haveTeams = true;
		}
		
		var el = $(elId);
		if (el && items && items[prop] && items[prop].length){
			//items[prop].sort(this.sortByName);
			
			for ( var i = 0; i < items[prop].length; i++) {
				var obj = items[prop][i];

				//aggregare cals;
				if (!obj.id) continue;
				
				var opEl = new Element('option', {
					'value' : obj.id,
					'html' : obj.name
				});
				
				opEl.inject(el);
			}

			el.removeProperty('disabled');
		}
	},
	
	setSports : function(res){
		this.setItems(res, this.options.sportsId);
	},
	
	setFactories : function(res){
		this.setItems(res, this.options.factoryId);
	},
	
	setTeams : function(res){
		res.cals.sort(this.sortByName);
		
		this.setItems(res, this.options.teamsId);
	},

	showTeams : function(){
		var el = $(this.options.teamsId);
		if (el){
			el.removeClass('hide');
		}
	},

	hideTeams : function(){
		var el = $(this.options.teamsId);
		if (el){
			el.addClass('hide');
		}
	},

	showContBtn : function(){
		var contBtn = $(this.options.contBtnId);
		if (contBtn){
			contBtn.removeClass('hide');
		}
	},

	hideContBtn : function(){
		var contBtn = $(this.options.contBtnId);
		if (contBtn){
			contBtn.addClass('hide');
		}
	}
});