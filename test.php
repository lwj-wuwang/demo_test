<?php
/**
 * Created by PhpStorm.
 * User: lvwenjing
 * Date: 2017/5/31
 * Time: 10:57
 */
header("Content-type: text/html; charset=utf-8");

//$url  = "https://112.74.25.29/xingyuanwatermeter/control/api2";
$url  = "https://112.74.25.29/xingyuanwatermeter/control/api";

$post_data = array(
    'cmd'       => 'getDeviceDataRecordSet',//'getDeviceList',
    'uuid'      => '1000000060539245',
    'viewSize'  => 10,
    'viewIndex' => 1,
    'starttime' => '2016-08-20 11:50:17',
    'endtime'   => '2017-06-16 11:50:17'
    /*'USERNAME'  => '13928423759',
    'PASSWORD'  => '123456',*/
);


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_PORT, 443);
$return = curl_exec($ch);
curl_close($ch);
$res = json_decode($return,true);
echo '<pre>';
print_r($res);


