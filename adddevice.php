<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/19 0019
 * Time: 16:06
 */
header("Content-type: text/html; charset=utf-8");
include_once "./func.php";
include_once "./model.php";
include_once "./iot_php/OneNetApi.php";

$sn     = empty($_GET['devicesn'])    ?  trim($_POST['devicesn'])     :  trim($_GET['devicesn']);
$dname  = empty($_GET['devicesname']) ?  trim($_POST['devicesname'])   :  trim($_GET['devicesname']);
$desc   = empty($_GET['devicesdesc']) ?  $_POST['devicesdesc']         :  $_GET['devicesdesc'];

$data           = array(
    "sn"        => $sn,
    "title"     => $dname,
    "protocol"  => "EDP",
    "desc"      => $desc,
);

$register_code  = "Fj9XY3o8RtK0Bo5l";//注册码
$master_key     = "8D=h5AFr8ueFS8XWX4=o=L7u9M4=";//产品APIKey

$apiurl     = 'http://api.heclouds.com';
//$url        = "http://api.heclouds.com/register_de?register_code=".$register_code;
$sm         = new OneNetApi($master_key, $apiurl);
$result     = $sm->device_add(json_encode($data));
$error_code = 0;
$error      = '';

if(empty($result)){
    $error_code = $sm->error_no();
    $error      = $sm->error();
}else{
    echo '<pre>';
    print_r($result);

}

die;

$result     = get_html($url,json_encode($data));
$reClass    = json_decode($result);
$device_id  = $reClass->data->device_id;
$device_key = $reClass->data->key;
//$device_id = 6458823;
//$device_key = "bMlvswoZ8L=Al47iwsy7lSNEboo=";
$insert_data = array(
    'device_sn'      => $sn,
    'device_name'    => $dname,
    'iot_device_id'  => $device_id,
    'iot_device_key' => $device_key
);

$db       = new table();
$inser_id = $db ->insert("device_info", $insert_data);
if($inser_id){

    echo "<script>window.location.href='./listdevice.php'</script>";
}

