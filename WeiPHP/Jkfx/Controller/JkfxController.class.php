<?php
namespace Addons\Jkfx\Controller;

use Home\Controller\AddonsController;
class JkfxController extends AddonsController
{
    public function _initialize()
    {
        //token = get_token();
        $act = strtolower(_ACTION);
        $nav = array();
        $res['title'] = '接口数据';
        $res['url'] = U('lists');
        $res['class'] = $act == 'lists' ? 'current' : '';
        $nav[] = $res;
        //		$res ['title'] = '月度数据';
        //		$res ['url'] = U ( 'month' );
        //		$res ['class'] = $act == 'month' ? 'current' : '';
        //		$nav [] = $res;
        $this->assign('nav', $nav);
    }
    public function lists()
    {		

        //if date is today
        $begin_date = I('begin_date');
		$end_date = I('end_date');
		//模型前置
		$jkfx = M('Jkfx');    
		$begin_date_Last = $jkfx->order('ref_dateST desc')->getField('ref_date');		
		//加载 to load	
		$this->storeVal($begin_date_Last);
		//to time
		if(empty($end_date)){
		$begin_date=date("Y-m-d",strtotime("-30 day"));
		$end_date=date("Y-m-d",strtotime("-1 day"));
		}
		$begin_dateST = strtotime($begin_date);
		$end_dateST = strtotime($end_date);
        $jkfxVal = $jkfx->where($begin_dateST.' <= ref_dateST AND ref_dateST <= '.$end_dateST)->order('ref_dateST desc')->select();
		//数据判断

        if (!empty($jkfxVal)) {
            $this->assign('data', $jkfxVal);	
			$this->getTableVal($jkfxVal);			
        } else {				
           /*  $res = $this->getInterfaceSummary($begin_date,$end_date);						
			$res = $res['list'];
			foreach($res as $resOne){
				$resOne['ref_dateST'] = strtotime($resOne['ref_date']);									
				$datas[] = $resOne;				
			}            
            $jkfx->addAll($datas);			
            $this->assign('data', $datas);			   
			$this->getTableVal($datas); */
        }

        $this->display();
    } 
	//加载数据
	public function storeVal($begin_date){
		//声明开始日期 action date 2014-12-01		
		if(!(bool)$begin_date || $begin_date == '0000-00-00'){
			$begin_date = '2014-12-01';			
		}			
		$end_date = date("Y-m-d",strtotime("-1 day"));		
		//timestamp		
		$begin_dateST = strtotime($begin_date);
		$end_dateST = strtotime($end_date);	
		//decide onload
		// $act = $this->toload($end_dateST);		
		//echo $begin_dateST.' '.$end_dateST;		
		if($begin_dateST < $end_dateST){		
			$d_value = $end_dateST - $begin_dateST;
			$d_valueD = $d_value/3600/24;	
			$begin_datef = $begin_date;			
			if($d_valueD > 30){
				for($i=1;$i<$d_valueD-30;$i+=30){
				//时间叠加
					$begin_datet = date("Y-m-d",strtotime($begin_datef) + 29*24*3600);					
					$getdatalist = $this->getInterfaceSummary($begin_datef,$begin_datet);						
					$getdata = $getdatalist['list'];
					foreach($getdata as $key){
						$key['ref_dateST'] = strtotime($key['ref_date']);
						$data[] = $key;						
					}
					$begin_datef = date("Y-m-d",strtotime($begin_datef) + 30*24*3600);					
				}				
			}
			//get residual
				$residual = $d_valueD%30;
				$end_datef = date("Y-m-d",$end_dateST - ($residual-1)*24*3600);
				$getdataendlist = $this->getInterfaceSummary($end_datef,$end_date);				
				$getdataend = $getdataendlist['list'];				
				foreach($getdataend as $key){
						$key['ref_dateST'] = strtotime($key['ref_date']);
						$data[] = $key;
						
				}
				$jkfxadd = M('Jkfx');
				$result = $jkfxadd->addAll($data);							
			if($result){    
				$this->success('数据正在加载',U(lists));
			} else {
				$this->success('数据已经加载',U(lists));
			}			 
		}
	}	
	//判断加载函数 ben 2015.06.11 deleted
/* 	public function toload($end_dateST){
		//校验时间戳 check timestamp
		$loadjkf = M('Jkfx');
		$condition['ref_dateST'] = $end_dateST;
		$swithget = $loadjkf->where($condition)->select;
		return $swithget;	
	} */
	//计算图表数值
	public function getTableVal($data){
		foreach($data as $dataOne){
			$showRes[]['dates'] = $dataOne['ref_date'];
			$showRes[]['callback_count'] = $dataOne['callback_count'];
			$showRes[]['fail_count'] = $dataOne['fail_count'];
			$showRes[]['total_time_cost_ave'] = $dataOne['total_time_cost']/$dataOne['callback_count'];
			$showRes[]['max_time_cost'] = $dataOne['max_time_cost'];			
		}

		$this->assign('showRes', $showRes);		
	}
    //获取信息
    public function getInterfaceSummary($begin_date,$end_date)
    {
        $begin_date = $begin_date;
        $end_date = $end_date;
        $url_x = 'https://api.weixin.qq.com/datacube/getinterfacesummary?access_token=';
        $data = json_decode($this->curl_getmsg($url_x, $begin_date, $end_date), TRUE);				
        return $data;
    }
    public function curl_getmsg($url_x, $begin_date, $end_date)
    {
        //$json = $this->get_access_token();
		//$token = $json['access_token'];
		$token = get_token();
		$access_token = get_access_token ( $token );		
		$token = $access_token;        
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