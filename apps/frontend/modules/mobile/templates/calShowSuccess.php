
<?php 
	slot('title', sprintf('%s Calendars: %s', $category->getName(), $cal->getName()));
	
	$eventsCount = $events->count();
?>


<?php slot(
  'keywords',
  sprintf('%s,%s', $cal->getKeywords(), $category->getCategoryPathAsKeywords()))
?>

<?php if (! UserUtils::getUserTZ()):?>
	<script type="text/javascript" src="/js/getUserTZ.js"></script>
<?php endif;?>

<!-- header -->
<?php
	if (!isset($backRow)) $backRow = null;
	include_partial('mobile/mainHeader',array('backRow' => $backRow, 'selectedTab' => 0)) 
?>


<h1 id="mainTitle"><?php echo $cal->getNameFixed($category);?></h1>

<section class="p5">
<?php if ($cal->getLocation()) : ?> 
    <h3>Location: <?php echo $cal->getLocation(); ?> </h3>
<?php endif?>

<?php if ($cal->getDescription()) : ?> 
    <p><?php echo $cal->getDescription(); ?> </p>
<?php endif?>
</section>

<?php
	$h4 = 'Add to my Calendar';
	$iconClass = 'calIconGlobal';
	$infoIconClass = 'itemArrowDown';
	$calLink = 'calLink_mobile';
	$addClass = 'itemRowBg1';
	$href = $cal->getIcalUrl(Cal::TYPE_MOBILE);
	$onClick = "return updateUserCals('" . Cal::TYPE_MOBILE ."');";
	
	if (Utils::clientIsAndroid()) {
		$addClass = 'itemRowBg1';
		$href = $cal->getIcalUrl(Cal::TYPE_GOOGLE, null, cal::TYPE_MOBILE);
	}
	
	include_partial('mobile/itemRow', array('calDown'=>true, 'calLink' => $calLink, 'href' => $href, 'h4' => $h4, 'iconClass' => $iconClass, 'infoIconClass' => $infoIconClass, 'addClass' => $addClass, 'onClick' => $onClick, 'target' => '_blank'));
?>

<div id="reminderWrapper">
	<input id="addReminder" name="addReminder" type="checkbox" checked="checked"/>
	<label id="addReminderLabel" for="addReminder"><?php echo __('Reminder');?></label>
	<select id="reminder" name="reminder">
		<?php foreach (CalTable::getReminderValues() as $val):?>
			<option value="<?php echo $val?>"><?php echo $val . ' ' . (($val > 1) ? __('Hours') : __('Hour'))?></option>
		<?php endforeach;?>
	</select>
</div>


<!-- include events -->
<?php if ($eventsCount) : ?>
	<?php include_partial('mobile/events',array('events' => $events, 'cal' => $cal, 'isMasterOf' => $isMasterOf)) ?>
<?php else : ?>
	<h2>The league has Ended</h2>
	<h3 class="tGray">Events will be automatically added to your calendar when published</h3>
<?php endif ?>

<?php if ($eventsCount && $eventsCount > 10) : ?>
	<?php include_partial('mobile/itemRow', array('calDown'=>true, 'calLink' => $calLink, 'href' => $href, 'h4' => $h4, 'iconClass' => $iconClass, 'infoIconClass' => $infoIconClass, 'addClass' => $addClass, 'onClick' => $onClick, 'target' => '_blank'));?>
<?php endif;?>

<div id="androidPopupWrapper" class="hidden">
	<div id="androidPopupBG"></div>
	<div id="androidPopup">
		<div class="mobilePopupT"></div>
		<div class="mobilePopupC">
			<div class="mobilePopupCPadding">
				<a target="_blank" href="/help/HowToAddSportycalToAndroid.pdf">For Android, please use the following steps</a>
				<a id="androidPopupClose" href="#" onclick="return hideAndroidPopUp()">close</a>
			</div>
		</div>
		<div class="mobilePopupB"></div>
	</div>
</div>

<!-- Place this tag in your head or just before your close body tag -->
<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
<?php sfContext::getInstance()->getResponse()->addJavascript('caldown.js'); ?>


<?php
  if ($cal->getId()) $strLabel = 'cal'.$cal->getId();
  else $strLabel = 'ctg'.$category->getId();    
?>

<script type="text/javascript" src="/js/caldown.js"></script>
<script type="text/javascript" src="/js/basics.js"></script>
<script type="text/javascript">
<!--

//Global obj eventId To event desc
var gHtmlPreivews = <?php echo ($mobilePopupItems) ? $sf_data->getRaw('htmlPreviews') : '{}';?>;
var gMobilePopupItems = <?php echo ($mobilePopupItems) ? $sf_data->getRaw('mobilePopupItems') : '{}';?>;

function handleAndroid() {
	
	var temp = updateUserCals('<?php echo Cal::TYPE_MOBILE?>', false);
	
	//alert("Android");
	var androidPopupWrapper = document.getElementById('androidPopupWrapper');
	if (androidPopupWrapper){
		androidPopupWrapper.removeAttribute('class');
	}
	
	return false;
	// TODO: show a popup that says: For Android, please use the following steps => this link to help\HowToAddSportycalToAndroid.pdf 
}

function hideAndroidPopUp(){
	var androidPopupWrapper = document.getElementById('androidPopupWrapper');
	if (androidPopupWrapper){
		androidPopupWrapper.setAttribute('class', 'hidden');
	}

	return false;
}

function updateUserCals(calType, returnVal1) {
	
	var returnVal = true;
	if (typeof returnVal1 != 'undefined'){
		returnVal = returnVal1;
	}
	//alert("ss");
	var userCalId = generateUserCalId('<?php echo $cal->getId(); ?>', '<?php echo $cal->getCategoryId(); ?>', '', calType);

    if (isNumeric(userCalId)) {
    	replaceInCalUrl("calLink_" + calType, "USERCAL", userCalId, false);
    	
        /*
        var links = document.getElementsByClassName("calLink_" + calType);
		
        for (var i = 0; i < links.length; i++){
			var link = links[i];
			
			// not sure why, but saw a case that link was not there...
	        if (link) {
		        // anyIcal is special, its in a span
		        if (calType == '<?php echo Cal::TYPE_ANY?>') {
		            link.innerHTML = link.innerHTML.replace("USERCAL", userCalId);
		           
		        } else {
		            link.href = link.href.replace("USERCAL", userCalId);
		        }
	        }

        }*/
    }
    reportIntel('Calendar export', calType, '<?php echo $strLabel?>', <?php echo $eventsCount ?>, '<?php echo $cal->getCategoryId(); ?>', '<?php echo $cal->getId(); ?>');
    
    if (returnVal){
    	 return true;
    }
   
}



//window.addEvent('domready', function(){
	//showPopup();
	//var openPopup = $('openPopup');
	//openPopup.addEvent('click', showPopup);
	
//});
-->
</script>	

