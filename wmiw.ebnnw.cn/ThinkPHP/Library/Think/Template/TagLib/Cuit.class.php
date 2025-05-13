<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: oncoo.net <www.oncoo.net>
// +----------------------------------------------------------------------
namespace Think\Template\TagLib;
use Think\Template\TagLib;
/**
 * Html标签库驱动
 */
class Cuit extends TagLib{
    // 标签定义
    protected $tags   =  array(
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        'region'     => array('attr'=>'province,city,area,layer','close'=>0),
        'menu'     => array('attr'=>'menupos,maxsize','close'=>0)
        );
    /**
     * 省市区标签解析
     * 格式： <region province="my_info.province" city="my_info.city" area="my_info.area" layer="3"/>
     * @access public
     * @param array $tag 标签属性
     * @return string|void
     */
    public function _region($tag) {
        if(empty($tag['province'])){
            $rid = array(0,0,0);
        }else{
            $provArr = explode('.', $tag['province']);
            $cityArr = explode('.', $tag['city']);
            $areaArr = explode('.', $tag['area']);
            
            $provTwoArr = $this->tpl->get($provArr['0']);
            $cityTwoArr = $this->tpl->get($cityArr['0']);
            $areaTwoArr = $this->tpl->get($areaArr['0']);
            
            $rid = array(
                $provTwoArr[$provArr['1']],
                $cityTwoArr[$cityArr['1']],
                $areaTwoArr[$areaArr['1']]
            );
        }

        if(!empty($tag['layer'])){
            //判断层级是否为数字
            if(is_numeric($tag['layer'])){
                $layer  =$tag['layer']>3 || $tag['layer']<1 ? 3 : $tag['layer'];
            }else{ //不是的话认为是模板变量  进行拆分获取值
                $layerArr = explode('.', $tag['layer']);
                $layerTwoArr = $this->tpl->get($layerArr['0']);
                $layer = $layerTwoArr['layer'];
            }
        }else{
            $layer=3;
        }
        
        $name =array('province','city','area');
        $option = array('——省、直辖市——','——选择城市——','——选择区、县——');
        $tier = 1;
        $region = M('region');
        $rMap = $region->field(array('region_id','region_name'))->where(array('parent_id'=>1))->select();
        
        for ($i=0; $i < $layer; $i++) { 
            $regionHtml .= '<select class="region" id="'.$name[$i].'" name="region['.$name[$i].']">';
            if($rid[$i] == 0){
                $regionHtml .= '<option tier="'.$tier.'" selected="selected" value="0">'.$option[$i].'</option>';
            }else{
                $regionHtml .= '<option tier="'.$tier.'" value="0">'.$option[$i].'</option>';
            }
            foreach ($rMap as $pk => $pv) {
                if($rid[$i] == $pv['region_id']){
                    $regionHtml .= '<option selected="selected" tier="'.$tier.'" value="'.$pv['region_id'].'">'.$pv['region_name'].'</option>';
                    $setout = $region->field(array('region_id','region_name'))->where(array('parent_id'=>$rid[$i]))->select();
                }else{
                    $regionHtml .= '<option tier="'.$tier.'" value="'.$pv['region_id'].'">'.$pv['region_name'].'</option>';
                }
            }
            $regionHtml .= '</select>';
            $tier += 1;
            $rMap = $setout;
        }
        $regionHtml .='<script type="text/javascript">var regionUrl = "{:U("region")}";</script>';//异步获取下一级地区URL
        $regionHtml .='<script type="text/javascript" src="__ROOT__/Public/Js/cuitTagLib.js"></script>';
        $regionHtml.=$phpCode;
        return $regionHtml;
    }
    /**
     * 底部菜单标签解析
     * 格式： <menu menupos="1" maxsize="4" row="10"/>
     * @access public
     * @param array $tag 标签属性
     * @return string|void
     */
    public function _menu($tag) {
        $nav = M("navigation");
        $menu = $nav->where(array('pid'=>$tag['menupos']))->limit($tag['maxsize'])->order('sort desc')->select();
        $menuHtml = '';
        $menuHtml .= '<ul class="menu_onelayer clearfix">';
        foreach ($menu as $ck => $cv) {
            $bg_cor=($ck%2==0)?' cor_ff':'';
            $one_a_cor =($cv['url']=="javascript:void(0);")?' one_cor':'';
            $menuHtml .= '<li class="one_li'.$bg_cor;
            $menuHtml .='"> <a'.' class="one_a'.$one_a_cor;
            $menuHtml .='" target="'.$cv['target'].'" href="'.$cv['url'].'">'.$cv['name'].'</a>';
            $menuTwo = $nav->where(array('pid'=>$cv['lid']))->order('sort desc')->select();
            if(!empty($menuTwo)){
                $menuHtml .= '<ul class="menu_twolayer clearfix">';
                foreach ($menuTwo as $mk => $mv) {
                    $two_a_cor =($mv['url']=="javascript:void(0);")?'two_cor':'';
                    $menuHtml .= '<li> <a class="'.$two_a_cor;
                    $menuHtml .= '" target="'.$mv['target'].'" href="'.$mv['url'].'">'.$mv['name'].'</a></li>';
                }
                $menuHtml .= '</ul></li>';
            }else{
                $menuHtml .= '</li>';
            }
            
        }
        $menuHtml .='</ul>';
        return $menuHtml;
    }
    
}