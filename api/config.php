<?php
include "app.php";
if ($_GET['action'] == null) {
    $result = array();
    $result['value'] = Config::getConfig($_GET['name']);
    header('Content-Type: application/json;charset=utf8');
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
} else {
    if ($_GET['action'] == "list") {
        $result = array();
        $result['value'] = Config::listConfig();
        header('Content-Type: application/json;charset=utf8');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    } elseif ($_GET['action'] == "edit") {
        $user = User::validate($_POST['session']);
        if ($user->isValid()) {
            var_dump($user);
            Config::setConfig($_POST['settingName'], $_POST['settingValue']);
        }
    }elseif($_GET['action'] == 'remove'){
        $user = User::validate($_POST['session']);
        if ($user->isValid()) {
            var_dump($user);
            Config::remove($_POST['settingName']);
        }
    }
}