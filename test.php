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
//短信报警提醒
/*$data_info = "数据为空";
$dx_url    = "http://cs.37jy.com/demo_test/datapush.php";
$sicode    = "cc71c15b69f14dc89620b5ca795f0d5e";
$mobiles   = "13368233580";
$get_url   = "http://api.sms.heclouds.com/tempsmsSend?sicode={$sicode}&mobiles={$mobiles}&tempid=10862&data={$data_info}&url={$dx_url}";

$result    = get_html($get_url);
file_put_contents('./test.txt',print_r($result,true).PHP_EOL,FILE_APPEND);
$result    = json_decode($result,true);*/

/*$apikey = 'j29Msh=PJ6cReT0sLoHnehqu13I=';
$apiurl = 'http://api.heclouds.com';
$oneOb = new OneNetApi($apikey, $apiurl);

$listdata = $oneOb->device_list(1,30,"0x0000001609004900");

debug($listdata);*/

$arr = array('a','b','c','d','e');
$arr = array(
    array(
        'at'    => '2017-08-02 15:26:22',
        'ds_id' => 'green_statu',
        'value' => 0
    ),
    array(
        'at'    => '2017-08-02 15:26:24',
        'ds_id' => 'green_statu',
        'value' => 0
    ),
    array(
        'at'    => '2017-08-02 15:26:25',
        'ds_id' => 'green_statu',
        'value' => 0
    ),
    array(
        'at'    => '2017-08-02 15:26:27',
        'ds_id' => 'green_statu',
        'value' => 0
    ),
);

print_r(reset($arr));
echo '<br>';
print_r(end($arr));
echo '<br>';

?>



