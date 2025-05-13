<?php
//$config_arr1 = include_once APP_PATH . 'Common/Conf/config.php';
$DB_PREFIX = C('DB_PREFIX');
return array(
    'PAGE_SIZE' =>20,//分页数量
    /*
     * 以下是上传图片路径和尺寸的配置
     */
    'ADV_PICPATH'=>'Advshow',//广告图片上传路径（相对于根路径下）

    'CATE_PICPATH'=>'Category',//分类图标上传路径（相对于根路径下）
    'CATE_ICO_WIDTH'=>'100',//分类图标宽度
    'CATE_ICO_HEIGHT'=>'100',//分类图标高度

    'LINK_PICPATH'=>'Link',//友情链接图标上传路径（相对于根路径下）
    'LINK_ICO_WIDTH'=>'128',//友情链接图标宽度
    'LINK_ICO_HEIGHT'=>'48',//友情链接图标高度

    'NEWS_PICPATH'=>'News',//文章图标上传路径（相对于根路径下）
    'NEWS_ICO_WIDTH'=>'180',//文章图标宽度
    'NEWS_ICO_HEIGHT'=>'100',//文章图标高度
    /*
     * 以下是RBAC认证配置信息
     */
    'USER_AUTH_ON' => true,
    'USER_AUTH_TYPE' => 2, // 默认认证类型 1 登录认证 2 实时认证
    'USER_AUTH_KEY' => 'authId', // 用户认证SESSION标记
//    'ADMIN_AUTH_KEY' => '1772703372@qq.com',
    'USER_AUTH_MODEL' => 'Admin', // 默认验证数据表模型
    'AUTH_PWD_ENCODER' => 'md5', // 用户认证密码加密方式encrypt
    'USER_AUTH_GATEWAY' => '/admin/Public/index', // 默认认证网关
    'NOT_AUTH_MODULE' => 'Public,Upload', // 默认无需认证模块
    'REQUIRE_AUTH_MODULE' => '', // 默认需要认证模块
    'NOT_AUTH_ACTION' => 'search,search_realname,getcate,search_user,goods_delpic,getFilt,getExtends,checkNewsTitle,goodPicOrder,del_pic,order_cate,order_filtrate,getChild,delLink,order_fields,delExtend,search_special', // 默认无需认证操作
    'REQUIRE_AUTH_ACTION' => '', // 默认需要认证操作
    'GUEST_AUTH_ON' => false, // 是否开启游客授权访问
    'GUEST_AUTH_ID' => 0, // 游客的用户ID
    'RBAC_ROLE_TABLE' => $DB_PREFIX . 'role',
    'RBAC_USER_TABLE' => $DB_PREFIX . 'role_user',
    'RBAC_ACCESS_TABLE' => $DB_PREFIX . 'access',
    'RBAC_NODE_TABLE' => $DB_PREFIX . 'node',

    'LOCK_ID'=>array(
        'link'=>'1,2,3',
        'article'=>'1,2,3',
        'goods'=>'1,2,3',
        'art_sun'=>'1,2,3'
        ),
    /*
     * 系统备份数据库时每个sql分卷大小，单位字节
     */
    'sqlFileSize' => 5242880, //该值不可太大，否则会导致内存溢出备份、恢复失败，合理大小在512K~10M间，建议5M一卷
        //10M=1024*1024*10=10485760
        //5M=5*1024*1024=5242880
);

//return array_merge($config_arr1, $config_arr2);
?>