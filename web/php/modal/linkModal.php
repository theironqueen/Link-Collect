<?php
include_once(BASE_DIR."/tool/tool.php");
include_once(Tool::getFilePath("tFactory"));
include_once(Tool::getFilePath("bLink"));
include_once(Tool::getFilePath("tDb"));
class LinkModal{
	private $pdo;

	public function __construct($v){
		$this->pdo = $v;
	}

	/**
	 * 输入link类数据，将其插入数据库中
	 * @param [Tab] $tab 其中tab没有tabId,pId必须在tab表中存在，或者为null
	 * @return Int  为1表示成功， 其他的为错误码
	 */
	public function add($link){
		$sql = "insert into link (link_name,link_address,link_time,tab_id) values (?,?,?,?)";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(1,$name);
		$stmt->bindParam(2,$address);
		$stmt->bindParam(3,$time);
		$stmt->bindParam(4,$tabId,PDO::PARAM_INT);

		$name = $link->linkName;
		$address = $link->linkAddress;
		$time = $link->linkTime;
		$tabId = $link->tabId;
		$code = 1;
		try{
			$stmt->execute();
		}catch(PDOException $e){
			$code = -4;
		}
		return $code;
	}
	/**
	 * 根据链接id获取链接信息
	 * @param  [type] $linkId 
	 * @return [type] 当查询到链接信息时为link，未查到时为null
	 */
	public function findLinkById($linkId){
		$sql = "select * from link where link_id = ?";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(1,$linkId);

		$stmt->bindColumn('link_id',$link_id);
		$stmt->bindColumn('link_name',$link_name);
		$stmt->bindColumn('link_address',$link_address);
		$stmt->bindColumn('link_time',$link_time);
		$stmt->bindColumn('tab_id',$tab_id);

		$stmt->execute();
		$result = null;
		while($stmt->fetch(PDO::FETCH_BOUND)){
			$result = Factory::createLink($link_id,$link_name,$link_time,$link_address,$tab_id);
		}
		return $result;
	}
	/**
	 * 查找全部标签
	 * @param  [type] $page    当前页数
	 * @param  [type] $pageNum 每页数量
	 * @return [type]         link的array
	 */
	public function findAll($page,$pageNum){
		$sql = "select * from link order by link_time desc limit ?,?";
		$stmt = $this->pdo->prepare($sql);
		$start = ($page-1)*$pageNum;

		$stmt->bindParam(1,$start,PDO::PARAM_INT);
		$stmt->bindParam(2,$pageNum,PDO::PARAM_INT);

		$stmt->bindColumn('link_id',$link_id);
		$stmt->bindColumn('link_name',$link_name);
		$stmt->bindColumn('link_address',$link_address);
		$stmt->bindColumn('link_time',$link_time);
		$stmt->bindColumn('tab_id',$tab_id);
		$stmt->execute();
		$result = array();
		while($stmt->fetch(PDO::FETCH_BOUND)){
			$link = Factory::createLink($link_id,$link_name,$link_time,$link_address,$tab_id);
			array_push($result,$link);
		}
		return $result;
	}
	/**
	 * 查找全部标签
	 * @return [type]    全部链接的数量
	 */
	public function findAllNumberHelp(){
		$sql = "select count(*) from link";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindColumn(1,$number);
		$stmt->execute();
		$stmt->fetch(PDO::FETCH_BOUND);
		return $number;
	}
	/**
	 * 根据标签名查找标签
	 * @param  [type] $linkName 链接名
	 * @param  [type] $page    页数
	 * @param  [type] $pageNum 每页数量
	 * @return [type]          Link的array
	 */
	public function findLinkByName($linkName,$page,$pageNum){
		$sql = "select * from link where link_name like ? limit ?,?";
		$stmt = $this->pdo->prepare($sql);
		$start = ($page-1)*$pageNum;

		$linkName = "%".$linkName."%";
		$stmt->bindParam(1,$linkName);
		$stmt->bindParam(2,$start,PDO::PARAM_INT);
		$stmt->bindParam(3,$pageNum,PDO::PARAM_INT);

		$stmt->bindColumn('link_id',$link_id);
		$stmt->bindColumn('link_name',$link_name);
		$stmt->bindColumn('link_address',$link_address);
		$stmt->bindColumn('link_time',$link_time);
		$stmt->bindColumn('tab_id',$tab_id);
		$stmt->execute();
		$result = array();
		while($stmt->fetch(PDO::FETCH_BOUND)){
			$link = Factory::createLink($link_id,$link_name,$link_time,$link_address,$tab_id);
			array_push($result,$link);
		}
		return $result;
	}
	/**
	 * [findTabByNameNumberHelp  查找符合姓名的链接的数量
	 * @return [type]          [description]
	 */
	public function findLinkByNameNumberHelp($linkName){
		$sql = "select count(*) from link where link_name like ?";
		$stmt = $this->pdo->prepare($sql);
		$linkName = "%".$linkName."%";
		$stmt->bindParam(1,$linkName);
		$stmt->bindColumn(1,$number);
		$stmt->execute();
		$stmt->fetch(PDO::FETCH_BOUND);
		return $number;
	}
	/**
	 * 查询给定标签列表下的链接信息
	 * 1参数为$tabIdList 查找该标签下所有链接信息
	 * 3参数为 $tabIdList $page $pageNum 进行分页查找
	 * @return [type] link的array
	 */
	public function findLinkByTabId(){
		$num = func_num_args();
		$arr = func_get_args();
		$sql;$stmt;
		$list = $arr[0];
		if($list==""&&isset($list)){
			return array();
		}
		if($num==1){
			$sql = "select * from link where tab_id in (".$list.") order by link_time desc";
			$stmt = $this->pdo->prepare($sql);
		}else if($num==3){
			$sql = "select * from link where tab_id in (".$list.") order by link_time desc limit ?,?";
			$stmt = $this->pdo->prepare($sql);
			$pageNum = $arr[2];
			$start = ($arr[1]-1)*$pageNum;
			$stmt->bindParam(1,$start,PDO::PARAM_INT);
			$stmt->bindParam(2,$pageNum,PDO::PARAM_INT);
		}else{
			return array();
		}
		$stmt->bindColumn('link_id',$link_id);
		$stmt->bindColumn('link_name',$link_name);
		$stmt->bindColumn('link_address',$link_address);
		$stmt->bindColumn('link_time',$link_time);
		$stmt->bindColumn('tab_id',$tab_id);
		$stmt->execute();
		$result = array();
		while($stmt->fetch(PDO::FETCH_BOUND)){
			$link = Factory::createLink($link_id,$link_name,$link_time,$link_address,$tab_id);
			array_push($result,$link);
		}
		return $result;

	}
	public function findLinkByTabIdNumberHelp($list){
		if($list==""&&isset($list)){
			return 0;
		}
		$sql = "select count(*) from link where tab_id in (".$list.")";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindColumn(1,$number);
		$stmt->execute();
		$stmt->fetch(PDO::FETCH_BOUND);
		return $number;
	}
	/**
	 * 查询链接通过 tabId串，返回结果按照链接点击次数排序
	 * 3个参数为 $tabList $page $pageNum
	 * 1个参数为 $tabList
	 * @return [type] link串
	 */
	public function findLinkByTabIdOrder(){
		$num = func_num_args();
		$arr = func_get_args();
		if($arr[0]==""){
			return array();
		}
		$sql = "select A.link_id,A.tab_id,A.link_name,A.link_address,A.link_time from (select * from link where tab_id in (".$arr[0].") ) A left join (select link_id,count(1) C from linktimes group by link_id) B on A.link_id=B.link_id order by C desc";
		if($num==3){
			$sql = $sql." limit ?,?";
		}else if($num!=1){
			return array();
		}
		$stmt = $this->pdo->prepare($sql);
		if($num==3){
			$pageNum = $arr[2];
			$start = ($arr[1]-1)*$pageNum;
			$stmt->bindParam(1,$start,PDO::PARAM_INT);
			$stmt->bindParam(2,$pageNum,PDO::PARAM_INT);
		}
		$stmt->bindColumn('link_id',$link_id);
		$stmt->bindColumn('link_name',$link_name);
		$stmt->bindColumn('link_address',$link_address);
		$stmt->bindColumn('link_time',$link_time);
		$stmt->bindColumn('tab_id',$tab_id);
		$stmt->execute();
		$result = array();
		while($stmt->fetch(PDO::FETCH_BOUND)){
			$link = Factory::createLink($link_id,$link_name,$link_time,$link_address,$tab_id);
			array_push($result,$link);
		}
		return $result;

	}
	public function edit($link){
		$sql = "update link set link_name=?, link_address=? where link_id=?";
		$stmt = $this->pdo->prepare($sql);

		$stmt->bindParam(1,$name);
		$stmt->bindParam(2,$address);
		$stmt->bindParam(3,$id);

		$name = $link->linkName;
		$address = $link->linkAddress;
		$id = $link->linkId;
		$code = 1;
		try{
			$stmt->execute();
		}catch(PDOException $e){
			$code = -5;
		}
		return $code;
	}
	public function delete($linkId){
		$sql = "delete from link where link_id=?";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(1,$linkId);

		$code = 1;
		try{
			$stmt->execute();
		}catch(PDOException $e){
			$code = -6;
		}
		return $code;
	}
	

}

//  $myDb = new MyDb();
//  $conn = $myDb->connect();

//  $lModal = new LinkModal($conn);
// $value = $lModal->findLinkByTabIdOrder("1,2,16,17");
// //$nn = $lModal->findLinkByTabIdNumberHelp("1,2,16,17");
// Tool::outputArray($value);
// //Tool::output($nn);

// // $link = Factory::createLink("link11",Tool::getDate(),"www.baidu.com",5);
// // for($i=0;$i<20;$i++){
// // 	$lModal->add($link);
// // }

?>