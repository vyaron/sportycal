<?php

/**
 * Cal
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    evento
 * @subpackage model
 * @author     Yaron Biton
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Cal extends BaseCal
{
	const TYPE_GOOGLE 	= 'google';
	const TYPE_OUTLOOK 	= 'outlook';
	const TYPE_MOBILE 	= 'mobile';
	const TYPE_ANY 		= 'any';
	const TYPE_HARDCOPY = 'hardcopy';
	
	public function getDescriptionForCal($userCal, $partner, $calType) {
	
		//Utils::pp($partner);
		if ($partner && !$this->isBirthdayCal()) {
			$desc = $partner->getDesriptionForCal($this->getId());
		} else {
			$desc = $this->getDescription();
			$desc .= $this->getLinksAsHtml($userCal);
		}
	
		if (false && !$partner) {
			// Currentrly - this is for tickets:
			$globalPartnerDesc = PartnerDesc::getDescForCal($this, $calType);
			$desc .= $globalPartnerDesc;
		}
	
		
		
		if (!$partner || $partner->allowsSportycalLogo()) {
			$url = "http://sportYcal.com";
			if (Cal::isHtmlSupported($calType)) {
				$url = "<a target='_blank' href='http://www.sportYcal.com' alt='sportycal.com' title='Find more on sportYcal.com' >sportYcal.com</a>";
			}
	
			$desc .= "\n\nCalendar provided by: $url";
	
			if (Cal::isHtmlSupported($calType)) {
				$desc .= "\n<a href='http://www.sportycal.com'><img src='".GeneralUtils::DOMAIN."/images/layout/logo.gif' alt='sportYcal.com' title='Find more on sportYcal.com' /></a>";
			}
		} else if ($partner && !$partner->isWhiteLabel()){
			if (Cal::isHtmlSupported($calType)) {
				$desc .= "<br/><br/>Powered by <a href='http://www.sportYcal.com' target='_blank'>sportYcal.com</a><br/>";
			} else {
				$desc .= "\n\nPowered by sportYcal.com";
			}
		} 
	
		return $desc;
	}
	
	
	
	
    public function getTheImagePath() {
        $path = $this->getImagePath();
        if (!$path) $path = "/images/layout/cal.png";
        else $path = "/uploads/cals/".$path;
        
        return $path;
    }
    
    public function getNumEvents() {
        $events = $this->getEvents();        
        return count($events);
    }
    public function getDateRange() {
        $minTime = 9999999999999;
        $maxTime = 0;

        $events = $this->getEvents();        
        
        //Utils::pp($events);
        
        foreach($events as $event) {
        	
            if (GeneralUtils::isDate($event->getStartsAt()) && $event->getStartsAt() < $minTime) $minTime = $event->getStartsAt();
            if (GeneralUtils::isDate($event->getEndsAt()) && $event->getEndsAt()   > $maxTime) $maxTime = $event->getEndsAt();
        }
        
        if ($maxTime && $minTime > 0) {
            $maxDate = format_date($maxTime, 'p');
            $minDate = format_date($minTime, 'p');
            $result = "$minDate - $maxDate";
        } else {
        	//$result = "N/A";	
        	$result = "";
        }
        
        return $result;
    }
    public function getByUserName() {

        $srcName = "sportYcal";
        
        $partnerId = $this->getPartnerId();
        if ($partnerId) {
        	$partner = $this->getPartner();
        	$srcName = $partner->getName();
        }
        return $srcName;
    }

    public function getEventsForIcal($calType, $partner=null, $tags=null, $intelLabel=null, $intelValue=null, $remider=null, $remiderMsg = 'Are you ready for the game?') {
    	$userCal = null;
    	
    	$calEvents 		= $this->getEvents();
    	$extraEvents 	= Event::getExtraEvents($this, $userCal, $calType);
    	$meregedEventes = array_merge($calEvents, $extraEvents);
    	
    	$filtedEvents = array();
    	if (!is_null($tags)){
    		foreach ($meregedEventes as $i => $event){
    			$eventTags = $event->getTags();
    			
    			if (!is_null($eventTags)) $eventTags = json_decode($eventTags);
    			if (!is_null($eventTags)) {
    				//TODO: replace 3 blocks with 1 foreach - support custom filter by user
    				if (property_exists($tags, 'countryCodes') && is_array($tags->countryCodes)){

    					if (property_exists($eventTags, 'countryCode') && is_array($eventTags->countryCode)){
    						$exist = false;
    						
    						foreach ($eventTags->countryCode as $countryCode){
    							if (in_array($countryCode, $tags->countryCodes)) {
    								$exist = true;
    								break;
    							}
    						}
    							
    						if (!$exist) continue;
    					}
    				}
    				
    				if (property_exists($tags, 'languageCodes') && is_array($tags->languageCodes)){
    					if (property_exists($eventTags, 'languageCode') && is_array($eventTags->languageCode)){
    						$exist = false;
    						
    						foreach ($eventTags->languageCode as $languageCode){
    							if (in_array($languageCode, $tags->languageCodes)) {
    								$exist = true;
    								break;
    							}
    						}
    					
    						if (!$exist) continue;
    					}
    				}
    				
    				if (property_exists($tags, 'CIDS') && is_array($tags->CIDS)){
    					if (property_exists($eventTags, 'CIDS') && is_array($eventTags->CIDS)){
    						$exist = false;
    				
    						foreach ($eventTags->CIDS as $CIDS){
    							if (in_array($CIDS, $tags->CIDS)) {
    								$exist = true;
    								break;
    							}
    						}
    							
    						if (!$exist) continue;
    					}
    				}
    			}
    			
    			$filtedEvents[] = $event;
    		}
    	} else {
    		$filtedEvents = $meregedEventes;
    	}
    	
    	$events = array();
    	foreach ($filtedEvents as $event){
    		$flatEvent = array();
    	
    		$flatEvent['id'] = $event->getIdForIcal();
    		$flatEvent['event_id'] = $flatEvent['id'];
    		$flatEvent['start_date'] = $event->getStartsAtForCal();
    		$flatEvent['end_date'] = $event->getEndsAtForCal();
    		$flatEvent['text'] = $event->getName() ? GeneralUtils::icalEscape($event->getName()) : '';
    		
    		$details = $event->getDescriptionForCal($this, $userCal, $partner, $calType, $intelLabel, $intelValue, null);
    		$flatEvent['details'] = $details ? GeneralUtils::icalEscape2($details) : '';
    		
    		$flatEvent['location'] = $event->getLocation() ? GeneralUtils::icalEscape($event->getLocation()) : '';
    		
    		$flatEvent['rec_type'] = $event->getRecType() ? $event->getRecType() : '';
    		$flatEvent['event_length'] = $event->getLength() ? $event->getLength() : '';
    		$flatEvent['event_pid'] = $event->getPid() ? $event->getPid() : 0;
    	
    		if ($event->hasHour() && $remider){
    			$flatEvent['reminder'] = $remider;
    			$flatEvent['reminder_msg'] = $remiderMsg;
    		}

    		if ($this->isBirthdayCal()) $flatEvent['rec_type'] = 'year_1___#no';
    		if ($this->isBirthdayCal() || ($flatEvent['end_date'] == '0000-00-00 00:00:00')) $flatEvent['end_date'] = '9999-01-01 00:00:00';
    	
    		$events[] = $flatEvent;
    	}

    	return $events;
    }
    
    private $cachedEvents;
    private $birthdayEvents;
    public function getEvents() {
    	
    	if ($this->isAggregated()) {
    		return $this->aggregatedEvents;	
    	}
    	if ($this->isBirthdayCal()) {
    		return $this->birthdayEvents;	
    	}
    	
    	//Utils::pp("NOT AGG");
    	
    	if (!isset($this->cachedEvents)) {
    		$this->cachedEvents = EventTable::getEvents($this->getId());
    		
    		
    	}
        return $this->cachedEvents;
    }
    
    public function hasHours() {
        $events = $this->getEvents();        
        foreach($events as $event) {
            if ($event->hasHour()) return true;
        }
        return false;
    }
    
    public function getTZ() {
        return false;
    }
 
/*
    Column Column Heading Description Example
    A1: Subject Appointment name Project Review Meeting
    B1: Start Date Date when appointment begins 12/14/2006
    C1: Start Time Time when appointment begins 8:30:00 AM
    D1: End Date Date when appointment ends 12/14/2006
    E1: End Time Time when appointment begins 9:00:00 AM
    F1: All day event All day even flag Use either TRUE or FALSE
    G1: Reminder on/off Reminder flag Use either TRUE or FALSE
    H1: Reminder Date Date for reminder to display 12/14/2006
    I1: Reminder Time Time for reminder to display 8:00:00 AM
    J1: Location Place of appointment Conference Room
    K1: Priority Priority flag Use Low, Normal, or High
    L1: Private Privacy flag Use either TRUE or FALSE
*/ 
    public function getAsCSV() {
        $events = $this->getEvents();
        
        $str = "Subject,Start Date,Start Time,End Date,End Time,All day event,Reminder on/off,Reminder Date,Reminder time,Location,Priority,Private\n";
        
        foreach ($events as $event) {
            $str .= $event->getName() . ",";
            $str .= $event->getDateForCSV() . ",";
            $str .= $event->getTimeForCSV() . ",";
            $str .=  ",";
            $str .=  ",";
            $str .= "TRUE" . ",";
            $str .= "TRUE" . ",";
            $str .= $event->getReminderDateForCSV() . ",";
            $str .= $event->getTimeForCSV() . ",";
            $str .= $event->getLocation() . ",";
            $str .= "Normal" . ",";
            $str .= "TRUE" . "\n";
        }
        
        return $str;

    }
    
    /*
    public function getRootCategory() {
        $ctg = $this->getCategory();
        $rootCtg = $ctg->getRootCategory();
        return $rootCtg;
    }*/

