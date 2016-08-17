<!DCOYTPE html>
<html lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<title>关键词排名查询</title>
</head>
<body>
<?php
/*set_time_limit(0);*/
$getText = trim($_POST['keywords']);
$getUrl = trim($_POST['urls']);
if(empty($getText) || empty($getUrl)){
	echo '请输入参数';
	exit;
}
/*time*/
$getName = date('y-m-d-h-i-s',time()).'.txt';
$keyarr = explode("\n", $getText);
$keylen = count($keyarr);
$file = fopen($getName, "a+");

for ($i=0; $i < $keylen ; $i++) { 	
	$rands = rand(10,99);	
	$keyarr[$i] = trim($keyarr[$i]);
	$urlkey = 'http://m.baidu.com/ssid=0/from=0/bd_page_type=1/uid=0/baiduid=8085E2747ED42303651DAF3E2A175260/s?&lib=85997769840999404'.$rands.'&word='.$keyarr[$i].'&uc_param_str=upssntdnvelami&sa=ib&st_1=111041&st_2=102041&pu=ta%40middle___3_537%2Csz%40224_220&idx=20000&tn_1=middle&tn_2=middle&ct_1=%E6%90%9C%E7%BD%91%E9%A1%B5';
	$textarr = file_get_contents($urlkey);
		$tghou = explode("ec_resitem ec_adv ec_adv1", $textarr,2);	
		$tg = 	explode("推广", $tghou[1],-1);
		$tglen = count($tg);

		for ($j=0; $j < $tglen ; $j++) { 			
			if(strpos($tg[$j], $getUrl)){			
				fwrite($file, trim($keyarr[$i]));
				fwrite($file, " --- ");
				fwrite($file, $j+1);
				fwrite($file, "\n");
				$cunzai = 1;
			}			
		}
		if($cunzai == 0){
				fwrite($file, trim($keyarr[$i]));
				fwrite($file, " --- ");
				fwrite($file, "暂无");
				fwrite($file, "\n");
				$cunzai = 0;
		}
	$cunzai = 0;	
	unset($textarr);			
}
fclose($file);



?>	
生成结果：
<a href="<?php echo $getName; ?>">查看结果文档</a>
<br/>
显示结果：
<?php
include $getName;
?>
</body>
</html>