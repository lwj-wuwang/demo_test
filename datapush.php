<?php
error_reporting(E_ALL ^ E_DEPRECATED);
header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('Asia/Chongqing');
require_once "./api_test/table_config.php";
require_once "./model.php";
$pageNo = !empty($_GET['page']) ?  $_GET['page']    : 1;
$solve  = isset($_GET['solve']) ?  $_GET['solve']   : '';
$id     = isset($_GET['id'])    ?  $_GET['id']      : '';
$tabeClass = new table($config);
if(!empty($solve) && !empty($id)){
    $updata = array('if_solve' =>2);
    $res = $tabeClass ->update('dev_error',$updata," id={$id} and if_solve='1' ");
}


$nums     = $tabeClass->counts('dev_error','');
$pageSize = 15;
$countPage  = ceil($nums / $pageSize);
$limit    = $tabeClass->pageLimit($nums,$pageSize,$pageNo);
$rowsArr  = $tabeClass->getList('dev_error','*','if_solve=1','id desc',$limit);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>推送告警</title>
    <script type="text/javascript" src="jquery.js"></script>
    <script type="text/javascript" src="jquery-1.6.2.min.js"></script>
    <style type="text/css">
        a{text-decoration: none;}
        .sub{text-align: center;font-size: 22px;margin-bottom: 30px;}
        .warn_num a{color: #1D69CA;}
        .warn_list{text-align: center;}
        .tab{margin: auto;}
        .pagediv{margin: 20px auto;}
        .notable{color: #969696;cursor:text; }
        .able{color: #1D69CA;;cursor:pointer;}
    </style>
</head>
<body>
<div class="sub">平台告警提醒</div>
<div>
    <table class="tab" border="1 solid #000" cellpadding="10px" cellspacing="0" width="1000px">
        <tr class="title">
            <th>序号</th>
            <th>告警内容</th>
            <th>告警原因</th>
            <th>告警时间</th>
            <th>操作</th>
        </tr>
        <?php foreach($rowsArr as $key =>$val){   ?>
        <tr class="warn_list">
            <td><?php echo $val['id']; ?></td>
            <td><?php if($val['error_type'] == '1'){ echo '数据推送';} elseif($val['error_type'] == '2'){ echo '数据上传';}  ?></td>
            <td><?php if($val['reason'] == 1){echo '设备已离线';}if($val['reason'] == 2){echo '数据间隔过长, 可能存在丢失';}else{echo '数据为空';} ?></td>
            <td><?php echo date("Y-m-d H:i:s",$val['errorTime']); ?></td>
            <td class="warn_num">
                <a href="?id=<?php echo $val['id'] ?>&solve=1&page=<?php echo $pageNo; ?>">已解决</a>   |
                <?php if($val['reason'] ==0 ){  ?>
                <a href="./datalist.php">查看详情</a>
                <?php } ?>
            </td>
        </tr>
        <?php  }  ?>
    </table>
    <div class="pagediv">
        <label>第<?php echo $pageNo; ?>页/共<?php echo $countPage; ?>页</label>
        <?php if($pageNo==1){ ?>
            <a class="notable" disabled="disabled" href='javascript:void(0);'>至首页</a>
            <a class="notable" disabled="disabled" href='javascript:void(0);'>上一页</a>
        <?php }else{ ?>
            <a class="able" href="./datapush.php?page=1">至首页</a>
            <a class="able" href='datapush.php?page=<?php echo $pageNo-1; ?>'>上一页</a>
        <?php } ?>

        <?php if($pageNo ==$countPage){ ?>
            <a class="notable" disabled="disabled" href='javascript:void(0);'>下一页</a>
            <a class="notable" disabled="disabled" href='javascript:void(0);'>至尾页</a>
        <?php }else{ ?>
            <a class="able" href="./datapush.php?page=<?php echo $pageNo+1; ?>">下一页</a>
            <a class="able" href='datapush.php?page=<?php echo $countPage; ?>'>至尾页</a>
        <?php } ?>

    </div>
</div>
</body>
</html>