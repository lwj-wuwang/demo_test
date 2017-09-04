<?php
/**
 * Created by PhpStorm.
 * User: lvwenjing
 * Date: 2017/5/31
 * Time: 10:57
 */
header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('Asia/Chongqing');
require_once "./func.php";
require_once './iot_php/OneNetApi.php';

/*$memcache_obj = memcache_connect("localhost", 11211);
$memcache_obj->set('key0', '测试', false, 30);
$memcache_obj->set('key0', '测试0', false, 30);
echo  $memcache_obj->get("key0");
die;

$a = 1503452281895;
$b = 1503452290411;
$c = 1503452298930;
echo $b-$a;
echo '<pre>';
echo $c-$b;die;*/
/*echo date('y-m-d H:i:s',1503995285);die;

//$apikey = '8D=h5AFr8ueFS8XWX4=o=L7u9M4=';
$apikey = 'iQoBW8WTNcZ18MHPcQYTfLMOpTY=';
$apiurl = 'http://api.heclouds.com';
$OneApi = new OneNetApi($apikey);
$error_code = 0;
$error = '';
$dev_id = 5280463;
//$dev_id = 7480650;
//$ds_id  = array('id'=>'test1');
$ds_id  = 'location';

$res = array();
//$ds_id  = 'location';
//$ds_id  = 'bin_data';
$result = $OneApi->datastream_delete($dev_id,$ds_id);
//$result = $OneApi->datastream_add($dev_id,$ds_id);
if (empty($result)) {
    //处理错误信息
    $error_code = $OneApi->error_no();
    $error = $OneApi->error();
    $res['error_code'] = $error_code;
    $res['error']      = $error;
    echo '<pre>';
    print_r($res);die;
}


//$result = $OneApi->datastream_of_dev($dev_id);
echo '<pre>';
print_r($result);
die;*/

//session_start();
/*$time = 1502677738;
echo date('Y-m-d H:i:s',$time);*/
/*$_SESSION['red'] = 12345;
$_SESSION['blue'] = 'asdfg';
unset($_SESSION['red']);
$_SESSION['red'] = 54321;
debug($_SESSION['red']);


//短信报警提醒*/
$data_info = "数据为空";
$dx_url    = "http://cs.37jy.com/demo_test/datapush.php";
$sicode    = "cc71c15b69f14dc89620b5ca795f0d5e";
$mobiles   = "13368233580";
$get_url   = "http://api.sms.heclouds.com/tempsmsSend?sicode={$sicode}&mobiles={$mobiles}&tempid=10862&data={$data_info}&url={$dx_url}";

$result    = get_html($get_url);
file_put_contents('./test.txt',print_r($result,true).PHP_EOL,FILE_APPEND);
$result    = json_decode($result,true);
debug($result);

