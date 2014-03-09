<?php

/**
 * Class: UserUtils
 * This class stores all the User mgmt logic
 */
 
class UserUtils {

	const KEY_USER          		= "user";
	const KEY_SECURITY      		= "security";
	
    const KEY_TZ            		= "tz";
    const KEY_TZ_VALUE            	= "tz_value";
    const KEY_FULL_NAME_TZ			= "fullNameTz";
    
    const KEY_PARTNER_ID          	= "partnerId";
    const KEY_PARTNER_ID_MASTER   	= "partnerIdMaster";
    const KEY_FROM_FB_APP   		= "fromFbApp";
    const KEY_FROM_ANDROID_APP		= "fromAndroidApp";
        
	const KEY_REF_USER_ID			= "refUserId";
	
	const KEY_CAPTCHA_STR			= 'captchaStr';
	const KEY_CAPTCHA_FILE_NAME		= 'captchaFileName';
	
	const KEY_USER_CAL_IDS			= 'userCalIds';
	
	const KEY_COUNTRY_BY_IP			= 'countryByIp';
	
	const KEY_ORPHAN_CAL_ID			= 'orphanCalId';
	
	const KEY_REFERER_URL			= 'refererUrl';
    const KEY_WIX_INSTANCE			= 'wixInstance';
	
	private static $cachedPartner = null;
	
   public static function logUserIn($user)  {
   	
   	self::logUserOut();

   	$pu = PartnerUser::getPartnerUser($user->getId());
   	
    $userSession = sfContext::getInstance()->getUser();
    $userSession[self::KEY_USER] 		= $user;
    if ($pu) {    	
    	$userSession[self::KEY_PARTNER_ID] 			= $pu->getPartnerId();
    	$userSession[self::KEY_PARTNER_ID_MASTER] 	= $pu->getPartnerId();
    }
    
    
  }
	
  public static function setOrphanCalId($calId){
  	$userSession = sfContext::getInstance()->getUser();
  	 
  	if ($calId){
  		$userSession[self::KEY_ORPHAN_CAL_ID] = $calId;
  	} else {
  		unset($userSession[self::KEY_ORPHAN_CAL_ID]);
  	}
  }
  
  public static function getOrphanCalId(){
  	return self::getFromSession(self::KEY_ORPHAN_CAL_ID);
  }

    public static function setWixInstance($instance){
        $userSession = sfContext::getInstance()->getUser();

        if ($instance){
            $userSession[self::KEY_WIX_INSTANCE] = $instance;
        } else {
            unset($userSession[self::KEY_WIX_INSTANCE]);
        }
    }

    public static function getWixInstance(){
        return self::getFromSession(self::KEY_WIX_INSTANCE);
    }
  
  public static function setRefererUrl($url){
  	$userSession = sfContext::getInstance()->getUser();
  
  	if ($url){
  		$userSession[self::KEY_REFERER_URL] = $url;
  	} else {
  		unset($userSession[self::KEY_REFERER_URL]);
  	}
  }
  
  public static function getRefererUrl(){
  	return self::getFromSession(self::KEY_REFERER_URL);
  }
  
  public static function logUserOut()  {
    $userSession = sfContext::getInstance()->getUser();
    unset($userSession[self::KEY_USER]);
    unset($userSession[self::KEY_PARTNER_ID]);
    unset($userSession[self::KEY_PARTNER_ID_MASTER]);
  }

    public static function getPartnerIdMaster() {
    	return self::getFromSession(self::KEY_PARTNER_ID_MASTER);
    }
    
    public static function getPartnerIfPartnerMaster() {
        $partnerId = self::getPartnerIdMaster();
		if ($partnerId) return PartnerTable::getById($partnerId);
		else return null;
    }
    
    
    public static function isMaster() {
        $user = self::getLoggedIn();
        if ($user && $user->isMaster()) return true;
        return false;
    }
    
 	/**
 	 * @return User
 	 */
    public static function getLoggedIn() {
        return self::getFromSession(self::KEY_USER);
    }
    public static function getLoggedInId() {
        $user = self::getLoggedIn();
        if (!$user) return null;
        return $user->getId();
    }
        
