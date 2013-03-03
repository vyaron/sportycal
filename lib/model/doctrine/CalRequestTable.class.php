<?php


class CalRequestTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('CalRequest');
    }
    
    public static function newReq($calId, $calType, $userCalId, $partner = null, $ctgId=null, $hash=null) {
    	//Don't save CalRequest without UserCal
    	if (!$userCalId) return;
    	
        $mysqldate = date( 'Y-m-d H:i:s', time());
        
		//Try to catch existing calReq
        $q = Doctrine::getTable('CalRequest')->createQuery('c');
        $q->where('c.user_cal_id =?', array($userCalId));
        $q->limit(1);
        $calReq = $q->execute()->getFirst();
		
        //Create new calRec
        if (!$calReq){
	        $calReq = new CalRequest();
	        
	        if ($calId) $calReq->setCalId($calId);
	        if ($ctgId) $calReq->setCategoryId($ctgId);
	        if ($hash)  $calReq->setHash($hash);
	        
	        $calReq->setUserCalId($userCalId);
	        $calReq->setCalType($calType);
	        
	
	        if ($partner) {
	        	$calReq->setPartnerId($partner->getId());
	        }
        }
		
        $calReq->setCreatedAt($mysqldate);
        $calReq->save();
    }
    public static function getBy(){
        
        return  Doctrine::getTable('CalRequest')
                ->createQuery('uc')
                ->orderBy('created_at desc, cal_type')
                ->execute();
    }
    
    
}