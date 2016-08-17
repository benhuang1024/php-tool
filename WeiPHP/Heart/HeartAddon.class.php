<?php

namespace Addons\Heart;
use Common\Controller\Addon;

/**
 * 个人中心插件
 * @author ben
 */

    class HeartAddon extends Addon{

        public $info = array(
            'name'=>'Heart',
            'title'=>'个人中心',
            'description'=>'个人中心',
            'status'=>1,
            'author'=>'ben',
            'version'=>'0.1',
            'has_adminlist'=>1,
            'type'=>1         
        );

	public function install() {
		$install_sql = './Addons/Heart/install.sql';
		if (file_exists ( $install_sql )) {
			execute_sql_file ( $install_sql );
		}
		return true;
	}
	public function uninstall() {
		$uninstall_sql = './Addons/Heart/uninstall.sql';
		if (file_exists ( $uninstall_sql )) {
			execute_sql_file ( $uninstall_sql );
		}
		return true;
	}

        //实现的weixin钩子方法
        public function weixin($param){

        }

    }