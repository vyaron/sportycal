<?php

/**
 * Class: FacebookUtils
 * This class stores all the facebook logic
 */

require_once 'facebook/facebook.php';

class FacebookUtils {
	const GRAPH_URL = "https://graph.facebook.com/";
	
	private static function getFbObject() {

		$config = array();
		$config['appId'] = FACEBOOK_APP_ID;
		$config['secret'] = FACEBOOK_SECRET;
		//$config['cookie'] = true; // optional
		$config['fileUpload'] = true; // optional
		
		$fb = new Facebook($config);
		return $fb;
	}
	
	public function getLogoutUrl(){
		$fb = self::getFbObject();
		$accessToken = $fb->getAccessToken();
		
		$url = null;
		if ($accessToken){
			$url= $fb->getLogoutUrl(array('next' => SERVER_FULL_URL));
		}
		
		return $url;
	}
	
	public static function getUser() {

		$fbUser = null;
		
		$fb = self::getFbObject();
		
	    $fbUserId = $fb->getUser();
		
	    // We may or may not have this data based on whether the user is logged in.
		// If we have a $user id here, it means we know the user is logged into
		// Facebook, but we don't know if the access token is valid. An access
		// token is invalid if the user logged out of Facebook.
	
		if ($fbUserId) {
		  try {
		    // Proceed knowing you have a logged in user who's authenticated.
		    $fbUser = $fb->api('/me');
		    $fbUser = (object)$fbUser;
		  } catch (FacebookApiException $e) {
		    error_log($e);
		  }
		}
		
		return $fbUser;    
	}
	
	public static function getFriends($user) {
		
		$fb = self::getFbObject();
		
		$fbFriends = array();
		$fbCode = $user->getFbCode();
		if ($fbCode) { 
			
			$accessToken = $fb->getAccessToken();
			//Utils::pa($accessToken);
			$q = 'SELECT uid,name,birthday_date FROM user WHERE uid in (SELECT uid2 FROM friend WHERE uid1=me()) ORDER BY name';
			if ($accessToken) {
				$jsonFriends = @file_get_contents(self::GRAPH_URL . 'fql?q=' . urlencode($q) . '&access_token=' . $accessToken );
				$jsonFriends = preg_replace('/uid":(\d+),/', 'uid":"$1",', $jsonFriends); 

				if ($jsonFriends) 	$fbFriends = json_decode($jsonFriends);
				if ($fbFriends) 	$fbFriends = $fbFriends->data;
			}
		}				


		
		return $fbFriends;
	}
	
	/*
	const GRAPH_URL = "https://graph.facebook.com/"; 

	public static function getCookie($app_id, $application_secret) {
          
          //echo "app_id:$app_id ; application_secret:$application_secret<br/>";

          if (!isset($_COOKIE['fbs_' . $app_id])) return null;
          
	  $args = array();
	  parse_str(trim($_COOKIE['fbs_' . $app_id], '\\"'), $args);
	  ksort($args);
	  $payload = '';
	  foreach ($args as $key => $value) {
	    if ($key != 'sig') {
	      $payload .= $key . '=' . $value;
	    }
	  }
	  
	  
	  if (md5($payload . $application_secret) != $args['sig']) {
	    return null;
	  }
	  return $args;
	}
	
	public static function getFriends($user) {
		
		$fbFriends = null;
		if ($user && $user->fb_code) { 
			
			$fbCookie = self::getCookie(FACEBOOK_APP_ID, FACEBOOK_SECRET);
			$q = 'SELECT uid,name,birthday_date FROM user WHERE uid in (SELECT uid2 FROM friend WHERE uid1=me()) ORDER BY name';
			if ($fbCookie) {
				$jsonFriends = @file_get_contents(self::GRAPH_URL . 'fql?q=' . urlencode($q) . '&access_token=' . $fbCookie['access_token'] );

				$jsonFriends = preg_replace('/uid":(\d+),/', 'uid":"$1",', $jsonFriends); 
				
				if ($jsonFriends) 	$fbFriends = json_decode($jsonFriends);
				if ($fbFriends) 	$fbFriends = $fbFriends->data;
			}
		}				

		return $fbFriends;
	}
	*/
}
?>