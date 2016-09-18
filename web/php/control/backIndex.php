<?php
	include_once(BASE_DIR."/tool/tool.php");
	include_once(Tool::getFilePath("tDb"));
	include_once(Tool::getFilePath("tFactory"));
	include_once(Tool::getFilePath("mFile"));
	include_once(Tool::getFilePath("mLink"));
	include_once(Tool::getFilePath("mTab"));
	include_once(Tool::getFilePath("mLinkTimes"));
	include_once(Tool::getFilePath("bLink"));
	include_once(Tool::getFilePath("bTab"));
	include_once(Tool::getFilePath("bLinkTimes"));
	function getTab(){

		$myDb = new MyDb();
		$pdo = $myDb->connect();

		$tabModal = new TabModal($pdo);
		$tabList = $tabModal->findSubTab(null);

		$infos = array();
		$num = count($tabList);
		for($i=0;$i<$num;$i++){
			$tab = $tabList[$i];
			$obj = array("tab_id"=>$tab->tabId,"tab_name"=>$tab->tabName);
			array_push($infos,$obj);
		}
		echo json_encode(array(
			"result" => "1",
			"msg" => $infos
			),JSON_UNESCAPED_UNICODE);

	}
	function getLink(){

		$tabId = $_POST["tab_id"];
		$myDb = new MyDb();
		$pdo = $myDb->connect();
		$fileModal = new FileModal($pdo);
		$string = $fileModal->fileRead(BASE_DIR."/subList.txt");
		$tabList = json_decode($string);
		$tabStr = $tabList->$tabId;
		if($tabStr==""){
			$tabStr = $tabId."";
		}else{
			$tabStr = $tabStr.",".$tabId;
		}
		$linkModal = new LinkModal($pdo);
		$links = $linkModal->findLinkByTabIdOrder($tabStr,1,10);
		$linkStr = Factory::getIdList($links,"linkId");
		$linktimeModal = new LinkTimesModal($pdo);
		$linktimes = $linktimeModal->searchClick($linkStr);
		$infos = array();
		$num = count($links);
		for($i=0;$i<$num;$i++){
			$link = $links[$i];
			$linkClick = 0;
			if(isset($linktimes[$link->linkId]))
				$linkClick = $linktimes[$link->linkId];
			$obj = array("link_name"=>$link->linkName,"link_address"=>$link->linkAddress,"rank_number"=>($i+1),"link_click"=>$linkClick);
			array_push($infos,$obj);
		}
		echo json_encode(array(
			"result" => "1",
			"msg" => $infos
			),JSON_UNESCAPED_UNICODE);
	}
?>