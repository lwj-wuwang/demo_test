<?php
error_reporting(E_ALL ^ E_DEPRECATED);
date_default_timezone_set('Asia/Chongqing');
require_once "func.php";
require_once "./api_test/table_config.php";
require_once "./model.php";

$pageno     = isset($_GET['page'])              ? $_GET['page']             : 1;
$protocol   = isset($_GET['protocol'])          ? $_GET['protocol']         : $_POST['protocol'];
$protocol   = !empty($protocol)                 ? $protocol                 : 'edp';
$ds_id      = !empty($_REQUEST['ds_id'])        ? $_REQUEST['ds_id']        : '';
$startTime  = !empty($_REQUEST['ds_start_time']) ? $_REQUEST['ds_start_time'] : '';
$endTime    = !empty($_REQUEST['ds_end_time'])   ? $_REQUEST['ds_end_time']   : '';

if( empty($ds_id) ){
    if($protocol == 'edp'){
       $ds_id   = 'blue_statu';
    }else{
        $ds_id  = 'humi';
    }

}
if($protocol == 'edp'){
    $table = 'data_str';
    $dev_id = 10072873;
}else{
    $table = 'data_str_mqtt';
    $dev_id = 11306166;
}

$tabeClass = new table($config);
//数据流列表
$ds_arr = $tabeClass->getList($table,'ds_id',"dev_id={$dev_id}",'','',"ds_id");

/*时间转换*/
//开始时间
if($startTime){
    $start  = explode('T',$startTime);
    $s_time_char =  $start[0] .' '. $start[1];
    $s_time = strtotime($s_time_char)*1000;
}

//结束时间
if($endTime){
    $end    = explode('T',$endTime);
    $e_time_char = $end[0] .' '. $end[1];
    $e_time = strtotime($e_time_char)*1000;
}


//默认查询最新2小时的数据
$lastTime = ( time() - 18000 )*1000;
$where = "ds_id='{$ds_id}'";
if(!empty($s_time) && empty($e_time)){

    $where .= " AND `at`>{$s_time} ";

}elseif(!empty($e_time) && empty($s_time)){

    $where .= " AND `at`<{$e_time} ";

}elseif(!empty($s_time) && !empty($e_time)){

    $where .= " AND `at`>{$s_time} AND `at`<{$e_time} ";

}else{

    $where .= " AND `at`>{$lastTime}";
}

$arr    = $tabeClass->getList($table,'*',$where,"id desc");
$counts = count($arr);
//echo '<pre>';
//print_r($arr);
//echo $counts;//die;
//debug($arr);

