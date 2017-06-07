CREATE TABLE `onenet_demo`.`device_info`(
  `id` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `device_sn` VARCHAR(100) NOT NULL COMMENT '设备序列号',
  `device_name` VARCHAR(100) COMMENT '设备名称',
  `iot_device_id` INT COMMENT 'OneNet平台的设备ID',
  `iot_device_key` VARCHAR(255) COMMENT 'OneNet平台的设备apikey',
  `addtime` INT COMMENT '添加时间',
  PRIMARY KEY (`id`)
) CHARSET=utf8
COMMENT='设备信息表';

ALTER TABLE `onenet_demo`.`device_info`
  ADD COLUMN `device_desc` VARCHAR(255) NULL   COMMENT '个性描述' AFTER `addtime`;


CREATE TABLE `onenet_demo`.`user`(
  `user_id` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` VARCHAR(100) COMMENT '用户名',
  `password` VARCHAR(100) COMMENT '密码',
  `alias` VARCHAR(100) COMMENT '别名',
  `openid` VARCHAR(100) COMMENT '微信openid',
  `regtime` INT COMMENT '注册时间',
  `device_id` INT COMMENT '设备id(关联device_info表)',
  `last_login` INT COMMENT '最近登录时间',
  `headerimg` VARCHAR(255) NULL   COMMENT '头像' ,
  `sex` ENUM('0','1','2') DEFAULT '0'  NULL   COMMENT '性别:0保密 1男 2女' ,
  `province` VARCHAR(50) NULL   COMMENT '省份' ,
  `city` VARCHAR(50) NULL   COMMENT '市/州' ,
  `area` VARCHAR(50) NULL   COMMENT '区县',
  PRIMARY KEY (`user_id`)
) CHARSET=utf8
COMMENT='用户信息';



ALTER TABLE `onenet_demo`.`user`
  ADD COLUMN `headerimg` VARCHAR(255) NULL   COMMENT '头像' AFTER `last_login`,
  ADD COLUMN `sex` ENUM('0','1','2') DEFAULT '0'  NULL   COMMENT '性别:0保密 1男 2女' AFTER `headerimg`,
  ADD COLUMN `province` VARCHAR(50) NULL   COMMENT '省份' AFTER `sex`,
  ADD COLUMN `city` VARCHAR(50) NULL   COMMENT '市/州' AFTER `province`,
  ADD COLUMN `area` VARCHAR(50) NULL   COMMENT '区县' AFTER `city`;


ALTER TABLE `onenet_demo`.`user`
  ADD COLUMN `if_focus` INT DEFAULT 0  NULL   COMMENT '是否关注公众号 0未关注 1关注' AFTER `area`;