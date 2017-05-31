<?php
/**
 * Created by PhpStorm.
 * User: lvwenjing
 * Date: 2017/5/31
 * Time: 10:57
 */
require_once './config.php';

$conn = mysql_connect(DB_HOST,DB_USERNAME,DB_PASSWORD);
$res = mysql_select_db(DB_NAME);
var_dump($res);