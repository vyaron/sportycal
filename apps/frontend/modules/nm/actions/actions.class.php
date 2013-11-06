<?php
class nmActions extends sfActions{
	public function executeIndex(sfWebRequest $request){
		$this->getResponse()->setSlot('homepage', true);
		$this->calsDownloadedCount = CalRequestTable::getCount();
		
		if ($this->calsDownloadedCount > 100) $this->calsDownloadedCount -= 100;
		
		$isReachedMaxCalendars = false;
		$user = UserUtils::getLoggedIn();
		if ($user) {
			$partner = $user->getPartner();
			if ($partner) $isReachedMaxCalendars = $partner->isReachedMaxCalendars();
		}
		
		$this->isReachedMaxCalendars = $isReachedMaxCalendars;
	}
	
	public function executeGetAndroidCal(sfWebRequest $request){
		$h = (int) $request->getParameter('h');
		$fn = $request->getParameter('fn', 'Calendar');

		$this->forward404Unless($userCal = Doctrine::getTable('UserCal')->find($request->getParameter('h')));
		
		$partner = $userCal->getPartner();
		if ($partner) {
			$language = $partner->getLang();
			$this->isRTL = ($language == NeverMissWidget::LANGUAGE_HEBREW) ? true : false;
			sfContext::getInstance()->getI18N()->setCulture($language);
		}
		
		$this->userCalId = $userCal->getId();
		$this->fileName = $fn;
	}
	
	public function executeContact(sfWebRequest $request){
		$form = new ContactForm();
		
		if ($request->isMethod('post')){
			$res = array('success' => false, 'msg' => __('Message not send'));

			$params = $request->getParameter('contact');
			$params['subject'] = 'iNeverMiss contact';
			$params['ip_address'] = Utils::getClientIP();
			$params['created_at'] = date('Y-m-d H:i:s');
			
			$form->bind($params);
			
			//Utils::pa($form->isValid());
			//Utils::pp($form->getErrorSchema());
			
			if ($form->isValid()){
				$contact = $form->save();
				
				$mail = new PHPMailer();
				$mail->IsSMTP();
				$mail->Host       = 'smtp.gmail.com';
				$mail->Port       = 587;
				$mail->SMTPSecure = 'tls';
				$mail->SMTPAuth   = true;
				
				$mail->Username   = sfConfig::get('app_gmail_username');
				$mail->Password   = sfConfig::get('app_gmail_password');
				
				//$mail->AddAddress($contact->getSenderEmail(), $contact->getSenderName());
				$mail->SetFrom($contact->getSenderEmail(), $contact->getSenderName());
				$mail->AddReplyTo($contact->getSenderEmail(), $contact->getSenderName());
				$mail->AddAddress(sfConfig::get('app_mailinglist_fromEmail'), sfConfig::get('app_mailinglist_fromName'));
				///$mail->AddAddress($contact->getSenderEmail(), $contact->getSenderName());
				//$mail->AddAddress('vyaron@gmail.com', 'Yaron Biton');
				
				$mail->Subject = $contact->getSubject();
				
				$txt = 'From: ' . $contact->getSenderName() . "\n";
				$txt .= 'Email: ' . $contact->getSenderEmail() . "\n";
				$txt .= 'Phone: ' . $contact->getPhone() . "\n";
				$txt .= 'Message: ' . $contact->getMessage();
				
				$mail->MsgHTML(nl2br($txt));
				$mail->AltBody = $txt;
				
				$mail->Send();
					
				$res = array('success' => true, 'msg' => __('Thank you for your contact'));
			}
			
			echo json_encode($res);
			
			return sfView::NONE;
		}
		
		$this->form = $form;
	}
	
	public function executeTerms(sfWebRequest $request){
		$nlo = $request->getParameter('nlo');
		if ($nlo) $this->setLayout(false);
	}
	
