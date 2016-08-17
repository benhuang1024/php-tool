<?php

namespace Addons\GetMsg\Controller;
use Home\Controller\AddonsController;

class GetMsgController extends AddonsController{
	Public function _initialize() {

		$controller = strtolower ( _ACTION );

		$res ['title'] = '收到消息';
		$res ['url'] = addons_url ( 'GetMsg://GetMsg/lists' );
		$res ['class'] = $controller == 'lists' ? 'current' : '';
		$nav [] = $res;

		$res ['title'] = '模板消息发送';
		$res ['url'] = addons_url ( 'GetMsg://GetMsg/edit' );
		$res ['class'] = $controller == 'edit' ? 'current' : '';
		$nav [] = $res;

		$this->assign ( 'nav', $nav );
	}
	// show openid 
	public function showopenid(){

		get_token('this_token');
		$openid = get_openid();

		var_dump($openid);

	}

	public function centre(){


		$this->display();
	}
	public function show(){
		$openid = get_openid();
		$setoid[openid] = $openid;
		$showRes = M("getmsg")->where($setoid)->select();
		$this->assign('showRes',$showRes);
		$this->display();
	}
	public function showOne(){
		$id = I('id');
		$setid['id'] = $id;
		$showOneRes = M('getmsg')->where($setid)->select();
		$this->assign('showOneRes',$showOneRes);
		$this->display();
	}
	public function lists() {
		$this->assign ( 'add_button', false );
		$model = $this->getModel ();
		parent::common_lists ( $model );
	}
	public function edit(){
		$id = $_GET['id'];
		$getres = M("getmsg");
		$sql['id'] = $id;
		$res = $getres->where($sql)->select();
		$this->assign('id',$id);
		$this->assign('symptom',$res[0]['symptom']);
		$this->assign('openid',$res[0]['openid']);
		$this->display();
	}
	public function guahao(){
		$openid = get_openid();
		$token = get_token();
		$this->display();
	}
	public function push() {

		$openid = I('openid');
		$template_id = 'GtUIqCm3fAwxU8vqHaF0zh_FQChmL244vnj89A1xUjg';
		$url = $_POST['url'];
		$first = $_POST['first'];
		$keyword1 = $_POST['keyword1'];
		$keyword2 = $_POST['keyword2'];
		$keyword3 = $_POST['keyword3'];
		$keyword4 = $_POST['keyword4'];
		$keyword5 = $_POST['keyword5'];
		$remark = $_POST['remark'];
		$data = '{"first": {
                       "value":"'.$first.'",
                       "color":"#173177"
                   },
                   "keyword1":{
                       "value":"'.$keyword1.'",
                       "color":"#173177"
                   },
                   "keyword2": {
                       "value":"'.$keyword2.'",
                       "color":"#173177"
                   },
                   "keyword3": {
                       "value":"'.$keyword3.'",
                       "color":"#173177"
                   },
                     "keyword4": {
                       "value":"'.$keyword4.'",
                       "color":"#173177"
                   },
                     "keyword5": {
                       "value":"'.$keyword5.'",
                       "color":"#173177"
                   },
                   "remark":{
                       "value":"'.$remark.'",
                       "color":"#173177"
                   }
				}';
		$doctor = $keyword3;

		$id = I('id');
		$a = $this->sendtempmsg($openid, $template_id, $url, $data,$keyword2,$doctor,$id);

		$this->display();
	}
	public function prompt() {

		$openid = 'this_openid';
		$template_id = 'kKqwfc1lB0T0-dhArnLy6oA1lL02YDln5-tcrR26f3s';
		$url = 'this_url';
		$first = '预约挂号';
		$keyword1 = '预约挂号';
		$keyword2 = '预约挂号';
		$keyword3 = '预约挂号';
		$remark = '预约挂号';
		$data = '{"first": {
                       "value":"'.$first.'",
                       "color":"#173177"
                   },
                   "keyword1":{
                       "value":"'.$keyword1.'",
                       "color":"#173177"
                   },
                   "keyword2": {
                       "value":"'.$keyword2.'",
                       "color":"#173177"
                   },
                   "keyword3": {
                       "value":"'.$keyword3.'",
                       "color":"#173177"
                   },                  
                   "remark":{
                       "value":"'.$remark.'",
                       "color":"#173177"
                   }
				}';

		$doctor = $keyword3;

		$id = I('id');

		$a = $this->sendtempmsg($openid, $template_id, $url, $data,$keyword2,$doctor,$id);
		return true;
		//$this->display();
	}

	public function sendtempmsg($openid, $template_id, $url, $data,$reply, $doctor,$id,$topcolor='#FF0000') {
		$json = $this->get_access_token();

		if ($json ['errcode'] == 0) {
			$xml = '{"touser":"'.$openid.'","template_id":"'.$template_id.'","url":"'.$url.'","topcolor":"'.$topcolor.'","data":'.$data.'}';

			$res = $this->curlPost('https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$json['access_token'],$xml);
			$res = json_decode($res, true);

			// 记录日志
			if ($res ['errcode'] == 0) {
				addWeixinLog ( $xml, 'post' );
			}
			$senddata = array(
				'openid' => $openid,
				'template_id' => $template_id,
				'MsgID' => $res['msgid'],
				'message' => $data,
				'sendstatus' => $res['errcode']==0 ? 0 : 1,
				'token' => get_token(),
				'ctime' => time(),
			);
			$upddoc['doctor'] = $doctor;
			$upddoc['reply'] = $reply;
			$updid[id] = $id;
			M('getmsg')->where($updid)->save($upddoc);
			M ('tmplmsg')->add ( $senddata );
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
