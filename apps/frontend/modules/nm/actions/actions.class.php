<?php
class nmActions extends sfActions{
	public function executeIndex(sfWebRequest $request){

	}
	
	public function executeCalEventsClear(sfWebRequest $request){
		$user = UserUtils::getLoggedIn();
		$cal = Doctrine::getTable('Cal')->find(array($request->getParameter('id')));
		$this->forward404Unless($cal && $cal->isOwner($user), sprintf('Object cal does not exist (%s).', $request->getParameter('id')));
		
		Doctrine::getTable('Event')->deleteBy($cal->getId());
		$this->redirect('/nm/calEdit?id=' . $cal->getId());
	}
	
	public function executeCalCreate(sfWebRequest $request){
		$currCalId = UserUtils::getOrphanCalId();
		if ($currCalId) {
			$user = UserUtils::getLoggedIn();
			$cal = Doctrine::getTable('Cal')->find($currCalId);
			$this->forward404Unless($cal && $cal->isOwner($user), sprintf('Object cal does not exist (%s).', $request->getParameter('id')));
		} else {
			$cal = new Cal();
			$cal->setIsPublic(false);
			$cal->setCategoryId(Category::CTG_NEVER_MISS);
			$cal->setCategoryIdsPath(Category::CTG_NEVER_MISS);
			$cal->save();
		}
		 
		UserUtils::setOrphanCalId($cal->getId());
		 
		$this->redirect('/nm/calEdit?id=' . $cal->getId());
	}
	
	public function executeCalEdit(sfWebRequest $request){
		$user = UserUtils::getLoggedIn();
		$cal = Doctrine::getTable('Cal')->find(array($request->getParameter('id')));
		$this->forward404Unless($cal && $cal->isOwner($user), sprintf('Object cal does not exist (%s).', $request->getParameter('id')));
		$this->cal = $cal;
		
		$event = Doctrine_Query::create()
  			->from('Event e')
			->where('e.cal_id = ?', $cal->getId())
			->fetchOne();
		
		if ($event) 							$tz = GeneralUtils::getTZValue($event->getTz());
		else if (UserUtils::getUserTzValue()) 	$tz = UserUtils::getUserTzValue();
		else 									$tz = 0;
		
		$this->tzFullName = $event ? (GeneralUtils::getUTCStrFromJSTZ($tz) . ' - ' . GeneralUtils::$timezones[$tz]) : null;
		
		$this->form = new NmCalForm();
		$this->form->setDefault('name', $cal->getName());
		$this->form->setDefault('description', $cal->getDescription());
		$this->form->setDefault('tz', $tz);
		
		if ($request->isMethod(sfRequest::POST)){
			$this->form->bind($request->getParameter('cal'));
			if ($this->form->isValid()){
				$this->cal->setName($this->form->getValue('name'));
				$this->cal->setDescription($this->form->getValue('description'));
				$this->cal->save();
				
				$tzName = GeneralUtils::getTZFromJSTZ($this->form->getValue('tz'));
				$q = Doctrine_Query::create()
			        ->update('Event e')
			        ->set('e.tz', '?', $tzName)
			        ->where('cal_id = ?', $this->cal->getId())
					->execute();
				
				$this->redirect('/nm/widget?calId=' . $cal->getId());
			}
		}
	}
	
	/**
	 * 
	 * @param sfWebRequest $request
	 */
	public function executeGetIcs(sfWebRequest $request){
		
		$cal = Doctrine::getTable('Cal')->find(array($request->getParameter('id')));
		$export = new ICalExporter();
		$events = array();

		if ($cal && $cal->getIsPublic()){
			$export->setTitle(GeneralUtils::icalEscape($cal->getName()));
			$events = $this->getFlatCalEvents($cal);
		}
		

		$this->ics = $export->toICal($events);
		$this->setLayout(false);
	}
	