    public static function putSecurityToken()  {
    	$token = time();
        $userSession = sfContext::getInstance()->getUser();
        $userSession[self::KEY_SECURITY] = $token;
        return $token;
    }
    public static function getSecurityToken()  {
		return self::getFromSession(self::KEY_SECURITY);
    }
    
    public static function isValidSecurityToken($token, $clear=true)  {
    	$prevToken = self::getFromSession(self::KEY_SECURITY);
    	// Yaron: HAD to give it some greiss, there is some kind of weird symfony problem here
    	// setting the value in the session in the views behave WIERD!!!
    	// look at _feedback to see example on how this security token is set
    	$isValid = ($prevToken-$token  < 20);
    	
        if ($clear) {
        	$userSession = sfContext::getInstance()->getUser();
        	unset($userSession[self::KEY_SECURITY]);
        }
        return $isValid;
    }
    
    public static function setUserTZ($tz)  {
        $userSession = sfContext::getInstance()->getUser();
        $userSession[self::KEY_TZ] = $tz;
    }

    public static function getUserTZ() {
        return self::getFromSession(self::KEY_TZ);
    }
    
    public static function setUserTzValue($tz)  {
    	$userSession = sfContext::getInstance()->getUser();
    	$userSession[self::KEY_TZ_VALUE] = $tz;
    }
    
    public static function getUserTzValue() {
    	return self::getFromSession(self::KEY_TZ_VALUE);
    }
    
 	public static function setUserFullNameTZ($tz)  {
        $userSession = sfContext::getInstance()->getUser();
        $userSession[self::KEY_FULL_NAME_TZ] = $tz;
    }

    public static function getUserFullNameTZ() {
        return self::getFromSession(self::KEY_FULL_NAME_TZ);
    }
	
    public static function userISMasterOf($category=null) {
    	
    	$user = self::getLoggedIn();
    	
    	if (!$user) 			return false;
    	if ($user->isMaster()) 	return true;
    	if (!$category)			return false;
    	
    	if ($user->isPartner() &&
    		UserUtils::getPartnerIdMaster() == $category->getPartnerId()) return true;
    	
    	return false;
    }
    public static function setRefUserId($refUserId) {
		$refUser = Doctrine::getTable('User')->find($refUserId);
    	if ($refUser) {
		    $userSession = sfContext::getInstance()->getUser();
		    if (isset($userSession)) $userSession[self::KEY_REF_USER_ID] = $refUserId;
    	}    
  	}

    public static function getRefUserId() {
		return self::getFromSession(self::KEY_REF_USER_ID); 		
    }
    
 	public static function setCountryByIp($countryByIp) {
		$userSession = sfContext::getInstance()->getUser();
		if (isset($userSession)) $userSession[self::KEY_COUNTRY_BY_IP] = $countryByIp;
    }
    
	public static function getCountryByIp() {
		$countryByIp = self::getFromSession(self::KEY_COUNTRY_BY_IP);
		
		if (!$countryByIp){
			$ip = $_SERVER['REMOTE_ADDR'];
			
			//$ip = '87.69.89.115';
			
			$countryByIp = DbUtils::getCountryByIp($ip);
			self::setCountryByIp($countryByIp);
		}
		
		return $countryByIp;
    }
    
 	public static function setPartner($partner){
 		$userSession = sfContext::getInstance()->getUser();
 		
 		if ($partner){
    	 	$userSession[self::KEY_PARTNER_ID] = $partner->getId();
    	} else {
    		unset($userSession[self::KEY_PARTNER_ID]);
    	}
    	
    	self::$cachedPartner = $partner;
    }
    
 	public static function getPartner(){
 		$partner = self::$cachedPartner;
 		
 		if (!$partner){
 			$partnerId = self::getFromSession(self::KEY_PARTNER_ID);
 			if ($partnerId) $partner = PartnerTable::getById($partnerId);
 			if ($partner) self::setPartner($partner);
 		}
 		
		return $partner;	
    }

	public static function setFromAndroidApp($an){
		$userSession = sfContext::getInstance()->getUser();
		
		if (isset($an)){
			if ($an) $userSession[self::KEY_FROM_ANDROID_APP] = true;
			else unset($userSession[self::KEY_FROM_ANDROID_APP]);
		}
	}
    
