<?php

/**
 * partner actions.
 *
 * @package    evento
 * @subpackage partner
 * @author     Yaron Biton
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class partnerActions extends sfActions
{
  private function restrictAccess() {
    $user = UserUtils::getLoggedIn();
    if (!$user || !$user->isMaster()) {
      $this->redirect("main/index");
    }
  }    
	
  public function executeIndex(sfWebRequest $request)
  {
  	
  	$this->restrictAccess();
    $this->partners = Doctrine::getTable('Partner')
      ->createQuery('a')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
  	$this->restrictAccess();
  	$this->partner = Doctrine::getTable('Partner')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->partner);
  }

  public function executeNew(sfWebRequest $request)
  {
  	$this->restrictAccess();
  	$this->form = new PartnerForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
  	$this->restrictAccess();
  	$this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new PartnerForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
  	$this->restrictAccess();
  	$this->forward404Unless($partner = Doctrine::getTable('Partner')->find(array($request->getParameter('id'))), sprintf('Object partner does not exist (%s).', $request->getParameter('id')));
    $this->form = new PartnerForm($partner);
  }

  public function executeUpdate(sfWebRequest $request)
  {
  	$this->restrictAccess();
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($partner = Doctrine::getTable('Partner')->find(array($request->getParameter('id'))), sprintf('Object partner does not exist (%s).', $request->getParameter('id')));
    $this->form = new PartnerForm($partner);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
  	$this->restrictAccess();
    $request->checkCSRFProtection();

    $this->forward404Unless($partner = Doctrine::getTable('Partner')->find(array($request->getParameter('id'))), sprintf('Object partner does not exist (%s).', $request->getParameter('id')));
    $partner->delete();

    $this->redirect('partner/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $partner = $form->save();

      $this->redirect('partner/edit?id='.$partner->getId());
    }
  }

  
  protected function processLoginForm(sfWebRequest $request, sfForm $form, $isAjax = false)
  {
  	$res = array('success' => false, 'msg' => __('Incorrect Email/Password Combination.'));
  	
    $form->bind($request->getParameter('user'));
    if ($form->isValid())
    {
    	
    	$userValues = $form->getValues();

  		$user = UserTable::getByEmail($userValues["email"]);
  		// TODO: encryption
        if ($user && $user->getPass() === $userValues["password"]) {
        	
			// Create the login session
			UserUtils::logUserIn($user);
			
			if ($isAjax){
				$res['success'] = true;
				$res['msg'] = __('Log in success');
				$res['html'] = $this->getPartial('global/topNav', array('user' => $user));
			} else {
				$refererUrl = UserUtils::getRefererUrl();
				if ($refererUrl) {
					UserUtils::setRefererUrl(null);
					$this->redirect($refererUrl);
				} else {
					if (sfConfig::get('app_domain_isNeverMiss')) $this->redirect('/nm/calList');
					else $this->redirect('@homepage');
				}
			}
        }
    }
    
    if ($isAjax) echo json_encode($res);
  }
  
  
  public function executeLogin(sfWebRequest $request){
  	$isAjax = $this->getRequest()->isXmlHttpRequest();

  	$this->getResponse()->setSlot('login', true);
  	
  	if (sfConfig::get('app_domain_isNeverMiss')){
	  	$url = $this->getRequest()->getReferer();
	  	if (strpos($url, sfConfig::get('app_domain_full')) === 0 && !strpos($url, '/partner/login') && !strpos($url, '/nm/index')) UserUtils::setRefererUrl($url);
  	}
  	
	$this->form = new LoginForm();
  	
  	if ($request->isMethod('post')) {
		$this->processLoginForm($request, $this->form, $isAjax);
  	}
  	
  	if ($isAjax) return sfView::NONE;	
  	else if (sfConfig::get('app_domain_isNeverMiss')) $this->setTemplate('login', 'nm');
  }
  

}