	public function executePaypalIpn(sfWebRequest $request){
		$reqData = $_REQUEST;
		
		/*
		$logPath = sfConfig::get('sf_log_dir').'/paypal.log';
		$custom_logger = new sfFileLogger(new sfEventDispatcher(), array('file' => $logPath));
		$json = json_encode($reqData);
		$custom_logger->info($json);
		*/
		
		$isTest = (isset($reqData['test_ipn']) && $reqData['test_ipn']) ? true : false;
		
		if (PayPal::isIpnVerified($reqData, $isTest)){
			$partner = null;
			$partnerId = PayPal::getPartnerId($reqData['custom']);
			if ($partnerId) $partner = Doctrine::getTable('Partner')->find(array($partnerId));
			
			if ($partner){
				$paypalIpn = new PaypalIpn();
				
				$paypalIpn->setPartnerId($partner->getId());
				$paypalIpn->setIpnCode($reqData['ipn_track_id']);
				$paypalIpn->setTransactionCode($reqData['subscr_id']);
				$paypalIpn->setStatus($reqData['payment_status']);
				$paypalIpn->setResData(json_encode($reqData));
				$paypalIpn->setIsTest($isTest);
				$paypalIpn->setCreatedAt(date('Y-m-d h:i:s'));
				
				$paypalIpn->save();
				
				if ($paypalIpn->isCompleted()) PartnerLicence::setLicence($reqData['custom'], $reqData['item_number'], $reqData['payment_date']);
			}
		}
		
		return sfView::NONE;
	}
	
	//TODO: check PDT - https://developer.paypal.com/webapps/developer/docs/classic/paypal-payments-standard/integration-guide/paymentdatatransfer/
	public function executeCheckoutSuccess(sfWebRequest $request){
		//Utils::pp($request->getPostParameters());
		$custom = $request->getParameter('custom');
		$itemNumber = $request->getParameter('item_number');

		$paymentDate = $request->getParameter('subscr_date');
		$paymentId = $request->getParameter('subscr_id');
		
		PartnerLicence::setLicence($custom, $itemNumber, $paymentDate, $paymentId, true);
		
		$this->redirect('/nm/calList');
	}
	
	public function executeCheckout(sfWebRequest $request){
		$user = UserUtils::getLoggedIn();
		$url = null;
		
		$planCode = $request->getParameter('c');
		
		if ($planCode){
			if (!$user || !$partner = $user->getPartner()) {
				UserUtils::setRefererUrl('/nm/checkout/c/' . $planCode);
				$url = '/nm/register';
			} else {
				$currLicence = $partner->getLicence();
				$newLicence = new PartnerLicence($planCode, date('Y-m-d H:i:s', strtotime('+1 month +1 day')));
				
				if ($newLicence->isBetterThan($currLicence)) $url = PayPal::getSubscriptionUrl($planCode, $partner);
				else $url = '/nm/calList';
			}
		}
		
		
		if ($url) $this->redirect($url);
		else $this->redirect('/');
	}
	
	public function executePricing(sfWebRequest $request){
		$this->getResponse()->setSlot('pricing', true);
		
		$user = UserUtils::getLoggedIn();
		if (!$user || $user && !($user->getEmail() == 'uzi@jivygroup.com' || $user->isMaster())) $this->setTemplate('comingSoon', 'nm');
	}
	
