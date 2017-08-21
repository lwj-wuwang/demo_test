<?php
/**
 * Created by PhpStorm.
 * User: lvwenjing
 * Date: 2017/7/11
 * Time: 10:19
 */
error_reporting(E_ALL ^ E_DEPRECATED);
session_start();
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

$tableClass = new table($config);

//判断推送数据是否为空，为空时则告警
if(!empty($resolved_body)){

    if($resolved_body['type'] == 2){//数据为设备在线信息
        $resolved_body['at'] = date('Y-m-d H:i:s',$resolved_body['at']/1000);

    }else{//数据流信息
        $str        = date('YmdHis').'_'.$resolved_body[0]['ds_id'];
        $startArr   = reset($resolved_body);
        $endArr     = end($resolved_body);
        $ds_id      = $endArr['ds_id'];//数据流ID

        $startArr['ident'] = $str;
        $endArr['ident']   = $str;

        unset($startArr['type']);
        unset($endArr['type']);
        $start_res  = $tableClass->insert('data_str',$startArr);
        $end_res    = $tableClass->insert('data_str',$endArr);

        $list = array();
        foreach($resolved_body as $key => $val){
            unset($val['type']);
            if($key == 0){
                if($_SESSION[$ds_id] ){
                    if( ($val['at']-$_SESSION[$ds_id]['at']) > 5000 ){//判断数据的间隔时间是否超时
                        $list[] = $_SESSION[$ds_id];
                        $list[] = $val;

                        $data = array(
                            'error_type' => '1',
                            'errorTime'  => time(),
                            'reason'     => 2
                        );

                        $insert_id = $tableClass->insert('dev_error',$data);

                        //发送短信经过提醒
                        $result = send_message('数据间隔过长, 可能存在丢失');
                        if($result['result'] == '10701'){
                            $updata = array('if_send_msg' => 1);
                            $res    = $tableClass->update('dev_error',$updata,"id='{$insert_id}'");
                        }

                    }
                }
            }else{
                if(($val['at'] - $resolved_body[$key-1]['at']) > 5000 ){//判断数据的间隔时间是否超时

                    $list[] = $resolved_body[$key-1];
                    $list[] = $val;

                    $data = array(
                        'error_type' => '1',
                        'errorTime'  => time(),
                        'reason'     => 2
                    );

                    $insert_id = $tableClass->insert('dev_error',$data);

                    //发送短信经过提醒
                    $result = send_message('数据间隔过长, 可能存在丢失');
                    if($result['result'] == '10701'){
                        $updata = array('if_send_msg' => 1);
                        $res    = $tableClass->update('dev_error',$updata,"id='{$insert_id}'");
                    }

                }
            }
            $resolved_body[$key]['at'] = date('Y-m-d H:i:s',$val['at']/1000);

        }

        $newlist = unique($list);
        //判断是否存在超时的数据
        if(!empty($newlist)){
            foreach($newlist as $ke =>$va){

                //判断数据是否已经入库
                $getlist = $tableClass ->getList('data_lag','*',"at={$va['at']}");
                if(empty($getlist)){
                    $va['addTime']  = time();
                    $res = $tableClass ->insert('data_lag',$va);
                }
            }
        }
        unset($_SESSION[$ds_id]);
        $_SESSION[$ds_id] = $endArr;
        file_put_contents('./data.txt',date('Y-m-d H:i:s').print_r('开始打印',true).PHP_EOL,FILE_APPEND);
        file_put_contents('./data.txt',date('Y-m-d H:i:s').print_r($_SESSION,true).PHP_EOL,FILE_APPEND);


    }

    file_put_contents('./data.txt',print_r($resolved_body,true).PHP_EOL,FILE_APPEND);
    file_put_contents('./data.txt',date('Y-m-d H:i:s').print_r('结束打印',true).PHP_EOL,FILE_APPEND);

}else{

    $time    = time();
    $dev_id  = '10072873';
    $apikey  = "OyvIPHO=yBK5p=h1sok0vDbRsKE=";

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

    $insert_id = $tableClass->insert('dev_error',$data);

    //短信报警提醒
    $result = send_message('数据为空');
    if($result['result'] == '10701'){
        $updata = array('if_send_msg' => 1);
        $res    = $tableClass->update('dev_error',$updata,"id='{$insert_id}'");
    }

}

