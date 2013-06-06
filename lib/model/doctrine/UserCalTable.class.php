<?php


class UserCalTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('UserCal');
    }
	
 	public static function getBy($minDate=null, $sort="id", $orderBy=null, $limit=null, $hash=null){
    	
    	$q = Doctrine::getTable('UserCal')
                ->createQuery('uc');
		
        //$q->select('uc.*, COUNT(uc.id) AS num_user_cal');
              
        
        $q->where('1=1');
        if ($minDate) $q->andWhere('uc.taken_at > :baseDate', array(':baseDate' => $minDate));
        if ($hash) $q->andWhere('uc.hash = :hash', array(':hash' => $hash));
        
                
		//if ($partnerId) $q->andWhere('a.partner_id = ? or a.partner_id is null', $partnerId);
        
        if ($sort == "id") {
        	$q->orderBy('id ' . $orderBy . ', taken_at desc');
        } else if ($sort == "type") {
        	$q->orderBy('cal_type ' . $orderBy . ', taken_at desc');
        } else if ($sort == "taken") {
        	$q->orderBy('taken_at ' . $orderBy . ', user_id');
        } else if ($sort == "name") {
        	$q->orderBy('name ' . $orderBy . ', user_id');
        } else if ($sort == "user") {
        	$q->orderBy('user_id ' . $orderBy . ', cal_type');
        } else if ($sort){
        	$q->orderBy($sort . ' ' . $orderBy);
        }
        
        if ($limit) $q->limit($limit);
        
        return $q->execute();
    }
    
    public static function getBy1($minDate=null, $sort=null, $orderBy=null){
    	
    	$q = Doctrine::getTable('UserCal')
                ->createQuery('uc');

        if ($minDate) {
			$q->where('uc.taken_at > :baseDate', array(':baseDate' => $minDate));        	 
        }
                
		//if ($partnerId) $q->andWhere('a.partner_id = ? or a.partner_id is null', $partnerId);
        
        if ($sort == "id") {
        	$q->orderBy('id, taken_at desc');
        } else if ($sort == "type") {
        	$q->orderBy('cal_type, taken_at desc');
        } else if ($sort == "taken") {
        	$q->orderBy('taken_at desc, user_id');
        } else if ($sort == "name") {
        	$q->orderBy('name, user_id');
        } else if ($sort == "user") {
        	$q->orderBy('user_id, cal_type');
        } else {
        	$q->orderBy('taken_at desc, user_id');
        }
        
        return $q->execute();
    }
    
  	public static function getSubscribes($userId = null){
  		$q = Doctrine::getTable('UserCal')->createQuery('uc')
  			->innerJoin('uc.Cal c')
  			//->innerJoin('c.Event e')
	  		->where('uc.type = ?', Cal::TYPE_MAILINGLIST)
  			->groupBy('uc.user_id, uc.cal_id');
  		
  		if ($userId) $q->andWhere('uc.user_id = ?', $userId);
  		
  		$q->execute();
  	}
    
    public static function deleteMailinglist($userId){
    	$q = Doctrine_Query::create()
	    	->delete('UserCal uc')
	    	->where('uc.user_id = ?', $userId)
	    	->andWhere('uc.cal_type = ?', Cal::TYPE_MAILINGLIST);
    	
    	return $q->execute();
    }
}