/*
    public function getHash() {
        return "xxx";
    }
    */

    public function getLinksAsJson($partnerId) {
    	$ctg = $this->getCategory();
    	$links = $ctg->getLinks();
    	$linksJson = CategoryLink::makeJSON($this->getId(), $partnerId, $links);
        return $linksJson;        
    }
    public function getLinksAsHtml($userCal) {
       	$ctg = $this->getCategory();
    	$links = $ctg->getLinks();
        $result = "\n";
        
        // user cal may be null (backward competabiliy..) so we use also the calId
        $userCalId = 0;
        if ($userCal) {
            $userCalId = $userCal->getId();
        }
        
        foreach ($links as $link) {
            $result .= "{$link->getTxt()} - \n" .
            	$link->getUrlToGive($this->getId(), $userCalId) . "\n\n";
        }
        return $result;        
    }
    
    const AGG_EXT = " - All Events";
	const MAX_NAME = 40;
	    
    public function getNameLimited() {
    	$name = $this->getName();

    	// Temporary
    	return $name;
    	$aggExt = __(self::AGG_EXT);
    	
    	$maxLength = self::MAX_NAME;
    	if (strlen($name) <= self::MAX_NAME) return $name;
    	
    	$allPos = strpos($name, $aggExt);
    	if ($allPos) {
    		$name = substr($name, 0, $allPos);
    		$maxLength = self::MAX_NAME - strlen($aggExt);
    	}
		$name = substr($name, 0, $maxLength);
    	$name .= "...";
    	if ($allPos) $name .= $aggExt;
    	
    	//Utils::pp("#".$name."#");
    	return $name;
    	
    }
    
    public function getNameFixed($ctg) {

    	$fixedName = '';
    	
    	$calName = $this->getName();
    	
    	if ($this->isBirthdayCal()) return $calName; 
    	
		$ctgName = $ctg->getName(ESC_RAW);

		$ctgName = str_replace("&#039;", "'", $ctgName);
		
		$tempCalName = strtolower($calName);
		$tempCtgName = strtolower($ctgName);
		
		if (strpos($tempCalName, $tempCtgName) === false) {
			$fixedName = $ctgName . " - " . $calName;
			
		} else {
			$fixedName = $calName;
		}
    	if ($this->getPartnerId()) 	return $fixedName;
		
		
		if (strpos($tempCalName, 'calendar') === false) {
			$fixedName .= ' Calendar';
		}
		
        return $fixedName;
    }
    
    private $aggregatedEvents;
    
	public function addAllEventsFrom($cal) {
		$events = $cal->getEvents();
		$this->addThoseEvents($events);
		//Utils::pa("Cal has ".count($events) . " Events");
		//$this->aggregatedEvents = array_merge($this->aggregatedEvents, $events);
		//Utils::pa($this->aggregatedEvents);  
	}
	public function addThoseEvents($events) {
		if (!isset($this->aggregatedEvents)) $this->aggregatedEvents = array();
		$this->aggregatedEvents = array_merge($this->aggregatedEvents, $events);
	}
	
	public function isAggregated() {
		return (isset($this->aggregatedEvents));
	}
	public function isBirthdayCal() {
		return (isset($this->birthdayEvents));
	}
	
	private function becomeAggregated($ctg, $cals, $includeEvents) {
 		//$this->setId(999999);
		$this->setName($ctg->getName() . __(self::AGG_EXT));
 		$this->setCategoryId($ctg->getId());
 		$this->setImagePath('cals.png');
 		$maxUpdatedAt = 0;

 		// TODO: need only count here, no need to load objects
 		//$subCtgs      = CategoryTable::getCategories($ctgId);
 		
 		//$i = 1;
 		
 		//Includ away games for ctg with cals - There are no ctgs including calendars and sub ctg
 		$calsCount = count($cals);
 		$includeAway = ($calsCount >= 1);
 		
 		if ($calsCount) {
	 		foreach ($cals as $cal) {
	 			if ($maxUpdatedAt < $cal->getUpdatedAt()) $maxUpdatedAt = $cal->getUpdatedAt();
	 		}
 		}
 		
 		$this->setUpdatedAt($maxUpdatedAt);

 		$events = array();
 		if ($includeEvents) {
		  	$underCtgIds = array($ctg->getId());
		  	
		  	$fromDate = date('Y-m-d 00:00:00');
		  	$events = EventTable::getBy(null, $underCtgIds, $fromDate, null, null, null, null, $includeAway);

		  	//$events = $events->getData();
 		}	  	
 		$this->addThoseEvents($events);
 		
 		//Utils::pp($events);
	}
	/*
	public function becomeAggregated2($ctg, $cals) {
 		//$this->setId(999999);
		$this->setName($ctg->getName() . " - All Events");
 		$this->setCategoryId($ctg->getId());
 		$this->setImagePath('cals.png');
 		$maxUpdatedAt = 0;
 		$i = 1;
 		$calsCount = count($cals);
 		foreach ($cals as $cal) {
 			//Utils::pa("Handling $i of $calsCount: " . $cal->getName());
 			$this->addAllEventsFrom($cal);
 			if ($maxUpdatedAt < $cal->getUpdatedAt()) $maxUpdatedAt = $cal->getUpdatedAt();
 			//Utils::pa("Done Handling $i of $calsCount: " . $cal->getName());
 			$i++;
 		}
 		$this->setUpdatedAt($maxUpdatedAt);
 		usort($this->aggregatedEvents, array('Event','cmpEventsByDate'));
 		//Utils::pa("Done");
	}*/
	
	public function getUrl() {
		if (!$this->isAggregated()) return 'cal/show?id=' . $this->getId() . '&n=' . Utils::slugify($this->getName()) . '-calendar';
		return 'cal/show?ctgId='.$this->getCategoryId() . '&n=' . Utils::slugify($this->getName()) . '-calendar';
	}

	public static function getAggregatedCal($ctg, $cals = null, $includeEvents=true) {
		if (!$cals) {
			$cals = $ctg->getCal();
			$cals = $cals->getData();
		}
		
		//Utils::pp($cals);
 		$aggCal = new Cal();
 		$aggCal->becomeAggregated($ctg, $cals, $includeEvents);
 		//Utils::pp($aggCal);
 		return $aggCal; 
	}

	public function getIcalUrl($calType, $partner=null, $intelCalType=null) {
		
		if (!$intelCalType) $intelCalType = $calType;
		
		$calSpecification = "id/".$this->getId();
		if ($this->isAggregated()) 	$calSpecification = 'ctgId/' . $this->getCategoryId();
		// As for the AJAX nature of birthday-cal creation
		//if ($this->isBirthdayCal()) $calSpecification = 'bc/' 	. $this->getByUserId();
		if ($this->isBirthdayCal()) $calSpecification = 'bc/' 	. "USERID";

		$partnerPart = '';
		if ($partner) $partnerPart = "/ref/{$partner->getHash()}";
		
		if ($calType == Cal::TYPE_HARDCOPY) {
			$url = "/cal/hardcopy/" . $calSpecification;
		} else if ($calType == Cal::TYPE_GOOGLE || ($calType == Cal::TYPE_MOBILE && Utils::clientIsAndroid())) {
			$url = "http://www.google.com/calendar/render?cid=" .
			        urlencode(GeneralUtils::DOMAIN . '/cal/get/'.$calSpecification."/hash/USERCAL/ct/$intelCalType".$partnerPart);
			
		} else if($calType == Cal::TYPE_ANY){
			$url = "http://".GeneralUtils::DOMAIN_SHORT . 
				   "/cal/get/".$calSpecification. "/hash/USERCAL/ct/$intelCalType$partnerPart/sportycal.ics";
		} else {
			$url = "webcal://".GeneralUtils::DOMAIN_SHORT . 
				   "/cal/get/".$calSpecification. "/hash/USERCAL/ct/$intelCalType$partnerPart/sportycal.ics";
			
		}		
		return $url;

	}

	public function getKeywords() {
		$keywords  = $this->getName() . ",";
		$keywords .= $this->getName() . " Calendar,";
		$keywords .= $this->getName() . " Schedule";

		return $keywords;
	}

 	public function hasEventsDescription() {
        $events = $this->getEvents();        
        foreach($events as $event) {
            if ($event->getDescriptionForCal($this, null, null, Cal::TYPE_MOBILE, null, null)) return true;
        }
        return false;
    }

	public static function getBirthdayCal($user) {
 		$birthdayCal = new Cal();
 		$birthdayCal->becomeBirthdayCal($user);
 		//Utils::pp($birthdayCal);
 		return $birthdayCal; 
	}
	
	public static function getBirthdayFbUsers($userId){
		$q = Doctrine::getTable('FbUser')->createQuery('fbu');
    	$q->innerJoin("fbu.UserFbUser ufbu");
    	$q->where("ufbu.user_id = {$userId}");
    	$q->andWhere("ufbu.in_birthday_cal=true");
    	$q->orderBy("fbu.birthdate");
    	
		$fbUsers = $q->execute();
		
		return $fbUsers;
	}
	
	private function becomeBirthdayCal($user) {

		// needed for having a cal for using calDown
		if (!$user) {
			$this->birthdayEvents = array();
			return;
		}
		
 		//$this->setId(999999);
		$this->setName($user->getFullName() . " Birthday Calendar");
		$this->setByUserId($user->getId());
 		$this->setCategoryId(Category::CTG_BIRTHDAY);
 		//$this->setImagePath('cals.png');
 		$this->setUpdatedAt(date('Y-m-d'));
 		
 		/*
    	$q = Doctrine::getTable('FbUser')->createQuery('fbu');
    	$q->innerJoin("fbu.UserFbUser ufbu");
    	$q->where("ufbu.user_id = {$user->getId()}");
    	$q->andWhere("ufbu.in_birthday_cal=true");
    	$q->orderBy("fbu.birthdate");
    	
		$fbUsers = $q->execute();
		*/
 		$fbUsers = self::getBirthdayFbUsers($user->getId());
 		
		//Utils::pp($fbUsers);
		
		foreach ($fbUsers as $fbUser) {
			$e = new Event();
			$e->setName($fbUser->getFullName() . " Birthday");
			
			$strBirthdate = $fbUser->getBirthdate();
			$birthdate = Utils::makeBirthdayFromStr($strBirthdate);
						
			$e->setStartsAt($birthdate);
			$e->setEndsAt($birthdate);
	 		$e->setImagePath("https://graph.facebook.com/{$fbUser->getFbCode()}/picture");
			
			$events[] = $e;
		}
		
		$q = Doctrine::getTable('UserBirthday')->createQuery('ub');
    	$q->where("ub.user_id = {$user->getId()}");
    	$q->orderBy("ub.birthdate");
    	
    	$userExtraBirthdays = $q->execute();
		foreach ($userExtraBirthdays as $userExtraBirthday) {
			$e = new Event();
			$e->setName($userExtraBirthday->getFullName() . " Birthday");
			
			$strBirthdate = $userExtraBirthday->getBirthdate();
			$birthdate = Utils::makeBirthdayFromStr($strBirthdate);
			
			$e->setStartsAt($birthdate);
			$e->setEndsAt($birthdate);
			
			$events[] = $e;
		}
    	
		
		$this->birthdayEvents = $events;		
		
 		//$this->addThoseEvents($events);
 		
 		//Utils::pp($events);
 		
 		
	}
	
 	public static function isHtmlSupported($calType) {
 		//$partner = UserUtils::getPartner();
 		
 		//if ($calType == Cal::TYPE_GOOGLE && !($partner && $partner->isRingtonepartner())) return true;
 		return false;
 	}

 	/*
 	public function isUnderAnyOfSubCategories ($ctgIds) {
 		$ctgsIdsPath = $this->getCategoryIdsPath();
 		foreach ($ctgIds as $ctgId) {
 			if (Category::isCtgUnderSubCtgs($ctgsIdsPath, $ctgId)) return true;
 		}
		return false; 		
 	}*/
	
	function getCategoryIds() {
    	$ctgIdsPath = $this->getCategoryIdsPath();
        $ctgIds = explode(",", $ctgIdsPath);
    	return $ctgIds;
    }
 	
	function getRootCategory() {
		$ctg = null;
    	
		$ctgId = $this->getRootCategoryId();
		if ($ctgId) $ctg = Doctrine::getTable('Category')->find(array($ctgId));
		
    	return $ctg;
    }
 	
    function getRootCategoryId() {
        $ctgIds = $this->getCategoryIds();
    	return $ctgIds[0];
    }
    
    public function getNextEvent($flat=true){
    	$event = null;
    	
    	//Get event
    	$q = Doctrine_Query::create()
    	->from('Event e')
    	->where('e.starts_at >= NOW()')
    	->andWhere('e.starts_at < NOW() + INTERVAL 100 hour')
    	->limit(1)
    	->orderBy('e.starts_at');
			
    	if ($this->isAggregated()){
    		//Not consider AWAY/HOME
    		$ctgId = $this->getCategoryId();
    		$q->andWhere('e.cal_id IN (SELECT c.id FROM cal c WHERE category_id = ?)', $ctgId);
    	} else {
    		$q->andWhere('e.cal_id = ?', $this->getId());
    	}
    	
    	$eventObj = $q->execute()->getFirst();
    	
    	if ($eventObj && $flat) {
    		$event = new stdClass();
    		$event->name = $eventObj->getName();
    	
    		$date1 = new DateTime();
    		$date2 = new DateTime($eventObj->getStartsAt());
    		
    		$event->diff = $date1->diff($date2);
    	} else if (!$flat){
    		$event = $eventObj;
    	}
    	
    	return $event;
    }
    
    public function getFlatObj($getEvents = false, $imgUrl = "", $partner=null, $min=false){
    	$res = new stdClass();

    	$res->id = $this->getId();
    	$res->category_id = $this->getCategoryId();
    	$res->name = $this->getName();
    	$res->update_at = format_date($this->getUpdatedAt(), 'f');
    	$res->events_count = 0;
    	
    	if (!$min){
    		$res->rate = $this->getRate();
    		$res->img_url = $imgUrl;
    		$res->updated_at = $this->getUpdatedAt();
    		$res->mobile_url = $this->getIcalUrl(Cal::TYPE_MOBILE, $partner);
    		$res->outlook_url = $this->getIcalUrl(Cal::TYPE_OUTLOOK, $partner);
    		$res->google_url = $this->getIcalUrl(Cal::TYPE_GOOGLE, $partner);
    		$res->any_url = $this->getIcalUrl(Cal::TYPE_ANY, $partner);
    	}
    	
    	//TODO: in min mode - show past games count?
    	if ($getEvents || $this->isAggregated()){
    		$res->league_ended = false;
    		
    		$events = $this->getEvents();
  			if (!$events && $this->isAggregated()){
  				$res->league_ended = true;
  				$events = EventTable::getBy(null, array($this->getCategoryId()), null, null, null, 100, null, true);
  			}
    		
    		$res->events = array();
    		if ($getEvents){
	    		foreach ($events as $event){
	  				$flatEvent = $event->getFlatObj();
	  				$flatEvent->img_url = $imgUrl;
					$res->events[] = $flatEvent;
	  			}
    		}
  			
  			$res->events_count = count($events);
    	} else {
    		$res->events_count =  EventTable::getEvents($this->getId(), true);;
    	}

    	return $res;
    }

    public function isUnderCategoryId($checkCtgId) {
    	$ctgIdsPath = $this->getCategoryIdsPath();
    	$ctgIds = explode(",", $ctgIdsPath);
    	foreach ($ctgIds as $ctgId) {
    		if ($ctgId == $checkCtgId) return true;
    	}
    	return false;
    }
    
    public function haveFutureEvents(){
    	$res = false;
    	
    	$currDate = date('U');
    	
    	$events = self::getEvents();
    	foreach ($events as $event){
    		$startsAt = strtotime($event->getStartsAt());
    		if ($startsAt >= $currDate){
    			$res = true;
    			break;
    		}
    	}
    	
    	return $res;
    }
    
    public function isOwner($user){
    	$calUserId = $this->getByUserId();
    	
    	return  (($calUserId == null && UserUtils::getOrphanCalId() == $this->getId())
    		|| ($user && ($user->isMaster() || ($calUserId == $user->getId())))) ? true : false;
    }
    
    public function getEventsForScheduler(){
    	$events = array();
    	
    	$calEvents = $this->getEvents();
    	foreach ($calEvents as $event){
    		$flatEvent = array();
    		 
    		$flatEvent['id'] = $event->getId();
    		$flatEvent['event_id'] = $flatEvent['id'];
    		$flatEvent['start_date'] = $event->getStartsAt();
    		$flatEvent['end_date'] = $event->getEndsAt();
    		$flatEvent['text'] = $event->getName() ? $event->getName() : '';
    		$flatEvent['details'] = $event->getDescription() ? $event->getDescription() : '';
    		$flatEvent['location'] = $event->getLocation() ? $event->getLocation() : '';
    		$flatEvent['rec_type'] = $event->getRecType() ? $event->getRecType() : '';
    		$flatEvent['event_length'] = $event->getLength() ? $event->getLength() : '';
    		$flatEvent['event_pid'] = $event->getPid() ? $event->getPid() : 0;
    		 
    		if ($flatEvent['end_date'] == '0000-00-00 00:00:00') $flatEvent['end_date'] = '9999-01-01 00:00:00';
    		 
    		$events[] = $flatEvent;
    	}
    	 
    	return $events;
    }
    
    public function setAdoptive(User $user, $rootName="ROOT", $website=null){
    	if (!$this->getByUserId()){
    		$partner = $user->getPartner();
    		
    		if (!$partner){
    			//Create Partner
    			$partner = new Partner();
    			$partner->setName($rootName);
    			$partner->setHash($user->getId()); //TODO: replace with nice hash
    		
    			//TODO: add timezone ?
    			$partner->Save();
    		
    			//Create PartnerUser
    			$partnerUser = new PartnerUser();
    			$partnerUser->setPartnerId($partner->getId());
    			$partnerUser->setUserId($user->getId());
    			$partnerUser->save();
    		
    			if ($user->isSimple()){
    				$user->setType(User::TYPE_PARTNER);
    				$user->save();
    			}
    		}
    		 
    		$category = $partner->getRootCategory();
    		if (!$category){
    			//Create Category
    			$category = new Category();
    			$category->setName($rootName);
    			$category->setIsPublic(false);
    			$category->setPartnerId($partner->getId());
    			$category->setByUserId($user->getId());
    			$category->save();
    		
    			//Create PartnerDesc
    			$partnerDesc = new PartnerDesc();
    			$partnerDesc->setPartnerId($partner->getId());
    			$partnerDesc->setWebsite($website);
    			$partnerDesc->setCategoryId($category->getId());
    			$partnerDesc->setCalId($this->getId());
    			$partnerDesc->save();
    		}
    		 
    		//Set orphan cal parent
    		$this->setByUserId($user->getId());
    		$this->setIsPublic(true);
    		$this->setCategoryId($category->getId());
    		$this->setPartnerId($partner->getId());
    		$this->save();
    		
    		//clear from session
    		UserUtils::setOrphanCalId(null);
    	}
    }
}