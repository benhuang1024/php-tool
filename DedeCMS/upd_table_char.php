<?php
$dbms='mysql';     //���ݿ�����
$host='localhost'; //���ݿ�������
$user='root';      //���ݿ������û���
$pass='blog.benhuang1024.com';          
$rpfield = 'body'; // update table char	
$rpstring = 'ben';  // update string
$tostring = '';
$sql_arr = array(table1,table2);
foreach($sql_arr as $sql_one){
	$mysqli = new MySQLi($host,$user,$pass,$sql_one);	
	$mysqli->query("set names UTF8");										
	$exptable_obj = $mysqli->query("show tables like '%_addonarticle'");	// ��ȡ��			 			
	 $exptable = mysqli_fetch_array($exptable_obj);
	$exptable = $exptable[0];			
	$rs = $mysqli->query("UPDATE $exptable SET $rpfield=REPLACE($rpfield,'$rpstring','$tostring')");
	$mysqli->query("OPTIMIZE TABLE `$exptable`");
		echo "UPDATE $exptable SET $rpfield=REPLACE($rpfield,'$rpstring','$tostring')"."<br />";
	if($rs){
		echo "�ɹ���������滻��".$sql_one."<br />";		
	}else{
		echo "�����滻ʧ�ܣ�".$sql_one."<br />";		
	}
	$mysqli->close();	
}


