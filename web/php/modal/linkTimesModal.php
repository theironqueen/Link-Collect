<?php
include_once(BASE_DIR."/tool/tool.php");
include_once(Tool::getFilePath("tFactory"));
include_once(Tool::getFilePath("bLinkTimes"));
include_once(Tool::getFilePath("tDb"));
class LinkTimesModal{
	private $pdo;

	public function __construct($v){
		$this->pdo = $v;
	}
	/**
	 * 输入record类数据，将其插入数据库中
	 * @param [Record] $record 
	 * @return Int  为1表示成功， 其他的为错误码
	 */
	public function add($linkTimes){
		$sql = "insert into linkTimes (link_id,click_time) values (?,?)";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(1,$id,PDO::PARAM_INT);
		$stmt->bindParam(2,$time);
		$time = $linkTimes->clickTime;
		$id = $linkTimes->linkId;
		$code = 1;
		try{
			$stmt->execute();
		}catch(PDOException $e){
			$code = -8;
		}
		return $code;
	}
	public function searchClick($list){
		if($list==""){
			return array();
		}
		$sql = "select link_id,count(*) from linktimes where link_id in (".$list.") group by link_id";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindColumn(1,$id);
		$stmt->bindColumn(2,$number,PDO::PARAM_INT);
		$stmt->execute();
		$result = array();
		while($stmt->fetch(PDO::FETCH_BOUND)){
			$result[$id] = $number;
		}
		return $result;
	}
}

// $myDb = new MyDb();
// $conn = $myDb->connect();

// $rModal = new LinkTimesModal($conn);
// $rr = $rModal->searchClick("1,2,3,4");
// print_r($rr);
// $lts = Factory::createLinkTimes(13,Tool::getDate());
// for($i=1;$i<60;$i++){
// 	$lts->linkId = $i;
// 	$rand = rand(1,100);
// 	for($j=0;$j<$rand;$j++){
// 		$rModal->add($lts);
// 	}
// }

?>