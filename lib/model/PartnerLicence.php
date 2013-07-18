<?php

class PartnerLicence{
	const PLAN_A = 'A';
	const PLAN_B = 'B';
	const PLAN_C = 'C';
	const PLAN_D = 'D';
	
	const UNLIMITED = -1;
	const DEFAULT_PLAN = self::PLAN_A;
	
	private static $PLANS = array(
		self::PLAN_A => array('max_subscribers' => 2000, 'name' => 'Basic Account', 'price' => 0, 'desc' => 'Text text text text Text Text text text text Text Text text text text Text Text text text text Text'),
		self::PLAN_B => array('max_subscribers' => 10000, 'name' => 'Pro Account', 'price' => 100, 'desc' => 'Text text text text Text Text text text text Text Text text text text Text Text text text text Text'),
		self::PLAN_C => array('max_subscribers' => 50000, 'name' => 'Premuim Account', 'price' => 400, 'desc' => 'Text text text text Text Text text text text Text Text text text text Text Text text text text Text'),
		self::PLAN_D => array('max_subscribers' => self::UNLIMITED, 'name' => 'Super Extra Basic Account', 'price' => null, 'desc' => 'Text text text text Text Text text text text Text Text text text text Text Text text text text Text'),
	);
	
	//TODO: change to real plans
	private static $PAYPAL_PLANS = array(
		self::PLAN_A => 'Y5RK59Y45C7BJ',
		self::PLAN_B => 'JHRH9HHV4QFWG',
		self::PLAN_C => '38SUU5ULHQ7KS'
	);
	
	private static $PAYPAL_SANBOX_PLANS = array(
		self::PLAN_A => 'Y5RK59Y45C7BJ',
		self::PLAN_B => 'JHRH9HHV4QFWG',
		self::PLAN_C => '38SUU5ULHQ7KS'
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
		else if ($planCode == self::PLAN_B || $planCode == self::PLAN_C) $url = url_for('/nm/checkout/?c=' . $planCode);
		else if ($planCode == self::PLAN_D) $url = url_for('/nm/contact');
	
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
	
	public function getMaxSubscribers(){
		$maxSubscribers =  $this->plan['max_subscribers'];
		
		if ($this->isEnded()) $maxSubscribers = self::$PLANS[self::DEFAULT_PLAN]['max_subscribers'];

		return $maxSubscribers;
	}
	
	public function isBetterThan($partnerLicence){
		return ($this->isUnlimited() || ($this->getMaxSubscribers() > $partnerLicence->getMaxSubscribers() && !$partnerLicence->isUnlimited()));
	}
	
	public function isEnded(){
		return time() > $this->endes_at;
	}
	
	public function isReachedTheMaxSubscribers($num){
		return (!$this->isUnlimited() && $num >= $this->getMaxSubscribers()) ? true : false;
	}
	
	public function isUnlimited(){
		return $this->getMaxSubscribers() == self::UNLIMITED;
	}
}
