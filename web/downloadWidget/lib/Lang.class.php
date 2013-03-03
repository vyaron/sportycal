<?php
class Lang{
	const LANG_LIB_PATH = '/langs/';
	
	private static $supportedLangs = array(
		'en_US',
		'he_IL',
		'bg', 'cs', 'da', 'de', 'el', 'es_EM', 'fr', 'hu', 'it', 'nl', 'ptbr', 'ro', 'ru', 'sr', 'sv'
	);
	
	private $langFileContant = '';
	private $transArr = array();
	
	public function __construct($lang = null){
		if (! in_array($lang, self::$supportedLangs)) $lang = self::$supportedLangs[0];
		
		$filePath = dirname(__FILE__) . self::LANG_LIB_PATH . $lang . '.ini';
		
		if (file_exists($filePath)){
			$this->langFileContant = @file_get_contents($filePath);
			$this->parseFileContent();
		}
	}
	
	private function parseFileContent(){
		if ($this->langFileContant){
			try {
				$trans = explode("\n", $this->langFileContant);

				foreach ($trans as $tran){
					$sepPos = strpos($tran, '|');
					
					if ($sepPos > 1){
						$key = substr($tran, 0, $sepPos);
						$val = substr($tran, $sepPos + 1);
						
						$this->transArr[trim($key)] = trim($val);
					}
				}
			} catch (Exception $e) {
				$this->transArr = array();
			}
		}
	}
	
	public function trans($val){
		$tVal = trim($val);
		
		if (array_key_exists($tVal, $this->transArr)){
			$tVal = $this->transArr[$val];
		}

		//return htmlentities($tVal, ENT_QUOTES, "UTF-8");
		return $tVal;
	}
}