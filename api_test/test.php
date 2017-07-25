<?php
/**
 * Created by PhpStorm.
 * User: lvwenjing
 * Date: 2017/7/11
 * Time: 10:19
 */
error_reporting(E_ALL ^ E_DEPRECATED);

header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('Asia/Chongqing');
require_once "./Onepush/util.php";
require_once "./table_config.php";
require_once "../model.php";
require_once '../iot_php/OneNetApi.php';


$raw_input = $GLOBALS['HTTP_RAW_POST_DATA'];

$resolved_body = Util::resolveBody($raw_input);
file_put_contents('./data_1.txt',print_r($resolved_body,true).PHP_EOL,FILE_APPEND);//

if(empty($resolved_body)){
    $dev_id  = '10072873';
    $apikey  = "OyvIPHO=yBK5p=h1sok0vDbRsKE=";

    $oneOb   = new OneNetApi($apikey, $apiurl);
    $dataArr = $oneOb ->device($dev_id);

    $reason = 0;
    if(is_array($dataArr) && $dataArr['online'] == false ){
        $reason = 1;
    }

    $tableClass = new table($config);
    $data = array(
        'error_type' => '1',
        'errorTime'  => time(),
        'reason'     => $reason
    );

    $insert_res = $tableClass->insert('dev_error',$data);exit();
}

