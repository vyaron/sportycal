var FBFriendsSelector = new Class({
	DEF_SEARCH_TEXT : 'Type friend name...',
	
	user_params : 'uid,name',
	
	wrapperEl : null,
	
	friends : [],
	uidToFriend : {},
	
	friendsSelected : new Hash(),
	
	friendsSelFx : null,
	
    initialize: function(options){
        this.options = options;
        
        if (this.options.id){
        	this.wrapperEl = $(this.options.id);

        	//set more parms
        	if (this.options.more_parms){
        		this.user_params += ',' + this.options.more_parms.toString();
        	}
        	
        	this.updateFriendsSelParagraph();
        }
    },
    
    clearSearch : function(){
    	var fbFriendSearch = this.wrapperEl.getElement('.fbFriendSearch');
    	var searchVal = fbFriendSearch.get('value');
    	fbFriendSearch.set('value', '');
    	
    	this.setDefSearchText();
    	
    	if (searchVal != '' && searchVal != this.DEF_SEARCH_TEXT){
    		this.friends.each(function(friend){
    			friend.htmlEl.removeClass('hidden');
    		});
    	}
    },
    
    setDefSearchText : function(){
    	var fbFriendSearch = this.wrapperEl.getElement('.fbFriendSearch');
    	
    	var searchVal = fbFriendSearch.get('value');
    	
    	if (!searchVal || searchVal == ''){
    		fbFriendSearch.set('value', this.DEF_SEARCH_TEXT);
        	fbFriendSearch.addClass('fbFriendSearchDef');
    	} else {
    		fbFriendSearch.removeClass('fbFriendSearchDef');
    	}
    },
    
    searchInProperty : function(property1, strToSearch1){
		var prop = null;
		if (typeof property1 != 'undefined'){
			prop = property1;
		}
		
		var needle = null;
		if (typeof strToSearch1 != 'undefined'){
			needle = strToSearch1.toLowerCase();
		}
		
		var searchFriends = [];
		if (needle && prop && this.friends){
			this.friends.each(function(friend){
				var propVal = friend[prop];
				if (propVal){
					propVal = propVal.toLowerCase();

					var needlePos = propVal.indexOf(needle);
					if (needlePos >= 0){
						searchFriends.push(friend);
					}
				}
			});
		}
		
		if (searchFriends.length || strToSearch1 != ''){
			this.friends.each(function(friend){
				friend.htmlEl.addClass('hidden');
			});

			searchFriends.each(function(friend){
				friend.htmlEl.removeClass('hidden');
			});
		} else {
			this.friends.each(function(friend){
				friend.htmlEl.removeClass('hidden');
			});
		}
		
	},
    
    setSearchEvent : function(){
    	var _this = this;
    	
    	var fbFriendSearch = this.wrapperEl.getElement('.fbFriendSearch');
    	
    	this.setDefSearchText();
    	
    	fbFriendSearch.addEvent('focus', function(e){
    		e.stop();
    		
    		var searchVal = fbFriendSearch.get('value');
    		
    		if (searchVal == _this.DEF_SEARCH_TEXT){
    			fbFriendSearch.set('value', '');
    		}
    		fbFriendSearch.removeClass('fbFriendSearchDef');
    	});
    	
    	fbFriendSearch.addEvent('blur', function(e){
    		e.stop();
    		
    		_this.setDefSearchText();
    	});
    	
    	fbFriendSearch.addEvent('keyup', function(e){
    		var searchVal = fbFriendSearch.get('value');
    		
    		_this.searchInProperty('name', searchVal);
    	});
    },
    
    setSelAllEvent : function(){
    	var _this = this;
    	
    	var fbFriendCheckAll = this.wrapperEl.getElement('.fbFriendCheckAll');
    	fbFriendCheckAll.addEvent('click', function(e){
    		_this.clearSearch();
    		
    		_this.friends.each(function(friend){
    			if (fbFriendCheckAll.getProperty('checked')){
    				//cl('checked');
    				_this.selFriend(friend);
    			} else {
    				//cl('NOT checked');
    				_this.unselFriend(friend);
    			}
    		});
    	});
    },
    
    selFriends : function(uids){
    	var _this = this;
    	uids.each(function(uid){
    		if (_this.uidToFriend[uid]){
    			_this.selFriend(_this.uidToFriend[uid]);
    		}
    	});
    },
    
   
    getFriends : function(){
    	var _this = this;
    	
    	var q = 'SELECT ' + _this.user_params + ' FROM user WHERE uid in (SELECT uid2 FROM friend WHERE uid1=me())';
    	
    	if (this.options.req_parms){
    		this.options.req_parms.each(function(req_parm){
    			q += ' AND (' + req_parm + '!="")';
    		});
    	}
    	
    	q += ' ORDER BY name';
    	
    	FB.api({
			method: 'fql.query',
			 query: q
		},
		function(friends) {
			//cl(friends);

		   if (friends && friends.length){
			   _this.friends = friends;
			   
			   var fbFriendWrapper = _this.wrapperEl.getElement('.fbFriendWrapper');
			   fbFriendWrapper.set('html', '');
			   
			   friends.each(function(friend){
				   _this.uidToFriend[friend.uid] = friend;
				   _this.setFriend(friend);
			   });
			   
			   var cbEl = new Element('div', {'class' : 'cb'});
			   cbEl.inject(fbFriendWrapper);
			   
			   _this.setSelAllEvent();			
			   _this.setSearchEvent();
		   }
		   
		   fireEvent('endGetFbFriends');
		});
    },
    
    updateFriendsSelParagraph : function(){
    	var fbFriendSelP = this.wrapperEl.getElement('.fbFriendSelP');
    	var numOfFriendsSel = this.friendsSelected.getLength();
    	
    	var pValue = '';
    	if (numOfFriendsSel == 1){
    		pValue = '1 Friend selected';
    	} else {
    		pValue = numOfFriendsSel + ' Friends selected';
    	}
    	
    	fbFriendSelP.set('html', pValue);
    	
    	if (!this.friendsSelFx){
    		this.friendsSelFx = new Fx.Morph(fbFriendSelP);
    	}
    	
    	this.friendsSelFx.start({
    		'background-color' : ['#FEDAC5', '#fff']
    	});
    },
    
    selFriend : function(friend){
    	var fbFriendCheckbox = friend.htmlEl.getElement('.fbFriendCheckbox');
    	
    	this.friendsSelected.set(friend.uid, friend);
		friend.htmlEl.addClass('fbFriendSel');
		fbFriendCheckbox.setProperty('checked', 'checked');
		
		this.updateFriendsSelParagraph();
    },
    
    unselFriend : function(friend){
    	var fbFriendCheckAll = this.wrapperEl.getElement('.fbFriendCheckAll');
    	var fbFriendCheckbox = friend.htmlEl.getElement('.fbFriendCheckbox');
    	
    	this.friendsSelected.erase(friend.uid);
		friend.htmlEl.removeClass('fbFriendSel');
		fbFriendCheckbox.removeProperty('checked');
		
		fbFriendCheckAll.removeProperty('checked');
		
		this.updateFriendsSelParagraph();
    },
    
    checkFriend : function(friend){
 	
    	var fbFriendCheckbox = friend.htmlEl.getElement('.fbFriendCheckbox');
    	
    	
    	if (this.friendsSelected.get(friend.uid)){
    		this.unselFriend(friend);
    	} else {
    		this.selFriend(friend);
    	}
    	
    	window.fireEvent('FBFriendsSelectorChange');
    },
    
    setFriend : function(friend){
    	var _this = this;
    	var dummy_fbFriend = this.wrapperEl.getElement('.dummy_fbFriend');

    	var friendEl = new Element('div', {
    		'class' : 'fbFriend',
    		'html' : dummy_fbFriend.get('html')
    	});

    	var fbFriendImg = friendEl.getElement('.fbFriendImg');
    	var fbFriendName = friendEl.getElement('.fbFriendName');
    	var fbFriendDate = friendEl.getElement('.fbFriendDate');
    	
    	fbFriendImg.setStyle('background-image', 'url(https://graph.facebook.com/' + friend.uid + '/picture)');
    	fbFriendName.set('html', friend.name);
    	fbFriendDate.set('html', friend.birthday_date);
    	
    	friend.htmlEl = friendEl;
    	
    	friendEl.addEvent('click', function(e){
    		//e.stop();
    		
    		_this.checkFriend(friend);
    	});
    	
    	this.wrapperEl.removeClass('fbFriendLoading');
    	
    	var fbFriendWrapper = this.wrapperEl.getElement('.fbFriendWrapper');
    	
    	friendEl.inject(fbFriendWrapper);

    },
    
    getFriendsSelected : function(){
    	return this.friendsSelected;
    },
    
    friendsSelectedIdsString : function(){
    	return this.getFriendsSelected().getKeys().toString();
    },
    
    getAllFriendsSelected : function(){
    	return this.friends;
    }
    
});

