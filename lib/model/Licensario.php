<?php
Class Licensario{
	const HEADER_API_KEY = 'ISV_API_KEY';
	const HEADER_API_SECERT = 'ISV_API_SECRET';
	const API_URL = 'https://users.licensario.com';
	const API_USER_URI = '/api/v1/users/external/';
	const API_WIZARD_URI = '/api/v1/wizards';
	const USER_PREFIX = 'P';
	
	private static function api($method, $uri = "", $data=null){
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
				self::HEADER_API_KEY . ': ' . self::getApiKey(),
				self::HEADER_API_SECERT . ': ' . self::getApiSecret(),
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
	
	private static function getApiKey(){
		return sfConfig::get('app_licensario_apiKey');
	}
	
	private static function getApiSecret(){
		return sfConfig::get('app_licensario_apiSecret');
	}
	
	private static function addUser($id, $data){
		$res = self::api("PUT", self::API_USER_URI . self::USER_PREFIX . $id, $data);
	}
	
	public static function getExternalUserId($id, $data){
		return (self::addUser($id, $user)) ? null : self::USER_PREFIX . $id;
	}
	
	public static function getToken($externalUserId){
		$res = array('success' => 'false');
		
		$data = array('externalUserId' => $externalUserId);
		return self::api("POST", self::API_WIZARD_URI, $data);
	}
}