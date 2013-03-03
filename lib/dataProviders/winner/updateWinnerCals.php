<?php

define('SECRET', '80f3dvfc8jcd60pd881s89s6bf25gi99');

//$URL = 'http://localhost/admin/updateWinnerCals?s='.SECRET;
$URL = 'http://www.sportycal.com/admin/updateWinnerCals?s='.SECRET;


//curl_setopt ($ch, CURLOPT_URL, 'http://username:password@myisp.net.au/test.txt');


$ch = curl_init();
$timeout = 5; // set to zero for no timeout
curl_setopt ($ch, CURLOPT_URL, $URL);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
$res = curl_exec($ch);
curl_close($ch);


if (strpos($res, "Imported Winner Cals") !== null) {
	echo date('d-m-Y H:i', time()) . " Imported Winner Cals\n";
} else {
	echo "PROBLEM!!! \n" . $res . "\n\n\n";
}

// Need to see how to use from command-line
//$importedGames = array();
//$updateWinnerCals = new UpdateWinnerCals();
//$calName2EventsCount = $updateWinnerCals->execute($importedGames);


