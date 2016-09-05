<?php
$result = array();
$result['success']=true;
$link = mysqli_connect($_POST['dbhost'],$_POST['dbuser'],$_POST['dbpass']);
// try to connect to the DB, if not display error
if(!$link)
{
    $result['success']=false;
    $result['msg']="Sorry, these details are not correct. 
  Here is the exact error: ".mysqli_error($link);
}

if($result['success'] && !@mysqli_select_db($link,$_POST['dbname']))
{
    $result['success']=false;
    $result['msg']="The host, username and password are correct. 
  But something is wrong with the given database.
  Here is the MySQL error: ".mysqli_error($link);
}
echo json_encode($result,JSON_UNESCAPED_UNICODE);