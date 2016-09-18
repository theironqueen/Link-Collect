<?php
class Tool{
	private static $filePath = array
	(
		"bLink"=>"\\bean\\Link.php",
		"bTab"=>"\\bean\\Tab.php",
		"bRecord"=>"\\bean\\Record.php",
		"bLinkTimes"=>"\\bean\\LinkTimes.php",
		"cbIndex"=>"\\control\\backIndex.php",
		"cLink"=>"\\control\\myLink.php",
		"cTab"=>"\\control\\myTab.php",
		"cRecord"=>"\\control\\myRecord.php",
		"coIndex"=>"\\control\\onIndex.php",
		"mLink"=>"\\modal\\linkModal.php",
		"mTab"=>"\\modal\\tabModal.php",
		"mRecord"=>"\\modal\\recordModal.php",
		"mLinkTimes"=>"\\modal\\linkTimesModal.php",
		"mFile"=>"\\modal\\fileModal.php",
		"tDb"=>"\\tool\\myDb.php",
		"tFactory"=>"\\tool\\beanFactory.php",
	);

	private static $errs = array
	(
		"-1"=>"标签信息添加错误",
		"-2"=>"标签信息修改错误",
		"-3"=>"标签信息删除错误",
		"-4"=>"链接信息添加错误",
		"-5"=>"链接信息修改错误",
		"-6"=>"链接信息删除错误",
		"-7"=>"记录信息添加错误",
		"-8"=>"链接点击信息添加错误",
		"-9"=>"文件打开错误"
	);

	public static function getFilePath($name){
		if(isset(Self::$filePath[$name]))
			return Self::$filePath[$name];
		else
			return '';
	}
	public static function output($string){
		echo $string."\r\n";
	}
	public static function outputArray($arr){
		foreach($arr as $key=>$val){
			echo "key:".$key." value:".$val."\r\n";
		}
	}
	public static function outputJson($arr){
		echo json_encode($arr)."\r\n";
	}
	public static function getDate(){
		return date('Y-m-d H:i:s',time());
	}
	public static function getErrorMsg($errCode){
		if(isset(Self::$errs[$errCode]))
			return Self::$errs[$errCode];
		else
			return '错误码不存在!';
	}
}
?>