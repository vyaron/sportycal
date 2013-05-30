<?php

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

$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'dev', false);
sfContext::createInstance($configuration)->dispatch();

// OLD - NOT SURE WHAT THIS IS
//define('FACEBOOK_APP_ID', '141286632559083');
//define('FACEBOOK_SECRET', '60e3898c83cd60da881e89f6bf25fb11');
