<?php
session_start();
	function login(){
	require_once './class/mysqlConn.class.php';
		
		//
		$sqls = "SELECT `id` FROM `user` WHERE `user` = '".$_POST['user']."' AND `pass` = '".$_POST['pass']."'";		
		$res = mysql_query($sqls);
		$ups = mysql_fetch_assoc($res);
		if($ups['id']){
			$_SESSION['loginsU'] = $ups['id'];			
			header("Location:http://www.120gw.com/benhuang/grab/benhuang/selbengrad.php");
		}else{
			header("Location:http://www.120gw.com/benhuang/grab/logina.php");
		}		
		
	}
	

	$action = $_REQUEST['action'];
	if($action){
		login();
	}
	
?>