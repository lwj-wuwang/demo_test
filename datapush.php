<?php
error_reporting(E_ALL ^ E_DEPRECATED);
header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('Asia/Chongqing');
require_once "./api_test/table_config.php";
require_once "./model.php";
require_once "./func.php";

$pageNo = !empty($_GET['page']) ?  $_GET['page']    : 1;
$solve  = isset($_GET['solve']) ?  $_GET['solve']   : '';
$id     = isset($_GET['id'])    ?  $_GET['id']      : '';
$protocol = isset($_REQUEST['protocol'])    ?  $_REQUEST['protocol']   : '';
$action = isset($_REQUEST['action'])    ?  $_REQUEST['action']   : '';
//判断是个选择协议
if(!empty($protocol)){
    if($protocol == 'mqtt'){
        $table = 'data_error_mqtt';
    }else{
        $table = 'dev_error';
    }
}else{
    $table = 'dev_error';
    $protocol = 'edp';
}

$tableClass = new table($config);
if(!empty($solve) && !empty($id)){
    $updata = array('if_solve' =>2);
    $res = $tableClass ->update($table,$updata," id={$id} and if_solve='1' ");
}


$nums     = $tableClass->counts($table," if_solve='1'");
$pageSize = 15;
$countPage  = ceil($nums / $pageSize);
if($pageNo < 1){
    $pageNo = 1;
}
if($pageNo > $countPage){
    $pageNo = $countPage;
}
$limit    = $tableClass->pageLimit($nums,$pageSize,$pageNo);
$rowsArr  = $tableClass->getList($table,'*','if_solve=1','id desc',$limit);

