<?php
require_once 'lib/Lang.class.php';

//define('ROOT_URL', 'http://sportycal.local/');
define('ROOT_URL', 'http://sportycal.com/');

define('DOWNLOAD_WIDGET_URL', ROOT_URL . '/downloadWidget/');

$language = 'en-US';
if (isset($_GET['language'])){
	$language = $_GET['language'];
}

$dirRTL = null;
switch ($language){
	case 'he_IL':
		$dirRTL = true;
		break;
	default:
		$dirRTL = false;
}

$showSmallTxt = false;
if ($language != 'en_US' || $language != 'he_IL'){
	$showSmallTxt = true;
}

$lang = new Lang($language);
$calId = null;
if (isset($_GET['calId'])){
	$calId = $_GET['calId'];
}

$ctgId = null;
if (isset($_GET['ctgId'])){
	$ctgId = $_GET['ctgId'];
}

$ref = null;
if (isset($_GET['ref'])){
	$ref = $_GET['ref'];
}

$label = null;
if (isset($_GET['label'])){
	$label = $_GET['label'];
}

$icons = array(
	array('calType' => 'google', 'class' => 'scdbIcon_google', 'label' => $lang->trans('Google'), 'href' =>'http://www.google.com/calendar/render?cid=http%3A%2F%2Fwww.sportYcal.com%2Fcal%2Fget%2F' . (($calId) ? 'id%2F' . $calId : 'ctgId%2F' . $ctgId) . '%2Fhash%2FUSERCAL' . (($ref) ? '%2Fct%2Fgoogle%2Fref%2F' . $ref  : '' ) . ($label ? '%2Flabel%2F' . $label : '')),
	array('calType' => 'outlook', 'class' => 'scdbIcon_outlook', 'label' => $lang->trans('Outlook'), 'href' => 'webcal://www.sportYcal.com/cal/get/' . (($calId) ? 'id/' . $calId : 'ctgId/' . $ctgId) . '/hash/USERCAL/ct/outlook/' . (($ref) ? 'ref/' . $ref  . '/' : '' ) . ($label ? '/label/' . $label . '/' : '') . 'sportycal.ics'),
	array('calType' => 'mobile', 'class' => 'scdbIcon_mobile', 'label' => $lang->trans('Mobile'), 'href' => 'webcal://www.sportYcal.com/cal/get/' . (($calId) ? 'id/' . $calId : 'ctgId/' . $ctgId) . '/hash/USERCAL/ct/mobile/' . (($ref) ? 'ref/' . $ref . '/' : '' ) . ($label ? '/label/' . $label . '/' : '') . 'sportycal.ics'),
	array('calType' => 'any', 'class' => 'scdbIcon_link', 'label' => $lang->trans('Link'), 'href' => 'webcal://www.sportYcal.com/cal/get/' . (($calId) ? 'id/' . $calId : 'ctgId/' . $ctgId) . '/hash/USERCAL/ct/any/' . (($ref) ? 'ref/' . $ref . '/' : '' ) . ($label ? '/label/' . $label . '/' : '') . 'sportycal.ics')
);

$calUrl = ROOT_URL . 'cal/find?addEvents=1&';
if ($calId) $calUrl .= 'calId=' . (int)$calId;
if ($ctgId) $calUrl .= 'ctgId=' . (int)$ctgId;
if ($ref) $calUrl .= '&ref=' . $ref;
if ($label) $calUrl .= '&label=' . $label;

//echo $calUrl; die();
$cal = file_get_contents($calUrl);

if ($cal) $cal = json_decode($cal);
else $cal = new stdClass();

if ($cal->Events) $events = $cal->Events;
else $events = array();
/*
Utils::pp($_SERVER);

Utils::pp(Utils::clientIsMobile());

echo ($icon['calType'] === 'mobile' && Utils::clientIsMobile()) ? 'isMobile="isMobile"' : '';
*/
?>

<?php echo ($dirRTL) ? '<div class="scdbRTL">' : '';?>

