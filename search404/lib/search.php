<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/16
 * Time: 16:00
 */
namespace lib\search;
class search
{
    private $url;
    private $child_url;

//    get state value
    public function getState($child_url){
        ini_set("max_execution_time","180000");
        $url = $child_url;
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_HEADER,1);
        $response = curl_exec($ch);
        $curl_info = curl_getinfo($ch);
        curl_close($ch);
        return $curl_info;
    }
//    set value
    public function setchild_url($child_url){
        $this->child_url = $child_url;
    }
    public function seturl($url){
        $this->url = $url;
    }
//    get value
    public function getchild_url(){
        return $this->child_url;
    }
    public function geturl(){
        return $this->url;
    }
}