<?php
if (file_exists("../api/include/db.php")){
    die("Installation is prohibited under current condition.");
}
?>
<!DOCTYPE html>
<html lang="zh_cn" ng-app="fgInstall">
<head>
    <meta charset="UTF-8">
    <title>FrameworkG Install</title>
    <link href="/css/zui/dist/css/zui.min.css" rel="stylesheet"/>
    <link href="/css/index.css" rel="stylesheet"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
</head>
<body ng-controller="settingCtrl">
<div class="container" style="margin-top: 100px;" id="userinputScreen">
    <div class="panel align-center" style="max-width: 400px;" >
        <div class="panel-heading">
            <h3>Install FrameworkG</h3>
        </div>
        <div class="panel-body">
            <form class="form form-inline">
                <div class="input-group">
                    <span class="input-group-addon">Database Host</span>
                    <input type="text" class="form-control" ng-model="dbSetting.dbhost" required>
                </div><br>
                <div class="input-group">
                    <span class="input-group-addon">Database Username</span>
                    <input type="text" class="form-control" ng-model="dbSetting.dbuser" required>
                </div><br>
                <div class="input-group">
                    <span class="input-group-addon">Database Password</span>
                    <input type="password" class="form-control" ng-model="dbSetting.dbpass" required>
                </div><br>
                <div class="input-group">
                    <span class="input-group-addon">Database Name</span>
                    <input type="text" class="form-control" ng-model="dbSetting.dbname" required>
                </div><br>
                <div class="input-group">
                    <span class="input-group-addon">Database Prefix</span>
                    <input type="text" class="form-control" ng-model="dbSetting.dbprefix" required>
                </div><br>
                <div class="input-group">
                    <span class="input-group-addon">Default Prefix</span>
                    <input type="text" class="form-control" ng-model="dbSetting.dbprefix" required>
                </div><br>
                <div class="pull-right" ng-show="testStatus==1">
                    <button type="button" class="btn btn-primary" ng-click="createDB()">Install</button>
                </div>
                <div class="input-group">
                    <button type="button" class="btn" ng-click="testConnection()" ng-class="{'btn-default':testStatus==0,'btn-danger':testStatus==-1,'btn-success':testStatus==1}">Test</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="container" style="margin-top: 100px; display: none;" id="progress">
    <div class="panel align-center" style="max-width: 500px;">
        <div class="panel-heading">
            <h3>Installing...</h3>
        </div>
        <div class="panel-body">
            Please Wait...
        </div>
    </div>
</div>
<div class="container" style="margin-top: 100px; display: none;" id="result">
    <div class="panel align-center" style="max-width: 500px;">
        <div class="panel-heading">
            <h3>Install Result</h3>
        </div>
        <div class="panel-body">
            <h3>{{installResult}}</h3>
            <h4>Default account : admin - Admin</h4>
        </div>
    </div>
</div></body>
<script src="/libs/angular/angular.min.js"></script>
<script src="/libs/jquery/dist/jquery.min.js"></script>
<!--<script src="/libs/js-cookie/src/js.cookie.js"></script>-->
<script src="/css/zui/dist/js/zui.min.js"></script>
<script src="app.js"></script>
</html>