<?php
include_once(BASE_DIR."/tool/tool.php");
include_once(Tool::getFilePath("tFactory"));
include_once(Tool::getFilePath("bTab"));
include_once(Tool::getFilePath("tDb"));
class TabModal{
	private $pdo;

	public function __construct($v){
		$this->pdo = $v;
	}
	/**
	 * 输入tab类数据，将其插入数据库中
	 * @param [Tab] $tab 其中tab没有tabId,pId必须在tab表中存在，或者为null
	 * @return Int  为1表示成功， 其他的为错误码
	 */
	public function add($tab){
		$sql = "insert into tab (tab_name,tab_parent_id,tab_time) values (?,?,?)";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(1,$name);
		$stmt->bindParam(2,$pId,PDO::PARAM_INT);
		$stmt->bindParam(3,$time);

		$name = $tab->tabName;
		$pId = $tab->tabParentId;
		$time = $tab->tabTime;
		$code = 1;
		try{
			$stmt->execute();
		}catch(PDOException $e){
			$code = -1;
		}
		return $code;
	}
	/**
	 * 根据标签id获取标签信息
	 * @param  [int] $tabId 
	 * @return 查询的标签信息，未查到为null 查到为Tab
	 */
	public function findTabById($tabId){
		$sql = "select * from tab where tab_id = ?";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(1,$tabId);
		$stmt->bindColumn('tab_id',$tab_id);
		$stmt->bindColumn('tab_name',$tab_name);
		$stmt->bindColumn('tab_parent_id',$tab_parent_id);
		$stmt->bindColumn('tab_time',$tab_time);
		$stmt->execute();
		$result = null;
		while($stmt->fetch(PDO::FETCH_BOUND)){
			$result = Factory::createTab($tab_id,$tab_name,$tab_time,$tab_parent_id);
		}
		return $result;
	}

	public function findTabNameByIdList($tabListStr){
		$sql = "select tab_id,tab_name from tab where tab_id in (".$tabListStr.")";
		if($tabListStr==""){
			return array();
		}
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindColumn('tab_id',$tab_id);
		$stmt->bindColumn('tab_name',$tab_name);
		$stmt->execute();
		$result = array();
		while($stmt->fetch(PDO::FETCH_BOUND)){
			$result[$tab_id] = $tab_name;
		}
		return $result;
	}
	public function getTabIdList($tabList){
		$number = count($tabList);
		if($number==0) return "";
		$result = "";
		for($i=0;$i<$number;$i++){
			$tab = $tabList[$i];
			$parentId = $tab->tabParentId;
			if($parentId!=null)
				$result = $result.$parentId.",";
		}
		if($result!="")
			$result = substr($result,0,-1);
		return $result;
	}
	/**
	 * 查找全部标签
	 * @param  [type] $page    当前页数
	 * @param  [type] $pageNum 每页数量
	 * @return [type]          Tab的array
	 */
	public function findAll($page,$pageNum){
		$sql = "select * from tab order by tab_time desc limit ?,?";
		$stmt = $this->pdo->prepare($sql);
		$start = ($page-1)*$pageNum;

		$stmt->bindParam(1,$start,PDO::PARAM_INT);
		$stmt->bindParam(2,$pageNum,PDO::PARAM_INT);

		$stmt->bindColumn('tab_id',$tab_id);
		$stmt->bindColumn('tab_name',$tab_name);
		$stmt->bindColumn('tab_parent_id',$tab_parent_id);
		$stmt->bindColumn('tab_time',$tab_time);
		$stmt->execute();
		$result = array();
		while($stmt->fetch(PDO::FETCH_BOUND)){
			$tab = Factory::createTab($tab_id,$tab_name,$tab_time,$tab_parent_id);
			array_push($result,$tab);
		}
		return $result;
	}
	/**
	 * 查找全部标签
	 * @return [type]    全部标签的数量
	 */
	public function findAllNumberHelp(){
		$sql = "select count(*) from tab";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindColumn(1,$number);
		$stmt->execute();
		$stmt->fetch(PDO::FETCH_BOUND);
		return $number;
	}
	/**
	 * 根据标签名查找标签
	 * @param  [type] $tabName 标签名
	 * @param  [type] $page    页数
	 * @param  [type] $pageNum 每页数量
	 * @return [type]          Tab的array
	 */
	public function findTabByName(){
		$num = func_num_args();
		$arr = func_get_args();
		$stmt;
		$tabName = "%".$arr[0]."%";
		if($num == 1){
			$sql = "select * from tab where tab_name like ?";
			$stmt = $this->pdo->prepare($sql);
			$stmt->bindParam(1,$tabName);
		}else if ($num == 3){
			$sql = "select * from tab where tab_name like ? limit ?,?";
			$pageNum = $arr[2];
			$start = ($arr[1]-1)*$pageNum;
			$stmt = $this->pdo->prepare($sql);
			$stmt->bindParam(1,$tabName);
			$stmt->bindParam(2,$start,PDO::PARAM_INT);
			$stmt->bindParam(3,$pageNum,PDO::PARAM_INT);
		}else{
			return array();
		}
		$stmt->bindColumn('tab_id',$tab_id);
		$stmt->bindColumn('tab_name',$tab_name);
		$stmt->bindColumn('tab_parent_id',$tab_parent_id);
		$stmt->bindColumn('tab_time',$tab_time);
		$stmt->execute();
		$result = array();
		while($stmt->fetch(PDO::FETCH_BOUND)){
			$tab = Factory::createTab($tab_id,$tab_name,$tab_time,$tab_parent_id);
			array_push($result,$tab);
		}
		return $result;
	}
	/**
	 * [findTabByNameNumberHelp  查找符合姓名的标签的数量
	 * @return [type]          [description]
	 */
	public function findTabByNameNumberHelp($tabName){
		$sql = "select count(*) from tab where tab_name like ?";
		$stmt = $this->pdo->prepare($sql);
		$tabName = "%".$tabName."%";
		$stmt->bindParam(1,$tabName);
		$stmt->bindColumn(1,$number);
		$stmt->execute();
		$stmt->fetch(PDO::FETCH_BOUND);
		return $number;
	}
	/**
	 * 查找给定标签id列的 子标签信息
	 * 1参数为$tabIdList  此时查找全部子标签信息
	 * 3参数为 $tabIdList $page $pageNumber  此时分页查找
	 * @param  [type] $tabIdList 子标签序列 null查找顶级，或1,2,3
	 * @return [type]           tab的list
	 */
	public function findSubTab(){
		$num = func_num_args();
		$arr = func_get_args();
		$sql;
		$stmt;
		$list = $arr[0];
		if($list==""&&isset($list)){
			return array();
		}
		if($num==1&&$list!=null){
			$sql = "select * from tab where tab_parent_id in (".$list.") order by tab_time desc";
			$stmt = $this->pdo->prepare($sql);
		}else if($num==3&&$list!=null){
			$sql = "select * from tab where tab_parent_id in (".$list.") order by tab_time desc limit ?,?";
			$stmt = $this->pdo->prepare($sql);
			$pageNum = $arr[2];
			$start = ($arr[1]-1)*$pageNum;

			$stmt->bindParam(1,$start,PDO::PARAM_INT);
			$stmt->bindParam(2,$pageNum,PDO::PARAM_INT);
		}else if($num==1&&$list==null){
			$sql = "select * from tab where tab_parent_id is null order by tab_time desc";
			$stmt = $this->pdo->prepare($sql);
		}else if($num==3&&$list==null){
			$sql = "select * from tab where tab_parent_id is null order by tab_time desc limit ?,?";
			$stmt = $this->pdo->prepare($sql);
			$pageNum = $arr[2];
			$start = ($arr[1]-1)*$pageNum;

			$stmt->bindParam(1,$start,PDO::PARAM_INT);
			$stmt->bindParam(2,$pageNum,PDO::PARAM_INT);
		}else{
			return array();
		}
		
		$stmt->bindColumn('tab_id',$tab_id);
		$stmt->bindColumn('tab_name',$tab_name);
		$stmt->bindColumn('tab_parent_id',$tab_parent_id);
		$stmt->bindColumn('tab_time',$tab_time);
		$stmt->execute();
		$result = array();
		while($stmt->fetch(PDO::FETCH_BOUND)){
			$tab = Factory::createTab($tab_id,$tab_name,$tab_time,$tab_parent_id);
			array_push($result,$tab);
		}
		return $result;
	}

