<?php
/*author:ben,email:benhuang1024@163.com */
header("Content-type:text/html;charset=utf-8");
$author = '/*author:ben,email:benhuang1024@163.com */';
// print_r($_SERVER);
$keyword = trim($_POST['keyword']);
if('ben' != $keyword){
	echo 'This is error keyword';	
	exit;
}
$old_css = trim($_POST['old_css']);
$del_css = trim($_POST['del_css']);
@$del_css_two = trim($_POST['del_css_two']);
@$del_css_three = trim($_POST['del_css_three']);
@$return_type = trim($_POST['type']);
$old_css_arr = explode("\n", $old_css);
$del_css_arr = explode("\n", $del_css); /* css_title css_count_and */
if(!empty($del_css_two)){
    $del_css_two_arr = explode("\n", $del_css_two);
    $del_type = 2;
}
if(!empty($del_css_three)){
    $del_css_three_arr = explode("\n", $del_css_three);
    $del_type = 3;
}
switch($del_type){
    case 2:
        $del_css_arr = array_intersect($del_css_arr,$del_css_two_arr);
        break;
    case 3:
        $del_css_arr = array_intersect($del_css_arr,$del_css_two_arr,$del_css_three_arr);
        break;
}


if(empty($return_type)){
    $return_type = 'text';
}
foreach ($del_css_arr as &$key) {
    $key = trim($key);
}
foreach ($old_css_arr as $key => $value) {
    // clear null rows
    if (!empty(trim($value))) {
        // add sum limit
        $old_css_explode = explode('{', $value,2);
        $old_css_explode[0] = trim($old_css_explode[0]);
        $stare = in_array($old_css_explode[0], $del_css_arr);
        if (!$stare) {
            $show_css_c[] = $old_css_explode;
            echo $old_css_explode[0].'{'.$old_css_explode[1];
            echo '<br />';
            @$show_css_text .= $old_css_explode[0].'{'.$old_css_explode[1];
        } else {
           // echo $old_css_explode[0].'{'.$old_css_explode[1];
            echo '<br />';
            $del_css_c[] = $old_css_explode;
        }
        unset($old_css_explode);
    }
}
if(!empty($show_css_text)){
    $show_css_text_json['type'] = 'ok';
    $show_css_text_json['cont'] = $show_css_text;
}
if($return_type == 'json'){
    echo json_encode($show_css_text_json); }else{
    //   echo $show_css_text;
}


// css


