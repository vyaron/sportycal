<?php


class Utils {
	const NEW_LINE = "\n";

    const DEVICE_TYPE_MAC = "macintosh";
	const DEVICE_TYPE_IPOD = "ipod";
	const DEVICE_TYPE_IPAD = "ipad";
	const DEVICE_TYPE_IPHONE = "iphone";
	const DEVICE_TYPE_ANDROID = "android";
	const DEVICE_TYPE_OPERA = "operaMobile";
	const DEVICE_TYPE_WINDOWS_PHONE = "windowsPhone";
	const DEVICE_TYPE_FIREFOX_DEVICE = "firefoxDevice";
	
	public static function substr($str, $length){
		if (strlen($str) > $length){
			return substr($str, 0, $length-1) . '&hellip;';
		} else return $str;
	}
	
	public static function hex2rgb($hex) {
		$hex = str_replace("#", "", $hex);
	
		if(strlen($hex) == 3) {
			$r = hexdec(substr($hex,0,1).substr($hex,0,1));
			$g = hexdec(substr($hex,1,1).substr($hex,1,1));
			$b = hexdec(substr($hex,2,1).substr($hex,2,1));
		} else {
			$r = hexdec(substr($hex,0,2));
			$g = hexdec(substr($hex,2,2));
			$b = hexdec(substr($hex,4,2));
		}
		$rgb = array($r, $g, $b);
		return $rgb;
	}
	
	public static function obsafe_print_r($var, $return = false, $html = false, $level = 0) {
		if (empty($var)) {
			$type = gettype($var);
			switch($type) {
				case 'boolean':
					return '<em>false</em>';
				case 'integer';
					return '0';
				case 'array';
					return '<em>array()</em>';
				default:
					return '<em>empty ' . gettype($var) . '</em>';	
			};
		}
		$space = $html ? "&nbsp;" : " ";
		$newline = $html ? "<br />" : "\n";
		
		$spaces = str_repeat($space, 4);
		$tabs = str_repeat($spaces, $level + 1);
		
		$output = "";
		$title  = "";
		
		if (is_array($var)) {
			$title = "Array";
		} else if (is_object($var)) {
			$title = get_class($var)." Object";
		}
		if ($title) {
			$output = $title . $newline;
			if ($level != 0) {
				$output .= $tabs;
			}
			$output .= '(' . $newline;
	
		}
		if (is_array($var) || is_object($var)) {
			foreach($var as $key => $value) {
				if (is_array($value) || is_object($value)) {
					$value = self::obsafe_print_r($value, true, $html, $level + 2) . $newline;
				}
				$var_tabs =  ($title && $level) ? ($tabs . $spaces) : $tabs;
				$output .=  $var_tabs . "[" . $key . "] => " . $value . $newline;
			}
			//unset($RECURSTION_ANCESTOR[$level]);
		} else {
			$output .= $var;
		}
		
		//Close it
		if ($title) {
			$output .=  ($level ? $tabs : '') . ")";
		}
		
		if ($return) {
			return $output;
		} else {
			echo $output;
		}
	}
	
	/**
	 * Function: pa
	 * HTML 'friendly' debugging output.  This uses our own obsafe_print_r, which
	 * handles displaying objects better (only shows the public vars).  Use pr to 
	 * see all the details about the objects.
	 * 
	 * This will also indicate what line it was being called from.
	 * NOTE: Due to the way that PHP handles references, it is impossible to detect
	 * recursion in _arrays_ in PHP.  Only print_r() is capable of this as it is
	 * low-language and has access to the memory location of variables.  To write
	 * a recursive-safe print_r equivalent, you'd have no choice but to parse
	 * print_r's output.
	 */
	public static function pa() {
		//trigger_error('DEBUGGING');
		$args = func_get_args();
		
		// Command line options:
		$prefix = '<pre style="background: #fff;">';
		$suffix = '</pre>';
		//if ($_SERVER['TERM']) {
		//	$prefix = $suffix = '';
		//}
		
		foreach($args as $a) { 	
			echo "$prefix\n" . self::obsafe_print_r($a, true) . "\n$suffix\n"; 
		}
	}
	public static function pp($x) { 
		//$args = func_get_args();
		//call_user_func_array('self::pa', $args);
		self::pa($x);
		exit; 
	}

	
	
    public static function getSubCtgInStrAndParams($ctgIds) {
    	
    	$result = array();
    	$params = array();
    	$str = ' (';
    	//Utils::pp($ctgIds);
    	$count = count($ctgIds);
    	
    	for ($i = 0; $i <  $count; $i++) {
    		$ctgId = $ctgIds[$i];
    		$str .= " cal.category_ids_path REGEXP :inCtgId$i ";

    		if ($i < $count-1) $str .= " OR ";
    		// TODO: potential bug here, need %,ctg,% but also handle when first ctg and last???
    		$params[":inCtgId$i"] = "(,|^)$ctgId(,|$)";
    	}
    	
    	$str .= ') ';
    	
    	$result[0] = $str;
    	$result[1] = $params;
    	
    	return $result;
	}
	
