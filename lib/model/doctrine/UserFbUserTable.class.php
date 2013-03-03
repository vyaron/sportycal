<?php


class UserFbUserTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('UserFbUser');
    }

	public function arrangeByFbUserId($userDBFriends) {
		//Utils::pp("aa");
		$byFbUserIdMap = array();
		foreach ($userDBFriends as $userDBFriend) {
			$byFbUserIdMap[$userDBFriend->getFbUserId()] = $userDBFriend;
		}
		return $byFbUserIdMap;		
	}
    

}