<?php
ini_set("max_execution_time", "180000");
$action_time = time();
header("Content-Type: text/html;charset=utf-8");
include 'Lib/phpQuery/phpQuery.php';

$keywords = trim($_POST['keywords']);

if (empty($keywords)) {
    echo '请输入参数';
    die;
}
$keyword_arr = explode('
', $keywords);
$pn = 0;
for($i=0;$i<2;$i++){  //分两页

    foreach ($keyword_arr as $key) {


        /* url */
        $url_meat = 'http://m.baidu.com';
        $get_body = file_get_contents($url_meat);
        preg_match_all('/value="(.*)"/isU',$get_body,$arr_body);
        $set_arr['rsv_iqid'] =$set_arr['rsv_pq'] = $arr_body[0][3];
        $set_arr['rsv_t'] = $arr_body[0][4];

        $randa = rand(100, 999);
        $url = 'http://m.baidu.com/s?pn='.$pn.'&word=' . $key . '&ts=0&t_kt=0&ie=utf-8&rsv_iqid='.$set_arr['rsv_iqid'].'&sa=ib&rsv_t='.$set_arr['rsv_t'].'&rsv_pq'.$set_arr['rsv_iqid'];
        // echo $url.'<br/>';
        //$url = 'https://m.baidu.com/ssid=51c6d0f9d4afbeb4b3c7313032349f53/s?word=%E8%85%8B%E8%87%AD%E5%93%AA%E5%AE%B6%E5%A5%BD&ts=4540515&t_kt=0&ie=utf-8&rsv_iqid=14185974960009362969&rsv_t=86b4PfWzG068tOD0w6BUql9TNDwRPRH04IcoRwmsNvA%252BhXPwSnCj&sa=ib&rsv_pq=14185974960009362969&rsv_sug4=6436&ss=001&inputT=110';
        //echo $url;
        try {
            @phpQuery::newDocumentFile($url);

        } catch (Exception $e) {
            echo 'have waring ' . $e;
            exit;
        }


        $get_arr = pq('.result.c-result.c-clk-recommend a:first-child');

        foreach ($get_arr as $gKey => $gVal) {
            $get_Urls[] = pq($gVal)->attr('href');
        }
        $get_Urls = array_unique($get_Urls);
        //exit;
        foreach($get_Urls as $key => $val){
            try {
                @phpQuery::newDocumentFile($val);

            } catch (Exception $e) {
                echo 'have waring ' . $e;
                exit;
            }
            // echo $val.'<br />';
            $e_url = pq('script')->html();
            @$ul_arr = explode('"',$e_url);
            @$is_url[] = $ul_arr[1];
            echo @$ul_arr[1];
            if(@$ul_arr[1]){
                echo '<br />';
            }
        }
    }
    $pn += 10;
}



