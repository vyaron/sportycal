<?php	
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
