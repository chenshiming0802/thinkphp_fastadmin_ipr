
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";



CREATE TABLE IF NOT EXISTS `__PREFIX__csmipr_clogininfo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `settingjson` text COLLATE utf8mb4_unicode_ci COMMENT '配置',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `status` enum('normal','hidden') COLLATE utf8mb4_unicode_ci DEFAULT 'normal',
  PRIMARY KEY (`id`),
  KEY `ix_csmipr_clogininfo_user_id` (`user_id`),
  KEY `ix_csmipr_clogininfo_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户配置'
;

CREATE TABLE IF NOT EXISTS `__PREFIX__csmipr_cloginlog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `operate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作类型',
  `port` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '访问端口',
  `object_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '对象ID',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `cyear` int(11) DEFAULT NULL COMMENT '年',
  `cmonth` int(11) DEFAULT NULL COMMENT '月',
  `cdate` int(11) DEFAULT NULL COMMENT '日',
  `cweek` int(11) DEFAULT NULL COMMENT '周1-7',
  `chour` int(11) DEFAULT NULL COMMENT '小时',
  `cmin` int(11) DEFAULT NULL COMMENT '分钟',
  `content` text COLLATE utf8mb4_unicode_ci,
  `recontent` text COLLATE utf8mb4_unicode_ci,
  `septime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='操作日志'
;

CREATE TABLE IF NOT EXISTS `__PREFIX__csmipr_cloginmessage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `cyear` int(11) DEFAULT NULL COMMENT '年',
  `cmonth` int(11) DEFAULT NULL COMMENT '月',
  `cdate` int(11) DEFAULT NULL COMMENT '日',
  `cweek` int(11) DEFAULT NULL COMMENT '周1-7',
  `chour` int(11) DEFAULT NULL COMMENT '小时',
  `cmin` int(11) DEFAULT NULL COMMENT '分钟',
  `title` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '标题',
  `content` text COLLATE utf8mb4_unicode_ci COMMENT '内容',
  `objectcode` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '对象名称',
  `objectid` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '对象ID',
  `isread` enum('Y','N') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '是佛已读:Y=已读,N=未读',
  `readtime` bigint(20) DEFAULT NULL COMMENT '阅读时间',
  `status` enum('normal','hidden') COLLATE utf8mb4_unicode_ci DEFAULT 'normal' COMMENT '状态',
  `weigh` int(11) DEFAULT NULL COMMENT '权重',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户消息'
;

CREATE TABLE IF NOT EXISTS `__PREFIX__csmipr_cloginthird` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) unsigned DEFAULT '0' COMMENT '会员ID',
  `platform` varchar(30) DEFAULT '' COMMENT '第三方应用',
  `platformid` varchar(30) DEFAULT NULL COMMENT '第三方接入账套，比如微信号appid',
  `apptype` varchar(50) DEFAULT '' COMMENT '应用类型',
  `unionid` varchar(100) DEFAULT '' COMMENT '第三方UNIONID',
  `openid` varchar(100) DEFAULT '' COMMENT '第三方OPENID',
  `openname` varchar(100) DEFAULT '' COMMENT '第三方会员昵称',
  `access_token` varchar(255) DEFAULT '' COMMENT 'AccessToken',
  `refresh_token` varchar(255) DEFAULT 'RefreshToken',
  `expires_in` int(10) unsigned DEFAULT '0' COMMENT '有效期',
  `createtime` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) unsigned DEFAULT NULL COMMENT '更新时间',
  `logintime` int(10) unsigned DEFAULT NULL COMMENT '登录时间',
  `expiretime` int(10) unsigned DEFAULT NULL COMMENT '过期时间',
  `avatarurl` varchar(200) DEFAULT NULL COMMENT '头像',
  `city` varchar(100) DEFAULT NULL COMMENT '城市',
  `country` varchar(100) DEFAULT NULL COMMENT '国家',
  `gender` varchar(100) DEFAULT NULL COMMENT '性别',
  `language` varchar(100) DEFAULT NULL COMMENT '语言',
  `name` varchar(100) DEFAULT NULL COMMENT '称呼',
  `nickname` varchar(100) DEFAULT NULL COMMENT '昵称',
  `province` varchar(100) DEFAULT NULL COMMENT '地区',
  `phonenumber` varchar(100) DEFAULT NULL COMMENT '用户绑定的手机号（国外手机号会有区号）',
  `purephonenumber` varchar(100) DEFAULT NULL COMMENT '没有区号的手机号',
  `countrycode` varchar(100) DEFAULT NULL COMMENT '区号',
  `status` enum('normal','hidden') DEFAULT 'normal',
  PRIMARY KEY (`id`),
  UNIQUE KEY `platform` (`platform`,`openid`),
  KEY `user_id` (`user_id`,`platform`),
  KEY `unionid` (`platform`,`unionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='第三方登录表'
;

CREATE TABLE IF NOT EXISTS `__PREFIX__csmipr_cloginwxscan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `login_scene_id` varchar(50) NOT NULL COMMENT 'SCENE',
  `user_id` int(11) DEFAULT NULL COMMENT '用户',
  `openid` varchar(100) DEFAULT NULL COMMENT 'OpenID',
  `username` varchar(100) DEFAULT NULL COMMENT '用户名',
  `event` varchar(100) DEFAULT NULL COMMENT '事件：subscribe,scan',
  `createtime` int(10) DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(10) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信扫码记录表'
;

CREATE TABLE IF NOT EXISTS `__PREFIX__csmipr_commoninterfacelog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URl',
  `request_post` text COLLATE utf8mb4_unicode_ci COMMENT 'sp',
  `request_result` text COLLATE utf8mb4_unicode_ci COMMENT 'sr',
  `costsecond` int(11) DEFAULT NULL COMMENT '花费时间',
  `issuccess` enum('Y','N') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '是否成功:Y=是,N=否',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='接口日志'
