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


$raw_input = $GLOBALS['HTTP_RAW_POST_DATA'];
//$raw_input   = " {\"msg\": [{\"type\": 1,\"dev_id\": 2016617,\"ds_id\": \"datastream_id\",\"at\": 1466133706841,\"value\": 42},{\"type\": 1,\"dev_id\": 2016617,\"ds_id\": \"datastream_id\",\"at\": 1466133706842, \"value\":43}],\"msg_signature\": \"message signature\",\"nonce\": \"abcdefgh\"}";
file_put_contents("./data.txt", print_r($raw_input,true).PHP_EOL, FILE_APPEND);

$resolved_body = Util::resolveBody($raw_input);
file_put_contents('./data_1.txt',print_r($resolved_body,true).PHP_EOL,FILE_APPEND);//

if(empty($resolved_body)){
    $tableClass = new table($config);
    $data = array(
        'error_type' => '1',
        'errorTime'  => time()
    );

    $insert_res = $tableClass->insert('dev_error',$data);exit();
}


if(!empty($resolved_body)){
    /*if($resolved_body['type'] == 2){ //设备上线
        $resolved_body['status'] =0;
    }elseif($resolved_body['type'] == 1){//数据流信息

    }*/
    $json['status'] = false;
    $json['data']   = $resolved_body;
    $json['error']  = '推送数据成功';
    exit(json_encode($json));
}else{
    $json['status'] = true;
    $json['data']   = array(
        'times' => date("Y-m-d H:i:s")
    );
    exit(json_encode($json));
}