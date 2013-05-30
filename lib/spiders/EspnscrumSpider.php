<?php

require_once('AbstractSpider.php');

define('CSV_SEP', '|');

class EspnscrumSpider extends AbstractSpider {
	private $calendars = array();
	
	
	public function __construct($sqlFileCsv=null, $sqlFileSql=null) {
		parent::__construct($sqlFileCsv, $sqlFileSql);
	}
	

	
	public function save(){

		foreach ($this->calendars as $name => $outLines){
			$replace="_";
			$pattern="/([[:alnum:]_\.-]*)/";
			$fname = str_replace(str_split(preg_replace($pattern,$replace,$name)),$replace,$name);
			//$fname = trim($name);
			$fname = sfConfig::get('app_spider_outputDir') ."espnscrum/$fname.csv";
			$fh = fopen($fname, 'a+') or die("can't open file $fname");
			
			foreach ($outLines as $outLine){
				fwrite($fh, $outLine . "\n");
			}
	
			fclose($fh);
		}
		
		$this->calendars = array();
	}
	
	
	public function spider($url, $calendarName=null){
		
		$webpage = $this->grabUrl($url);
		$webpage = $this->cleanWhiteSpaces($webpage);
		
		$mothYearStrPos = array();
		
		$mothToStr = array();
		
		$regex = "fixtureTblColHdr.+?<a.+?\>([^<]+).+?<\/tr>";
		while(preg_match( '/^(.*?)'.$regex.'/', $webpage, $matches)){
			$mothYearStr = $matches[2];
			
			$startPos = strlen($matches[0]);
			
			$mothYearStrPos[$mothYearStr] = $startPos;
			
			$webpage = substr($webpage, $startPos);
			
			$mothToStr[$mothYearStr] = $webpage;
			if (count($mothToStr) > 1){
				$mothToStr[$lastMothYearStr] = substr($mothToStr[$lastMothYearStr], 0, $startPos);
			}
			
			$lastMothYearStr = $mothYearStr;
		}
		
		foreach ($mothToStr as $mothYear => $str){
			
			$mothYear = trim($mothYear);
			
			$mothYear = explode(' ', $mothYear);
			
			if (count($mothYear) != 2) continue;
			
			$month = $mothYear[0];
			$year = $mothYear[1];

			self::addToCalendars($month, $year, $str, $calendarName);
		}
		
		self::save();
		
	}
	
	private function addToCalendars($month, $year, $str, $cName){
		if ($cName) $regex = '/fixtureTableDate.+?\>([^<]+).+?<.+?<td.+?>(.+?)<\/td/';
		else $regex = '/fixtureTableDate.+?\>([^<]+)<\/td\>.+?fixtureTblContent.+?\>([^<]+)<\/td\>.+?fixtureTblContent.+?\>(.+?)<\/td\>/';
		
		if( preg_match_all( $regex, $str, $matches)){
			$countMatches =  count($matches[1]);
			for($i = 0; $i < $countMatches; $i++) {
				
				if ($cName){
					$dayCharAndNum = trim($matches[1][$i]);
					$calendarName = $cName;
					$teamsAndTime = trim($matches[2][$i]);
				} else {
					$dayCharAndNum = trim($matches[1][$i]);
					$calendarName = trim($matches[2][$i]);
					$teamsAndTime = trim($matches[3][$i]);
				}

				if (!$dayCharAndNum || !$calendarName || !$teamsAndTime) continue;
				
				//get date
				$dateStr = $dayCharAndNum . ' ' . $month . ' ' . $year;
				$dateTime = strtotime($dateStr);
				$date = date('d/m/Y', $dateTime);
				
				//get time
				$teamsAndTime = explode('<br/>', $teamsAndTime);
				if (count($teamsAndTime) == 2){
					$time = $teamsAndTime[1];
					
					$posGMT = strpos($time, 'GMT');
					if ($posGMT > 0){
						$time = substr($time, $posGMT - 6, 5);
						$timeZone = 'GMT';
					}
				} else {
					$time = '';
					$timeZone = '';
				}
				
				$teams = strip_tags($teamsAndTime[0]);

				//get teams
				$teams = explode(' v ', $teams);
				if (count($teams) != 2) continue;
				
				$homeTeam = $teams[0];
				
				
				$awayTeam = explode(', ', $teams[1]);
				
				if (isset($awayTeam[1])) $location = $awayTeam[1];
				else $location = '';
				
				$awayTeam = $awayTeam[0];
				
				$csvLine = $homeTeam . CSV_SEP . $awayTeam  . CSV_SEP . $date  . CSV_SEP . $time  . CSV_SEP . $timeZone . CSV_SEP . $location;
				
				$this->calendars[$calendarName][] = $csvLine;
			}
		}
	}
}
?>