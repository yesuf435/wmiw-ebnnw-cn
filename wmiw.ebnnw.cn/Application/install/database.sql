-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2021-08-27 18:06:10
-- 服务器版本： 5.6.50-log
-- PHP 版本： 7.3.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `ext`
--

-- --------------------------------------------------------

--
-- 表的结构 `on_access`
--

CREATE TABLE `on_access` (
  `role_id` smallint(6) UNSIGNED NOT NULL,
  `node_id` smallint(6) UNSIGNED NOT NULL,
  `level` tinyint(1) NOT NULL,
  `pid` smallint(6) DEFAULT NULL,
  `module` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='权限分配表';

--
-- 转存表中的数据 `on_access`
--

INSERT INTO `on_access` (`role_id`, `node_id`, `level`, `pid`, `module`) VALUES
(2, 1, 1, 0, ''),
(2, 2, 2, 1, ''),
(2, 5, 3, 2, ''),
(2, 6, 3, 2, ''),
(2, 112, 3, 2, ''),
(2, 113, 3, 2, ''),
(2, 128, 3, 2, ''),
(2, 136, 3, 2, ''),
(2, 137, 3, 2, ''),
(2, 138, 3, 2, ''),
(2, 139, 3, 2, ''),
(2, 3, 2, 1, ''),
(2, 7, 3, 3, ''),
(2, 45, 3, 3, ''),
(2, 46, 3, 3, ''),
(2, 47, 3, 3, ''),
(2, 48, 3, 3, ''),
(2, 49, 3, 3, ''),
(2, 101, 3, 3, ''),
(2, 122, 3, 3, ''),
(2, 123, 3, 3, ''),
(2, 124, 3, 3, ''),
(2, 125, 3, 3, ''),
(2, 140, 3, 3, ''),
(2, 141, 3, 3, ''),
(2, 142, 3, 3, ''),
(2, 143, 3, 3, ''),
(2, 160, 3, 3, ''),
(2, 161, 3, 3, ''),
(2, 4, 2, 1, ''),
(2, 10, 3, 4, ''),
(2, 11, 3, 4, ''),
(2, 12, 3, 4, ''),
(2, 13, 3, 4, ''),
(2, 95, 3, 4, ''),
(2, 96, 3, 4, ''),
(2, 97, 3, 4, ''),
(2, 98, 3, 4, ''),
(2, 99, 3, 4, ''),
(2, 100, 3, 4, ''),
(2, 14, 2, 1, ''),
(2, 8, 3, 14, ''),
(2, 9, 3, 14, ''),
(2, 15, 3, 14, ''),
(2, 16, 3, 14, ''),
(2, 17, 3, 14, ''),
(2, 18, 3, 14, ''),
(2, 19, 3, 14, ''),
(2, 20, 3, 14, ''),
(2, 21, 3, 14, ''),
(2, 22, 3, 14, ''),
(2, 23, 3, 14, ''),
(2, 24, 3, 14, ''),
(2, 25, 3, 14, ''),
(2, 26, 2, 1, ''),
(2, 27, 3, 26, ''),
(2, 28, 3, 26, ''),
(2, 29, 3, 26, ''),
(2, 30, 3, 26, ''),
(2, 31, 3, 26, ''),
(2, 32, 2, 1, ''),
(2, 33, 3, 32, ''),
(2, 34, 3, 32, ''),
(2, 35, 3, 32, ''),
(2, 36, 3, 32, ''),
(2, 37, 3, 32, ''),
(2, 38, 3, 32, ''),
(2, 39, 3, 32, ''),
(2, 40, 3, 32, ''),
(2, 41, 3, 32, ''),
(2, 42, 3, 32, ''),
(2, 43, 3, 32, ''),
(2, 44, 3, 32, ''),
(2, 50, 2, 1, ''),
(2, 51, 3, 50, ''),
(2, 52, 3, 50, ''),
(2, 53, 3, 50, ''),
(2, 54, 3, 50, ''),
(2, 55, 3, 50, ''),
(2, 56, 3, 50, ''),
(2, 57, 3, 50, ''),
(2, 58, 3, 50, ''),
(2, 59, 3, 50, ''),
(2, 60, 3, 50, ''),
(2, 61, 3, 50, ''),
(2, 62, 3, 50, ''),
(2, 111, 3, 50, ''),
(2, 144, 3, 50, ''),
(2, 145, 3, 50, ''),
(2, 146, 3, 50, ''),
(2, 147, 3, 50, ''),
(2, 150, 3, 50, ''),
(2, 148, 3, 50, ''),
(2, 149, 3, 50, ''),
(2, 151, 3, 50, ''),
(2, 152, 3, 50, ''),
(2, 63, 2, 1, ''),
(2, 64, 3, 63, ''),
(2, 65, 3, 63, ''),
(2, 66, 3, 63, ''),
(2, 67, 3, 63, ''),
(2, 68, 3, 63, ''),
(2, 102, 3, 63, ''),
(2, 103, 3, 63, ''),
(2, 104, 3, 63, ''),
(2, 105, 3, 63, ''),
(2, 106, 3, 63, ''),
(2, 107, 3, 63, ''),
(2, 108, 3, 63, ''),
(2, 109, 3, 63, ''),
(2, 110, 3, 63, ''),
(2, 127, 3, 63, ''),
(2, 134, 3, 63, ''),
(2, 135, 3, 63, ''),
(2, 153, 3, 63, ''),
(2, 154, 3, 63, ''),
(2, 155, 3, 63, ''),
(2, 156, 3, 63, ''),
(2, 157, 3, 63, ''),
(2, 158, 3, 63, ''),
(2, 159, 3, 63, ''),
(2, 162, 3, 63, ''),
(2, 163, 3, 63, ''),
(2, 164, 3, 63, ''),
(2, 165, 3, 63, ''),
(2, 69, 2, 1, ''),
(2, 70, 3, 69, ''),
(2, 71, 3, 69, ''),
(2, 72, 3, 69, ''),
(2, 73, 3, 69, ''),
(2, 74, 3, 69, ''),
(2, 75, 3, 69, ''),
(2, 76, 2, 1, ''),
(2, 77, 3, 76, ''),
(2, 78, 3, 76, ''),
(2, 79, 3, 76, ''),
(2, 80, 3, 76, ''),
(2, 81, 2, 1, ''),
(2, 82, 3, 81, ''),
(2, 83, 3, 81, ''),
(2, 84, 3, 81, ''),
(2, 85, 3, 81, ''),
(2, 86, 3, 81, ''),
(2, 87, 3, 81, ''),
(2, 88, 3, 81, ''),
(2, 89, 3, 81, ''),
(2, 90, 2, 1, ''),
(2, 91, 3, 90, ''),
(2, 92, 3, 90, ''),
(2, 93, 3, 90, ''),
(2, 94, 3, 90, ''),
(2, 129, 3, 90, ''),
(2, 130, 3, 90, ''),
(2, 131, 3, 90, ''),
(2, 132, 3, 90, ''),
(2, 133, 3, 90, ''),
(2, 114, 2, 1, ''),
(2, 115, 3, 114, ''),
(2, 116, 3, 114, ''),
(2, 117, 3, 114, ''),
(2, 118, 3, 114, ''),
(2, 119, 3, 114, ''),
(2, 120, 3, 114, ''),
(2, 121, 3, 114, ''),
(2, 126, 3, 114, ''),
(7, 1, 1, 0, NULL),
(7, 2, 2, 1, NULL),
(7, 5, 3, 2, NULL),
(7, 6, 3, 2, NULL),
(7, 112, 3, 2, NULL),
(7, 113, 3, 2, NULL),
(7, 128, 3, 2, NULL),
(7, 136, 3, 2, NULL),
(7, 137, 3, 2, NULL),
(7, 138, 3, 2, NULL),
(7, 139, 3, 2, NULL),
(7, 3, 2, 1, NULL),
(7, 7, 3, 3, NULL),
(7, 45, 3, 3, NULL),
(7, 46, 3, 3, NULL),
(7, 47, 3, 3, NULL),
(7, 48, 3, 3, NULL),
(7, 49, 3, 3, NULL),
(7, 101, 3, 3, NULL),
(7, 122, 3, 3, NULL),
(7, 123, 3, 3, NULL),
(7, 124, 3, 3, NULL),
(7, 125, 3, 3, NULL),
(7, 140, 3, 3, NULL),
(7, 141, 3, 3, NULL),
(7, 142, 3, 3, NULL),
(7, 143, 3, 3, NULL),
(7, 160, 3, 3, NULL),
(7, 161, 3, 3, NULL),
(7, 4, 2, 1, NULL),
(7, 10, 3, 4, NULL),
(7, 11, 3, 4, NULL),
(7, 12, 3, 4, NULL),
(7, 13, 3, 4, NULL),
(7, 95, 3, 4, NULL),
(7, 96, 3, 4, NULL),
(7, 97, 3, 4, NULL),
(7, 98, 3, 4, NULL),
(7, 99, 3, 4, NULL),
(7, 100, 3, 4, NULL),
(7, 14, 2, 1, NULL),
(7, 8, 3, 14, NULL),
(7, 9, 3, 14, NULL),
(7, 15, 3, 14, NULL),
(7, 16, 3, 14, NULL),
(7, 17, 3, 14, NULL),
(7, 18, 3, 14, NULL),
(7, 19, 3, 14, NULL),
(7, 20, 3, 14, NULL),
(7, 21, 3, 14, NULL),
(7, 22, 3, 14, NULL),
(7, 23, 3, 14, NULL),
(7, 24, 3, 14, NULL),
(7, 25, 3, 14, NULL),
(7, 26, 2, 1, NULL),
(7, 27, 3, 26, NULL),
(7, 28, 3, 26, NULL),
(7, 29, 3, 26, NULL),
(7, 30, 3, 26, NULL),
(7, 31, 3, 26, NULL),
(7, 32, 2, 1, NULL),
(7, 33, 3, 32, NULL),
(7, 34, 3, 32, NULL),
(7, 35, 3, 32, NULL),
(7, 36, 3, 32, NULL),
(7, 37, 3, 32, NULL),
(7, 38, 3, 32, NULL),
(7, 39, 3, 32, NULL),
(7, 40, 3, 32, NULL),
(7, 41, 3, 32, NULL),
(7, 42, 3, 32, NULL),
(7, 43, 3, 32, NULL),
(7, 44, 3, 32, NULL),
(7, 50, 2, 1, NULL),
(7, 51, 3, 50, NULL),
(7, 52, 3, 50, NULL),
(7, 53, 3, 50, NULL),
(7, 54, 3, 50, NULL),
(7, 55, 3, 50, NULL),
(7, 56, 3, 50, NULL),
(7, 57, 3, 50, NULL),
(7, 58, 3, 50, NULL),
(7, 59, 3, 50, NULL),
(7, 60, 3, 50, NULL),
(7, 61, 3, 50, NULL),
(7, 62, 3, 50, NULL),
(7, 111, 3, 50, NULL),
(7, 144, 3, 50, NULL),
(7, 145, 3, 50, NULL),
(7, 146, 3, 50, NULL),
(7, 147, 3, 50, NULL),
(7, 150, 3, 50, NULL),
(7, 148, 3, 50, NULL),
(7, 149, 3, 50, NULL),
(7, 151, 3, 50, NULL),
(7, 152, 3, 50, NULL),
(7, 63, 2, 1, NULL),
(7, 64, 3, 63, NULL),
(7, 65, 3, 63, NULL),
(7, 66, 3, 63, NULL),
(7, 67, 3, 63, NULL),
(7, 68, 3, 63, NULL),
(7, 102, 3, 63, NULL),
(7, 103, 3, 63, NULL),
(7, 104, 3, 63, NULL),
(7, 105, 3, 63, NULL),
(7, 106, 3, 63, NULL),
(7, 107, 3, 63, NULL),
(7, 108, 3, 63, NULL),
(7, 109, 3, 63, NULL),
(7, 110, 3, 63, NULL),
(7, 127, 3, 63, NULL),
(7, 134, 3, 63, NULL),
(7, 135, 3, 63, NULL),
(7, 153, 3, 63, NULL),
(7, 154, 3, 63, NULL),
(7, 155, 3, 63, NULL),
(7, 156, 3, 63, NULL),
(7, 157, 3, 63, NULL),
(7, 158, 3, 63, NULL),
(7, 159, 3, 63, NULL),
(7, 162, 3, 63, NULL),
(7, 163, 3, 63, NULL),
(7, 164, 3, 63, NULL),
(7, 165, 3, 63, NULL),
(7, 69, 2, 1, NULL),
(7, 70, 3, 69, NULL),
(7, 71, 3, 69, NULL),
(7, 72, 3, 69, NULL),
(7, 73, 3, 69, NULL),
(7, 74, 3, 69, NULL),
(7, 75, 3, 69, NULL),
(7, 76, 2, 1, NULL),
(7, 77, 3, 76, NULL),
(7, 78, 3, 76, NULL),
(7, 79, 3, 76, NULL),
(7, 80, 3, 76, NULL),
(7, 81, 2, 1, NULL),
(7, 82, 3, 81, NULL),
(7, 83, 3, 81, NULL),
(7, 84, 3, 81, NULL),
(7, 85, 3, 81, NULL),
(7, 86, 3, 81, NULL),
(7, 87, 3, 81, NULL),
(7, 88, 3, 81, NULL),
(7, 89, 3, 81, NULL),
(7, 90, 2, 1, NULL),
(7, 91, 3, 90, NULL),
(7, 92, 3, 90, NULL),
(7, 93, 3, 90, NULL),
(7, 94, 3, 90, NULL),
(7, 129, 3, 90, NULL),
(7, 130, 3, 90, NULL),
(7, 131, 3, 90, NULL),
(7, 132, 3, 90, NULL),
(7, 133, 3, 90, NULL),
(7, 114, 2, 1, NULL),
(7, 115, 3, 114, NULL),
(7, 116, 3, 114, NULL),
(7, 117, 3, 114, NULL),
(7, 118, 3, 114, NULL),
(7, 119, 3, 114, NULL),
(7, 120, 3, 114, NULL),
(7, 121, 3, 114, NULL),
(7, 126, 3, 114, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `on_admin`
--

CREATE TABLE `on_admin` (
  `aid` int(11) NOT NULL,
  `nickname` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL COMMENT '登录账号',
  `avatar` varchar(100) NOT NULL COMMENT '用户头像',
  `pwd` char(32) DEFAULT NULL COMMENT '登录密码',
  `status` int(11) DEFAULT '1' COMMENT '账号状态',
  `remark` varchar(255) DEFAULT '' COMMENT '备注信息',
  `find_code` char(5) DEFAULT NULL COMMENT '找回账号验证码',
  `time` int(10) DEFAULT NULL COMMENT '开通时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='网站后台管理员表';

-- --------------------------------------------------------

--
-- 表的结构 `on_advertising`
--

CREATE TABLE `on_advertising` (
  `id` int(11) NOT NULL,
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
  `adv_end_time` int(11) DEFAULT '0' COMMENT '广告结束时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='广告表';

--
-- 转存表中的数据 `on_advertising`
--

INSERT INTO `on_advertising` (`id`, `pid`, `name`, `code`, `bkcolor`, `type`, `status`, `url`, `click_count`, `desc`, `sort`, `adv_start_time`, `adv_end_time`) VALUES
(1, 1, 'LOGO（大屏显示）', 'Advshow/20171021/59eb145dcafbc.png', '', 0, 0, '', 0, '本图片在电脑版屏幕上会显示。', 0, 0, 0),
(2, 2, 'LOGO（小屏显示）', 'Advshow/20171021/59eb16122a541.jpg', '', 0, 0, 'http://www.oncoo.net', 0, '小屏幕访问网站显示该图片', 0, 0, 0),
(3, 3, '首页-BANNER列表图片（1）', 'Advshow/20171021/59eb1d6a06143.jpg', '#281736', 0, 0, '', 0, '', 0, 0, 0),
(4, 3, '首页-BANNER列表图片（2）', 'Advshow/20171021/59eb1dc1d423b.jpg', '#010101', 0, 0, '', 0, '', 0, 0, 0),
(5, 3, '首页-BANNER列表图片（3）', 'Advshow/20171021/59eb1de28a609.jpg', '#ffffff', 0, 0, '', 0, '', 0, 0, 0),
(7, 4, '头条底部广告', 'Advshow/20171021/59eb256ddbca3.jpg', '', 0, 0, '', 0, '', 0, 0, 0),
(8, 5, '首页-拍卖会标题处（PNG）', 'Advshow/20171022/59ec511ef3fff.jpg', '', 0, 0, '', 0, '', 0, 0, 0),
(9, 6, '导航-全部分类-为你推荐', 'Advshow/20171022/59ec56e244687.jpg', '', 0, 0, '', 0, '', 0, 0, 0),
(10, 7, '注册登陆-BANNER', 'Advshow/20171024/59eefa4516122.jpg', '#980000', 0, 0, '', 0, '', 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `on_advertising_position`
--

CREATE TABLE `on_advertising_position` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `tagname` varchar(30) NOT NULL COMMENT '广告标示',
  `name` varchar(60) NOT NULL COMMENT '广告名称',
  `width` smallint(5) UNSIGNED NOT NULL DEFAULT '0' COMMENT '宽度',
  `height` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '高度'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='广告位';

--
-- 转存表中的数据 `on_advertising_position`
--

INSERT INTO `on_advertising_position` (`id`, `tagname`, `name`, `width`, `height`) VALUES
(1, 'logoA', 'LOGO（大屏显示）', 200, 90),
(2, 'logoB', 'LOGO（小屏显示）', 159, 78),
(3, 'banner', '首页-BANNER列表图片', 1010, 455),
(4, 'headlines', '首页-头条-底部', 220, 80),
(5, 'mittingtitle', '首页-拍卖会标题处', 120, 120),
(6, 'recommend', '导航-全部分类-为你推荐', 750, 270),
(7, 'lrbanner', '登陆注册-BANNER', 1000, 500);

-- --------------------------------------------------------

--
-- 表的结构 `on_attention`
--

CREATE TABLE `on_attention` (
  `gid` int(11) NOT NULL COMMENT '商品id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `rela` varchar(5) NOT NULL COMMENT '关注类型p-u：拍品关注g-u：一口价关注'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户关注商品表';

-- --------------------------------------------------------

--
-- 表的结构 `on_attention_seller`
--

CREATE TABLE `on_attention_seller` (
  `uid` int(11) NOT NULL COMMENT '用户id',
  `sellerid` int(11) NOT NULL COMMENT '卖家id',
  `time` int(10) NOT NULL COMMENT '关注时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户关注卖家';

-- --------------------------------------------------------

--
-- 表的结构 `on_auction`
--

CREATE TABLE `on_auction` (
  `pid` int(11) NOT NULL COMMENT '拍卖id',
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
  `aid` int(11) NOT NULL COMMENT '发布人'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='拍卖表';

-- --------------------------------------------------------

--
-- 表的结构 `on_auction_agency`
--

CREATE TABLE `on_auction_agency` (
  `pid` int(11) NOT NULL COMMENT '拍卖id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `price` int(11) NOT NULL COMMENT '目标价',
  `time` int(10) NOT NULL COMMENT '设置时间',
  `status` tinyint(1) NOT NULL COMMENT '代理出价状态0：执行中无状态；1：达到目标价；2：被超越；3已关闭'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='代理出价表';

-- --------------------------------------------------------

--
-- 表的结构 `on_auction_pledge`
--

CREATE TABLE `on_auction_pledge` (
  `uid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `pledge` decimal(10,0) NOT NULL,
  `time` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='拍卖冻结用户保证金记录';

-- --------------------------------------------------------

--
-- 表的结构 `on_auction_record`
--

CREATE TABLE `on_auction_record` (
  `pid` int(11) NOT NULL COMMENT '拍品id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `time` int(10) NOT NULL COMMENT '出价时间',
  `money` decimal(10,2) NOT NULL COMMENT '出价金额',
  `bided` decimal(10,2) NOT NULL COMMENT '出价后',
  `type` varchar(10) NOT NULL COMMENT '出价方式'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='拍卖出价记录';

-- --------------------------------------------------------

--
-- 表的结构 `on_auction_repeat`
--

CREATE TABLE `on_auction_repeat` (
  `id` int(11) NOT NULL COMMENT '主键',
  `type` tinyint(1) NOT NULL COMMENT '类型：0[拍品]1[ 专场]2 [拍卖会]',
  `rid` int(11) NOT NULL COMMENT '对应类型的id',
  `etafter` int(11) NOT NULL COMMENT '每次间隔时间（秒）',
  `prg` int(11) NOT NULL COMMENT '共计拍卖次数',
  `now` int(11) NOT NULL COMMENT '当前拍卖次数',
  `stop` tinyint(1) NOT NULL COMMENT '停止条件0：达到次数；1：商品成交',
  `pastidstr` text NOT NULL COMMENT '记录已拍的id，用-分割',
  `status` tinyint(1) NOT NULL COMMENT '状态：0：执行中；1：已停止',
  `time` int(10) NOT NULL COMMENT '添加循环时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='拍卖自动上架';

-- --------------------------------------------------------

--
-- 表的结构 `on_blacklist`
--

CREATE TABLE `on_blacklist` (
  `uid` int(11) NOT NULL COMMENT '所属用户uid',
  `xid` int(11) NOT NULL COMMENT '拉黑用户uid',
  `time` int(10) NOT NULL COMMENT '拉黑时间',
  `selbuy` varchar(5) NOT NULL COMMENT '拉黑角色（buy：卖家；sel：卖家）'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='黑名单';

-- --------------------------------------------------------

--
-- 表的结构 `on_category`
--

CREATE TABLE `on_category` (
  `cid` int(5) NOT NULL,
  `pid` int(5) DEFAULT NULL COMMENT 'parentCategory上级分类',
  `name` varchar(20) DEFAULT NULL COMMENT '分类名称',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='新闻分类表' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `on_category`
--

INSERT INTO `on_category` (`cid`, `pid`, `name`, `sort`) VALUES
(1, 0, '帮助中心', 4),
(2, 0, '本站头条', 3),
(9, 1, '配送方式', 0),
(4, 2, '公告', 5),
(5, 1, '关于我们', 4),
(3, 0, '每日新鲜事', 2),
(10, 2, '专场', 1),
(11, 2, '拍卖会', 1),
(13, 2, '拍卖物品', 4),
(14, 2, '拍卖车', 1),
(16, 9, '测', 2);

-- --------------------------------------------------------

--
-- 表的结构 `on_consultation`
--

CREATE TABLE `on_consultation` (
  `id` int(11) NOT NULL COMMENT '意见反馈id',
  `uid` int(11) NOT NULL COMMENT '反馈用户uid',
  `content` text NOT NULL COMMENT '反馈内容',
  `time` int(10) NOT NULL COMMENT '反馈时间',
  `reply` text NOT NULL COMMENT '回复内容',
  `rtime` int(10) NOT NULL COMMENT '回复时间',
  `aid` int(11) NOT NULL COMMENT '回复管理员aid',
  `status` tinyint(1) NOT NULL COMMENT '是否已读'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='意见反馈';

-- --------------------------------------------------------

--
-- 表的结构 `on_deliver_address`
--

CREATE TABLE `on_deliver_address` (
  `adid` int(11) NOT NULL COMMENT '地址id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `prov` varchar(20) NOT NULL COMMENT '省',
  `city` varchar(20) NOT NULL COMMENT '市',
  `district` varchar(20) NOT NULL COMMENT '区、县',
  `address` varchar(50) NOT NULL COMMENT '详细地址',
  `postalcode` int(10) NOT NULL COMMENT '邮政编码',
  `truename` varchar(8) NOT NULL COMMENT '收件人姓名',
  `mobile` varchar(11) NOT NULL COMMENT '手机号',
  `phone` varchar(30) NOT NULL COMMENT '电话号码',
  `default` tinyint(1) NOT NULL COMMENT '是否默认：1是，0否'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='地址表';

-- --------------------------------------------------------

--
-- 表的结构 `on_feedback`
--

CREATE TABLE `on_feedback` (
  `id` int(11) NOT NULL,
  `name` varchar(10) DEFAULT NULL COMMENT '推广名',
  `count` int(11) DEFAULT '0' COMMENT '统计'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='推广类型表';

-- --------------------------------------------------------

--
-- 表的结构 `on_goods`
--

CREATE TABLE `on_goods` (
  `id` int(11) NOT NULL,
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
  `aid` int(11) DEFAULT NULL COMMENT '发布者'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品表';

-- --------------------------------------------------------

--
-- 表的结构 `on_goods_category`
--

CREATE TABLE `on_goods_category` (
  `cid` int(5) NOT NULL,
  `pid` int(5) DEFAULT NULL COMMENT 'parentCategory上级分类',
  `name` varchar(20) DEFAULT NULL COMMENT '分类名称',
  `remark` varchar(255) NOT NULL COMMENT '备注',
  `ico` varchar(255) DEFAULT NULL COMMENT '分类图标',
  `path` text NOT NULL COMMENT '路径',
  `hot` tinyint(1) NOT NULL COMMENT '是否推荐',
  `modelno` int(1) NOT NULL COMMENT '显示效果',
  `sort` int(5) NOT NULL COMMENT '排序'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品分类表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `on_goods_category_extend`
--

CREATE TABLE `on_goods_category_extend` (
  `cid` int(5) DEFAULT NULL COMMENT '商品分类id',
  `eid` int(5) DEFAULT NULL COMMENT '扩展字段id'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品分类扩展字段关联表';

-- --------------------------------------------------------

--
-- 表的结构 `on_goods_category_filtrate`
--

CREATE TABLE `on_goods_category_filtrate` (
  `cid` int(5) DEFAULT NULL COMMENT '商品分类id',
  `fid` int(5) DEFAULT NULL COMMENT '商品属性id'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品分类与商品属性关联表';

-- --------------------------------------------------------

--
-- 表的结构 `on_goods_evaluate`
--

CREATE TABLE `on_goods_evaluate` (
  `id` int(11) NOT NULL COMMENT '评价id',
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
  `rtime` int(10) NOT NULL COMMENT '回评时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品评价表';

-- --------------------------------------------------------

--
-- 表的结构 `on_goods_extend`
--

CREATE TABLE `on_goods_extend` (
  `eid` int(11) NOT NULL,
  `name` varchar(16) DEFAULT NULL COMMENT '字段名',
  `default` mediumtext COMMENT '字段默认值',
  `rank` int(2) NOT NULL DEFAULT '0' COMMENT '字段排序',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否启用0：不启用，1启用'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品扩展字段';

-- --------------------------------------------------------

--
-- 表的结构 `on_goods_fields`
--

CREATE TABLE `on_goods_fields` (
  `eid` int(11) NOT NULL,
  `default` text COMMENT '字段默认值',
  `gid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品对应字段';

-- --------------------------------------------------------

--
-- 表的结构 `on_goods_filtrate`
--

CREATE TABLE `on_goods_filtrate` (
  `fid` int(5) NOT NULL,
  `pid` int(5) DEFAULT NULL COMMENT '上级条件',
  `name` varchar(20) DEFAULT NULL COMMENT '商品属性名称',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品属性表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `on_goods_order`
--

CREATE TABLE `on_goods_order` (
  `id` int(11) NOT NULL,
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
  `remark` varchar(255) NOT NULL COMMENT '备注'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单表';

-- --------------------------------------------------------

--
-- 表的结构 `on_goods_order_return`
--

CREATE TABLE `on_goods_order_return` (
  `id` int(11) NOT NULL,
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
  `deftime9st` tinyint(1) NOT NULL COMMENT '默认卖家收货时间处理状态，0：未执行 1已执行  (【卖家默认收货】 进入正常  流程买家评价-卖家评价)'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='退货订单';

-- --------------------------------------------------------

--
-- 表的结构 `on_goods_user`
--

CREATE TABLE `on_goods_user` (
  `uid` int(11) NOT NULL COMMENT '用户id',
  `gid` int(11) NOT NULL COMMENT '商品id',
  `limsum` decimal(10,2) NOT NULL COMMENT '冻结信用额度',
  `pledge` decimal(10,2) NOT NULL COMMENT '冻结的保证金',
  `g-u` varchar(3) NOT NULL COMMENT 'p-u：拍品-用户g-u：商品-用户,s-u：专场-用户',
  `time` int(10) NOT NULL COMMENT '时间',
  `rtime` int(10) NOT NULL COMMENT '保证金处理时间',
  `status` tinyint(1) NOT NULL COMMENT '处理状态，0：未处理，1：已处理'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户对商品缴纳保证金';

-- --------------------------------------------------------

--
-- 表的结构 `on_link`
--

CREATE TABLE `on_link` (
  `id` int(11) NOT NULL,
  `name` varchar(10) NOT NULL COMMENT '名称',
  `url` varchar(255) NOT NULL COMMENT '链接',
  `ico` varchar(255) NOT NULL COMMENT '图标',
  `rec` tinyint(1) NOT NULL COMMENT '图标显示0：不显示1：显示',
  `sort` int(11) NOT NULL COMMENT '排序'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='友情链接表';

-- --------------------------------------------------------

--
-- 表的结构 `on_meeting_auction`
--

CREATE TABLE `on_meeting_auction` (
  `mid` int(11) NOT NULL COMMENT '拍卖会id',
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
  `aid` int(11) NOT NULL COMMENT '发布者'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='拍卖会表';

-- --------------------------------------------------------

--
-- 表的结构 `on_member`
--

CREATE TABLE `on_member` (
  `uid` int(11) NOT NULL,
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
  `alerttype` varchar(30) NOT NULL COMMENT '提醒方式（email，mobile，weixin）'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='网站前台会员表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `on_member_evaluate`
--

CREATE TABLE `on_member_evaluate` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '评价所属人',
  `sellerid` int(11) NOT NULL COMMENT '卖家id',
  `pid` int(11) NOT NULL COMMENT '拍品id',
  `score` tinyint(1) NOT NULL COMMENT '得分：0差评；1中评；2好评',
  `evaluate` varchar(255) NOT NULL COMMENT '评价内容',
  `time` int(10) NOT NULL COMMENT '评价时间',
  `order_no` varchar(100) NOT NULL COMMENT '对应订单'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='卖家对用户的评价';

-- --------------------------------------------------------

--
-- 表的结构 `on_member_footprint`
--

CREATE TABLE `on_member_footprint` (
  `uid` int(11) NOT NULL COMMENT '用户id',
  `pidstr` text NOT NULL COMMENT '看过的拍品pid集合'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户足迹';

-- --------------------------------------------------------

--
-- 表的结构 `on_member_limsum_bill`
--

CREATE TABLE `on_member_limsum_bill` (
  `id` int(11) NOT NULL,
  `order_no` varchar(100) NOT NULL COMMENT '单号',
  `uid` int(11) NOT NULL,
  `changetype` varchar(20) NOT NULL COMMENT '竞拍冻结bid_freeze 竞拍解冻bid_unfreeze 后台充值admin_deposit 管理员扣除 admin_deduct 支付充值pay_deposit 支付扣除pay_deduct  提现extract',
  `time` int(10) DEFAULT NULL COMMENT '操作时间',
  `annotation` text COMMENT '记录操作说明',
  `income` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '账户收入',
  `expend` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '账户支出',
  `usable` decimal(10,2) NOT NULL COMMENT '可用余额',
  `balance` decimal(10,2) NOT NULL COMMENT '当前余额'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户信用额度账单';

-- --------------------------------------------------------

--
-- 表的结构 `on_member_pledge_bill`
--

CREATE TABLE `on_member_pledge_bill` (
  `id` int(11) NOT NULL,
  `order_no` varchar(100) NOT NULL COMMENT '订单号',
  `uid` int(11) NOT NULL,
  `changetype` varchar(20) NOT NULL COMMENT '竞拍冻结bid_freeze 竞拍解冻bid_unfreeze 后台充值admin_deposit 管理员扣除 admin_deduct 支付充值pay_deposit 支付扣除pay_deduct  提现extract',
  `time` int(10) DEFAULT NULL COMMENT '操作时间',
  `annotation` text COMMENT '记录操作说明',
  `income` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '账户收入',
  `expend` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '账户支出',
  `usable` decimal(10,2) NOT NULL COMMENT '可用余额',
  `balance` decimal(10,2) NOT NULL COMMENT '当前余额'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户保证金账单';

-- --------------------------------------------------------

--
-- 表的结构 `on_member_pledge_take`
--

CREATE TABLE `on_member_pledge_take` (
  `tid` int(11) NOT NULL,
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
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0等待退款；1已退款；2驳回提现'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户提现账单';

-- --------------------------------------------------------

--
-- 表的结构 `on_member_weixin`
--

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
  `weitime` int(10) NOT NULL COMMENT '微信登陆时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微信用户表';

-- --------------------------------------------------------

--
-- 表的结构 `on_mysms`
--

CREATE TABLE `on_mysms` (
  `sid` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '收件人id',
  `rsid` int(11) NOT NULL COMMENT '回复消息的sid',
  `sendid` int(11) NOT NULL COMMENT '发送人id',
  `aid` int(11) NOT NULL COMMENT '管理员id',
  `pid` int(11) NOT NULL COMMENT '对应拍品的pid',
  `type` varchar(20) NOT NULL COMMENT '消息类型',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '信息状态0：未读；1：已读；',
  `delmark` tinyint(1) NOT NULL COMMENT '删除标记 1：设置删除',
  `content` text NOT NULL COMMENT '信息内容',
  `time` int(10) NOT NULL COMMENT '接收时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户消息提醒';

-- --------------------------------------------------------

--
-- 表的结构 `on_navigation`
--

CREATE TABLE `on_navigation` (
  `lid` int(5) NOT NULL,
  `pid` int(5) DEFAULT NULL COMMENT '上级导航',
  `name` varchar(20) DEFAULT NULL COMMENT '导航名称',
  `target` varchar(10) NOT NULL DEFAULT '_self' COMMENT '_blank:新窗口；_self:当前窗口',
  `url` varchar(255) NOT NULL DEFAULT 'javascript:void(0);' COMMENT '链接地址',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='网站导航链接表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `on_news`
--

CREATE TABLE `on_news` (
  `id` mediumint(8) NOT NULL,
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
  `aid` smallint(3) DEFAULT NULL COMMENT '发布者UID'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='新闻表';

-- --------------------------------------------------------

--
-- 表的结构 `on_node`
--

CREATE TABLE `on_node` (
  `id` smallint(6) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `remark` varchar(255) DEFAULT NULL,
  `sort` smallint(6) UNSIGNED DEFAULT NULL,
  `pid` smallint(6) UNSIGNED NOT NULL,
  `level` tinyint(1) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='权限节点表';

--
-- 转存表中的数据 `on_node`
--

INSERT INTO `on_node` (`id`, `name`, `title`, `status`, `remark`, `sort`, `pid`, `level`) VALUES
(1, 'Admin', '后台管理', 1, '网站后台管理项目', 0, 0, 1),
(2, 'Index', '管理首页', 1, '', 15, 1, 2),
(3, 'Member', '注册用户管理', 1, '', 14, 1, 2),
(4, 'Webinfo', '系统管理', 1, '', 3, 1, 2),
(5, 'index', '默认页', 1, '', 6, 2, 3),
(6, 'myinfo', '修改密码', 1, '', 5, 2, 3),
(7, 'index', '用户管理', 1, '', 17, 3, 3),
(8, 'index', '管理员列表', 1, '', 8, 14, 3),
(9, 'addAdmin', '添加管理员', 1, '', 9, 14, 3),
(10, 'index', '站点信息', 1, '', 10, 4, 3),
(11, 'setEmailConfig', '邮箱配置', 1, '', 12, 4, 3),
(12, 'testEmailConfig', '发送测试邮件', 1, '', 0, 4, 3),
(13, 'setSafeConfig', '系统安全设置', 1, '', 0, 4, 3),
(14, 'Access', '权限管理', 1, '权限管理，为系统后台管理员设置不同的权限', 4, 1, 2),
(15, 'nodeList', '查看节点', 1, '节点列表信息', 0, 14, 3),
(16, 'roleList', '角色列表查看', 1, '角色列表查看', 0, 14, 3),
(17, 'addRole', '添加角色', 1, '', 0, 14, 3),
(18, 'editRole', '编辑角色', 1, '', 0, 14, 3),
(19, 'opNodeStatus', '便捷开启禁用节点', 1, '', 0, 14, 3),
(20, 'opRoleStatus', '便捷开启禁用角色', 1, '', 0, 14, 3),
(21, 'editNode', '编辑节点', 1, '', 0, 14, 3),
(22, 'addNode', '添加节点', 1, '', 0, 14, 3),
(23, 'addAdmin', '添加管理员', 1, '', 0, 14, 3),
(24, 'editAdmin', '编辑管理员信息', 1, '', 0, 14, 3),
(25, 'changeRole', '权限分配', 1, '', 0, 14, 3),
(26, 'News', '文章管理', 1, '', 10, 1, 2),
(27, 'index', '文章列表', 1, '', 0, 26, 3),
(28, 'category', '文章分类管理', 1, '', 0, 26, 3),
(29, 'add', '发布文章', 1, '', 0, 26, 3),
(30, 'edit', '编辑文章', 1, '', 0, 26, 3),
(31, 'del', '删除信息', 0, '', 0, 26, 3),
(32, 'SysData', '数据库管理', 1, '包含数据库备份、还原、打包等', 5, 1, 2),
(33, 'index', '查看数据库表结构信息', 1, '', 0, 32, 3),
(34, 'backup', '备份数据库', 1, '', 0, 32, 3),
(35, 'restore', '查看已备份SQL文件', 1, '', 0, 32, 3),
(36, 'restoreData', '执行数据库还原操作', 1, '', 0, 32, 3),
(37, 'delSqlFiles', '删除SQL文件', 1, '', 0, 32, 3),
(38, 'sendSql', '邮件发送SQL文件', 1, '', 0, 32, 3),
(39, 'zipSql', '打包SQL文件', 1, '', 0, 32, 3),
(40, 'zipList', '查看已打包SQL文件', 1, '', 0, 32, 3),
(41, 'unzipSqlfile', '解压缩ZIP文件', 1, '', 0, 32, 3),
(42, 'delZipFiles', '删除zip压缩文件', 1, '', 0, 32, 3),
(43, 'downFile', '下载备份的SQL,ZIP文件', 1, '', 0, 32, 3),
(44, 'repair', '数据库优化修复', 1, '', 0, 32, 3),
(45, 'add', '用户（添加）', 1, '添加用户的权限', 16, 3, 3),
(46, 'feedback', '推广反馈', 1, '添加推广项的', 4, 3, 3),
(47, 'wallet', '用户（账户编辑）', 1, '编辑用户资金账户', 13, 3, 3),
(48, 'edit', '用户（编辑）', 1, '编辑用户信息', 15, 3, 3),
(49, 'del', '用户（删除）', 1, '删除用户', 14, 3, 3),
(50, 'Goods', '商品管理', 1, '商品仓库和一些商品频道、筛选、扩展的配置', 13, 1, 2),
(51, 'index', '商品管理', 1, '商品列表的显示', 37, 50, 3),
(52, 'add', '商品（添加）', 1, '添加商品', 36, 50, 3),
(53, 'category', '频道分类管理', 1, '添加频道或分类的权限', 33, 50, 3),
(54, 'filtrate', '商品属性管理', 1, '商品属性管理', 29, 50, 3),
(55, 'cate_filt', '分类与属性关联', 1, '频道、分类与筛选条件关联', 25, 50, 3),
(56, 'fields_list', '扩展字段管理', 1, '商品扩展字段列表', 22, 50, 3),
(57, 'cate_extend', '分类与扩展字段关联', 1, '频道、分类与扩展字段关联的操作', 18, 50, 3),
(58, 'del_goods', '商品（删除）', 1, '商品的删除操作', 34, 50, 3),
(59, 'delLink', '分类与属性关联（删除）', 1, '频道、分类与筛选条件关联的删除', 23, 50, 3),
(60, 'fields_add', '扩展字段（添加、编辑）', 1, '扩展字段的添加和编辑', 20, 50, 3),
(61, 'delField', '扩展字段（删除）', 1, '扩展字段的删除', 19, 50, 3),
(62, 'delExtend', '分类与扩展字段关联（删除）', 1, '频道、分类与扩展字段关联的删除', 0, 50, 3),
(63, 'Auction', '拍卖管理', 1, '拍卖管理', 12, 1, 2),
(64, 'index', '拍卖管理', 1, '拍品列表', 29, 63, 3),
(65, 'add', '拍卖（发布）', 1, '商品列表发布到拍卖的操作', 28, 63, 3),
(66, 'edit', '拍卖（编辑）', 1, '编辑拍卖', 27, 63, 3),
(67, 'set_auction', '拍卖配置', 1, '配置拍卖的一些信息', 1, 63, 3),
(68, 'del', '拍卖（删除）', 1, '删除拍卖', 26, 63, 3),
(69, 'Order', '订单管理', 1, '订单管理', 11, 1, 2),
(70, 'index', '订单列表', 1, '订单列表', 0, 69, 3),
(71, 'lose', '过期订单', 1, '过期的订单', 0, 69, 3),
(72, 'deduct', '过期订单扣除保证金操作', 1, '过期订单扣除保证金操作', 0, 69, 3),
(73, 'edit', '订单编辑', 1, '订单编辑', 0, 69, 3),
(74, 'del', '订单删除', 1, '订单删除', 0, 69, 3),
(75, 'set_order', '订单配置', 1, '订单有效期的配置', 0, 69, 3),
(76, 'Link', '友情链接', 1, '友情链接', 6, 1, 2),
(77, 'index', '列表', 1, '友情链接列表', 0, 76, 3),
(78, 'add', '添加', 1, '添加友情链接', 0, 76, 3),
(79, 'edit', '编辑', 1, '编辑友情链接', 0, 76, 3),
(80, 'del', '删除', 1, '友情链接删除', 0, 76, 3),
(81, 'Advertising', '广告管理', 1, '广告管理', 9, 1, 2),
(82, 'index', '广告列表', 1, '广告列表', 0, 81, 3),
(83, 'add_advertising', '添加广告', 1, '添加广告', 0, 81, 3),
(84, 'edit_advertising', '编辑广告', 1, '编辑广告', 0, 81, 3),
(85, 'del_advertising', '删除广告', 1, '删除广告', 0, 81, 3),
(86, 'position', '广告位列表', 1, '广告位列表', 0, 81, 3),
(87, 'add_position', '添加广告位', 1, '添加广告位', 0, 81, 3),
(88, 'edit_position', '编辑广告位', 1, '编辑广告位', 0, 81, 3),
(89, 'del_position', '删除广告位', 1, '删除广告位', 0, 81, 3),
(90, 'Payment', '支付管理', 1, '支付管理', 8, 1, 2),
(91, 'pay_gallery', '支付接口配置', 1, '支付接口配置', 0, 90, 3),
(92, 'edit', '支付接口编辑', 0, '支付接口编辑', 0, 90, 3),
(93, 'index', '支付订单列表', 1, '支付订单列表', 0, 90, 3),
(94, 'del', '支付订单删除', 1, '支付订单删除', 0, 90, 3),
(95, 'setUserAgreement', '用户协议', 0, '用户协议管理', 0, 4, 3),
(96, 'navigation', '导航链接管理', 1, '导航链接管理', 0, 4, 3),
(97, 'steWebConfig', '站点配置', 1, '配置站点的信息', 0, 4, 3),
(98, 'setNoteConfig', '短信配置', 1, '配置短信接口', 0, 4, 3),
(99, 'testNoteConfig', '发送测试短信', 1, '发送测试短信', 0, 4, 3),
(100, 'setUserAgreement', '用户协议', 1, '编辑用户协议', 0, 4, 3),
(101, 'set_member', '注册用户设置', 1, '注册用户设置', 1, 3, 3),
(102, 'special', '专场管理', 1, '专场列表查看各个状态的专场', 16, 63, 3),
(103, 'special_add', '专场（添加）', 1, '添加专场的操作', 15, 63, 3),
(104, 'special_edit', '专场（编辑）', 1, '编辑专场', 14, 63, 3),
(105, 'special_del', '专场（删除）', 1, '删除专场操作', 13, 63, 3),
(106, 'meeting', '拍卖会管理', 1, '拍卖会列表', 10, 63, 3),
(107, 'meeting_add', '拍卖会（添加）', 1, '添加拍卖会', 9, 63, 3),
(108, 'meeting_edit', '拍卖会（编辑）', 1, '编辑拍卖会', 8, 63, 3),
(109, 'meeting_del', '拍卖会（删除）', 1, '删除拍卖会', 7, 63, 3),
(110, 'special_hideshow', '专场（显示、隐藏）', 1, '专场的显示和隐藏', 12, 63, 3),
(111, 'edit', '商品（编辑）', 1, '编辑商品信息', 35, 50, 3),
(112, 'take', '提现管理', 1, '提现申请的显示', 3, 2, 3),
(113, 'cache', '缓存管理', 1, '缓存的查看和清空操作', 1, 2, 3),
(114, 'Weixin', '微信平台', 1, '微信平台管理', 7, 1, 2),
(115, 'index', '图文消息列表', 1, '图文列表显示', 0, 114, 3),
(116, 'addurl', '添加图文消息', 1, '添加图文消息', 0, 114, 3),
(117, 'editurl', '编辑图文消息', 1, '编辑图文消息', 0, 114, 3),
(118, 'weipush', '批量推送图文消息', 1, '批量推送图文消息', 0, 114, 3),
(119, 'delurl', '删除图文消息', 1, '删除图文消息', 0, 114, 3),
(120, 'weimenu', '自定义菜单编辑', 1, '自定义菜单编辑', 0, 114, 3),
(121, 'weiconfig', '微信配置', 1, '微信配置', 0, 114, 3),
(122, 'webmail', '站内信管理', 1, '站内信列表的显示', 8, 3, 3),
(123, 'sendsms', '发送站内信', 1, '给用户发送站内信', 7, 3, 3),
(124, 'setdelsms', '站内信（设置删除）', 1, '设置站内信为删除状态', 5, 3, 3),
(125, 'delsms', '站内信（删除）', 1, '彻底删除站内信', 6, 3, 3),
(126, 'sharerecord', '用户分享记录', 1, '显示微信版用户分享链接的记录', 0, 114, 3),
(127, 'showset', '查看拍卖', 1, '查看拍卖信息', 0, 63, 3),
(128, 'statistics', '资金统计', 1, '用于查看网站资金的页面', 4, 2, 3),
(129, 'rechargeable', '充值卡管理（列表）', 1, '显示充值卡列表', 0, 90, 3),
(130, 'search_rechargeable', '充值卡搜索', 1, '搜索充值卡', 0, 90, 3),
(131, 'export_rechargeable', '导出充值卡excel', 1, '导出充值卡excel', 0, 90, 3),
(132, 'add_rechargeable', '添加充值卡（制卡）', 1, '添加充值卡（制卡）', 0, 90, 3),
(133, 'del_rechargeable', '删除充值卡', 1, '删除充值卡', 0, 90, 3),
(134, 'seller_pledge', '卖家保证金管理', 1, '查看卖家保证金列表', 3, 63, 3),
(135, 'add_jurisdiction', '添加一次性缴纳保证金用户（添加会员）', 1, '', 2, 63, 3),
(136, 'rtake', '提现处理', 1, '提现的处理、驳回', 1, 2, 3),
(137, 'consultation', '意见反馈管理', 1, '意见反馈列表的显示', 0, 2, 3),
(138, 'consultation_edit', '意见反馈（回复）', 1, '意见反馈的回复', 0, 2, 3),
(139, 'consultation_del', '意见反馈（删除）', 1, '意见反馈的删除操作', 0, 2, 3),
(140, 'deliver_address', '用户（地址列表）', 1, '用户地址的显示', 10, 3, 3),
(141, 'walletbill', '账户记录', 1, '账户记录的显示', 9, 3, 3),
(142, 'feedback_add', '推广反馈（添加）', 1, '推广反馈的添加', 3, 3, 3),
(143, 'feedback_del', '推广反馈（删除）', 1, '推广反馈的删除', 2, 3, 3),
(144, 'category_add', '频道分类（添加）', 1, '频道分类的添加', 32, 50, 3),
(145, 'category_edit', '频道分类（编辑）', 1, '频道分类的编辑', 31, 50, 3),
(146, 'category_del', '频道分类（删除）', 1, '频道分类的删除', 30, 50, 3),
(147, 'filtrate_add', '商品属性（添加）', 1, '商品属性的添加', 28, 50, 3),
(150, 'cate_filt_add', '分类与属性关联（添加）', 1, '添加分类与属性的关联', 24, 50, 3),
(148, 'filtrate_edit', '商品属性（编辑）', 1, '商品属性的编辑', 27, 50, 3),
(149, 'filtrate_del', '商品属性（删除）', 1, '商品属性的删除', 26, 50, 3),
(151, 'fields_describe', '扩展字段（商品详情默认值设置）', 1, '商品详情默认值设置', 21, 50, 3),
(152, 'cate_extend_add', '分类与扩展字段关联（添加）', 1, '分类与扩展字段的关联的添加', 17, 50, 3),
(153, 'info', '拍卖（详情）', 1, '拍卖信息的查看', 25, 63, 3),
(154, 'hideshow', '拍卖（隐藏）', 1, '拍卖的隐藏', 24, 63, 3),
(155, 'recommend', '拍卖（推荐、取消推荐）', 1, '拍品的推荐和取消推荐', 22, 63, 3),
(156, 'cancelPai', '拍卖（撤拍）', 1, '拍卖的撤拍', 21, 63, 3),
(157, 'special_info', '专场（详情）', 1, '专场详情的查看', 11, 63, 3),
(158, 'meeting_info', '拍卖会（详情）', 1, '拍卖会详情的查看', 5, 63, 3),
(159, 'meeting_hideshow', '拍卖会（显示、隐藏）', 1, '拍卖会的显示和隐藏', 6, 63, 3),
(160, 'realname', '实名认证管理', 1, '用于显示查询实名认证的用户', 12, 3, 3),
(161, 'realname_edit', '实名认证编辑', 1, '实名认证的操作编辑', 11, 3, 3),
(162, 'repeat', '重复拍', 1, '重复拍的列表显示', 20, 63, 3),
(163, 'repeat_add', '重复拍（添加）', 1, '重复拍的添加', 19, 63, 3),
(164, 'repeat_edit', '重复拍（编辑）', 1, '重复拍的编辑', 18, 63, 3),
(165, 'repeat_del', '重复拍（删除）', 1, '重复拍的删除', 17, 63, 3);

-- --------------------------------------------------------

--
-- 表的结构 `on_order_break`
--

CREATE TABLE `on_order_break` (
  `order_no` varchar(100) NOT NULL COMMENT '订单号',
  `pledge` decimal(10,2) NOT NULL COMMENT '保证金',
  `limsum` decimal(10,2) NOT NULL COMMENT '信誉额度',
  `time` int(10) NOT NULL COMMENT '收入时间',
  `how` varchar(4) NOT NULL COMMENT '角色，buy：买家 sel：卖家'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单违约网站收入';

-- --------------------------------------------------------

--
-- 表的结构 `on_payorder`
--

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
  `update_time` int(11) NOT NULL COMMENT '订单更新时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='支付订单';

-- --------------------------------------------------------

--
-- 表的结构 `on_rechargeable`
--

CREATE TABLE `on_rechargeable` (
  `cardno` varchar(20) NOT NULL COMMENT '卡号',
  `pwd` char(10) NOT NULL COMMENT '密码',
  `uid` int(11) NOT NULL COMMENT '充值用户',
  `aid` int(11) NOT NULL COMMENT '管理员',
  `pledge` decimal(10,0) NOT NULL COMMENT '保证金值',
  `limsum` decimal(10,0) NOT NULL COMMENT '信誉额度值',
  `time` int(10) NOT NULL COMMENT '生成时间',
  `pasttime` int(10) NOT NULL COMMENT '过期时间',
  `status` tinyint(1) NOT NULL COMMENT '是否已使用或过期，0：未使用；1：已使用；2：已过期'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='充值卡表';

-- --------------------------------------------------------

--
-- 表的结构 `on_role`
--

CREATE TABLE `on_role` (
  `id` smallint(6) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `pid` smallint(6) DEFAULT NULL,
  `status` tinyint(1) UNSIGNED DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='权限角色表';

--
-- 转存表中的数据 `on_role`
--

INSERT INTO `on_role` (`id`, `name`, `pid`, `status`, `remark`) VALUES
(6, '审核小组', NULL, 1, ''),
(7, '审核员1', 6, 1, ''),
(8, '发布小组', 6, 0, '');

-- --------------------------------------------------------

--
-- 表的结构 `on_role_user`
--

CREATE TABLE `on_role_user` (
  `role_id` mediumint(9) UNSIGNED DEFAULT NULL,
  `user_id` char(32) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户角色表';

--
-- 转存表中的数据 `on_role_user`
--

INSERT INTO `on_role_user` (`role_id`, `user_id`) VALUES
(7, '13');

-- --------------------------------------------------------

--
-- 表的结构 `on_scheduled`
--

CREATE TABLE `on_scheduled` (
  `pid` int(11) NOT NULL COMMENT '拍品pid',
  `uid` int(11) NOT NULL COMMENT '用户uid',
  `stype` varchar(5) NOT NULL COMMENT '提醒类型（fut:开拍提醒，ing：结束提醒）',
  `time` int(10) NOT NULL COMMENT '提醒时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='拍品开拍结束提醒表';

-- --------------------------------------------------------

--
-- 表的结构 `on_seller_pledge`
--

CREATE TABLE `on_seller_pledge` (
  `id` int(11) NOT NULL,
  `sellerid` int(11) NOT NULL COMMENT '商户UID',
  `pid` int(11) NOT NULL COMMENT '拍品id',
  `type` varchar(15) NOT NULL COMMENT '[seller_pledge_disposable]一次性缴纳；[seller_pledge_every]每件缴纳；[seller_pledge_proportion]按照起拍比例缴纳',
  `pledge` decimal(10,2) NOT NULL COMMENT '保证金',
  `limsum` decimal(10,2) NOT NULL COMMENT '信誉额度',
  `time` int(10) NOT NULL COMMENT '缴纳时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '记录是否有效，1有效；0无效'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商家保证金表';

-- --------------------------------------------------------

--
-- 表的结构 `on_share`
--

CREATE TABLE `on_share` (
  `id` int(11) NOT NULL COMMENT '记录id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `terrace` varchar(20) NOT NULL COMMENT '分享平台',
  `title` varchar(255) NOT NULL COMMENT '分享链接名称',
  `link` varchar(255) NOT NULL COMMENT '分享链接',
  `limsum` decimal(10,2) NOT NULL COMMENT '奖励信誉额度',
  `time` int(10) NOT NULL COMMENT '分享时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分享记录表';

-- --------------------------------------------------------

--
-- 表的结构 `on_special_auction`
--

CREATE TABLE `on_special_auction` (
  `sid` int(11) NOT NULL COMMENT '专场id',
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
  `aid` int(11) NOT NULL COMMENT '发布者'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='专场表';

-- --------------------------------------------------------

--
-- 表的结构 `on_weiurl`
--

CREATE TABLE `on_weiurl` (
  `id` int(11) NOT NULL,
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
  `status` tinyint(1) NOT NULL COMMENT '链接状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微信图文';

--
-- 转储表的索引
--

--
-- 表的索引 `on_access`
--
ALTER TABLE `on_access`
  ADD KEY `groupId` (`role_id`),
  ADD KEY `nodeId` (`node_id`);

--
-- 表的索引 `on_admin`
--
ALTER TABLE `on_admin`
  ADD PRIMARY KEY (`aid`);

--
-- 表的索引 `on_advertising`
--
ALTER TABLE `on_advertising`
  ADD PRIMARY KEY (`id`),
  ADD KEY `position_id` (`pid`),
  ADD KEY `inx_adv_001` (`status`,`pid`);

--
-- 表的索引 `on_advertising_position`
--
ALTER TABLE `on_advertising_position`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `on_auction`
--
ALTER TABLE `on_auction`
  ADD PRIMARY KEY (`pid`),
  ADD KEY `msort` (`msort`),
  ADD KEY `gid` (`gid`),
  ADD KEY `sid` (`sid`),
  ADD KEY `mid` (`mid`),
  ADD KEY `type` (`type`),
  ADD KEY `pattern` (`pattern`),
  ADD KEY `status` (`status`),
  ADD KEY `onset` (`onset`),
  ADD KEY `starttime` (`starttime`),
  ADD KEY `endtime` (`endtime`),
  ADD KEY `endstatus` (`endstatus`);

--
-- 表的索引 `on_auction_agency`
--
ALTER TABLE `on_auction_agency`
  ADD KEY `pid` (`pid`),
  ADD KEY `uid` (`uid`),
  ADD KEY `status` (`status`),
  ADD KEY `price` (`price`);

--
-- 表的索引 `on_auction_record`
--
ALTER TABLE `on_auction_record`
  ADD KEY `uid` (`uid`),
  ADD KEY `pid` (`pid`);

--
-- 表的索引 `on_auction_repeat`
--
ALTER TABLE `on_auction_repeat`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `on_blacklist`
--
ALTER TABLE `on_blacklist`
  ADD KEY `uid` (`uid`),
  ADD KEY `xid` (`xid`),
  ADD KEY `time` (`time`),
  ADD KEY `selbuy` (`selbuy`);

--
-- 表的索引 `on_category`
--
ALTER TABLE `on_category`
  ADD PRIMARY KEY (`cid`);

--
-- 表的索引 `on_consultation`
--
ALTER TABLE `on_consultation`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `on_deliver_address`
--
ALTER TABLE `on_deliver_address`
  ADD PRIMARY KEY (`adid`);

--
-- 表的索引 `on_feedback`
--
ALTER TABLE `on_feedback`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `on_goods`
--
ALTER TABLE `on_goods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- 表的索引 `on_goods_category`
--
ALTER TABLE `on_goods_category`
  ADD PRIMARY KEY (`cid`),
  ADD KEY `pid` (`pid`);

--
-- 表的索引 `on_goods_category_extend`
--
ALTER TABLE `on_goods_category_extend`
  ADD KEY `cid` (`cid`),
  ADD KEY `eid` (`eid`);

--
-- 表的索引 `on_goods_category_filtrate`
--
ALTER TABLE `on_goods_category_filtrate`
  ADD KEY `cid` (`cid`),
  ADD KEY `fid` (`fid`);

--
-- 表的索引 `on_goods_evaluate`
--
ALTER TABLE `on_goods_evaluate`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `on_goods_extend`
--
ALTER TABLE `on_goods_extend`
  ADD PRIMARY KEY (`eid`),
  ADD KEY `rank` (`rank`),
  ADD KEY `status` (`status`);

--
-- 表的索引 `on_goods_fields`
--
ALTER TABLE `on_goods_fields`
  ADD KEY `eid` (`eid`),
  ADD KEY `gid` (`gid`);

--
-- 表的索引 `on_goods_filtrate`
--
ALTER TABLE `on_goods_filtrate`
  ADD PRIMARY KEY (`fid`),
  ADD KEY `pid` (`pid`);

--
-- 表的索引 `on_goods_order`
--
ALTER TABLE `on_goods_order`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_no` (`order_no`);

--
-- 表的索引 `on_goods_order_return`
--
ALTER TABLE `on_goods_order_return`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_no` (`order_no`);

--
-- 表的索引 `on_link`
--
ALTER TABLE `on_link`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `on_meeting_auction`
--
ALTER TABLE `on_meeting_auction`
  ADD PRIMARY KEY (`mid`),
  ADD KEY `sid` (`mid`);

--
-- 表的索引 `on_member`
--
ALTER TABLE `on_member`
  ADD PRIMARY KEY (`uid`),
  ADD KEY `account` (`account`),
  ADD KEY `email` (`email`),
  ADD KEY `account_2` (`account`),
  ADD KEY `status` (`status`),
  ADD KEY `sourceuid` (`sourceuid`);

--
-- 表的索引 `on_member_evaluate`
--
ALTER TABLE `on_member_evaluate`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `on_member_limsum_bill`
--
ALTER TABLE `on_member_limsum_bill`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_no` (`order_no`),
  ADD KEY `uid` (`uid`);

--
-- 表的索引 `on_member_pledge_bill`
--
ALTER TABLE `on_member_pledge_bill`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_no` (`order_no`),
  ADD KEY `uid` (`uid`);

--
-- 表的索引 `on_member_pledge_take`
--
ALTER TABLE `on_member_pledge_take`
  ADD PRIMARY KEY (`tid`);

--
-- 表的索引 `on_member_weixin`
--
ALTER TABLE `on_member_weixin`
  ADD UNIQUE KEY `openid` (`openid`) USING BTREE,
  ADD UNIQUE KEY `uid` (`uid`) USING BTREE,
  ADD KEY `groupid` (`groupid`);

--
-- 表的索引 `on_mysms`
--
ALTER TABLE `on_mysms`
  ADD PRIMARY KEY (`sid`),
  ADD KEY `uid` (`uid`),
  ADD KEY `sendid` (`sendid`),
  ADD KEY `aid` (`aid`),
  ADD KEY `time` (`time`),
  ADD KEY `status` (`status`);

--
-- 表的索引 `on_navigation`
--
ALTER TABLE `on_navigation`
  ADD PRIMARY KEY (`lid`);

--
-- 表的索引 `on_news`
--
ALTER TABLE `on_news`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `on_node`
--
ALTER TABLE `on_node`
  ADD PRIMARY KEY (`id`),
  ADD KEY `level` (`level`),
  ADD KEY `pid` (`pid`),
  ADD KEY `status` (`status`),
  ADD KEY `name` (`name`);

--
-- 表的索引 `on_order_break`
--
ALTER TABLE `on_order_break`
  ADD KEY `order_no` (`order_no`);

--
-- 表的索引 `on_payorder`
--
ALTER TABLE `on_payorder`
  ADD KEY `bill_no` (`bill_no`),
  ADD KEY `order_no` (`order_no`),
  ADD KEY `status` (`status`),
  ADD KEY `create_time` (`create_time`),
  ADD KEY `update_time` (`update_time`);

--
-- 表的索引 `on_rechargeable`
--
ALTER TABLE `on_rechargeable`
  ADD UNIQUE KEY `cardno` (`cardno`),
  ADD KEY `cardno_2` (`cardno`),
  ADD KEY `status` (`status`);

--
-- 表的索引 `on_role`
--
ALTER TABLE `on_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pid` (`pid`),
  ADD KEY `status` (`status`);

--
-- 表的索引 `on_role_user`
--
ALTER TABLE `on_role_user`
  ADD KEY `group_id` (`role_id`),
  ADD KEY `user_id` (`user_id`);

--
-- 表的索引 `on_scheduled`
--
ALTER TABLE `on_scheduled`
  ADD KEY `pid` (`pid`),
  ADD KEY `uid` (`uid`),
  ADD KEY `time` (`time`);

--
-- 表的索引 `on_seller_pledge`
--
ALTER TABLE `on_seller_pledge`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`),
  ADD KEY `sellerid` (`sellerid`),
  ADD KEY `type` (`type`),
  ADD KEY `pid` (`pid`);

--
-- 表的索引 `on_share`
--
ALTER TABLE `on_share`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`);

--
-- 表的索引 `on_special_auction`
--
ALTER TABLE `on_special_auction`
  ADD PRIMARY KEY (`sid`),
  ADD KEY `sid` (`sid`);

--
-- 表的索引 `on_weiurl`
--
ALTER TABLE `on_weiurl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sellerid` (`sellerid`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `on_admin`
--
ALTER TABLE `on_admin`
  MODIFY `aid` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `on_advertising`
--
ALTER TABLE `on_advertising`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- 使用表AUTO_INCREMENT `on_advertising_position`
--
ALTER TABLE `on_advertising_position`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- 使用表AUTO_INCREMENT `on_auction`
--
ALTER TABLE `on_auction`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT COMMENT '拍卖id';

--
-- 使用表AUTO_INCREMENT `on_auction_repeat`
--
ALTER TABLE `on_auction_repeat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键';

--
-- 使用表AUTO_INCREMENT `on_category`
--
ALTER TABLE `on_category`
  MODIFY `cid` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- 使用表AUTO_INCREMENT `on_consultation`
--
ALTER TABLE `on_consultation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '意见反馈id';

--
-- 使用表AUTO_INCREMENT `on_deliver_address`
--
ALTER TABLE `on_deliver_address`
  MODIFY `adid` int(11) NOT NULL AUTO_INCREMENT COMMENT '地址id';

--
-- 使用表AUTO_INCREMENT `on_feedback`
--
ALTER TABLE `on_feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `on_goods`
--
ALTER TABLE `on_goods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `on_goods_category`
--
ALTER TABLE `on_goods_category`
  MODIFY `cid` int(5) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `on_goods_evaluate`
--
ALTER TABLE `on_goods_evaluate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '评价id';

--
-- 使用表AUTO_INCREMENT `on_goods_extend`
--
ALTER TABLE `on_goods_extend`
  MODIFY `eid` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `on_goods_filtrate`
--
ALTER TABLE `on_goods_filtrate`
  MODIFY `fid` int(5) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `on_goods_order`
--
ALTER TABLE `on_goods_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `on_goods_order_return`
--
ALTER TABLE `on_goods_order_return`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `on_link`
--
ALTER TABLE `on_link`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `on_meeting_auction`
--
ALTER TABLE `on_meeting_auction`
  MODIFY `mid` int(11) NOT NULL AUTO_INCREMENT COMMENT '拍卖会id';

--
-- 使用表AUTO_INCREMENT `on_member`
--
ALTER TABLE `on_member`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `on_member_evaluate`
--
ALTER TABLE `on_member_evaluate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `on_member_limsum_bill`
--
ALTER TABLE `on_member_limsum_bill`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `on_member_pledge_bill`
--
ALTER TABLE `on_member_pledge_bill`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `on_member_pledge_take`
--
ALTER TABLE `on_member_pledge_take`
  MODIFY `tid` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `on_mysms`
--
ALTER TABLE `on_mysms`
  MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `on_navigation`
--
ALTER TABLE `on_navigation`
  MODIFY `lid` int(5) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `on_news`
--
ALTER TABLE `on_news`
  MODIFY `id` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `on_node`
--
ALTER TABLE `on_node`
  MODIFY `id` smallint(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=166;

--
-- 使用表AUTO_INCREMENT `on_role`
--
ALTER TABLE `on_role`
  MODIFY `id` smallint(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- 使用表AUTO_INCREMENT `on_seller_pledge`
--
ALTER TABLE `on_seller_pledge`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `on_share`
--
ALTER TABLE `on_share`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '记录id';

--
-- 使用表AUTO_INCREMENT `on_special_auction`
--
ALTER TABLE `on_special_auction`
  MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT COMMENT '专场id';

--
-- 使用表AUTO_INCREMENT `on_weiurl`
--
ALTER TABLE `on_weiurl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
