<?php

class PageGrabber { 

	private $proxy;
	
	public function setProxy($proxy) {
		$this->proxy = $proxy;
	}
	
	
	public function grabURL($url) {
		$ch = curl_init();
       
		if ($this->proxy) curl_setopt($ch, CURLOPT_PROXY, $this->proxy);
		
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPGET, true);

        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.5; en-US; rv:1.9) Gecko/2008061004 Firefox/3.0');
        
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_COOKIESESSION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		
               
        $webpage = curl_exec($ch);
        
        return $webpage;
    }

    private function debugo($msg, $die = false) {
        echo $msg . "\n";
        if ($die) die("DEATH is a BLESS");
    }
    
    public function failure() {
    	echo "Nk, failed, Nk?";
    }
    
    
}
