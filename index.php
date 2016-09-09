<?php
if (!file_exists("./api/include/db.php")){
    header('Location: '.'/install');
    die();
}
?>
<!DOCTYPE html>
<html lang="zh_cn" ng-app="cms" ng-controller="globalCtrl">
<head>
    <meta charset="UTF-8">
    <title>{{cms_site_title}}</title>
    <link href="css/zui/dist/css/zui.min.css" rel="stylesheet"/>
    <link href="libs/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
</head>
<body>
<ng-include src="'template/navbar.ng'"></ng-include>
<div class="container" style="margin-top: 70px;">
    <ng-view></ng-view>
</div>
<div class="footer">
    <div class="container text-muted vertic">
        <h4>Copyright {{cms_companyName}} {{cms_site_ICP}}</h4>
        <div class="pull-right">
            <p>Powered By FrameworkG Version {{cms_frontendVersion}} / {{cms_backendVersion}}</p>
        </div>
    </div>
</div>
</body>
<script src="libs/angular/angular.min.js"></script>
<script src="libs/angular-route/angular-route.min.js"></script>
<script src="libs/jquery/dist/jquery.min.js"></script>
<script src="libs/js-cookie/src/js.cookie.js"></script>
<script src="css/zui/dist/js/zui.min.js"></script>
<script src="js/app.js"></script>
</html>