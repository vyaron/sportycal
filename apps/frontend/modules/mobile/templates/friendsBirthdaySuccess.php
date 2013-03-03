<!-- header -->
<?php
	$backRow = array();
	$backRow['nextBtn'] = true;
	
	include_partial('mobile/mainHeader',array('backRow' => $backRow, 'selectedTab' => 0));
	
	define('FB_FRIEND_ID', 'birthdayCal');
	
	$cal = $birthdayCal;

?>

<h1 id="mainTitle" class="hidden"><span id="mainTitleBig"></span><span id="mainTitleSmall"></span></h1>

<div id="friendsBirthdayBox">
	<div class="step">
		<div class="stepContent">
			<div id="desc" class="blueGradiantBg">
				<h3>Never miss a friend's birthday again!</h3>
				<ul>
					<li>1. Connect with your Facebook</li>
					<li>2. Select friends that you like (-:</li>
					<li>3. Get a birthday-calendar that you can add to any calendar you using (google, outlook, mobile, etc)</li>
				</ul>
			</div>
			<div class="pt15">
				<a id="proLoginBtn" class="stepBtn">Take the Calendar!</a>
			</div>
		</div>
	</div>
	<div class="step hidden">
		<div id="fbFriendsBirthdayStep" class="stepContent">
			<?php include_partial('main/fbFriends',array('id' => FB_FRIEND_ID, 'height' => -1)) ?>
			<a class="stepBtn nextBtn hidden" href="#">Next</a>
		</div>
	</div>
	<div class="step hidden">
		<div class="stepContent">
			<p id="noFbFriends" class="hidden">You do not have Facebook friends who share their birthday</p>
			<?php include_partial('main/addCustomFriendsBirthday') ?>
			<a id="downloadPageBtn" class="stepBtn" href="#">Make the Calendar</a>
		</div>
	</div>
	<div class="step hidden">
		<div class="stepContent">
			<?php
				$addClass = 'calLink_mobile itemRowBg1';
				$h4 = 'Add to my Calendar';
				$iconClass = 'calIconGlobal';
				$infoIconClass = 'itemArrowDown';
				
				if (Utils::clientIsAndroid()) {
					$href = $cal->getIcalUrl(Cal::TYPE_GOOGLE);
					$onClick = "return updateUserCals('" . Cal::TYPE_GOOGLE ."');";
				} else {
					$href = $cal->getIcalUrl(Cal::TYPE_MOBILE);
					$onClick = "return updateUserCals('" . Cal::TYPE_MOBILE ."');";
				}
				
				include_partial('mobile/itemRow', array('calDown' => TRUE, 'href' => $href, 'h4' => $h4, 'iconClass' => $iconClass, 'infoIconClass' => $infoIconClass, 'addClass' => $addClass, 'onClick' => $onClick, 'target' => '_blank'));
			?>
			
			<div id="friendsBirthdayList"></div>
		</div>
	</div>
</div>

<script type="text/javascript" src="/js/caldown.js"></script>
<script type="text/javascript" src="/js/basics.js"></script>
<?php sfContext::getInstance()->getResponse()->addJavascript('AddCustomFriendBirthday.class.js'); ?>
<?php sfContext::getInstance()->getResponse()->addJavascript('friendsBirthday.js'); ?>
<script type="text/javascript">

function updateSmallTitle(){
	var titleSmall = '';
	var friendsCount = 0;
	
	if (typeof gCustomFriendBirthday != 'undefined'){
		fbFriendsCount = gFBFreinds_birthdayCal.getFriendsSelected().getLength();
		friendsCount += fbFriendsCount;
	}

	if (typeof gCustomFriendBirthday != 'undefined' && gCustomFriendBirthday.friends){
		cFriendsCount = gCustomFriendBirthday.friends.getLength();
		friendsCount += cFriendsCount;
	}

	if (friendsCount){
		titleSmall = '(' + friendsCount + ' Friends)';
	}

	var mainTitleSmall = $('mainTitleSmall');
	if (mainTitleSmall) {
		mainTitleSmall.set('html', titleSmall);
	}
}

function updateTitle(stepNum){
	var show = false;
	var titleBig = '';
	
	switch (stepNum){
		case 1:
			show = true;
			titleBig = 'Select friends ';
			break;
		case 2:
			show = true;
			titleBig = 'Add More Dates ';
			break;
	}

	
	
	var mainTitle = $('mainTitle');
	if (mainTitle){
		if (show) {
			mainTitle.removeClass('hidden');

			var mainTitleBig = $('mainTitleBig');
			if (mainTitleBig) {
				mainTitleBig.set('html', titleBig);
			}

			updateSmallTitle();
		} else {
			mainTitle.addClass('hidden');
		}
	}
	
}

function updateUserCals(){
	var userCalId = generateUserCalId('', '', gUserLogin, 'bc');
	if (userCalId){
		replaceInCalUrl("calLink_mobile", "USERCAL", userCalId, false);
	}
}

function openStep(num){
	if (num in gStepsEls){
		if (gCurrStep in gStepsEls){
			gStepsEls[gCurrStep].addClass('hidden');
		}
		
		gCurrStep = num;
		gStepsEls[gCurrStep].removeClass('hidden');

		updateTitle(num);
		
		var nextBtn = $('nextBtn');
		switch (num){
			case 3:
				nextBtn.addClass('hidden');
				break;
			default:
				if (gGotFbFriends) {
					nextBtn.removeClass('hidden');
				}
		}
		
	}
}

window.addEvent('friendsBirthday', function(){
	gStepsEls = $$('.step');
	openStep(0);

	var proLoginBtn = $('proLoginBtn');
	if (proLoginBtn){
		proLoginBtn.addEvent('click', fbConnect);
	}

	//Back btn event
	var backBtn = $('backBtn');
	if (backBtn){
		backBtn.addEvent('click', function(e){
			e.stop();
			if (gCurrStep == 0){
				window.location = '/';
			} else {
				goBack();
			}
		});
	}

	//Next btn Event
	var nextBtns = $$('.nextBtn');
	nextBtns.each(function(nextBtn){
		nextBtn.addEvent('click', function(e){
			e.stop();
			if (gCurrStep == 2){
				saveFriends();
			} else {
				goNext();
			}
		});
	});
});


window.addEvent('endGetFbFriends', function(){
	var nextBtn = $('nextBtn');
	nextBtn.removeClass('hidden');
});


//Update friends count
window.addEvent('customFriendsBirthdayChange', function(){
	updateSmallTitle();
});

window.addEvent('FBFriendsSelectorChange', function(){
	updateSmallTitle();
});


</script>