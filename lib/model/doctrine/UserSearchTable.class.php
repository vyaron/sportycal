<?php


class UserSearchTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('UserSearch');
    }

    public static function getBy(){
        
        return  Doctrine::getTable('UserSearch')
                ->createQuery('us')
                ->orderBy('created_at desc')
                ->execute();
    }



}