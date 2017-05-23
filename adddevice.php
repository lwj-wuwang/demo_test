<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/19 0019
 * Time: 16:06
 */
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
$url = "http://api.heclouds.com/register_de?register_code=".$register_code;

$result = get_html($url,json_encode($data));
$res = json_decode($result);
//print_r($res);die;
$insert_data = array(
    'device_sn'      => $sn,
    'device_name'    => $dname,
    'iot_device_id'  => $res['data']['device_id'],
    'iot_device_key' => $res['data']['key']
);
$db = new table();
$inser_id = $db ->insert("device_info", $insert_data);
if($inser_id){

    echo "<script>window.location('./listdevice.php')</script>";
}

