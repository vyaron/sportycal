<?php

/**
 * l actions.
 *
 * @package    evento
 * @subpackage l
 * @author     Yaron Biton
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class shortURLActions extends sfActions
{

  private function restrictAccess() {
    $user = UserUtils::getLoggedIn();

    if (!$user || !($user->isMaster() || $user->isPartner())) {
      sfConfig::get('app_domain_isNeverMiss') ? $this->redirect("nm/index") : $this->redirect("main/index");
    }
    
    $this->user = $user;
  }    
	
	
	public function executeIndex(sfWebRequest $request)  {
	    $this->restrictAccess();   
    
	    $this->sort    = $request->getParameter('sort');
	    if (!$this->sort) $this->sort = 'hash';
	    
	    $this->orderBy    = $request->getParameter('orderBy');
	    if (!$this->orderBy) $this->orderBy = 'asc';
	    
	    $q = Doctrine::getTable('ShortUrl')
	      ->createQuery('a')
	      ->orderBy("$this->sort $this->orderBy");
			
		$this->shortURLs = $q->execute();
	}

  public function executeShow(sfWebRequest $request)
  {
  	$this->restrictAccess();
    $this->short_url = Doctrine::getTable('ShortUrl')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->short_url);
  }

  public function executeNew(sfWebRequest $request)
  {
  	$this->restrictAccess();
    $this->form = new ShortUrlForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
  	$this->restrictAccess();
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new ShortUrlForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
  	$this->restrictAccess();
    $this->forward404Unless($short_url = Doctrine::getTable('ShortUrl')->find(array($request->getParameter('id'))), sprintf('Object short_url does not exist (%s).', $request->getParameter('id')));
    $this->form = new ShortUrlForm($short_url);
  }

  public function executeUpdate(sfWebRequest $request)
  {
  	$this->restrictAccess();
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($short_url = Doctrine::getTable('ShortUrl')->find(array($request->getParameter('id'))), sprintf('Object short_url does not exist (%s).', $request->getParameter('id')));
    $this->form = new ShortUrlForm($short_url);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
  	$this->restrictAccess();
    $request->checkCSRFProtection();

    $this->forward404Unless($short_url = Doctrine::getTable('ShortUrl')->find(array($request->getParameter('id'))), sprintf('Object short_url does not exist (%s).', $request->getParameter('id')));
    $short_url->delete();

    $this->redirect('shortURL/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
  	
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $shortUrl = $form->save();
      // Yaron: not used for now, lets keep the hash meaningful
      //$hash = ShortUrl::createHash($shortUrl->getId());
  	  //$shortUrl->setHash($hash);
  	  $shortUrl->save();

      $this->redirect('shortURL/edit?id='.$shortUrl->getId());
    }
  }




}
