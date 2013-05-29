<?php


class EventTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Event');
    }

    public static function getTeamEvents($team1, $team2, $baseDate){
    	
    	/*
    	 * select * 
			from event 
			where name like 'Arsenal%Everton%' AND
			      '2011-02-01' BETWEEN DATE_ADD(starts_at,INTERVAL -3 DAY) AND DATE_ADD(starts_at,INTERVAL 3 DAY)
    	 */
    	
    	$nameLike = "$team1%$team2%";
    	
    	$q = Doctrine::getTable('Event')->createQuery('e');
    	$q->where('e.name like :lik', array(':lik' => "$nameLike"));
    	$q->andWhere(':baseDate BETWEEN DATE_ADD(e.starts_at,INTERVAL -3 DAY) AND DATE_ADD(e.starts_at,INTERVAL 3 DAY)', array(':baseDate' => $baseDate));			

// 		echo $q->getSqlQuery(), "<br/>";
//		print_r($q->getParams());
//		die();    	
    	
    	$events = $q->execute();
    	//$events = $events->toArray();
		return $events;
    	
    }

    
    
    public static function getBy($txtSearch=null, $underCtgIds = null, $fromDate=null, $toDate=null, $nameLike=null, $limit=null, $locId=null, $includeAway=false, $calId=null){    	
    	$q = Doctrine::getTable('Event')->createQuery('e');
    	//$q->select('e, es');
    	$q->innerJoin("e.Cal cal");
    	$q->leftJoin("e.EventStat es");
    	$q->innerJoin("cal.Category c");

    	if ($locId)   	 {
	    	$q->innerJoin("c.Address a");
			$q->innerJoin("a.Location l");
    	}

		$subCtgInStrAndParams 	= Utils::getSubCtgInStrAndParams($underCtgIds);
		$subCtgInStr 			= $subCtgInStrAndParams[0];	  
		$subCtgParams 			= $subCtgInStrAndParams[1];

		$q->where('1=1');
		
		if ($calId)			$q->andWhere('e.cal_id = :id', array(':id' => (int)$calId));
		
		if ($txtSearch)  	$q->andWhere('(cal.name like :txt OR e.name like :txt)', array(':txt' => "%$txtSearch%"));
		
		if ($fromDate) 	 	$q->andWhere('e.starts_at >= :fromDate', array(':fromDate' => $fromDate));			
		if ($toDate && !$calId) 	 	$q->andWhere('e.ends_at <= :toDate', array(':toDate' => $toDate));
    	if ($subCtgParams) 	$q->andWhere($subCtgInStr, $subCtgParams);
    	if ($nameLike)   	$q->andWhere('e.name like :lik', array(':lik' => "$nameLike"));
    	
    	//otherwise we get both games..
    	if (!$includeAway)$q->andWhere('e.location != "Away"');	
    	
    	if ($locId)   	 {
    		$q->andWhere('l.id = :locId', array(':locId' => $locId));
    	}

    	// YARON: dont ask IS-PUBLIC! otherwise the subscription for aggregation does not work
    	//$q->andWhere('c.is_public = 1');
    	$q->andWhere('c.deleted_at  is null');
    	$q->andWhere('cal.deleted_at is null');
    	
    	if ($fromDate) $q->orderBy('e.starts_at, e.name');
    	else $q->orderBy('e.starts_at desc, e.name');
    	
    	if ($limit) $q->limit($limit);
    	else $q->limit(600);
    	
    	//if (!$locId) die($q->getSqlQuery());      
    	
    	/*
    	if (true || !$fromDate){
	    	echo $q->getSqlQuery(), "<br/>";
			print_r($q->getParams());
			die();
    	}*/
		
    	
    	$objs = $q->execute();
    	
    	
    	
    	// The location is not enough to remove duplicates, as sometimes there is an address there and not Home/Away
    	//$objs = $objs->getData();
    	$objs = self::removeDuplicates($objs);
    	//Utils::pp($objs);
		return $objs;
    }
    private static function removeDuplicates($sortedEvents){
    	$uniqueEvents = array();
    	if (!$sortedEvents) return $sortedEvents;
    	$prevEvent = new Event();
    	foreach ($sortedEvents as $event) {
    		$startDateDiff = date_diff(date_create($event->getStartsAt()), date_create($prevEvent->getStartsAt()));

    		$nameA = trim($event->getName());
    		$nameB = trim($prevEvent->getName());
    		if ($nameA == $nameB &&
    			($startDateDiff->y + $startDateDiff->m + $startDateDiff->d + $startDateDiff->h + $startDateDiff->i) == 0) {
				// Duplicate    				
    		} else {
    			$uniqueEvents[] = $event;	
    		}
    		$prevEvent = $event;
    	}
    	return $uniqueEvents;
    }
    public static function updateEventsAddress(){
		
    	echo "-- Handle Events with *NO* Teams <br/>";
		$sql = "update event e, category c, cal cl
				SET e.address_id = c.address_id
				where e.cal_id = cl.id AND
      					cl.category_id = c.id AND
         				e.name not like '% vs. %'";
		
		//$conn = Doctrine_Manager::getInstance()->getCurrentConnection();
		// $conn->execute($sql);
		
		echo $sql . "<br/><br/>";
		
		$events = self::getBy(null, null, null, null, '% vs. %', null);
    	
		echo "-- Handle Events WITH Teams <br/>";
		echo "-- Found " . count($events) . " (With Teams) To Handle <br/>"; 
		
		$count = 0;
		$countAddressed = 0;
		foreach ($events as $event) {
			$eventName	= $event->getName();
			$eventId	= $event->getId();
			$eventTeam1 = $event->getTeam(1);
			$eventTeam2 = $event->getTeam(2);
			$cal 	 	= $event->getCal();
			$calName 	= $cal->getName();
			$calId 		= $cal->getId();
			$ctg 		= $cal->getCategory();
			$ctgName 	= $ctg->getName();
			$ctgId 		= $ctg->getId();
			$addressId 	= null;
			
			// The second part of the condition should also handle casses like: 
			// Lithuania Euro 2012 Qualifying Calendar
			if ($eventTeam1 == $ctgName || strpos($calName, $eventTeam1) === 0) {
				$addressId = $ctg->getAddressId();
				//echo "-- Team 1 address ($eventTeam1) address: $addressId <br/>";
			}

			// Find the CTG for team1 - Look for EXACT MATCH under this CTG
			if (!$addressId) {
				$otherCtgs = CategoryTable::getCategories($ctg->getParentId(), $eventTeam1);
				if (count($otherCtgs) == 1) {
					$otherCtg  = $otherCtgs[0];
					$addressId = $otherCtg->getAddressId();
					//echo "FOUND address for: $eventTeam1 - EXACT MATCH <br/>";
				}
			}
			// Find the CTG for team1 - Look for LIKE MATCH under this CTG 
			if (!$addressId) {
				//echo "<br/>-- ** $eventName (Category: <a href='http://sportycal.com/category/$ctgId'>$ctgName</a> ; Calendar: <a href='http://sportycal.com/cal/$calId'>$calName</a>)<br/>";
				$otherCtgs = CategoryTable::getCategories($ctg->getParentId(), null, "$eventTeam1");
				if (count($otherCtgs) == 1) {
					$otherCtg  = $otherCtgs[0];
					$addressId = $otherCtg->getAddressId();
					//echo "-- FOUND address for: $eventTeam1 - LIKE, UNDER PARENT Category: <a href='http://sportycal.com/category/{$ctg->getParentId()}'>PARENT</a> <br/>";
				}
			}
			
			// Find the CTG for team1 - Look EXACT match under ALL CTG
			if (!$addressId) {
				$otherCtgs = CategoryTable::getCategories("ALL", null, "$eventTeam1");
				if (count($otherCtgs)) {
					$otherCtg  = $otherCtgs[0];
					$addressId = $otherCtg->getAddressId();
					//echo "-- FOUND address for: $eventTeam1 - LIKE, UNDER ANY Category: <a href='http://sportycal.com/category/{$otherCtg->getParentId()}'>{$otherCtg->getName()}</a> <br/>";
				} else {
					//echo " -- ########## Found more than one! <br/>";
				}
			}
			if ($addressId) {
				$event->setAddressId($addressId);
				
				//echo "UPDATE event SET address_id = $addressId WHERE id = {$event->getId()}; <br/>"; 
				//$event->save();
				$countAddressed++;
			} else {
				echo "-- ERROR: EVENT ID: $eventId (Category: <a href='http://sportycal.com/category/$ctgId'>$ctgName</a> ; Calendar: <a href='http://sportycal.com/cal/$calId'>$calName</a>) event: {$event->getName()}   <br/><br/>";
			}
						
			$count++;
			//if ($count == 40) break;
		}
		echo "-- Total events: $count found address for: $countAddressed of them <br/>" ;
		
		
//		$q->select('{u.*}')
//		  ->from('user u')
//		  ->addComponent('u', 'User');

//		$q->execute();
    }

  
    public function deleteBy($calId) {
    	$q = Doctrine_Query::create()
    			->delete('Event e')
    			->where('e.cal_id = ?', $calId);


		$affectedRows = $q->execute();
    }


    public static function getEvents($calId, $countOnly=false){
    	
    	$q = Doctrine_Query::create()
			->from('Event e')
			->leftJoin('e.EventStat es')
			->orderBy('e.starts_at, e.name, es.created_at');
			
		if ($calId) $q->where('e.cal_id = ?', $calId);	

		if ($countOnly) $q->select('e.id, COUNT(e.id) AS eventsCount');
		
    	/*
    	$q = Doctrine::getTable('Event')->createQuery('e');
		
		$q->where('1=1');
		if ($calId) $q->andWhere('e.cal_id = :calId', array(':calId' => $calId));
		
		
    	$q->orderBy('e.starts_at, e.name,es.created_at');
		*/
    	
    	//if (!$locId) die($q->getSqlQuery());      
    	//echo $q->getSqlQuery(), "<br/>";
		//print_r($q->getParams());
		//die();
    	

    	// If there are events in the Future - dont present past events
    	$events = $q->execute();

    	//Work only for regular cal
    	if ($countOnly){
    		$event = $events->getFirst();
    		
    		$eventsCount = 0;
    		if ($event->eventsCount) $eventsCount = (int)$event->eventsCount;
    		return $eventsCount;
    	}
    	
    	//$events = $q->fetchArray();
    	
    	$events = $events->getData();
    	$eventsCount = count($events);
		$yesterday = strtotime("yesterday");
		
		
		$hasRecEvent = false;
		foreach ($events as $event){
			if ($event->getRecType()) {
				$hasRecEvent = true;
				break;
			}
		}
		
		//Utils::pa($eventsCount);
    	if ($eventsCount && !$hasRecEvent) {
    		$lastEventTime = strtotime($events[$eventsCount-1]->getStartsAt());
    		// There are future events, remove all past events
    		if ($lastEventTime > $yesterday) {
				$futureEvents = array();
				foreach ($events as $event) {
					if (strtotime($event->getStartsAt()) < $yesterday) continue;
					$futureEvents[] = $event;				
				}
				$events = $futureEvents;    			
    		}
    	} 
    	
    	
    	// The location is not enough to remove duplicates, as sometimes there is an address there and not Home/Away
    	$events = self::removeDuplicates($events);
    	
		return $events;
    }
    
    //Get event by label filter: {"countryCodes":["IL"],"languageCodes":["he-IL"],"CIDS":["123"]}
	public static function filterByTags($events, $tags=null){
		$filtedEvents = array();
		
		//if (!is_null($tags)){
			foreach ($events as $i => $event){
				$exist = true; //flag - event tag value exist in label
				 
				//Get Event tags
				$eventTags = $event->getTags();
				if (!is_null($eventTags)) $eventTags = json_decode($eventTags, true);
				 
				if (is_null($tags)) $tags = array();
				if (!key_exists('CIDS', $tags)) $tags['CIDS'] = array();
				
				//Check event tags
				if (!is_null($eventTags)) {
					foreach ($tags as $key => $values){
						//If tag key from label filter exists in event tags AND the value is missing. The event can be sifted
						if (key_exists($key, $eventTags)){
							$exist = false;
								
							foreach ($eventTags[$key] as $value){
								if (in_array($value, $values)) {
									$exist = true;
									break;
								}
							}
								
							if (!$exist) break;
						}
					}
				}
				 
				if ($exist) $filtedEvents[] = $event;
			}
		//} else {
			//$filtedEvents = $events;
		//}
		
		return $filtedEvents;
	}
}
