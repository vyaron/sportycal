<?php

/**
 * Partner
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    evento
 * @subpackage model
 * @author     Yaron Biton
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Partner extends BasePartner
{
	
	private $subscribersCount = null;
	
	public function __toString()  	{
    	return $this->getName();
	}
	
	public function getDesriptionForCal($calId) {
		//$desc = "Desc for cal $calId from Partner {$this->getId()}:";
		$partnerId = $this->getId();
		
		$desc = PartnerDescTable::getBy($this->getId(), $calId);
		
		return $desc; 
	}
	
	public function getSampleUrl() {
		$pHash = $this->getHash();
		return sfConfig::get('app_domain_full') . 
			   "/cal/get/id/1155/ref/$pHash/sportycal.ics";
	}

	public function getImgPath() {
		$url = sfConfig::get('app_domain_full')."/images/partner/{$this->getId()}.jpg";
		return $url;
	}	

	public function getUrlToShare() {

		// TODO: add ref param 
		$urlToShare = sfConfig::get('app_domain_full');
		
		// TODO: make it the TOTO page
		if ($this->getId() == 1979) {
			$urlToShare = "http://www.facebook.com/pages/SportYcal-Toto/210297105728890?sk=app_260955383948174";
		}
		
		return $urlToShare;
	}

	public function isToto() {
		return $this->getId() == 1979;
	}
	public function is888() {
		return $this->getId() == 10;
	}
	
	public function isRingtonepartner() {
		return $this->getId() == 9;
	}
	
	public function isPokerstars() {
		return $this->getId() == 777;
	}

    public function isHapoelTelAviv() {
        return $this->getId() == 2100;
    }
	
	public function getDescTopPart($isHtmlSupported, $event, $cal) {
		$desc = '';
		
		if ($isHtmlSupported) {
			
			if ($this->is888() && $cal->isUnderCategoryId(3001)) {
				$desc .= "<a href='http://www.sportYcal.com/l/888PokerEuromania' target='_blank'><img src='http://www.sportycal.com/images/partner/888-euromania.jpg' alt='" . "Euromania' title='" . "Euromania"  . "' /></a><br />";
			} else if ($this->is888() && $cal->isUnderCategoryId(3002)) {
				$desc .= "<a href='http://www.sportYcal.com/l/888poker-es' target='_blank'><img src='http://www.sportycal.com/images/partner/888Poker.es.jpg' alt='" . "888Poker.es' title='" . "888Poker.es"  . "' /></a><br />";
			} else if ($this->isRingtonepartner()){
				$desc .= "Provided to you by <a href='http://www.cellsportal.com' target='_blank'>Cellsportal.com</a><br/>";
			} else {
				$desc .= "<img src='" . $this->getImgPath() . "' alt='" . "Provided to you by " . $this->getName()  . "' title='" . "Provided to you by " . $this->getName()  . "' /><br />";
			}
			
			
			if ($this->isPokerstars()) {
				$matches = array();
				preg_match("/GAME-ID : #(\d+)#/", $event->getDescription(), $matches);
				if ($matches && !empty($matches[1])) {
					$psGameId = $matches[1];
					$desc .= "<h3><a href='pokerstars://tournament/". $psGameId ."'>Open this Game</a>\n</h3>";
				}
			}
		} else {
			if ($this->isRingtonepartner()){
				$desc .= "Provided to you by http://www.cellsportal.com \n";
			} else {
				$desc .= "Provided to you by " . $this->getName() . "\n";
			}
		}
		return $desc;
	}
	
	public function getEventDescription($isHtmlSupported, $event, $cal) {
		$desc = $event->getDescription();
		
		// 3001 is the EUROMANIA CAMPAIGN
		if ($this->is888() &&  $cal->isUnderCategoryId(3001)){
			$linkURL = 'http://www.sportYcal.com/l/888PokerEuromania';
			
			if ($isHtmlSupported){
				$tempDesc = '';
				
				$lines = explode(';', $desc);
				$linesCount = count($lines);
				
				foreach ($lines as $i => $line){
					if ($linesCount == $i+1){
						$tempDesc .= '<a href="' . $linkURL . '" target="_blank">' . trim($line) . '</a>' . "<br />";
					} else if ($i == 0) {
						$tempDesc .= '<p style="font-weight:bold;color:#007EF9">' . trim($line) . '</p>' . "<br />";
					} else {
						$tempDesc .= trim($line) . "<br />";
					}
				}
				
				//Utils::pp($tempDesc);
				$desc = $tempDesc;
			} else {
				$desc = str_replace(';', "\n", $desc);
				$desc .= "\n" . $linkURL;
			}
		} else if ($this->is888() &&  $cal->isUnderCategoryId(3002)){
			if ($isHtmlSupported){
				$tempDesc = '';
			
				$lines = explode(';', $desc);
			
				foreach ($lines as $line){
					$tempDesc .= trim($line) . "<br />";
				}
			
				//Utils::pp($tempDesc);
				$desc = $tempDesc;
			} else {
				$desc = str_replace(';', "\n", $desc);
				$desc .= "\n" . $linkURL;
			}
			
		}
		
		
		return $desc;
	}

	public function allowsSportycalLogo() {
		if ($this->isToto()) 					return true;
		else 									return false;
	}
	
	public function isWhiteLabel() {
		if ($this->isRingtonepartner() || $this->is888())			return true;
		else 														return false;
		
	}
	
	public function getRootCategory(){
		$category = null;
		 
		$category = Doctrine_Query::create()
			->from('Category c')
			->innerJoin('c.PartnerDesc pd')
			->where('pd.partner_id = ?', $this->getId())
			->fetchOne();
		
		return $category;
	}
	
	public function getFirstUser(){
		return  Doctrine_Query::create()
			->from('User u')
			->innerJoin('u.PartnerUser pu')
			->where('pu.partner_id =?', $this->getId())
			->orderBy('u.created_at ASC')
			->fetchOne();		
	}
	
	public function getLicence(){
		return new PartnerLicence($this->getLicenceCode(), $this->getLicenceEndsAt());
	}
	
	public function setLicence($licenceCode, $licenceEndsAt, $paypalCode=null){
		$this->setLicenceCode($licenceCode);
		$this->setLicenceEndsAt($licenceEndsAt);
		
		if ($paypalCode) $this->setPaypalCode($paypalCode);
		
		$this->save();
	}
	
	public function getSubscribers(){
		if (is_null($this->subscribersCount)){
			$q = Doctrine_Query::create()
				->select('c.id, COUNT(cr.id) cal_request_count')
				->from('Cal c')
				->innerJoin('c.CalRequest cr')
				->where('c.partner_id =?', $this->getId())
				->andWhere('c.deleted_at IS NULL')
				->groupBy('c.partner_id');
			
			$calRequests = $q->fetchOne();
			
			$this->subscribersCount = ($calRequests && $calRequests['cal_request_count']) ? $calRequests['cal_request_count'] : 0;
		}
		
		return $this->subscribersCount;
	}
	
	public function isClosedMaxSubscribers(){
		$isClosed = false;

		if ($licence = $this->getLicence()){
			$countSubscribers = $this->getSubscribers();
		
			$licence = $this->getLicence();
			$isClosed = $licence->isClosedMaxSubscribers($countSubscribers);
		}
		
		return $isClosed;
	}
	
	public function isReachedMaxSubscribers(){
		$isReached = false;
		
		if ($licence = $this->getLicence()){
			$countSubscribers = $this->getSubscribers();
		
			$licence = $this->getLicence();
			$isReached = $licence->isReachedMaxSubscribers($countSubscribers);
		}

		return $isReached;
	}
	
	public function isReachedMaxCalendars(){
		$isReached = false;
	
		if ($licence = $this->getLicence()){
			$q = Doctrine_Query::create()
				->select('c.id, COUNT(c.id) AS cal_count')
				->from('Cal c')
				->where('c.partner_id =?', $this->id)
				->andWhere('c.deleted_at IS NULL');
	
			$calCount = $q->fetchOne();
	
			$calCount = ($calCount && $calCount['cal_count']) ? $calCount['cal_count'] : 0;
	
			$licence = $this->getLicence();
			$isReached = $licence->isReachedMaxCalendars($calCount);
		}
			
		return $isReached;
	}
	
	public function isReachedMaxEvents(){
		$isReached = false;
	
		if ($licence = $this->getLicence()){
			$q = Doctrine_Query::create()
			->select('e.id, COUNT(e.id) AS event_count')
			->from('Event e')
			->innerJoin('e.Cal c')
			->where('c.partner_id =?', $this->id)
			->andWhere('c.deleted_at IS NULL');
	
			$eventCount = $q->fetchOne();
	
			$eventCount = ($eventCount && $eventCount['event_count']) ? $eventCount['event_count'] : 0;
	
			$licence = $this->getLicence();
			$isReached = $licence->isReachedMaxEvents($eventCount);
		}
			
		return $isReached;
	}
	
	public function getLang(){
		return ($this->getId() == 2047) ? NeverMissWidget::LANGUAGE_HEBREW : NeverMissWidget::DEFAULT_LANGUAGE;
	}
}