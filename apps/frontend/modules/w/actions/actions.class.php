<?php
class wActions extends sfActions{
	public function executeNeverMiss(sfWebRequest $request){
		$this->language = $request->getParameter('language');
		
		$this->setLayout(false);
	}
	
	public function executeNeverMissBtn(sfWebRequest $request){
		$this->calId = $request->getParameter('calId');
		$this->popupId = $request->getParameter('popupId');
		$this->language = $request->getParameter('language');
		$this->isMobile = $request->getParameter('isMobile', Utils::clientIsMobile());
		
		$cal = Doctrine::getTable('Cal')->find(array($this->calId));
		$this->isReachedMaxSubscribers = $cal->isReachedMaxSubscribers();
		
		if (!($cal && $this->calId && $this->popupId)){
			echo 'ERROR!!!';
			return sfView::NONE;
		}
		
		$this->setLayout(false);
	}
	
	public function executeNeverMissPopup(sfWebRequest $request){
		$this->calId = $request->getParameter('calId');
		$this->popupId = $request->getParameter('popupId');
		$this->isBubble = $request->getParameter('isBubble', false);
		$this->language = $request->getParameter('language');
		
		if (!($this->calId)){
			echo 'ERROR!!!';
			return sfView::NONE;
		}
		
		sfContext::getInstance()->getI18N()->setCulture($this->language);
		
		$this->setLayout(false);
	}
}