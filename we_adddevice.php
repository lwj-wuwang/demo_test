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

session_start();
$dev_sn     = $_GET['device_sn'];
$dev_name   = "设备 " . $_GET['version'];

$_SESSION['dev']['sn']      = $dev_sn;
$_SESSION['dev']['name']    = $dev_name;


//判断code
if(empty($_GET['code'])){

}else{
    $code     = $_GET['code'];
}

$tokenArr       = get_access_token($code,APPId,SECRET);
$access_token   = $tokenArr['access_token'];
$openid         = $tokenArr['openid'];

$userinfo       = get_user_info($access_token,$openid);

if(empty($userinfo)){
    $code_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=APPID&redirect_uri=REDIRECT_URL&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
    header("location:",$code_url);
    exit;
}

$username       = $userinfo['nickname'] . "_" . rand(10000,99999); //用户名
$alias          = $userinfo['nickname'];                           //昵称
$sex            = "'" . $userinfo['sex'] . "'";                    //性别
$province       = $userinfo['province'];                           //省份
$city           = $userinfo['city'];                               //市区
$headimg        = $userinfo['headimgurl'];                         //头像
$openid         = $userinfo['openid'];                             //微信openid
$regtime        = time();                                          //注册时间

$OneClass   = new OneNetApi(MASTER_KEY,API_URL);
$dev_data   = array(
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
    return;
}else{
    $device_id = $result['device_id'];
}


