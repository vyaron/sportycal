<?php


class PartnerTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Partner');
    }
	
    public static function getPartner($partnerHash) {
		$q =  Doctrine::getTable('Partner')
                ->createQuery('p')
                ->where('p.hash = ?', $partnerHash);

                //Utils::pp($q->getSql());
        return $q->fetchOne();
		
	}
	
	public static function getById($partnerId) {
		$q =  Doctrine::getTable('Partner')
                ->createQuery('p')
                ->where('p.id = ?', $partnerId);

        return $q->fetchOne();
	}
	
	public static function getBy() {
		$q =  Doctrine::getTable('Partner')
                ->createQuery('p');
        return $q->execute();
		
	}
	
    
}