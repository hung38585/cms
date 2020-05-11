<?php
include_once  dirname(__DIR__)."\config\config.php";
class connectdb{
    private $connection;
    //Check connection with mysql
    public function checkDBConnection(){
        
        $this->connection = mysqli_connect(host_name, user_name, db_password, db_name);
        //check connect
        if ($this->connection){
            mysqli_set_charset($this->connection, 'utf8');
        }else{
            echo "Ket noi voi db khong thanh cong";
        }
    }
    public function connectQuery($sql_string){
        $result = mysqli_query($this->connection, $sql_string);
        if ($result){
            return $result;
        }else{
            return false;
        }
    }
}