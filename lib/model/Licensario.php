<?php
Class Licensario{
	const HEADER_API_KEY = 'ISV_API_KEY';
	const HEADER_API_SECERT = 'ISV_API_SECRET';
	const API_URL = 'https://users.licensario.com';
	const API_USER_URI = '/api/v1/users/external/';
	const API_WIZARD_URI = '/api/v1/wizards';
	const USER_PREFIX = 'P';
	
	private function api($method, $uri = "", $data=null){
		// create curl resource
		$ch = curl_init();

		// set url
		curl_setopt($ch, CURLOPT_URL, self::API_URL . $uri);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		
		$headers = array(
				self::HEADER_API_KEY . ': ' . $this->getApiKey(),
				self::HEADER_API_SECERT . ': ' . $this->getApiSecret(),
		);
		
		if ($data) {
			$fields = http_build_query($data, '', '&');
			
			//if ($method == 'PUT') $fields = 'email=ido@gmail.com';
			
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
			//curl_setopt($ch, CURLOPT_POSTFIELDSIZE, strlen($fields));
		}

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		
		if ($method == 'PUT') curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		else if ($method == 'POST') curl_setopt($ch, CURLOPT_POST, 1);
		
		// $output contains the output string
		$output = curl_exec($ch);
		
		// close curl resource to free up system resources
		curl_close($ch);
		
		return $output;
	}
	
	private function getApiKey(){
		return sfConfig::get('app_licensario_apiKey');
	}
	
	private function getApiSecret(){
		return sfConfig::get('app_licensario_apiSecret');
	}
	
	public function addUser(User $user){
		$data = array('email' => $user->getEmail());
		$data['user_first_name'] = $user->getFullName();
		$data['user_signed_up_at'] = $user->getCreatedAt();

		if ($user->getFbCode()) $data['signed_up_via'] = "facebook";
		if ($user->getBirthdate()) $data['user_born_at'] = $user->getBirthdate();
		
		$res = $this->api("PUT", self::API_USER_URI . self::USER_PREFIX . $user->getId(), $data);
	}
	
	public function getExternalUserId(User $user){
		$res = $this->addUser($user);
		
		return ($res === false) ? false : self::USER_PREFIX . $user->getId();
	}
	
	public function getToken(User $user){
		$res = array('success' => 'false');
		
		$externalUserId = $this->getExternalUserId($user);
		$data = array('externalUserId' => $externalUserId);
		$token = $this->api("POST", self::API_WIZARD_URI, $data);
		//Utils::pp($token);
		if ($token && $externalUserId){
			$res['success'] = true;
			$res['externalUserId'] = $externalUserId;
			$res['token'] = $token;
		}
		
		return $res;
	}
}