<?php
/* @benhuang;time:140812; */
 
require_once './class/mysqlConn.class.php';
function getIP()
{
global $ip;
if (getenv("HTTP_CLIENT_IP"))
$ip = getenv("HTTP_CLIENT_IP");
else if(getenv("HTTP_X_FORWARDED_FOR"))
$ip = getenv("HTTP_X_FORWARDED_FOR");
else if(getenv("REMOTE_ADDR"))
$ip = getenv("REMOTE_ADDR");
else $ip = "Unknow";
return $ip;
}
$ip = getIP();
$fUrl = trim($_GET['furl']); //跨域
$url = trim($_GET['url']);
$murl = trim($_GET['murl']);
//
$sqls = "SELECT `baiduUrl` FROM `grabpath` WHERE `ip` = '".$ip."' ORDER BY id DESC";
$res = mysql_query($sqls);
$urlold = mysql_fetch_assoc($res);
$urloldf = $urlold['baiduUrl'];
if($urloldf != $fUrl){
$sql = "INSERT INTO `grabpath` (`date`,`baiduUrl`,`url`,`murl`,`ip`) VALUES(now(),'".$fUrl."','".$url."','".$murl."','".$ip."')"; 
mysql_query($sql);
}
mysql_close($mysqlC); 
unset($ip);
unset($fUrl);
unset($url);
$callback = $_GET['callback']; //验证
echo $callback.'('.json_encode($_GET).')';
 
 ?>