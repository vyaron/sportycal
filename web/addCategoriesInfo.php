<?php

///////////////////////////////////////////////// Parameters to change: ///////////////////////////////
// dir structure to import
//$filePath = "D:\Yaron\dev\sportYcal\Categories\importInfo\info.csv";
//$filePath = "C:\dev1\sportYcal\data\URL_and_Locations3.csv";
$filePath = "C:\dev1\sportYcal\data\URL_and_Locations after fixing san fransisco.csv";
//$filePath = "/tmp/yaron/info.csv";
// Use \n when running from command line
//$newLine   = "<br/>";            
$newLine = "\n";

$HANDLE_ADDRESSES = true;
$HANDLE_URLS	  = false;

///////////////////////////////////////////////// Stop Editing Here ///////////////////////////////

$errPrefix = "!!!!!!!!!!!!!!!!!!";
$doneNoGood = "FATAL ERROR - fix and run again";


echo "-- ------------------------------------ Start Working --------------------------------------"; 
echo $newLine, $newLine;

$gCon = null;
dbConnect("localhost","root","yokoono");
//dbConnect("localhost","sportycal","makemoney");


//clearstatcache();
handleInfoFile($filePath);


dbDisconnect();
echo $newLine . $newLine . "-- DONE". $newLine;



function handleInfoFile($filePath) {

  global $errPrefix;
  global $newLine;
  global $doneNoGood;
  global $HANDLE_ADDRESSES;
  global $HANDLE_URLS;
  

  echo $newLine,$newLine;  
  $linesStr = file_get_contents($filePath);
  $lines    = explode("\n", $linesStr);
  
  $firstLine = true;
  $count = 0;
  
  $sql = "SET FOREIGN_KEY_CHECKS=0;"; 
  echo $sql, $newLine,$newLine; 
  
  foreach ($lines as $line) {
    //if ($count >= 1) break;
  	
    if ($firstLine)  {
    	$firstLine = false;
    	continue;
    }
    
    $line = trim($line);

    if (!$line) continue;

    // Support comments
    if (strpos($line, "--") === 0) {
      echo $newLine, $line, $newLine;
      continue;
    }

    $ctgInfo = explode(",", $line);
    // line format:
    // <ctgId>,<ctgName (unused)>,website,location
    //  So the order of the fields is always:
    //  1. CtgId - required
    //  2. CtgName  - optional
    //  3. website  - optional    
    //  6. location - optional    
    // Valid examples:
    // 1,basketball,http:xx.com,location

    //echo "-- Line: '$line' $newLine";

    
    
    if (count($ctgInfo) < 8) {
      echo "$newLine $errPrefix Line: $line does not have all parts! $newLine";
      die($doneNoGood);
    }
    
    $ctgId         			= trim($ctgInfo[0]);
    $ctgName       			= trim($ctgInfo[1]);
    $ctgUrl        			= trim($ctgInfo[2]);
    $ctgLocationCountry     = trim($ctgInfo[3]);
    $ctgLocationState     	= trim($ctgInfo[4]);
    $ctgLocationCity	  	= trim($ctgInfo[5]);
    $ctgLocationAddress   	= trim($ctgInfo[6]);
    $ctgLocationZip		  	= trim($ctgInfo[7]);
    
    //echo "-- Line: '$line' $newLine";
    //debugIt("ctgId:$ctgId ctgName:$ctgName ctgUrl:$ctgUrl ctgLocationCountry:$ctgLocationCountry ctgLocationState:$ctgLocationState ctgLocationCity:$ctgLocationCity ctgLocationAddress:$ctgLocationAddress ctgLocationZip:$ctgLocationZip");
    
	$locationId = null;    
    // Handle Category Address
    if ($HANDLE_ADDRESSES && $ctgLocationCountry) {
	    $ctgLocationCity  		= addslashes($ctgLocationCity);
	    $ctgLocationAddress  	= addslashes($ctgLocationAddress);

    	$locationId = getLocationIdFor($ctgLocationCountry, $ctgLocationState, $ctgLocationCity, $line);
	    if (!$locationId) $locationId = 'null';
	    //debugIt("locationId: $locationId");
    
	    $addressId = getAddressIdIfAlreadyExist($ctgLocationCountry, $ctgLocationState, $ctgLocationCity, $ctgLocationAddress, $ctgLocationZip);
		if ($addressId) {
		} else {  
		    $sql = "delete from address where id = $ctgId;"; 
			echo $sql, $newLine;
		    $sql = "insert into address (id, country_code, state, city, addr, zip, location_id) values";
		    $sql .= "($ctgId, '$ctgLocationCountry', '$ctgLocationState', '$ctgLocationCity', '$ctgLocationAddress', '$ctgLocationZip', $locationId);"; 
			echo $sql, $newLine;
		    $sql = "update category set address_id = $ctgId where id=$ctgId; ";
			echo $sql;
		    echo $newLine, $newLine;
		    
		}
    }	
    
    if ($HANDLE_URLS && $ctgUrl) {
		$ctgLinkId = getCtgLinkIdIfAlreadyExist($ctgId, 'website');
		if ($ctgLinkId) {
		    $sql = "update category_link set url='$ctgUrl', target_url='$ctgUrl' where id=$ctgLinkId;";
		} else {  
		    $sql = "insert into category_link (category_id, type, txt, url, target_url) values";
		    $sql .= "($ctgId, 'website', 'Web Site', '$ctgUrl', '$ctgUrl');"; 
		}
		  
		echo $sql;
	    echo $newLine, $newLine;
    }
      
    $count++;
  }
  echo $newLine, $newLine;
  $sql = "SET FOREIGN_KEY_CHECKS=1;"; 
  echo $sql, $newLine,$newLine; 
  
}

