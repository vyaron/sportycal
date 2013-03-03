<?php


class AliasTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Alias');
    }

	public static function getAlias($strAlias, $partnerId=null) {
		$q =  Doctrine::getTable('Alias')
                ->createQuery('a')
                ->where('a.alias = ?', $strAlias);
                
		if ($partnerId) $q->andWhere('a.partner_id = ? or a.partner_id is null', $partnerId);
        return $q->fetchOne();
		
	}
}