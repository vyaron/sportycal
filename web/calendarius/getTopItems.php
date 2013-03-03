<?php

require_once 'local.php';

$pId = NULL;
if (isset($_GET['pId'])){
	$pId = $_GET['pId'];
	
}

$destId = NULL;
if (isset($_GET['destId'])){
	$destId = $_GET['destId'];
}

$limit = NULL;
if (isset($_GET['limit'])){
	$limit = $_GET['limit'];
}

$offset = NULL;
if (isset($_GET['offset'])){
	$offset = $_GET['offset'];
}

$typesMap 		= array('a'=>2, 'd'=>3, 'l'=>4, 'e'=>444);
$strRefTypes 	= '';
$refTypes 		= array();
if (isset($_GET['type'])){
	$type 	= $_GET['type'];
	$types 	= explode('|', $type);
	foreach ($types as $type) {
		$refTypes[] = $typesMap[$type];
	} 	
	$strRefTypes = implode(",", $refTypes);
}

$url = SERVER_URL . '/api/topItems?pId=' . urlencode($pId) . '&destId=' . $destId;
if ($limit) 		$url .= '&limit=' . $limit;
if ($offset) 		$url .= '&offset=' . $offset;
if ($strRefTypes) 	$url .= '&refTypes=' . $strRefTypes;

// Yaron: Added a COMMENT and NEW-LINE here, is that fine?
echo "//" . $url . "\n";
//$url = 'https://travplannr.com:8080/api/topItems?ref=sl0796&destId=102&refTypes=2,3,4,444&limit=5';
$data = file_get_contents($url);
?>

var gResData = <?php echo ($data) ? $data : '[]'; ?>;

gSetData();


