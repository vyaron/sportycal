<?php

require_once('AbstractSpider.php');

define('CSV_SEP', '|');

class SoccernetSpider extends AbstractSpider {
	private $league = null;
	
	
	public function __construct($sqlFileCsv=null, $sqlFileSql=null) {
		parent::__construct($sqlFileCsv, $sqlFileSql);
	}
	                                                                                 
	public function save(){
		//$fName = SPORTYCAL_ROOT ."test/soccernet/{$this->league}.csv";
		//$fName = SPORTYCAL_ROOT ."test/soccernet/try.csv";
		//$fName = SPIDERS_OUTPUT_PATH ."soccernet/try.csv";
		$fname = SPIDERS_OUTPUT_PATH ."soccernet/{$this->league}.csv";
		
	    $fh = fopen($fname, 'a+') or die("can't open file: $fname");
		
		$outLines = self::getOutputCsv();

		foreach ($outLines as $outLine){
			fwrite($fh, $outLine . "\n");
		}

		fclose($fh);
	}
	
	/*
	 * Ran on:
		http://soccernet.espn.go.com/fixtures/_/league/gre.1/greek-super-league?cc=4716
		http://soccernet.espn.go.com/fixtures/_/league/tur.1/turkish-super-lig?cc=4716
		http://soccernet.espn.go.com/fixtures/_/league/rus.1/russian-premier-league?cc=4716
		http://soccernet.espn.go.com/fixtures/_/league/arg.1/argentina?cc=4716
		http://soccernet.espn.go.com/fixtures/_/league/bra.1/brazil?cc=4716
		http://soccernet.espn.go.com/fixtures/_/league/aus.1/australian-a-league?cc=4716
	 */
	
	public function spider($currDate,$league){
		$this->league = $league;
		
		$currDateURL = date('Ymd', $currDate);
		$currDateStr = date('l, F j, Y', $currDate);
		
		//Utils::pa($currDateStr);
		
		$url = "http://soccernet.espn.go.com/fixtures/_/date/$currDateURL/league/$league";
		
		$webpage = $this->grabUrl($url);
		$webpage = $this->cleanWhiteSpaces($webpage);
		
		$regex = "/class=\"stathead\".+?>($currDateStr).+?\/table/";
		
		if (preg_match_all($regex,$webpage,$matchesa)) {
			
			$regex = "/(oddrow|evenrow)+.+?td>([^<]+)<.+?\">([^<]+)<.+?\">([^<]+)<.+?\">([^<]+)<.+?\">([^<]+)/";
			if (preg_match_all($regex,$matchesa[0][0],$matches)) {
				$countMatches =  count($matches[2]);
				for($i = 0; $i < $countMatches; $i++) {
					/**
					 At this point:
					 $matches[2] Time (format: 15:30 GMT)
					 $matches[3] Team1
					 $matches[5] Team2
					 $matches[6] location
					 **/
					$time = trim($matches[2][$i]);
					
					$timeEx = explode(' ', $time);
					
					if ($time === 'TBD'){
						$time = '';
						$timeZone = '';
					} else if (count($timeEx) == 2){
						$time = $timeEx[0];
						$timeZone = $timeEx[1];
					} else {
						continue;
					}
					
					
					$homeTeam = $matches[3][$i];
					$awayTeam = $matches[5][$i];
					
					$date = date('d/m/Y', $currDate);
					
					$location = $matches[6][$i];
					
					//CSV: homeTeam, awayTeam, Date, Time, TimeZone, Location
					$csvLine = $homeTeam . CSV_SEP . $awayTeam  . CSV_SEP . $date  . CSV_SEP . $time  . CSV_SEP . $timeZone . CSV_SEP . $location;
					$this->addOutputCsv($csvLine);
				}
			} else {
				//Utils::pa($currDateStr . ' no items');
			}
		} else if (preg_match_all('/No matches scheduled/',$webpage,$matchesb)){
			//No more games
			return false;
		}
		
		return true;
	}
}
?>