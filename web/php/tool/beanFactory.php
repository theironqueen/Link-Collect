<?php
include_once(BASE_DIR."/tool/tool.php");
include_once(Tool::getFilePath("bTab"));
include_once(Tool::getFilePath("bLink"));
include_once(Tool::getFilePath("bRecord"));
include_once(Tool::getFilePath("bLinkTimes"));
class Factory{
	/**
	 * 输入 4参数为 $tab_id, tabName, tabTime, tabParentId
	 * 输入 3参数为 $tabName, tabTime, tabParentId
	 * 输入 0参数为获取一个空的对象
	 * @return [Tab] 返回一个标签
	 */
	public static function createTab(){
		$tab = new Tab();
		$num = func_num_args();
		$param = func_get_args();
		if($num == 3){
			$tab->tabName = $param[0];
			$tab->tabTime = $param[1];
			$tab->tabParentId = $param[2];
		}else if ($num == 4){
			$tab->tabId = $param[0];
			$tab->tabName = $param[1];
			$tab->tabTime = $param[2];
			$tab->tabParentId = $param[3];
		}
		return $tab;
	}
	/**
	 * 输入 5参数为 $linkId, linkName, linkTime, linkAddress, tabId
	 * 输入 4参数为 $linkName, linkTime, linkAddress, tabId
	 * 输入 0参数为获取一个空的对象
	 * @return [Tab] 返回一个链接
	 */
	public static function createLink(){
		$link = new Link();
		$num = func_num_args();
		$param = func_get_args();
		if($num == 4){
			$link->linkName = $param[0];
			$link->linkTime = $param[1];
			$link->linkAddress = $param[2];
			$link->tabId = $param[3];
		}else if ($num == 5){
			$link->linkId = $param[0];
			$link->linkName = $param[1];
			$link->linkTime = $param[2];
			$link->linkAddress = $param[3];
			$link->tabId = $param[4];
		}
		return $link;
	}
	/**
	 * 获取一个Record对象
	 * 输入5个参数为 $recordId, recordType, recordObject, recordTime, recordMemo
	 * 输入4个参数为 $recordType, recordObject, recordTime, recordMemo
	 * 输入0个参数 返回一个空对象
	 * @return [type] [description]
	 */
	public static function createRecord(){
		$record = new Record();
		$num = func_num_args();
		$param = func_get_args();
		if($num == 4){
			$record->recordType = $param[0];
			$record->recordObject = $param[1];
			$record->recordTime = $param[2];
			$record->recordMemo = $param[3];
		}else if ($num == 5){
			$record->recordId = $param[0];
			$record->recordType = $param[1];
			$record->recordObject = $param[2];
			$record->recordTime = $param[3];
			$record->recordMemo = $param[4];
		}
		return $record;
	}
	/**
	 * 获取一个linkTimes对象
	 * 输入2参数为 linkId clickTime
	 * 输入0参数 返回一个空对象
	 * @return [type] [description]
	 */
	public static function createLinkTimes(){
		$linkTimes = new LinkTimes();
		$num = func_num_args();
		$param = func_get_args();
		if($num==2){
			$linkTimes->linkId = $param[0];
			$linkTimes->clickTime = $param[1];
		}
		return $linkTimes;
	}

	/**
	 * 根据输入的link的array获取其id组成的字符串
	 * @param  [type] $array 
	 * @return [type]        idString
	 */
	public static function getIdList($array,$name){
		$num = count($array);
		$string = "";
		for($i=0;$i<$num;$i++){
			$obj = $array[$i];
			$string = $string.$obj->$name.",";
		}
		if($num!=0)
			$string = substr($string,0,-1);
		return $string;
	}
}
?>