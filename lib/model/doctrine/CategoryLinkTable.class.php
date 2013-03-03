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


    


}