<?php
/**
 * Created by PhpStorm.
 * User: lvwenjing
 * Date: 2017/6/7
 * Time: 14:45
 */

require_once "./init.php";
session_start();

file_put_contents("./file.txt", "session_".date("Y-m-d H:i:s").print_r($_SESSION, TRUE), FILE_APPEND);die;
//查询用户信息
$userinfo   = $db->getList('user','*',"user_id='{$_SESSION['user_id']}'");
$if_focus   = $userinfo['if_focus'];
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
    $up_res   = $db->update('user',$up_data,"user_id= '{$_SESSION['user_id']}'");

    if($last_dev && $up_res){
        MobileErrorJS("设备注册成功！","./listdevice.php?unionid={$if_focus}");
        exit;
    }else{
        $jump_url = site_url(true)."/demo_test/error.php";
        MobileErrorJS("设备注册失败！",$jump_url);
        exit;
    }

}

