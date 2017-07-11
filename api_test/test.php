<?php
/**
 * Created by PhpStorm.
 * User: lvwenjing
 * Date: 2017/7/11
 * Time: 10:19
 */
header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('Asia/Chongqing');
require_once "./Onepush/util.php";

file_put_contents('./data_1.txt',print_r($GLOBALS['HTTP_RAW_POST_DATA'], TRUE),FILE_APPEND);
//file_put_contents("./data.txt", print_r($GLOBALS['HTTP_RAW_POST_DATA'], TRUE), FILE_APPEND);die;
$raw_input = json_decode($GLOBALS['HTTP_RAW_POST_DATA'],true);
file_put_contents('./data_2.txt',print_r($resolved_body,true),FILE_APPEND);
//$raw_input = file_get_contents('php://input');
$resolved_body = Util::resolveBody($raw_input);
file_put_contents('./data.txt',print_r($resolved_body),FILE_APPEND);