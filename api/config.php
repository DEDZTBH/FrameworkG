<?php
include "app.php";
$result = array();
$result['value']=Config::getConfig($_GET['name']);
header('Content-Type: application/json;charset=utf8');
echo json_encode($result,JSON_UNESCAPED_UNICODE);