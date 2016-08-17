<?php

namespace Addons\Xwfx\Controller;
use Home\Controller\AddonsController;

class XwfxController extends AddonsController{
	function _initialize() {
		//token = get_token();
		$act = strtolower ( _ACTION );
		$nav = array ();
		$res ['title'] = '7日数据';
		$res ['url'] = U ( 'lists' );
		$res ['class'] = $act == 'lists' ? 'current' : '';
		$nav [] = $res;
		
//		$res ['title'] = '月度数据';
//		$res ['url'] = U ( 'month' );
//		$res ['class'] = $act == 'month' ? 'current' : '';
//		$nav [] = $res;
		
		$this->assign ( 'nav', $nav );
	}
	public function lists(){
		$etime = $_REQUEST['end_date'];
		if(isset($etime)){
			if(strtotime($etime)>strtotime(date("Y-m-d"))){
				
				$etime = date("Y-m-d",strtotime("-1 day"));
			}
		}else{
			$etime = date("Y-m-d",strtotime("-1 day"));
		}
		
		$data=array();
		for($i=0;$i<7;$i++){
			$j = $i;
			$end_date = date("Y-m-d",strtotime("-".$i."day",strtotime($etime)));
			//$end_date = date("Y-m-d",strtotime("-".$i."day"));
			$begin_date = date("Y-m-d",strtotime("-".$i."day",strtotime($etime)));
			//$begin_date = date("Y-m-d",strtotime("-".$i."day"));
			$url_x = 'https://api.weixin.qq.com/datacube/getarticlesummary?access_token=';
			//$url_x = 'https://api.weixin.qq.com/datacube/getarticletotal?access_token=';
			$data[$j]=  json_decode($this->curl_getmsg($url_x,$begin_date,$end_date),TRUE);			
			$data[$j]['time'] = $end_date;
			$time[] = $end_date;
			if(count($data[$j]['list'])==0){
				$data[$j]['list']['0']['int_page_read_user']=0;
				$data[$j]['list']['0']['int_page_read_count']=0;
				$data[$j]['list']['0']['ori_page_read_user']=0;
				$data[$j]['list']['0']['ori_page_read_count']=0;
				$data[$j]['list']['0']['share_user']=0;
				$data[$j]['list']['0']['share_count']=0;
				$data[$j]['list']['0']['add_to_fav_user']=0;
				$data[$j]['list']['0']['add_to_fav_count']=0;
				//$data[$j]['list']['0']['target_user']=0;
			}
			foreach($data[$j]['list'] as $list){
					$int_page_read_user[$j] = $list['int_page_read_user'];
					$int_page_read_count[$j] = $list['int_page_read_count'];
					$ori_page_read_user[$j] = $list['ori_page_read_user'];
					$ori_page_read_count[$j] = $list['ori_page_read_count'];
					$share_user[$j] = $list['share_user'];
					$share_count[$j] = $list['share_count'];
					$add_to_fav_user[$j] = $list['add_to_fav_user'];
					$add_to_fav_count[$j] = $list['add_to_fav_count'];
					//$target_user[$j] = $list['target_user'];
			}
		}
		
		$int_page_read_user_new = join(',',$int_page_read_user);
		$int_page_read_count_new = join(',',$int_page_read_count);
		$ori_page_read_user_new = join(',',$ori_page_read_user);
		$ori_page_read_count_new = join(',',$ori_page_read_count);
		$share_user_new = join(',',$share_user);
		$share_count_new = join(',',$share_count);
		$add_to_fav_user_new = join(',',$add_to_fav_user);
		$add_to_fav_count_new = join(',',$add_to_fav_count);
		//$target_user_new = join(',',$target_user);		
		$time_new = join("','",$time);
		$time_new = "'".$time_new."'";
		$this->assign('data',$data);
		$this->assign('time_new',$time_new);
		$this->assign('int_page_read_user_new',$int_page_read_user_new);
		$this->assign('int_page_read_count_new',$int_page_read_count_new);
		$this->assign('ori_page_read_user_new',$ori_page_read_user_new);
		$this->assign('ori_page_read_count_new',$ori_page_read_count_new);
		$this->assign('share_user_new',$share_user_new);
		$this->assign('share_count_new',$share_count_new);
		$this->assign('add_to_fav_user_new',$add_to_fav_user_new);
		$this->assign('add_to_fav_count_new',$add_to_fav_count_new);
		$this->assign('time',$etime);
		$this->display ();
	}
	public function curl_getmsg($url_x,$begin_date,$end_date){
		$json = $this->get_access_token();	
		$token = $json['access_token'];
		$url = $url_x.$token;
		$data =array('begin_date'=>$begin_date,'end_date'=>$end_date);
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
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$status = curl_exec($ch);
		$errorno=curl_errno($ch);
		return $status;
	}
	//发送模板消息
	public function sendtempmsg($openid, $template_id, $url, $data,$reply, $doctor,$id,$topcolor='#FF0000',$get) {		
		$json = $this->get_access_token();		
		if ($json ['errcode'] == 0) {			
			$xml = '{"touser":"'.$openid.'","template_id":"'.$template_id.'","url":"'.$url.'","topcolor":"'.$topcolor.'","data":'.$data.'}';
			echo $xml;
			$res = $this->curlPost($get.$json['access_token'],$xml);
			$res = json_decode($res, true);
			// 记录日志
			if ($res ['errcode'] == 0) {
				addWeixinLog ( $xml, 'post' );
			}
					
			return $res;
		}else{			
			return $json;
		}
		
	}
	public function addmsg(){
		$openid = get_openid();	
		
		$senddata = array(
				'openid' => $openid,
				'region' => $_POST['region'],
				'age' => $_POST['age'],
				'onset' => $_POST['onset'],
				'symptom' => $_POST['symptom'],
				'need' => $_POST['need'],				
				'experience' => $_POST['experience']				
			);
		$result = M ('getmsg')->add ( $senddata );
		echo $result;	
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
