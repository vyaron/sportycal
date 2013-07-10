<?php
class nmActions extends sfActions{
	public function executeIndex(sfWebRequest $request){
		$this->getResponse()->setSlot('homepage', true);
		$this->calsDownloadedCount = CalRequestTable::getCount();
		
		if ($this->calsDownloadedCount > 100) $this->calsDownloadedCount -= 100;
	}
	
	/*
	public function executeLicenceWizard(sfWebRequest $request){
		$this->getResponse()->setSlot('pricing', true);
		
		$user = UserUtils::getLoggedIn();
		$this->paymentPlanCode = $request->getParameter('c');
		
		if (!$user){
			$this->redirect('/nm/loginAndRegister/c/' . $this->paymentPlanCode);
		} else {
			$partner = $user->getPartner();
			if ($partner){
				$this->externalUserId = $partner->tryToGetExternalUserId();
				$this->token = Licensario::getToken($this->externalUserId);
				
			}
		}

		$this->forward404Unless(($this->externalUserId && $this->token && $this->paymentPlanCode));
	}
	
	public function executeLoginAndRegister(sfWebRequest $request){
		$this->code = $request->getParameter('c');
		
		$this->loginForm = new LoginForm();
		$this->registerForm = new NmRegisterForm();
	}
	*/
	
	
	public function executePricing(sfWebRequest $request){
		$this->getResponse()->setSlot('pricing', true);
		
		$this->setTemplate('comingSoon', 'nm');
	}
	
	public function executeCaseStudies(sfWebRequest $request){
		$this->getResponse()->setSlot('caseStudies', true);
		$this->setTemplate('comingSoon', 'nm');
	}
	
	//TODO: check defarent Timezones cals
	public function executeImportCal(sfWebRequest $request){
		$user = UserUtils::getLoggedIn();
		$cal = Doctrine::getTable('Cal')->find(array($request->getParameter('id')));
		$this->forward404Unless($cal && $cal->isOwner($user), sprintf('Object cal does not exist (%s).', $request->getParameter('id')));
		
		
		$tz = UserUtils::getUserTZ() ? UserUtils::getUserTZ() : null;
		
		//$content = file_get_contents('E:/temp/cal.ics');
		//$export = new ICalExporter();
		//$eventsHash = $export->toHash($content);
		//Utils::pp($eventsHash);
		
		$res = array('success' => false, 'msg' => 'File not supported!');
		if ($request->isMethod('post')){
			$file = $request->getFiles('file');
			
			if (key_exists('tmp_name', $file) && $file['type'] == 'text/calendar'){
				$content = file_get_contents($file['tmp_name']);
				$export = new ICalExporter();
				$eventsHash = $export->toHash($content);
				
				$childEventsHash = array();
				
				$collectionEvent = new Doctrine_Collection('Event');
				foreach ($eventsHash as $eventHash){
					$startDate = $eventHash['start_date'];
					$endDate = $eventHash['end_date'];
					$description = (key_exists('description', $eventHash) && !empty($eventHash['description'])) ? $eventHash['description'] : null;
					$location = (key_exists('location', $eventHash) && !empty($eventHash['location'])) ? $eventHash['location'] : null;
					$recType = (key_exists('rec_type', $eventHash) && !empty($eventHash['rec_type'])) ? $eventHash['rec_type'] : null;
					$length = (key_exists('event_length', $eventHash) && !empty($eventHash['event_length'])) ? $eventHash['event_length'] : null;
					
					if ($tz){
						$startDate = GeneralUtils::getDateTimeInSpecificTZ($startDate, 'GMT', $tz)->format('Y-m-d H:i');
						$endDate = GeneralUtils::getDateTimeInSpecificTZ($endDate, 'GMT', $tz)->format('Y-m-d H:i');
					}
					
					$event = new Event();
					$event->setCalId($cal->getId());
					$event->setCreatedAt($startDate);
					$event->setName($eventHash['text']);
					$event->setDescription($description);
					$event->setLocation($location);
					$event->setTz($tz);
					$event->setStartsAt($startDate);
					$event->setEndsAt($endDate);
					$event->setUpdatedAt($startDate);
					$event->setRecType($recType);
					$event->setLength($length);
					
					if ($eventHash['event_pid'] == 0) $collectionEvent->add($event, $eventHash['id']);
					else $childEvents[$eventHash['event_id']] = $event;
				}

				$collectionEvent->save();
				
				$collectionChildEvent = new Doctrine_Collection('Event');
				foreach ($childEvents as $eventHashId => $childEvent){
					$eventHash = $eventsHash[$eventHashId];
					$parentEvent = $collectionEvent->get($eventHash['event_pid']);
					
					if (!$parentEvent) continue;
					
					$childEvent->setPid($parentEvent->getId());
						
					$collectionChildEvent->add($childEvent, $eventHashId);
				}
				$collectionChildEvent->save();
				
				$res['success'] = true;
				$res['msg'] = ($collectionEvent->count() + $collectionChildEvent->count()) . ' Events imported';
			}
		}
		
		echo json_encode($res);
		return sfView::NONE;
	}
	
