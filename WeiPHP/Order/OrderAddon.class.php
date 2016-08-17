<?php

namespace Addons\Order;
use Common\Controller\Addon;

/**
 * 预约插件
 * @author tzj
 */

    class OrderAddon extends Addon{

        public $info = array(
            'name'=>'Order',
            'title'=>'预约',
            'description'=>'预约',
            'status'=>1,
            'author'=>'tzj',
            'version'=>'0.1',
            'has_adminlist'=>0,
            'type'=>1         
        );

	public function install() {
		$install_sql = './Addons/Order/install.sql';
		if (file_exists ( $install_sql )) {
			execute_sql_file ( $install_sql );
		}
		return true;
	}
	public function uninstall() {
		$uninstall_sql = './Addons/Order/uninstall.sql';
		if (file_exists ( $uninstall_sql )) {
			execute_sql_file ( $uninstall_sql );
		}
		return true;
	}

        //实现的weixin钩子方法
        public function weixin($param){

        }

    }