$newarr = array();
if($counts !=0 && $counts%2 == 0){//偶数
    if($arr[0]['ident'] == $arr[1]['ident']){//为整数对数组
        $j = 0;
        for($i=0;$i<$counts;$i++){
            $newarr[$j]['dev_id']   = $arr[$i]['dev_id'];
            $newarr[$j]['ds_id']    = $arr[$i]['ds_id'];
            $newarr[$j]['e_at']     = $arr[$i]['at'];
            $ident                  = explode('_',$arr[$i]['ident']);
            $i++;
            $newarr[$j]['f_at']     = $arr[$i]['at'];
            $newarr[$j]['ident']    = $ident[0];
            $j++;
        }
    }else{//非整数对

        $j = 0;
        for($i=1;$i<$counts-2;$i++){
            $newarr[$j]['dev_id']   = $arr[$i]['dev_id'];
            $newarr[$j]['ds_id']    = $arr[$i]['ds_id'];
            $newarr[$j]['e_at']     = $arr[$i]['at'];
            $ident                  = explode('_',$arr[$i]['ident']);
            $i++;
            $newarr[$j]['f_at']     = $arr[$i]['at'];
            $newarr[$j]['ident']    = $ident[0];
            $j++;
        }

        $fristArr   = reset($arr);
        $endArr     = end($arr);
        $fdata = $tabeClass->getList($table,'*',"ident = '{$fristArr['ident']}' ");
        $edata = $tabeClass->getList($table,'*',"ident = '{$endArr['ident']}' ");

        if(!empty($fdata) || !empty($edata)){
            //首数组组合
            $fident          = explode('_',$fdata[0]['ident']);
            $farr  = array(
                'dev_id'    => $fdata[0]['dev_id'],
                'ds_id'     => $fdata[0]['ds_id'],
                'f_at'      => $fdata[0]['at'],
                'e_at'      => !empty($fdata[1]['at']) ? $fdata[1]['at'] : '',
                'ident'     => $fident[0]
            );

            //尾数组组合
            $eident          = explode('_',$edata[0]['ident']);
            $earr  = array(
                'dev_id'    => $edata[0]['dev_id'],
                'ds_id'     => $edata[0]['ds_id'],
                'f_at'      => $edata[0]['at'],
                'e_at'      => !empty($edata[1]['at']) ? $edata[1]['at'] : '',
                'ident'     => $eident[0]
            );

            array_unshift($newarr,$farr);
            array_push($newarr,$earr);

        }


    }


}elseif($counts !=0 && $counts%2 != 0){//奇数
    if($arr[0]['ident'] == $arr[1]['ident']){//单尾部数组
        $endArr = end($arr);
        $j = 0;
        for($i=0;$i<$counts-1;$i++){
            $newarr[$j]['dev_id']   = $arr[$i]['dev_id'];
            $newarr[$j]['ds_id']    = $arr[$i]['ds_id'];
            $newarr[$j]['e_at']     = $arr[$i]['at'];
            $ident                  = explode('_',$arr[$i]['ident']);
            $i++;
            $newarr[$j]['f_at']     = $arr[$i]['at'];
            $newarr[$j]['ident']    = $ident[0];
            $j++;
        }

        $edata = $tabeClass->getList($table,'*',"ident = '{$endArr['ident']}' ");
        if(!empty($edata)) {

            //尾数组组合
            $eident = explode('_', $edata[0]['ident']);
            $earr = array(
                'dev_id' => $edata[0]['dev_id'],
                'ds_id'  => $edata[0]['ds_id'],
                'f_at'   => $edata[0]['at'],
                'e_at'   => !empty($edata[1]['at']) ? $edata[1]['at'] : '',
                'ident'  => $eident[0]
            );

            array_push($newarr, $earr);
        }

    }else{
        $fristArr   = reset($arr);
        $j = 0;
        for($i=1;$i<$counts;$i++){
            $newarr[$j]['dev_id']   = $arr[$i]['dev_id'];
            $newarr[$j]['ds_id']    = $arr[$i]['ds_id'];
            $newarr[$j]['e_at']     = $arr[$i]['at'];
            $ident                  = explode('_',$arr[$i]['ident']);
            $i++;
            $newarr[$j]['f_at']     = $arr[$i]['at'];
            $newarr[$j]['ident']    = $ident[0];
            $j++;
        }

        $fdata = $tabeClass->getList($table,'*',"ident = '{$fristArr['ident']}' ");

        if(!empty($fdata)){
            //首数组组合
            $fident          = explode('_',$fdata[0]['ident']);
            $farr  = array(
                'dev_id'    => $fdata[0]['dev_id'],
                'ds_id'     => $fdata[0]['ds_id'],
                'f_at'      => $fdata[0]['at'],
                'e_at'      => !empty($fdata[1]['at']) ? $fdata[1]['at'] : '',
                'ident'     => $fident[0]
            );

            array_unshift($newarr,$farr);

        }
    }

}

//debug($newarr);

//分页处理
$newCounts  = count($newarr);               //总条数
$pageSize   = 10;                           //每页条数
$countPage  = ceil($newCounts / $pageSize); //总页数
$countPage  = $countPage    ? $countPage    : 1;
$pageno     = ($pageno && !empty($pageno))? $pageno : 1;

if($pageno <=1 ){
    $pageno = 1;
}elseif($pageno>$countPage){
    $pageno = $countPage;
}
$start      = ($pageno - 1) * $pageSize;    //起始偏移值

