<?php
session_start();
//file_put_contents("./file.txt", date("Y-m-d H:i:s")."session".print_r($_SESSION, TRUE), FILE_APPEND);
require_once "./init.php";


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>设备列表</title>
    <style type="text/css">
        body,div{margin: 0;padding: 0;font-family: '微软雅黑';}
        a,img{border: 0;}
        a{text-decoration: none;}
        #cover{
            /*display: none;*/
            z-index: 999;
            background-color: #000;
            -moz-opacity: 0.8;
            opacity: 0.40;
            filter: alpha(opacity=40);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        #code{
            z-index: 1005;
            border: 1px solid red;
            width: 70%;
            margin: auto;
            background: #fff;
        }
        #close_me{
            height: 30px;
            background: #BE3948;
            line-height: 30px;
            width: 100%;
        }
        #close_str{
            color: white;
            float: right;
            margin-right: 1rem;
        }
        .wx{
            margin-top: 1rem;
            text-align: center;
        }
        .wx_img{
            text-align: center;
            margin: 0.5rem auto;
        }
    </style>
    <script type="text/javascript" src="./jquery-1.6.2.min.js"></script>
    <script type="text/javascript" src="./jquery.js"></script>

</head>
<body>
    <div>
        <hi>我的设备</hi>
    </div>
    <?php if(empty($_GET['unionid'])){ ?>
    <div id="cover"></div>
    <div id="code">
        <div id="close_me">
            <div id="close_str" onclick="close()">关闭</div>
        </div>
        <div class="wx">关注公众号</div>
        <div class="wx_img"><img src="./image/code.png"></div>
    </div>
    <script>
        function close(){
            $("#cover").hide();
            $("#code").hide();
        }
    </script>
    <?php } ?>
</body>
</html>


