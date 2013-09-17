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
    
    public static function getCalList($userId, $offset=0, $limit = 10){
    	$q = Doctrine_Query::create()
	    	->from('Cal c')
	    	->where('c.by_user_id = ?', $userId)
	    	->orderBy('c.deleted_at ASC, c.updated_at DESC');
    	
    	$total = $q->select('COUNT(c.id) AS cal_count')->fetchArray();
    	
    	if (isset($total[0]) && isset($total[0]['cal_count'])) $total = $total[0]['cal_count'];
    	else $total = 0;
    	
    	$q->select('c.id, c.name, c.updated_at, c.deleted_at, COUNT(e.id) event_count')
    		->leftJoin('c.Event e ON e.cal_id = c.id AND (e.starts_at > NOW() OR (e.starts_at <= NOW() AND e.ends_at > NOW()))')
    		->groupBy('c.id')
    		->offset($offset * $limit)
    		->limit($limit);
    	
    	$cals = $q->fetchArray();

    	$calId2CalIndex = array();
    	foreach ($cals as $i => $cal){
    		if (!$cal['id']) unset($cals[$i]);
    		$cal['cal_request_count'] = 0;
    		$calId2Index[$cal['id']] = $i;
    	}
    	
    	$calIds = $calId2Index ? array_keys($calId2Index) : array();
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
    	
    	foreach ($cals as &$cal){
    		if (!key_exists('cal_request_count', $cal)) $cal['cal_request_count'] = 0;
    	}
    	
    	$calList = array(
    		'offset' => $offset,
    		'limit' => $limit,
    		'total' => $total,
    		'data' => $cals		
    	);

    	return $calList;
    }
}