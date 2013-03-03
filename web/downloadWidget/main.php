<?php 
//define('DOWNLOAD_WIDGET_URL', 'http://sportycal.local/downloadWidget/');
//define('SPORTYCAL_ROOT', '../../');

define('DOWNLOAD_WIDGET_URL', 'http://www.sportycal.com/downloadWidget/');
define('SPORTYCAL_ROOT', '/var/sportycal/');

require_once SPORTYCAL_ROOT . 'lib/model/Utils.php';

$elId = null;
if (isset($_GET['elId'])){
	$elId = $_GET['elId'];
}


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

if (!$calId && !$ctgId || ($calId && $ctgId)){
	die('Incorrect parameters');
}

$language = 'en-US';
if (isset($_GET['language'])){
	$language = $_GET['language'];
}

$style = null;
if (isset($_GET['style'])){
	$style = $_GET['style'];
	$cssFile = 'css/' . $style . '.css';
	
	if (file_exists($cssFile)) $style = $cssFile;
	else $style = null;
}

$htmlTemplateUrl = DOWNLOAD_WIDGET_URL .'getHTML.php?' . (($calId) ? 'calId=' .$calId : 'ctgId=' .$ctgId);

if ($ref){
	$htmlTemplateUrl .= '&ref=' .$ref;
}

if ($language){
	$htmlTemplateUrl .= '&language=' .$language;
}

$htmlTemplate = file_get_contents($htmlTemplateUrl);
$htmlTemplate = json_encode($htmlTemplate);

?>

var spdwClientIsMobile = <?php echo (Utils::clientIsMobile()) ? 'true' : 'false'?>;

var spdwCss ='';
spdwCss = '<sty'+'le type="text/css" id="gpiwStyle">';
spdwCss += '@import "<?php echo DOWNLOAD_WIDGET_URL ?>css/main.css";';
<?php if ($style):?>
spdwCss += '@import "<?php echo DOWNLOAD_WIDGET_URL . $style ?>";';
<?php endif;?>
spdwCss += '</sty'+'le>';
document.write(spdwCss);

//document.write('<scr'+'ipt type="text/JavaScript" src="<?php echo DOWNLOAD_WIDGET_URL ?>js/mootools-core-1.4.0.shrink.js"></scr'+'ipt>');

if (typeof MooTools == 'undefined'){
	document.write('<scr'+'ipt type="text/JavaScript" src="<?php echo DOWNLOAD_WIDGET_URL ?>js/mootools-core-1.4.3.js"></scr'+'ipt>');
	//document.write('<scr'+'ipt type="text/JavaScript" src="https://ajax.googleapis.com/ajax/libs/mootools/1.4.1/mootools-yui-compressed.js"></scr'+'ipt>');
}

document.write('<scr'+'ipt type="text/JavaScript" src="<?php echo DOWNLOAD_WIDGET_URL ?>js/main.js"></scr'+'ipt>');

function spdwAddLoadEvent(func) {
	if (window.addEventListener){
		window.addEventListener('DOMContentLoaded', func, false)
	} else if (window.attachEvent){
		window.attachEvent('onload', func);
	}
}



spdwAddLoadEvent(function(){
	var widgetElWrapper = document.getElementById('<?php echo $elId;?>');
	if (widgetElWrapper){
		new scDownloadWidget({
			'el' : widgetElWrapper,
			'SCDW_HTML_TEMPALATE' : <?php echo str_replace("\n", "", $htmlTemplate);?>,
			'SCDW_CAL_ID' : '<?php echo ($calId) ? $calId : ''?>',
			'SCDW_CTG_ID' : '<?php echo ($ctgId) ? $ctgId : ''?>',
			'SCDW_REF' : '<?php echo ($ref) ? $ref : ''?>',
			'SCDW_LABEL' : '<?php echo ($label) ? $label : ''?>',
			'USER_IP' : '<?php echo (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0');?>'
		});
	}
});






