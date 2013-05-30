<?php
class UpdateWinnerCals extends UpdatePartnerCals {
	const PARTNER_ID = 1979;
	const PARTNER_CTG_ID = 2100;
	const CTG_SOCCER = 29;
	const CTG_BASKETBALL = 30;
	const CTG_BASEBALL = 39;
	const CTG_FOOTBALL = 36;
	
	const WINNER_CTGS_URL = 'work/winner/XML/sports_variants/sports.xml';
	//const WINNER_EVENTS_URL = 'http://www.winner.co.il/totoxml/totoxml.asmx/Winner';
	const WINNER_EVENTS_URL = 'http://www.winner.co.il/totoxml/totoxml.asmx/WinnerLinePresentData';

	private $approvedTypes = array(1,2,232);
	private $approvedSports2LeaguesCodes = array(
		29 => array(36, 37, 28, 104, 103, 100, 108, 164, 107, 105, 1233, 1234),
		30 => array(134, 211, 580),
		37 => array(1098),
		39 => array(1065),
		36 => array(692)
	);
	private $sportCode2sport;
	
	private $log = array('calName2EventsCount' => array(), 'importedGames' => array());
	
	private function getSportCode2ctg(){
		$xmlStr = file_get_contents(sfConfig::get('sf_root_dir') . self::WINNER_CTGS_URL);
		//$xmlStr = file_get_contents('/var/sportycal/lib/dataProviders/winner/sports.xml');
		$xml = new SimpleXMLElement($xmlStr);
		
		$ctgName2Ctg = array();
		$ctgNames = array();

		foreach ($xml->sports_row as $ctg) {
			$sportCode = $ctg->sport_code->__toString();
			if (!key_exists($sportCode, $this->approvedSports2LeaguesCodes)) continue;
			
			$name = $ctg->sport_name->__toString();
			
			$ord = null;
			$i = 0;
			foreach ($this->approvedSports2LeaguesCodes as $key => $value) {
				$i++;
				if ($sportCode == $key) {
					$ord = count($this->approvedSports2LeaguesCodes) - $i;
					break;
				}
			}
			if ($ord >= 0) $ctgNames[$ord+1] = $name;
			
			$ctgName2Ctg[$name] = $ctg;
		}

		//$ctgNames = array_keys($ctgName2Ctg);
		$ctgNames2ctg = $this->getCtgName2CtgMapForPartner(self::PARTNER_ID, self::PARTNER_CTG_ID, $ctgNames, true);
		
		$sportCode2ctg = array();
		foreach ($xml->sports_row as $ctg) {
			$name = $ctg->sport_name->__toString();
			$sportCode = $ctg->sport_code->__toString();
			
			if (array_key_exists($name, $ctgNames2ctg)) $sportCode2ctg[$sportCode] = $ctgNames2ctg[$name];
		}
		
		return $sportCode2ctg;
	}	
	
	private function haveTeamCtgs($sportCode){
		$ans = false;
		
		if ($sportCode == self::CTG_BASKETBALL || $sportCode == self::CTG_SOCCER || $sportCode== self::CTG_BASEBALL ||  $sportCode== self::CTG_FOOTBALL) $ans = true;
		
		return $ans;
	}
	
	private function cleanTeamName($name, $type){
		//Other type have different logic
		if (key_exists($type, $this->approvedTypes)){
			$patterns = array('/\(.+?\)/');
			$replacements = array('');
			$name = preg_replace($patterns, $replacements, $name);
			
			//remove &lrm;
			$name = str_replace(chr(0xE2).chr(0x80).chr(0x8E), '', $name);

			$name = trim($name);
		}
		
		return $name;
	}
	
	private function removeDuplicateGames($games = array()){
		foreach ($games as $i => $game){
			$type = $game->OptionCode->__toString();
			
			$gameDateA = $game->event_time->__toString();
			
			if ($type == 1) continue;
			
			$unicDate = true;
			foreach ($games as $j => $gameB){
				$gameB = $games[$j];
				
				$gameDateB = $gameB->event_time->__toString();
				
				if ($j != $i && $gameDateA == $gameDateB){
					$unicDate = false;
					break;
				}
			}
			
			if ($unicDate) continue;

			unset($games[$i]);
		}
		
		return $games;
	}
	
