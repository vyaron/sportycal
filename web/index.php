<?php
require_once(dirname(__FILE__).'/../lib/model/NeverMissUtils.php');
//TODO: find nice solution
if (NeverMissUtils::hostIsNeverMiss()){
	define('FACEBOOK_APP_ID', '144855559036945');
	define('FACEBOOK_SECRET', 'c6254950824ec32f2a1e3c14b5c286d2');
} else {
	// PROD
	//define('FACEBOOK_APP_ID', '144339832273543');
	//define('FACEBOOK_SECRET', '2b3826caa86512d10958465a54de5afb');
	
	// DEV
	define('FACEBOOK_APP_ID', '313400138677127');
	define('FACEBOOK_SECRET', '62094be3d3db0caf976e93cc44ef7cad');
}

define('WEB_ROOT', dirname(__FILE__));
define('SPORTYCAL_ROOT', WEB_ROOT . '/../');
define('SPIDERS_OUTPUT_PATH', SPORTYCAL_ROOT . 'test/');

define('CAPTCHA_IMAGES_FOLDER',"/images/captchas/");
define('ROOT_CAPTCHA_IMAGES_FOLDER',WEB_ROOT . CAPTCHA_IMAGES_FOLDER);

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

// Determine if we should use a alternative view
$subdomain = substr($_SERVER['SERVER_NAME'], 0, strpos($_SERVER['SERVER_NAME'], '.'));
$isMobile = false;
if ($subdomain == 'm') {
	$isMobile = true;	
}
define ('IS_MOBILE', $isMobile);

$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'prod', false);
sfContext::createInstance($configuration)->dispatch();

// OLD - NOT SURE WHAT THIS IS
//define('FACEBOOK_APP_ID', '141286632559083');
//define('FACEBOOK_SECRET', '60e3898c83cd60da881e89f6bf25fb11');
