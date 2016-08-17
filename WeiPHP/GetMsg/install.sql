
--
-- 表的结构 `hd_registration`
--

CREATE TABLE IF NOT EXISTS `hd_registration` (
  `id` int(11) NOT NULL,
  `openid` varchar(260) NOT NULL COMMENT '用户',
  `region` varchar(260) NOT NULL COMMENT '地区',
  `age` int(11) NOT NULL COMMENT '年龄',
  `symptom` varchar(500) NOT NULL COMMENT '症状',
  `need` varchar(500) NOT NULL COMMENT '需求',
  `experience` varchar(500) NOT NULL COMMENT '就诊经历'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户挂号';
INSERT INTO `hd_model` (`name`,`title`,`extend`,`relation`,`need_pk`,`field_sort`,`field_group`,`attribute_list`,`template_list`,`template_add`,`template_edit`,`list_grid`,`list_row`,`search_key`,`search_list`,`create_time`,`update_time`,`status`,`engine_type`) VALUES ('tmplmsg','收到消息','0','','1','{"1":["openid","region","age","symptom","need","experience"]}','1:基础','','','','','id:ID\r\nopenid:OPENID\r\nregion:region\r\nage|get_name_by_status:发送状态\r\nsymptom|get_name_by_status:送达报告\r\nneed:需求\r\nexperience|time_format:experience\r\nid:操作:[DELETE]|发送','10','openid','','1409247434','1409276606','1','MyISAM');
INSERT INTO `hd_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('openid','openid','varchar(255) NOT NULL','string','','','1','','0','0','1','1409247462','1409247462','','3','','regex','','3','function');
INSERT INTO `hd_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('template_id','模板ID','varchar(500) NOT NULL','string','','','1','','0','0','1','1409247489','1409247489','','3','','regex','','3','function');
INSERT INTO `hd_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('message','消息内容','text NOT NULL','textarea','','','1','','0','0','1','1409247512','1409247512','','3','','regex','','3','function');
INSERT INTO `hd_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('MsgID','消息ID','varchar(255) NOT NULL','string','','','1','','0','0','1','1409247552','1409247552','','3','','regex','','3','function');
INSERT INTO `hd_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('sendstatus','发送状态','char(50) NOT NULL','select','','','1','0:成功\r\n1:失败','0','0','1','1409247862','1409247608','','3','','regex','','3','function');
INSERT INTO `hd_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('Status','送达报告','char(50) NOT NULL','select','','','1','0:成功\r\n1:失败：用户拒收\r\n2:失败：其他原因','0','0','1','1409247873','1409247697','','3','','regex','','3','function');
INSERT INTO `hd_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('token','token','varchar(255) NOT NULL','string','','','0','','0','0','1','1409247732','1409247713','','3','','regex','get_token','1','function');
INSERT INTO `hd_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('ctime','发送时间','int(10) NOT NULL','datetime','','','1','','0','0','1','1409247759','1409247759','','3','','regex','time','3','function');