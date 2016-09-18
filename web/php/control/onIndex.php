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
	include_once(Tool::getFilePath("bRecord"));
	include_once(Tool::getFilePath("bLinkTimes"));
	function search(){
		$tabId = $_POST["tab_id"];
		$myDb = new MyDb();
		$pdo = $myDb->connect();
		$fileModal= new FileModal($pdo);
		$string = $fileModal->fileRead(BASE_DIR."/subList.txt");
		$tabList = json_decode($string);
		$linkModal = new LinkModal($pdo);
		$tabStr = "";
		$links;
		if($tabId!="0"){
			$tabStr = "".$tabId."";
			// 搜索标签及其所有子标签下的链接
			// if($tabList->$tabId!=""){
			// 	$tabStr = $tabStr.",".$tabList->$tabId;
			// }
			$links = $linkModal->findLinkByTabIdOrder($tabStr);
		}else{
			$tempList = get_object_vars($tabList);
			foreach ($tempList as $key=>$value){
				$tabStr = $tabStr.$key.",";
			}
			if($tabStr!="")
				$tabStr = substr($tabStr,0,-1);
			$links = $linkModal->findLinkByTabIdOrder($tabStr,1,88);
		}
		$infos = array();
		$num = count($links);
		for($i=0;$i<$num;$i++){
			$link = $links[$i];
			$obj = array("link_id"=>$link->linkId,"link_name"=>$link->linkName,"link_address"=>$link->linkAddress);
			array_push($infos,$obj);
		}
		echo json_encode(array(
			"result" => "1",
			"tab_id" => $tabId,
			"msg" => $infos
			),JSON_UNESCAPED_UNICODE);

	}
	function addTab(){
		$parentId = $_POST["parent_tab_id"];
		$tabName = $_POST["tab_name"];
		$myDb = new MyDb();
		$pdo = $myDb->connect();
		if($parentId=="0")
			$parentId = null;
		$tab = Factory::createTab($tabName,Tool::getDate(),$parentId);
		$code = 1;
		try{
			$pdo->beginTransaction();
			$tabModal = new TabModal($pdo);
			$recordModal = new RecordModal($pdo);
			$code = $tabModal->add($tab);
			if($code!=1)throw new PDOException();
			$tabId = $pdo->lastInsertId();
			$tab->tabId = $tabId;
			$memo = "Tab:".$tab;
			$record = Factory::createRecord(0,1,Tool::getDate(),$memo);
			$code = $recordModal->add($record);
			if($code!=1)throw new PDOException();
			//添加成功
			$fileModal= new FileModal($pdo);
			$code = $fileModal->tabFileCreate();
			if($code!=1)throw new PDOException();
			$pdo->commit();
		} catch (PDOException $e){
			$pdo->rollBack();
		}
		$msg = "";
		if($code==1){
			$msg = "添加标签成功";
		}else{
			$msg = Tool::getErrorMsg($code);
			$code = 0;
		}
		echo json_encode(array(
			"msg"=>$msg,
			"result"=>$code
			),JSON_UNESCAPED_UNICODE);
	}
	function addLink(){
		$myDb = new MyDb();
		$pdo = $myDb->connect();
		$tabId = $_POST["tab_id"];
		$linkName = $_POST["link_name"];
		$linkAddress = $_POST["link_address"];
		$linkModal = new LinkModal($pdo);
		$recordModal = new RecordModal($pdo);
		$code = 1;
		try{
			$pdo->beginTransaction();
			$link = Factory::createLink($linkName,Tool::getDate(),$linkAddress,$tabId);
			$code = $linkModal->add($link);
			if($code!=1)throw new PDOException();
			$link->linkId = $pdo->lastInsertId();
			$memo = "Link:".$link;
			$record = Factory::createRecord(0,0,Tool::getDate(),$memo);
			$code = $recordModal->add($record);
			if($code!=1)throw new PDOException();
			$pdo->commit();
		}catch(PDOException $e){
			$pdo->rollBack();
		}
		$msg = "";
		if($code==1){
			$msg = "添加链接成功";
		}else{
			$msg = Tool::getErrorMsg($code);
			$code = 0;
		}
		echo json_encode(array(
			"msg"=>$msg,
			"result"=>$code
			),JSON_UNESCAPED_UNICODE);
	}
	function getTabList(){
		$myDb = new MyDb();
		$pdo = $myDb->connect();
		$fileModal= new FileModal($pdo);
		$string = $fileModal->fileRead(BASE_DIR."/nav.txt");
		echo $string;
	}
	function linkClick(){
		$linkId = $_POST["link_id"];
		$myDb = new MyDb();
		$pdo = $myDb->connect();
		$linktimesModal = new LinkTimesModal($pdo);
		$linktime = Factory::createLinkTimes($linkId,Tool::getDate());
		$linktimesModal->add($linktime);
	}
?>