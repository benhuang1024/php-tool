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
    $curl = curl_init();//��ʼ��curlģ��
    curl_setopt($curl, CURLOPT_URL, $url);//��¼�ύ�ĵ�ַ
    curl_setopt($curl, CURLOPT_HEADER, 0);//�Ƿ���ʾͷ��Ϣ
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 0);//�Ƿ��Զ���ʾ���ص���Ϣ
    curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie); //����Cookie��Ϣ������ָ�����ļ���
    curl_setopt($curl, CURLOPT_POST, 1);//post��ʽ�ύ
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));//Ҫ�ύ����Ϣ
    curl_exec($curl);//ִ��cURL
    curl_close($curl);//�ر�cURL��Դ�������ͷ�ϵͳ��Դ
}

function get_content($url, $cookie)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie); //��ȡcookie
    $rs = curl_exec($ch); //ִ��cURLץȡҳ������
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
//������������
$cookie = __File__.'\cookie.txt';
$file = __File__.'\file.txt';
//�ص����...
$get_cookie = login_post($url, $cookie, $post);
//Ϊ����ص����
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


