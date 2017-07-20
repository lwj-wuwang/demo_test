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



//$GLOBALS['HTTP_RAW_POST_DATA'] = "{\"msg\":[{\"at\":1499742990554,\"type\":1,\"ds_id\":\"red_statu\",\"value\":\"0\",\"dev_id\":5246392},{\"at\":1499743010530,\"type\":1,\"ds_id\":\"red_statu\",\"value\":\"0\",\"dev_id\":5246392},{\"at\":1499743010530,\"type\":1,\"ds_id\":\"red_statu\",\"value\":\"0\",\"dev_id\":5246392},{\"at\":1499743010530,\"type\":1,\"ds_id\":\"red_statu\",\"value\":\"0\",\"dev_id\":5246392},{\"at\":1499743010530,\"type\":1,\"ds_id\":\"red_statu\",\"value\":\"0\",\"dev_id\":5246392},{\"at\":1499743010530,\"type\":1,\"ds_id\":\"red_statu\",\"value\":\"0\",\"dev_id\":5246392},{\"at\":1499743010531,\"type\":1,\"ds_id\":\"red_statu\",\"value\":\"0\",\"dev_id\":5246392},{\"at\":1499743010531,\"type\":1,\"ds_id\":\"red_statu\",\"value\":\"0\",\"dev_id\":5246392},{\"at\":1499743010531,\"type\":1,\"ds_id\":\"red_statu\",\"value\":\"0\",\"dev_id\":5246392},{\"at\":1499743010531,\"type\":1,\"ds_id\":\"red_statu\",\"value\":\"0\",\"dev_id\":5246392}],\"msg_signature\":\"uO9S4bsLUDdjoV//RgRGAQ==\",\"nonce\":\"34OGir&7\"}";

$raw_input = $GLOBALS['HTTP_RAW_POST_DATA'];
file_put_contents("./data.txt", print_r($raw_input, TRUE).PHP_EOL, FILE_APPEND);
$resolved_body = Util::resolveBody($raw_input);
file_put_contents('./data_1.txt',print_r($resolved_body).PHP_EOL,FILE_APPEND);die;