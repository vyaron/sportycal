<?php

$dirLangs = "D:\\temp\\888\\langs";
$newLine = "\n";


echo "-- ------------------------------------ Start Working --------------------------------------"; 
echo $newLine, $newLine;


clearstatcache();

$dh = @opendir( $dirLangs );

$enTexts = array(
"Add to my Calendar",
"Google",
"Outlook",
"Mobile",
"Link",
"Help",		
"Close",
"Google Calendar",
"Just Click to add to your Google Calendar",
"Outlook",
"Just Click to add to your Outlook Calendar 2007 and up",
"Browse from your mobile and Click to add to your mobile phone or netbook",
"Yahoo, Lotus notes, and many others. To add this calendar, copy & use the Link",
"Download Sports Schedules to your Calendar",
"Show Events",
"Hide Events",
"iPhone/iPad calendar",
"use your iPhone/iPad to browse and download the calendar",
"click the google calendar button to add to your google account and synch the calendar from your device, need instructions?",
"see here"
);


while( $file = readdir( $dh )) {
	if( "." == $file || ".." == $file )    	continue;
	handleFile($dirLangs . "\\" . $file);
	
	//break;
}
closedir( $dh );	


function handleFile($fileName) {

	global $newLine;
	global $enTexts;
	
	// Validated and done : use strrpos here after validating that it does not break subscripion
	$langName = substr($fileName,0, strrpos($fileName, "."));
	echo $newLine, $newLine, "-- Importing : $langName ", $newLine;

	
	$linesStr = file_get_contents($fileName);
	$lines    = explode("\n", $linesStr);
	
	$fixedLines = array();
	
	$lineIndex = 0;
	foreach ($lines as $line) {
		$line = trim($line);
		if (!$line) 					continue;
		if (strpos($line, "#") === 0) 	continue;
		
		$parts    = explode("|", $line);
		$txt = (count($parts))? $parts[0] : $line;
		$fixedLine =  $enTexts[$lineIndex] . "|" .  $txt;
		
		$fixedLines[] = $fixedLine;
		
		$lineIndex++;
	}
	
	//var_dump($fixedLines);
	file_put_contents($fileName, implode("\n", $fixedLines));
	
	echo $newLine, $newLine;
	

}



?>
