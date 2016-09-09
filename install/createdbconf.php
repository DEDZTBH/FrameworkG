<?php
$connect_code="<?php
define('db_host','".$_POST['dbhost']."');
define('db_name','".$_POST['dbname']."');
define('db_user','".$_POST['dbuser']."');
define('db_pass','".$_POST['dbpass']."');
define('db_prefix','".$_POST['dbprefix']."');
?>";
$result = array();
if (!file_exists('../api/include')) {
    mkdir('../api/include', 0777, false);
}
$configFile = fopen("../api/include/db.php", "w");
if(!$configFile){
    $result['success'] = false;
    $result['reason'] = "permissionDeny";
}else {
    $result['success'] = true;
    fwrite($configFile, $connect_code);
    $result['success'] = fclose($configFile);
}
if ($result['success']){
    require '../api/app.php';
    $prefix = Database::$db_prefix;
    //Create cache
    mysqli_query(Database::getConnection(),"CREATE TABLE `{$prefix}cache` (`user_id` int(11) NOT NULL, `sessionkey` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
    //Create Settings
    mysqli_query(Database::getConnection(),"CREATE TABLE `{$prefix}settings` (`keyname` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL, `value` text NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    mysqli_query(Database::getConnection(),"INSERT INTO `{$prefix}settings` (`keyname`, `value`) VALUES ('companyName', 'CompanyName'), ('siteICP', 'ICP Number If Needed'), ('siteTitle', 'SiteTitle');");
    mysqli_query(Database::getConnection(),"CREATE TABLE `{$prefix}user` (`user_id` int(11) NOT NULL, `email` varchar(128) COLLATE utf8_bin NOT NULL, `username` varchar(32) COLLATE utf8_bin NOT NULL, `phone` varchar(16) NOT NULL, `password` varchar(32) COLLATE utf8_bin NOT NULL, `name` text COLLATE utf8_bin NOT NULL, `company` text COLLATE utf8_bin NOT NULL, `account_level` int(11) NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");
    mysqli_query(Database::getConnection(),"INSERT INTO `{$prefix}user` (`user_id`, `email`, `username`, `phone`, `password`, `name`, `company`, `account_level`) VALUES (1, 'admin@admin.admin', 'admin', 88888888, '21232f297a57a5a743894a0e4a801fc3', 'Administrator', 'GTech', 10);");
    mysqli_query(Database::getConnection(),"ALTER TABLE `{$prefix}cache` ADD PRIMARY KEY (`user_id`);");
    mysqli_query(Database::getConnection(),"ALTER TABLE `{$prefix}settings` ADD PRIMARY KEY (`keyname`);");
    mysqli_query(Database::getConnection(),"ALTER TABLE `{$prefix}user` ADD PRIMARY KEY (`user_id`), ADD UNIQUE KEY `username` (`username`);");
    mysqli_query(Database::getConnection(),"ALTER TABLE `{$prefix}user` MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;");
}

echo json_encode($result,JSON_UNESCAPED_UNICODE);