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

session_start();
//短信报警提醒
/*$data_info = "数据为空";
$dx_url    = "http://cs.37jy.com/demo_test/datapush.php";
$sicode    = "cc71c15b69f14dc89620b5ca795f0d5e";
$mobiles   = "13368233580";
$get_url   = "http://api.sms.heclouds.com/tempsmsSend?sicode={$sicode}&mobiles={$mobiles}&tempid=10862&data={$data_info}&url={$dx_url}";

$result    = get_html($get_url);
file_put_contents('./test.txt',print_r($result,true).PHP_EOL,FILE_APPEND);
$result    = json_decode($result,true);
debug($result);*/
/*$apikey = 'j29Msh=PJ6cReT0sLoHnehqu13I=';
$apiurl = 'http://api.heclouds.com';
$oneOb = new OneNetApi($apikey, $apiurl);

$listdata = $oneOb->device_list(1,30,"0x0000001609004900");

debug($listdata);*/
$time_str = 1500950665672;
$time_str = 1500950670232;
echo '<pre>';
//echo time();
echo '<pre>';
echo date('y-m-d H:i:s',$time_str/1000);
echo '<pre>';
//$str = strtotime('17-07-25 10:44:26') * 1000;
$str = 1500950670232;
$cha =  $str - $time_str;
echo $cha;
echo '<pre>';
if(($str - $time_str)>3000){
    echo '超时';
}else{
    echo 'ok';
}

$lastarr = array
(
    0 => Array(
        'at'    => 1501198998836,
        'type'  => 1,
        'ds_id' => 'yellow_statu',
        'value' => 0,
        'dev_id' => 10072873
    ),

    1 => Array(
        'at'    => 1501199000475,
        'type'  => 1,
        'ds_id' => 'yellow_statu',
        'value' => 0,
        'dev_id' => 10072873
    ),

    2 => Array(
        'at'    => 1501199002099,
        'type'  => 1,
        'ds_id' => 'yellow_statu',
        'value' => 0,
        'dev_id' => 10072873
    ),

    3 => Array(
        'at'    => 1501199003730,
        'type'  => 1,
        'ds_id' => 'yellow_statu',
        'value' => 0,
        'dev_id' => 10072873
    ),

    4 => Array(
        'at'    => 1501199005362,
        'type'  => 1,
        'ds_id' => 'yellow_statu',
        'value' => 0,
        'dev_id' => 10072873
    ),

    5 => Array(
        'at'    => 1501199006993,
        'type'  => 1,
        'ds_id' => 'yellow_statu',
        'value' => 0,
        'dev_id' => 10072873
    ),

    6 => Array(
        'at'    => 1501199008625,
        'type'  => 1,
        'ds_id' => 'yellow_statu',
        'value' => 0,
        'dev_id' => 10072873
    ),

    7 => Array(
        'at'    => 1501199010260,
        'type'  => 1,
        'ds_id' => 'yellow_statu',
        'value' => 0,
        'dev_id' => 10072873
    ),

    8 => Array(
        'at'    => 1501199011888,
        'type'  => 1,
        'ds_id' => 'yellow_statu',
        'value' => 0,
        'dev_id' => 10072873
    ),

    9 => Array
    (
        'at'    => 1501199013519,
        'type'  => 1,
        'ds_id' => 'yellow_statu',
        'value' => 0,
        'dev_id' => 10072873
    ),
    10 => Array(
        'at'    => 1501199015148,
        'type'  => 1,
        'ds_id' => 'yellow_statu',
        'value' => 0,
        'dev_id' => 10072873
    )
);

$endarr = end($lastarr);
$_SESSION[$endarr['ds_id']] = $endarr['at'];