	public function executeCalList(sfWebRequest $request){
		$this->getResponse()->setSlot('calList', true);
		
		$user = UserUtils::getLoggedIn();
		if (!$user) $this->redirect('partner/login');
		
		$maxSubcribers = null;
		$partner = $user->getPartner();
		if ($partner) $maxSubcribers = $partner->getMaxSubscribers();
		
		$offset = $request->getParameter('p', 0);
		
		$calList = CalTable::getCalList($user->getId(), $offset);
		
		$this->calList = $calList;
		$this->maxSubcribers = $maxSubcribers;
	}
	
	public function executeCalDelete(sfWebRequest $request){
		$res = array('success' => false, 'msg' => 'Calendar not deleted!');
		
		$user = UserUtils::getLoggedIn();
		$cal = Doctrine::getTable('Cal')->find(array($request->getParameter('id')));
		
		if ($cal && $cal->isOwner($user)){
			$dateNow = date("Y-m-d g:i:s");
			$cal->setDeletedAt($dateNow);
			$cal->save();
			
			$res['success'] = true;
			$res['msg'] = $cal->getName() . ' calendar deleted.';
		}
		
		echo json_encode($res);
		return sfView::NONE;
	}
	
	public function executeCalRestore(sfWebRequest $request){
		$res = array('success' => false, 'msg' => 'Calendar not restored!');
		
		$user = UserUtils::getLoggedIn();
		$cal = Doctrine::getTable('Cal')->find(array($request->getParameter('id')));

		if ($cal && $cal->isOwner($user)){
			$cal->setDeletedAt(null);
			$cal->save();
			
			$res['success'] = true;
			$res['msg'] = $cal->getName() . ' calendar restored.';
		}
	
		echo json_encode($res);
		return sfView::NONE;
	}
	
	public function executeCalEventsClear(sfWebRequest $request){
		$user = UserUtils::getLoggedIn();
		$cal = Doctrine::getTable('Cal')->find(array($request->getParameter('id')));
		$this->forward404Unless($cal && $cal->isOwner($user), sprintf('Object cal does not exist (%s).', $request->getParameter('id')));
		
		Doctrine::getTable('Event')->deleteBy($cal->getId());
		
		$dateNow = date("Y-m-d g:i:s");
		$cal->setUpdatedAt($dateNow);
		$cal->save();
		
		$this->redirect('/nm/calEdit/id/' . $cal->getId());
	}
	
