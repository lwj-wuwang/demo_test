<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/19 0019
 * Time: 16:09
 */

/**
 * 提交网页信息
 * @param $url
 * @return bool|mixed
 */
function get_html($url,$data='') {
    if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        if($data){
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $return = curl_exec($ch);
        curl_close($ch);
        return $return;
    }
    return false;
}

//获取access_token
function get_access_token($code,$appid,$appsecret){
    $url    = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$appsecret}&code={$code}&grant_type=authorization_code ";
    $result = get_html($url);
    return json_decode($result);
}

//获取用户信息
function get_user_info($access_token,$openid){
    $url    = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN ";
    $result = get_html($url);
    return json_decode($result);
}