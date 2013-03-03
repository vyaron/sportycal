<?php

///////////////////////////////////////////////// Parameters to change: ///////////////////////////////


// !! This is for PARTNER Calendars!
$gPartnerId 	= 'null';
$gIsPublic 		= 'true';


//$gPartnerId 	= 10;
//$gIsPublic 		= 'false';



// dir structure to import
//$sourcepath = "C:\dev1\sportYcal\Categories\importTest";
$sourcepath = "D:\\temp\\import";
//$sourcepath = "/tmp/import";

$gCon = null;
dbConnect("localhost","root","yokoono");
//dbConnect("localhost","sportycal","makemoney");

// Use \n when running from command line
//$newLine   = "<br/>";            
$newLine = "\n";


///////////////////////////////////////////////// Stop Editing Here ///////////////////////////////

require_once("../lib/model/Utils.php");

$errPrefix = "!!!!!!!!!!!!!!!!!!";
$doneNoGood = "FATAL ERROR - fix and run again";


echo "-- ------------------------------------ Start Working --------------------------------------"; 
echo $newLine, $newLine;



// Category Id to start from
$gCurrCtgId = getCtgIdToStartFrom();
// Cal Id to start from
$gCalId = getCalIdToStartFrom();
// Event Id to start from
$gEventId = getEventIdToStartFrom();


clearstatcache();

$gRootLevel = array ( 'American Football' => 1,
                      'Baseball'          => 2,
                      'Football (Soccer)' => 3,
                      'Basketball'        => 4,
                      'Tennis'            => 5,
                      'Cricket'           => 6,
                      'Badminton'         => 7,
                      'Golf'              => 8,
                      'Ice Hockey'        => 9,
                      'Motor Sport'       => 10,
                      'Table Tennis'      => 11,
                      'Surfing'           => 12,
                      'Swimming'          => 13,
                      'Cycling'           => 14,
                      'Kayaking & Canoeing' => 15,
                      'Sailing'           => 16,
                      'Athletics'         => 17,
                      'Olympic Games'     => 18,
                      'Volleyball'        => 19,
                      'Martial Art'       => 20,
                      'Boxing'            => 21,
                      'TEST'              => 2000,
                      'Snooker'            => 2101,
                      'Darts'            => 2102,
					  'Rugby'            => 2500,
                      '888'            => 2600
                    );




$gLevel2ParentId = array();
$gCtgId2Name = array();

// Replace \ by / and remove the final / if any
//$root = ereg_replace( "/$", "", ereg_replace( "[\\]", "/", $sourcepath ));

$root = $sourcepath;
if( false === m_walk_dir( $root, "handleFile", true )) {
    echo "'{$root}' is not a valid directory\n";
}

dbDisconnect();
echo $newLine . $newLine . "-- DONE". $newLine;


// Walk a directory recursivelly, and apply a callback on each file
function m_walk_dir( $root, $callback, $recursive = true, $level = 0 ) {
    $dh = @opendir( $root );
    if( false === $dh ) {
        return false;
    }
    while( $file = readdir( $dh )) {
        if( "." == $file || ".." == $file ){
            continue;
        }
        //echo "## DEBUG:$level - $file<br/>"; 

        //echo "Level: $level";
        call_user_func( $callback,$level, "{$root}/{$file}", $file );
        if( false !== $recursive && is_dir( "{$root}/{$file}" )) {
            m_walk_dir( "{$root}/{$file}", $callback, $recursive, $level+1 );
        }
    }
    closedir( $dh );
    return true;
}

function handleFile($level, $path , $fileName) {
    global $gCurrCtgId;
    global $gLevel2ParentId;
    global $gRootLevel;
    global $newLine;
    global $gPartnerId;
    global $gIsPublic;
    
    // Directory = Category, so we insert a new one
    if( is_dir( $path )) {
      
      // Look for the parent 
      if ($level > 0) {
        $parentId = $gLevel2ParentId[$level-1];
        $ctgName  = addslashes($fileName);

        
        $dateNow    = date("Y-m-d g:i");

        $ctgId = getCtgIdIfAlreadyExist($parentId, $ctgName);
        $newCtg = true;
        if ($ctgId) $newCtg = false;
        
        if ($newCtg) {
          echo $newLine;
          echo "insert into `category`(`id`,`name`,`parent_id`, `rate`, `approved_at`, `partner_id`, `is_public`) values ($gCurrCtgId,'$ctgName',$parentId, 10, '$dateNow', $gPartnerId, $gIsPublic);";
          echo $newLine;
  
          $gLevel2ParentId[$level]      = $gCurrCtgId;
          $gCurrCtgId++;
        } else {
          $gLevel2ParentId[$level]      = $ctgId;
        }

      }  else {
        // This is a root level category, must be on our list      
        $rootCtgId = $gRootLevel[$fileName];
        
        if (!$rootCtgId) {
          echo "Found unknown root level category: $fileName " . $newLine; 
          die("ERROR!");
        }
        $gLevel2ParentId[0] = $rootCtgId;
      }
    
    } else {
        // We found a file - this is a calendar so we import it
        importCal($gLevel2ParentId[$level-1], $path , $fileName);
    }
}


