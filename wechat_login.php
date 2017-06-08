<?php
/**
 * Created by PhpStorm.
 * User: lvwenjing
 * Date: 2017/5/25
 * Time: 16:57
 * url:http://cs.37jy.com/demo_test/wechat_login.php?device_sn=201705261443&version=XQ270a
 */


require_once "./init.php";
session_start();

if(!empty($_GET['device_sn']) && !empty($_GET['version'])){
    $_SESSION['dev']['sn']      = $_GET['device_sn'];
    $_SESSION['dev']['name']    = $_GET['version'];
}


file_put_contents("./file.txt", "dev_".date("Y-m-d H:i:s").print_r($_SESSION['dev'], TRUE), FILE_APPEND);




if( empty($_SESSION['dev']['sn'] ) || empty($_SESSION['dev']['name'] )){
    MobileErrorJS("非法请求",$jump_url);die;
}


$appid        = APPId;
$appsecret    = SECRET;
$redirect_url = urlencode(REDIRECT_URL);
$oauth2_url   = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri={$redirect_url}&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";

$openid = $_SESSION['wx_openid'];
file_put_contents("./file.txt", "openid_".date("Y-m-d H:i:s").print_r($openid, TRUE), FILE_APPEND);
/*$openid = "oMV6QuJyODe_gsaXsz2M-BBYbdoI";
$_SESSION['wx_openid']  = $openid;*/
if(empty($openid)){
    //判断code
    if( !isset($_GET['code']) && empty($_GET['code']) ){
        header("Location:".$oauth2_url);
        exit;

    }else{
        $code           = $_GET['code'];
        file_put_contents("./file.txt", "code_".date("Y-m-d H:i:s").print_r($code, TRUE), FILE_APPEND);
        //获取微信的access_token和openid
        $tokenArr       = get_access_token($code,APPId,SECRET);
        $access_token   = $tokenArr['access_token'];
        $openid         = $tokenArr['openid'];
    }

    $_SESSION['wx_openid']  = $openid;

}

if( !isset($_SESSION['wx_openid'] ) || empty($_SESSION['wx_openid'] )){
    MobileErrorJS("非法请求",$jump_url);die;
}

file_put_contents("./file.txt", "user_openid_".date("Y-m-d H:i:s").print_r($openid, TRUE), FILE_APPEND);

//查询用户是否已注册
$user_res   = $db->getList('user','*',"openid='{$openid}'");
//debug($user_res);
file_put_contents("./file.txt", "user_".date("Y-m-d H:i:s").print_r($user_res, TRUE), FILE_APPEND);
if(!$user_res){ //用户未注册
    //获取微信用户信息
    $userinfo           = get_user_info($access_token,$openid);
    file_put_contents("./file.txt", "userinfo_".date("Y-m-d H:i:s").print_r($userinfo, TRUE), FILE_APPEND);
//    die;
    if(empty($userinfo)){
        header("Location:".$oauth2_url);
        exit;
    }

    $if_focus = empty($userinfo->unionid)    ?   0 : 1;

//用户信息添加
    $insert_data = array(
        'username'  =>  $userinfo->nickname . "_" . rand(10000,99999), //用户名
        'alias'     =>  $userinfo->nickname,                           //昵称
        'sex'       =>  $userinfo->sex ,                               //性别
        'province'  =>  $userinfo->province,                           //省份
        'city'      =>  $userinfo->city,                               //市区
        'headerimg' =>  $userinfo->headimgurl,                         //头像
        'openid'    =>  $userinfo->openid,                             //微信openid
        'regtime'   =>  time(),
        'if_focus'  =>  $if_focus

    );

    file_put_contents("./file.txt", "insert_data_".date("Y-m-d H:i:s").print_r($insert_data, TRUE), FILE_APPEND);
//    die;
    $insert_id      = $db ->insert("user",$insert_data);
    $user_id        = $insert_id;

}else{
    $user_id        = $user_res['user_id'];
    $if_focus       = $user_res['if_focus'];
}

$_SESSION['user_id'] = $user_id;

header("Location:"."./add_dev.php");
exit;