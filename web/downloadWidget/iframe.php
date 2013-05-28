<?php 
//define('DOWNLOAD_WIDGET_URL', 'http://sportycal.local/downloadWidget/');
//define('SPORTYCAL_ROOT', '../../');

define('DOWNLOAD_WIDGET_URL', 'http://www.sportycal.com/downloadWidget/');
define('SPORTYCAL_ROOT', '/var/sportycal/');

$jsSrc = DOWNLOAD_WIDGET_URL . 'main.php?';

$elId = 'scDownloadWidget_' . rand(100000, 9999999);
if (isset($_GET['elId'])) $elId = $_GET['elId'];
$jsSrc .= 'elId=' . $elId;

if (isset($_GET['calId'])) $jsSrc .= '&calId=' . $_GET['calId'];
if (isset($_GET['ctgId'])) $jsSrc .= '&ctgId=' . $_GET['ctgId'];
if (isset($_GET['ref'])) $jsSrc .= '&ref=' . $_GET['ref'];
if (isset($_GET['label'])) $jsSrc .= '&label=' . rawurlencode($_GET['label']);
if (isset($_GET['language'])) $jsSrc .= '&language=' . $_GET['language'];
if (isset($_GET['style'])) $jsSrc .= '&style=' . $_GET['style'];

$color = 'default';
if (isset($_GET['color'])) $color = $_GET['color'];

$colors = array('default', 'lightblue', 'green' , 'magenta', 'orange', 'purple', 'red', 'yellow');

$colorClass = null;
if (in_array($color, $colors)) $colorClass = "scdwBg_$color";
else $colorClass = "scdwBg_default";
//echo $jsSrc;
//die();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>sportYcal.com</title>
<style type="text/css">*{padding:0; margin:0;}</style>
</head>

<!-- Yaron: added this: overflow:hidden as 888 wanted to not show the show-events link (here: http://stage-en.888poker.com/poker-client/migration_ccp_lm5.htm) they are limiting the size an and in IE they got ugly-scroll  -->
<body style="background-color: transparent;overflow:hidden">
  <div id="<?php echo $elId;?>" class="scDownloadWidgetWrapper <?php echo ($colorClass) ? $colorClass : ''?>"></div>
  <script type="text/javascript" src="<?php echo $jsSrc;?>"></script>
</body>
</html>