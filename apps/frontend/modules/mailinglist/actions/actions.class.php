<?php
class mailinglistActions extends sfActions{
	public function executeTest(sfWebRequest $request){
		//$mailingList = Doctrine::getTable('MailingList')->find(3);
		//$events = $mailingList->getEvents();
		//Utils::pp($events);
		//echo $mailingList->getHtmlMail();
		//echo $mailingList->getHtmlMail();
		//$mailingList->send();
		
		return sfView::NONE;
	}
	
	public function executeUnsubscribe(sfWebRequest $request){
		$hash = base64_decode($request->getParameter('h'));
		$hash = json_decode($hash, true);
		
		if (key_exists('userId', $hash)) $user = MailinglistTable::Unsubscribe($hash['userId']);
		
		$this->forward404Unless($user);

		$this->user = $user;
	}
	
	public function executeSubscribe(sfWebRequest $request){
		$fullName 	= $request->getParameter('full_name');
		$email 		= $request->getParameter('email');
		$calId 		= $request->getParameter('calId');
		$tz 		= $request->getParameter('tz');
		
		$tzStr 		= GeneralUtils::getTZFromJSTZ($tz);
		$ip			= Utils::getClientIP();

		$cal = null;
		if ($calId) $cal = Doctrine::getTable('Cal')->find($calId);
		
		//TODO: add validations
		$res = array('success' => false, 'msg' => 'Wrong parameters!');
		if ($fullName && $email && $cal && $cal->getPartnerId() /*&& $request->isMethod(sfRequest::POST)*/){
			MailinglistTable::subscribe($cal->getId(), $tzStr, $email, $fullName, $cal->getPartnerId(), $ip);

			$res['success'] = true;
			$res['msg'] = $email . ' subscribe success';
		}
		
		echo json_encode($res);
		return sfView::NONE;
	}
}