<?php
/**
 * Created by PhpStorm.
 * User: lvwenjing
 * Date: 2017/8/21
 * Time: 9:19
 */
error_reporting(E_ALL ^ E_DEPRECATED);
header("Content-type: text/html; charset=utf-8");
$raw_input = $GLOBALS['HTTP_RAW_POST_DATA'];
$raw_input = json_decode($raw_input,true);
file_put_contents('./trigger.txt',date('Y-m-d H:i:s').print_r('开始打印',true).PHP_EOL,FILE_APPEND);
file_put_contents('./trigger.txt',date('Y-m-d H:i:s').print_r($raw_input,true).PHP_EOL,FILE_APPEND);
file_put_contents('./trigger.txt',date('Y-m-d H:i:s').print_r('结束打印',true).PHP_EOL,FILE_APPEND);