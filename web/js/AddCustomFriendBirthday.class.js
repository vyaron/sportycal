var AddCustomFriendBirthday = new Class({
	currFriendId : 0,
	friends : new Hash(),
	
	friendsWrapperId : 'cfFriendsWrapper',
	
	//inputs
	nameId : 'cfName',
	dayId : 'cfDay',
	monthId : 'cfMonth',
	yearId : 'cfYear',
	addBtnId : 'acfbAddBtn',
	
	//friend dummy
	dummyId : 'dummy_customFriendBirthday',
	
    initialize: function(options){
        this.options = options;
        
        var _this = this;
        
        var acfbAddBtn = $(this.addBtnId);
        if (acfbAddBtn){
        	acfbAddBtn.addEvent('click', function(e){
        		e.stop();
        		_this.addFriend();
        	});
        }
    },
    
    createFriendEl : function(inNameVal,dateStr){
    	var dummy_customFriendBirthday = $(this.dummyId);
		if (dummy_customFriendBirthday){
			//create friends html element
			var friendEl = new Element('div', {
    			'class' : 'cfFriend',
    			'html' : dummy_customFriendBirthday.get('html')
    		});
			
			var cfFriendName = friendEl.getElement('.cfFriendName');
			if (cfFriendName){
				cfFriendName.set('html', inNameVal);
			}
			
			var cfFriendDate = friendEl.getElement('.cfFriendDate');
			if (cfFriendDate){
				cfFriendDate.set('html', dateStr);
			}
			
			var cfFriendClose = friendEl.getElement('.cfFriendClose');
			if (cfFriendClose){
				var _this = this;
				
				var currId = _this.currFriendId;
				cfFriendClose.addEvent('click', function(e){
					e.stop();
					
					_this.removeFriend(currId);
				});
			}
			
			friendEl.inject($(this.friendsWrapperId));
			
			//create friend object
			var friend = {
				'id' : this.currFriendId,
				'full_name' : inNameVal,
				'birthdate' : dateStr,
				'htmlEl' : friendEl
			};
			
			this.friends.set(this.currFriendId, friend);

			this.currFriendId += 1;
			this.clearInputs();			
		}
    },
    
    setFriends : function(userBirthdays){
    	var _this = this;
    	
    	var friendsList = $(this.friendsWrapperId);
    	if (friendsList){
    		friendsList.set('html', '');
    		this.friends = new Hash();
    	}
    	
    	userBirthdays.each(function(userBirthday){
    		if (userBirthday.full_name && userBirthday.birthdate){
    			_this.createFriendEl(userBirthday.full_name, userBirthday.birthdate);
    		}
    	});
    	
    	window.fireEvent('customFriendsBirthdayChange');
    },
    
    addFriend : function(){
    	
    	var inName = $(this.nameId);
    	if (inName){
    		var inNameVal = inName.get('value');
    		
    		if (!inNameVal){
    			alert('Please enter your friend\'s name');
    		} else {
    			
	    		var cfDay = $(this.dayId);
	    		var cfMonth = $(this.monthId);
	    		var cfYear = $(this.yearId);
	    		
	    		if (cfDay && cfMonth && cfYear){
	    			var cfDayVal = cfDay.get('value');
	    			var cfMonthVal = cfMonth.get('value');
	    			var cfYearVal = cfYear.get('value');
	    			
	    			var dateStr = cfMonthVal + '/' + cfDayVal;
	    			if (cfYearVal.toInt()){
	    				dateStr += '/' + cfYearVal;
	    			}

	    			this.createFriendEl(inNameVal,dateStr);
	    		}
    		}
    	}
    	
    	window.fireEvent('customFriendsBirthdayChange');
    },
    
    removeFriend : function(id){
		var friend = this.friends.get(id);
		if (friend){
			//remove html element
			friend.htmlEl.destroy();
			
			//remove friend object
			this.friends.erase(id);
		}
		
		window.fireEvent('customFriendsBirthdayChange');
	},
	
	clearInputs : function(){
		var inName = $(this.nameId);
		if (inName){
			inName.set('value' , '');
		}
		
		var cfDay = $(this.dayId);
		if (cfDay){
			cfDay.set('value' , '01');
		}
		
		var cfMonth = $(this.monthId);
		if (cfMonth){
			cfMonth.set('value' , '01');
		}
		
		var cfYear = $(this.yearId);
		if (cfYear){
			cfYear.set('value' , '0');
		}
	},
	
	toJSON : function(){
		var parsedFriends = [];
		
		this.friends.each(function(friend){
			var parsedFriend = {
				'full_name' : friend.full_name,
				'birthdate' : friend.birthdate
			};
			parsedFriends.push(parsedFriend);
		});
		
		return JSON.encode(parsedFriends);
	}
});

