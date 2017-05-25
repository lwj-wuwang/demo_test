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
