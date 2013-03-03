<?php

require_once "PageGrabber.php";

// Yaron: AbstractSpider give some general services to spiders
  	
class AbstractSpider{

	private   	$outFileCsv;
	private   	$outFileSql;
	private		$outSql;
	private		$outCsv;
	private	 	$anonymizer;
	
	protected function __construct($outFileCsv=null, $outFileSql=null) {
		//$this->outFileCsv = $outFileCsv;
		//$this->outFileSql = $outFileSql;
		$this->outSql = array();
		$this->outCsv = array();
		
		//$this->emptyFiles();
		
		$this->anonymizer = new PageGrabber();
	}

	public function getOutputSql() {
		return $this->outSql;
	}
	public function getOutputCsv() {
		return $this->outCsv;
	}
	
	protected function grabUrl($url) {
		if (false && defined("NO_PROXIES_GO_DIRECT")) {
			$webpage = $this->anonymizer->grabURLGoDirect($url);
		} else {
			$webpage = $this->anonymizer->grabURL($url);	
		}
		return $webpage;
	}

	protected function isGrabFailed() {
		return $this->anonymizer->failure();
	}
	
	protected function cleanWhiteSpaces($webpage) {
		$webpage = str_replace(array("\n", "\r", "\t", "\o", "  ", "\xOB"), '', $webpage);
		return $webpage;
	}
	

	protected function addOutput($out) {
		$this->doAddOutput($out, $this->outFileCsv);
		$this->doAddOutput($out, $this->outFileSql);
	}
	
	
	protected function addOutputCsv($out) {
		$this->doAddOutput($out, $this->outFileCsv);
		$this->outCsv[] = $out;
	}
	protected function addOutputSql($out) {
		$this->doAddOutput($out, $this->outFileSql);
		$this->outSql[] = $out;
	}
	
	protected function cleanOutputCsv() {
		$this->outCsv = array();
	}
	
	private function doAddOutput($out, $file) {
		if (!$file) return;
		$fh = fopen($file, 'a');
		fwrite($fh, $out."\n");
		fclose($fh);
	}
	private function emptyFiles() {
		$this->emptyFile($this->outFileCsv);
		$this->emptyFile($this->outFileSql);
	}
	
	private function emptyFile($file) {
		if (!$file) return;
		$fh = fopen($file, 'w');
		fclose($fh);
	}
	
	protected function debugo($msg, $die = false) {
		//echo "DEBUG: " . $msg . "<br/>";
		//if ($die) die("DEATH is a BLESS");
	}

	
}
?>