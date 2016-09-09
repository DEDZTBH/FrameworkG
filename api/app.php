<?php
include_once __DIR__+"/include/db.php";
class Database
{
    private static $db_address = db_host;
    private static $db_username = db_user;
    private static $db_password = db_pass;
    private static $db_name = db_name;
    public static $db_prefix = db_prefix;

    private static $db_conn;

    public static function getConnection(){
        if (self::$db_conn == null){
            self::$db_conn = mysqli_connect(self::$db_address,self::$db_username,self::$db_password,self::$db_name);
            mysqli_set_charset(self::$db_conn,"utf8");
        }
        return self::$db_conn;
    }
}
class Config{
    public static function getConfig($name){
        $prefix = Database::$db_prefix;
        if($returnValue = mysqli_fetch_array(mysqli_query(Database::getConnection(),"SELECT `value` FROM `{$prefix}settings` WHERE `keyname`='{$name}'"))['value']){
            return $returnValue;
        }
        return "";
    }
    public static function setConfig($name,$value){
        $prefix = Database::$db_prefix;
        return mysqli_query(Database::getConnection(),"INSERT INTO `{$prefix}settings` (`keyname`,`value`) VALUES ('{$name}','{$value}') ON DUPLICATE KEY UPDATE `value`=`value`");
    }
}
class User{
    private $userid;
    private $sessionID;
    private $isValid = false;

    static function validate($sessionID){
        $prefix = Database::$db_prefix;
        $self = new self();
        $sqlresult = mysqli_query(Database::getConnection(),"SELECT * FROM `{$prefix}cache` WHERE `sessionkey` = '{$sessionID}'");
        if($row = mysqli_fetch_array($sqlresult)){
            $self->isValid = true;
            $self->userid = $row['user_id'];
            $self->sessionID = $sessionID;
        }
        return $self;
    }

    static function login($username,$password){
        $prefix = Database::$db_prefix;
        $self = new self();
        $sqlresult = mysqli_query(Database::getConnection(),"SELECT * FROM `{$prefix}user` WHERE `username`='{$username}' OR `email`='{$username}'");
        if($row = mysqli_fetch_array($sqlresult)){
            if ($row['password'] == md5($password)){
                $self->isValid = true;
                $self->userid = $row['user_id'];
                $self->sessionID = uniqid();
                mysqli_query(Database::getConnection(),"INSERT INTO `{$prefix}cache` (`user_id`,`sessionkey`) VALUES ('{$row['user_id']}','{$self->sessionID}') ON DUPLICATE KEY UPDATE `sessionkey`=VALUES(`sessionkey`)");
            }
        }
        return $self;
    }

    public function fetchInfo(){
        $prefix = Database::$db_prefix;
        return mysqli_fetch_array(mysqli_query(Database::getConnection(), "SELECT *, NULL AS `password` FROM `{$prefix}user` WHERE `user_id`='{$this->getUserID()}'"));
    }

    public function isValid(){
        return $this->isValid;
    }
    public function getSession(){
        return $this->sessionID;
    }
    public function getUserID(){
        return $this->userid;
    }
}