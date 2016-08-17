<?php

namespace Addons\Jkfx;
use Common\Controller\Addon;

/**
 * 接口分析插件
 * @author ben
 */

    class JkfxAddon extends Addon{

        public $info = array(
            'name'=>'Jkfx',
            'title'=>'接口分析',
            'description'=>'接口分析',
            'status'=>1,
            'author'=>'ben',
            'version'=>'0.1',
            'has_adminlist'=>1,
            'type'=>1         
        );

	public function install() {
		$install_sql = './Addons/Jkfx/install.sql';
		if (file_exists ( $install_sql )) {
			execute_sql_file ( $install_sql );
		}
		return true;
	}
	public function uninstall() {
		$uninstall_sql = './Addons/Jkfx/uninstall.sql';
		if (file_exists ( $uninstall_sql )) {
			execute_sql_file ( $uninstall_sql );
		}
		return true;
	}

        //实现的weixin钩子方法
        public function weixin($param){

        }

    }