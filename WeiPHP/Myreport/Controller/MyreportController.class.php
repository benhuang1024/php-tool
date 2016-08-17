<?php

namespace Addons\Myreport\Controller;
use Home\Controller\AddonsController;

class MyreportController extends AddonsController{
	public function index() {
		$openid = get_openid();
		//检测 ben
		$Userinfo = M(Userinfo);
		$where['openid'] = $openid;
		$data =  $Userinfo->where($where)->find();
		$url = U('/addon/Order/Order/binding');
		if(!$data['name']){
    	 header("Location:$url") ;
		}
		//301
		$sqm = 'HDLYY-WXKF00001';
        $ywlx = '1';
        $serviceid = 'GetPatBasicInfo'; 
        // 构造xml               
        $openid = get_openid();
        // get inhospno and name
        $Order = M("Userinfo");
        $where['openid'] = $openid;
        $dataOrder = $Order->where($where)->find();
        if(!empty($dataOrder['lyh'])){
            $inhospno = $dataOrder['lyh'];
            $name = $dataOrder['name'];
			$yytc = $dataOrder['yytc'];
        }else{
            //test value 测试数据

        }       
        // constitution 构造
        $getxml = $this->buildInstr($inhospno,$name);
        $instr = $getxml; 
        $getres = $this->GetHisService($sqm,$ywlx,$serviceid,$instr,$inhospno,$name);
        // format xml to array 转换xml成array
        $resArr = $this->xml_to_array($getres);	
		/* var_dump($getres);exit();  */
        // get reality content 获取主体内容
        $reportArr = $resArr['body'];
		$message = $resArr['head']['message'];
		$this->assign ('yytc',$yytc);
		$this->assign ('message',$message);
		$this->assign ('xmls',$reportArr);
        /*var_dump($getres);
        exit();*/
		$this->display ();
	}
	
    public function consumption(){
		$sqm = 'HDLYY-WXKF00001';
        $ywlx = '1';
        $serviceid = 'GetPatItemFee'; 
        // 构造xml               
        $openid = get_openid();
        // get inhospno and name
        $Order = M("Userinfo");
        $where['openid'] = $openid;
        $dataOrder = $Order->where($where)->find();
		$url = U('/addon/Order/Order/binding');
		if(!$dataOrder['name']){
		// header("Location:$url") ;
		}
		//301
        if(!empty($dataOrder['lyh'])){
            $inhospno = $dataOrder['lyh'];
            $name = $dataOrder['name'];
        }else{
            // test value 测试数据

        }       
        // constitution 构造
        $getxml = $this->buildInstr($inhospno,$name);
        $instr = $getxml; 
        $getres = $this->GetHisService($sqm,$ywlx,$serviceid,$instr,$inhospno,$name);
        // format xml to array 转换xml成array$name);
        // format xml to array 转换xml成array
        $resArr = $this->xml_to_array($getres); 
        
        // get reality content 获取主体内容
        $reportArr = $resArr['body']['PatFee']['Record']; 		
		/* var_dump($getres);exit();  */
		$this->assign ('xml',$reportArr);
    	$this->display ();
    }
    public function consumptionno(){
    	$this->display ();
    }
}