;

CREATE TABLE IF NOT EXISTS `__PREFIX__csmipr_dmo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `csmipr_dmoapply_id` int(11) DEFAULT NULL COMMENT 'DMO申请',
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '名称',
  `csmipr_dmocategory_id` int(11) DEFAULT NULL COMMENT '分类',
  `csmipr_dmocategory_ids` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '分类（多选）',
  `type` enum('T1','T2') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '类型:T1=类型1,T2=类型2',
  `types` set('T1','T2') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '类型(多选):T1=类型1,T2=类型2',
  `isread` enum('Y','N') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '是否已读:Y=是,N=否',
  `content` text COLLATE utf8mb4_unicode_ci COMMENT '正文',
  `bannerimage` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '附件（单个）',
  `images` varchar(2000) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '附件（多个）',
  `user_id` int(10) DEFAULT '0' COMMENT '会员ID',
  `admin_id` int(10) DEFAULT '0' COMMENT '管理员ID',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) DEFAULT '0' COMMENT '权重',
  `status` enum('normal','hidden') COLLATE utf8mb4_unicode_ci DEFAULT 'normal' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='DMO'
;

CREATE TABLE IF NOT EXISTS `__PREFIX__csmipr_dmo2category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `csmipr_dmo_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'DMO',
  `csmipr_dmocategory_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'DMO分类',
  `user_id` int(10) DEFAULT '0' COMMENT '会员ID',
  `admin_id` int(10) DEFAULT '0' COMMENT '管理员ID',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) DEFAULT '0' COMMENT '权重',
  `status` enum('normal','hidden') COLLATE utf8mb4_unicode_ci DEFAULT 'normal' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='DMO所属分类'
;

CREATE TABLE IF NOT EXISTS `__PREFIX__csmipr_dmoapply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '名称',
  `csmipr_dmocategory_id` int(11) DEFAULT NULL COMMENT '分类',
  `csmipr_dmocategory_ids` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '分类（多选）',
  `type` enum('T1','T2') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '类型:T1=类型1,T2=类型2',
  `types` set('T1','T2') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '类型(多选):T1=类型1,T2=类型2',
  `content` text COLLATE utf8mb4_unicode_ci COMMENT '正文',
  `bannerimage` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '附件（单个）',
  `images` varchar(2000) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '附件（多个）',
  `auditcontent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '审核意见',
  `user_id` int(10) DEFAULT '0' COMMENT '会员ID',
  `admin_id` int(10) DEFAULT '0' COMMENT '管理员ID',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) DEFAULT '0' COMMENT '权重',
  `status` enum('normal','hidden','draft','toaudit','reject') COLLATE utf8mb4_unicode_ci DEFAULT 'draft' COMMENT '状态:toaudit=待审,reject=审核退回,normal=发布,draft=草稿,hidden=已删除,',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='DMO申请'
;

CREATE TABLE IF NOT EXISTS `__PREFIX__csmipr_dmocategory` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '名称',
  `user_id` int(10) DEFAULT '0' COMMENT '会员ID',
  `admin_id` int(10) DEFAULT '0' COMMENT '管理员ID',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) DEFAULT '0' COMMENT '权重',
  `status` enum('normal','hidden') COLLATE utf8mb4_unicode_ci DEFAULT 'normal' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='DEMO分类'
;

CREATE TABLE IF NOT EXISTS `__PREFIX__csmipr_dmoitem` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `csmipr_dmo_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'DMO',
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '名称',
  `type` enum('type1','type2') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '类型:type1=类型1,type2=类型2',
  `type1_val` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '类型1值',
  `type2_val` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '类型2值',
  `user_id` int(10) DEFAULT '0' COMMENT '会员ID',
  `admin_id` int(10) DEFAULT '0' COMMENT '管理员ID',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) DEFAULT '0' COMMENT '权重',
  `status` enum('normal','hidden') COLLATE utf8mb4_unicode_ci DEFAULT 'normal' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='DEMO明细'
;

CREATE TABLE `__PREFIX__csmipr_xdictset` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` VARCHAR(100) DEFAULT '' COMMENT '名称',
  `code` VARCHAR(100) DEFAULT '' COMMENT '编码',
  `desc` TEXT COMMENT '备注',

  `admin_id` INT(10) DEFAULT '0' COMMENT '管理员ID',
  `createtime` BIGINT(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` BIGINT(16) DEFAULT NULL COMMENT '更新时间',
  `weigh` INT(10) DEFAULT '0' COMMENT '权重',
  `status` ENUM('normal','hidden') DEFAULT 'normal' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=INNODB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='字典';

CREATE TABLE `__PREFIX__csmipr_xdictvalue` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `csmipr_xdictset_id` INT(10) NOT NULL COMMENT '字典',
  `code` VARCHAR(100) DEFAULT '' COMMENT '代码',
  `label` VARCHAR(100) DEFAULT '' COMMENT '显示名称',
  `en_label` VARCHAR(100) DEFAULT '' COMMENT '显示名称(EN)',
  `desc` TEXT COMMENT '备注',

  `admin_id` INT(10) DEFAULT '0' COMMENT '管理员ID',
  `createtime` BIGINT(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` BIGINT(16) DEFAULT NULL COMMENT '更新时间',
  `weigh` INT(10) DEFAULT '0' COMMENT '权重',
  `status` ENUM('normal','hidden') DEFAULT 'normal' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=INNODB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='字典值';

COMMIT;