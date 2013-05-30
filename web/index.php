<?php
require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'dev', false);

sfContext::createInstance($configuration)->dispatch();



// OLD - NOT SURE WHAT THIS IS
//define('FACEBOOK_APP_ID', '141286632559083');
//define('FACEBOOK_SECRET', '60e3898c83cd60da881e89f6bf25fb11');
