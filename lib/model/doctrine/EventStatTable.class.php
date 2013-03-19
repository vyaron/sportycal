<?php


class EventStatTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('EventStat');
    }
    
    public static function getBy($eventIds = null){
    	$q = Doctrine::getTable('EventStat')->createQuery('e');
    	$q->where('1=1');
    	$q->where('event_id in (' . implode(",", $eventIds) . ')');

    	//echo $q->getSqlQuery(), "<br/>";
    	//print_r($q->getParams());
    	//die();
    	 
    	$objs = $q->execute();
    	//Utils::pp($objs); 
    	return $objs;
    }
    
}