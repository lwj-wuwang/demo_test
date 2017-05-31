<?php
/**
 * Created by PhpStorm.
 * User: lvwenjing
 * Date: 2017/5/31
 * Time: 10:57
 */

$conn = mysql_connect("localhost","root","xqqdzswsd");
$res = mysql_select_db("onenet_demo");
var_dump($res);