	private function setSubCtgs(){
		foreach ($this->sportCode2sport as $sportCode => $sport){
			//Have leagues ctgs
			if (count($sport->leagues)){
				$sportCtgId = $sport->ctg->getId();
				
				$leagueNames = array();
				foreach ($sport->leagues as $leagueName => $league){
					$leagueCode = $league->code;

					$ord = null;
					foreach ($this->approvedSports2LeaguesCodes[$sportCode] as $key => $value) {
						if ($leagueCode == $value) {
							$ord = count($this->approvedSports2LeaguesCodes[$sportCode]) - $key;
							break;
						}
					}
					
					if ($ord) $leagueNames[$ord] = $leagueName;
				}
				

				$leagueName2ctg = $this->getCtgName2CtgMapForPartner(self::PARTNER_ID, $sportCtgId, $leagueNames, true);
				
				foreach ($sport->leagues as $leagueName => $league) {
					if (array_key_exists($leagueName, $leagueName2ctg)){
						$league->ctg = $leagueName2ctg[$leagueName];
					}
				}
			}
		}
	}
	
	
	private function setEvents($cals){
		$now = date( 'Y-m-d H:i:s', time());
		
		$collectionEvent = new Doctrine_Collection('Event');
		foreach ($cals as $cal){
			Doctrine::getTable('Event')->deleteBy($cal->cal->getId());
		
			$cal->games = $this->removeDuplicateGames($cal->games);
	
			$this->log['calName2EventsCount'][$cal->cal->getName()] = count($cal->games);
			
			foreach ($cal->games as $game){
				//prepare Event Data
				$type = $game->OptionCode->__toString();
				$homeName = $this->cleanTeamName($game->homeTeamName->__toString(), $type);
				$guestName = $this->cleanTeamName($game->gestTeamName->__toString(), $type);
				
				$game->homeTeamName = $homeName;
				$game->gestTeamName = $guestName;
				
				$eventName = $homeName . ' - ' . $guestName;
				
				
				$isrTimezone = new DateTimeZone('Asia/Jerusalem');
				$gmtDateTime = new DateTime($game->event_time->__toString(), $isrTimezone);
				
				$gmtDateTime->setTimezone(new DateTimeZone('GMT'));
				$eventStarts = $gmtDateTime->format('Y-m-d H:i:s');
				
				$eventEnds = $eventStarts;
				
				$remarks = str_replace(chr(0xE2).chr(0x80).chr(0x8E), '', $game->eventRemarks);
				$remarks = trim($remarks);
				$game->eventRemarks = $remarks;

				$eventDesc = HebrewUtils::getDescForWinnerGame($game);
	
				$this->log['importedGames'][$eventName] = true;
				
				//set Event Data
				$event = new Event();
				$event->setCalId($cal->cal->getId());
				$event->setName($eventName);
				$event->setStartsAt($eventStarts);
				$event->setEndsAt($eventEnds);
				$event->setDescription($eventDesc);
				$event->setCreatedAt($now);
				$event->setUpdatedAt($now);
				
				$collectionEvent->add($event);
			}
		}
		
		$collectionEvent->save();
	}
	
