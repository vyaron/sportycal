var gDoNotRedirectAfterFbLogin = true;

var gUserLogin = null;

var gCurrStep = null;
var gStepsEls = [];

var gCustomFriendBirthday = null;
var gGotFbFriends = false;


function goBack(){
	var stepNum = gCurrStep - 1;
	if (stepNum < 0){
		stepNum = 0;
	}
	openStep(stepNum);
}

function goNext(){
	var stepNum = gCurrStep + 1;
	if (stepNum in gStepsEls){
		openStep(stepNum);
	}
}

function setFriendsBirthdayList(){
	var friendsBirthdayList = $('friendsBirthdayList');
	if (friendsBirthdayList){
		friendsBirthdayList.set('html', '');
		
		var friends = [];
		if (typeof gCustomFriendBirthday != 'undefined' && gCustomFriendBirthday.friends){
			gCustomFriendBirthday.friends.each(function(friend){
				var f = {birthdate : friend.birthdate, name : friend.full_name};
				friends.push(f);
			});
		}
		
		if (typeof gFBFreinds_birthdayCal != 'undefined'){
			var fbFriends = gFBFreinds_birthdayCal.getFriendsSelected();
			fbFriends.each(function(friend){
				var f = {birthdate : friend.birthday_date, name : friend.name};
				friends.push(f);
			});
		}
		
		friends.each(function(friend){
			var el = new Element('div', {
				'class' : 'friendInCal',
				'html' : '<div class="friendInCalName">' + friend.name + '</div><div class="friendInCalDate">' + friend.birthdate + '</div>'
			});
			el.inject(friendsBirthdayList);
		});
	}
}

function performLogin(){
	var userIdReq = new Request({
		url : '/main/fbLogin',
		method : 'get',
		onSuccess : function(res){
			gUserLogin = res;
			
			if (res){
				//reportIntel("birthdayCal", "fb-login", "", 0);
			}
			
			if (typeof gCalDownBcId != 'undefined') {
				gCalDownBcId = res;
			}

			if (typeof gCalDownLabel != 'undefined') {
				gCalDownLabel += res;
			}
			
			replaceInCalUrl("calLink_mobile", "USERID", res, false);
			replaceInCalUrl("calDownLink", "USERID", res, false);
			replaceInCalUrl("calLink_any", "USERID", res, true);
			gFBFreinds_birthdayCal.getFriends();			
		}
	});

	userIdReq.send('getUserId=1');
}

function fbConnect(){
	FB.login(function(response) {
		if (response.authResponse) {
		  openStep(1);
		  performLogin();
	  	}
	}, {scope:'email,user_birthday,publish_stream,friends_birthday'});

}

function fbPost(){
	
	FB.api('/me/feed', 'post', {
		'actions'	:	{"name": "View on sportYcal", "link": 'http://sportycal.com/main/friendsBirthday/src/fb'},
		'message'		: 'I have just created a cool friends birthday calendar that was added to my personal calendar, make yourself one here',
		//'name' 			: 'a Trip Plan from GoPlanIt.com',
		//'caption' 		: 'Plan your trip and Go...',
		'picture' 		: 'http://sportycal.com/images/friendsBirthday/friendsBirthdayCal.png',
		//'description' 	: 'Travel Planning at the click of a button',
		'link'			: 'http://sportycal.com/main/friendsBirthday/src/fb'
		}, function(response) {
		if (!response || response.error) {
			//cl(response.error);
		} else {
			//reportIntel("frinedsBirthday", "fb-post", "", 0);
		}
	});
}

function saveFriends(){
	var selFbFriends = gFBFreinds_birthdayCal.friendsSelectedIdsString();
	var customFriends = gCustomFriendBirthday.toJSON();

	var params = 'clear=1&d=' + Math.floor(Math.random()*11000000);
	if (selFbFriends){
		params += '&fbFriends=' + selFbFriends;
	}

	if (customFriends){
		params +=  '&cFriends=' + customFriends;
	}
	
	var myRequest = new Request({
		method : 'post',
		url : '/main/friendsBirthday/',
		onSuccess : function(){
			fbPost();
			reportIntel("birthdayCal", "fb-post", "user:"+gUserLogin, 0);
			setFriendsBirthdayList();
			openStep(3);
		}
	}).post(params);
}

window.addEvent('domready', function(){
	gCustomFriendBirthday = new AddCustomFriendBirthday();
	
	var downloadPageBtn = $('downloadPageBtn');
	downloadPageBtn.addEvent('click', function(e){
		e.stop();

		saveFriends();
	});
	
	window.fireEvent('friendsBirthday');
});

//User without fb friends
window.addEvent('endGetFbFriends', function(){
	gGotFbFriends = true;
	
	//show next btns in FB Freinds step
	var nextBtns = $$('#fbFriendsBirthdayStep .stepBtn');
	if (nextBtns){
		nextBtns.each(function(btn){
			btn.removeClass('hidden');
		});
	}
	
	//Select current frineds in user calendar
	var myRequest = new Request({
		method : 'get',
		url : '/main/friendsBirthday/',
		onSuccess : function(res){
			var currFriends = JSON.decode(res);

			//select fb firends
			if (currFriends.fbUids){
				gFBFreinds_birthdayCal.selFriends(currFriends.fbUids);
			}

			//set Custom friends
			if (currFriends.userBirthdays){
				gCustomFriendBirthday.setFriends(currFriends.userBirthdays);
			}
			
		}
	}).post('currFriends=1');

	//no Fb friends with birthday open custom frinds and show msg
	if (gFBFreinds_birthdayCal.friends.length <= 0){
		openStep(2);

		var noFbFriends = $('noFbFriends');
		if (noFbFriends){
			noFbFriends.removeClass('hidden');
		}
	}
});