<?php

require_once 'local.php';

$ref = NULL;
//$ref = '888Sport-0796';
if (isset($_GET['ref'])){
	$ref = $_GET['ref'];
	
}

$ctgAlias = NULL;
if (isset($_GET['ctgAlias'])){
	$ctgAlias = $_GET['ctgAlias'];
}
//$eId = 720;

//$url = SERVER_URL . '/category/getSubCtgs?ref=' . $ref . '&parentCtgId=' . $eId . '&parentCtgAlias=' . urlencode($ctgAlias);
$url = SERVER_URL . '/category/getSubCtgs?ref=' . $ref . '&parentCtgAlias=' . urlencode($ctgAlias);

echo "//" . $url . "\n";
$data = file_get_contents($url);
?>

var gResData = <?php echo ($data) ? $data : '[]'; ?>;



