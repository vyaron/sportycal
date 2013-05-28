<?php


class SportyCalAPI {

	const RESPONSE_FORMAT_JSON 	= 'JSON';
	const RESPONSE_FORMAT_JS	 = 'JS';
	
	const CALL_BACK_FUNCION	 = 'scCallBack';
	
	public static function getTags(sfWebRequest $request){
		$tags = null;
		
		$label = $request->getParameter('label');
		if (!is_null($label)) {
			$label = base64_decode($label);
			$tags = json_decode($label, true);
		}

		return $tags;
	}
	
    public static function getStartTime(sfWebRequest $request) {
  		$starts 		= $request->getParameter('starts');
	  	$timeStarts = strtotime($starts);
	  	if (!$timeStarts) {
	  		$timeStarts = strtotime('now');	
	  		$starts = date('Y-m-d', $timeStarts);
	  	}
		return $starts;  		
    }

    public static function getEndTime(sfWebRequest $request, $starts) {
		$ends 			= $request->getParameter('ends');
  		$timeEnds 		= strtotime($ends);
  		$timeStarts 	= strtotime($starts);	
	  	
  		if (false && (!$ends || $timeEnds < $timeStarts)) {
	  		$timeEnds = strtotime("+2 week");
	  		$ends = date('Y-m-d', $timeEnds);	
	  	}   
    	return $ends;  		
    }
    
    public static function getValidPartner(sfWebRequest $request=null,$ref=null) {
    	
    	if ($request)		$ref = $request->getParameter('ref');
		if (!$ref) return null;    	
    	
		$partnerHash = $ref;
		
		//if (!$partnerHash) return null;
		if ($partnerHash == 'YOUR-PARTNER-ID') $partnerHash = 'sportYcal';
		//Utils::pp($partnerHash);
		$partner 		= PartnerTable::getPartner($partnerHash);
		//Utils::pp($partner);
		//if ($partnerId == "gpi" || $partnerId == "andru") return $partnerId;
    	
    	return $partner;
    }
    
    public static function getCalId(sfWebRequest $request){
    	$calId = $request->getParameter('calId');
    	return $calId;
    }

	public static function getCtgId(sfWebRequest $request){
    	$ctgId = $request->getParameter('ctgId');
    	return $ctgId;
    }
    
    public static function getCtgIds(sfWebRequest $request) {
		$underCtgIds 	= $request->getParameter('ctgIds');
		
		if ($underCtgIds) {
			$underCtgIds = explode(",", $underCtgIds);
		}
		
    	return $underCtgIds;
    }
    
    public static function getParentCtgId(sfWebRequest $request, $partnerId) {
		$parentCtgId 	= $request->getParameter('parentCtgId');
		if (!$parentCtgId) {
			$parentCtgAlias 	= $request->getParameter('parentCtgAlias');
			if ($parentCtgAlias) {
				$alias = AliasTable::getAlias($parentCtgAlias, $partnerId);
				if ($alias) $parentCtgId = $alias->getCategoryId();
				else {
					$ctg = CategoryTable::getCategories("ALL", $parentCtgAlias, null, true);
					if ($ctg) $parentCtgId = $ctg->getId();
				}
			}
		}
    	return $parentCtgId;
    }
    
    public static function getFormat(sfWebRequest $request, $partner) {
    	
		$format 	= $request->getParameter('f');

		if ($format != self::RESPONSE_FORMAT_JS && $format != self::RESPONSE_FORMAT_JSON) {
			if ($partner && $partner->getHash() == "GoPlanIt-4217") 	$format = self::RESPONSE_FORMAT_JS;
			else $format = self::RESPONSE_FORMAT_JSON;
		}
		
    	return $format;
    }

    public static function getLocationId(sfWebRequest $request) {
		$locId 	= $request->getParameter('locId');
    	return $locId;
    }
    
    public static function getLimit(sfWebRequest $request) {
		$limit 	= $request->getParameter('limit');
		//die(is_numeric($limit));
		if (!$limit || !is_numeric($limit)) $limit = 50;
		if ($limit > 100) $limit = 100;
		 
    	return $limit;
    }

    public static function getCountryCode(sfWebRequest $request) {
		$countryCode 	= $request->getParameter('countryCode');
		$countryCode 	= strtoupper($countryCode);
    	return $countryCode;
    }
    
    public static function getStateCode(sfWebRequest $request) {
		$stateCode 	= $request->getParameter('stateCode');
    	return $stateCode;
    }
    
    
    public static function getLocationType(sfWebRequest $request, $countryCode) {
    	
    	$returnLocType = Location::TYPE_CITY;
    	
		$locType 	= $request->getParameter('locType');
		
		if ($locType == Location::TYPE_COUNTRY) 	$returnLocType = Location::TYPE_COUNTRY; 
		else if ($locType == Location::TYPE_STATE) 		{
			if ($countryCode && Utils::$STATES[$countryCode]) {
				$returnLocType = Location::TYPE_STATE;
			}
		}
		
    	return $returnLocType;
    }
    

}




?>