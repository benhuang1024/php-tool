<?php
$dbms='mysql';     //���ݿ�����
$host='localhost'; //���ݿ�������
$user='root';      //���ݿ������û���
$pass='blog.benhuang1024.com';          
$rpfield = 'body'; // update table char		
$tostring = ''; //	
$sql_arr = array(table1,table2);
$rpstring_arr = array('Υ���1','Υ���2');
foreach($rpstring_arr as $rpstring_one){	
$rpstring = $rpstring_one;
	foreach($sql_arr as $sql_one){
			$mysqli = new MySQLi($host,$user,$pass,$sql_one);	
			$mysqli->query("set names UTF8");										
			$exptable_obj = $mysqli->query("show tables like '%_addonarticle'");	// ��ȡ��			 			
			 $exptable = mysqli_fetch_array($exptable_obj);
			$exptable = $exptable[0];			
			$rs = $mysqli->query("UPDATE $exptable SET $rpfield=REPLACE($rpfield,'$rpstring','$tostring')");
			$mysqli->query("OPTIMIZE TABLE `$exptable`");					
			if($rs){
				echo $sql_one."�ɹ����&nbsp;".$rpstring."&nbsp;�����滻!<br />";		
			}else{
				echo $sql_one."������&nbsp;".$rpstring."&nbsp;�滻ʧ�ܣ�<br />";		
			}
			$mysqli->close();				
	}
}

