<?php
/**
 * Created by PhpStorm.
 * User: lvwenjing
 * Date: 2017/5/25
 * Time: 16:57
 */

date_default_timezone_set('Asia/Chongqing');
require_once "./func.php";
require_once "./config.php";
require_once "./iot_php/OneNetApi.php";
require_once "./model.php";

file_put_contents("./get_dev.txt", print_r($_GET, TRUE), FILE_APPEND);die;

session_start();
$dev_sn       = $_GET['device_sn'];
$dev_name     = "设备 " . $_GET['version'];

$_SESSION['dev']['sn']      = $dev_sn;
$_SESSION['dev']['name']    = $dev_name;

$jump_url = site_url(true)."/demo_test/error.php";

if( !isset($_SESSION['dev']['sn'] ) || empty($_SESSION['dev']['name'] )){
    MobileErrorJS("非法请求",$jump_url);die;
}


$appid        = APPId;
$appsecret    = SECRET;
$redirect_url = urlencode(REDIRECT_URI);
$oauth2_url   = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri={$redirect_url}&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";

//判断code
if(empty($_GET['code'])){
    header("location:",$oauth2_url);
    exit;

}else{
    $code           = $_GET['code'];
}

$tokenArr           = get_access_token($code,APPId,SECRET);
if(!empty($tokenArr)){
    $access_token   = $tokenArr['access_token'];
    $openid         = $tokenArr['openid'];

}

$userinfo           = get_user_info($access_token,$openid);

if(empty($userinfo)){

    header("location:",$oauth2_url);
    exit;
}


//接入OneNET 完成设备新增
$OneClass       = new OneNetApi(MASTER_KEY,API_URL);
$dev_data       = array(
    'auth_info'     => $_SESSION['dev']['sn'],
    'title'         => $_SESSION['dev']['name'],
    'protocol'      => PROTOCOL,
    'private'       => true
);

$res        = $OneClass->device_add(json_encode($dev_data));
$error_code = 0;
$error      = '';

if(empty($result)){
    $error_code = $sm->error_no();
    $error      = $sm->error();
    MobileErrorJS($error,$jump_url);
    exit;
}else{
    $device_id = $result['device_id'];
}


$insert_data = array(
    'username'  =>  $userinfo['nickname'] . "_" . rand(10000,99999), //用户名
    'alias'     =>  $userinfo['nickname'],                           //昵称
    'sex'       =>  "'" . $userinfo['sex'] . "'",                    //性别
    'province'  =>  $userinfo['province'],                           //省份
    'city'      =>  $userinfo['city'],                               //市区
    'headimg'   =>  $userinfo['headimgurl'],                         //头像
    'openid'    =>  $userinfo['openid'],                             //微信openid
    'regtime'   =>  time(),
    'device_id' =>  $device_id

);

$userClass = new table();
$res       = $userClass ->insert("user",$insert_data);
if($res){
    MobileErrorJS("注册成功！","./listdevice.php");
    exit;
}else{
    MobileErrorJS("添加失败！",$jump_url);
    exit;
}