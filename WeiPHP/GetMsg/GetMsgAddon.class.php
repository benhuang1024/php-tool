<?php

namespace Addons\GetMsg;
use Common\Controller\Addon;

/**
 * 获取预约信息插件
 * @author benhuang
 */

    class GetMsgAddon extends Addon{

        public $info = array(
            'name'=>'GetMsg',
            'title'=>'获取预约信息',
            'description'=>'获取用户预约提交信息，进行处理',
            'status'=>1,
            'author'=>'benhuang',
            'version'=>'0.1',
            'has_adminlist'=>1,
            'type'=>1         
        );

	public function install() {
		$install_sql = './Addons/GetMsg/install.sql';
		if (file_exists ( $install_sql )) {
			execute_sql_file ( $install_sql );
		}
		return true;
	}
	public function uninstall() {
		$uninstall_sql = './Addons/GetMsg/uninstall.sql';
		if (file_exists ( $uninstall_sql )) {
			execute_sql_file ( $uninstall_sql );
		}
		return true;
	}

        //实现的weixin钩子方法
        public function weixin($param){

        }

    }