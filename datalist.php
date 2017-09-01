<?php
error_reporting(E_ALL ^ E_DEPRECATED);
date_default_timezone_set('Asia/Chongqing');
require_once "func.php";
require_once "./api_test/table_config.php";
require_once "./model.php";
$tabeClass = new table($config);
$pageno     = isset($_GET['page']) ? $_GET['page'] : 1;
if($_POST){
    debug($_POST);
}
//$lasttime = (time() - 3600) * 1000;
$arr = $tabeClass->getList('data_str','*',"ds_id = 'blue_statu' ");//AND at>={$lasttime}
//$arr = $tabeClass->getList('data_str','*',"ds_id = 'red_statu' AND at>={$lasttime}");
$counts = count($arr);
/*echo '<pre>';
print_r($arr);*/
//echo $counts;die;
//debug($arr);

$newarr = array();
if($counts%2 == 0){//偶数

    if($arr[0]['ident'] == $arr[1]['ident']){//为整数对数组
        $j = 0;
        for($i=0;$i<$counts;$i++){
            $newarr[$j]['dev_id']   = $arr[$i]['dev_id'];
            $newarr[$j]['ds_id']    = $arr[$i]['ds_id'];
            $newarr[$j]['f_at']     = $arr[$i]['at'];
            $ident                  = explode('_',$arr[$i]['ident']);
            $i++;
            $newarr[$j]['e_at']     = $arr[$i]['at'];
            $newarr[$j]['ident']    = $ident[0];
            $j++;
        }
    }else{//非整数对

        $j = 0;
        for($i=1;$i<$counts-2;$i++){
            $newarr[$j]['dev_id']   = $arr[$i]['dev_id'];
            $newarr[$j]['ds_id']    = $arr[$i]['ds_id'];
            $newarr[$j]['f_at']     = $arr[$i]['at'];
            $ident                  = explode('_',$arr[$i]['ident']);
            $i++;
            $newarr[$j]['e_at']     = $arr[$i]['at'];
            $newarr[$j]['ident']    = $ident[0];
            $j++;
        }

        $fristArr   = reset($arr);
        $endArr     = end($arr);
        $fdata = $tabeClass->getList('data_str','*',"ident = '{$fristArr['ident']}' ");
        $edata = $tabeClass->getList('data_str','*',"ident = '{$endArr['ident']}' ");

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


}else{//奇数
    if($arr[0]['ident'] == $arr[1]['ident']){//单尾部数组
        $endArr = end($arr);
        $j = 0;
        for($i=0;$i<$counts-1;$i++){
            $newarr[$j]['dev_id']   = $arr[$i]['dev_id'];
            $newarr[$j]['ds_id']    = $arr[$i]['ds_id'];
            $newarr[$j]['f_at']     = $arr[$i]['at'];
            $ident                  = explode('_',$arr[$i]['ident']);
            $i++;
            $newarr[$j]['e_at']     = $arr[$i]['at'];
            $newarr[$j]['ident']    = $ident[0];
            $j++;
        }

        $edata = $tabeClass->getList('data_str','*',"ident = '{$endArr['ident']}' ");
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
            $newarr[$j]['f_at']     = $arr[$i]['at'];
            $ident                  = explode('_',$arr[$i]['ident']);
            $i++;
            $newarr[$j]['e_at']     = $arr[$i]['at'];
            $newarr[$j]['ident']    = $ident[0];
            $j++;
        }

        $fdata = $tabeClass->getList('data_str','*',"ident = '{$fristArr['ident']}' ");

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
        <div>
            <form action="" method="post">
                <label>数据流</label>
                <select name="ds_id">
                    <option value="blue_statu" selected>blue_statu</option>
                    <option value="yellow_statu">yellow_statu</option>
                    <option value="green_statu">green_statu</option>
                    <option value="red_statu">red_statu</option>
                </select>

                <label>时间周期</label>
                <input type="text" name="ds_start_ime" id="ds_start_ime" value="">
                <input name="start_time_btn" type="button" id="start_time_btn" onclick="return showCalendar('ds_start_ime', '%Y-%m-%d %H:%M', '24', false, 'start_time_btn');" value="{$lang.btn_select}" class="button"/>
                &nbsp;&nbsp;——&nbsp;&nbsp;
                <input type="text" name="ds_end_time" id="ds_end_time" value="">
                <input name="end_time_btn" type="button" id="end_time_btn" onclick="return showCalendar('ds_end_time', '%Y-%m-%d %H:%M', '24', false, 'end_time_btn');" value="{$lang.btn_select}" class="button"/>
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
                <td><?php if($val['f_at']){echo date('Y-m-d H:i:s',$val['f_at']/1000);}   ?></td>
                <td><?php if($val['e_at']){echo date('Y-m-d H:i:s',$val['e_at']/1000);}  ?></td>
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
                <a class="able" href="./datalist.php?page=1">至首页</a>
                <a class="able" href='datalist.php?page=<?php echo $pageno-1; ?>'>上一页</a>
            <?php } ?>

            <?php if($pageno ==$countPage){ ?>
                <a class="notable" disabled="disabled" href='javascript:void(0);'>下一页</a>
                <a class="notable" disabled="disabled" href='javascript:void(0);'>至尾页</a>
            <?php }else{ ?>
                <a class="able" href="./datalist.php?page=<?php echo $pageno+1; ?>">下一页</a>
                <a class="able" href='datalist.php?page=<?php echo $countPage; ?>'>至尾页</a>
            <?php } ?>

        </div>
    </div>
</body>
</html>