<?php
session_start();
if(empty($_SESSION['loginsU'])){
	header("Location:http://www.120gw.com/");
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>benhuang路径抓取</title>
<link href="http://www.120gw.com\benhuang\bootstrap-3.2.0-dist\css\bootstrap.min.css" rel="stylesheet">
</head>
<body style="width:1500px;">
<table class="table table-hover" style="width:1500px;">
<tr style="width:1500px;" >
<th >时间</th>
<th >访问页面</th>
<th >搜索引擎</th>
<th >IP</th>
<th >关键词</th>
</tr>
<?php
/* @benhuang;time:140812; */
require_once '../class/mysqlConn.class.php';

$sql = "SELECT * FROM `grabpath` ORDER BY id DESC"; 
$res = mysql_query($sql);
while ($row = mysql_fetch_assoc($res)){
	//engine
	if($keyEngine = stripos($row['baiduUrl'],'www.sogou.com')){
		//sogou
		$keya = stripos($row['baiduUrl'],'query=');
		$keyb = stripos($row['baiduUrl'],'&',$keya);
		if($keyb != null){
			$keylen = $keyb - $keya;			
			$key = substr($row['baiduUrl'],$keya+6,$keylen-6);
		}else{	
			$key = substr($row['baiduUrl'],$keya+6);
		}
		$Engine = '搜狗PC';
	}elseif($keyEngine = stripos($row['baiduUrl'],'www.baidu.com')){
		//baidu	
		$keya = stripos($row['baiduUrl'],'wd=');
		$keyb = stripos($row['baiduUrl'],'&',$keya);
		if($keyb != null){
			$keylen = $keyb - $keya;			
			$key = substr($row['baiduUrl'],$keya+3,$keylen-3);
		}else{	
			$key = substr($row['baiduUrl'],$keya+3);
		}
		$Engine = '百度PC';
	}elseif($keyEngine = stripos($row['baiduUrl'],'m.baidu.com')){
		//m.baidu	
		$keya = stripos($row['murl'],'word=');
		$keyb = stripos($row['murl'],'&',$keya);
		if($keyb != null){
			$keylen = $keyb - $keya;			
			$key = substr($row['murl'],$keya+5,$keylen-5);
		}else{	
			$key = substr($row['murl'],$keya+5);
		}
		$Engine = '百度M';		
	}elseif($keyEngine = stripos($row['baiduUrl'],'m.sogou.com')){
		//m.sogou
		$keya = stripos($row['murl'],'keyword=');
		$keyb = stripos($row['murl'],'&',$keya);
		if($keyb != null){
			$keylen = $keyb - $keya;			
			$key = substr($row['murl'],$keya+8,$keylen-8);
		}else{	
			$key = substr($row['murl'],$keya+8);
		}
		$Engine = '搜狗M';	
	}		
	$ukey = urldecode("$key");
	echo "<tr ><td>".$row['date']."</td><td style=\"display:block;overflow: auto;width:800px!important;\"><a href=".$row['url']." target=\"_blank\" >".$row['url']."</a></td><td>".$Engine."</td><td>".$row['ip']."</td><td><a href=".$row['baiduUrl']." target=\"_blank\" >".$ukey."</a></td></tr>";
	unset($Engine);
	unset($key);
	unset($ukey);
}	
mysql_close($mysqlC);
 ?>
 </table>
 </body>
 </html>