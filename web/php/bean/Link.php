<?php
class Link{
	private $linkId;
	private $tabId;
	private $linkTime;
	private $linkName;
	private $linkAddress;
	
	public function __get($n){
		return $this->$n;
	}
	public function __set($n,$v){
		$this->$n = $v;
	}
	public function __toString(){
		$string = "";
		$string = $string."linkId:".$this->linkId;
		$string = $string." tabId:".$this->tabId;
		$string = $string." linkTime:".$this->linkTime;
		$string = $string." linkName:".$this->linkName;
		$string = $string." linkAddress:".$this->linkAddress;

		return $string;
	}
}

?>