<?php
/**
 * Created by PhpStorm.
 * User: lvwenjing
 * Date: 2017/7/4
 * Time: 14:25
 */
header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('Asia/Chongqing');


define("MASTER_KEY","8D=h5AFr8ueFS8XWX4=o=L7u9M4=");    //产品APIKey
define("PROTOCOL","EDP");                               //接入协议
//define('token','test123');                              //token验证

include_once "../iot_php/OneNetApi.php";
include_once "./Onepush/util.php";

class ApiTest{
    protected $_key;
    protected $_oneOb;
    protected $_devId       = 7480650;
    protected $_datastreams = 'testdata';

    function __construct(){
        $this->_key = MASTER_KEY;
        $this->_oneOb = new OneNetApi($this->_key);
    }


    function UploadData(){

        $json['status'] = false;
        $json['error']  = '上传数据成功';

        $data   = $this->AnalogData();
        $result = $this->_oneOb->datapoint_add($this->_devId,$this->_datastreams,$data);
        echo '<pre>';
        print_r($result);
        echo '</pre>';
        die;
        if(!$result){//数据上传错误时，尝试多次上传，排除异常，否则告警
            $errorNum = 0;
            for($i=0;$i<5;$i++){
                $result = $this->_oneOb->datapoint_add($this->_devId,$this->_datastreams,$data);
                if($result){
                    continue;
                }

                $errorNum++;
            }

            $json['status'] = true;
            $json['error']  = '';
            $json['data']   = array(
                'times'     => time(),
                'errorNum'  => $errorNum
            );

           /* $message = date("Y-m-d H:i:s")." 连续{$errorNum}次上传失败,请处理！！";
            $this->ErrorMessage("数据上传",$message);
            */
        }

        return $json;

    }


    //模拟数据
    function AnalogData(){
        $time1  = time()-10;
        $time2  = time();
        $arr    = array(
            $time1 => 'test1_'.$time1,
            $time2 => 'test2_'.$time2
        );

        return $arr;
    }




    function ErrorMessage($event,$message,$toUrl){
        $content = <<<EOF
            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <title>Insert title here</title>
            <style type="text/css">
            *{padding:0;margin:0}
            .jump{width:300px;margin:100px auto;border:1px solid orange;}
            .jump h1{padding-left:10px;font-size:14px;background:purple;color:white;height:30px;line-height:30px;}
            .jump p{line-height:30px;padding:10px;}
            </style>
            </head>
            <body>
            <!-- 页面提示并跳转 -->
            <div class="jump">
                <h1>{$event}告警</h1>
                <p>{$message}</p>
            </div>
            </body>
            </html>

EOF;

        echo $content;
    }

}


/*$testOb = new ApiTest();
$res = $testOb->UploadData();
print_r(json_decode($res,true));
die;*/

/*$raw_input = file_get_contents('php://input');
$resolved_body = Util::resolveBody($raw_input);
file_put_contents('./data.txt',print_r($resolved_body),FILE_APPEND);
die;*/