//数据延迟详情
if($action == 'getLag'){
    $err_num    = empty($_POST['err_num'])  ? $_GET['err_num']  : $_POST['err_num'];
    if(!empty($protocol)){
        if($protocol == 'mqtt'){
            $table = 'data_lag_mqtt';
        }else{
            $table = 'data_lag';
        }
    }else{
        $table = 'data_lag';
    }

    $where = "err_num = {$err_num}";
    $lagArr    = $tableClass->getList($table,'*',$where,"id desc");

    if(!empty($lagArr)){
        $newLag = array();
        foreach($lagArr as $lagK => $lagV){
            $lagV['at_date'] = date('Y-m-d H:i:s',$lagV['at']/1000);
            $newLag[$lagK] = $lagV;
        }
        debug($newLag);
        $Json['data']              = $newLag;
        $Json['status']            = true;
    }else{
        $Json['error']              = '暂无数据，请联系管理员！';
        $Json['status']            = false;
    }

    exit(json_encode($Json));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>推送告警</title>
    <script type="text/javascript" src="jquery.js"></script>
    <script type="text/javascript" src="jquery-1.6.2.min.js"></script>
    <style type="text/css">
        body{font-family: "微软雅黑";}
        a{text-decoration: none;}
        .sub{text-align: center;font-size: 22px;margin-bottom: 30px;}
        /*.select{text-align: center;}*/
        .form_div{width: 53%;margin:20px auto;padding-left: 20px;}
        .select_font{color: #00a0e9;margin-right: 10px;}
        .select_box{width: 90px;height: 25px;line-height: 25px;}

        .warn_num a{color: #3C8DBC;}
        .warn_list{text-align: center;}
        .tab{margin: auto;}
        .pagediv{text-align: left;width: 53%;margin: 20px auto;padding-left: 20px;}
        .notable{color: #969696;cursor:text; }
        .able{color: #1D69CA;cursor:pointer;}
        .sub_input{border: 1px solid #00a0e9; background: #00a0e9;color: #fff;border-radius: 15%;width: 50px;height: 25px;line-height: 20px;}
        .warn_num .nosolute{color: red;}
        .lag_list{display: none;position: fixed;width: 600px;height: 400px;left: 50%;top: 50%;transform: translate(-50%,-50%);-webkit-transform: translate(-50%,-50%);z-index: 1200;}
        .lag_list .head{width: 600px;height: 40px;background: rgba(60, 141, 188, 1);}
        .lag_list .head h3{color: #fff;width: 93%;height: 40px;text-align: center;line-height: 40px;float: left;margin: 0;}
        .lag_list .head img{width: 15px;margin-top:10px;margin-right:8px;height: 16px;display: inline-block;float: right;}
        .lag_list .content{background: #fff;width: 600px;height: 360px;z-index: 1201;overflow-x: hidden;overflow-y: auto;border: 1px solid #B6B4B6;}
        .lag_list .content table{width: 100%;background: #fff;margin-top: 4px;}
        .lag_list .content table tr{display: table-row;vertical-align: inherit;border-color: inherit;}
        .lag_list .content table tr td{display: table-cell;vertical-align: inherit;}
        .lag_list .content .err_td{color:red;}
    </style>
    <script type="text/javascript">
       function showList(that,errNum){
           $(".lag_list").hide();
           $(".lag_list .content tbody").html('');
           var protocol = $(".select_box").val();

           $.ajax({
               url: "?action=getLag",
               type: "post",
               data: {
                   err_num: errNum,//报警编号
                   protocol:protocol//协议
               },
               dataType:"json",
               success:function(data){
                   if(data.status == true){
                       var list   = data.data;
                       var TDhtml = "";
                       $(".lag_list .content tbody").html('');
                       for(var i=0;i<list.length;i++){
                           TDhtml  +='<tr>';
                           TDhtml  +='<td align="center">'+(i+1)+'</td>';
                           TDhtml  +='<td align="center" >'+list[i].dev_id+'</td>';
                           TDhtml  +='<td align="center" >'+list[i].ds_id+'</td>';
                           TDhtml  +='<td align="center" >'+list[i].at_date+'</td>';
                           TDhtml  +='</tr>';
                       }

                       $(".lag_list .content tbody").html($(".lag_list .content tbody").html()+TDhtml);
                   }else{
                       var TDhtml = "";
                       TDhtml  +='<tr>';
                       TDhtml  +='<td align="center" colspan=4 class="err_td" >'+data.error+'</td>';
                       TDhtml  +='</tr>';
                       $(".lag_list .content tbody").html($(".lag_list .content tbody").html()+TDhtml);

                   }
               }
           });

           $(".lag_list").show();

       }

        function close_al(){
            $(".lag_list").hide();
        }
    </script>
</head>
<body>
<div class="sub">平台告警提醒</div>
<div class="select">
    <div class="form_div">
        <form action="" method="post">
            <label class="select_font">选择协议:</label>
            <select name="protocol" class="select_box">
                <option value="edp"  <?php if( empty($protocol) ){ ?> selected <?php }elseif($protocol=='edp'){ ?> selected <?php } ?> >EDP</option>
                <option value="mqtt" <?php if($protocol=='mqtt'){ ?> selected <?php } ?> >MQTT</option>
                <option value="modbus" <?php if($protocol=='modbus'){ ?> selected <?php } ?> >Modbus</option>
                <option value="private" <?php if($protocol=='private'){ ?> selected <?php } ?> >私有</option>
            </select>
            <input type="submit" value="搜索" class="sub_input">
        </form>
    </div>
</div>
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
                <a href="?id=<?php echo $val['id'] ?>&protocol=<?php echo $table; ?>&solve=1&page=<?php echo $pageNo; ?>" class="nosolute">未解决</a>   |
                <?php if($val['reason'] == 0 ){  ?>
                <a href="./datalist.php?protocol=<?php echo $protocol; ?>">查看详情</a>
                <?php }elseif($val['reason'] == 2){ ?>
                <a onclick="showList(this,<?php echo $val['error_num']; ?>)" href="javascript:void(0);">查看详情</a>
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
            <a class="able" href="./datapush.php?page=1&protocol=<?php echo $protocol; ?>">至首页</a>
            <a class="able" href='datapush.php?page=<?php echo $pageNo-1; ?>&protocol=<?php echo $protocol; ?>'>上一页</a>
        <?php } ?>

        <?php if($pageNo ==$countPage){ ?>
            <a class="notable" disabled="disabled" href='javascript:void(0);'>下一页</a>
            <a class="notable" disabled="disabled" href='javascript:void(0);'>至尾页</a>
        <?php }else{ ?>
            <a class="able" href="./datapush.php?page=<?php echo $pageNo+1; ?>&protocol=<?php echo $protocol; ?>">下一页</a>
            <a class="able" href='datapush.php?page=<?php echo $countPage; ?>&protocol=<?php echo $protocol; ?>'>至尾页</a>
        <?php } ?>

    </div>

    <div class="lag_list">
        <div class="head">
            <h3>数据延时详情</h3>
            <img src="./image/aaa.png" class="close_a" onclick="close_al()"/>
        </div>
        <div class="content">
            <table class="lag_table" cellpadding="10">
                <thead>
                    <tr class="tab_title">
                        <td width="10%" align="center">序号</td>
                        <td width="25%" align="center">设备编号</td>
                        <td width="30%" align="center">数据流</td>
                        <td width="35%" align="center">上报时间</td>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>