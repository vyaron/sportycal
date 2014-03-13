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
	
	public static function getById($partnerId = null, $calId = null) {
		$q =  Doctrine::getTable('Partner')->createQuery('p');

        if ($partnerId) $q->where('p.id = ?', $partnerId);

        if ($calId) {
            $q->innerJoin('p.Cal c')
            ->where('c.id = ?', $calId);
        }

        return ($partnerId || $calId) ? $q->fetchOne() : null;
	}
	
	public static function getBy() {
		$q =  Doctrine::getTable('Partner')
                ->createQuery('p');
        return $q->execute();
		
	}
	
    
}