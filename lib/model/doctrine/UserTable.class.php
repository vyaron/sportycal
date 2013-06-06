<?php


class UserTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('User');
    }


    public static function getByFBCode($fbCode) {
        $user = null;
        $q = Doctrine_Query::create()
            ->select('u.*')
            ->from('User u')
            ->where("u.fb_code = ? ", $fbCode);
    

        
        $users = $q->execute();

        if (count($users) == 1) {
          $user = $users[0];  
        }
        return $user;
    }

    public static function getByEmail($email) {
        $q = Doctrine_Query::create()
            ->select('')
            ->from('User u')
            ->where('u.email = :email', array('email'=>$email));
    
        //echo $q->getSqlQuery();
    
        $user = $q->fetchOne();
        return $user;
    }
 	
    const CREDIT_FOR_SIGNUP			= 5;
    const CREDIT_REF_VISIT			= "REF_VISIT";
    const CREDIT_REF_SIGNUP			= "REF_SIGNUP";
    const CREDIT_DOWNLOAD			= "DOWNLOAD";
    const CREDIT_REF_DOWNLOAD		= "REF_DOWNLOAD";
    const CREDIT_THANKYOU_SHARE		= "THANKYOU_SHARE";
    const CREDIT_PROMO_FBPOST		= "PROMO_FBPOST";
    
    public static function creditToUser($userId, $creditType) {
    	$credit = 0;
    	if ($creditType == self::CREDIT_REF_SIGNUP) {
    		$credit = 11;
    	} elseif ($creditType == self::CREDIT_DOWNLOAD) {
    		$credit = 4;
    	} elseif ($creditType == self::CREDIT_REF_DOWNLOAD) {
    		$credit = 5	;
    	} elseif ($creditType == self::CREDIT_THANKYOU_SHARE) {
    		$credit = 9;
    	} elseif ($creditType == self::CREDIT_PROMO_FBPOST) {
    		$credit = 8;
    	} elseif ($creditType == self::CREDIT_REF_VISIT) {
    		$credit = 1;
    	}
    		    		    		    		    		
    	$user = Doctrine::getTable('User')->find($userId);
    	//Utils::pp($user);
    	if ($user && $credit) {
    		$user->setBalance($user->getBalance() + $credit);
    		$user->save();
    	} 
    	
    }   
    
    public static function getSubscribes($page=0, $limit=100){
    	$offset = $page * $limit;
    	 
    	$q = Doctrine::getTable('User')->createQuery('u')
	    	->offset($offset)
	    	->limit($limit)
	    	->where('u.is_subscribe = true');
    	 
    	return $q->execute();
    }
}