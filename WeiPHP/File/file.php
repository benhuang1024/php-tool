<?php
class file
{
/*
 * sqm：授权码，
 * ymlx：业务类型，
 * serviced：服务GetPatItemFeeID，调用的接口名称，定义为GetPatBasicInfo，GetPatCheckResult，GetPatSingleGuide，GetPatItemFee
serviced：服务ID，调用的接口名称，定义为GetPatBasicInfo(病人信息)，GetPatCheckResult(结果说明)，GetPatSingleGuide(导引单信息)，GetPatItemFee(费用信息)
*/
    public function GetInfoService($sqm = 'HDLYY-WXKF00001', $ymlx = '1', $serviceid, $instr, $inhospno, $name)
    {
//select xml file
        $filename = "./Data/Xml/" . $serviceid . '/' . $inhospno . '.xml';
        ini_set("max_execution_time", "1800");
        if (file_exists($filename)) {
            $filedate = filemtime($filename);
            $fdate = date("Ymdhi", $filedate); // file date;
//now date
            $nowdate = date("Ymdhi");  // now
            if ($nowdate - $fdate <= 30) {
                $fres = file_get_contents($filename);
                return $fres;
            } else {
                goto geturlval;
            }
        }
        geturlval: {
// set overtime  设置超时时间
        if (!empty($instr)) {
            $ch = curl_init();
            $data = array('sqm' => $sqm, 'ymlx' => $ymlx, 'serviceid' => $serviceid, 'instr' => $instr, 'name' => $name, 'inhospno' => $inhospno);
//		var_dump($data);exit;


            $datas = http_build_query($data);
            curl_setopt($ch, CURLOPT_URL, 'http://ben.com/index.php?s=/get');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
            $resposts = curl_exec($ch);
            curl_close();
            $checkval = $this->xml_to_array($resposts);
            $arr_check = $checkval['head']['code'];
// create local xml 创建本地文件
            if ($arr_check == 1) {
                if ($inhospno) {
                    $filename = "./Data/Xml/" . $serviceid . '/' . $inhospno . '.xml';
                    file_put_contents($filename, $resposts);
                }
            }
            return $resposts;
        } else {
            return 'message lack';
        }
    }
    }
}