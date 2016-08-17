<?php
define ("MYSQL_HOST", "localhost");
define ("MYSQL_USER", "root");
define ("MYSQL_PWD","***");
define ("MYSQL_DB","***");//数据库
define ("MYSQL_CHARSET","UTF8");

class MySQL{
    private $mysqli;
    public function __construct(){
        $this->mysqli = new MySQLi(MYSQL_HOST,MYSQL_USER,MYSQL_PWD,MYSQL_DB);
        $this->mysqli->query("set names ".MYSQL_CHARSET);
    }

    public function execsql($sql){
        $flag = $this->mysqli->query($sql);
        if($flag){
            return $this->mysqli->affected_rows;//影响行数
        }else{
            return $flag;
        }
    }

    public function query($sql){

        $res = $this->mysqli->query($sql);
        $data = array();
        while($row = $res->fetch_assoc()){ //结果
            $data[] = $row;
        }
        return $data;
    }


    public function close(){
        $this->mysqli->close();
    }
}