/*$apikey = 'j29Msh=PJ6cReT0sLoHnehqu13I=';
$apiurl = 'http://api.heclouds.com';
$oneOb = new OneNetApi($apikey, $apiurl);

$listdata = $oneOb->device_list(1,30,"0x0000001609004900");

debug($listdata);*/
/*
$time_str = 1502687769857;//2017-08-14 13:16:09 powder_quantity_b
$time_str = 1502687765757;//2017-08-14 13:16:05 powder_quantity_c
$time_str = 1502687751556;//2017-08-14 13:15:51 alarm_output
$time_str = 1502687746317;//2017-08-14 13:15:46 cup_rest
$time_str = 1502682223689;//2017-08-14 11:43:43 cup_rest
$time_str = 1502682199549;//2017-08-14 11:43:19 cup_rest
$time_str = 1502682066568;//2017-08-14 11:41:06 powder_quantity_b
$time_str = 1502682060528;//2017-08-14 11:41:00 powder_quantity_c
$time_str = 1502682038870;//2017-08-14 11:40:38 alarm_output
$time_str = 1502682035830;//2017-08-14 11:40:35 cup_rest
$time_str = 1502681789398;//2017-08-14 11:36:29 cup_rest
$time_str = 1502681233568;//2017-08-14 11:27:13 powder_quantity_b
$time_str = 1502681227725;//2017-08-14 11:27:07 powder_quantity_c
$time_str = 1502681210901;//2017-08-14 11:26:50 alarm_output
$time_str = 1502681208881;//2017-08-14 11:26:48 cup_rest
$time_str = 1502681040424;//2017-08-14 11:24:00 powder_quantity_b
$time_str = 1502681035984;//2017-08-14 11:23:55 powder_quantity_c
$time_str = 1502681017664;//2017-08-14 11:23:37 alarm_output
$time_str = 1502681015664;//2017-08-14 11:23:35 cup_rest
$time_str = 1502680553286;//2017-08-14 11:15:53 powder_quantity_b
$time_str = 1502680550826;//2017-08-14 11:15:50 powder_quantity_c
$time_str = 1502680550825;//2017-08-14 11:15:50 alarm_output
$time_str = 1502680550825;//2017-08-14 11:15:50 cup_rest
$time_str = 1502677318596;//2017-08-14 10:21:58 cup_rest
$time_str = 1502676432376;//2017-08-14 10:07:12 cup_rest
$time_str = 1502687801665;//2017-08-14 13:16:41 lock_status
$time_str = 1502687799918;//2017-08-14 13:16:39 alarm_sensor
$time_str = 1502682108911;//2017-08-14 11:41:48 lock_status
$time_str = 1502682105249;//2017-08-14 11:41:45 alarm_sensor
$time_str = 1502681271845;//2017-08-14 11:27:51 lock_status
$time_str = 1502681263904;//2017-08-14 11:27:43 alarm_sensor
$time_str = 1502681084142;//2017-08-14 11:24:44 lock_status
$time_str = 1502681082625;//2017-08-14 11:24:42 alarm_sensor
$time_str = 1502680555965;//2017-08-14 11:15:55 alarm_sensor
$time_str = 1502680555965;//2017-08-14 11:15:55 lock_status
$time_str = 1502691737754;//2017-08-14 14:22:17 powder_quantity_e
$time_str = 1502687781879;//2017-08-14 13:16:21 powder_quantity_d
$time_str = 1502687763738;//2017-08-14 13:16:03 powder_quantity_d
$time_str = 1502687759158;//2017-08-14 13:15:59 powder_quantity_e
$time_str = 1502682080368;//2017-08-14 11:41:20 powder_quantity_d
$time_str = 1502682054507;//2017-08-14 11:40:54 powder_quantity_d
$time_str = 1502682049869;//2017-08-14 11:40:49 powder_quantity_e
$time_str = 1502681245664;//2017-08-14 11:27:25 powder_quantity_d
$time_str = 1502681223504;//2017-08-14 11:27:03 powder_quantity_d
$time_str = 1502681221524;//2017-08-14 11:27:01 powder_quantity_e
$time_str = 1502681054584;//2017-08-14 11:24:14 powder_quantity_d
$time_str = 1502681034244;//2017-08-14 11:23:54 powder_quantity_d
$time_str = 1502681028884;//2017-08-14 11:23:48 powder_quantity_e
$time_str = 1502680553286;//2017-08-14 11:15:53 powder_quantity_d
$time_str = 1502680550826;//2017-08-14 11:15:50 powder_quantity_e
$time_str = 1502680550826;//2017-08-14 11:15:50 powder_quantity_d

*/





/*$time_str = 1503387616800;
echo '<pre>';
//echo time();
echo '<pre>';
echo date('Y-m-d H:i:s',$time_str/1000);
echo '<pre>';
//$str = strtotime('17-07-25 10:44:26') * 1000;
die;*/