$arr = array
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
            'at'    => 1501199020048,
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
            'at'    => 1501199023309,
            'type'  => 1,
            'ds_id' => 'yellow_statu',
            'value' => 0,
            'dev_id' => 10072873
        ),

    5 => Array(
            'at'    => 1501199024940,
            'type'  => 1,
            'ds_id' => 'yellow_statu',
            'value' => 0,
            'dev_id' => 10072873
        ),

    6 => Array(
            'at'    => 1501199026570,
            'type'  => 1,
            'ds_id' => 'yellow_statu',
            'value' => 0,
            'dev_id' => 10072873
        ),

    7 => Array(
            'at'    => 1501199028202,
            'type'  => 1,
            'ds_id' => 'yellow_statu',
            'value' => 0,
            'dev_id' => 10072873
        ),

    8 => Array(
            'at'    => 1501199029833,
            'type'  => 1,
            'ds_id' => 'yellow_statu',
            'value' => 0,
            'dev_id' => 10072873
        ),

    9 => Array
(
            'at'    => 1501199031465,
            'type'  => 1,
            'ds_id' => 'yellow_statu',
            'value' => 0,
            'dev_id' => 10072873
        ),
    10 => Array(
            'at'    => 1501199033096,
            'type'  => 1,
            'ds_id' => 'yellow_statu',
            'value' => 0,
            'dev_id' => 10072873
        ),

    11 => Array(
            'at'    => 1501199034733,
            'type'  => 1,
            'ds_id' => 'yellow_statu',
            'value' => 0,
            'dev_id' => 10072873
        ),

    12 => Array(
            'at'    => 1501199036360,
            'type'  => 1,
            'ds_id' => 'yellow_statu',
            'value' => 0,
            'dev_id' => 10072873
        ),

    13 => Array(
            'at'    => 1501199037990,
            'type'  => 1,
            'ds_id' => 'yellow_statu',
            'value' => 0,
            'dev_id' => 10072873
        ),

    14 => Array(
            'at'    => 1501199039620,
            'type'  => 1,
            'ds_id' => 'yellow_statu',
            'value' => 0,
            'dev_id' => 10072873
        ),

    15 => Array(
            'at'    => 1501199041253,
            'type'  => 1,
            'ds_id' => 'yellow_statu',
            'value' => 0,
            'dev_id' => 10072873
        ),

    16 => Array(
            'at'    => 1501199042888,
            'type'  => 1,
            'ds_id' => 'yellow_statu',
            'value' => 0,
            'dev_id' => 10072873
        ),

    17 => Array(
            'at'    => 1501199044516,
            'type'  => 1,
            'ds_id' => 'yellow_statu',
            'value' => 0,
            'dev_id' => 10072873
        ),

);

$endarr = end($arr);
$list = array();
$data = array();
foreach($arr as $key =>$val){
    if($key !=0){
        if( $val['at'] - $arr[$key-1]['at'] >3000 ){
            $list[$key] = '超时';
        }
    }else{
        if( $val['at'] - $_SESSION[$endarr['ds_id']] >3000 ){
            $list[$key] = '超时';
        }
    }

    $val['at'] = date('Y-m-d H:i:s',$val['at']/1000);
    $data[$key] = $val;
}
echo '<pre>';
print_r($list);
//debug($data);
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


function unique($data = array()){
    $tmp = array();
    foreach($data as $key => $value){
        //把一维数组键值与键名组合
        foreach($value as $key1 => $value1){
            $value[$key1] = $key1 . '_|_' . $value1;//_|_分隔符复杂点以免冲突

        }
        $tmp[$key] = implode(',|,', $value);//,|,分隔符复杂点以免冲突

    }

    //对降维后的数组去重复处理
    $tmp = array_unique($tmp);

    //重组二维数组
    $newArr = array();
    $tmp_v3 = array();
    foreach($tmp as $k => $tmp_v){
        $tmp_v2 = explode(',|,', $tmp_v);

        foreach($tmp_v2 as $k2 => $v2){
            $v2 = explode('_|_', $v2);

            $tmp_v3[$v2[0]] = $v2[1];
        }
        $newArr[$k] = $tmp_v3;

    }
    return $newArr;
}