<?php
ini_set("max_execution_time", "180000");
include 'Lib/phpQuery/phpQuery.php';
$keywords = trim($_POST['keywords']);
$urls = trim($_POST['urls']);
$warning = trim($_POST['alarmnumber']);
$userid = trim($_POST['ts']);
$ismonitor = $_POST['ismonitor'];

$keyword_arr = explode("\n", $keywords);
$url_arr = explode("\n", $urls);

$keyword_con = count($keyword_arr);
$no_sum = '';
foreach ($keyword_arr as $key) {
    $randa = rand(100, 999);
    $url = 'http://wap.baidu.com/s?word=' . $key . '&ts=8173515&t_kt=46&rsv_iqid=13192952292074186' . $randa . '&sa=ib&rsv_sug4=3785&inputT=1951&ss=100';
    try{
        @phpQuery::newDocumentFile($url);
    }catch(Exception $e){
        echo 'have waring '.$e. '('.json_encode ($_POST). ')';
    }
    $get_arr = pq('.ec_site');
    foreach ($get_arr as $gKey => $gVal) {
        $gValHtml = pq($gVal)->html();
        if (empty($gValHtml)) {
            $gValHtmls[0] = 'no';
        } elseif (!is_array($gValHtml)) {
            $gValHtmls[] = $gValHtml;
        } else {
            $gValHtmls = $gValHtml;
        }

        foreach ($url_arr as $uKey => $urlone) {
            if (in_array($urlone, $gValHtmls)) {
                $get_rank = $gKey + 1;
            }
        }

        if(empty($get_rank)){
            $get_rank = 'no';
            $no_sum += 1;
        }
        $rankVal['keyword'] = $key;
        $rankVal['url'] = $gValHtml;
        $rankVal['rank'] = $get_rank;
        $rankVal_arr[] = $rankVal;
        unset($gValHtmls);
        unset($get_rank);
    }
    if(empty($rankVal)){
        $rankVal['keyword'] = $key;
        $rankVal['url'] = '暂无';
        $rankVal['rank'] = 'no';
        $rankVal_arr[] = $rankVal;
    }
    unset($rankVal);
}
  include 'Lib/MySQL/MySQL.class.php';
    $sql_str = '';
    $exist_str = '';
    $conts = count($rankVal_arr);
    $cont = $conts - 1;
    foreach($rankVal_arr as $key=>$val){
        $sql_str .= " ('".$val['keyword']."','".$val['url']."','".$val['rank']."',NOW())";
        if($key < $cont){
            $sql_str .= ',';
        }

    }
    $db = new MySQL();
    $sql = "INSERT INTO `monitor_baidu` (`keyword`,`url`,`rank`,`timestamp`) VALUES ".$sql_str;
    $res = $db->execsql($sql);
switch($userid){   // input this opeind(wechat)
    case 0:
        $openid = '***';
        break;
    case 1:
        $openid = '***';
        break;
    case 2:
        $openid = '***';
        break;
    case 3:
        $openid = '***';
        break;
    case 4:
        $openid = '***';
        break;
    case 5:
        $openid = '***'; //ben
        break;
    default:
        $openid = '***';
}
    if('1' == $ismonitor){

        //echo json_encode($rankVal_arr). '('.json_encode ($_POST). ')';
        echo json_encode($rankVal_arr);
    }else{
        if(0 != $conts + 1){
            $percentage = 1 - $no_sum/$conts;
        }else{
            $percentage = 0;
        }
        $wechat = 'action';
        if($percentage < $warning){
//            推送微信
            $url_wecaht = "http://***.ben.com/index.php?s=/addon/GetMsg/GetMsg/pushwaring/openid/".$openid."/percentage/".$percentage;
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL,$url_wecaht);
            curl_setopt($ch,CURLOPT_HEADER,false);
            curl_setopt($ch,CURLOPT_TIMEOUT,2000);
            curl_exec($ch);
            curl_close($ch);

        }
        $ruturn_res = array('wechat'=>$wechat,'percentage'=>$percentage);
        //echo $url_wecaht;
        echo json_encode($ruturn_res); //. '('.json_encode ($_POST). ')';
    }

