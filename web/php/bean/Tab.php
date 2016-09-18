<?php
class Tab{
	private $tabId;
	private $tabName;
	private $tabParentId;
	private $tabTime;

	public function __construct(){
		$this->tabId = null;
		$this->tabName = null;
		$this->tabParentId = null;
		$this->tabTime = null;
	}

	public function __get($n){
		return $this->$n;
	}
	public function __set($n,$v){
		$this->$n = $v;
	}
	public function __toString(){
		$string = "";
		$string = $string."tabId:".$this->tabId;
		$string = $string." tabName:".$this->tabName;
		$string = $string." tabParentId:".$this->tabParentId;
		$string = $string." tabTime:".$this->tabTime;

		return $string;
	}
}
?>