<?php
define ("MYSQL_HOST", "localhost");
define ("MYSQL_USER", "root");
define ("MYSQL_PWD","***");
define ("MYSQL_DB","***");
define ("MYSQL_PRELUDE","***");
define ("MYSQL_CHARSET","UTF8");
//ÏÅËÀ±¾±¦±¦ÁË

class MySQL{
    private $mysqli;
    public function __construct(){
        $this->mysqli = new MySQLi(MYSQL_HOST,MYSQL_USER,MYSQL_PWD,MYSQL_DB);
        $this->mysqli->query("set names ".MYSQL_CHARSET);
    }
    public function query($sql){
        $res = $this->mysqli->query($sql);
        $data = array();
        while($row = $res->fetch_assoc()){
            $data[] = $row;
        }
        return $data;
    }


    public function close(){
        $this->mysqli->close();
    }



}
