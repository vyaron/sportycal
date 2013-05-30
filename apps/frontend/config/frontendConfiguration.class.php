<?php
require_once(dirname(__FILE__).'/../../../lib/model/NeverMissUtils.php');
class frontendConfiguration extends sfApplicationConfiguration
{
  public function configure(){
  	
  }
  
  public function initConfiguration(){
  	parent::initConfiguration();
  	
  	//TODO: need investigate
  	if ($this->isDebug()) sfConfig::set('sf_web_debug', true);
  	
  	if (NeverMissUtils::hostIsNeverMiss()){
  		if ($this->getEnvironment() == 'dev'){
  			sfConfig::set('app_domain_short', 'inevermiss.local');
  			sfConfig::set('app_domain_full', 'http://inevermiss.local/');
  			sfConfig::set('app_facebook_appId', '144855559036945');
  			sfConfig::set('app_facebook_secret', 'c6254950824ec32f2a1e3c14b5c286d2');
  		} else {
  			sfConfig::set('app_domain_short', 'inevermiss.biz');
  			sfConfig::set('app_domain_full', 'http://inevermiss.biz/');
  			sfConfig::set('app_facebook_appId', '398575090257350');
  			sfConfig::set('app_facebook_secret', 'c64b6bd02f8a2dc1da4a0bc6f88fdc36');
  		}
  	}
  }
}
