<?php
class PartnerLicence{
	const PLAN_A = 'SILVERb1679504e7';
	const PLAN_B = 'GOLD0f0dcd9122';
	const PLAN_C = 'PLATINUM7bef4d179d';
	
	const UNLIMITED = false;
	const DEFAULT_PLAN = 'SILVERb1679504e7';
	
	private static $PLANS = array(
		self::PLAN_A => array('max_subscribers' => 1000),
		self::PLAN_B => array('max_subscribers' => 10000),
		self::PLAN_C => array('max_subscribers' => UNLIMITED)
	);
	
	public static function getMaxSubscribers($planCode = null){
		if (!$planCode || !(key_exists($planCode, self::PLANS))) $planCode = self::DEFAULT_PLAN;
			
		return self::$PLANS[$planCode]['max_subscribers'];
	}
}