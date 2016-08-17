<?php
/**
 * Created by PhpStorm.
 * User: ben
 * Date: 2015/9/17
 * Time: 16:06
 * Count:
 * basal lib
 * down - download file
 * makeUrl - create url from css or js or images
 *
 */

namespace lib;


class Download
{
    public static function down($url,$path,$file_name){
        $url = $url;

        $fp_output = fopen($path.'/'.$file_name,'w');
        $ch = curl_init($url);
        curl_setopt($ch,CURLOPT_FILE,$fp_output);
        curl_exec($ch);
        curl_close($ch);
    }
    public static function makeUrl($domain,$this_path,$this_name){
        $fast_path = substr($this_name,0,1);
        switch($fast_path){
            case 'h':
                return $this_name;
                break;
            case '/':
                return 'http://'.$domain.$this_name;
                break;
            default:
                return $this_path.$this_name;
        }

    }
    public static function getName($path){
        $name = explode('/',$path);
        return $name[count($name) - 1];
    }

    public static function addFileToZip($path,$zip,$this_name){
        $handler=opendir($path); //打开当前文件夹由$path指定。
        while(($filename=readdir($handler))!==false){
            if($filename != "." & $filename != ".." & $filename != 'down' & $filename != $this_name){//文件夹文件名字为'.'和‘..’，不要对他们进行操作
                if(is_dir($path."/".$filename)){// 如果读取的某个对象是文件夹，则递归
                    Download::addFileToZip($path."/".$filename, $zip,$this_name);
                }else{ //将文件加入zip对象
                    $zip->addFile($path."/".$filename);
                }
            }
	}
        @closedir($path);
    }

}