<?php

require_once('AbstractSpider.php');
  	
class FoxSpider extends AbstractSpider {
	private $league;
	
	public function __construct($league, $sqlFileCsv=null, $sqlFileSql=null) {
		parent::__construct($sqlFileCsv, $sqlFileSql);
		$this->league = $league;
	}
	
	public function save(){
		$fname = $this->getFileName();
	
		$fh = fopen($fname, 'a+') or die("can't open file: $fname");
	
		$outLines = self::getOutputCsv();
	
		foreach ($outLines as $outLine){
			fwrite($fh, $outLine . "\n");
		}
		
		//fwrite($fh, '***********' . "\n");
		
		fclose($fh);
	
		$this->cleanOutputCsv();
	}
	
	public function cleanFile(){
		$fname = $this->getFileName();
		
		$fh = fopen($fname, 'w') or die("can't open file: $fname");
		fwrite($fh, '');
		fclose($fh);
	}
	
	public function getFileName(){
		$teamNameClean = str_replace(" ", "-", $this->league);
		$fname = sfConfig::get('app_spider_outputDir') ."foxsoccer/$teamNameClean.csv";
		
		return $fname;
	}
	
	// month: 1-12 year: 2011
	public function spider($month, $year) {

		$url = "http://msn.foxsports.com/foxsoccer/{$this->league}/fixtures?month=$month&year=$year";		
		$this->addOutput("\n\n-- Handling $month-$year : $url \n\n"); 

		$this->debugo("FOX: grabing the url: " . $url);
		$webpage = $this->grabUrl($url);
		$webpage = $this->cleanWhiteSpaces($webpage);
		
		/*$regex = "/(fsiFixturesRoundDate\">([^<]+)<.+?)?fsiFixturesGameStatus\">([^<]+)<.+?class=\"fsiLeagueDataLink\".+?>([^<]+)<.+?class=\"fsiLeagueDataLink\".+?>([^<]+)<.+?fsiFixturesStadiumName\">([^<]+)</";*/
		$regex = "/(fsiFixturesRoundDate\">([^<]+)<.+?)?(fsiFixturesGameStatus|fsiFixturesTournamentGameStatus)\">([^<]+)<.+?fsiFixturesTmName.+?>([^<]+)<.+?fsiFixturesTmName.+?>([^<]+)<.+?fsiFixturesStadiumName.+?>([^<]+)</";
		
		$this->debugo("FOX: Trying to Match with Regular Expression... ");
		
		if (preg_match_all($regex,$webpage,$matches)) {

			$countMatches =  count($matches[1]);
			$this->debugo("FOX: Found $countMatches Matches");
			
			$gameDate 	= null;
			$eDate 		= null;
			
			for($i = 0; $i < $countMatches; $i++) {
			
			
				//var_dump($matches);
				//die("DIED!");
							
				/**
				 At this point:
				 $matches[2] Date (only when date is changed we have this)
				 $matches[4] Time (format: Final or 7:45 AM ET)
				 $matches[5] Team1
				 $matches[6] Team2
				 $matches[7] Stadium
				 **/
				
				$strDate 			= $matches[2][$i];
				$strTime 			= $matches[4][$i];
				$team1		 		= $matches[5][$i];
				$team2		 		= $matches[6][$i];
				$stadium	 		= $matches[7][$i];
				
				$this->debugo("FOX: strDate:$strDate strTime:$strTime team1:$team1 team2:$team2 stadium:$stadium");

				// Changing Date
				if ($strDate) {
					$gameDate 		= date('d/m/Y', strtotime($strDate));
				    $pieces     =  explode("/", $gameDate);
	    			$eDate    	= $pieces[2] . "-" . $pieces[1] . "-" . $pieces[0];					
					$this->debugo("FOX: GAME DATE IS: $eDate");
					//die($eDate);
					
				}
				if (strstr($strTime, 'AM') === false && strstr($strTime, 'PM') === false) {
					$time =0;
				} else {
					$timeParts = explode(" ", $strTime);
					//pp($timeParts);
					if (count($timeParts) == 3) {
						$time = date('H:i', strtotime($timeParts[0] . " " . $timeParts[1]));	
					} else {
						$time = '';
					}
				}
				$this->debugo("FOX: GAME TIME IS: $time"); 
				
				if (!$gameDate) $this->debugo("ERROR: missing date it game: $team1 vs. $team2", true);

				$csvLine =  "$team1,$team2,$gameDate,$time,EST,$stadium,,";
				$this->addOutputCsv($csvLine);
				
				continue;

				if (defined("OUTPUT_SQL") ) {			
					$this->addOutputSql("-- $csvLine");
					
					$events = EventTable::getTeamEvents($team1, $team2, $eDate);
					
					//Utils::pp($events->toArray());
					
					$numEvents = count($events);
					if ($numEvents) {
						// Found events - 
						$this->addOutputSql("-- Found " . $numEvents . " Matching Events in DB");
						foreach ($events as $event) {
							
							//Utils::pa("event: " . $event->getStartsAt());
							
							$dt         	= new DateTime($event->getStartsAt());
     					   	$currD 			= $dt->format('Y-m-d');
     					   	$currT 			= $dt->format('H:i');
							
							//Utils::pa("new: " . $eDate);
							//Utils::pa("Comparing: currD:$currD with eDate:$eDate OR currT:$currT with time:$time");							
							if ($currD !==  $eDate || ($time && $currT !== $time)) {
								$out = Utils::getSqlForEventLine($csvLine, $event->getId(), null, null, true);
						    	if (strpos($out, "UPDATE")===0) {
						    		$this->addOutputSql($out);
						    	}
							} else {
								$this->addOutputSql("-- Event is ALREADY updated in DB");								
								$this->debugo("FOX: THIS GAME HAS NO CHANGE"); 							
							}
						}    
						
					} else {
						// TODO: Generate Inserts to inserts-log
						
						// TODO: need to know cal name and cal id
						//die("INSERT");
					    $out = Utils::getSqlForEventLine($csvLine);
					    if (strpos($out, "INSERT")===0) $this->addOutputSql($out);    
						
											
					}
					//$this->addOutput($outLine . "\n");
					
				}				
				if (defined("OUTPUT_CSV") ) {			
					$this->addOutputCsv($csvLine);
				} 
				
			}
			
		} else {
			$this->debugo("FOX: Found NO match");
		}
		sleep(3);
		
	}
	
}
?>