/*$str = 1500950670232;
$cha =  $str - $time_str;
echo $cha;
echo '<pre>';
if(($str - $time_str)>3000){
    echo '超时';
}else{
    echo 'ok';
}*/
$session = Array(
    'temp' => Array
    (
        'at' => '2017-08-22 15:40:16',
        'ds_id' => 'temp',
        'value' => 30,
        'dev_id' => 11306166,
        'ident' => '20170822154024_temp'
        )

);


$lastarr = array
(
    0 => Array(
        'at'    => '2017-08-22 15:39:51',
        'type'  => 1,
        'ds_id' => 'temp',
        'value' => 30,
        'dev_id' => 11306166
    ),

    1 => Array(
        'at'    => '2017-08-22 15:39:59',
        'type'  => 1,
        'ds_id' => 'temp',
        'value' => 30,
        'dev_id' => 11306166
    ),

    2 => Array(
        'at'    => '2017-08-22 15:40:08',
        'type'  => 1,
        'ds_id' => 'temp',
        'value' => 30,
        'dev_id' => 11306166
    ),

    3 => Array(
        'at'    => '2017-08-22 15:40:16',
        'type'  => 1,
        'ds_id' => 'temp',
        'value' => 30,
        'dev_id' => 11306166
    ),

);

$endarr = end($lastarr);
$endarr['at'] = strtotime($endarr['at']);
$_SESSION[$endarr['ds_id']] = $endarr['at'];

$arr = array
(
    0 => Array(
        'at'    => '2017-08-22 15:40:25',
        'type'  => 1,
        'ds_id' => 'temp',
        'value' => 30,
        'dev_id' => 11306166
        ),

    1 => Array(
        'at'    => '2017-08-22 15:40:33',
        'type'  => 1,
        'ds_id' => 'temp',
        'value' => 30,
        'dev_id' => 11306166
        ),

    2 => Array(
        'at'    => '2017-08-22 15:40:42',
        'type'  => 1,
        'ds_id' => 'temp',
        'value' => 30,
        'dev_id' => 11306166
        ),

    3 => Array(
        'at'    => '2017-08-22 15:40:50',
        'type'  => 1,
        'ds_id' => 'temp',
        'value' => 30,
        'dev_id' => 11306166
        ),

);

$endarr = end($arr);
$list = array();
$data = array();
foreach($arr as $key =>$val){
    $val['at'] = strtotime($val['at']);
    if($key !=0){
        $arr[$key-1]['at'] = strtotime($arr[$key-1]['at']);
        if( $val['at'] - $arr[$key-1]['at'] >10 ){
            $list[$key] = '超时';
        }
    }else{
        if( $val['at'] - $_SESSION[$endarr['ds_id']] >10 ){
            $list[$key] = '超时';
        }
    }

//    $val['at'] = date('Y-m-d H:i:s',$val['at']/1000);
    $data[$key] = $val;
}
echo '<pre>';
print_r($list);
debug($data);
//debug($list);

$arr1 = array
(
    0 => Array(
        'at'    => 1501199016781,
        'type'  => 1,
        'ds_id' => 'yellow_statu',
        'value' => 0,
        'dev_id' => 10072873
    ),

    1 => Array(
        'at'    => 1501199018412,
        'type'  => 1,
        'ds_id' => 'yellow_statu',
        'value' => 0,
        'dev_id' => 10072873
    ),

    2 => Array(
        'at'    => 1501199018412,
        'type'  => 1,
        'ds_id' => 'yellow_statu',
        'value' => 0,
        'dev_id' => 10072873
    ),

    3 => Array(
        'at'    => 1501199021677,
        'type'  => 1,
        'ds_id' => 'yellow_statu',
        'value' => 0,
        'dev_id' => 10072873
    ),

    4 => Array(
        'at'    => 1501199021677,
        'type'  => 1,
        'ds_id' => 'yellow_statu',
        'value' => 0,
        'dev_id' => 10072873
    )
);
$arr2 = unique($arr1);
debug($arr2);