	public static $STATES = array(
		'US' => array(
			'AL' => 'Alabama',
			'AK' => 'Alaska',
			'AZ' => 'Arizona',
			'AR' => 'Arkansas',
			'CA' => 'California',
			'CO' => 'Colorado',
			'CT' => 'Connecticut',
			'DC' => 'District of Columbia',
			'DE' => 'Delaware',
			'FL' => 'Florida',
			'GA' => 'Georgia',
			'HI' => 'Hawaii',
			'ID' => 'Idaho',
			'IL' => 'Illinois',
			'IN' => 'Indiana',
			'IA' => 'Iowa',
			'KS' => 'Kansas',
			'KY' => 'Kentucky',
			'LA' => 'Louisiana',
			'ME' => 'Maine',
			'MD' => 'Maryland',
			'MA' => 'Massachusetts',
			'MI' => 'Michigan',
			'MN' => 'Minnesota',
			'MS' => 'Mississippi',
			'MO' => 'Missouri',
			'MT' => 'Montana',
			'NE' => 'Nebraska',
			'NV' => 'Nevada',
			'NH' => 'New Hampshire',
			'NJ' => 'New Jersey',
			'NM' => 'New Mexico',
			'NY' => 'New York',
			'NC' => 'North Carolina',
			'ND' => 'North Dakota',
			'OH' => 'Ohio',
			'OK' => 'Oklahoma',
			'OR' => 'Oregon',
			'PA' => 'Pennsylvania',
			'RI' => 'Rhode Island',
			'SC' => 'South Carolina',
			'SD' => 'South Dakota',
			'TN' => 'Tennessee',
			'TX' => 'Texas',
			'UT' => 'Utah',
			'VT' => 'Vermont',
			'VA' => 'Virginia',
			'WA' => 'Washington',
			'WV' => 'West Virginia',
			'WI' => 'Wisconsin',
			'WY' => 'Wyoming'
		),
		'CA' => array(
			'AB' => 'Alberta',
			'BC' => 'British Columbia',
			'MB' => 'Manitoba',
			'NB' => 'New Brunswick',
			'NF' => 'Newfoundland',
			'NS' => 'Nova Scotia',
			'NT' => 'Northwest Territory',
			'NU' => 'Nunavut',
			'ON' => 'Ontario',
			'PE' => 'Prince Edward Island',
			'QC' => 'Quebec',
			'SK' => 'Saskatchewan',
			'YU' => 'Yukon'
		),
		'AU' => array(
			'AC' => 'Australian Capital',
			'NO' => 'Northern Territory',
			'NW' => 'New South Wales',
			'QL' => 'Queensland',
			'SA' => 'South Australia',
			'TS' => 'Tasmania',
			'VC' => 'Victoria',
			'WT' => 'Western Australia'
		),

	);

	
	public static $COUNTRIES = array(
		'US' => 'United States',
		'AD' => 'Andorra',
		'AE' => 'United Arab Emirates',
		'AF' => 'Afghanistan',
		'AG' => 'Antigua and Barbuda',
		'AI' => 'Anguilla',
		'AL' => 'Albania',
		'AM' => 'Armenia',
		'AN' => 'Netherlands Antilles',
		'AO' => 'Angola',
		'AQ' => 'Antarctica',
		'AR' => 'Argentina',
		'AS' => 'American Samoa',
		'AT' => 'Austria',
		'AU' => 'Australia',
		'AW' => 'Aruba',
		'AX' => 'Aland Islands',
		'AZ' => 'Azerbaijan',
		'BA' => 'Bosnia and Herzegovina',
		'BB' => 'Barbados',
		'BD' => 'Bangladesh',
		'BE' => 'Belgium',
		'BF' => 'Burkina Faso',
		'BG' => 'Bulgaria',
		'BH' => 'Bahrain',
		'BI' => 'Burundi',
		'BJ' => 'Benin',
		'BM' => 'Bermuda',
		'BN' => 'Brunei Darussalam',
		'BO' => 'Bolivia',
		'BR' => 'Brazil',
		'BS' => 'Bahamas',
		'BT' => 'Bhutan',
		'BV' => 'Bouvet Island',
		'BW' => 'Botswana',
		'BY' => 'Belarus',
		'BZ' => 'Belize',
		'CA' => 'Canada',
		'CC' => 'Cocos (Keeling) Islands',
		'CD' => 'Congo (Kinshasa)',
		'CF' => 'Central African Republic',
		'CG' => 'Congo',
		'CH' => 'Switzerland',
		'CI' => 'Cote D\'Ivoire (Ivory Coast)',
		'CK' => 'Cook Islands',
		'CL' => 'Chile',
		'CM' => 'Cameroon',
		'CN' => 'China',
		'CO' => 'Colombia',
		'CR' => 'Costa Rica',
		'CS' => 'Serbia and Montenegro',
		'CU' => 'Cuba',
		'CV' => 'Cape Verde',
		'CX' => 'Christmas Island',
		'CY' => 'Cyprus',
		'CZ' => 'Czech Republic',
		'DE' => 'Germany',
		'DJ' => 'Djibouti',
		'DK' => 'Denmark',
		'DM' => 'Dominica',
		'DO' => 'Dominican Republic',
		'DZ' => 'Algeria',
		'EC' => 'Ecuador',
		'EE' => 'Estonia',
		'EG' => 'Egypt',
		'EH' => 'Western Sahara',
		'ER' => 'Eritrea',
		'ES' => 'Spain',
		'ET' => 'Ethiopia',
		'FI' => 'Finland',
		'FJ' => 'Fiji',
		'FK' => 'Falkland Islands',
		'FM' => 'Micronesia',
		'FO' => 'Faroe Islands',
		'FR' => 'France',
		//'FX' => 'France, Metropolitan',
		'GA' => 'Gabon',
		//'GB' => 'Great Britain (UK)',
		'GD' => 'Grenada',
		'GE' => 'Georgia',
		'GF' => 'French Guiana',
		'GG' => 'Guernsey',
		'GH' => 'Ghana',
		'GI' => 'Gibraltar',
		'GL' => 'Greenland',
		'GM' => 'Gambia',
		'GN' => 'Guinea',
		'GP' => 'Guadeloupe',
		'GQ' => 'Equatorial Guinea',
		'GR' => 'Greece',
		'GS' => 'S. Georgia and S. Sandwich',
		'GT' => 'Guatemala',
		'GU' => 'Guam',
		'GW' => 'Guinea-Bissau',
		'GY' => 'Guyana',
		'HK' => 'Hong Kong',
		//'HM' => 'Heard & McDonald Islands',
		'HN' => 'Honduras',
		'HR' => 'Croatia',
		'HT' => 'Haiti',
		'HU' => 'Hungary',
		'ID' => 'Indonesia',
		'IE' => 'Ireland',
		'IL' => 'Israel',
		'IM' => 'Isle of Man',
		'IN' => 'India',
		'IO' => 'British Indian Ocean Territory',
		'IQ' => 'Iraq',
		'IR' => 'Iran',
		'IS' => 'Iceland',
		'IT' => 'Italy',
		'JM' => 'Jamaica',
		'JE' => 'Jersey',
		'JO' => 'Jordan',
		'JP' => 'Japan',
		'KE' => 'Kenya',
		'KG' => 'Kyrgyzstan',
		'KH' => 'Cambodia',
		'KI' => 'Kiribati',
		'KM' => 'Comoros',
		'KN' => 'Saint Kitts and Nevis',
		'KP' => 'Korea (North)',
		'KR' => 'Korea (South)',
		'KW' => 'Kuwait',
		'KY' => 'Cayman Islands',
		'KZ' => 'Kazakhstan',
		'LA' => 'Laos',
		'LB' => 'Lebanon',
		'LC' => 'Saint Lucia',
		'LI' => 'Liechtenstein',
		'LK' => 'Sri Lanka',
		'LR' => 'Liberia',
		'LS' => 'Lesotho',
		'LT' => 'Lithuania',
		'LU' => 'Luxembourg',
		'LV' => 'Latvia',
		'LY' => 'Libya',
		'MA' => 'Morocco',
		'MC' => 'Monaco',
		'MD' => 'Moldova',
		'MG' => 'Madagascar',
		'MH' => 'Marshall Islands',
		'MK' => 'Macedonia',
		'ML' => 'Mali',
		'MM' => 'Myanmar',
		'MN' => 'Mongolia',
		'MO' => 'Macau',
		'MP' => 'Northern Mariana Islands',
		'MQ' => 'Martinique',
		'MR' => 'Mauritania',
		'MS' => 'Montserrat',
		'MT' => 'Malta',
		'MU' => 'Mauritius',
		'MV' => 'Maldives',
		'MW' => 'Malawi',
		'MX' => 'Mexico',
		'MY' => 'Malaysia',
		'MZ' => 'Mozambique',
		'NA' => 'Namibia',
		'NC' => 'New Caledonia',
		'NE' => 'Niger',
		'NF' => 'Norfolk Island',
		'NG' => 'Nigeria',
		'NI' => 'Nicaragua',
		'NL' => 'Netherlands',
		'NO' => 'Norway',
		'NP' => 'Nepal',
		'NR' => 'Nauru',
		'NU' => 'Niue',
		'NZ' => 'New Zealand',
		'OM' => 'Oman',
		'PA' => 'Panama',
		'PE' => 'Peru',
		'PF' => 'French Polynesia',
		'PG' => 'Papua New Guinea',
		'PH' => 'Philippines',
		'PK' => 'Pakistan',
		'PL' => 'Poland',
		'PM' => 'Saint Pierre and Miquelon',
		'PN' => 'Pitcairn',
		'PR' => 'Puerto Rico',
		'PS' => 'Palestinian Territory',
		'PT' => 'Portugal',
		'PW' => 'Palau',
		'PY' => 'Paraguay',
		'QA' => 'Qatar',
		'RE' => 'Reunion',
		'RO' => 'Romania',
		'RU' => 'Russia',
		'RW' => 'Rwanda',
		'SA' => 'Saudi Arabia',
		'SB' => 'Solomon Islands',
		'SC' => 'Seychelles',
		'SD' => 'Sudan',
		'SE' => 'Sweden',
		'SG' => 'Singapore',
		'SH' => 'Saint Helena',
		'SI' => 'Slovenia',
		'SJ' => 'Svalbard and Jan Mayen',
		'SK' => 'Slovakia',
		'SL' => 'Sierra Leone',
		'SM' => 'San Marino',
		'SN' => 'Senegal',
		'SO' => 'Somalia',
		'SR' => 'Suriname',
		'ST' => 'Sao Tome and Principe',
		'SV' => 'El Salvador',
		'SY' => 'Syria',
		'SZ' => 'Swaziland',
		'TC' => 'Turks and Caicos Islands',
		'TD' => 'Chad',
		'TF' => 'French Southern Territories',
		'TG' => 'Togo',
		'TH' => 'Thailand',
		'TJ' => 'Tajikistan',
		'TK' => 'Tokelau',
		'TL' => 'Timor-Leste',
		'TM' => 'Turkmenistan',
		'TN' => 'Tunisia',
		'TO' => 'Tonga',
		'TP' => 'East Timor',
		'TR' => 'Turkey',
		'TT' => 'Trinidad and Tobago',
		'TV' => 'Tuvalu',
		'TW' => 'Taiwan',
		'TZ' => 'Tanzania',
		'UA' => 'Ukraine',
		'UG' => 'Uganda',
		'UK' => 'United Kingdom',
		//'UM' => 'United States Minor Outlying Islands',
		'UY' => 'Uruguay',
		'UZ' => 'Uzbekistan',
		'VA' => 'Vatican City State (Holy See)',
		'VC' => 'St Vincent & the Grenadines',
		'VE' => 'Venezuela',
		'VG' => 'Virgin Islands (British)',
		'VI' => 'Virgin Islands (U.S.)',
		'VA' => 'Vatican City',
		'VN' => 'Vietnam',
		'VU' => 'Vanuatu',
		'WF' => 'Wallis and Futuna',
		'WS' => 'Samoa',
		'YE' => 'Yemen',
		'YT' => 'Mayotte',
		'ZA' => 'South Africa',
		'ZM' => 'Zambia',
		'ZW' => 'Zimbabwe'
	);