function dbConnect($host, $user, $pass) {
  global $gCon;
  
  $gCon = mysql_connect($host,$user,$pass);
  if (!$gCon) die('Could not connect: ' . mysql_error());
  $db = mysql_select_db("evento", $gCon);
  if (!$db) die('Could not select db: ' . mysql_error());
}

function dbDisconnect() {
  global $gCon;
  mysql_close($gCon);
}

function getCtgLinkIdIfAlreadyExist($ctgId, $type) {
  $ctgLinkId = null;
  $sql = "SELECT id FROM category_link WHERE `category_id`=$ctgId AND `type`='$type'";

  $result = mysql_query($sql);
  
  if ( $row = mysql_fetch_array($result) )  {
    $ctgLinkId = $row['id'];
  }
  return $ctgLinkId;
}

function getAddressIdIfAlreadyExist($countryCode, $stateCode, $city, $address, $zip) {
  $addressId = null;
  $sql = "SELECT id FROM location WHERE `country`='$countryCode' AND `state`='$stateCode' AND city='$city' AND address='$address' AND zip='$zip'";

  $result = mysql_query($sql);
  
  if ($result && $row = mysql_fetch_array($result) )  {
    $addressId = $row['id'];
  }
  return $addressId;
}

function getLocationIdFor($countryCode, $stateCode, $city, $line) {
  $locationId = null;
  $sql = "SELECT id FROM location WHERE `country`='$countryCode' ";
  
  if ($stateCode) 	$sql .= " AND `state`='$stateCode' ";
  if ($city) 		$sql .= " AND (city='$city' OR (type_id=1 AND name_ascii='$city'))";

  $result = mysql_query($sql);
  
  if ($result && $row = mysql_fetch_array($result) )  {
    $locationId = $row['id'];
  }
  /*
  //if (strlen($countryCode) != 2) debugIt($countryCode);
  if (!$locationId && $countryCode != "US") {
  	//debugIt("location not found:");
  	//debugIt($line);
  	//debugIt("location not found: `country`='$countryCode' AND `state`='$stateCode' AND city='$city'");
  	debugIt("$sql");
  }*/
  
  
  return $locationId;
	
}


function debugIt($msg, $die = false) {

	global $errPrefix;
	global $newLine;
	global $doneNoGood;
	
	echo $msg . $newLine;
	//echo "DEBUG: " . $msg . $newLine;
	if ($die) die("DEATH is a BLESS");
}


?>
