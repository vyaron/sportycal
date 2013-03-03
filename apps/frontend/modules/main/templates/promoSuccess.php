<?php sfContext::getInstance()->getResponse()->addStylesheet('promotion');?>


<div id="promotionWrapper">
	<div id="stepCirclesWrapper">
		<div class="stepCircle"></div>
		<div class="stepCircle"></div>
		<div class="stepCircle"></div>
		<div class="cb"></div>
	</div>
	<div id="proStepsWrapper">
		<div id="proSteps">
			<div class="proStep">
				<div id="proMsg">
					<div id="proLoginBtn"></div>
					<a target="_blank" id="proTerms" href="<?php echo url_for('main/promoTerms') ?>">Terms &amp; Conditions</a>
				</div>
			</div>
			
			<div class="proStep">
				<div class="proStepT">
					<div id="shareWidthFriendsTitle"></div>
				</div>
				<div class="proStepC">
					<div class="proStepCPadding">
						<div id="facebookPostElWrapper">
							<textarea id="facebookPostEl"></textarea>
						</div>
						<input id="facebookPostBtn" type="button" value="Post to Facebook"/>
						<div class="cb"></div>
					</div>
				</div>
				<div class="proStepB"></div>
			</div>
			<div class="proStep">
				<div class="proStepT">
					<div id="thankYouTitle"></div>
				</div>
				<div class="proStepC">
					<div class="proStepCPadding">
						<p>Thank you for sharing that with your friends, you just earned 5 points.</p>
						<p>Any action taken by your invited friends will earn you more points!</p>
						
						<br/><br/>
						<p>You may share the following link in any blog or forum you like:</p>
						<p style="color:blue">http://sportYcal.com?iu=<span id="iuInThankyouStep"></span></p>
						<p style="color:gray">(This link identifies you as the inviter)</p>
						<br/>
						<p>Information about your account and winning will be sent via email</p>
						<a href="/" title="sportYcal home page">Back to Home Page</a>
					</div>
				</div>
				<div class="proStepB"></div>
			</div>
			
		</div>
	</div>
	
</div>

<script type="text/javascript">
gRedirectAfterFbLogin = false;

var DEV_MODE = false;

var PRO_MSG_WIDTH = 670;

var gUserLogin = null;

var gCurrStep = null;

var gProSlideFx = null;

var gStepsEls = [];
var gCircleStepsEls = [];


//Facebook Share/Feed
var DEFAULT_COMMENT	= "Never miss a game again! download team's schedules right into your personal calendar!";

function feed(msg){
	FB.api('/me/feed', 'post', {
		'actions'	:	{"name": "View on sportYcal", "link": 'http://sportycal.com?iu=' + gUserLogin},
		'message'		: msg,
		//'name' 			: 'a Trip Plan from GoPlanIt.com',
		//'caption' 		: 'Plan your trip and Go...',
		'picture' 		: 'http://sportycal.com/images/layout/logo.gif',
		//'description' 	: 'Travel Planning at the click of a button',
		'link'			: 'http://sportycal.com?iu=' + gUserLogin
		}, function(response) {
		if (!response || response.error) {
			//cl(response.error);
			openStep(0);
		} else {
			reportIntel("promo", "fb-post", "", 0);
			openStep(2);
		}
	});
}



function fbPost(){
	if (DEV_MODE){
		openStep(2);
		return;
	}
	
	var facebookPostEl = $('facebookPostEl');
	if (facebookPostEl){
		var msg = facebookPostEl.get('value');
		feed(msg);
		/*
		Yaron: this makes double feed on FB
		FB.getLoginStatus(function(response) {
			if (response.session) {
				// logged in and connected user, someone you know
				feed(msg);
			} else {
				//alert('Not conected');
			}
		});*/
	}
}


function getUserId(){
	var userIdReq = new Request({
		url : '/main/fbLogin',
		method : 'get',
		onSuccess : function(res){
			gUserLogin = res;
			$('iuInThankyouStep').set('html', gUserLogin);
			openStep(1);
		}
	});

	userIdReq.send('getUserId=1');
	
	
}

function fbConnect(){
	if (DEV_MODE){
		openStep(1);
		return;
	}
	
	FB.login(function(response) {
	  if (response.session) {
		  getUserId();
	  } else {
		//alert('user cancelled login');
	  }
	}, {perms:'publish_stream,email,user_birthday'});

}

function openStep(num1, how1){
	var num = null;
	if (typeof num1 != 'undefined'){
		num = num1;
	} else {
		return;
	}

	var how = null;
	if (typeof how1 != 'undefined'){
		how = how1;
	}
	
	
	if (gCircleStepsEls[gCurrStep]){
		gCircleStepsEls[gCurrStep].removeClass('stepCircleSel');
	}

	gCurrStep = num;
	gCircleStepsEls[gCurrStep].addClass('stepCircleSel');

	var leftVal = gCurrStep * PRO_MSG_WIDTH * (-1);
	
	if (gProSlideFx){

		if (how == 'set'){
			gProSlideFx.set({
				'left' : leftVal
			});
		} else {
			gProSlideFx.start({
				'left' : leftVal
			});
		}
		
	}
}

window.addEvent('domready', function(){
	gProSlideFx = new Fx.Morph($('proSteps'));

	gStepsEls = $$('.proStep');
	gCircleStepsEls = $$('.stepCircle');
	
	var proLoginBtn = $('proLoginBtn');
	if (proLoginBtn){
		proLoginBtn.addEvent('click', fbConnect);
	}


	openStep(0);
	/*	
	if (gUserLogin){
		openStep(1, 'set');
	}
	*/
	
	var facebookPostEl = $('facebookPostEl');
	if (facebookPostEl){
		facebookPostEl.set('html', DEFAULT_COMMENT);
	}

	var facebookPostBtn = $('facebookPostBtn');
	if (facebookPostBtn){
		facebookPostBtn.addEvent('click', fbPost);
	}
});

</script>

