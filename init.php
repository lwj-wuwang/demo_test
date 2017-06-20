<?php
/**
 * Created by PhpStorm.
 * User: lvwenjing
 * Date: 2017/6/7
 * Time: 14:47
 */

header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('Asia/Chongqing');
$myPath	= dirname(__FILE__);
//echo $myPath;die;
require_once $myPath."/func.php";
require_once $myPath."/config.php";
require_once $myPath."/iot_php/OneNetApi.php";
require_once $myPath."/model.php";

$OneClass       = new OneNetApi(MASTER_KEY,API_URL);
$db             = new table();