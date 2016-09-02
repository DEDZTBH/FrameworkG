<?php
require_once "app.php";
$result = array();
$prefix = Database::$db_prefix;
$login = User::login($_POST['username'],$_POST['password']);
$result['success'] = $login->isValid();
if ($login->isValid()){
    $result['session']=$login->getSession();
}
echo json_encode($result,JSON_UNESCAPED_UNICODE);