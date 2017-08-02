<?php
/**
 * Created by PhpStorm.
 * User: lvwenjing
 * Date: 2017/5/31
 * Time: 10:57
 */
header("Content-type: text/html; charset=utf-8");
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

date_default_timezone_set('Asia/Chongqing');
$a = 1466133706841;
echo date('Y-m-d H:i:s',$a/1000);
echo '<pre>';
echo date('Y-m-d H:i:s',1501205306522/1000);
//echo date('Y-m-d H:i:s','15016386202803');
echo '<pre>';
echo date('Y-m-d H:i:s','1501636616');
echo '<pre>';
echo time();
echo '<pre>';
echo microtime(true);
?>