	//TODO: check defarent Timezones cals
	public function executeImportCal(sfWebRequest $request){
		$user = UserUtils::getLoggedIn();
		$cal = Doctrine::getTable('Cal')->find(array($request->getParameter('id')));
		$this->forward404Unless($cal && $cal->isOwner($user), sprintf('Object cal does not exist (%s).', $request->getParameter('id')));
		
		
		$tz = UserUtils::getUserTZ() ? UserUtils::getUserTZ() : null;
		
		$currEventsCount = EventTable::getEvents($cal->getId(), true);
		
		//Get maxEvents
		$this->setMaxEvents($request, $user);
		
		$res = array('success' => false, 'msg' => __('File not supported!'), 'isReachedMaxEvents' => false);
		if ($request->isMethod('post')){
			$file = $request->getFiles('file');
			
			if (key_exists('tmp_name', $file) && $file['type'] == 'text/calendar'){
				$res['success'] = true;
				
				$content = file_get_contents($file['tmp_name']);
				$export = new ICalExporter();
				$eventsHash = $export->toHash($content);
				
				$childEventsHash = array();
				$childEvents = array();
				
				$collectionEvent = new Doctrine_Collection('Event');
				foreach ($eventsHash as $i => $eventHash){
					if ($this->maxEvents != PartnerLicence::UNLIMITED && ($collectionEvent->count() + $currEventsCount) >= $this->maxEvents) {
						$res['isReachedMaxEvents'] = true;
						break;
					}
					
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
				
				$res['msg'] = __('%count% Events imported', array('%count%' => ($collectionEvent->count() + $collectionChildEvent->count())));
			}
		}
		
		echo json_encode($res);
		return sfView::NONE;
	}
	
	public function executeCalList(sfWebRequest $request){
		$this->getResponse()->setSlot('calList', true);
		
		$user = UserUtils::getLoggedIn();
		if (!$user || !$partner = $user->getPartner()) $this->redirect('partner/login');
		
		
		$offset = $request->getParameter('p', 0);
		$limit = 11;
		$calList = CalTable::getCalList($user->getId(), $offset, $limit);
		
		$licenceError = null;
		$licenceErrors = array();
		
		
		$isReachedMaxSubscribers = $partner->isReachedMaxSubscribers();
		if ($isReachedMaxSubscribers) $licenceErrors[] = 'subscribers';
		
		$isReachedMaxCalendars = $partner->isReachedMaxCalendars();
		if ($isReachedMaxCalendars) $licenceErrors[] = 'calendars';
		
		$isReachedMaxEvents = $partner->isReachedMaxEvents();
		if ($isReachedMaxEvents) $licenceErrors[] = 'events';
		
		//$licenceErrors = array('subscribers', 'calendars', 'events');
		//$licenceErrors = array('calendars', 'events');
		//$licenceErrors = array('subscribers');
		//$licenceErrors = array();
		
		$errorsCount = count($licenceErrors);
		if ($errorsCount){
			if ($errorsCount == 1) $licenceError = __('You\'ve reached your %name% limit', array('%name%' =>$licenceErrors[0]));
			else if ($errorsCount == 2) {
				$licenceError = __('You\'ve reached both your %name1% limit and your %name2% limit', array('%name1%' => $licenceErrors[0], '%name2%' => $licenceErrors[1]));
			} else {
				$licenceError = __('You\'ve reached your ');
				
				foreach ($licenceErrors as $i => $name){
					if ($i != 0) $licenceError .= ', ';
					$licenceError .= __('%name% limit', array('%name%' => $name));
					
				}
			}
			
			$licenceError .= __(', please upgrade your account');
		}
		
		$this->licenceError = $licenceError;
		$this->isClosedMaxSubscribers = $partner->isClosedMaxSubscribers();
		$this->subscribers = $partner->getSubscribers();
		$this->calList = $calList;
		
		$this->licenece = $partner->getLicence();
		$this->isReachedMaxCalendars = $isReachedMaxCalendars;
		$this->isReachedMaxSubscribers = $isReachedMaxSubscribers;
	}
	
	public function executeCalDelete(sfWebRequest $request){
		$res = array('success' => false, 'msg' => __('Calendar not deleted!'));
		
		$user = UserUtils::getLoggedIn();
		$cal = Doctrine::getTable('Cal')->find(array($request->getParameter('id')));
		
		if ($cal && $cal->isOwner($user)){
			$dateNow = date("Y-m-d g:i:s");
			$cal->setDeletedAt($dateNow);
			$cal->save();
			
			$res['success'] = true;
			$res['msg'] = __('%name% calendar deleted.', array('%name%' => $cal->getName()));
		}
		
		echo json_encode($res);
		return sfView::NONE;
	}
	
	public function executeCalRestore(sfWebRequest $request){
		$res = array('success' => false, 'msg' => __('Calendar not restored!'));
		
		$user = UserUtils::getLoggedIn();
		$cal = Doctrine::getTable('Cal')->find(array($request->getParameter('id')));

		if ($cal && $cal->isOwner($user)){
			$partner = $user->getPartner();
			if ($partner->isReachedMaxCalendars()){
				$res['msg'] = __('Calendar not restored - reached max calendars');
			} else {
				$cal->setDeletedAt(null);
				$cal->save();
					
				$res['success'] = true;
				$res['msg'] = __('%name% calendar restored.', array('%name%' => $cal->getName()));
			}
		}
	
		echo json_encode($res);
		return sfView::NONE;
	}
	
	public function executeCalEventsClear(sfWebRequest $request){
		$res = array('success' => false, 'msg' => __('Clear event failed'));
		
		$user = UserUtils::getLoggedIn();
		$cal = Doctrine::getTable('Cal')->find(array($request->getParameter('id')));
		if ($cal && $cal->isOwner($user)){
			Doctrine::getTable('Event')->deleteBy($cal->getId());
			
			$dateNow = date("Y-m-d g:i:s");
			$cal->setUpdatedAt($dateNow);
			$cal->save();
			
			$res['success'] = true;
			$res['msg'] = __('Events cleared');
		}
		
		echo json_encode($res);
		return sfView::NONE;
	}
	
	public function executeCalCreate(sfWebRequest $request){
		$isPopup = $request->getParameter('isPopup');
		$wixInstance = $request->getParameter('wixInstance');
		
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
		
		$url = '/nm/calEdit/id/' . $cal->getId();
		if ($isPopup) $url .= '/isPopup/' . $isPopup;
		if ($wixInstance) $url .= '/wixInstance/' . $wixInstance;
		
		$this->redirect($url);
	}
	
	private function setMaxEvents(sfWebRequest $request, $user){
		$this->wixInstance = $request->getParameter('wixInstance');
		
		$partnerLicence = new PartnerLicence(PartnerLicence::DEFAULT_PLAN, date('d-m-Y', strtotime("+1 month")));
		
		if ($this->wixInstance){
			$data = Wix::getInstanceData($this->wixInstance);
			if (Wix::isPremium($data)){
				$partnerLicence = new PartnerLicence(PartnerLicence::PLAN_B, date('d-m-Y', strtotime("+1 month")));
			}
		} else if ($user) {
			$partner = $user->getPartner();
			if ($partner) $partnerLicence = $partner->getLicence();
		}
		
		$this->maxEvents = $partnerLicence->getMaxEvents();
	}
	
	public function executeCalEdit(sfWebRequest $request){
		$user = UserUtils::getLoggedIn();
		$cal = Doctrine::getTable('Cal')->find(array($request->getParameter('id')));
		$this->forward404Unless($cal && $cal->isOwner($user), sprintf('Object cal does not exist (%s).', $request->getParameter('id')));
		$this->cal = $cal;
		
		//Get maxEvents
		$this->setMaxEvents($request, $user);

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
		
		//$this->tzFullName = $event ? (GeneralUtils::getUTCStrFromJSTZ($tz) . ' - ' . GeneralUtils::$timezones[$tz]) : null;
		
		$this->form = new NmCalForm();
		$this->form->setDefault('name', $cal->getName());
		$this->form->setDefault('description', $cal->getDescription());
		$this->form->setDefault('tz', $tz);
		
		
		if ($request->isMethod(sfRequest::PUT)){
			$res = array('success' => false, 'msg' => __('calendar not updated'));
			
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
				
				//case user not redirect to widget
				if ($user && !$this->cal->getByUserId() && $this->cal->isOwner($user)) $this->cal->setAdoptive($user);
				
				$res['success'] = true;
				$res['msg'] = 'Calendar updated';
				
				echo json_encode($res);
				return sfView::NONE;
				//$this->redirect('/nm/widget/calId/' . $cal->getId());
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
				$reminder = $request->getParameter($id . '_reminder');
				
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
					$event->setReminder($reminder);
					
					
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
	
	private function registerForm($data){
		$res = array('success' => false, 'msg' => __('Register failed'));
		
		if ($data){
			$this->registerForm->bind($data);
			
			if ($this->registerForm->isValid()){
				$now = date('Y-m-d H:i:s');
				$rootName = $this->registerForm->getValue('full_name');
				$website = $this->registerForm->getValue('website');
					
				//Create user
				$user = new User();
				$user->setFullName($this->registerForm->getValue('full_name'));
				$user->setEmail($this->registerForm->getValue('email'));
				$user->setPass($this->registerForm->getValue('password'));
				$user->setType(User::TYPE_PARTNER);
				$user->setCreatedAt($now);
				$user->setLastLoginDate($now);
				$user->save();
					
				UserUtils::logUserIn($user);
				
				//if ($user->getType() != User::TYPE_PARTNER || $user->getType() != User::TYPE_MASTER) $partner = $user->createPartner($rootName, $website);
				$partner = $user->createPartner($rootName, $website);
				
				
				$res['success'] = true;
				$res['msg'] = 'Registration was successful';
			} else {
				
				foreach ($this->registerForm->getErrorSchema() as $name => $errorsSchema){
					foreach ($errorsSchema as $name => $validatorError){
						$renderName = $this->registerForm[$name]->renderName();
						$res['errors'][$renderName] = $validatorError->getMessageFormat();
						break;
					}
				}
			}
		}
		return $res;
	}
	
	public function executeRegister(sfWebRequest $request){
		$user = UserUtils::getLoggedIn();

		$this->registerForm = new NmRegisterForm();
		$this->loginForm = new LoginForm();
		$this->isShowLogin = false;
		
		if (!$user && $request->isMethod('post') && $registerData = $request->getParameter('register')) {
			$res = $this->registerForm($registerData);
			
			echo json_encode($res);
			return sfView::NONE;
		}
		
		$user = UserUtils::getLoggedIn();
		if ($user){
			$refererUrl = UserUtils::getRefererUrl();
			if ($refererUrl) {
				UserUtils::setRefererUrl(null);
				$this->redirect($refererUrl);
			} else {
				if (sfConfig::get('app_domain_isNeverMiss')) $this->redirect('/nm/calList');
				else $this->redirect('@homepage');
			}
		}
		
		$this->setTemplate('loginAndRegister', 'nm');
	}
	
	public function executeSignUpToSave(sfWebRequest $request){
		$user = UserUtils::getLoggedIn();
		
		$this->calId = $request->getParameter('calId');
		$this->forward404Unless($cal = Doctrine::getTable('Cal')->find(array($this->calId)), sprintf('Object cal does not exist (%s).', $this->calId));
		
		if (!$user){
			$this->code = NeverMissWidget::getWidgetCode($cal, $this->language, $this->btnStyle, $this->btnSize, $this->color, $this->upcoming);
			
			$this->registerForm = new NmRegisterForm();
			$this->loginForm = new LoginForm();
			$this->isShowLogin = false;
		} else {
			$this->redirect('/nm/widget/?calId=' . $this->calId);
		}
	}
	
	public function executeWidget(sfWebRequest $request){
		$user = UserUtils::getLoggedIn();
		
		$this->calId = $request->getParameter('calId');
		if (!$user) $this->redirect('/nm/signUpToSave/?calId=' . $this->calId);
		
		$this->language = $request->getParameter('language', NeverMissWidget::DEFAULT_LANGUAGE);
		$this->btnStyle = $request->getParameter('btn-style', NeverMissWidget::DEFAULT_VALUE);
		$this->btnSize = $request->getParameter('btn-size', NeverMissWidget::DEFAULT_VALUE);
		$this->color = $request->getParameter('color', NeverMissWidget::DEFAULT_VALUE);
		
		$this->forward404Unless($cal = Doctrine::getTable('Cal')->find(array($this->calId)), sprintf('Object cal does not exist (%s).', $this->calId));
		
		if ($user) {
			$cal->setAdoptive($user);
			if ($cal->getDeletedAt()) $this->redirect('/nm/calList/');
		}
		
		$this->code = NeverMissWidget::getWidgetCode($cal, $this->language, $this->btnStyle, $this->btnSize, $this->color);

		$this->user = $user;
	}
	
	public function executeSubscribeByMail(sfWebRequest $request){
		$this->forward404Unless($user = UserUtils::getLoggedIn());
		$res = array('success' => false, 'msg' => __('Subscribe by mail failed!'));
		
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
			//$mail->AddReplyTo(sfConfig::get('app_mailinglist_replyToEmail'), sfConfig::get('app_mailinglist_replyToName'));
				
			$mail->AddAddress($user->getEmail(), $user->getFullName());

			$mail->Subject = 'Subscribe ' . $cal->getName() . ' Calendar By Mail';
			$mail->MsgHTML($this->getPartial('nm/subscribeByMailHtml', array('user' => $user, 'cal' => $cal, 'message' => $message)));
			$mail->AltBody = $this->getPartial('nm/subscribeByMailTxt', array('user' => $user, 'cal' => $cal, 'message' => $message));

			if ($mail->Send()){
				$res['success'] = true;
				$res['msg'] = __('Send mail to: %email%', array('%email%' => $user->getEmail()));
			}
		}
	
		echo json_encode($res);
		return sfView::NONE;
	}
	
	public function executeAddToCalendar(sfWebRequest $request){
		$calId 	= $request->getParameter('calId');
		$ctgId 	= $request->getParameter('ctgId');
		$ref 	= $request->getParameter('ref');
		
		if ($calId)  $cal = Doctrine::getTable('Cal')->find($calId);
		elseif ($ctgId) $ctg = Doctrine::getTable('Category')->find($ctgId);
		
		if (!$cal && !$ctg) $this->forward404();
		
		$name = $cal ? $cal->getName() : $ctg->getName();
		$name = Utils::slugify($name);
		
		$partner = $cal ? $cal->getPartner() : $ctg->getPartner();
		
		if (Utils::clientIsMobile()) {
			$url = '/cal/sub' . ($calId ? '/id/' . $calId : '') . ($ctgId ? '/ctgId/' . $ctgId : '') . '/ct/' . Cal::TYPE_MOBILE . ($ref ? '/ref/' . $ref : '') .'/' . $name . '.ics';
			$this->redirect($url);
		}
		
		if ($partner && $partner->getId() == 2047) $this->redirect('/mega-demo/index.php?' . ($ctgId ? 'ctgId=' . $ctgId : 'calId=' . $calId));
		
		$this->calId = $calId;
		$this->ctgId = $ctgId;
		$this->ref   = $ref;
	}
}