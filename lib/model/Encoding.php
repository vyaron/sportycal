<?php


class Encoding {

	public static function replaceSpecialChars($txt) {

		$txt = str_replace('ó', 'o', $txt);
		$txt = str_replace('ú', 'u', $txt);
		$txt = str_replace('í', 'i', $txt);
		$txt = str_replace('é', 'e', $txt);
		$txt = str_replace('á', 'a', $txt);
		$txt = str_replace('\'', '', $txt);
		
		return $txt;
		
	}  
	
    
}




?>