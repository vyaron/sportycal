<?php
	// NOT WORKING from command line, needs to import EventTable and things


	// CONFIGURATION:
	$todo = array(array(02, 2011), array(03, 2011));
	// END CONFIGURATION
	

	require_once("FoxSpider.php");
	require_once("../model/Utils.php");
	Utils::runFoxSpider($todo);	

  	
?>