<?php


/**
 * stat actions.
 *
 * @package    evento
 * @subpackage admin
 * @author     Yaron Biton
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sActions extends sfActions
{

  private function restrictAccess() {
    $user = UserUtils::getLoggedIn();
    if (!$user || !$user->isMaster()) {
      $this->redirect("main/index");
    }
  }    

  // Called when an event is open in a web mail (google)
  // http://sportycal.local/s/intel?e=14999&ucl=22&s=event&a=open&p=1976&l=cId:9191&v=111
  public function executeIntel(sfWebRequest $request) {

  	$ctgId    	= $request->getParameter('ctg');
  	$calId    	= $request->getParameter('cl');
  	$userId  	= $request->getParameter('uid');
  	$userCalId  = $request->getParameter('ucl');
    $eventId    = $request->getParameter('e');
    $partnerId 	= $request->getParameter('p');
    $section   	= $request->getParameter('s');
    $action   	= $request->getParameter('a');
    $label   	= $request->getParameter('l');
    $value   	= $request->getParameter('v');
    
    if ($eventId) {
	    $event = Doctrine::getTable('Event')->find(array($eventId));
	    if (!$event) return sfView::NONE;
	    else $calId = $event->getCalId();
    }
    
	if (!$userId) $userId 	= UserUtils::getLoggedInId();
    $dateNow    = date("Y-m-d H:i:s");

	$intelId = IntelTable::insertNew($section, $action, $label, $value, $userId, $calId, $ctgId, $eventId, $partnerId, $userCalId);    

	
	if ($section == "promo" && $action == "fb-post" && $userId) {
	  UserTable::creditToUser($userId, UserTable::CREDIT_PROMO_FBPOST);
	}
	
	if ($section == "ThankYou Share" && $userId) {
	  UserTable::creditToUser($userId, UserTable::CREDIT_THANKYOU_SHARE);
	}
	
    //echo $intelId;
    return sfView::NONE;
  }
  
    
    
}

	