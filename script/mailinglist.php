<?php
require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'prod', false);
sfContext::createInstance($configuration);

function setMailinglist(){
	//Remove open tasks
	MailinglistTable::clearPendings();
	
	$i = 0;
	do {
		
		$users = UserTable::getSubscribes($i);

		$collectionMailinglists = new Doctrine_Collection('Mailinglist');
		foreach ($users as $user){
			$mailinglist = new Mailinglist();
			$mailinglist->setUserId($user->getId());
			$mailinglist->setCreatedAt(date('Y-m-d g:i:s'));
			
			$collectionMailinglists->add($mailinglist);
		}
		$collectionMailinglists->save();
		
		$i++;
	} while($users->count());
}

function sendMails(){
	do {
		$mailinglists = MailinglistTaskTable::getAll();
		
		foreach ($mailinglists as $i => $mailinglist){
			$mailinglist->send();
		}
		
		sleep(rand(10,25));
	} while($mailinglists->count());
}

if(function_exists( $argv[1] )) call_user_func_array($argv[1], $argv);