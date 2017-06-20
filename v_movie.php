<?php
/**
 * Created by PhpStorm.
 * User: lvwenjing
 * Date: 2017/6/16
 * Time: 11:36
 */

header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('Asia/Chongqing');
require_once './func.php';
require_once "./config.php";
require_once "./iot_php/OneNetApi.php";

$styleArr = array('古装剧','言情剧','武侠剧','偶像剧','家庭剧','青春剧','都市','喜剧','谍战剧','悬疑剧','罪案剧','穿越剧','宫廷剧','历史剧','神话剧','农村剧','奇幻','年代剧','商战剧','科幻剧');

echo '<pre>';
print_r($styleArr);
$i = 0;
$j = 0;
$data = array();
for($page = 35;$page<50;$page++) {

//    $page = 2;
    $oneClass = new OneNetApi(MASTER_KEY, API_URL);
    $devArr = $oneClass->device_list($page, 100);
    $devLists = $devArr['devices'];//设备列表


    if ($devLists) {
        $count = count($styleArr);
        foreach ($devLists as $key => $val) {
            $times = floor($key / $count);
            $datastreams[time()] = $styleArr[$key - $count * $times];
            $res = $oneClass->datapoint_add($val['id'], 'style', $datastreams);
            if ($res) {
                $i++;
            } else {
                array_push($data, $val['id']);
                $j++;
            }
        }
    }
}
    echo '第' . $page . '页，成功：' . $i . ",  失败：" . $j;
    echo '<pre>';
    print_r($data);
    die;


debug($data);
echo '<pre>';
print_r($devLists);