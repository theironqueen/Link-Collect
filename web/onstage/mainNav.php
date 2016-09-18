<?php
//header("Content-type: text/html; charset=utf-8");
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


// $tab1 = new Tab("2","hehe11");
// $tab2 = new Tab("3","hehe3");
// $tab3 = new Tab("4","hehe4");

// $array = array();
// array_push($array,$tab1);
// array_push($array,$tab2);
// array_push($array,$tab3);

// $sub_array1 = array();
// $tab4 = new Tab("5","hehe5");
// $tab5 = new Tab("6","hehe6");
// array_push($sub_array1,$tab4);
// array_push($sub_array1,$tab5);

// $array[0]->subTab = $sub_array1;
// $array[1]->subTab = $sub_array1;

// $sub_array2 = array();
// $tab6 = new Tab("7","hehe7");
// array_push($sub_array2,$tab6);
// $sub_array1[0]->subTab = $sub_array2;
// //echo $tab1->tabId;
// //echo mb_convert_encoding("hehe","UTF-8");
// $jsonString = json_encode($array);
$file = fopen("../php/nav.txt","r");
$jsonString = fread($file,filesize("../php/nav.txt"));
fclose($file);

$obj = json_decode($jsonString);
createNav($obj);

function createNav($navObj){
	for($i=0;$i<count($navObj);$i++){
		$item = $navObj[$i];
		echo "<li><a href='#' title='".$item->tabId."'>";
		echo $item->tabName;
		echo "</a>";
		if($item->subTab!=null){
			echo "<ul class='submenu'>";
			createNav($item->subTab);
			echo "</ul>";
		}
		echo "</li>";
	}
}


?>