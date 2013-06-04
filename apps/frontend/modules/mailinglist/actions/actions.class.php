<?php
class mailinglistActions extends sfActions{
	public function executeTest(sfWebRequest $request){
		$mailingList = Doctrine::getTable('MailingList')->find(4);
		
		//echo $mailingList->getHtmlMail();
		
		//$mailingList->send();
		
		return sfView::NONE;
	}
	
	public function executeUnsubscribe(sfWebRequest $request){
		$hash = base64_decode($request->getParameter('h'));
		$hash = json_decode($hash, true);
		
		if (key_exists('id', $hash)) $mailingList = Doctrine::getTable('MailingList')->find($hash['id']);
		else $this->forward404Unless(false);
		
		$dateNow = date("Y-m-d g:i:s");
		$mailingList->setDeletedAt($dateNow);
		$mailingList->save();
		
		$this->mailingList = $mailingList;
	}
	
	public function executeSubscribe(sfWebRequest $request){
		$fullName 	= $request->getParameter('full_name');
		$email 		= $request->getParameter('email');
		$calId 		= $request->getParameter('calId');
		$ctgId 		= $request->getParameter('ctgId');
		$tz 		= $request->getParameter('tz');
		
		$tzStr 		= GeneralUtils::getTZFromJSTZ($tz);

		$cal = null;
		$category = null;
		
		if ($calId) $cal = Doctrine::getTable('Cal')->find($calId);
		//if ($ctgId) $category = Doctrine::getTable('Category')->find($ctgId);
		
		//TODO: add validations
		$res = array('success' => false, 'msg' => 'Wrong parameters!');
		if ($fullName && $email && $cal && $cal->getPartnerId() && $request->isMethod(sfRequest::POST)){
			//try to get existing mailinglist
			$mailinglist = MailinglistTable::getBy($email, $calId, $ctgId);

			if (!$mailinglist){
				$dateNow = date("Y-m-d g:i:s");
				
				$mailinglist = new Mailinglist();
				
				$mailinglist->setPartnerId($cal->getPartnerId());
				$mailinglist->setFullName($fullName);
				$mailinglist->setEmail($email);
				$mailinglist->setCreatedAt($dateNow);
				
				if ($cal) 		$mailinglist->setCal($cal);
				//if ($category) 	$mailinglist->setCal($category);
				if ($tzStr) 	$mailinglist->setTz($tzStr);
					
				$mailinglist->save();
			}
			
			if ($mailinglist) {
				//Send email
				$mailinglist->send();
				
				$res['success'] = true;
				$res['msg'] = $email . ' subscribe success';
			}
			
		}
		
		echo json_encode($res);
		return sfView::NONE;
	}
}