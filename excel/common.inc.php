<?php

define('EXCEL_FIELD_ROW', 1);

// 验证第几列
define('EXCEL_FIELD_VALID_COL', 0);

define('EXCEL_FIELD_NUM', 100);

// 平台用户类别名称
define('EXCEL_USER_TYPE_PLATFORM', '平台用户');

// 平台用户类别名称2
define('EXCEL_USER_TYPE_BRANCH', '社内用户');

// 帐号类型所在列
define('EXCEL_USER_TYPE_COL', 3);

define('EXCEL_USER_MAX_COL', 5);


define('EXCEL_CATE_COL', 1);

// 后台用户表(社内)字段
$userFieldss = array(
    'user_name',
    'user_pass',
    'user_nickname',
    'user_type',
    'user_year',//出生年月日
    'user_major',//专业
    'user_card',//是否有责任编辑证
    'user_card_time'//领证时间
    
);

// 后台用户表(社外)字段
$userFields = array(
    'user_name',
    'user_pass',
    'user_nickname',
    'user_type',
    'user_branch_name',//社内联系人
    'user_compay'//协助单位
);

// 前台用户表字段
$buyerFields = array(
    'buyer_username',
    'buyer_password',
    'buyer_xingming',
    'user_type',
    'buyer_pseudonym',//笔名
    'buyer_sex',//性别
    'buyer_age',//年龄
    'buyer_qq',//qq
    'buyer_profile',//个人介绍
    'buyer_ojb',//职务
    'buyer_email_code',//邮编
    'buyer_city',//所在地
    'buyer_company_tel',//办公电话
    'buyer_moblie',//电话
    'buyer_address',//通讯地址
    'buyer_company_address',//工作单位
    'buyer_editlike',//兴趣领域
);


$cateList=array(
    'gli_cate_parent_id',
    'gli_cate_name',
    'gli_cate_desc'
);


$mail_list=array(
    'gli_name',
    'gli_eamil',
    'gli_remarks'
);

//兴源表字段
$bookFields = array(
    'deviceId'              => '设备编号',
    'createTime'            => '创建时间',
    'product'               => '对应产品',
    'deviceSN'              => '序列号',
    'iccid'                 => 'ICCID',
    'logTime'               => '当前读表时间',
    'devNum'                => '当前读数',
    'init'                  => '单位',
    'voltage'               => '电池电压',
    'version'               => '软件版本',
    'lastOnlineTime'        => '最后上线时间',
    'activateNum'           => '激活次数'

);

//v_movie表字段
$movieFields = array(
    'deviceId'              => 'id',
    'private'               => 'private',
    'title'                 => 'title',
    'auth_info'             => 'auth_info',
    'create_time'           => 'create_time'
);


if (!function_exists('output')) { 
    function output($code = -1, $data = array())
    {
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
        }

        if (0 === $code && !count($data)) {
            $data = 'SUCCESS';
        }

        echo json_encode(array('code' => $code, 'data' => $data), JSON_UNESCAPED_UNICODE);
        exit(0);
    }
}
