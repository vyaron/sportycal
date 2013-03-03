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

    
    
}