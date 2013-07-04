<?php

/**
 * main actions.
 *
 * @package    evento
 * @subpackage main
 * @author     Yaron Biton
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class mainActions extends sfActions
{
 public function preExecute() {
  	Utils::redirectToMobileVersionIfNeeded($this);
 }
/* 
  public function executeFakeLogin(sfWebRequest $request)
  {
    $user = new User();
    $user->setId(2);
    $user->setFullName('FAKE');
    $user->setType('MASTER');
    // Create the login session
    UserUtils::logUserIn($user);
    $this->redirect('main/index');
  }
  */
 
 public function redirectByHost($host){
 	if (sfConfig::get('app_domain_isNeverMiss')){
 		$this->redirect('/nm/index');
 	}
 }
 
	/**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
  	$this->redirectByHost($request->getHost());
  	
	$this->fromFbApp = UserUtils::getFromFbApp();
  	if ($this->fromFbApp) $this->setLayout("fbapp");

  	$src = $request->getParameter("src");
  	if ($src) Intel::reportSrc("main", $src);
  	
  	$ctgs = CategoryTable::getCategories();
  	
  	$this->categories 		= array();
  	$this->sponsCategories 	= array();
  	
  	CategoryTable::divideSponsored($ctgs, $this->categories, $this->sponsCategories);
  	
    $this->categoriesCount 		= count($this->categories);
    $this->sponsCategoriesCount = count($this->sponsCategories);
    
    $this->user = UserUtils::getLoggedIn();

    $this->txtSearch = "";
    $this->fromDate = '';
    $this->toDate = '';
    
    //Mobile
  	$fullSite = $this->getRequestParameter('fullSite');
  	if ($fullSite){
  		$this->getUser()->setAttribute('fullSite', true);
  	} elseif ($fullSite === '0') {
  		$this->getUser()->getAttributeHolder()->remove('fullSite');
  	}
  	
  	Utils::useMobileViewIfNeeded($this, "index");
  }

  public function executeFbLogin(sfWebRequest $request)
  {
  	$isAjax = $this->getRequest()->isXmlHttpRequest();
  	$isAjax = true;
    $fbLoginSuccess = false;
    //$fbCookie = FacebookUtils::getCookie(FACEBOOK_APP_ID, FACEBOOK_SECRET);
	
    $fbUser = FacebookUtils::getUser();
	
    if ($fbUser) {
        $user = $this->handleFBUser($fbUser);
        
		//Utils::pp($user);
        if ($user) {
          // Create the login session
          UserUtils::logUserIn($user);
		  
          //Utils::pp($user);
    	  self::setFbFriends();
          
          $fbLoginSuccess = true;
        }
    }
    if (!$fbLoginSuccess) {
      // Authentication failed
      //$this->getRequest()->setError(null, 'Could not connect you using Facebook Connect');
    }
    
    if ($isAjax){
    	if ($fbLoginSuccess){
    		$res = array('success' => true, 'msg' => __("Facebook log in success"), 'html' => $this->getPartial('global/topNav', array('user' => $user)));
    	} else {
    		$res = array('success' => false,'msg' => __("Facebook log in field"));
    	}
    	
    	echo json_encode($res);
    	return sfView::NONE;
    } else {
    	$getUserId = $request->getParameter('getUserId');
    	
    	if ($getUserId){
    		if (isset($user)) return $this->renderText('' . $user->getId());
    		else return $this->renderText('0');
    	}
    	
    	$gotoPage = $request->getParameter('gt');
    	if ($gotoPage) $this->redirect($gotoPage);
    	
    	//Redirect to referer
    	if (sfConfig::get('app_domain_isNeverMiss')){
    		$refererUrl = UserUtils::getRefererUrl();
    		if ($refererUrl) {
    			UserUtils::setRefererUrl(null);
    			$this->redirect($refererUrl);
    		}
    	}
    	
    	//die("Done");
    	if (sfConfig::get('app_domain_isNeverMiss')) $this->redirect('/nm/calList');
    	else $this->redirect('main/index');
    }
  }


  private function handleFBUser($fbUser)  {

  	//Utils::pp($fbUser);
    //echo "Handling FB User <br/>";
    //echo $fbUser->id;
    //die("fb done");
    // Is this FB user exist in our DB?
    $mysqldate = date( 'Y-m-d H:i:s', time() );        
	
    $user = UserTable::getByFBCode($fbUser->id);
    
    if (!$user) {
      // CONGRUTS! THIS IS A SIGNUP!	
      //echo "New User, adding to DB<br/>";
      
	  $refUserId = UserUtils::getRefUserId();
	  if ($refUserId) UserTable::creditToUser($refUserId, UserTable::CREDIT_REF_SIGNUP);
	  
    	
      $user = new User();
      $user->setFbCode($fbUser->id);
      $user->setEmail($fbUser->email);
      $user->setFullName($fbUser->first_name . " " . $fbUser->last_name);
      $user->setBirthdate(date('Y-m-d', strtotime($fbUser->birthday)));
      $user->setLastLoginDate($mysqldate);
      $user->setBalance(UserTable::CREDIT_FOR_SIGNUP);
      $user->setRefUserId($refUserId);
      $user->setCreatedAt($mysqldate);
      $user->setUpdatedAt($mysqldate);
     
      $user->save();
    } else {
      	$user->setBirthdate(date('Y-m-d', strtotime($fbUser->birthday)));
      	$user->setLastLoginDate($mysqldate);
	    $user->save();

    }

    return $user;
    
  }

  public function executeLogout(sfWebRequest $request) {
    UserUtils::logUserOut();
    $this->redirect('main/index');    
  }
  
  public function executeLogin(sfWebRequest $request) {
    //mobile
    Utils::useMobileViewIfNeeded($this, "login");    
  }

  public function executeContact(sfWebRequest $request) {
    $this->sent = $request->getParameter('sent');
    $this->user = UserUtils::getLoggedIn();
    $this->toki = UserUtils::putSecurityToken();
  }

  public function executeSendContact(sfWebRequest $request) {

    $user = UserUtils::getLoggedIn();
    $ip = Utils::getClientIP();
    
    //$prevToki = UserUtils::getSecurityToken();
    //Utils::pp("$prevToki");
    if (!UserUtils::isValidSecurityToken($request->getParameter('toki'))) {
    	//echo "toki is wrong! Prev: $prevToki ";
    	$res = new stdClass();
    	$res->status = true;
    	echo json_encode($res);

    	return sfView::NONE;
    }
    
    if ($user) {
    	$fromName = $user->getFullName();
    	$fromEmail = $user->getEmail();
    } else {
    	$fromName = $request->getParameter('name');
    	$fromEmail = $request->getParameter('email');

//     	if (Utils::isIPBlackListed($ip)) {

//     		$msg = "REQUEST ";
//     		$msg .= implode(" | ", $_REQUEST);
//     		$msg .= "SERVER";
//     		$msg .= implode(" | ", $_SERVER);

//     		//GeneralUtils::mailAdmins($fromName, $fromEmail, $msg, "Black listed Spam detected");

//     		$res = new stdClass();
//     		$res->status = true;
//     		echo json_encode($res);
    		
//     		return sfView::NONE;
//     	}
    }
	
    $res = new stdClass();
    $msg = $request->getParameter('msg');
    
    if ($msg && ($user || (!$user && UserUtils::isValidCaptcha($request->getParameter('captcha'))))) {
    	$rating = $request->getParameter('feedbackRating');
    	if ($rating) $msg = ' rating:' . $rating . ' - ' . $msg;
    	
    	$msg = "We got feedback from: $fromName (email: $fromEmail) :  $msg";
    	$sub = "sportYcal - New Contact";
    	
    	$contact = new Contact();
    	
    	$contact->setCreatedAt(date('Y-m-d h:i:s'));
    	$contact->setSenderName($fromName);
    	$contact->setSenderEmail($fromEmail);
    	$contact->setIpAddress($ip);
    	$contact->setMessage($msg);
    	$contact->setSubject($sub);
    	
    	if ($user) $contact->setByUserId($user->getId());
    	
    	$contact->save();
    	
		GeneralUtils::mailAdmins($fromName, $fromEmail, $msg, $sub);
		
		UserUtils::removeCaptcha();
		
		$res->status = true;
    } else {
    	$capthaPath = '';
    	
    	if (!$user){
    		UserUtils::generateCaptcha();
    		$capthaPath = UserUtils::getCaptchaImgPath();
    	}
    	
    	$res->status = false;
    	$res->captchaImgPath = $capthaPath;
    }
    
	echo json_encode($res);
    return sfView::NONE;
  }  
  
  public function executeGetCaptchaImgPath(sfWebRequest $request) {
  	$res = new stdClass();
  	
  	$res->status = true;
  	$res->captchaImgPath = UserUtils::getCaptchaImgPath();
  	
  	echo json_encode($res);
  	
  	return sfView::NONE;
  }


  public function executeAbout(sfWebRequest $request) {
  }
  public function executeTerms(sfWebRequest $request) {
  }



  public function executeSearch(sfWebRequest $request) {
      $txtSearch 	= $request->getParameter('txtSearch');
      $fromDate 	= $request->getParameter('fromDate');
      $toDate 		= $request->getParameter('toDate');
      
      //$fromDate 	= '2011-05-21';
      //$toDate 		= '2011-05-28';
      
      $this->txtSearch = '';
      $this->fromDate = '';
      $this->toDate = '';
      
      $this->cals = array();
      $this->categorys = array();
      $toDateDB = '';
      $fromDateDB = '';
      
      $events = array();
      $ctgs = array();
      if (!empty($txtSearch) || !empty($fromDate) || !empty($toDate)) {
      	
      	if ($fromDate) {
      		$fromDateDB = date( 'Y-m-d', 	$fromDate );
      		$this->fromDate 	= $fromDate;	
      	}  
      	if ($toDate) {
      		$toDateDB = date( 'Y-m-d', 	$toDate );	
			$this->toDate 		= $toDate;
      	}
      	
        // Yaron, not sure why, but this returns empty string at PRODUCTION
        //$txtSearch = mysql_real_escape_string($txtSearch);

        // Save this search
        $ip = Utils::getClientIP();
        $search = new UserSearch();
        $search->setUserId(UserUtils::getLoggedInId());
        $search->setStr($txtSearch);
        $mysqldate = date( 'Y-m-d H:i:s', time() );        
        $search->setCreatedAt($mysqldate);
        if ($fromDateDB) $search->setFromDate($fromDateDB);
        if ($toDateDB) $search->setToDate($toDateDB);
        
        $search->setIpAddress($ip);
        $search->save();
		
        if ($fromDateDB && $toDateDB) {
        	$events = EventTable::getBy($txtSearch, null, $fromDateDB, $toDateDB, null, null, null, false, null);
        	//Utils::pp($events);
        } else {
        	$ctgs = CategoryTable::getCtgsBy($txtSearch, $fromDateDB, $toDateDB);
        	//Utils::pp($ctgs);
        }
        $this->txtSearch 	= $txtSearch;
      }
      
      
      $this->events = $events;
      $this->categorys = $ctgs;
      
      
      //mobile
      Utils::useMobileViewIfNeeded($this, "search");
  }
  
  public function executeSearchCtgs(sfWebRequest $request){
  	//TODO get AJAX + Dates, 10, 3 char
  	$txtSearch 		= $request->getParameter('txtSearch');
  	$fromDate 		= $request->getParameter('fromDate');
  	$toDate 		= $request->getParameter('toDate');
  	
  	if ($fromDate) {
  		$fromDateDB = date( 'Y-m-d', $fromDate);
  	}
  	if ($toDate) {
  		$toDateDB = date( 'Y-m-d', $toDate);
  	}

  	$categorys = CategoryTable::getCtgsBy($txtSearch, $fromDateDB, $toDateDB, null, 10);
  	
  	$ctgs = array();
  	foreach ($categorys as $category){
  		$ctg = new stdClass();
  		$ctg->name = $category->getName();
  		$ctg->id = $category->getId();
  		
  		$ctgs[] = $ctg;
  	}
  	
  	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Content-type: application/json');
  	
  	echo json_encode($ctgs);
  	return sfView::NONE;
  }
  
  public function executeSearchCals(sfWebRequest $request){
  	$txtSearch = $request->getParameter('txtSearch');

  	$calendars = Doctrine::getTable('Cal')
    	->createQuery('c')
    	->where('c.name LIKE "%' . $txtSearch . '%"')
    	->andWhere('c.is_public = 1')
    	->andWhere('c.deleted_at IS NULL')
    	->limit(10)
   		->execute();

   	$cals = array();
  	foreach ($calendars as $calendar){
  		$cal = new stdClass();
  		$cal->name = $calendar->getName();
  		$cal->id = $calendar->getId();
  		
  		$cals[] = $cal;
  	}
  	
  	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Content-type: application/json');
  	
  	echo json_encode($cals);
  	return sfView::NONE;
  }

  public function executeSaveTZ(sfWebRequest $request) {
    $tz = $request->getPostParameter('tz');
    
    $tzStr = null;
    if (isset($tz)) $tzStr = GeneralUtils::getTZFromJSTZ($tz);
    
    $res = new stdClass();
    if ($tzStr && $tzStr != UserUtils::getUserTZ()){
    	UserUtils::setUserTZ($tzStr);
    	UserUtils::setUserTzValue($tz);
    	UserUtils::setUserFullNameTZ(GeneralUtils::getUTCStrFromJSTZ($tz) . ' ' . $tzStr);
    	
    	$res->status = true;
    	$res->msg = 'change to ' . $tzStr;
    } else if ($tzStr) {
    	$res->status = false;
    	$res->msg = 'current timezone ' . $tzStr;
    } else {
    	$res->status = false;
    	$res->msg = $tz . ' Not GMT hours';
    }
    
	echo json_encode($res);
    return sfView::NONE;
  }

  public function executePromo(sfWebRequest $request){
  	$src = $request->getParameter("src");
  	if ($src) Intel::reportSrc("promo", $src);
  }

  public function executePromoTerms(sfWebRequest $request){
	IntelTable::insertNew("promo", "terms", "", 0);
  }  
  
  public function executeFifpro(sfWebRequest $request){
  	$this->redirect("cal/show?id=432");
  }
  
  public function executeFriendsBirthday(sfWebRequest $request){
  	$fbFriends 			= $request->getParameter('fbFriends');
  	$cFriends 			= $request->getParameter('cFriends');
  	
  	$clear				= $request->getParameter('clear');

  	$currFriends 		= $request->getParameter('currFriends');
  	
  	$src = $request->getParameter("src");
  	if ($src) Intel::reportSrc("birthdayCal", $src);
  	
  	
  	$loggedinUserId = UserUtils::getLoggedInId();

  	if ($loggedinUserId){
  		if ($clear){
  			
  			//delete custom friends
	  		$q = Doctrine_Query::create()
	  		->delete()
	  		->from('UserBirthday ub')
	  		->where('ub.user_id=?', $loggedinUserId)
	  		->execute();
			
	  		//unchecked fb friends
	  		$q = Doctrine_Query::create()->update('UserFbUser')
			->set('in_birthday_cal', 0)
			->whereIn('user_id', $loggedinUserId)
			->execute();
  		}
  		
  		//set custom friends birthday
	  	if ($cFriends){
	  		$cFriends = json_decode($cFriends);
	  		
	  		$collectionUserBirthday = new Doctrine_Collection('UserBirthday');
	  		foreach ($cFriends as $cFriend){
	  			$userBirthday = new UserBirthday();
	  			$userBirthday->setUserId($loggedinUserId);
	  			$userBirthday->setFullName($cFriend->full_name);
	  			$userBirthday->setBirthdate($cFriend->birthdate);
	  			$userBirthday->setCreatedAt(date('Y-m-d h:i:s'));
	  			
	  			$collectionUserBirthday->add($userBirthday);
	  		}
	  		$collectionUserBirthday->save();
	  	}
	  	
	  	//set FB friends birthday
	  	if ($fbFriends){
	  		//add specific friends to birthday calendar
  			$fbFriends = explode(',', $fbFriends);
			
  			if ($fbFriends){
	  			$q = Doctrine::getTable('UserFbUser')->createQuery('ufbu');
				$q->innerJoin("ufbu.FbUser fbu");
			 	$q->where('ufbu.user_id=?', $loggedinUserId);
			 	$q->andWhereIn('fbu.fb_code', $fbFriends);
			 	
				$currUserFbUsers = $q->execute();  
				
				$currUserFbUsersToUpdate = array();
				foreach ($currUserFbUsers as $currUserFbUser){
				    $currUserFbUsersToUpdate[] = $currUserFbUser->getFbUserId();
				}
				
				if ($currUserFbUsersToUpdate){
					$q = Doctrine_Query::create()->update('UserFbUser');
					$q->set('in_birthday_cal', 1);
					$q->whereIn('fb_user_id', $currUserFbUsersToUpdate);
					$q->execute();
				}
  			}
	  	}
	  	
	  	//curr friends in cal
	  	if ($currFriends){
	  		//fb users
		  	$fbUsers = Cal::getBirthdayFbUsers($loggedinUserId);
		  	
		  	$currFriendsObj = new stdClass();
		  	
		  	$uids = array();
		  	foreach ($fbUsers as $fbUser){
		  		$uids[] = $fbUser->getFbCode();
		  	}
			
		  	
			//custom user birthday
			$userBirthdays = Doctrine::getTable('UserBirthday')->createQuery('ub')
			->where('ub.user_id=?', $loggedinUserId)
	  		->fetchArray();
			
		  	$currFriendsObj->userBirthdays = $userBirthdays;
		  	$currFriendsObj->fbUids = $uids;
		  	echo json_encode($currFriendsObj);
	  	}
	  	
	  	if ($fbFriends || $cFriends || $currFriends){
	  		return sfView::NONE;
	  	}
  	}
  	
  	$this->birthdayCtg = Category::getBirthdayCategory(null);
  	$this->birthdayCal = Cal::getBirthdayCal(null);
  	
  	Utils::useMobileViewIfNeeded($this, "friendsBirthday");
  }
  
  private function setFbFriends(){

  	$loggedinUser = UserUtils::getLoggedIn();
  	if (!$loggedinUser) return;
  	
  	$loggedinUserId = $loggedinUser->getId();
  	
  	
  	$fbFriends = FacebookUtils::getFriends($loggedinUser);
	if (!$fbFriends) return;
  	
  	$fbCodeTofbFriend = array();
  	$fbFriendFbCodes = array();
	foreach($fbFriends as $fbFriend){
		$fbFriendFbCodes[] = $fbFriend->uid;
		$fbCodeTofbFriend[$fbFriend->uid] = $fbFriend;
	}
	
	
	$q = Doctrine::getTable('FbUser')
	->createQuery('fbu')
	->whereIn('fb_code', $fbFriendFbCodes);
	$currFbUsers = $q->execute();

	$currFbCodes = array();
	//$collectionFbUserToUpdate = new Doctrine_Collection('FbUser');
	foreach ($currFbUsers as $currFbUser){
		$currFbCodes[] = $currFbUser->fb_code;
		
		
		if ($fbCodeTofbFriend[$currFbUser->fb_code] && $fbCodeTofbFriend[$currFbUser->fb_code]->birthday_date && !$currFbUser->getBirthdate()){
			$birthdate = $fbCodeTofbFriend[$currFbUser->fb_code]->birthday_date;
			
			$q = Doctrine_Query::create()->update('FbUser fu');
			$q->set('fu.birthdate', '?', $birthdate);
			$q->where('fu.fb_code = ?', $currFbUser->fb_code);

			$q->execute();
		}
	}
	//$collectionFbUserToUpdate->save();
	
	
  	//Set FbUser
  	$collectionFbUser = new Doctrine_Collection('FbUser');
  	foreach ($fbFriends as $fbFriend) {
  		if (in_array($fbFriend->uid, $currFbCodes)) continue;
  		//Utils::pa($fbFriend->uid);
  		$birthDate = is_string($fbFriend->birthday_date) ? $fbFriend->birthday_date : '';

  		$fbUser = new FbUser();
		$fbUser->setFullName( $fbFriend->name);
		$fbUser->setBirthdate($birthDate);
		$fbUser->setFbCode($fbFriend->uid);
		$fbUser->setCreatedAt(date('Y-m-d h:i:s'));
		
		$collectionFbUser->add($fbUser);

  	}
  	//Utils::pp($collectionFbUser);
  	$collectionFbUser->save();
  	
  	//Get current user fb_users
	$q = Doctrine::getTable('FbUser')->createQuery('fbu');
	$q->innerJoin("fbu.UserFbUser ufbu");
 	$q->where('ufbu.user_id=?', $loggedinUserId);
	$byUserCurrFbUsers = $q->execute();
	
	$allExistFbUserIds = $currFbUsers->getPrimaryKeys();
	$byUserCurrFbUsersUserIds = $byUserCurrFbUsers->getPrimaryKeys();
	
	//Get exist fb_users but not in current user_fb_user
	$fbUserIds = array_diff($allExistFbUserIds, $byUserCurrFbUsersUserIds);
	
	//Merge new fb_users
	$fbUserIds = array_merge($fbUserIds, $collectionFbUser->getPrimaryKeys());

	//Set UserFbUser
	$collectionUserFbUser = new Doctrine_Collection('UserFbUser');
	foreach ($fbUserIds as $fbUserId){
		$userFbUser = new UserFbUser();
		
		$userFbUser->setUserId($loggedinUserId);
		$userFbUser->setFbUserId($fbUserId);
		$userFbUser->setCreatedAt(date('Y-m-d h:i:s'));
		
		$collectionUserFbUser->add($userFbUser);
	}
  	$collectionUserFbUser->save();
  }
  

  public function executeDownCalTerms(sfWebRequest $request){
  	
  	
  }
  
  public function executeCalDownloadWidget(sfWebRequest $request){
  	$this->languages = array('en_US', 'he_IL');
  	
  	$ctgName 	= $request->getParameter('ctgName');
  	$this->ctgName = Utils::iff($ctgName, '');
  	
  	$language 	= $request->getParameter('language');
  	$this->language = Utils::iff($language, 'en_US');
	
  	$this->type = $request->getParameter('type');
  	$this->typeId = (int) $request->getParameter('typeId');
  	
  	if (!$this->typeId) {
  		$this->typeId = 732;
  		$this->type = 'cal';
  	}
	
  	$this->calId = null;
  	$this->ctgId = null;
  	if ($this->type == 'ctg') $this->ctgId = (int) $this->typeId;
  	else if ($this->type == 'cal') $this->calId = (int) $this->typeId;
  	
  	$ref 	= $request->getParameter('ref');
  	$this->ref = Utils::iff($ref, 'YOUR-PARTNER-ID');
	$this->inRef = $ref;
  	
  	$label 	= $request->getParameter('label');
  	$this->label = Utils::iff($label, '');
  	
  	$style = $request->getParameter('style');
	$this->style = Utils::iff($style, null);
  	
  	$color = $request->getParameter('color');
  	if (!$color) $color = 'default';
	$this->color = Utils::iff($color, null);
	
  	$this->colors = array('default', 'lightblue', 'green' , 'magenta', 'orange', 'purple', 'red', 'yellow');

  	$this->colorClass = '';
  	if (in_array($this->color, $this->colors)) $this->colorClass = "scdwBg_$color";


  	$this->showWidget = true;
  	if ((!$this->ctgId && !$this->calId) || ($this->ctgId && $this->calId)){
  		$this->showWidget = false;
  	}

  	define('DOWNLOAD_WIDGET_URL', sfConfig::get('app_domain_full') . '/downloadWidget/');
	//define('DOWNLOAD_WIDGET_URL', 'http://www.sportycal.com/downloadWidget/');

  	$params = '';
	if ($this->calId) $params .= 'calId=' 	. $this->calId;
	if ($this->ctgId) $params .= 'ctgId=' 	. $this->ctgId;
	if ($this->ref)   $params .= '&ref=' 	. $this->ref;
	if ($this->label) $params .= '&label='  . base64_encode($this->label);
	if ($this->language) 	$params .= '&language=' . $this->language;
	if ($this->style) 	$params .= '&style=' . $this->style;
	
	//for ifrme
	if ($this->color) 	$params .= '&color=' . $this->color;
	
	$elId = 'scDownloadWidget_' . $this->type . '_' . $this->typeId . '_' . $this->colorClass;
	$params .= '&elId=' . $elId;
	
	//$this->jsSrc = DOWNLOAD_WIDGET_URL . 'main.php?' . $params;
	$this->iframeSrc = DOWNLOAD_WIDGET_URL . 'iframe.php?' . $params;
	
	//$this->jsCode = '<div id="' . $elId . '" class="scDownloadWidgetWrapper ' . (($this->colorClass) ? $this->colorClass : '') . '"></div><script type="text/javascript" src="' . $this->jsSrc .'"></script>';
	$this->iframeCode = '<iframe id="' . $elId . 'Frame"  frameborder="0" border="0" cellspacing="0" allowTransparency="true" style="border:none; height:270px; width:220px;" src="' . $this->iframeSrc . '"></iframe>';
	
	//$this->copyJsCode = stripslashes($this->jsCode);
	$this->copyIframeCode = stripslashes($this->iframeCode);
  }
  
  private function makeUrlStr($urlStr){
  	return htmlentities($urlStr, ENT_QUOTES, 'UTF-8');
  }
  
  private function makeUrlTag($url, $modifiedDateTime=null, $changeFrequency='weekly', $priority=0.5) {
  	
  	if (!$changeFrequency) $changeFrequency = 'weekly';
  	
  	$urlOpen = INDENT . "<url>" . NEW_LINE;
  	$urlValue = "";
  	$urlClose = INDENT . "</url>" . NEW_LINE;
  	$locOpen = INDENT . INDENT ."<loc>";
  	$locValue = "";
  	$locClose = "</loc>" . NEW_LINE;
  	$lastmodOpen = INDENT.INDENT . "<lastmod>";
  	$lastmodValue = "";
  	$lastmodClose = "</lastmod>" . NEW_LINE;
  	$changefreqOpen = INDENT.INDENT . "<changefreq>";
  	$changefreqValue = "";
  	$changefreqClose = "</changefreq>" . NEW_LINE;
  	$priorityOpen = INDENT.INDENT . "<priority>";
  	$priorityValue = "";
  	$priorityClose = "</priority>" . NEW_LINE;

  	$urlTag = $urlOpen;
  	$urlValue     = $locOpen . self::makeUrlStr("$url") .$locClose;
  	
  	if ($modifiedDateTime) {
  		$urlValue .= $lastmodOpen . $modifiedDateTime .$lastmodClose;
  	} else {
  		$urlValue .= $lastmodOpen . date('c') .$lastmodClose;
  	}
  	
  	if ($changeFrequency) {
  		$urlValue .= $changefreqOpen .$changeFrequency .$changefreqClose;
  	}
  	if ($priority) {
  		$urlValue .= $priorityOpen .$priority .$priorityClose;
  	}
  	$urlTag .= $urlValue;
  	$urlTag .= $urlClose;
  	
  	return $urlTag;
  }
  
  public function executeSitemap(sfWebRequest $request){
  	
  	
  	$this->lines = '';
  	//Utils::pa("ok 111");

  	//header('Content-type: application/xml charset="utf-8"');
  	//header('Content-type: text/xml');
  	
  	//Utils::pa("ok 1");
  	define('ROOT_PROD_URL', 'http://www.sportycal.com');
  	
  	//define('CTG_ROOT_PROD_URL', ROOT_PROD_URL . '/category/');
  	//define('CAL_ROOT_PROD_URL', ROOT_PROD_URL . '/cal/');
  	
  	define('NEW_LINE', "\n");
  	define('INDENT', ' ');
  	
  	//define('XML_HEADER', '<?xml version="1.0" encoding="UTF-8">' . NEW_LINE);
  	//define('URL_SET_OPEN', '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.sitemaps.org/schemas/sitemap-image/1.1" xmlns:video="http://www.sitemaps.org/schemas/sitemap-video/1.1">' . NEW_LINE);
  	//define('URL_SET_CLOSE', '</urlset>' . NEW_LINE);
  	
  	
  	$q = Doctrine::getTable('Cal')->createQuery('cal');
 	$q->where('cal.is_public=?', 1);
 	$q->andWhere('cal.deleted_at is NULL');
	$cals = $q->execute();
	
	
	$q = Doctrine::getTable('Category')->createQuery('ctg');
 	$q->where('ctg.is_public=?', 1);
 	$q->andWhere('ctg.deleted_at is NULL');
	$ctgs = $q->execute();

	//header('Content-type: application/xml; charset="utf-8"',true);
	//header('Content-type: test/xml; charset="utf-8"');

	$urls = '';
	
	$urls .= self::makeUrlTag(ROOT_PROD_URL . '/', null, null, 0.9);
	$urls .= self::makeUrlTag(ROOT_PROD_URL . '/main/terms');
	$urls .= self::makeUrlTag(ROOT_PROD_URL . '/main/about', null, null, 0.6);
	$urls .= self::makeUrlTag(ROOT_PROD_URL . '/main/contact');
	$urls .= self::makeUrlTag(ROOT_PROD_URL . '/main/friendsBirthday');
	
	foreach ($cals as $cal){
		
		$url = ROOT_PROD_URL . $this->getController()->genUrl($cal->getUrl());
		
		$date = $cal->getUpdatedAt();
		$date = strtotime($date);
		$date = date('c', $date);
		
		$urls .= self::makeUrlTag($url,$date, 'daily', 0.8);
	}
	
  	foreach ($ctgs as $ctg){
		$url = ROOT_PROD_URL . $this->getController()->genUrl($ctg->getUrl());
		
		$date = $ctg->getApprovedAt();
		$date = strtotime($date);
		$date = date('c', $date);
		
		$urls .= self::makeUrlTag($url,$date,null, 0.7);
	}

	$this->lines =  $urls;
	
	
	
	//Utils::pp($this->lines);
	//$this->setLayout(false);
	//Utils::pa("ok done action");
	//return sfView::NONE;
	 
  }

  public function executeNotInIframe(sfWebRequest $request){
   UserUtils::setFromFbApp(false);
   echo 'ok';
   return sfView::NONE;
  }


  // Used to pass through the filter (via ajax) but do no action (used when changing language)
  public function executeDummy(sfWebRequest $request){
   	return sfView::NONE;
  }
  
  
  public function executeToto(sfWebRequest $request){
  	$partner = SportyCalAPI::getValidPartner($request);
  	if (!$partner) die("Invalid Partner");
	
  	UserUtils::setUserTZ('Asia/Jerusalem');
  	
  	$this->fromFbApp = UserUtils::getFromFbApp();
  	if ($this->fromFbApp) $this->setLayout("fbapp");
  	
  	$this->partnerHash = $partner->hash;
  	$this->rootCtgId = UpdateWinnerCals::PARTNER_CTG_ID;
  	
  }
}

