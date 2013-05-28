<?php

/**
 * cal actions.
 *
 * @package    evento
 * @subpackage cal
 * @author     Yaron Biton
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class calActions extends sfActions
{
 public function preExecute() {
  	Utils::redirectToMobileVersionIfNeeded($this);
 }
	
	private function restrictAccessAllowPartners($category) {
	 	if (!UserUtils::userISMasterOf($category)) 
	  		$this->redirect("main/index");
	}
 
  private function restrictAccess() {
    $user = UserUtils::getLoggedIn();
    if (!$user || !$user->isMaster()) {
      $this->redirect("main/index");
    }
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->ctgId = $request->getParameter('ctgId');
    $category = Doctrine::getTable('Category')->find(array($this->ctgId));
    $this->forward404Unless($category);
    
  	$this->restrictAccessAllowPartners($category);   
    
    //sfContext::getInstance()->getLogger()->info("Entering ExecuteNEW");

    $cal = new Cal();
    $cal->setCategoryId($this->ctgId);
    
    $this->form = new CalForm($cal);
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $cal = new Cal();
    $this->form = new CalForm($cal);
    
    
  	$params = $request->getParameter($this->form->getName());
  	//$this->ctgId = $params['category_id'];
  	
  	$this->forward404Unless($category = Doctrine::getTable('Category')->find(array($params['category_id'])), sprintf('Object category does not exist (%s).', $params['category_id']));
  	$this->restrictAccessAllowPartners($category);   
  	
  	
  	$cal->setCategoryId($params['category_id']);
  	
  	   
    $this->processForm($request, $this->form);
    $this->setTemplate('new');
  }
  
  

  public function executeEdit(sfWebRequest $request)
  {

    $this->forward404Unless($cal = Doctrine::getTable('Cal')->find(array($request->getParameter('id'))), sprintf('Object cal does not exist (%s).', $request->getParameter('id')));
    $ctg = $cal->getCategory();
  	$this->restrictAccessAllowPartners($ctg);
    
    //$cal = $this->getRoute()->getObject();
    $this->form = new CalForm($cal);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $cal = $this->getRoute()->getObject();
    $this->ctg = $cal->getCategory(); 
  	$this->restrictAccessAllowPartners($ctg);   

//    $this->forward404Unless($cal = Doctrine::getTable('Cal')->find(array($request->getParameter('id'))), sprintf('Object cal does not exist (%s).', $request->getParameter('id')));

    $this->form = new CalForm($cal);
    
    $this->processForm($request, $this->form);
    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    $cal = $this->getRoute()->getObject();
    $ctg = $cal->getCategory();
  	$this->restrictAccessAllowPartners($ctg);   
    

//    $this->forward404Unless($cal = Doctrine::getTable('Cal')->find(array($request->getParameter('id'))), sprintf('Object cal does not exist (%s).', $request->getParameter('id')));
    //$cal->delete();

    $dateNow    = date("Y-m-d g:i");
    $cal->setDeletedAt($dateNow);
    $cal->save();
    
    CategoryTable::updateCalsCountForPathEndsWith($ctg, -1);
    
    $ctgId = $cal->getCategoryId();
    //die($ctgParentId);
   	$this->redirect('category/show?id=' . $ctgId);
    
  }

  /*
  public function executeTest(sfWebRequest $request) {
  	$this->events = EventTable::getBy(null, array(211), date('Y-m-d 00:00:00'), null, null, null, null, true);
  }*/
  
  public function executeShow(sfWebRequest $request)
  {
  	$this->fromFbApp = UserUtils::getFromFbApp();
  	if ($this->fromFbApp) $this->setLayout("fbapp");
  	
    $calId 				= $request->getParameter('id');
    $ctgId 				= $request->getParameter('ctgId');
    $birthdayCalUserId 	= $request->getParameter('bc');
  	
    $this->forward404Unless($calId || $ctgId || $birthdayCalUserId);

    $user = UserUtils::getLoggedIn();
    
    if ($calId) {
    	$this->cal = Doctrine::getTable('Cal')->find(array($calId));
    	$this->forward404Unless($this->cal);
    	
	    //$this->cal = $this->getRoute()->getObject();
	    $this->category = $this->cal->getCategory();
    } elseif ($ctgId){
    	$this->category = Doctrine::getTable('Category')->find(array($ctgId));
    	$this->forward404Unless($this->category);
    	
    	$this->cal = Cal::getAggregatedCal($this->category);
    } elseif ($birthdayCalUserId) {
    	$this->forward404Unless($user && ($user->isMaster() || $user->getId() == $birthdayCalUserId));
    	$this->cal = Cal::getBirthdayCal($user);
    	$this->category = Category::getBirthdayCategory($user);
    } 

    $this->isMasterOf = UserUtils::userISMasterOf($this->category);
    
    $this->events = $this->cal->getEvents();
    
    //Utils::pp($this->events);
    
    $this->haveFutureEvents = $this->cal->haveFutureEvents();

    $this->leagueEnded = false;
    if (!$this->haveFutureEvents){
    	$this->events = array_reverse($this->events);
    	
	    if ($ctgId){
	    	$this->leagueEnded = true;
	    	$this->events = EventTable::getBy(null, array($ctgId), null, null, null, 100, false, true);
	    }
    }

    //$this->ctgLinks = $this->category->getLinks();
    
    $this->partners = null;
    $this->partnersDesc = null;
    if ($user && $user->isMaster()) {
    	$this->partners		= PartnerTable::getBy();
    	$this->partnersDesc = PartnerDescTable::getBy(null, $this->cal->getId());	
    }
    
    $this->forward404Unless($this->cal);
    
    // mobile
    Utils::useMobileViewIfNeeded($this, "calShow");
    
  	if (IS_MOBILE){
    	//Get back btn params
	    
	    //case 1 calender 
	    $calsCount = $this->category->getCalsCount();
	    $parentCtg = $this->category;
	    if ($calsCount == 1){
	    	$parentCtg = $this->category->getParentCategory();
	    }

	    $this->backRow = Utils::prepareBackButton($this->category, $parentCtg);	
	    
    }
    //preper events for moblePopup
    $mobilePopupItems = array();
    $htmlPreviews = array();
    
    if ($this->isMasterOf){
	    $mobilePopupItems[0] = array(
	    	"title" => $this->cal->getName(),
	    	"notes" => $this->cal->getDescription()
	    );
	    
	    foreach ($this->events as $event){
	    	$mobilePopupItems[$event->getId()] = array(
	    		"title" => $event->getName(),
	    		"notes" => Utils::nl2brReplace($event->getDescriptionForCal($this->cal, null, null, Cal::TYPE_ANY, null, null, $this->category))
	    	);
	    	
	    	$htmlPreviews[$event->getId()] = array(
	    		"title" => $event->getName(),
	    		"notes" => Utils::nl2brReplace($event->getDescriptionForCal($this->cal, null, null, Cal::TYPE_GOOGLE, null, null, $this->category))
	    	);
	    }
    }
    
    $this->tzList = GeneralUtils::getTZList();
    
    //Utils::pp(json_encode($mobilePopupItems));
    $this->mobilePopupItems = json_encode($mobilePopupItems);
    $this->htmlPreviews = json_encode($htmlPreviews);
    
    
    $isMasterOf = UserUtils::userISMasterOf($this->category);
    if ($isMasterOf) {
    	$this->ctgLinks = $this->category->getLinks();
    }
  }


  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      if ($form->getObject()->isNew()) {
      	CategoryTable::updateCalsCountForPathEndsWith($form->getObject()->getCategory(), 1);
      }
      $cal = $form->save();

      //$this->redirect('cal/edit?id='.$cal->getId());
      $this->redirect('cal/show?id='.$cal->getId());
    }
  }



  public function executeGetCSV(sfWebRequest $request)
  {
    $this->cal = Doctrine::getTable('Cal')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->cal);
  }


  public function executeHardcopy(sfWebRequest $request)
  {
    $calId 				= $request->getParameter('id');
    $ctgId 				= $request->getParameter('ctgId');
    $birthdayCalUserId 	= $request->getParameter('bc');
  	
    $this->forward404Unless($calId || $ctgId || $birthdayCalUserId);

    if ($calId) {
    	$cal = Doctrine::getTable('Cal')->find(array($calId));
	    //$cal = $this->getRoute()->getObject();
    	//$category = $cal->getCategory();
    } elseif ($ctgId) {
    	$category = Doctrine::getTable('Category')->find(array($ctgId));
    	$cal = Cal::getAggregatedCal($category);
    } elseif ($birthdayCalUserId) {
    	$user = UserUtils::getLoggedIn();
    	$this->forward404Unless($user && ($user->isMaster() || $user->getId() == $birthdayCalUserId));
    	$cal = Cal::getBirthdayCal($user);
    }
    
    $this->getResponse()->setContentType('application/pdf');
    
    
    //$cal = Doctrine::getTable('Cal')->find(array($calId));
    
    $rootCtg = CategoryTable::getRootCategory($cal->getCategoryId());
    
    $events = $cal->getEvents();

    //Instanciation of inherited class
    $pdf = new SportycalPDF();
    //$pdf->AliasNbPages();
    $pdf->AddPage();

    $pdf->PrintHeader($cal->getName(), $rootCtg->getImagePathForPdf());
    $pdf->PrintTable($events);
    
    $pdf->Output();

    
    
/*    
//    define('FPDF_FONTPATH','C:\\dev\\evento\\web\\fpdf\\font\\');
    $pdf = new FPDF();
    $pdf -> addPage();
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(40,10,'Hello World!');
    $pdf->Output();
    //$this->pdf = $pdf;
*/  
    return sfView::NONE;

  }
  
  public function executeGetIcs(sfWebRequest $request){
  	$calId      	= $request->getParameter('id');
  	$ctgId      	= $request->getParameter('ctgId');
  	$calType       	= $request->getParameter('ct');
  	$label       	= $request->getParameter('label');
  	$remider       	= $request->getParameter('remider');

  	$tags = null;
  	if (!is_null($label)) $tags = json_decode($label, true);
  
  	//http://sportYcal.local/frontend_dev.php/cal/get/id/134/ct/google/l/cId:9191sportycal.ics
  	$intelLabel       	= $request->getParameter('l');
  	$intelValue       	= $request->getParameter('v');
  	 
  	//http://sportycal.local/frontend_dev.php/cal/get?bc=2&ct=google
  	$birthdayCalUserId	= $request->getParameter('bc');
  	 
  	$this->forward404Unless($calId || $ctgId || $birthdayCalUserId);
  	 
  	$partner = SportyCalAPI::getValidPartner($request);
  	if ($partner) UserUtils::setPartner($partner);
  	 
  	if ($calId) {
  		$this->cal = Doctrine::getTable('Cal')->find($calId);
  		$this->forward404Unless($this->cal);
  	} elseif ($ctgId) {
  		$ctg = Doctrine::getTable('Category')->find($ctgId);
  		$this->forward404Unless($ctg);
  		$aggregatedCal = $ctg->getAggregatedCal();
  		$this->cal = $aggregatedCal;
  	} elseif ($birthdayCalUserId) {
  		$birthdayCalUser = Doctrine::getTable('User')->find($birthdayCalUserId);
  		//Utils::pp($birthdayCalUser);
  		$this->cal = Cal::getBirthdayCal($birthdayCalUser);
  	} else {
  		return sfView::NONE;
  	}
  	 
  	$events = $this->cal->getEventsForIcal($calType, $partner, $tags, $intelLabel, $intelValue, $remider);

  	$export = new ICalExporter();
  	$export->setTitle(GeneralUtils::icalEscape($this->cal->getName()));
  	
  	$this->ics = $export->toICal($events);
  	$this->setLayout(false);
  }
  
  public function executeGet(sfWebRequest $request){
    $hash       	= $request->getParameter('hash');

    // Backward competability with the XXX we used to sent
    $userCalId      = null;
    $this->userCal  = null;
    if (is_numeric($hash)) {
      $this->userCal = Doctrine::getTable('UserCal')->find($hash);
    } else if ($hash){
    	// TODO: FOR THE WIDGET - try to get a UserCal by hash (not always we will find)
    	// TODO: problem - the random may get us duplicates, need some kind of a key in JS (ip (generated by PHP) + timestamp)
    	$this->userCal = Doctrine::getTable('UserCal')->getBy(null, null, null, 1, $hash)->getFirst();
    	
    }
    
    // Dont fail if userCal is not known...
    // $this->forward404Unless($this->userCal);
    
    // Backword compatibility - but userCal is preffered
    $dateNow    = date("Y-m-d g:i");
    if ($this->userCal)   {
    	$calType = $this->userCal->getCalType();
    	$this->userCal->setUpdatedAt($dateNow);
    	$this->userCal->save();
    	
    	$userCalId = $this->userCal->getId();
    }
    
    //TODO: ASK YARON
	if (!$partner && $this->userCal && $this->userCal->getPartnerId()) {
		$partner =  $this->userCal->getPartner();
	}
	
    // stop saving this data for now, as we dont do anything with it and its alot
    CalRequestTable::newReq($calId, $calType, $userCalId, $partner, $ctgId, $hash);
    
    //Split action for cache purpose
    $urlParams = substr($request->getUri(), strlen($request->getUriPrefix()));
    
    //http://sportycal.local/cal/get/id/1044/hash/26368/ct/any/sportycal.ics
    $hashParam = '/';
    if ($this->userCal && $this->userCal->getReminder() > 0) $hashParam = '/remider/' . $this->userCal->getReminder() . '/';
    $urlParams = preg_replace('/\/hash\/(.+?)\//', $hashParam, $urlParams);

    $this->redirect(str_replace('/get/', '/getIcs/', $urlParams));
  }


  public function executeForwardTo(sfWebRequest $request)
  {
    $calId          = $request->getParameter('cal');
    $userCalId      = $request->getParameter('ucal');
    $ctgLinkId      = $request->getParameter('link');
    
    
    // userCalId may be 0 (backward competabiliy..) so we use also the calId
    if ($userCalId) {
      $userCal = Doctrine::getTable('UserCal')->find($userCalId);
      $this->forward404Unless($userCal);
    }
    
    $ctgLink = Doctrine::getTable('CategoryLink')->find($ctgLinkId);
    $this->forward404Unless($ctgLink);
    
    CategoryLinkUsageTable::newReq($userCalId, $ctgLinkId);
    
    $this->redirect($ctgLink->getUrl());
  }
  

  public function executeUpdatePartnerDesc(sfWebRequest $request)
  {
  	
  	$this->restrictAccess();
  	
    $calId          	= $request->getParameter('calId');
    $partnerId      	= $request->getParameter('partnerId');
    $partnerDesc      	= $request->getParameter('partnerDesc');
  	
    $this->forward404Unless($calId && $partnerId);
    
	$mysqlTime 	= date( 'Y-m-d H:i:s', time() );
	  	
  	$pDesc = PartnerDescTable::getBy($partnerId, $calId);
  	if (!$pDesc) {
        $pDesc = new PartnerDesc();
        $pDesc->setCalId($calId);
        $pDesc->setPartnerId($partnerId);
  	}
  	$pDesc->setDescription($partnerDesc);
    $pDesc->setUpdatedAt($mysqlTime);
  	
  	$pDesc->save();
	$this->redirect('cal/show?id='.$calId);  	
  }

  public function executeDeletePartnerDesc(sfWebRequest $request)
  {
  	$this->restrictAccess();
  	
    $partnerDescId      = $request->getParameter('pdId');
    $calId      		= $request->getParameter('calId');
    
    $this->forward404Unless($partnerDescId);
    
  	$pDesc = Doctrine::getTable('PartnerDesc')->find($partnerDescId);
  	if ($pDesc) {
        $pDesc->delete();
  	}
	$this->redirect('cal/show?id='.$calId);  	
  }
  
  public function executeFind(sfWebRequest $request)  {
  	$calId 		= SportyCalAPI::getCalId($request);
  	$ctgId 		= SportyCalAPI::getCtgId($request);
  	$tags 		= SportyCalAPI::getTags($request);

  	$partner 	= SportyCalAPI::getValidPartner($request);
  	$starts		= SportyCalAPI::getStartTime($request);
  	$ends		= SportyCalAPI::getEndTime($request, $starts);
  	
  	if (!$partner) return sfView::NONE;
  	
	$addCtg = $request->getParameter('addCtg');
	$addEvents = $request->getParameter('addEvents');
  	
	$cal = null;
	$category = null;
	
  	if ($calId) {
    	$cal = Doctrine::getTable('Cal')->find(array($calId));
    	if ($addCtg) $category = $cal->getCategory();
    } elseif ($ctgId){
    	$category = Doctrine::getTable('Category')->find(array($ctgId));
    	$cal = Cal::getAggregatedCal($category);
    }
	
	if ($cal) {
		//Utils::pp($cal->getEvents());
		
// 		if ($addEvents){
// 			$includeAway = false;
// 	  		if ($calId) $includeAway = true;
// 	  		else {
// 	  			$ctgId = array($ctgId);
// 	  		}
// 			$events = EventTable::getBy(null, $ctgId, $starts, $ends, null, null, null, $includeAway, $calId);
// 		}

		$events = EventTable::filterByTags($cal->getEvents(), $tags);
		
		//Utils::pp($tags);
		//Utils::pp($events);
		
		$cal = $cal->toArray();
		
		if ($events){
			foreach ($events as $event){
				// Currently - no need for Cal as part of the event
				$event->setCal(null);
				$cal['Events'][] = $event->toArray();
			}
		}
		
		
		//needed for AggregatedCal
		if ($category) {
			// Currently - no need for Cal as part of the categories
			// Also - note that DELETED CALS are returned
			$category->setCal(new Doctrine_Collection("cal"));
			
			$cal['Category'] = $category->toArray();
		}
	} else {
		$cal = new stdClass();
	}

    echo json_encode($cal);
    return sfView::NONE;
  }
  
  public function executeApi(sfWebRequest $request)  {
  	$partner = SportyCalAPI::getValidPartner($request);
  	if (!$partner) die("Invalid Partner");

  	$calId = (int) $request->getParameter('calId');
  	$ctgId = (int) $request->getParameter('ctgId');
  	
  	$getTotoCals = $request->getParameter('totoCals');
  	$sportCtgId = $request->getParameter('sportCtgId');
  	
	if (!($calId || $ctgId)) die("Invalid Calendar");
	
	$cal = null;
    if ($calId) {
    	$cal = Doctrine::getTable('Cal')->find(array($calId));
    } elseif ($ctgId){
    	$ctg = Doctrine::getTable('Category')->find(array($ctgId));
    	
    	if ($ctg) $cal = Cal::getAggregatedCal($ctg);
    }

  	$res = new stdClass();
  	if ($cal){
  		$rootCtgImgUrl = '';
  		$category = $cal->getRootCategory();
  		if ($category) $rootCtgImgUrl = GeneralUtils::DOMAIN . $category->getImagePath();
  		
  		$min = false;
  		$minWithLinks = false;
  		if ($getTotoCals) {
  			$minWithLinks = true;
  		}
  		
  		$getEvents = true;
  		if ($getTotoCals) $getEvents = false;
  		
  		$calFlatObj = $cal->getFlatObj($getEvents, $rootCtgImgUrl, $partner, $min, $minWithLinks);
  		
  		if ($getTotoCals) {
  			$res = new stdClass();
  			$res->rootCal = $calFlatObj;
  			
  			//rootCal - get next event
			$rootEvent = $cal->getNextEvent();
			if ($rootEvent) $res->rootCal->nextEvent = $rootEvent;
  			
  			//get aggrigate cal
  			$ctg = Doctrine::getTable('Category')->find(array($cal->getCategoryId()));
  			$aggregatedCal = Cal::getAggregatedCal($ctg);
  			$res->aggregatedCal = $aggregatedCal->getFlatObj(false, $rootCtgImgUrl, $partner, $min, $minWithLinks);
  			
  			//aggrigateCal - get next event
  			$aggEvent = $aggregatedCal->getNextEvent();
			if ($aggEvent) $res->aggregatedCal->nextEvent = $aggEvent;
  			
  			//calsByName
  			$res->cals = array();
  			$calsByName = CalTable::getCals(null, $partner->getId(), array($cal->getName()), true, $cal->getId(), $sportCtgId);
			foreach ($calsByName as $calByName){
				$calByNameCtg = Doctrine::getTable('Category')->find(array($calByName->getCategoryId()));
				$res->cals[$calByNameCtg->getName()] = $calByName->getFlatObj(false, $rootCtgImgUrl, $partner, $min, $minWithLinks);
				
				//get next event
				$nextEvent = $calByName->getNextEvent();
				if ($nextEvent && key_exists($calByNameCtg->getName(), $res->cals)) $res->cals[$calByNameCtg->getName()]->nextEvent = $nextEvent;
			}
			
			if (count($res->cals) == 0) $res->cals = null;
  		} else $res = $calFlatObj;
  	}
  	
  	$this->getResponse()->setHttpHeader('Cache-Control', 'no-cache, must-revalidate');
	$this->getResponse()->setHttpHeader('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT');
  	$this->getResponse()->setHttpHeader('Content-type', 'application/json');

  	echo json_encode($res);
  	return sfView::NONE;
  }

  // Used to provide a simple link to external sites to subscribe to a calendar 
  //http://sportYcal.local/frontend_dev.php/cal/sub/id/134/ct/google/l/cId:9191/ref/winner-5712/sportycal.ics
  //http://sportYcal.local/frontend_dev.php/cal/sub/id/134/ct/outlook/l/cId:9191/ref/winner-5712/sportycal.ics
  //http://sportYcal.local/frontend_dev.php/cal/sub/id/134/ct/any/l/cId:9191/ref/winner-5712/sportycal.ics
  
  // http://sportYcal.com/cal/sub/id/134/ct/google/l/cId:9191/ref/FTBpro/cal.ics
  // http://sportYcal.com/cal/sub/id/134/ct/outlook/l/cId:9191/ref/FTBpro/cal.ics
  // http://sportYcal.com/cal/sub/id/134/ct/any/l/cId:9191/ref/FTBpro/cal.ics
  
  
  
  public function executeSub(sfWebRequest $request)   {
  	  
  	$partner 		= SportyCalAPI::getValidPartner($request);

  	$calId      	= $request->getParameter('id');
  	$ctgId      	= $request->getParameter('ctgId');
  	$calType       	= $request->getParameter('ct');
  	
  	$intelLabel     = $request->getParameter('l');
  	$intelValue     = $request->getParameter('v');
  
  	// currently no need for reminder:
  	$reminder = null;
  	
  	$this->forward404Unless($calId || $ctgId);
  
  
  	$url = null;
  	
  	if ($calId) {
  		$cal = Doctrine::getTable('Cal')->find($calId);
  		$this->forward404Unless($cal);
  		$url = $cal->getIcalUrl($calType);
  	} elseif ($ctgId) {
  		$ctg = Doctrine::getTable('Category')->find($ctgId);
  		$this->forward404Unless($ctg);
  		$cal = Cal::getAggregatedCal($ctg, array(), false);
  		// Dirty trick to make this Cal an aggregated cal
  		//$cal->addThoseEvents(array());
  		$url = $cal->getIcalUrl($calType);
   	}
  
  	
  	$ip 			= Utils::getClientIP();
  	$dateNow    	= date("Y-m-d g:i");
  	 
  	
  	// Save this UserCal
  	$userCal = new UserCal();
  	
  	if ($calId) 			$userCal->setCalId($calId);
  	if ($ctgId) 			$userCal->setCategoryId($ctgId);
  	if ($partner)			$userCal->setPartnerId($partner->getId());
  	if ($intelLabel)		$userCal->setLabel($intelLabel);
  	if ($reminder) 			$userCal->setReminder($reminder);
  	
  	$userCal->setUserId(UserUtils::getLoggedInId());
  	$userCal->setCalType($calType);
  	$userCal->setTakenAt($dateNow);
  	$userCal->setUpdatedAt($dateNow);
  	$userCal->setIpAddress($ip);
  	$userCal->save();
  	$userCalId = $userCal->getId();
  	
  	$url = str_replace("USERCAL", $userCalId, $url);
  	
  	if ($calType == Cal::TYPE_ANY) {
		echo "Use this Url:  $url ";
  		return sfView::NONE;  		
  	} else {
  		$this->redirect($url);
  	}
  	
  }
}

 
