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
require_once 'excel/common.inc.php';
require_once 'excel/PHPExcel/IOFactory.php';

set_time_limit(0);
//视频类型
$styleArr = array('古装剧','言情剧','武侠剧','偶像剧','家庭剧','青春剧','都市','喜剧','谍战剧','悬疑剧','罪案剧','穿越剧','宫廷剧','历史剧','神话剧','农村剧','奇幻','年代剧','商战剧','科幻剧');
$count    = count($styleArr);

//幕后文章类型
$artColumn = array('电影自习室','电影会客厅','拍摄','器材','VR','后期','综述');
$artCount  = count($artColumn);

$i = 0;
$j = 0;
$data = array();

//excel解析获取数据
/*$files    = "./excelFiles/vmovie/2017-06-23.xls";
$devLists = parse_movie_excel($files);*/
//debug($devLists);
/*if ($devLists) {
    $count = count($styleArr);
    foreach ($devLists as $key => $val) {
        $times = floor($key / $count);
        $datastreams[time()] = $styleArr[$key - $count * $times];
        $res = $oneClass->datapoint_add($val['deviceId'], 'style', $datastreams);
        if ($res) {
            $i++;
        } else {
            array_push($data, $val['deviceId']);
            $j++;
        }
    }
}*/
$date = '2017-06-07';

$oneClass = new OneNetApi(MASTER_KEY, API_URL);
$devArr   = $oneClass->device_list(1, 100,null,null,null,null,null,null,$date,$date);
$totalCount = $devArr['total_count'];
$countPage  = ceil($totalCount/100);
//debug($countPage);

//设备查询获取数据
for($page=1;$page<$countPage+1;$page++){

    $oneClass = new OneNetApi(MASTER_KEY, API_URL);
    $devArr   = $oneClass->device_list($page, 100,null,null,null,null,null,null,$date,$date);
    $devLists = $devArr['devices'];

    if ($devLists) {
        foreach ($devLists as $key => $val) {
//            $times = floor($key / $count);
            $artKey = floor($key / $artCount);
//            $datastreams[time()] = $styleArr[$key - $count * $times];
            $artstreams[time()]  = $artColumn[$key - $artCount * $artKey];
//            $res = $oneClass->datapoint_add($val['id'], 'style', $datastreams);
            $artre = $oneClass->datapoint_add($val['id'], 'article_column', $artstreams);
//            if ($res && $artre) {
            if ($artre) {
                $i++;
            } else {
                array_push($data, $val['id']);
                $j++;
            }
        }
    }
}

//debug($devLists);

    echo $date.'数据存储，成功：' . $i . ",  失败：" . $j;
    echo '<pre>';
    print_r($data);
    die;


debug($data);
echo '<pre>';
print_r($devLists);