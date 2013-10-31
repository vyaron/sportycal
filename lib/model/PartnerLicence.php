<?php

class PartnerLicence{
	const PLAN_A = 'A';
	const PLAN_B = 'B';
	const PLAN_C = 'C';
	const PLAN_D = 'D';
	
	const UNLIMITED = -1;
	const DEFAULT_PLAN = self::PLAN_A;
	
	private static $PLANS = array(
		self::PLAN_A => array('prestige' => 0, 'max_subscribers' => 10, 'max_calendars' => 1, 'max_events' => 10, 'name' => 'Free', 'price' => 0, 'desc' => ''),
		self::PLAN_B => array('prestige' => 1, 'max_subscribers' => 250, 'max_calendars' => 3, 'max_events' => 100, 'name' => 'Starters', 'price' => 7, 'desc' => ''),
		self::PLAN_C => array('prestige' => 2, 'max_subscribers' => 2500, 'max_calendars' => 10, 'max_events' => self::UNLIMITED, 'name' => 'Business', 'price' => 59, 'desc' => ''),
		self::PLAN_D => array('prestige' => 3, 'max_subscribers' => 25000, 'max_calendars' => self::UNLIMITED, 'max_events' => self::UNLIMITED, 'name' => 'Pro', 'price' => 490, 'desc' => ''),
	);

	public static function setLicence($custom, $itemNumber, $paymentDate, $paypalCode=null, $cancelCurrentLicence=false){
		if ($custom && $itemNumber && $paymentDate){
			$partnerId = PayPal::getPartnerId($custom);
				
			if ($partnerId) $partner = Doctrine::getTable('Partner')->find(array($partnerId));
				
			$licenceCode = array_key_exists($itemNumber, self::$PLANS) ? $itemNumber : null;
				
			$endsDate = new DateTime($paymentDate);
			$endsDate->modify('+1 month');
			$endsDate->modify('+1 day'); // One day on the house
			$licenceEndsAt = $endsDate->format('Y-m-d H:i:s');
	
			if ($partner && $licenceCode && $licenceEndsAt){
				if ($cancelCurrentLicence) PayPal::cancelSubscription($partner->getPaypalCode());
				
				
				$partner->setLicence($licenceCode, $licenceEndsAt, $paypalCode);
			}
				
		}
	}
	
	
	/*Objs*/
	public static function getPlan($planCode){
		if (!$planCode || !(key_exists($planCode, self::$PLANS))) $planCode = self::DEFAULT_PLAN;
		return self::$PLANS[$planCode];
	}
	
	public static function getPlans(){
		return self::$PLANS;
	}
	
	public static function getPlanUrl($planCode){
		$url = null;
	
		if ($planCode == self::PLAN_A) $url = url_for('/nm/calCreate');
		else if ($planCode == self::PLAN_B || $planCode == self::PLAN_C || $planCode == self::PLAN_D) $url = url_for('/nm/checkout/?c=' . $planCode);
	
		return $url;
	}
	
	public static function getCalendarsForDisplay($num = null){
		$txt = '';
		
		if ($num == self::UNLIMITED) $txt = 'unlimited calendars';
		else if ($num == 1) $txt = '1 calendar';
		else if ($num > 1) $txt = 'up to ' . $num . ' calendars';
		
		return $txt;
	}
	
	public static function getEventsForDisplay($num = null){
		$txt = '';
	
		if ($num == self::UNLIMITED) $txt = 'unlimited events';
		else if ($num == 1) $txt = '1 event';
		else if ($num > 1) $txt = 'up to ' . $num . ' events';
	
		return $txt;
	}
	
	
	/*Obj*/
	
	private $plan, $endes_at;
	
	public function __construct($planCode, $endesAt){
		$this->plan = self::getPlan($planCode);
		$this->endes_at = strtotime($endesAt);
	}
	
	public function getName(){
		return $this->plan['name'];
	}
	
	public function getDescription(){
		return $this->plan['desc'];
	}
	
	public function getPrice(){
		return $this->plan['price'];
	}
	
	public function getPrestige(){
		return $this->plan['prestige'] ? $this->plan['prestige'] : 0;
	}
	
	public function getMaxSubscribers(){
		$maxSubscribers =  $this->plan['max_subscribers'];
		if ($this->isEnded()) $maxSubscribers = self::$PLANS[self::DEFAULT_PLAN]['max_subscribers'];

		return $maxSubscribers;
	}
	
	public function getMaxCalendars(){
		$maxCalendars =  $this->plan['max_calendars'];
	
		if ($this->isEnded()) $maxCalendars = self::$PLANS[self::DEFAULT_PLAN]['max_calendars'];
	
		return $maxCalendars;
	}
	
	public function getMaxEvents(){
		$maxEvents =  $this->plan['max_events'];
	
		if ($this->isEnded()) $maxEvents = self::$PLANS[self::DEFAULT_PLAN]['max_events'];
	
		return $maxEvents;
	}
	
	public function isBetterThan($partnerLicence=null){
		if (!$partnerLicence || $partnerLicence->isEnded()) return true;
		return ($this->isUnlimited() || ($this->getPrestige() > $partnerLicence->getPrestige() && !$partnerLicence->isUnlimited()));
	}
	
	public function isEnded(){
		return ($this->endes_at && time() > $this->endes_at);
	}
	
	public function isClosedMaxSubscribers($num){
		$maxSubscribers = $this->getMaxSubscribers();
		return (!($maxSubscribers == self::UNLIMITED) && $num >= ($maxSubscribers * 0.8)) ? true : false;
	}
	
	public function isReachedMaxSubscribers($num){
		$maxSubscribers = $this->getMaxSubscribers();
		return (!($maxSubscribers == self::UNLIMITED) && $num >= $maxSubscribers) ? true : false;
	}
	
	public function isReachedMaxCalendars($num){
		$maxCalendars = $this->getMaxCalendars();
		return (!($maxCalendars == self::UNLIMITED) && $num >= $maxCalendars) ? true : false;
	}
	
	public function isReachedMaxEvents($num){
		$maxEvents = $this->getMaxEvents();
		return (!($maxEvents == self::UNLIMITED) && $num >= $maxEvents) ? true : false;
	}

	public function isUnlimited(){
		return $this->getMaxSubscribers() == self::UNLIMITED;
	}
}