<div class="scDownloadWidgetPadding scLang_<? echo $language;?>">
	<div class="scDownloadWidget">
		<div class="scdbTopWrapper show_scdbTitleWrapper">
			<div class="scdbTitleWrapper">
				<div class="scdbTitle"><?php echo $lang->trans('Add to my Calendar');?></div>
				<input class="scdbLink" type="text" value="#"/>
				<a class="scdbHelp" title="<?php echo $lang->trans('Help');?>" href="#"></a>
				<div class="cb"></div>
			</div>
		</div>
		<div class="scdbBottomWrapper">
			<!-- Icon buttons -->
			<?php foreach ($icons as $icon):?>
			<a class="scdbIconWrapper" target="_blank" href="<?php echo $icon['href']?>" calType="<?php echo $icon['calType']?>">
				<div class="scdbIcon <?php echo $icon['class']?>"></div>
				<div class="scdbIconLabel"><?php echo $icon['label']?></div>
			</a>
			<?php endforeach;?>
			
			<div class="cb"></div>
		</div>
	</div>
	
	<div class="scdwHelpContentWrapper">
		<div class="scdwHelpTop">
			<div class="scdwHelpLabel"><?php echo $lang->trans('Help')?>:</div>
			<a class="scdwHelpClose"><?php echo $lang->trans('Close')?></a>
			<div class="cb"></div>
		</div>
		<div class="scdwHelpContent">
			<p><span class='b'><?php echo $lang->trans('Google Calendar')?>: </span><?php echo $lang->trans('Just Click to add to your Google Calendar')?></p>
			<p><span class='b'><?php echo $lang->trans('Outlook')?>: </span><?php echo $lang->trans('Just Click to add to your Outlook Calendar 2007 and up')?></p>
			<p><span class='b'><?php echo $lang->trans('Mobile')?>: </span><?php echo $lang->trans('Browse from your mobile and Click to add to your mobile phone or netbook')?></p>
			<p><span class='b'><?php echo $lang->trans('Link')?>: </span><?php echo $lang->trans('Yahoo, Lotus notes, and many others. To add this calendar, copy & use the Link')?> </p>
			<br>
			<a class='c gray' target="_blank" href='<?php echo ROOT_URL ?>' title='<?php echo $lang->trans('Download Sports Schedules to your Calendar')?>'><?php echo $lang->trans('Powered by sportYcal.com')?> </a>
		</div>
	</div>
	
	<p class="scdwCalName"><?php echo ($cal->name) ? $cal->name : '';?></p>
	<div class="scdwEventsWrapper hideEvents">
		<a class="scdwShowEventsBtn" href="#"><?php echo $lang->trans('Show Events')?></a>
		<div class="scdwEventsList">
			<div class="scdwEventsListWrapper">
			<?php foreach ($events as $event):?>
				<p class="scdwEvent"><?php echo $event->name;?> <span class="scdwEventDate"><?php echo date('d M Y', strtotime($event->starts_at));?></span></p>
			<?php endforeach;?>
			</div>
			
			<a class="scdwHideEventsBtn" href="#"><?php echo $lang->trans('Hide Events')?></a>
		</div>
	</div>
	
	<div class="scdwPopup hidden">
		<div class="scdwPopupBG"></div>
		<div class="scdwPopupContentWrapper">
			<div class="scdwPopupContent">
				<div class="scdwPopupContentPadding">
					<p><span class="b"><?php echo $lang->trans('iPhone/iPad calendar')?></span> - <?php echo $lang->trans('use your iPhone/iPad to browse and download the calendar')?></p>
					<p><span class="b"><?php echo $lang->trans('Android')?></span> - <?php echo $lang->trans('click the google calendar button to add to your google account and synch the calendar from your device, need instructions?')?> <a target="_blank" href="<?php echo ROOT_URL?>help/HowToAddSportycalToAndroid.pdf"><?php echo $lang->trans('see here')?></a>.</p>
				</div>
			</div>
			<a class="scdwPopupClose" href="#"><?php echo $lang->trans('Close')?></a>
		</div>
	</div>
</div>
<?php echo ($dirRTL) ? '</div>' : '';?>
