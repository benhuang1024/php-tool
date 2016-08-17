<?php

namespace Addons\Hhfx\Controller;
use Home\Controller\AddonsController;

class HhfxController extends AddonsController{
    Public function _initialize() {	
		$controller = strtolower ( _ACTION );
		$res ['title'] = '收到消息';
		$res ['class'] = $controller == 'lists' ? 'current' : '';
		$nav [] = $res;			
		$this->assign ( 'nav', $nav );
	}
	 //去重并且重组数组
   Public function assoc_unique($arr, $key)
  {
	$tmp_arr = array();
	foreach($arr as $k => $v) {
		if(in_array($v[$key], $tmp_arr))//搜索$v[$key]是否在$tmp_arr数组中存在，若存在返回true
			unset($arr[$k]);
		else
			array_push($tmp_arr,$v[$key]);
	}
	sort($arr);
	return $arr;
   } 
    public function lists() {
       date_default_timezone_set('PRC');
       $begin_date=date("Y-m-d",strtotime("-1 day"));
       $page = I ( 'p', 1, 'intval' ); // 默认显示第一页数据   
       $row = empty ( $this->model ['list_row'] ) ? 10 : $this->model ['list_row'];  
       $hhfx = M('Hhfx');
       $data2['dates'] = $begin_date;
       $jkfxVal = $hhfx->where($data2)->select();
       if(empty($jkfxVal)){
       $end_date=date("Y-m-d",strtotime("-1 day"));
	   $zen=$this->sendtempmsg($begin_date,$end_date);//增减 
	   $lei=$this->sendtem($begin_date,$end_date);//累计
	   $z=count($zen['list']);
	   for($i=0;$i<$z;$i++){
		   $time[]=$zen['list'][$i]['ref_date'];
		   $time_new = array_values(array_flip(array_flip($time)));	   
	   }
			for($i=0;$i<count($time_new);$i++){
				for($j=0;$j<$z;$j++){
					$time1 = $zen['list'][$j]['ref_date'];
				   if($time_new[$i]==$time1){
						    $s[$time_new[$i]]=0;
							$s[$time_new[$i]]=$s[$time_new[$i]]+$zen['list'][$j]['new_user'];					
				   }
			   }
		   }	   
	   $array2D = $zen['list'];
	   $key = 'ref_date';
       $temp2=$this-> assoc_unique($array2D,$key);
       $temp=count($temp2);
       for($is=0;$is<$temp;$is++)
       {
          $datw=$temp2[$is]['ref_date'];
          $list[$is]['aa']=$datw;
       	  $list[$is]['bb']=$s[$datw];
          $list[$is]['cc']=$temp2[$is]['cancel_user'];
       	  $list[$is]['dd']=$s[$datw]-$temp2[$is]['cancel_user'];
       	  $list[$is]['ee']=$lei['list'][$is]['cumulate_user'];
       }
       $data1["dates"]=$list['0']['aa'];
       $data1["newc"]=$list['0']['bb'];
       $data1["cancel"]=$list['0']['cc'];
       $data1["increase"]=$list['0']['dd'];
       $data1["cumulative"]=$list['0']['ee'];
       $lastInsId = $hhfx->add($data1);
       }

       if (isset ($_REQUEST['begin_date'])) {
       	   $re=$_REQUEST ['begin_date'];
       	   $sql = "dates>='$re'"; 
       }
       if (isset ($_REQUEST['begin_date'])&&isset ($_REQUEST['end_date'])) {
       	   $sd=$_REQUEST ['end_date'];
       	   $sql .= " and dates<='$sd'";
       }
       if (!isset($_REQUEST['begin_date'])&&isset ($_REQUEST['end_date'])) {
         	$sd=$_REQUEST ['end_date'];
       	    $sql = "dates<='$sd'";
       }
       echo "$sql";
        $count = $hhfx->where($sql)->count ();
       	$lists = $hhfx->where($sql)-> order ('dates DESC')->page ( $page, $row )->select ();
       	if ($count > $row) {
       		$page = new \Think\Page ( $count, $row );
       		$page->setConfig ( 'theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
       		$this->assign ( '_page', $page->show () );
       }

	   $this->assign('showRes',$lists);
	   $this->display();
   }
  
   //获取用户增减数据
   public function sendtempmsg($begin_date,$end_date) {		
		$json = $this->get_access_token();		
		if ($json ['errcode'] == 0) {
            $data =array('begin_date'=>$begin_date,'end_date'=>$end_date);
			$xml=json_encode($data); 
			$res = $this->curlPost('https://api.weixin.qq.com/datacube/getusersummary?access_token='.$json['access_token'],$xml);
			$res = json_decode($res, true);		
			return $res;
		}else{			
			return $json;
		}		
	}
	//获取累计用户数据
	public function sendtem($begin_date,$end_date) {		
		$json = $this->get_access_token();		
		if ($json ['errcode'] == 0) {
            $data =array('begin_date'=>$begin_date,'end_date'=>$end_date);
			$xml=json_encode($data); 
			$res = $this->curlPost('https://api.weixin.qq.com/datacube/getusercumulate?access_token='.$json['access_token'],$xml);
			$res = json_decode($res, true);		
			return $res;
		}else{			
			return $json;
		}		
	}
   //获取access_token
	public function get_access_token(){
	
		$map ['token'] = get_token ();
		$info = M ( 'member_public' )->where ( $map )->find ();
		$url_get = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $info ['appid'] . '&secret=' . $info ['secret'];
		$data = json_decode($this->curlGet($url_get), true);
		if ($data ['errcode'] == 0) {

			return $data;
		}else{
		
			return $data;
		}
	}
    public function curlPost($url, $data){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		$errorno=curl_errno($ch);
		return $tmpInfo;
	}
	
    public function curlGet($url){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$temp = curl_exec($ch);
		return $temp;
	}
}