	public static function getSqlForEventLine($line, $eventId=null, $calId=null, $calName='', $isUpdate=false) {
	    $line = trim($line);
	    if (!$line) return '';

	    // Support comments
	    if (strpos($line, "--") === 0) {
	      //echo $newLine, $line, $newLine;
	      return $line;
	    }
    
	    $eventInfo = explode(self::CSV_SEP, $line);
	    // line format:
	    // <competitor A>|<competitor B>|dd/mm/yyyy|hh:mm or TBD|timezone|location| endHour| desc
	    //  So the order of the fields is always:
	    //  1. Commpetitor A - required
	    //  2. Competitor B - possibly empty - if not empty than the Home-Away strategy is used for location
	    //  3. date in format: dd/mm/yyyy
	    //  4. hour in format: hh:mm or empty
	    //  5. timezone from the list of PHP supported timezones.
	    //  6. location - optional    
	    //  7. end-hour in format: hh:mm - optional
	    //  8. desc - optional	    	    
	    // Valid examples:
	    // Team A|Team B|28/08/2010|TBD
	    // Team A|Team B|28/08/2010|10:00| Asia/Jerusalem
	    // Tournament||28/08/2010|11:00|EST
	    // Tournament||28/08/2010|11:00|EST| Bet Shemesh    

    	// echo "-- Line: '$line' $newLine";
    

	    if (count($eventInfo) < 3) {
			//echo "$newLine $errPrefix Line: $line does not have 6 parts! $newLine";
			//die($doneNoGood);
			return "ERROR: Problem with line: $line";
	    }
    
	    $eP1          = trim($eventInfo[0]);
	    $eP2          = trim($eventInfo[1]);
	    $eDate        = trim($eventInfo[2]);
	    $eHour        = trim(self::iff($eventInfo[3], ''));
	    $eTZ          = trim(self::iff($eventInfo[4], ''));
	    $eLocation    = trim(self::iff($eventInfo[5], ''));
	    $eEndHour     = trim(self::iff($eventInfo[6], ''));
	    $eDesc        = trim(self::iff($eventInfo[7], ''));
	    
	    // Sometimes in CSV, excel will add "" to the data, so we remove that
	    if (strpos($eP1, '"') === 0 &&  strpos(strrev($eP1) , '"') === 0) $eP1 = substr($eP1,1,strlen($eP1)-2);
	    if (strpos($eP2, '"') === 0 &&  strpos(strrev($eP2) , '"') === 0) $eP2 = substr($eP2,1,strlen($eP2)-2);
	    if (strpos($eDesc, '"') === 0 &&  strpos(strrev($eDesc) , '"') === 0) $eDesc = substr($eDesc,1,strlen($eDesc)-2);
	    
	    $eP1        = addslashes($eP1);
	    $eP2        = addslashes($eP2);
	    $eLocation  = addslashes($eLocation);
		$eDesc      = addslashes($eDesc);

	    $eventName = $eP1;
	    if ($eP2) {
	      $eventName = "$eP1 vs. $eP2";
	      
	      if (!$eLocation) {
	        // The Home/Away strategy:
	        $eLocation = "Away";
	        //if ($calName == $eP1) $eLocation = "Home";

          //var_dump("Cal name: " . $calName);
          //var_dump("Team name: " . $eP1);
          //var_dump(strpos($calName, $eP1));

	        if (!$calName) $eLocation = "UNKNOWN";
	        elseif (strpos($calName, $eP1) !== false || strpos($eP1, $calName) !== false) $eLocation = "Home";
	      }
	    }
	    //echo "Original Time is: #" . $eventDate . "#<br/>";
    	// Convert: "28/08/2010" to "2010-08-28"
	    $pieces       =  explode("/", $eDate);
	    $eDate    = $pieces[2] . "-" . $pieces[1] . "-" . $pieces[0];
	    $eDateTime = $eDate;
	    if ($eHour) {
	      $eDateTime = $eDate . " " . $eHour;
	      if ($eTZ) {
	        $eTZ = self::convertTZ($eTZ);
	        $objTZ     = timezone_open ($eTZ);
	        if (!$objTZ) {
	          return "ERROR: Found Hour: $eHour, TZ: $eTZ - Invalid TimeZone!";
	        }
	      } else {
	         return "ERROR: Found Hour: $eHour, but no TimeZone!";
	      }
	    }  
	    //$tsEvent      = strtotime($eDate);
	    //$dateEvent    = date("Y-m-d g:i", $tsEvent);

	    $starts = $ends = $eDateTime;

	    if ($eEndHour) {
	    	$ends = $eDate . " " . $eEndHour;
	    }
	    
	    //$eventName = addslashes("{$eventInfo[0]} vs. {$eventInfo[1]}");

	    $dateNow    = date("Y-m-d g:i");
	    
	    if (!$eventId) $eventId = "null";
	   	if (!$calId) $calId = "UNKNOWN";
	    
	    if ($isUpdate) {
	    	$sql = "UPDATE `event` SET tz='$eTZ', starts_at='$starts',ends_at='$ends', updated_at='$dateNow' WHERE id=$eventId;"; 
	    } else {
	    	$sql = "INSERT INTO `event`(`id`,`cal_id`,`name`,`description`, `location`, `tz`, `starts_at`,`ends_at`,`created_at`, `updated_at`) VALUES
	    			($eventId,$calId,'$eventName','$eDesc','$eLocation','$eTZ','$starts','$ends', '$dateNow', '$dateNow');"; 
	    	
	    }
	    
	    return $sql;
  }
	private static function convertTZ($eTZ) {
		if ($eTZ == "ET")  $eTZ = "EST";
		return $eTZ;
	}
	/**
	 * Function: iff
	 * 
	 * A shorthand for quick if else or the tenary operator, largely used in templates. 
	 * If there are only two parameters, if the condition is met, then it return the value of the condition.
	 * 
	 *
	 * Paramters
	 *   a - The conditional parameter
	 *   b - Either the else for the conditional (for 2 params) or the if for the cond
	 *   c - The else value (if it exists)
	 */
	public static function iff($a, $b = '', $c = '') {
		if (func_num_args() == 3) {
			return $a ? $b : $c;
		} else {
			return $a ? $a : $b;
		}
	}  
	
