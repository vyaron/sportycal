<?php

require_once('AbstractSpider.php');

define('CSV_SEP', '|');

class ESPNSpider extends AbstractSpider {
	private $teamId;
	private $teamName;
	private $ctgName;
	
	public function __construct($ctgName, $sqlFileCsv=null, $sqlFileSql=null) {
		parent::__construct($sqlFileCsv, $sqlFileSql);
		$this->ctgName = $ctgName;
		//$this->team = $team;
	}
	
	public function save(){
		$teamNameClean = str_replace(" ", "-", $this->teamName);
		$fname = sfConfig::get('app_spider_outputDir') ."espn/{$this->ctgName}/$teamNameClean.csv";
				
		$fh = fopen($fname, 'w') or die("can't open file: $fname");
		
		$outLines = self::getOutputCsv();

		foreach ($outLines as $outLine){
			fwrite($fh, $outLine . "\n");
		}

		fclose($fh);
		
		$this->cleanOutputCsv();
		
		//Retry one more time
		if (count($outLines) === 0) {
			sleep(5);
			$this->getTeam($this->teamId, $this->teamName);
		}
	}
	
	public function getTeams(){
		$url = 'http://espn.go.com/' . $this->ctgName . '/teams';
		
		$webpage = $this->grabUrl($url);
		$webpage = $this->cleanWhiteSpaces($webpage);
		
		$regex = "/team\/_\/id\/([^\/]+).+?>([^<]+)/";

		if (preg_match_all($regex,$webpage,$matches)) {
			return $matches;
		}
	}
	
