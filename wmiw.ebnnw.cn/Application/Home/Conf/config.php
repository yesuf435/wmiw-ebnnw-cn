<?php
/**
 * Created by PhpStorm.
 * User: cony
 * Date: 14-2-21
 * Time: 下午2:13
 */
return array(
    /* 开发人员相关信息 */
    'AUTHOR_INFO' => array(
        'author' => 'leo.li',
        'author_email' => '1772703372@qq.com',
    ),

    /* 模板相关配置 */
    'TMPL_PARSE_STRING' => array(
        '__STATIC__' => __ROOT__ . '/Public',
        '__IMG__'    => __ROOT__ . '/Public/Home/Img',
        '__CSS__'    => __ROOT__ . '/Public/Home/Css',
        '__JS__'     => __ROOT__ . '/Public/Home/Js',
        '--PUBLIC--'=>__ROOT__ . '/Public',
        '__WEBSOCKET__'=>__ROOT__ .'/Public/WebSocketMain'
    ),
    'DEFAULT_THEME'         =>  'Web',
    'PAGE_SIZE' =>20,//分页数量
//缓存设置
    
);