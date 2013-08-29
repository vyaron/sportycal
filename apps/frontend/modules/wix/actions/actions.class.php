<?php
class wixActions extends sfActions{
	private function init(sfWebRequest $request, $needInstance = false){
		if ($needInstance){
			$this->forward404Unless($instance = $request->getParameter('instance'));
			
			$locale = $request->getParameter('locale');
			
			$wix = WixTable::getByInstanceCode($instance);
			if (!$wix){
				$wix = new Wix();
			
				$wix->setInstanceCode($instance);
				$wix->setLocale($locale);
				$wix->setCreatedAt(date('Y-m-d H:i:s'));
			
				$wix->save();
			}
			
			$this->wix = $wix;
		}
		
		$this->setLayout('wix');
	}
	
	public function executeSignUp(sfWebRequest $request){
		$this->init($request, false);
	}
	
	public function executeUpdate(sfWebRequest $request){
		$instance = $request->getParameter('instance');
		$wix = WixTable::getByInstanceCode($instance);

		$this->forward404Unless($wix);
		
		$userId = UserUtils::getLoggedInId();
		$calId = $request->getParameter('cal_id');
		$upcoming = $request->getParameter('upcoming');
		
		if (!$wix->getUserId()) $wix->setUserId($userId);

		if ($wix->getUserId() == $userId){
			if ($calId) $wix->setCalId($calId);
			if ($upcoming) $wix->setUpcoming($upcoming);
			
			$wix->setUpdatedAt(date('Y-m-d H:i:s'));
			$wix->save();
		}
		
		return sfView::NONE;
	}
	
	public function executeSettings(sfWebRequest $request){
		$this->init($request, true);
		
		$this->user = UserUtils::getLoggedIn();
		if ($this->user) {
			$this->cals = $this->user->getCals();
			
			if (!$this->wix->getCalId() && count($this->cals) === 1){
				$this->wix->setCalId($this->cals[0]->getId());
				$this->wix->save();
			}
		}
		
		$this->calId = $this->wix->getCalId();
		$this->upcoming = $this->wix->getUpcoming();
	}
	
	public function executeWidget(sfWebRequest $request){
		$this->init($request, true);
		
		$calId = $this->wix->getCalId() ? $this->wix->getCalId() : Wix::DEFAULT_CAL_ID;
		$upcoming = $this->wix->getUpcoming() ? $this->wix->getUpcoming() : Wix::DEFAULT_UPCOMING;
		
		$cal = Doctrine::getTable('Cal')->find(array($calId));

		$dayKeyOrder = array();
		$dayKey2Events = array();
		if ($upcoming) {
			$events = Doctrine_Query::create()
			->from('Event e')
			->where('e.cal_id = ?', $calId)
			->andWhere('e.starts_at >= NOW()')
			->limit($upcoming)
			->orderBy('e.starts_at ASC')->execute();

			foreach ($events as $event){
				$dayKey = date('d M Y', strtotime($event->getStartsAt()));
		
				if (! key_exists($dayKey, $dayKey2Events)) {
					$dayKeyOrder[] = $dayKey;
					$dayKey2Events[$dayKey] = array();
				}
		
				$dayKey2Events[$dayKey][] = $event;
			}
		}
		
		$this->calId = $calId;
		$this->upcoming = $upcoming;
		
		$this->dayKeyOrder = $dayKeyOrder;
		$this->dayKey2Events = $dayKey2Events;
	}
}