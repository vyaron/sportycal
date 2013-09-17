<?php

/**
 * Event
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    evento
 * @subpackage model
 * @author     Yaron Biton
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Event extends BaseEvent
{
	const TYPE_PROMO_BIRTHDAY = "birthdayPromo";
	
	private static $gettingDescForAggregatedCal = null;
	private static $calDesc;
	
	public function getDescriptionForCal($cal, $userCal, $partner, $calType, $intelLabel, $intelValue, $ctg = null) {
		$isMasterOf = false;
		if ($ctg) $isMasterOf = UserUtils::userISMasterOf($ctg);

		
		// Boost Performance (dont do for each event):
		if (!self::$gettingDescForAggregatedCal ||  $cal->isAggregated()) {
			self::$gettingDescForAggregatedCal = $cal = $this->getCal();
		}
		
		// This is for the event-preview, as it does not go through the cal/get action 
		if (!$partner && $cal->getPartnerId()) {
			$partner =  $cal->getPartner();
			UserUtils::setPartner($partner);
		}

		// Support an event-level image (used for birthday-cal img for now) 
		//$eventImgPath 	= $this->getImagePath();
		//$eventImgLinkTo = $this->getEventImgLinkTo();
		
		$desc = "";

    	/*
    	// add TV channels
		if ($cal->isUnderCategoryId(775)){
			$country = UserUtils::getCountryByIp();
			$tvChannels = TVBrodcasting::getEventDesc($country, Cal::isHtmlSupported($calType));
			$desc .= $tvChannels;
		}
		*/
		
		
		if ($partner){
			$desc .= @$partner->getDescTopPart(Cal::isHtmlSupported($calType), $this, $cal, ESC_RAW);
			$eventDesc = @$partner->getEventDescription(Cal::isHtmlSupported($calType), $this, $cal, ESC_RAW);
		} else {
			$eventDesc = $this->getDescription();
		}

		// This is the event-desc from the table (Supports both HTML and NOT-HTML)
		// (i.e. - HTML used for TOTO)
		
		if (Cal::isHtmlSupported($calType)) {
			/*
			if ($partner && $partner->isToto()) {
				$eventDesc = str_replace(HebrewUtils::TOTO_SEND_TOFES, HebrewUtils::TOTO_SEND_TOFES_HTML, $eventDesc);
			}
			*/
			$eventDesc = Utils::nl2brReplace($eventDesc);
			
		} else {
			$eventDesc = strip_tags($eventDesc);
			$eventDesc = str_replace('&nbsp;', ' ', $eventDesc);				
		}
		
		// These are new-lines entered in the UI
		//$eventDesc = str_replace("\n", "\\n", $eventDesc);
		$eventDesc = addcslashes($eventDesc, "\n");
		$desc .= $eventDesc;

		if (!isset(self::$calDesc)) self::$calDesc = @$cal->getDescriptionForCal($userCal, $partner, $calType, ESC_RAW);
		if (self::$calDesc) $desc .= "\n" . self::$calDesc;
		
		
		if (Cal::isHtmlSupported($calType)) {
			
			// Use event image if exists
			if ($eventImgPath) {
				if ($eventImgLinkTo) $desc .= "<a target='_blank' href='$eventImgLinkTo'>";
				$desc .= "<img src='$eventImgPath' />";
				if ($eventImgLinkTo) $desc .= "</a>";
			}		
			
			// Build the INTEL for event-open
			if (!$isMasterOf){
				$partnerPart = '';$labelPart='';$valuePart='';$uclPart='';$userIdPart='';
				if ($partner) 		$partnerPart 	= "&p={$partner->getId()}";
				if ($intelLabel) 	$labelPart 		= "&l=$intelLabel;";
				if ($intelValue) 	$valuePart 		= "&v=$intelValue";
				
				if ($userCal) 	{
					$uclPart 		= "&ucl=".$userCal->getId();
					if ($userCal->getUserId()) {
						$userIdPart 		= "&uid=".$userCal->getUserId();
					}	
					
				}
				
				if ($this->getEventType() == self::TYPE_PROMO_BIRTHDAY) {
					if (!$labelPart) $labelPart = "&l=";
					$labelPart .= 'type:promoBirthday';
				}
				
				$desc .= "\n\n<img width='1' height='1' src='".sfConfig::get('app_domain_full')."/s/intel?e={$this->getId()}&s=event&a=open$partnerPart$labelPart$valuePart$uclPart$userIdPart' />";
			}
		}

		//Facebook Share
		if (!$partner && !$cal->isBirthdayCal()) {
			$fbShareUrl = sfConfig::get('app_domain_full') . '/l/facebook/e/' . $this->getId();
			$fbShareTxt = 'Share on Facebook';
			if (Cal::isHtmlSupported($calType)) {
				$desc .= '<br/><br/><a href="' . $fbShareUrl . '" target="_blank">' . $fbShareTxt . '</a>';
			} else {
				$desc .= "\n\n" . $fbShareTxt . ': ' . $fbShareUrl;
			}
		}
		

		// NO BIRTHDAY CAL FOR NOW (18/3)
		if (false && !$partner) {
			// Add Birthday Cal promo to event description
			$imgLinkTo = "http://sportycal.com/l/birthday";
			if (Cal::isHtmlSupported($calType)) {
				

				$desc .= "\n\nNever miss a friend's birthday again - get a birthday-calendar that you can add to your $calType Calendar - ";			
				
				$desc .= "<a target='_blank' href='$imgLinkTo'>";
				$desc .= "<img align='left' src='http://www.sportycal.com/images/friendsBirthday/friendsBirthdayCal.png' />";
				$desc .= "</a>";
				$desc .= "<a target='_blank' href='$imgLinkTo'>Get it here";
				$desc .= "</a>";
				//$desc .= "<a target='_blank' href='$imgLinkTo'>";
				//$desc .= "<img src='http://www.sportycal.com/images/friendsBirthday/friendsBirthdayCal.png' />"; 
				//$desc .= "</a>";
			} else {
				$desc .= "\n\nGet your friends birthday calendar: $imgLinkTo";
			}
			
		}
		
		
		
		if (!$partner || $partner->isRingtonepartner()) {
			// sportwisser statistics
			$eventStat = self::getEventStat();
			if ($eventStat->count()){
				if (Cal::isHtmlSupported($calType)) {
					$desc .= '<br/><br/><br/><br/><br/>';
				} else {
					$desc .= '\n\n\n\n\n';
				}
				$desc .= $eventStat[0]->getDescriptionForCal(Cal::isHtmlSupported($calType));
			}
			
		}
		
		
		return $desc;			
	}
	
	
	public function getFormatedDateRange() {
      return GeneralUtils::formatDateRange($this->getStartsAt(), $this->getEndsAt());
    }

    public function getDateForCSV() {
        $startsAt   = $this->getStartsAt();
        $dt         = new DateTime($startsAt);
        return $dt->format('d/m/Y');
    }
    public function getTimeForCSV() {
        $startsAt   = $this->getStartsAt();
        $dt         = new DateTime($startsAt);
        return $dt->format('H:i');
    }
    public function getReminderDateForCSV() {
        $startsAt   = $this->getStartsAt();
        $reminderAt = strtotime ( '-1 day' , strtotime ( $startsAt ) ) ;
        $newdate = date ( 'd/m/Y' , $reminderAt );
        
        return $newdate;
    }

    public function getDateForDisplay() {
        $startsAt   = $this->getStartsAt();
        
         // dont display 00:00:00 hours
        if ($startsAt == '0000-00-00 00:00:00') return '';
        
        $dt = strtotime($startsAt);
        if (!$this->hasHour()) return format_date($dt, 'D');
        
        $tz             = $this->getTz();
        //$msStartsAt     = strtotime($startsAt);
        $strUserTZ = UserUtils::getUserTZ();
        
        $dateTime = GeneralUtils::getDateTimeInSpecificTZ($startsAt, $tz, $strUserTZ, $this->getId(), $this->getCalId());
        return format_date($dateTime->format('d M Y H:i'), 'f');
    }
    
    public function getStartsAtForCal() {
        $startsAt   = $this->getStartsAt();
        return $this->getDateTimeForCal($startsAt);
    }
    public function getEndsAtForCal() {
        $endsAt     = $this->getEndsAt();
        return $this->getDateTimeForCal($endsAt);
    }
    
    private function getDateTimeForCal($strDateTime) {
        $dt         = new DateTime($strDateTime);
        if (!$this->hasHour()) return $dt->format('Ymd');
        
        $tz         = $this->getTz();
        $dateTime   = GeneralUtils::getDateTimeInSpecificTZ($strDateTime, $tz, "GMT", $this->getId(), $this->getCalId());
        
        $result = date("Ymd\THis",strtotime($dateTime->format('Y-m-d H:i')));
        $result .= "Z";
        
        return $result;
    }


    public function hasHour() {

        // if: 00:00:00 then it has no hour
        $startsAt       = $this->getStartsAt();
        $msStartsAt     = strtotime($startsAt);
        $hour           = date('H', $msStartsAt);
        $min            = date('i', $msStartsAt);
        $sec            = date('s', $msStartsAt);

        if ($hour != "00" || $min != "00" || $sec != "00") return true;
        else return false;
        
        
    }
    // This function returns the event name for Vcal
    // every x we want to tell the user too look for more details
	public function getNameForVcal($count) {
		$name = $this->getName();
		if ($count % 4 == 0) {
			$name .= " (more details inside)";
		}
		return $name;
	}
	
	public function toJSON($partnerId) {
		
		$cal 				= $this->getCal();
		$calUrl				= sfConfig::get('app_domain_full') . "/cal/" . $cal->getId() . "/ref/" . $partnerId;
		$calNumEvents 		= $cal->getNumEvents();
		$calCategory 		= $cal->getCategory();
		$rootCategory 		= $calCategory->getRootCategory();
		$ctgPath 			= $calCategory->getCategoryPathAsText();
		$ctgImgPath			= $rootCategory->getImagePathSub('1');
		$strLinks			= $cal->getLinksAsJson($partnerId);
		//$imgUrl				= sfConfig::get('app_domain_full') . "/" . $rootCategory->getImagePathSub(); 
		
		
		$strJson = '{';
		$strJson .= ' "id": '.$this->getId();
		$strJson .= ',"name": "'.$this->getName().'"';		
		$strJson .= ',"starts": "'.$this->getStartsAt().'"';		
		$strJson .= ',"ends": "'.$this->getEndsAt().'"';		
		$strJson .= ',"cal": "'.$cal->getName().'"';		
		$strJson .= ',"calNumEvents": '. $calNumEvents .'';
		$strJson .= ',"calUrl": "'. $calUrl .'"';
		//$strJson .= ',"imgUrl": "'. $imgUrl .'"';
		$strJson .= ',"ctgPath": "' . $ctgPath . '"';
		$strJson .= ',"ctgImgPath": "' . $ctgImgPath . '"';
						
		$strJson .= ',"links": '. $strLinks .'';
		$strJson .= '}';
		return $strJson;
		
	}
	
	public static function makeJSON($partnerId, $events) {
		// Note: need this space in case that links is empty
		$strJson = '[ ';
		foreach ($events as $event) {
			$strJson .= $event->toJSON($partnerId) . ",";	
		}
		$strJson = substr($strJson, 0, -1);
		$strJson .= ']';
		return $strJson;
	}

	public function getTeam($i) {
		$eventName = $this->getName();
		$team = null;
		$resultTeam = null;
		
		$pos = strpos($eventName, " vs. ");
		if ($pos) {
			if ($i == 1) $team = substr($eventName, 0, $pos);
			if ($i == 2) $team = substr($eventName, $pos + 5);
			$tempTeam = $team;
			$resultTeam = $team;
			//$resultTeam = preg_replace ( "/'+/" , '&#39' , $resultTeam);
			//$resultTeam = preg_replace ( "/,+/" , '&#44' , $resultTeam);
			
			//$thaana = new Thaana_Conversions();
			$resultTeam = Encoding::replaceSpecialChars($resultTeam);
			
			//die($resultTeam);
//			for ($i=0; $i <strlen($team); $i++) {
//				$asciicode = ord($tempTeam);
//				$tempTeam = substr($tempTeam, 1);
//				$char = chr($asciicode);
//				$resultTeam .= $char;
//			}
			
		}
		return $resultTeam;
	}
	
	
	private $strAddress = '';
	public function getAddressToDisplay() {
		if (!$this->strAddress) {
			$addressId = $this->getAddressId();
			if ($addressId) {
				$address = $this->getAddress();
				$this->strAddress = $address->getForDisplay();
			}
		}
		return $this->strAddress;
	}	
	

	
  	public static function cmpEventsByDate($e1, $e2)
    {
        $eld = strtotime($e1->getStartsAt());
        $e2d = strtotime($e2->getStartsAt());
        if ($eld == $e2d) {
            return 0;
        }
        return ($eld > $e2d) ? +1 : -1;
    }	


    /*
    public static function getExtraEvents($cal, $userCal, $calType) {
		$extraEvents = array();
		
		//$time_start = microtime(true);

		if ($userCal && $userCal->getCalId() == 7538){
			$label = $userCal->getLabel();
			preg_match('/cid:(\d+)/', $label, $matches);
			
			if (isset($matches[1])){
				$cid = $matches[1];
				
				$filePath = sfConfig::get('sf_root_dir') . 'work/888/supercal_cid_list.csv';
				if (file_exists($filePath)){
					$f = fopen($filePath, "r");
				
					while (!feof($f)) {
						$line = fgets($f);
						
						if (trim($line) == $cid){
							$e = new Event();
							$e->setName('$200 SuperCal Freeroll (six)');
							$e->setDescription('$200 guaranteed. This special secret freeroll is only for players who downloaded the 888poker SuperCal events calendar. Your secret password is: PURPLE888');
							$e->setLocation('Tournaments-Restricted tab');
							$e->setStartsAt('2012-09-09 19:02:00');
							$e->setEndsAt('2012-09-09 21:02:00');
							$e->setTz('GMT');
							
							$extraEvents[] = $e;
	
							break;
						}
					}
					fclose($f);
				}
			}
		}
		
		//$time_end = microtime(true);
		//$time = $time_end - $time_start;
		//Utils::pp($time);
		
		return $extraEvents;
		
		
		
		//Utils::pp($cal);
		//if ($cal->getId() != 118) {
		//	return $extraEvents;	
		//}
		
		if ($cal->getPartnerId()) 	return $extraEvents;
		if ($cal->isBirthdayCal()) 	return $extraEvents;

		$imgLinkTo = "http://www.sportycal.com/main/friendsBirthday/src/cal-event";
		$desc = "Never miss a friend's birthday again - get a birthday-calendar that you can add to your $calType Calendar";			
		
		
		if (Cal::isHtmlSupported($calType)) {
			$desc .= "\n\n<a target='_blank' href='$imgLinkTo'>Get it here</a>";
		} else {
			$desc .= "\n\nGet it here: $imgLinkTo";
		}
		 
		
		$e = new Event();
		$e->setName("Open me - I'm a gift from sportYcal");
		$e->setStartsAt("2011-11-21 19:00:00");
		$e->setEndsAt("2011-11-21 19:00:00");
		$e->setImagePath("http://www.sportycal.com/images/friendsBirthday/friendsBirthdayCal.png");
		$e->setEventImgLinkTo($imgLinkTo);
		//$e->setTz();
		$e->setDescription($desc);
		$e->setLocation("sportYcal.com");
		$e->setEventType(self::TYPE_PROMO_BIRTHDAY);
		
		//Utils::pp($e);
		
		
		$extraEvents[] = $e;
		
		$e = new Event();
		$e->setName("Open me - I'm a gift from sportYcal");
		$e->setStartsAt("2011-11-26 19:00:00");
		$e->setEndsAt("2011-11-26 19:00:00");
		$e->setImagePath("http://www.sportycal.com/images/friendsBirthday/friendsBirthdayCal.png");
		$e->setEventImgLinkTo($imgLinkTo);
		//$e->setTz();
		$e->setDescription($desc);
		$e->setLocation("sportYcal.com");
		$e->setEventType(self::TYPE_PROMO_BIRTHDAY);
		
		//Utils::pp($e);
		
		$extraEvents[] = $e;
		
		
		return $extraEvents;
		
	}
	*/
    
	private $type = null;
	private function setEventType($type) {
		$this->type = $type;
	}
	private function getEventType() {
		return $this->type;
	}
	
	private $eventImgLinkTo = null;
	private function setEventImgLinkTo($eventImgLinkTo) {
		$this->eventImgLinkTo = $eventImgLinkTo;
	}
	private function getEventImgLinkTo() {
		return $this->eventImgLinkTo;
	}

	public function getIdForIcal() {
		$id = $this->getId();
		if (!$id) {
			$id = rand(100000, 999999);
		}
		return $id;
	}
	
	public function getStat($hasHtml = true){
		$res = null;
		
		$stats = self::getEventStat();
		if ($stats->count()) $res = $stats[0]->getDescriptionForCal($hasHtml);
		
		return $res;
	}
	
	public function getInfoBubble($infoType = null){
		$infoBubbleHTML = '';
		
		$contHTML = '';
		$classIcon = '';
		$title = '';
		
		$show = false;
		
		switch ($infoType){
			case 'T':
				//Text Desc
				$show = true;
				$classIcon = 'eventTextIcon';
				$title = __('Preview');
				break;
			case 'H':
				//HTML Desc
				$show = true;
				$classIcon = 'eventHtmlIcon';
				$title = __('Preview HTML');
				break;
			default:
				//Stat HTML Desc
				$contHTML .= $this->getStat(true);
				
				if ($contHTML) $show = true;
				
				$classIcon = 'eventStatIcon';
				$title = __('Show game statistics');
		}
		
		if ($show) $infoBubbleHTML = '<div class="infoBubble ' . $classIcon . '" itemId="' . $this->getId() . '" title="' . $title . '"><div class="hidden">' . $contHTML . '</div></div>';
		
		return $infoBubbleHTML;
	}
	
	public function getFlatObj(){
    	$res = new stdClass();

    	$res->id = $this->getId();
    	$res->cal_id = $this->getCalId();
    	$res->name = $this->getName();
    	$res->description = $this->getDescription();
    	$res->location = $this->getLocation();
    	$res->tz = $this->getTz();
    	$res->starts_at = $this->getStartsAt();
    	$res->updated_at = $this->getUpdatedAt();

    	return $res;
    }
    
    public function getFbShareUrl(){
    	$cal = $this->getCal();
    	$ctg = $cal->getCategory();

    	$url = 'https://www.facebook.com/dialog/feed?app_id=' . FACEBOOK_APP_ID . '&';
    	$url .= 'picture=' . urlencode(sfConfig::get('app_domain_full') . '/' .$ctg->getImagePathSub()) . '&';
    	$url .= 'name=' . urlencode($this->getName()) . '&';
    	$url .= 'link=' . urlencode(sfConfig::get('app_domain_full') . '/' . $cal->getUrl()) . '&';
    	$url .= 'caption=' . urlencode($ctg->getCategoryPathAsText()) . '&';
    	$url .= 'description=' . urlencode('Download your favorite team & tournament schedule to your own calendar (google, outlook, mobile...)') . '&';
    	
    	$properties = array("When" => $this->getDateForDisplay());
    	if ($this->getLocation()) $properties['Location'] = $this->getLocation();
    	
		$url .= 'properties=' . urlencode(json_encode($properties)) . '&';
		$url .= 'actions=' . urlencode('[{"name" : "Buy Tickets", "link" : "http://www.sportYcal.com/l/tickets"}]') . '&';
    	
    	$url .= 'redirect_uri=' . urlencode(sfConfig::get('app_domain_full') . '/?src=fbpr');
    	
    	return $url;
    }
    
    public function getStartTimeForDisplay(){
    	return date('H:i', strtotime($this->getStartsAt()));
    }
    
    public static function isAllDay($event){
    	$s = explode(' ', $event->getStartsAt());
    	$e = explode(' ', $event->getEndsAt());
    	
    	return ($s[0] != $e[0] && $s[1] == $e[1]) ? true : false;
    }
}