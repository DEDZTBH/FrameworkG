<?php
/**
 * Created by IntelliJ IDEA.
 * User: Codetector
 * Date: 16/9/2
 * Time: 下午11:06
 */
require_once 'app.php';
$result = array();
$user = User::validate($_POST['session']);
$prefix = Database::$db_prefix;
$result['valid']=false;
if ($link = $user->fetchInfo()) {
    $result['valid']=true;
    $result['info'] = $link;
}else{
    header('HTTP/1.0 401 Unauthorized');
    die();
}
echo json_encode($result,JSON_UNESCAPED_UNICODE);