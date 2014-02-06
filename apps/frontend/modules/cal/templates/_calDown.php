<?php

	sfContext::getInstance()->getResponse()->addStylesheet('calDown');
	
	$calDownLinks = array(
		array(
			'type' => Cal::TYPE_GOOGLE,
			'title' => 'Download to Google Calendar',
			'label' => __('Google Calendar')
		),
		array(
			'type' => Cal::TYPE_OUTLOOK,
			'title' => 'Download to Outlook Calendar',
			'label' => __('Outlook')
		),
		array(
			'type' => Cal::TYPE_MOBILE,
			'title' => 'Download to Mobile Calendar',
			'label' => __('Mobile')
		),
		array(
			'type' => Cal::TYPE_ANY,
			'title' => 'Get iCal Link',
			'label' => __('Link')
		)
	);

	if (empty($hideTitle)) $hideTitle = false;
	else if (!$hideTitle)  $hideTitle = false;
?>

<?php if (!$hideTitle):?>
	<?php echo image_tag('layout/help.png', 'alt="Help" title="Help" class="imgHelp" onclick="showHelp()"')?>
	<h3 id="calDownTitle"><?php echo __('Download to your own Calendar:');?></h3>
	<div id="reminderWrapper">
		<input id="addReminder" name="addReminder" type="checkbox" checked="checked"/>
		<label id="addReminderLabel" for="addReminder"><?php echo __('Reminder');?></label>
		<select id="reminder" name="reminder">
			<?php foreach (CalTable::getReminderValues() as $val):?>
				<option value="<?php echo $val * 60?>"><?php echo $val . ' ' . (($val > 1) ? __('Hours') : __('Hour'))?></option>
			<?php endforeach;?>
		</select>
	</div>
	<div class="cb"></div>
<?php endif;?>

<div id="calDownLinkWrapper">
	<?php foreach ($calDownLinks as $calDownLink):?>
		<?php if(Cal::TYPE_ANY == $calDownLink['type']):?>
			<a id="calLink_link" class="calDownLink" href="javascript:showCalUrl()" title="<?php echo $calDownLink['title']?>" onClick="return updateUserCals('<?php echo $calDownLink['type']?>');">
				<div class="calDownLinkIcon"></div>
				<h4 class="calDownLinkLabel"><?php echo $calDownLink['label']?></h4>
			</a>
		<?php elseif(Cal::TYPE_MOBILE == $calDownLink['type']):?>
			<a id="calLink_mobile" class="calDownLink" href="<?php echo $cal->getIcalUrl($calDownLink['type'])?>" title="<?php echo $calDownLink['title']?>" target="_blank" onClick="return <?php echo (Utils::clientIsMobile() || Utils::clientIsIpad()) ? 'updateUserCals(\'' . $calDownLink['type'] . '\')' : 'showCalMobile()' ?>;">
				<div class="calDownLinkIcon"></div>
				<h4 class="calDownLinkLabel"><?php echo $calDownLink['label']?></h4>
			</a>
		<?php else:?>
			<a id="calLink_<?php echo $calDownLink['type']?>" class="calDownLink" href="<?php echo $cal->getIcalUrl($calDownLink['type'])?>" title="<?php echo $calDownLink['title']?>" target="_blank" onClick="return updateUserCals('<?php echo $calDownLink['type']?>');">
				<div class="calDownLinkIcon"></div>
				<h4 class="calDownLinkLabel"><?php echo $calDownLink['label']?></h4>
			</a>
		<?php endif;?>
	<?php endforeach;?>
	<div class="cb"></div>
</div>