	public 	static function runFoxSpider($league, $todo) {

		//define ("OUTPUT_SQL", "out_FOX.sql");
		//define ("OUTPUT_SQL", "");		
		//define ("OUTPUT_CSV", "out_FOX.csv");
		//define ("OUTPUT_CSV", "");
		define ("NO_PROXIES_GO_DIRECT", 1);

		$fs = new FoxSpider($league);
		$fs->cleanFile();
		//$needSpiderCount = count($todo);
		//$handledCount = 0;
	
		//self::debugIt("-- WORK TO DO: $needSpiderCount FOX Months");
		
		foreach ($todo as $item) {
			
			$fs->spider($item[0], $item[1]);
			$fs->save();
			
			//Utils::pp('asd');
			//$handledCount++;
			//self::debugIt("***** FINISHED handling $handledCount of $needSpiderCount *****");
		} 
		//self::debugIt("-- Finished running FOX spider, bye.");
		
		//if (defined("OUTPUT_CSV") ) 	return $fs->getOutputCsv();
		//else 							return $fs->getOutputSql();
		
	}
	
	public 	static function runESPNSpider($ctgName) {
		define ("NO_PROXIES_GO_DIRECT", 1);
		
		/*
		$specificTeams = array(325 => "Cleveland State",
							   338  => "Kennesaw State",
							   288  => "Lipscomb",
							   2382  => "Mercer",
							   350  => "North Carolina-Wilmington",
							   2454  => "North Florida",
							   3084  => "Utah Valley"
							);
		
		$specificSpider = new ESPNSpider();
		foreach ($specificTeams as $id=>$name) {
			$specificSpider->getTeam($id, $name);
			$specificSpider->save();
			
		}
		Utils::pp("DONE SPECIFIC");
		*/
		
		$es = new ESPNSpider($ctgName);
		$infoLines = array("Starting up E-Spider");
		$teams = $es->getTeams();
		
		$countMatches = count($teams[1]);
		for($i = 0; $i < $countMatches; $i++) {
			//if ($i >=2) die("enough");

			$teamId 	= $teams[1][$i];
			$teamName 	= $teams[2][$i];
			
			//$es = new ESPNSpider();
			$es->getTeam($teamId, $teamName);
			
			$es->save();
			$infoLines[] = "Handeled $teamName (Id: $teamId)";
			//echo "Just Done Handeling $teamName (Id: $teamId)<br/>";
			//flush();

			//if ($i == 1)Utils::pp('asd');
		}
		
		return $infoLines;
	}

