<?php
/**
 * Created by PhpStorm.
 * User: lvwenjing
 * Date: 2017/7/11
 * Time: 10:19
 */


$raw_input = file_get_contents('php://input');
$resolved_body = Util::resolveBody($raw_input);
file_put_contents('./data.txt',print_r($resolved_body),FILE_APPEND);