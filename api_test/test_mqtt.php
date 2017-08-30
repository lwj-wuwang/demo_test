<?php
/**
 * Created by PhpStorm.
 * User: lvwenjing
 * Date: 2017/7/11
 * Time: 10:19
 */
error_reporting(E_ALL ^ E_DEPRECATED);
//session_start();
header("Content-type:text/html; charset=utf-8");
date_default_timezone_set('Asia/Chongqing');
require_once "./Onepush/util.php";
require_once "./table_config.php";
require_once "../model.php";
require_once '../iot_php/OneNetApi.php';
require_once "../func.php";


//$raw_input = $GLOBALS['HTTP_RAW_POST_DATA'];
$raw_input = file_get_contents('php://input');
$resolved_body = Util::resolveBody($raw_input);
//echo $resolved_body;
file_put_contents('./data_mqtt.txt',date('Y-m-d H:i:s').print_r('开始打印',true).PHP_EOL,FILE_APPEND);
$tableClass = new table($config);
$memcache   = new Memcache;
$memcache_obj = $memcache->connect("localhost", 11211);

$resolved_body = Array
(
    0 => Array
    (
        'at' => 1503454458237,
        'type' => 1,
        'ds_id' => 'humi',
        'value' => 57,
        'dev_id' => 11306166
        ),

    1 => Array
    (
        'at' => 1503454466754,
        'type' => 1,
        'ds_id' => 'humi',
        'value' => 57,
        'dev_id' => 11306166
        ),

    2 => Array
(
        'at' => 1503454475274,
        'type' => 1,
        'ds_id' => 'humi',
        'value' => 57,
        'dev_id' => 11306166
        ),

    3 => Array
(
        'at' => 1503454483789,
        'type' => 1,
        'ds_id' => 'humi',
        'value' => 57,
        'dev_id' => 11306166
        )

);
//判断推送数据是否为空，为空时则告警
if(!empty($resolved_body)){

    if($resolved_body['type'] == 2){//数据为设备在线信息
        $resolved_body['at'] = date('Y-m-d H:i:s',$resolved_body['at']/1000);

    }else{//数据流信息
        $str        = date('YmdHis').'_'.$resolved_body[0]['ds_id'];
        $startArr   = reset($resolved_body);
        $endArr     = end($resolved_body);
        $ds_id      = $endArr['ds_id'];//数据流ID
        file_put_contents('./data_mqtt_at.txt',date('Y-m-d H:i:s').'_ds_'.print_r($ds_id,true).PHP_EOL,FILE_APPEND);

        $startArr['ident'] = $str;
        $endArr['ident']   = $str;

        unset($startArr['type']);
        unset($endArr['type']);
        $start_res  = $tableClass->insert('data_str_mqtt',$startArr);
        $end_res    = $tableClass->insert('data_str_mqtt',$endArr);

        $list = array();
        foreach($resolved_body as $key => $val){
            unset($val['type']);
            if($key == 0){
                $cache_data = $memcache->get($ds_id);
                file_put_contents('./data_mqtt_at.txt',date('Y-m-d H:i:s').'_sess_'.print_r($cache_data,true).PHP_EOL,FILE_APPEND);
                if(isset($cache_data) ){
                    $diff0 = $val['at']-$cache_data['at'];
                    file_put_contents('./data_mqtt_at.txt',date('Y-m-d H:i:s').'_'.$key.$val['ds_id'].'_减_'.print_r($diff0,true).PHP_EOL,FILE_APPEND);
                    if( ($val['at']-$cache_data['at']) > 10000 ){//判断数据的间隔时间是否超时
                        $list[] = $cache_data;
                        $list[] = $val;

                        $data = array(
                            'error_type' => '1',
                            'errorTime'  => time(),
                            'reason'     => 2
                        );

                        $insert_id = $tableClass->insert('data_error_mqtt',$data);

                        //发送短信经过提醒
                        $result = send_message('数据间隔过长, 可能存在丢失');
                        if($result['result'] == '10701'){
                            $updata = array('if_send_msg' => 1);
                            $res    = $tableClass->update('data_error_mqtt',$updata,"id='{$insert_id}'");
                        }

                    }
                }
            }else{
                $diff = $val['at']-$resolved_body[$key-1]['at'];
                file_put_contents('./data_mqtt_at.txt',date('Y-m-d H:i:s').'_'.$key.$val['ds_id'].'_减_'.print_r($diff,true).PHP_EOL,FILE_APPEND);
                if($diff > 10000 ){//判断数据的间隔时间是否超时
                    $list[] = $resolved_body[$key-1];
                    $list[] = $val;

                    $data = array(
                        'error_type' => '1',
                        'errorTime'  => time(),
                        'reason'     => 2
                    );

                    $insert_id = $tableClass->insert('data_error_mqtt',$data);

                    //发送短信经过提醒
                    $result = send_message('数据间隔过长, 可能存在丢失');
                    if($result['result'] == '10701'){
                        $updata = array('if_send_msg' => 1);
                        $res    = $tableClass->update('data_error_mqtt',$updata,"id='{$insert_id}'");
                    }

                }

            }
            //$resolved_body[$key]['at'] = date('Y-m-d H:i:s',$val['at']/1000);
        }

        $newlist = unique($list);
        //判断是否存在超时的数据
        if(!empty($newlist)){
            foreach($newlist as $ke =>$va){

                //判断数据是否已经入库
                $getlist = $tableClass ->getList('data_lag_mqtt','*',"at={$va['at']}");
                if(empty($getlist)){
                    $res = $tableClass ->insert('data_lag_mqtt',$va);
                }
            }
        }
        //unset($_SESSION[$ds_id]);
        $cache_re = $memcache->set($ds_id, $endArr);
        //$_SESSION[$ds_id] = $endArr;

        file_put_contents('./data_mqtt.txt',date('Y-m-d H:i:s').print_r($cache_re,true).PHP_EOL,FILE_APPEND);
        $new_resolved = array();
        foreach($resolved_body as $ke=>$va){
            $va['at'] = date('Y-m-d H:i:s',$va['at']/1000);
            $new_resolved[$ke] = $va;
        }
    }

    file_put_contents('./data_mqtt.txt',print_r($new_resolved,true).PHP_EOL,FILE_APPEND);

}else{

    $time    = time();
    $dev_id  = '11306166';
    $apikey  = "v6u5jR8k6j0yYzoslPiwJrIIk9k=";

    $oneOb   = new OneNetApi($apikey);
    $dataArr = $oneOb ->device($dev_id);

    $reason = 0;
    if(is_array($dataArr) && $dataArr['online'] == false ){
        $reason = 1;
    }

    $data = array(
        'error_type' => '1',
        'errorTime'  => $time,
        'reason'     => $reason
    );

    $insert_id = $tableClass->insert('data_error_mqtt',$data);

    //短信报警提醒
    $data_info = "数据为空";

    $result = send_message($data_info);
    if($result['result'] == '10701'){
        $updata = array('if_send_msg' => 1);
        $res    = $tableClass->update('data_error_mqtt',$updata,"id='{$insert_id}'");
    }

}

file_put_contents('./data_mqtt.txt',date('Y-m-d H:i:s').print_r('结束打印',true).PHP_EOL,FILE_APPEND);

