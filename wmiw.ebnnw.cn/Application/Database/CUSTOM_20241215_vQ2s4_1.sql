# -----------------------------------------------------------
# PHP-Amateur database backup files
# Type: 管理员后台手动备份
# Description:当前SQL文件包含了表：on_access、on_admin、on_advertising、on_advertising_position、on_attention、on_attention_seller、on_auction、on_auction_agency、on_auction_pledge、on_auction_record、on_auction_repeat、on_blacklist、on_category、on_consultation、on_deliver_address、on_feedback、on_goods、on_goods_category、on_goods_category_extend、on_goods_category_filtrate、on_goods_evaluate、on_goods_extend、on_goods_fields、on_goods_filtrate、on_goods_order、on_goods_order_return、on_goods_user、on_link、on_meeting_auction、on_member、on_member_evaluate、on_member_footprint、on_member_limsum_bill、on_member_pledge_bill、on_member_pledge_take、on_member_weixin、on_mysms、on_navigation、on_news、on_node、on_order_break、on_payorder、on_rechargeable、on_role、on_role_user、on_scheduled、on_seller_pledge、on_share、on_special_auction、on_weiurl的结构信息，表：on_access、on_admin、on_advertising、on_advertising_position、on_attention、on_attention_seller、on_auction、on_auction_agency、on_auction_pledge、on_auction_record、on_auction_repeat、on_blacklist、on_category、on_consultation、on_deliver_address、on_feedback、on_goods、on_goods_category、on_goods_category_extend、on_goods_category_filtrate、on_goods_evaluate、on_goods_extend、on_goods_fields、on_goods_filtrate、on_goods_order、on_goods_order_return、on_goods_user、on_link、on_meeting_auction、on_member、on_member_evaluate、on_member_footprint、on_member_limsum_bill、on_member_pledge_bill、on_member_pledge_take、on_member_weixin、on_mysms、on_navigation、on_news、on_node、on_order_break、on_payorder、on_rechargeable、on_role、on_role_user、on_scheduled、on_seller_pledge、on_share、on_special_auction、on_weiurl的数据
# Time: 2024-12-15 11:51:10
# -----------------------------------------------------------
# 当前SQL卷标：#1
# -----------------------------------------------------------


