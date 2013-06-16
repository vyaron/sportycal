<?php


class CalTable extends Doctrine_Table
{
    private static $reminderValues = array(1,2,4,24);
	
    public static function getReminderValues(){
    	return self::$reminderValues;
    }
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Cal');
    }

    public static function getCals($ctgId=null, $partnerId=null, $calNames=null, $showPastCals=true, $notCalId = null, $underCtgId = null){
        $q = Doctrine::getTable('Cal')
                ->createQuery('c') 
                ->where('c.deleted_at is null');

        if (!$showPastCals) {
			$q->andWhere('CURDATE() < (select max(ends_at) from event where cal_id = c.id)');
        }                
		
		if ($ctgId) 		$q->andWhere('c.category_id = ?', $ctgId);
		if ($partnerId) 	$q->andWhere('c.partner_id = ?', $partnerId);
		if ($notCalId) 		$q->andWhere('c.id != ?', $notCalId);
		
		
		if ($underCtgId)	$q->andWhere('(c.category_ids_path LIKE ? OR c.category_ids_path LIKE ?)', array('%,' . $underCtgId . ',%', '%,' . $underCtgId));
		if ($calNames) 		$q->andWhereIn('c.name', $calNames);
               
        $q->orderBy('rate desc, name asc');

        $cals = $q->execute();
        return $cals;
    }
    
    public static function getCalList($userId){
    	$q = Doctrine_Query::create()
	    	->select('c.id, c.name, c.updated_at, COUNT(e.id) event_count')
	    	->from('Cal c')
	    	->leftJoin('c.Event e')
	    	->where('c.by_user_id = ?', $userId)
	    	->andWhere('c.deleted_at IS NULL')
	    	->orderBy('c.updated_at DESC');
    	
    	$cals = $q->fetchArray();
    	
    	$calId2CalIndex = array();
    	foreach ($cals as $i => $cal){
    		$cal['cal_request_count'] = 0;
    		$calId2Index[$cal['id']] = $i;
    	}
    	
    	$calIds = array_keys($calId2Index);
    	if (count($calIds)){
    		$q = Doctrine_Query::create()
	    		->select('c.id, COUNT(cr.id) cal_request_count')
	    		->from('Cal c')
	    		->leftJoin('c.CalRequest cr')
	    		->whereIn('c.id', $calIds)
	    		->andWhere('c.deleted_at IS NULL')
	    		->groupBy('cr.cal_id');
    		$calRequests = $q->fetchArray();
    		
    		foreach ($calRequests as $calRequest){
    			$cals[$calId2Index[$calRequest['id']]]['cal_request_count'] = $calRequest['cal_request_count'];
    		}
    	}
    	
    	return $cals;
    }
}