	public static function runNHLSpider() {
		define ("NO_PROXIES_GO_DIRECT", 1);
				
		$nhlSpider = new NHLSpider();
		$infoLines = array("Starting up E-Spider");
		
		$teamsGames = $nhlSpider->getTeamsGames();
		
		foreach ($teamsGames as $teamName => $teamGames) {
			$nhlSpider->saveTeam($teamName, $teamGames);
			$infoLines[] = "Handeled $teamName";
		}
		
		return $infoLines;
	}
	
	public 	static function runSoccernetSpider() {
		define("NO_PROXIES_GO_DIRECT", 1);
		define("DAYS_PER_YAER", 365);
		
		$infoLines = array("Starting up Soccernet-Spider");
		$leagues = array('gre.1', 'tur.1', 'rus.1', 'arg.1', 'bra.1', 'aus.1', 'por.1');
		
		foreach ($leagues as $league){
			$infoLines[] = "Staring $league";
			for ($i=0; $i <= DAYS_PER_YAER; $i += 1){
				$millis = strtotime("+$i days");
				$date = date("d/m/Y", $millis);				
				$sos = new SoccernetSpider();
				
				$hasGames = $sos->spider($millis, $league);
				//if (!$hasGames) break;
				
				$sos->save();
				$infoLines[] = "Handeled Date: $date";
				//echo "Just Done Handeling $league (Date: $date)<br/>";
				//flush();
				
			}
		}
		return $infoLines;
		
	}
	

