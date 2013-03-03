<?php
// PROD
//define('FACEBOOK_APP_ID', '141286632559083');
//define('FACEBOOK_SECRET', '60e3898c83cd60da881e89f6bf25fb11');

// DEV
define('FACEBOOK_APP_ID', '313400138677127');
define('FACEBOOK_SECRET', '62094be3d3db0caf976e93cc44ef7cad');



define('WEB_ROOT', dirname(__FILE__));
define('SPORTYCAL_ROOT', WEB_ROOT . '/../');
define('SPIDERS_OUTPUT_PATH', WEB_ROOT . '/../test/');


define('CAPTCHA_IMAGES_FOLDER',"/images/captchas/");
define('ROOT_CAPTCHA_IMAGES_FOLDER',WEB_ROOT . CAPTCHA_IMAGES_FOLDER);
// this check prevents access to debug front controllers that are deployed by accident to production servers.
// feel free to remove this, extend it or make something more sophisticated.
if (!in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1')))
{
  die('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}


// Determine if we should use a alternative view
$subdomain = substr($_SERVER['SERVER_NAME'], 0, strpos($_SERVER['SERVER_NAME'], '.'));
$isMobile = false;
if ($subdomain == 'm') {
	$isMobile = true;	
}
define ('IS_MOBILE', $isMobile);




require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'dev', true);
sfContext::createInstance($configuration)->dispatch();
