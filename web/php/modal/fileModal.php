<?php
include_once(BASE_DIR."/tool/tool.php");
include_once(Tool::getFilePath("tFactory"));
include_once(Tool::getFilePath("bTab"));
include_once(Tool::getFilePath("tDb"));
include_once(Tool::getFilePath("mTab"));

class FileModal{
	private $pdo;
	private $subList;
	private $tabModal;
	public function __construct($v){
		$this->pdo = $v;
	}

	public function fileWrite($fileName,$array){
		try{
		$file = fopen($fileName,"w");
		fwrite($file,json_encode($array));
		fclose($file);
		return 1;
		} catch(Exception $e){
			return -9;
		}
	}
	public function fileRead($fileName){
		try{
		$file = fopen($fileName,"r");
		$string = fread($file,filesize($fileName));
		fclose($file);
		return $string;
		} catch(Exception $e){
			return -9;
		}
	}
	public function tabFileCreate(){
		$navList = array();
		$this->subList = array();
		$this->tabModal = new TabModal($this->pdo);
		$navList = $this->getSubTab(null);
		//print_r($navList);
		//print_r($this->subList);
		$code = $this->fileWrite(BASE_DIR."/nav.txt",$navList);
		if($code!=1) return $code;
		$code = $this->fileWrite(BASE_DIR."/subList.txt",$this->subList);
		return $code;
	}
	private function getSubTab($tabId){
		$subNav = array();
		$tabArray = $this->tabModal->findSubTab($tabId);
		$arrNum = count($tabArray);
		for($i=0;$i<$arrNum;$i++){
			$tab = $tabArray[$i];
			$subArray = $this->getSubTab($tab->tabId);
			$tempTab = new TempTab($tab->tabId,$tab->tabName);
			$tempTab->subTab = $subArray;
			array_push($subNav,$tempTab);
			//subList
			$number = count($subArray);
			$idList = "";
			for($j=0;$j<$number;$j++){
				$temp = $subArray[$j];
				$idList = $idList.$temp->tabId.",";
				if($this->subList[$temp->tabId]!=""){
					$idList = $idList.$this->subList[$temp->tabId].",";
				}
			}
			if($idList!=""){
				$idList = substr($idList,0,-1);
			}
			$this->subList[$tab->tabId] = $idList;
		}
		return $subNav;
	}

}
class TempTab{
	public $tabId;
	public $tabName;
	public $subTab;
	function __construct($tabId,$tabName){
		$this->tabId = $tabId;
		$this->tabName = $tabName;
		$this->subTab = null;
	}
}
// $myDb = new MyDb();
// $pdo = $myDb->connect();

// $fileModal= new FileModal($pdo);
// $fileModal->tabFileCreate();

?>