<?php
error_reporting(E_ALL ^ E_DEPRECATED);


date_default_timezone_set('Asia/Chongqing');
require_once "./api_test/table_config.php";
require_once "./model.php";


$tabeClass = new table($config);
$rowsArr = $tabeClass->getList('dev_error','*','','id desc');
echo '<pre>';
print_r($rowsArr);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <script type="text/javascript" src="jquery.js"></script>
    <script type="text/javascript" src="jquery-1.6.2.min.js"></script>
    <style type="text/css">
        .sub{text-align: center;font-size: 22px;margin-bottom: 30px;}
        .warn_num{color: red;}
        .warn_list{text-align: center;}
        .tab{margin: auto;}
    </style>
</head>
<body>
<div class="sub">平台告警提醒</div>
<div>
    <table class="tab" border="1 solid #000" cellpadding="10px" cellspacing="0" width="1000px">
        <tr class="title">
            <th>序号</th>
            <th>告警内容</th>
            <th>告警时间</th>
            <th>告警次数</th>
        </tr>

        <tr class="warn_list">
            <td>1</td>
            <td>数据上传</td>
            <td>2017-07-07 14:21:20</td>
            <td class="warn_num">5</td>
        </tr>
    </table>
</div>
</body>
</html>