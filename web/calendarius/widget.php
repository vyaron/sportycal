<?php
require_once 'local.php';

$ref = null;
if (isset($_GET['ref'])){
	$ref = $_GET['ref'];
}

// Yaron:

$html = file_get_contents(WIDGET_URL . 'getHTML.php?ref=' . urlencode($ref));
$html = json_encode($html);
$lang = file_get_contents('lang/en-US.ini');

?>

var gpiWCss ='';
gpiWCss = '<sty'+'le type="text/css">';
gpiWCss += '@import "<?php echo WIDGET_URL ?>css/cleanslate.min.css";';
gpiWCss += '@import "<?php echo WIDGET_URL ?>css/calendarius.css";';
gpiWCss += '</sty'+'le>';
document.write(gpiWCss);

document.write('<scr'+'ipt type="text/JavaScript" src="<?php echo WIDGET_URL ?>js/mootools-core-1.3c.js"></scr'+'ipt>');
document.write('<scr'+'ipt type="text/JavaScript" src="<?php echo WIDGET_URL ?>js/mootools-more.js"></scr'+'ipt>');

var ROOT 	= '<?php echo WIDGET_URL ?>';
var SC_REF 	= '<?php echo urlencode($ref) ?>';

var gHTML = <?php echo str_replace("\n", "", $html);?>;

var gLang = <?php echo $lang;?>;

document.write('<scr'+'ipt type="text/JavaScript" src="<?php echo WIDGET_URL ?>js/widget.js"></scr'+'ipt>');

document.getElementById('scContainer').innerHTML = <?php echo $html?>;


