<?php
//define('SPIDERS_OUTPUT_PATH', '/tmp/spiders/');

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'prod', false);

sfContext::createInstance($configuration);

define('WEB_ROOT', dirname(__FILE__));


$sportWiser = new SportWiser();
$sportWiser->updateEventStat();
echo $sportWiser;
?>