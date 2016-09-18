<?php
	include_once(BASE_DIR."/tool/tool.php");
	include_once(Tool::getFilePath("tDb"));
	include_once(Tool::getFilePath("tFactory"));
	include_once(Tool::getFilePath("mFile"));
	include_once(Tool::getFilePath("mLink"));
	include_once(Tool::getFilePath("mTab"));
	include_once(Tool::getFilePath("mRecord"));
	include_once(Tool::getFilePath("bLink"));
	include_once(Tool::getFilePath("bTab"));
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
		$tabList = array();
		$totalNumber = 0;
		if($searchContent==""){
			$tabList = $tabModal->findAll($page,$pageNum);
			$totalNumber = $tabModal->findAllNumberHelp();
		}else if($searchType=="0"){
			$tab = $tabModal->findTabById($searchContent);
			if($tab!=null){
				array_push($tabList,$tab);
				$totalNumber = 1;
			}
		}else if ($searchType == "1"){
			$tabList = $tabModal->findTabByName($searchContent,$page,$pageNum);
			$totalNumber = $tabModal->findTabByNameNumberHelp($searchContent);
		}else if ($searchType=="2"){
			$tabList = $tabModal->findSubTab($searchContent,$page,$pageNum);
			$totalNumber = $tabModal->findSubTabNumberHelp($searchContent);
		}
		//获取其他内容
		$infos = array();
		$number = count($tabList);
		$tabIdStr = $tabModal->getTabIdList($tabList);
		$parentNames = $tabModal->findTabNameByIdList($tabIdStr);

		for($i=0;$i<$number;$i++){
			$tab = $tabList[$i];
			$tabNumber = $tabModal->findSubTabNumberHelp($tab->tabId);
			$linkNumber = $linkModal->findLinkByTabIdNumberHelp($tab->tabId);
			$parentId = $tab->tabParentId==null?"":$tab->tabParentId;
			$parentName = $parentId==""?"无":$parentNames[$parentId];
			$obj = array("tab_id"=>$tab->tabId,"tab_name"=>$tab->tabName,"tab_parent_id"=>$parentId,
						"tab_parent_name"=>$parentName,"tab_number"=>$tabNumber,"link_number"=>$linkNumber,
						"tab_time"=>$tab->tabTime);
			array_push($infos,$obj);
		}

		echo json_encode(array(
			"draw" => $draw,
			"recordsFiltered" => $totalNumber,
			"data" => $infos
			),JSON_UNESCAPED_UNICODE);


	}
	function getParent(){
		$tabParentId = $_POST["tab_parent_id"];
		if($tabParentId==""){
			$code = 1;
			$msg = "无";
			echo json_encode(array(
			"msg"=>$msg,
			"result"=>$code
			),JSON_UNESCAPED_UNICODE);
			return;
		}
		$tabId = $_POST["tab_id"];
		$myDb = new MyDb();
		$pdo = $myDb->connect();
		$tabModal = new TabModal($pdo);
		$fileModal = new FileModal($pdo);
		$code = 1;
		$msg;
		if($tabId == $tabParentId){
			$code = 0;
			$msg = "父标签id不能与当前标签id相同";
			echo json_encode(array(
			"msg"=>$msg,
			"result"=>$code
			),JSON_UNESCAPED_UNICODE);
			return;
		}
		$string = $fileModal->fileRead(BASE_DIR."/subList.txt");
		$tabList = json_decode($string);
		$subStr = $tabList->$tabId;

		if(strpos($subStr,strval($tabParentId))!==false){
			$code = 0;
			$msg = "父标签id不能是当前标签的所有下级标签的id";
			echo json_encode(array(
			"msg"=>$msg,
			"result"=>$code
			),JSON_UNESCAPED_UNICODE);
			return;
		}

		$tab = $tabModal->findTabById($tabParentId);
		if($tab==null){
			$msg = "该标签不存在";
			$code = 0;
		}else{
			$msg = $tab->tabName;
		}
		echo json_encode(array(
			"msg"=>$msg,
			"result"=>$code
			),JSON_UNESCAPED_UNICODE);

	}
	function edit(){
		$tabId = $_POST["tab_id"];
		$tabName = $_POST["tab_name"];
		$parentId = $_POST["tab_parent_id"];
		if($parentId=="")
			$parentId = null;
		$myDb = new MyDb();
		$pdo = $myDb->connect();
		$tabModal = new TabModal($pdo);
		$fileModal = new FileModal($pdo);
		$recordModal = new RecordModal($pdo);
		$code = 1;
		$tab = $tabModal->findTabById($tabId);
		try{
			$pdo->beginTransaction();
			$memo = "oldTab:".$tab." ";
			$parentChangeFlag = ($tab->tabParentId==$parentId);
			$tab->tabName = $tabName;
			$tab->tabParentId = $parentId;
			$code = $tabModal->edit($tab);
			if($code!=1)throw new PDOException();
			$memo = $memo." newTab:".$tab;

			$record = Factory::createRecord($parentChangeFlag?2:3,1,Tool::getDate(),$memo);
			$code = $recordModal->add($record);
			if($code!=1)throw new PDOException();
			$code = $fileModal->tabFileCreate();
			if($code!=1)throw new PDOException();
			$pdo->commit();
		}catch(PDOException $e){
			$pdo->rollBack();
		}
		$msg = "";
		if($code==1){
			$msg = "修改标签成功";
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
		$tabId = $_POST["tab_id"];

		$myDb = new MyDb();
		$pdo = $myDb->connect();
		$tabModal = new TabModal($pdo);
		$linkModal = new LinkModal($pdo);
		$fileModal = new FileModal($pdo);
		$recordModal = new RecordModal($pdo);
		$code = 1;
		$tab = $tabModal->findTabById($tabId);
		try{
			$pdo->beginTransaction();
			$memo = "Tab:".$tab."\r\n";
			$string = $fileModal->fileRead(BASE_DIR."/subList.txt");
			$tabList = json_decode($string);
			$subStr = $tabList->$tabId;
			$subList = $tabModal->findSubTab($subStr);
			if($subStr==""){
				$subStr = strval($tabId);
			}else{
				$subStr = strval($tabId).",".$subStr;
			}
			$linkList = $linkModal->findLinkByTabId($subStr);
			//tab信息和link信息存入modal中
			$number = count($subList);
			for($i=0;$i<$number;$i++){
				$memo = $memo."subTab:".$subList[$i]."\r\n";
			}
			$number = count($linkList);
			for($i=0;$i<$number;$i++){
				$memo = $memo."subLink:".$linkList[$i]."\r\n";
			}
			$code = $tabModal->delete(intval($tabId));
			if($code!=1)throw new PDOException();

			$record = Factory::createRecord(1,1,Tool::getDate(),$memo);
			$code = $recordModal->add($record);
			if($code!=1)throw new PDOException();
			$code = $fileModal->tabFileCreate();
			if($code!=1)throw new PDOException();
			$pdo->commit();
		}catch(PDOException $e){
			$pdo->rollBack();
		}
		$msg = "";
		if($code==1){
			$msg = "删除标签成功";
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