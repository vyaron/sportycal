<?php

/**
 * This is a shortURL mechanism
 *
 * @package    evento
 * @subpackage l
 * @author     Yaron Biton
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class lActions extends sfActions
{
	
	// This is the REDIRECT method that is used whenever someone uses a short-url
  public function executeR(sfWebRequest $request)
  {
	$hash = $request->getParameter('h');
	$this->forward404Unless($hash);
	
	if ($hash == 'facebook'){
		$this->forward404Unless($event = Doctrine::getTable('Event')->find(array($request->getParameter('id'))), sprintf('Object event does not exist (%s).', $request->getParameter('id')));
		$url = $event->getFbShareUrl();
	} else {
		$shortUrl = Doctrine::getTable('ShortUrl')->findOneByHash($hash);
		//Utils::pp($shortUrl);
	  	
	  	$this->forward404Unless($shortUrl);
	  	
	  	$shortUrl->setCountUsed($shortUrl->getCountUsed() + 1);
	  	$shortUrl->setUsedAt(date('Y-m-d H:i:s'));
	  	$shortUrl->save();
	
	    $url = $shortUrl->getUrl();
	}
	
  	$this->redirect($url);
  }
}