<div id="calDownPopup" class="hidden">
	<div id="calDownPopupBG"></div>
	<table id="calDownPopupTable">
		<tr>
			<td class="calDownPopupTL"></td>
			<td class="calDownPopupTC"></td>
			<td class="calDownPopupTR"></td>
		</tr>
		<tr>
			<td class="calDownPopupCL"></td>
			<td class="calDownPopupCC">
				<div id="calDownPopupContent">
					<!-- Help -->
					<div id="divHelp" class="calDownPopupContent">
						<div id="innerHelp" class="helpInner">
							<p class="strong"><?php echo __('Google Calendar');?></p>
							<p><?php echo __('Just Click to add to your Google Calendar');?></p>
							<br />
							<p class="strong"><?php echo __('Outlook');?></p>
							<p><?php echo __('Just Click to add to your Outlook Calendar 2007 and up');?></p>
							<br />
							<p class="strong"><?php echo __('Mobile');?></p>
							<p> 
								<span class="tGray strong"><?php echo __('iPhone/iPad calendar');?></span> - <?php echo __('use your iPhone/iPad to browse and download the calendar');?><br/>
								<span class="tGray strong"><?php echo __('Android');?></span> - <?php echo __('click the google calendar button to add to your google account and synch the calendar from your device, need instructions?');?> <a target="_blank" href="/help/HowToAddSportycalToAndroid.pdf"><?php echo __('see here');?></a>.
							</p>
							<br />
							<p class="strong"><?php echo __('Hardcopy');?></p>
							<p><?php echo __('Download a PDF, print and use as you wish');?></p>
							<br />
							<p class="tGray">
								(<?php echo __('Need help adding schedules to');?> <b><?php echo __('Yahoo');?></b> <?php echo __('calendar')?>? <a
									class="tGray"
									href="<?php echo url_for('help/HowToAddSportycalToYahoo.pdf') ?>"
									target="_blank"> <?php echo __('Click Here');?>) </a>
							</p>
							<br />
						</div>
					</div>
				
					<!-- Link -->
					<div id="divCalUrl" class="calDownPopupContent">
						<div id="innerHelp" class="helpInner">
							<center>
								<p class="strong"><?php echo __('Subscribe with any calendar that supports Ical');?></p>
							</center>
							<br />
							<p><?php echo __('Yahoo, Lotus notes, and many others.');?></p>
							<br />
							<p><?php echo __('To add this calendar, copy & use the following URL:');?></p>
							<br />
					
							<p>
								&nbsp;&nbsp;&nbsp; <i><span id="calLink_any" class="url"><?php echo $cal->getIcalUrl(Cal::TYPE_ANY)?>
								</span> </i>
							</p>
							<br />
							<p class="tGray"> (<?php echo __('Need help adding schedules to');?> <b><?php echo __('Yahoo');?></b> <?php echo __('calendar');?>? <a class="tGray" href="<?php echo url_for('help/HowToAddSportycalToYahoo.pdf') ?>" target="_blank"> <?php  echo __('Click Here');?>) </a></p>
							<br />
					
						</div>
					</div>

					<!-- Mobile -->
					<div id="divMobile" class="calDownPopupContent">
						<?php if (isset($divMobileHeader)) : ?>
						<p class="b tac" id="divMobileHeader"><?php echo $divMobileHeader ?></p>
						<?php endif ?>
						<p><span class="b"><?php echo __('iPhone/iPad calendar');?></span> - <?php echo __('use your iPhone/iPad to browse and download the calendar');?></p>
						<p><span class="b"><?php echo __('Android');?></span> - <?php  echo __('click the google calendar button to add to your google account and synch the calendar from your device, need instructions?');?> <a target="_blank" href="/help/HowToAddSportycalToAndroid.pdf"><?php echo __('see here');?></a>.</p>
					</div>
				
				</div>
				<a id="calDownPopupClose" onClick="return hideCalPopup()"><?php echo __('[x] Close');?></a>
			</td>
			<td class="calDownPopupCR"></td>
		</tr>
		<tr>
			<td class="calDownPopupBL"></td>
			<td class="calDownPopupBC"></td>
			<td class="calDownPopupBR"></td>
		</tr>
	</table>
</div>
                
<?php sfContext::getInstance()->getResponse()->addJavascript('caldown.js'); ?>
<script type="text/javascript">
<!--
<?php
		$strCalId 				= "";
		$strCtgId 				= "";
		$strBirthdayCalUserId 	= "";
		$eventsCount = $cal->getNumEvents();
		
		if ($cal && $cal->isBirthdayCal()) {
			// BirthdayCal is working in AJAX so need to be calculated later in JS
		  	$birthdayCalUserId = $cal->getByUserId();
			$strLabel = 'birthdayCal'.$birthdayCalUserId;
			$eventsCount = 0; 
		} elseif ($cal && $cal->getId()) {
		  	$strCalId = $cal->getId();
			$strLabel = 'cal'.$strCalId;
		} elseif ($category && $category->getId()) {
			$strCtgId = $category->getId();
		  	$strLabel = 'ctg'.$strCtgId;
		}

		
?>

var gCalDownCalId 			= '<?php echo $strCalId?>';
var gCalDownCtgId 			= '<?php echo $strCtgId?>';
var gCalDownBcId 			= '<?php echo $strBirthdayCalUserId?>';
var gCalDownLabel 			= '<?php echo $strLabel?>';
var gCalDownEventsCount 	= '<?php echo $eventsCount?>';


function updateUserCals(calType) {
	
    //toggleLoading(true);
	
	var elId = "calLink_" + calType;
	var placeHolder = "USERCAL";

	var el = $(elId);
	if (el){
		var haveUserCal = el.getProperty('data-have-user-cal');
		if (!haveUserCal){
			var userCalId = generateUserCalId(gCalDownCalId, gCalDownCtgId, gCalDownBcId, calType);

			if (isNumeric(userCalId)) {
				el.setProperty('data-have-user-cal', '1');
				replaceInCalUrl(elId, placeHolder, userCalId, true);
				reportIntel('Calendar export', calType, gCalDownLabel, gCalDownEventsCount, '<?php echo $cal->getCategoryId(); ?>', '<?php echo $cal->getId(); ?>');
			} else {
				//Case generateUserCalId failed - Do nothing
				return false;
			}
		}
	}
	
    
    //toggleLoading(false);

    if (calType != '<?php echo Cal::TYPE_ANY?>')  openThankYou(calType);
    return true;
}

function openThankYou(calType) {

	var perCalTypeTxt 		= $('perCalTypeTxt');
	var perCalTypeSubTxt 	= $('perCalTypeSubTxt');

	if (calType == 'google') {
		perCalTypeTxt.set('html', '<?php echo __('Sent to your Google Calendar');?>');
		perCalTypeSubTxt.set('html', '<?php echo __('Google will now index and add this calendar (might take few minutes)');?>');

	} else if (calType == 'outlook') {
		perCalTypeTxt.set('html', '<?php echo __('Sent to your Outlook Calendar');?>');
		perCalTypeSubTxt.set('html', '');
	} else if (calType == 'mobile') {
		perCalTypeTxt.set('html', '<?php echo __('Sent to your Mobile');?>');
		perCalTypeSubTxt.set('html', '');
	} else if (calType == 'hardcopy') {
		perCalTypeTxt.set('html', '<?php echo __('Opened a new window with your PDF');?>');
		perCalTypeSubTxt.set('html', '');
	}
		
	showPopup();	
}

-->
</script>	

