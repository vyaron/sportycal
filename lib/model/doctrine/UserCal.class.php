<?php

/**
 * UserCal
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    evento
 * @subpackage model
 * @author     Yaron Biton
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class UserCal extends BaseUserCal
{

   public function getAsCSV() {
        $userCal    = $this;
        $d          = $this->getTakenAt();
        $dt         = new DateTime($d);

        $cal 					 = $userCal->getCal();
        if (!$cal->getId()) $cal = null;
        if ($cal)	{
        	$ctg = $userCal->getCal()->getCategory();
        	$name = $cal->getName();	
        } else {
        	$ctg = $userCal->getCategory();
        	$name = $ctg->getName() . __(Cal::AGG_EXT);
        } 				
        
        
        
        $str = sprintf('%s, %s, %s, %s, %s, %s',
                                        $userCal->getId(),
                                        $userCal->getCalType(),
                                        $dt->format('Y-m-d'),
                                        $name,
                                        $userCal->getUserId(),
                                        $ctg->getCategoryPathAsText()
                                        );
        
        return $str;

    }
 
	public function getIcalUrl($fileName = 'calendar'){
		//TODO: support BC
		
		$id = $this->getId();
		$fileName = Utils::slugify($fileName);
		
		$url = sfConfig::get('app_domain_full') . "/cal/get/h/$id/$fileName.ics";
		
		if ($this->getCalType() == Cal::TYPE_GOOGLE || ($this->getCalType() == Cal::TYPE_MOBILE && Utils::clientIsAndroid())) $url = Cal::GOOGLE_IMPORT_URL .  urlencode($url);
		else if ($this->getCalType() == Cal::TYPE_OUTLOOK || $this->getCalType() == Cal::TYPE_MOBILE) $url = str_replace('http://', 'webcal://', $url);

		return $url;
	}
}