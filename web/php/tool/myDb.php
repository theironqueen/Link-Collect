<?php
include_once(BASE_DIR."/tool/tool.php");
class MyDb{
	private $pdo;

	private $dsn,$user,$password;

	public function __construct(){
		$this->dsn = 'mysql:dbname=mypage2;host=localhost';
		$this->user = "tuzi";
		$this->password = "";
	}
	public function connect(){
		if(empty($this->pdo)){
			try{
				$this->pdo = new pdo($this->dsn,$this->user,$this->password);
				$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$this->pdo->exec('set names utf8');  
				//Tool::output("pdo connect success");
				return $this->pdo;
			}catch(PDOException $e){
				//Tool::output("pdo connect error".$e->getMessage());
				return null;
			}
			//Tool::output($this->pdo);
		}else{
			//Tool::output("pdo has connect");
			return $this->pdo;
		}		
	}
	// public function close(){
	// 	if(!empty($this->pdo)){
	// 		$this->pdo->close();
	// 		$this->pdo = null;
	// 		echo "pdo close is null"."\r\n";
	// 	}
	// }
}
// $db = new MyDb();
// $my = $db->connect();
// $stmt = $my->prepare("insert into tab (tab_name,tab_parent_id,tab_time) values (?,?,?)");
// $stmt->bind_param('sis',$name,$id,$time);
// $name = "123";
// $id = null;
// $time = "1999-11-11 19:55:39";
// $stmt->execute();
// echo $stmt->affected_rows;
// $stmt->close();
// $my->close();
?>
