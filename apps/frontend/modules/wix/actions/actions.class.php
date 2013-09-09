<?php
class wixActions extends sfActions{
	private function init(sfWebRequest $request, $needInstance = false){
		if ($needInstance){
			$instance = $request->getParameter('instance');
			$compId = $request->getParameter('origCompId', $request->getParameter('compId'));
			$locale = $request->getParameter('locale');
			
			$data = Wix::getInstanceData($instance);

			if ($data && $instance && $compId){
				$wix = WixTable::getBy($data->instanceId, $compId);
				if (!$wix){
					$wix = new Wix();
						
					$wix->setInstanceCode($data->instanceId);
					$wix->setCompCode($compId);
					$wix->setLocale($locale);
					$wix->setCreatedAt(date('Y-m-d H:i:s'));
						
					$wix->save();
				}
			} else {
				$wix = new Wix();
				$wix->setCalId(Wix::DEFAULT_CAL_ID);
				$wix->setUpcoming(Wix::DEFAULT_UPCOMING);
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
		$compId = $request->getParameter('compId');
		
		$wix = WixTable::getBy($instance, $compId);

		$this->forward404Unless($wix);
		
		$userId = UserUtils::getLoggedInId();
		$calId = $request->getParameter('cal_id');
		$upcoming = $request->getParameter('upcoming');
		$lineColor = $request->getParameter('line_color');
		
		if (!$wix->getUserId()) $wix->setUserId($userId);

		if ($wix->getInstanceCode() && $wix->getUserId() == $userId){
			if ($calId) $wix->setCalId($calId);
			if ($upcoming) $wix->setUpcoming($upcoming);
			if ($lineColor && preg_match('/^#[0-9A-F]{6}$/i', $lineColor)) $wix->setLineColor($lineColor);
				
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
		$this->lineColor = $this->wix->getLineColorForDisplay();
	}
	
	public function executeWidget(sfWebRequest $request){
		$this->init($request, true);
		
		$calId = $this->wix->getCalId() ? $this->wix->getCalId() : Wix::DEFAULT_CAL_ID;
		$upcoming = $this->wix->getUpcoming() ? $this->wix->getUpcoming() : Wix::DEFAULT_UPCOMING;
		
		$cal = Doctrine::getTable('Cal')->find(array($calId));

		$dayKeyOrder = array();
		$dayKey2Events = array();
		if ($upcoming) {
			$q = Doctrine_Query::create()
				->from('Event e')
				->where('e.cal_id = ?', $calId)
				->limit($upcoming)
				->orderBy('e.starts_at ASC');
			
			if ($cal->getId() != Wix::DEFAULT_CAL_ID) $q->andWhere('e.starts_at >= NOW()');
			
			$events = $q->execute();
			
			if ($cal->getId() == Wix::DEFAULT_CAL_ID){
				foreach ($events as $i => &$event){
					$event->setStartsAt(date('Y-m-d H:i:s', strtotime('+' . ($i+1) . 'day')));
					$event->setEndsAt(date('Y-m-d H:i:s', strtotime('+' . ($i+1) . ' day +1 hour')));
				}
			}
			
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
		$this->lineColor = $this->wix->getLineColorForDisplay();
	}
}