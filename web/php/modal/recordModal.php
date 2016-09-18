<?php
include_once(BASE_DIR."/tool/tool.php");
include_once(Tool::getFilePath("tFactory"));
include_once(Tool::getFilePath("bRecord"));
include_once(Tool::getFilePath("tDb"));
class RecordModal{
	private $pdo;

	public function __construct($v){
		$this->pdo = $v;
	}
	/**
	 * 输入record类数据，将其插入数据库中
	 * @param [Record] $record 
	 * @return Int  为1表示成功， 其他的为错误码
	 */
	public function add($record){
		$sql = "insert into record (record_time,record_type,record_object,record_memo) values (?,?,?,?)";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(1,$time);
		$stmt->bindParam(2,$type,PDO::PARAM_INT);
		$stmt->bindParam(3,$object,PDO::PARAM_INT);
		$stmt->bindParam(4,$memo);

		$time = $record->recordTime;
		$type = $record->recordType;
		$object = $record->recordObject;
		$memo = $record->recordMemo;

		$code = 1;
		try{
			$stmt->execute();
		}catch(PDOException $e){
			$code = -7;
		}
		return $code;
	}
	/**
	 * 查找record信息
	 * @param  [type] $searchType   record类型 为-1时表示全部类型
	 * @param  [type] $searchObject record对象 为-1时表示全部对象
	 * @param  [type] $startDate    起始时间	为空时表示所有时间
	 * @param  [type] $endDate      结束时间	为空时表示所有时间
	 * @param  [type] $page         页数	
	 * @param  [type] $pageNum      每页数量
	 * @return [type]               record的array
	 */
	public function findRecord($searchType,$searchObject,$startDate,$endDate,$page,$pageNum){
		$sql = "select * from record ";
		$paramList = array();
		$start = ($page-1)*$pageNum;
		if($searchType!=-1){
			$paramList["searchType"] = "record_type=:searchType";
		}
		if($searchObject!=-1){
			$paramList["searchObject"] = "record_object=:searchObject";
		}
		if($startDate!=""){
			$paramList["startDate"] = "record_time>=:startDate";
			$paramList["endDate"] = "record_time<=:endDate";
		}
		//整合sql
		$i = 0;
		foreach($paramList as $k=>$v){
			if($i==0){
				$sql = $sql." where ".$v." ";
			}else{
				$sql = $sql." and ".$v." ";
			}
			$i++;
		}
		$sql = $sql."order by record_time desc limit :start,:pageNum";
		//echo $sql."\r\n";
		//绑定
		$stmt = $this->pdo->prepare($sql);
		foreach($paramList as $k=>$v){
			$stmt->bindParam(":".$k,$$k);
		}
		$stmt->bindParam(":start",$start,PDO::PARAM_INT);
		$stmt->bindParam(":pageNum",$pageNum,PDO::PARAM_INT);

		$stmt->bindColumn('record_id',$id);
		$stmt->bindColumn('record_type',$type);
		$stmt->bindColumn('record_object',$object);
		$stmt->bindColumn('record_time',$time);
		$stmt->bindColumn('record_memo',$memo);
		$stmt->execute();
		$result = array();
		while($stmt->fetch(PDO::FETCH_BOUND)){
			$record = Factory::createRecord($id,$type,$object,$time,$memo);
			array_push($result,$record);
		}
		return $result;
	}
	public function findRecordNumberHelp($searchType,$searchObject,$startDate,$endDate){
		$sql = "select count(*) from record ";
		$paramList = array();
		if($searchType!=-1){
			$paramList["searchType"] = "record_type=:searchType";
		}
		if($searchObject!=-1){
			$paramList["searchObject"] = "record_object=:searchObject";
		}
		if($startDate!=""){
			$paramList["startDate"] = "record_time>=:startDate";
			$paramList["endDate"] = "record_time<=:endDate";
		}
		//整合sql
		$i = 0;
		foreach($paramList as $k=>$v){
			if($i==0){
				$sql = $sql." where ".$v." ";
			}else{
				$sql = $sql." and ".$v." ";
			}
			$i++;
		}
		$stmt = $this->pdo->prepare($sql);
		foreach($paramList as $k=>$v){
			$stmt->bindParam(":".$k,$$k);
		}
		$stmt->bindColumn(1,$number);
		$stmt->execute();
		$stmt->fetch(PDO::FETCH_BOUND);
		return $number;
	}
}

// $myDb = new MyDb();
// $conn = $myDb->connect();

// $rModal = new RecordModal($conn);
// $value = $rModal->findRecord(1,1,"2016-09-06 00:00:00","2016-09-06 23:59:59",1,20);
// $nn = $rModal->findRecordNumberHelp(1,1,"2016-09-06 00:00:00","2016-09-06 23:59:59");
// Tool::outputArray($value);
// Tool::output($nn);

?>