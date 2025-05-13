<?php

/*
 * 通用配置文件
 * Author：leo.1772703372@qq.com）
 * Date:2013-02-01
 */
$config1 = array(
    /* 数据库设置 */
    'DB_TYPE' => 'mysql', // 数据库类型
    'SHOW_PAGE_TRACE' => FALSE, //显示页面Trace信息
    'TOKEN_ON' => true, // 是否开启令牌验证
    'TOKEN_NAME' => '__oncoo__', // 令牌验证的表单隐藏字段名称
    'TOKEN_TYPE' => 'md5', //令牌哈希验证规则 默认为MD5
    'TOKEN_RESET' => true, //令牌验证出错后是否重置令牌 默认为true
    'URL_CASE_INSENSITIVE' =>false, //URL区分大小写
    /* 开发人员相关信息 */
    'AUTHOR_INFO' => array(
        'author' => 'oncoo.net',
        'author_email' => '1772703372@qq.com',
    ),
    'DEFAULT_C_LAYER'       =>  'Controller', // 默认的控制器层名称
    'MODULE_ALLOW_LIST'     =>  array('Home','lws'), // 配置你原来的分组列表
    'URL_MODULE_MAP' => array('lws'=>'admin'), //设置模块映射  '模块映射名'=>'实际模块名'
    'DEFAULT_MODULE'        =>  'Home', // 配置你原来的默认分组
    'MODULE_DENY_LIST'   => array('Common'),
    'TAGLIB_BUILD_IN' =>'Cx,Cuit', 
    'URL_MODEL'=> '2', //URL模式
    'UPLOADS_PICPATH'=>'./Uploads/',//图片上传根路径
    
    'VAR_SESSION_ID' =>  'session_id', //sessionID的提交变量
    // 验证码过期时间
    'SEND_LOSE_TIME'=>24,
    // 拍品前缀
    'BID_PRE'=>'a',
    // 专场前缀
    'SID_PRE'=>'s',
    // 拍卖会前缀
    'MID_PRE'=>'m',
    /*
     * 以下是关于商品图片的配置
     */
    'GOODS_PICPATH'=>'Goods',//商品图片上传路径（相对于根路径下）
    
    //pre_(最初用于裁剪的，也用于备份)max_,mid_,mini_(网站使用大中小图)
    'GOODS_PIC_PREFIX' =>'pre_,max_,mid_,mini_',
    'GOODS_PIC_WIDTH' =>'1000,800,400,100',//商品图片宽度和图片前缀对应
    'GOODS_PIC_HEIGHT' =>'1000,800,400,100',//商品图片高对和图片前缀对应

    'USER_PICPATH'=>'User',//商品图片上传路径（相对于根路径下）
    'USER_PIC_PREFIX' =>'max_,mid_,mini_',
    'USER_PIC_WIDTH' =>'215,100,50',//商品图片宽度和图片前缀对应
    'USER_PIC_HEIGHT' =>'215,100,50',//商品图片高对和图片前缀对应
    /*
     * 以下是关于微信图文图片上传配置
     */
    'WEI_PICPATH'=>'Weixin',//文章图标上传路径（相对于根路径下）
    'WEI_TOP_WIDTH'=>'360',//文章图标宽度
    'WEI_TOP_HEIGHT'=>'200',//文章图标高度
    'WEI_LIST_WIDTH'=>'200',//文章图标宽度
    'WEI_LIST_HEIGHT'=>'200',//文章图标高度

    // 评价晒图和退款凭证上传路径
    'EVIDENCE_PICPATH' => 'Evidence',
    'EVIDENCE_MAX_WIDTH' =>'1000',//凭证图片最大宽度
    'EVIDENCE_MAX_HEIGHT' =>'800',//凭证图片最大高度

    // 身份证图片上传
    'IDCARD_PICPATH' => 'Idcard',
    'IDCARD_MAX_WIDTH' =>'1000',//凭证图片最大宽度
    'IDCARD_MAX_HEIGHT' =>'800',//凭证图片最大高度

    /*
     * 以下是关于专场图片上传配置
     */
    'SPECIAL_PICPATH'=>'Special',//专场图片上传路径（相对于根路径下）
    'SPECIAL_ICO_WIDTH'=>'520',//专场列表图片宽度
    'SPECIAL_ICO_HEIGHT'=>'220',//专场列表图片高度
    'SPECIAL_BANNER_WIDTH'=>'2000',//专场banner图片宽度
    'SPECIAL_BANNER_HEIGHT'=>'300',//专场banner图片高度
    /*
     * 以下是关于拍卖会图片上传配置
     */
    'MEETING_PICPATH'=>'Meeting',//拍卖会图片上传路径（相对于根路径下）
    'MEETING_ICO_WIDTH'=>'700',//拍卖会列表图片宽度
    'MEETING_ICO_HEIGHT'=>'296',//拍卖会列表图片高度
    'MEETING_BANNER_WIDTH'=>'2000',//拍卖会banner图片宽度
    'MEETING_BANNER_HEIGHT'=>'300',//拍卖会banner图片高度
    /*
     * 以下是文件上传路径设置
     */
    'FILE_PICPATH'=>'File',

    'ARTICLE_PICPATH'=>'Article',//文章内图片上传路径（相对于根路径下）
    'ART_MAX_WIDTH'=>'1000',//广告图片最大宽度


    'DATA_CACHE_TYPE' => 'Memcache',//Memcache缓存
    'MEMCACHE_HOST' => '127.0.0.1',//memcache服务器地址和端口，这里为本机。
    'MEMCACHE_PORT' => '11211',
    // 'DATA_CACHE_TYPE' => 'File',文件缓存
    // 'DATA_CACHE_PREFIX'     =>  'on_',     // 缓存前缀
    // 'DATA_CACHE_TIME' => '20',  //过期的秒数。
    // 'DATA_CACHE_SUBDIR'=>true,
    // 'DATA_PATH_LEVEL'=>2,
    // 命名空间自动加载
    'AUTOLOAD_NAMESPACE'    =>  array(
        'Lib'   =>  APP_PATH.'Lib'
    ),
    'LOG_LEVEL'  =>'EMERG,ALERT,CRIT,ERR',
    'TMPL_ACTION_ERROR'     => './Public/error.html', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'   => './Public/error.html', // 默认成功跳转对应的模板文件
    'ERROR_PAGE' =>'./Public/404/404.html',
    /* 模板相关配置 */
    'TMPL_PARSE_STRING' => array(
        '__STATIC__' => __ROOT__ . '/Public',
        '__IMG__'    => __ROOT__ . '/Public/Admin/Img',
        '__CSS__'    => __ROOT__ . '/Public/Admin/Css',
        '__JS__'     => __ROOT__ . '/Public/Admin/Js',
        '--PUBLIC--'=>__ROOT__ . '/Public',
        '__WEBSOCKET__'=>__ROOT__ . '/Public/WebSocketMain'
    ),
   
);
$config2 = APP_PATH . "Common/Conf/systemConfig.php";
$config2 = file_exists($config2) ? include "$config2" : array();

