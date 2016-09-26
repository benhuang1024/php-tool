<?php
$dbms='mysql';     //数据库类型
$host='localhost'; //数据库主机名
$user='root';      //数据库连接用户名
$pass='blog.benhuang1024.com';          
$rpfield = 'body'; // update table char		
$tostring = ''; //	
$sql_arr = array(table1,table2);
$rpstring_arr = array('违规词1','违规词2');
foreach($rpstring_arr as $rpstring_one){	
$rpstring = $rpstring_one;
	foreach($sql_arr as $sql_one){
			$mysqli = new MySQLi($host,$user,$pass,$sql_one);	
			$mysqli->query("set names UTF8");										
			$exptable_obj = $mysqli->query("show tables like '%_addonarticle'");	// 获取表(内容表,标签表)			 			
			 $exptable = mysqli_fetch_array($exptable_obj);
			$exptable = $exptable[0];			
			$rs = $mysqli->query("UPDATE $exptable SET $rpfield=REPLACE($rpfield,'$rpstring','$tostring')");
			$mysqli->query("OPTIMIZE TABLE `$exptable`");					
			if($rs){
				echo $sql_one."成功完成&nbsp;".$rpstring."&nbsp;数据替换!<br />";		
			}else{
				echo $sql_one."中数据&nbsp;".$rpstring."&nbsp;替换失败！<br />";		
			}
			$mysqli->close();				
	}
}