	public 	static function runEspnscrumSpider() {
		$calendars = array(
			'Aviva premiership' => 'http://www.espnscrum.com/premiership-2011-12/rugby/series/142402.html?template=fixtures',
			'Heineken Cup' => 'http://www.espnscrum.com/heineken-cup-2011-12/rugby/series/144542.html?template=fixtures',
			'Amlin Challenge Cup' => 'http://www.espnscrum.com/amlin-challenge-cup-2011-12/rugby/series/144784.html?template=fixtures',
			'Top 14 Orange' => 'http://www.espnscrum.com/france-top-14-2011-12/rugby/series/143738.html?template=fixtures',
			'RaboDirect PRO12' => 'http://www.espnscrum.com/rabodirect-pro12-2011-12/rugby/series/144177.html?template=fixtures',
			'Anglo-Welsh Cup' => 'http://www.espnscrum.com/anglo-welsh-cup-2011-12/rugby/series/143739.html?template=fixtures'
		);
		
		$ess = new EspnscrumSpider();
		
		
		foreach ($calendars as $calendar => $url){
			$ess->spider($url,$calendar);
		}
		
		
		$url = 'http://www.espnscrum.com/scrum/rugby/match/fixtures/international.html';
		$ess->spider($url);
		
		
		
		Utils::pp('DONE');

	}
	
