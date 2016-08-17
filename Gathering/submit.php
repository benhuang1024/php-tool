<?php
/**获取参数,采集网站
 *author:ben;
 *date:15.09.3**/
//  requite lib
include 'phpQuery/phpQuery.php';
include 'lib/Getelem.class.php';
include 'lib/Download.class.php';
use lib\Download;
use lib\Getelem;
$url = $_POST['url'];
if (!empty($url)) {
    //  create dir
    $pattern = '/([\\w-]+\\.)+[\\w-]+/';
    preg_match($pattern, $url, $domain_name);
    $today = date('Ymd');
    //  path
    $dir_path = './down/' . $today;
    if (!file_exists($dir_path)) {
        mkdir($dir_path, 484);
    }
    $dir_path_Url = './down/' . $today . '/' . $domain_name[0];
    $ben_path = $dir_path_Url . '/ben';
    $ben_img = $ben_path . '/img';
    $ben_css = $ben_path . '/css';
    $ben_js = $ben_path . '/js';
    if (!file_exists($dir_path_Url)) {
        mkdir($dir_path_Url, 484);
        mkdir($ben_path, 484);
        mkdir($ben_img, 484);
        mkdir($ben_css, 484);
        mkdir($ben_js, 484);
    }
    if (!file_exists($dir_path_Url . '/index.html')) {
    }
     phpQuery::newDocumentFile($url);

    // get css link and download css

        $elem_css = 'css';
        $css_arr =  Getelem::getelem($elem_css);
    foreach($css_arr as $key => $val){
        $css_url = Download::makeUrl($domain_name[0],$url,$val);
        $css_name = Download::getName($val);
        $getkey = Getelem::updGetelem($elem_css,$key,$css_name);
        Download::down($css_url,$ben_css,$css_name);
    }

    //get js link and download js


    $elem_js = 'js';
    $js_arr = Getelem::getelem($elem_js);
    foreach ($js_arr as $key => $val) {
        $js_url = Download::makeUrl($domain_name[0], $url, $val);
        $js_name = Download::getName($val);
        $getkey = Getelem::updGetelem($elem_js,$key,$js_name);
        Download::down($js_url, $ben_js, $js_name);
    }
    //get img and download img

    $elem_img = 'img';
    $img_arr = Getelem::getelem($elem_img);
    foreach ($img_arr as $key => $val) {
        $img_url = Download::makeUrl($domain_name[0], $url, $val);
        $img_name = Download::getName($val);
        $getkey = Getelem::updGetelem($elem_img,$key,$img_name);
        Download::down($img_url, $ben_img, $img_name);
    }
    //upd index.html file path
    $cont_head = '<!DOCTYPE html><html><!-- author:ben,email:benhuang1024@163.com,交流群301010443,请保留作者信息 -->';
    $cont_foot = '</html>';
    $content = pq("html")->html();
    $res_type = file_put_contents($dir_path_Url.'/index.html',$cont_head.$content.$cont_foot);
    if($res_type>0){
       echo '采集成功';
        $zip = new ZipArchive();
            $opened = $zip->open( $dir_path_Url.'/'.$domain_name[0].'_down.zip', ZIPARCHIVE::OVERWRITE );
        if( $opened !== true ){
            die("cannot open {$dir_path_Url} for writing.");
        }else{
            Download::addFileToZip($dir_path_Url, $zip,$today);
        }
        $zip->close();
        echo '<br /><a href="'.$dir_path_Url.'/'.$domain_name[0].'_down.zip" target="_blank" >点击下载</a>';
    }else{
        echo '采集失败';
    }

} else {
    echo 'This post value is null.';
}