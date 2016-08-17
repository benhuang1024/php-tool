<?php

namespace Addons\Heart\Controller;
use Home\Controller\AddonsController;

class HeartController extends AddonsController{
	public function index(){
		$openid = get_openid();
		//检测
		$Userinfo = M(Userinfo);
		$where['openid'] = $openid;
		$data =  $Userinfo->where($where)->find();
		$url = U('/addon/Order/Order/binding');
		//301
		if(!$data['name']){
			header("Location:$url") ;
		}
		$data['name'] = $this->change_asterisk($data['name'],1,null,'name');
		$data['lyh'] = $this->change_asterisk($data['lyh'],3,8);
		$data['cardID'] = $this->change_asterisk($data['cardID'],6,14);
		$this->assign('data',$data);
		$this->display();
	}
	public function particulars(){
		$openid = get_openid();
		//检测
		$Userinfo = M(Userinfo);
		$where['openid'] = $openid;
		$data =  $Userinfo->where($where)->find();
		$url = U('/addon/Order/Order/binding');
		//301
		if(!$data['name']){
			header("Location:$url") ;
		}
		// *
		//substr_replace($data[name],'*',1);

		$data['name'] = $this->change_asterisk($data['name'],1,null,'name');
		$data['lyh'] = $this->change_asterisk($data['lyh'],3,8);
//		var_dump($data['name']);
//		exit;
		$this->assign('data',$data);
		/* var_dump($data);exit; */
		$this->display();
	}

	public function subscribe(){
		$openid = get_openid();
		$Oreder = M('Oreder');
		$where['openid'] = $openid;
		$where['age'] = array('gt',0);
		$datas = $Oreder->where($where)->order ('id DESC')->select();
		$this->assign('datas',$datas);
		$url = U('/addon/Order/Order/index_list');
		if(empty($datas['0']['id'])){
			$this->error('请先预约',$url,3);
		}
		$this->display();
	}

	public function order(){


	}
	//检验 非正常
	public function checkf2(){
		$sqm = 'HDLYY-WXKF00001';
		$ymlx = '1';
		$serviceid = 'GetPatCheckResult';
		// 构造xml
		$openid = get_openid();
		// get inhospno and name
		$Order = M("Userinfo");
		$where['openid'] = $openid;
		$dataOrder = $Order->where($where)->find();
		if(!empty($dataOrder['lyh'])){
			$inhospno = $dataOrder['lyh'];
			$name = $dataOrder['name'];
		}else{
			// test value 测试数据 ben

		}
		// constitution 构造
		$getxml = $this->buildInstr($inhospno,$name);
		$instr = $getxml;
		$getres = $this->GetHisService($sqm,$ymlx,$serviceid,$instr,$inhospno,$name);

		// format xml to array 转换xml成array
		$resArr = $this->xml_to_array($getres);
		// get reality content 获取主体内容
		$reportArr = $resArr['body']['checkresult']['Record'];
		// select unofficial 筛选非正常
		foreach($reportArr as $key){
			/* 正常项筛选 高低标识	GDBZ	字符	10	允许	H高L低N正常，其它情况为空 */
			if($key['GDBZ'] == 'H' or $key['GDBZ'] == 'L' || ('阴性' == $key['CKZ'] && '阴性(-)' != $key['CHECKVALUE'])){
				/* select class 筛选类别  */
				switch($key['CHECKITEMTYPE']){
					case 0:
						$reports[0][] = $key;
						break;
					case 1:
						$reports[1][] = $key;
						break;
					case 2:
						$reports[2][] = $key;
						break;

				}
			}
		}
		/*echo '<pre>';
        print_r($reports);
        echo '</pre>';
        exit();*/
		$this->assign('reports',$reports);
		$this->display();
	}
	public  function  gettime(){
		echo time().'<br />';
	}
	public function testCheck(){
		echo time().'<br />';
		$sqm = 'HDLYY-WXKF00001';
		$ymlx = '1';
		$serviceid = 'GetPatCheckResult';
		// 构造xml
		$openid = get_openid();
		// get inhospno and name
		$Order = M("Userinfo");
		$where['openid'] = $openid;
		$dataOrder = $Order->where($where)->find();
		if(!empty($dataOrder['lyh'])){
			$inhospno = $dataOrder['lyh'];
			$name = $dataOrder['name'];
		}else{
			// test value 测试数据 ben

		}

		// constitution 构造
		$getxml = $this->buildInstr($inhospno,$name);
		$instr = $getxml;
		echo time().'his-act<br />';
		$getres = $this->GetHisService($sqm,$ymlx,$serviceid,$instr,$inhospno,$name);
		echo time().'his-end<br />';
		// format xml to array 转换xml成array
		$resArr = $this->xml_to_array($getres);
		// get reality content 获取主体内容
		$reportArr = $resArr['body']['checkresult']['Record'];
		// select unofficial 筛选非正常
		foreach($reportArr as $key){
			/* 正常项筛选 高低标识	GDBZ	字符	10	允许	H高L低N正常，其它情况为空 */
			if($key['GDBZ'] == 'H' or $key['GDBZ'] == 'L'){
				/* select class 筛选类别  */
				switch($key['CHECKITEMTYPE']){
					case 0:
						$reports[0][] = $key;
						break;
					case 1:
						$reports[1][] = $key;
						break;
					case 2:
						$reports[2][] = $key;
						break;

				}

			}
		}
		/*echo '<pre>';
        print_r($reports);
        echo '</pre>';
        exit();*/
		echo time().'<br />';
		var_dump($getres);
		exit;
		$this->assign('reports',$getres);
		$this->display();
	}

