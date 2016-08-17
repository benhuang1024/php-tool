<?php
include 'phpQuery/phpQuery.php';
include 'lib/search.php';
ini_set("max_execution_time","180000");
// url search
function search404($url){
    phpQuery::newDocumentFile($url);
    $a_arr = pq("a");
    $a_count = count($a_arr);
    for($i = 0 ;$i<$a_count;$i++){
        $url_arr[] = pq("a:eq($i)")->attr("href");
    }
    return $url_arr;
}

//
function connUrl($domain,$urlworn,$isnull){
    $rest4 = substr($urlworn,0,4);
    $rest3 = substr($urlworn,0,3);
    $rest2 = substr($urlworn,0,2);
    $rest1 = substr($urlworn,0,1);
    if('http' == $rest4){
        $inter = 'http';
    }elseif('/swt' == $rest4){
        $inter = 'swt';
    }elseif('tel:' == $rest4){
        $inter = 'tel';
    }elseif('java' == $rest4){
        $inter = 'javascript';
    }elseif('../.' == $rest4){
        $inter = '../.';
    }elseif('../' == $rest3){
        $inter = '..';
    }elseif('./' == $rest2){
        $inter = '.';
    }elseif('/' == $rest1){
        $inter = '/';
    }elseif('' == $rest1){
        $inter = 'null';
    }else{
        $inter = 'roll';
    }


    $domain_arr = explode('/',$domain);
    $domain_root = $domain_arr[0].'//'.$domain_arr[2];
    array_pop($domain_arr);
    $domain_this = implode('/',$domain_arr);
    array_pop($domain_arr);
    $domain_father = implode('/',$domain_arr);
    array_pop($domain_arr);
    $domain_grandfather = implode('/',$domain_arr);

    switch($inter){
        case 'http':
            $conn_url = $urlworn;
            break;
        case '/':
            $conn_url = $domain_root.$urlworn;
            break;
        case '.':
            $conn_url = $domain_this.$urlworn;
            break;
        case '../.':
            $conn_url = $domain_grandfather.$urlworn;
            break;
        case '..':
            $conn_url = $domain_father.$urlworn;
            break;
        case 'tel':
            $conn_url = 1;
            break;
        case 'javascript':
            $conn_url = 1;
            break;
        case 'swt':
            $conn_url = 1;
            break;
//        bind links
        case 'null':
            if(1 != $isnull){
                $conn_url = 1;
            }else{
                $conn_url = 'null';
            }

            break;
        default:
            $conn_url = $conn_url = $domain_this.$urlworn;;
    }
    return $conn_url;
}
//action
$urls = trim($_POST['urls']);
@$isnull = $_POST['isnull'];
@$is301 = $_POST['is301'];
if(empty($isnull)){
    $isnull = 0;
}
if(empty($is301)){
    $is301 = 0;
}
if(!empty($urls)){
    $urls_arr = explode("\n",$urls);
    foreach($urls_arr as $url){
        $domain = $url = trim($url);
        $oneurl_arr = search404($url);

        $urlsearch = new lib\search\search();
        foreach($oneurl_arr as $key=>$val){
            $conn_url = connUrl($domain,$val,$isnull);

            if($conn_url != 1){
                $start = $urlsearch->getState($conn_url);
                if(1 == $is301){
                    $fun301 = '301 == $start[\'http_code\']';
                }else{
                    $fun301 = false;
                }
                if(200 != $start['http_code'] && $fun301){
                    $temps['url'] = $start['url'];
                    $temps['http_code'] = $start['http_code'];
                    $start_arr[] = $temps;
                    unset($temp);
                }
            }
        }

        if(!empty($start_arr)) {
            $start_all[$url] = $start_arr;
        }
        unset($start_arr);
    }
    if(!empty($start_all)){

        include 'temp/show.php';
    }else{
        echo '所有链接正常';
//        echo 'all links is ok';
    }
}else{
    echo '参数为空';
//    echo "parameter is null";
}










