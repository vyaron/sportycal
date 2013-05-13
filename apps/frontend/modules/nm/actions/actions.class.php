<?php
class nmActions extends sfActions{
	public function executeIndex(sfWebRequest $request){

	}

	public function executeCalCreate(sfWebRequest $request){
		$cal = new Cal();
		$cal->setIsPublic(false);
		$cal->setCategoryId(Category::CTG_NEVER_MISS);
		$cal->setCategoryIdsPath(Category::CTG_NEVER_MISS);
		$cal->save();
		 
		UserUtils::setOrphanCalId($cal->getId());
		 
		$this->redirect('/nm/calEdit?id=' . $cal->getId());
	}
	
	public function executeCalEdit(sfWebRequest $request){
		$this->forward404Unless($cal = Doctrine::getTable('Cal')->find(array($request->getParameter('id'))), sprintf('Object cal does not exist (%s).', $request->getParameter('id')));
		$this->cal = $cal;
		 
		if ($request->isMethod(sfRequest::POST) && $this->cal->isAbandoned()){
			$name = $request->getParameter('name');
			if (empty($name)) $name = date('Y-m-d H:i:s');
			$this->cal->setName($name);
			$this->cal->setDescription($request->getParameter('description'));
			$this->cal->save();
	
			$this->redirect('/nm/widget?calId=' . $cal->getId());
		}
	}
	
	public function executeCalEvents(sfWebRequest $request){
		$this->forward404Unless($cal = Doctrine::getTable('Cal')->find(array($request->getParameter('id'))), sprintf('Object cal does not exist (%s).', $request->getParameter('id')));
		 
		$isEditing = $request->getParameter('editing') ? true : false;
		if ($isEditing){
			//Create XML
			//TODO: replace with json
			$xml = new SimpleXMLElement('<xml/>');
			$xmlData = $xml->addChild('data');
	
			$ids = explode(',', $request->getParameter('ids'));
	
			$dateStr = date('Y-m-d H:i:s');
			foreach ($ids as $id){
				$xmlAction = $xmlData->addChild('action');
					
				$status = $request->getParameter($id . '_!nativeeditor_status');
				$name = $request->getParameter($id . '_text');
				$description = $request->getParameter($id . '_details');
				$location = $request->getParameter($id . '_location');
				$startDate = $request->getParameter($id . '_start_date');
				$endDate = $request->getParameter($id . '_end_date');
					
				if ($status == 'inserted'){
					$event = new Event();
					$event->setCalId($cal->getId());
					$event->setCreatedAt($dateStr);
				} else if ($status == 'updated'){
					$event = Doctrine::getTable('Event')->find($id);
				}
					
				$event->setName($name);
				$event->setDescription($description);
				$event->setLocation($location);
				$event->setStartsAt($startDate);
				$event->setEndsAt($endDate);
					
				$event->setUpdatedAt($dateStr);
					
				$event->save();
					
				$xmlAction->addAttribute('type', $status);
				$xmlAction->addAttribute('sid', $id);
				$xmlAction->addAttribute('tid', $event->getId());
					
				/*
				 $activity = new stdClass();
				$activity->type = $status;
				$activity->sid = $id;
				$activity->tid = $event->getId();
					
				$res['data'][] = $activity;
				*/
			}
	
			$this->xml = $xml;
			$this->setLayout(false);
		} else {
			$events = $cal->getEvents();
	
			$res = array('data' => array());
			foreach ($events as $event){
				$flatEvent = new stdClass();
	
				$flatEvent->id = $event->getId();
				$flatEvent->start_date = $event->getStartsAt();
				$flatEvent->end_date = $event->getEndsAt();
				$flatEvent->text = $event->getName();
				$flatEvent->details = $event->getDescription();
				$flatEvent->location = $event->getLocation();
	
				$res['data'][] = $flatEvent;
			}
	
			echo json_encode($res);
			return sfView::NONE;
		}
	}
	
	public function executeWidget(sfWebRequest $request){
		$calId = $request->getParameter('calId');
		$this->calId = $calId ? $calId : 1;
	}
}