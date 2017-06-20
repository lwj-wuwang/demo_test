<?php
// error_reporting(E_ALL);
// ini_set('display_errors','On');
session_start();
require_once '../init.php';
    if($_POST){
        debug($_POST);
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>添加歌手分类</title>
    <script type="text/javascript" src="../jquery.js"></script>
    <script type="text/javascript" src="../jquery-1.11.3.js"></script>
    <style type="text/css">
        body,div,h3{margin: 0;padding: 0;}
        h3{text-align: center;}
        .box{
            width: 30em;
            margin: 2em auto;
        }
        .singer_cate{
            border-radius: 0.3em;
            border: 1px solid #33acd4;
            width: 90%;
            height: 3em;
            line-height: 3em;
        }
        .sub,.cate,.title{
            /*text-align: center;*/
            margin-top: 1.5em;
        }
        .sub input{
            background: #33acd4;
            color: #fff;
            border: 0;
            border-radius: 0.4em;
            width: 40%;
            height: 2.5em;
        }
    </style>
</head>
<body>
    <div>
        <h3>添加歌手分类</h3>
        <form name="form1" id="form1" action="add_singer_cate.php" method="post">
            <div class="box">
                <div class="title">
                    <label>歌手分类</label>
                </div>
                <div class="cate">
                    <input type="text" name="singer_cate" class="singer_cate">
                </div>
                <div class="sub">
                    <input type="button" value="提交" id="sub_form">
                </div>
            </div>
        </form>
    </div>
<script>
    $("#sub_form").click(function(){
        var val =  $(".singer_cate").val();
        if(val== false){
            alert("分类名称为空！");
        }else {
            $("#form1").submit();
        }
    });
</script>
</body>
</html>