<?php
/**
 * Created by PhpStorm.
 * User: lvwenjing
 * Date: 2017/5/31
 * Time: 10:57
 */
header("Content-type: text/html; charset=utf-8");
require_once "./func.php";

//短信报警提醒
$data_info = "数据为空";
$dx_url    = "http://cs.37jy.com/demo_test/datapush.php";
$sicode    = "cc71c15b69f14dc89620b5ca795f0d5e";
$mobiles   = "13368233580";
$get_url   = "http://api.sms.heclouds.com/tempsmsSend?sicode={$sicode}&mobiles={$mobiles}&tempid=10862&data={$data_info}&url={$dx_url}";

$result    = get_html($get_url);
file_put_contents('./test.txt',print_r($result,true).PHP_EOL,FILE_APPEND);
$result    = json_decode($result,true);


?>



