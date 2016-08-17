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
foreach ($keyword_arr as $key) {
    $randa = rand(100, 999);
    $url = 'http://wap.baidu.com/s?word=' . $key . '&ts=8173515&t_kt=46&rsv_iqid=13192952292074186' . $randa . '&sa=ib&rsv_sug4=3785&inputT=1951&ss=100';
    try{
        @phpQuery::newDocumentFile($url);
    }catch(Exception $e){
        echo 'have waring '.$e;
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
include 'show.php';

if('closesql' != $closesql){
    include 'Lib/MySQL/MySQL.class.php';
    $sql_str = '';
    $cont = count($rankVal_arr) - 1;
    foreach($rankVal_arr as $key=>$val){
        $sql_str .= " ('".$val['keyword']."','".$val['url']."','".$val['rank']."',NOW())";
        if($key < $cont){
            $sql_str .= ',';
        }
    }
    $db = new MySQL();
    $sql = "INSERT INTO `mobile_baidu` (`keyword`,`url`,`rank`,`timestamp`) VALUES ".$sql_str;
    $res = $db->execsql($sql);
    echo '已存储' . $res . '条数据';
}else{
    echo '已关闭数据库处理';
}
$end_time = time();
$differ_time = $end_time - $action_time;
echo '<br />';
echo '用时:'.$differ_time;

