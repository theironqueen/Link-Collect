<?php
class LinkTimes{
	private $linkId;
	private $clickTime;

	public function __get($n){
		return $this->$n;
	}
	public function __set($n,$v){
		$this->$n = $v;
	}
	public function __toString(){
		$string = "";
		$string = $string."linkId:".$this->linkId;
		$string = $string." clickTime:".$this->clickTime;
		return $string;
	}
}
?>