<?php
class PartnerLicence{
	const PLAN_A = 'A';
	const PLAN_B = 'B';
	const PLAN_C = 'C';
	const PLAN_D = 'D';
	
	const UNLIMITED = -1;
	const DEFAULT_PLAN = self::PLAN_A;
	
	private static $PLANS = array(
		self::PLAN_A => array('max_subscribers' => 2000, 'name' => 'Basic Account', 'price' => 25, 'desc' => 'Text text text text Text Text text text text Text Text text text text Text Text text text text Text'),
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
	
	public static function getMaxSubscribers($planCode = null){
		if (!$planCode || !(key_exists($planCode, self::PLANS))) $planCode = self::DEFAULT_PLAN;
			
		return self::$PLANS[$planCode]['max_subscribers'];
	}
	
	public static function getPlans(){
		return self::$PLANS;
	}
	
	public static function getPaypalPlan($planCode = null){
		$paypalPlan = null;
		
		if (key_exists($planCode, self::$PAYPAL_PLANS)){
			$env = sfContext::getInstance()->getConfiguration()->getEnvironment();
			
			if ($env == 'dev') 	$paypalPlan = self::$PAYPAL_SANBOX_PLANS[$planCode];
			else $paypalPlan = self::$PAYPAL_PLANS[$planCode];
		}
		
		return $paypalPlan;
	}
	
	public static function getPlanCode($paypalCode){
		$env = sfContext::getInstance()->getConfiguration()->getEnvironment();
			
		if ($env == 'dev') 	$plans = self::$PAYPAL_SANBOX_PLANS;
		else $plans = self::$PAYPAL_PLANS;
		
		$code = null;
		foreach ($plans as $planCode => $paypalPlanCode){
			if ($paypalPlanCode == $paypalCode){
				$code = $planCode;
				break;
			}
		}
		
		return $planCode;
	} 
}
