<?php

/**
  +------------------------------------------------------------------------------
 * 分类管理
  +------------------------------------------------------------------------------
 */
namespace Org\Util;
class Category {
    Static function getList($model,$field='cid',$pid = 0,$condition = NULL, $orderby = NULL) {
        $cate = $model->where($condition)->order($orderby)->select();
        $arr = array();
        foreach ($cate as $v) {
            if ($v['pid'] == $pid){
                $v['level'] = $level +1;
                $v['fullname'] =$v['name'];
                $arr[] = $v;
                $arr = array_merge($arr, self::thisChilds($model,$cate,$field, $v[$field], $level + 1));
            }
        }
        return $arr;
    }

    Static Public function thisChilds($model,$cate, $field='cid', $pid = 0, $level = 0){
        $arr = array();
        $count = $model->where(array('pid'=>$pid))->count();
        $ctad = 0;
        foreach($cate as $k=>$v){
            if ($v['pid'] == $pid){
            $ctad +=1;
            if ($count>0) {
                if ($ctad==$count) {
                    $html = str_repeat('&nbsp;&nbsp;&nbsp;', $level).'└&nbsp;';
                }else{
                    $html = str_repeat('&nbsp;&nbsp;&nbsp;', $level).'├&nbsp;';
                }
            }else{
                $html = str_repeat('&nbsp;&nbsp;', $level);
            }
            $v['level'] = $level +1;
            $v['fullname'] = $html.$v['name'];
            $arr[] = $v;
            $arr = array_merge($arr, self::thisChilds($model,$cate, $field,$v[$field], $level + 1));
          
            }
        }
        return $arr;
    }
    Static Public function catesort($cate, $html = '&nbsp;&nbsp;&nbsp;--', $pid = 0, $level = 0){
      $arr = array();
      foreach($cate as $v){
        if ($v['pid'] == $pid){
          $v['level'] = $level +1;
          $v['html'] = str_repeat($html, $level);
          $arr[] = $v;
          $arr = array_merge($arr, self::catesort($cate, $html, $v['cid'], $level + 1));
          
        }
      }
      return $arr;
    }
    Static Public function countlayer($cate){
        // 所有父类
        $layersize = array_unique(array_reduce($cate, create_function('$v,$w', '$v[$w["cid"]]=$w["pid"];return $v;')));
        // 数组层数
        return count($layersize)-1;
    }

    Static Public function catesortforlayer($cate, $name = 'child', $pid = 0){
      $arr = array();
      foreach($cate as $v){
        if($v['pid'] == $pid){
          $v[$name] = self::catesortforlayer($cate, $name, $v['cid']);
          $arr[] = $v;
          
        }
      }
      return $arr;
    }
    Static Public function getPath ($data, $cid,$field='cid'){
      $result = array();
      $obj = array();
      foreach($data as $node) {
          $obj[$node[$field]] = $node;
      }    
      if (isset($obj[$cid])) {
         $value = $obj[$cid];
         $result[] = $obj[$cid];
      }else{
         $value = null;
      }     
      while($value) {
          $cid = null;
          foreach($data as $node) {
              if($node[$field] == $value['pid']) {
                  $cid = $node[$field];
                  $result[] = $node;
                  break;
              }
          }
          $value = isset($obj[$cid]) ? $obj[$cid] : null;
      }
      unset($obj);
      sort($result);
      return $result;
    }
    Static Public function getChildsId($cate, $pid){
        $arr = array();
        foreach($cate as $v){
            if($v['pid'] == $pid){
                $arr[] = $v['cid'];
                $arrA = self::getChildsId($cate, $v['cid']);
                $arr = array_merge($arr, $arrA );
                unset($arrA);
            }
        }
        return $arr;
    }

    Static Public function getChilds($cate, $pid){
        $arr = array();
        foreach($cate as $v){
            if($v['pid'] == $pid){
                $arr[] = $v;
                $arr = array_merge($arr, self::getChildsId($cate, $v['cid']));
            }
        }
        return $arr;
    }
}
?>