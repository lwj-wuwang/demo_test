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
require_once "../func.php";


$raw_input = $GLOBALS['HTTP_RAW_POST_DATA'];

$resolved_body = Util::resolveBody($raw_input);
if(!empty($resolved_body)){
    
    if($resolved_body['type'] == 2){
        $resolved_body['at'] = date('Y-m-d H:i:s',$resolved_body['at']);

    }else{
        foreach($resolved_body as $key => $val){
            $resolved_body[$key]['at'] = date('Y-m-d H:i:s',$val['at']);
        }
    }
    file_put_contents('./data_1.txt',date('Y-m-d H:i:s').print_r('开始打印',true).PHP_EOL,FILE_APPEND);
    file_put_contents('./data_1.txt',print_r($resolved_body,true).PHP_EOL,FILE_APPEND);
    file_put_contents('./data_1.txt',date('Y-m-d H:i:s').print_r('结束打印',true).PHP_EOL,FILE_APPEND);
    die;
}


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

    $insert_id = $tableClass->insert('dev_error',$data);



    //短信报警提醒
    $data_info = "数据为空";
    $dx_url    = "http://cs.37jy.com/demo_test/datapush.php";
    $sicode    = "cc71c15b69f14dc89620b5ca795f0d5e";
    $mobiles   = "13368233580";
    $get_url   = "http://api.sms.heclouds.com/tempsmsSend?sicode={$sicode}&mobiles={$mobiles}&tempid=10862&data={$data_info}&url={$dx_url}";

    $result    = get_html($get_url);
    file_put_contents('./test.txt',print_r($result,true).PHP_EOL,FILE_APPEND);
    $result    = json_decode($result,true);
    if($result['result'] == '10701'){
        $updata = array('if_send_msg' => 1);
        $res    = $tableClass->update('dev_error',$updata,"id='{$insert_id}'");
    }

}

