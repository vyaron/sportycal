<?php


class PartnerDescTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('PartnerDesc');
    }

    
    public static function getBy($partnerId, $calId) {
		
    	$q = Doctrine::getTable('PartnerDesc')->createQuery('pd');
    	
    	$q->where('1=1');
    	
    	if ($partnerId) $q->andWhere('pd.partner_id = :partnerId', 	array(':partnerId' => $partnerId));
    	if ($calId) 	$q->andWhere('pd.cal_id = :calId', 			array(':calId' => $calId));

// 		echo $q->getSqlQuery(), "<br/>";
//		print_r($q->getParams());
//		die();    	
    	
    	if ($partnerId && $calId) {
    		$pDescs = $q->fetchOne();
    	} else {
    		$pDescs = $q->execute();	
    	}
    	
    	//Utils::pa("Cal: $calId");
    	//Utils::pp("Partner: $partnerId");
    	return $pDescs; 
    }    
    
}