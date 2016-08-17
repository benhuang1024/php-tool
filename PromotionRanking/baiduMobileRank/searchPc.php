<?php
/*@auth:ben<benhuang1024@163.com>
 * */
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
function creatArr($pre,$id,$key,$url_arr){
    $pre_id = $pre.$id;
    // $("#3001 div:last-child a span:last-child")
    // $("#bdfs0  a font:last-child")
    // $("#5002 a span")
    switch($pre){
        case '#300':
            $gVal = $pre_id." div:last-child a span:last-child:eq(0)";
                         $rank = '上'.$id;
            break;
        case '#bdfs':
            $gVal = $pre_id." a font:last-child:eq(2)";
            $rank = '右'.$id;
            break;
        case '#500':
            $gVal = $pre_id."div:last-child a span:eq(0)";
            $rank = '下'.$id;
            break;
    }
    $gValHtml = pq($gVal)->html();

    if (empty($gValHtml)) {
        $gValHtmls[0] = 'no';
    } elseif (!is_array($gValHtml)) {
        $gValHtmls[] = $gValHtml;
    } else {
        $gValHtmls = $gValHtml;
    }

    if (empty($pre_id)  || !in_array($gValHtmls[0],$url_arr)) {
        $rank = 'no';
    }
    $rankVal['keyword'] = $key;
    $rankVal['url'] = $gValHtmls[0];
    $rankVal['rank'] = $rank;
    return $rankVal;
}

foreach ($keyword_arr as $key) {
    $url_meat = 'http://m.baidu.com';
    $get_body = file_get_contents($url_meat);
    preg_match_all('/value="(.*)"/isU',$get_body,$arr_body);
    $set_arr['rsv_iqid'] =$set_arr['rsv_pq'] = $arr_body[0][3];
    $set_arr['rsv_t'] = $arr_body[0][4];


    $randa = rand(100, 999);
    $url = 'http://www.baidu.com/s?ie=utf-8&f=8&rsv_bp=0&rsv_idx=1&tn=baidu&wd=' . $key . '&rsv_enter=1&rsv_sug7=100';

    try {
        phpQuery::newDocumentFile($url);
    } catch (Exception $e) {
        echo 'have waring ' . $e;
        exit;
    }


    $top_arr = pq("#3001");
    if(!empty($top_arr)){
        for($i = 1;$i < 9;$i++){
            $rankVal_arr[] =  creatArr('#300',$i,$key,$url_arr);
        }
        $bottom_arr = pq("#5001");
        if(!empty($bottom_arr))
        {
            for($i = 1;$i < 4;$i++){
                $rankVal_arr[] = creatArr('#500',$i,$key,$url_arr);
            }
            $right_arr = pq("#bdfs0");
            if(!empty($right_arr )){
                for($i = 0;$i < 8;$i++){
                    $rankVal_arr[] = creatArr('#bdfs',$i,$key,$url_arr);
                }
            }
        }
    }
   /* if (empty($rankVal)) {
        $rankVal['keyword'] = $key;
        $rankVal['url'] = '暂无';
        $rankVal['rank'] = 'no';
        $rankVal_arr[] = $rankVal;
    }*/
    unset($rankVal);
}
$pc = 1;
include 'show.php';
$end_time = time();
$differ_time = $end_time - $action_time;
echo '<br />';
echo '用时:' . $differ_time;

