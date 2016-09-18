<?php
include_once(BASE_DIR."/tool/tool.php");
class MyDb{
	private $pdo;

	private $dsn,$user,$password;

	public function __construct(){
		$this->dsn = 'mysql:dbname=yourdatabase;host=localhost';
		$this->user = "username";
		$this->password = "password";
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
}
?>