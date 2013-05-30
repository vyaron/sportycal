<?php

class SportWiser{
	const NEW_LINE = "\n";
	const API_URL = 'http://api.sportwiser.com/Event/FindEventStats';
	const API_DEFAULT_PARAMS = '&StatisticType=All&languageCode=en-US&format=json&operatorId=7&token=ebe81840-5cde-4365-8f04-6aa197096a86';
	const API_EVENT_TYPE = '?EventType=';
	const API_HOME_TEAM = '&HomeParticipant=';
	const API_AWAY_TEAM = '&AwayParticipant=';
	const API_DATE = '&EventTime=';
	
	private $startAt = null;
	private $endAt = null;
	private $tookTime = null;
	
	private $eventsCount = 0;
	private $errorCount = 0;
	private $updateCount = 0;
	private $insertCount = 0;
	
	public function updateEventStat(){
		$this->startAt = time();
		
		
		$events = $this->getEvents();
		
		
		foreach ($events as $event){
			$this->getEventStat($event);
		}
		
		$this->endAt = time();
		
		$this->tookTime = $this->endAt - $this->startAt;
	}
	
	private function addErrorCount(){
		$this->errorCount += 1;
	}
	
	private function addUpdateCount(){
		$this->updateCount += 1;
	}
	
	private function addInsertCount(){
		$this->insertCount += 1;
	}
	
	private function getEvents(){
		$q = Doctrine_Query::create()
		//->limit(5)
		->select('e.*, c.category_ids_path AS category_ids_path')
		->from('Event e')
		->leftJoin('e.Cal c')
		->where('c.deleted_at IS NULL')
		->andWhere('e.starts_at between CURDATE() and DATE_ADD(CURDATE(), INTERVAL 7 DAY)')
		->andWhere('(c.category_ids_path LIKE ? OR c.category_ids_path LIKE ? )', array('3%','4%'))
		->orderBy('e.id');
	 
		$events = $q->fetchArray();
		
		$this->eventsCount = count($events);
		
		return $events;
	}
	
	private function getEventStatUrl($event){
		$url = self::API_URL;

		$path = $event['category_ids_path'];

		$type = '';
		if (strpos($path, '3') === 0) $type = 'Soccer';
		else if	(strpos($path, '4') === 0) $type = 'Basketball';
		//else if (strpos($path, '5') === 0) $type = 'Tennis';

		$url .= self::API_EVENT_TYPE . urlencode($type);

		$name = $event['name'];
		$nameParts = explode(' vs. ', $name);
		if (count($nameParts) === 2){
			$url .= self::API_HOME_TEAM . urlencode(trim($nameParts[0])) . self::API_AWAY_TEAM . urlencode(trim($nameParts[1]));
		} else {
			$url .= self::API_HOME_TEAM . urlencode($name);
		}

		$date = date('Y-m-d', strtotime($event['starts_at']));
		$url .= self::API_DATE . $date;

		$url .= self::API_DEFAULT_PARAMS;
		
		return $url;
	}
	
	private function getEventStat($event){

		$url = $this->getEventStatUrl($event);

		$content = @file_get_contents($url);
		//$content = 'http://api.sportwiser.com/Event/FindEventStats?EventType=Soccer&HomeParticipant=Barcelona&AwayParticipant=Malaga&EventTime=2012-01-21&StatisticType=All&languageCode=en-US&format=json&operatorId=7&token=ebe81840-5cde-4365-8f04-6aa197096a86';

		if ($content){
			if (!$error = $this->hasError($content, $url)){
				$evenStat = Doctrine::getTable('EventStat')->createQuery('e')->where('e.event_id = ?', $event['id'])->execute();
					
				if (!$evenStat->count()) {
					$evenStat = new EventStat();
					$this->addInsertCount();
				} else {
					$evenStat = $evenStat[0];
					$this->addUpdateCount();
				}
					
				$evenStat->setEventId($event['id']);
				$evenStat->setText($content);
				$evenStat->setCreatedAt(date('Y-m-d h:i:s'));
					
				$evenStat->save();
			}
		}
	}
	
	private function hasError($content, $url){
		$error = false;
		
		//$LOG_FILE = sfConfig::get('app_spider_outputDir') . '/sportwiser/eventStat.log';
		
		
		$content = json_decode($content, true);
		if ($content['ErrorCode']){
			$errorMsg = $content['ErrorCode'] . ':' . $content['ErrorMessage'];

			//$fh = fopen($LOG_FILE, 'a+') or die("can't open file: " . $LOG_FILE);
			//fwrite($fh, 'ERROR: ' . $errorMsg . ' -  URL: ' . $url . self::NEW_LINE);
			echo 'ERROR: ' . $errorMsg . ' -  URL: ' . $url . self::NEW_LINE;
			//fclose($fh);

			$error = true;
			$this->addErrorCount();
		}

		return $error;
	}
	
	public function __toString(){
		$str = '';
		$str .= '##########################' . self::NEW_LINE;
		$str .= $this->errorCount . ' Error founds' . self::NEW_LINE;
		$str .= $this->updateCount . ' Items Updated' . self::NEW_LINE;
		$str .= $this->insertCount . ' Items Inserted' . self::NEW_LINE;
		$str .= '--------------------------' . self::NEW_LINE;
		$str .= 'Found ' . $this->eventsCount . ' Events' . self::NEW_LINE;
		$str .= 'Start at: ' . date('Y-m-d h:i:s',$this->endAt) . self::NEW_LINE;
		$str .= 'End at: ' . date('Y-m-d h:i:s',$this->startAt) . self::NEW_LINE;
		$str .= 'It took: ' . round($this->tookTime/60) . ' Min' . self::NEW_LINE;
		$str .= '##########################' . self::NEW_LINE;
		return $str;
	}
}

?>