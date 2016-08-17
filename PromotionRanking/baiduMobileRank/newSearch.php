<?php
ini_set("max_execution_time", "180000");
$action_time = time();
header("Content-Type: text/html;charset=utf-8");
include 'Lib/phpQuery/phpQuery.php';
$keywords = trim($_POST['keywords']);
$urls = trim($_POST['urls']);
@$closesql = $_POST['closesql'];
if (empty($keywords) || empty($urls)) {
    echo '请输入参数';
    die;
}
$keyword_arr = explode('
', $keywords);
$url_arr = explode('
', $urls);
$keyword_con = count($keyword_arr);
function utf8_str_to_unicode($utf8_str)
{
    $unicode = (ord($utf8_str[0]) & 0x1F) << 12;
    $unicode |= (ord($utf8_str[1]) & 0x3F) << 6;
    $unicode |= (ord($utf8_str[2]) & 0x3F);
    return dechex($unicode);
}

foreach ($keyword_arr as $key) {
    $url_meat = 'http://m.baidu.com';
    @$get_body = file_get_contents($url_meat);
    preg_match_all('/value="(.*)"/isU',$get_body,$arr_body);
    @$set_arr['rsv_iqid'] =$set_arr['rsv_pq'] = $arr_body[0][3];
    @$set_arr['rsv_t'] = $arr_body[0][4];
// print_r($set_arr);
// echo '<hr/>'; 
    $randa = rand(100, 999);
	if(!empty($set_arr['rsv_t'])){
	$url = 'http://m.baidu.com/s?word=' . $key . '&ts=0&t_kt=0&ie=utf-8&rsv_iqid='.$set_arr['rsv_iqid'].'&sa=ib&rsv_t='.$set_arr['rsv_t'].'&rsv_pq'.$set_arr['rsv_iqid'];	
	}else{
		$url = ' http://m.baidu.com/ssid=48cbc4bed2d7b9e3c1a5383630363236b522/s?word=' . $key . '&ts=8354647&t_kt=0&ie=utf-8&rsv_iqid=7102299170899189' . $randa . '&rsv_t=a5d7y%252BxdQwsT4bWbHyDA0uav7jKfWRb%252BCTEJzufVlG4TesEXRgro&sa=ib&rsv_pq=7102299170899189859&rsv_sug4=4563&ss=101&inputT=3351';
	}
    
//    
    try {
        @phpQuery::newDocumentFile($url);
    } catch (Exception $e) {
        echo 'have waring ' . $e;
        exit;
    }
//echo $url;

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
			//echo $gValHtmls[0].'_'.$urlone;
            if (strpos($gValHtmls[0], $urlone) or $gValHtmls[0] == $urlone) {
//                $sql = "INSERT INTO `mobile_baidu` (keyword,url,rank,`timestamp`) VALUES ($key,$gValHtml,$gKey+1,now())";
                $get_rank = $gKey + 1;
            }
        }
        if (empty($get_rank)) {
            $get_rank = 'no';
        }
        $rankVal['keyword'] = $key;
        $rankVal['url'] = $gValHtml;
        $rankVal['rank'] = $get_rank;
        $rankVal_arr[] = $rankVal;
        unset($gValHtmls);
        unset($get_rank);
    }
    if (empty($rankVal)) {
        $rankVal['keyword'] = $key;
        $rankVal['url'] = '暂无';
        $rankVal['rank'] = 'no';
        $rankVal_arr[] = $rankVal;
    }
    unset($rankVal);
}
include 'show.php';

if ('closesql' != $closesql) {
    include 'Lib/MySQL/MySQL.class.php';
    $sql_str = '';
    $cont = count($rankVal_arr) - 1;
    foreach ($rankVal_arr as $key => $val) {
		
		$val['url'] = strip_tags($val['url']);		
        $sql_str .= " ('" . $val['keyword'] . "','" . $val['url'] . "','" . $val['rank'] . "',NOW())";
        if ($key < $cont) {
            $sql_str .= ',';
        }
    }
    $db = new MySQL();
    $sql = "INSERT INTO `mobile_baidu` (`keyword`,`url`,`rank`,`timestamp`) VALUES " . $sql_str;
    $res = $db->execsql($sql);
    echo '已存储' . $res . '条数据';
} else {
    echo '已关闭数据库处理';
}
$end_time = time();
$differ_time = $end_time - $action_time;
echo '<br />';
echo '用时:' . $differ_time;

