<?php

define('SECRET', '80f3dvfc8jcd60pd881s89s6bf25gi99');

/**
 * admin actions.
 *
 * @package    evento
 * @subpackage admin
 * @author     Yaron Biton
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class adminActions extends sfActions
{

  private function restrictAccess() {
    $user = UserUtils::getLoggedIn();
    if (!$user || !$user->isMaster()) {
      $this->redirect("main/index");
    }
  }    


  public function executeIndex(sfWebRequest $request) {
      $this->restrictAccess();
  }
  
  private function partnerRestrictAccess() {
  	$redirect = false;
  	$user = UserUtils::getLoggedIn();
  	
  	if (!$user || !$user->isPartner()) {
  		$this->redirect("main/index");
  	}
  }

  
  public function executePartnersReports(sfWebRequest $request) {
  	$this->restrictAccess();
  	
  	$this->partners = Doctrine::getTable('Partner')
  		->createQuery('p')
  		->limit(100)
  		->execute();
  }
  
  public function executePartnerReport(sfWebRequest $request) {
  	$user = UserUtils::getLoggedIn();
  	if ($user && $user->isMaster()) {
  		$partnerId = $request->getParameter('partnerId');
  	} else {
  		$this->partnerRestrictAccess();
  		$partnerId = UserUtils::getPartnerIdMaster();
  	}
  	
  	$this->forward404Unless($partnerId);
  	$this->partnerId = $partnerId;
  	
  	$shortUrls = array(
  		'1979' => array(7,8,12,13),
  		'10'   => array(5,6,10,11)
  	);
  	
  	$this->shortUrls = null;
  	if (isset($shortUrls[$partnerId])){
  		$qs = Doctrine::getTable('ShortUrl')
	  		->createQuery('su')
	  		->select('su.url, su.hash, su.comment, su.used_at, su.count_used')
	  		->where('su.partner_id =?', array($partnerId))
	  		->andWhereIn('su.id', $shortUrls[$partnerId]);
  		
  		$this->shortUrls = $qs->execute();
  	}
  	
  	$q = Doctrine::getTable('Cal')
	  	->createQuery('c')
	  	->select('c.id, c.name')
	  	->where('c.deleted_at is null')
	  	->andWhere('c.partner_id =?', array($partnerId));
  	$this->cals = $q->execute();
  	
  	$calIds = array();
  	foreach ($this->cals as $cal){
  		$calIds[] = $cal->getId();
  	}
  	
  	$this->generalDetails = null;
  	if (count($calIds)){
  		$this->generalDetails = $this->getCalReqTotalDownloads($partnerId);
  		
  		$this->generalDetailsLastMonth = $this->getCalReqTotalDownloads($partnerId, null, true);
  		$this->eventsCount = $this->getCalReqEventsDownloads($partnerId);
  		$this->activeCount = $this->getCalReqTotalActive($partnerId);
  	}
	
  	$this->calReportByDays = $this->getCalReqReportByDays($partnerId);
  	$this->calReportByTypes = $this->getCalReqReportByTypes($partnerId);
  }
  
  public function executePartnerCalReport(sfWebRequest $request) {
  	$user = UserUtils::getLoggedIn();
  	if ($user && $user->isMaster()) {
  		$partnerId = $request->getParameter('partnerId');
  	} else {
  		$this->partnerRestrictAccess();
  		$partnerId = UserUtils::getPartnerIdMaster();
  	}
  	
  	$this->forward404Unless($partnerId);
  	$this->partnerId = $partnerId;
  	
  	$calId = $request->getParameter('calId');
  	$this->forward404Unless($calId);
  	
  	$this->generalDetails = null;
  	if ($calId){
  		$this->generalDetails = $this->getCalReqTotalDownloads($partnerId, $calId);
  		
  		$this->generalDetailsLastMonth = $this->getCalReqTotalDownloads($partnerId, $calId, true);
  		$this->eventsCount = $this->getCalReqEventsDownloads($partnerId, $calId);
  		$this->activeCount = $this->getCalReqTotalActive($partnerId, $calId);

  		$q = Doctrine::getTable('Cal')
	  		->createQuery('c')
	  		->select('c.id')
	  		->where('c.deleted_at is null')
	  		->andWhere('c.id =?', array($calId))
	  		->andWhere('c.partner_id =?', array($partnerId))
	  		->limit(1);

  		$this->cal = $q->execute()->getFirst();
  		
  		if ($this->cal) {
  			$this->reportByDays = $this->getCalReqReportByDays($partnerId, $calId);
	  		$this->reportByTypes = $this->getCalReqReportByTypes($partnerId, $calId);

	  		/*
	  		$this->reportByActionTypes = Doctrine::getTable('intel')
		  		->createQuery('i')
		  		->select('i.event_id, i.ip_address')
		  		->where('i.cal_id =?', array($calId))
		  		->andWhere('i.section = "event"')
		  		->andWhere('i.action  = "open"')
		  		->execute();
		  	//Utils::pp($this->reportByActionTypes);
		  	*/
  		} else {
  			$this->redirect("main/index");
  		}
  	}
  }
  
  private function getCalReqEventsDownloads($partnerId, $calId=null){
  	$q = Doctrine::getTable('CalRequest')
	  	->createQuery('cr')
	  	->select('COUNT(e.id) events_count')
	  	->innerJoin('cr.Cal c')
	  	->leftJoin('c.Event e')
	  	->whereIn('cr.partner_id', $partnerId)
	  	->andWhere('cr.cal_id IS NOT NULL');
  	 
  	if ($calId) $q->andWhereIn('cr.cal_id', $calId);
  	
  	$res = $q->execute()->getFirst();
  	
  	return $res['events_count'];
  }

  private function getCalReqTotalActive($partnerId, $calId=null){
  	$q = Doctrine::getTable('CalRequest')
  	->createQuery('cr')
  	->select('cr.id, COUNT(cr.id) AS active_count')
  	->innerJoin('cr.UserCal uc')
  	->whereIn('cr.partner_id', $partnerId)
  	->andWhere('cr.cal_id IS NOT NULL')
  	->andWhere('cr.created_at >= ?', date("Y-m-d H:i:s",strtotime("-2 day")));
  	 
  	if ($calId) $q->andWhereIn('cr.cal_id', $calId);
  	
  	$res = $q->execute()->getFirst();
  	 
  	return $res['active_count'];
  }
  
  private function getCalReqTotalDownloads($partnerId, $calId=null, $lastMonth = false){
  	$q = Doctrine::getTable('CalRequest')
	  		->createQuery('cr')
	  		->select('cr.id, COUNT(cr.id) AS total_count, MAX(uc.taken_at) as last_taken_at')
	  		->innerJoin('cr.UserCal uc')
	  		->whereIn('cr.partner_id', $partnerId)
  			->andWhere('cr.cal_id IS NOT NULL');
  	
  	if ($calId) $q->andWhereIn('cr.cal_id', $calId);
  	if ($lastMonth) $q->andWhere('uc.taken_at >= ?', date("Y-m-d H:i:s",strtotime("-1 month")));
  	
  	return $q->execute()->getFirst();
  }
  
  private function getCalReqReportByDays($partnerId, $calId=null){
  	$reportByDays = Doctrine::getTable('CalRequest')
	  	->createQuery('cr')
	  	->select('cr.id, uc.taken_at AS taken_at, COUNT(cr.id) AS num_user_cal')
	  	->innerJoin('cr.UserCal uc')
	  	->where('cr.partner_id =?', array($partnerId))
	  	->andWhere('cr.cal_id IS NOT NULL')
	  	->groupBy('DATE(uc.taken_at)')
	  	->orderBy('uc.taken_at DESC');
  	
  	if ($calId) $reportByDays->andWhere('cr.cal_id =?', array($calId));
  	
  	return $reportByDays->execute();
  }
  
  private function getCalReqReportByTypes($partnerId, $calId=null){
  	$reportByTypes = Doctrine::getTable('CalRequest')
	  	->createQuery('cr')
	  	->select('cr.cal_type, COUNT(cr.id) AS num_user_cal')
	  	->where('cr.partner_id =?', array($partnerId))
	  	->andWhere('cr.cal_id IS NOT NULL')
	  	->groupBy('cr.cal_type');
  	
  	if ($calId) $reportByTypes->andWhere('cr.cal_id =?', array($calId));
  	
  	return $reportByTypes->execute();
  }

  private function countCals(){
  	set_time_limit (180);
  	$this->totalCount = CategoryTable::calculateCalsCount();
  }
  
  public function executeCountCals(sfWebRequest $request) {
    $this->restrictAccess();
    $this->countCals();
  }

  public function executeDisplaySearch(sfWebRequest $request) {
    $this->restrictAccess();
    $this->searches = UserSearchTable::getBy();
  }

  public function executeUpdateStats(sfWebRequest $request) {
    $calId    = $request->getParameter('calId');
    $calType  = $request->getParameter('calType');
  	$ctgId    = $request->getParameter('ctgId');
  	
  	// This HASH is currrently only saved in the UserCal Table, but not used
  	$hash     = $request->getParameter('hash');
  	$label    = $request->getParameter('label');
  	$birthdayCalForUserId    = $request->getParameter('bc');
  	$reminder    = $request->getParameter('reminder');
  	
  	$partner  	= UserUtils::getPartner();
  	
  	
    //$sToken    = $request->getPostParameter('st');
    $dateNow    = date("Y-m-d g:i");

    // for now ignore the security token (had problems getting it right...)
    //if (!UserUtils::isValidSecurityToken($token)) return sfView::NONE;
    if ((!$calId && !$ctgId && !$birthdayCalForUserId) || !$calType) return sfView::NONE;
    
    if ($calId) {
	    $cal = Doctrine::getTable('Cal')->find(array($calId));
	    if (!$cal) return sfView::NONE;
    }
    
        
    $ip = Utils::getClientIP();
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    
    //$key = null;
    //if ($ctgId) $key = 'ctgId_' . $ctgId;
    //else if ($calId) $key = 'calId_' . $calId;
    
    //$userCalId = UserUtils::getUserCalId($key);
    
    
    //$userCal = null;
    //if ($userCalId) $userCal = Doctrine::getTable('UserCal')->find($userCalId);
    //if (!$userCal) $userCal = new UserCal();

    // Save this UserCal
    $userCal = new UserCal();
    
    if ($calId) 	$userCal->setCalId($calId);
    if ($ctgId) 	$userCal->setCategoryId($ctgId);
    if ($birthdayCalForUserId) 	$userCal->setBirthdayCalForUserId($birthdayCalForUserId);
    if ($partner)	$userCal->setPartnerId($partner->getId());
    if ($hash)		$userCal->setHash($hash);
    if ($label)		$userCal->setLabel($label);
    if ($reminder != null) 	$userCal->setReminder($reminder);
    if ($userAgent)         $userCal->setUserAgent($userAgent);
    
    $userCal->setUserId(UserUtils::getLoggedInId());
    $userCal->setCalType($calType);
    $userCal->setTakenAt($dateNow);
    $userCal->setUpdatedAt($dateNow);
    $userCal->setIpAddress($ip);
    $userCal->save();
    
    //if (!$userCalId) UserUtils::setUserCalId($key, $userCal->getId());
	
    $loggedinId = UserUtils::getLoggedInId();
    if ($loggedinId) UserTable::creditToUser($loggedinId, UserTable::CREDIT_DOWNLOAD);
    $refUserId = UserUtils::getRefUserId();
    if ($refUserId) UserTable::creditToUser($refUserId, UserTable::CREDIT_REF_DOWNLOAD);
    

    echo $userCal->getId();


    return sfView::NONE;
  }

  public function executeDisplayUserCals(sfWebRequest $request) {
      
    $this->restrictAccess();
    
    $this->sort    = $request->getParameter('sort');
    if (!$this->sort) $this->sort = 'taken';
    
    $this->orderBy    = $request->getParameter('orderBy');
    if (!$this->orderBy) $this->orderBy = 'desc';

    $timeStarts = strtotime('30 days ago');	
	$starts = date('Y-m-d', $timeStarts);
	
    $this->userCals = UserCalTable::getBy($starts, $this->sort, $this->orderBy, 1000);

  }
  public function executeDisplayCalReqs(sfWebRequest $request) {
    $this->restrictAccess();
    $this->calReqs = CalRequestTable::getBy();
  }
  public function executeDisplayLinkUsage(sfWebRequest $request) {
    $this->restrictAccess();

    $timeStarts = strtotime('30 days ago');	
	$starts = date('Y-m-d', $timeStarts);
    
    $this->linksUsage = CategoryLinkUsageTable::getBy($starts);
  }
  public function executeLinkUsageCSV(sfWebRequest $request) {
    $this->restrictAccess();
    $this->linksUsage = CategoryLinkUsageTable::getBy();
  }


  public function executeUserCalsCSV(sfWebRequest $request) {
    $this->restrictAccess();
    
    $timeStarts = strtotime('30 days ago');	
	$starts = date('Y-m-d', $timeStarts);
    
    //Utils::pp($starts);
	
    $this->userCals = UserCalTable::getBy($starts, 'taken', 'desc', 1000);
  }
  public function executeCalReqsCSV(sfWebRequest $request) {
    $this->restrictAccess();
    $this->calReqs = CalRequestTable::getBy();
  }

  public function executeUserSearchCSV(sfWebRequest $request) {
    $this->restrictAccess();
    $this->userSearches = UserSearchTable::getBy();
  }


  // Yaron, better run this one locally and only run created SQL
    private function executeUpdateEventsLocations(sfWebRequest $request) {
		//die("done");
    	EventTable::updateEventsAddress();
		die("<br/>done");
    }

	public function executeFSpider(sfWebRequest $request) {
		
		$this->restrictAccess();
		
		$league     = $request->getParameter('league');
		$month    	= $request->getParameter('month');
		$year     	= $request->getParameter('year');
		$format		= $request->getParameter('format');
		$untilNextYear		= $request->getParameter('untilNextYear');
		
		$format = strtoupper($format);
		if ($format == "CSV") {
			define ("OUTPUT_CSV", "");			
		} else {
			define ("OUTPUT_SQL", "");
		}		

		
		
		//$league = "euro2012qualifying";
		
		
		if (!$league || !$month || !$year || $year < date('Y') || $month > 12) {
			echo("Example Usage:");
			//echo("<br/>");
			//echo("Get SQL: <b>".sfConfig::get('app_domain_full')."/admin/fSpider?league=premierleague&month=2&year=2011" . "</b>" );
			echo("<br/>");
			//echo("Get CSV: <b>".sfConfig::get('app_domain_full')."/admin/fSpider?league=premierleague&month=2&year=2011&format=CSV" . "</b>" );
			echo("Get CSV: <b>".sfConfig::get('app_domain_full')."/admin/fSpider?league=premierleague&month=" . date('n') . "&year=" . date('Y') . "&untilNextYear=1" . "</b>" );
			die();
		}

		//$todo = array(array(02, 2011));
		//$todo = array(array(02, 2011), array(03, 2011));
		//$todo = array(array($month, $year));
		
		$startMonth = (int) $month;
		
		$endMonth = $startMonth;
		if ($untilNextYear) $endMonth = 12;
		
		$todo = array();
		for ($i = $startMonth; $i <= $endMonth; $i++) {
			$todo[] = array($i, $year);
		}

		$this->outLines = Utils::runFoxSpider($league, $todo);
		$this->month 	= $month;
		$this->year 	= $year;
		
		
		//Fix cals count
		$this->countCals();
	}
	
	public function executeESpiderMensCollegeBasketball(sfWebRequest $request) {
		
		$this->restrictAccess();

		//define ("OUTPUT_CSV", "out_ESPN.csv");

		//mens-college-basketball
		$this->outLines = Utils::runESPNSpider("mens-college-basketball");
		
		$this->setLayout(false);
		
		//Fix cals count
		$this->countCals();
	}
	
	public function executeESpiderCollegeFootball(sfWebRequest $request) {	
		$this->restrictAccess();		
		$this->outLines = Utils::runESPNSpider("college-football");	
		$this->setLayout(false);
		$this->countCals();
	}
	
	//SoccernetSpider
	public function executeSSpider(sfWebRequest $request) {
		
		$this->restrictAccess();

		//define ("OUTPUT_CSV", "out_ESPN.csv");


		$this->outLines = Utils::runSoccernetSpider();
		
		$this->setLayout(false);
		
		//Fix cals count
		$this->countCals();
	}
	
	//Espnscrum
	public function executeEspnscrumSpider(sfWebRequest $request) {
		
		$this->restrictAccess();

		$this->outLines = Utils::runEspnscrumSpider();
		
		$this->setLayout(false);
		
		//Fix cals count
		$this->countCals();
	}
	
	//EspnOlympics
	public function executeEspnOlympicsSpider(sfWebRequest $request) {
		
		$this->restrictAccess();

		$this->outLines = Utils::runEspnOlympicsSpider();
		
		$this->setLayout(false);
		
		//Fix cals count
		$this->countCals();
	}

	public function executeDisplayUsers(sfWebRequest $request) {
	    $this->restrictAccess();
		
	    $this->sort    = $request->getParameter('sort');
	    if (!$this->sort) $this->sort = 'last_login_date';
	    
	    $this->orderBy    = $request->getParameter('orderBy');
	    if (!$this->orderBy) $this->orderBy = 'desc';
	    
	    $this->users = Doctrine::getTable('User')
	      ->createQuery('a')
	      ->orderBy('a.' . $this->sort . ' ' . $this->orderBy)
	      ->execute();
	    	    
	}
	
	public function executeDisplayCalendars(sfWebRequest $request) {
	    $this->restrictAccess();   
    
	    $this->sort    = $request->getParameter('sort');
	    if (!$this->sort) $this->sort = 'id';
	    
	    $this->orderBy    = $request->getParameter('orderBy');
	    if (!$this->orderBy) $this->orderBy = 'asc';
	    
	    $q = Doctrine_Query::create()
			->select('c.*, COUNT(u.id) AS num_user_cal')
			->from('Cal c')
			->leftJoin('c.UserCal u')
			->groupBy('c.id')
			->orderBy("$this->sort $this->orderBy");
			
		$this->cals = $q->execute();
	}
	
	public function executeDisplayContacts(sfWebRequest $request) {
	    $this->restrictAccess();
		
	    $sort    = $request->getParameter('sort');
	    if (!$sort) $sort = 'created_at';
	    
	    $orderBy    = $request->getParameter('orderBy');
	    if (!$orderBy) $orderBy = 'desc';
	    
	    $this->contacts = Doctrine::getTable('Contact')
	      ->createQuery('a')
	      ->orderBy('a.' . $sort . ' ' . $orderBy)
	      ->execute();
	    	    
	}
	
	public function executeUpdateWinnerCals(sfWebRequest $request) {
		
		$secret     = $request->getParameter('s');
		if ($secret != SECRET) $this->restrictAccess();
	
	    $updateWinnerCals = new UpdateWinnerCals();
	    $log = $updateWinnerCals->execute();
	    
	    $this->calName2EventsCount = $log['calName2EventsCount'];

	    $this->importedGames = array_keys($log['importedGames']);
	    
	    //Fix cals count
	    $this->countCals();
	}
	
	public function executeUpdatePokerstarsCals(sfWebRequest $request) {
		//$secret     = $request->getParameter('s');
		//if ($secret != SECRET) $this->restrictAccess();
		
		
		$importedGames = array();
	    $updatePokerstarsCals = new UpdatePokerstarsCals();
	    
	    $this->calName2EventsCount = $updatePokerstarsCals->execute($importedGames);
		
	    $this->importedGames = $importedGames;
	    
	    //Fix cals count
	    $this->countCals();
	}
	
	public function executeDisplayIntels(sfWebRequest $request) {
	    $this->restrictAccess();
	
	    $this->intels = Doctrine::getTable('Intel')
	      ->createQuery('i')
	      ->where('i.section="event"')
      	  ->orderBy('i.id desc')      
	      ->execute();
	   	
	      $this->reportTitle = "Calendar Event - Intels";	      
	}
	
	public function executeDisplayInvites(sfWebRequest $request) {
	    $this->restrictAccess();
	
	    $this->intels = Doctrine::getTable('Intel')
	      ->createQuery('i')
	      ->where('i.section="invite"')
      	  ->orderBy('i.id desc')      
	      ->execute();
	    
	   	$this->reportTitle = "Invite Intels";
	    $this->setTemplate("displayIntels");
	      
	      
	}
	
	public function executeDisplayPromo(sfWebRequest $request) {
	    $this->restrictAccess();
	
	    $this->intels = Doctrine::getTable('Intel')
	      ->createQuery('i')
	      ->where('i.section="promo"')
      	  ->orderBy('i.id desc')      
	      ->execute();
	   	
	      
	    $this->reportTitle = "Promo Intels";
	    $this->setTemplate("displayIntels");
	      
	}

	public function executeDisplayBirthdayCals(sfWebRequest $request) {
	    $this->restrictAccess();
	
	    $this->intels = Doctrine::getTable('Intel')
	      ->createQuery('i')
	      ->where('i.section="birthdayCal"')
      	  ->orderBy('i.id desc')      
	      ->execute();
	   	
	      
	    $this->reportTitle = "Birthday Cals Intels";
	    $this->setTemplate("displayIntels"); 
	}
	
	public function executeDisplayBirthdayCalSignups(sfWebRequest $request) {
	    $this->restrictAccess();
	
	    $this->intels = Doctrine::getTable('Intel')
	      ->createQuery('i')
	      ->where('i.section="birthdayCal"')
	      ->andWhere('i.action="fb-login"')
      	  ->orderBy('i.id desc')      
	      ->execute();
	   	
	      
	    $this->reportTitle = "Birthday Cals Intels";
	    $this->setTemplate("displayIntels"); 
	}
	
	public function executeDisplayCtgs(sfWebRequest $request) {
	    $this->restrictAccess();
	    
	    /*
		 $this->categorys = Doctrine::getTable('Category')
	      ->createQuery('a')
	      ->execute();
    	*/
	    $this->sort = $request->getParameter('sort');
	    if (!$this->sort) $this->sort = 'id';
	    
	    $this->orderBy    = $request->getParameter('orderBy');
	    if (!$this->orderBy) $this->orderBy = 'asc';
		
	    
	    $orderBtStr = 'a.' . $this->sort . ' ' . $this->orderBy;
	    $this->categorys = Doctrine::getTable('Category')
	      ->createQuery('a')
	      ->orderBy($orderBtStr)
	      ->execute();
	    
	    //$this->userCals = UserCalTable::getBy($starts, $this->sort, $this->orderBy);   
	}
	
	
	public function executeDisplayEndingCals(sfWebRequest $request) {
	    $this->restrictAccess();

	    $currDate = date('Y-m-d');
	    $msEndDate = strtotime('+3 week');
	    $endDate = date('Y-m-d', $msEndDate);
	    
	    $sql = "SELECT distinct(cal_id), cal.name
				from event join cal on (event.cal_id = cal.id) 
				where 	cal.deleted_at is null and cal.partner_id is null and
						event.starts_at >= '$currDate' and 
						cal_id not in 
						    (select distinct(e.cal_id)
						    from event e 
						    WHERE e.starts_at > '$endDate')";
	    
	    
		$statement = Doctrine_Manager::getInstance()->connection();
		$results = $statement->execute($sql);
		$this->calsInfo = $results->fetchAll();
		$this->endDate = $endDate;
		
		//Utils::pp($this->calsInfo);
	      
	}
	
	public function executeUpdateEventStats(sfWebRequest $request) {
		$this->restrictAccess();
		
		$this->sportWiser = new SportWiser();
		$this->sportWiser->updateEventStat();
	}
	
	/**
	 * 
	 * @param sfWebRequest $request
	 */
	public function executeImportCal(sfWebRequest $request)  {
		//TODO: remove hardcoded user
		//Only for michal@campustelaviv.com
		$user = UserUtils::getLoggedIn();
		if (!$user || $user->getId() != 705) $this->redirect("main/index");
		
		$this->partnerRestrictAccess();
		$icalUrl 	= $request->getParameter('url');

		//TODO: remove hardcoded partnerId,CtgId
		$partnerId = 1982;
		$ctgId = 2003;
		
		$ical = new Ical($icalUrl);

		
		$cal = CalTable::getCals($ctgId, null, array($ical->cal['VCALENDAR']['X-WR-CALNAME']))->getFirst();
		if (!$cal) {
			$cal = new Cal();
			$cal->setName($ical->cal['VCALENDAR']['X-WR-CALNAME']);
			$cal->setCategoryId($ctgId);
			$cal->setByUserId($user->getId());
			$cal->setCategoryIdsPath($ctgId);
			$cal->setPartnerId();
		} else {
			Doctrine::getTable('Event')->deleteBy($cal->getId());
		}
		
		$cal->setDescription($ical->cal['VCALENDAR']['X-WR-CALDESC']);
		$cal->save();
		
		//echo '<pre>';
		//echo 'Update/Create cal ' . $cal->getName() . "\n";
		//echo '-----------------------------------------------------------------------'. "\n";
		
		$tz = $ical->cal['VCALENDAR']['X-WR-TIMEZONE'];
		
		if ($tz == 'Asia/Jerusalem') $tz = 'Europe/Athens';

		foreach ($ical->cal['VEVENT'] as $icalEvent){
			$event = new Event();
			$event->setCalId($cal->getId());
			$event->setName($icalEvent['SUMMARY']);
			$event->setDescription($icalEvent['DESCRIPTION']);
			$event->setLocation($icalEvent['LOCATION']);
			//$event->setTz($tz);
			$event->setStartsAt(date('Y-m-d H:i', Ical::ical_date_to_unix_timestamp($icalEvent['DTSTART'])));
			$event->setEndsAt(date('Y-m-d H:i', Ical::ical_date_to_unix_timestamp($icalEvent['DTEND'])));
			$event->save();
			
			//Utils::pp($icalEvent['DTSTART']);
			
			//echo 'Update/Create event ' . $event->getName() . "\n";
		}
		//echo '</pre>';
		
		$this->redirect("/cal/" . $cal->getId());
	}
	
	public function executeNHLSpider(sfWebRequest $request) {	
 		$this->restrictAccess();
		$this->outLines = Utils::runNHLSpider();
		$this->setLayout(false);
	}	
}

	