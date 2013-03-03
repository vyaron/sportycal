<?php

require_once('AbstractSpider.php');

define('CSV_SEP', '|');

class EspnOlympicsSpider extends AbstractSpider {
	private $calendars = array();
	
	
	public function __construct($sqlFileCsv=null, $sqlFileSql=null) {
		parent::__construct($sqlFileCsv, $sqlFileSql);
	}
	

	
	public function save(){

		foreach ($this->calendars as $name => $outLines){
			$replace="_";
			//$pattern="/([[:alnum:]_\.-]*)/";
			//$fname = str_replace(str_split(preg_replace($pattern,$replace,$name)),$replace,$name);
			$fname = $name;
			$fname = SPIDERS_OUTPUT_PATH ."espnolympics/$fname.csv";
			$fh = fopen($fname, 'a+') or die("can't open file $fname");
			
			foreach ($outLines as $outLine){
				fwrite($fh, $outLine . "\n");
			}
	
			fclose($fh);
		}
		
		$this->calendars = array();
	}
	
	
	public function spider($calendarName, $eventName, $url, $year=null){
		$webpage = $this->grabUrl($url);
		$webpage = $this->cleanWhiteSpaces($webpage);
		
		$wStart = strpos($webpage, 'id="strycnt"');
		
		
		if ($wStart) $webpage = substr($webpage, $wStart);
		else return;
		
		$wEnd = strpos($webpage, 'id="strybtm"');
		if ($wEnd) $webpage = substr($webpage, 0, $wEnd);
		
		if (!$year) $year = date("Y");
		
		$regex = "/<b\>([^<]+)<.+?BR\>(.+?)<\/p/";
		if( preg_match_all($regex, $webpage, $matches)){
			$countMatches =  count($matches[1]);
			for($i = 0; $i < $countMatches; $i++) {
				$name = $eventName . ' Day ' . ($i+1);
				$dateStr = trim($matches[1][$i]);
				$desc = trim($matches[2][$i]);
				
				$desc = str_replace("<BR>" , "\\n", $desc);
				$desc = str_replace("<br/>" , "\\n", $desc);
				
				//get date
				$dateStr .= ' ' . $year;
				$dateTime = strtotime($dateStr);
				$date = date('d/m/Y', $dateTime);
				
				$csvLine = Utils::generateCSVLine($name, null, $date, null, null, "London, UK", null, $desc);
				
				$this->calendars[$calendarName][] = $csvLine;
				
			}
		}
		
		self::save();
		
	}
}
?>