	public function getfile(){
		$url = I('url');
		//HISService.asmx?op=GetHisService
		if($url == 'GetHisService'){
			$files = file_get_contents("http://172.16.100.68:9090/HISService.asmx?op=GetHisService");
		}else{
			$files = file_get_contents("http://172.16.100.68:9090/HISService.asmx");
		}
		print_r($files);
	}







	//临床免疫学检验 正常
	public function checks(){
		$sqm = 'HDLYY-WXKF00001';
		$ymlx = '1';
		$serviceid = 'GetPatCheckResult';
		// 构造xml
		$openid = get_openid();
		// get inhospno and name
		$Order = M("Userinfo");
		$where['openid'] = $openid;
		$dataOrder = $Order->where($where)->find();
		if(!empty($dataOrder['lyh'])){
			$inhospno = $dataOrder['lyh'];
			$name = $dataOrder['name'];
		}else{
			// test value

		}
		// constitution
		$getxml = $this->buildInstr($inhospno,$name);
		$instr = $getxml;
		$getres = $this->GetHisService($sqm,$ymlx,$serviceid,$instr,$inhospno,$name);
		// format xml to array
		$resArr = $this->xml_to_array($getres);
		// user info get self doctor
		$serviceid = 'GetPatBasicInfo';
		$getUser = $this->GetHisService($sqm,$ymlx,$serviceid,$instr,$inhospno,$name);
		$userRes = $this->xml_to_array($getUser);
		$userArr = $userRes['body']['patinfo'];

		$reportArr = $resArr['body']['checkresult']['Record'];
		$checkname = I('checkname');
		//筛选检验科
		$check_arr = array('雌二醇（发光法pmol/L）','睾酮（发光法pmol/L）','黄体生成素（发光法pmol/L）','卵泡刺激素（发光法pmol/L）','孕酮（发光法pmol/L）');
		foreach($reportArr as $key){
			if('阴性' == $key['CKZ'] && '阴性(-)' != $key['CHECKVALUE']){
				$key['BenCHECK'] = 1;
			}
			if(in_array($key['ITEMNAME'],$check_arr)){
				$key['BenCHECK'] = 2;
			}
			if($key['CHECKITEMNAME'] == $checkname){
				$reports[] = $key;
			}
		}

		$this->assign('checkname',$checkname);
		$this->assign('reports',$reports);
		$this->assign('userArr',$userArr);
//  var_dump($userArr);exit;
		$this->display('checks');
	}

	//检验科 main
	public function report(){
		$sqm = 'HDLYY-WXKF00001';
		$ymlx = '1';
		$serviceid = 'GetPatCheckResult';
		// 构造xml
		$openid = get_openid();
		// get inhospno and name
		$Order = M("Userinfo");
		$where['openid'] = $openid;
		$dataOrder = $Order->where($where)->find();
		if(!empty($dataOrder['lyh'])){
			$inhospno = $dataOrder['lyh'];
			$name = $dataOrder['name'];
		}else{
			// test value

		}
		// constitution
		$getxml = $this->buildInstr($inhospno,$name);
		$instr = $getxml;
		$getres = $this->GetHisService($sqm,$ymlx,$serviceid,$instr,$inhospno,$name);
		$resArr = $this->xml_to_array($getres);
		$reportArr = $resArr['body']['checkresult']['Record'];
		$reportJson = json_encode($reportArr);

		//筛选检验科
		foreach($reportArr as $key){
			if($key['DEPTNAME'] == '检验科'){
				/* 正常项筛选 高低标识	GDBZ	字符	10	允许	H高L低N正常，其它情况为空 */
				if(!empty($key['CHECKVALUE'])) {
					$department[] = $key['CHECKITEMNAME'];
					$reports[] = $key;
				}
			}
		}
		$department = array_unique($department);
		$this->assign('department',$department);
		$this->assign('reports',$reports);
		$this->display();
	}

