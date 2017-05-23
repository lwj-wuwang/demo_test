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

$sn     = empty($_GET['devicesn'])    ?  $_POST['devicesn']     :  $_GET['devicesn'];
$dname  = empty($_GET['devicesname']) ?  $_POST['devicesname'] :  $_GET['devicesname'];
$register_code = "Fj9XY3o8RtK0Bo5l";
$data   = array(
    "sn"        => $sn,
    "title"     => $dname
);
//var_dump(json_encode($data));die;
//$url        = "http://api.heclouds.com/register_de?register_code=".$register_code;
//
//$result     = get_html($url,json_encode($data));
//$reClass    = json_decode($result);
//$device_id  = $reClass->data->device_id;
//$device_key = $reClass->data->key;
$device_id = 6458823;
$device_key = "bMlvswoZ8L=Al47iwsy7lSNEboo=";
$insert_data = array(
    'device_sn'      => $sn,
    'device_name'    => $dname,
    'iot_device_id'  => $device_id,
    'iot_device_key' => $device_key
);
//echo '<pre>';
//print_r($insert_data);
//die;
$db       = new table();
$inser_id = $db ->insert("device_info", $insert_data);
if($inser_id){

    echo "<script>window.location.href='./listdevice.php'</script>";
}

