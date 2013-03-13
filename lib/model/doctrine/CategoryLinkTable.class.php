<?php


class CategoryLinkTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('CategoryLink');
    }

    public static function getByCategoryIds($ctgIds){
        
        $w = 'cl.category_id';
        
        $ctgs = Doctrine::getTable('CategoryLink')
                    ->createQuery('cl')
                    ->whereIn($w, $ctgIds)
                    ->execute();
        return $ctgs;
    }

	public static function getBy($ctgId, $url){
		$ctgs = Doctrine::getTable('CategoryLink')
		->createQuery('cl')
		->where('cl.category_id = ?', $ctgId)
		->andWhere('cl.url = ?', $url)
		->execute();

		return $ctgs;
	}
    


}