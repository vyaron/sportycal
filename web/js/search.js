var Search = new Class({
	Implements: Options,
	
	options : {
		ajaxSearchEl : null,
		startDateEl :null,
		endDateEl : null,
		submitEls : null,
		moreOptions : false,
		disableEnterSubmit : false,
		hiddenEl : null,
		searchType : 'ctg'
	},
	
	el : null,
	formEl : null,
	
	lastSearch : null,
	ctgReq : null,
	calReq : null,
	
	currAjaxItems : [],
	currAjaxSelItem : -1,
	
	sbMoreOptionsFx : null,
	sbMoreOptionsOpen : false,
	
	initialize : function(el, options){
		if (el){
			this.setOptions(options);
			
			this.el = el;
			this.el.addEvent('keyup', this.onKeyUp.bind(this));
			
			this.ctgReq = new Request.JSON({
				'url' 		: '/main/searchCtgs',
				'link'		: 'cancel',
				'onSuccess' : this.setRes.bind(this)
			});
			
			this.calReq = new Request.JSON({
				'url' 		: '/main/searchCals',
				'link'		: 'cancel',
				'onSuccess' : this.setRes.bind(this)
			});
			
			if (this.options.moreOptions){
				this.setMoreOptions();
			}

			this.setSubmitEvent();
		}
	},
	
	setSearchType : function(type){
		this.options.searchType = type;
	},
	
	submitForm : function(e){
		if (e){
			e.stop();
		}
		
		if (!this.formEl){
			this.formEl = this.el.getParent('form');
		}
		
		this.formEl.submit();
	},
	
	setSubmitEvent : function(){
		var _this = this;
		
		var btns = this.options.submitEls;
		if (btns){
			btns.each(function(btn){
				btn.addEvent('click', _this.submitForm.bind(_this));
			});
		}
	},
	
	showMoreOptions : function(e){
		e.stop();
		
		this.sbMoreOptionsOpen = true;
		
		var sbOptionsShow = e.target;
		if (sbOptionsShow){
			sbOptionsShow.addClass('hidden');
		}
		
		if (this.sbMoreOptionsFx){
			this.sbMoreOptionsFx.slideIn();
		}
	},
	
	hideMoreOptions : function(e){
		e.stop();
		
		this.sbMoreOptionsOpen = false;		
		
		if (this.sbMoreOptionsFx){
			this.sbMoreOptionsFx.slideOut();
		}
	},
	
	setMoreOptions : function(){
		var _this = this;
		
		var sbOptionsShow = $('sbOptionsShow');
		if (sbOptionsShow){
			sbOptionsShow.addEvent('click', this.showMoreOptions.bind(this));
		}
		
		var sbOptionsHide = $('sbOptionsHide');
		if (sbOptionsHide){
			sbOptionsHide.addEvent('click', this.hideMoreOptions.bind(this));
		}
		
		var sbMoreOptions = $('sbMoreOptions');
		if (sbMoreOptions){
			this.sbMoreOptionsFx = new Fx.Slide(sbMoreOptions,{
				onComplete : function(){
					if (! _this.sbMoreOptionsOpen){
						if (sbOptionsShow){
							sbOptionsShow.removeClass('hidden');
						}
					}
				}
			});
			this.sbMoreOptionsFx.hide();
			sbMoreOptions.removeClass('hidden');
		}
	},
	
	setRes : function(items){
		var _this = this;
		
		var ajaxSearchEl = this.options.ajaxSearchEl;
		if (ajaxSearchEl && items){
			ajaxSearchEl.set('html', '');
			
			this.clearAjaxSearch();
			
			items.each(function(item){
				var itemEl = new Element('p', {
					'class' : 'ajaxCtgRes',
					'html' : item.name,
					'itemId' : item.id 
				});
				
				itemEl.inject(ajaxSearchEl);
				_this.currAjaxItems.push(itemEl);
				
				itemEl.addEvent('click', function(e){
					e.stop();

					ajaxSearchEl.addClass('hidden');
					_this.el.set('value', itemEl.get('html'));
					
					if (_this.options.hiddenEl){
						_this.options.hiddenEl.set('value', itemEl.get('itemId'));
					}
				});
			});

			this.showAjaxSearch();
		} else if (ajaxSearchEl){
			this.hideAjaxSearch();
		}
	},

	onMoveKey : function(keyName){
		if (this.currAjaxItems.length && this.currAjaxSelItem in this.currAjaxItems){
			this.currAjaxItems[this.currAjaxSelItem].removeClass('ajaxSearchSelected');
		}

		switch (keyName){
			case 'enter':
				if (this.currAjaxSelItem in this.currAjaxItems){
					this.el.set('value', this.currAjaxItems[this.currAjaxSelItem].get('html'));
					
					if (this.options.hiddenEl){
						this.options.hiddenEl.set('value', this.currAjaxItems[this.currAjaxSelItem].get('itemId'));
					}
					
					this.hideAjaxSearch();
				}
				
				if (!this.options.disableEnterSubmit){
					this.submitForm();
				}
				
				break;
			case 'down':
				var newAjaxSelCtgIndex = this.currAjaxSelItem +1;
				if (this.currAjaxItems.length && newAjaxSelCtgIndex in this.currAjaxItems){
					this.currAjaxSelItem = newAjaxSelCtgIndex;
					this.currAjaxItems[this.currAjaxSelItem].addClass('ajaxSearchSelected');
				}
				break;
			case 'up':
				var newAjaxSelCtgIndex = this.currAjaxSelItem -1;
				if (this.currAjaxItems.length && newAjaxSelCtgIndex in this.currAjaxItems){
					this.currAjaxSelItem = newAjaxSelCtgIndex;
					this.currAjaxItems[this.currAjaxSelItem].addClass('ajaxSearchSelected');
				}
				break;
		}
		
	},
	
	onKeyUp : function(e){
		if (e) {
			e.stop();
		}

		var txtVal = this.el.get('value');
		if (this.lastSearch == txtVal && e){
			var keyName = e.key;
			if (keyName == 'down' || keyName == 'up' || keyName == 'enter'){
				this.onMoveKey(keyName);
			}
		} else if (txtVal && txtVal.length >= 3){
			this.lastSearch = txtVal;
			
			if (this.options.searchType == 'ctg'){
				this.ctgReq.post({
					'txtSearch' : txtVal,
					'fromDate' : this.getStartDate(),
					'toDate' : this.getEndDate()
				});
			} else {
				this.calReq.post({
					'txtSearch' : txtVal
				});
			}
			
		} else {
			this.hideAjaxSearch();
		}
	},
	
	getStartDate :  function(){
		var val = '';
		if (this.options.startDateEl){
			val = this.options.startDateEl.get('value');
		}
		
		return val;
	},
	
	getEndDate : function(){
		var val = '';
		if (this.options.endDateEl){
			val = this.options.endDateEl.get('value');
		}
		
		return val;
	},
	
	clearAjaxSearch : function(){
		this.currAjaxItems = [];
		this.currAjaxSelItem = -1;
	},
	
	showAjaxSearch : function(){
		var ajaxSearchEl = this.options.ajaxSearchEl;
		if (ajaxSearchEl){
			var coord = this.el.getCoordinates();
			
			ajaxSearchEl.setStyles({
				'left' : coord.left,
				'top' : coord.top + coord.height
			});
			
			ajaxSearchEl.removeClass('hidden');
		}
	},
	
	hideAjaxSearch : function(){
		var ajaxSearchEl = this.options.ajaxSearchEl;
		if (ajaxSearchEl){
			this.clearAjaxSearch();
			ajaxSearchEl.addClass('hidden');
		}
	}
});