$payment = APP_PATH . "Common/Conf/payment.php";
$payment = file_exists($payment) ? include "$payment" : array();

$setExtend = APP_PATH . "Common/Conf/setExtend.php";
$setExtend = file_exists($setExtend) ? include "$setExtend" : array();

$SetAuction = APP_PATH . "Common/Conf/SetAuction.php";
$SetAuction = file_exists($SetAuction) ? include "$SetAuction" : array();

$SetWeixin = APP_PATH . "Common/Conf/SetWeixin.php";
$SetWeixin = file_exists($SetWeixin) ? include "$SetWeixin" : array();

$SetOrder = APP_PATH . "Common/Conf/SetOrder.php";
$SetOrder = file_exists($SetOrder) ? include "$SetOrder" : array();

$SetMember = APP_PATH . "Common/Conf/SetMember.php";
$SetMember = file_exists($SetMember) ? include "$SetMember" : array();

$SetSellerLevel = APP_PATH . "Common/Conf/SetSellerLevel.php";
$SetSellerLevel = file_exists($SetSellerLevel) ? include "$SetSellerLevel" : array();

$SetBuyLevel = APP_PATH . "Common/Conf/SetBuyLevel.php";
$SetBuyLevel = file_exists($SetBuyLevel) ? include "$SetBuyLevel" : array();

$SetExpress = APP_PATH . "Common/Conf/SetExpress.php";
$SetExpress = file_exists($SetExpress) ? include "$SetExpress" : array();


return array_merge($config1, $config2, $payment,$setExtend,$SetAuction,$SetOrder,$SetMember,$SetBuyLevel,$SetSellerLevel,$SetWeixin,$SetExpress);
?>