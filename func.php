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

include_once 'excel/common.inc.php';
include_once 'excel/PHPExcel/IOFactory.php';

function get_html($url,$host=null,$data='',$port='') {
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
        if($host){
            curl_setopt($ch,CURLOPT_HTTPHEADER,$host);
        }
        if($port){
            curl_setopt($ch, CURLOPT_PORT, $port);
        }
        $return = curl_exec($ch);
        curl_close($ch);
        return $return;
    }
    return false;
}

//获取access_token
function get_access_token($code,$appid,$appsecret){
    $url     = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$appsecret}&code={$code}&grant_type=authorization_code ";
    $result  = get_html($url);
    $content = @json_decode($result,true);
    if(is_array($content) && !empty($content)){
        $outArr  = array(
            'access_token' => $content['access_token'],
            'openid'       => $content['openid'],
        );

        return $outArr;
    }
    return false;
}

//获取用户信息
function get_user_info($access_token,$openid){
    $url    = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN ";
    $result = get_html($url);
    $userinfo = @json_decode($result,true);
    return $userinfo;
}


/***
 * 提示
 * @param string $Err       错误信息
 * @param string $URL       跳转地址
 * @param string $JavaScript        js代码
 */
function MobileErrorJS($Err="",$URL="",$JavaScript=""){
    echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
    echo "<script language='javascript'>";

    if( $Err!="" ){
        echo "alert('{$Err}');";
    }

    if( $JavaScript!="" ){
        echo $JavaScript;
        echo "</script>";
        exit;
    }

    if( $URL=="" ){
        echo "location='{$_SERVER['HTTP_REFERER']}'";
    }else{
        echo "location='{$URL}'";
    }
    echo "</script>";
    exit;
}


//获取网站的网站地址 或者访问地址
function site_url($host = false) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $url      = $protocol . $_SERVER[HTTP_HOST] . $_SERVER[REQUEST_URI];
    if ($host) {
        return $protocol . $_SERVER[HTTP_HOST];
    }
    return $url;
}


function parse_excel($file)
{
    global $bookFields;
   /* echo '<pre>';
    print_r($bookFields);die;*/
    if (!file_exists($file)) {
        output(-1, '“' . basename($file) . '” 文件不存在！');
    }

    $objPHPExcel = PHPExcel_IOFactory::load($file);

    $sh         = $objPHPExcel->getSheet(0);

    $maxRow     = $sh->getHighestRow();
    $maxCol     = $sh->getHighestColumn();

    if( $maxCol != 'L'){
        return false;
    }

    $data = $fields = array();

    for ($row = EXCEL_FIELD_ROW; $row <= $maxRow; $row++) {
        $rowData = $sh->rangeToArray('A' . $row . ':' . $maxCol . $row, FALSE, FALSE, FALSE);
        $tmpFields = array();
        foreach ($rowData[0] as $key => $val) {

            if (EXCEL_FIELD_ROW === $row) {
                $field = array_search($val, $bookFields);
                if ($field) {
                    $fields[$key] = $field;
                }
            } else if (EXCEL_FIELD_VALID_COL === $key && empty($val)) {
                continue 2;
            } else if (isset($fields[$key])) {
                $tmpFields[$fields[$key]] = $val;
            }
        }

        if (count($tmpFields)) {
            $data[] = $tmpFields;
        }
    }
    if (!count($data)) {
        output(-1, '解析数据为空！');
    }
    /*echo '<pre>';
    print_r($data);die;*/

    return $data;
}

function parse_movie_excel($file)
{
    global $movieFields;
    /* echo '<pre>';
     print_r($bookFields);die;*/
    if (!file_exists($file)) {
        output(-1, '“' . basename($file) . '” 文件不存在！');
    }

    $objPHPExcel = PHPExcel_IOFactory::load($file);

    $sh         = $objPHPExcel->getSheet(0);

    $maxRow     = $sh->getHighestRow();
    $maxCol     = $sh->getHighestColumn();

    if( $maxCol != 'E'){
        return false;
    }

    $data = $fields = array();

    for ($row = EXCEL_FIELD_ROW; $row <= $maxRow; $row++) {
        $rowData = $sh->rangeToArray('A' . $row . ':' . $maxCol . $row, FALSE, FALSE, FALSE);
        $tmpFields = array();
        foreach ($rowData[0] as $key => $val) {

            if (EXCEL_FIELD_ROW === $row) {
                $field = array_search($val, $movieFields);
                if ($field) {
                    $fields[$key] = $field;
                }
            } else if (EXCEL_FIELD_VALID_COL === $key && empty($val)) {
                continue 2;
            } else if (isset($fields[$key])) {
                $tmpFields[$fields[$key]] = $val;
            }
        }

        if (count($tmpFields)) {
            $data[] = $tmpFields;
        }
    }
    if (!count($data)) {
        output(-1, '解析数据为空！');
    }
    /*echo '<pre>';
    print_r($data);die;*/

    return $data;
}


function debug($data){
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    die;
}