function importCal($ctgId, $path, $fileName) {
  global $errPrefix;
  global $newLine;
  global $gEventId;
  global $gCalId;
  global $gCtgId2Name;
  global $doneNoGood;
  global $gPartnerId;
  global $gIsPublic;
  

  // Validated and done : use strrpos here after validating that it does not break subscripion
  $calName = substr($fileName,0, strrpos($fileName, "."));
  echo $newLine, $newLine, "-- Importing : $path ", $newLine;
  $dateNow    = date("Y-m-d g:i");

  $calName  = addslashes($calName);


  $calId = getCalIdIfAlreadyExist($ctgId, $calName);
  $newCal = true;
  
  if ($calId) $newCal = false;

  if ($newCal) {
    $calId = $gCalId;
    echo "INSERT INTO `cal`
    (id, category_id, name, location, created_at, updated_at, `partner_id`, `is_public`) VALUES 
    ($calId, $ctgId, '$calName', '', '$dateNow', '$dateNow', $gPartnerId, $gIsPublic);";
  
  } else {
    //echo "DELETE FROM `event` where cal_id=$calId;";
    //echo $newLine;
    echo "UPDATE `cal` set updated_at = '$dateNow' WHERE id=$calId;";
  }
  
  echo $newLine,$newLine;  
  $linesStr = file_get_contents($path);
  $lines    = explode("\n", $linesStr);
  
  $firstLine = true;
  
  foreach ($lines as $line) {
  	
  	if (strpos($line, "#") === 0) continue;

    // Support an info line that starts with: "sportYcal Info" only at first line of CSV
    // something like: 
    // sportYcal Info|tz=Asia/Jerusalem|location=Trinidad   
    //if ($firstLine) {
    //  $pos = strpos($line, "sportYcal Info");
    //  if ($pos === 0) {
    //    $extraInfo = explode("|", $line);
        // Yaron: for now - no firstLine is expected in cal file
        //getExtraInfoAsUpdateCmd($extraInfo, $calId);        
    //  } 
          
    //  $firstLine = false;
    //}  

    $out = Utils::getSqlForEventLine($line, $gEventId, $calId, $calName);
    echo $out, $newLine;
    if (strpos($out, "INSERT") ===0 ) $gEventId++;    
  }
  
  if ($newCal) $gCalId++;    
  
  echo $newLine, $newLine;
  
}

// Function gets an array like this: 
// sportYcal Info, primary_slogan=slog, description=desco, location=Trinidad
// and generate update command
function  getExtraInfoAsUpdateCmd($extraInfo, $calId) {
  global $newLine;

  $updateCmd = "";
  $updateParts = "";
  foreach ($extraInfo as $info) {
    
    $info = trim($info);
  
    if (strpos($info, "location") === 0) {
      $location = substr($info, strpos($info, "=")+1);
      $updateParts .= " `location` = '$location' ,";
    }
    if (strpos($info, "tz") === 0) {
      $tz = substr($info, strpos($info, "=")+1);
      $updateParts .= " `tz` = '$tz' ,";
    }
  
  }

  if ($updateParts) {
    $updateParts = chop($updateParts, ',');
    $updateCmd = "update `cal` set $updateParts where `id` = $calId;";
    echo "-- Found sportYcal info line, updating cal:$newLine";
    echo $updateCmd;
    echo $newLine;

  }
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

function getCalIdIfAlreadyExist($ctgId, $calName) {
  $calId = 0;
  $result = mysql_query("SELECT id FROM cal WHERE category_id=$ctgId AND name='$calName'");
  
  if ( $row = mysql_fetch_array($result) )  {
    $calId = $row['id'];
  }
  return $calId;
}

function getCtgIdIfAlreadyExist($parentId, $ctgName) {
  $ctgId = 0;
  $result = mysql_query("SELECT id FROM category WHERE parent_id=$parentId AND name='$ctgName'");
  
  if ( $row = mysql_fetch_array($result) )  {
    $ctgId = $row['id'];
  }
  return $ctgId;
}

function getCtgIdToStartFrom() {
  $ctgId = 0;
  $result = mysql_query("SELECT max(id) as id FROM category");
  
  if ( $row = mysql_fetch_array($result) )  {
    $ctgId = $row['id'];
  }
  return ++$ctgId;
} 
function getCalIdToStartFrom() {
  $calId = 0;
  $result = mysql_query("SELECT max(id) as id FROM cal");
  
  if ( $row = mysql_fetch_array($result) )  {
    $calId = $row['id'];
  }
  return ++$calId;
} 
function getEventIdToStartFrom() {
  $evId = 0;
  $result = mysql_query("SELECT max(id) as id FROM event");
  
  if ( $row = mysql_fetch_array($result) )  {
    $evId = $row['id'];
  }
  return ++$evId;
} 


?>
