<?php


class IntelTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Intel');
    }
    
    public static function insertNew($section, $action, $label, $value, $userId=null, $calId=null, $ctgId=null, $eventId=null, $partnerId=null, $userCalId=null) {
    	
    	if (!$userId) $userId = UserUtils::getLoggedInId();
    	
	    $sessionCode = session_id();
	    $dateNow    = date("Y-m-d H:i:s");
	    $ip = Utils::getClientIP();
    	
    	
    	$intel = new Intel();
	    if ($calId) 		$intel->setCalId($calId);
	    if ($ctgId) 		$intel->setCategoryId($ctgId);
	    if ($eventId) 		$intel->setEventId($eventId);
	    if ($userId) 		$intel->setUserId($userId);
	    if ($partnerId) 	$intel->setPartnerId($partnerId);
	    if ($userCalId) 	$intel->setUserCalId($userCalId);
	    $intel->setSessionCode($sessionCode);
	    $intel->setSection($section);
	    $intel->setAction($action);
	    $intel->setLabel($label);
	    $intel->setValue($value);
	    $intel->setCreatedAt($dateNow);
	    $intel->setIpAddress($ip);
	    $intel->save();

	    return $intel->getId();
    }
}