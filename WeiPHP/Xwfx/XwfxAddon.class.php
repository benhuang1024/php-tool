<?php

namespace Addons\Xwfx;
use Common\Controller\Addon;

/**
 * 图文分析插件
 * @author ben
 */

    class XwfxAddon extends Addon{

        public $info = array(
            'name'=>'Xwfx',
            'title'=>'图文分析',
            'description'=>'图文分析',
            'status'=>1,
            'author'=>'ben',
            'version'=>'0.1',
            'has_adminlist'=>1,
            'type'=>1         
        );

	public function install() {
		$install_sql = './Addons/Xwfx/install.sql';
		if (file_exists ( $install_sql )) {
			execute_sql_file ( $install_sql );
		}
		return true;
	}
	public function uninstall() {
		$uninstall_sql = './Addons/Xwfx/uninstall.sql';
		if (file_exists ( $uninstall_sql )) {
			execute_sql_file ( $uninstall_sql );
		}
		return true;
	}

        //实现的weixin钩子方法
        public function weixin($param){

        }

    }