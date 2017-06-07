<?php
/**
 * Created by PhpStorm.
 * User: lvwenjing
 * Date: 2017/6/7
 * Time: 14:47
 */

header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('Asia/Chongqing');

require_once "./func.php";
require_once "./config.php";
require_once "./iot_php/OneNetApi.php";
require_once "./model.php";

$OneClass       = new OneNetApi(MASTER_KEY,API_URL);
$db             = new table();