	public function executeCalEvents(sfWebRequest $request){
		$user = UserUtils::getLoggedIn();
		$cal = Doctrine::getTable('Cal')->find(array($request->getParameter('id')));
		$this->forward404Unless($cal && $cal->isOwner($user), sprintf('Object cal does not exist (%s).', $request->getParameter('id')));
		
		$isEditing = $request->getParameter('editing') ? true : false;
		if ($isEditing){
			//Create XML
			$xml = new SimpleXMLElement('<xml/>');
			$xmlData = $xml->addChild('data');
	
			$ids = explode(',', $request->getParameter('ids'));
			
			$tz = UserUtils::getUserTZ() ? UserUtils::getUserTZ() : null;
			$dateStr = date('Y-m-d H:i:s');
			foreach ($ids as $id){
				$xmlAction = $xmlData->addChild('action');
					
				$status = $request->getParameter($id . '_!nativeeditor_status');
				$name = $request->getParameter($id . '_text');
				$description = $request->getParameter($id . '_details');
				$location = $request->getParameter($id . '_location');
				$startDate = $request->getParameter($id . '_start_date');
				$endDate = $request->getParameter($id . '_end_date');
				
				//Recurring events
				$recType = $request->getParameter($id . '_rec_type');
				$pid = $request->getParameter($id . '_event_pid');
				$length = $request->getParameter($id . '_event_length');
				
				if ($status == 'deleted'){
					$event = Doctrine::getTable('Event')->find($id);
					
					if ($event->getCalId() != $cal->getId()) continue;
					
					$event->delete();
				} else {
					if ($status == 'inserted'){
						$event = new Event();
						$event->setCalId($cal->getId());
						$event->setCreatedAt($dateStr);
					} else if ($status == 'updated'){
						$event = Doctrine::getTable('Event')->find($id);
						if ($event->getCalId() != $cal->getId()) continue;
					}
					
					$event->setName($name);
					$event->setDescription($description);
					$event->setLocation($location);
					$event->setStartsAt($startDate);
					$event->setEndsAt($endDate);
					$event->setUpdatedAt($dateStr);
					$event->setTz($tz);
					
					
					//recurring events
					if ($recType) $event->setRecType($recType);
					if ($length) $event->setLength($length);
					if ($pid) $event->setPid($pid);

					$event->save();
					
				}

				$xmlAction->addAttribute('type', $status);
				$xmlAction->addAttribute('sid', $id);
				$xmlAction->addAttribute('tid', $event->getId());
			}
			
			$this->xml = $xml;
			$this->setLayout(false);
		} else {
			$events = $cal->getEvents();
	
			$res = array('data' => array());
			$res['data'] = $this->getFlatCalEvents($cal);
	
			echo json_encode($res);
			return sfView::NONE;
		}
	}
	
	private function getFlatCalEvents($cal){
		$events = array();
		
		$calEvents = $cal->getEvents();

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
	
	public function executeWidget(sfWebRequest $request){
		$calId = $request->getParameter('calId');
		$this->calId = $calId ? $calId : 1;
		
		$this->form = new NmRegisterForm();
		if ($request->isMethod('post')){
			$this->forward404Unless($orphanCal = Doctrine::getTable('Cal')->find(array(UserUtils::getOrphanCalId())), sprintf('Object cal does not exist (%s).', UserUtils::getOrphanCalId()));
			
			$this->form->bind($request->getParameter('register'));
			if ($this->form->isValid()){
				
				$now = date('Y-m-d H:i:s');
				$rootName = $this->form->getValue('company_name') ? $this->form->getValue('company_name') : $this->form->getValue('full_name');
				
				//Create user
				$user = new User();
				$user->setFullName($this->form->getValue('full_name'));
				$user->setEmail($this->form->getValue('email'));
				$user->setPass($this->form->getValue('password'));
				$user->setType(User::TYPE_PARTNER);
				$user->setCreatedAt($now);
				$user->setLastLoginDate($now);
				$user->save();
				
				//Create Partner
				$partner = new Partner();
				$partner->setName($rootName);
				$partner->setHash($user->getId()); //TODO: replace with nice hash
				//TODO: add timezone
				$partner->Save();
				
				//Create PartnerUser
				$partnerUser = new PartnerUser();
				$partnerUser->setPartnerId($partner->getId());
				$partnerUser->setUserId($user->getId());
				$partnerUser->save();
				
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
				$partnerDesc->setWebsite($this->form->getValue('website'));
				$partnerDesc->setCategoryId($category->getId());
				$partnerDesc->setCalId($orphanCal->getId());
				$partnerDesc->save();
				
				//Set orphan cal parent
				$orphanCal->setByUserId($user->getId());
				$orphanCal->setIsPublic(true);
				$orphanCal->save();
				
				//clear from session
				UserUtils::setOrphanCalId(null);
				
				UserUtils::logUserIn($user);
			}
		}
		
		$this->user = UserUtils::getLoggedIn();
	}
}