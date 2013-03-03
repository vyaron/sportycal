<?php


class CategoryTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Category');
    }

    
    
    public static function getCategories($ctgId=null, $name=null, $nameLike=null, $fetchOne=false, $onlyWithCals=false){
        
    	$q = Doctrine::getTable('Category')->createQuery('c');
    	
    	$user = UserUtils::getLoggedIn();
    	if (!($user && $user->isMaster())) $q->where('c.deleted_at is null');

		/*
		 * Functinality not needed for now
		if ($onlyWithCals) {
			$q->andWhere('c.cals_count > 0');
		}
    	*/  	
    	if ($ctgId !== "ALL") {
	        $w = 'c.parent_id = ?';
	        if (!$ctgId) $w = 'c.parent_id is null';
	        $q->andWhere($w, $ctgId);
    	}
    	

    	if (!$user || $user->isSimple()) {
    		$q->andWhere('c.is_public = 1');
    	} elseif ($user->isPartner()) {
    		$q->andWhere('(c.is_public = 1');
    		if (UserUtils::getPartnerIdMaster()) $q->orWhere("c.partner_id = " . UserUtils::getPartnerIdMaster() . ")");
    	} else { 
    		//IS MASTER
    	}
		
        if ($name) 	$q->andWhere('c.name = ?', $name);
        if ($nameLike) 	 {
        	$q->andWhere('c.name like ?', $nameLike . '%');	
        	$q->orWhere('? like c.name', $nameLike);
        }
        
        $q->orderBy('c.rate desc, c.cals_count desc, c.rate desc, c.name, c.is_public');
        
        //if ($nameLike) {
	        //echo "<br/>*****************************************************<br/>";
	        //echo $q->getSqlQuery();
	        //echo "<br/>*****************************************************<br/>";
        //}
        //die("DIE");
        
        //echo "<br/>*****************************************************<br/>";
        //echo $q->getSqlQuery();
        //echo "<br/>*****************************************************<br/>";
        //die("DIE");
        
        if ($fetchOne) 	return  $q->fetchOne();     
        else 			return  $q->execute();

    }

    public static function getByIds($ctgIds){
        
        $ctgs =     Doctrine::getTable('Category')
                    ->createQuery('c')
                    ->whereIn('id', $ctgIds)
                    ->orderBy('c.id')
                    ->execute();
        return $ctgs;
    }

    public static function getCtgsBy1($txtSearch, $fromDate='', $toDate=''){
		$stmt = Doctrine_Manager::getInstance()->connection();
		$results = $stmt->execute("SELECT * FROM category WHERE parent_id = ?", array(1));
		var_dump($results->fetchAll());    
		die("enough");
    }    
    
    public static function getCtgsBy($txtSearch, $fromDate='', $toDate='', $locId=null, $limit = null, $isPublic = true, $partnerId=null, $parentId=null, $ctgNames=null){
        
    	$txtSearch = self::cleanTxtSearch($txtSearch);
    	
        $q =  Doctrine::getTable('Category')->createQuery('c');
        
        
		$q->leftJoin('c.Cal cl');
        
		if ($txtSearch) {              
        	$q->where('(c.name like :txt1 OR cl.name like :txt2)', array(':txt1' => "%$txtSearch%", ':txt2' => "%$txtSearch%"));
        	//$q->orWhere('cl.name like :txt', array(':txt' => "%$txtSearch%"));
		}
		
		$user = UserUtils::getLoggedIn();
    	if (!$user || !$user->isMaster()) {
			$q->andWhere('c.is_public = 1');    		
    	}
		
		
		if ($fromDate || $toDate || $locId) {                
			$q->leftJoin('cl.Event e');
			$q->leftJoin('e.Address a');
			$q->leftJoin('a.Location l');
			if ($fromDate) 	$q->andWhere('e.starts_at >= :fromDate', array(':fromDate' => $fromDate));			
			if ($toDate) 	$q->andWhere('e.ends_at <= :toDate', array(':toDate' => $toDate));
			if ($locId) 	$q->andWhere('l.id = :locId', array(':locId' => $locId));
		}
		
		if ($isPublic){
			$q->andWhere('cl.deleted_at is null');
			$q->andWhere('c.deleted_at  is null');
			$q->andWhere('cl.is_public = 1');
			$q->andWhere('c.is_public = 1');
	    }
	    
		if ($partnerId) $q->andWhere('c.partner_id = ?', $partnerId);
		if ($parentId) 	$q->andWhere('c.parent_id = ?', $parentId);
		if ($ctgNames) 	$q->andWhereIn('c.name', $ctgNames);
		
		$q->groupBy('c.id');
        $q->orderBy('c.rate desc, c.cals_count desc, c.name');
		
        if ($limit){
        	$q->limit($limit);
        }
        
        //echo $q->getSqlQuery() . "<br/><br/><br/>";
        //die("DIE");
        
		return $q->execute();
    }

    private static function cleanTxtSearch($txtSearch) {
    	$txtSearch = str_replace(",", " ", $txtSearch);
    	
    	return $txtSearch;
    }
    
    public static function calculateCalsCount() {
        
        $result = 0;
        $ctgId = 0;

        $subCtgs = CategoryTable::getCategories($ctgId);
        foreach($subCtgs as $subCtg) {
            $result += $subCtg->doCalculateCalsCount(array($subCtg->getId()));
        }

        return $result;
    }

    public static function getRootCategory($ctgId) {
        
    	 $category = Doctrine::getTable('Category')->find($ctgId);
    	 
    	 if ($category->getParentId())  $category = $category->getRootCategory();
		 return $category;
    	 
//        $done = false;
//        $result = null;
//        
//        while (!$done) {
//            $category = Doctrine::getTable('Category')->find(array($ctgId));
//            $ctgId = $category->getParentId();
//            if (!$ctgId) {
//                $result = $category;
//                $done = true;
//                continue;
//            }
//        }
//        return $result;
    }

    public static function getRootCategoriesOf($ctgs) {
    	$ctgIds = array();
    	foreach($ctgs as $ctg) {
    		$ctgId = $ctg->getRootCategoryId();
    		if (!isset($ctgIds[$ctgId])) $ctgIds[$ctgId] = $ctgId;
    	}

    	$rootCtgs = array();
    	if ($ctgIds) {
    		$rootCtgs = self::getByIds(array_keys($ctgIds));
    	}
    	return $rootCtgs;
    }

    public static function divideSponsored($ctgs, &$regularCtgs, &$sponsCtgs) {
		foreach ($ctgs as $ctg) {
			if ($ctg->getPartnerId()) 	$sponsCtgs[] = $ctg; 
			else  						$regularCtgs[] = $ctg;
		}    	
    }

    
    // send $val =1 to inc, or $val=-1 to dec
    public static function updateCalsCountForPathEndsWith($category, $val) {
    	$ctgs = $category->getCategoryPath();

    	// for safety..
    	$val = ($val>0)? 1 : -1;
    	
    	foreach ($ctgs as $ctg) {
    		$ctg->setCalsCount($ctg->getCalsCount()+$val);
    		//Utils::pa($ctg);
    		$ctg->save();
    	}
    	//Utils::pp();
    }
}
