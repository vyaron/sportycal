<?php
class frontendConfiguration extends sfApplicationConfiguration
{
  const I_NEVER_MISS = 'inevermiss';
	
  public function configure(){
  	
  }
  
  private function getNeverMissDomainExt(){
  	$ext = null;
  	
  	$host = strtolower($_SERVER['HTTP_HOST']);
  	$hostParts = explode('.', $host, 3);
  	
  	if ($hostParts >= 0) $ext = $hostParts[count($hostParts) -1];

  	return $ext;
  }
  
  public function initConfiguration(){
  	parent::initConfiguration();
  	
  	$isNevermissTest = (strpos(strtolower($_SERVER['HTTP_HOST']), 'lempert') !== false) ? true : false;
  	
  	//TODO: need investigate
  	if ($this->isDebug()) sfConfig::set('sf_web_debug', true);
  	
  	if ((strpos(strtolower($_SERVER['HTTP_HOST']), 'inevermiss.') !== false) || $isNevermissTest) sfConfig::set('app_domain_isNeverMiss', true);
  	
  	if (sfConfig::get('app_domain_isNeverMiss')){
  		sfConfig::set('app_domain_name', 'iNeverMiss');
  		
  		if ($isNevermissTest){
  			sfConfig::set('app_domain_short', 'lempert.co.il');
  			sfConfig::set('app_domain_full', 'http://lempert.co.il');
  		} else {
  			$ext = $this->getNeverMissDomainExt();
  			if ($ext){
  				sfConfig::set('app_domain_short', 'inevermiss.' . $ext);
  				sfConfig::set('app_domain_full', 'http://inevermiss.' . $ext);
  			}
  		}
  		
  		
  		if ($this->getEnvironment() == 'dev'){
  			sfConfig::set('app_facebook_appId', '144855559036945');
  			sfConfig::set('app_facebook_secret', 'c6254950824ec32f2a1e3c14b5c286d2');
  		} else if ($this->getEnvironment() == 'ec2'){
  			sfConfig::set('app_facebook_appId', '1418512618366881');
  			sfConfig::set('app_facebook_secret', '5826d11b1a917d0547f45c2f7f221ec7');
  		} else {
  			sfConfig::set('app_facebook_appId', '398575090257350');
  			sfConfig::set('app_facebook_secret', 'c64b6bd02f8a2dc1da4a0bc6f88fdc36');
  		}
  	}
  	
  	$subdomain = substr($_SERVER['SERVER_NAME'], 0, strpos($_SERVER['SERVER_NAME'], '.'));
  	$isMobile = false;
  	if ($subdomain == 'm') {
  		sfConfig::set('app_domain_isMobile', true);
  	}

  	//var_dump(sfConfig::getAll());die();
  }
}
