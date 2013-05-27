<?php


class LocationTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Location');
    }

    
    
    public static function getBy($underCtgIds, $fromDate, $toDate, $countryCode, $stateCode){
    	//$conn = Doctrine_Manager::connection();
		$q = new Doctrine_RawSql();
		
		
		$q->select('{l.*}')
		  ->from('location l')
		  ->addComponent('l', 'Location');

		$locWhere = '';
		if ($countryCode) {
			$locWhere .= " AND l.country = '$countryCode' ";
		}
		if ($stateCode) {
			$locWhere .= " AND l.state = '$stateCode' ";
		}
		
		$subCtgInStrAndParams 	= Utils::getSubCtgInStrAndParams($underCtgIds);
		$subCtgInStr 			= $subCtgInStrAndParams[0];	  
		$subCtgParams 			= $subCtgInStrAndParams[1];

		if (!$subCtgParams) $subCtgInStr = " 1=1 ";  
		
		
		//$strUnderCtgIds = implode(",", $underCtgIds);
		//die($subCtgInStr);
		$subSelect =   "(select distinct location_id 
					    from address a 
						WHERE l.id in 
							(select distinct location_id 
						 	from address a
						 	WHERE a.id in (select distinct e.address_id          
						                from event e 
						                        INNER JOIN cal cal ON (e.cal_id = cal.id) 
						                        INNER JOIN category ctg ON (cal.category_id = ctg.id)                
						                WHERE   e.starts_at >= :fromDate AND e.ends_at <= :toDate AND 
						                        $subCtgInStr AND
						                        ctg.deleted_at is null AND cal.deleted_at is null
						                )
						    )
						)
						$locWhere
						
						";		        

						                        
		//echo $subSelect, "<br/>";
		//die();						                        

		$params = array(':fromDate' => $fromDate, ':toDate' => $toDate);

		
		$params = array_merge($params, $subCtgParams);
		$q->where("l.id in $subSelect", $params);
		
		// NOTE - for some reason, this order by takes alot of time, dont use it!
		//$q->orderBy('l.name');
		// DONT USE LIMIT HERE, as the final result is a distinct list that is LIMITED afterwards
    	//if ($limit) $q->limit($limit);
    	
    	//echo $q->getSqlQuery(), "<br/><br/><br/>";
		//print_r($q->getParams());
		//echo "<br/><br/><br/>";
		//die();
		
		$locs1 = $q->execute();
		
		// change from doctrine collection to an array
		$locs = array();
		foreach ($locs1 as $loc) {
			$locs[] = $loc;
		}
		
		usort($locs, "cmpLocations");		
		//print_r($locs);
		
		return $locs;
		
    }
    
    
    
    
    public static function getBy1($underCtgId = null, $fromDate=null, $toDate=null, $limit=100){

    	
    	$q = Doctrine::getTable('Location')->createQuery('l');
    	$q->innerJoin("l.Address a ON l.id = a.id");
    	$q->innerJoin("a.Event e ON a.id = e.address_id");
    	$q->innerJoin("e.Cal cl");
    	$q->innerJoin("cl.Category c");
		    	
		$q->where('1=1');
		if ($fromDate) 	 $q->andWhere('e.starts_at >= :fromDate', array(':fromDate' => $fromDate));			
		if ($toDate) 	 $q->andWhere('e.ends_at <= :toDate', array(':toDate' => $toDate));
    	if ($underCtgId) $q->andWhere("cl.category_ids_path like :inCtgId", array(':inCtgId' => "$underCtgId,%"));
    	
    	$q->andWhere('c.deleted_at  is null');
    	$q->andWhere('cl.deleted_at is null');
    	
    	$q->orderBy('l.name');
    	if ($limit) $q->limit($limit);
    	
    	die($q->getSqlQuery());
    	$objs = $q->execute();
		return $objs;
    }
    
    public static function getCountryOptions(){
    	return Doctrine::getTable('Location')->createQuery('l')
    	->select('l.id, l.country, l.name')
    	->where('l.type_id =3')
    	->groupBy('l.country')
    	->orderBy('l.country')->execute();
    }

}