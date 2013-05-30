<?php
require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'prod', false);

sfContext::createInstance($configuration);

function add($filename){
	//Utils::pa($filename);
	$xml = simplexml_load_file($filename);
	if ($xml){
		foreach ($xml->Row as $row){
			$ctgIds = explode(',', $row->ctg_ids->__toString());
			$ctgs = CategoryTable::getByIds($ctgIds);
			
			if ($ctgs){
				$txt = $row->txt->__toString();
				$targetUrl = $row->target_url->__toString();
				$url = $row->url->__toString();
				
				if (empty($txt) || empty($url)) continue;
				foreach ($ctgs as $ctg) {
					//Delete old ctgLinks
					$oldCtgLink = CategoryLinkTable::getBy($ctg->getId(), $url);
					$oldCtgLink->delete();
					
					$ctgLink = new CategoryLink();
					$ctgLink->setCategory($ctg);
					$ctgLink->setTxt($txt);
					$ctgLink->setUrl($url);
					$ctgLink->setTargetUrl($targetUrl);
					$ctgLink->setType("website");
					$ctgLink->save();
					
				}
			}
		}
	}
}

if (isset($argv[1]) && file_exists($argv[1])){
	add($argv[1]);
}
?>