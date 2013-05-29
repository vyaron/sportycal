<?php
class nmActions extends sfActions{
	public function executeIndex(sfWebRequest $request){

	}
	
	public function executeCalList(sfWebRequest $request){
		$user = UserUtils::getLoggedIn();
		if (!$user) $this->redirect('partner/login');
		
		$cals = array();
		
		$cals = $user->getCals();
		
		$this->cals = $cals;
	}
	
	public function executeCalDelete(sfWebRequest $request){
		$user = UserUtils::getLoggedIn();
		$cal = Doctrine::getTable('Cal')->find(array($request->getParameter('id')));
		$this->forward404Unless($cal && $cal->isOwner($user), sprintf('Object cal does not exist (%s).', $request->getParameter('id')));
		
		//$cal->delete();
		$dateNow = date("Y-m-d g:i");
		$cal->setDeletedAt($dateNow);
		$cal->save();
		
		/*
		$refererUrl = UserUtils::getRefererUrl();	
		if ($refererUrl) {
			$this->redirect($refererUrl);
			serUtils::setRefererUrl(null);
		} else $this->redirect('nm/calList');
		*/
		
		$this->redirect('nm/calList');
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
				
				//TODO: get root ctg AND website (old users come from sportycal)
				if ($user && !$this->cal->isOwner($user)) $this->cal->setAdoptive($user);
				
				$this->redirect('/nm/widget?calId=' . $cal->getId());
			}
		}
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
			$res = array('data' => array());
			$res['data'] = $cal->getEventsForScheduler();
	
			echo json_encode($res);
			return sfView::NONE;
		}
	}
	
	public function executeWidget(sfWebRequest $request){
		$user = UserUtils::getLoggedIn();
		$this->calId = $request->getParameter('calId');
		$this->forward404Unless($cal = Doctrine::getTable('Cal')->find(array($this->calId)), sprintf('Object cal does not exist (%s).', $this->calId));

		$this->form = new NmRegisterForm();
		
		if ($user){
			$cal->setAdoptive($user);
		} else {
			if ($request->isMethod('post')){
				$this->form->bind($request->getParameter('register'));
				if ($this->form->isValid()){
					$now = date('Y-m-d H:i:s');
					$rootName = $this->form->getValue('company_name') ? $this->form->getValue('company_name') : $this->form->getValue('full_name');
					$website = $this->form->getValue('website');
			
					//Create user
					$user = new User();
					$user->setFullName($this->form->getValue('full_name'));
					$user->setEmail($this->form->getValue('email'));
					$user->setPass($this->form->getValue('password'));
					$user->setType(User::TYPE_PARTNER);
					$user->setCreatedAt($now);
					$user->setLastLoginDate($now);
					$user->save();
			
					UserUtils::logUserIn($user);
			
					$cal->setAdoptive($user, $rootName, $website);
				}
			}
		}
		
		$this->calId = $calId;
		$this->user = UserUtils::getLoggedIn();
	}
}