	public function findSubTabNumberHelp($tabIdList){
		$sql;$stmt;
		if($tabIdList==""&&isset($tabIdList)){
			return 0;
		}
		if($tabIdList==null){
			$sql = "select count(*) from tab where tab_parent_id is null";
			$stmt = $this->pdo->prepare($sql);
		}else{
			$sql = "select count(*) from tab where tab_parent_id in (".$tabIdList.")";
			$stmt = $this->pdo->prepare($sql);
			$stmt->bindParam(1,$tabIdList);
		}
		$stmt->bindColumn(1,$number);
		$stmt->execute();
		$stmt->fetch(PDO::FETCH_BOUND);
		return $number;
	}
	public function edit($tab){
		$sql = "update tab set tab_name=?, tab_parent_id=? where tab_id=?";
		$stmt = $this->pdo->prepare($sql);

		$stmt->bindParam(1,$name);
		$stmt->bindParam(2,$pId,PDO::PARAM_INT);
		$stmt->bindParam(3,$id);

		$name = $tab->tabName;
		$pId = $tab->tabParentId;
		$id = $tab->tabId;
		$code = 1;
		try{
			$stmt->execute();
		}catch(PDOException $e){
			$code = -2;
		}
		return $code;
	}
	public function delete($tabId){
		$sql = "delete from tab where tab_id=?";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(1,$tabId,PDO::PARAM_INT);

		$code = 1;
		try{
			$stmt->execute();
		}catch(PDOException $e){
			$code = -3;
		}
		return $code;
	}
}

// $myDb = new MyDb();
// $conn = $myDb->connect();

// $tModal = new TabModal($conn);
// $value = $tModal->findSubTab("2,16,17",1,20);
// $nn = $tModal->findSubTabNumberHelp("2,16,17");
// Tool::outputArray($value);
// Tool::output($nn);

// $tab = new Tab();
// $tab->tabName = "test3";
// $tab->tabId = 41;
// $tab->tabParentId = 16;
// echo $tModal->delete(40);
// for($i=0;$i<20;$i++){
// 	$tModal->addTab($tab);
// }

?>