	public function executeCalCreate(sfWebRequest $request){
		$dateNow = date("Y-m-d g:i");
		
		$currCalId = UserUtils::getOrphanCalId();
		if ($currCalId) {
			$user = UserUtils::getLoggedIn();
			$cal = Doctrine::getTable('Cal')->find($currCalId);
			$this->forward404Unless($cal && $cal->isOwner($user), sprintf('Object cal does not exist (%s).', $request->getParameter('id')));
		} else {
			$cal = new Cal();
			$cal->setCreatedAt($dateNow);
			$cal->setUpdatedAt($dateNow);
			$cal->setIsPublic(false);
			$cal->setCategoryId(Category::CTG_NEVER_MISS);
			$cal->setCategoryIdsPath(Category::CTG_NEVER_MISS);
			$cal->save();
		}
		 
		UserUtils::setOrphanCalId($cal->getId());
		 
		$this->redirect('/nm/calEdit/id/' . $cal->getId());
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
		
		//Update TZ on session
		UserUtils::setUserTZ(GeneralUtils::$timezones[$tz]);
		UserUtils::setUserTzValue($tz);
		
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
				
				$this->redirect('/nm/widget/calId/' . $cal->getId());
			}
		}
	}
	
	public function executeCalEvents(sfWebRequest $request){
		$user = UserUtils::getLoggedIn();
		$cal = Doctrine::getTable('Cal')->find(array($request->getParameter('id')));
		$this->forward404Unless($cal && $cal->isOwner($user), sprintf('Object cal does not exist (%s).', $request->getParameter('id')));
		
		$isEditing = $request->getParameter('editing') ? true : false;
		if ($isEditing){
			$dateNow = date("Y-m-d g:i");
			$cal->setUpdatedAt($dateNow);
			$cal->save();
			
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
	
	private function registerForm(sfWebRequest $request, $cal = null){
		$isAjax = $this->getRequest()->isXmlHttpRequest();
		$res = array('success' => false, 'msg' => __('Rgister field!'));
		
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
					
				if ($cal) $cal->setAdoptive($user, $rootName, $website);
				
				if ($isAjax){
					$res['success'] = true;
					$res['msg'] = 'Register success';
					$res['html'] = $this->getPartial('global/topNav', array('user' => $user));
				}
			}
		}
		
		if ($isAjax){
			echo json_encode($res);
		}
	}
	
	public function executeRegister(sfWebRequest $request){
		$this->forward404Unless($this->getRequest()->isXmlHttpRequest());
		$this->form = new NmRegisterForm();
		$this->registerForm($request);
		return sfView::NONE;
	}
	
	public function executeWidget(sfWebRequest $request){
		$user = UserUtils::getLoggedIn();
		
		$this->calId = $request->getParameter('calId');
		$this->language = $request->getParameter('language', NeverMissWidget::DEFAULT_LANGUAGE);
		$this->btnStyle = $request->getParameter('btn-style', NeverMissWidget::DEFAULT_VALUE);
		$this->btnSize = $request->getParameter('btn-size', NeverMissWidget::DEFAULT_VALUE);
		$this->color = $request->getParameter('color', NeverMissWidget::DEFAULT_VALUE);
		
		$this->forward404Unless($cal = Doctrine::getTable('Cal')->find(array($this->calId)), sprintf('Object cal does not exist (%s).', $this->calId));
		
		$this->code = NeverMissWidget::getWidgetCode($cal, $this->language, $this->btnStyle, $this->btnSize, $this->color);
		
		$this->form = new NmRegisterForm();
		
		if ($user) $cal->setAdoptive($user);
		else $this->registerForm($request, $cal);

		$this->user = UserUtils::getLoggedIn();
	}
	
	public function executeSubscribeByMail(sfWebRequest $request){
		$this->forward404Unless($user = UserUtils::getLoggedIn());
		$res = array('success' => false, 'msg' => 'Subscribe by mail failed!');
		
		$message = $request->getParameter('message');
		if (!$message) $message = __('Please click the calendar of your choice');
		
		$cal = Doctrine::getTable('Cal')->find(array($request->getParameter('calId')));
		
		//$cal = new Cal();
		
		if ($cal){
			$mail = new PHPMailer();
			
			//$mail->SMTPDebug = 3;
			//$mail->Debugoutput = 'html';
			
			$env = sfContext::getInstance()->getConfiguration()->getEnvironment();
			if (true || $env == 'dev'){
				//Gmail SMTP
				$mail->IsSMTP();
				$mail->Host       = 'smtp.gmail.com';
				$mail->Port       = 587;
				$mail->SMTPSecure = 'tls';
				$mail->SMTPAuth   = true;
				
				$mail->Username   = sfConfig::get('app_gmail_username');
				$mail->Password   = sfConfig::get('app_gmail_password');
			}

			$mail->SetFrom(sfConfig::get('app_mailinglist_fromEmail'), sfConfig::get('app_mailinglist_fromName'));
			$mail->AddReplyTo(sfConfig::get('app_mailinglist_replyToEmail'), sfConfig::get('app_mailinglist_replyToName'));
				
			$mail->AddAddress($user->getEmail(), $user->getFullName());

			$mail->Subject = 'Subscribe ' . $cal->getName() . ' Calendar By Mail';
			$mail->MsgHTML($this->getPartial('nm/subscribeByMailHtml', array('user' => $user, 'cal' => $cal, 'message' => $message)));
			$mail->AltBody = $this->getPartial('nm/subscribeByMailTxt', array('user' => $user, 'cal' => $cal, 'message' => $message));

			if ($mail->Send()){
				$res['success'] = true;
				$res['msg'] = 'Sen mail to: ' . $user->getEmail();
			}
		}
	
		echo json_encode($res);
		return sfView::NONE;
	}
}