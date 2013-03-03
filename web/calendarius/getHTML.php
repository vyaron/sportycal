<?php

require_once 'local.php';
/*
$ref = null;
if (isset($_GET['ref'])){
	$ref = $_GET['ref'];
}
*/
$lang = file_get_contents('lang/en-US.ini');
$lang = json_decode($lang);


?>

<div id="spPopup" class="spHidden">
	<div class="spClose"></div>
	<p class="spPopupTitle">Select a team to download its schedule to your own Calendar:</p>
	<br/>
	<div>
		<select id="spSelectCtg"></select> 
	</div>
	<div class="spIcalLinks">
		<a class="spIcalLink" href="webcal://<?php echo SERVER_URL?>/cal/get/ctgId/CTG_ID/hash/USERCAL" target="blank">
			<div id="spCalLink_any" class="spIcalLinkBox">
				Any <span class="spSmall">(Outlook, Mobile)</span>
	    	</div>
		</a>
    
		<a class="spIcalLink" href="http://www.google.com/calendar/render?cid=<?php echo urlencode(SERVER_URL . '/cal/get/ctgId/'.'CTG_ID'.'/hash/USERCAL')?>" target="blank">
			<div id="spCalLink_google" class="spIcalLinkBox">
				Google Calendar
	        </div>
		</a>	        
	</div>
	<div class="spClear"></div>
</div>