	public function getTeam($teamId, $teamName = ''){
		$this->teamId = $teamId;
		$this->teamName = $teamName;
		
		// The "-2 days" is needed for games that are on the same day that we ran the script,
		// as we dont want them to become next year by mistake
		$currMillis = strtotime("-2 days");
		//$d = date('d/m/Y', $currMillis);
		//Utils::pa($d);
		
		
		// SAMPLE URL: http://espn.go.com/mens-college-basketball/team/schedule/_/id/399
		//$url = 'http://espn.go.com/' . $this->ctgName . '/team/_/id/' . $teamId;
		$url = 'http://espn.go.com/' . $this->ctgName . '/team/schedule/_/id/' . $teamId;
		
		$webpage = $this->grabUrl($url);

		$webpage = $this->cleanWhiteSpaces($webpage);
		//Utils::pp($webpage);
		
		$regexLines = '/(oddrow|evenrow).+?<\/tr/';
		if (preg_match_all($regexLines,$webpage,$lines)) {
			//echo '<pre>';
			//var_dump($lines);
			//echo '</pre>';
			if (isset($lines[0])){
				foreach ($lines[0] as $line){
					$regex = "/td>([^<]+).+?game-status.+?(@|vs).+?team-name.+?>(.*?)<\/li.+?td.+?>(.*?)<\/td.+?a.+?href=\"([^\"]+).+?Tickets/";
					if (preg_match($regex,$line,$matche)) {
						/*
						 At this point:
						 $matche[1] DATE (format: Thu, Feb 23).
						 $matche[2] OPPONENT (format: vs || @).
						 $matche[3] Team2 Link.
						 $matche[4] html contain time and TBD(format: 7:00 PM ET) Maybe broadcast. 
						 $matche[5] Tickets link.
						 */
						
						//echo '<pre>';
						//var_dump($matches);
						//echo '</pre>';
						
						$isValid = true;
						
						$broadcast = null;
						$regexTime = '/(([\d]+:[\d]+\s[a-zA-Z]+)\s([a-zA-Z]+)|TBD)+(.+)?/';
						if (preg_match($regexTime, $matche[4], $matcheTime)){
							/*
							 * $matcheTime[1] - 12:30 PM ET OR TBD
							 * $matcheTime[2] - 12:30 PM OR ''
							 * $matcheTime[3] - ET OR ''
							 * $matcheTime[4] - rest OR ''
							 */
							$allTimeStr = trim($matcheTime[1]);
							$time = trim($matcheTime[2]);
							$timeZone = trim($matcheTime[3]);
							$broadcast = trim($matcheTime[4]);
							
							if ($allTimeStr == 'TBD'){
								$time 		= '';
								$timeZone 	= '';
							} else if ($time){
								$time 		= date('H:i', strtotime($time));;
								$timeZone 	= ($timeZone == 'Half') ? '' : $timeZone;
							} else {
								$isValid = false;
							}
						} else {
							$isValid = false;
						}
						
						//Utils::pp($matcheTime);
						
						if (!$isValid) continue;
						
						$broadcastLinks = array();
						if ($broadcast){
							//broadcast links
							$broadcastLinksRegex = "/href=\"([^\"]+).+?alt=\"([^\"]+)/";
							preg_match_all($broadcastLinksRegex, $broadcast, $broadcastLinks);
							
							//broadcast without links
							$broadcastStr = trim(strip_tags($broadcast));
							if ($broadcastStr){
								$broadcastLinks[0][] = null;
								$broadcastLinks[1][] = null;
								$broadcastLinks[2][] = $broadcastStr;
							}
						}
						
						/*
						$broadcastLinks = array();
						$haveBroadcastLinks = strpos($matche[4], '<');
						if ($haveBroadcastLinks > 0) {
							$time = substr($matche[4], 0, $haveBroadcastLinks);
							$broadcastLinksStr = substr($matche[4], $haveBroadcastLinks);
							
							$broadcastLinksRegex = "/href=\"([^\"]+).+?alt=\"([^\"]+)/";
							preg_match_all($broadcastLinksRegex, $broadcastLinksStr, $broadcastLinks);
						} else {
							$time = $matche[4];
						}
		
						$time = trim($time);
						$timeEx = explode(' ', $time);
						
						$broadcastName = null;
						if (count($timeEx) > 3) {
							for ($j = 3; $j < count($timeEx); $j++) {
								$broadcastName .= ' ' . $timeEx[$j];
							}
							
							$broadcastName = substr($broadcastName, 1);
							
							$broadcastLinks[0][] = null;
							$broadcastLinks[1][] = null;
							$broadcastLinks[2][] = $broadcastName;
						}
						
						if (count($timeEx) == 1 && $timeEx[0] == "TBD") {
							$time 		= '';					
							$timeZone 	= '';
						} elseif (count($timeEx) < 3) {
							// This will ignore PAST games that has no time - but game score
							continue;
						} else {
							//Utils::pp($timeEx);
							$time = strtotime($timeEx[0] . ' ' . $timeEx[1]);
							$time = date('H:i', $time);

							$timeZone = $timeEx[2];
							if ($timeZone == 'ET') $timeZone = 'EST';
							
							// Weird thing that happen once for a game that was in progress
							if ($timeZone == 'Half') $timeZone = 'EST';
						}
						*/
						
						$millis = strtotime($matche[1]);
		
						// We should use year+1 here
						if ($millis < $currMillis) $millis = strtotime($matche[1]. " " .  (1+date("Y")));
						
						$date = date('d/m/Y', $millis);

						$team1 = $teamName;
						$team2 = strip_tags($matche[3]);
						
						$location = '';
						if (strrpos($team2,'*')){
							$team2 = str_replace('*', '', $team2);
							$location = '*';
						}
						
						$location = trim(Utils::iff($location,$matche[2]) .'');
						if ($location == '*'){
							$location = 'Neutral';
						} else if ($location == 'vs'){
							$location = 'Home';
						} else if ($location == '@'){
							$location = 'Away';
						}
						
						if ($location == 'Home') {
							$homeTeam = $team1;
							$awayTeam = $team2;
						} else {
							$homeTeam = $team2;
							$awayTeam = $team1;
						}
						
						// Description
						$desc = '';
						if (count($broadcastLinks)) {
							$desc .= 'Broadcasts:' . '\n';					
							for ($j = 0; $j < count($broadcastLinks[0]); $j++) {				
								$desc .= $broadcastLinks[2][$j] . ($broadcastLinks[1][$j] ? ' - ' . $broadcastLinks[1][$j] : '')  . '\n';
							}					
						}				 
						
						// Yaron: removed the tickets for now, as the Page was changed, and reg-ex no longer valid
						//$desc .= "Tickets - " . $matche[5];
			
						$csvLine = $awayTeam . CSV_SEP . $homeTeam . CSV_SEP . $date  . CSV_SEP . $time  . CSV_SEP . $timeZone . CSV_SEP . $location . CSV_SEP . CSV_SEP . $desc;
						$this->addOutputCsv($csvLine);
					}
				}
			}
		}
		
		sleep(rand(1,4));
	}
}
?>