	public 	static function runEspnOlympicsSpider() {
		$calendars = array(
			'Archery London 2012' => array(
				'Archery' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/80878.html'
			),
			'Shooting London 2012' => array(
				'Shooting' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/82346.html'
			),
			'Athletics London 2012' => array(
				'Athletics' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/80876.html',
				'Triathlon' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/82354.html'
			),
			'Badminton London 2012' => array(
				'Badminton' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/82136.html'
			),
			'Basketball London 2012' => array(
				'Basketball' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/82137.html'
			),
			'Volleyball London 2012' => array(
				'Beach Volleyball' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/82147.html',
				'Volleyball' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/82357.html'
			),
			'Canoe and Kayaking London 2012' => array(
				'Slalom' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/82169.html',
				'Sprint' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/82171.html',
				'Rowing' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/82203.html'
			),
			'Cycling London 2012' => array(
				'Road Cycling' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/82176.html',
				'Track Cycling' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/82177.html',
				'Mountain Bike' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/82175.html',
				'BMX' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/82173.html'
			),
			'The Pool London 2012' => array(
				'Diving' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/82178.html',
				'Swimming' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/82358.html',
				'Synchronized Swimming' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/82573.html',
				'Water Polo' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/82359.html'
			),
			'Equestrian London 2012' => array(
				'Equestrian Dressage' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/82179.html',
				'Equestrian Eventing' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/82180.html',
				'Equestrian Jumping' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/82181.html'
			),
			'Football London 2012' => array(
				'Football' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/82185.html'
			),
			'Gymnastics London 2012' => array(
				'Artistic Gymnastics' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/82186.html',
				'Rhythmic Gymnastics' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/82188.html',
				'Trampoline' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/82190.html'
			),
			'Handball London 2012' => array(
				'Handball' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/82193.html'
			),
			'Hockey London 2012' => array(
				'Hockey' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/82195.html'
			),
			'Martial Arts London 2012' => array(
				'Judo' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/82196.html',
				'Modern Pentathlon' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/82197.html',
				'Taekwondo' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/82351.html',
				'Fencing' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/82184.html',
				'Boxing' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/82168.html'
			),
			'Sailing London 2012' => array(
				'Sailing' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/82345.html'
			),
			'Table Tennis London 2012' => array(
				'Table Tennis' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/82348.html'
			),
			'Tennis London 2012' => array(
				'Tennis' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/82352.html'
			),
			'Weightlifting London 2012' => array(
				'Weightlifting' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/82360.html'
			),
			'Wrestling London 2012' => array(
				'Freestyle Wrestling' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/82363.html',
				'Greco-Roman Wrestling' => 'http://www.espn.co.uk/london-olympics-2012/sport/story/82364.html'
			)
		);
		
		$eos = new EspnOlympicsSpider();
		
		foreach ($calendars as $calName => $cal){
			foreach ($cal as $eventName => $url){
				$eos->spider($calName, $eventName, $url, 2012);
			}
		}
		
		Utils::pp('DONE');

	}
	
	public static function debugIt($msg, $die = false) {
		//echo "DEBUG: " . $msg . "\n";
		//echo "DEBUG: " . $msg . "<br/>";
		//if ($die) die("DEATH is a BLESS");
	}

	
	public static function redirectToMobileVersionIfNeeded($controller) {
		if (!sfConfig::get('app_domain_isNeverMiss') && !sfConfig::get('app_domain_isMobile') && self::clientIsMobile(false)) {
			$file = $_SERVER["REQUEST_URI"];
			$controller->redirect("http://m.sportycal.com" . $file);				
		}
	}
	
	
	
	public static function clientIsMobile($withBigScreens = true) {
		$userAgent = $_SERVER['HTTP_USER_AGENT'];
		
		$isMobile = false;
		$deviceType = self::getClientDeviceType($withBigScreens);
		if ($deviceType) $isMobile = true;
		
		return $isMobile;
	}

	public static function clientIsAndroid() {
		$userAgent = $_SERVER['HTTP_USER_AGENT'];
		if (stripos($userAgent,'android') !== false ) return true;
		return false;
	}
	
	public static function clientIsIpad(){
		$userAgent = $_SERVER['HTTP_USER_AGENT'];
		return (stripos($userAgent,'ipad') !== false);
	}

    public static function clientIsOutlook(){
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        return (stripos($userAgent,'Outlook') !== false);
    }

    public static function clientIsMac(){
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        return (stripos($userAgent,'Macintosh') !== false);
    }
	
