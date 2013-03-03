<?php


class CategoryLinkUsageTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('CategoryLinkUsage');
    }
    
    public static function newReq($userCalId, $ctgLinkId) {
        
        $mysqldate = date( 'Y-m-d H:i:s', time() );
        
        $categoryLinkUsage = new CategoryLinkUsage();
        
        if ($userCalId) $categoryLinkUsage->setUserCalId($userCalId);
        $categoryLinkUsage->setCategoryLinkId($ctgLinkId);
        $categoryLinkUsage->setCreatedAt($mysqldate);

        $categoryLinkUsage->save();

    }
    
    public static function getBy($minDate=null, $orderBy=null){
        
    	$q = Doctrine::getTable('CategoryLinkUsage')
                ->createQuery('clu');
    	
        if ($minDate) {
			$q->where('clu.created_at > :baseDate', array(':baseDate' => $minDate));        	 
        }
        
    	if ($orderBy == "user") {
        	$q->orderBy('user_id, cal_type');
        } else {
        	$q->orderBy('created_at desc, category_link_id');
        }

		return $q->execute();
    }
    
    
}