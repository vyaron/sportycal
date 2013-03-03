<?php

/**
 * PartnerUser
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    evento
 * @subpackage model
 * @author     Yaron Biton
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class PartnerUser extends BasePartnerUser
{
	public static function getPartnerUser($userId){
		$q = Doctrine::getTable('PartnerUser')->createQuery('pu')->where('pu.user_id = :userId', array('userId'=>$userId));
    	$pu = $q->fetchOne();

		return $pu;
	}
}