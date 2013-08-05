<?php

class PartnerLicence{
	const PLAN_A = 'A';
	const PLAN_B = 'B';
	const PLAN_C = 'C';
	const PLAN_D = 'D';
	
	const UNLIMITED = -1;
	const DEFAULT_PLAN = self::PLAN_A;
	
	private static $PLANS = array(
		self::PLAN_A => array('prestige' => 0, 'max_subscribers' => 10, 'max_calendars' => 1, 'max_events' => 10, 'name' => 'Free Account', 'price' => 0, 'desc' => ''),
		self::PLAN_B => array('prestige' => 1, 'max_subscribers' => 500, 'max_calendars' => 3, 'max_events' => 100, 'name' => 'Starters Account', 'price' => 6.9, 'desc' => ''),
		self::PLAN_C => array('prestige' => 2, 'max_subscribers' => 5000, 'max_calendars' => 10, 'max_events' => self::UNLIMITED, 'name' => 'Business Account', 'price' => 49, 'desc' => ''),
		self::PLAN_D => array('prestige' => 3, 'max_subscribers' => 50000, 'max_calendars' => self::UNLIMITED, 'max_events' => self::UNLIMITED, 'name' => 'Pro Account', 'price' => 390, 'desc' => ''),
	);
	
	//TODO: change to real plans
	private static $PAYPAL_PLANS = array(
		//self::PLAN_A => 'Y5RK59Y45C7BJ',
		self::PLAN_B => '7ZKZHATKWHPY8',
		self::PLAN_C => 'YLT29ZFCMMA6Q',
		self::PLAN_D => '7BJTQC3CL3GNG'
	);
	
	private static $PAYPAL_SANBOX_PLANS = array(
		//self::PLAN_A => 'Y5RK59Y45C7BJ',
		self::PLAN_B => '7PFXEQNF3TCB6',
		self::PLAN_C => 'ZZX3NSQCA23YL',
		self::PLAN_D => 'FYL8MLRLZMBNJ'
	);
	
	/*Paypal*/
	public static function getPaypalPlan($planCode = null){
		$paypalPlan = null;
	
		if (key_exists($planCode, self::$PAYPAL_PLANS)){
			$env = sfContext::getInstance()->getConfiguration()->getEnvironment();
				
			if ($env == 'dev') 	$paypalPlan = self::$PAYPAL_SANBOX_PLANS[$planCode];
			else $paypalPlan = self::$PAYPAL_PLANS[$planCode];
		}
	
		return $paypalPlan;
	}
	
	//https://developer.paypal.com/webapps/developer/docs/classic/ipn/integration-guide/IPNIntro/#example_req_resp
	public static function isComeFromPaypal(sfWebRequest $request){
		$url = 'https://' . sfConfig::get('app_paypal_url') . '/cgi-bin/webscr?cmd=_notify-validate&' . http_build_query($request->getPostParameters());
	
		// create curl resource
		$ch = curl_init();
	
		// set url
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	
		// $output contains the output string
		$output = curl_exec($ch);
	
		// close curl resource to free up system resources
		curl_close($ch);
	
		return $output == 'VERIFIED' ? true : false;
	}
	
	public static function setLicence($custom, $itemNumber, $paymentDate){
		if ($custom && $itemNumber && $paymentDate){
			$customData = json_decode($custom, true);
				
			if ($customData['uid']) $user = Doctrine::getTable('User')->find(array($customData['uid']));
			$partner = ($user) ? $user->getPartner() : null;
				
			$licenceCode = array_key_exists($itemNumber, self::$PLANS) ? $itemNumber : null;
				
			$endsDate = new DateTime($paymentDate);
			$endsDate->modify('+1 month');
			$endsDate->modify('+1 day'); // One day on the house
			$licenceEndsAt = $endsDate->format('Y-m-d H:i:s');
	
			if ($partner && $licenceCode && $licenceEndsAt){
				$partner->setLicence($licenceCode, $licenceEndsAt);
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
	
	public function isBetterThan($partnerLicence){
		return ($this->isUnlimited() || ($this->getPrestige() > $partnerLicence->getPrestige() && !$partnerLicence->isUnlimited()));
	}
	
	public function isEnded(){
		return ($this->endes_at && time() > $this->endes_at);
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
