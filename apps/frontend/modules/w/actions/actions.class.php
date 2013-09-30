<?php
class wActions extends sfActions{
	public function executeNeverMiss(sfWebRequest $request){
		$this->setLayout(false);
	}
	
	public function executeNeverMissBtn(sfWebRequest $request){
		$this->calId = $request->getParameter('calId');
		$this->ctgId = $request->getParameter('ctgId');

		if ($this->calId) $cal = Doctrine::getTable('Cal')->find($this->calId);
		if ($this->ctgId) $ctg = Doctrine::getTable('Category')->find($this->ctgId);
		
		if (!$cal && !$ctg) die();
		
		$this->ref = $request->getParameter('ref');

		$this->popupId = $request->getParameter('popupId');
		
		$this->language = $request->getParameter('language');
		$this->isRTL = ($this->language == NeverMissWidget::LANGUAGE_HEBREW) ? true : false; 
		sfContext::getInstance()->getI18N()->setCulture($this->language);
		
		$this->btnStyle = $request->getParameter('btnStyle');
		if ($this->btnStyle != 'only_icon') $this->btnStyle = null;
		
		$this->btnSize = $request->getParameter('btnSize');
		if ($this->btnSize != 'small') $this->btnSize = null;
		
		$this->color = $request->getParameter('color');
		if ($this->color != 'dark') $this->color = null;
		
		$this->width = $request->getParameter('width');
		$this->height = $request->getParameter('height');
		$this->src = $request->getParameter('src');
		
		$partner = $cal ? $cal->getPartner() : $ctg->getPartner();
		
		$this->isMobile = $request->getParameter('isMobile', Utils::clientIsMobile());
		
		$this->isReachedMaxSubscribers = $partner ? $partner->isReachedMaxSubscribers() : false;
		
// 		if (!($cal && $this->popupId)){
// 			echo 'ERROR!!!';
// 			return sfView::NONE;
// 		}
		
		$this->setLayout(false);
	}
	
	public function executeNeverMissPopup(sfWebRequest $request){
		$this->calId = $request->getParameter('calId');
		$this->ctgId = $request->getParameter('ctgId');
		if (!$this->calId && !$this->ctgId){
			echo 'ERROR!!!';
			return sfView::NONE;
		}
		
		$this->ref = $request->getParameter('ref');
		
		$this->popupId = $request->getParameter('popupId');
		$this->isBubble = $request->getParameter('isBubble') === 'true' ? true : false;
		$this->bubblePos = $request->getParameter('bubblePos', 'bottom-right');
		
		$this->language = $request->getParameter('language');
		$this->isRTL = ($this->language == NeverMissWidget::LANGUAGE_HEBREW) ? true : false; 
		sfContext::getInstance()->getI18N()->setCulture($this->language);

		$this->setLayout(false);
	}
	
	public function executeNeverMissList(sfWebRequest $request){
		$ref = $request->getParameter('ref', 'widget');
		$calId = $request->getParameter('calId');
		$ctgId = $request->getParameter('ctgId');
		$upcoming = $request->getParameter('upcoming', 5);
		$isDark = ($request->getParameter('color')) == 'dark' ? true : false;
		$isMobile = $request->getParameter('isMobile', Utils::clientIsMobile());
		$language = $request->getParameter('language');
		$isRTL = ($language == NeverMissWidget::LANGUAGE_HEBREW) ? true : false;
		
		if ($calId) $cal = Doctrine::getTable('Cal')->find($calId);
		elseif ($ctgId) $ctg = Doctrine::getTable('Category')->find($ctgId);
		
		if (!$cal && !$ctg) die();
		
		$partner = $cal ? $cal->getPartner() : $ctg->getPartner();
		
		$this->events = CalTable::getUpcomingEvents($cal, $ctg, $upcoming);
		$this->isReachedMaxSubscribers = $partner ? $partner->isReachedMaxSubscribers() : false;
		$this->ref = $ref;
		$this->calId = $calId;
		$this->ctgId = $ctgId;
		$this->isDark = $isDark;
		$this->isMobile = $isMobile;
		$this->isRTL = $isRTL;
		
		$this->setLayout('cleanLayout');
		
		sfContext::getInstance()->getI18N()->setCulture($language);
	}
}