//根据分页形成的新数组
$data = array();
for($i=0;$i<$pageSize;$i++){
    if(isset($newarr[$start+$i])){
        $data[$start+$i] = $newarr[$start+$i];
    }

}
/*echo '<pre>';
echo $s_time_char.'-----'.$e_time_char;die;*/
//debug($data);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>数据流展示</title>
    <link href="./js/calendar/calendar.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="./js/calendar.php"></script>
    <style type="text/css">
        a{text-decoration: none;}
        .container{margin: ;padding:0 auto;}
        .pagediv{margin-top: 20px;}
        .notable{color: #969696;cursor:text; }
        .able{color: #1D69CA;;cursor:pointer;}
    </style>

</head>
<body>
    <div class="container">
        <h2>数据流查询</h2>
        <div><a href="./datapush.php?protocol=<?php echo $protocol; ?>">返回</a></div>
        <div>
            <form action="" method="post">
                <label>数据流</label>
                <select name="ds_id">
                    <?php foreach($ds_arr as $ds_k => $ds_v){ ?>
                    <option value="<?php echo $ds_v['ds_id']; ?>" <?php if($ds_v['ds_id'] == $ds_id){?>selected <?php } ?> ><?php echo $ds_v['ds_id']; ?> </option>
                    <?php } ?>
                </select>

                <label>时间周期</label>
                <input type="datetime-local" name="ds_start_time" id="ds_start_time" value="<?php echo $startTime; ?>" />
                &nbsp;&nbsp;——&nbsp;&nbsp;
                <input type="datetime-local" name="ds_end_time" id="ds_end_time" value="<?php echo $endTime; ?>" />
                <input name="sub" type="submit" value="搜索" href="javascript:void(0);">

            </form>
        </div>
        <table border="1 solid #000" cellpadding="10px" cellspacing="0" width="1000px">
            <tr>
                <th>序号</th>
                <th>组号</th>
                <th>首次数据</th>
                <th>末次数据</th>
                <th>数据流</th>
            </tr>
            <?php foreach($data as $key =>$val){ ?>
            <tr>
                <td><?php echo $key; ?></td>
                <td><?php echo $val['ident'];  ?></td>
                <td><?php if($val['f_at']){echo date('Y-m-d H:i:s',$val['f_at']/1000 );}  ?></td>
                <td><?php if($val['e_at']){echo date('Y-m-d H:i:s',$val['e_at']/1000 );}  ?></td>
                <td><?php echo $val['ds_id'];  ?></td>
            </tr>
            <?php }  ?>
        </table>
        <div class="pagediv">
            <label>第<?php echo $pageno; ?>页/共<?php echo $countPage; ?>页</label>
            <?php if($pageno==1){ ?>
                <a class="notable" disabled="disabled" href='javascript:void(0);'>至首页</a>
                <a class="notable" disabled="disabled" href='javascript:void(0);'>上一页</a>
            <?php }else{ ?>
                <a class="able" href="./datalist.php?page=1&protocol=<?php echo $protocol; ?>&ds_id=<?php echo $ds_id; ?>&ds_id=<?php echo $ds_id; ?>&ds_start_time=<?php echo $startTime;?>&ds_end_time=<?php echo $endTime; ?>">至首页</a>
                <a class="able" href="./datalist.php?page=<?php echo $pageno-1; ?>&protocol=<?php echo $protocol; ?>&ds_id=<?php echo $ds_id; ?>&ds_start_time=<?php echo $startTime; ?>&ds_end_time=<?php echo $endTime; ?>">上一页</a>
            <?php } ?>

            <?php if($pageno ==$countPage){ ?>
                <a class="notable" disabled="disabled" href='javascript:void(0);'>下一页</a>
                <a class="notable" disabled="disabled" href='javascript:void(0);'>至尾页</a>
            <?php }else{ ?>
                <a class="able" href="./datalist.php?page=<?php echo $pageno+1; ?>&protocol=<?php echo $protocol; ?>&ds_id=<?php echo $ds_id; ?>&ds_id=<?php echo $ds_id; ?>&ds_start_time=<?php echo $startTime; ?>&ds_end_time=<?php echo $endTime; ?>">下一页</a>
                <a class="able" href="./datalist.php?page=<?php echo $countPage; ?>&protocol=<?php echo $protocol; ?>&ds_id=<?php echo $ds_id; ?>&ds_id=<?php echo $ds_id; ?>&ds_start_time=<?php echo $startTime; ?>&ds_end_time=<?php echo $endTime; ?>">至尾页</a>
            <?php } ?>

        </div>
    </div>
</body>
</html>