<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/11/11
 * Time: 11:00
 */
$get_keyword = $_POST['keyword'];
if(empty($get_keyword)){
   echo 0;
   exit;
}
include 'Lib/MySQL/MySQL.class.php';
$sql = "SELECT * FROM `mobile_baidu` WHERE `keyword` = '".$get_keyword."'";
$db = new MySQL();
$res = $db->query($sql);
$ranks = 'no';
foreach($res as $key=>$val){
	$urls[] = $val['url'];
	if($val['rank'] != 'no'){
		unset($ranks);
		$ranks[] = $val['rank'];
	}
}

if($ranks != 'no') {
	$rank_average = array_sum($ranks) / count($ranks);
	$rank_average = (int)$rank_average;
}else{
	$rank_average = 'no';
}
$url_cou = array_count_values($urls);
$url_max = array_search(max($url_cou),$url_cou); //max keyword's url

$returnRes = array('average'=>$rank_average,'maxUrl'=>$url_max);
$jsonRes = json_encode($returnRes);
echo $jsonRes;