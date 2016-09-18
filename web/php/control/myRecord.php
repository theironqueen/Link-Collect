<?php
	include_once(BASE_DIR."/tool/tool.php");
	include_once(Tool::getFilePath("tDb"));
	include_once(Tool::getFilePath("tFactory"));
	include_once(Tool::getFilePath("mRecord"));
	include_once(Tool::getFilePath("bRecord"));

	function search(){
		$pageNum = $_POST["page_num"];
		$page = $_POST["page"];
		$page = intval($page);
		$pageNum = intval($pageNum);
		$draw = $_POST["draw"];

		$searchType = $_POST["search_type"];
		$searchObject = $_POST["search_object"];
		$startDate = $_POST["search_start_date"];
		$endDate = $_POST["search_end_date"];
		$myDb = new MyDb();
		$pdo = $myDb->connect();

		$recordModal = new RecordModal($pdo);
		$records = $recordModal->findRecord($searchType, $searchObject, $startDate, $endDate, $page, $pageNum);
		$totalNumber = $recordModal->findRecordNumberHelp($searchType, $searchObject, $startDate, $endDate);
		$num = count($records);
		$infos = array();

		for ($i=0;$i<$num;$i++) {
			$record = $records[$i];
			$obj = array("record_id"=>$record->recordId,"record_type"=>$record->recordType,"record_object"=>$record->recordObject,"record_memo"=>$record->recordMemo,"record_time"=>$record->recordTime);
			array_push($infos,$obj);
		}

		echo json_encode(array(
			"draw" => $draw,
			"recordsFiltered" => $totalNumber,
			"data" => $infos
			),JSON_UNESCAPED_UNICODE);

	}

?>