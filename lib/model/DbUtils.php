<?php
class DbUtils {
	public static function getCountryByIp($ip){
		if ($ip){
			$ipParts = explode('.', $ip);
			$ipToNum = -1;
			if (count($ipParts) == 4) $ipToNum = ($ipParts[0] * pow(256,3)) + ($ipParts[1] * pow(256,2)) + ($ipParts[2] * 256) + $ipParts[3];

			$con = Doctrine_Manager::getInstance()->connection();
			$results = $con
				->execute("SELECT `contry` FROM `ip_to_geo` WHERE ip_from < ? AND ip_to > ?", array($ipToNum, $ipToNum))
				->fetchAll();
			
			if (count($results)) $ret = $results[0][0];
			else $ret = null;
			
			return $ret;
		}
	}
}
?>