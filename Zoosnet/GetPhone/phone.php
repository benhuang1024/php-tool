<?php
/*
<!-- ben benhuang1024@163.com -->
*/
function getIP()
{
    global $ip;
    if (getenv("HTTP_CLIENT_IP"))
        $ip = getenv("HTTP_CLIENT_IP");
    else if (getenv("HTTP_X_FORWARDED_FOR"))
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if (getenv("REMOTE_ADDR"))
        $ip = getenv("REMOTE_ADDR");
    else $ip = "Unknow";
    return $ip;
}

function login_post($url, $cookie, $post)
{
    $curl = curl_init();//初始化curl模块
    curl_setopt($curl, CURLOPT_URL, $url);//登录提交的地址
    curl_setopt($curl, CURLOPT_HEADER, 0);//是否显示头信息
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 0);//是否自动显示返回的信息
    curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie); //设置Cookie信息保存在指定的文件中
    curl_setopt($curl, CURLOPT_POST, 1);//post方式提交
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));//要提交的信息
    curl_exec($curl);//执行cURL
    curl_close($curl);//关闭cURL资源，并且释放系统资源
}

function get_content($url, $cookie)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie); //读取cookie
    $rs = curl_exec($ch); //执行cURL抓取页面内容
    curl_close($ch);
    return $rs;
}

$url = 'http://ben.com/get/index.php?action=login';
$ip = getIP();
$BeginDateTime = date("Y-m-d");
@$domain_c = '&domain_c=' . $_POST['host'];
@$ip_c = '&ip_c=' . $ip;
@$key_c = '&key_c=' . $_POST['key_c'];
@$tel_c = '&tel_c=' . $_POST['tel_c'];
@$BeginDateTime = '&BeginDateTime=' . $BeginDateTime;
@$EndDateTime = '&EndDateTime=' . $_POST['EndDateTime'];
$url2 = "http://ben.com/get/index.php?action=qqsList" . $domain_c . $ip_c . $key_c . $tel_c . $BeginDateTime . $EndDateTime;
$post = array('username' => '***', 'password' => '***');
//吓死本宝宝了
$cookie = __File__.'\cookie.txt';
$file = __File__.'\file.txt';
//回调溢出...
$get_cookie = login_post($url, $cookie, $post);
//为处理回调溢出
echo '<ben>';
$content = get_content($url2, $cookie);

$exp_data = explode('left_txt2', $content);
$val = $exp_data[2];
preg_match_all('/<td>(.*?)<\/td>/', $val, $preg_val);
$time = $preg_val[1][0];
$tel = $preg_val[1][1];
$address = $preg_val[1][2];
$ip = $preg_val[1][3];
$url = $preg_val[1][5];
preg_match('/<a(.*?)>(.*?)<\/a>/', $tel, $preg_tel);
$tel = $preg_tel[2];
preg_match('/<a(.*?)>(.*?)<\/a>/', $ip, $preg_ip);
$ip = $preg_ip[2];
preg_match('/<a(.*?)href="(.*?)"(.*?)>(.*?)<\/a>/i', $url, $preg_url);
$url = $preg_url[2];
$r_data = '%E6%97%B6%E9%97%B4:' . $time . '.|.%E7%94%B5%E8%AF%9D:' . $tel . '.|.%E7%94%B5%E8%AF%9D%E5%9C%B0%E5%9D%80:' . $address . '.|.ip:' . $ip . '.|.URL:' . $url;

//file_put_contents($file, $r_data);
echo $r_data;


