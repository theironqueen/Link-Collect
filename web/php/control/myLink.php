<?php
	include_once(BASE_DIR."/tool/tool.php");
	include_once(Tool::getFilePath("tDb"));
	include_once(Tool::getFilePath("tFactory"));
	include_once(Tool::getFilePath("mFile"));
	include_once(Tool::getFilePath("mLink"));
	include_once(Tool::getFilePath("mTab"));
	include_once(Tool::getFilePath("mRecord"));
	include_once(Tool::getFilePath("mLinkTimes"));
	include_once(Tool::getFilePath("bLink"));
	include_once(Tool::getFilePath("bTab"));
	include_once(Tool::getFilePath("bLinkTimes"));
	include_once(Tool::getFilePath("bRecord"));
	function search(){
		$pageNum = $_POST["page_num"];
		$page = $_POST["page"];
		$page = intval($page);
		$pageNum = intval($pageNum);
		$draw = $_POST["draw"];
		$searchType = $_POST["search_type"];
		$searchContent = $_POST["search_content"];

		$myDb = new MyDb();
		$pdo = $myDb->connect();
		$tabModal = new TabModal($pdo);
		$linkModal = new LinkModal($pdo);
		$linkTimesModal = new LinkTimesModal($pdo);
		$linkList = array();
		$totalNumber = 0;
		if($searchContent==""){
			$linkList = $linkModal->findAll($page,$pageNum);
			$totalNumber = $linkModal->findAllNumberHelp();
		}else if($searchType=="0"){
			$linkList = $linkModal->findLinkByTabId($searchContent,$page,$pageNum);
			$totalNumber = $linkModal->findLinkByTabIdNumberHelp($searchContent);
			// if($tab!=null){
			// 	array_push($linkList,$tab);
			// 	$totalNumber = 1;
			// }
		}else if ($searchType == "1"){
			$tabs = $tabModal->findTabByName($searchContent);
			$tabStr = Factory::getIdList($tabs,"tabId");
			$linkList = $linkModal->findLinkByTabId($tabStr,$page,$pageNum);
			$totalNumber = $linkModal->findLinkByTabIdNumberHelp($tabStr);
		}else if ($searchType=="2"){
			$link = $linkModal->findLinkById($searchContent);
			if($link!=null){
				array_push($linkList,$link);
				$totalNumber = 1;
			}
		}else if ($searchType == "3") {
			$linkList = $linkModal->findLinkByName($searchContent,$page,$pageNum);
			$totalNumber = $linkModal->findLinkByNameNumberHelp($searchContent);
		}
		//获取其他内容
		$infos = array();
		$number = count($linkList);
		//获得 link 中的tab的name
		$tabIdStr = Factory::getIdList($linkList,"tabId");
		$strList = explode(",",$tabIdStr);
		$strList = array_unique($strList);
		$tabIdStr = implode(",", $strList);
		$nameList = $tabModal->findTabNameByIdList($tabIdStr);
		//获得link 的 click number
		$linkIdStr = Factory::getIdList($linkList,"linkId");
		$clickList = $linkTimesModal->searchClick($linkIdStr);
		for($i=0;$i<$number;$i++){
			$link = $linkList[$i];
			$tabName = $nameList[$link->tabId];
			$click = isset($clickList[$link->linkId])?$clickList[$link->linkId]:0;

			$obj = array("link_id"=>$link->linkId,"link_name"=>$link->linkName,"tab_id"=>$link->tabId,"tab_name"=>$tabName,"link_click"=>$click,"link_address"=>$link->linkAddress,"link_time"=>$link->linkTime);
			array_push($infos,$obj);
		}
		echo json_encode(array(
			"draw" => $draw,
			"recordsFiltered" => $totalNumber,
			"data" => $infos
			),JSON_UNESCAPED_UNICODE);
	}
	function edit(){
		$myDb = new MyDb();
		$pdo = $myDb->connect();
		$linkModal = new LinkModal($pdo);
		$recordModal = new RecordModal($pdo);
		$linkId = $_POST["link_id"];
		$linkName = $_POST["link_name"];
		$linkAddress = $_POST["link_address"];
		$link = $linkModal->findLinkById(intval($linkId));
		if($link==null){
			echo json_encode(array(
				"msg"=>"修改链接不存在",
				"result"=>"0"
				),JSON_UNESCAPED_UNICODE);
			return;
		}
		$code = 1;
		try{
			$pdo->beginTransaction();
			$memo = "oldLink:".$link."\r\n";
			$link->linkName = $linkName;
			$link->linkAddress = $linkAddress;
			$code = $linkModal->edit($link);
			if($code!=1)throw new PDOException();
			$memo = $memo."newLink:".$link."\r\n";
			$record = Factory::createRecord(2,0,Tool::getDate(),$memo);
			$code = $recordModal->add($record);
			if($code!=1)throw new PDOException();
			$pdo->commit();
		}catch(PDOException $e){
			$pdo->rollBack();
		}
		$msg = "";
		if($code==1){
			$msg = "修改链接成功";
		}else{
			$msg = Tool::getErrorMsg($code);
			$code = 0;
		}
		echo json_encode(array(
			"msg"=>$msg,
			"result"=>$code
			),JSON_UNESCAPED_UNICODE);
	}
	function delete(){
		$myDb = new MyDb();
		$pdo = $myDb->connect();
		$linkModal = new LinkModal($pdo);
		$recordModal = new RecordModal($pdo);

		$linkId = $_POST["link_id"];
		$link = $linkModal->findLinkById(intval($linkId));
		if($link==null){
			echo json_encode(array(
				"msg"=>"删除链接不存在",
				"result"=>"0"
				),JSON_UNESCAPED_UNICODE);
			return;
		}
		$code = 1;
		try{
			$pdo->beginTransaction();
			$memo = "Link:".$link."";
			$code = $linkModal->delete(intval($linkId));
			if($code!=1)throw new PDOException();
			$record = Factory::createRecord(1,0,Tool::getDate(),$memo);
			$code = $recordModal->add($record);
			if($code!=1)throw new PDOException();
			$pdo->commit();
		}catch(PDOException $e){
			$pdo->rollBack();
		}
		$msg = "";
		if($code==1){
			$msg = "删除链接成功";
		}else{
			$msg = Tool::getErrorMsg($code);
			$code = 0;
		}
		echo json_encode(array(
			"msg"=>$msg,
			"result"=>$code
			),JSON_UNESCAPED_UNICODE);
	}

?>