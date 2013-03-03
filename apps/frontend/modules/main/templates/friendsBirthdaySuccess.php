<?php 

define('NUM_OF_STEPS', 4);
define('FB_FRIEND_ID', 'birthdayCal');

?>

<?php sfContext::getInstance()->getResponse()->addStylesheet('friendsBirthday.css');?>
<?php sfContext::getInstance()->getResponse()->addStylesheet('addCustomFriendsBirthday.css');?>




<div id="promotionWrapper">
	<div id="stepCirclesWrapper" style="width:<?php echo NUM_OF_STEPS * 21 ?>px;">
		<?php for($i=0; $i < NUM_OF_STEPS; $i++):?>
		<div class="stepCircle"></div>
		<?php endfor;?>
		<div class="cb"></div>
	</div>
	<div id="proStepsWrapper">
		<div id="proSteps" style="width:<?php echo (NUM_OF_STEPS * 800)?>px;">
			<div class="proStep">
				<div id="proMsg">
					<div id="proLoginBtn"></div>
				</div>
			</div>
			
			<div class="proStep">
				<table class="rbbtTable" style="width:800px; height: 600px;">
					<tr>
						<td class="rbbtTL"></td>
						<td class="rbbtTC">
							<div class="stepBackBtn" title="Back"></div>
							<div id="selectYourFriendsTitle"></div>
						</td>
						<td class="rbbtTR"></td>
					</tr>
					<tr>
						<td class="rbbtCL"></td>
						<td class="rbbtCC">
							<?php include_partial('main/fbFriends',array('id' => FB_FRIEND_ID, 'height' => 400)) ?>
							<div id="nextBtn"></div>
						</td>
						<td class="rbbtCR"></td>
					</tr>
					<tr>
						<td class="rbbtBL"></td>
						<td class="rbbtBC"></td>
						<td class="rbbtBR"></td>
					</tr>
				</table>
			</div>

			<div class="proStep">
				<table class="rbbtTable" style="width:500px; height: 400px;">
					<tr>
						<td class="rbbtTL"></td>
						<td class="rbbtTC">
							<div class="stepBackBtn" title="Back"></div>
							<div id="customFriendsTitle"></div>
						</td>
						<td class="rbbtTR"></td>
					</tr>
					<tr>
						<td class="rbbtCL"></td>
						<td class="rbbtCC">
							<p id="noFbFriends" class="hidden">You do not have Facebook friends who share their birthday</p>
							<?php include_partial('main/addCustomFriendsBirthday') ?>
							<div id="downloadPageBtn"></div>
						</td>
						<td class="rbbtCR"></td>
					</tr>
					<tr>
						<td class="rbbtBL"></td>
						<td class="rbbtBC"></td>
						<td class="rbbtBR"></td>
					</tr>
				</table>
			</div>
			
			<div class="proStep">
				<table class="rbbtTable" style="width:720px; height: 260px; position:relative;">
					<tr>
						<td class="rbbtTL"></td>
						<td class="rbbtTC">
							<div class="stepBackBtn" title="Back"></div>
							<div id="addToYourCalendarTitle">
								<?php echo image_tag('layout/help.png', 'alt="Help" title="Help" id="calImgHelp" class="imgHelp" onclick="showHelp()"')?>
								<div class="cb"></div>
							</div>
							
						</td>
						<td class="rbbtTR"></td>
					</tr>
					<tr>
						<td class="rbbtCL"></td>
						<td class="rbbtCC">
							<?php include_partial('cal/calDown',array('cal' => $birthdayCal, 'category'=>$birthdayCtg, 'hideTitle' => true, 'divMobileHeader' => 'Your friends selection was succesfuly saved')) ?>
							<?php include_partial('cal/downThanks',array('category' => $birthdayCtg, 'cal' => $birthdayCal)) ?>
							
						</td>
						<td class="rbbtCR"></td>
					</tr>
					<tr>
						<td class="rbbtBL"></td>
						<td class="rbbtBC"></td>
						<td class="rbbtBR"></td>
					</tr>
				</table>
			</div>

		</div>
	</div>
</div>


<?php sfContext::getInstance()->getResponse()->addJavascript('AddCustomFriendBirthday.class.js'); ?>
<?php sfContext::getInstance()->getResponse()->addJavascript('friendsBirthday.js'); ?>
<script type="text/javascript">

var PRO_MSG_WIDTH = 800;

var gProSlideFx = null;

var gCircleStepsEls = [];


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

window.addEvent('friendsBirthday', function(){
	gProSlideFx = new Fx.Morph($('proSteps'));

	gStepsEls = $$('.proStep');
	gCircleStepsEls = $$('.stepCircle');
	
	var proLoginBtn = $('proLoginBtn');
	if (proLoginBtn){
		proLoginBtn.addEvent('click', fbConnect);
	}

	openStep(0);

	var nextBtn = $('nextBtn');
	if (nextBtn){
		nextBtn.addEvent('click', function(e){
			e.stop();
			openStep(2);
		});
	}

	var stepBackBtns = $$('.stepBackBtn');
	if (stepBackBtns){
		var _this = this;
		stepBackBtns.each(function(stepBackBtn){
			stepBackBtn.addEvent('click', _this.goBack.bind(_this));
		});
	}
});
</script>