	private function setCals(){
		$now = date( 'Y-m-d H:i:s', time());
		
		$collectionCategory = new Doctrine_Collection('Category');
		$collectionCal = new Doctrine_Collection('Cal');
		
		$cals = array();
		
		$calsCount = 0;
		
		//League Cals
		foreach ($this->sportCode2sport as $sportCode => $sport){
			//Have leagues cals
			$sportCalsCount = count($sport->cals);
			if ($sportCalsCount){
				$sportCtgId = $sport->ctg->getId();
				$calNames = array_keys($sport->cals);
				
				$leagueName2cal = $this->getCalName2CalMapForPartner(self::PARTNER_ID, $sportCtgId, $calNames, $sport->ctg);
				
				foreach ($sport->cals as $leagueName => $cal) {
					if (array_key_exists($leagueName, $leagueName2cal)){
						$cal->cal = $leagueName2cal[$leagueName];
						$cal->cal->setUpdatedAt($now);
						$collectionCal->add($cal->cal);
						$cals[] = $cal;
					}
				}
			}
			
			//Team Cals
			foreach ($sport->leagues as $leagueName => $league) {
				//Have teams cals
				$leagueCalsCount = count($league->cals);
				if (count($leagueCalsCount)){
					//TODO: merge old cals for count
					$sportCalsCount += $leagueCalsCount;
					
					$league->ctg->setCalsCount($leagueCalsCount);
					$collectionCategory->add($league->ctg);
					
					$leagueCtgId = $league->ctg->getId();
					$teamNames = array_keys($league->cals);
					$teamName2cal = $this->getCalName2CalMapForPartner(self::PARTNER_ID, $leagueCtgId, $teamNames, $league->ctg);
					
					foreach ($league->cals as $teamName => $cal) {
						if (array_key_exists($teamName, $teamName2cal)){
							$cal->cal = $teamName2cal[$teamName];
							$cal->cal->setUpdatedAt($now);
							$collectionCal->add($cal->cal);
							
							$cals[] = $cal;
						}
					}
				}
			}
			$calsCount += $sportCalsCount;

			$sport->ctg->setCalsCount($sportCalsCount);
			$collectionCategory->add($sport->ctg);
		}
		
		$collectionCategory->save();
		$collectionCal->save();
		$this->setEvents($cals);
		
		//Update Root CTG Cals count
		$partnerCtg = CategoryTable::getByIds(array(self::PARTNER_CTG_ID))->getFirst();
		$partnerCtg->setCalsCount($calsCount);
		$partnerCtg->save();
	}
	
	
	public function execute() {
		$sportCode2ctg = $this->getSportCode2ctg();

		$xmlStr = file_get_contents(self::WINNER_EVENTS_URL);
		//$xmlStr = file_get_contents(sfConfig::get('sf_root_dir') . '/work/winner/XML/all-events/winner.xml');
		
		$xml = new SimpleXMLElement($xmlStr);
		//Utils::pp($xmlStr);
		
		$this->sportCode2sport = array();
		foreach ($xml->Row as $game) {
			$type = $game->OptionCode->__toString();
			if (!in_array($type, $this->approvedTypes)) continue;
			
			$homeName = $this->cleanTeamName($game->homeTeamName->__toString(), $type);
			$guestName = $this->cleanTeamName($game->gestTeamName->__toString(), $type);
			$sportCode = $game->sportCode->__toString();
			$leagueCode = $game->leagueCode->__toString();
			
			$gameDate = $game->event_time->__toString();
			$timeStr = date('H:i', strtotime($gameDate));
			

			
			if (!$homeName || !$guestName
				|| !key_exists($sportCode, $sportCode2ctg) 
				|| !key_exists($sportCode, $this->approvedSports2LeaguesCodes) || !in_array($leagueCode, $this->approvedSports2LeaguesCodes[$sportCode])
				|| $timeStr == '06:50') continue;
			
			
			
			$leagueName = $game->leagueName->__toString();
			
			if (array_key_exists($sportCode, $this->sportCode2sport)){
				$sport = $this->sportCode2sport[$sportCode];
			} else {
				$sport = new stdClass();
				$sport->leagues = array();
				$sport->cals = array();
				
				//Set Sport CTG
				if (array_key_exists($sportCode, $sportCode2ctg)) $sport->ctg = $sportCode2ctg[$sportCode];
				
				$this->sportCode2sport[$sportCode] = $sport;
			}
			
			$haveTeamCtgs = $this->haveTeamCtgs($sportCode);
			if ($haveTeamCtgs){
				if (array_key_exists($leagueName, $sport->leagues)){
					$league = $sport->leagues[$leagueName];
				} else {
					$league = new stdClass();
					$league->cals = array();
					$league->code = $leagueCode;
					
					$sport->leagues[$leagueName] = $league;
				}

				//$homeName = $this->cleanTeamName($homeName, $type);
				//$guestName = $this->cleanTeamName($guestName, $type);
				
				$teams = array();
				$teams[] = $homeName;
				$teams[] = $guestName;
				
				foreach ($teams as $teamName){
					if (array_key_exists($teamName, $league->cals)){
						$cal = $league->cals[$teamName];
					} else {
						$cal = new stdClass();
						$cal->games = array();
						
						$league->cals[$teamName] = $cal;
					}
					
					$cal->games[] = $game;
				}
			} else {
				if (array_key_exists($leagueName, $sport->cals)){
					$cal = $sport->cals[$leagueName];
				} else {
					$cal = new stdClass();
					$cal->games = array();
					
					$sport->cals[$leagueName] = $cal;
				}
				
				$cal->games[] = $game;
			}
		}
		
		$this->setSubCtgs();
		$this->setCals();
		
		return $this->log;
	}


}