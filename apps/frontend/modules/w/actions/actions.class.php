<?php
class wActions extends sfActions{
	public function executeNeverMiss(sfWebRequest $request){
		$this->setLayout(false);
	}
	
	public function executeNeverMissBtn(sfWebRequest $request){
		$this->calId = $request->getParameter('calId');
		if (!$this->calId) die();
		
		$this->popupId = $request->getParameter('popupId');
		
		$this->language = $request->getParameter('language');

		$this->btnStyle = $request->getParameter('btnStyle');
		if ($this->btnStyle != 'only_icon') $this->btnStyle = null;
		
		$this->btnSize = $request->getParameter('btnSize');
		if ($this->btnSize != 'small') $this->btnSize = null;
		
		$this->color = $request->getParameter('color');
		if ($this->color != 'dark') $this->color = null;
		
		$upcoming = $request->getParameter('upcoming');
		if (!($upcoming > 0  && $upcoming <= 5)) $upcoming = 0;
		
		$dayKeyOrder = array();
		$dayKey2Events = array();
		if ($upcoming) {
			$events = Doctrine_Query::create()
				->from('Event e')
				->where('e.cal_id = ?', $this->calId)
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
		
		$this->dayKeyOrder = $dayKeyOrder;
		$this->dayKey2Events = $dayKey2Events;
		
		$this->isMobile = $request->getParameter('isMobile', Utils::clientIsMobile());
		
		$cal = Doctrine::getTable('Cal')->find(array($this->calId));
		$partner = $cal->getPartner();
		$this->isReachedMaxSubscribers = $partner ? $partner->isReachedMaxSubscribers() : false;
		
		if (!($cal && $this->calId && $this->popupId)){
			echo 'ERROR!!!';
			return sfView::NONE;
		}
		
		$this->setLayout(false);
	}
	
	public function executeNeverMissPopup(sfWebRequest $request){
		$this->calId = $request->getParameter('calId');
		$this->popupId = $request->getParameter('popupId');
		$this->isBubble = $request->getParameter('isBubble') === 'true' ? true : false;
		
		$this->bubblePos = $request->getParameter('bubblePos', 'bottom-right');
		$this->language = $request->getParameter('language');
		$this->isRTL = ($this->language == NeverMissWidget::LANGUAGE_HEBREW) ? true : false; 
		
		if (!($this->calId)){
			echo 'ERROR!!!';
			return sfView::NONE;
		}
		
		sfContext::getInstance()->getI18N()->setCulture($this->language);
		
		$this->setLayout(false);
	}
}