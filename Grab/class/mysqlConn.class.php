<?php
define("MYSQL_ADD","localhost");
define("MYSQL_USER","benhuang");
define("MYSQL_PWD","benhuang");
define("MYSQL_DB","benhuang");
define("MYSQL_CHARSET","gbk");
$mysqlC = mysql_connect(MYSQL_ADD,MYSQL_USER,MYSQL_PWD);
mysql_select_db(MYSQL_DB,$mysqlC);