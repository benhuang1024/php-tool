<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/18
 * Time: 15:14
 */

namespace lib;


class Getelem
{
    public static $elem_key;
    public static $elem_val;
    public static $pregs;

    public static function check_link($link){
        $link_2 = substr(2,$link);
        switch($link_2[0]){
            case '':
                break;
        }
    }

    public static function construt_link($links){
        foreach($links as $key=>$val){

        }
    }

    public static function construct_elem($elem){
        switch ($elem){
            case 'css':
                Getelem::$elem_key = 'link';
                Getelem::$elem_val = 'href';
                Getelem::$pregs = '/\.css/';
                break;
            case 'js':
                Getelem::$elem_key = 'script';
                Getelem::$elem_val = 'src';
                Getelem::$pregs = '/\.js/';
                break;
            case 'img':
                Getelem::$elem_key = 'img';
                Getelem::$elem_val = 'src';
                Getelem::$pregs = '/\.png|\.jpg|\.gif/';
                break;
        }
    }

    public static function getelem($elem){
        Getelem::construct_elem($elem);
        $i = 1;
        do{
            $key = $i - 1;
            $link_key = pq(Getelem::$elem_key)->eq($key)->attr(Getelem::$elem_val);
            if(!empty($link_key)) {
                $link_arr = pq(Getelem::$elem_key)->eq($key)->attr(Getelem::$elem_val);
                $pregs = Getelem::$pregs;

                if(preg_match($pregs,$link_arr)){
                    $link_arrs[] = $link_arr;
                }
                $i += 1;
            }else{
                $i = 0;
            }

        }while($i);
        return $link_arrs;
    }
    public static function updGetelem($elem,$key,$name){
            Getelem::construct_elem($elem);
           $link_key = pq(Getelem::$elem_key)->eq($key)->attr(Getelem::$elem_val,'ben/'.$elem.'/'.$name);

        return $link_key;
    }

}