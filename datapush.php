<?php
error_reporting(E_ALL ^ E_DEPRECATED);

header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('Asia/Chongqing');
require_once "./api_test/table_config.php";
require_once "./model.php";


$tabeClass = new table($config);
$rowsArr = $tabeClass->getList('dev_error','*','','id desc');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>推送告警</title>
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
            <!--<th>告警次数</th>-->
        </tr>
        <?php foreach($rowsArr as $key =>$val){   ?>
        <tr class="warn_list">
            <td><?php echo $val['id']; ?></td>
            <td><?php if($val['error_type'] == '1'){ echo '数据推送';} elseif($val['error_type'] == '2'){ echo '数据上传';}  ?></td>
            <td><?php echo date("Y-m-d H:i:s",$val['errorTime']); ?></td>
            <!--<td class="warn_num">5</td>-->
        </tr>
        <?php  }  ?>
    </table>
</div>
</body>
</html>