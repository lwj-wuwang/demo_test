<?php
/**
 * Created by PhpStorm.
 * User: lvwenjing
 * Date: 2017/7/6
 * Time: 10:13
 */
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('Asia/Chongqing');
require_once './testApiclass.php';
require_once "./Onepush/util.php";
$testClass = new ApiTest();

$action         = !empty($_GET['action'])       ? $_GET['action']                         : $_POST['action'];
$raw_input      = $GLOBALS['HTTP_RAW_POST_DATA'];
file_put_contents("./data.txt", print_r($raw_input,true).PHP_EOL, FILE_APPEND);
if( empty($action) ){
    if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest") {
        // ajax 请求的处理方式
        $Json['status'] = false;
        $Json['error'] = "请检查数据接口";
        echo json_encode($Json);
    }

    return false;
}



switch($action){
    //上传数据
    case 'upload_data':
        $Json = $testClass   ->UploadData();
        break;

    //推送数据
    /*case 'get_push_data':
        $Json = $testClass   ->getPushData($raw_input);
        break;*/

    default:
        $Json['status']     = false;
        $Json['error']      = "文件不存在";
        break;
}


if($_GET['debug']){
    echo '<pre>';
    print_r($Json);
    echo '</pre>';
    die;
}


echo json_encode($Json);
die;