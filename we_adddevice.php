<?php
/**
 * Created by PhpStorm.
 * User: lvwenjing
 * Date: 2017/5/25
 * Time: 16:57
 * url:http://cs.37jy.com/demo_test/we_adddevice.php?device_sn=201705261443&version=XQ270a
 */

header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('Asia/Chongqing');

require_once "./func.php";
require_once "./config.php";
require_once "./iot_php/OneNetApi.php";
require_once "./model.php";



session_start();

if(!empty($_GET['device_sn']) && !empty($_GET['version'])){
    $_SESSION['dev']['sn']      = $_GET['device_sn'];
    $_SESSION['dev']['name']    = $_GET['version'];
}

$OneClass       = new OneNetApi(MASTER_KEY,API_URL);
$db             = new table();
//file_put_contents("./file.txt", date("Y-m-d H:i:s")."session".print_r($_SESSION, TRUE), FILE_APPEND);

$jump_url = site_url(true)."/demo_test/error.php";


if( !isset($_SESSION['dev']['sn'] ) || empty($_SESSION['dev']['name'] )){
    MobileErrorJS("非法请求",$jump_url);die;
}




$appid        = APPId;
$appsecret    = SECRET;
$redirect_url = urlencode(REDIRECT_URL);
$oauth2_url   = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri={$redirect_url}&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";

$openid = $_SESSION['wx_openid'];
if(empty($openid)){
    //判断code
    if(empty($_GET['code'])){
        header("Location:".$oauth2_url);
        exit;

    }else{
        $code           = $_GET['code'];
    }

//获取微信的access_token和openid
    $tokenArr           = get_access_token($code,APPId,SECRET);
    if(empty($tokenArr)){
        header("Location:".$oauth2_url);
        exit;
    }

    $access_token   = $tokenArr['access_token'];
    $openid         = $tokenArr['openid'];
    $_SESSION['wx_openid']  = $openid;

}

if( !isset($_SESSION['wx_openid'] ) || empty($_SESSION['wx_openid'] )){
    MobileErrorJS("非法请求",$jump_url);die;
}


//查询用户是否已注册
$user_res   = $db->getList('user','*',"openid={$openid}");
if(!$user_res){ //用户未注册
    //获取微信用户信息
    $userinfo           = get_user_info($access_token,$openid);

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
    $insert_id      = $db ->insert("user",$insert_data);
    $user_id        = $insert_id;

}else{
    $user_id        = $user_res[0]['user_id'];
    $if_focus       = $user_res[0]['if_focus'];
}

$_SESSION['user_id'] = $user_id;



//查询设备是否已经注册
$master_key     = MASTER_KEY;
$OneDevUrl      = API_URL."/devices?auth_info={$_SESSION['dev']['sn']}";
$header         = array("api-key:{$master_key}");
$result         = get_html($OneDevUrl,$header);
$devArr         = @json_decode($result,true);
//file_put_contents("./file.txt", date("Y-m-d H:i:s")."devOb".print_r($devArr, TRUE), FILE_APPEND);

if($devArr['error']='succ' && !empty($devArr['data']['devices'])){//判断设备已注册
    /*header("Location:"."./dev_index.php");
    exit;*/
    MobileErrorJS("设备已存在！","./listdevice.php?unionid={$if_focus}");
    exit;
}else{
    //接入OneNET 完成设备新增

    $dev_data       = array(
        'auth_info'     => $_SESSION['dev']['sn'],
        'title'         => "设备 " . $_SESSION['dev']['name'],
        'protocol'      => PROTOCOL,
        'private'       => true
    );

    $res        = $OneClass->device_add(json_encode($dev_data));
    $error_code = 0;
    $error      = '';

    if(!empty($res)){
        $device_id  = $res['device_id'];
    }else{
        $error_code = $OneClass->error_no();
        $error      = $OneClass->error();
        MobileErrorJS($error,$jump_url);
        exit;

    }

    //设备信息添加
    $dev_data = array(
        'device_sn'      => $_SESSION['dev']['sn'],
        'device_name'    =>  "设备 " . $_SESSION['dev']['name'],
        'iot_device_id'  => $device_id,
        'addtime'        => time()
    );

    $last_dev = $db ->insert("device_info", $dev_data);

    //用户表添加设备云ID
    $up_data  = array('device_id'=>$device_id);
    $up_res   = $db->update('user',$up_data,"user_id= {$_SESSION['user_id']}");

    if($last_dev && $up_res){
        MobileErrorJS("设备注册成功！","./listdevice.php?unionid={$if_focus}");
        exit;
    }else{
        MobileErrorJS("设备注册失败！",$jump_url);
        exit;
    }


}


