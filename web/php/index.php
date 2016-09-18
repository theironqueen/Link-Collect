<?php
if (!defined("BASE_DIR"))
define("BASE_DIR", $_SERVER['DOCUMENT_ROOT'].'/mypage2/web/php');

header("Content-type: text/html; charset=utf-8");
	$pConfig = array
		(
		"0"=>"./control/onIndex",
		"1"=>"./control/backIndex",
		"2"=>"./control/myLink",
		"3"=>"./control/myTab",
		"4"=>"./control/myRecord"
		);
	$eConfig = array
		(
		"0"=>array("0"=>"search","1"=>"addTab","2"=>"addLink","3"=>"getTabList","4"=>"linkClick"),
		"1"=>array("0"=>"getTab","1"=>"getLink"),
		"2"=>array("0"=>"search","1"=>"edit","2"=>"delete"),
		"3"=>array("0"=>"search","1"=>"edit","2"=>"getParent","4"=>"delete"),
		"4"=>array("0"=>"search")
		);
	$p = $_GET["page"];
	$e = $_GET["event"];
	// $p = "0";
	// $e = "3";
	$page = $pConfig[$p].".php";
	$event = $eConfig[$p][$e];
	include_once($page);
	$event();

?>