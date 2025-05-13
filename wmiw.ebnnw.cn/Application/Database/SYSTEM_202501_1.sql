# -----------------------------------------------------------
# PHP-Amateur database backup files
# Type: 系统自动备份
# Description:当前SQL文件包含了表：on_access、on_admin、on_advertising、on_advertising_position、on_attention、on_attention_seller、on_auction、on_auction_agency、on_auction_pledge、on_auction_record、on_auction_repeat、on_blacklist、on_category、on_consultation、on_deliver_address、on_feedback、on_goods、on_goods_category、on_goods_category_extend、on_goods_category_filtrate、on_goods_evaluate、on_goods_extend、on_goods_fields、on_goods_filtrate、on_goods_order、on_goods_order_return、on_goods_user、on_link、on_meeting_auction、on_member、on_member_evaluate、on_member_footprint、on_member_limsum_bill、on_member_pledge_bill、on_member_pledge_take、on_member_weixin、on_mysms、on_navigation、on_news、on_node、on_order_break、on_payorder、on_rechargeable、on_role、on_role_user、on_scheduled、on_seller_pledge、on_share、on_special_auction、on_weiurl的结构信息，表：on_access、on_admin、on_advertising、on_advertising_position、on_attention、on_attention_seller、on_auction、on_auction_agency、on_auction_pledge、on_auction_record、on_auction_repeat、on_blacklist、on_category、on_consultation、on_deliver_address、on_feedback、on_goods、on_goods_category、on_goods_category_extend、on_goods_category_filtrate、on_goods_evaluate、on_goods_extend、on_goods_fields、on_goods_filtrate、on_goods_order、on_goods_order_return、on_goods_user、on_link、on_meeting_auction、on_member、on_member_evaluate、on_member_footprint、on_member_limsum_bill、on_member_pledge_bill、on_member_pledge_take、on_member_weixin、on_mysms、on_navigation、on_news、on_node、on_order_break、on_payorder、on_rechargeable、on_role、on_role_user、on_scheduled、on_seller_pledge、on_share、on_special_auction、on_weiurl的数据
# Time: 2025-01-02 14:44:04
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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='网站后台管理员表' ;

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
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='广告表' ;

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
  `caiyu` int(11) NOT NULL DEFAULT '0' COMMENT '初始参与人数',
  `guanzhu` int(11) NOT NULL DEFAULT '0' COMMENT '初始关注人数',
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
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=utf8 COMMENT='拍卖表' ;

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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='地址表' ;

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
) ENGINE=MyISAM AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 COMMENT='商品表' ;

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
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商品分类表' ;

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商品属性表' ;

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
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='订单表' ;

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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='拍卖会表' ;

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
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='网站前台会员表' ;

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
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COMMENT='用户信用额度账单' ;

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
) ENGINE=MyISAM AUTO_INCREMENT=93 DEFAULT CHARSET=utf8 COMMENT='用户保证金账单' ;

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
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='用户提现账单' ;

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
) ENGINE=MyISAM AUTO_INCREMENT=317 DEFAULT CHARSET=utf8 COMMENT='用户消息提醒' ;

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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='新闻表' ;

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
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 COMMENT='商家保证金表' ;

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
INSERT INTO `on_access` VALUES ('7','74','3','69','');
INSERT INTO `on_access` VALUES ('7','73','3','69','');
INSERT INTO `on_access` VALUES ('7','72','3','69','');
INSERT INTO `on_access` VALUES ('7','71','3','69','');
INSERT INTO `on_access` VALUES ('7','70','3','69','');
INSERT INTO `on_access` VALUES ('7','69','2','1','');
INSERT INTO `on_access` VALUES ('7','155','3','63','');
INSERT INTO `on_access` VALUES ('7','154','3','63','');
INSERT INTO `on_access` VALUES ('7','153','3','63','');
INSERT INTO `on_access` VALUES ('7','127','3','63','');
INSERT INTO `on_access` VALUES ('7','108','3','63','');
INSERT INTO `on_access` VALUES ('7','107','3','63','');
INSERT INTO `on_access` VALUES ('7','106','3','63','');
INSERT INTO `on_access` VALUES ('7','104','3','63','');
INSERT INTO `on_access` VALUES ('7','103','3','63','');
INSERT INTO `on_access` VALUES ('7','68','3','63','');
INSERT INTO `on_access` VALUES ('7','67','3','63','');
INSERT INTO `on_access` VALUES ('7','66','3','63','');
INSERT INTO `on_access` VALUES ('7','65','3','63','');
INSERT INTO `on_access` VALUES ('7','64','3','63','');
INSERT INTO `on_access` VALUES ('7','63','2','1','');
INSERT INTO `on_access` VALUES ('7','52','3','50','');
INSERT INTO `on_access` VALUES ('7','51','3','50','');
INSERT INTO `on_access` VALUES ('7','50','2','1','');
INSERT INTO `on_access` VALUES ('7','161','3','3','');
INSERT INTO `on_access` VALUES ('7','160','3','3','');
INSERT INTO `on_access` VALUES ('7','141','3','3','');
INSERT INTO `on_access` VALUES ('7','123','3','3','');
INSERT INTO `on_access` VALUES ('7','49','3','3','');
INSERT INTO `on_access` VALUES ('7','48','3','3','');
INSERT INTO `on_access` VALUES ('7','45','3','3','');
INSERT INTO `on_access` VALUES ('7','7','3','3','');
INSERT INTO `on_access` VALUES ('7','3','2','1','');
INSERT INTO `on_access` VALUES ('7','136','3','2','');
INSERT INTO `on_access` VALUES ('7','128','3','2','');
INSERT INTO `on_access` VALUES ('7','113','3','2','');
INSERT INTO `on_access` VALUES ('7','112','3','2','');
INSERT INTO `on_access` VALUES ('7','5','3','2','');
INSERT INTO `on_access` VALUES ('7','2','2','1','');
INSERT INTO `on_access` VALUES ('7','1','1','0','');


# 数据库表：on_admin 数据信息
INSERT INTO `on_admin` VALUES ('1','超级管理员','super@ebnnw.cn','','02e35b7bb4750dc82d90c9e4f49dcbc0','1','我是超级管理员','','1733925334');
INSERT INTO `on_admin` VALUES ('2','管理员','jiashide@gmail.com','','ace250e3c8f8d908155dc48201d30bfe','1','','','1734497517');


