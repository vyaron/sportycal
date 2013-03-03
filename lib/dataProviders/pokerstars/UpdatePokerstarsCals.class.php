<?php

class UpdatePokerstarsCals extends UpdatePartnerCals {
	const PARTNER_ID = 777;
	const CTG_ID = 3000;
	const DB_TIME_FORMAT = 'Y-m-d H:i:s';
	
	private $calIdsToUrls = array(
		'4000' => 'http://www.pokerstars.com/datafeed/tournaments/regular.xml',
		'4001' => 'http://www.pokerstars.com/datafeed/tournaments/satellite.xml',
		'4002' => 'http://www.pokerstars.com/datafeed/tournaments/special.xml',
		'4003' => 'http://www.pokerstars.com/datafeed/tournaments/freeroll.xml'
	);
	
	public function execute(&$importedGames) {
		$calName2EventsCount = array();
		
		$calIdToName = $this->getCalIdToName();

		foreach ($this->calIdsToUrls as $calId => $url){
			$calName2EventsCount[$calIdToName[$calId]] = $this->parseCalEvents($calId, $url, $importedGames);
		}
		
		return $calName2EventsCount;
	}
	
	private function getCalIdToName(){
		$calObjs = Doctrine::getTable('Cal')
			->createQuery('c') 
			->whereIn('c.id', array_keys($this->calIdsToUrls))
			->execute();
			
		$calIdToName = array();	
		foreach ($calObjs as $cal){
			$calIdToName[$cal->getId()] = $cal->getName();
		}
		
		return $calIdToName;
	}
	
	private function parseCalEvents($calId, $url, &$importedGames){
		$eventsCount = 0;
		
		//Delete all events under calendar id
		Doctrine::getTable('Event')->deleteBy($calId);
		
		$now = date(self::DB_TIME_FORMAT, time());
		
		try {
			$xmlStr = file_get_contents($url);
			$xml = new SimpleXMLElement($xmlStr);
			
			$eventsCount = count($xml->tournament);
			
			foreach ($xml->tournament as $game){
				$eventName = trim($game->name);
				
				$eventTz = null;
				$eventStarts = null;
				
				if (trim($game->start_date)) {
					$gameDateTime = new DateTime(trim($game->start_date));
					$eventTz = $gameDateTime->format('e');
					$eventTz = GeneralUtils::getTZFromStr($eventTz);
					$eventStarts = $gameDateTime->format(self::DB_TIME_FORMAT);
				}
			
				
				// TODO: Add the Prize
				$eventDesc = null;
				if (trim($game->game)) 			$eventDesc .= "\n GAME: " . $game->game . ',';
				if (trim($game["prize"])) 		$eventDesc .= " PRIZE : " . $game["prize"] . ',';
				if (trim($game->buy_in_fee)) 	$eventDesc .= " BUY-IN: $" . $game->buy_in_amount . "";
				
				$eventDesc .= "\n\n" . $game->description . "\n";

				// Dont change this, Partner.class uses it to find the event-id for pokerstars URL
				$lineEventId = "GAME-ID : #" . $game["id"] . '#';
				$eventDesc .= $lineEventId;
				
				
				$event = new Event();
				$event->setCalId($calId);
				$event->setName($eventName);
				$event->setTz($eventTz);
				$event->setStartsAt($eventStarts);
				$event->setEndsAt($eventStarts);
				$event->setLocation("PokerStars.com");
				$event->setDescription($eventDesc);
				$event->setCreatedAt($now);
				$event->setUpdatedAt($now);

				$event->save();
				
				//set Events names for view
				$importedGames[] = $eventName;
			}
		} catch (Exception $e) {
			
		}
		
		return $eventsCount;
	}
}