	public static function getClientDeviceType($withBigScreens=true){
		$userAgent = $_SERVER['HTTP_USER_AGENT'];

		$deviceType = '';
		if (stripos($userAgent,'ipod') !== false) $deviceType = self::DEVICE_TYPE_IPOD;
        else if ($withBigScreens && stripos($userAgent,'Macintosh') !== false) $deviceType = self::DEVICE_TYPE_MAC;
        else if ($withBigScreens && stripos($userAgent,'ipad') !== false ) $deviceType = self::DEVICE_TYPE_IPAD;
		else if (stripos($userAgent,'iphone') !== false ) $deviceType = self::DEVICE_TYPE_IPHONE;
		else if (stripos($userAgent,'android') !== false ) $deviceType = self::DEVICE_TYPE_ANDROID;
		else if (stripos($userAgent,'opera mobi') !== false ) $deviceType = self::DEVICE_TYPE_OPERA;
		else if (stripos($userAgent,'windows phone os') !== false && stripos($userAgent,'iemobile')) $deviceType = self::DEVICE_TYPE_WINDOWS_PHONE;
		else if (stripos($userAgent,'fennec') !== false) $deviceType = self::DEVICE_TYPE_FIREFOX_DEVICE;
		
		return $deviceType;
	}

	public static function useMobileViewIfNeeded($controller, $viewName) {
		$fullSite = $controller->getUser()->getAttribute('fullSite');

		if (sfConfig::get('app_domain_isMobile') && !$fullSite) {
	    	$controller->setLayout("mobile");
	    	$controller->setTemplate($viewName, "mobile");
		}
	}
	
	public static function getClientIP() {
		$ip = $_SERVER['REMOTE_ADDR'];
		return $ip;
	}

	public static function createDataURI($imgPath, $fileType){
		$fileContent = file_get_contents($imgPath);
		
		$encoding = base64_encode($fileContent);
		return ('data:' . $fileType . ';base64,' . $encoding);
	}
	
	
	public static function prepareBackButton($ctg, $parentCtg=null) {
	   	$backRow = array();
	   	
	   	if ($parentCtg){
			$backRow['parentTitle'] = $parentCtg->getName();
			$backRow['parentUrl'] = $parentCtg->getUrl();
	   	} else{
			$backRow['parentTitle'] = 'Home';
			$backRow['parentUrl'] = '/';
		}
		$backRow['iconClass'] = 'ctgIcon ctgIcon_' . $ctg->getRootCategoryId();
		return $backRow;
	}
	
	public static function makeBirthdayFromStr($strBirthdate) {
		$birthdate = '';
		if ($strBirthdate){
			$parts 	= explode("/", $strBirthdate);
			$month 	= $parts[0];
			$day 	= $parts[1];
			$year 	= date('Y');
			$birthdate = "$year-$month-$day 13:00:00";
		}
		
		return $birthdate;
	}
	
 	public static function slugify($text){
	    // replace all non letters or digits by -
	    $text = preg_replace('/\W+/', '-', $text);
	 
	    // trim and lowercase
	    $text = strtolower(trim($text, '-'));
    return $text;
  }
  
  const CSV_SEP = "|";
  public static function generateCSVLine($competitor1, $competitor2, $date, $time, $timezone, $location, $endHour, $desc) {
  	
  	//$desc = str_replace("'", "\\'", $desc);
  	
  	$csvLine = 	$competitor1 . self::CSV_SEP .
  				$competitor2 . self::CSV_SEP .
  				$date		 . self::CSV_SEP .
  				$time		 . self::CSV_SEP .
  				$timezone	 . self::CSV_SEP .
  				$location	 . self::CSV_SEP .
  				$endHour 	 . self::CSV_SEP .
  				$desc;
  	return $csvLine; 
  }
  
  public static function isIPBlackListed($ip) {
  	$blacklist = array(	"109.230.216.225",
  					   	"58.210.6.50",
  					   	"208.166.55.181",
  					   	"92.96.203.84",
  						"109.230.216.60",
  						"86.96.226.21",
  						"193.27.47.253",
  						"79.141.8.42",
  						"98.247.132.54",
  						"46.137.117.214",
  						"119.46.154.247",
  						"173.15.50.1",
  						"46.51.224.252",
  						"174.78.141.227",
  						"178.63.26.42",
  						"111.68.229.149",
  						"187.174.193.242"
  	
  				 );
  						
	return (in_array($ip, $blacklist));   						
  						
  }
  
	public static function nl2brReplace($string) {
		$string = str_replace(array("\r\n", "\r", "\n", '\r\n', '\r','\n'), "<br />", $string);
		return $string;  
	}

    // This is for making a Doctrine_Collection empty Object to NULL
    public static function ifd($o) {
        return ($o->count())? $o : null;
    }

}




?>