<?php
/**
 * Created by PhpStorm.
 * User: lvwenjing
 * Date: 2017/8/15
 * Time: 10:10
 */

require_once './Onepush/util.php';

$raw_input = file_get_contents('php://input');

$resolved_body = Util::resolveBody($raw_input);
 Util::l($resolved_body);
echo $resolved_body;