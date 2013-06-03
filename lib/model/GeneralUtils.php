<?php


class GeneralUtils {
    public static $MONTHS = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
    
    public static function formatDateRange($start, $end) {

        $start  = strtotime($start);
        $end    = strtotime($end);
        
        $startInfo  = getdate($start);
        $endInfo    = getdate($end);
        
        $startYear  = $startInfo["year"];
        $endYear    = $endInfo["year"];
    
        $startMonth  = $startInfo["mon"];
        $endMonth    = $endInfo["mon"];
    
        $startDay  = $startInfo["mday"];
        $endDay    = $endInfo["mday"];

        $result = "$startYear " .self::$MONTHS[$startMonth-1] ." $startDay,  " . format_date($start, 'H:mm') . ' - ' . format_date($end, 'H:mm');
    
        if ($startYear != $endYear || $startMonth != $endMonth) {
            $result = format_date($start, 'MMM d y H:mm') . ' - ' . format_date($end, 'MMM d y H:mm');
        }
        
        return $result;

        
    }

    public static function createIcalFile($calId, $strCal) {
        $fileName = "uploads/ical/sportyCal-$calId.ics";
        $fh = fopen($fileName, 'w') or die("can't open file");
        fwrite($fh, $strCal);
        fclose($fh);        
    }

    public static function icalEscape($val) {
        $val = html_entity_decode($val, ENT_QUOTES);
    	$val = str_replace(array("\n",'/',';'),array('\n',' ','\;'),$val);
        return $val;
    }
    
    // This is not touching the '/' character
    public static function icalEscape2($val) {
            return str_replace(array("\n",';'),array('\n','\;'),$val);
    }
    
    public static function arrayToString($array) {
            $str = "";
            foreach ($array as $key=>$val) {
                    $str .= "$key => $val , ";
            }
            return $str;
    }


    public static function mailAdmins($fromName, $fromEmail, $msg, $sub) {
        mail( "davido.cohen@sportycal.com",  $sub , $msg, "From: $fromEmail" );
        mail( "vyaron@gmail.com",  $sub , $msg, "From: $fromEmail" );
        mail( "rami.vachner@sportycal.com",  $sub , $msg, "From: $fromEmail" );
        
    }


    static $timezones = array (
        '-720' => 'Etc/GMT-12', 
        '-660' => 'Pacific/Niue', 
        '-600' => 'US/Hawaii', 
        '-540' => 'Pacific/Gambier', 
        '-480' => 'Pacific/Pitcairn', 
        '-420' => 'US/Arizona', 
        '-360' => 'Canada/Saskatchewan', 
        '-300' => 'US/Indiana-Starke',  
        '-240' => 'Canada/Atlantic',  
        '-210' => 'Canada/Newfoundland', 
        '-180' => 'America/Godthab', 
        '-120' => 'America/Noronha',  
        '-60' => 'Atlantic/Azores',  
        '0' => 'Africa/Casablanca',
        '60' => 'Europe/Paris', 
        '120' => 'Europe/Athens', 
        '180' => 'Europe/Moscow',  
        '210' => 'Asia/Tehran', 
        '240' => 'Asia/Muscat',  
        '270' => 'Asia/Kabul', 
        '300' => 'Asia/Yekaterinburg', 
        '330' => 'Asia/Kolkata', 
        '345' => 'Asia/Katmandu', 
        '360' => 'Asia/Dhaka', 
        '390' => 'Asia/Rangoon', 
        '420' => 'Asia/Krasnoyarsk', 
        '480' => 'Asia/Hong_Kong', 
        '525' => 'Australia/Eucla', 
        '540' => 'Asia/Yakutsk',  
        '570' => 'Australia/Darwin',  
        '600' => 'Australia/Brisbane', 
        '630' => 'Australia/Lord_Howe', 
        '660' => 'Pacific/Noumea', 
        '690' => 'Pacific/Norfolk', 
        '720' => 'Pacific/Auckland',  
        '765' => 'Pacific/Chatham', 
        '780' => 'Pacific/Tongatapu', 
        '840' => 'Pacific/Kiritimati'
	);
	
	
    public static function getTZValue($str) {
    	$val = null;
    	
    	foreach (self::$timezones as $value => $name){
    		if ($str == $name) {
    			$val = $value;
    			break;
    		}
    	}
    	
    	return $val;
    }
    
