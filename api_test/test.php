<?php
/**
 * Created by PhpStorm.
 * User: lvwenjing
 * Date: 2017/7/11
 * Time: 10:19
 */
error_reporting(E_ALL ^ E_DEPRECATED);
header("Content-type:text/html; charset=utf-8");
date_default_timezone_set('Asia/Chongqing');
require_once "./Onepush/util.php";
require_once "./table_config.php";
require_once "../model.php";
require_once '../iot_php/OneNetApi.php';
require_once "../func.php";


$raw_input = file_get_contents('php://input');
$resolved_body = Util::resolveBody($raw_input);
//echo $resolved_body;
file_put_contents('./data.txt',date('Y-m-d H:i:s').print_r('开始打印',true).PHP_EOL,FILE_APPEND);
$tableClass = new table($config);
$memcache   = new Memcache;
$memcache_obj = $memcache->connect("localhost", 11211);
file_put_contents('./data_at.txt',date('Y-m-d H:i:s').print_r($memcache_obj,true).PHP_EOL,FILE_APPEND);

//判断推送数据是否为空，为空时则告警
if(!empty($resolved_body)){

    if($resolved_body['type'] == 2){//数据为设备在线信息
        $resolved_body['at'] = date('Y-m-d H:i:s',$resolved_body['at']/1000);
        $new_resolved = $resolved_body;
        $memcache->delete('green_statu');
        $memcache->delete('blue_statu');
        $memcache->delete('red_statu');
        $memcache->delete('yellow_statu');

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

        $err_num = date('YmdHis').rand(1000,9999);//错误编号
        $memcache->set('num_'.$ds_id,$err_num);
        $list = array();
        foreach($resolved_body as $key => $val){
            unset($val['type']);
            if($key == 0){
                $cache_data = $memcache->get($ds_id);
                file_put_contents('./data_at.txt',date('Y-m-d H:i:s').'_sess_'.print_r($cache_data,true).PHP_EOL,FILE_APPEND);
                if( !empty($cache_data) ){
                    $diff0 = $val['at']-$cache_data['at'];
                    file_put_contents('./data_at.txt',date('Y-m-d H:i:s').'_'.$key.$val['ds_id'].'_减_'.print_r($diff0,true).PHP_EOL,FILE_APPEND);
                    if( ($val['at']-$cache_data['at']) > 5000 ){//判断数据的间隔时间是否超时
                        $last_data = $cache_data;
                        unset($last_data['ident']);

                        $list[] = $last_data;
                        $list[] = $val;
                        $err	= $memcache->get('num_'.$val['ds_id']);
                        file_put_contents('./data_at.txt',date('Y-m-d H:i:s').'_'.$key.$val['ds_id'].'_err_'.print_r($err,true).PHP_EOL,FILE_APPEND);
                        $data = array(
                            'error_type' => '1',
                            'errorTime'  => time(),
                            'reason'     => 2,
                            'error_num'  => $err
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
                $diff = $val['at']-$resolved_body[$key-1]['at'];
                file_put_contents('./data_at.txt',date('Y-m-d H:i:s').'_'.$key.$val['ds_id'].'_减_'.print_r($diff,true).PHP_EOL,FILE_APPEND);
                if( $diff > 5000 ){//判断数据的间隔时间是否超时

                    $list[] = $resolved_body[$key-1];
                    $list[] = $val;
                    $err	= $memcache->get('num_'.$val['ds_id']);
                    file_put_contents('./data_at.txt',date('Y-m-d H:i:s').'_'.$key.$val['ds_id'].'_err_'.print_r($err,true).PHP_EOL,FILE_APPEND);
                    $data = array(
                        'error_type' => '1',
                        'errorTime'  => time(),
                        'reason'     => 2,
                        'error_num'  => $err
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

        }

        $new_list = unique($list);
        //判断是否存在超时的数据
        if(!empty($new_list)){
            foreach($new_list as $ke =>$va){
                $va['err_num'] = $memcache->get('num_'.$va['ds_id']);
                file_put_contents('./data_at.txt',date('Y-m-d H:i:s').'_'.$ke.$va['ds_id'].'_va_'.print_r($va,true).PHP_EOL,FILE_APPEND);
                //判断数据是否已经入库
                $lagList = $tableClass ->getList('data_lag','*',"at={$va['at']} AND ds_id='{$va['ds_id']}'");
                file_put_contents('./data_at.txt',date('Y-m-d H:i:s').'_延时数据查询_'.print_r($lagList,true).PHP_EOL,FILE_APPEND);
                if(empty($lagList)){
                    $res = $tableClass ->insert('data_lag',$va);
                    file_put_contents('./data_at.txt',date('Y-m-d H:i:s').'_延时数据入库_'.print_r($res,true).PHP_EOL,FILE_APPEND);
                }
            }
        }

        file_put_contents('./data_at.txt',date('Y-m-d H:i:s').'_数据延时list_'.print_r($new_list,true).PHP_EOL,FILE_APPEND);
        $cache_re = $memcache->set($ds_id, $endArr);
        file_put_contents('./data.txt',date('Y-m-d H:i:s').print_r($cache_re,true).PHP_EOL,FILE_APPEND);

        $new_resolved = array();
        foreach($resolved_body as $ke=>$va){
            $va['at'] = date('Y-m-d H:i:s',$va['at']/1000);
            $new_resolved[$ke] = $va;
        }
    }

    file_put_contents('./data.txt',print_r($new_resolved,true).PHP_EOL,FILE_APPEND);
    
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
    $data_info = "数据为空";
	
	$result = send_message($data_info);
	if($result['result'] == '10701'){
		$updata = array('if_send_msg' => 1);
		$res    = $tableClass->update('dev_error',$updata,"id='{$insert_id}'");
	}

}

file_put_contents('./data.txt',date('Y-m-d H:i:s').print_r('结束打印',true).PHP_EOL,FILE_APPEND);