	public static function getFromAndroidApp(){
    	 $userSession = sfContext::getInstance()->getUser();
    	 return (isset($userSession[self::KEY_FROM_ANDROID_APP]))? $userSession[self::KEY_FROM_ANDROID_APP] : null;
    }
	
	public static function setFromFbApp($fb){
		$userSession = sfContext::getInstance()->getUser();
		
		if (isset($fb)){
			if ($fb) $userSession[self::KEY_FROM_FB_APP] = true;
			else unset($userSession[self::KEY_FROM_FB_APP]);
		}
	}
	
	public static function getFromFbApp(){
    	 $userSession = sfContext::getInstance()->getUser();
    	 return (isset($userSession[self::KEY_FROM_FB_APP]))? $userSession[self::KEY_FROM_FB_APP] : null;
    }
    
    private static function getFromSession($name, $default=null) {
		$userSession = sfContext::getInstance()->getUser();
    	if (isset($userSession[$name])) return $userSession[$name];
    	else return $default; 
    }
    
    
    public static function generateCaptcha(){
    	self::removeCaptcha();
    	
    	$captcha 			= new Captcha();
	  	$imgCaptcha 		= $captcha->create();
	  	
		$strCaptcha 		= $captcha->getCaptchaStr();
		if ($strCaptcha) 	self::setCaptchaStr($strCaptcha);
		
		$fileNameCaptcha 		= $captcha->getCaptchaFileName();
		if ($fileNameCaptcha) 	self::setCaptchaFileName($fileNameCaptcha);
    }
    
    public static function removeCaptcha(){
    	self::setCaptchaStr(null);
    	self::setCaptchaFileName(null);
    }
    
	public static function getCaptchaStr(){
		return self::getFromSession(self::KEY_CAPTCHA_STR); 		
    }
    
	public static function setCaptchaStr($cStr){
		$userSession = sfContext::getInstance()->getUser();
    	
    	if ($cStr){
    	 	$userSession[self::KEY_CAPTCHA_STR] = $cStr;
    	} else {
    		unset($userSession[self::KEY_CAPTCHA_STR]);
    	}
    }
    
	public static function getCaptchaFileName(){
		return self::getFromSession(self::KEY_CAPTCHA_FILE_NAME); 		
    }
    
	public static function setCaptchaFileName($cFileName){
		$userSession = sfContext::getInstance()->getUser();
    	
    	if ($cFileName){
    	 	$userSession[self::KEY_CAPTCHA_FILE_NAME] = $cFileName;
    	} else {
    		Captcha::deleteImageFile($userSession[self::KEY_CAPTCHA_FILE_NAME]);
    		unset($userSession[self::KEY_CAPTCHA_FILE_NAME]);
    	}
    }
    
	public static function getCaptchaImgPath(){
		if (!self::getFromSession(self::KEY_CAPTCHA_FILE_NAME)) self::generateCaptcha();
		return Captcha::getImageFilePath(self::getFromSession(self::KEY_CAPTCHA_FILE_NAME), false); 		
    }
    
    public static function isValidCaptcha($captchaStr){
    	return ($captchaStr && $captchaStr === self::getCaptchaStr());
    }
    
    public static function setUserCalId($key, $userCalId){
    	$userSession = sfContext::getInstance()->getUser();
    	
    	if ($key && $userCalId){
    		$userCalsIds = $userSession[self::KEY_USER_CAL_IDS];
    		
    		if (!$userCalsIds) $userCalsIds = array();
    		
    		$userCalsIds[$key] = $userCalId;
    		
    	 	$userSession[self::KEY_USER_CAL_IDS] = $userCalsIds;
    	}
    }
    
	public static function getUserCalId($key){
    	$userSession = sfContext::getInstance()->getUser();
    	$userCalId = null;
    	if ($key){
    	 	$userSession = sfContext::getInstance()->getUser();
    	 	$userCalsIds = $userSession[self::KEY_USER_CAL_IDS];
	    	if (isset($userCalsIds[$key])){
	    		$userCalId = $userCalsIds[$key];
	    	}
    	}
    	
    	return $userCalId;
    }
}
?>