	/* quit user date:2015.07.29 */
	public function quit(){
		$openid = get_openid();
		$Userinfo = M("Userinfo");
		$where['openid'] = $openid;
		$Userinfo->where($where)->delete();
		$url = U('/addon/Order/Order/binding');
		$this->success('退出成功！',$url);
	}

	public function lists(){
		$page = I ( 'p', 1, 'intval' ); // 默认显示第一页数据
		$row = empty ( $this->model ['list_row'] ) ? 10 : $this->model ['list_row'];
		$Userinfo = M('Userinfo');
		$count = $Userinfo->count ();
		$where['openid'] = array('neq',-1);
		$lists = $Userinfo->where($where)->order ('id DESC')->page ( $page, $row )->select ();

		if ($count > $row) {
			$page = new \Think\Page ( $count, $row );
			$page->setConfig ( 'theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
			$this->assign ( '_page', $page->show () );
		}
		$this->assign('data',$lists);
		$this->display();
	}

	/* 导诊单 */
	public function getDaozhen(){
		$sqm = 'HDLYY-WXKF00001';
		$ymlx = '1';
		$serviceid = 'GetPatSingleGuide';
		// 构造xml
		$openid = get_openid();
		// get inhospno and name
		$Order = M("Userinfo");
		$where['openid'] = $openid;
		$dataOrder = $Order->where($where)->find();
		$url = U('/addon/Order/Order/binding');
		//301
		if(!$dataOrder['name']){
			$this->error('请绑定疗养号',$url,3);
		}
		if(!empty($dataOrder['lyh'])){
			$inhospno = $dataOrder['lyh'];
			$name = $dataOrder['name'];
		}else{
			// test value 测试数据 ben

		}
		// constitution 构造
		$getxml = $this->buildInstr($inhospno,$name);
		$instr = $getxml;
		$getres = $this->GetHisService($sqm,$ymlx,$serviceid,$instr,$inhospno,$name);
		// format xml to array 转换xml成array
		$resArr = $this->xml_to_array($getres);
		// get reality content 获取主体内容
		$reportArr = $resArr['body']['CheckItem']['Record'];
		// get userinfo
		$serviceid = 'GetPatBasicInfo';
		$getUser = $this->GetHisService($sqm,$ymlx,$serviceid,$instr,$inhospno,$name);
		$userRes = $this->xml_to_array($getUser);
		$userArr = $userRes['body']['patinfo'];
		// age
		$userDate = $userArr['IDCARD'];
		$userDateu = substr($userDate,6,4);
		$age = date(Y) - $userDateu;
		$userArr['age'] = $age;
		// foreach
		foreach($reportArr as $key){
			if($key['CHECKTYPE'] == '空腹'){
				$kval[] = $key;
			}else{
				// chock character 分割
				$dep = $key['GROUPNAME1'];
				$depid = substr($dep, 0, 2);
				$groupnamenew = substr($dep, 2);
				$key['groupnamenew'] = $groupnamenew;
				$nkval[$depid][] = $key;

				/* $nkval[] = $key; */
			}
		}

		ksort($nkval);

		/* var_dump($resArr);exit();   */
		$this->assign('kval',$kval);
		$this->assign('nkval',$nkval);
		$this->assign('user',$userArr);
		$this->display();

	}

	public function echoa(){
		$a = 'a';
		return $a;
	}

	public function testb(){
		$openid = get_openid();
		//检测
		$Userinfo = M(Userinfo);
		$where['openid'] = $openid;
		$data =  $Userinfo->where($where)->find();
		$url = U('/addon/Order/Order/binding');
		//301
		/* if(!$data['name']){
         header("Location:$url") ;
        } */
		$this->assign('data',$data);
		/* var_dump($openid);
         var_dump($data);exit;  */
		$this->display();
	}

	public function putdaozen(){


	}
}