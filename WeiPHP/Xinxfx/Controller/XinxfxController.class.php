<?php

namespace Addons\Xinxfx\Controller;
use Home\Controller\AddonsController;

class XinxfxController extends AddonsController{
		public function _initialize()
		{
			$act = strtolower(_ACTION);
			$nav = array();
			$res['title'] = '消息分析';
			$res['url'] = U('lists');
			$res['class'] = $act == 'lists' ? 'current' : '';
			$nav[] = $res;
			$this->assign('nav', $nav);
		}
		
        public function lists()
    {   

	    $yesdate = date("Y-m-d",strtotime("-1 day"));
		$yesdate_d = date("d",strtotime("-1 day"));
		$info = M ('xinxfx')->field('max(ref_date) as max')->select();
		$max = $info[0]['max'];//获取数据库最后一天的日期
		//echo $max;exit;
		$max_d = date("d",strtotime($max));//最后一天的天
		$max_y = date("Y",strtotime($max));//最后一天的年
		$max_m = date("m",strtotime($max));//最后一天的月
		for($i=$max_d;$i<=$yesdate_d;$i++){
			if($i<10){
				$i = "0".$i;
			}else{
				$i = $i;
			}
			$time = $max_y."-".$max_m."-".$i;
			$sql = "ref_date='$time'";
			$infos = M ('xinxfx')->where($sql)->select();
			
			if(empty($infos)){//如果这个日期不存在，调用接口
				$end_date = $begin_date = $time;
				$url_x = 'https://api.weixin.qq.com/datacube/getupstreammsg?access_token=';
		        $data = json_decode($this->curl_getmsg($url_x, $begin_date, $end_date), TRUE);
				if(empty($data['list'][0])){ //获取的数据为空
					$info = array(
					'ref_date' => $time,
					'user_source' => 0,
					'msg_type' =>0 ,
					'msg_user' =>0 ,
					'msg_count' => 0				
							  );
					$result = M ('xinxfx')->add ( $info ); 
				}else{   //获取的数据不为空
					$info = array(
					'ref_date' => $data['list'][0]['ref_date'],
					'user_source' => $data['list'][0]['user_source'],
					'msg_type' => $data['list'][0]['msg_type'],
					'msg_user' => $data['list'][0]['msg_user'],
					'msg_count' => $data['list'][0]['msg_count']				
							  );
					$result = M ('xinxfx')->add ( $info ); 
				}
			}else{
				//如果这个日期存在
				
			}
		}
		
		$sevenago = date("Y-m-d",strtotime("-30 day"));
		$sql = "ref_date>='$sevenago' and ref_date<='$yesdate' and (msg_user>0 and msg_count>0)";
		$datas = M ('xinxfx')->where($sql)->order('ref_date desc')->limit(0,10)->select();
		foreach ($datas AS $key => $val) {
		$datas[$key]['avgs'] = round($datas[$key]['msg_count']/$datas[$key]['msg_user'] ,1);
		}

        $temp=count($datas);
        for($is=0;$is<$temp;$is++)
        {
          $datw=$datas[$is]['ref_date'];
		  $datws=$datas[$is]['msg_user'];
          $list1[] = $datw;
		  $list2[] = $datws;
       	 
        }
	
		$time = join("','",$list1);
		$msg_user = join(',',$list2);
		$time_new = "'".$time."'";
		$this->assign('time_new',$time_new);
		$this->assign('msg_user',$msg_user);
		$this->assign('data',$datas);
		$this->display ();
    }
	function searches(){
	    if($_GET['p'] == ''){
		    $_GET['p'] = 1;
		}                             //获取当前页数，如果为空默认为1
		$yesdate = date("Y-m-d",strtotime("-1 day"));
		$begin_date = I('begin_date');
		$end_date = I('end_date');
		if($begin_date){              //开始时间存在
			if($end_date){            //开始时间存在并且结束时间存在
				$sql = "ref_date>='$begin_date' and ref_date<='$end_date'";
		    }else{                  //开始时间存在但是结束时间不存在 
				$end_date = $yesdate;
				$sql = "ref_date>='$begin_date' and ref_date<='$yesdate'";	
			}
		}else{              //开始时间不存在
			$begin_date = "2014-12-01";
			if($end_date){                  //开始时间不存在但是结束时间存在
				$sql = "ref_date>='2014-12-01' and ref_date<='$end_date'";
             }else{                          //开始时间不存在并且结束时间也存在
				$end_date = $yesdate;
				$sql = "ref_date>='2014-12-01' and ref_date<='$yesdate'";	
			}
		}
		
		$datas = M ('xinxfx')->where($sql)->page($_GET['p'].',10')->select();
		$count = M ('xinxfx')->where($sql)->count();
		$page = new \Think\Page ( $count, 10 );
		
		$temp=count($datas);
        for($is=0;$is<$temp;$is++)
        {
          $datw=$datas[$is]['ref_date'];
		  $datws=$datas[$is]['msg_user'];
          $list1[] = $datw;
		  $list2[] = $datws;
       	 
        }
	
		$time = join("','",$list1);
		$msg_user = join(',',$list2);
		$time_new = "'".$time."'";
		$this->assign('begin_date',$begin_date);
		$this->assign('end_date',$end_date);
		$this->assign('time_new',$time_new);
		$this->assign('msg_user',$msg_user);
		$this->assign('data',$datas);
		$this->assign('_page',$page->show ());
		$this->display ();
	}
	
	
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
	
    public function curl_getmsg($url_x, $begin_date, $end_date)
    {
        $json = $this->get_access_token();
        $token = $json['access_token'];
        $url = $url_x . $token;
        $data = array('begin_date' => $begin_date, 'end_date' => $end_date);
        $ch = curl_init();
        $header = 'Accept-Charset: utf-8';
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $status = curl_exec($ch);
        $errorno = curl_errno($ch);
        return $status;
    }
    //发送模板消息
    public function sendtempmsg($openid, $template_id, $url, $data, $reply, $doctor, $id, $topcolor = '#FF0000', $get)
    {
        $json = $this->get_access_token();
        if ($json['errcode'] == 0) {
            $xml = '{"touser":"' . $openid . '","template_id":"' . $template_id . '","url":"' . $url . '","topcolor":"' . $topcolor . '","data":' . $data . '}';
            echo $xml;
            $res = $this->curlPost($get . $json['access_token'], $xml);
            $res = json_decode($res, true);
            // 记录日志
            if ($res['errcode'] == 0) {
                addWeixinLog($xml, 'post');
            }
            return $res;
        } else {
            return $json;
        }
    }
    public function addmsg()
    {
        $openid = get_openid();
        $senddata = array('openid' => $openid, 'region' => $_POST['region'], 'age' => $_POST['age'], 'onset' => $_POST['onset'], 'symptom' => $_POST['symptom'], 'need' => $_POST['need'], 'experience' => $_POST['experience']);
        $result = M('getmsg')->add($senddata);
        echo $result;
    }
    //获取access_token
    public function get_access_token()
    {
        $map['token'] = get_token();
        $info = M('member_public')->where($map)->find();
        $url_get = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $info['appid'] . '&secret=' . $info['secret'];
        $data = json_decode($this->curlGet($url_get), true);
        if ($data['errcode'] == 0) {
            return $data;
        } else {
            return $data;
        }
    }
    public function curlPost($url, $data)
    {
        $ch = curl_init();
        $header = 'Accept-Charset: utf-8';
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        $errorno = curl_errno($ch);
        return $tmpInfo;
    }
    public function curlGet($url)
    {
        $ch = curl_init();
        $header = 'Accept-Charset: utf-8';
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
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