	//$str - '-H:i' ex -04:00
	public static function getTZFromStr($str) {
		$timeZoneStr = null;
		
		$str = explode(':', $str);
		if (count($str) > 1){
			$h = abs($str[0]);
			$m = abs($str[1]);
			
			$minTzStr = '';
			$minTzStr = ($h * 60) + $m;
			if (strpos($h, '-') >= 0){
				$minTzStr = '-' . $minTzStr;
			}
			
			$timeZoneStr = self::$timezones[$minTzStr];
		}
		
		return $timeZoneStr; 
	}

	public static function getTZFromJSTZ($jsTZ) {
		return (isset(self::$timezones[$jsTZ]))? self::$timezones[$jsTZ] : 0;
	}
	
	public static function getUTCStrFromJSTZ($jsTZ){
		$absMin = abs($jsTZ);
		$h = round($absMin / 60);
		$m = $absMin % 60;
		
		$utcStr = '(UTC';
		if ($jsTZ == 0) {
			$utcStr .= ')';
			return $utcStr;
		}
		else if ($jsTZ < 0) $utcStr .= ' -';
		else $utcStr .= ' +';
		
		$utcStr .= ($h < 10 ? '0' . $h : $h) . ':' . ($m < 10 ? '0' . $m : $m) . ')';
		
		return $utcStr;
	}
	
	public static function getTZList($keyToName=false){
		$tzList = array();
		foreach (self::$timezones as $TZmin => $TZname){
			$name = self::getUTCStrFromJSTZ($TZmin) . ' ' . $TZname;
			
			if ($keyToName){
				$tz = array();
				$tzList[$TZmin] = $name;
			} else {
				$tz = new stdClass();
				$tz->value = $TZmin;
				$tz->name = $name;
				$tzList[] = $tz;
			}
			
			
		}
		
		return $tzList;
	}

    public static function  getDateTimeInSpecificTZ($strDate, $originalTZ, $targetTZ, $eventIdForAlert=null, $calIdForAlert=null) {
        try {
        	$dateTime   = new DateTime("$strDate $originalTZ");
        } catch (Exception $e) {
        	//Wrong timezone on DB || Old PHP ver
        	
        	//Send David mail about
        	if ($eventIdForAlert && $calIdForAlert) @mail( "davido.cohen@sportycal.com",  'Timezone not supported' , "Wrong TZ: $originalTZ (cal: http://sportycal.com/cal/$calIdForAlert ; event-id: $eventIdForAlert)", "From: vyaron@gmail.com" );
        	
        	$dateTime   = new DateTime("$strDate");
        	return $dateTime;
        }
        
        //$strUserTZ = 'America/Chicago';
    	if (!$targetTZ) return $dateTime;
        
        $targetTZ     = timezone_open ($targetTZ);
    
        //echo "before changing TZ: " . $dateTime->format('Y-m-d H:i') . "<br/>";
    
        $dateTime->setTimezone( $targetTZ );
        //echo "after changing TZ: " . $dateTime->format('Y-m-d H:i') . "<br/>";
        return $dateTime;
    }


    public static function mapById($models) {
        $map = array();
        
        foreach ($models as $model) {
            $map[$model->getId()] = $model;
        }
        
        return $map;
    }

	public static function getDateForDisplay($time) {
		$strTime = date( 'Y-m-d', 	$time );
        $dt         = new DateTime($strTime);
        return $dt->format('Y-m-d');
	}
    
  	public static function isDate($strDate) {
  		if ($strDate == '0000-00-00 00:00:00') return false;
  		return true;
  	}  
}




?>