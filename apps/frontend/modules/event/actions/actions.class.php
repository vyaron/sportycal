<?php

/**
 * event actions.
 *
 * @package    evento
 * @subpackage event
 * @author     Yaron Biton
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eventActions extends sfActions
{
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
  
/*  
  public function executeIndex(sfWebRequest $request)
  {
    $this->events = Doctrine::getTable('Event')
      ->createQuery('a')
      ->execute();
  }


  public function executeShow(sfWebRequest $request)
  {
    $this->event = Doctrine::getTable('Event')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->event);
  }
*/

  public function executeNew(sfWebRequest $request)
  {	
    $calId = $request->getParameter('calId');
    $cal = Doctrine::getTable('Cal')->find(array($calId));
    $this->forward404Unless($cal);
    
    $category = $cal->getCategory();
    
    $this->restrictAccessAllowPartners($category);
	    
    $event = new Event();
    $event->setCalId($calId);
    $this->form = new EventForm($event);
	
    
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    
    $event = new Event();
  	$this->form = new EventForm($event);
    $params = $request->getParameter($this->form->getName());

  	$this->forward404Unless($cal = Doctrine::getTable('Cal')->find(array($params['cal_id'])), sprintf('Object cal does not exist (%s).', $params['cal_id']));
    $category = $cal->getCategory();
  	$this->restrictAccessAllowPartners($category);
    
  	$event->setCalId($params['cal_id']);
	
  	$this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($event = Doctrine::getTable('Event')->find(array($request->getParameter('id'))), sprintf('Object event does not exist (%s).', $request->getParameter('id')));
    $this->restrictAccessAllowPartnersForEvent($event);    
    $this->form = new EventForm($event);
    
    //dates
    $this->startsAt = date('d-m-Y H:i', strtotime($event->getStartsAt()));
    $this->endsAt = date('d-m-Y H:i', strtotime($event->getEndsAt()));
    
    //Get tags JSON
    $this->tags = array();
    $tags = $event->getTags();
    if (!is_null($tags)) {
    	$tags = json_decode($tags);
    	if (!empty($tags->countryCodes)) {
    		$this->countryCodes = $tags->countryCodes;
    		if (is_array($this->countryCodes)) $this->countryCodes = implode(',', $this->countryCodes);
    	}
    	if (!empty($tags->languageCodes)) {
    		$this->languageCodes = $tags->languageCodes;
    		if (is_array($this->languageCodes)) $this->languageCodes = implode(',', $this->languageCodes);
    	}
    	
    	$this->tags = (array) $tags;
    }
  }

  public function executeUpdate(sfWebRequest $request)
  {
  	
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($event = Doctrine::getTable('Event')->find(array($request->getParameter('id'))), sprintf('Object event does not exist (%s).', $request->getParameter('id')));
	$this->restrictAccessAllowPartnersForEvent($event);
    $this->form = new EventForm($event);
    
    $this->processForm($request, $this->form);

    $this->startsAt = date('d-m-Y H:i', strtotime($event->getStartsAt()));
    $this->endsAt = date('d-m-Y H:i', strtotime($event->getEndsAt()));
    
    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    
    $eventId = $request->getParameter('id');
    $this->forward404Unless($event = Doctrine::getTable('Event')->find(array($eventId)), sprintf('Object event does not exist (%s).', $eventId));
    
	$this->restrictAccessAllowPartnersForEvent($event);
    
    $calId = $event->getCalId();
    $event->delete();

    $this->redirect('cal/show?id='.$calId);
  }
  
  protected function processForm(sfWebRequest $request, sfForm $form)
  {
  	$params = $request->getParameter($form->getName());
  	$form->bind($params, $request->getFiles($form->getName()));
  	if ($form->isValid())
  	{
  		$event = $form->save();
  		
  		//Remove seconds
  		$startsAt = date('Y-m-d H:i:00', strtotime($event->getStartsAt()));
  		$endsAt = date('Y-m-d H:i:00', strtotime($event->getEndsAt()));
  		
		$event->setStartsAt($startsAt);
		$event->setEndsAt($endsAt);
		
		//Set tags JSON
		$tags = array();
		if (!empty($params['countryCodes'])) $tags['countryCodes'] = explode(',', $params['countryCodes']);
		if (!empty($params['languageCodes'])) $tags['languageCodes'] = explode(',', $params['languageCodes']);
		 
		//Get Custom fields
		if (!empty($params['custom']) && is_array($params['custom'])){
			foreach ($params['custom'] as $i => $custom){
				$key = trim($custom['name']);
				$values = trim($custom['values']);
					
				if (!empty($key) && !empty($values)){
					$values = explode(',', $values);
					$valuesTrim = array();
					foreach ($values as $value){
						$value = trim($value);
							
						if (empty($value)) continue;
							
						$valuesTrim[] = $value;
					}
		
					$tags[$key] = $valuesTrim;
				}
			}
		}
		
		if (!empty($tags)) $event->setTags(json_encode($tags));
		else $event->setTags(null);

		$event->save();
		
  		//$this->redirect('event/edit?id='.$event->getId());
  		$this->redirect('cal/show?id='.$event->getCalId());
  	}
  }
	//http://sportycal.local/frontend_dev.php/event/find?starts=2010-12-21&ends=2010-12-23&ctgId=4&ref=gpi
	//http://sportycal.local/frontend_dev.php/event/find?starts=2010-12-21&ends=2010-12-29&underCtgId=4&ref=gpi  	
	//http://sportycal.local/frontend_dev.php/event/find?starts=1292869800&ends=1293561000&underCtgId=4&ref=gpi
	//http://sportycal.local/frontend_dev.php/event/find?ctgId=4&locId=314&ref=GoPlanIt-4217
  
  public function executeFind(sfWebRequest $request)  {
	$callBack 		= $request->getParameter('callBack');
  	
  	$starts 		= SportyCalAPI::getStartTime($request);
  	$ends 			= SportyCalAPI::getEndTime($request, $starts);
  	$underCtgIds 	= SportyCalAPI::getCtgIds($request);
  	$partner 		= SportyCalAPI::getValidPartner($request);
  	$format 		= SportyCalAPI::getFormat($request, $partner);
  	$locId 			= SportyCalAPI::getLocationId($request);
  	$limit 			= SportyCalAPI::getLimit($request);
	
  	$calId 			= SportyCalAPI::getCalId($request);
	
  	if (!$partner) return sfView::NONE;
	
  	$includeAway = false;
  	if ($calId) $includeAway = true;
  	
  	//echo $starts . " - " . $ends . "<br/>";
	
  	$events = EventTable::getBy(null, $underCtgIds, $starts, $ends, null, $limit, $locId, $includeAway, $calId);

    //echo "found: " . count($events) . "<br/>";  
      
    $jsonEvents = Event::makeJSON($partner->getHash(), $events);  

    if ($format == SportyCalAPI::RESPONSE_FORMAT_JS) {
		// Backward compatability to the events widget on GPI
		// untill we fix it to work with the callback
    	if ($partner->getHash() == "GoPlanIt-4217") {
    	
    		$jsonEvents = addslashes($jsonEvents);
    		$this->jsonEvents = $jsonEvents;
    	
    		$response = $this->getResponse()->setContentType('text/JavaScript');
    	} else {
	    	if (!$callBack) $callBack = SportyCalAPI::CALL_BACK_FUNCION;
	    	echo $callBack . '(' . $jsonEvents . ');';
  			return sfView::NONE; 
    	}
    	
    	
  	}  else {
  		echo $jsonEvents;
  		return sfView::NONE;  		
  	}  

  }
  
  // Sample URL:
  //http://sportycal.local/frontend_dev.php/event/findLocs?ctgIds=3,4&ref=andru
  public function executeFindLocs(sfWebRequest $request)  {
  	
  	$starts 		= SportyCalAPI::getStartTime($request);
  	$ends 			= SportyCalAPI::getEndTime($request, $starts);
  	$underCtgIds 	= SportyCalAPI::getCtgIds($request);
  	$partner 		= SportyCalAPI::getValidPartner($request);
  	$format 		= SportyCalAPI::getFormat($request, $partner);
  	$limit 			= SportyCalAPI::getLimit($request);
  	$countryCode	= SportyCalAPI::getCountryCode($request);
  	$stateCode 		= SportyCalAPI::getStateCode($request);
  	$locType 		= SportyCalAPI::getLocationType($request, $countryCode);
  	
  	if (!$partner) return sfView::NONE;
  	
  	$locationsWithEvents = LocationTable::getBy($underCtgIds, $starts, $ends, $countryCode, $stateCode);
  
    $jsonLocations = Location::makeJSON($locationsWithEvents, $locType, $limit);  

    if ($format == SportyCalAPI::RESPONSE_FORMAT_JS) {
       	$jsonLocations = addslashes($jsonLocations);
    	$this->jsonLocations = $jsonLocations;
    	$response = $this->getResponse()->setContentType('text/JavaScript');
  	}  else {
  		echo $jsonLocations;
  		return sfView::NONE;  		
  	}  

  }
  
  private function restrictAccessAllowPartnersForEvent($event) {
  	$cal = Doctrine::getTable('Cal')->find(array($event->getCalId()));
    $category = $cal->getCategory();
  	$this->restrictAccessAllowPartners($category);    
  	
  }
  
  
}

