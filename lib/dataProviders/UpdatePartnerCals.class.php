<?php

abstract class UpdatePartnerCals {
	
	protected function getCtgName2CtgMapForPartner($partnerId, $parentId, $ctgNames, $ordered=false){
		$ctgs = CategoryTable::getCtgsBy(null, null, null, null, null, false, $partnerId, $parentId, $ctgNames);
		
		$now = date( 'Y-m-d H:i:s', time());
		
		$ctgNames2ctg = array();
		
		$collectionCategory = new Doctrine_Collection('Category');
		foreach ($ctgNames as $i => $ctgName){
			$exist = false;
	
			foreach($ctgs as $ctg) {
				if ($ctg->getName() === $ctgName) {
					$exist = true;
					$ctgNames2ctg[$ctgName] = $ctg;
					break;
				}
			}
			
			if (!$exist){
				$ctg = new Category();
				
				if ($ordered) $ctg->rate = $i;
				
				$ctg->setName($ctgName);
				$ctg->setPartnerId($partnerId);
				$ctg->setApprovedAt($now);
				$ctg->setParentId($parentId);
				
				
				$collectionCategory->add($ctg);
			}
		}
		
		// Need a double save here, as the ctg-path should include the ctg id
		$newCtgs = $collectionCategory->save();
		foreach ($newCtgs as $newCtg){
			$ctgNames2ctg[$newCtg->getName()] = $newCtg;
			
			$parentNewCtg = $newCtg->getParentCategory();
		  	$ctgPath = $parentNewCtg->getCategoryIdsPath() . "," . $newCtg->getId();
		  	$newCtg->setCategoryIdsPath($ctgPath);
		}
		$newCtgs = $collectionCategory->save();
		
		return $ctgNames2ctg;
	}
	
	protected function getCalName2CalMapForPartner($partnerId, $categoryId, $calNames, $ctg=null) {
        $cals = CalTable::getCals($categoryId, $partnerId, $calNames);
        
        $now = date( 'Y-m-d H:i:s', time() );
        $calName2Cal = array();
        
        // Create new Cals for the ones that dont exist yet
		foreach ($calNames as $calName) {
			$exist = false;
			foreach($cals as $cal) {
				if ($cal->getName() === $calName) {
					$exist = true;
					break;
				}
			}
			if (!$exist) {
				$cal = new Cal();
				$cal->setName($calName);
				$cal->setCategoryId($categoryId);
				$cal->setPartnerId($partnerId);
				$cal->setCreatedAt($now);
				$cal->setUpdatedAt($now);
				
				if ($ctg) $cal->setCategoryIdsPath($ctg->getCategoryIdsPath());
			}
			$calName2Cal[$calName] = $cal;
			
		}        

		//Utils::pp($calName2Cal);
        return $calName2Cal;
	}    
	
	
	protected function debugo($msg, $die = false) {
		echo "DEBUG: " . $msg . "<br/>";
		if ($die) die("DEATH is a BLESS");
	}
	

}