# 数据库表：on_advertising 数据信息
INSERT INTO `on_advertising` VALUES ('1','1','LOGO（大屏显示）','Advshow/20241217/67607d3b0b50a.jpg','','0','0','','0','本图片在电脑版屏幕上会显示。','0','0','0');
INSERT INTO `on_advertising` VALUES ('2','2','LOGO（小屏显示）','Advshow/20241225/676afad036b3f.jpg','','0','0','http://eliaukjsd.wcrcz.com','0','小屏幕访问网站显示该图片','0','0','0');
INSERT INTO `on_advertising` VALUES ('3','4','','Advshow/20241219/676317fb7fb34.jpg','#281736','0','0','','0','','2','0','0');
INSERT INTO `on_advertising` VALUES ('4','3','首页-BANNER列表图片（2）','Advshow/20241217/676077773e619.jpg','#010101','0','0','','0','','0','0','0');
INSERT INTO `on_advertising` VALUES ('5','3','首页-BANNER列表图片（3）','Advshow/20241217/676077848c9c8.jpg','#ffffff','0','0','','0','','0','0','0');
INSERT INTO `on_advertising` VALUES ('7','4','正在拍賣aaaaabbbb'','Advshow/20241218/6762a9fb5b58e.jpg','','0','0','','0','走进艺术与珍品的殿堂——佳士得拍卖行','0','0','0');
INSERT INTO `on_advertising` VALUES ('8','5','首页-拍卖会标题处（PNG）','Advshow/20241218/6762a7e492f30.jpg','','0','0','','0','','0','0','0');
INSERT INTO `on_advertising` VALUES ('9','6','导航-全部分类-为你推荐','Advshow/20241213/675b45ba82522.jpg','','0','1','','0','','0','0','0');
INSERT INTO `on_advertising` VALUES ('10','7','注册登陆-BANNER','Advshow/20241218/67623248acfd2.jpg','#980000','0','0','','0','登錄您的帳戶以獲取建議，出價和註冊銷售','0','0','0');
INSERT INTO `on_advertising` VALUES ('11','3','首页-BANNER列表图片（34）','Advshow/20241219/67631af7081de.jpg','#8b0000','0','0','','0','','1','0','0');


# 数据库表：on_advertising_position 数据信息
INSERT INTO `on_advertising_position` VALUES ('1','logoA','LOGO（大屏显示）','200','200');
INSERT INTO `on_advertising_position` VALUES ('2','logoB','LOGO（小屏显示）','159','78');
INSERT INTO `on_advertising_position` VALUES ('3','banner','首页-BANNER列表图片','500','855');
INSERT INTO `on_advertising_position` VALUES ('4','headlines','首页-头条-底部','220','800');
INSERT INTO `on_advertising_position` VALUES ('5','mittingtitle','首页-拍卖会标题处','500','700');
INSERT INTO `on_advertising_position` VALUES ('6','recommend','导航-全部分类-为你推荐','750','270');
INSERT INTO `on_advertising_position` VALUES ('7','lrbanner','登陆注册-BANNER','600','400');
INSERT INTO `on_advertising_position` VALUES ('9','html标签','正在拍賣aaaaabbbb'','1000','800');


# 数据库表：on_attention 数据信息
INSERT INTO `on_attention` VALUES ('21','12','p-u');
INSERT INTO `on_attention` VALUES ('28','16','p-u');
INSERT INTO `on_attention` VALUES ('29','17','p-u');
INSERT INTO `on_attention` VALUES ('28','22','p-u');
INSERT INTO `on_attention` VALUES ('29','22','p-u');
INSERT INTO `on_attention` VALUES ('29','9','p-u');
INSERT INTO `on_attention` VALUES ('30','23','p-u');
INSERT INTO `on_attention` VALUES ('27','26','p-u');
INSERT INTO `on_attention` VALUES ('30','28','p-u');
INSERT INTO `on_attention` VALUES ('43','22','p-u');


# 数据库表：on_attention_seller 数据信息
INSERT INTO `on_attention_seller` VALUES ('4','1','1734099464');
INSERT INTO `on_attention_seller` VALUES ('9','5','1734467944');
INSERT INTO `on_attention_seller` VALUES ('12','5','1734858031');
INSERT INTO `on_attention_seller` VALUES ('22','5','1735013693');
INSERT INTO `on_attention_seller` VALUES ('26','5','1735320502');


# 数据库表：on_auction 数据信息
INSERT INTO `on_auction` VALUES ('1','2','0','0','','0','0','0.00','0.00','0','0','清康熙·窑变釉鹿头尊','4800000.00','4800000.00','4800000.00','1733936100','1734470784','50000','1','fixation','fixation','20.00','ratio','10.00','20000.00','120','300','0','0','0.00','0','4','0','0','37','0','0','0','1733935104','0');
INSERT INTO `on_auction` VALUES ('2','3','0','0','','0','0','0.00','0.00','0','0',' 清雍正 青花纏枝花卉紋蒜頭大瓶','6300000.00','6300000.00','6300000.00','1734106020','1735574700','300000','1','fixation','ratio','5.00','ratio','10.00','20000.00','120','300','26','0','0.00','1','1','0','0','295','42','29','0','1734534845','0');
INSERT INTO `on_auction` VALUES ('3','4','0','0','','0','0','0.00','0.00','0','0','何海霞 万山红遍','4800000.00','4800000.00','4800000.00','1734281280','1738252800','50000','1','fixation','fixation','20.00','ratio','10.00','30000.00','120','300','9','0','0.00','1','0','0','0','65','0','0','0','1734281343','0');
INSERT INTO `on_auction` VALUES ('4','5','0','0','','0','0','0.00','0.00','0','0','徐悲鸿 奔马 立轴','2800000.00','2800000.00','2800000.00','1734281640','1737996600','50000','1','fixation','fixation','20.00','ratio','10.00','30000.00','120','300','9','0','0.00','1','0','0','0','70','0','0','0','1734281692','0');
INSERT INTO `on_auction` VALUES ('5','6','0','0','','0','0','0.00','0.00','0','0',' 明宣德青花暗花【海水遊龍】圖高足杯','12000000.00','12000000.00','12000000.00','1734284580','1736876400','500000','1','fixation','ratio','5.00','ratio','10.00','100000.00','120','300','0','0','0.00','0','0','0','0','66','23','9','0','1734534812','0');
INSERT INTO `on_auction` VALUES ('6','7','0','0','','0','0','0.00','0.00','0','0',' 清乾隆 禦製洋彩紫紅錦地乾坤交泰轉旋瓶','10800000.00','10800000.00','10800000.00','1734287460','1737311400','500000','1','fixation','ratio','5.00','ratio','10.00','50000.00','120','300','0','0','0.00','0','0','0','0','149','31','12','0','1734534787','0');
INSERT INTO `on_auction` VALUES ('7','8','0','0','','0','0','0.00','0.00','0','0','清乾隆《松石綠錦地浮雕金彩百壽琮式瓶》','6800000.00','6800000.00','6800000.00','1735240500','1735606800','300000','1','fixation','ratio','5.00','ratio','10.00','50000.00','120','300','38','0','0.00','1','1','0','0','131','0','0','0','1735606319','0');
INSERT INTO `on_auction` VALUES ('8','9','0','0','','0','0','0.00','0.00','0','0','清乾隆·淡綠彩浮雕礬紅金彩萬壽八方瓶','3500000.00','3500000.00','3500000.00','1738351500','1746041100','300000','1','fixation','fixation','20.00','ratio','10.00','50000.00','120','300','0','0','0.00','0','0','0','0','49','0','0','0','1734290764','0');
INSERT INTO `on_auction` VALUES ('9','10','0','0','','0','0','0.00','0.00','0','0','清雍正 爐鈞釉仿古尊','2200000.00','2200000.00','2200000.00','1738351500','1746041100','100000','1','fixation','fixation','20.00','ratio','10.00','50000.00','120','300','0','0','0.00','0','0','0','0','51','0','0','0','1734290954','0');
INSERT INTO `on_auction` VALUES ('10','11','0','0','','0','0','0.00','0.00','0','0',' 清乾隆 仿官釉六稜貫耳瓶','2000000.00','2000000.00','2000000.00','1738351800','1746041400','100000','1','fixation','ratio','5.00','ratio','10.00','50000.00','120','300','0','0','0.00','0','0','0','0','121','55','22','0','1734535044','0');
INSERT INTO `on_auction` VALUES ('11','12','0','0','','0','0','0.00','0.00','0','0','17世紀 黃花梨軸門圓角櫃','800000.00','800000.00','800000.00','1738350900','1746052200','50000','1','fixation','ratio','5.00','ratio','10.00','30000.00','120','300','0','0','0.00','0','0','0','0','72','22','6','0','1734535014','0');
INSERT INTO `on_auction` VALUES ('12','13','0','0','','0','0','0.00','0.00','0','0',' 傅抱石 苦瓜和尚詩圖','5000000.00','5000000.00','5000000.00','1738347000','1746067800','300000','1','fixation','ratio','5.00','ratio','10.00','100000.00','120','300','0','0','0.00','0','0','0','0','131','35','12','0','1734534990','0');
INSERT INTO `on_auction` VALUES ('13','14','0','0','','0','0','0.00','0.00','0','0',' 劉野 雷鋒叔叔','4000000.00','4000000.00','4000000.00','1738339800','1746052200','300000','1','fixation','ratio','5.00','ratio','10.00','50000.00','120','300','0','0','0.00','0','0','0','0','207','52','23','0','1734534956','0');
INSERT INTO `on_auction` VALUES ('14','15','0','0','','0','0','0.00','0.00','0','0',' 曾梵​​志 《三年級一班 第6號》','1200000.00','1200000.00','1200000.00','1738352400','1746041400','100000','1','fixation','ratio','5.00','ratio','10.00','20000.00','120','300','0','0','0.00','0','0','0','0','55','0','0','0','1734534925','0');
INSERT INTO `on_auction` VALUES ('19','20','0','2','','0','0','0.00','0.00','3','0','銅錯銀鎏金鎏銀持拂塵者像','2000000.00','2000000.00','2000000.00','1740774960','1740775080','50000','1','fixation','ratio','5.00','ratio','10.00','0.00','120','300','0','0','0.00','0','0','0','0','564','305','255','1','1734369891','0');
INSERT INTO `on_auction` VALUES ('20','21','0','0','','0','0','0.00','0.00','0','0','巴拿馬-太平洋紀念幣','1600000.00','1600000.00','1600000.00','1734297180','1737580200','50000','1','fixation','ratio','5.00','ratio','10.00','30000.00','120','300','26','0','0.00','1','0','0','1','144','63','25','0','1734534752','0');
INSERT INTO `on_auction` VALUES ('21','22','0','0','','0','0','0.00','0.00','0','0','清乾隆 御制铜胎掐丝珐琅双人托长方形炉','4800000.00','4800000.00','6300000.00','1734297660','1738272000','500000','1','fixation','ratio','5.00','ratio','10.00','50000.00','120','300','9','0','0.00','4','0','0','0','70','0','0','0','1734297728','0');
INSERT INTO `on_auction` VALUES ('22','23','0','0','','0','0','0.00','0.00','0','0','晚明 黃花梨架子床','3000000.00','3000000.00','3000000.00','1734298140','1738358700','50000','1','fixation','ratio','5.00','ratio','10.00','50000.00','120','300','0','0','0.00','0','0','0','0','114','10','30','0','1734534699','0');
INSERT INTO `on_auction` VALUES ('23','24','0','0','','0','0','0.00','0.00','0','0','乾隆帝 御笔行书〈御制澄怀堂诗〉七言联 ','7200000.00','7200000.00','7700000.00','1734298500','1738359300','500000','1','fixation','ratio','5.00','ratio','10.00','50000.00','120','300','12','0','0.00','2','0','0','1','89','0','12','0','1734396901','0');
INSERT INTO `on_auction` VALUES ('24','25','0','2','','0','0','0.00','0.00','3','0','十六世紀 銅鎏金密集文殊金剛像','3500000.00','3500000.00','3500000.00','1740776220','1740776340','30000','1','fixation','ratio','5.00','ratio','10.00','0.00','120','300','0','0','0.00','0','0','0','0','510','73','51','2','1734534903','0');
INSERT INTO `on_auction` VALUES ('25','26','0','2','','0','0','0.00','0.00','3','0','十四世紀 銅鎏金佛陀像','1600000.00','1600000.00','1600000.00','1740776220','1740776340','30000','1','fixation','ratio','5.00','ratio','10.00','0.00','120','300','0','0','0.00','0','0','0','0','357','124','96','3','1734534890','0');
INSERT INTO `on_auction` VALUES ('26','27','0','2','','0','0','0.00','0.00','3','0',' 十三世紀 銅鎏金釋迦牟尼像','8000000.00','8000000.00','8000000.00','1740776040','1740776160','300000','1','fixation','ratio','5.00','ratio','10.00','0.00','120','300','0','0','0.00','0','0','0','0','359','55','23','4','1734534872','0');
INSERT INTO `on_auction` VALUES ('27','28','0','0','','0','0','0.00','0.00','0','0',' 大清雍正六年時憲歷','5500000.00','5500000.00','6000000.00','1734470460','1739222400','100000','1','fixation','ratio','5.00','ratio','10.00','50000.00','120','300','26','0','0.00','2','0','0','0','199','15','9','0','1734534605','0');
INSERT INTO `on_auction` VALUES ('28','29','0','0','','0','0','0.00','0.00','0','0','元代 青花龙纹玉壶春瓶','800000.00','800000.00','1130000.00','1734932760','1735214400','30000','1','fixation','ratio','5.00','ratio','10.00','50000.00','120','300','12','0','0.00','4','1','0','1','156','1','3','0','1734960180','0');
INSERT INTO `on_auction` VALUES ('29','30','0','0','','0','0','0.00','0.00','0','0','清乾隆 珐瑯彩描金雙耳花卉紋瓶','5000000.00','5000000.00','7100000.00','1734959100','1735286400','300000','1','fixation','ratio','5.00','ratio','10.00','50000.00','120','300','12','0','0.00','6','1','0','1','224','0','5','0','1734960170','0');
INSERT INTO `on_auction` VALUES ('30','31','0','0','','0','0','0.00','0.00','0','0','丁雲鵬《佛像圖》','28000000.00','28000000.00','29000000.00','1735097340','1735395900','500000','1','fixation','ratio','5.00','ratio','10.00','50000.00','120','300','28','0','0.00','3','1','0','0','285','0','9','0','1735099004','0');
INSERT INTO `on_auction` VALUES ('31','32','0','0','','0','0','0.00','0.00','0','0','清乾隆 孔雀藍地描金折枝花卉紋雙耳瓶','10000000.00','10000000.00','10000000.00','1735291200','1739006400','500000','1','fixation','ratio','5.00','ratio','10.00','100000.00','120','300','0','0','0.00','0','0','0','0','141','5','11','0','1735291364','0');
INSERT INTO `on_auction` VALUES ('32','33','0','3','','0','0','0.00','0.00','3','0','STEGOSAURUS（劍龍）','45000000.00','45000000.00','45000000.00','1740674460','1740674520','3000000','1','fixation','ratio','5.00','ratio','10.00','0.00','120','300','0','0','0.00','0','0','0','0','366','59','31','1','1735327083','0');
INSERT INTO `on_auction` VALUES ('33','34','0','3','','0','0','0.00','0.00','3','0','成年同幼年異特龍','73000000.00','73000000.00','73000000.00','1740674460','1740674520','3000000','1','fixation','ratio','5.00','ratio','10.00','0.00','120','300','0','0','0.00','0','0','0','0','1237','599','93','2','1735327332','0');
INSERT INTO `on_auction` VALUES ('34','35','0','0','','0','0','0.00','0.00','0','0','清康熙青花人物雙耳瓶','3500000.00','3500000.00','4400000.00','1735453980','1735713000','300000','1','fixation','ratio','5.00','ratio','10.00','50000.00','120','300','36','0','0.00','3','1','0','0','158','0','5','0','1735523891','0');
INSERT INTO `on_auction` VALUES ('35','36','0','0','','0','0','0.00','0.00','0','0','元 青花蒙括將軍玉壺春瓶','3200000.00','3200000.00','4100000.00','1735472760','1735797600','300000','1','fixation','ratio','5.00','ratio','10.00','50000.00','120','300','38','0','0.00','4','1','0','0','190','0','3','0','1735523818','0');
INSERT INTO `on_auction` VALUES ('36','37','0','4','','0','0','0.00','0.00','3','0','弗雷德里克·伦赛特（1861-1909）','2100000.00','2100000.00','2100000.00','1737565200','1737565320','30000','1','fixation','ratio','5.00','ratio','10.00','0.00','120','300','0','0','0.00','0','0','0','0','2','0','0','1','1735539373','0');
INSERT INTO `on_auction` VALUES ('37','38','0','4','','0','0','0.00','0.00','3','0','弗雷德里克·伦赛特（1861-1909）','2100000.00','2100000.00','2100000.00','1737565380','1737565500','300000','1','fixation','ratio','5.00','ratio','10.00','0.00','120','300','0','0','0.00','0','0','0','0','0','0','0','2','1735540636','0');
INSERT INTO `on_auction` VALUES ('38','39','0','4','','0','0','0.00','0.00','3','0','托馬斯·莫蘭（1837-1926）','5600000.00','5600000.00','5600000.00','1737565560','1737565680','300000','1','fixation','ratio','5.00','ratio','10.00','0.00','120','300','0','0','0.00','0','0','0','0','0','0','0','3','1735541084','0');
INSERT INTO `on_auction` VALUES ('39','40','0','4','','0','0','0.00','0.00','3','0','托馬斯·希爾（1829-1908）','1800000.00','1800000.00','1800000.00','1737565740','1737565860','50000','1','fixation','ratio','5.00','ratio','10.00','0.00','120','300','0','0','0.00','0','0','0','0','0','0','0','4','1735541380','0');
INSERT INTO `on_auction` VALUES ('40','41','0','4','','0','0','0.00','0.00','3','0','阿尔伯特·比尔施塔特 1830-1902','8000000.00','8000000.00','8000000.00','1737565920','1737566040','500000','1','fixation','ratio','5.00','ratio','10.00','0.00','120','300','0','0','0.00','0','0','0','0','1','0','0','5','1735541633','0');
INSERT INTO `on_auction` VALUES ('41','42','0','4','','0','0','0.00','0.00','3','0','約翰·弗雷德里克·肯塞特 1816','2200000.00','2200000.00','2200000.00','1737566100','1737566220','30000','1','fixation','ratio','5.00','ratio','10.00','0.00','120','300','0','0','0.00','0','0','0','0','3','0','0','6','1735542204','0');
INSERT INTO `on_auction` VALUES ('42','43','0','4','','0','0','0.00','0.00','3','0','馬丁·約翰遜·海德（1819-1904）','7000000.00','7000000.00','7000000.00','1737566280','1737566400','300000','1','fixation','ratio','5.00','ratio','10.00','0.00','120','300','0','0','0.00','0','0','0','0','1','0','0','7','1735542604','0');
INSERT INTO `on_auction` VALUES ('43','44','0','0','','0','0','0.00','0.00','0','0','清乾隆 琺瑯彩贲巴壺','3000000.00','3000000.00','3000000.00','1735552440','1735811400','300000','1','fixation','ratio','5.00','ratio','10.00','50000.00','120','300','22','0','0.00','1','0','0','0','148','0','2','0','1735606081','0');
INSERT INTO `on_auction` VALUES ('44','45','0','0','','0','0','0.00','0.00','0','0','清康熙 撇口瓶','1200000.00','1200000.00','1200000.00','1735553220','1735783500','50000','1','fixation','ratio','5.00','ratio','10.00','50000.00','120','300','0','0','0.00','0','3','0','0','90','0','0','0','1735606095','0');


# 数据库表：on_auction_agency 数据信息
INSERT INTO `on_auction_agency` VALUES ('21','9','6300000','1734467709','1');


# 数据库表：on_auction_pledge 数据信息


# 数据库表：on_auction_record 数据信息
INSERT INTO `on_auction_record` VALUES ('23','9','1734467611','0.00','7200000.00','手动');
INSERT INTO `on_auction_record` VALUES ('4','9','1734467660','0.00','2800000.00','手动');
INSERT INTO `on_auction_record` VALUES ('3','9','1734467684','0.00','4800000.00','手动');
INSERT INTO `on_auction_record` VALUES ('21','9','1734467709','500000.00','5300000.00','手动');
INSERT INTO `on_auction_record` VALUES ('27','12','1734857959','0.00','5500000.00','手动');
INSERT INTO `on_auction_record` VALUES ('23','12','1734857979','500000.00','7700000.00','手动');
INSERT INTO `on_auction_record` VALUES ('21','12','1734858001','500000.00','5800000.00','手动');
INSERT INTO `on_auction_record` VALUES ('21','9','1734858001','500000.00','6300000.00','代理');
INSERT INTO `on_auction_record` VALUES ('28','22','1735013879','0.00','800000.00','手动');
INSERT INTO `on_auction_record` VALUES ('29','22','1735022081','0.00','5000000.00','手动');
INSERT INTO `on_auction_record` VALUES ('29','12','1735022936','300000.00','5300000.00','手动');
INSERT INTO `on_auction_record` VALUES ('29','9','1735098621','300000.00','5600000.00','手动');
INSERT INTO `on_auction_record` VALUES ('28','12','1735100789','240000.00','1040000.00','手动');
INSERT INTO `on_auction_record` VALUES ('29','12','1735100825','900000.00','6500000.00','手动');
INSERT INTO `on_auction_record` VALUES ('29','9','1735104523','300000.00','6800000.00','手动');
INSERT INTO `on_auction_record` VALUES ('28','9','1735104704','60000.00','1100000.00','手动');
INSERT INTO `on_auction_record` VALUES ('28','12','1735188677','30000.00','1130000.00','手动');
INSERT INTO `on_auction_record` VALUES ('29','12','1735199963','300000.00','7100000.00','手动');
INSERT INTO `on_auction_record` VALUES ('30','12','1735289804','0.00','28000000.00','手动');
INSERT INTO `on_auction_record` VALUES ('30','26','1735289890','500000.00','28500000.00','手动');
INSERT INTO `on_auction_record` VALUES ('2','26','1735289931','0.00','6300000.00','手动');
INSERT INTO `on_auction_record` VALUES ('27','26','1735320464','500000.00','6000000.00','手动');
INSERT INTO `on_auction_record` VALUES ('20','26','1735320569','0.00','1600000.00','手动');
INSERT INTO `on_auction_record` VALUES ('30','28','1735323055','500000.00','29000000.00','手动');
INSERT INTO `on_auction_record` VALUES ('34','26','1735523982','0.00','3500000.00','手动');
INSERT INTO `on_auction_record` VALUES ('35','12','1735524109','0.00','3200000.00','手动');
INSERT INTO `on_auction_record` VALUES ('35','9','1735544050','300000.00','3500000.00','手动');
INSERT INTO `on_auction_record` VALUES ('34','10','1735544531','600000.00','4100000.00','手动');
INSERT INTO `on_auction_record` VALUES ('34','36','1735604764','300000.00','4400000.00','手动');
INSERT INTO `on_auction_record` VALUES ('35','37','1735605366','300000.00','3800000.00','手动');
INSERT INTO `on_auction_record` VALUES ('7','38','1735606325','0.00','6800000.00','手动');
INSERT INTO `on_auction_record` VALUES ('35','38','1735618173','300000.00','4100000.00','手动');
INSERT INTO `on_auction_record` VALUES ('43','22','1735715811','0.00','3000000.00','手动');


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
INSERT INTO `on_deliver_address` VALUES ('1','17','四川省','成都市','青羊区','草堂铭城一栋','610072','四川省成都市青羊','13111899216','','1');
INSERT INTO `on_deliver_address` VALUES ('2','12','北京','北京市','东城区','故宮附件','854152','admin','18852545214','','1');
INSERT INTO `on_deliver_address` VALUES ('3','31','浙江省','杭州市','余杭区','乔司镇乔司街道良熟新苑7栋3单元202','311100','陈搏函','18258186779','','1');
INSERT INTO `on_deliver_address` VALUES ('4','32','陕西省','宝鸡市','渭滨区','姜谭路符家村一组','123564','王志翔','15399177311','','1');
INSERT INTO `on_deliver_address` VALUES ('5','35','黑龙江省','大庆市','林甸县','西市场2314铁锅炖','150000','杨春芝','18346647501','','1');


# 数据库表：on_feedback 数据信息


# 数据库表：on_goods 数据信息
INSERT INTO `on_goods` VALUES ('34','3','成年同幼年異特龍','','','成年同幼年異特龍
美國懷俄明州
來自懷俄明州卡本县梅迪辛弓嘅Meilyn埰石場，晚侏羅世（約157-1.45億年前）。 與黑色骨骼化石一起保存嘅異特龍標本：成年由大約143個骨骼元素化石組成，幼年由大約135個骨骼化石元素組成; 兩者都使用額外嘅鑄造、雕刻同3D打印材料，安裝喺定製框架上。
71 5 歳 8 x 220 1/2 x 102 3 歳 8英寸 （182 x 560 x 260厘米。
60 1/4 x 136 1/4 x 33 1⁄8英寸 （153 x 346 x 84厘米。','73000000.00','<p>成蟲：1994-1995年發現並發掘</p><p>幼魚：2002年被發現並發掘</p><p>埰石場同標本均由現任所有者於2008年收購</p><p>2017-2018年嘅其他發掘活動</p><p>2022年喺德國進行最後嘅準備工作同鑲嵌</p>','','九龙','','Goods/20241228/676efc2cda577.jpg|Goods/20241228/676efc31230e4.jpg|Goods/20241228/676efc3539289.jpg|Goods/20241228/676efc39396e0.jpg|Goods/20241228/676efc3eef80c.jpg|Goods/20241228/676efc43466f4.jpg|Goods/20241228/676efc4806303.jpg|Goods/20241228/676efc4c5a1eb.jpg|Goods/20241228/676efc507b794.jpg|Goods/20241228/676efc56106d1.jpg|Goods/20241228/676efc5b2bdd4.jpg','','1735326835','1735326961','5','');
INSERT INTO `on_goods` VALUES ('31','6','丁雲鵬《佛像圖》','','','尺寸：108x65cm
款识：乾隆、嘉庆皇帝印章。','28000000.00','<p>丁雲鵬，生於明嘉靖二十六年（1547年），卒於天啟年間（1628年），字南羽，號聖華居士，是晚明時期著名的畫家，尤以佛像畫見長。他的佛像圖不僅技藝精湛，而且蘊含深厚的文化內涵，是中國古代佛教藝術寶庫中的瑰寶。</p>','','九龙','','Goods/20241225/676b7bf529d9a.jpg|Goods/20241225/676b7bfbdf206.jpg|Goods/20241225/676b7c0be73dc.jpg|Goods/20241225/676b7c124fe61.jpg','','1735097365','','5','');
INSERT INTO `on_goods` VALUES ('32','1','清乾隆 孔雀藍地描金折枝花卉紋雙耳瓶','','','題識：（大清乾隆年制）款
尺寸：20.3cm
估價：HKD 8,000,000-12,000,000','10000000.00','<p>乾隆年間，盛行以瓷胎繪彩仿掐絲珐瑯器，然鮮見如本瓶細膩精緻、色彩斑駁。縱使其器型、色調、纏枝花卉及仿古夔龍，均常見於乾隆御瓷，設計相近之器寡，足見此瓶之珍。</p>','','九龙','','Goods/20241227/676e7171bee83.jpg','','1735291251','1735606138','5','');
INSERT INTO `on_goods` VALUES ('33','3','STEGOSAURUS（劍龍）','','','劍龍
美國懷俄明州
來自懷俄明州卡本县梅迪辛弓嘅Meilyn埰石場，晚侏羅世（約157-1.45億年前）。 劍龍（ Stegosaurus sp.）嘅標本與黑色嘅骨骼化石一起保存; 大約144個Fossil BONE元素，帶有額外嘅鑄造、雕刻同3D打印材料，安裝喺定製框架上。
98 x 213 3 歳 8 x 81 1 歳 8英寸 （249 x 542 x 206厘米。','45000000.00','<p>2002年首次發掘</p><p>埰石場同標本由現任所有者於2008年收購</p><p>2017-2018年嘅其他發掘活動</p><p>2023年喺德國進行最後嘅準備工作同鑲嵌</p><p><br/></p>','','九龙','','Goods/20241228/676ef5bad9a73.jpg|Goods/20241228/676ef5bf3ffaa.jpg|Goods/20241228/676ef5c32a8fc.jpg|Goods/20241228/676ef5c76f351.jpg|Goods/20241228/676ef5cc995bb.jpg|Goods/20241228/676ef5d1a98d2.jpg|Goods/20241228/676ef5d645333.jpg|Goods/20241228/676ef5daca3e7.jpg','','1735325444','1735326231','5','');
INSERT INTO `on_goods` VALUES ('35','6','清康熙青花人物雙耳瓶','','','清康熙青花人物雙耳瓶是清朝康熙時期（1662-1722年）所製作的瓷器，康熙時期是中國瓷器發展的高峰之一，特別是在青花瓷的製作上，達到了一個新的藝術高度。青花瓷以其典雅的藍色圖案與純淨的白瓷底釉相結合，受到當時宮廷和民間的喜愛。','3500000.00','<p>該清康熙青花人物雙耳瓶採用的是高質量的瓷土，釉面細膩，青花色澤明亮，圖案線條流暢。人物圖案精細，尤其是在衣飾、面部表情和動態上的描繪非常生動，展示了康熙青花瓷的典型風格。康熙青花人物雙耳瓶作為清代重要的瓷器類型之一，在市場上具有較高的收藏和投資價值。</p>','','九龙','','Goods/20241229/6770ed048485f.jpg|Goods/20241229/6770ed10e1493.jpg|Goods/20241229/6770ed1e2ca80.jpg|Goods/20241229/6770ed2aecbfc.jpg','','1735453999','1735454128','5','');
INSERT INTO `on_goods` VALUES ('3','6',' 清雍正 青花纏枝花卉紋蒜頭大瓶','','','瓶蒜头，束颈，圆折肩，弧腹渐收，圈足。通身以青花装饰九层纹饰，口沿饰缠枝菊花纹，颈部饰蕉叶纹上下相对，每组蕉叶纹由长短蕉叶相互重迭，极具层次感，颈部下部绘折枝莲及回纹。瓶肩部则饰如意花卉纹、缠枝莲纹及折枝花，肩部下方饰方形蕉叶纹一周。瓶腹部满饰缠枝花，近足处饰海水纹。底青花书「大清雍正年制」篆书款。','6300000.00','<p><span style="font-family: &quot;Microsoft Yahei&quot;; font-size: 14px; text-wrap-mode: wrap; background-color: rgb(255, 255, 255);">坂井米夫（1900－1978）先生为早年日本倍受尊重的驻外记者之一。出身于日本佐贺县，坂井米夫先后在关西学院大学及明治学院大学进修文学专业，之后于日本国际情报社担任映画及演技编辑。1926年搬至美国旧金山后他开始为旧金山、洛杉矶及纽约的日文报纸撰写文章。1931年他成为朝日新闻社的驻外记者，并负责对1937年的西班牙内战做专题报导，之后两年又跋涉土耳其、伊朗、伊拉克、埃及、德国、巴勒斯坦及中国负责报导。第二次世界大战期间，美国政府对在美日裔美国人进行扣留和转移，坂井米夫因此被送入位于格兰纳达的日裔美国人集中营，并在避难期间在科罗拉多州立大学教授日文并持续至1948年。1949年后坂井米夫及其家人迁至华盛顿，并重拾其记者生涯，在日本各大报社在杜鲁门、艾森豪威尔威尔、肯尼迪及约翰逊四任总统执政下的驻外记者，直至他1978年逝世。坂井米夫的职业生涯充满传奇色彩，他曾在自己主办的「美国新闻」广播节目中采访肯尼迪、约翰逊总统及著名作家海明威，亦对约翰逊及尼克松早期执政下的越南战争进行过专题报导。坂井米夫在1956年正式成为美国公民。</span><br style="padding: 0px; margin: 0px; box-sizing: border-box; font-family: &quot;Microsoft Yahei&quot;; font-size: 14px; text-wrap-mode: wrap; background-color: rgb(255, 255, 255);"/><span style="font-family: &quot;Microsoft Yahei&quot;; font-size: 14px; text-wrap-mode: wrap; background-color: rgb(255, 255, 255);">以上部分研究资料由加州大学洛杉矶分校Charles E. Young研究图书馆特殊文献部提供</span><br style="padding: 0px; margin: 0px; box-sizing: border-box; font-family: &quot;Microsoft Yahei&quot;; font-size: 14px; text-wrap-mode: wrap; background-color: rgb(255, 255, 255);"/><span style="font-family: &quot;Microsoft Yahei&quot;; font-size: 14px; text-wrap-mode: wrap; background-color: rgb(255, 255, 255);">此种蒜头瓶为雍正官窑新创器形，传世稀少，弥足珍贵。迄今为止相似之例仅见于清宫旧藏一件雍正蒜头瓶，现藏于北京故宫博物院，见《故宫博物院藏文物珍品全集：青花釉里红（下）》，香港，1993年，页107，图93。耿宝昌先生亦在其著作中提及雍正官窑所创烧的这种特殊器形，见耿宝昌，《明清瓷器鉴定》，香港，1993年，页236，图12。乾隆一朝亦对此器形有所仿制，可见收藏于北京故宫博物院一例，见同著录，页135，图121。</span><br style="padding: 0px; margin: 0px; box-sizing: border-box; font-family: &quot;Microsoft Yahei&quot;; font-size: 14px; text-wrap-mode: wrap; background-color: rgb(255, 255, 255);"/><span style="font-family: &quot;Microsoft Yahei&quot;; font-size: 14px; text-wrap-mode: wrap; background-color: rgb(255, 255, 255);">雍正一朝虽仅十三年，但制瓷工艺却不断推陈出新，使其得到空前发展。雍正朝青花瓷器又以仿制明代永乐、宣德最为精美。此蒜头瓶形制亦可能是受到明代永宣时期官窑所烧造的不同瓷器而启发，再加之用青料钟笔点染以刻意仿制出永宣青花瓷中的铁锈斑点，从而创烧出如此气势恢宏却又清新淡雅的仿古佳器。</span></p>','','北京市','','Goods/20241214/675c5b8e68c66.jpg','','1734106022','1734107701','4','');
INSERT INTO `on_goods` VALUES ('4','6','何海霞 万山红遍','','','款识：万山红遍，层林尽染，一片北国风光。秋色景象，为山河大地而自豪。一九九二年■■■■■■■■■，八十四叟，海霞老人
钤印：海霞书画（白文）、何（朱文）','4800000.00','<p><span style="font-family: &quot;Microsoft Yahei&quot;; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">简介：何海霞，原名何瀛，满族，北京人。擅长中国画。1934年拜张大千为师，随同入川作画。1936年曾与齐白石、于非闇、张大千举办四人作品联展。1956年后历任西北美术家协会画师、陕西省国画院副院长、中国画研究院专业画家。</span></p>','','北京市','','Goods/20241216/675f07e9ec541.jpg|Goods/20241216/675f07ed51deb.jpg|Goods/20241216/675f07f77f489.jpg','','1734281012','1734281220','4','');
INSERT INTO `on_goods` VALUES ('5','6','徐悲鸿 奔马 立轴','','','尺寸：87x47cm
款识：卅年冬至黎陇巴雨中即奉，启庆先生正，悲鸿写
钤印：悲鸿','2800000.00','<p>我是商品详情的默认值，每当发布商品时候我都会出现。你可以把我当成商品详情的模板，可以修改我达到快速发布的效果。</p><p>通常对于有网站商品有共同的商品参数或者内容的时候设置我可以快速发布商品！</p><p>如果不需要我，可以在登陆后台【商品管理】-》【扩展字段管理】中选择商品详情进行编辑！<br/></p>','','北京市','','Goods/20241216/675f09a8a44a4.jpg|Goods/20241216/675f09b18dbf6.jpg','','1734281666','','4','');
INSERT INTO `on_goods` VALUES ('6','6','明宣德青花暗花【海水游龙】图高足杯','','','《大明宣德年制》
器形优美，深圆的侧面与外翻的边缘齐平，支在高大的空心足上，底部略微外扬，内部中央以釉下青花刻制，双环内有六字年款，侧面以暗花（“隐性装饰”）装饰一对大步蟠龙，均以逆时针方向移动，外部以深钴蓝色绘有一对蜿蜒的五爪龙相互追逐，一条龙回头，另一条龙头向前，上方是一片锯齿状的岩石，背景是淡蓝色的汹涌海浪，茎部同样装饰有五块锯齿状岩石，从底部升起，背景是淡蓝色的海浪
','12000000.00','<p>我是商品详情的默认值，每当发布商品时候我都会出现。你可以把我当成商品详情的模板，可以修改我达到快速发布的效果。</p><p>通常对于有网站商品有共同的商品参数或者内容的时候设置我可以快速发布商品！</p><p>如果不需要我，可以在登陆后台【商品管理】-》【扩展字段管理】中选择商品详情进行编辑！<br/></p>','','北京市','','Goods/20241216/675f151b7815a.jpg','','1734284579','','4','');
INSERT INTO `on_goods` VALUES ('7','1','清乾隆 御制洋彩紫红锦地乾坤交泰转旋瓶','','','创作年代：清乾隆
尺寸：高31厘米
款底：大清乾隆年制

梨形，喇叭状颈部，以圆柱形内瓶为轴旋转，球状身体沿一排互锁的如意头切割而成，并穿孔八卦，鲜红宝石色地面，精细地刻有密集的羽毛花纹，保留了风格化的莲花，莲花生长在卷曲的叶子上，长出额外的小花和花蕾，展开的脚被红蓝相间的如意带环绕，在鲜艳的柠檬黄色地面上，也有类似的刻纹，收紧的颈部在淡蓝和黄色的地面上饰有相互连接的如意和花卉图案，两侧有两个扁平的卷轴形把手，涂有铁红色和镀金珐琅，喇叭形口沿在淡绿色的地面上饰有叶子枝条，通过狭窄的开口可以看到内瓶','10800000.00','<p>我是商品详情的默认值，每当发布商品时候我都会出现。你可以把我当成商品详情的模板，可以修改我达到快速发布的效果。</p><p>通常对于有网站商品有共同的商品参数或者内容的时候设置我可以快速发布商品！</p><p>如果不需要我，可以在登陆后台【商品管理】-》【扩展字段管理】中选择商品详情进行编辑！<br/></p>','','北京市','','Goods/20241216/675f207286015.jpg|Goods/20241216/675f2077cb092.jpg|Goods/20241216/675f207c07e59.jpg|Goods/20241216/675f20813ed69.jpg|Goods/20241216/675f2086e73d2.jpg|Goods/20241216/675f208c1f386.jpg','','1734287503','1735553755','1','');
INSERT INTO `on_goods` VALUES ('8','1','清乾隆·松石綠錦地浮雕金彩百壽琮式瓶','','','金彩陽文「大清乾隆年製」篆書款，高30.2 cm','6800000.00','<p><span style="color: rgb(68, 68, 68); font-family: TimesNewRoman, &quot;Times New Roman&quot;, 微軟正黑體, sans-serif, &quot;Apple LiGothic Medium&quot;, &quot;Heiti TC&quot;, &quot;LiHei Pro&quot;; font-size: 18px; word-spacing: 0.018px; text-wrap: wrap; background-color: rgb(255, 255, 255);">清乾隆《松石綠錦地浮雕金彩百壽琮式瓶》，器形仿自玉琮，方形長柱式而有底。通體施松石釉為地，頸部飾有仿古龍紋，其中有團壽紋，方足之上有另一組仿古龍紋，並有「C」型卷花、雷紋各一圈。通體以金彩描邊，釉彩厚重。底部帶金彩「大清乾隆年製」陽文篆書款。</span><br style="box-sizing: inherit; font-family: TimesNewRoman, &quot;Times New Roman&quot;, 微軟正黑體, sans-serif, &quot;Apple LiGothic Medium&quot;, &quot;Heiti TC&quot;, &quot;LiHei Pro&quot;; color: rgb(68, 68, 68); font-size: 18px; word-spacing: 0.018px; text-wrap: wrap; background-color: rgb(255, 255, 255);"/><br style="box-sizing: inherit; font-family: TimesNewRoman, &quot;Times New Roman&quot;, 微軟正黑體, sans-serif, &quot;Apple LiGothic Medium&quot;, &quot;Heiti TC&quot;, &quot;LiHei Pro&quot;; color: rgb(68, 68, 68); font-size: 18px; word-spacing: 0.018px; text-wrap: wrap; background-color: rgb(255, 255, 255);"/><span style="color: rgb(68, 68, 68); font-family: TimesNewRoman, &quot;Times New Roman&quot;, 微軟正黑體, sans-serif, &quot;Apple LiGothic Medium&quot;, &quot;Heiti TC&quot;, &quot;LiHei Pro&quot;; font-size: 18px; word-spacing: 0.018px; text-wrap: wrap; background-color: rgb(255, 255, 255);">此瓶造型規整大氣，雕工紋飾繁縟，施金華麗，盡顯皇家富麗、雍容華貴之威嚴。瓶身四面各飾有24個形態不同的「壽」字，加上頸部一圈四組團壽紋飾，總共有一百個「壽」字，象徵「百壽」，有長命百歲、吉祥如意之寓意。</span></p>','','九龙','','Goods/20241216/675f2b5e68ca3.jpg','','1734290305','1734290513','5','');
INSERT INTO `on_goods` VALUES ('9','1','清乾隆·淡綠彩浮雕礬紅金彩萬壽八方瓶','','','金彩陽文「大清乾隆年製」篆書款，高26.7 cm
清乾隆《淡綠彩浮雕礬紅金彩萬壽八方瓶》通體施淡綠釉，外壁肩、腹每面開光，礬紅金彩浮雕飾萬壽、菊花及卷草花紋，對稱工整，邊角處塗厚層金彩。底部帶金彩「大清乾隆年製」陽文篆書款。外壁所繪之變體「壽」字，結合「卍」字符，寓意萬壽無疆。因此，此尊或是專為「萬壽節」，即皇帝生辰壽慶，或祝賀皇太后、皇后生辰而進貢之禮。而周邊點綴之金彩菊花紋，亦稱萬壽菊，更添富貴長壽之意。','3500000.00','','','九龙','','Goods/20241216/675f2cf0a6872.jpg','','1734290682','1734468346','5','');
INSERT INTO `on_goods` VALUES ('25','8','十六世纪 铜鎏金密集文殊金刚像','','','此造像衣饰珠宝呈现出十六世纪藏中造像之典型特征，宝冠之叶造型纤巧，边缘卷叶繁复弯转而层次明晰。密集金刚宝冠下缘带有一圈嵌绿松石之花形装饰，与鲁宾艺术博物馆之藏中金刚萨埵唐卡一致，颇为罕见。此外，嵌四枚绿松石之臂钏亦为十六世纪藏中唐卡之常见细节。','3500000.00','','','九龙','','Goods/20241218/6761e5e080403.jpg|Goods/20241218/6761e5e6e703d.jpg|Goods/20241218/6761e5ecb0cd2.jpg','','1734469106','1734469272','5','');
INSERT INTO `on_goods` VALUES ('10','1','清雍正 爐鈞釉仿古尊','','','「大清雍正年製」篆書印款，高17.5 cm
爐鈞釉為雍正時期景德鎮御窯廠新研發的仿鈞低溫釉，於唐英督陶下燒造，仿宋代鈞窯窯變釉色而來，清雍正《爐鈞釉仿古尊》此尊器型端莊典雅，為仿效宋代渣斗式尊而成，又保留了上古青銅觚敞沿寬闊之元素，線條簡潔流暢；釉色藍中帶綠，和諧融合，呈現如孔雀羽毛般之瑰麗色彩。

除上述雍正、乾隆署官款御器外，高福全亦喜康雍乾朝堂款器，包括「彩華堂」（18世紀《青花釉裡紅八仙人物圖碗》、18世紀《鬥彩梵文花卉紋碗一對》）、「浩然堂」（清18世紀早期《孔雀綠釉八角仿古尊》）及「慶宜','2200000.00','','','九龙','','Goods/20241216/675f2de598efb.jpg','','1734290925','1734468335','5','');
INSERT INTO `on_goods` VALUES ('11','1','清乾隆 仿官釉六棱貫耳瓶','','','青花「大清乾隆年製」篆書款，高30 cm
清乾隆《仿官釉六棱貫耳瓶》通體施灰青釉，釉質肥潤，釉面光亮並有冰裂紋、大開片，整體造型古樸典雅，無論從形式或風格上，本品都甚為罕見。','2000000.00','','','九龙','','Goods/20241216/675f2e9eb0989.jpg','','1734291107','1734468314','5','');
INSERT INTO `on_goods` VALUES ('26','8','十四世纪 铜鎏金佛陀像','','','佛陀像之背光及蓮台皆完好，極為難得。','1600000.00','<p>佛祖持缽，身側為文殊菩薩及觀音菩薩，象徵智慧與慈悲，其身前之金剛杵則為佛陀於菩提迦耶證道之金剛座之表徵。背光上為大鵬金翅鳥振翅而飛，那伽託其兩側，下方為繁茂枝葉與摩羯魚所構成的華美曲線。下方蓮台上可見二獅守護著佛法僧三寶。佛陀身形圓潤，遵循十三至十四世紀之間紐瓦爾人之傳統美學，此風格亦為卡薩馬拉王朝造像所借鑒，例如，寶座之稻粒圖形即常見於卡薩馬拉造像，其束腰底座兩側亦有以點構成的稻粒圖案。</p>','','九龙','','Goods/20241218/6761e72ddc47b.jpg|Goods/20241218/6761e733c8847.jpg','','1734469431','1734535185','5','');
INSERT INTO `on_goods` VALUES ('12','3','17世紀 黃花梨軸門圓角櫃','','','高181 × 闊113 × 深55 cm','800000.00','<p>17世紀《黃花梨軸門圓角櫃》此櫃上斂下舒，比例勻稱，用料色澤光潤，通體光素，線條流暢，整體典雅大氣。圓角櫃以小型和中型為主，如本品般尺寸碩大的則較為少見。</p>','','九龙','','Goods/20241216/675f2f090509e.jpg','','1734291223','1734468296','5','');
INSERT INTO `on_goods` VALUES ('13','4','傅抱石 苦瓜和尚詩意圖','','','設色紙本 立軸，145×86.5 cm， 1960年','5000000.00','<p>傅抱石1960年代山水巨製《苦瓜和尚詩意圖》，此作氣勢磅礴，畫幅題詩「林濤似發雷霆怒，雲氣常令日月昏」乃石濤晚年題畫詩句。傅抱石景仰石濤，此作用巨幅尺寸，不寫人物，不設亭榭，天地間全為山川植被水流充盈，前景大筆揮灑，筆勢勁疾深重，山得其崢嶸奇崛，松得其風骨剛健；遠景則鬆散筆觸，淡墨漫寫，略施薄彩，得山嵐抹黛之風致，為其偶像詩句作完美闡釋。</p>','','九龙','','Goods/20241216/675f2fefcb0f5.jpg','','1734291445','1734468270','5','');
INSERT INTO `on_goods` VALUES ('27','8','十三世纪 铜鎏金释迦牟尼像','','','佛像古雅莊嚴，右手結觸地印，儀度壯觀。佛陀深入禪觀，銅像的腳趾的形態便自然鬆弛，稍稍彎曲，搭在大腿內側，呈現出久坐之態。','8000000.00','<p>此銅像出自於十三至十四世紀時期統治尼泊爾及西藏西部之卡薩馬拉王朝。卡薩馬拉藝術融合其周邊多種藝術傳統，尤其受加德滿都之紐瓦爾人影響，銅像俊美雄健之身形及寬闊舒展之面容皆為尼泊爾典型特徵。如此壯觀的存世卡薩馬拉佛陀像屈指可數，魯賓藝術博物館所藏一尊卡薩馬拉風格之造像與其具有相同之核心元素，如長袍邊緣之稻粒圖案以及手指關節。熊文彬主編之《東去西來》一書中著錄一尊刻有銘文之卡薩馬拉釋迦牟尼造像，極為難得，其面容以及長袍邊緣之細節皆可與本尊進行比較。</p>','','九龙','','Goods/20241218/6761e8283ab7f.jpg|Goods/20241218/6761e82db4627.jpg','','1734469681','1734535125','5','');
INSERT INTO `on_goods` VALUES ('14','4','劉野 雷鋒叔叔','','','壓克力 畫布，45.3×45.3 cm，2003年','4000000.00','<p>劉野繪於2013年的《雷鋒叔叔》以一位中國解放軍雷鋒為主角，他在1962年因公殉職時年僅21歲，但形象與精神則因隔年《中國青年》雜誌在專題中刊登毛澤東親題「向雷鋒同志學習」，自此被形塑為黨員的模範。劉野在構圖上與1963年的雜誌封面相似，保留其身著軍裝、面露燦笑的形象，卻將之轉化為自身創作極具標誌性的男孩模樣，而在用色上的紅、藍、黃使用也反映他對蒙德里安（Piet Cornelies Mondrian, 1872-1944）的推崇。</p>','','九龙','','Goods/20241216/675f30a90e0b8.jpg','','1734291646','1734468250','5','');
INSERT INTO `on_goods` VALUES ('15','4','曾梵志 《三年級一班 第6號》','','','油彩 畫布，48×38 cm，1996年','1200000.00','<p>在本季上拍《三年級一班 第6號》與《三年級一班 第20號》屬於曾梵志的「三年級一班系列」，為其創作初期的重要指標，描繪小學階段的32位同學不僅反映個人的生命經驗也投射著中國的過去。紅領巾代表著集體主義的象徵，加上戴著面具而掩去個人辨識度的頭像也充滿諷刺意味的隱喻，凸顯現實與內心的兩面性。</p>','','九龙','','Goods/20241216/675f3145e5a0d.jpg','','1734291792','1734468219','5','');
INSERT INTO `on_goods` VALUES ('28','6','大清雍正六年时宪历','','','清朝自順治元年（1644）至1910年，皆採用西人湯若望（Johann Adam Schall von Bell）重訂之《西洋新法曆書》，編製頒布「時憲歷＂，至乾隆元年（1736）避「弘曆」諱，改名「時憲［。','5500000.00','<p>此冊《大清雍正六年時憲歷》為朱墨精寫本，乃是欽天監上呈皇帝禦覽之用，是為禦覽時憲歷，皇帝確認無誤，乃頒布臣民。以此曆尺寸當為黃綾袖珍本，黃綾函套、黃綾封面，原裝原函特別罕見，民間稱之為「皇歷」者，即是此書。皇帝受命於天，曆書彰顯皇帝代天頒布正朔之職權，旨在授民以時，使民順時耕作和休息。</p>','','九龙','','Goods/20241218/6761eb40652c4.jpg|Goods/20241218/6761eb45ac24c.jpg|Goods/20241218/6761eb4b224a5.jpg|Goods/20241218/6761eb50d8e33.jpg|Goods/20241218/6761eb59f41d4.jpg','','1734470495','1734535097','5','');
INSERT INTO `on_goods` VALUES ('20','8','銅錯銀鎏金鎏銀持拂塵者像','','','犍陀羅4至6世纪《銅錯銀鎏金鎏銀持拂塵者像》，高8.3公分','2000000.00','<p><span style="color: rgb(68, 68, 68); font-family: TimesNewRoman, &quot;Times New Roman&quot;, 微軟正黑體, sans-serif, &quot;Apple LiGothic Medium&quot;, &quot;Heiti TC&quot;, &quot;LiHei Pro&quot;; font-size: 18px; word-spacing: 0.018px; text-wrap: wrap; background-color: rgb(255, 255, 255);">此尊持拂塵者像小巧精美，出自顯赫「菩薩道收藏」，並曾長期借展於紐約魯賓藝術博物館（RubinMuseum of Art）及牛津阿什莫林博物館（Ashmolean Museum），乃橫跨中亞與北印度的鎏金銅像傳統與絲綢之路的最經典的例證之一。此像體態無比生動柔和，亦可見對西方古典形體之偏愛與笈多王朝風格在此交織。其面孔微揚，神采奕奕。此造像金屬桿的位置較低，與斯瓦特銅像常見在肩骨之間位置有所不同，年輕俊美的男子所持拂塵，乃印度皇室的神聖象徵。題材方面，則常見於犍陀羅，羅馬式的髮型又為陸上與海上絲綢之路之見證。</span></p>','','九龙','','Goods/20241216/675f41b350446.jpg','','1734295695','1734296019','5','');
INSERT INTO `on_goods` VALUES ('36','6','元 青花蒙括將軍玉壺春瓶','','','蒙恬将军青花玉壶春瓶年代:元代瓷工
高度:30 cm口径:8.4cm  撇口，细长颈，圆腹且下
垂，圈足，形体秀美。饰釉下青花。内口沿绘如意头
圈足为卷草纹，腹部主纹为人物故事。人物故事:中
间头戴凤尾高冠、身着甲袍的武将正是蒙恬。后立武
士双手握书有"蒙恬将军"的大旗。前一武士抓一跪着
俘虏，另一武士似作汇报，人物间点缀以怪石、篱笆、
芭蕉、竹叶、花草等,画面繁而不乱。','3200000.00','<p>元青花人物故事多取材于元杂剧。人物的服
饰如披肩、皮靴以及所用道具都具有元代风格。武士所
举的旗子即《元史。舆服》所载的火焰纹牙旗，此旗与
元巽申《大驾卤薄图》上的牙旗相同，属典型的元代风
格。根据鉴定结果，元青花瓷器在市场上一直备受青睐
，特别是那些工艺复杂、装饰精美的瓷器，其收藏价值
更高。蒙恬将军青花玉壶春瓶作为一种典型的元青花瓷
器,其市场价值也相对较高。</p>','香港特别行政区','九龙','黄大仙区','Goods/20241230/67728780d1aae.jpg|Goods/20241230/6772878711d70.jpg|Goods/20241230/6772878cbede2.jpg','','1735472779','1735559061','5','');
INSERT INTO `on_goods` VALUES ('37','3','弗雷德里克·伦赛特（1861-1909）','','','弗雷德里克·倫斯敦（1861-1909）《山地人》，底座上刻有“版權歸弗雷德里克·倫斯敦所有”（在底座上），底座周圍刻有鑄造廠標記“羅馬青銅製品紐約”（沿著底座），底座下刻有“No. 27”（在底座下），銅製，表面呈棕黑色，高28 ¼英寸（71.8厘米）。大約於1903年製作；至1916年鑄造完成。','2100000.00','<p>1903年，作為弗雷德里克·倫梅特（Frederic Remington）的第九款銅制模型，《山地人》（The Mountain Man）以青銅鑄造而成，它永恆地記錄了西部邊疆的生活場景，一名捕獸者和他的馬匹在崎嶇的山路上急速下降。為了準確描繪馬匹的運動，藝術家依賴他收集的軍事軍官照片以及一個活生生的模特：他的朋友和軍人粗野騎士，曾在西班牙-美國戰爭中服役的萊昂納德·伍德將軍（General Leonard Wood）。因為倫梅特希望強調山路的高聳陡峭，所以《山地人》比他的其他青銅作品要高出幾英寸。</p>','','九龙','','Goods/20241230/67723a63ad81c.jpg|Goods/20241230/67723a6b0e062.jpg|Goods/20241230/67723a72dd4e9.jpg|Goods/20241230/67723a7f649a0.jpg|Goods/20241230/67723a8925c9b.jpg|Goods/20241230/67723a921e84a.jpg|Goods/20241230/67723a9a2a453.jpg|Goods/20241230/67723aa1c0fa2.jpg','','1735539365','1735539582','5','');
INSERT INTO `on_goods` VALUES ('30','6','清乾隆 珐瑯彩描金雙耳花卉紋瓶','','','清朝乾隆時期（1736年-1795年）製作的一種極具藝術價值的瓷器，屬於乾隆時期宮廷瓷器中的重要類型。乾隆時期是中國歷史上瓷器製作的高峰期之一，清代的宮廷瓷器在這一時期達到了藝術與工藝的頂峰。','5000000.00','<p>此瓶身上使用了珐瑯彩釉料，顏色包括紅、綠、藍、黃等，色澤明亮且饱滿，花卉圖案精細且富有層次感，常見牡丹、蓮花等象徵富貴與繁榮的傳統圖案，描金部分則是用細膩的金色勾勒出花卉和圖案的邊緣，造型優美且端莊，瓶口微敞，瓶身中部通常較寬，呈對稱狀，整體設計非常穩重且具有藝術感，有很高的收藏價值以及市場價值。</p>','','九龙','','Goods/20241223/67695fd8e3656.jpg|Goods/20241223/67695fe08143f.jpg|Goods/20241223/67695fe740730.jpg','','1734959082','1734960000','5','');
INSERT INTO `on_goods` VALUES ('21','5','巴拿马-太平洋纪念币','','','美國，PCGS 紀念五件套巴拿馬太平洋博覽會套裝，1915-S，附盒','1600000.00','<p>* 1915-S 半美元。設計師：正面為 Charles Barber；反面為 George Morgan。發行量：27,134 枚。 MS66+。這是關鍵日期經典紀念幣的絕妙原創範例。色調適中，為原始銀灰色，帶有一些深海藍色和古金色點綴；印記大膽，整體上幾乎沒有痕跡和磨損。 1914 年巴拿馬運河的開通代表著科技和政治上的里程碑。為紀念這項成就，也為慶祝舊金山在 1906 年地震後的重生，1915 年在舊金山金門公園 635 英畝的土地上舉辦了巴拿馬太平洋萬國博覽會。</p>','','九龙','','Goods/20241216/675f466ce1d5a.jpg|Goods/20241216/675f467332169.jpg|Goods/20241216/675f4678d013b.jpg','','1734297211','1734535364','5','');
INSERT INTO `on_goods` VALUES ('29','6','元代 青花龍紋玉壺春瓶','','','元代（1271-1368年）是中國瓷器史上極為重要的一個時期，尤其是在青花瓷器的製作上，元代達到了新的高度。元代青花瓷以其精緻的工藝和獨特的裝飾風格而著稱，尤其是龍紋系列作品，極具象徵意義。','800000.00','<p>此件元代青花龍紋玉壺春瓶的青花紋飾細膩生動，尤其是龍紋的刻畫十分精緻，龍身的蜿蜒姿態與雲紋的流暢相得益彰，展現出元代青花瓷器獨有的高貴氣息。瓶身的釉色均勻，光澤感十足，且細節豐富，紋理清晰，保存狀態極為完好。玉壺春瓶的獨特造型和龍紋裝飾更是將元代瓷器的工藝推向了一個新的高峰，具有非常高的藝術價值和歷史價值。</p>','','九龙','','Goods/20241223/6768f94d6d918.jpg|Goods/20241223/6768fcd681e30.jpg|Goods/20241223/6768fcddd1a20.jpg','','1734932816','1734960015','5','');
INSERT INTO `on_goods` VALUES ('22','6','清乾隆 禦製銅胎掐絲琺瑯雙人托長方形爐','','','清乾隆 御制铜胎掐丝珐琅双人托长方形炉 宽112cm×2','4800000.00','<p><span style="font-family: " microsoft="" font-size:="" text-wrap:="" background-color:="">長方形大箱體，板沿狀口沿，口沿外壁鏨刻鎏金迴紋一圈，長方形蓋高隆，一銅鎏金幼獅作鈕，幼獅四爪伏於蓋頂，回首仰望，其雙目圓瞪，豎尾處毛髮向下墜散，蓋壁及蓋頂獅鈕下方及左右對稱的兩個區域均以藍色琺瑯釉為地，並飾銅胎掐絲纏枝花卉紋，而其爐蓋餘部分則飾以鏤空纏枝雲龍紋。爐身亦以藍色琺瑯釉為地色，並以掐絲填彩飾纏枝蓮紋，兩側各一人呈半蹲狀背對香爐並以雙手托拖著爐底，兩托爐人面部、手掌及雙足飾鎏金，均頭戴螺旋狀尖帽，其身著中式官服以銅胎掐絲飾纏枝蓮紋，一人領口系一單節，另一人則以饕餮紋作領，兩人表情各異，雖都雙瞪圓目，但一人雙唇緊閉，表情嚴肅，另一人則留卷腮鬍，張口吐舌，氣宇軒昂。</span><br style="padding: 0px; margin: 0px; box-sizing: border-box; font-family: " microsoft="" font-size:="" text-wrap:="" background-color:=""/><span style="font-family: " microsoft="" font-size:="" text-wrap:="" background-color:="">香爐曾由托馬斯•弗穆爾-赫斯基思男爵（Sir Thomas Fermor-Hesketh，1881-1944）收藏。托馬斯男爵曾就讀於伊頓學院，畢業於劍橋大學三一學院，優越的教育造就了他後來在皇家軍校以及皇家陸軍的卓越成就。湯瑪斯深受叔父影響，對古董藝術品收藏有濃厚的興趣。他繼承叔父的伊斯頓莊園後，無疑同時也繼承了莊園中的稀世珍寶。</span></p>','','九龙','','Goods/20241216/675f485e2088c.jpg','','1734297696','1734535338','5','');
INSERT INTO `on_goods` VALUES ('23','6','晚明 黄花梨架子床','','','晚明 黃花梨架子床，204×209×146cm','3000000.00','','','','','Goods/20241216/675f49f00a079.jpg','','1734298100','1734535259','5','');
INSERT INTO `on_goods` VALUES ('24','6','乾隆帝 禦筆行書〈禦制澄懷堂詩〉七言聯','','','乾隆帝 禦筆行書〈禦制澄懷堂詩〉七言聯 （一對）
作者
乾隆帝 （1711～1799）
尺寸
182×34cm
款識：
四壁圖書鑑今古，一庭花木驗農桑。
鎢印：所寳惟賢、乾隆禦筆、奉三無私','7200000.00','<p>乾隆帝禦筆《禦製澄懷堂詩》七言聯</p><p>乾隆帝御筆對聯是其書法作品中一個獨特的門類，僅故宮博物院收藏此類作品就達近一千五百件，包括從四言聯至十七言聯共十三種形制。其中七言聯數量最多，佔各類對聯總量的六成以上。四言聯至七言聯多在宮殿內簷明間、暖閣等處張貼使用。此類通常是將禦筆墨跡托裱後，以貼落的方式直接粘貼在墻上，所以這類作品裱褙多有粘貼痕跡；九言聯及以上的對聯大多在宮殿內外簷明間或抱廈的金柱、望柱上使用，通常是將禦筆墨跡錒刻在木匾上，再懸掛起來展示。此類禦筆墨跡通常未曾托裱，以畫心形式保存。</p><p><br/></p>','','','','Goods/20241216/675f4baf4d697.jpg','','1734298545','1734535234','5','');
INSERT INTO `on_goods` VALUES ('38','3','弗雷德里克·伦赛特（1861-1909）','','','弗雷德里克·倫賽特（1861-1909）《野馬馴服者》，刻有“版權所有/弗雷德里克·倫賽特”字樣，並印有鑄造廠標記“ROMAN BRONZE WORKS N.Y.”（底座上）—刻有“71”（底座下），青銅材質，表面呈棕色，高23英寸（58.4厘米）。該作品於1895年創作，至1908年鑄造完成。','2100000.00','<p><span style="color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">FREDERIC REMINGTON (1861-1909)</span><br style="box-sizing: inherit; color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);"/><span style="color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">Broncho Buster</span><br style="box-sizing: inherit; color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);"/><span style="color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">inscribed &#39;Copyrighted by/Frederic Remington&#39; and stamped with foundry mark &#39;ROMAN BRONZE WORKS N.Y.&#39; (on the base)—inscribed &#39;71&#39; (underneath the base)</span><br style="box-sizing: inherit; color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);"/><span style="color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">bronze with brown patina</span><br style="box-sizing: inherit; color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);"/><span style="color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">23 in. (58.4 cm.) high</span><br style="box-sizing: inherit; color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);"/><span style="color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">Modeled in 1895; cast by 1908.</span></p>','','九龙','','Goods/20241230/67723f507e10a.jpg|Goods/20241230/67723f585f4d8.jpg|Goods/20241230/67723f5fc76ed.jpg|Goods/20241230/67723f675b7e4.jpg|Goods/20241230/67723f6ead077.jpg|Goods/20241230/67723f766b12f.jpg|Goods/20241230/67723f7f8da23.jpg|Goods/20241230/67723f87803e9.jpg','','1735540618','1735540746','5','');
INSERT INTO `on_goods` VALUES ('39','4','托馬斯·莫蘭（1837-1926）','','','托馬斯·莫蘭（1837-1926）
《大景觀小徑》
簽名為縮寫字母並標註年份“TMoran. 1904.”（左下角）
油畫，畫布上
14 x 20 英寸（35.6 x 50.8 厘米）
創作於1904年','5600000.00','<p>達茲爾·哈特菲爾德畫廊，加利福尼亞州洛杉磯。<br/>私人收藏，加利福尼亞州文圖拉縣，1976年之前。<br/>弗雷德·哈斯頓，德州休斯頓。<br/>芬畫廊有限公司，新墨西哥州聖塔菲，1986年。<br/>內德拉·馬特奇畫廊，新墨西哥州聖塔菲。<br/>現任所有者於1988年收購。</p>','','九龙','','Goods/20241230/67724137b1496.jpg|Goods/20241230/67724140d9fc7.jpg|Goods/20241230/6772414921035.jpg|Goods/20241230/677241516bca0.jpg','','1735541076','1735541122','5','');
INSERT INTO `on_goods` VALUES ('40','4','托馬斯·希爾（1829-1908）','','','托馬斯·希爾（1829-1908）
《高塞拉山脈》
簽名並標註年份“T. Hill./1886.”（左下角）
油畫，畫布上
36 x 54 英寸（91.4 x 137.2 厘米）
創作於1886年','1800000.00','<p><span style="color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">THOMAS HILL (1829-1908)</span><br style="box-sizing: border-box; color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);"/><span style="color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">In the High Sierras</span><br style="box-sizing: border-box; color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);"/><span style="color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">signed and dated &#39;T. Hill./1886.&#39; (lower left)</span><br style="box-sizing: border-box; color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);"/><span style="color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">oil on canvas</span><br style="box-sizing: border-box; color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);"/><span style="color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">36 x 54 in. (91.4 x 137.2 cm.)</span><br style="box-sizing: border-box; color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);"/><span style="color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">Painted in 1886.</span></p>','','九龙','','Goods/20241230/6772425ce3e4a.jpg|Goods/20241230/6772426702e3d.jpg|Goods/20241230/6772426eaf875.jpg|Goods/20241230/677242773ccd2.jpg','','1735541370','1735541411','5','');
INSERT INTO `on_goods` VALUES ('41','4','阿尔伯特·比尔施塔特 1830-1902','','','阿爾伯特·比爾施塔特（1830-1902）
《騎行中的休息》
簽名並標註字母“ABierstadt”（左下角）
油畫，畫布上
30 x 50 英寸（76.2 x 127 厘米）
創作於約1863-64年','8000000.00','<p><span style="color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">ALBERT BIERSTADT (1830-1902)</span><br style="box-sizing: inherit; color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);"/><span style="color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">A Rest on the Ride</span><br style="box-sizing: inherit; color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);"/><span style="color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">signed with conjoined initials &#39;ABierstadt&#39; (lower left)</span><br style="box-sizing: inherit; color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);"/><span style="color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">oil on canvas</span><br style="box-sizing: inherit; color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);"/><span style="color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">30 x 50 in. (76.2 x 127 cm.)</span><br style="box-sizing: inherit; color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);"/><span style="color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">Painted&nbsp;</span><em style="box-sizing: inherit; margin: 0px; border: 0px; padding: 0px; vertical-align: baseline; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: inherit; font-family: ABCArizonaSans; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 14px; color: rgb(34, 34, 34); text-wrap: wrap; background-color: rgb(255, 255, 255);">circa&nbsp;</em><span style="color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">1863-64.</span></p>','','九龙','','Goods/20241230/6772435dd6a32.jpg|Goods/20241230/67724365bf1ed.jpg|Goods/20241230/6772436d2ef75.jpg|Goods/20241230/67724374ec06a.jpg','','1735541624','1735541652','5','');
INSERT INTO `on_goods` VALUES ('42','4','約翰·弗雷德里克·肯塞特 1816','','','約翰·弗雷德里克·肯塞特（1816-1872）
《林中瀑布與印第安人》
簽名並標註字母縮寫及年份“JFK.50”（左下角）
油畫，畫布上
17 x 23 ¾ 英寸（43.2 x 60.3 厘米）
創作於1850年','2200000.00','<p><span style="color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">JOHN FREDERICK KENSETT (1816-1872)</span><br style="box-sizing: border-box; color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);"/><span style="color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">Waterfall in the Woods with Indians</span><br style="box-sizing: border-box; color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);"/><span style="color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">signed with conjoined initials and dated &#39;JFK.50&#39; (lower left)</span><br style="box-sizing: border-box; color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);"/><span style="color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">oil on canvas</span><br style="box-sizing: border-box; color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);"/><span style="color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">17 x 23 ¾ in. (43.2 x 60.3 cm.)</span><br style="box-sizing: border-box; color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);"/><span style="color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">Painted in 1850.</span></p>','','九龙','','Goods/20241230/6772458d76c60.jpg|Goods/20241230/6772459505866.jpg|Goods/20241230/6772459ceaf0c.jpg|Goods/20241230/677245a4b871c.jpg','','1735542198','1735542225','5','');
INSERT INTO `on_goods` VALUES ('43','4','馬丁·約翰遜·海德（1819-1904）','','','馬丁·約翰遜·海德（1819-1904）
《光滑桌面上的木蘭花》
簽名“MJ Heade”（左下角）
油畫，畫布上
14 x 22 英寸（35.6 x 55.9 厘米）
創作於約1885-95年','7000000.00','<p><span style="color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">MARTIN JOHNSON HEADE (1819-1904)</span><br style="box-sizing: inherit; color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);"/><span style="color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">Magnolias on a Shiny Table</span><br style="box-sizing: inherit; color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);"/><span style="color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">signed &#39;MJ Heade&#39; (lower left)</span><br style="box-sizing: inherit; color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);"/><span style="color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">oil on canvas</span><br style="box-sizing: inherit; color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);"/><span style="color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">14 x 22 in. (35.6 x 55.9 cm.)</span><br style="box-sizing: inherit; color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);"/><span style="color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">Painted&nbsp;</span><em style="box-sizing: inherit; margin: 0px; border: 0px; padding: 0px; vertical-align: baseline; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: inherit; font-family: ABCArizonaSans; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 14px; color: rgb(34, 34, 34); text-wrap: wrap; background-color: rgb(255, 255, 255);">circa&nbsp;</em><span style="color: rgb(34, 34, 34); font-family: ABCArizonaSans; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">1885-95.</span></p>','','九龙','','Goods/20241230/677247290932d.jpg|Goods/20241230/67724731306f3.jpg|Goods/20241230/677247393547d.jpg|Goods/20241230/677247429e226.jpg','','1735542597','1735542623','5','');
INSERT INTO `on_goods` VALUES ('44','6','清乾隆 琺瑯彩贲巴壺','','','乾隆琺瑯彩瓷器的製作工藝非常精湛，採用了多種複雜的裝飾技法，如琺瑯彩、描金、雕刻等。這些技法使得瓷器的裝飾層次豐富，色彩鮮豔，具有很高的藝術價值。民間基本沒有留存。因此，存世量稀少直接導致在收藏價值以及市場上它的價值極高。','3000000.00','<p>大清乾隆琺瑯彩贲巴壺是一種典型的乾隆時期瓷器，以其精湛的製作工藝和獨特的藝術風格而聞名。琺瑯彩瓷器是清代宮廷瓷器中的精品，其製作工藝複雜，色彩鮮豔，裝飾精美。造型：贲巴壺通常為磨口瓶，細頸，彎曲的流嘴，球腹下承喇叭形足。整體造型優美，線條流暢，具有濃郁的藏族風情。裝飾：贲巴壺通體以琺瑯彩為飾，自上至下分飾各種紋飾。裝飾層次豐富，色彩鮮豔。紋飾：主題紋飾為八寶紋、雲龍紋、蓮花紋等吉祥圖案，寓意吉祥。這些紋飾是中國傳統瓷器裝飾中常見的紋飾之一，具有很高的藝術價值。款識：底部通常有“三行六字楷書款，款識清晰，字體工整。</p>','','九龙','','Goods/20241230/677285f97bd3b.jpg|Goods/20241230/677286583b21b.jpg|Goods/20241230/6772866186446.jpg','','1735552441','1735558758','5','');
INSERT INTO `on_goods` VALUES ('45','6','清康熙 撇口瓶','','','清康熙撇口瓶作為康熙時期瓷器中的精品，其市場價值較高。在市場上一直備受青睞，特別是那些工藝複雜、裝飾精美的瓷器，其收藏價值更高。','1200000.00','<p>清康熙撇口瓶是一種典型的康熙時期瓷器，以其精湛的製作工藝和獨特的藝術風格而聞名。粉彩瓷器是清代宮廷瓷器中的精品，其製作工藝複雜，色彩鮮豔，裝飾精美。根據圖中鑑定結果，大清康熙撇口瓶具有以下特徵：撇口瓶通常為撇口，短頸，圓腹，下承圈足。整體造型優美，線條流暢。撇口瓶通體以粉彩為飾，自上至下分飾各種紋飾。裝飾層次豐富，色彩鮮豔。主題紋飾為牡丹花紋，寓意吉祥。牡丹紋是中國傳統瓷器裝飾中常見的紋飾之一，具有很高的藝術價值。</p>','','九龙','','Goods/20241230/677288562ce12.jpg|Goods/20241230/6772885b36d41.jpg|Goods/20241230/6772885fc91bd.jpg|Goods/20241230/67728864ceed7.jpg','','1735553231','1735559270','5','');


# 数据库表：on_goods_category 数据信息
INSERT INTO `on_goods_category` VALUES ('1','0','瓷器','','Category/20241217/67606c3121757.jpg','','0','0','1');
INSERT INTO `on_goods_category` VALUES ('2','0','玉器','','Category/20241217/67606cc34c827.jpg','','0','0','0');
INSERT INTO `on_goods_category` VALUES ('3','0','雜項','','Category/20241217/67606d3f61bc9.jpg','','0','0','0');
INSERT INTO `on_goods_category` VALUES ('4','0','字畫','','Category/20241217/67606d7d0bf9f.jpg','','0','0','0');
INSERT INTO `on_goods_category` VALUES ('5','0','錢幣','','Category/20241217/67606eb5bbafc.jpg','','0','0','0');
INSERT INTO `on_goods_category` VALUES ('6','0','正在拍賣','','Category/20241217/67606f93913df.jpg','','1','0','2');
INSERT INTO `on_goods_category` VALUES ('8','0','佛教文物','','Category/20241217/67606f1622b03.jpg','','0','0','0');


# 数据库表：on_goods_category_extend 数据信息


# 数据库表：on_goods_category_filtrate 数据信息


# 数据库表：on_goods_evaluate 数据信息


# 数据库表：on_goods_extend 数据信息


# 数据库表：on_goods_fields 数据信息


# 数据库表：on_goods_filtrate 数据信息
INSERT INTO `on_goods_filtrate` VALUES ('1','0','瓷器','0');


# 数据库表：on_goods_order 数据信息
INSERT INTO `on_goods_order` VALUES ('1','BID173521440485271','0','28','12','5','1130000.00','0.00','56500.00','113000.00','1735214404','1735289773','0','0','0','0','1735894573','0','0','0','0','0','0','0','1735819204','0','1','0','0','','a:6:{s:4:"prov";s:6:"北京";s:4:"city";s:9:"北京市";s:8:"district";s:9:"东城区";s:7:"address";s:12:"故宮附件";s:8:"truename";s:5:"admin";s:6:"mobile";s:11:"18852545214";}','','','','已支付我们会尽快安排发货');
INSERT INTO `on_goods_order` VALUES ('2','BID173528754867011','0','29','12','5','7100000.00','0.00','355000.00','710000.00','1735287548','1735289787','0','0','0','0','1735894587','0','0','0','0','0','0','0','1735892348','0','1','0','0','','a:6:{s:4:"prov";s:6:"北京";s:4:"city";s:9:"北京市";s:8:"district";s:9:"东城区";s:7:"address";s:12:"故宮附件";s:8:"truename";s:5:"admin";s:6:"mobile";s:11:"18852545214";}','','','','已支付我们会尽快安排发货');
INSERT INTO `on_goods_order` VALUES ('3','BID173539751445066','0','30','28','5','29000000.00','0.00','1450000.00','2900000.00','1735397514','0','0','0','0','0','0','0','0','0','0','0','0','0','1736002314','0','0','0','0','','','','','','a:1:{i:0;s:54:"竞拍成功，请在订单有效期内进行支付！";}');
INSERT INTO `on_goods_order` VALUES ('4','BID173557886177039','0','2','26','4','6300000.00','0.00','315000.00','630000.00','1735578861','0','0','0','0','0','0','0','0','0','0','0','0','0','1736183661','0','0','0','0','','','','','','a:1:{i:0;s:54:"竞拍成功，请在订单有效期内进行支付！";}');
INSERT INTO `on_goods_order` VALUES ('5','BID173560796468590','0','7','38','5','6800000.00','0.00','340000.00','680000.00','1735607964','0','0','0','0','0','0','0','0','0','0','0','0','0','1736212764','0','0','0','0','','','','','','a:1:{i:0;s:54:"竞拍成功，请在订单有效期内进行支付！";}');
INSERT INTO `on_goods_order` VALUES ('6','BID173571328830643','0','34','36','5','4400000.00','0.00','220000.00','440000.00','1735713288','0','0','0','0','0','0','0','0','0','0','0','0','0','1736318088','0','0','0','0','','','','','','a:1:{i:0;s:54:"竞拍成功，请在订单有效期内进行支付！";}');
INSERT INTO `on_goods_order` VALUES ('7','BID173579770714638','0','35','38','5','4100000.00','0.00','205000.00','410000.00','1735797707','0','0','0','0','0','0','0','0','0','0','0','0','0','1736402507','0','0','0','0','','','','','','a:1:{i:0;s:54:"竞拍成功，请在订单有效期内进行支付！";}');


# 数据库表：on_goods_order_return 数据信息


# 数据库表：on_goods_user 数据信息
INSERT INTO `on_goods_user` VALUES ('9','23','0.00','50000.00','p-u','1734467611','0','0');
INSERT INTO `on_goods_user` VALUES ('9','4','0.00','30000.00','p-u','1734467660','0','0');
INSERT INTO `on_goods_user` VALUES ('9','3','0.00','30000.00','p-u','1734467684','0','0');
INSERT INTO `on_goods_user` VALUES ('9','21','0.00','50000.00','p-u','1734467709','0','0');
INSERT INTO `on_goods_user` VALUES ('12','27','0.00','50000.00','p-u','1734857959','0','0');
INSERT INTO `on_goods_user` VALUES ('12','23','0.00','50000.00','p-u','1734857979','0','0');
INSERT INTO `on_goods_user` VALUES ('12','21','0.00','50000.00','p-u','1734858001','0','0');
INSERT INTO `on_goods_user` VALUES ('22','28','0.00','50000.00','p-u','1735013879','1735214404','1');
INSERT INTO `on_goods_user` VALUES ('22','29','0.00','50000.00','p-u','1735022081','1735287548','1');
INSERT INTO `on_goods_user` VALUES ('12','29','0.00','0.00','p-u','1735022936','0','0');
INSERT INTO `on_goods_user` VALUES ('9','29','0.00','50000.00','p-u','1735098621','1735287548','1');
INSERT INTO `on_goods_user` VALUES ('12','28','0.00','0.00','p-u','1735100789','0','0');
INSERT INTO `on_goods_user` VALUES ('9','28','0.00','50000.00','p-u','1735104704','1735214404','1');
INSERT INTO `on_goods_user` VALUES ('12','30','0.00','50000.00','p-u','1735289804','1735397514','1');
INSERT INTO `on_goods_user` VALUES ('26','30','0.00','50000.00','p-u','1735289890','1735397514','1');
INSERT INTO `on_goods_user` VALUES ('26','2','0.00','20000.00','p-u','1735289931','0','0');
INSERT INTO `on_goods_user` VALUES ('26','27','0.00','50000.00','p-u','1735320464','0','0');
INSERT INTO `on_goods_user` VALUES ('26','20','0.00','30000.00','p-u','1735320569','0','0');
INSERT INTO `on_goods_user` VALUES ('28','30','0.00','50000.00','p-u','1735323055','0','0');
INSERT INTO `on_goods_user` VALUES ('26','34','0.00','50000.00','p-u','1735523982','1735713288','1');
INSERT INTO `on_goods_user` VALUES ('12','35','0.00','50000.00','p-u','1735524109','1735797707','1');
INSERT INTO `on_goods_user` VALUES ('9','35','0.00','50000.00','p-u','1735544050','1735797707','1');
INSERT INTO `on_goods_user` VALUES ('10','34','0.00','50000.00','p-u','1735544531','1735713288','1');
INSERT INTO `on_goods_user` VALUES ('36','34','0.00','50000.00','p-u','1735604764','0','0');
INSERT INTO `on_goods_user` VALUES ('37','35','0.00','50000.00','p-u','1735605366','1735797707','1');
INSERT INTO `on_goods_user` VALUES ('38','7','0.00','50000.00','p-u','1735606325','0','0');
INSERT INTO `on_goods_user` VALUES ('38','35','0.00','50000.00','p-u','1735618173','0','0');
INSERT INTO `on_goods_user` VALUES ('22','43','0.00','50000.00','p-u','1735715811','0','0');


# 数据库表：on_link 数据信息


# 数据库表：on_meeting_auction 数据信息
INSERT INTO `on_meeting_auction` VALUES ('3','侏羅紀龍踪：異特龍與劍龍','Meeting/20241228/676ef518dc3b9.jpg','','本季佳士得榮幸首次喺拍賣會上呈獻三具恐龍骨架。 侏羅紀圖標以一眼就能認出嘅劍龍為特色，它的尾尖刺同排排裝甲甲，以及一對可怕嘅食肉異特龍。 它們係喺懷俄明州嘅Meilyn埰石場彼此靠近嘅地方發現嘅，並且保存完好，它們的化石骨骼具有美麗的黑色。 呢啲令人印象深刻嘅動物生活喺大約1.57至1.45億年前嘅侏羅紀晚期，生活喺半乾旱嘅稀樹草原般嘅景觀中，作為獵物同頂級捕食者，它們完美地適應了它們的環境。 呢啲動物嘅研究歷史悠久，可以追溯到19世紀末嘅“骨戰爭”，呢啲動物令科學家和公眾都感到開心，並將成為國王街提供嘅','1740674100','1740674340','60','1200','120','0','0','0','2','0','5','0','0');
INSERT INTO `on_meeting_auction` VALUES ('2','香港佳士得2024春拍「誠虔韻映」佛教專','Meeting/20241216/675f3d014a4ca.jpg','','香港佳士得秋拍「誠虔韻映」佛教專場舉槌，本場尤重喜馬拉雅藝術之傳承與廣博，自犍陀羅、斯瓦特及帕拉、馬拉藝術的早期源流，以達隆噶舉派唐卡為經典的對上師法脈的尊崇。','1740774600','1740775800','120','120','60','0','50000','0','4','0','5','0','0');
INSERT INTO `on_meeting_auction` VALUES ('4','十九世紀美國與西方藝術','Meeting/20241230/67723939a1f1d.jpg','','佳士得美洲艺术部很高兴在1月23日美国周期间举办年度19世纪美国和西部艺术品拍卖会。此次精心策划的拍卖会包括美国绘画、纸上作品和雕塑，以阿尔伯特·比尔施塔特（Albert Bierstadt）的史诗级加州风景画《骑行中的休息》等时期的杰作领衔。从温斯洛·霍默（Winslow Homer）到哈德逊河画派的重要成员，一系列重要艺术家的作品将与马丁·约翰逊·海德（Martin Johnson Heade）的出色画作一同呈现。','1737565200','1737566400','120','120','60','0','0','0','7','0','5','0','0');


# 数据库表：on_member 数据信息
INSERT INTO `on_member` VALUES ('1','0','','','','admin1','热爱珍藏','新鸿古玩店','只卖真货！','74be16979710d4c4e7c6647856088456','','毛群英','522230197102281080','Idcard/20241212/6759bddb29e99.jpg','Idcard/20241212/6759bde3373e0.jpg','2','1733934959','','15845645564','1734287260','218.78.135.44','1','1','','','','','','0','0','','0','北京','北京市','东城区','','','','','','100000.00','400.00','0.00','0.00','76','76','103.151.116.62','1734287281','1','0','');
INSERT INTO `on_member` VALUES ('2','0','','','','Hk112233','','','','8b16088ce658aeef2251f6f263f77351','','','','','','','0','','','1733927902','101.44.81.136','0','0','','','','','','','','','','','','','','','','','','0.00','0.00','0.00','0.00','0','0','101.44.81.136','1733927902','1','0','');
INSERT INTO `on_member` VALUES ('3','0','','','','gymmmmm913','','','','19d52d1d771a556a7338319184c4a0b3','','','','','','','0','','','1733937567','150.129.23.9','0','0','','','','','','','','','','','','','','','','','','0.00','0.00','0.00','0.00','0','0','150.129.23.6','1734518202','1','0','');
INSERT INTO `on_member` VALUES ('4','0','','','','adminxl','藏宝阁主','藏宝阁','出手、回收、各类瓷器钱币..','ace250e3c8f8d908155dc48201d30bfe','','','','','','2','1734099374','','18525414152','1734273255','218.78.135.44','1','1','','','','','User/675c41764b7dd.jpg','0','1','','0','北京','北京市','大兴区','交天下藏友','','','','','100000.00','600.00','0.00','0.00','80','80','103.151.116.62','1734281384','1','0','');
INSERT INTO `on_member` VALUES ('5','0','','','','banghansi001','佳士得官方','佳士得官方','佳士得唯一官方账号','ace250e3c8f8d908155dc48201d30bfe','14e1b600b1fd579f47433b88e8d85291','','','','','2','1734288622','','18858888888','1735012790','103.151.116.62','1','1','','','','User/6760f21732cc9.jpg','User/6760f2790341d.jpg','-28800','0',' 6/F, The Henderson, 2 Murray Rd, Central, 香港','0','香港特别行政区','九龙','黄大仙区','佳士得官方账号','','','','','3000000.00','0.00','5000000.00','1301800.00','410','410','150.129.23.12','1735578885','1','0','');
INSERT INTO `on_member` VALUES ('6','0','','','','t001','昂酷客服','','','14e1b600b1fd579f47433b88e8d85291','14e1b600b1fd579f47433b88e8d85291','','','','','0','0','','18567568766','','','0','0','','','','','','0','0','','0','河南省','郑州市','金水区','','','','','','0.00','0.00','0.00','0.00','0','0','123.15.153.223','1734350081','1','0','');
INSERT INTO `on_member` VALUES ('7','0','','','','t002','','','','14e1b600b1fd579f47433b88e8d85291','','','','','','','0','','','1734356708','123.15.153.223','0','0','','','','','','','','','','','','','','','','','','0.00','0.00','0.00','0.00','0','0','123.15.153.223','1734356708','1','0','');
INSERT INTO `on_member` VALUES ('8','0','','','','t003','','','','14e1b600b1fd579f47433b88e8d85291','','','','','','','0','','','1734356754','123.15.153.223','0','0','','','','','','','','','','','','','','','','','','0.00','0.00','0.00','0.00','0','0','123.15.153.223','1734358576','1','0','');
INSERT INTO `on_member` VALUES ('9','0','','','','xiaopacai123','Mri ei','','','ace250e3c8f8d908155dc48201d30bfe','','','','','','2','1734467573','','18525525454','1734467573','150.129.23.12','1','1','','','','User/67724d2f04223.jpg','','-28800','0','額請我吃','0','广东省','云浮市','罗定市','我很懒，什么也不想写！','','','','','500000.00','160000.00','0.00','0.00','0','0','103.151.116.62','1735543960','1','0','');
INSERT INTO `on_member` VALUES ('10','0','','','','qwe135123','麦克杰克','','','2980780ca907df2d99b09f13a713d3aa','','','','','','2','1735544470','','18852545214','1735544470','103.151.116.62','1','1','','','','User/67724e5a3e4d9.jpg','','0','1','','0','广东省','深圳市','龙岗区','','','','','','5000000.00','0.00','0.00','0.00','0','0','103.151.116.62','1735544506','1','0','');
INSERT INTO `on_member` VALUES ('11','0','','','','Dcccca','','','','3fed78bfb3e690e9ffbf7dca5efb2bc4','','','','','','','0','','','1734491132','150.129.23.21','0','0','','','','','','','','','','','','','','','','','','0.00','0.00','0.00','0.00','0','0','150.129.23.21','1734491132','1','0','');
INSERT INTO `on_member` VALUES ('12','0','','','','adminjinzi','塔拉甫·列斯','','','2980780ca907df2d99b09f13a713d3aa','','','','','','2','1734857917','','15825220215','1734857917','218.78.135.44','1','1','','','','','','-28800','1','嘘嘘嘘','0','四川省','泸州市','其它区','我很懒，什么也不想写！','','','','','7147000.00','150000.00','0.00','0.00','0','0','103.151.116.62','1735544187','1','0','');
INSERT INTO `on_member` VALUES ('13','0','','','','xie0028','','','','2980780ca907df2d99b09f13a713d3aa','','','','','','','0','','','1734695154','103.151.116.62','0','0','','','','','','','','','','','','','','','','','','0.00','0.00','0.00','0.00','0','0','103.151.116.62','1735558438','1','0','');
INSERT INTO `on_member` VALUES ('14','0','','','','a18029532832','艺术空间','','','ad8c1e4fdfcf4cb67b2af9988d46b012','','蔡树扬','440521196306250036','Idcard/20241220/6765814f1737f.jpg','Idcard/20241220/6765816532c45.jpg','2','1734764602','','18029532832','1734764616','103.151.116.62','0','1','','','','User/6765669ce2ed6.jpg','','-205833600','1','凤翔城南新宫巷二号','0','广东省','汕头市','澄海区','我很懒，什么也不想写！','','','','','0.00','0.00','0.00','0.00','0','0','223.74.124.62','1734748998','1','0','');
INSERT INTO `on_member` VALUES ('15','0','','','','a13389213346','^_^','','','00c351677029d3840898d241bc542fb9','','','','','','0','1734771534','','13389213346','1734771534','103.151.116.62','0','0','','','','','','0','0','','0','北京','北京市','东城区','','','','','','0.00','0.00','0.00','0.00','0','0','223.160.172.35','1734771738','1','0','');
INSERT INTO `on_member` VALUES ('16','0','','','','z13864385388','','','','206c3d4297d075eb6ff56de7a071897b','','','','','','','0','','','1734921674','223.104.196.76','0','0','','','','','','','','','','','','','','','','','','0.00','0.00','0.00','0.00','0','0','223.104.196.107','1735541833','1','0','');
INSERT INTO `on_member` VALUES ('17','0','','','','13111899216','131118','','','00c351677029d3840898d241bc542fb9','','','','','','0','0','','13111899216','','','0','1','','','','','','0','0','','0','北京','北京市','东城区','','','','','','0.00','0.00','0.00','0.00','0','0','223.85.219.153','1735229433','1','0','');
INSERT INTO `on_member` VALUES ('18','0','','','','13978051735','dihns','','','70873e8580c9900986939611618d7b1e','','','','','','0','0','','13978051735','','','0','1','','','','','','0','1','','0','北京','北京市','东城区','','','','','','0.00','0.00','0.00','0.00','0','0','','','1','0','');
INSERT INTO `on_member` VALUES ('19','0','','','','a13879237626','','','','1bcd44f0736b1e8b537aa3316db37f6f','','','','','','','0','','','1734961339','103.151.116.62','0','0','','','','','','','','','','','','','','','','','','0.00','0.00','0.00','0.00','0','0','103.151.116.62','1734961339','1','0','');
INSERT INTO `on_member` VALUES ('20','0','','','','15095076122','123456','','','70873e8580c9900986939611618d7b1e','','','','','','0','0','','15095076122','','','0','1','','','','','','-253169520','1','临清市造纸厂家属楼六单元九号楼','0','山东省','聊城市','临清市','我很懒，什么也不想写！','','','','','0.00','0.00','0.00','0.00','0','0','39.144.109.99','1735038616','1','0','');
INSERT INTO `on_member` VALUES ('21','0','','','','a15095076122','','','','18b108c769b42e49aba2dd5407c88355','','','','','','','0','','','1735011052','103.151.116.62','0','0','','','','','','','','','','','','','','','','','','0.00','0.00','0.00','0.00','0','0','103.151.116.62','1735011052','1','0','');
INSERT INTO `on_member` VALUES ('22','0','','','','a123520','爱德华','','','2980780ca907df2d99b09f13a713d3aa','','','','','','2','1735013859','','15252465666','1735013859','103.151.116.62','0','1','','','','','','0','0','','0','北京','北京市','东城区','','','','','','1000000.00','50000.00','0.00','0.00','0','0','103.151.116.62','1735715793','1','0','');
INSERT INTO `on_member` VALUES ('23','0','','','','a13645354539','空白名','','','a9d1a2a049b115295f23f2b7cb5c1dbe','','','','','','0','1735107149','','13645354539','1735107149','218.78.135.44','0','1','','','','','','0','0','','0','北京','北京市','东城区','','','','','','0.00','0.00','0.00','0.00','0','0','111.37.74.86','1735354905','1','0','');
INSERT INTO `on_member` VALUES ('29','0','','','','shuiwuju01','','','','2980780ca907df2d99b09f13a713d3aa','','','','','','','0','','','1735268748','218.78.135.44','0','0','','','','','','','','','','','','','','','','','','0.00','0.00','0.00','0.00','0','0','218.78.135.44','1735268748','1','0','');
INSERT INTO `on_member` VALUES ('24','0','','','','xuch','','','','1755bd2d612cda53bc75580d3f58fa59','','','','','','','0','','','1735188271','122.96.44.29','0','0','','','','','','','','','','','','','','','','','','0.00','0.00','0.00','0.00','0','0','122.96.44.29','1735188271','1','0','');
INSERT INTO `on_member` VALUES ('25','0','','','','a543300','','','','f62fc4140934ab0ac540a894b0e52cf3','','','','','','','0','','','1735188954','103.151.116.62','0','0','','','','','','','','','','','','','','','','','','0.00','0.00','0.00','0.00','0','0','103.151.116.62','1735188954','1','0','');
INSERT INTO `on_member` VALUES ('26','0','','','','adminjinzi1','adminj','','','2980780ca907df2d99b09f13a713d3aa','','','','','','2','1735289883','','18525352521','1735289883','218.78.135.44','0','1','','','','','','0','0','','0','北京','北京市','房山区','','','','','','11000000.00','100000.00','0.00','0.00','0','0','103.151.116.62','1735523970','1','0','');
INSERT INTO `on_member` VALUES ('27','0','','','','a15845642152','张霞','','','2980780ca907df2d99b09f13a713d3aa','','','','','','','0','','','1735220064','218.78.135.44','0','0','','','','','','-28800','','大青蛙','','北京','北京市','东城区','我很懒，什么也不想写！','','','','','1370000.00','0.00','0.00','0.00','0','0','218.78.135.44','1735220735','1','0','');
INSERT INTO `on_member` VALUES ('28','0','','','','a11223309','李坤玉','','','2980780ca907df2d99b09f13a713d3aa','','','','','','2','1735323030','','15425125214','1735323030','103.151.116.62','0','1','','','','','','0','0','','0','北京','北京市','东城区','','','','','','1590000.00','50000.00','0.00','0.00','0','0','150.129.23.2','1735322981','1','0','');
INSERT INTO `on_member` VALUES ('30','0','','','','a18924478368','请输入名称','','','b8d8e17e46cd327df530629d71c657e9','','陈善清','44142119601212411X','Idcard/20241228/676f5d6e26bc1.jpg','Idcard/20241228/676f5d76c1d83.jpg','2','1735351793','','18924478368','1735351793','183.24.172.101','0','1','','','','','','0','0','','0','北京','北京市','东城区','','','','','','0.00','0.00','0.00','0.00','0','0','183.24.172.101','1735449131','1','0','');
INSERT INTO `on_member` VALUES ('31','0','','','','a18258186779','陈博函','','','70873e8580c9900986939611618d7b1e','','','','','','','0','','','1735463045','103.151.116.62','0','0','','','','','','107366400','1','乔司镇乔司街道良熟新苑7栋3单元202','','浙江省','杭州市','余杭区','我很懒，什么也不想写！','','','','','0.00','0.00','0.00','0.00','0','0','117.136.111.225','1735526013','1','0','');
INSERT INTO `on_member` VALUES ('32','0','','','','a11223305','','','','f62fc4140934ab0ac540a894b0e52cf3','','','','','','','0','','','1735469825','103.151.116.62','0','0','','','','','','','','','','','','','','','','','','0.00','0.00','0.00','0.00','0','0','113.140.169.73','1735702321','1','0','');
INSERT INTO `on_member` VALUES ('33','0','','','','a11223304','韩继泰','','','f62fc4140934ab0ac540a894b0e52cf3','','','','','','','0','','','1735471277','103.151.116.62','0','0','','','','','','-28800','1','天津市，武清区，下伍旗镇，中义村','','天津','天津市','武清区','我很懒，什么也不想写！','','','','','0.00','0.00','0.00','0.00','0','0','111.32.119.38','1735539555','1','0','');
INSERT INTO `on_member` VALUES ('34','0','','','','a112211000','','','','f62fc4140934ab0ac540a894b0e52cf3','','','','','','','0','','','1735542770','103.151.116.62','0','0','','','','','','','','','','','','','','','','','','0.00','0.00','0.00','0.00','0','0','103.151.116.62','1735542770','1','0','');
INSERT INTO `on_member` VALUES ('35','0','','','','a11223306','空白名','','','f62fc4140934ab0ac540a894b0e52cf3','','杨春芝','','','','0','1735556321','','15046882133','1735556321','103.151.116.62','0','1','','','','','','0','0','','0','北京','北京市','东城区','','','','','','0.00','0.00','0.00','0.00','0','0','103.151.116.62','1735555421','1','0','');
INSERT INTO `on_member` VALUES ('36','0','','','','adminjinzi2','James','','','2980780ca907df2d99b09f13a713d3aa','','','','','','2','0','','18852545213','','','0','1','','','','User/677339bc77bb2.jpg','','0','1','','0','北京','北京市','东城区','','','','','','5000000.00','50000.00','0.00','0.00','0','0','103.151.116.62','1735621070','1','0','');
INSERT INTO `on_member` VALUES ('37','0','','','','adminjinzi3','林百里','','','2980780ca907df2d99b09f13a713d3aa','','','','','','2','1735605314','','15046882122','1735605314','103.151.116.62','0','1','','','','User/67733bcea0e8b.jpg','','-28800','1','點位是','0','台湾','高雄市','永安区','我很懒，什么也不想写！','','','','','5000000.00','0.00','0.00','0.00','0','125','103.151.116.62','1735605030','1','0','');
INSERT INTO `on_member` VALUES ('38','0','','','','adminjinzi4','李西延','','','2980780ca907df2d99b09f13a713d3aa','','','','','','2','1735605927','','18852545543','1735605927','103.151.116.62','0','1','','','','User/67733e521c55d.jpg','','-28800','1','還扎灣區','0','香港特别行政区','新界','荃湾区','我很懒，什么也不想写！','','','','','5000000.00','100000.00','0.00','0.00','0','0','103.151.116.62','1735618157','1','0','');
INSERT INTO `on_member` VALUES ('39','0','','','','a18138986397','','','','70873e8580c9900986939611618d7b1e','','','','','','','0','','','1735714450','103.151.116.62','0','0','','','','','','','','','','','','','','','','','','0.00','0.00','0.00','0.00','0','0','14.126.244.79','1735793493','1','0','');
INSERT INTO `on_member` VALUES ('40','0','','','','a11223301','','','','f62fc4140934ab0ac540a894b0e52cf3','','','','','','','0','','','1735798375','103.151.116.62','0','0','','','','','','','','','','','','','','','','','','0.00','0.00','0.00','0.00','0','0','103.151.116.62','1735798375','1','0','');
INSERT INTO `on_member` VALUES ('41','0','','','','a112233001','','','','f62fc4140934ab0ac540a894b0e52cf3','','','','','','','0','','','1735798502','103.151.116.62','0','0','','','','','','','','','','','','','','','','','','0.00','0.00','0.00','0.00','0','0','103.151.116.62','1735798502','1','0','');


# 数据库表：on_member_evaluate 数据信息


# 数据库表：on_member_footprint 数据信息
INSERT INTO `on_member_footprint` VALUES ('1','a:1:{i:0;s:1:"1";}');
INSERT INTO `on_member_footprint` VALUES ('3','a:3:{i:0;s:1:"4";i:1;s:1:"3";i:2;s:1:"1";}');
INSERT INTO `on_member_footprint` VALUES ('4','a:3:{i:0;s:1:"3";i:1;s:1:"2";i:2;s:1:"1";}');
INSERT INTO `on_member_footprint` VALUES ('5','a:32:{i:0;s:2:"33";i:1;s:2:"35";i:2;s:2:"43";i:3;s:2:"30";i:4;s:2:"35";i:5;s:2:"34";i:6;s:2:"43";i:7;s:1:"3";i:8;s:2:"22";i:9;s:1:"6";i:10;s:2:"27";i:11;s:2:"41";i:12;s:2:"36";i:13;s:2:"34";i:14;s:2:"35";i:15;s:2:"34";i:16;s:2:"30";i:17;s:1:"2";i:18;s:2:"30";i:19;s:2:"23";i:20;s:2:"22";i:21;s:2:"23";i:22;s:2:"27";i:23;s:2:"33";i:24;s:2:"32";i:25;s:2:"30";i:26;s:2:"27";i:27;s:2:"28";i:28;s:2:"27";i:29;s:2:"24";i:30;s:2:"18";i:31;s:2:"14";}');
INSERT INTO `on_member_footprint` VALUES ('8','a:3:{i:0;s:2:"19";i:1;s:2:"23";i:2;s:2:"19";}');
INSERT INTO `on_member_footprint` VALUES ('9','a:17:{i:0;s:2:"35";i:1;s:1:"2";i:2;s:2:"28";i:3;s:2:"29";i:4;s:2:"30";i:5;s:2:"29";i:6;s:1:"5";i:7;s:1:"6";i:8;s:1:"4";i:9;s:1:"3";i:10;s:2:"21";i:11;s:2:"19";i:12;s:2:"23";i:13;s:2:"21";i:14;s:1:"3";i:15;s:1:"4";i:16;s:2:"23";}');
INSERT INTO `on_member_footprint` VALUES ('10','a:3:{i:0;s:1:"2";i:1;s:2:"34";i:2;s:1:"6";}');
INSERT INTO `on_member_footprint` VALUES ('13','a:2:{i:0;s:2:"43";i:1;s:2:"27";}');
INSERT INTO `on_member_footprint` VALUES ('12','a:17:{i:0;s:2:"35";i:1;s:2:"30";i:2;s:2:"29";i:3;s:2:"28";i:4;s:2:"29";i:5;s:2:"28";i:6;s:2:"14";i:7;s:2:"30";i:8;s:2:"29";i:9;s:2:"28";i:10;s:1:"2";i:11;s:2:"29";i:12;s:2:"23";i:13;s:2:"24";i:14;s:2:"21";i:15;s:2:"23";i:16;s:2:"27";}');
INSERT INTO `on_member_footprint` VALUES ('16','a:19:{i:0;s:2:"28";i:1;s:2:"29";i:2;s:2:"34";i:3;s:2:"35";i:4;s:2:"28";i:5;s:2:"29";i:6;s:2:"10";i:7;s:2:"28";i:8;s:2:"29";i:9;s:2:"28";i:10;s:2:"29";i:11;s:2:"21";i:12;s:2:"28";i:13;s:2:"14";i:14;s:2:"28";i:15;s:2:"21";i:16;s:2:"28";i:17;s:2:"22";i:18;s:2:"28";}');
INSERT INTO `on_member_footprint` VALUES ('17','a:3:{i:0;s:2:"29";i:1;s:1:"5";i:2;s:2:"29";}');
INSERT INTO `on_member_footprint` VALUES ('22','a:3:{i:0;s:2:"43";i:1;s:2:"29";i:2;s:2:"28";}');
INSERT INTO `on_member_footprint` VALUES ('24','a:3:{i:0;s:2:"22";i:1;s:1:"4";i:2;s:2:"21";}');
INSERT INTO `on_member_footprint` VALUES ('26','a:9:{i:0;s:2:"34";i:1;s:2:"30";i:2;s:2:"27";i:3;s:2:"20";i:4;s:2:"29";i:5;s:2:"27";i:6;s:1:"5";i:7;s:1:"2";i:8;s:2:"30";}');
INSERT INTO `on_member_footprint` VALUES ('28','a:3:{i:0;s:2:"30";i:1;s:2:"19";i:2;s:2:"30";}');
INSERT INTO `on_member_footprint` VALUES ('23','a:5:{i:0;s:2:"30";i:1;s:2:"27";i:2;s:2:"30";i:3;s:2:"27";i:4;s:2:"30";}');
INSERT INTO `on_member_footprint` VALUES ('31','a:7:{i:0;s:2:"35";i:1;s:1:"2";i:2;s:2:"35";i:3;s:1:"2";i:4;s:2:"34";i:5;s:2:"31";i:6;s:2:"35";}');
INSERT INTO `on_member_footprint` VALUES ('32','a:10:{i:0;s:2:"43";i:1;s:2:"20";i:2;s:2:"43";i:3;s:2:"22";i:4;s:2:"43";i:5;s:2:"20";i:6;s:2:"43";i:7;s:2:"29";i:8;s:1:"6";i:9;s:2:"34";}');
INSERT INTO `on_member_footprint` VALUES ('33','a:1:{i:0;s:2:"29";}');
INSERT INTO `on_member_footprint` VALUES ('35','a:1:{i:0;s:2:"43";}');
INSERT INTO `on_member_footprint` VALUES ('36','a:2:{i:0;s:2:"34";i:1;s:2:"35";}');
INSERT INTO `on_member_footprint` VALUES ('37','a:2:{i:0;s:2:"35";i:1;s:2:"44";}');
INSERT INTO `on_member_footprint` VALUES ('38','a:7:{i:0;s:2:"34";i:1;s:2:"35";i:2;s:1:"2";i:3;s:2:"29";i:4;s:1:"7";i:5;s:2:"43";i:6;s:2:"35";}');
INSERT INTO `on_member_footprint` VALUES ('39','a:1:{i:0;s:2:"34";}');
INSERT INTO `on_member_footprint` VALUES ('41','a:1:{i:0;s:2:"43";}');


# 数据库表：on_member_limsum_bill 数据信息
INSERT INTO `on_member_limsum_bill` VALUES ('1','aad173428883473692','5','admin_deposit','1734288834','','5000000.00','0.00','5000000.00','5000000.00');
INSERT INTO `on_member_limsum_bill` VALUES ('2','add173429037909140','5','add_freeze','1734290379','发布拍卖：“<a href="/Auction/details/pid/7/aptitude/1.html">清乾隆《松石綠錦地浮雕金彩百壽琮式瓶》</a>”冻结','0.00','200.00','4999800.00','5000000.00');
INSERT INTO `on_member_limsum_bill` VALUES ('3','add173429076482591','5','add_freeze','1734290764','发布拍卖：“<a href="/Auction/details/pid/8/aptitude/1.html">清乾隆·淡綠彩浮雕礬紅金彩萬壽八方瓶</a>”冻结','0.00','200.00','4999600.00','5000000.00');
INSERT INTO `on_member_limsum_bill` VALUES ('4','add173429095441756','5','add_freeze','1734290954','发布拍卖：“<a href="/Auction/details/pid/9/aptitude/1.html">清雍正 爐鈞釉仿古尊</a>”冻结','0.00','200.00','4999400.00','5000000.00');
INSERT INTO `on_member_limsum_bill` VALUES ('5','add17342911530155','5','add_freeze','1734291153','发布拍卖：“<a href="/Auction/details/pid/10/aptitude/1.html">清乾隆 仿官釉六棱貫耳瓶</a>”冻结','0.00','200.00','4999200.00','5000000.00');
INSERT INTO `on_member_limsum_bill` VALUES ('6','add173429124357027','5','add_freeze','1734291243','发布拍卖：“<a href="/Auction/details/pid/11/aptitude/1.html">17世紀 黃花梨軸門圓角櫃</a>”冻结','0.00','200.00','4999000.00','5000000.00');
INSERT INTO `on_member_limsum_bill` VALUES ('7','add173429147481533','5','add_freeze','1734291474','发布拍卖：“<a href="/Auction/details/pid/12/aptitude/1.html">傅抱石 苦瓜和尚詩意圖</a>”冻结','0.00','200.00','4998800.00','5000000.00');
INSERT INTO `on_member_limsum_bill` VALUES ('8','add173429167857368','5','add_freeze','1734291678','发布拍卖：“<a href="/Auction/details/pid/13/aptitude/1.html">劉野 雷鋒叔叔</a>”冻结','0.00','200.00','4998600.00','5000000.00');
INSERT INTO `on_member_limsum_bill` VALUES ('9','add173429181541129','5','add_freeze','1734291815','发布拍卖：“<a href="/Auction/details/pid/14/aptitude/1.html">曾梵志 《三年級一班 第6號》</a>”冻结','0.00','200.00','4998400.00','5000000.00');
INSERT INTO `on_member_limsum_bill` VALUES ('10','add173429278422696','5','add_freeze','1734292784','发布拍卖：“<a href="/Auction/details/pid/15/aptitude/1.html">15世紀早期《銅鎏金西方廣目天王像》</a>”冻结','0.00','200.00','4998200.00','5000000.00');
INSERT INTO `on_member_limsum_bill` VALUES ('11','add173429379586557','5','add_freeze','1734293795','发布拍卖：“<a href="/Auction/details/pid/16/aptitude/1.html">類烏齊寺第二任法台烏堅貢布肖像唐卡</a>”冻结','0.00','50000.00','4948200.00','5000000.00');
INSERT INTO `on_member_limsum_bill` VALUES ('12','add173429500062423','5','add_freeze','1734295000','发布拍卖：“<a href="/Auction/details/pid/17/aptitude/1.html">15世紀早期《銅鎏金西方廣目天王像》</a>”冻结','0.00','50000.00','4898200.00','5000000.00');
INSERT INTO `on_member_limsum_bill` VALUES ('13','add173429553524353','5','add_freeze','1734295535','发布拍卖：“<a href="/Auction/details/pid/18/aptitude/1.html">類烏齊寺第二任法台烏堅貢布肖像唐卡</a>”冻结','0.00','50000.00','4848200.00','5000000.00');
INSERT INTO `on_member_limsum_bill` VALUES ('14','add17342957145041','5','add_freeze','1734295714','发布拍卖：“<a href="/Auction/details/pid/19/aptitude/1.html">銅錯銀鎏金鎏銀持拂塵者像</a>”冻结','0.00','50000.00','4798200.00','5000000.00');
INSERT INTO `on_member_limsum_bill` VALUES ('15','add173429724831611','5','add_freeze','1734297248','发布拍卖：“<a href="/Auction/details/pid/20/aptitude/1.html">巴拿马-太平洋纪念币</a>”冻结','0.00','50000.00','4748200.00','5000000.00');
INSERT INTO `on_member_limsum_bill` VALUES ('16','add173429772891742','5','add_freeze','1734297728','发布拍卖：“<a href="/Auction/details/pid/21/aptitude/1.html">清乾隆 御制铜胎掐丝珐琅双人托长方形炉</a>”冻结','0.00','50000.00','4698200.00','5000000.00');
INSERT INTO `on_member_limsum_bill` VALUES ('17','add173446912117826','5','add_freeze','1734469121','发布拍卖：“<a href="/Auction/details/pid/24/aptitude/1.html">十六世纪 铜鎏金密集文殊金刚像</a>”冻结','0.00','50000.00','4648200.00','5000000.00');
INSERT INTO `on_member_limsum_bill` VALUES ('18','add173446944284865','5','add_freeze','1734469442','发布拍卖：“<a href="/Auction/details/pid/25/aptitude/1.html">十四世纪 铜鎏金佛陀像</a>”冻结','0.00','50000.00','4598200.00','5000000.00');
INSERT INTO `on_member_limsum_bill` VALUES ('19','add173446970274147','5','add_freeze','1734469702','发布拍卖：“<a href="/Auction/details/pid/26/aptitude/1.html">十三世纪 铜鎏金释迦牟尼像</a>”冻结','0.00','50000.00','4548200.00','5000000.00');
INSERT INTO `on_member_limsum_bill` VALUES ('20','add173447058321723','5','add_freeze','1734470583','发布拍卖：“<a href="/Auction/details/pid/27/aptitude/1.html">大清雍正六年时宪历</a>”冻结','0.00','50000.00','4498200.00','5000000.00');
INSERT INTO `on_member_limsum_bill` VALUES ('21','add173493287814321','5','add_freeze','1734932878','发布拍卖：“<a href="/Auction/details/pid/28/aptitude/1.html">元代青花龙纹玉壶春瓶</a>”冻结','0.00','50000.00','4448200.00','5000000.00');
INSERT INTO `on_member_limsum_bill` VALUES ('22','add173495919462678','5','add_freeze','1734959194','发布拍卖：“<a href="/Auction/details/pid/29/aptitude/1.html">清乾隆珐瑯彩描金雙耳花卉紋瓶</a>”冻结','0.00','50000.00','4398200.00','5000000.00');
INSERT INTO `on_member_limsum_bill` VALUES ('23','add173509741513766','5','add_freeze','1735097415','发布拍卖：“<a href="/Auction/details/pid/30/aptitude/1.html">丁雲鵬《佛像圖》</a>”冻结','0.00','50000.00','4348200.00','5000000.00');
INSERT INTO `on_member_limsum_bill` VALUES ('24','add173529128396121','5','add_freeze','1735291283','发布拍卖：“<a href="/Auction/details/pid/31/aptitude/1.html">清乾隆 孔雀藍地描金折枝花卉紋雙耳瓶</a>”冻结','0.00','50000.00','4298200.00','5000000.00');
INSERT INTO `on_member_limsum_bill` VALUES ('25','add173532546934198','5','add_freeze','1735325469','发布拍卖：“<a href="/Auction/details/pid/32/aptitude/1.html">STEGOSAURUS（劍龍）</a>”冻结','0.00','50000.00','4248200.00','5000000.00');
INSERT INTO `on_member_limsum_bill` VALUES ('26','add173532685611219','5','add_freeze','1735326856','发布拍卖：“<a href="/Auction/details/pid/33/aptitude/1.html">成年同幼年異特龍</a>”冻结','0.00','50000.00','4198200.00','5000000.00');
INSERT INTO `on_member_limsum_bill` VALUES ('27','add173545404726661','5','add_freeze','1735454047','发布拍卖：“<a href="/Auction/details/pid/34/aptitude/1.html">清康熙青花人物雙耳瓶</a>”冻结','0.00','50000.00','4148200.00','5000000.00');
INSERT INTO `on_member_limsum_bill` VALUES ('28','add173547283209790','5','add_freeze','1735472832','发布拍卖：“<a href="/Auction/details/pid/35/aptitude/1.html">元 青花蒙括將軍玉壺春瓶</a>”冻结','0.00','50000.00','4098200.00','5000000.00');
INSERT INTO `on_member_limsum_bill` VALUES ('29','add173553937395783','5','add_freeze','1735539373','发布拍卖：“<a href="/Auction/details/pid/36/aptitude/1.html">弗雷德里克·伦赛特（1861-1909）</a>”冻结','0.00','50000.00','4048200.00','5000000.00');
INSERT INTO `on_member_limsum_bill` VALUES ('30','add17355406363440','5','add_freeze','1735540636','发布拍卖：“<a href="/Auction/details/pid/37/aptitude/1.html">弗雷德里克·伦赛特（1861-1909）</a>”冻结','0.00','50000.00','3998200.00','5000000.00');
INSERT INTO `on_member_limsum_bill` VALUES ('31','add173554108461154','5','add_freeze','1735541084','发布拍卖：“<a href="/Auction/details/pid/38/aptitude/1.html">托馬斯·莫蘭（1837-1926）</a>”冻结','0.00','50000.00','3948200.00','5000000.00');
INSERT INTO `on_member_limsum_bill` VALUES ('32','add173554138008217','5','add_freeze','1735541380','发布拍卖：“<a href="/Auction/details/pid/39/aptitude/1.html">托馬斯·希爾（1829-1908）</a>”冻结','0.00','50000.00','3898200.00','5000000.00');
INSERT INTO `on_member_limsum_bill` VALUES ('33','add173554163327164','5','add_freeze','1735541633','发布拍卖：“<a href="/Auction/details/pid/40/aptitude/1.html">阿尔伯特·比尔施塔特 1830-1902</a>”冻结','0.00','50000.00','3848200.00','5000000.00');
INSERT INTO `on_member_limsum_bill` VALUES ('34','add173554220478347','5','add_freeze','1735542204','发布拍卖：“<a href="/Auction/details/pid/41/aptitude/1.html">約翰·弗雷德里克·肯塞特 1816</a>”冻结','0.00','50000.00','3798200.00','5000000.00');
INSERT INTO `on_member_limsum_bill` VALUES ('35','add173554260469566','5','add_freeze','1735542604','发布拍卖：“<a href="/Auction/details/pid/42/aptitude/1.html">馬丁·約翰遜·海德（1819-1904）</a>”冻结','0.00','50000.00','3748200.00','5000000.00');
INSERT INTO `on_member_limsum_bill` VALUES ('36','add173555247814140','5','add_freeze','1735552478','发布拍卖：“<a href="/Auction/details/pid/43/aptitude/1.html">清乾隆 琺瑯彩贲巴壺</a>”冻结','0.00','50000.00','3698200.00','5000000.00');
INSERT INTO `on_member_limsum_bill` VALUES ('37','add173555326188093','5','add_freeze','1735553261','发布拍卖：“<a href="/Auction/details/pid/44/aptitude/1.html">清康熙 撇口瓶</a>”冻结','0.00','50000.00','3648200.00','5000000.00');
INSERT INTO `on_member_limsum_bill` VALUES ('38','auf173578443039033','5','add_unfreeze','1735784430','拍品流拍<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/44/aptitude/1.html">【清康熙 撇口瓶】</a>','50000.00','0.00','3698200.00','5000000.00');


# 数据库表：on_member_pledge_bill 数据信息
INSERT INTO `on_member_pledge_bill` VALUES ('1','aad173392708791721','1','admin_deposit','1733927087','','100000.00','0.00','100000.00','100000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('2','add173393510447294','1','add_freeze','1733935104','发布拍卖：“<a href="/Auction/details/pid/1/aptitude/1.html">清康熙·窑变釉鹿头尊</a>”冻结','0.00','200.00','99800.00','100000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('3','aad173409862335995','4','admin_deposit','1734098623','','100000.00','0.00','100000.00','100000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('4','add17341061059649','4','add_freeze','1734106105','发布拍卖：“<a href="/Auction/details/pid/2/aptitude/1.html"> 清雍正 青花纏枝花卉紋蒜頭大瓶</a>”冻结','0.00','200.00','99800.00','100000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('5','add173428169232491','4','add_freeze','1734281692','发布拍卖：“<a href="/Auction/details/pid/4/aptitude/1.html">徐悲鸿 奔马 立轴</a>”冻结','0.00','200.00','99600.00','100000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('6','add173428462447942','4','add_freeze','1734284624','发布拍卖：“<a href="/Auction/details/pid/5/aptitude/1.html">明宣德青花暗花【海水游龙】图高足杯</a>”冻结','0.00','200.00','99400.00','100000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('7','add173428753012229','1','add_freeze','1734287530','发布拍卖：“<a href="/Auction/details/pid/6/aptitude/1.html">清乾隆 御制洋彩紫红锦地乾坤交泰转旋瓶</a>”冻结','0.00','200.00','99600.00','100000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('8','aad173428890864592','5','admin_deposit','1734288908','','3000000.00','0.00','3000000.00','3000000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('9','aad173446747456647','9','admin_deposit','1734467474','','500000.00','0.00','500000.00','500000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('10','plg173446761122165','9','bid_freeze','1734467611','参拍“<a href="/Auction/details/pid/23/aptitude/1.html">乾隆帝 御笔行书〈御制澄怀堂诗〉七言联 </a>”冻结拍品保证金！','0.00','50000.00','450000.00','500000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('11','plg173446766035713','9','bid_freeze','1734467660','参拍“<a href="/Auction/details/pid/4/aptitude/1.html">徐悲鸿 奔马 立轴</a>”冻结拍品保证金！','0.00','30000.00','420000.00','500000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('12','plg17344676844281','9','bid_freeze','1734467684','参拍“<a href="/Auction/details/pid/3/aptitude/1.html">何海霞 万山红遍</a>”冻结拍品保证金！','0.00','30000.00','390000.00','500000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('13','plg173446770921979','9','bid_freeze','1734467709','参拍“<a href="/Auction/details/pid/21/aptitude/1.html">清乾隆 御制铜胎掐丝珐琅双人托长方形炉</a>”冻结拍品保证金！','0.00','50000.00','340000.00','500000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('14','aad173485794192471','12','admin_deposit','1734857941','','1200000.00','0.00','1200000.00','1200000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('15','plg173485795953498','12','bid_freeze','1734857959','参拍“<a href="/Auction/details/pid/27/aptitude/1.html"> 大清雍正六年時憲歷</a>”冻结拍品保证金！','0.00','50000.00','1150000.00','1200000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('16','plg173485797990750','12','bid_freeze','1734857979','参拍“<a href="/Auction/details/pid/23/aptitude/1.html">乾隆帝 御笔行书〈御制澄怀堂诗〉七言联 </a>”冻结拍品保证金！','0.00','50000.00','1100000.00','1200000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('17','plg17348580013693','12','bid_freeze','1734858001','参拍“<a href="/Auction/details/pid/21/aptitude/1.html">清乾隆 御制铜胎掐丝珐琅双人托长方形炉</a>”冻结拍品保证金！','0.00','50000.00','1050000.00','1200000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('18','aad173492878271340','16','admin_deposit','1734928782','','10.00','0.00','10.00','10.00');
INSERT INTO `on_member_pledge_bill` VALUES ('19','sfr173493084671724','16','extract_freeze','1734930846','提现暂时冻结可用余额，等待提现完成扣除！','0.00','10.00','0.00','10.00');
INSERT INTO `on_member_pledge_bill` VALUES ('20','suf173493118594113','16','admin_unfreeze','1734931185','您好，我司暫時不支持使用支付寶進行提款，請您綁定銀行卡進行提款即可，謝謝。','10.00','0.00','10.00','10.00');
INSERT INTO `on_member_pledge_bill` VALUES ('21','sfr173493175442085','16','extract_freeze','1734931754','提现暂时冻结可用余额，等待提现完成扣除！','0.00','10.00','0.00','10.00');
INSERT INTO `on_member_pledge_bill` VALUES ('22','rtk173493204227795','16','extract','1734932042','您好，已經為您成功匯款，請您進入手機銀行查看即可。','0.00','10.00','0.00','0.00');
INSERT INTO `on_member_pledge_bill` VALUES ('23','aad173495209715597','17','admin_deposit','1734952097','','10.00','0.00','10.00','10.00');
INSERT INTO `on_member_pledge_bill` VALUES ('24','sfr17349681058580','17','extract_freeze','1734968105','提现暂时冻结可用余额，等待提现完成扣除！','0.00','10.00','0.00','10.00');
INSERT INTO `on_member_pledge_bill` VALUES ('25','suf173499964463970','17','admin_unfreeze','1734999644','您好，我司暫時不支持使用支付寶進行提款，請您綁定銀行卡進行提款即可，謝謝。','10.00','0.00','10.00','10.00');
INSERT INTO `on_member_pledge_bill` VALUES ('26','aad173501360878810','22','admin_deposit','1735013608','','1000000.00','0.00','1000000.00','1000000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('27','plg17350138793297','22','bid_freeze','1735013879','参拍“<a href="/Auction/details/pid/28/aptitude/1.html">元代 青花龙纹玉壶春瓶</a>”冻结拍品保证金！','0.00','50000.00','950000.00','1000000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('28','sfr173501389901535','17','extract_freeze','1735013899','提现暂时冻结可用余额，等待提现完成扣除！','0.00','10.00','0.00','10.00');
INSERT INTO `on_member_pledge_bill` VALUES ('29','plg173502208129213','22','bid_freeze','1735022081','参拍“<a href="/Auction/details/pid/29/aptitude/1.html">清乾隆 珐瑯彩描金雙耳花卉紋瓶</a>”冻结拍品保证金！','0.00','50000.00','900000.00','1000000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('30','plg173502293650851','12','bid_freeze','1735022936','参拍“<a href="/Auction/details/pid/29/aptitude/1.html">清乾隆 珐瑯彩描金雙耳花卉紋瓶</a>”冻结拍品保证金！','0.00','50000.00','1000000.00','1200000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('31','aad173504071191271','23','admin_deposit','1735040711','','10.00','0.00','10.00','10.00');
INSERT INTO `on_member_pledge_bill` VALUES ('32','sfr173504154071198','23','extract_freeze','1735041540','提现暂时冻结可用余额，等待提现完成扣除！','0.00','10.00','0.00','10.00');
INSERT INTO `on_member_pledge_bill` VALUES ('33','plg173509862190368','9','bid_freeze','1735098621','参拍“<a href="/Auction/details/pid/29/aptitude/1.html">清乾隆 珐瑯彩描金雙耳花卉紋瓶</a>”冻结拍品保证金！','0.00','50000.00','290000.00','500000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('34','plg173510078932220','12','bid_freeze','1735100789','参拍“<a href="/Auction/details/pid/28/aptitude/1.html">元代 青花龙纹玉壶春瓶</a>”冻结拍品保证金！','0.00','50000.00','950000.00','1200000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('35','plg173510470419898','9','bid_freeze','1735104704','参拍“<a href="/Auction/details/pid/28/aptitude/1.html">元代 青花龙纹玉壶春瓶</a>”冻结拍品保证金！','0.00','50000.00','240000.00','500000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('36','aad173520010836689','26','admin_deposit','1735200108','','1000000.00','0.00','1000000.00','1000000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('37','guf17352144048571','22','bid_unfreeze','1735214404','拍品【<a  target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/28/aptitude/1.html">元代 青花龙纹玉壶春瓶</a>】结束','50000.00','0.00','950000.00','1000000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('38','guf173521440486146','9','bid_unfreeze','1735214404','拍品【<a  target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/28/aptitude/1.html">元代 青花龙纹玉壶春瓶</a>】结束','50000.00','0.00','290000.00','500000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('39','aad173522013901979','27','admin_deposit','1735220139','','20000.00','0.00','20000.00','20000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('40','aad173522071978019','27','admin_deposit','1735220719','','1350000.00','0.00','1370000.00','1370000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('41','aad173522442607672','28','admin_deposit','1735224426','','30000.00','0.00','30000.00','30000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('42','ami173527112711236','28','admin_deduct','1735271127','','0.00','30000.00','0.00','0.00');
INSERT INTO `on_member_pledge_bill` VALUES ('43','aad173527113934985','28','admin_deposit','1735271139','','40000.00','0.00','40000.00','40000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('44','aad173527151696782','28','admin_deposit','1735271516','','1550000.00','0.00','1590000.00','1590000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('45','guf173528754867589','22','bid_unfreeze','1735287548','拍品【<a  target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/29/aptitude/1.html">清乾隆 珐瑯彩描金雙耳花卉紋瓶</a>】结束','50000.00','0.00','1000000.00','1000000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('46','guf17352875486795','9','bid_unfreeze','1735287548','拍品【<a  target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/29/aptitude/1.html">清乾隆 珐瑯彩描金雙耳花卉紋瓶</a>】结束','50000.00','0.00','340000.00','500000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('47','aad173528959247918','26','admin_deposit','1735289592','','10000000.00','0.00','11000000.00','11000000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('48','aad17352897499755','12','admin_deposit','1735289749','','15000000.00','0.00','15950000.00','16200000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('49','dhk173528977344321','12','pay_pledge','1735289773','保证金抵商品：“<a href="/Home/Auction/details/pid/28/aptitude/1.html">元代 青花龙纹玉壶春瓶</a>”货款【50000.00元】！订单号：“<a href="/Home/Member/order_details/order_no/BID173521440485271/aptitude/1.html">BID173521440485271</a>”','0.00','50000.00','15950000.00','16150000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('50','yef173528977344628','12','pay_deduct','1735289773','支付商品：“<a href="/Home/Auction/details/pid/28/aptitude/1.html">元代 青花龙纹玉壶春瓶</a>”订单号：“<a href="/Home/Member/order_details/order_no/BID173521440485271/aptitude/1.html">BID173521440485271</a>”，支付成功！','0.00','1193000.00','14757000.00','14957000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('51','dhk173528978750733','12','pay_pledge','1735289787','保证金抵商品：“<a href="/Home/Auction/details/pid/29/aptitude/1.html">清乾隆 珐瑯彩描金雙耳花卉紋瓶</a>”货款【50000.00元】！订单号：“<a href="/Home/Member/order_details/order_no/BID173528754867011/aptitude/1.html">BID173528754867011</a>”','0.00','50000.00','14757000.00','14907000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('52','yef173528978751190','12','pay_deduct','1735289787','支付商品：“<a href="/Home/Auction/details/pid/29/aptitude/1.html">清乾隆 珐瑯彩描金雙耳花卉紋瓶</a>”订单号：“<a href="/Home/Member/order_details/order_no/BID173528754867011/aptitude/1.html">BID173528754867011</a>”，支付成功！','0.00','7760000.00','6997000.00','7147000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('53','plg173528980428144','12','bid_freeze','1735289804','参拍“<a href="/Auction/details/pid/30/aptitude/1.html">丁雲鵬《佛像圖》</a>”冻结拍品保证金！','0.00','50000.00','6947000.00','7147000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('54','plg173528989056974','26','bid_freeze','1735289890','参拍“<a href="/Auction/details/pid/30/aptitude/1.html">丁雲鵬《佛像圖》</a>”冻结拍品保证金！','0.00','50000.00','10950000.00','11000000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('55','plg173528993173068','26','bid_freeze','1735289931','参拍“<a href="/Auction/details/pid/2/aptitude/1.html"> 清雍正 青花纏枝花卉紋蒜頭大瓶</a>”冻结拍品保证金！','0.00','20000.00','10930000.00','11000000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('56','plg173532046496151','26','bid_freeze','1735320464','参拍“<a href="/Auction/details/pid/27/aptitude/1.html"> 大清雍正六年時憲歷</a>”冻结拍品保证金！','0.00','50000.00','10880000.00','11000000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('57','plg173532056940159','26','bid_freeze','1735320569','参拍“<a href="/Auction/details/pid/20/aptitude/1.html">巴拿馬-太平洋紀念幣</a>”冻结拍品保证金！','0.00','30000.00','10850000.00','11000000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('58','plg173532305521614','28','bid_freeze','1735323055','参拍“<a href="/Auction/details/pid/30/aptitude/1.html">丁雲鵬《佛像圖》</a>”冻结拍品保证金！','0.00','50000.00','1540000.00','1590000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('59','guf173539751445563','12','bid_unfreeze','1735397514','拍品【<a  target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/30/aptitude/1.html">丁雲鵬《佛像圖》</a>】结束','50000.00','0.00','6997000.00','7147000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('60','guf173539751445919','26','bid_unfreeze','1735397514','拍品【<a  target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/30/aptitude/1.html">丁雲鵬《佛像圖》</a>】结束','50000.00','0.00','10900000.00','11000000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('61','aad173544740416314','30','admin_deposit','1735447404','','20.00','0.00','20.00','20.00');
INSERT INTO `on_member_pledge_bill` VALUES ('62','sfr173544936077081','30','extract_freeze','1735449360','提现暂时冻结可用余额，等待提现完成扣除！','0.00','20.00','0.00','20.00');
INSERT INTO `on_member_pledge_bill` VALUES ('63','rtk173545131783859','17','extract','1735451317','','0.00','10.00','0.00','0.00');
INSERT INTO `on_member_pledge_bill` VALUES ('64','rtk173545132958767','23','extract','1735451329','','0.00','10.00','0.00','0.00');
INSERT INTO `on_member_pledge_bill` VALUES ('65','rtk173545210781671','30','extract','1735452107','已成功汇款，请登录手机银行进行查看，谢谢。','0.00','20.00','0.00','0.00');
INSERT INTO `on_member_pledge_bill` VALUES ('66','aad173547047082255','31','admin_deposit','1735470470','','10.00','0.00','10.00','10.00');
INSERT INTO `on_member_pledge_bill` VALUES ('67','sfr173547213783261','31','extract_freeze','1735472137','提现暂时冻结可用余额，等待提现完成扣除！','0.00','10.00','0.00','10.00');
INSERT INTO `on_member_pledge_bill` VALUES ('68','plg173552398253372','26','bid_freeze','1735523982','参拍“<a href="/Auction/details/pid/34/aptitude/1.html">清康熙青花人物雙耳瓶</a>”冻结拍品保证金！','0.00','50000.00','10850000.00','11000000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('69','plg173552410955677','12','bid_freeze','1735524109','参拍“<a href="/Auction/details/pid/35/aptitude/1.html">元 青花蒙括將軍玉壺春瓶</a>”冻结拍品保证金！','0.00','50000.00','6947000.00','7147000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('70','aad17355420418671','33','admin_deposit','1735542041','','10.00','0.00','10.00','10.00');
INSERT INTO `on_member_pledge_bill` VALUES ('71','rtk173554230762910','31','extract','1735542307','','0.00','10.00','0.00','0.00');
INSERT INTO `on_member_pledge_bill` VALUES ('72','aad173554234118457','32','admin_deposit','1735542341','','30.00','0.00','30.00','30.00');
INSERT INTO `on_member_pledge_bill` VALUES ('73','sfr173554367836454','33','extract_freeze','1735543678','提现暂时冻结可用余额，等待提现完成扣除！','0.00','10.00','0.00','10.00');
INSERT INTO `on_member_pledge_bill` VALUES ('74','plg173554405093938','9','bid_freeze','1735544050','参拍“<a href="/Auction/details/pid/35/aptitude/1.html">元 青花蒙括將軍玉壺春瓶</a>”冻结拍品保证金！','0.00','50000.00','290000.00','500000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('75','aad17355444889082','10','admin_deposit','1735544488','','5000000.00','0.00','5000000.00','5000000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('76','plg173554453104835','10','bid_freeze','1735544531','参拍“<a href="/Auction/details/pid/34/aptitude/1.html">清康熙青花人物雙耳瓶</a>”冻结拍品保证金！','0.00','50000.00','4950000.00','5000000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('77','rtk173555388810064','33','extract','1735553888','','0.00','10.00','0.00','0.00');
INSERT INTO `on_member_pledge_bill` VALUES ('78','aad173560471069697','36','admin_deposit','1735604710','','5000000.00','0.00','5000000.00','5000000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('79','plg173560476422462','36','bid_freeze','1735604764','参拍“<a href="/Auction/details/pid/34/aptitude/1.html">清康熙青花人物雙耳瓶</a>”冻结拍品保证金！','0.00','50000.00','4950000.00','5000000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('80','aad173560532712872','37','admin_deposit','1735605327','','5000000.00','0.00','5000000.00','5000000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('81','plg173560536678963','37','bid_freeze','1735605366','参拍“<a href="/Auction/details/pid/35/aptitude/1.html">元 青花蒙括將軍玉壺春瓶</a>”冻结拍品保证金！','0.00','50000.00','4950000.00','5000000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('82','aad173560593719538','38','admin_deposit','1735605937','','5000000.00','0.00','5000000.00','5000000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('83','plg173560632536672','38','bid_freeze','1735606325','参拍“<a href="/Auction/details/pid/7/aptitude/1.html">清乾隆《松石綠錦地浮雕金彩百壽琮式瓶》</a>”冻结拍品保证金！','0.00','50000.00','4950000.00','5000000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('84','plg17356181738064','38','bid_freeze','1735618173','参拍“<a href="/Auction/details/pid/35/aptitude/1.html">元 青花蒙括將軍玉壺春瓶</a>”冻结拍品保证金！','0.00','50000.00','4900000.00','5000000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('85','sfr173562194214095','32','extract_freeze','1735621942','提现暂时冻结可用余额，等待提现完成扣除！','0.00','30.00','0.00','30.00');
INSERT INTO `on_member_pledge_bill` VALUES ('86','rtk173563821930534','32','extract','1735638219','','0.00','30.00','0.00','0.00');
INSERT INTO `on_member_pledge_bill` VALUES ('87','guf173571328831162','26','bid_unfreeze','1735713288','拍品【<a  target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/34/aptitude/1.html">清康熙青花人物雙耳瓶</a>】结束','50000.00','0.00','10900000.00','11000000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('88','guf173571328831593','10','bid_unfreeze','1735713288','拍品【<a  target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/34/aptitude/1.html">清康熙青花人物雙耳瓶</a>】结束','50000.00','0.00','5000000.00','5000000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('89','plg173571581145783','22','bid_freeze','1735715811','参拍“<a href="/Auction/details/pid/43/aptitude/1.html">清乾隆 琺瑯彩贲巴壺</a>”冻结拍品保证金！','0.00','50000.00','950000.00','1000000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('90','guf173579770715176','12','bid_unfreeze','1735797707','拍品【<a  target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/35/aptitude/1.html">元 青花蒙括將軍玉壺春瓶</a>】结束','50000.00','0.00','6997000.00','7147000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('91','guf173579770715587','9','bid_unfreeze','1735797707','拍品【<a  target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/35/aptitude/1.html">元 青花蒙括將軍玉壺春瓶</a>】结束','50000.00','0.00','340000.00','500000.00');
INSERT INTO `on_member_pledge_bill` VALUES ('92','guf173579770715765','37','bid_unfreeze','1735797707','拍品【<a  target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/35/aptitude/1.html">元 青花蒙括將軍玉壺春瓶</a>】结束','50000.00','0.00','5000000.00','5000000.00');


# 数据库表：on_member_pledge_take 数据信息
INSERT INTO `on_member_pledge_take` VALUES ('1','16','10.00','支付宝','','朱占三','13864385388','','1734930846','1734931185','您好，我司暫時不支持使用支付寶進行提款，請您綁定銀行卡進行提款即可，謝謝。','2');
INSERT INTO `on_member_pledge_take` VALUES ('2','16','10.00','中国民生银行','山东省淄博市柳泉路分行','朱占三','6226221614275607','','1734931754','1734932042','您好，已經為您成功匯款，請您進入手機銀行查看即可。','1');
INSERT INTO `on_member_pledge_take` VALUES ('3','17','10.00','支付宝','','吴金华','支付宝','非常感谢佳士得的各位老师热情周到的服务，我是你们的新朋友，也将是永久的朋友。希望我们团结奋斗，齐心协力，共创辉煌','1734968105','1734999644','您好，我司暫時不支持使用支付寶進行提款，請您綁定銀行卡進行提款即可，謝謝。','2');
INSERT INTO `on_member_pledge_take` VALUES ('4','17','10.00',' 中国建设银行','',' 吴金华','6217000010091550171','','1735013899','1735451317','','1');
INSERT INTO `on_member_pledge_take` VALUES ('5','23','10.00','中国工商银行','山东省烟台市福山区长江路支行','李柏山','6217211606018021868','','1735041540','1735451329','','1');
INSERT INTO `on_member_pledge_take` VALUES ('6','30','20.00','梅县客家村镇银行','梅县客家村镇银行','陈善清','6213930010000218252','','1735449360','1735452107','已成功汇款，请登录手机银行进行查看，谢谢。','1');
INSERT INTO `on_member_pledge_take` VALUES ('7','31','10.00','中国农业很行','乔司分行','陈贵福','6228480329561879076','好的','1735472137','1735542307','','1');
INSERT INTO `on_member_pledge_take` VALUES ('8','33','10.00','农业银行','农业银行','韩继太','6228230025044675064','心想事成','1735543678','1735553888','','1');
INSERT INTO `on_member_pledge_take` VALUES ('9','32','30.00','中国银行','陕西省宝鸡市中国银行','王志翔','6216603600001781461','','1735621942','1735638219','','1');


# 数据库表：on_member_weixin 数据信息


# 数据库表：on_mysms 数据信息
INSERT INTO `on_mysms` VALUES ('1','1','0','0','0','0','管理员充值','1','0','管理员充值余额【100000元】，单号aad173392708791721','1733927087');
INSERT INTO `on_mysms` VALUES ('2','1','0','0','0','0','发布拍卖冻结保证金','1','0','发布拍卖：“<a href="/Auction/details/pid/1/aptitude/1.html">清康熙·窑变釉鹿头尊</a>”冻结保证金【200.00元】','1733935104');
INSERT INTO `on_mysms` VALUES ('3','4','0','0','0','0','管理员充值','0','0','管理员充值余额【100000元】，单号aad173409862335995','1734098623');
INSERT INTO `on_mysms` VALUES ('4','4','0','0','0','0','发布拍卖冻结保证金','0','0','发布拍卖：“<a href="/Auction/details/pid/2/aptitude/1.html"> 清雍正 青花纏枝花卉紋蒜頭大瓶</a>”冻结保证金【200.00元】','1734106105');
INSERT INTO `on_mysms` VALUES ('5','4','0','0','0','0','发布拍卖冻结保证金','0','0','发布拍卖：“<a href="/Auction/details/pid/4/aptitude/1.html">徐悲鸿 奔马 立轴</a>”冻结保证金【200.00元】','1734281692');
INSERT INTO `on_mysms` VALUES ('6','4','0','0','0','0','发布拍卖冻结保证金','0','0','发布拍卖：“<a href="/Auction/details/pid/5/aptitude/1.html">明宣德青花暗花【海水游龙】图高足杯</a>”冻结保证金【200.00元】','1734284624');
INSERT INTO `on_mysms` VALUES ('7','1','0','0','0','0','发布拍卖冻结保证金','0','0','发布拍卖：“<a href="/Auction/details/pid/6/aptitude/1.html">清乾隆 御制洋彩紫红锦地乾坤交泰转旋瓶</a>”冻结保证金【200.00元】','1734287530');
INSERT INTO `on_mysms` VALUES ('8','4','0','0','0','0','拍品上架提醒','0','0','卖家[新鸿古玩店]的拍品“<a href="https://wmiw.ebnnw.cn/Home/Auction/details/pid/6/aptitude/1.html">【清乾隆 御制洋彩紫红锦地乾坤交泰转旋瓶】</a>”已上架！','1734287530');
INSERT INTO `on_mysms` VALUES ('9','5','0','0','0','0','管理员充值','0','0','管理员充值信用额度【5000000元】，单号aad173428883473692','1734288834');
INSERT INTO `on_mysms` VALUES ('10','5','0','0','0','0','管理员充值','0','0','管理员充值余额【3000000元】，单号aad173428890864592','1734288908');
INSERT INTO `on_mysms` VALUES ('11','5','0','0','0','0','发布拍卖冻结信誉额度','0','0','发布拍卖：“<a href="/Auction/details/pid/7/aptitude/1.html">清乾隆《松石綠錦地浮雕金彩百壽琮式瓶》</a>”冻结信誉额度【200.00元】','1734290379');
INSERT INTO `on_mysms` VALUES ('12','5','0','0','0','0','发布拍卖冻结信誉额度','0','0','发布拍卖：“<a href="/Auction/details/pid/8/aptitude/1.html">清乾隆·淡綠彩浮雕礬紅金彩萬壽八方瓶</a>”冻结信誉额度【200.00元】','1734290764');
INSERT INTO `on_mysms` VALUES ('13','5','0','0','0','0','发布拍卖冻结信誉额度','0','0','发布拍卖：“<a href="/Auction/details/pid/9/aptitude/1.html">清雍正 爐鈞釉仿古尊</a>”冻结信誉额度【200.00元】','1734290954');
INSERT INTO `on_mysms` VALUES ('14','5','0','0','0','0','发布拍卖冻结信誉额度','0','0','发布拍卖：“<a href="/Auction/details/pid/10/aptitude/1.html">清乾隆 仿官釉六棱貫耳瓶</a>”冻结信誉额度【200.00元】','1734291153');
INSERT INTO `on_mysms` VALUES ('15','5','0','0','0','0','发布拍卖冻结信誉额度','0','0','发布拍卖：“<a href="/Auction/details/pid/11/aptitude/1.html">17世紀 黃花梨軸門圓角櫃</a>”冻结信誉额度【200.00元】','1734291243');
INSERT INTO `on_mysms` VALUES ('16','5','0','0','0','0','发布拍卖冻结信誉额度','0','0','发布拍卖：“<a href="/Auction/details/pid/12/aptitude/1.html">傅抱石 苦瓜和尚詩意圖</a>”冻结信誉额度【200.00元】','1734291474');
INSERT INTO `on_mysms` VALUES ('17','5','0','0','0','0','发布拍卖冻结信誉额度','0','0','发布拍卖：“<a href="/Auction/details/pid/13/aptitude/1.html">劉野 雷鋒叔叔</a>”冻结信誉额度【200.00元】','1734291678');
INSERT INTO `on_mysms` VALUES ('18','5','0','0','0','0','发布拍卖冻结信誉额度','0','0','发布拍卖：“<a href="/Auction/details/pid/14/aptitude/1.html">曾梵志 《三年級一班 第6號》</a>”冻结信誉额度【200.00元】','1734291815');
INSERT INTO `on_mysms` VALUES ('19','5','0','0','0','0','发布拍卖冻结信誉额度','0','0','发布拍卖：“<a href="/Auction/details/pid/15/aptitude/1.html">15世紀早期《銅鎏金西方廣目天王像》</a>”冻结信誉额度【200.00元】','1734292784');
INSERT INTO `on_mysms` VALUES ('20','5','0','0','0','0','发布拍卖冻结信誉额度','0','0','发布拍卖：“<a href="/Auction/details/pid/16/aptitude/1.html">類烏齊寺第二任法台烏堅貢布肖像唐卡</a>”冻结信誉额度【50000.00元】','1734293795');
INSERT INTO `on_mysms` VALUES ('21','5','0','0','0','0','发布拍卖冻结信誉额度','0','0','发布拍卖：“<a href="/Auction/details/pid/17/aptitude/1.html">15世紀早期《銅鎏金西方廣目天王像》</a>”冻结信誉额度【50000.00元】','1734295000');
INSERT INTO `on_mysms` VALUES ('22','5','0','0','0','0','发布拍卖冻结信誉额度','0','0','发布拍卖：“<a href="/Auction/details/pid/18/aptitude/1.html">類烏齊寺第二任法台烏堅貢布肖像唐卡</a>”冻结信誉额度【50000.00元】','1734295535');
INSERT INTO `on_mysms` VALUES ('23','5','0','0','0','0','发布拍卖冻结信誉额度','0','0','发布拍卖：“<a href="/Auction/details/pid/19/aptitude/1.html">銅錯銀鎏金鎏銀持拂塵者像</a>”冻结信誉额度【50000.00元】','1734295714');
INSERT INTO `on_mysms` VALUES ('24','5','0','0','0','0','发布拍卖冻结信誉额度','0','0','发布拍卖：“<a href="/Auction/details/pid/20/aptitude/1.html">巴拿马-太平洋纪念币</a>”冻结信誉额度【50000.00元】','1734297248');
INSERT INTO `on_mysms` VALUES ('25','5','0','0','0','0','发布拍卖冻结信誉额度','0','0','发布拍卖：“<a href="/Auction/details/pid/21/aptitude/1.html">清乾隆 御制铜胎掐丝珐琅双人托长方形炉</a>”冻结信誉额度【50000.00元】','1734297728');
INSERT INTO `on_mysms` VALUES ('26','9','0','0','0','0','管理员充值','0','0','管理员充值余额【500000元】，单号aad173446747456647','1734467474');
INSERT INTO `on_mysms` VALUES ('27','9','0','0','0','0','参与拍品拍竞价冻结保证金','0','0','参拍“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/23/aptitude/1.html">乾隆帝 御笔行书〈御制澄怀堂诗〉七言联 </a>”冻结保证金【50000.00元】','1734467611');
INSERT INTO `on_mysms` VALUES ('28','5','0','0','0','0','拍品出价更新','0','0','拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/23/aptitude/1.html">乾隆帝 御笔行书〈御制澄怀堂诗〉七言联 </a>”当前价【7200000.00元】，目前领先','1734467611');
INSERT INTO `on_mysms` VALUES ('29','9','0','0','0','0','竞拍出价成功','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/23/aptitude/1.html">乾隆帝 御笔行书〈御制澄怀堂诗〉七言联 </a>”出价【7200000.00元】成功！','1734467611');
INSERT INTO `on_mysms` VALUES ('30','4','0','0','0','0','拍品出价更新','0','0','拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/4/aptitude/1.html">徐悲鸿 奔马 立轴</a>”当前价【2800000.00元】，目前领先','1734467660');
INSERT INTO `on_mysms` VALUES ('31','9','0','0','0','0','竞拍出价成功','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/4/aptitude/1.html">徐悲鸿 奔马 立轴</a>”出价【2800000.00元】成功！','1734467660');
INSERT INTO `on_mysms` VALUES ('32','9','0','0','0','0','参与拍品拍竞价冻结保证金','0','0','参拍“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/4/aptitude/1.html">徐悲鸿 奔马 立轴</a>”冻结保证金【30000.00元】','1734467660');
INSERT INTO `on_mysms` VALUES ('33','4','0','0','0','0','拍品出价更新','0','0','拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/3/aptitude/1.html">何海霞 万山红遍</a>”当前价【4800000.00元】，目前领先','1734467684');
INSERT INTO `on_mysms` VALUES ('34','9','0','0','0','0','竞拍出价成功','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/3/aptitude/1.html">何海霞 万山红遍</a>”出价【4800000.00元】成功！','1734467684');
INSERT INTO `on_mysms` VALUES ('35','9','0','0','0','0','参与拍品拍竞价冻结保证金','0','0','参拍“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/3/aptitude/1.html">何海霞 万山红遍</a>”冻结保证金【30000.00元】','1734467684');
INSERT INTO `on_mysms` VALUES ('36','5','0','0','0','0','拍品出价更新','1','0','拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/21/aptitude/1.html">清乾隆 御制铜胎掐丝珐琅双人托长方形炉</a>”当前价【5300000.00元】，目前领先','1734467709');
INSERT INTO `on_mysms` VALUES ('37','9','0','0','0','0','竞拍出价成功','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/21/aptitude/1.html">清乾隆 御制铜胎掐丝珐琅双人托长方形炉</a>”出价【5300000.00元】成功！','1734467709');
INSERT INTO `on_mysms` VALUES ('38','9','0','0','0','0','参与拍品拍竞价冻结保证金','0','0','参拍“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/21/aptitude/1.html">清乾隆 御制铜胎掐丝珐琅双人托长方形炉</a>”冻结保证金【50000.00元】','1734467709');
INSERT INTO `on_mysms` VALUES ('39','5','0','0','0','0','发布拍卖冻结信誉额度','0','0','发布拍卖：“<a href="/Auction/details/pid/24/aptitude/1.html">十六世纪 铜鎏金密集文殊金刚像</a>”冻结信誉额度【50000.00元】','1734469121');
INSERT INTO `on_mysms` VALUES ('40','9','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/24/aptitude/1.html">【十六世纪 铜鎏金密集文殊金刚像】</a>”已上架！','1734469121');
INSERT INTO `on_mysms` VALUES ('41','5','0','0','0','0','发布拍卖冻结信誉额度','0','0','发布拍卖：“<a href="/Auction/details/pid/25/aptitude/1.html">十四世纪 铜鎏金佛陀像</a>”冻结信誉额度【50000.00元】','1734469442');
INSERT INTO `on_mysms` VALUES ('42','9','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/25/aptitude/1.html">【十四世纪 铜鎏金佛陀像】</a>”已上架！','1734469442');
INSERT INTO `on_mysms` VALUES ('43','9','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/25/aptitude/1.html">【十四世纪 铜鎏金佛陀像】</a>”已上架！','1734469454');
INSERT INTO `on_mysms` VALUES ('44','5','0','0','0','0','发布拍卖冻结信誉额度','0','0','发布拍卖：“<a href="/Auction/details/pid/26/aptitude/1.html">十三世纪 铜鎏金释迦牟尼像</a>”冻结信誉额度【50000.00元】','1734469702');
INSERT INTO `on_mysms` VALUES ('45','9','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/26/aptitude/1.html">【十三世纪 铜鎏金释迦牟尼像】</a>”已上架！','1734469702');
INSERT INTO `on_mysms` VALUES ('46','5','0','0','0','0','发布拍卖冻结信誉额度','0','0','发布拍卖：“<a href="/Auction/details/pid/27/aptitude/1.html">大清雍正六年时宪历</a>”冻结信誉额度【50000.00元】','1734470583');
INSERT INTO `on_mysms` VALUES ('47','9','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/27/aptitude/1.html">【大清雍正六年时宪历】</a>”已上架！','1734470583');
INSERT INTO `on_mysms` VALUES ('48','12','0','0','0','0','管理员充值','0','0','管理员充值余额【1200000元】，单号aad173485794192471','1734857941');
INSERT INTO `on_mysms` VALUES ('49','5','0','0','0','0','拍品出价更新','0','0','拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/27/aptitude/1.html"> 大清雍正六年時憲歷</a>”当前价【5500000.00元】，目前领先','1734857959');
INSERT INTO `on_mysms` VALUES ('50','12','0','0','0','0','竞拍出价成功','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/27/aptitude/1.html"> 大清雍正六年時憲歷</a>”出价【5500000.00元】成功！','1734857959');
INSERT INTO `on_mysms` VALUES ('51','12','0','0','0','0','参与拍品拍竞价冻结保证金','0','0','参拍“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/27/aptitude/1.html"> 大清雍正六年時憲歷</a>”冻结保证金【50000.00元】','1734857959');
INSERT INTO `on_mysms` VALUES ('52','9','0','0','0','0','竞拍出价被超越','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/23/aptitude/1.html">乾隆帝 御笔行书〈御制澄怀堂诗〉七言联 </a>”出价【7200000.00元】已被超过。','1734857980');
INSERT INTO `on_mysms` VALUES ('53','5','0','0','0','0','拍品出价更新','0','0','拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/23/aptitude/1.html">乾隆帝 御笔行书〈御制澄怀堂诗〉七言联 </a>”当前价【7700000.00元】，目前领先','1734857980');
INSERT INTO `on_mysms` VALUES ('54','12','0','0','0','0','竞拍出价成功','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/23/aptitude/1.html">乾隆帝 御笔行书〈御制澄怀堂诗〉七言联 </a>”出价【7700000.00元】成功！','1734857980');
INSERT INTO `on_mysms` VALUES ('55','12','0','0','0','0','参与拍品拍竞价冻结保证金','0','0','参拍“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/23/aptitude/1.html">乾隆帝 御笔行书〈御制澄怀堂诗〉七言联 </a>”冻结保证金【50000.00元】','1734857980');
INSERT INTO `on_mysms` VALUES ('56','12','0','0','0','0','竞拍出价被超越','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/21/aptitude/1.html">清乾隆 御制铜胎掐丝珐琅双人托长方形炉</a>”出价【5800000.00元】已被超过。','1734858001');
INSERT INTO `on_mysms` VALUES ('57','5','0','0','0','0','拍品出价更新','0','0','拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/21/aptitude/1.html">清乾隆 御制铜胎掐丝珐琅双人托长方形炉</a>”当前价【6300000.00元】，目前领先','1734858001');
INSERT INTO `on_mysms` VALUES ('58','9','0','0','0','0','竞拍出价成功','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/21/aptitude/1.html">清乾隆 御制铜胎掐丝珐琅双人托长方形炉</a>”出价【6300000.00元】成功！','1734858001');
INSERT INTO `on_mysms` VALUES ('59','12','0','0','0','0','参与拍品拍竞价冻结保证金','0','0','参拍“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/21/aptitude/1.html">清乾隆 御制铜胎掐丝珐琅双人托长方形炉</a>”冻结保证金【50000.00元】','1734858001');
INSERT INTO `on_mysms` VALUES ('60','16','0','0','0','0','管理员充值','1','0','管理员充值余额【10元】，单号aad173492878271340','1734928782');
INSERT INTO `on_mysms` VALUES ('61','16','0','0','0','0','提现冻结','1','0','提现暂时冻结可用余额，等待提现完成扣除！','1734930846');
INSERT INTO `on_mysms` VALUES ('62','16','0','0','0','0','系统发送','1','0','网站驳回了您10.00元提现申请！解冻保证金10.00元！备注：','1734931185');
INSERT INTO `on_mysms` VALUES ('63','16','0','0','0','0','提现冻结','1','0','提现暂时冻结可用余额，等待提现完成扣除！','1734931754');
INSERT INTO `on_mysms` VALUES ('64','16','0','0','0','0','系统发送','1','0','管理员已同意您的10.00元保证金提现申请！即将为您转账请注意查收！备注：10.00','1734932042');
INSERT INTO `on_mysms` VALUES ('65','5','0','0','0','0','发布拍卖冻结信誉额度','0','0','发布拍卖：“<a href="/Auction/details/pid/28/aptitude/1.html">元代青花龙纹玉壶春瓶</a>”冻结信誉额度【50000.00元】','1734932878');
INSERT INTO `on_mysms` VALUES ('66','9','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/28/aptitude/1.html">【元代青花龙纹玉壶春瓶】</a>”已上架！','1734932878');
INSERT INTO `on_mysms` VALUES ('67','12','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/28/aptitude/1.html">【元代青花龙纹玉壶春瓶】</a>”已上架！','1734932878');
INSERT INTO `on_mysms` VALUES ('68','17','0','0','0','0','管理员充值','0','0','管理员充值余额【10元】，单号aad173495209715597','1734952097');
INSERT INTO `on_mysms` VALUES ('69','5','0','0','0','0','发布拍卖冻结信誉额度','0','0','发布拍卖：“<a href="/Auction/details/pid/29/aptitude/1.html">清乾隆珐瑯彩描金雙耳花卉紋瓶</a>”冻结信誉额度【50000.00元】','1734959194');
INSERT INTO `on_mysms` VALUES ('70','9','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/29/aptitude/1.html">【清乾隆珐瑯彩描金雙耳花卉紋瓶】</a>”已上架！','1734959194');
INSERT INTO `on_mysms` VALUES ('71','12','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/29/aptitude/1.html">【清乾隆珐瑯彩描金雙耳花卉紋瓶】</a>”已上架！','1734959194');
INSERT INTO `on_mysms` VALUES ('72','17','0','0','0','0','提现冻结','0','0','提现暂时冻结可用余额，等待提现完成扣除！','1734968105');
INSERT INTO `on_mysms` VALUES ('73','17','0','0','0','0','系统发送','0','0','网站驳回了您10.00元提现申请！解冻保证金10.00元！备注：','1734999644');
INSERT INTO `on_mysms` VALUES ('74','22','0','0','0','0','管理员充值','1','0','管理员充值余额【1000000元】，单号aad173501360878810','1735013608');
INSERT INTO `on_mysms` VALUES ('75','22','0','0','0','0','参与拍品拍竞价冻结保证金','0','0','参拍“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/28/aptitude/1.html">元代 青花龙纹玉壶春瓶</a>”冻结保证金【50000.00元】','1735013879');
INSERT INTO `on_mysms` VALUES ('76','5','0','0','0','0','拍品出价更新','0','0','拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/28/aptitude/1.html">元代 青花龙纹玉壶春瓶</a>”当前价【800000.00元】，目前领先','1735013879');
INSERT INTO `on_mysms` VALUES ('77','22','0','0','0','0','竞拍出价成功','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/28/aptitude/1.html">元代 青花龙纹玉壶春瓶</a>”出价【800000.00元】成功！','1735013879');
INSERT INTO `on_mysms` VALUES ('78','17','0','0','0','0','提现冻结','1','0','提现暂时冻结可用余额，等待提现完成扣除！','1735013899');
INSERT INTO `on_mysms` VALUES ('79','22','0','0','0','0','参与拍品拍竞价冻结保证金','0','0','参拍“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/29/aptitude/1.html">清乾隆 珐瑯彩描金雙耳花卉紋瓶</a>”冻结保证金【50000.00元】','1735022081');
INSERT INTO `on_mysms` VALUES ('80','5','0','0','0','0','拍品出价更新','0','0','拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/29/aptitude/1.html">清乾隆 珐瑯彩描金雙耳花卉紋瓶</a>”当前价【5000000.00元】，目前领先','1735022081');
INSERT INTO `on_mysms` VALUES ('81','22','0','0','0','0','竞拍出价成功','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/29/aptitude/1.html">清乾隆 珐瑯彩描金雙耳花卉紋瓶</a>”出价【5000000.00元】成功！','1735022081');
INSERT INTO `on_mysms` VALUES ('82','22','0','0','0','0','竞拍出价被超越','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/29/aptitude/1.html">清乾隆 珐瑯彩描金雙耳花卉紋瓶</a>”出价【5000000.00元】已被超过。','1735022937');
INSERT INTO `on_mysms` VALUES ('83','5','0','0','0','0','拍品出价更新','0','0','拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/29/aptitude/1.html">清乾隆 珐瑯彩描金雙耳花卉紋瓶</a>”当前价【5300000.00元】，目前领先','1735022937');
INSERT INTO `on_mysms` VALUES ('84','12','0','0','0','0','竞拍出价成功','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/29/aptitude/1.html">清乾隆 珐瑯彩描金雙耳花卉紋瓶</a>”出价【5300000.00元】成功！','1735022937');
INSERT INTO `on_mysms` VALUES ('85','12','0','0','0','0','参与拍品拍竞价冻结保证金','0','0','参拍“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/29/aptitude/1.html">清乾隆 珐瑯彩描金雙耳花卉紋瓶</a>”冻结保证金【50000.00元】','1735022937');
INSERT INTO `on_mysms` VALUES ('86','23','0','0','0','0','管理员充值','0','0','管理员充值余额【10元】，单号aad173504071191271','1735040711');
INSERT INTO `on_mysms` VALUES ('87','23','0','0','0','0','提现冻结','0','0','提现暂时冻结可用余额，等待提现完成扣除！','1735041540');
INSERT INTO `on_mysms` VALUES ('88','5','0','0','0','0','发布拍卖冻结信誉额度','0','0','发布拍卖：“<a href="/Auction/details/pid/30/aptitude/1.html">丁雲鵬《佛像圖》</a>”冻结信誉额度【50000.00元】','1735097415');
INSERT INTO `on_mysms` VALUES ('89','9','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/30/aptitude/1.html">【丁雲鵬《佛像圖》】</a>”已上架！','1735097415');
INSERT INTO `on_mysms` VALUES ('90','12','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/30/aptitude/1.html">【丁雲鵬《佛像圖》】</a>”已上架！','1735097415');
INSERT INTO `on_mysms` VALUES ('91','22','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/30/aptitude/1.html">【丁雲鵬《佛像圖》】</a>”已上架！','1735097415');
INSERT INTO `on_mysms` VALUES ('92','12','0','0','0','0','竞拍出价被超越','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/29/aptitude/1.html">清乾隆 珐瑯彩描金雙耳花卉紋瓶</a>”出价【5300000.00元】已被超过。','1735098622');
INSERT INTO `on_mysms` VALUES ('93','5','0','0','0','0','拍品出价更新','0','0','拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/29/aptitude/1.html">清乾隆 珐瑯彩描金雙耳花卉紋瓶</a>”当前价【5600000.00元】，目前领先','1735098622');
INSERT INTO `on_mysms` VALUES ('94','9','0','0','0','0','竞拍出价成功','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/29/aptitude/1.html">清乾隆 珐瑯彩描金雙耳花卉紋瓶</a>”出价【5600000.00元】成功！','1735098622');
INSERT INTO `on_mysms` VALUES ('95','9','0','0','0','0','参与拍品拍竞价冻结保证金','0','0','参拍“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/29/aptitude/1.html">清乾隆 珐瑯彩描金雙耳花卉紋瓶</a>”冻结保证金【50000.00元】','1735098622');
INSERT INTO `on_mysms` VALUES ('96','22','0','0','0','0','竞拍出价被超越','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/28/aptitude/1.html">元代 青花龙纹玉壶春瓶</a>”出价【800000.00元】已被超过。','1735100789');
INSERT INTO `on_mysms` VALUES ('97','5','0','0','0','0','拍品出价更新','0','0','拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/28/aptitude/1.html">元代 青花龙纹玉壶春瓶</a>”当前价【1040000.00元】，目前领先','1735100789');
INSERT INTO `on_mysms` VALUES ('98','12','0','0','0','0','竞拍出价成功','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/28/aptitude/1.html">元代 青花龙纹玉壶春瓶</a>”出价【1040000.00元】成功！','1735100789');
INSERT INTO `on_mysms` VALUES ('99','12','0','0','0','0','参与拍品拍竞价冻结保证金','0','0','参拍“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/28/aptitude/1.html">元代 青花龙纹玉壶春瓶</a>”冻结保证金【50000.00元】','1735100790');
INSERT INTO `on_mysms` VALUES ('100','9','0','0','0','0','竞拍出价被超越','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/29/aptitude/1.html">清乾隆 珐瑯彩描金雙耳花卉紋瓶</a>”出价【5600000.00元】已被超过。','1735100826');
INSERT INTO `on_mysms` VALUES ('101','5','0','0','0','0','拍品出价更新','0','0','拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/29/aptitude/1.html">清乾隆 珐瑯彩描金雙耳花卉紋瓶</a>”当前价【6500000.00元】，目前领先','1735100826');
INSERT INTO `on_mysms` VALUES ('102','12','0','0','0','0','竞拍出价成功','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/29/aptitude/1.html">清乾隆 珐瑯彩描金雙耳花卉紋瓶</a>”出价【6500000.00元】成功！','1735100826');
INSERT INTO `on_mysms` VALUES ('103','12','0','0','0','0','竞拍出价被超越','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/29/aptitude/1.html">清乾隆 珐瑯彩描金雙耳花卉紋瓶</a>”出价【6500000.00元】已被超过。','1735104524');
INSERT INTO `on_mysms` VALUES ('104','5','0','0','0','0','拍品出价更新','0','0','拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/29/aptitude/1.html">清乾隆 珐瑯彩描金雙耳花卉紋瓶</a>”当前价【6800000.00元】，目前领先','1735104524');
INSERT INTO `on_mysms` VALUES ('105','9','0','0','0','0','竞拍出价成功','1','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/29/aptitude/1.html">清乾隆 珐瑯彩描金雙耳花卉紋瓶</a>”出价【6800000.00元】成功！','1735104524');
INSERT INTO `on_mysms` VALUES ('106','9','0','0','0','0','参与拍品拍竞价冻结保证金','0','0','参拍“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/28/aptitude/1.html">元代 青花龙纹玉壶春瓶</a>”冻结保证金【50000.00元】','1735104704');
INSERT INTO `on_mysms` VALUES ('107','12','0','0','0','0','竞拍出价被超越','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/28/aptitude/1.html">元代 青花龙纹玉壶春瓶</a>”出价【1040000.00元】已被超过。','1735104704');
INSERT INTO `on_mysms` VALUES ('108','5','0','0','0','0','拍品出价更新','0','0','拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/28/aptitude/1.html">元代 青花龙纹玉壶春瓶</a>”当前价【1100000.00元】，目前领先','1735104704');
INSERT INTO `on_mysms` VALUES ('109','9','0','0','0','0','竞拍出价成功','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/28/aptitude/1.html">元代 青花龙纹玉壶春瓶</a>”出价【1100000.00元】成功！','1735104704');
INSERT INTO `on_mysms` VALUES ('110','9','0','0','0','0','竞拍出价被超越','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/28/aptitude/1.html">元代 青花龙纹玉壶春瓶</a>”出价【1100000.00元】已被超过。','1735188678');
INSERT INTO `on_mysms` VALUES ('111','5','0','0','0','0','拍品出价更新','0','0','拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/28/aptitude/1.html">元代 青花龙纹玉壶春瓶</a>”当前价【1130000.00元】，目前领先','1735188678');
INSERT INTO `on_mysms` VALUES ('112','12','0','0','0','0','竞拍出价成功','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/28/aptitude/1.html">元代 青花龙纹玉壶春瓶</a>”出价【1130000.00元】成功！','1735188678');
INSERT INTO `on_mysms` VALUES ('113','9','0','0','0','0','竞拍出价被超越','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/29/aptitude/1.html">清乾隆 珐瑯彩描金雙耳花卉紋瓶</a>”出价【6800000.00元】已被超过。','1735199964');
INSERT INTO `on_mysms` VALUES ('114','5','0','0','0','0','拍品出价更新','0','0','拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/29/aptitude/1.html">清乾隆 珐瑯彩描金雙耳花卉紋瓶</a>”当前价【7100000.00元】，目前领先','1735199964');
INSERT INTO `on_mysms` VALUES ('115','12','0','0','0','0','竞拍出价成功','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/29/aptitude/1.html">清乾隆 珐瑯彩描金雙耳花卉紋瓶</a>”出价【7100000.00元】成功！','1735199964');
INSERT INTO `on_mysms` VALUES ('116','26','0','0','0','0','管理员充值','0','0','管理员充值余额【1000000元】，单号aad173520010836689','1735200108');
INSERT INTO `on_mysms` VALUES ('117','22','0','0','0','0','保证金解冻','0','0','拍品【<a  target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/28/aptitude/1.html">元代 青花龙纹玉壶春瓶</a>】结束解冻保证金50000.00元；','1735214404');
INSERT INTO `on_mysms` VALUES ('118','9','0','0','0','0','保证金解冻','0','0','拍品【<a  target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/28/aptitude/1.html">元代 青花龙纹玉壶春瓶</a>】结束解冻保证金50000.00元；','1735214404');
INSERT INTO `on_mysms` VALUES ('119','12','0','0','0','0','系统提示','0','0','恭喜您以1130000.00元拍到[【<a target="_blank" href="/Auction/details/pid/28/aptitude/1.html">元代 青花龙纹玉壶春瓶</a>】请在2025-01-02 20:00之前支付完成支付。否则将扣除您参与该拍卖所缴纳的保证金或信用额度！','1735214404');
INSERT INTO `on_mysms` VALUES ('120','12','0','0','0','0','订单状态通知','0','0','订单号：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Member/order_details/order_no/BID173521440485271/aptitude/1.html">BID173521440485271</a>”已生成订单，请在2025-01-02 20:00前支付订单！商品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/28/aptitude/1.html">元代 青花龙纹玉壶春瓶</a>”。','1735214404');
INSERT INTO `on_mysms` VALUES ('121','5','0','0','0','0','订单状态通知','0','0','订单号：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Member/order_details/order_no/BID173521440485271/aptitude/1.html">BID173521440485271</a>”已生成订单，等待买家支付！商品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/28/aptitude/1.html">元代 青花龙纹玉壶春瓶</a>”。','1735214404');
INSERT INTO `on_mysms` VALUES ('122','27','0','0','0','0','管理员充值','0','0','管理员充值余额【20000元】，单号aad173522013901979','1735220139');
INSERT INTO `on_mysms` VALUES ('123','27','0','0','0','0','管理员充值','0','0','管理员充值余额【1350000元】，单号aad173522071978019','1735220719');
INSERT INTO `on_mysms` VALUES ('124','28','0','0','0','0','管理员充值','0','0','管理员充值余额【30000元】，单号aad173522442607672','1735224426');
INSERT INTO `on_mysms` VALUES ('125','28','0','0','0','0','管理员扣除','0','0','管理员扣除余额【30000元】，单号ami173527112711236','1735271127');
INSERT INTO `on_mysms` VALUES ('126','28','0','0','0','0','管理员充值','0','0','管理员充值余额【40000元】，单号aad173527113934985','1735271139');
INSERT INTO `on_mysms` VALUES ('127','28','0','0','0','0','管理员充值','0','0','管理员充值余额【1550000元】，单号aad173527151696782','1735271516');
INSERT INTO `on_mysms` VALUES ('128','22','0','0','0','0','保证金解冻','0','0','拍品【<a  target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/29/aptitude/1.html">清乾隆 珐瑯彩描金雙耳花卉紋瓶</a>】结束解冻保证金50000.00元；','1735287548');
INSERT INTO `on_mysms` VALUES ('129','9','0','0','0','0','保证金解冻','0','0','拍品【<a  target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/29/aptitude/1.html">清乾隆 珐瑯彩描金雙耳花卉紋瓶</a>】结束解冻保证金50000.00元；','1735287548');
INSERT INTO `on_mysms` VALUES ('130','12','0','0','0','0','系统提示','0','0','恭喜您以7100000.00元拍到[【<a target="_blank" href="/Auction/details/pid/29/aptitude/1.html">清乾隆 珐瑯彩描金雙耳花卉紋瓶</a>】请在2025-01-03 16:19之前支付完成支付。否则将扣除您参与该拍卖所缴纳的保证金或信用额度！','1735287548');
INSERT INTO `on_mysms` VALUES ('131','12','0','0','0','0','订单状态通知','0','0','订单号：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Member/order_details/order_no/BID173528754867011/aptitude/1.html">BID173528754867011</a>”已生成订单，请在2025-01-03 16:19前支付订单！商品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/29/aptitude/1.html">清乾隆 珐瑯彩描金雙耳花卉紋瓶</a>”。','1735287548');
INSERT INTO `on_mysms` VALUES ('132','5','0','0','0','0','订单状态通知','0','0','订单号：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Member/order_details/order_no/BID173528754867011/aptitude/1.html">BID173528754867011</a>”已生成订单，等待买家支付！商品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/29/aptitude/1.html">清乾隆 珐瑯彩描金雙耳花卉紋瓶</a>”。','1735287548');
INSERT INTO `on_mysms` VALUES ('133','26','0','0','0','0','管理员充值','0','0','管理员充值余额【10000000元】，单号aad173528959247918','1735289592');
INSERT INTO `on_mysms` VALUES ('134','12','0','0','0','0','管理员充值','0','0','管理员充值余额【15000000元】，单号aad17352897499755','1735289749');
INSERT INTO `on_mysms` VALUES ('135','12','0','0','0','0','保证金抵货款','0','0','保证金抵商品：“<a href="/Home/Auction/details/pid/28/aptitude/1.html">元代 青花龙纹玉壶春瓶</a>”货款【50000.00元】！订单号：“<a href="/Home/Member/order_details/order_no/BID173521440485271/aptitude/1.html">BID173521440485271</a>”','1735289773');
INSERT INTO `on_mysms` VALUES ('136','12','0','0','0','0','支付订单','0','0','支付商品：“<a href="/Home/Auction/details/pid/28/aptitude/1.html">元代 青花龙纹玉壶春瓶</a>”订单号：“<a href="/Home/Member/order_details/order_no/BID173521440485271/aptitude/1.html">BID173521440485271</a>”扣除余额1193000元','1735289773');
INSERT INTO `on_mysms` VALUES ('137','12','0','0','0','0','订单状态通知','0','0','订单号：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Member/order_details/order_no/BID173521440485271/aptitude/1.html">BID173521440485271</a>”您已支付，等待卖家发货！商品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/28/aptitude/1.html">元代 青花龙纹玉壶春瓶</a>”。','1735289773');
INSERT INTO `on_mysms` VALUES ('138','5','0','0','0','0','订单状态通知','0','0','订单号：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Member/order_details/order_no/BID173521440485271/aptitude/1.html">BID173521440485271</a>”买家已支付，请尽快给买家发货！商品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/28/aptitude/1.html">元代 青花龙纹玉壶春瓶</a>”。','1735289773');
INSERT INTO `on_mysms` VALUES ('139','12','0','0','0','0','保证金抵货款','0','0','保证金抵商品：“<a href="/Home/Auction/details/pid/29/aptitude/1.html">清乾隆 珐瑯彩描金雙耳花卉紋瓶</a>”货款【50000.00元】！订单号：“<a href="/Home/Member/order_details/order_no/BID173528754867011/aptitude/1.html">BID173528754867011</a>”','1735289787');
INSERT INTO `on_mysms` VALUES ('140','12','0','0','0','0','支付订单','0','0','支付商品：“<a href="/Home/Auction/details/pid/29/aptitude/1.html">清乾隆 珐瑯彩描金雙耳花卉紋瓶</a>”订单号：“<a href="/Home/Member/order_details/order_no/BID173528754867011/aptitude/1.html">BID173528754867011</a>”扣除余额7760000元','1735289787');
INSERT INTO `on_mysms` VALUES ('141','12','0','0','0','0','订单状态通知','0','0','订单号：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Member/order_details/order_no/BID173528754867011/aptitude/1.html">BID173528754867011</a>”您已支付，等待卖家发货！商品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/29/aptitude/1.html">清乾隆 珐瑯彩描金雙耳花卉紋瓶</a>”。','1735289787');
INSERT INTO `on_mysms` VALUES ('142','5','0','0','0','0','订单状态通知','0','0','订单号：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Member/order_details/order_no/BID173528754867011/aptitude/1.html">BID173528754867011</a>”买家已支付，请尽快给买家发货！商品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/29/aptitude/1.html">清乾隆 珐瑯彩描金雙耳花卉紋瓶</a>”。','1735289787');
INSERT INTO `on_mysms` VALUES ('143','5','0','0','0','0','拍品出价更新','0','0','拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/30/aptitude/1.html">丁雲鵬《佛像圖》</a>”当前价【28000000.00元】，目前领先','1735289804');
INSERT INTO `on_mysms` VALUES ('144','12','0','0','0','0','竞拍出价成功','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/30/aptitude/1.html">丁雲鵬《佛像圖》</a>”出价【28000000.00元】成功！','1735289804');
INSERT INTO `on_mysms` VALUES ('145','12','0','0','0','0','参与拍品拍竞价冻结保证金','0','0','参拍“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/30/aptitude/1.html">丁雲鵬《佛像圖》</a>”冻结保证金【50000.00元】','1735289804');
INSERT INTO `on_mysms` VALUES ('146','12','0','0','0','0','竞拍出价被超越','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/30/aptitude/1.html">丁雲鵬《佛像圖》</a>”出价【28000000.00元】已被超过。','1735289890');
INSERT INTO `on_mysms` VALUES ('147','5','0','0','0','0','拍品出价更新','0','0','拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/30/aptitude/1.html">丁雲鵬《佛像圖》</a>”当前价【28500000.00元】，目前领先','1735289890');
INSERT INTO `on_mysms` VALUES ('148','26','0','0','0','0','竞拍出价成功','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/30/aptitude/1.html">丁雲鵬《佛像圖》</a>”出价【28500000.00元】成功！','1735289890');
INSERT INTO `on_mysms` VALUES ('149','26','0','0','0','0','参与拍品拍竞价冻结保证金','0','0','参拍“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/30/aptitude/1.html">丁雲鵬《佛像圖》</a>”冻结保证金【50000.00元】','1735289890');
INSERT INTO `on_mysms` VALUES ('150','4','0','0','0','0','拍品出价更新','0','0','拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/2/aptitude/1.html"> 清雍正 青花纏枝花卉紋蒜頭大瓶</a>”当前价【6300000.00元】，目前领先','1735289931');
INSERT INTO `on_mysms` VALUES ('151','26','0','0','0','0','竞拍出价成功','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/2/aptitude/1.html"> 清雍正 青花纏枝花卉紋蒜頭大瓶</a>”出价【6300000.00元】成功！','1735289931');
INSERT INTO `on_mysms` VALUES ('152','26','0','0','0','0','参与拍品拍竞价冻结保证金','0','0','参拍“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/2/aptitude/1.html"> 清雍正 青花纏枝花卉紋蒜頭大瓶</a>”冻结保证金【20000.00元】','1735289931');
INSERT INTO `on_mysms` VALUES ('153','5','0','0','0','0','发布拍卖冻结信誉额度','0','0','发布拍卖：“<a href="/Auction/details/pid/31/aptitude/1.html">清乾隆 孔雀藍地描金折枝花卉紋雙耳瓶</a>”冻结信誉额度【50000.00元】','1735291283');
INSERT INTO `on_mysms` VALUES ('154','9','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/31/aptitude/1.html">【清乾隆 孔雀藍地描金折枝花卉紋雙耳瓶】</a>”已上架！','1735291283');
INSERT INTO `on_mysms` VALUES ('155','12','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/31/aptitude/1.html">【清乾隆 孔雀藍地描金折枝花卉紋雙耳瓶】</a>”已上架！','1735291283');
INSERT INTO `on_mysms` VALUES ('156','22','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/31/aptitude/1.html">【清乾隆 孔雀藍地描金折枝花卉紋雙耳瓶】</a>”已上架！','1735291283');
INSERT INTO `on_mysms` VALUES ('157','12','0','0','0','0','竞拍出价被超越','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/27/aptitude/1.html"> 大清雍正六年時憲歷</a>”出价【5500000.00元】已被超过。','1735320465');
INSERT INTO `on_mysms` VALUES ('158','5','0','0','0','0','拍品出价更新','0','0','拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/27/aptitude/1.html"> 大清雍正六年時憲歷</a>”当前价【6000000.00元】，目前领先','1735320465');
INSERT INTO `on_mysms` VALUES ('159','26','0','0','0','0','竞拍出价成功','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/27/aptitude/1.html"> 大清雍正六年時憲歷</a>”出价【6000000.00元】成功！','1735320465');
INSERT INTO `on_mysms` VALUES ('160','26','0','0','0','0','参与拍品拍竞价冻结保证金','0','0','参拍“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/27/aptitude/1.html"> 大清雍正六年時憲歷</a>”冻结保证金【50000.00元】','1735320465');
INSERT INTO `on_mysms` VALUES ('161','5','0','0','0','0','拍品出价更新','0','0','拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/20/aptitude/1.html">巴拿馬-太平洋紀念幣</a>”当前价【1600000.00元】，目前领先','1735320569');
INSERT INTO `on_mysms` VALUES ('162','26','0','0','0','0','竞拍出价成功','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/20/aptitude/1.html">巴拿馬-太平洋紀念幣</a>”出价【1600000.00元】成功！','1735320569');
INSERT INTO `on_mysms` VALUES ('163','26','0','0','0','0','参与拍品拍竞价冻结保证金','0','0','参拍“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/20/aptitude/1.html">巴拿馬-太平洋紀念幣</a>”冻结保证金【30000.00元】','1735320569');
INSERT INTO `on_mysms` VALUES ('164','28','0','0','0','0','参与拍品拍竞价冻结保证金','0','0','参拍“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/30/aptitude/1.html">丁雲鵬《佛像圖》</a>”冻结保证金【50000.00元】','1735323055');
INSERT INTO `on_mysms` VALUES ('165','26','0','0','0','0','竞拍出价被超越','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/30/aptitude/1.html">丁雲鵬《佛像圖》</a>”出价【28500000.00元】已被超过。','1735323055');
INSERT INTO `on_mysms` VALUES ('166','5','0','0','0','0','拍品出价更新','0','0','拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/30/aptitude/1.html">丁雲鵬《佛像圖》</a>”当前价【29000000.00元】，目前领先','1735323055');
INSERT INTO `on_mysms` VALUES ('167','28','0','0','0','0','竞拍出价成功','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/30/aptitude/1.html">丁雲鵬《佛像圖》</a>”出价【29000000.00元】成功！','1735323055');
INSERT INTO `on_mysms` VALUES ('168','5','0','0','0','0','发布拍卖冻结信誉额度','0','0','发布拍卖：“<a href="/Auction/details/pid/32/aptitude/1.html">STEGOSAURUS（劍龍）</a>”冻结信誉额度【50000.00元】','1735325469');
INSERT INTO `on_mysms` VALUES ('169','9','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/32/aptitude/1.html">【STEGOSAURUS（劍龍）】</a>”已上架！','1735325469');
INSERT INTO `on_mysms` VALUES ('170','12','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/32/aptitude/1.html">【STEGOSAURUS（劍龍）】</a>”已上架！','1735325469');
INSERT INTO `on_mysms` VALUES ('171','22','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/32/aptitude/1.html">【STEGOSAURUS（劍龍）】</a>”已上架！','1735325469');
INSERT INTO `on_mysms` VALUES ('172','26','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/32/aptitude/1.html">【STEGOSAURUS（劍龍）】</a>”已上架！','1735325469');
INSERT INTO `on_mysms` VALUES ('173','5','0','0','0','0','发布拍卖冻结信誉额度','0','0','发布拍卖：“<a href="/Auction/details/pid/33/aptitude/1.html">成年同幼年異特龍</a>”冻结信誉额度【50000.00元】','1735326856');
INSERT INTO `on_mysms` VALUES ('174','9','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/33/aptitude/1.html">【成年同幼年異特龍】</a>”已上架！','1735326856');
INSERT INTO `on_mysms` VALUES ('175','12','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/33/aptitude/1.html">【成年同幼年異特龍】</a>”已上架！','1735326856');
INSERT INTO `on_mysms` VALUES ('176','22','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/33/aptitude/1.html">【成年同幼年異特龍】</a>”已上架！','1735326856');
INSERT INTO `on_mysms` VALUES ('177','26','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/33/aptitude/1.html">【成年同幼年異特龍】</a>”已上架！','1735326856');
INSERT INTO `on_mysms` VALUES ('178','12','0','0','0','0','保证金解冻','0','0','拍品【<a  target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/30/aptitude/1.html">丁雲鵬《佛像圖》</a>】结束解冻保证金50000.00元；','1735397514');
INSERT INTO `on_mysms` VALUES ('179','26','0','0','0','0','保证金解冻','0','0','拍品【<a  target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/30/aptitude/1.html">丁雲鵬《佛像圖》</a>】结束解冻保证金50000.00元；','1735397514');
INSERT INTO `on_mysms` VALUES ('180','28','0','0','0','0','系统提示','0','0','恭喜您以29000000.00元拍到[【<a target="_blank" href="/Auction/details/pid/30/aptitude/1.html">丁雲鵬《佛像圖》</a>】请在2025-01-04 22:51之前支付完成支付。否则将扣除您参与该拍卖所缴纳的保证金或信用额度！','1735397514');
INSERT INTO `on_mysms` VALUES ('181','28','0','0','0','0','订单状态通知','0','0','订单号：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Member/order_details/order_no/BID173539751445066/aptitude/1.html">BID173539751445066</a>”已生成订单，请在2025-01-04 22:51前支付订单！商品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/30/aptitude/1.html">丁雲鵬《佛像圖》</a>”。','1735397514');
INSERT INTO `on_mysms` VALUES ('182','5','0','0','0','0','订单状态通知','1','0','订单号：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Member/order_details/order_no/BID173539751445066/aptitude/1.html">BID173539751445066</a>”已生成订单，等待买家支付！商品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/30/aptitude/1.html">丁雲鵬《佛像圖》</a>”。','1735397514');
INSERT INTO `on_mysms` VALUES ('183','30','0','0','0','0','管理员充值','0','0','管理员充值余额【20元】，单号aad173544740416314','1735447404');
INSERT INTO `on_mysms` VALUES ('184','30','0','0','0','0','提现冻结','0','0','提现暂时冻结可用余额，等待提现完成扣除！','1735449360');
INSERT INTO `on_mysms` VALUES ('185','17','0','0','0','0','系统发送','0','0','管理员已同意您的10.00元保证金提现申请！即将为您转账请注意查收！备注：10.00','1735451317');
INSERT INTO `on_mysms` VALUES ('186','23','0','0','0','0','系统发送','0','0','管理员已同意您的10.00元保证金提现申请！即将为您转账请注意查收！备注：10.00','1735451329');
INSERT INTO `on_mysms` VALUES ('187','30','0','0','0','0','系统发送','0','0','管理员已同意您的20.00元保证金提现申请！即将为您转账请注意查收！备注：20.00','1735452107');
INSERT INTO `on_mysms` VALUES ('188','5','0','0','0','0','发布拍卖冻结信誉额度','0','0','发布拍卖：“<a href="/Auction/details/pid/34/aptitude/1.html">清康熙青花人物雙耳瓶</a>”冻结信誉额度【50000.00元】','1735454047');
INSERT INTO `on_mysms` VALUES ('189','9','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/34/aptitude/1.html">【清康熙青花人物雙耳瓶】</a>”已上架！','1735454047');
INSERT INTO `on_mysms` VALUES ('190','12','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/34/aptitude/1.html">【清康熙青花人物雙耳瓶】</a>”已上架！','1735454047');
INSERT INTO `on_mysms` VALUES ('191','22','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/34/aptitude/1.html">【清康熙青花人物雙耳瓶】</a>”已上架！','1735454047');
INSERT INTO `on_mysms` VALUES ('192','26','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/34/aptitude/1.html">【清康熙青花人物雙耳瓶】</a>”已上架！','1735454047');
INSERT INTO `on_mysms` VALUES ('193','28','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/34/aptitude/1.html">【清康熙青花人物雙耳瓶】</a>”已上架！','1735454047');
INSERT INTO `on_mysms` VALUES ('194','31','0','0','0','0','管理员充值','0','0','管理员充值余额【10元】，单号aad173547047082255','1735470470');
INSERT INTO `on_mysms` VALUES ('195','31','0','0','0','0','提现冻结','0','0','提现暂时冻结可用余额，等待提现完成扣除！','1735472137');
INSERT INTO `on_mysms` VALUES ('196','5','0','0','0','0','发布拍卖冻结信誉额度','0','0','发布拍卖：“<a href="/Auction/details/pid/35/aptitude/1.html">元 青花蒙括將軍玉壺春瓶</a>”冻结信誉额度【50000.00元】','1735472832');
INSERT INTO `on_mysms` VALUES ('197','9','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/35/aptitude/1.html">【元 青花蒙括將軍玉壺春瓶】</a>”已上架！','1735472832');
INSERT INTO `on_mysms` VALUES ('198','12','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/35/aptitude/1.html">【元 青花蒙括將軍玉壺春瓶】</a>”已上架！','1735472832');
INSERT INTO `on_mysms` VALUES ('199','22','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/35/aptitude/1.html">【元 青花蒙括將軍玉壺春瓶】</a>”已上架！','1735472832');
INSERT INTO `on_mysms` VALUES ('200','26','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/35/aptitude/1.html">【元 青花蒙括將軍玉壺春瓶】</a>”已上架！','1735472832');
INSERT INTO `on_mysms` VALUES ('201','28','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/35/aptitude/1.html">【元 青花蒙括將軍玉壺春瓶】</a>”已上架！','1735472832');
INSERT INTO `on_mysms` VALUES ('202','5','0','0','0','0','拍品出价更新','0','0','拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/34/aptitude/1.html">清康熙青花人物雙耳瓶</a>”当前价【3500000.00元】，目前领先','1735523982');
INSERT INTO `on_mysms` VALUES ('203','26','0','0','0','0','竞拍出价成功','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/34/aptitude/1.html">清康熙青花人物雙耳瓶</a>”出价【3500000.00元】成功！','1735523982');
INSERT INTO `on_mysms` VALUES ('204','26','0','0','0','0','参与拍品拍竞价冻结保证金','0','0','参拍“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/34/aptitude/1.html">清康熙青花人物雙耳瓶</a>”冻结保证金【50000.00元】','1735523982');
INSERT INTO `on_mysms` VALUES ('205','5','0','0','0','0','拍品出价更新','0','0','拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/35/aptitude/1.html">元 青花蒙括將軍玉壺春瓶</a>”当前价【3200000.00元】，目前领先','1735524109');
INSERT INTO `on_mysms` VALUES ('206','12','0','0','0','0','竞拍出价成功','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/35/aptitude/1.html">元 青花蒙括將軍玉壺春瓶</a>”出价【3200000.00元】成功！','1735524109');
INSERT INTO `on_mysms` VALUES ('207','12','0','0','0','0','参与拍品拍竞价冻结保证金','0','0','参拍“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/35/aptitude/1.html">元 青花蒙括將軍玉壺春瓶</a>”冻结保证金【50000.00元】','1735524109');
INSERT INTO `on_mysms` VALUES ('208','5','0','0','0','0','发布拍卖冻结信誉额度','0','0','发布拍卖：“<a href="/Auction/details/pid/36/aptitude/1.html">弗雷德里克·伦赛特（1861-1909）</a>”冻结信誉额度【50000.00元】','1735539373');
INSERT INTO `on_mysms` VALUES ('209','9','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/36/aptitude/1.html">【弗雷德里克·伦赛特（1861-1909）】</a>”已上架！','1735539373');
INSERT INTO `on_mysms` VALUES ('210','12','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/36/aptitude/1.html">【弗雷德里克·伦赛特（1861-1909）】</a>”已上架！','1735539373');
INSERT INTO `on_mysms` VALUES ('211','22','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/36/aptitude/1.html">【弗雷德里克·伦赛特（1861-1909）】</a>”已上架！','1735539373');
INSERT INTO `on_mysms` VALUES ('212','26','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/36/aptitude/1.html">【弗雷德里克·伦赛特（1861-1909）】</a>”已上架！','1735539373');
INSERT INTO `on_mysms` VALUES ('213','28','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/36/aptitude/1.html">【弗雷德里克·伦赛特（1861-1909）】</a>”已上架！','1735539373');
INSERT INTO `on_mysms` VALUES ('214','5','0','0','0','0','发布拍卖冻结信誉额度','0','0','发布拍卖：“<a href="/Auction/details/pid/37/aptitude/1.html">弗雷德里克·伦赛特（1861-1909）</a>”冻结信誉额度【50000.00元】','1735540636');
INSERT INTO `on_mysms` VALUES ('215','9','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/37/aptitude/1.html">【弗雷德里克·伦赛特（1861-1909）】</a>”已上架！','1735540636');
INSERT INTO `on_mysms` VALUES ('216','12','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/37/aptitude/1.html">【弗雷德里克·伦赛特（1861-1909）】</a>”已上架！','1735540636');
INSERT INTO `on_mysms` VALUES ('217','22','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/37/aptitude/1.html">【弗雷德里克·伦赛特（1861-1909）】</a>”已上架！','1735540636');
INSERT INTO `on_mysms` VALUES ('218','26','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/37/aptitude/1.html">【弗雷德里克·伦赛特（1861-1909）】</a>”已上架！','1735540636');
INSERT INTO `on_mysms` VALUES ('219','28','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/37/aptitude/1.html">【弗雷德里克·伦赛特（1861-1909）】</a>”已上架！','1735540636');
INSERT INTO `on_mysms` VALUES ('220','5','0','0','0','0','发布拍卖冻结信誉额度','0','0','发布拍卖：“<a href="/Auction/details/pid/38/aptitude/1.html">托馬斯·莫蘭（1837-1926）</a>”冻结信誉额度【50000.00元】','1735541084');
INSERT INTO `on_mysms` VALUES ('221','9','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/38/aptitude/1.html">【托馬斯·莫蘭（1837-1926）】</a>”已上架！','1735541084');
INSERT INTO `on_mysms` VALUES ('222','12','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/38/aptitude/1.html">【托馬斯·莫蘭（1837-1926）】</a>”已上架！','1735541084');
INSERT INTO `on_mysms` VALUES ('223','22','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/38/aptitude/1.html">【托馬斯·莫蘭（1837-1926）】</a>”已上架！','1735541084');
INSERT INTO `on_mysms` VALUES ('224','26','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/38/aptitude/1.html">【托馬斯·莫蘭（1837-1926）】</a>”已上架！','1735541084');
INSERT INTO `on_mysms` VALUES ('225','28','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/38/aptitude/1.html">【托馬斯·莫蘭（1837-1926）】</a>”已上架！','1735541084');
INSERT INTO `on_mysms` VALUES ('226','5','0','0','0','0','发布拍卖冻结信誉额度','0','0','发布拍卖：“<a href="/Auction/details/pid/39/aptitude/1.html">托馬斯·希爾（1829-1908）</a>”冻结信誉额度【50000.00元】','1735541380');
INSERT INTO `on_mysms` VALUES ('227','9','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/39/aptitude/1.html">【托馬斯·希爾（1829-1908）】</a>”已上架！','1735541380');
INSERT INTO `on_mysms` VALUES ('228','12','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/39/aptitude/1.html">【托馬斯·希爾（1829-1908）】</a>”已上架！','1735541380');
INSERT INTO `on_mysms` VALUES ('229','22','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/39/aptitude/1.html">【托馬斯·希爾（1829-1908）】</a>”已上架！','1735541380');
INSERT INTO `on_mysms` VALUES ('230','26','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/39/aptitude/1.html">【托馬斯·希爾（1829-1908）】</a>”已上架！','1735541380');
INSERT INTO `on_mysms` VALUES ('231','28','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/39/aptitude/1.html">【托馬斯·希爾（1829-1908）】</a>”已上架！','1735541380');
INSERT INTO `on_mysms` VALUES ('232','5','0','0','0','0','发布拍卖冻结信誉额度','0','0','发布拍卖：“<a href="/Auction/details/pid/40/aptitude/1.html">阿尔伯特·比尔施塔特 1830-1902</a>”冻结信誉额度【50000.00元】','1735541633');
INSERT INTO `on_mysms` VALUES ('233','9','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/40/aptitude/1.html">【阿尔伯特·比尔施塔特 1830-1902】</a>”已上架！','1735541633');
INSERT INTO `on_mysms` VALUES ('234','12','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/40/aptitude/1.html">【阿尔伯特·比尔施塔特 1830-1902】</a>”已上架！','1735541633');
INSERT INTO `on_mysms` VALUES ('235','22','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/40/aptitude/1.html">【阿尔伯特·比尔施塔特 1830-1902】</a>”已上架！','1735541633');
INSERT INTO `on_mysms` VALUES ('236','26','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/40/aptitude/1.html">【阿尔伯特·比尔施塔特 1830-1902】</a>”已上架！','1735541633');
INSERT INTO `on_mysms` VALUES ('237','28','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/40/aptitude/1.html">【阿尔伯特·比尔施塔特 1830-1902】</a>”已上架！','1735541633');
INSERT INTO `on_mysms` VALUES ('238','33','0','0','0','0','管理员充值','0','0','管理员充值余额【10元】，单号aad17355420418671','1735542041');
INSERT INTO `on_mysms` VALUES ('239','5','0','0','0','0','发布拍卖冻结信誉额度','0','0','发布拍卖：“<a href="/Auction/details/pid/41/aptitude/1.html">約翰·弗雷德里克·肯塞特 1816</a>”冻结信誉额度【50000.00元】','1735542204');
INSERT INTO `on_mysms` VALUES ('240','9','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/41/aptitude/1.html">【約翰·弗雷德里克·肯塞特 1816】</a>”已上架！','1735542204');
INSERT INTO `on_mysms` VALUES ('241','12','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/41/aptitude/1.html">【約翰·弗雷德里克·肯塞特 1816】</a>”已上架！','1735542204');
INSERT INTO `on_mysms` VALUES ('242','22','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/41/aptitude/1.html">【約翰·弗雷德里克·肯塞特 1816】</a>”已上架！','1735542204');
INSERT INTO `on_mysms` VALUES ('243','26','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/41/aptitude/1.html">【約翰·弗雷德里克·肯塞特 1816】</a>”已上架！','1735542204');
INSERT INTO `on_mysms` VALUES ('244','28','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/41/aptitude/1.html">【約翰·弗雷德里克·肯塞特 1816】</a>”已上架！','1735542204');
INSERT INTO `on_mysms` VALUES ('245','31','0','0','0','0','系统发送','0','0','管理员已同意您的10.00元保证金提现申请！即将为您转账请注意查收！备注：10.00','1735542307');
INSERT INTO `on_mysms` VALUES ('246','32','0','0','0','0','管理员充值','0','0','管理员充值余额【30元】，单号aad173554234118457','1735542341');
INSERT INTO `on_mysms` VALUES ('247','5','0','0','0','0','发布拍卖冻结信誉额度','0','0','发布拍卖：“<a href="/Auction/details/pid/42/aptitude/1.html">馬丁·約翰遜·海德（1819-1904）</a>”冻结信誉额度【50000.00元】','1735542604');
INSERT INTO `on_mysms` VALUES ('248','9','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/42/aptitude/1.html">【馬丁·約翰遜·海德（1819-1904）】</a>”已上架！','1735542604');
INSERT INTO `on_mysms` VALUES ('249','12','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/42/aptitude/1.html">【馬丁·約翰遜·海德（1819-1904）】</a>”已上架！','1735542604');
INSERT INTO `on_mysms` VALUES ('250','22','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/42/aptitude/1.html">【馬丁·約翰遜·海德（1819-1904）】</a>”已上架！','1735542604');
INSERT INTO `on_mysms` VALUES ('251','26','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/42/aptitude/1.html">【馬丁·約翰遜·海德（1819-1904）】</a>”已上架！','1735542604');
INSERT INTO `on_mysms` VALUES ('252','28','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/42/aptitude/1.html">【馬丁·約翰遜·海德（1819-1904）】</a>”已上架！','1735542604');
INSERT INTO `on_mysms` VALUES ('253','33','0','0','0','0','提现冻结','0','0','提现暂时冻结可用余额，等待提现完成扣除！','1735543678');
INSERT INTO `on_mysms` VALUES ('254','12','0','0','0','0','竞拍出价被超越','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/35/aptitude/1.html">元 青花蒙括將軍玉壺春瓶</a>”出价【3200000.00元】已被超过。','1735544051');
INSERT INTO `on_mysms` VALUES ('255','5','0','0','0','0','拍品出价更新','0','0','拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/35/aptitude/1.html">元 青花蒙括將軍玉壺春瓶</a>”当前价【3500000.00元】，目前领先','1735544051');
INSERT INTO `on_mysms` VALUES ('256','9','0','0','0','0','竞拍出价成功','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/35/aptitude/1.html">元 青花蒙括將軍玉壺春瓶</a>”出价【3500000.00元】成功！','1735544051');
INSERT INTO `on_mysms` VALUES ('257','9','0','0','0','0','参与拍品拍竞价冻结保证金','0','0','参拍“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/35/aptitude/1.html">元 青花蒙括將軍玉壺春瓶</a>”冻结保证金【50000.00元】','1735544051');
INSERT INTO `on_mysms` VALUES ('258','10','0','0','0','0','管理员充值','0','0','管理员充值余额【5000000元】，单号aad17355444889082','1735544488');
INSERT INTO `on_mysms` VALUES ('259','26','0','0','0','0','竞拍出价被超越','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/34/aptitude/1.html">清康熙青花人物雙耳瓶</a>”出价【3500000.00元】已被超过。','1735544531');
INSERT INTO `on_mysms` VALUES ('260','5','0','0','0','0','拍品出价更新','0','0','拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/34/aptitude/1.html">清康熙青花人物雙耳瓶</a>”当前价【4100000.00元】，目前领先','1735544531');
INSERT INTO `on_mysms` VALUES ('261','10','0','0','0','0','竞拍出价成功','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/34/aptitude/1.html">清康熙青花人物雙耳瓶</a>”出价【4100000.00元】成功！','1735544531');
INSERT INTO `on_mysms` VALUES ('262','10','0','0','0','0','参与拍品拍竞价冻结保证金','0','0','参拍“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/34/aptitude/1.html">清康熙青花人物雙耳瓶</a>”冻结保证金【50000.00元】','1735544531');
INSERT INTO `on_mysms` VALUES ('263','5','0','0','0','0','发布拍卖冻结信誉额度','0','0','发布拍卖：“<a href="/Auction/details/pid/43/aptitude/1.html">清乾隆 琺瑯彩贲巴壺</a>”冻结信誉额度【50000.00元】','1735552478');
INSERT INTO `on_mysms` VALUES ('264','9','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/43/aptitude/1.html">【清乾隆 琺瑯彩贲巴壺】</a>”已上架！','1735552478');
INSERT INTO `on_mysms` VALUES ('265','12','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/43/aptitude/1.html">【清乾隆 琺瑯彩贲巴壺】</a>”已上架！','1735552478');
INSERT INTO `on_mysms` VALUES ('266','22','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/43/aptitude/1.html">【清乾隆 琺瑯彩贲巴壺】</a>”已上架！','1735552478');
INSERT INTO `on_mysms` VALUES ('267','26','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/43/aptitude/1.html">【清乾隆 琺瑯彩贲巴壺】</a>”已上架！','1735552478');
INSERT INTO `on_mysms` VALUES ('268','28','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/43/aptitude/1.html">【清乾隆 琺瑯彩贲巴壺】</a>”已上架！','1735552478');
INSERT INTO `on_mysms` VALUES ('269','5','0','0','0','0','发布拍卖冻结信誉额度','0','0','发布拍卖：“<a href="/Auction/details/pid/44/aptitude/1.html">清康熙 撇口瓶</a>”冻结信誉额度【50000.00元】','1735553261');
INSERT INTO `on_mysms` VALUES ('270','9','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/44/aptitude/1.html">【清康熙 撇口瓶】</a>”已上架！','1735553261');
INSERT INTO `on_mysms` VALUES ('271','12','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/44/aptitude/1.html">【清康熙 撇口瓶】</a>”已上架！','1735553261');
INSERT INTO `on_mysms` VALUES ('272','22','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/44/aptitude/1.html">【清康熙 撇口瓶】</a>”已上架！','1735553261');
INSERT INTO `on_mysms` VALUES ('273','26','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/44/aptitude/1.html">【清康熙 撇口瓶】</a>”已上架！','1735553261');
INSERT INTO `on_mysms` VALUES ('274','28','0','0','0','0','拍品上架提醒','0','0','卖家[佳士得官方]的拍品“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/44/aptitude/1.html">【清康熙 撇口瓶】</a>”已上架！','1735553261');
INSERT INTO `on_mysms` VALUES ('275','33','0','0','0','0','系统发送','0','0','管理员已同意您的10.00元保证金提现申请！即将为您转账请注意查收！备注：10.00','1735553888');
INSERT INTO `on_mysms` VALUES ('276','26','0','0','0','0','系统提示','0','0','恭喜您以6300000.00元拍到[【<a target="_blank" href="/Auction/details/pid/2/aptitude/1.html"> 清雍正 青花纏枝花卉紋蒜頭大瓶</a>】请在2025-01-07 01:14之前支付完成支付。否则将扣除您参与该拍卖所缴纳的保证金或信用额度！','1735578861');
INSERT INTO `on_mysms` VALUES ('277','26','0','0','0','0','订单状态通知','0','0','订单号：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Member/order_details/order_no/BID173557886177039/aptitude/1.html">BID173557886177039</a>”已生成订单，请在2025-01-07 01:14前支付订单！商品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/2/aptitude/1.html"> 清雍正 青花纏枝花卉紋蒜頭大瓶</a>”。','1735578861');
INSERT INTO `on_mysms` VALUES ('278','4','0','0','0','0','订单状态通知','0','0','订单号：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Member/order_details/order_no/BID173557886177039/aptitude/1.html">BID173557886177039</a>”已生成订单，等待买家支付！商品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/2/aptitude/1.html"> 清雍正 青花纏枝花卉紋蒜頭大瓶</a>”。','1735578861');
INSERT INTO `on_mysms` VALUES ('279','36','0','0','0','0','管理员充值','0','0','管理员充值余额【5000000元】，单号aad173560471069697','1735604710');
INSERT INTO `on_mysms` VALUES ('280','10','0','0','0','0','竞拍出价被超越','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/34/aptitude/1.html">清康熙青花人物雙耳瓶</a>”出价【4100000.00元】已被超过。','1735604764');
INSERT INTO `on_mysms` VALUES ('281','5','0','0','0','0','拍品出价更新','0','0','拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/34/aptitude/1.html">清康熙青花人物雙耳瓶</a>”当前价【4400000.00元】，目前领先','1735604764');
INSERT INTO `on_mysms` VALUES ('282','36','0','0','0','0','竞拍出价成功','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/34/aptitude/1.html">清康熙青花人物雙耳瓶</a>”出价【4400000.00元】成功！','1735604764');
INSERT INTO `on_mysms` VALUES ('283','36','0','0','0','0','参与拍品拍竞价冻结保证金','0','0','参拍“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/34/aptitude/1.html">清康熙青花人物雙耳瓶</a>”冻结保证金【50000.00元】','1735604764');
INSERT INTO `on_mysms` VALUES ('284','37','0','0','0','0','管理员充值','0','0','管理员充值余额【5000000元】，单号aad173560532712872','1735605327');
INSERT INTO `on_mysms` VALUES ('285','9','0','0','0','0','竞拍出价被超越','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/35/aptitude/1.html">元 青花蒙括將軍玉壺春瓶</a>”出价【3500000.00元】已被超过。','1735605366');
INSERT INTO `on_mysms` VALUES ('286','5','0','0','0','0','拍品出价更新','0','0','拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/35/aptitude/1.html">元 青花蒙括將軍玉壺春瓶</a>”当前价【3800000.00元】，目前领先','1735605366');
INSERT INTO `on_mysms` VALUES ('287','37','0','0','0','0','竞拍出价成功','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/35/aptitude/1.html">元 青花蒙括將軍玉壺春瓶</a>”出价【3800000.00元】成功！','1735605366');
INSERT INTO `on_mysms` VALUES ('288','37','0','0','0','0','参与拍品拍竞价冻结保证金','0','0','参拍“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/35/aptitude/1.html">元 青花蒙括將軍玉壺春瓶</a>”冻结保证金【50000.00元】','1735605367');
INSERT INTO `on_mysms` VALUES ('289','38','0','0','0','0','管理员充值','0','0','管理员充值余额【5000000元】，单号aad173560593719538','1735605937');
INSERT INTO `on_mysms` VALUES ('290','5','0','0','0','0','拍品出价更新','0','0','拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/7/aptitude/1.html">清乾隆《松石綠錦地浮雕金彩百壽琮式瓶》</a>”当前价【6800000.00元】，目前领先','1735606325');
INSERT INTO `on_mysms` VALUES ('291','38','0','0','0','0','竞拍出价成功','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/7/aptitude/1.html">清乾隆《松石綠錦地浮雕金彩百壽琮式瓶》</a>”出价【6800000.00元】成功！','1735606325');
INSERT INTO `on_mysms` VALUES ('292','38','0','0','0','0','参与拍品拍竞价冻结保证金','0','0','参拍“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/7/aptitude/1.html">清乾隆《松石綠錦地浮雕金彩百壽琮式瓶》</a>”冻结保证金【50000.00元】','1735606325');
INSERT INTO `on_mysms` VALUES ('293','38','0','0','0','0','系统提示','1','0','恭喜您以6800000.00元拍到[【<a target="_blank" href="/Auction/details/pid/7/aptitude/1.html">清乾隆《松石綠錦地浮雕金彩百壽琮式瓶》</a>】请在2025-01-07 09:19之前支付完成支付。否则将扣除您参与该拍卖所缴纳的保证金或信用额度！','1735607964');
INSERT INTO `on_mysms` VALUES ('294','38','0','0','0','0','订单状态通知','0','0','订单号：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Member/order_details/order_no/BID173560796468590/aptitude/1.html">BID173560796468590</a>”已生成订单，请在2025-01-07 09:19前支付订单！商品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/7/aptitude/1.html">清乾隆《松石綠錦地浮雕金彩百壽琮式瓶》</a>”。','1735607964');
INSERT INTO `on_mysms` VALUES ('295','5','0','0','0','0','订单状态通知','0','0','订单号：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Member/order_details/order_no/BID173560796468590/aptitude/1.html">BID173560796468590</a>”已生成订单，等待买家支付！商品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/7/aptitude/1.html">清乾隆《松石綠錦地浮雕金彩百壽琮式瓶》</a>”。','1735607964');
INSERT INTO `on_mysms` VALUES ('296','37','0','0','0','0','竞拍出价被超越','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/35/aptitude/1.html">元 青花蒙括將軍玉壺春瓶</a>”出价【3800000.00元】已被超过。','1735618174');
INSERT INTO `on_mysms` VALUES ('297','5','0','0','0','0','拍品出价更新','0','0','拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/35/aptitude/1.html">元 青花蒙括將軍玉壺春瓶</a>”当前价【4100000.00元】，目前领先','1735618174');
INSERT INTO `on_mysms` VALUES ('298','38','0','0','0','0','竞拍出价成功','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/35/aptitude/1.html">元 青花蒙括將軍玉壺春瓶</a>”出价【4100000.00元】成功！','1735618174');
INSERT INTO `on_mysms` VALUES ('299','38','0','0','0','0','参与拍品拍竞价冻结保证金','0','0','参拍“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/35/aptitude/1.html">元 青花蒙括將軍玉壺春瓶</a>”冻结保证金【50000.00元】','1735618174');
INSERT INTO `on_mysms` VALUES ('300','32','0','0','0','0','提现冻结','0','0','提现暂时冻结可用余额，等待提现完成扣除！','1735621942');
INSERT INTO `on_mysms` VALUES ('301','32','0','0','0','0','系统发送','0','0','管理员已同意您的30.00元保证金提现申请！即将为您转账请注意查收！备注：30.00','1735638219');
INSERT INTO `on_mysms` VALUES ('302','26','0','0','0','0','保证金解冻','0','0','拍品【<a  target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/34/aptitude/1.html">清康熙青花人物雙耳瓶</a>】结束解冻保证金50000.00元；','1735713288');
INSERT INTO `on_mysms` VALUES ('303','10','0','0','0','0','保证金解冻','0','0','拍品【<a  target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/34/aptitude/1.html">清康熙青花人物雙耳瓶</a>】结束解冻保证金50000.00元；','1735713288');
INSERT INTO `on_mysms` VALUES ('304','36','0','0','0','0','系统提示','0','0','恭喜您以4400000.00元拍到[【<a target="_blank" href="/Auction/details/pid/34/aptitude/1.html">清康熙青花人物雙耳瓶</a>】请在2025-01-08 14:34之前支付完成支付。否则将扣除您参与该拍卖所缴纳的保证金或信用额度！','1735713288');
INSERT INTO `on_mysms` VALUES ('305','36','0','0','0','0','订单状态通知','0','0','订单号：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Member/order_details/order_no/BID173571328830643/aptitude/1.html">BID173571328830643</a>”已生成订单，请在2025-01-08 14:34前支付订单！商品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/34/aptitude/1.html">清康熙青花人物雙耳瓶</a>”。','1735713288');
INSERT INTO `on_mysms` VALUES ('306','5','0','0','0','0','订单状态通知','0','0','订单号：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Member/order_details/order_no/BID173571328830643/aptitude/1.html">BID173571328830643</a>”已生成订单，等待买家支付！商品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/34/aptitude/1.html">清康熙青花人物雙耳瓶</a>”。','1735713288');
INSERT INTO `on_mysms` VALUES ('307','22','0','0','0','0','参与拍品拍竞价冻结保证金','0','0','参拍“<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/43/aptitude/1.html">清乾隆 琺瑯彩贲巴壺</a>”冻结保证金【50000.00元】','1735715811');
INSERT INTO `on_mysms` VALUES ('308','5','0','0','0','0','拍品出价更新','0','0','拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/43/aptitude/1.html">清乾隆 琺瑯彩贲巴壺</a>”当前价【3000000.00元】，目前领先','1735715811');
INSERT INTO `on_mysms` VALUES ('309','22','0','0','0','0','竞拍出价成功','0','0','您参拍拍品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/43/aptitude/1.html">清乾隆 琺瑯彩贲巴壺</a>”出价【3000000.00元】成功！','1735715811');
INSERT INTO `on_mysms` VALUES ('310','5','0','0','0','0','解冻信誉','0','0','拍品流拍<a href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/44/aptitude/1.html">【清康熙 撇口瓶】</a>解冻信誉50000.00元！','1735784430');
INSERT INTO `on_mysms` VALUES ('311','12','0','0','0','0','保证金解冻','0','0','拍品【<a  target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/35/aptitude/1.html">元 青花蒙括將軍玉壺春瓶</a>】结束解冻保证金50000.00元；','1735797707');
INSERT INTO `on_mysms` VALUES ('312','9','0','0','0','0','保证金解冻','0','0','拍品【<a  target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/35/aptitude/1.html">元 青花蒙括將軍玉壺春瓶</a>】结束解冻保证金50000.00元；','1735797707');
INSERT INTO `on_mysms` VALUES ('313','37','0','0','0','0','保证金解冻','0','0','拍品【<a  target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/35/aptitude/1.html">元 青花蒙括將軍玉壺春瓶</a>】结束解冻保证金50000.00元；','1735797707');
INSERT INTO `on_mysms` VALUES ('314','38','0','0','0','0','系统提示','0','0','恭喜您以4100000.00元拍到[【<a target="_blank" href="/Auction/details/pid/35/aptitude/1.html">元 青花蒙括將軍玉壺春瓶</a>】请在2025-01-09 14:01之前支付完成支付。否则将扣除您参与该拍卖所缴纳的保证金或信用额度！','1735797707');
INSERT INTO `on_mysms` VALUES ('315','38','0','0','0','0','订单状态通知','0','0','订单号：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Member/order_details/order_no/BID173579770714638/aptitude/1.html">BID173579770714638</a>”已生成订单，请在2025-01-09 14:01前支付订单！商品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/35/aptitude/1.html">元 青花蒙括將軍玉壺春瓶</a>”。','1735797707');
INSERT INTO `on_mysms` VALUES ('316','5','0','0','0','0','订单状态通知','0','0','订单号：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Member/order_details/order_no/BID173579770714638/aptitude/1.html">BID173579770714638</a>”已生成订单，等待买家支付！商品：“<a target="_blank" href="http://wmiw.ebnnw.cn/Home/Auction/details/pid/35/aptitude/1.html">元 青花蒙括將軍玉壺春瓶</a>”。','1735797707');


# 数据库表：on_navigation 数据信息


# 数据库表：on_news 数据信息
INSERT INTO `on_news` VALUES ('1','2','CHRISTIE’S 佳士得','undefinedundefined','關於佳士得','一個乾隆九年開始的故事。','1','佳士得（Christie's）是世界上最知名和最受尊敬的拍賣公司之一，成立於1766年，佳士得擁有超過250年的悠久歷史。總部位於英國倫敦。佳士得在全球設有多個辦事處和展覽空間，包括倫敦、紐約、香港、巴黎、日內瓦、杜拜等地。','1733930313','1734535803','<p><span class="text_B_eob" data-text="true" style="box-sizing: content-box; margin: 0px; padding: 0px; position: relative; color: rgb(51, 51, 51); font-family: " helvetica="" pingfang="" hiragino="" sans="" microsoft="" wenquanyi="" micro="" font-size:="" text-indent:="" text-wrap-mode:="" background-color:=""></span>佳士得拍賣行，1766年由詹姆士·佳士得（James Christie）在倫敦創立，為世界上歷史最悠久的藝術品拍賣行。 1766年12月5日，詹姆斯·佳士得在倫敦進行了他的首次拍賣；自此，佳士得拍賣行致力為顧客提供優質精良的服務。作風穩健、能言善道和極富幽默感的詹姆斯·佳士得將拍賣演變為一項精緻的藝術，並於18世紀至19世紀期間進行了多場非常重要的拍賣。<span class="text_B_eob" data-text="true" style="box-sizing: content-box; margin: 0px; padding: 0px; position: relative; color: rgb(51, 51, 51); font-family: " helvetica="" pingfang="" hiragino="" sans="" microsoft="" wenquanyi="" micro="" font-size:="" text-indent:="" text-wrap-mode:="" background-color:=""></span></p><p><br/></p><p>創辦人</p><p>1766年一位來自澳洲佩思的蘇格蘭人在倫敦開設了第一家藝術品拍賣行，他的名字叫詹姆士‧佳士得（James Christie），於是拍賣行的名稱就叫「佳士得拍賣公司」。當時，正是滿清的干隆皇帝統治的中期，已有255年的歷史了。<br/><br/></p><p><a style="box-sizing: content-box; margin: 0px; padding: 0px; color: rgb(19, 110, 194); text-decoration-skip-ink: auto; position: absolute; top: -80px;" name="首次拍卖"></a><a style="box-sizing: content-box; margin: 0px; padding: 0px; color: rgb(19, 110, 194); text-decoration-skip-ink: auto; position: absolute; top: -80px;" name="2-2"></a></p><p>首次拍賣</p><p>公司開幕不久，佳士得即於1766年12月5日籌備舉行了首次拍賣，引起英國民眾的注意。在此後的兩百多年中，佳士得公司好事不斷，生意也越做越大。 1778年，佳士得公司成功地為豪頓的渥爾波爵士珍藏的繪畫作品進行估價，商議賣價為4萬英鎊，悉數轉讓給買家俄羅斯帝國的葉卡捷琳娜女王。當時的4萬英鎊，對一般人來說可是一個天文數字。<span style="text-wrap-mode: nowrap;">1795年佳士得又做了兩樁大買賣，四天的雷諾茲爵士藏品拍賣賣得10319英鎊；在杜巴莉夫人授權下，她的珠寶飾品又拍賣得近1萬英鎊。</span></p><p><span style="text-wrap-mode: nowrap;">兩年以後，英國著名畫家賀加斯的《婚禮風俗》油畫賣得1050英鎊。這是第一件突破1千英鎊的拍賣品。</span></p><p><span class="text_B_eob" data-text="true" style="box-sizing: content-box; margin: 0px; padding: 0px; position: relative;"></span><br/></p><p>子承父業</p><p>1803年，詹姆士‧佳士得逝世，小佳士得接掌公司。在他任職的近30年中，拍賣成績平平，只是將拍賣行由原來的泡爾路搬到了國王街8號，如今那裡已成為佳士得公司的總部。 1831年，小佳士得也死了，有一個名叫威廉·曼森的人加入該公司，並掌管事務，公司於是改名為「佳士得和曼森拍賣行」。但曼森期間，拍賣成績也不怎樣，僅在1848年藉斯篤大樓搞了一次長達40天的白金漢公爵藏品拍賣，也不過賣得77562英鎊。 1859年托馬斯·伍茲加入該公司，執掌大權，公司又更名為「佳士得、曼森和伍茲拍賣行」。<br/>1876年，英國著名風景畫和肖像畫家庚斯博羅（1727-1788）的《特文肖公爵夫人像》由佳士得公司拍賣，成為第1件拍賣得1萬英鎊的藝術品。此後名家油畫的身價大增，1882年佳士得在倫敦漢密爾頓宮舉辦為期17天的拍賣會，其中有11幅油畫被國立美術館看中，以總計397562英鎊的價格成交。從此，佳士得在整個歐洲的名聲大噪。<br/><br/></p><p><a style="box-sizing: content-box; margin: 0px; padding: 0px; color: rgb(19, 110, 194); text-decoration-skip-ink: auto; position: absolute; top: -80px;" name="一战期间"></a><a style="box-sizing: content-box; margin: 0px; padding: 0px; color: rgb(19, 110, 194); text-decoration-skip-ink: auto; position: absolute; top: -80px;" name="2-4"></a></p><p><span style="text-wrap-mode: nowrap;">一戰期間</span></p><p><span style="text-wrap-mode: nowrap;">第一次世界大戰期間，佳士得為紅十字會總共舉辦過7次系列義拍活動，統共得款40萬多英鎊。</span><span style="text-wrap-mode: nowrap;">1926年英國畫家羅姆尼（1734-1802）的《達文波夫人像》賣得60900英鎊，那是兩次世界大戰之間最貴的藝術品了。另外值得一提的是，1928年從豪福特收藏館拿出的78幅畫作一個早上就被拍賣一光，共得364994英鎊。</span></p><h3 name="2-4" style="box-sizing: content-box; margin: 0px; padding: 0px; font-size: 18px; font-weight: 400;"><span class="text_B_eob" data-text="true" style="box-sizing: content-box; margin: 0px; padding: 0px; position: relative;"></span><br/></h3><p><a style="box-sizing: content-box; margin: 0px; padding: 0px; color: rgb(19, 110, 194); text-decoration-skip-ink: auto; position: absolute; top: -80px;" name="二战过后"></a><a style="box-sizing: content-box; margin: 0px; padding: 0px; color: rgb(19, 110, 194); text-decoration-skip-ink: auto; position: absolute; top: -80px;" name="2-5"></a></p><p><span style="text-wrap-mode: nowrap;">二戰過後</span></p><p><span style="text-wrap-mode: nowrap;">第二次世界大戰開始，人心惶惶，藝術品拍賣轉入低潮，1941年倫敦的“不列顛大轟炸”，佳士得公司駐地毀壞慘重，更是雪上加霜。以後的辦公地東遷西搬，直到1953年老地方國王街8號新大樓造就，才又遷回原處，安定下來。</span></p><p><span style="text-wrap-mode: nowrap;">隨著戰後經濟的復甦，巨商大賈腰包裡又有了錢，藝術品買賣又始興隆起來，1956年一張荷蘭畫家倫勃朗的《梯德斯像》賣得798,000英鎊。 1970年更不尋常，西班牙畫家委拉斯凱茲（1599-1660）的《朱安·德·帕勒加像》以231萬英鎊的價格成交，它是拍賣史上第一件打破100萬英鎊紀錄的藝術品。</span></p><h3 name="2-5" style="box-sizing: content-box; margin: 0px; padding: 0px; font-size: 18px; font-weight: 400;"><span style="font-size: 16px;">從1968年開始，佳士得注意拓展國外業務，該年在日內瓦設立第一個國外辦事處，專營珠寶業務。 1977年又在美國紐約設立辦事處，並舉辦拍賣會，大獲成功，1979年在紐約又設立一個新的辦事處，名為「東佳士得」。值得一提的是，1973年佳士得成為大眾投資的股份公司，股票在倫敦股票交易所上市，這大大增強了公司的活力。<br/><br/></span><span class="text_B_eob" data-text="true" style="box-sizing: content-box; margin: 0px; padding: 0px; position: relative;"></span></h3><p><a style="box-sizing: content-box; margin: 0px; padding: 0px; color: rgb(19, 110, 194); text-decoration-skip-ink: auto; position: absolute; top: -80px;" name="巅峰时期"></a><a style="box-sizing: content-box; margin: 0px; padding: 0px; color: rgb(19, 110, 194); text-decoration-skip-ink: auto; position: absolute; top: -80px;" name="2-6"></a></p><p>巔峰時期</p><p>1980年代是佳士得大發的年代，1980年紐約福特收藏館的印象派畫家作品賣得600萬英鎊，從此印象派繪畫行情看好，有後來居上之勢。 1984年對恰茲沃斯所藏的71幅18世紀前歐洲偉大畫家的作品進行拍賣，合計賣得2千萬英鎊，其中拉斐爾的一幅作品賣得350萬英鎊。同年在賽福克的埃爾衛登廳舉行的拍賣會也賣得620萬英鎊。</p><h3 name="2-6" style="box-sizing: content-box; margin: 0px; padding: 0px; font-size: 18px; font-weight: 400;"><span style="font-size: 16px;">1985年，義大利文藝復興初期畫家曼坦尼亞（1431-1506）的一幅《博士來拜》成為拍賣過的18世紀前歐洲名畫家作品中最貴一幅，高達810萬英鎊。印象派繪畫也不甘落後，1986年馬奈的一幅《莫斯納派物斯大街》賣到。 770萬英鎊，創印象派作品的最高紀錄。</span><span class="text_B_eob" data-text="true" style="box-sizing: content-box; margin: 0px; padding: 0px; position: relative;"></span></h3><p><span class="text_B_eob" data-text="true" style="box-sizing: content-box; margin: 0px; padding: 0px; position: relative;"></span>好事還在後頭。 1987年是佳士得輝煌的一年。梵谷的《向日葵》創歷史最高紀錄，達2,475萬英鎊。另一幅梵谷的《欽克泰勒大橋》賣得1265萬英鎊。一幅德加的作品則高達748萬英鎊。另外，一部古登堡《聖經》賣得326萬英鎊；一顆重64.83克拉的法勞萊的鑽石賣得384萬英鎊；一輛布蓋提路易爾牌的轎車賣得550萬英鎊。 1988年，梵谷、畢卡索等人的作品都有拍賣好成績，像梵谷的《拉沃克絲像》賣得1375萬美元（733萬英鎊），畢卡索的《耍雜技的年輕小丑》賣得209萬英鎊。<span class="text_B_eob" data-text="true" style="box-sizing: content-box; margin: 0px; padding: 0px; position: relative;"></span></p><p><span class="text_B_eob" data-text="true" style="box-sizing: content-box; margin: 0px; padding: 0px; position: relative;"></span>1989年，佳士得公司做了這麼幾件大事：文藝復興畫家蓬托莫的《美第奇公爵像》賣得3520萬美元（2230萬英鎊），打破以往18世紀前歐洲大畫家作品拍賣的所有紀錄。還有一張尼古拉的棕色桌子在紐約賣得1210萬美元（750萬鎊），除繪畫外，在藝術品中算是世界紀錄。<br/><span class="text_B_eob" data-text="true" style="box-sizing: content-box; margin: 0px; padding: 0px; position: relative;"><br/></span>同時，佳士得在東方拓展業務，在香港成立太古佳士得有限公司，這是佳士得公司在1984年設立香港辦事處的基礎上發展而來的。<br/><br/><span class="text_B_eob" data-text="true" style="box-sizing: content-box; margin: 0px; padding: 0px; position: relative;"></span></p><p><br/></p><p><br/></p>','1');
INSERT INTO `on_news` VALUES ('2','3','韧性与活力并存，永樂2024秋拍第二日2.19亿元收官，石涛、张大千领衔','','石涛、张大千','古画夜场','1','12月10日晚，随着“中国书画夜场”的顺利收槌，永樂2024秋拍中国书画、古籍部分圆满收官，三场拍卖920余件拍品总成交2.19亿元，2件拍品成交价过千万。纵观本季中国书画拍卖，以专题性、学术性为线索，注重名家精品、名人旧藏，同时在估价上也更具吸引力。石涛、王时敏、王鉴、张大千、傅抱石、吴冠中、齐白石、李可染等多位名家精品成交稳健，重磅拍品悉数成交，尤其是来源清晰的名人旧藏成为众多藏家竞拍的焦点；特设的“变革之路—吴冠中、林风眠专辑”“纪念李可染写生七十周年书画作品专题”100%成交，为市场注入蓬勃新活力','1733933127','1733933127','<p style="margin-top: 1em; margin-bottom: 1em; padding: 0px; overflow-wrap: break-word; font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">中国书画夜场共有165件作品上拍，汇聚了张大千、傅抱石、吴冠中、李可染、齐白石、徐悲鸿、刘海粟、溥儒等名家佳作，可谓是星光璀璨。最终历经5个小时的鏖战总成交逾 1.79亿元，成交率达88%，2件拍品超千万元成交。</p><p style="margin-top: 1em; margin-bottom: 1em; padding: 0px; overflow-wrap: break-word; font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">近现代书画的领衔之作，<strong>张大千“血战古人”的力作《仿董北苑江堤晚景》吸引众多买家纷纷举牌，竞争激烈，最终以1207.5万元成交</strong>，再次证明了其在中国书画市场中的重要地位。和许多画家一样，张大千也同样经历了描摹之路。他师古人、师近人、师万物、师造化，最后达到“师心“的境界。《仿董北苑江堤晚景》则是在1947年十月间所仿，行笔如流水，水石幽深，平远险易之行，莫不曲尽其妙。</p><p><strong style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">傅抱石典型的金刚坡风格作品</strong><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">，创作于1944年，再现其曾经居住过而且形成艺术风貌的环境之</span><strong style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">《金刚坡秋色》以920万元成交。</strong><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">这幅画更多像实景写生，重庆的天气多雾潮湿，所以成就了傅抱石独特的氤氲的画风。在这个时期傅抱石就把中国山水画的皴法中间最自由的几种皴法比如说像乱麻皴、乱柴皴、卷云皴这样的一些非常自由的而适合于他的个性的一些皴法用之于来表现巴山蜀水，并形成了他基本的艺术面貌。</span></p><p><span style="background-color: rgb(255, 255, 255); font-family: 宋体, Verdana, Arial; font-size: 14px;">值得一提的是，</span><strong style="background-color: rgb(255, 255, 255); font-family: 宋体, Verdana, Arial; font-size: 14px;">本场<strong>特设专题表现抢眼。</strong></strong><span style="background-color: rgb(255, 255, 255); font-family: 宋体, Verdana, Arial; font-size: 14px;">其中，</span><strong style="background-color: rgb(255, 255, 255); font-family: 宋体, Verdana, Arial; font-size: 14px;">“变革之路—吴冠中、林风眠专辑”（Lot 9014-9018）</strong><span style="background-color: rgb(255, 255, 255); font-family: 宋体, Verdana, Arial; font-size: 14px;">竞投热络，5件作品悉数成交，总揽1932万元。其中，能够集中体现吴冠中关于点、线、面和黑、白、灰等绘画形式语言的理解的</span><strong style="background-color: rgb(255, 255, 255); font-family: 宋体, Verdana, Arial; font-size: 14px;">《新林》以920万元成交</strong><span style="background-color: rgb(255, 255, 255); font-family: 宋体, Verdana, Arial; font-size: 14px;">；同样作于1988年的吴冠中</span><strong style="background-color: rgb(255, 255, 255); font-family: 宋体, Verdana, Arial; font-size: 14px;">《枣林》以345万元成交</strong><span style="background-color: rgb(255, 255, 255); font-family: 宋体, Verdana, Arial; font-size: 14px;">。此外，林风眠仕女作品</span><strong style="background-color: rgb(255, 255, 255); font-family: 宋体, Verdana, Arial; font-size: 14px;">《捧花仕女》《抱琴仕女》</strong><span style="background-color: rgb(255, 255, 255); font-family: 宋体, Verdana, Arial; font-size: 14px;">也备受青睐，均以287.5万元成交。</span><br/></p><p><span style="background-color: rgb(255, 255, 255); font-family: 宋体, Verdana, Arial; font-size: 14px;"><strong style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">“纪念李可染写生七十周年书画作品专题”</strong><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">（Lot9029-9040）同样取得100%成交，总成交超900万元。其中，</span><strong style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">李可染笔下罕有的明丽轻快的江南水乡作品《江南春雨》以488.75万元成交</strong><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">领衔。此外，李可染</span><strong style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">《一览众山小》《观瀑图》</strong><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">分别以138万元、103.5万元成交；李可染经典的动物题材作品</span><strong style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">《五牛图》</strong><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">以92万元成交。</span></span></p><p><span style="background-color: rgb(255, 255, 255); font-family: 宋体, Verdana, Arial; font-size: 14px;"><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);"><strong style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">此外，齐白石、刘海粟、徐悲鸿、溥儒等名家佳构成交喜人，为专场增添了一抹亮色。</strong><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">以酣畅淋漓的泼彩表现黄山气象万千与云海的变幻莫测的</span><strong style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">刘海粟《黄山祥云》以379.5万元成交</strong><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">；外形健朗壮美，且具有坚毅雄强气质的</span><strong style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">徐悲鸿《奔马》以345万元成交</strong><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">；画面幽静秀美的</span><strong style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">溥儒《杂画集锦册》</strong><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">吸引了众多买家的目光，经过激烈角逐，最终</span><strong style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">以138万元成交</strong><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">。</span></span></span></p><p><strong style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">古代书画部分，由石涛《山水集册·纪游图咏》以2357.5万元成交领衔。</strong><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">此作以850万元起拍，引得多位藏家青睐，并以最小的竞价阶梯展开拉锯战，很快突破1000万元的最高估价，在竞价至1900万元时仍有4位藏家在踊跃竞投，此后又经2000万、2020万，最终止步2050万元，含佣金以2357.5万元成交，成为永樂2024秋拍书画最高成交拍品。</span></p><p><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);"><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">作为清代最具传奇色彩的绘画大师，石涛《山水集册·纪游图咏》以十二开册页形式纪游图咏，诗画对题，足称石涛“诗书画”三绝的惊世之作。张大千藏此石涛册页，不惜重金特为刊印出版多次，钤印累累，几乎倾注半生之情，难以割舍，自此册页的每一笔中汲取养分与解放。石涛艺术生涯中极具代表性的一部，汇聚他的艺术与思想精华。大涤草堂，搜尽奇峰，尽数在此。</span><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">“如观自在——中国书画专场”总揽2379.35万元。</span><strong style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">杨文骢仿董北苑笔意的《为周亮工作山水》以92万元入袋新藏家。</strong><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">此作杨文骢不以传统的构图法意识去控制画面布局，松散的置放，反而达到一种自然与幻想的精妙感。此外，</span><strong style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">项圣谟《山居图》</strong><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">以及</span><strong style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">上官周《松柳重翠》</strong><span style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">同样善价成交，</span><strong style="font-family: 宋体, Verdana, Arial; font-size: 14px; text-wrap: wrap; background-color: rgb(255, 255, 255);">分别录得86.25万元、69万元。</strong></span></p>','1');
INSERT INTO `on_news` VALUES ('3','1','ssss','','sss','','0','佳士得&nbsp; &nbsp;','1734482064','1734482064','<p>
    <a href="https://www.christies.com/zh/events/hong-kong-auctions/what-is-on" target="_self">佳士得</a>&nbsp; &nbsp;<br/>
</p>','1');


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
INSERT INTO `on_payorder` VALUES ('czy173428761650223','czy173428761650223','pledge','0','1','1000.00','0.00','0.00','ALI_WEB','充值余额','https://wmiw.ebnnw.cn/Home/Member/wallet','https://wmiw.ebnnw.cn/Home/Member/payment','0','1734287616','1734287616');
INSERT INTO `on_payorder` VALUES ('czy173465479783368','czy173465479783368','pledge','0','5','10.00','0.00','0.00','ALI_WEB','充值余额','http://wmiw.ebnnw.cn/Home/Member/wallet','http://wmiw.ebnnw.cn/Home/Member/payment','0','1734654797','1734654797');
INSERT INTO `on_payorder` VALUES ('czy173492105337686','czy173492105337686','pledge','0','5','100.00','0.00','0.00','ALI_WEB','充值余额','http://wmiw.ebnnw.cn/Home/Member/wallet','http://wmiw.ebnnw.cn/Home/Member/payment','0','1734921053','1734921053');
INSERT INTO `on_payorder` VALUES ('czy173492108350832','czy173492108350832','pledge','0','5','100.00','0.00','0.00','JD_WEB','充值余额','http://wmiw.ebnnw.cn/Home/Member/wallet','http://wmiw.ebnnw.cn/Home/Member/payment','0','1734921083','1734921083');
INSERT INTO `on_payorder` VALUES ('zfp173528962979559','BID173521440485271','auction','28','12','1243000.00','0.00','50000.00','ALI_WEB','支付拍品订单','http://wmiw.ebnnw.cn/Member/mysucc/st/1','/Member/payment_order/order_no/BID173521440485271/1/1.html','0','1735289629','1735289629');


# 数据库表：on_rechargeable 数据信息


# 数据库表：on_role 数据信息
INSERT INTO `on_role` VALUES ('6','审核小组','','1','');
INSERT INTO `on_role` VALUES ('7','审核员1','6','1','');
INSERT INTO `on_role` VALUES ('8','发布小组','6','0','');


# 数据库表：on_role_user 数据信息
INSERT INTO `on_role_user` VALUES ('7','13');
INSERT INTO `on_role_user` VALUES ('7','2');


# 数据库表：on_scheduled 数据信息
INSERT INTO `on_scheduled` VALUES ('28','16','ing','0');
INSERT INTO `on_scheduled` VALUES ('29','17','ing','0');


# 数据库表：on_seller_pledge 数据信息
INSERT INTO `on_seller_pledge` VALUES ('1','1','1','every','200.00','0.00','1733935104','1');
INSERT INTO `on_seller_pledge` VALUES ('2','4','2','every','200.00','0.00','1734106105','1');
INSERT INTO `on_seller_pledge` VALUES ('3','4','4','every','200.00','0.00','1734281692','1');
INSERT INTO `on_seller_pledge` VALUES ('4','4','5','every','200.00','0.00','1734284624','1');
INSERT INTO `on_seller_pledge` VALUES ('5','1','6','every','200.00','0.00','1734287530','1');
INSERT INTO `on_seller_pledge` VALUES ('6','5','7','every','0.00','200.00','1734290379','1');
INSERT INTO `on_seller_pledge` VALUES ('7','5','8','every','0.00','200.00','1734290764','1');
INSERT INTO `on_seller_pledge` VALUES ('8','5','9','every','0.00','200.00','1734290954','1');
INSERT INTO `on_seller_pledge` VALUES ('9','5','10','every','0.00','200.00','1734291153','1');
INSERT INTO `on_seller_pledge` VALUES ('10','5','11','every','0.00','200.00','1734291243','1');
INSERT INTO `on_seller_pledge` VALUES ('11','5','12','every','0.00','200.00','1734291474','1');
INSERT INTO `on_seller_pledge` VALUES ('12','5','13','every','0.00','200.00','1734291678','1');
INSERT INTO `on_seller_pledge` VALUES ('13','5','14','every','0.00','200.00','1734291815','1');
INSERT INTO `on_seller_pledge` VALUES ('14','5','15','every','0.00','200.00','1734292784','1');
INSERT INTO `on_seller_pledge` VALUES ('15','5','16','every','0.00','50000.00','1734293795','1');
INSERT INTO `on_seller_pledge` VALUES ('16','5','17','every','0.00','50000.00','1734295000','1');
INSERT INTO `on_seller_pledge` VALUES ('17','5','18','every','0.00','50000.00','1734295535','1');
INSERT INTO `on_seller_pledge` VALUES ('18','5','19','every','0.00','50000.00','1734295714','1');
INSERT INTO `on_seller_pledge` VALUES ('19','5','20','every','0.00','50000.00','1734297248','1');
INSERT INTO `on_seller_pledge` VALUES ('20','5','21','every','0.00','50000.00','1734297728','1');
INSERT INTO `on_seller_pledge` VALUES ('21','5','24','every','0.00','50000.00','1734469121','1');
INSERT INTO `on_seller_pledge` VALUES ('22','5','25','every','0.00','50000.00','1734469442','1');
INSERT INTO `on_seller_pledge` VALUES ('23','5','26','every','0.00','50000.00','1734469702','1');
INSERT INTO `on_seller_pledge` VALUES ('24','5','27','every','0.00','50000.00','1734470583','1');
INSERT INTO `on_seller_pledge` VALUES ('25','5','28','every','0.00','50000.00','1734932878','1');
INSERT INTO `on_seller_pledge` VALUES ('26','5','29','every','0.00','50000.00','1734959194','1');
INSERT INTO `on_seller_pledge` VALUES ('27','5','30','every','0.00','50000.00','1735097415','1');
INSERT INTO `on_seller_pledge` VALUES ('28','5','31','every','0.00','50000.00','1735291283','1');
INSERT INTO `on_seller_pledge` VALUES ('29','5','32','every','0.00','50000.00','1735325469','1');
INSERT INTO `on_seller_pledge` VALUES ('30','5','33','every','0.00','50000.00','1735326856','1');
INSERT INTO `on_seller_pledge` VALUES ('31','5','34','every','0.00','50000.00','1735454047','1');
INSERT INTO `on_seller_pledge` VALUES ('32','5','35','every','0.00','50000.00','1735472832','1');
INSERT INTO `on_seller_pledge` VALUES ('33','5','36','every','0.00','50000.00','1735539373','1');
INSERT INTO `on_seller_pledge` VALUES ('34','5','37','every','0.00','50000.00','1735540636','1');
INSERT INTO `on_seller_pledge` VALUES ('35','5','38','every','0.00','50000.00','1735541084','1');
INSERT INTO `on_seller_pledge` VALUES ('36','5','39','every','0.00','50000.00','1735541380','1');
INSERT INTO `on_seller_pledge` VALUES ('37','5','40','every','0.00','50000.00','1735541633','1');
INSERT INTO `on_seller_pledge` VALUES ('38','5','41','every','0.00','50000.00','1735542204','1');
INSERT INTO `on_seller_pledge` VALUES ('39','5','42','every','0.00','50000.00','1735542604','1');
INSERT INTO `on_seller_pledge` VALUES ('40','5','43','every','0.00','50000.00','1735552478','1');
INSERT INTO `on_seller_pledge` VALUES ('41','5','44','every','0.00','50000.00','1735553261','0');


# 数据库表：on_share 数据信息


# 数据库表：on_special_auction 数据信息


# 数据库表：on_weiurl 数据信息
