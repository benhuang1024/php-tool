<?php

namespace Addons\UserCenter\Controller;

use Home\Controller\AddonsController;
use User\Api\UserApi;

class UserCenterController extends AddonsController {

    /**
     * 显示微信用户列表数据
     */
    public function lists() {
        $this->assign ( 'add_button', false );
        $this->assign ( 'del_button', false );
        $this->assign ( 'check_all', false );

        $model = $this->getModel ( 'follow' );
        //var_dump($model);
        parent::common_lists ( $model );
    }

    //拉取用户列表并获得基本信息
    public function Alllistsinfo() {
        $key = 'access_token_gh_f2e51b1a7957';
        S ( $key, 'op73kbizjqi3bys8Z68LYGqM7NxNPbsePGImiX8wE5mdF-Iw_vfSg9ewqoCK5ZulCI9vhczB6vnOUOIlWhsXt66JV15WVFWixMYrNCI0lp1Os1Fk-4-xIaqMlM7kuzW5QPYjCIAGFO', 7200 );
        set_time_limit(18000);
        echo time();
        $token = get_token ();
        $Follow = M('follow');
        $openid_arr = $Follow->order("id asc")->select();
        foreach($openid_arr as $key => $value){
            if($value['id'] <= 7000){
                continue;
            }elseif('' == $value['nickname'] || null == $value['nickname']) {
                $openid = $value['openid'];
                $userinfo = getWeixinUserInfo($openid, $token);
                // update
                $where['openid'] = $openid;
                $data['nickname'] = $userinfo['nickname'];
                $Follow->where($where)->data($data)->save();
                echo $value['id'];
                var_dump($userinfo);
                echo '<br/>';

                $c_key += 1;
                if($c_key > 10){
                    echo time();

                    exit;
                }
            }
        }
        echo $c_key;
        exit;
        $openids = $content['data']['openid'];
        if (is_array ( $openids )) {
            foreach ($openids as $openid) {
                $info = D ( 'Common/Follow' )->init_follow ($openid);
            }
            $url = addons_url ( 'UserCenter://UserCenter/lists');
            $this->success ( '总共拉取了'.$content['count'].'个粉丝！',$url);
        }else{
            $this->error('订阅号无拉取粉丝接口');
        }
    }

    // 用户绑定
    public function edit() {
        $is_admin_edit = false;
        if(!empty($_REQUEST['id'])){
            $map['id'] = intval($_REQUEST['id']);
            $is_admin_edit = true;
            $msg = '编辑';
            $html = 'edit';
        }else{
            $msg = '绑定';
            $openid = $map ['openid'] = get_openid ();
            $html = 'moblieForm';
        }
        $token = $map ['token'] = get_token ();
        $model = $this->getModel ( 'follow' );

        if (IS_POST) {
            $is_admin_edit && $_POST['status'] = 2;
            $Model = D ( parse_name ( get_table_name ( $model ['id'] ), 1 ) );
            // 获取模型的字段信息
            $Model = $this->checkAttr ( $Model, $model ['id'] );
            if ($Model->create () && $Model->where ( $map )->save ()) {
                //lastsql();exit;
                $url = '';
                $bind_backurl = cookie('__forward__');
                $config = getAddonConfig ( 'UserCenter' );
                $jumpurl = $config['jumpurl'];

                if(!empty($bind_backurl)){
                    $url = $bind_backurl;
                    cookie('__forward__', NULL);
                }elseif(!empty($jumpurl)){
                    $url = $jumpurl;
                }elseif(!$is_admin_edit){
                    $url = addons_url ( 'WeiSite://WeiSite/index', $map );
                }

                $this->success ( $msg.'成功！',  $url);
            } else {
                //lastsql();
                //dump($map);exit;
                $this->error ( $Model->getError () );
            }
        } else {
            $fields = get_model_attribute ( $model ['id'] );
            if(!$is_admin_edit){
                $fieldArr = array('nickname','sex','mobile'); //headimgurl
                foreach($fields[1] as $k=>$vo){
                    if(!in_array($vo['name'], $fieldArr))
                        unset($fields[1][$k]);
                }
            }

            // 获取数据
            $data = M ( get_table_name ( $model ['id'] ) )->where ( $map )->find ();

            $token = get_token ();
            if (isset ( $data ['token'] ) && $token != $data ['token'] && defined ( 'ADDON_PUBLIC_PATH' )) {
                $this->error ( '非法访问！' );
            }

            // 自动从微信接口获取用户信息
            empty($openid) || $info = getWeixinUserInfo ( $openid, $token );
            if (is_array ( $info )) {
                if (empty ( $data ['headimgurl'] ) && ! empty ( $info ['headimgurl'] )) {
                    // 把微信头像转到WeiPHP的通用图片ID保存 TODO
                    $data ['headimgurl'] = $info ['headimgurl'];
                }
                $data = array_merge ( $info, $data );
            }

            $this->assign ( 'fields', $fields );
            $this->assign ( 'data', $data );
            $this->meta_title = $msg.'用户消息';

            $this->assign('post_url', U('edit'));

            $this->display ($html);
        }


    }
    public function userCenter() {
        $this->display();
    }
    function config(){
        // 使用提示
        $normal_tips = '如需用户关注时提示先绑定，请进入‘欢迎语’插件按提示进行配置提示语';
        $this->assign ( 'normal_tips', $normal_tips );

        parent::config();
    }
}