# 数据库表：on_access 结构信息
DROP TABLE IF EXISTS `on_access`;
CREATE TABLE `on_access` (
  `role_id` smallint(6) unsigned NOT NULL,
  `node_id` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) NOT NULL,
  `pid` smallint(6) DEFAULT NULL,
  `module` varchar(50) DEFAULT NULL,
  KEY `groupId` (`role_id`),
  KEY `nodeId` (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='权限分配表' ;

# 数据库表：on_admin 结构信息
DROP TABLE IF EXISTS `on_admin`;
CREATE TABLE `on_admin` (
  `aid` int(11) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL COMMENT '登录账号',
  `avatar` varchar(100) NOT NULL COMMENT '用户头像',
  `pwd` char(32) DEFAULT NULL COMMENT '登录密码',
  `status` int(11) DEFAULT '1' COMMENT '账号状态',
  `remark` varchar(255) DEFAULT '' COMMENT '备注信息',
  `find_code` char(5) DEFAULT NULL COMMENT '找回账号验证码',
  `time` int(10) DEFAULT NULL COMMENT '开通时间',
  PRIMARY KEY (`aid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='网站后台管理员表' ;

# 数据库表：on_advertising 结构信息
DROP TABLE IF EXISTS `on_advertising`;
CREATE TABLE `on_advertising` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` mediumint(8) NOT NULL COMMENT '所属广告位ID',
  `name` varchar(20) NOT NULL COMMENT '广告名称',
  `code` text NOT NULL COMMENT '广告代码',
  `bkcolor` varchar(7) NOT NULL COMMENT '广告背景色',
  `type` tinyint(1) NOT NULL COMMENT '广告类型',
  `status` tinyint(4) NOT NULL COMMENT '禁用状态：1：禁用；0：启用',
  `url` varchar(255) NOT NULL COMMENT '链接地址',
  `click_count` int(11) NOT NULL COMMENT '点击统计',
  `desc` text NOT NULL COMMENT '说明',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `adv_start_time` int(11) DEFAULT '0' COMMENT '广告开始时间',
  `adv_end_time` int(11) DEFAULT '0' COMMENT '广告结束时间',
  PRIMARY KEY (`id`),
  KEY `position_id` (`pid`),
  KEY `inx_adv_001` (`status`,`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='广告表' ;

# 数据库表：on_advertising_position 结构信息
DROP TABLE IF EXISTS `on_advertising_position`;
CREATE TABLE `on_advertising_position` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `tagname` varchar(30) NOT NULL COMMENT '广告标示',
  `name` varchar(60) NOT NULL COMMENT '广告名称',
  `width` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '宽度',
  `height` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '高度',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='广告位' ;

# 数据库表：on_attention 结构信息
DROP TABLE IF EXISTS `on_attention`;
CREATE TABLE `on_attention` (
  `gid` int(11) NOT NULL COMMENT '商品id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `rela` varchar(5) NOT NULL COMMENT '关注类型p-u：拍品关注g-u：一口价关注'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户关注商品表' ;

# 数据库表：on_attention_seller 结构信息
DROP TABLE IF EXISTS `on_attention_seller`;
CREATE TABLE `on_attention_seller` (
  `uid` int(11) NOT NULL COMMENT '用户id',
  `sellerid` int(11) NOT NULL COMMENT '卖家id',
  `time` int(10) NOT NULL COMMENT '关注时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户关注卖家' ;

# 数据库表：on_auction 结构信息
DROP TABLE IF EXISTS `on_auction`;
CREATE TABLE `on_auction` (
  `pid` int(11) NOT NULL AUTO_INCREMENT COMMENT '拍卖id',
  `gid` int(11) NOT NULL COMMENT '商品id',
  `sid` int(11) NOT NULL COMMENT '专场id',
  `mid` int(11) NOT NULL COMMENT '拍卖会id',
  `bidnb` varchar(30) NOT NULL COMMENT '拍品编号',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '拍卖类型',
  `succtype` tinyint(1) NOT NULL COMMENT '成交模式0普通模式1即时成交',
  `succprice` decimal(10,2) NOT NULL COMMENT '即时成交价格',
  `freight` decimal(10,2) NOT NULL COMMENT '运费',
  `pattern` tinyint(1) NOT NULL DEFAULT '0' COMMENT '拍卖模式0：集市 1：专场扣除，2：专场单品 3：拍卖会4：拍卖会单品',
  `status` int(2) NOT NULL DEFAULT '0' COMMENT '拍卖状态0新增，1降价',
  `pname` varchar(255) NOT NULL COMMENT '拍卖名称',
  `onset` decimal(10,2) NOT NULL COMMENT '起拍价',
  `price` decimal(10,2) NOT NULL COMMENT '保留价',
  `nowprice` decimal(10,2) NOT NULL COMMENT '当前价',
  `starttime` int(10) NOT NULL COMMENT '开始时间',
  `endtime` int(10) NOT NULL COMMENT '结束时间',
  `stepsize` varchar(255) NOT NULL COMMENT '价格浮动',
  `stepsize_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '价格浮动方式',
  `pledge_type` varchar(10) NOT NULL DEFAULT 'fixation' COMMENT '保证金冻结方式',
  `broker_type` varchar(10) NOT NULL DEFAULT 'fixation' COMMENT '佣金收取方式ratio比例;fixation定额',
  `broker` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '佣金',
  `broker_buy_type` varchar(10) NOT NULL DEFAULT 'fixation' COMMENT '买家佣金收取方式ratio比例;fixation定额',
  `broker_buy` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '买家佣金',
  `pledge` decimal(10,2) NOT NULL COMMENT '参拍保证金',
  `steptime` int(10) NOT NULL DEFAULT '0' COMMENT '触发延时时间段',
  `deferred` int(10) NOT NULL DEFAULT '0' COMMENT '延时时间',
  `uid` int(11) NOT NULL COMMENT '当前出价人id',
  `agency_uid` int(11) NOT NULL COMMENT '最高代理人uid',
  `agency_price` decimal(10,2) NOT NULL COMMENT '最高代理价',
  `bidcount` int(11) NOT NULL DEFAULT '0' COMMENT '出价次数',
  `endstatus` tinyint(1) DEFAULT '0' COMMENT '0：无状态，1成交，2.流拍，3无人出价流拍，4.撤拍',
  `hide` tinyint(1) NOT NULL COMMENT '是否隐藏',
  `recommend` tinyint(1) NOT NULL COMMENT '是否推荐',
  `clcount` int(11) NOT NULL DEFAULT '0' COMMENT '想拍人数',
  `msort` int(11) NOT NULL COMMENT '拍卖会排序',
  `time` int(10) NOT NULL COMMENT '发布或更新时间',
  `aid` int(11) NOT NULL COMMENT '发布人',
  PRIMARY KEY (`pid`),
  KEY `msort` (`msort`),
  KEY `gid` (`gid`),
  KEY `sid` (`sid`),
  KEY `mid` (`mid`),
  KEY `type` (`type`),
  KEY `pattern` (`pattern`),
  KEY `status` (`status`),
  KEY `onset` (`onset`),
  KEY `starttime` (`starttime`),
  KEY `endtime` (`endtime`),
  KEY `endstatus` (`endstatus`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='拍卖表' ;

# 数据库表：on_auction_agency 结构信息
DROP TABLE IF EXISTS `on_auction_agency`;
CREATE TABLE `on_auction_agency` (
  `pid` int(11) NOT NULL COMMENT '拍卖id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `price` int(11) NOT NULL COMMENT '目标价',
  `time` int(10) NOT NULL COMMENT '设置时间',
  `status` tinyint(1) NOT NULL COMMENT '代理出价状态0：执行中无状态；1：达到目标价；2：被超越；3已关闭',
  KEY `pid` (`pid`),
  KEY `uid` (`uid`),
  KEY `status` (`status`),
  KEY `price` (`price`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='代理出价表' ;

# 数据库表：on_auction_pledge 结构信息
DROP TABLE IF EXISTS `on_auction_pledge`;
CREATE TABLE `on_auction_pledge` (
  `uid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `pledge` decimal(10,0) NOT NULL,
  `time` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='拍卖冻结用户保证金记录' ;

# 数据库表：on_auction_record 结构信息
DROP TABLE IF EXISTS `on_auction_record`;
CREATE TABLE `on_auction_record` (
  `pid` int(11) NOT NULL COMMENT '拍品id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `time` int(10) NOT NULL COMMENT '出价时间',
  `money` decimal(10,2) NOT NULL COMMENT '出价金额',
  `bided` decimal(10,2) NOT NULL COMMENT '出价后',
  `type` varchar(10) NOT NULL COMMENT '出价方式',
  KEY `uid` (`uid`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='拍卖出价记录' ;

# 数据库表：on_auction_repeat 结构信息
DROP TABLE IF EXISTS `on_auction_repeat`;
CREATE TABLE `on_auction_repeat` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `type` tinyint(1) NOT NULL COMMENT '类型：0[拍品]1[ 专场]2 [拍卖会]',
  `rid` int(11) NOT NULL COMMENT '对应类型的id',
  `etafter` int(11) NOT NULL COMMENT '每次间隔时间（秒）',
  `prg` int(11) NOT NULL COMMENT '共计拍卖次数',
  `now` int(11) NOT NULL COMMENT '当前拍卖次数',
  `stop` tinyint(1) NOT NULL COMMENT '停止条件0：达到次数；1：商品成交',
  `pastidstr` text NOT NULL COMMENT '记录已拍的id，用-分割',
  `status` tinyint(1) NOT NULL COMMENT '状态：0：执行中；1：已停止',
  `time` int(10) NOT NULL COMMENT '添加循环时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='拍卖自动上架' ;

# 数据库表：on_blacklist 结构信息
DROP TABLE IF EXISTS `on_blacklist`;
CREATE TABLE `on_blacklist` (
  `uid` int(11) NOT NULL COMMENT '所属用户uid',
  `xid` int(11) NOT NULL COMMENT '拉黑用户uid',
  `time` int(10) NOT NULL COMMENT '拉黑时间',
  `selbuy` varchar(5) NOT NULL COMMENT '拉黑角色（buy：卖家；sel：卖家）',
  KEY `uid` (`uid`),
  KEY `xid` (`xid`),
  KEY `time` (`time`),
  KEY `selbuy` (`selbuy`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='黑名单' ;

# 数据库表：on_category 结构信息
DROP TABLE IF EXISTS `on_category`;
CREATE TABLE `on_category` (
  `cid` int(5) NOT NULL AUTO_INCREMENT,
  `pid` int(5) DEFAULT NULL COMMENT 'parentCategory上级分类',
  `name` varchar(20) DEFAULT NULL COMMENT '分类名称',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='新闻分类表' ;

# 数据库表：on_consultation 结构信息
DROP TABLE IF EXISTS `on_consultation`;
CREATE TABLE `on_consultation` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '意见反馈id',
  `uid` int(11) NOT NULL COMMENT '反馈用户uid',
  `content` text NOT NULL COMMENT '反馈内容',
  `time` int(10) NOT NULL COMMENT '反馈时间',
  `reply` text NOT NULL COMMENT '回复内容',
  `rtime` int(10) NOT NULL COMMENT '回复时间',
  `aid` int(11) NOT NULL COMMENT '回复管理员aid',
  `status` tinyint(1) NOT NULL COMMENT '是否已读',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='意见反馈' ;

# 数据库表：on_deliver_address 结构信息
DROP TABLE IF EXISTS `on_deliver_address`;
CREATE TABLE `on_deliver_address` (
  `adid` int(11) NOT NULL AUTO_INCREMENT COMMENT '地址id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `prov` varchar(20) NOT NULL COMMENT '省',
  `city` varchar(20) NOT NULL COMMENT '市',
  `district` varchar(20) NOT NULL COMMENT '区、县',
  `address` varchar(50) NOT NULL COMMENT '详细地址',
  `postalcode` int(10) NOT NULL COMMENT '邮政编码',
  `truename` varchar(8) NOT NULL COMMENT '收件人姓名',
  `mobile` varchar(11) NOT NULL COMMENT '手机号',
  `phone` varchar(30) NOT NULL COMMENT '电话号码',
  `default` tinyint(1) NOT NULL COMMENT '是否默认：1是，0否',
  PRIMARY KEY (`adid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='地址表' ;

# 数据库表：on_feedback 结构信息
DROP TABLE IF EXISTS `on_feedback`;
CREATE TABLE `on_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(10) DEFAULT NULL COMMENT '推广名',
  `count` int(11) DEFAULT '0' COMMENT '统计',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='推广类型表' ;

# 数据库表：on_goods 结构信息
DROP TABLE IF EXISTS `on_goods`;
CREATE TABLE `on_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) DEFAULT NULL COMMENT '所属类',
  `title` varchar(200) DEFAULT NULL COMMENT '商品名称',
  `filtrate` varchar(255) DEFAULT NULL COMMENT '筛选条件id集合',
  `keywords` varchar(50) DEFAULT NULL COMMENT '商品关键字',
  `description` varchar(255) DEFAULT NULL COMMENT '商品描述',
  `price` decimal(10,2) NOT NULL COMMENT '商品价格',
  `content` mediumtext COMMENT '商品详情',
  `prov` varchar(20) NOT NULL COMMENT '省',
  `city` varchar(20) NOT NULL COMMENT '市',
  `district` varchar(20) NOT NULL COMMENT '区',
  `pictures` text COMMENT '商品图片',
  `viedo` text NOT NULL COMMENT '商品视频',
  `published` int(10) DEFAULT NULL COMMENT '发布时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  `sellerid` int(11) NOT NULL COMMENT '卖家id',
  `aid` int(11) DEFAULT NULL COMMENT '发布者',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='商品表' ;

# 数据库表：on_goods_category 结构信息
DROP TABLE IF EXISTS `on_goods_category`;
CREATE TABLE `on_goods_category` (
  `cid` int(5) NOT NULL AUTO_INCREMENT,
  `pid` int(5) DEFAULT NULL COMMENT 'parentCategory上级分类',
  `name` varchar(20) DEFAULT NULL COMMENT '分类名称',
  `remark` varchar(255) NOT NULL COMMENT '备注',
  `ico` varchar(255) DEFAULT NULL COMMENT '分类图标',
  `path` text NOT NULL COMMENT '路径',
  `hot` tinyint(1) NOT NULL COMMENT '是否推荐',
  `modelno` int(1) NOT NULL COMMENT '显示效果',
  `sort` int(5) NOT NULL COMMENT '排序',
  PRIMARY KEY (`cid`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商品分类表' ;

# 数据库表：on_goods_category_extend 结构信息
DROP TABLE IF EXISTS `on_goods_category_extend`;
CREATE TABLE `on_goods_category_extend` (
  `cid` int(5) DEFAULT NULL COMMENT '商品分类id',
  `eid` int(5) DEFAULT NULL COMMENT '扩展字段id',
  KEY `cid` (`cid`),
  KEY `eid` (`eid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品分类扩展字段关联表' ;

# 数据库表：on_goods_category_filtrate 结构信息
DROP TABLE IF EXISTS `on_goods_category_filtrate`;
CREATE TABLE `on_goods_category_filtrate` (
  `cid` int(5) DEFAULT NULL COMMENT '商品分类id',
  `fid` int(5) DEFAULT NULL COMMENT '商品属性id',
  KEY `cid` (`cid`),
  KEY `fid` (`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品分类与商品属性关联表' ;

# 数据库表：on_goods_evaluate 结构信息
DROP TABLE IF EXISTS `on_goods_evaluate`;
CREATE TABLE `on_goods_evaluate` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '评价id',
  `uid` int(11) NOT NULL COMMENT '评价用户id',
  `sellerid` int(11) NOT NULL COMMENT '商品所属用户',
  `pid` int(11) NOT NULL COMMENT '拍品id',
  `order_no` varchar(100) NOT NULL COMMENT '对应订单号',
  `conform_evaluate` varchar(255) NOT NULL COMMENT '商品评价',
  `conform` int(2) NOT NULL COMMENT '商品评分',
  `pictures` text NOT NULL COMMENT '评价晒图',
  `time` int(10) NOT NULL COMMENT '评价时间',
  `member` int(2) NOT NULL COMMENT '买家得分',
  `member_evaluate` varchar(255) NOT NULL COMMENT '回评内容',
  `rtime` int(10) NOT NULL COMMENT '回评时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品评价表' ;

# 数据库表：on_goods_extend 结构信息
DROP TABLE IF EXISTS `on_goods_extend`;
CREATE TABLE `on_goods_extend` (
  `eid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(16) DEFAULT NULL COMMENT '字段名',
  `default` mediumtext COMMENT '字段默认值',
  `rank` int(2) NOT NULL DEFAULT '0' COMMENT '字段排序',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否启用0：不启用，1启用',
  PRIMARY KEY (`eid`),
  KEY `rank` (`rank`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品扩展字段' ;

# 数据库表：on_goods_fields 结构信息
DROP TABLE IF EXISTS `on_goods_fields`;
CREATE TABLE `on_goods_fields` (
  `eid` int(11) NOT NULL,
  `default` text COMMENT '字段默认值',
  `gid` int(11) NOT NULL,
  KEY `eid` (`eid`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品对应字段' ;

# 数据库表：on_goods_filtrate 结构信息
DROP TABLE IF EXISTS `on_goods_filtrate`;
CREATE TABLE `on_goods_filtrate` (
  `fid` int(5) NOT NULL AUTO_INCREMENT,
  `pid` int(5) DEFAULT NULL COMMENT '上级条件',
  `name` varchar(20) DEFAULT NULL COMMENT '商品属性名称',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`fid`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商品属性表' ;

# 数据库表：on_goods_order 结构信息
DROP TABLE IF EXISTS `on_goods_order`;
CREATE TABLE `on_goods_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_no` varchar(100) NOT NULL COMMENT '订单号',
  `type` tinyint(1) NOT NULL COMMENT '订单类型  0竞拍 1 竞价 2一口价',
  `gid` int(11) NOT NULL COMMENT '商品id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `sellerid` int(11) NOT NULL COMMENT '卖家uid',
  `price` decimal(10,2) NOT NULL COMMENT '商品价',
  `freight` decimal(10,2) NOT NULL COMMENT '运费',
  `broker` decimal(10,2) NOT NULL COMMENT '佣金',
  `broker_buy` decimal(10,2) NOT NULL COMMENT '买家需要支付的佣金',
  `time` int(10) NOT NULL COMMENT '订单生成时间',
  `time1` int(11) NOT NULL COMMENT '支付时间',
  `time2` int(11) NOT NULL COMMENT '发货时间',
  `time3` int(11) NOT NULL COMMENT '买家收货时间',
  `time4` int(11) NOT NULL COMMENT '评价卖家时间',
  `time10` int(10) NOT NULL COMMENT '卖家评价时间',
  `deftime2` int(10) NOT NULL COMMENT '默认发货时间',
  `deftime2st` tinyint(1) NOT NULL COMMENT '默认发货处理状态,0：执行，1已执行',
  `deftime3` int(10) NOT NULL COMMENT '默认收货时间',
  `deftime3st` tinyint(1) NOT NULL COMMENT '默认收货处理状态,0：未执行，1已执行',
  `deftime4` int(10) NOT NULL COMMENT '买家默认评价时间',
  `deftime4st` tinyint(1) NOT NULL COMMENT '买家默认评价状态,0：未执行，1已执行',
  `deftime10` int(10) NOT NULL COMMENT '卖家默认评价时间',
  `deftime10st` tinyint(1) NOT NULL COMMENT '卖家默认评价状态,0：未执行，1已执行',
  `deftime1` int(10) NOT NULL COMMENT '支付过期时间',
  `deftime1st` tinyint(1) NOT NULL COMMENT '支付过期状态，0：未处理，1：已处理',
  `status` tinyint(2) NOT NULL COMMENT '支付状态0：待支付1：已支付待发货 2：已发货待收货3：已收货待评价 4：已评价卖家 5：申请退货 6：同意退货 7：不同意退货 8：买家已发货 9：卖家确认收货 10：已互评',
  `rstatus` tinyint(1) NOT NULL COMMENT '是否退货流程0：购买流程 1：退货流程',
  `downpay` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1：线下已支付',
  `buyer_msg` varchar(200) NOT NULL COMMENT '买家留言',
  `address` text NOT NULL COMMENT '收货地址',
  `express` varchar(50) NOT NULL COMMENT '快递公司',
  `express_other` varchar(20) NOT NULL COMMENT '其他快递物流',
  `express_no` varchar(30) NOT NULL COMMENT '快递单号',
  `remark` varchar(255) NOT NULL COMMENT '备注',
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_no` (`order_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单表' ;

# 数据库表：on_goods_order_return 结构信息
DROP TABLE IF EXISTS `on_goods_order_return`;
CREATE TABLE `on_goods_order_return` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_no` varchar(100) NOT NULL COMMENT '订单号',
  `cause` varchar(255) NOT NULL COMMENT '退货原因',
  `money` decimal(10,2) NOT NULL COMMENT '退货金额',
  `explain` varchar(255) NOT NULL COMMENT '退货说明',
  `evidence` text NOT NULL COMMENT '退货凭证',
  `express` varchar(50) NOT NULL COMMENT '快递公司',
  `express_other` varchar(20) NOT NULL COMMENT '其他快递公司',
  `express_no` varchar(30) NOT NULL COMMENT '快递单号',
  `address` text NOT NULL COMMENT '买家收货地址',
  `selcause` varchar(255) NOT NULL COMMENT '卖家拒绝退货原因',
  `mediate` tinyint(1) NOT NULL COMMENT '是否调解状态',
  `defmediate` int(10) NOT NULL COMMENT '申请调解期限，如过期将设置买家默认收货。',
  `defmediatest` tinyint(1) NOT NULL COMMENT '默认买家未申请介入过期处理状态，0：未执行 1已执行  (【申请介入过期】 如买家未在  有效期申请平台介入，将默认买家订单为已收货进入正常流程)',
  `mediate_time` int(10) NOT NULL COMMENT '申请调节时间',
  `time5` int(10) NOT NULL COMMENT '买家提交退货时间',
  `time6` int(10) NOT NULL COMMENT '卖家拒绝退货时间',
  `deftime6` int(10) NOT NULL COMMENT '卖家默认拒绝时间',
  `deftime6st` tinyint(1) NOT NULL COMMENT '默认拒绝退货处理状态，0：未执行 1已执行  (【卖家拒绝退货】卖家在期限内未同意退货，将默认不同意退货)',
  `time7` int(10) NOT NULL COMMENT '卖家同意退货',
  `time8` int(10) NOT NULL COMMENT '买家发货时间',
  `deftime8` int(10) NOT NULL COMMENT '买家发货过期时间',
  `deftime8st` tinyint(1) NOT NULL COMMENT '默认买家发货处理状态，0：未执行 1已执行  (【买家发货过期】 如买家未在  有效期发货，将默认买家订单为已收货进入正常流程)',
  `time9` int(10) NOT NULL COMMENT '卖家收货时间',
  `deftime9` int(10) NOT NULL COMMENT '卖家默认收货时间',
  `deftime9st` tinyint(1) NOT NULL COMMENT '默认卖家收货时间处理状态，0：未执行 1已执行  (【卖家默认收货】 进入正常  流程买家评价-卖家评价)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_no` (`order_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='退货订单' ;

# 数据库表：on_goods_user 结构信息
DROP TABLE IF EXISTS `on_goods_user`;
CREATE TABLE `on_goods_user` (
  `uid` int(11) NOT NULL COMMENT '用户id',
  `gid` int(11) NOT NULL COMMENT '商品id',
  `limsum` decimal(10,2) NOT NULL COMMENT '冻结信用额度',
  `pledge` decimal(10,2) NOT NULL COMMENT '冻结的保证金',
  `g-u` varchar(3) NOT NULL COMMENT 'p-u：拍品-用户g-u：商品-用户,s-u：专场-用户',
  `time` int(10) NOT NULL COMMENT '时间',
  `rtime` int(10) NOT NULL COMMENT '保证金处理时间',
  `status` tinyint(1) NOT NULL COMMENT '处理状态，0：未处理，1：已处理'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户对商品缴纳保证金' ;

# 数据库表：on_link 结构信息
DROP TABLE IF EXISTS `on_link`;
CREATE TABLE `on_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL COMMENT '名称',
  `url` varchar(255) NOT NULL COMMENT '链接',
  `ico` varchar(255) NOT NULL COMMENT '图标',
  `rec` tinyint(1) NOT NULL COMMENT '图标显示0：不显示1：显示',
  `sort` int(11) NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='友情链接表' ;

# 数据库表：on_meeting_auction 结构信息
DROP TABLE IF EXISTS `on_meeting_auction`;
CREATE TABLE `on_meeting_auction` (
  `mid` int(11) NOT NULL AUTO_INCREMENT COMMENT '拍卖会id',
  `mname` varchar(200) NOT NULL COMMENT '拍卖会名',
  `mpicture` text NOT NULL COMMENT '拍卖会列表图',
  `mbanner` text NOT NULL COMMENT '拍卖会banner',
  `description` varchar(255) NOT NULL COMMENT '描述',
  `starttime` int(10) NOT NULL COMMENT '开始时间',
  `endtime` int(10) NOT NULL COMMENT '结束时间',
  `losetime` int(10) NOT NULL COMMENT '流拍时间',
  `bidtime` int(10) NOT NULL COMMENT '拍卖时间',
  `intervaltime` int(10) NOT NULL COMMENT '间隔时间',
  `meeting_pledge_type` tinyint(1) NOT NULL COMMENT '保证金扣除模式',
  `mpledge` decimal(10,0) NOT NULL DEFAULT '0' COMMENT '保证金',
  `mcount` int(11) NOT NULL COMMENT '拍卖会出价次数',
  `bcount` int(11) NOT NULL COMMENT '拍卖会拍品数量',
  `sendstatus` tinyint(1) NOT NULL DEFAULT '0' COMMENT '结束状态 0未结束 1结束',
  `sellerid` int(10) NOT NULL COMMENT '卖家uid',
  `hide` tinyint(1) NOT NULL COMMENT '是否隐藏',
  `aid` int(11) NOT NULL COMMENT '发布者',
  PRIMARY KEY (`mid`),
  KEY `sid` (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='拍卖会表' ;

# 数据库表：on_member 结构信息
DROP TABLE IF EXISTS `on_member`;
CREATE TABLE `on_member` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `sourceuid` int(11) NOT NULL COMMENT '推广人uid',
  `weibo_uid` varchar(15) DEFAULT NULL COMMENT '对应的新浪微博uid',
  `tencent_uid` varchar(20) DEFAULT NULL COMMENT '腾讯微博UID',
  `email` varchar(100) DEFAULT NULL COMMENT '邮箱地址',
  `account` varchar(20) DEFAULT NULL COMMENT '登录账号',
  `nickname` varchar(20) DEFAULT NULL COMMENT '用户昵称',
  `organization` varchar(100) NOT NULL COMMENT '机构组织',
  `intro` varchar(255) NOT NULL COMMENT '机构简介',
  `pwd` char(32) DEFAULT NULL,
  `paypwd` char(32) CHARACTER SET armscii8 NOT NULL COMMENT '支付密码',
  `truename` varchar(20) DEFAULT NULL COMMENT '真实姓名',
  `idcard` varchar(20) NOT NULL COMMENT '身份证号码',
  `idcard_front` varchar(255) NOT NULL COMMENT '身份证前面',
  `idcard_behind` varchar(255) NOT NULL COMMENT '身份证后面',
  `idcard_check` tinyint(1) DEFAULT NULL COMMENT '实名认证1:已提交认证 2：已认证 3：未通过认证',
  `idcard_check_time` int(10) NOT NULL COMMENT '实名认证操作时间',
  `question` text NOT NULL COMMENT '安全问题',
  `mobile` varchar(11) DEFAULT NULL,
  `reg_date` int(10) DEFAULT NULL COMMENT '注册时间',
  `reg_ip` char(15) DEFAULT NULL COMMENT '注册IP地址',
  `verify_email` int(1) DEFAULT '0' COMMENT '电子邮件验证标示 0未验证，1已验证',
  `verify_mobile` int(1) DEFAULT '0' COMMENT '手机验证状态',
  `find_fwd_code` varchar(32) DEFAULT NULL COMMENT '找回密码验证随机码',
  `find_pwd_time` int(10) DEFAULT NULL COMMENT '找回密码申请提交时间',
  `find_pwd_exp_time` int(10) DEFAULT NULL COMMENT '找回密码验证随机码过期时间',
  `avatar` varchar(100) DEFAULT NULL COMMENT '用户头像',
  `avatar_sel` varchar(100) NOT NULL COMMENT '卖家头像',
  `birthday` int(10) DEFAULT NULL COMMENT '用户生日',
  `sex` int(1) DEFAULT NULL COMMENT '0女1男',
  `address` varchar(50) DEFAULT NULL COMMENT '地址',
  `postalcode` int(10) DEFAULT NULL COMMENT '邮政编码',
  `prov` varchar(20) DEFAULT NULL COMMENT '省份',
  `city` varchar(20) DEFAULT NULL COMMENT '城市',
  `district` varchar(20) NOT NULL COMMENT '区、县',
  `intr` varchar(500) DEFAULT NULL COMMENT '个人介绍',
  `phone` varchar(30) DEFAULT NULL COMMENT '电话',
  `fax` varchar(30) DEFAULT NULL,
  `qq` int(15) DEFAULT NULL,
  `msn` varchar(100) DEFAULT NULL,
  `wallet_pledge` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '保证金账户',
  `wallet_pledge_freeze` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '保证金冻结金额',
  `wallet_limsum` decimal(10,2) NOT NULL COMMENT '信用额度',
  `wallet_limsum_freeze` decimal(10,2) NOT NULL COMMENT '信用冻结额度',
  `score` int(10) NOT NULL DEFAULT '0' COMMENT '卖家得分',
  `scorebuy` int(11) NOT NULL DEFAULT '0' COMMENT '买家得分',
  `login_ip` varchar(15) DEFAULT NULL COMMENT '登录ip',
  `login_time` int(10) DEFAULT NULL COMMENT '登录时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `weiauto` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1：自动登陆；0：手动登陆',
  `alerttype` varchar(30) NOT NULL COMMENT '提醒方式（email，mobile，weixin）',
  PRIMARY KEY (`uid`),
  KEY `account` (`account`),
  KEY `email` (`email`),
  KEY `account_2` (`account`),
  KEY `status` (`status`),
  KEY `sourceuid` (`sourceuid`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='网站前台会员表' ;

# 数据库表：on_member_evaluate 结构信息
DROP TABLE IF EXISTS `on_member_evaluate`;
CREATE TABLE `on_member_evaluate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '评价所属人',
  `sellerid` int(11) NOT NULL COMMENT '卖家id',
  `pid` int(11) NOT NULL COMMENT '拍品id',
  `score` tinyint(1) NOT NULL COMMENT '得分：0差评；1中评；2好评',
  `evaluate` varchar(255) NOT NULL COMMENT '评价内容',
  `time` int(10) NOT NULL COMMENT '评价时间',
  `order_no` varchar(100) NOT NULL COMMENT '对应订单',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='卖家对用户的评价' ;

# 数据库表：on_member_footprint 结构信息
DROP TABLE IF EXISTS `on_member_footprint`;
CREATE TABLE `on_member_footprint` (
  `uid` int(11) NOT NULL COMMENT '用户id',
  `pidstr` text NOT NULL COMMENT '看过的拍品pid集合'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户足迹' ;

# 数据库表：on_member_limsum_bill 结构信息
DROP TABLE IF EXISTS `on_member_limsum_bill`;
CREATE TABLE `on_member_limsum_bill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_no` varchar(100) NOT NULL COMMENT '单号',
  `uid` int(11) NOT NULL,
  `changetype` varchar(20) NOT NULL COMMENT '竞拍冻结bid_freeze 竞拍解冻bid_unfreeze 后台充值admin_deposit 管理员扣除 admin_deduct 支付充值pay_deposit 支付扣除pay_deduct  提现extract',
  `time` int(10) DEFAULT NULL COMMENT '操作时间',
  `annotation` text COMMENT '记录操作说明',
  `income` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '账户收入',
  `expend` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '账户支出',
  `usable` decimal(10,2) NOT NULL COMMENT '可用余额',
  `balance` decimal(10,2) NOT NULL COMMENT '当前余额',
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_no` (`order_no`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户信用额度账单' ;

# 数据库表：on_member_pledge_bill 结构信息
DROP TABLE IF EXISTS `on_member_pledge_bill`;
CREATE TABLE `on_member_pledge_bill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_no` varchar(100) NOT NULL COMMENT '订单号',
  `uid` int(11) NOT NULL,
  `changetype` varchar(20) NOT NULL COMMENT '竞拍冻结bid_freeze 竞拍解冻bid_unfreeze 后台充值admin_deposit 管理员扣除 admin_deduct 支付充值pay_deposit 支付扣除pay_deduct  提现extract',
  `time` int(10) DEFAULT NULL COMMENT '操作时间',
  `annotation` text COMMENT '记录操作说明',
  `income` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '账户收入',
  `expend` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '账户支出',
  `usable` decimal(10,2) NOT NULL COMMENT '可用余额',
  `balance` decimal(10,2) NOT NULL COMMENT '当前余额',
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_no` (`order_no`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='用户保证金账单' ;

# 数据库表：on_member_pledge_take 结构信息
DROP TABLE IF EXISTS `on_member_pledge_take`;
CREATE TABLE `on_member_pledge_take` (
  `tid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '提现用户',
  `money` decimal(10,2) NOT NULL COMMENT '金额',
  `bank` varchar(20) NOT NULL COMMENT '银行',
  `bankhome` varchar(255) NOT NULL COMMENT '开户行',
  `name` varchar(10) NOT NULL COMMENT '体现人名',
  `account` varchar(30) NOT NULL COMMENT '账号',
  `remark` varchar(255) NOT NULL COMMENT '备注',
  `time` int(10) NOT NULL COMMENT '时间',
  `dtime` int(10) NOT NULL COMMENT '处理时间',
  `cause` varchar(255) NOT NULL COMMENT '备注、原因',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0等待退款；1已退款；2驳回提现',
  PRIMARY KEY (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户提现账单' ;

# 数据库表：on_member_weixin 结构信息
DROP TABLE IF EXISTS `on_member_weixin`;
CREATE TABLE `on_member_weixin` (
  `openid` varchar(60) NOT NULL COMMENT '公众号微信标示',
  `uid` int(11) NOT NULL COMMENT '对应用户表id',
  `nickname` varchar(20) NOT NULL COMMENT '微信昵称',
  `sex` tinyint(1) NOT NULL COMMENT '微信性别',
  `city` varchar(16) NOT NULL COMMENT '微信城市',
  `country` varchar(40) NOT NULL COMMENT '国家',
  `province` varchar(16) NOT NULL COMMENT '省份',
  `language` varchar(10) NOT NULL COMMENT '使用语言',
  `headimgurl` text NOT NULL COMMENT '微信头像地址',
  `subscribe` tinyint(1) NOT NULL COMMENT '公众号是否关注',
  `subscribe_time` int(10) NOT NULL COMMENT '关注公众号时间',
  `unionid` varchar(60) NOT NULL COMMENT '开放平台标示',
  `remark` varchar(255) NOT NULL COMMENT '备注',
  `groupid` int(11) NOT NULL COMMENT '分组id',
  `weitime` int(10) NOT NULL COMMENT '微信登陆时间',
  UNIQUE KEY `openid` (`openid`) USING BTREE,
  UNIQUE KEY `uid` (`uid`) USING BTREE,
  KEY `groupid` (`groupid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微信用户表' ;

# 数据库表：on_mysms 结构信息
DROP TABLE IF EXISTS `on_mysms`;
CREATE TABLE `on_mysms` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '收件人id',
  `rsid` int(11) NOT NULL COMMENT '回复消息的sid',
  `sendid` int(11) NOT NULL COMMENT '发送人id',
  `aid` int(11) NOT NULL COMMENT '管理员id',
  `pid` int(11) NOT NULL COMMENT '对应拍品的pid',
  `type` varchar(20) NOT NULL COMMENT '消息类型',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '信息状态0：未读；1：已读；',
  `delmark` tinyint(1) NOT NULL COMMENT '删除标记 1：设置删除',
  `content` text NOT NULL COMMENT '信息内容',
  `time` int(10) NOT NULL COMMENT '接收时间',
  PRIMARY KEY (`sid`),
  KEY `uid` (`uid`),
  KEY `sendid` (`sendid`),
  KEY `aid` (`aid`),
  KEY `time` (`time`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='用户消息提醒' ;

# 数据库表：on_navigation 结构信息
DROP TABLE IF EXISTS `on_navigation`;
CREATE TABLE `on_navigation` (
  `lid` int(5) NOT NULL AUTO_INCREMENT,
  `pid` int(5) DEFAULT NULL COMMENT '上级导航',
  `name` varchar(20) DEFAULT NULL COMMENT '导航名称',
  `target` varchar(10) NOT NULL DEFAULT '_self' COMMENT '_blank:新窗口；_self:当前窗口',
  `url` varchar(255) NOT NULL DEFAULT 'javascript:void(0);' COMMENT '链接地址',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`lid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='网站导航链接表' ;

# 数据库表：on_news 结构信息
DROP TABLE IF EXISTS `on_news`;
CREATE TABLE `on_news` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `cid` smallint(3) DEFAULT NULL COMMENT '所在分类',
  `title` varchar(200) DEFAULT NULL COMMENT '新闻标题',
  `picture` varchar(255) NOT NULL COMMENT '文章图片show',
  `keywords` varchar(50) DEFAULT NULL COMMENT '文章关键字',
  `description` mediumtext COMMENT '文章描述',
  `status` tinyint(1) DEFAULT NULL COMMENT '状态',
  `summary` varchar(255) DEFAULT NULL COMMENT '文章摘要',
  `published` int(10) DEFAULT NULL COMMENT '发布时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  `content` text COMMENT '文章内容',
  `aid` smallint(3) DEFAULT NULL COMMENT '发布者UID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='新闻表' ;

# 数据库表：on_node 结构信息
DROP TABLE IF EXISTS `on_node`;
CREATE TABLE `on_node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `remark` varchar(255) DEFAULT NULL,
  `sort` smallint(6) unsigned DEFAULT NULL,
  `pid` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=166 DEFAULT CHARSET=utf8 COMMENT='权限节点表' ;

# 数据库表：on_order_break 结构信息
DROP TABLE IF EXISTS `on_order_break`;
CREATE TABLE `on_order_break` (
  `order_no` varchar(100) NOT NULL COMMENT '订单号',
  `pledge` decimal(10,2) NOT NULL COMMENT '保证金',
  `limsum` decimal(10,2) NOT NULL COMMENT '信誉额度',
  `time` int(10) NOT NULL COMMENT '收入时间',
  `how` varchar(4) NOT NULL COMMENT '角色，buy：买家 sel：卖家',
  KEY `order_no` (`order_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单违约网站收入' ;

# 数据库表：on_payorder 结构信息
DROP TABLE IF EXISTS `on_payorder`;
CREATE TABLE `on_payorder` (
  `bill_no` varchar(100) NOT NULL COMMENT '支付订单号',
  `order_no` varchar(100) NOT NULL COMMENT '本站订单号',
  `purpose` varchar(10) NOT NULL COMMENT '支付用途',
  `useid` int(11) NOT NULL COMMENT '支付用途的id号，如：拍品id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `money` decimal(10,2) NOT NULL COMMENT '在线支付金额',
  `yuemn` decimal(10,2) NOT NULL COMMENT '使用余额支付多少',
  `pledge` decimal(10,2) NOT NULL COMMENT '使用保证金支付多少',
  `paytype` varchar(20) NOT NULL COMMENT '支付方式',
  `title` varchar(255) NOT NULL COMMENT '订单标题',
  `return_url` varchar(255) NOT NULL COMMENT '同步返回页面',
  `show_url` varchar(255) NOT NULL COMMENT '商品展示地址以http://',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '支付状态0：未支付：1：已支付',
  `create_time` int(11) NOT NULL COMMENT '订单生成时间',
  `update_time` int(11) NOT NULL COMMENT '订单更新时间',
  KEY `bill_no` (`bill_no`),
  KEY `order_no` (`order_no`),
  KEY `status` (`status`),
  KEY `create_time` (`create_time`),
  KEY `update_time` (`update_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='支付订单' ;

# 数据库表：on_rechargeable 结构信息
DROP TABLE IF EXISTS `on_rechargeable`;
CREATE TABLE `on_rechargeable` (
  `cardno` varchar(20) NOT NULL COMMENT '卡号',
  `pwd` char(10) NOT NULL COMMENT '密码',
  `uid` int(11) NOT NULL COMMENT '充值用户',
  `aid` int(11) NOT NULL COMMENT '管理员',
  `pledge` decimal(10,0) NOT NULL COMMENT '保证金值',
  `limsum` decimal(10,0) NOT NULL COMMENT '信誉额度值',
  `time` int(10) NOT NULL COMMENT '生成时间',
  `pasttime` int(10) NOT NULL COMMENT '过期时间',
  `status` tinyint(1) NOT NULL COMMENT '是否已使用或过期，0：未使用；1：已使用；2：已过期',
  UNIQUE KEY `cardno` (`cardno`),
  KEY `cardno_2` (`cardno`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='充值卡表' ;

# 数据库表：on_role 结构信息
DROP TABLE IF EXISTS `on_role`;
CREATE TABLE `on_role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `pid` smallint(6) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='权限角色表' ;

# 数据库表：on_role_user 结构信息
DROP TABLE IF EXISTS `on_role_user`;
CREATE TABLE `on_role_user` (
  `role_id` mediumint(9) unsigned DEFAULT NULL,
  `user_id` char(32) DEFAULT NULL,
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户角色表' ;

# 数据库表：on_scheduled 结构信息
DROP TABLE IF EXISTS `on_scheduled`;
CREATE TABLE `on_scheduled` (
  `pid` int(11) NOT NULL COMMENT '拍品pid',
  `uid` int(11) NOT NULL COMMENT '用户uid',
  `stype` varchar(5) NOT NULL COMMENT '提醒类型（fut:开拍提醒，ing：结束提醒）',
  `time` int(10) NOT NULL COMMENT '提醒时间',
  KEY `pid` (`pid`),
  KEY `uid` (`uid`),
  KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='拍品开拍结束提醒表' ;

# 数据库表：on_seller_pledge 结构信息
DROP TABLE IF EXISTS `on_seller_pledge`;
CREATE TABLE `on_seller_pledge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sellerid` int(11) NOT NULL COMMENT '商户UID',
  `pid` int(11) NOT NULL COMMENT '拍品id',
  `type` varchar(15) NOT NULL COMMENT '[seller_pledge_disposable]一次性缴纳；[seller_pledge_every]每件缴纳；[seller_pledge_proportion]按照起拍比例缴纳',
  `pledge` decimal(10,2) NOT NULL COMMENT '保证金',
  `limsum` decimal(10,2) NOT NULL COMMENT '信誉额度',
  `time` int(10) NOT NULL COMMENT '缴纳时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '记录是否有效，1有效；0无效',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `sellerid` (`sellerid`),
  KEY `type` (`type`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='商家保证金表' ;

# 数据库表：on_share 结构信息
DROP TABLE IF EXISTS `on_share`;
CREATE TABLE `on_share` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '记录id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `terrace` varchar(20) NOT NULL COMMENT '分享平台',
  `title` varchar(255) NOT NULL COMMENT '分享链接名称',
  `link` varchar(255) NOT NULL COMMENT '分享链接',
  `limsum` decimal(10,2) NOT NULL COMMENT '奖励信誉额度',
  `time` int(10) NOT NULL COMMENT '分享时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分享记录表' ;

# 数据库表：on_special_auction 结构信息
DROP TABLE IF EXISTS `on_special_auction`;
CREATE TABLE `on_special_auction` (
  `sid` int(11) NOT NULL AUTO_INCREMENT COMMENT '专场id',
  `sname` varchar(200) NOT NULL COMMENT '专场名',
  `spicture` text NOT NULL COMMENT '专场图',
  `sbanner` text NOT NULL COMMENT '专场banner',
  `description` varchar(255) NOT NULL COMMENT '描述',
  `starttime` int(10) NOT NULL COMMENT '开始时间',
  `endtime` int(10) NOT NULL COMMENT '结束时间',
  `special_pledge_type` tinyint(1) NOT NULL COMMENT '保证金扣除模式',
  `spledge` decimal(10,0) NOT NULL DEFAULT '0' COMMENT '保证金',
  `bcount` int(11) NOT NULL COMMENT '专场拍品数量',
  `scount` int(11) NOT NULL COMMENT '专场出价次数',
  `sellerid` int(10) NOT NULL COMMENT '卖家uid',
  `hide` tinyint(1) NOT NULL COMMENT '是否隐藏',
  `aid` int(11) NOT NULL COMMENT '发布者',
  PRIMARY KEY (`sid`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='专场表' ;

# 数据库表：on_weiurl 结构信息
DROP TABLE IF EXISTS `on_weiurl`;
CREATE TABLE `on_weiurl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keywords` varchar(100) NOT NULL COMMENT '关键词',
  `msgtype` varchar(20) NOT NULL COMMENT '回复消息类型',
  `type` varchar(50) NOT NULL COMMENT '类型：auction 拍卖；article文章；',
  `rid` int(11) NOT NULL COMMENT '对应的类型的id，如对应拍卖的id',
  `sellerid` int(11) NOT NULL COMMENT '卖家id',
  `url` text NOT NULL COMMENT '图文url地址或音乐url地址',
  `urlh` text NOT NULL COMMENT '高质量音乐链接',
  `name` varchar(20) NOT NULL COMMENT '消息名称或标题',
  `comment` varchar(255) NOT NULL COMMENT '消息描述或文本回复内容',
  `toppic` varchar(255) NOT NULL COMMENT '头条图片',
  `picture` varchar(255) NOT NULL COMMENT '图文图片',
  `enclosure` varchar(255) NOT NULL COMMENT '附件地址：包括音频、视频等',
  `media_id` varchar(100) NOT NULL COMMENT '上传至微信的资源id',
  `succount` int(11) NOT NULL COMMENT '成功推送统计',
  `errcount` int(11) NOT NULL COMMENT '推送失败统计',
  `status` tinyint(1) NOT NULL COMMENT '链接状态',
  PRIMARY KEY (`id`),
  KEY `sellerid` (`sellerid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微信图文' ;



# 数据库表：on_access 数据信息
INSERT INTO `on_access` VALUES ('2','1','1','0','');
INSERT INTO `on_access` VALUES ('2','2','2','1','');
INSERT INTO `on_access` VALUES ('2','5','3','2','');
INSERT INTO `on_access` VALUES ('2','6','3','2','');
INSERT INTO `on_access` VALUES ('2','112','3','2','');
INSERT INTO `on_access` VALUES ('2','113','3','2','');
INSERT INTO `on_access` VALUES ('2','128','3','2','');
INSERT INTO `on_access` VALUES ('2','136','3','2','');
INSERT INTO `on_access` VALUES ('2','137','3','2','');
INSERT INTO `on_access` VALUES ('2','138','3','2','');
INSERT INTO `on_access` VALUES ('2','139','3','2','');
INSERT INTO `on_access` VALUES ('2','3','2','1','');
INSERT INTO `on_access` VALUES ('2','7','3','3','');
INSERT INTO `on_access` VALUES ('2','45','3','3','');
INSERT INTO `on_access` VALUES ('2','46','3','3','');
INSERT INTO `on_access` VALUES ('2','47','3','3','');
INSERT INTO `on_access` VALUES ('2','48','3','3','');
INSERT INTO `on_access` VALUES ('2','49','3','3','');
INSERT INTO `on_access` VALUES ('2','101','3','3','');
INSERT INTO `on_access` VALUES ('2','122','3','3','');
INSERT INTO `on_access` VALUES ('2','123','3','3','');
INSERT INTO `on_access` VALUES ('2','124','3','3','');
INSERT INTO `on_access` VALUES ('2','125','3','3','');
INSERT INTO `on_access` VALUES ('2','140','3','3','');
INSERT INTO `on_access` VALUES ('2','141','3','3','');
INSERT INTO `on_access` VALUES ('2','142','3','3','');
INSERT INTO `on_access` VALUES ('2','143','3','3','');
INSERT INTO `on_access` VALUES ('2','160','3','3','');
INSERT INTO `on_access` VALUES ('2','161','3','3','');
INSERT INTO `on_access` VALUES ('2','4','2','1','');
INSERT INTO `on_access` VALUES ('2','10','3','4','');
INSERT INTO `on_access` VALUES ('2','11','3','4','');
INSERT INTO `on_access` VALUES ('2','12','3','4','');
INSERT INTO `on_access` VALUES ('2','13','3','4','');
INSERT INTO `on_access` VALUES ('2','95','3','4','');
INSERT INTO `on_access` VALUES ('2','96','3','4','');
INSERT INTO `on_access` VALUES ('2','97','3','4','');
INSERT INTO `on_access` VALUES ('2','98','3','4','');
INSERT INTO `on_access` VALUES ('2','99','3','4','');
INSERT INTO `on_access` VALUES ('2','100','3','4','');
INSERT INTO `on_access` VALUES ('2','14','2','1','');
INSERT INTO `on_access` VALUES ('2','8','3','14','');
INSERT INTO `on_access` VALUES ('2','9','3','14','');
INSERT INTO `on_access` VALUES ('2','15','3','14','');
INSERT INTO `on_access` VALUES ('2','16','3','14','');
INSERT INTO `on_access` VALUES ('2','17','3','14','');
INSERT INTO `on_access` VALUES ('2','18','3','14','');
INSERT INTO `on_access` VALUES ('2','19','3','14','');
INSERT INTO `on_access` VALUES ('2','20','3','14','');
INSERT INTO `on_access` VALUES ('2','21','3','14','');
INSERT INTO `on_access` VALUES ('2','22','3','14','');
INSERT INTO `on_access` VALUES ('2','23','3','14','');
INSERT INTO `on_access` VALUES ('2','24','3','14','');
INSERT INTO `on_access` VALUES ('2','25','3','14','');
INSERT INTO `on_access` VALUES ('2','26','2','1','');
INSERT INTO `on_access` VALUES ('2','27','3','26','');
INSERT INTO `on_access` VALUES ('2','28','3','26','');
INSERT INTO `on_access` VALUES ('2','29','3','26','');
INSERT INTO `on_access` VALUES ('2','30','3','26','');
INSERT INTO `on_access` VALUES ('2','31','3','26','');
INSERT INTO `on_access` VALUES ('2','32','2','1','');
INSERT INTO `on_access` VALUES ('2','33','3','32','');
INSERT INTO `on_access` VALUES ('2','34','3','32','');
INSERT INTO `on_access` VALUES ('2','35','3','32','');
INSERT INTO `on_access` VALUES ('2','36','3','32','');
INSERT INTO `on_access` VALUES ('2','37','3','32','');
INSERT INTO `on_access` VALUES ('2','38','3','32','');
INSERT INTO `on_access` VALUES ('2','39','3','32','');
INSERT INTO `on_access` VALUES ('2','40','3','32','');
INSERT INTO `on_access` VALUES ('2','41','3','32','');
INSERT INTO `on_access` VALUES ('2','42','3','32','');
INSERT INTO `on_access` VALUES ('2','43','3','32','');
INSERT INTO `on_access` VALUES ('2','44','3','32','');
INSERT INTO `on_access` VALUES ('2','50','2','1','');
INSERT INTO `on_access` VALUES ('2','51','3','50','');
INSERT INTO `on_access` VALUES ('2','52','3','50','');
INSERT INTO `on_access` VALUES ('2','53','3','50','');
INSERT INTO `on_access` VALUES ('2','54','3','50','');
INSERT INTO `on_access` VALUES ('2','55','3','50','');
INSERT INTO `on_access` VALUES ('2','56','3','50','');
INSERT INTO `on_access` VALUES ('2','57','3','50','');
INSERT INTO `on_access` VALUES ('2','58','3','50','');
INSERT INTO `on_access` VALUES ('2','59','3','50','');
INSERT INTO `on_access` VALUES ('2','60','3','50','');
INSERT INTO `on_access` VALUES ('2','61','3','50','');
INSERT INTO `on_access` VALUES ('2','62','3','50','');
INSERT INTO `on_access` VALUES ('2','111','3','50','');
INSERT INTO `on_access` VALUES ('2','144','3','50','');
INSERT INTO `on_access` VALUES ('2','145','3','50','');
INSERT INTO `on_access` VALUES ('2','146','3','50','');
INSERT INTO `on_access` VALUES ('2','147','3','50','');
INSERT INTO `on_access` VALUES ('2','150','3','50','');
INSERT INTO `on_access` VALUES ('2','148','3','50','');
INSERT INTO `on_access` VALUES ('2','149','3','50','');
INSERT INTO `on_access` VALUES ('2','151','3','50','');
INSERT INTO `on_access` VALUES ('2','152','3','50','');
INSERT INTO `on_access` VALUES ('2','63','2','1','');
INSERT INTO `on_access` VALUES ('2','64','3','63','');
INSERT INTO `on_access` VALUES ('2','65','3','63','');
INSERT INTO `on_access` VALUES ('2','66','3','63','');
INSERT INTO `on_access` VALUES ('2','67','3','63','');
INSERT INTO `on_access` VALUES ('2','68','3','63','');
INSERT INTO `on_access` VALUES ('2','102','3','63','');
INSERT INTO `on_access` VALUES ('2','103','3','63','');
INSERT INTO `on_access` VALUES ('2','104','3','63','');
INSERT INTO `on_access` VALUES ('2','105','3','63','');
INSERT INTO `on_access` VALUES ('2','106','3','63','');
INSERT INTO `on_access` VALUES ('2','107','3','63','');
INSERT INTO `on_access` VALUES ('2','108','3','63','');
INSERT INTO `on_access` VALUES ('2','109','3','63','');
INSERT INTO `on_access` VALUES ('2','110','3','63','');
INSERT INTO `on_access` VALUES ('2','127','3','63','');
INSERT INTO `on_access` VALUES ('2','134','3','63','');
INSERT INTO `on_access` VALUES ('2','135','3','63','');
INSERT INTO `on_access` VALUES ('2','153','3','63','');
INSERT INTO `on_access` VALUES ('2','154','3','63','');
INSERT INTO `on_access` VALUES ('2','155','3','63','');
INSERT INTO `on_access` VALUES ('2','156','3','63','');
INSERT INTO `on_access` VALUES ('2','157','3','63','');
INSERT INTO `on_access` VALUES ('2','158','3','63','');
INSERT INTO `on_access` VALUES ('2','159','3','63','');
INSERT INTO `on_access` VALUES ('2','162','3','63','');
INSERT INTO `on_access` VALUES ('2','163','3','63','');
INSERT INTO `on_access` VALUES ('2','164','3','63','');
INSERT INTO `on_access` VALUES ('2','165','3','63','');
INSERT INTO `on_access` VALUES ('2','69','2','1','');
INSERT INTO `on_access` VALUES ('2','70','3','69','');
INSERT INTO `on_access` VALUES ('2','71','3','69','');
INSERT INTO `on_access` VALUES ('2','72','3','69','');
INSERT INTO `on_access` VALUES ('2','73','3','69','');
INSERT INTO `on_access` VALUES ('2','74','3','69','');
INSERT INTO `on_access` VALUES ('2','75','3','69','');
INSERT INTO `on_access` VALUES ('2','76','2','1','');
INSERT INTO `on_access` VALUES ('2','77','3','76','');
INSERT INTO `on_access` VALUES ('2','78','3','76','');
INSERT INTO `on_access` VALUES ('2','79','3','76','');
INSERT INTO `on_access` VALUES ('2','80','3','76','');
INSERT INTO `on_access` VALUES ('2','81','2','1','');
INSERT INTO `on_access` VALUES ('2','82','3','81','');
INSERT INTO `on_access` VALUES ('2','83','3','81','');
INSERT INTO `on_access` VALUES ('2','84','3','81','');
INSERT INTO `on_access` VALUES ('2','85','3','81','');
INSERT INTO `on_access` VALUES ('2','86','3','81','');
INSERT INTO `on_access` VALUES ('2','87','3','81','');
INSERT INTO `on_access` VALUES ('2','88','3','81','');
INSERT INTO `on_access` VALUES ('2','89','3','81','');
INSERT INTO `on_access` VALUES ('2','90','2','1','');
INSERT INTO `on_access` VALUES ('2','91','3','90','');
INSERT INTO `on_access` VALUES ('2','92','3','90','');
INSERT INTO `on_access` VALUES ('2','93','3','90','');
INSERT INTO `on_access` VALUES ('2','94','3','90','');
INSERT INTO `on_access` VALUES ('2','129','3','90','');
INSERT INTO `on_access` VALUES ('2','130','3','90','');
INSERT INTO `on_access` VALUES ('2','131','3','90','');
INSERT INTO `on_access` VALUES ('2','132','3','90','');
INSERT INTO `on_access` VALUES ('2','133','3','90','');
INSERT INTO `on_access` VALUES ('2','114','2','1','');
INSERT INTO `on_access` VALUES ('2','115','3','114','');
INSERT INTO `on_access` VALUES ('2','116','3','114','');
INSERT INTO `on_access` VALUES ('2','117','3','114','');
INSERT INTO `on_access` VALUES ('2','118','3','114','');
INSERT INTO `on_access` VALUES ('2','119','3','114','');
INSERT INTO `on_access` VALUES ('2','120','3','114','');
INSERT INTO `on_access` VALUES ('2','121','3','114','');
INSERT INTO `on_access` VALUES ('2','126','3','114','');
INSERT INTO `on_access` VALUES ('7','1','1','0','');
INSERT INTO `on_access` VALUES ('7','2','2','1','');
INSERT INTO `on_access` VALUES ('7','5','3','2','');
INSERT INTO `on_access` VALUES ('7','6','3','2','');
INSERT INTO `on_access` VALUES ('7','112','3','2','');
INSERT INTO `on_access` VALUES ('7','113','3','2','');
INSERT INTO `on_access` VALUES ('7','128','3','2','');
INSERT INTO `on_access` VALUES ('7','136','3','2','');
INSERT INTO `on_access` VALUES ('7','137','3','2','');
INSERT INTO `on_access` VALUES ('7','138','3','2','');
INSERT INTO `on_access` VALUES ('7','139','3','2','');
INSERT INTO `on_access` VALUES ('7','3','2','1','');
INSERT INTO `on_access` VALUES ('7','7','3','3','');
INSERT INTO `on_access` VALUES ('7','45','3','3','');
INSERT INTO `on_access` VALUES ('7','46','3','3','');
INSERT INTO `on_access` VALUES ('7','47','3','3','');
INSERT INTO `on_access` VALUES ('7','48','3','3','');
INSERT INTO `on_access` VALUES ('7','49','3','3','');
INSERT INTO `on_access` VALUES ('7','101','3','3','');
INSERT INTO `on_access` VALUES ('7','122','3','3','');
INSERT INTO `on_access` VALUES ('7','123','3','3','');
INSERT INTO `on_access` VALUES ('7','124','3','3','');
INSERT INTO `on_access` VALUES ('7','125','3','3','');
INSERT INTO `on_access` VALUES ('7','140','3','3','');
INSERT INTO `on_access` VALUES ('7','141','3','3','');
INSERT INTO `on_access` VALUES ('7','142','3','3','');
INSERT INTO `on_access` VALUES ('7','143','3','3','');
INSERT INTO `on_access` VALUES ('7','160','3','3','');
INSERT INTO `on_access` VALUES ('7','161','3','3','');
INSERT INTO `on_access` VALUES ('7','4','2','1','');
INSERT INTO `on_access` VALUES ('7','10','3','4','');
INSERT INTO `on_access` VALUES ('7','11','3','4','');
INSERT INTO `on_access` VALUES ('7','12','3','4','');
INSERT INTO `on_access` VALUES ('7','13','3','4','');
INSERT INTO `on_access` VALUES ('7','95','3','4','');
INSERT INTO `on_access` VALUES ('7','96','3','4','');
INSERT INTO `on_access` VALUES ('7','97','3','4','');
INSERT INTO `on_access` VALUES ('7','98','3','4','');
INSERT INTO `on_access` VALUES ('7','99','3','4','');
INSERT INTO `on_access` VALUES ('7','100','3','4','');
INSERT INTO `on_access` VALUES ('7','14','2','1','');
INSERT INTO `on_access` VALUES ('7','8','3','14','');
INSERT INTO `on_access` VALUES ('7','9','3','14','');
INSERT INTO `on_access` VALUES ('7','15','3','14','');
INSERT INTO `on_access` VALUES ('7','16','3','14','');
INSERT INTO `on_access` VALUES ('7','17','3','14','');
INSERT INTO `on_access` VALUES ('7','18','3','14','');
INSERT INTO `on_access` VALUES ('7','19','3','14','');
INSERT INTO `on_access` VALUES ('7','20','3','14','');
INSERT INTO `on_access` VALUES ('7','21','3','14','');
INSERT INTO `on_access` VALUES ('7','22','3','14','');
INSERT INTO `on_access` VALUES ('7','23','3','14','');
INSERT INTO `on_access` VALUES ('7','24','3','14','');
INSERT INTO `on_access` VALUES ('7','25','3','14','');
INSERT INTO `on_access` VALUES ('7','26','2','1','');
INSERT INTO `on_access` VALUES ('7','27','3','26','');
INSERT INTO `on_access` VALUES ('7','28','3','26','');
INSERT INTO `on_access` VALUES ('7','29','3','26','');
INSERT INTO `on_access` VALUES ('7','30','3','26','');
INSERT INTO `on_access` VALUES ('7','31','3','26','');
INSERT INTO `on_access` VALUES ('7','32','2','1','');
INSERT INTO `on_access` VALUES ('7','33','3','32','');
INSERT INTO `on_access` VALUES ('7','34','3','32','');
INSERT INTO `on_access` VALUES ('7','35','3','32','');
INSERT INTO `on_access` VALUES ('7','36','3','32','');
INSERT INTO `on_access` VALUES ('7','37','3','32','');
INSERT INTO `on_access` VALUES ('7','38','3','32','');
INSERT INTO `on_access` VALUES ('7','39','3','32','');
INSERT INTO `on_access` VALUES ('7','40','3','32','');
INSERT INTO `on_access` VALUES ('7','41','3','32','');
INSERT INTO `on_access` VALUES ('7','42','3','32','');
INSERT INTO `on_access` VALUES ('7','43','3','32','');
INSERT INTO `on_access` VALUES ('7','44','3','32','');
INSERT INTO `on_access` VALUES ('7','50','2','1','');
INSERT INTO `on_access` VALUES ('7','51','3','50','');
INSERT INTO `on_access` VALUES ('7','52','3','50','');
INSERT INTO `on_access` VALUES ('7','53','3','50','');
INSERT INTO `on_access` VALUES ('7','54','3','50','');
INSERT INTO `on_access` VALUES ('7','55','3','50','');
INSERT INTO `on_access` VALUES ('7','56','3','50','');
INSERT INTO `on_access` VALUES ('7','57','3','50','');
INSERT INTO `on_access` VALUES ('7','58','3','50','');
INSERT INTO `on_access` VALUES ('7','59','3','50','');
INSERT INTO `on_access` VALUES ('7','60','3','50','');
INSERT INTO `on_access` VALUES ('7','61','3','50','');
INSERT INTO `on_access` VALUES ('7','62','3','50','');
INSERT INTO `on_access` VALUES ('7','111','3','50','');
INSERT INTO `on_access` VALUES ('7','144','3','50','');
INSERT INTO `on_access` VALUES ('7','145','3','50','');
INSERT INTO `on_access` VALUES ('7','146','3','50','');
INSERT INTO `on_access` VALUES ('7','147','3','50','');
INSERT INTO `on_access` VALUES ('7','150','3','50','');
INSERT INTO `on_access` VALUES ('7','148','3','50','');
INSERT INTO `on_access` VALUES ('7','149','3','50','');
INSERT INTO `on_access` VALUES ('7','151','3','50','');
INSERT INTO `on_access` VALUES ('7','152','3','50','');
INSERT INTO `on_access` VALUES ('7','63','2','1','');
INSERT INTO `on_access` VALUES ('7','64','3','63','');
INSERT INTO `on_access` VALUES ('7','65','3','63','');
INSERT INTO `on_access` VALUES ('7','66','3','63','');
INSERT INTO `on_access` VALUES ('7','67','3','63','');
INSERT INTO `on_access` VALUES ('7','68','3','63','');
INSERT INTO `on_access` VALUES ('7','102','3','63','');
INSERT INTO `on_access` VALUES ('7','103','3','63','');
INSERT INTO `on_access` VALUES ('7','104','3','63','');
INSERT INTO `on_access` VALUES ('7','105','3','63','');
INSERT INTO `on_access` VALUES ('7','106','3','63','');
INSERT INTO `on_access` VALUES ('7','107','3','63','');
INSERT INTO `on_access` VALUES ('7','108','3','63','');
INSERT INTO `on_access` VALUES ('7','109','3','63','');
INSERT INTO `on_access` VALUES ('7','110','3','63','');
INSERT INTO `on_access` VALUES ('7','127','3','63','');
INSERT INTO `on_access` VALUES ('7','134','3','63','');
INSERT INTO `on_access` VALUES ('7','135','3','63','');
INSERT INTO `on_access` VALUES ('7','153','3','63','');
INSERT INTO `on_access` VALUES ('7','154','3','63','');
INSERT INTO `on_access` VALUES ('7','155','3','63','');
INSERT INTO `on_access` VALUES ('7','156','3','63','');
INSERT INTO `on_access` VALUES ('7','157','3','63','');
INSERT INTO `on_access` VALUES ('7','158','3','63','');
INSERT INTO `on_access` VALUES ('7','159','3','63','');
INSERT INTO `on_access` VALUES ('7','162','3','63','');
INSERT INTO `on_access` VALUES ('7','163','3','63','');
INSERT INTO `on_access` VALUES ('7','164','3','63','');
INSERT INTO `on_access` VALUES ('7','165','3','63','');
INSERT INTO `on_access` VALUES ('7','69','2','1','');
INSERT INTO `on_access` VALUES ('7','70','3','69','');
INSERT INTO `on_access` VALUES ('7','71','3','69','');
INSERT INTO `on_access` VALUES ('7','72','3','69','');
INSERT INTO `on_access` VALUES ('7','73','3','69','');
INSERT INTO `on_access` VALUES ('7','74','3','69','');
INSERT INTO `on_access` VALUES ('7','75','3','69','');
INSERT INTO `on_access` VALUES ('7','76','2','1','');
INSERT INTO `on_access` VALUES ('7','77','3','76','');
INSERT INTO `on_access` VALUES ('7','78','3','76','');
INSERT INTO `on_access` VALUES ('7','79','3','76','');
INSERT INTO `on_access` VALUES ('7','80','3','76','');
INSERT INTO `on_access` VALUES ('7','81','2','1','');
INSERT INTO `on_access` VALUES ('7','82','3','81','');
INSERT INTO `on_access` VALUES ('7','83','3','81','');
INSERT INTO `on_access` VALUES ('7','84','3','81','');
INSERT INTO `on_access` VALUES ('7','85','3','81','');
INSERT INTO `on_access` VALUES ('7','86','3','81','');
INSERT INTO `on_access` VALUES ('7','87','3','81','');
INSERT INTO `on_access` VALUES ('7','88','3','81','');
INSERT INTO `on_access` VALUES ('7','89','3','81','');
INSERT INTO `on_access` VALUES ('7','90','2','1','');
INSERT INTO `on_access` VALUES ('7','91','3','90','');
INSERT INTO `on_access` VALUES ('7','92','3','90','');
INSERT INTO `on_access` VALUES ('7','93','3','90','');
INSERT INTO `on_access` VALUES ('7','94','3','90','');
INSERT INTO `on_access` VALUES ('7','129','3','90','');
INSERT INTO `on_access` VALUES ('7','130','3','90','');
INSERT INTO `on_access` VALUES ('7','131','3','90','');
INSERT INTO `on_access` VALUES ('7','132','3','90','');
INSERT INTO `on_access` VALUES ('7','133','3','90','');
INSERT INTO `on_access` VALUES ('7','114','2','1','');
INSERT INTO `on_access` VALUES ('7','115','3','114','');
INSERT INTO `on_access` VALUES ('7','116','3','114','');
INSERT INTO `on_access` VALUES ('7','117','3','114','');
INSERT INTO `on_access` VALUES ('7','118','3','114','');
INSERT INTO `on_access` VALUES ('7','119','3','114','');
INSERT INTO `on_access` VALUES ('7','120','3','114','');
INSERT INTO `on_access` VALUES ('7','121','3','114','');
INSERT INTO `on_access` VALUES ('7','126','3','114','');


# 数据库表：on_admin 数据信息
INSERT INTO `on_admin` VALUES ('1','超级管理员','super@ebnnw.cn','','c3284d0f94606de1fd2af172aba15bf3','1','我是超级管理员','','1733925334');


# 数据库表：on_advertising 数据信息
INSERT INTO `on_advertising` VALUES ('1','1','LOGO（大屏显示）','Advshow/20241213/675b455a918fe.jpg','','0','0','','0','本图片在电脑版屏幕上会显示。','0','0','0');
INSERT INTO `on_advertising` VALUES ('2','2','LOGO（小屏显示）','Advshow/20241211/6759b32523f03.jpg','','0','1','https://www.bonhams.com/auctions/upcoming/','0','小屏幕访问网站显示该图片','0','0','0');
INSERT INTO `on_advertising` VALUES ('3','4','','Advshow/20241213/675b44ab3a94c.jpg','#281736','0','0','','0','','0','0','0');
INSERT INTO `on_advertising` VALUES ('4','3','首页-BANNER列表图片（2）','Advshow/20241213/675b45410e1fc.jpg','#010101','0','0','','0','','0','0','0');
INSERT INTO `on_advertising` VALUES ('5','3','首页-BANNER列表图片（3）','Advshow/20241213/675b452d10433.jpg','#ffffff','0','0','','0','','0','0','0');
INSERT INTO `on_advertising` VALUES ('7','4','头条底部广告','Advshow/20241213/675b4510b4c03.jpg','','0','0','','0','','0','0','0');
INSERT INTO `on_advertising` VALUES ('8','5','首页-拍卖会标题处（PNG）','Advshow/20241213/675b459cb0607.jpg','','0','0','','0','','0','0','0');
INSERT INTO `on_advertising` VALUES ('9','6','导航-全部分类-为你推荐','Advshow/20241213/675b45ba82522.jpg','','0','1','','0','','0','0','0');
INSERT INTO `on_advertising` VALUES ('10','7','注册登陆-BANNER','Advshow/20241213/675b45ab84e28.jpg','#980000','0','0','','0','','0','0','0');


# 数据库表：on_advertising_position 数据信息
INSERT INTO `on_advertising_position` VALUES ('1','logoA','LOGO（大屏显示）','200','200');
INSERT INTO `on_advertising_position` VALUES ('2','logoB','LOGO（小屏显示）','159','78');
INSERT INTO `on_advertising_position` VALUES ('3','banner','首页-BANNER列表图片','1010','855');
INSERT INTO `on_advertising_position` VALUES ('4','headlines','首页-头条-底部','220','80');
INSERT INTO `on_advertising_position` VALUES ('5','mittingtitle','首页-拍卖会标题处','120','120');
INSERT INTO `on_advertising_position` VALUES ('6','recommend','导航-全部分类-为你推荐','750','270');
INSERT INTO `on_advertising_position` VALUES ('7','lrbanner','登陆注册-BANNER','1000','500');
INSERT INTO `on_advertising_position` VALUES ('9','html标签','正在拍賣aaaaabbbb'','1000','800');


# 数据库表：on_attention 数据信息


# 数据库表：on_attention_seller 数据信息
INSERT INTO `on_attention_seller` VALUES ('4','1','1734099464');


# 数据库表：on_auction 数据信息
INSERT INTO `on_auction` VALUES ('1','2','0','0','','0','0','0.00','0.00','0','0','清康熙·窑变釉鹿头尊','4800000.00','4800000.00','4800000.00','1733936100','1735576500','50000','1','fixation','fixation','20.00','ratio','10.00','20000.00','120','300','0','0','0.00','0','0','0','0','36','0','1733935104','0');
INSERT INTO `on_auction` VALUES ('2','3','0','0','','0','0','0.00','0.00','0','0',' 清雍正 青花纏枝花卉紋蒜頭大瓶','6300000.00','6300000.00','6300000.00','1734106020','1735574700','300000','1','fixation','fixation','20.00','ratio','10.00','20000.00','120','300','0','0','0.00','0','0','0','0','7','0','1734106105','0');


# 数据库表：on_auction_agency 数据信息


# 数据库表：on_auction_pledge 数据信息


# 数据库表：on_auction_record 数据信息


# 数据库表：on_auction_repeat 数据信息


# 数据库表：on_blacklist 数据信息


# 数据库表：on_category 数据信息
INSERT INTO `on_category` VALUES ('1','0','帮助中心','4');
INSERT INTO `on_category` VALUES ('2','0','本站头条','3');
INSERT INTO `on_category` VALUES ('9','1','配送方式','0');
INSERT INTO `on_category` VALUES ('4','2','公告','5');
INSERT INTO `on_category` VALUES ('5','1','关于我们','4');
INSERT INTO `on_category` VALUES ('3','0','每日新鲜事','2');
INSERT INTO `on_category` VALUES ('10','2','专场','1');
INSERT INTO `on_category` VALUES ('11','2','拍卖会','1');
INSERT INTO `on_category` VALUES ('13','2','拍卖物品','4');
INSERT INTO `on_category` VALUES ('14','2','拍卖车','1');
INSERT INTO `on_category` VALUES ('16','9','测','2');


# 数据库表：on_consultation 数据信息


# 数据库表：on_deliver_address 数据信息


# 数据库表：on_feedback 数据信息


# 数据库表：on_goods 数据信息
INSERT INTO `on_goods` VALUES ('1','1','清康熙·窑变釉鹿头尊','','','此尊直口、溜肩、两侧置鹿头、球形腹、圈足，胎坚质密，通
体施窑变釉，外形俊秀挺拔，线条优美流畅，予人高贵、端庄、静穆之美。釉
色艳丽，垂流似霞若焰，红、紫、蓝、月白交织富于动态，红紫交融，蓝白相
掩，纵横变化，釉表有细密自然的冰裂现象，足部釉有垂流，修足规整。此尊
器形气势恢宏，包浆莹润亮丽，端庄的器形与瑰丽明艳的窑变釉完美地结合，
达到了“合于天造，厌于人意”的艺术境界，久观则更觉回味无穷，实为一件
不可多得的赏玩陈列之佳器。','4800000.00','<p>我是商品详情的默认值，每当发布商品时候我都会出现。你可以把我当成商品详情的模板，可以修改我达到快速发布的效果。</p><p>通常对于有网站商品有共同的商品参数或者内容的时候设置我可以快速发布商品！</p><p>如果不需要我，可以在登陆后台【商品管理】-》【扩展字段管理】中选择商品详情进行编辑！<br/></p>','','','','Goods/20241212/6759bda6378a3.jpg|Goods/20241212/6759bdad75c75.jpg|Goods/20241212/6759bdb50952e.jpg','','1733934520','','1','');
INSERT INTO `on_goods` VALUES ('2','1','清康熙·窑变釉鹿头尊','','','特点：

器型：尊的形状端庄稳重，颈部两侧饰有对称的鹿头造型，细节生动精致，展示了高超的雕刻工艺。
釉色：釉面呈现深红色和细腻的窑变纹理，散发着温润光泽，釉色过渡自然，展现了传统工艺的独特美感。
文化内涵：鹿在中国文化中象征长寿与吉祥，鹿头尊寓意美好，具有重要的文化价值。','4800000.00','<h3><strong>商品名称：清康熙·窑变釉鹿头尊</strong></h3><p><strong>特点：</strong></p><ol class=" list-paddingleft-2"><li><p><strong>器型：</strong><br/>造型规整，颈部两侧饰有对称的鹿头造型，线条流畅，鹿头雕刻生动逼真，体现了康熙时期精湛的工艺技术和独特设计风格。</p></li><li><p><strong>釉色：</strong><br/>窑变釉色泽温润，主体呈现深红与细腻纹理，釉面色彩自然流动，过渡柔和，呈现出极高的视觉美感。</p></li><li><p><strong>底款：</strong><br/>底部书写“<strong>大清康熙年制</strong>”六字楷书款，字体工整规矩，蓝色款识与器物整体协调，符合康熙官窑风格。</p></li><li><p><strong>文化寓意：</strong><br/>鹿在中国传统文化中象征长寿和吉祥，鹿头尊不仅是艺术珍品，也寓意美好祝愿，常见于宫廷陈设或重要仪式场合。</p></li></ol><hr/><h3><strong>尺寸与规格</strong></h3><ul class=" list-paddingleft-2"><li><p><strong>高度：</strong>（请测量具体高度）</p></li><li><p><strong>宽度：</strong>（请测量具体宽度）</p></li><li><p><strong>重量：</strong>（可补充重量信息）</p></li></ul><hr/><h3><strong>适用场景：</strong></h3><p>适合博物馆陈列、高端藏品展示或作为私人收藏，彰显品味和文化价值。</p><hr/><h3><strong>市场价值与评估：</strong></h3><p>康熙时期的窑变釉器物因工艺独特且烧制难度高，在艺术品市场中极为稀有，具有较高的历史价值和收藏潜力。建议结合拍卖记录进行进一步鉴定与估值。</p><p><br/></p>','','北京市','','Goods/20241212/6759bfab78a14.jpg|Goods/20241212/6759bfb275ff3.jpg|Goods/20241212/6759bfb98e34e.jpg','','1733935038','1734056257','1','');
INSERT INTO `on_goods` VALUES ('3','6',' 清雍正 青花纏枝花卉紋蒜頭大瓶','','','瓶蒜头，束颈，圆折肩，弧腹渐收，圈足。通身以青花装饰九层纹饰，口沿饰缠枝菊花纹，颈部饰蕉叶纹上下相对，每组蕉叶纹由长短蕉叶相互重迭，极具层次感，颈部下部绘折枝莲及回纹。瓶肩部则饰如意花卉纹、缠枝莲纹及折枝花，肩部下方饰方形蕉叶纹一周。瓶腹部满饰缠枝花，近足处饰海水纹。底青花书「大清雍正年制」篆书款。','6300000.00','<p><span style="font-family: &quot;Microsoft Yahei&quot;; font-size: 14px; text-wrap-mode: wrap; background-color: rgb(255, 255, 255);">坂井米夫（1900－1978）先生为早年日本倍受尊重的驻外记者之一。出身于日本佐贺县，坂井米夫先后在关西学院大学及明治学院大学进修文学专业，之后于日本国际情报社担任映画及演技编辑。1926年搬至美国旧金山后他开始为旧金山、洛杉矶及纽约的日文报纸撰写文章。1931年他成为朝日新闻社的驻外记者，并负责对1937年的西班牙内战做专题报导，之后两年又跋涉土耳其、伊朗、伊拉克、埃及、德国、巴勒斯坦及中国负责报导。第二次世界大战期间，美国政府对在美日裔美国人进行扣留和转移，坂井米夫因此被送入位于格兰纳达的日裔美国人集中营，并在避难期间在科罗拉多州立大学教授日文并持续至1948年。1949年后坂井米夫及其家人迁至华盛顿，并重拾其记者生涯，在日本各大报社在杜鲁门、艾森豪威尔威尔、肯尼迪及约翰逊四任总统执政下的驻外记者，直至他1978年逝世。坂井米夫的职业生涯充满传奇色彩，他曾在自己主办的「美国新闻」广播节目中采访肯尼迪、约翰逊总统及著名作家海明威，亦对约翰逊及尼克松早期执政下的越南战争进行过专题报导。坂井米夫在1956年正式成为美国公民。</span><br style="padding: 0px; margin: 0px; box-sizing: border-box; font-family: &quot;Microsoft Yahei&quot;; font-size: 14px; text-wrap-mode: wrap; background-color: rgb(255, 255, 255);"/><span style="font-family: &quot;Microsoft Yahei&quot;; font-size: 14px; text-wrap-mode: wrap; background-color: rgb(255, 255, 255);">以上部分研究资料由加州大学洛杉矶分校Charles E. Young研究图书馆特殊文献部提供</span><br style="padding: 0px; margin: 0px; box-sizing: border-box; font-family: &quot;Microsoft Yahei&quot;; font-size: 14px; text-wrap-mode: wrap; background-color: rgb(255, 255, 255);"/><span style="font-family: &quot;Microsoft Yahei&quot;; font-size: 14px; text-wrap-mode: wrap; background-color: rgb(255, 255, 255);">此种蒜头瓶为雍正官窑新创器形，传世稀少，弥足珍贵。迄今为止相似之例仅见于清宫旧藏一件雍正蒜头瓶，现藏于北京故宫博物院，见《故宫博物院藏文物珍品全集：青花釉里红（下）》，香港，1993年，页107，图93。耿宝昌先生亦在其著作中提及雍正官窑所创烧的这种特殊器形，见耿宝昌，《明清瓷器鉴定》，香港，1993年，页236，图12。乾隆一朝亦对此器形有所仿制，可见收藏于北京故宫博物院一例，见同著录，页135，图121。</span><br style="padding: 0px; margin: 0px; box-sizing: border-box; font-family: &quot;Microsoft Yahei&quot;; font-size: 14px; text-wrap-mode: wrap; background-color: rgb(255, 255, 255);"/><span style="font-family: &quot;Microsoft Yahei&quot;; font-size: 14px; text-wrap-mode: wrap; background-color: rgb(255, 255, 255);">雍正一朝虽仅十三年，但制瓷工艺却不断推陈出新，使其得到空前发展。雍正朝青花瓷器又以仿制明代永乐、宣德最为精美。此蒜头瓶形制亦可能是受到明代永宣时期官窑所烧造的不同瓷器而启发，再加之用青料钟笔点染以刻意仿制出永宣青花瓷中的铁锈斑点，从而创烧出如此气势恢宏却又清新淡雅的仿古佳器。</span></p>','','北京市','','Goods/20241214/675c5b8e68c66.jpg','','1734106022','1734107701','4','');


# 数据库表：on_goods_category 数据信息
INSERT INTO `on_goods_category` VALUES ('1','0','瓷器','','Category/20241212/6759bd43f062d.jpg','','0','0','1');
INSERT INTO `on_goods_category` VALUES ('2','0','玉器','','','','0','0','0');
INSERT INTO `on_goods_category` VALUES ('3','0','雜項','','','','0','0','0');
INSERT INTO `on_goods_category` VALUES ('4','0','字畫','','','','0','0','0');
INSERT INTO `on_goods_category` VALUES ('5','0','錢幣','','','','0','0','0');
INSERT INTO `on_goods_category` VALUES ('6','0','正在拍賣','','','','1','0','2');


# 数据库表：on_goods_category_extend 数据信息


# 数据库表：on_goods_category_filtrate 数据信息


# 数据库表：on_goods_evaluate 数据信息


# 数据库表：on_goods_extend 数据信息


# 数据库表：on_goods_fields 数据信息


# 数据库表：on_goods_filtrate 数据信息


# 数据库表：on_goods_order 数据信息


# 数据库表：on_goods_order_return 数据信息


# 数据库表：on_goods_user 数据信息


# 数据库表：on_link 数据信息


# 数据库表：on_meeting_auction 数据信息


# 数据库表：on_member 数据信息
INSERT INTO `on_member` VALUES ('1','0','','','','admin1','热爱珍藏','新鸿古玩店','只卖真货！','dc4423418ddb4d0196ae8d5fee0db185','','毛群英','522230197102281080','Idcard/20241212/6759bddb29e99.jpg','Idcard/20241212/6759bde3373e0.jpg','2','1733934959','','15845645564','1733947774','218.78.135.44','1','1','','','','','','0','0','','0','北京','北京市','东城区','','','','','','100000.00','200.00','0.00','0.00','76','76','218.78.135.44','1733947808','1','0','');
INSERT INTO `on_member` VALUES ('2','0','','','','Hk112233','','','','8b16088ce658aeef2251f6f263f77351','','','','','','','0','','','1733927902','101.44.81.136','0','0','','','','','','','','','','','','','','','','','','0.00','0.00','0.00','0.00','0','0','101.44.81.136','1733927902','1','0','');
INSERT INTO `on_member` VALUES ('3','0','','','','gymmmmm913','','','','19d52d1d771a556a7338319184c4a0b3','','','','','','','0','','','1733937567','150.129.23.9','0','0','','','','','','','','','','','','','','','','','','0.00','0.00','0.00','0.00','0','0','150.129.23.9','1734040816','1','0','');
INSERT INTO `on_member` VALUES ('4','0','','','','adminxl','藏宝阁主','藏宝阁','出手、回收、各类瓷器钱币..','2610d39bb46535427e37b29cb4c4d6ac','','','','','','2','1734099374','','18525414152','1734105296','218.78.135.44','1','1','','','','','User/675c41764b7dd.jpg','0','1','','0','北京','北京市','大兴区','交天下藏友','','','','','100000.00','200.00','0.00','0.00','80','80','150.129.23.9','1734105335','1','0','');


# 数据库表：on_member_evaluate 数据信息


# 数据库表：on_member_footprint 数据信息
INSERT INTO `on_member_footprint` VALUES ('1','a:1:{i:0;s:1:"1";}');
INSERT INTO `on_member_footprint` VALUES ('3','a:1:{i:0;s:1:"1";}');
INSERT INTO `on_member_footprint` VALUES ('4','a:2:{i:0;s:1:"2";i:1;s:1:"1";}');


# 数据库表：on_member_limsum_bill 数据信息


# 数据库表：on_member_pledge_bill 数据信息
INSERT INTO `on_member_pledge_bill` VALUES ('1','aad173392708791721','1','admin_deposit','1733927087','','100000.00','0.00','100000.00','100000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('2','add173393510447294','1','add_freeze','1733935104','发布拍卖：“<a href="/Auction/details/pid/1/aptitude/1.html">清康熙·窑变釉鹿头尊</a>”冻结','0.00','200.00','99800.00','100000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('3','aad173409862335995','4','admin_deposit','1734098623','','100000.00','0.00','100000.00','100000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('4','add17341061059649','4','add_freeze','1734106105','发布拍卖：“<a href="/Auction/details/pid/2/aptitude/1.html"> 清雍正 青花纏枝花卉紋蒜頭大瓶</a>”冻结','0.00','200.00','99800.00','100000.00');


# 数据库表：on_member_pledge_take 数据信息


# 数据库表：on_member_weixin 数据信息


# 数据库表：on_mysms 数据信息
INSERT INTO `on_mysms` VALUES ('1','1','0','0','0','0','管理员充值','1','0','管理员充值余额【100000元】，单号aad173392708791721','1733927087');
INSERT INTO `on_mysms` VALUES ('2','1','0','0','0','0','发布拍卖冻结保证金','1','0','发布拍卖：“<a href="/Auction/details/pid/1/aptitude/1.html">清康熙·窑变釉鹿头尊</a>”冻结保证金【200.00元】','1733935104');
INSERT INTO `on_mysms` VALUES ('3','4','0','0','0','0','管理员充值','0','0','管理员充值余额【100000元】，单号aad173409862335995','1734098623');
INSERT INTO `on_mysms` VALUES ('4','4','0','0','0','0','发布拍卖冻结保证金','0','0','发布拍卖：“<a href="/Auction/details/pid/2/aptitude/1.html"> 清雍正 青花纏枝花卉紋蒜頭大瓶</a>”冻结保证金【200.00元】','1734106105');


# 数据库表：on_navigation 数据信息


# 数据库表：on_news 数据信息
INSERT INTO `on_news` VALUES ('1','2','邦瀚斯（Bonhams）','undefinedundefined','关于邦瀚斯','一个乾隆九年开始的故事。','1','邦瀚斯（Bonhams）是一所私人全资拥有的英国拍卖行，是全球最历史悠久和最规模的古董及艺术品拍卖行之一。成立于1793年，总部位于英国伦敦。作为全球规模最大的拍卖行之一，邦瀚斯在艺术品拍卖领域具有深厚实力和卓越影响力。2023年度，邦瀚斯全球总销售额超越了11.4亿美元，创下公司开业230年以来的最佳业绩。邦瀚斯的名字在环球的艺术精品及古董巿场上，以至收藏界，都是声名显赫，在某些艺术界别更是世界翘楚。 邦瀚斯由托马士·多德（Thomas Dodd）於1793年创立，是现时伦敦少数仅存的源自乔治时代的拍卖','1733930313','1734020784','<p><span class="text_BvQ2v" data-text="true" style="box-sizing: content-box; margin: 0px; padding: 0px; position: relative;">950年代初，邦瀚斯家族在伦敦武士桥区（Knightsbridge）区买了一些土地，并於蒙彼利埃街(Montpelier Street)开设销售办事处。　2000年邦瀚斯被布鲁士拍卖行收购，改名邦瀚斯·布鲁士拍卖行。布鲁士的创办人是罗拔·布鲁士(Robert Brooks)，创於1989年。布鲁士先生曾经执掌佳士林得拍卖行的古董车部门，专门负责传统的古董车拍卖事务。之後布鲁士继续进行大型收购活动，目的是要建立一所崭新的国际性艺术品拍卖行。　2001年跟菲利普·逊及尼尔(Phillips Son &amp; Neale )合并，组成新公司，正式改名邦瀚斯。　菲利普·逊及尼尔的办事处设於New Bond Street 101号，合并之後成为邦瀚斯的新总部。这座建筑物由7所独立楼房所组成，常被比喻为“狄更斯时代的养兔埸”。Blenstock House 是第一幢被买下来的办公楼，位处Blenheim Street 与 Woodstock Street交界，是一幢迷人的装饰艺术式建筑。1939年，菲利普先买下地下及地库，之後再逐个楼层买下来，直至1974拥有全幢大楼。至1980年，再买入New Bond Street 101的大楼。邦瀚斯搬入新总部之前，曾委托着名设计师克莱儿·阿格纽( Clare Agnew )重新粉饰及大肆装修一番。</span></p><p><span class="text_BvQ2v" data-text="true" style="box-sizing: content-box; margin: 0px; padding: 0px; position: relative;">之後收购合并并未有停下来，2002年邦瀚斯收购了美国西岸着名和始创於1865年的拍卖行伯得富(Butterfields)，改名邦瀚斯·伯得富。并委任前任职布鲁士拍卖行的马尔科·姆巴伯 (Malcolm Barber)，，出任这间美国附属公司的行政总裁。</span></p><p><span class="text_BvQ2v" data-text="true" style="box-sizing: content-box; margin: 0px; padding: 0px; position: relative;">至2003年底，邦瀚斯每年拍卖700多件</span><span class="text_BvQ2v" data-text="true" style="box-sizing: content-box; margin: 0px; padding: 0px; position: relative;"><a class="innerLink_zQv0z" href="https://baike.baidu.com/item/%E8%89%BA%E6%9C%AF%E5%93%81/0?fromModule=lemma_inlink" target="_blank" data-from-module="" style="box-sizing: content-box; margin: 0px; padding: 0px; color: rgb(19, 110, 194); text-decoration-line: none;">艺术品</a></span><span class="text_BvQ2v" data-text="true" style="box-sizing: content-box; margin: 0px; padding: 0px; position: relative;">，</span><span class="text_BvQ2v" data-text="true" style="box-sizing: content-box; margin: 0px; padding: 0px; position: relative;"><a class="innerLink_zQv0z" href="https://baike.baidu.com/item/%E5%85%AC%E5%8F%B8/0?fromModule=lemma_inlink" target="_blank" data-from-module="" style="box-sizing: content-box; margin: 0px; padding: 0px; color: rgb(19, 110, 194); text-decoration-line: none;">公司</a></span><span class="text_BvQ2v" data-text="true" style="box-sizing: content-box; margin: 0px; padding: 0px; position: relative;">员工超过600人，营业额达3亿400万元。邦瀚斯的环球营销网络以伦敦2个拍卖地点为主，在英国的其他省市设有9个</span><span class="text_BvQ2v" data-text="true" style="box-sizing: content-box; margin: 0px; padding: 0px; position: relative;"><a class="innerLink_zQv0z" href="https://baike.baidu.com/item/%E6%8B%8D%E5%8D%96/81152?fromModule=lemma_inlink" target="_blank" data-from-module="" style="box-sizing: content-box; margin: 0px; padding: 0px; color: rgb(19, 110, 194); text-decoration-line: none;">拍卖</a></span><span class="text_BvQ2v" data-text="true" style="box-sizing: content-box; margin: 0px; padding: 0px; position: relative;">厅，还有</span><span class="text_BvQ2v" data-text="true" style="box-sizing: content-box; margin: 0px; padding: 0px; position: relative;"><a class="innerLink_zQv0z" href="https://baike.baidu.com/item/%E7%91%9E%E5%A3%AB/0?fromModule=lemma_inlink" target="_blank" data-from-module="" style="box-sizing: content-box; margin: 0px; padding: 0px; color: rgb(19, 110, 194); text-decoration-line: none;">瑞士</a></span><span class="text_BvQ2v" data-text="true" style="box-sizing: content-box; margin: 0px; padding: 0px; position: relative;">丶摩纳哥丶德国丶洛杉矶丶旧金山和悉尼都设有销售办事处。邦瀚斯·伯得富於2003年首次在美国东岸举行拍卖会，拍卖埃德温·贾米森 (Edwin C Jameson)在麻省所收藏的古董车和古董精品。</span></p><p><span class="text_BvQ2v" data-text="true" style="box-sizing: content-box; margin: 0px; padding: 0px; position: relative;"><span class="sgs-key" style="margin: 0px; padding: 0px 0px 2px; color: rgb(25, 35, 56); font-weight: var(--base-text-weight-semibold,700); position: relative; display: inline; background-image: linear-gradient(0deg,var(--color-decoration) 12px,transparent 0); font-family: arial, sans-serif; text-wrap-mode: wrap; background-color: rgb(255, 255, 255);">邦瀚斯（Bonhams）是一家历史悠久的国际拍卖行</span><span style="color: rgb(25, 35, 56); font-family: arial, sans-serif; text-wrap-mode: wrap; background-color: rgb(255, 255, 255);">，成立于1793年，总部位于英国伦敦。作为全球规模最大的拍卖行之一，邦瀚斯在艺术品拍卖领域具有深厚实力和卓越影响力。2023年度，邦瀚斯全球总销售额超越了11.4亿美元，创下公司开业230年以来的最佳业绩。</span></span></p>','1');
INSERT INTO `on_news` VALUES ('2','3','韧性与活力并存，永樂2024秋拍第二日2.19亿元收官，石涛、张大千领衔','','石涛、张大千','古画夜场','1','12月10日晚，随着“中国书画夜场”的顺利收槌，永樂2024秋拍中国书画、古籍部分圆满收官，三场拍卖920余件拍品总成交2.19亿元，2件拍品成交价过千万。纵观本季中国书画拍卖，以专题性、学术性为线索，注重名家精品、名人旧藏，同时在估价上也更具吸引力。石涛、王时敏、王鉴、张大千、傅抱石、吴冠中、齐白石、李可染等多位名家精品成交稳健，重磅拍品悉数成交，尤其是来源清晰的名人旧藏成为众多藏家竞拍的焦点；特设的“变革之路—吴冠中、林风眠专辑”“纪念李可染写生七十周年书画作品专题”100%成交，为市场注入蓬勃新活力','1733933127','1733933127','<p style="margin-top: 1em; margin-bottom: 1em; padding: 0px; overflow-wrap: break-word; font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">中国书画夜场共有165件作品上拍，汇聚了张大千、傅抱石、吴冠中、李可染、齐白石、徐悲鸿、刘海粟、溥儒等名家佳作，可谓是星光璀璨。最终历经5个小时的鏖战总成交逾 1.79亿元，成交率达88%，2件拍品超千万元成交。</p><p style="margin-top: 1em; margin-bottom: 1em; padding: 0px; overflow-wrap: break-word; font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">近现代书画的领衔之作，<strong>张大千“血战古人”的力作《仿董北苑江堤晚景》吸引众多买家纷纷举牌，竞争激烈，最终以1207.5万元成交</strong>，再次证明了其在中国书画市场中的重要地位。和许多画家一样，张大千也同样经历了描摹之路。他师古人、师近人、师万物、师造化，最后达到“师心“的境界。《仿董北苑江堤晚景》则是在1947年十月间所仿，行笔如流水，水石幽深，平远险易之行，莫不曲尽其妙。</p><p><strong style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">傅抱石典型的金刚坡风格作品</strong><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">，创作于1944年，再现其曾经居住过而且形成艺术风貌的环境之</span><strong style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">《金刚坡秋色》以920万元成交。</strong><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">这幅画更多像实景写生，重庆的天气多雾潮湿，所以成就了傅抱石独特的氤氲的画风。在这个时期傅抱石就把中国山水画的皴法中间最自由的几种皴法比如说像乱麻皴、乱柴皴、卷云皴这样的一些非常自由的而适合于他的个性的一些皴法用之于来表现巴山蜀水，并形成了他基本的艺术面貌。</span></p><p><span style="background-color: rgb(255, 255, 255); font-family: 宋体, Verdana, Arial; font-size: 14px;">值得一提的是，</span><strong style="background-color: rgb(255, 255, 255); font-family: 宋体, Verdana, Arial; font-size: 14px;">本场<strong>特设专题表现抢眼。</strong></strong><span style="background-color: rgb(255, 255, 255); font-family: 宋体, Verdana, Arial; font-size: 14px;">其中，</span><strong style="background-color: rgb(255, 255, 255); font-family: 宋体, Verdana, Arial; font-size: 14px;">“变革之路—吴冠中、林风眠专辑”（Lot 9014-9018）</strong><span style="background-color: rgb(255, 255, 255); font-family: 宋体, Verdana, Arial; font-size: 14px;">竞投热络，5件作品悉数成交，总揽1932万元。其中，能够集中体现吴冠中关于点、线、面和黑、白、灰等绘画形式语言的理解的</span><strong style="background-color: rgb(255, 255, 255); font-family: 宋体, Verdana, Arial; font-size: 14px;">《新林》以920万元成交</strong><span style="background-color: rgb(255, 255, 255); font-family: 宋体, Verdana, Arial; font-size: 14px;">；同样作于1988年的吴冠中</span><strong style="background-color: rgb(255, 255, 255); font-family: 宋体, Verdana, Arial; font-size: 14px;">《枣林》以345万元成交</strong><span style="background-color: rgb(255, 255, 255); font-family: 宋体, Verdana, Arial; font-size: 14px;">。此外，林风眠仕女作品</span><strong style="background-color: rgb(255, 255, 255); font-family: 宋体, Verdana, Arial; font-size: 14px;">《捧花仕女》《抱琴仕女》</strong><span style="background-color: rgb(255, 255, 255); font-family: 宋体, Verdana, Arial; font-size: 14px;">也备受青睐，均以287.5万元成交。</span><br/></p><p><span style="background-color: rgb(255, 255, 255); font-family: 宋体, Verdana, Arial; font-size: 14px;"><strong style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">“纪念李可染写生七十周年书画作品专题”</strong><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">（Lot9029-9040）同样取得100%成交，总成交超900万元。其中，</span><strong style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">李可染笔下罕有的明丽轻快的江南水乡作品《江南春雨》以488.75万元成交</strong><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">领衔。此外，李可染</span><strong style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">《一览众山小》《观瀑图》</strong><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">分别以138万元、103.5万元成交；李可染经典的动物题材作品</span><strong style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">《五牛图》</strong><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">以92万元成交。</span></span></p><p><span style="background-color: rgb(255, 255, 255); font-family: 宋体, Verdana, Arial; font-size: 14px;"><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);"><strong style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">此外，齐白石、刘海粟、徐悲鸿、溥儒等名家佳构成交喜人，为专场增添了一抹亮色。</strong><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">以酣畅淋漓的泼彩表现黄山气象万千与云海的变幻莫测的</span><strong style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">刘海粟《黄山祥云》以379.5万元成交</strong><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">；外形健朗壮美，且具有坚毅雄强气质的</span><strong style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">徐悲鸿《奔马》以345万元成交</strong><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">；画面幽静秀美的</span><strong style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">溥儒《杂画集锦册》</strong><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">吸引了众多买家的目光，经过激烈角逐，最终</span><strong style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">以138万元成交</strong><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">。</span></span></span></p><p><strong style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">古代书画部分，由石涛《山水集册·纪游图咏》以2357.5万元成交领衔。</strong><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">此作以850万元起拍，引得多位藏家青睐，并以最小的竞价阶梯展开拉锯战，很快突破1000万元的最高估价，在竞价至1900万元时仍有4位藏家在踊跃竞投，此后又经2000万、2020万，最终止步2050万元，含佣金以2357.5万元成交，成为永樂2024秋拍书画最高成交拍品。</span></p><p><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);"><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">作为清代最具传奇色彩的绘画大师，石涛《山水集册·纪游图咏》以十二开册页形式纪游图咏，诗画对题，足称石涛“诗书画”三绝的惊世之作。张大千藏此石涛册页，不惜重金特为刊印出版多次，钤印累累，几乎倾注半生之情，难以割舍，自此册页的每一笔中汲取养分与解放。石涛艺术生涯中极具代表性的一部，汇聚他的艺术与思想精华。大涤草堂，搜尽奇峰，尽数在此。</span><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">“如观自在——中国书画专场”总揽2379.35万元。</span><strong style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">杨文骢仿董北苑笔意的《为周亮工作山水》以92万元入袋新藏家。</strong><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">此作杨文骢不以传统的构图法意识去控制画面布局，松散的置放，反而达到一种自然与幻想的精妙感。此外，</span><strong style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">项圣谟《山居图》</strong><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">以及</span><strong style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">上官周《松柳重翠》</strong><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">同样善价成交，</span><strong style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">分别录得86.25万元、69万元。</strong></span></p>','1');


# 数据库表：on_node 数据信息
INSERT INTO `on_node` VALUES ('1','Admin','后台管理','1','网站后台管理项目','0','0','1');
INSERT INTO `on_node` VALUES ('2','Index','管理首页','1','','15','1','2');
INSERT INTO `on_node` VALUES ('3','Member','注册用户管理','1','','14','1','2');
INSERT INTO `on_node` VALUES ('4','Webinfo','系统管理','1','','3','1','2');
INSERT INTO `on_node` VALUES ('5','index','默认页','1','','6','2','3');
INSERT INTO `on_node` VALUES ('6','myinfo','修改密码','1','','5','2','3');
INSERT INTO `on_node` VALUES ('7','index','用户管理','1','','17','3','3');
INSERT INTO `on_node` VALUES ('8','index','管理员列表','1','','8','14','3');
INSERT INTO `on_node` VALUES ('9','addAdmin','添加管理员','1','','9','14','3');
INSERT INTO `on_node` VALUES ('10','index','站点信息','1','','10','4','3');
INSERT INTO `on_node` VALUES ('11','setEmailConfig','邮箱配置','1','','12','4','3');
INSERT INTO `on_node` VALUES ('12','testEmailConfig','发送测试邮件','1','','0','4','3');
INSERT INTO `on_node` VALUES ('13','setSafeConfig','系统安全设置','1','','0','4','3');
INSERT INTO `on_node` VALUES ('14','Access','权限管理','1','权限管理，为系统后台管理员设置不同的权限','4','1','2');
INSERT INTO `on_node` VALUES ('15','nodeList','查看节点','1','节点列表信息','0','14','3');
INSERT INTO `on_node` VALUES ('16','roleList','角色列表查看','1','角色列表查看','0','14','3');
INSERT INTO `on_node` VALUES ('17','addRole','添加角色','1','','0','14','3');
INSERT INTO `on_node` VALUES ('18','editRole','编辑角色','1','','0','14','3');
INSERT INTO `on_node` VALUES ('19','opNodeStatus','便捷开启禁用节点','1','','0','14','3');
INSERT INTO `on_node` VALUES ('20','opRoleStatus','便捷开启禁用角色','1','','0','14','3');
INSERT INTO `on_node` VALUES ('21','editNode','编辑节点','1','','0','14','3');
INSERT INTO `on_node` VALUES ('22','addNode','添加节点','1','','0','14','3');
INSERT INTO `on_node` VALUES ('23','addAdmin','添加管理员','1','','0','14','3');
INSERT INTO `on_node` VALUES ('24','editAdmin','编辑管理员信息','1','','0','14','3');
INSERT INTO `on_node` VALUES ('25','changeRole','权限分配','1','','0','14','3');
INSERT INTO `on_node` VALUES ('26','News','文章管理','1','','10','1','2');
INSERT INTO `on_node` VALUES ('27','index','文章列表','1','','0','26','3');
INSERT INTO `on_node` VALUES ('28','category','文章分类管理','1','','0','26','3');
INSERT INTO `on_node` VALUES ('29','add','发布文章','1','','0','26','3');
INSERT INTO `on_node` VALUES ('30','edit','编辑文章','1','','0','26','3');
INSERT INTO `on_node` VALUES ('31','del','删除信息','0','','0','26','3');
INSERT INTO `on_node` VALUES ('32','SysData','数据库管理','1','包含数据库备份、还原、打包等','5','1','2');
INSERT INTO `on_node` VALUES ('33','index','查看数据库表结构信息','1','','0','32','3');
INSERT INTO `on_node` VALUES ('34','backup','备份数据库','1','','0','32','3');
INSERT INTO `on_node` VALUES ('35','restore','查看已备份SQL文件','1','','0','32','3');
INSERT INTO `on_node` VALUES ('36','restoreData','执行数据库还原操作','1','','0','32','3');
INSERT INTO `on_node` VALUES ('37','delSqlFiles','删除SQL文件','1','','0','32','3');
INSERT INTO `on_node` VALUES ('38','sendSql','邮件发送SQL文件','1','','0','32','3');
INSERT INTO `on_node` VALUES ('39','zipSql','打包SQL文件','1','','0','32','3');
INSERT INTO `on_node` VALUES ('40','zipList','查看已打包SQL文件','1','','0','32','3');
INSERT INTO `on_node` VALUES ('41','unzipSqlfile','解压缩ZIP文件','1','','0','32','3');
INSERT INTO `on_node` VALUES ('42','delZipFiles','删除zip压缩文件','1','','0','32','3');
INSERT INTO `on_node` VALUES ('43','downFile','下载备份的SQL,ZIP文件','1','','0','32','3');
INSERT INTO `on_node` VALUES ('44','repair','数据库优化修复','1','','0','32','3');
INSERT INTO `on_node` VALUES ('45','add','用户（添加）','1','添加用户的权限','16','3','3');
INSERT INTO `on_node` VALUES ('46','feedback','推广反馈','1','添加推广项的','4','3','3');
INSERT INTO `on_node` VALUES ('47','wallet','用户（账户编辑）','1','编辑用户资金账户','13','3','3');
INSERT INTO `on_node` VALUES ('48','edit','用户（编辑）','1','编辑用户信息','15','3','3');
INSERT INTO `on_node` VALUES ('49','del','用户（删除）','1','删除用户','14','3','3');
INSERT INTO `on_node` VALUES ('50','Goods','商品管理','1','商品仓库和一些商品频道、筛选、扩展的配置','13','1','2');
INSERT INTO `on_node` VALUES ('51','index','商品管理','1','商品列表的显示','37','50','3');
INSERT INTO `on_node` VALUES ('52','add','商品（添加）','1','添加商品','36','50','3');
INSERT INTO `on_node` VALUES ('53','category','频道分类管理','1','添加频道或分类的权限','33','50','3');
INSERT INTO `on_node` VALUES ('54','filtrate','商品属性管理','1','商品属性管理','29','50','3');
INSERT INTO `on_node` VALUES ('55','cate_filt','分类与属性关联','1','频道、分类与筛选条件关联','25','50','3');
INSERT INTO `on_node` VALUES ('56','fields_list','扩展字段管理','1','商品扩展字段列表','22','50','3');
INSERT INTO `on_node` VALUES ('57','cate_extend','分类与扩展字段关联','1','频道、分类与扩展字段关联的操作','18','50','3');
INSERT INTO `on_node` VALUES ('58','del_goods','商品（删除）','1','商品的删除操作','34','50','3');
INSERT INTO `on_node` VALUES ('59','delLink','分类与属性关联（删除）','1','频道、分类与筛选条件关联的删除','23','50','3');
INSERT INTO `on_node` VALUES ('60','fields_add','扩展字段（添加、编辑）','1','扩展字段的添加和编辑','20','50','3');
INSERT INTO `on_node` VALUES ('61','delField','扩展字段（删除）','1','扩展字段的删除','19','50','3');
INSERT INTO `on_node` VALUES ('62','delExtend','分类与扩展字段关联（删除）','1','频道、分类与扩展字段关联的删除','0','50','3');
INSERT INTO `on_node` VALUES ('63','Auction','拍卖管理','1','拍卖管理','12','1','2');
INSERT INTO `on_node` VALUES ('64','index','拍卖管理','1','拍品列表','29','63','3');
INSERT INTO `on_node` VALUES ('65','add','拍卖（发布）','1','商品列表发布到拍卖的操作','28','63','3');
INSERT INTO `on_node` VALUES ('66','edit','拍卖（编辑）','1','编辑拍卖','27','63','3');
INSERT INTO `on_node` VALUES ('67','set_auction','拍卖配置','1','配置拍卖的一些信息','1','63','3');
INSERT INTO `on_node` VALUES ('68','del','拍卖（删除）','1','删除拍卖','26','63','3');
INSERT INTO `on_node` VALUES ('69','Order','订单管理','1','订单管理','11','1','2');
INSERT INTO `on_node` VALUES ('70','index','订单列表','1','订单列表','0','69','3');
INSERT INTO `on_node` VALUES ('71','lose','过期订单','1','过期的订单','0','69','3');
INSERT INTO `on_node` VALUES ('72','deduct','过期订单扣除保证金操作','1','过期订单扣除保证金操作','0','69','3');
INSERT INTO `on_node` VALUES ('73','edit','订单编辑','1','订单编辑','0','69','3');
INSERT INTO `on_node` VALUES ('74','del','订单删除','1','订单删除','0','69','3');
INSERT INTO `on_node` VALUES ('75','set_order','订单配置','1','订单有效期的配置','0','69','3');
INSERT INTO `on_node` VALUES ('76','Link','友情链接','1','友情链接','6','1','2');
INSERT INTO `on_node` VALUES ('77','index','列表','1','友情链接列表','0','76','3');
INSERT INTO `on_node` VALUES ('78','add','添加','1','添加友情链接','0','76','3');
INSERT INTO `on_node` VALUES ('79','edit','编辑','1','编辑友情链接','0','76','3');
INSERT INTO `on_node` VALUES ('80','del','删除','1','友情链接删除','0','76','3');
INSERT INTO `on_node` VALUES ('81','Advertising','广告管理','1','广告管理','9','1','2');
INSERT INTO `on_node` VALUES ('82','index','广告列表','1','广告列表','0','81','3');
INSERT INTO `on_node` VALUES ('83','add_advertising','添加广告','1','添加广告','0','81','3');
INSERT INTO `on_node` VALUES ('84','edit_advertising','编辑广告','1','编辑广告','0','81','3');
INSERT INTO `on_node` VALUES ('85','del_advertising','删除广告','1','删除广告','0','81','3');
INSERT INTO `on_node` VALUES ('86','position','广告位列表','1','广告位列表','0','81','3');
INSERT INTO `on_node` VALUES ('87','add_position','添加广告位','1','添加广告位','0','81','3');
INSERT INTO `on_node` VALUES ('88','edit_position','编辑广告位','1','编辑广告位','0','81','3');
INSERT INTO `on_node` VALUES ('89','del_position','删除广告位','1','删除广告位','0','81','3');
INSERT INTO `on_node` VALUES ('90','Payment','支付管理','1','支付管理','8','1','2');
INSERT INTO `on_node` VALUES ('91','pay_gallery','支付接口配置','1','支付接口配置','0','90','3');
INSERT INTO `on_node` VALUES ('92','edit','支付接口编辑','0','支付接口编辑','0','90','3');
INSERT INTO `on_node` VALUES ('93','index','支付订单列表','1','支付订单列表','0','90','3');
INSERT INTO `on_node` VALUES ('94','del','支付订单删除','1','支付订单删除','0','90','3');
INSERT INTO `on_node` VALUES ('95','setUserAgreement','用户协议','0','用户协议管理','0','4','3');
INSERT INTO `on_node` VALUES ('96','navigation','导航链接管理','1','导航链接管理','0','4','3');
INSERT INTO `on_node` VALUES ('97','steWebConfig','站点配置','1','配置站点的信息','0','4','3');
INSERT INTO `on_node` VALUES ('98','setNoteConfig','短信配置','1','配置短信接口','0','4','3');
INSERT INTO `on_node` VALUES ('99','testNoteConfig','发送测试短信','1','发送测试短信','0','4','3');
INSERT INTO `on_node` VALUES ('100','setUserAgreement','用户协议','1','编辑用户协议','0','4','3');
INSERT INTO `on_node` VALUES ('101','set_member','注册用户设置','1','注册用户设置','1','3','3');
INSERT INTO `on_node` VALUES ('102','special','专场管理','1','专场列表查看各个状态的专场','16','63','3');
INSERT INTO `on_node` VALUES ('103','special_add','专场（添加）','1','添加专场的操作','15','63','3');
INSERT INTO `on_node` VALUES ('104','special_edit','专场（编辑）','1','编辑专场','14','63','3');
INSERT INTO `on_node` VALUES ('105','special_del','专场（删除）','1','删除专场操作','13','63','3');
INSERT INTO `on_node` VALUES ('106','meeting','拍卖会管理','1','拍卖会列表','10','63','3');
INSERT INTO `on_node` VALUES ('107','meeting_add','拍卖会（添加）','1','添加拍卖会','9','63','3');
INSERT INTO `on_node` VALUES ('108','meeting_edit','拍卖会（编辑）','1','编辑拍卖会','8','63','3');
INSERT INTO `on_node` VALUES ('109','meeting_del','拍卖会（删除）','1','删除拍卖会','7','63','3');
INSERT INTO `on_node` VALUES ('110','special_hideshow','专场（显示、隐藏）','1','专场的显示和隐藏','12','63','3');
INSERT INTO `on_node` VALUES ('111','edit','商品（编辑）','1','编辑商品信息','35','50','3');
INSERT INTO `on_node` VALUES ('112','take','提现管理','1','提现申请的显示','3','2','3');
INSERT INTO `on_node` VALUES ('113','cache','缓存管理','1','缓存的查看和清空操作','1','2','3');
INSERT INTO `on_node` VALUES ('114','Weixin','微信平台','1','微信平台管理','7','1','2');
INSERT INTO `on_node` VALUES ('115','index','图文消息列表','1','图文列表显示','0','114','3');
INSERT INTO `on_node` VALUES ('116','addurl','添加图文消息','1','添加图文消息','0','114','3');
INSERT INTO `on_node` VALUES ('117','editurl','编辑图文消息','1','编辑图文消息','0','114','3');
INSERT INTO `on_node` VALUES ('118','weipush','批量推送图文消息','1','批量推送图文消息','0','114','3');
INSERT INTO `on_node` VALUES ('119','delurl','删除图文消息','1','删除图文消息','0','114','3');
INSERT INTO `on_node` VALUES ('120','weimenu','自定义菜单编辑','1','自定义菜单编辑','0','114','3');
INSERT INTO `on_node` VALUES ('121','weiconfig','微信配置','1','微信配置','0','114','3');
INSERT INTO `on_node` VALUES ('122','webmail','站内信管理','1','站内信列表的显示','8','3','3');
INSERT INTO `on_node` VALUES ('123','sendsms','发送站内信','1','给用户发送站内信','7','3','3');
INSERT INTO `on_node` VALUES ('124','setdelsms','站内信（设置删除）','1','设置站内信为删除状态','5','3','3');
INSERT INTO `on_node` VALUES ('125','delsms','站内信（删除）','1','彻底删除站内信','6','3','3');
INSERT INTO `on_node` VALUES ('126','sharerecord','用户分享记录','1','显示微信版用户分享链接的记录','0','114','3');
INSERT INTO `on_node` VALUES ('127','showset','查看拍卖','1','查看拍卖信息','0','63','3');
INSERT INTO `on_node` VALUES ('128','statistics','资金统计','1','用于查看网站资金的页面','4','2','3');
INSERT INTO `on_node` VALUES ('129','rechargeable','充值卡管理（列表）','1','显示充值卡列表','0','90','3');
INSERT INTO `on_node` VALUES ('130','search_rechargeable','充值卡搜索','1','搜索充值卡','0','90','3');
INSERT INTO `on_node` VALUES ('131','export_rechargeable','导出充值卡excel','1','导出充值卡excel','0','90','3');
INSERT INTO `on_node` VALUES ('132','add_rechargeable','添加充值卡（制卡）','1','添加充值卡（制卡）','0','90','3');
INSERT INTO `on_node` VALUES ('133','del_rechargeable','删除充值卡','1','删除充值卡','0','90','3');
INSERT INTO `on_node` VALUES ('134','seller_pledge','卖家保证金管理','1','查看卖家保证金列表','3','63','3');
INSERT INTO `on_node` VALUES ('135','add_jurisdiction','添加一次性缴纳保证金用户（添加会员）','1','','2','63','3');
INSERT INTO `on_node` VALUES ('136','rtake','提现处理','1','提现的处理、驳回','1','2','3');
INSERT INTO `on_node` VALUES ('137','consultation','意见反馈管理','1','意见反馈列表的显示','0','2','3');
INSERT INTO `on_node` VALUES ('138','consultation_edit','意见反馈（回复）','1','意见反馈的回复','0','2','3');
INSERT INTO `on_node` VALUES ('139','consultation_del','意见反馈（删除）','1','意见反馈的删除操作','0','2','3');
INSERT INTO `on_node` VALUES ('140','deliver_address','用户（地址列表）','1','用户地址的显示','10','3','3');
INSERT INTO `on_node` VALUES ('141','walletbill','账户记录','1','账户记录的显示','9','3','3');
INSERT INTO `on_node` VALUES ('142','feedback_add','推广反馈（添加）','1','推广反馈的添加','3','3','3');
INSERT INTO `on_node` VALUES ('143','feedback_del','推广反馈（删除）','1','推广反馈的删除','2','3','3');
INSERT INTO `on_node` VALUES ('144','category_add','频道分类（添加）','1','频道分类的添加','32','50','3');
INSERT INTO `on_node` VALUES ('145','category_edit','频道分类（编辑）','1','频道分类的编辑','31','50','3');
INSERT INTO `on_node` VALUES ('146','category_del','频道分类（删除）','1','频道分类的删除','30','50','3');
INSERT INTO `on_node` VALUES ('147','filtrate_add','商品属性（添加）','1','商品属性的添加','28','50','3');
INSERT INTO `on_node` VALUES ('150','cate_filt_add','分类与属性关联（添加）','1','添加分类与属性的关联','24','50','3');
INSERT INTO `on_node` VALUES ('148','filtrate_edit','商品属性（编辑）','1','商品属性的编辑','27','50','3');
INSERT INTO `on_node` VALUES ('149','filtrate_del','商品属性（删除）','1','商品属性的删除','26','50','3');
INSERT INTO `on_node` VALUES ('151','fields_describe','扩展字段（商品详情默认值设置）','1','商品详情默认值设置','21','50','3');
INSERT INTO `on_node` VALUES ('152','cate_extend_add','分类与扩展字段关联（添加）','1','分类与扩展字段的关联的添加','17','50','3');
INSERT INTO `on_node` VALUES ('153','info','拍卖（详情）','1','拍卖信息的查看','25','63','3');
INSERT INTO `on_node` VALUES ('154','hideshow','拍卖（隐藏）','1','拍卖的隐藏','24','63','3');
INSERT INTO `on_node` VALUES ('155','recommend','拍卖（推荐、取消推荐）','1','拍品的推荐和取消推荐','22','63','3');
INSERT INTO `on_node` VALUES ('156','cancelPai','拍卖（撤拍）','1','拍卖的撤拍','21','63','3');
INSERT INTO `on_node` VALUES ('157','special_info','专场（详情）','1','专场详情的查看','11','63','3');
INSERT INTO `on_node` VALUES ('158','meeting_info','拍卖会（详情）','1','拍卖会详情的查看','5','63','3');
INSERT INTO `on_node` VALUES ('159','meeting_hideshow','拍卖会（显示、隐藏）','1','拍卖会的显示和隐藏','6','63','3');
INSERT INTO `on_node` VALUES ('160','realname','实名认证管理','1','用于显示查询实名认证的用户','12','3','3');
INSERT INTO `on_node` VALUES ('161','realname_edit','实名认证编辑','1','实名认证的操作编辑','11','3','3');
INSERT INTO `on_node` VALUES ('162','repeat','重复拍','1','重复拍的列表显示','20','63','3');
INSERT INTO `on_node` VALUES ('163','repeat_add','重复拍（添加）','1','重复拍的添加','19','63','3');
INSERT INTO `on_node` VALUES ('164','repeat_edit','重复拍（编辑）','1','重复拍的编辑','18','63','3');
INSERT INTO `on_node` VALUES ('165','repeat_del','重复拍（删除）','1','重复拍的删除','17','63','3');


# 数据库表：on_order_break 数据信息


# 数据库表：on_payorder 数据信息
INSERT INTO `on_payorder` VALUES ('czy173392794270847','czy173392794270847','pledge','0','2','1000.00','0.00','0.00','UN_WAP','充值余额','http://wmiw.ebnnw.cn/Home/Member/wallet','http://wmiw.ebnnw.cn/Home/Member/payment','0','1733927942','1733927942');
INSERT INTO `on_payorder` VALUES ('czy173392807474184','czy173392807474184','pledge','0','2','100.00','0.00','0.00','ALI_WAP','充值余额','http://wmiw.ebnnw.cn/Home/Member/wallet','http://wmiw.ebnnw.cn/Home/Member/payment','0','1733928074','1733928074');
INSERT INTO `on_payorder` VALUES ('czy173392811586936','czy173392811586936','pledge','0','2','1000.00','0.00','0.00','YEE_WAP','充值余额','http://wmiw.ebnnw.cn/Home/Member/wallet','http://wmiw.ebnnw.cn/Home/Member/payment','0','1733928115','1733928115');
INSERT INTO `on_payorder` VALUES ('czy173392814500086','czy173392814500086','pledge','0','2','1000.00','0.00','0.00','YEE_WAP','充值余额','http://wmiw.ebnnw.cn/Home/Member/wallet','http://wmiw.ebnnw.cn/Home/Member/payment','0','1733928145','1733928145');
INSERT INTO `on_payorder` VALUES ('czy173392814602168','czy173392814602168','pledge','0','2','1000.00','0.00','0.00','YEE_WAP','充值余额','http://wmiw.ebnnw.cn/Home/Member/wallet','http://wmiw.ebnnw.cn/Home/Member/payment','0','1733928146','1733928146');
INSERT INTO `on_payorder` VALUES ('czy173392814604313','czy173392814604313','pledge','0','2','1000.00','0.00','0.00','YEE_WAP','充值余额','http://wmiw.ebnnw.cn/Home/Member/wallet','http://wmiw.ebnnw.cn/Home/Member/payment','0','1733928146','1733928146');
INSERT INTO `on_payorder` VALUES ('czy17339281460657','czy17339281460657','pledge','0','2','1000.00','0.00','0.00','YEE_WAP','充值余额','http://wmiw.ebnnw.cn/Home/Member/wallet','http://wmiw.ebnnw.cn/Home/Member/payment','0','1733928146','1733928146');


# 数据库表：on_rechargeable 数据信息


# 数据库表：on_role 数据信息
INSERT INTO `on_role` VALUES ('6','审核小组','','1','');
INSERT INTO `on_role` VALUES ('7','审核员1','6','1','');
INSERT INTO `on_role` VALUES ('8','发布小组','6','0','');


# 数据库表：on_role_user 数据信息
INSERT INTO `on_role_user` VALUES ('7','13');


# 数据库表：on_scheduled 数据信息


# 数据库表：on_seller_pledge 数据信息
INSERT INTO `on_seller_pledge` VALUES ('1','1','1','every','200.00','0.00','1733935104','1');
INSERT INTO `on_seller_pledge` VALUES ('2','4','2','every','200.00','0.00','1734106105','1');


# 数据库表：on_share 数据信息


# 数据库表：on_special_auction 数据信息


# 数据库表：on_weiurl 数据信息
