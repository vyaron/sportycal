<?php

/**
 * category actions.
 *
 * @package    evento
 * @subpackage category
 * @author     Yaron Biton
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class categoryActions extends sfActions
{
 	public function preExecute() {
  		Utils::redirectToMobileVersionIfNeeded($this);
 	}
	
 	private function restrictAccessAllowPartners($category) {
  		if (!UserUtils::userISMasterOf($category)) 
  		$this->redirect("main/index");
 	}
 	
  private function restrictAccess() {
  	$user = UserUtils::getLoggedIn();
    if (!$user || !$user->isMaster()) {
      $this->redirect("main/index");
    }
  }    

  public function executeShow(sfWebRequest $request)
  {
 	$this->fromFbApp = UserUtils::getFromFbApp();
  	if ($this->fromFbApp) $this->setLayout("fbapp");

    $ctgId = $request->getParameter('id');
    $this->forward404Unless($ctgId);


    $this->category = Doctrine::getTable('Category')->find(array($ctgId));
    $this->forward404Unless($this->category);
	
    //$this->user = UserUtils::getLoggedIn();
    $isMasterOf = UserUtils::userISMasterOf($this->category);
    $partnerIdMaster = UserUtils::getPartnerIdMaster();
    
    
    //Utils::pp($this->category);
    
    // TODO: find only ctgs, subctgs by permissions
    
    
    $this->subCtgs      = CategoryTable::getCategories($ctgId);
    $this->subCtgsCount = count($this->subCtgs);
	//Utils::pp($this->subCtgs);
    //Utils::pp($this->subCtgsCount);
    
    
	// Temporary solution for Toto-Winner (2 places)
    $showPastCals = true;
    if ($ctgId == 2100) $showPastCals = false;
    
    $this->cals         = CalTable::getCals($ctgId, null, null, $showPastCals);
    $this->cals			= $this->cals->getData();
    //Utils::pp($this->cals);
    $this->calsCount    = count($this->cals);

    
    // for now, we dont use the ctg links
    if ($isMasterOf) {
      //$this->ctgLinks = CategoryLinkTable::getByCategoryIds(array($ctgId));
		$this->ctgLinks = $this->category->getLinks();      
    }

    // Yaron: changes the condition 
    // !$isMasterOf && 
    // per David request
    if ((!$partnerIdMaster) && $this->subCtgsCount == 0 && $this->calsCount == 1) {
      $this->redirect($this->cals[0]->getUrl());
    }
    
    
    
    //Utils::pp($this->category->getParentId());
    // If there is only one calendar - no need for aggregation (this is for MASTER only as we dont show a single cal but resirect to cal/show in such case)
    // Also we dont offer this to a main category
    // changed this: $this->calsCount != 1 to: $this->calsCount > 1
    
    $this->aggCal = null;
    
    // THIS FIRST PART IS TEMPORARY: $ctgId != 2100 &&
    //if ($ctgId != 2100 &&  $this->category->getCalsCount() > 1 && $this->subCtgsCount != 1 && ($ctgId == 2100 || $this->category->getParentId())) {
    
    $getAggregatedCal = false;

    if ($this->category->getCalsCount() > 1 
    	&& ($this->subCtgsCount != 1 || $isMasterOf) 
    	&& ($ctgId == 2100 || $this->category->getParentId())) {

 		$aggCal = Cal::getAggregatedCal($this->category, $this->cals, true);
		
 		//$haveFutureEvents = $aggCal->haveFutureEvents();
 		//if ($isMasterOf || $haveFutureEvents){
 			$this->aggCal = $aggCal;
			$this->cals = array_merge(array($this->aggCal), $this->cals);
			$this->calsCount++;
 		//}
	}
	
    //Mobile
    Utils::useMobileViewIfNeeded($this, "ctgShow");
	
    if (IS_MOBILE){
    	//Get back btn params
	    $parentCtg = $this->category->getParentCategory();
		$this->backRow = Utils::prepareBackButton($this->category, $parentCtg);	
    }
  }

  public function executeNew(sfWebRequest $request)
  {
	$this->forward404Unless($this->parentCategory = Doctrine::getTable('Category')->find(array($request->getParameter('id'))), sprintf('Object category does not exist (%s).', $request->getParameter('id')));
	//Utils::pp($this->parentCategory);
  	$this->restrictAccessAllowPartners($this->parentCategory);   

  	 $this->form = new CategoryForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->form = new CategoryForm();
  	
  	$params = $request->getParameter($this->form->getName());
  	$this->forward404Unless($this->parentCategory = Doctrine::getTable('Category')->find(array($params['parent_id'])), sprintf('Object category does not exist (%s).', $params['parent_id']));
	
  	$this->restrictAccessAllowPartners($this->parentCategory);   
    $this->forward404Unless($request->isMethod(sfRequest::POST));


    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($category = Doctrine::getTable('Category')->find(array($request->getParameter('id'))), sprintf('Object category does not exist (%s).', $request->getParameter('id')));
  	$this->restrictAccessAllowPartners($category);   
    $this->form = new CategoryForm($category);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($category = Doctrine::getTable('Category')->find(array($request->getParameter('id'))), sprintf('Object category does not exist (%s).', $request->getParameter('id')));
  	$this->restrictAccessAllowPartners($category);
   
    $this->form = new CategoryForm($category);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    //$request->checkCSRFProtection();
	
    $category = Doctrine::getTable('Category')->find(array($request->getParameter('id')));
  	$this->restrictAccessAllowPartners($category);
	$this->forward404Unless($category);
    
  	$category->logicDelete();

  	$format = $request->getParameter('f');
    if ($format === SportyCalAPI::RESPONSE_FORMAT_JSON) {
  		$res = new stdClass();
    	$res->status = 1;
    	echo json_encode($res);
    	return sfView::NONE;
    }
    
    $ctgParentId = $category->getParentId();
    if ($ctgParentId) {
    	$this->redirect('category/show?id=' . $ctgParentId);
    } else {
    	$this->redirect('main/index');
    }
  }
  
  public function executeRevive(sfWebRequest $request)
  {
  	//$request->checkCSRFProtection();
  	$category = Doctrine::getTable('Category')->find(array($request->getParameter('id')));
  	$this->restrictAccessAllowPartners($category);
  	$this->forward404Unless($category);

  	$category->logicRevive();
  	

  	$format = $request->getParameter('f');
  	if ($format === SportyCalAPI::RESPONSE_FORMAT_JSON) {
  		$res = new stdClass();
  		$res->status = 1;
  		echo json_encode($res);
  		return sfView::NONE;
  	}
	
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $category = $form->save();

      // Need a double save here, as the ctg-path should include the ctg id
	  $parentCtg = $category->getParentCategory();
	  $ctgPath = $parentCtg->getCategoryIdsPath() . "," . $category->getId();
	  
	  $category->setCategoryIdsPath($ctgPath);
	  $category->setPartnerId($parentCtg->getPartnerId());
	  $category->setIsPublic($parentCtg->getIsPublic());
	  
	  $category->save();
      
      
      $this->redirect('category/show?id='.$category->getId());
    }
  }

  public function executeAddLink(sfWebRequest $request)
  {
    $this->restrictAccess();
    $ctgId      = $request->getParameter('ctgId');
    $txt        = $request->getParameter('txt');
    $type       = $request->getParameter('type');
    $url        = $request->getParameter('url');
    $targetUrl  = $request->getParameter('targetUrl');
    
    if ($ctgId && $txt && $type && $url && $targetUrl) {
      $ctgLink = new CategoryLink();
      $ctgLink->setCategoryId($ctgId);
      $ctgLink->setTxt($txt);
      $ctgLink->setUrl($url);
      $ctgLink->setTargetUrl($targetUrl);
      $ctgLink->setType($type);
      $ctgLink->save();
    }
    
    $this->redirect('category/show?id=' . $ctgId);
  }

  public function executeFind(sfWebRequest $request)  {
  	
  	$starts 		= SportyCalAPI::getStartTime($request);
  	$ends 			= SportyCalAPI::getEndTime($request, $starts);
  	$locId 			= SportyCalAPI::getLocationId($request);
  	$partner 		= SportyCalAPI::getValidPartner($request);
  	$format 		= SportyCalAPI::getFormat($request, $partnerId);
  	//$limit 			= SportyCalAPI::getLimit($request);
  	
  	if (!$partner) return sfView::NONE;

  	
  	$ctgsWithEventsAtRange = CategoryTable::getCtgsBy(null, $starts, $ends, $locId);
  	
  	//echo "<br/>**************** Found " . $ctgsWithEventsAtRange->count() . "Catgoroeis with Events<br/>";
//  	foreach ($ctgsWithEventsAtRange as $ctgWithEventsAtRange) {
//  		echo $ctgWithEventsAtRange->getName() . " (" . $ctgWithEventsAtRange->getId() . ") rootId: " .  $ctgWithEventsAtRange->getRootCategoryId();
//  	}
	  	
  	//var_dump($ctgsWithEventsAtRange);
  	//die("<br/>day");
  	$rootCtgs = CategoryTable::getRootCategoriesOf($ctgsWithEventsAtRange);
  	
  	//echo "<br/>**************** Found " . count($rootCtgs) . "Root Catgoroeis with Events<br/>";
  	
    $jsonCtgs = Category::makeJSON($rootCtgs);  

    
    if ($format == SportyCalAPI::RESPONSE_FORMAT_JS) {
    	$jsonCtgs = addslashes($jsonCtgs);
    	$this->jsonCtgs = $jsonCtgs;
    	$response = $this->getResponse()->setContentType('text/JavaScript');
  	}  else {
  		echo $jsonCtgs;
  		return sfView::NONE;  		
  	}  
  }
  
  public function executeGetSubCtgs(sfWebRequest $request)  {
  	
  	$partner 			= SportyCalAPI::getValidPartner($request);
  	if (!$partner) 		die("Invalid Partner");
  	  	
  	$parentCtgId		= SportyCalAPI::getParentCtgId($request, $partner->getId());
  	if (!$parentCtgId) die("Invalid Parent Category");
  	//return sfView::NONE;

  	
  	$ctgs = CategoryTable::getCategories($parentCtgId);
    $jsonCtgs = Category::makeJSON($ctgs);  
    
  	echo $jsonCtgs;
  	return sfView::NONE;  		
  }
  
 public function executeApi(sfWebRequest $request)  {
 	$partner 			= SportyCalAPI::getValidPartner($request);
  	if (!$partner) die("Invalid Partner");

  	$ctgId = (int) $request->getParameter('ctgId');
  	$format = SportyCalAPI::getFormat($request, $partner);
  	$min = $request->getParameter('min');
  	//$fromDate = $request->getParameter('starts');
  	//$toDate = $request->getParameter('ends');
	
  	$res = null;
  	if ($ctgId) {
  		$res = new stdClass();
  		
  		//Get Category
  		$category = Doctrine::getTable('Category')->find(array($ctgId));
  		if ($category){
  			
  			$res->category = $category->getFlatObj($min);
  			
  			$rootCtgImgUrl = '';
  			if (!$min){
  				$rootCtgImgUrl = GeneralUtils::DOMAIN . $category->getRootCategory()->getImagePath();
  				$res->category->img_url = $rootCtgImgUrl;
  			}
  			
  			//Get Sub Categories
  			$res->subCategories = array();

  			$subCtgs = CategoryTable::getCategories($ctgId);

  			//$subCtgs = CategoryTable::getCtgsBy('', $fromDate, $toDate, null, null, $ctgId);
  			foreach ($subCtgs as $subCtg) {
  				$flatSubCtg = $subCtg->getFlatObj($min);
  				if (!$min) $flatSubCtg->img_url = $rootCtgImgUrl;
  				
  				//get category width cals;
  				if ($min && $flatSubCtg->cals_count == 0) continue;
  				
  				$res->subCategories[] = $flatSubCtg;
  			}
  			
  			//Get Cals
  			$showPastCals = true;
    		if ($ctgId == 2100) $showPastCals = false;
  			$cals = CalTable::getCals($ctgId, null, null, $showPastCals);

  			$cals = $cals->getData();
			
	  		if ($ctgId != 2100 &&  $category->getCalsCount() > 1 && count($subCtgs) != 1 && ($ctgId == 2100 || $category->getParentId())) {
	  			$aggCal = Cal::getAggregatedCal($category);
				$cals = array_merge(array($aggCal), $cals);
			}
  						
  						
			//Get Cals Events
			$res->cals = array();
  			foreach ($cals as $cal){
  				$res->cals[] = $cal->getFlatObj(!$min, $rootCtgImgUrl, $partner, $min);
  			}

  		}
  	} else {
  		//Get Root Categories
  		$res = array();
  		$ctgs = CategoryTable::getCategories();
  		foreach ($ctgs as $ctg) {
  			$res[] = $ctg->getFlatObj();
  		}
  	}
	
  	$this->getResponse()->setHttpHeader('Cache-Control', 'no-cache, must-revalidate');
	$this->getResponse()->setHttpHeader('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT');
  	
	$jsonStr = json_encode($res);
	
  	if ($format == SportyCalAPI::RESPONSE_FORMAT_JS) {
  		$response = $this->getResponse()->setContentType('text/JavaScript');
    	$res = SportyCalAPI::CALL_BACK_FUNCION . '(' . $jsonStr . ');';
  	}  else {
  		$this->getResponse()->setHttpHeader('Content-type', 'application/json');
  		$res = $jsonStr;		
  	}

  	echo $res;
  	return sfView::NONE;
  }
}
