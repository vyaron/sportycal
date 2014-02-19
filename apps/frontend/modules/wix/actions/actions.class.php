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
			//$data->vendorProductId = Wix::PREMUIM_PRODUCT_CODE;
			$this->instance = $instance;
			$this->wixData = $data;
			$this->wix = $wix;
		}
		
		$this->setLayout('cleanLayout');
	}
	
	public function executeDemo(sfWebRequest $request){
		$this->setLayout('cleanLayout');
	}
	
	public function executeSignUp(sfWebRequest $request){
		$this->init($request, false);
	}
	
	private function isColor($color){
		return preg_match('/^#[0-9A-F]{6}$/i', $color) ? true : false;
	}
	
	public function executeUpdate(sfWebRequest $request){
		$this->init($request, true);

		$this->forward404Unless($this->wix->getInstanceCode());
		
		$calId = $request->getParameter('cal_id');
		$lineColor = $request->getParameter('line_color');
		$textColor = $request->getParameter('text_color');
		$bgColor = $request->getParameter('bg_color');
		$bgOpacity = $request->getParameter('bg_opacity');
		$bgIsTransparent =  $request->getParameter('bg_is_transparent') === 'true' ? true : false;
		$isShowCalName = $request->getParameter('is_show_cal_name') == 'true' ? true : false;

		if ($calId) $this->wix->setCalId($calId);
		if ($lineColor && $this->isColor($lineColor)) $this->wix->setLineColor($lineColor);
		if ($textColor && $this->isColor($textColor)) $this->wix->setTextColor($textColor);
		if ($bgColor && $this->isColor($bgColor)) $this->wix->setBgColor($bgColor);

		if ($bgIsTransparent) $this->wix->setBgOpacity(0);
		else if ($bgOpacity) $this->wix->setBgOpacity($bgOpacity);
		
		$this->wix->setIsShowCalName($isShowCalName);
			
		$this->wix->setUpdatedAt(date('Y-m-d H:i:s'));
		$this->wix->save();
		
		return sfView::NONE;
	}
	
	private function handleLogin(){
		//Auto login based on wix code.instance
		if ($this->wixData->permissions == "OWNER"){
					
			if ($this->wix && $this->wix->getUserId()) {
				$user = Doctrine_Query::create()
					->from('User u')
					->where('u.id = ?', $this->wix->getUserId())
					->fetchOne();
		
				UserUtils::logUserIn($user);
			} else {
				UserUtils::logUserOut();
			}
		}
	}
	
	public function executeSettings(sfWebRequest $request){
		$this->init($request, true);

		$this->handleLogin();
		
		$this->user = UserUtils::getLoggedIn();
		
		if ($this->user) {
			$this->cals = $this->user->getCals();
			
			if (!$this->wix->getCalId() && count($this->cals) === 1){
				$this->wix->setCalId($this->cals[0]->getId());
				$this->wix->save();
			}
		}
		
		$this->isPremium = Wix::isPremium($this->wixData);
		$this->calId = $this->wix->getCalId();
		$this->upcoming = $this->wix->getUpcomingForDisplay();
		$this->lineColor = $this->wix->getLineColorForDisplay();
		$this->textColor = $this->wix->getTextColorForDisplay();
		$this->bgColor = $this->wix->getBgColorForDisplay();
		$this->bgIsTransparent = $this->wix->getBgIsTransparent();
		$this->bgOpacity = $this->wix->getBgOpacityForDisplay();
		$this->isShowCalName = (boolean) $this->wix->getIsShowCalName();
	}
	
	
	
	public function executeWidget(sfWebRequest $request){
		$this->init($request, true);
		
		$this->handleLogin();
		
		$calId = $this->wix->getCalIdForDisplay();
		$upcoming = $this->wix->getUpcomingForDisplay();
		$isMobile = $request->getParameter('isMobile', Utils::clientIsMobile());
		if ($calId) $cal = Doctrine::getTable('Cal')->find($calId);
		if (!$cal) die();
		
		$partner = $cal ? $cal->getPartner() : $ctg->getPartner();
		
		$this->cal = $cal;
		$this->partner = $parnter;
		$this->events = CalTable::getUpcomingEvents($cal, $ctg, null, true);
		$this->isReachedMaxSubscribers = ($partner && !Wix::isPremium($this->wixData)) ? $partner->isReachedMaxSubscribers() : false;
		$this->calId = $calId;
		$this->isMobile = $isMobile;
		
		$this->lineColor = $this->wix->getLineColorForDisplay();
		$this->textColor = $this->wix->getTextColorForDisplay();
		$this->bgColor = $this->wix->getBgColorForDisplay();
		$this->bgIsTransparent = $this->wix->getBgIsTransparent();
		$this->bgOpacity = $this->wix->getBgOpacityForDisplay();
		
		$isShowCalName = (boolean) $this->wix->getIsShowCalName();
		$this->title = ($isShowCalName) ? $cal->getName() : null;
		//$this->setLayout('cleanLayout');
		
		sfContext::getInstance()->getI18N()->setCulture($language);
	}
}