<?php
class Record{
	private $recordId;
	private $recordTime;
	private $recordType;
	private $recordObject;
	private $recordMemo;
	
	public function __get($n){
		return $this->$n;
	}
	public function __set($n,$v){
		$this->$n = $v;
	}
	public function __toString(){
		$string = "";
		$string = $string."recordId:".$this->recordId;
		$string = $string." recordTime:".$this->recordTime;
		$string = $string." recordType:".$this->recordType;
		$string = $string." recordObject:".$this->recordObject;
		$string = $string." recordMemo:".$this->recordMemo;

		return $string;
	}
}
?>