<?php
//公共函数
function toDate($time, $format = 'Y-m-d H:i:s') {
    if (empty ( $time )) {
        return '';
    }
    $format = str_replace ( '#', ':', $format );
    return date ($format, $time );
}

function toDateYmd($time, $format = 'Y-m-d') {
    if (empty ( $time )) {
        return '';
    }
    $format = str_replace ( '#', ':', $format );
    return date ($format, $time );
}

// 获取筛选条件名
function filtName($fid){
    return M('Goods_filtrate')->where('fid='.$fid)->getField('name');
}
// 获取分类名
function cateName($cid){
    return M('Goods_category')->where('cid='.$cid)->getField('name');
}

// 检查是否有子级条件
function countChild($fid){
    return M('Goods_filtrate')->where('pid='.$fid)->count();
}
 

?>