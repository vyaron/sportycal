<?php

require_once('AbstractSpider.php');

define('CSV_SEP', '|');

class NHLSpider extends AbstractSpider {
	private $url = "http://www.nhl.com/ice/schedulebyseason.htm";
	private $teamId;
	private $teamName;
	private $ctgName;
		
	
	public function __construct($ctgName = "ice", $sqlFileCsv=null, $sqlFileSql=null) {
		parent::__construct($sqlFileCsv, $sqlFileSql);
		$this->ctgName = $ctgName;
	}
	
	public function save(){
		$teamNameClean = str_replace(" ", "-", $this->teamName);
		$fname = sfConfig::get('app_spider_outputDir') ."nhl/$teamNameClean.csv";
	
		$fh = fopen($fname, 'w') or die("can't open file: $fname");
	
		$outLines = self::getOutputCsv();
	
		foreach ($outLines as $outLine){
			fwrite($fh, $outLine . "\n");
		}
	
		fclose($fh);
	
		$this->cleanOutputCsv();
	}
	

	/**
	 * private function getGames()
	 * Get all season games for NHL league
	 * 
	 * @return multitype:stdClass
	 */
	private function getGames() {
		$games = array();
		
		$webpage = $this->grabUrl($this->url);
		$webpage = $this->cleanWhiteSpaces($webpage);

	
		$teamNameRegex 	= '/teamName">([^\/"]+).+?>([^<]+)/';
		$dateRegex 		= '/skedStartDateSite">([^<]+)/';
		$timeRegex 		= '/skedStartTimeLocal">([^<]+)/';
	
		preg_match_all($dateRegex, $webpage, $dateMatches);
		preg_match_all($timeRegex, $webpage, $timeMatches);
	
	
		if (preg_match_all($teamNameRegex,$webpage,$matches)) {
			$teams = array();
			$c = 0;
			for ($i = 0; $i < count($matches[2]); $i++) {
				$c = $i == 0 ? $i : $i+$i ;
				if (empty($matches[2][$c])) break;
				$teams[] = $matches[2][$c] . "," . $matches[2][$c+1];
			}
	
			
			for ($i = 0; $i < count($teams); $i++) {
				$allTimeStr = explode(" ", $timeMatches[1][$i]);
				$teamsSpl = explode(",", $teams[$i]);
				
				// set time format to 24H
				$timePieces = explode(":", $allTimeStr[0]);
				if ($allTimeStr[1] == "PM") $timePieces[0] = $timePieces[0]+12;
				$time = $timePieces[0] . ":" . $timePieces[1];
				
				$o = new stdClass();
				$o->date 		= date('d/m/Y', strtotime($dateMatches[1][$i]));
				$o->time 		= $time;
				$o->timeZone 	= $allTimeStr[2];
				$o->visitTeam 	= $teamsSpl[0];
				$o->homeTeam 	= $teamsSpl[1];
				
				$games[] = $o;
			}
		}
		
		return $games;
	}

	/**
	 * public function getTeamsGames()
	 * returns an array of "{teamName}" => <teamGames>
	 * 
	 * @return Ambigous <multitype:, Array>
	 */
	public function getTeamsGames() {
		$games = $this->getGames();

		$teamsGames = array();
		foreach($games as $game){
			$teamsGames[$game->visitTeam][] = $game;
			$teamsGames[$game->homeTeam][] 	= $game;
		}

		return $teamsGames;		
	}

	/**
	 * public function saveTeam()
	 * saves team to CSV file
	 * 
	 * @param String $teamName
	 * @param Array $teamGames
	 */
	public function saveTeam($teamName, $teamGames) {
		$this->teamName = $teamName;
		foreach ($teamGames as $teamGame) {
			$position = "Away";
			if ($teamName == $teamGame->homeTeam) {
				$position = "Home";
			}
			$csvLine = 	$teamGame->visitTeam .CSV_SEP .
						$teamGame->homeTeam .CSV_SEP .
						$teamGame->date . CSV_SEP .
						$teamGame->time . CSV_SEP .
						$teamGame->timeZone . CSV_SEP .
						$position . CSV_SEP . CSV_SEP;
	
			$this->addOutputCsv($csvLine);
		}
		$this->save();
	}
	
}
