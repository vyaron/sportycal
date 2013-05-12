<?php


/**
 * Class: SportycalFilter
 * This class stores all the User mgmt logic
 */

class SportycalFilter extends sfFilter {
 
    public function execute($filterChain){
    	UserUtils::setFromFbApp($this->getReqParam('fb'));
    	UserUtils::setFromAndroidApp($this->getReqParam('an'));
    	
    	$partner = null;
    	$ref = $this->getReqParam('ref');
    	if ($ref) $partner = SportyCalAPI::getValidPartner(null,$this->getReqParam('ref'));
		
    	if ($partner) UserUtils::setPartner($partner);
    	else if ($ref)UserUtils::setPartner(null);
    	
        //runs before action
    	$refUserId = $this->getReqParam('iu');
    	if ($refUserId) {
    		UserUtils::setRefUserId($refUserId);
    		IntelTable::insertNew("invite", "ref-visit", null, null, $refUserId);
		    UserTable::creditToUser($refUserId, UserTable::CREDIT_REF_VISIT);
    		
    	}
    	
    	$this->handleLanguage();
    	
  		sfContext::getInstance()->getConfiguration()->loadHelpers(array('I18N', 'Date'));

  		$hasLayout = (sfConfig::get('has_layout', null) == 'off') ? false : true;

  		if ((strpos($_SERVER['HTTP_HOST'], 'promotecal.') !== false) && $hasLayout) $this->getContext()->getActionStack()->getFirstEntry()->getActionInstance()->setLayout('neverMiss');
  		
        //execute the next filter in the chain
        $filterChain->execute();
        //code after here runs after action
 
    }
    
    private function getReqParam($name, $default=null) {
    	if (isset($_REQUEST[$name])) return $_REQUEST[$name];
    	else return $default; 
    	
    }
    private function handleLanguage() {
    	$lang =  $this->getReqParam('lang');
    	if (!$lang) return;
    	
    	$langs = array('en', 'he');
    	if (!in_array($lang, $langs)) $lang = 'en'; 
  		sfContext::getInstance()->getUser()->setCulture($lang);
    	
    }
}