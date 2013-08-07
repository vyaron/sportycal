<?php
class PayPal{
	private static $PLAN_2_BUTTON_ID = array(
			//PartnerLicence::PLAN_A => 'Y5RK59Y45C7BJ',
			PartnerLicence::PLAN_B => '7ZKZHATKWHPY8',
			PartnerLicence::PLAN_C => 'YLT29ZFCMMA6Q',
			PartnerLicence::PLAN_D => '7BJTQC3CL3GNG'
	);
	
	private static $PLAN_2_SANDBOX_BUTTON_ID = array(
			//PartnerLicence::PLAN_A => 'Y5RK59Y45C7BJ',
			PartnerLicence::PLAN_B => '7PFXEQNF3TCB6',
			PartnerLicence::PLAN_C => 'ZZX3NSQCA23YL',
			PartnerLicence::PLAN_D => 'FYL8MLRLZMBNJ'
	);
	
	private static function getButtonId($planCode = null){
		$buttonId = null;
	
		if (key_exists($planCode, self::$PLAN_2_BUTTON_ID)){
			$env = sfContext::getInstance()->getConfiguration()->getEnvironment();
	
			if ($env == 'dev') 	$buttonId = self::$PLAN_2_SANDBOX_BUTTON_ID[$planCode];
			else $buttonId = self::$PLAN_2_BUTTON_ID[$planCode];
		}
	
		return $buttonId;
	}
	
	public static function getPartnerId($customJson){
		$custom = json_decode($customJson, true);
		return  ($custom && key_exists('pid', $custom)) ? $custom['pid'] : null;
	}
	
	public static function getSubscriptionUrl($planCode, $partner){
		$url = null;
		
		$buttonId = self::getButtonId($planCode);
		if ($buttonId){
			$custom = array('pid' => $partner->getId());
			$returnUrl = sfConfig::get('app_domain_full') . '/nm/checkoutSuccess';
			
			$params = array(
				'cmd' => '_s-xclick',
				'hosted_button_id' => $buttonId,
				'custom' => json_encode($custom),
				'return' => $returnUrl,
				'notify_url' => 'http://inevermiss.net/nm/paypalIpn', // PROD
				'rm' => 2
			);
				
			$url = sfConfig::get('app_paypal_url') . '/cgi-bin/webscr/?' . http_build_query($params, '', '&');
		}

		return $url;
	} 
	
	public static function cancelSubscription($profileId = null){
		if (!$profileId) return false;
		
		$params = array(
			'USER' => sfConfig::get('app_paypal_user'),
			'PWD' => sfConfig::get('app_paypal_pwd'),
			'SIGNATURE' => sfConfig::get('app_paypal_signature'),
			'VERSION' => sfConfig::get('app_paypal_version'),
			'METHOD' => 'ManageRecurringPaymentsProfileStatus',
			'PROFILEID' => $profileId,
			'ACTION' => 'Cancel',
			'NOTE' => 'Automate unsubscribe'
		);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, sfConfig::get('app_paypal_nvpApiUrl'));
		curl_setopt($ch, CURLOPT_VERBOSE, 1 );
		
		// Uncomment these to turn off server and peer verification
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		
		// Set the API parameters for this transaction
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params, '', '&'));
		
		// Request response from PayPal
		$response = curl_exec($ch);
		
		// If no response was received from PayPal there is no point parsing the response
		if (!$response) return false;

		// An associative array is more usable than a parameter string
		parse_str($response, $parsedResponse);
		
		return $parsedResponse;
	}
	
	//https://developer.paypal.com/webapps/developer/docs/classic/ipn/integration-guide/IPNIntro/#example_req_resp
	public static function isIpnVerified($params, $isTest = false){
		//TODO: create better solution for $isTest hard-coded url
		$paypalUrl = sfConfig::get('app_paypal_url');
		if ($isTest) $paypalUrl = 'https://www.sandbox.paypal.com';
			
		//$request->getPostParameters()
		$url = 'https://' . sfConfig::get('app_paypal_url') . '/cgi-bin/webscr?cmd=_notify-validate&' . http_build_query($params);
	
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
}