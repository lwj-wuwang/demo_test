<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/19 0019
 * Time: 16:06
 */
header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('Asia/Chongqing');

include_once "./func.php";
include_once "./model.php";
include_once "./iot_php/OneNetApi.php";
include_once "./config.php";

$sn     = empty($_GET['devicesn'])    ?  trim($_POST['devicesn'])     :  trim($_GET['devicesn']);
$dname  = empty($_GET['devicesname']) ?  trim($_POST['devicesname'])   :  trim($_GET['devicesname']);
$desc   = empty($_GET['devicesdesc']) ?  $_POST['devicesdesc']         :  $_GET['devicesdesc'];

$data           = array(
    "auth_info" => $sn,
    "title"     => $dname,
    "protocol"  => PROTOCOL,
    "desc"      => $desc,
    "private"   => true
);

$register_code  = "Fj9XY3o8RtK0Bo5l";//注册码
$master_key     = "8D=h5AFr8ueFS8XWX4=o=L7u9M4=";//产品APIKey

//$apiurl     = 'http://api.heclouds.com';
//$url        = "http://api.heclouds.com/register_de?register_code=".REGISTER_CODE;
$sm         = new OneNetApi(MASTER_KEY, API_URL);
$result     = $sm->device_add(json_encode($data));
$error_code = 0;
$error      = '';

if(empty($result)){
    $error_code = $sm->error_no();
    $error      = $sm->error();
    return;
}else{
//    echo '<pre>';
//    print_r($result);
    $device_id = $result['device_id'];

}


//$result     = get_html($url,json_encode($data));
//$reClass    = json_decode($result);
//$device_id  = $reClass->data->device_id;
//$device_key = $reClass->data->key;

$insert_data = array(
    'device_sn'      => $sn,
    'device_name'    => $dname,
    'iot_device_id'  => $device_id,
    'device_desc'    => $desc,
    'addtime'        => time()
);

$db       = new table();
$inser_id = $db ->insert("device_info", $insert_data);
if($inser_id){

    echo "<script>window.location.href='./listdevice.php'</script>";
}

