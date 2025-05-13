<?php

/**
  +----------------------------------------------------------
 * 通过分类cid生成筛选条件html ——————来自>>>>>>>>昂酷拍卖——www.oncoo.net<<<<<<<<
  +----------------------------------------------------------
 * @param  [type] $cid     [分类id]
 * @param  [array] $get [属性字符串]
 * @return [type]          [html]
   +----------------------------------------------------------
 */
function getFiltrateHtml($stage,$screen,$sort,$gt){
    if(!S(C('CACHE_FIX').'_ftml_'.$stage.'_'.$screen.'_'.$sort.'_'.$gt[0].'_'.implode('_',$gt))){
        $filtrate = M('Goods_filtrate');
        $category = M('Goods_category');
        $cLinkF =M('Goods_category_filtrate');
        $filtList =$filtrate->select();
        $cat = new \Org\Util\Category();
        $fisgt = implode('_%', explode('_', $gt[4]));
        $gtArr = explode('_', $gt[4]);
        $interimCid = $gt[0]; //临时存放cid
        $catPath = array($gt[0]); //用来保存分类路径的cid
        // 获取对应顶级条件
        do {
             $interimCid = $category->where('cid='.$interimCid)->getField('pid');
             if($interimCid !=0){
                $catPath[] = $interimCid ;
             }
        } while ( $interimCid !=0 );

        $catPath = array_reverse($catPath); //获取分类路径id数组
        $filtHtml = '';
        foreach ($catPath as $lk => $lv) {
            $fidArr = $cLinkF->where('cid='.$lv)->getField('fid',true);
            $filtMap = $filtrate->where(array('fid'=>array('in',$fidArr)))->order('sort desc')->select();
            $filtBoxClass = $lk == 0 ? 'filtbox' : 'filtbox child';  //除了顶级以外的样式
            if ($filtMap) {
                // 获取不限条件fid
                $topChildMap = $filtrate->where('pid= 0')->order('sort desc')->select();
                $top_layer = $filtrate->where('pid = 0')->order('sort desc')->getField('fid',true);
                // 顶级
                $filtHtml .= '<div class="'.$filtBoxClass.'">';
                $makeArr = $gtArr;
                foreach ($filtMap as $fk => $fv) {
                    $filtHtml.='<ul class="am-cf">';
                    $filtHtml.='<li class="filt-tit"><span>'.$fv['name'].':</span></li>';
                    $filtHtml.='<li><a class="filtParent';
                    if(empty($gt[4])||in_array($fv['fid'],$gtArr)){
                        $filtHtml.= ' current';
                    }
                    // 不限条件地址生成
                    foreach ($makeArr as $key => $fiv) {

                        if($filtrate->where('fid='.$fiv)->order('sort desc')->getField('pid')==0){
                            $topNew_gt='';
                            foreach ($cat->getList($filtrate,'fid',$fiv,null,'sort desc') as $onek => $onev) {
                                $topNew_gt = str_replace('_%'.$onev['fid'].'_%','_%'.$fiv.'_%','_%'.$fisgt.'_%');
                            }
                            $topSunLayer = $filtrate->where('pid = 0')->order('sort desc')->getField('fid',true);
                            foreach ($topSunLayer as $tsk => $tsv) {
                                if($tsv==$fiv){
                                    $topNew_gt = '_%'.$fisgt.'_%';
                                    foreach ($cat->getList($filtrate,'fid',$fiv,null,'sort desc') as $tglk => $tgl) {
                                        if($tgl['fid']==$fiv){
                                            $topNew_gt = str_replace('_%'.$tgl['fid'].'_%'.$fv['fid'].'_%', '_%', $topNew_gt);
                                        }else{
                                        $topNew_gt = str_replace('_%'.$tgl['fid'].'_%', '_%', $topNew_gt);
                                        }
                                    }
                                    unset($makeArr[$key]);
                                    break 1;
                                }
                            }

                        }else{
                            $topNew_gt='';
                            $tpfid=$filtrate->where('fid='.$fv['fid'])->order('sort desc')->getField('pid');
                            $topSunLayer = $filtrate->where('pid='.$fv['fid'])->order('sort desc')->getField('fid',true);

                            foreach ($topSunLayer as $tsk => $tsv) {
                                if($tsv==$fiv){
                                    $topNew_gt = str_replace('_%'.$fiv.'_%', '_%'.$fv['fid'].'_%', '_%'.$fisgt.'_%');
                                    $fivlist = $cat->getList($filtrate,'fid',$fiv,null,'sort desc');
                                    if(is_array($fivlist)){
                                        foreach ($fivlist as $tglk => $tgl) {
                                            $topNew_gt = str_replace('_%'.$tgl['fid'].'_%', '_%', $topNew_gt);
                                        }
                                    }
                                    
                                    
                                    unset($makeArr[$key]);
                                    break 2;
                                }
                            }

                        }
                    }
                    $topNew_gt=str_replace('_%', '_', $topNew_gt);
                    $topHref = U(ACTION_NAME,array('gt'=>$gt[0].'-'.$gt[1].'-'.$gt[2].'-'.$gt[3].'-'.substr($topNew_gt,1,strlen($topNew_gt)-2).'-'.$gt[5].'-'.$gt[6]));
                    $filtHtml.= '" fid="'.$fv['fid'].'" href="'.$topHref.'">不限</a></li>';
                    // 不限条件地址生成_end
                    $childMap = $filtrate->where('pid='.$fv['fid'])->order('sort desc')->select();

                    $fixPath = $cat->getPath($filtList,$fv['fid'],'fid'); //获取该分类路径
                    // foreach ($fixPath as $fpk => $fpv) {
                    //     $fixPath
                    // }
                    // 获取该条件下一级条件fid集合
                    $same_layer = $filtrate->where('pid='.$fv['fid'])->order('sort desc')->getField('fid',true);
                    $fixsunArr=array();
                    $childLi ='';
                    foreach ($childMap as $ck => $cv) {
                        $filtHtml.= '<li><a fid="'.$cv['fid'].'" class="filtParent';
                        $display = 'none';
                        if(in_array($cv['fid'],$gtArr)){
                            $filtHtml.= ' current';
                            $display = 'block';
                        }
                        if(in_array($fv['fid'],$gtArr)){
                            $new_gt = str_replace('_%'.$fv['fid'].'_%', '_%'.$cv['fid'].'_%','_%'.$fisgt.'_%');
                  
                        }
                        $fpke=1;
                        foreach ($fixPath as $fxk => $fxv) {
                            if($fxv['fid']=$cv['fid']){
                                foreach (array_slice($fixPath,$fpke) as $slk => $slv) {
                                    if($slv){$fixsunArr[$slk]=$slv['fid'];}
                                }
                            }
                            $fpke+=1;
                        }
                        foreach ($same_layer as $sly => $slv) {
                            foreach ($gtArr as $onfak => $onfav) {
                                $searchArr=$fixsunArr;
                                $replaceArr=$fixsunArr;
                                if($slv==$onfav){
                                    $searchArr[]=$onfav;
                                    $replaceArr[]=$cv['fid'];
                                    $searchStr = implode('_%', $searchArr);
                                    $replaceStr = implode('_%', $replaceArr);
                                    $new_gt = str_replace('_%'.$searchStr.'_%', '_%'.$replaceStr.'_%','_%'.$fisgt.'_%');
                                    foreach ($cat->getList($filtrate,'fid',$onfav,null,'sort desc') as $glk => $gl) {
                                        $new_gt = str_replace('_%'.$gl['fid'].'_%', '_%', $new_gt);
                                    }
                                    break;
                                }
                            }
                        }
                        $new_gt=str_replace('_%', '_', $new_gt);
                        // 一级筛选条件地址生成_end
                        $ahref = U(ACTION_NAME,array('stage'=>$stage,'screen'=>$screen,'sort'=>$sort,'gt'=>$gt[0].'-'.$gt[1].'-'.$gt[2].'-'.$gt[3].'-'.substr($new_gt,1,strlen($new_gt)-2).'-'.$gt[5].'-'.$gt[6]));
                        if(countChild($cv['fid'])!=0){
                            $filtHtml .= '" href="'.$ahref;
                        }else{
                            $filtHtml .= '" href="'.$ahref;
                        }
                        $filtHtml.='">'.$cv['name'].'</a></li>';
                        $childLi .= getChildHtml($cv['fid'],$gt,$display,$gtArr,$stage,$screen,$sort); //获取子类
                    }
                    $filtHtml.='</ul>';
                    $filtHtml.=$childLi;
                    $childLi = '';
                    $display = 'none';
                }
                $filtHtml.='</div>';
            }
        }  
        S(C('CACHE_FIX').'_ftml_'.$stage.'_'.$screen.'_'.$sort.'_'.$gt[0].'_'.implode('_',$gt),$filtHtml,36000);
    }else{
        $filtHtml = S(C('CACHE_FIX').'_ftml_'.$stage.'_'.$screen.'_'.$sort.'_'.$gt[0].'_'.implode('_',$gt));
    }
    
    return $filtHtml;
}
// 检查是否有子级条件
function countChild($fid){
    return M('Goods_filtrate')->where('pid='.$fid)->count();
}
    // 通过fid生成子级筛选条件html
function getChildHtml($fid,$gt,$display,$gtArr,$stage,$screen,$sort){
    if(countChild($fid)!=0){
        $filtrate =M('Goods_filtrate');
        $cat = new \Org\Util\Category();
        $filtList = $filtrate->select();
        
        $childArr = $filtrate->where('pid='.$fid)->order('sort desc')->select();
        // 同一级条件fid
        $same_layer = $filtrate->where('pid='.$fid)->order('sort desc')->getField('fid',true);
        $childStr = '<div class="filtLi" style="display:'.$display.';" fid="'.$fid.'">';
        $childStr .='<ul class="am-cf filtChild">';
        foreach ($childArr as $ck => $cv) {
            $childStr .='<li><a class="filtParent';
            $displaySun = 'none';
            if(in_array($cv['fid'],$gtArr)){
                $childStr.= ' current';
                $displayCh = 'block';
            }else{
                $displayCh = 'none';
            }
            // 生成地址
            $fixPath = $cat->getPath($filtList,$fid,'fid');
            $fpke=1;
            foreach ($fixPath as $fxk => $fxv) {
                if($fxv['fid']=$fid){
                    // 从节点到结束
                    foreach (array_slice($fixPath,$fpke) as $slk => $slv) {
                        if($slv){$fixsunArr[$slk]=$slv['fid'];}
                    }
                    // 从开始到节点
                    foreach (array_slice($fixPath,1,$fpke) as $slk => $slv) {
                        if($slv){$fixstarArr[$slk]=$slv['fid'];}
                    }
                    break;
                }
                $fpke+=1;
            }

            $fisgt = implode('_%', explode('_', $gt[4]));
            foreach ($same_layer as $sly => $slv) {
                foreach ($gtArr as $fak => $fav) {
                    
                    if($slv==$fav){
                        $searchArr=$fixsunArr;
                        $replaceArr=$fixsunArr;
                        $searchArr[]=$fav;
                        $replaceArr[]=$cv['fid'];

                        $searchStr = implode('_%', $searchArr);
                        $replaceStr = implode('_%', $replaceArr);
                        $new_gt = str_replace('_%'.$searchStr.'_%', '_%'.$replaceStr.'_%', '_%'.$fisgt.'_%');
                        // 删除子级fid
                        foreach ($cat->getList($filtrate,'fid',$fav) as $glk => $gl) {
                            $new_gt = str_replace('_%'.$gl['fid'].'_%', '_%', $new_gt);
                        }
                        break;
                    }else{
                        $searchArr=$fixstarArr;
                        $replaceArr=$fixstarArr;
                            $replaceArr[]=$cv['fid'];
                            $searchStr = implode('_%', $searchArr);
                            $replaceStr = implode('_%', $replaceArr);
                            $new_gt = str_replace('_%'.$searchStr.'_%', '_%'.$replaceStr.'_%', '_%'.$fisgt.'_%');
                            // 删除同级其他fid
                            foreach ($filtrate->where('pid='.$fid)->order('sort desc')->getField('fid',true) as $glk => $gl) {
                                if($gl!=$cv['fid']){
                                    $new_gt = str_replace('_%'.$gl.'_%', '_%', $new_gt);
                                }
                                $catgetList = $cat->getList($filtrate,'fid',$gl);
                                if($catgetList){
                                    foreach ($cat->getList($filtrate,'fid',$gl) as $glk => $gl) {
                                        $new_gt = str_replace('_%'.$gl['fid'].'_%', '_%', $new_gt);
                                    }
                                }
                            }

                            break;
                    }
                }
            } 


            // 删除
            $new_gt=str_replace('_%', '_', $new_gt);
            $childStr.='" fid="'.$cv['fid'];
            $ahref = U(ACTION_NAME,array('stage'=>$stage,'screen'=>$screen,'sort'=>$sort,'gt'=>$gt[0].'-'.$gt[1].'-'.$gt[2].'-'.$gt[3].'-'.substr($new_gt,1,strlen($new_gt)-2).'-'.$gt[5].'-'.$gt[6]));
            if(countChild($cv['fid'])!=0){
                $childStr .= '" href="'.$ahref.'">'.$cv['name'];
            }else{
                $childStr .= '" href="'.$ahref.'">'.$cv['name'];
            }
            $childStr .= '</a></li>';
            $childSun = getChildHtml($cv['fid'],$gt,$displayCh,$gtArr,$stage,$screen);  
        }
        $childStr .='</ul>';
        $childStr .=$childSun;
        $childStr .= '</div>';
        return $childStr;
    }
    return ;
}
/**
 * 正在拍卖结束时间段查询条件
 * @param  [str] $gt [条件]
 * @return [array]     [条件数组]
 */
function bidSection($gt){
    $nowTime=time();
    switch ($gt) {
        // 今天结束
        case '1':
            $bid_time = array(
                'starttime'=>array('elt',$nowTime),
                // 小于明天就是今天的
                'endtime'=>array(
                    array('lt',strtotime(date("Y-m-d",strtotime("+1 day")))),
                    array('egt',$nowTime)
                )
            );
            break;
        // 明天结束
        case '2':
            $bid_time = array(
                'starttime'=>array('elt',$nowTime),
                // 小于后天大于今天就是明天的
                'endtime'=>array(
                    array('gt',strtotime(date("Y-m-d",strtotime("+1 day")))),
                    array('lt',strtotime(date("Y-m-d",strtotime("+2 day")))),
                    array('egt',$nowTime)
                )
            );
            break;
        // 后天结束
        case '3':
            $bid_time = array(
                'starttime'=>array('elt',$nowTime),
                // 大于后天
                'endtime'=>array(
                    array('gt',strtotime(date("Y-m-d",strtotime("+2 day")))),
                    array('lt',strtotime(date("Y-m-d",strtotime("+3 day")))),
                    array('egt',$nowTime)
                )
            );
            break;
        // 其他结束
        case '4':
            $bid_time = array(
                'starttime'=>array('elt',$nowTime),
                // 大于后天
                'endtime'=>array(
                    array('gt',strtotime(date("Y-m-d",strtotime("+3 day")))),
                    array('egt',$nowTime)
                )
            );
            break;
        // 全部正在拍卖
        default:
            $bid_time = array(
                'starttime'=>array('elt',$nowTime),
                'endtime'=>array('egt',$nowTime)
            );
            break;
    }
    $bid_time['hide']=array('eq',0);
    
    return $bid_time;
}
/**
 * 开拍时间段查询条件
 * @param  [str] $gt [条件]
 * @return [array]     [条件数组]
 */
function foreshow($gt){
    $nowTime=time();
    switch ($gt) {
        // 即将开拍
        case '1':
            $bid_time = array(
                // 小于明天就是今天的
                'starttime'=>array(
                    array('lt',strtotime(date("Y-m-d",strtotime("+1 day")))),
                    array('egt',$nowTime)
                )
            );
            break;
        // 明天开拍
        case '2':
            $bid_time = array(
                // 小于后天大于今天就是明天的
                'starttime'=>array(
                    array('gt',strtotime(date("Y-m-d",strtotime("+1 day")))),
                    array('lt',strtotime(date("Y-m-d",strtotime("+2 day")))),
                    array('egt',$nowTime)
                )
            );
            break;
        // 后天开拍
        case '3':
            $bid_time = array(
                // 大于后天
                'starttime'=>array(
                    array('gt',strtotime(date("Y-m-d",strtotime("+2 day")))),
                    array('lt',strtotime(date("Y-m-d",strtotime("+3 day")))),
                    array('egt',$nowTime)
                )
            );
            break;
        // 其他开拍
        case '4':
            $bid_time = array(
                // 大于后天
                'starttime'=>array(
                    array('gt',strtotime(date("Y-m-d",strtotime("+3 day")))),
                    array('egt',$nowTime)
                )
            );
            break;
        // 所有未开拍
        default:
            $bid_time = array(
                'starttime'=>array('gt',$nowTime),
            );
            break;
    }
    $bid_time['hide']=array('eq',0);
    return $bid_time;
}
/**
 * 已结束-结束时间段查询条件
 * @param  [str] $gt [条件]
 * @return [array]     [条件数组]
 */
function endbid($gt){
    $nowTime=time();
    switch ($gt) {
        // 今天成交
        case '1':
            $end_where = array(
                // 小于明天就是今天的
                'endtime'=>array(
                    array('lt',strtotime(date("Y-m-d",strtotime("+1 day")))),
                    array('gt',strtotime(date("Y-m-d",strtotime("-1 day")))),
                    array('elt',$nowTime)
                )
            );
            break;
        // 昨天成交
        case '2':
            $end_where = array(
                // 大于前天小于今天就是昨天的
                'endtime'=>array(
                    array('lt',strtotime(date("Y-m-d",strtotime("0 day")))),
                    array('gt',strtotime(date("Y-m-d",strtotime("+2 day"))))
                )
            );
            break;
        // 前天成交
        case '3':
            $end_where = array(
                // 大于大前天小于昨天就是昨天的
                'endtime'=>array(
                    array('gt',strtotime(date("Y-m-d",strtotime("-3 day")))),
                    array('lt',strtotime(date("Y-m-d",strtotime("-1 day")))),
                )
            );
            break;
        // 全部结束
        default:
            $end_where = array(
                'endtime'=>array('elt',$nowTime)
            );
            break;
    }
    // 有人出过价的
    $end_where['uid']=array('neq',0);
    // 判处撤拍的
    $end_where['endstatus'] = array('neq',4);
    // 当前价大于等于保留价
    $end_where['_string'] = 'Auction.nowprice>=Auction.price';
    $end_where['hide']=array('eq',0);
    
    return $end_where;
}
/**
 * [checkAtt 判断是否关注]
 * @param  [type] $gid  [商品id]
 * @param  [type] $rela [关注类型]
 * @return [type]       [判断]
 */
function checkAtt($rela,$gid,$uid){
    $data = array(
        'gid'=>$gid,
        'rela'=>$rela,
        'uid'=>$uid
    );
    if(M('attention')->where($data)->count()){
        return 1;
    }else{
        return 0;
    }
}

// 通过分类cid生成扩展字段html
function getExtendsCon($cid,$gid){
    $extend = M('Goods_extend');
    $cate = M('Goods_category');
    $cLinkE =M('goods_category_extend');

    $cate->where('cid='.$cid)->getField('pid');
    $interimCid = $cid; //临时存放cid
    $catPath = array($cid); //用来保存分类路径的cid
    do {
         $interimCid = $cate->where('cid='.$interimCid)->getField('pid');
         if($interimCid !=0){
            $catPath[] = $interimCid ;
         }
    } while ( $interimCid !=0 );
    $catPath = array_reverse($catPath); //获取分类路径id数组
    $eHtmlUl = '';
    $eHtmlDiv = '';
    $goods_fields = M('goods_fields');
    $regWhere = array(
        array('cid'=>array('in',$catPath)),
        array('eid'=>0)
        );

    if($cLinkE->where($regWhere)->count()){
        $region = C('goods_region'); //判断是否关联地区
    }else{
        $region = 'no';
    }
    foreach ($catPath as $lk => $lv) {
        $eidArr = $cLinkE->where('cid='.$lv)->getField('eid',true);
        $eMap = $extend->where(array('eid'=>array('in',$eidArr),'status'=>1))->order('rank desc')->select();
        if(is_array($eMap)){
            foreach ($eMap as $ek => $ev) {
                $field[$ek]['title'] = $ev['name'];
                if($gid){
                    $fieldVal=$goods_fields->where(array('eid'=>$ev['eid'],'gid'=>$gid))->getField('default');
                }else{
                    $fieldVal=$ev['default'];
                }
                $field[$ek]['content'] = htmlspecialchars_decode(stripslashes($fieldVal));
            }
        }
        
    }
    return $field;
}


// 获取第三次出价后的价格
//$type:1获取三次加价，0获取三次加价后 
function thricebid($stepsize_type,$stepsize,$nowprice,$type=1){
    $futureA = setStep($stepsize_type,$stepsize,$nowprice);
    $futureAp = $nowprice+$futureA;
    // 第二次后
    $futureB = setStep($stepsize_type,$stepsize,$futureAp);
    $futureBp = $futureAp+$futureB;
    // 第三次后
    $futureC = setStep($stepsize_type,$stepsize,$futureBp);
    if($type==1){
        return $futureBp+$futureC;
    }else{
        return $futureA+$futureB+$futureC;
    }
}
// 获取不对用户显示商品的卖家uid集合
function blackuser($uid){
    // 如果登陆了uid【
    if ($uid) {
        $blacklist = M('blacklist');
        $xidarr = array();
        // 买家屏蔽的卖家uid集合
        $selarr= $blacklist->where(array('uid'=>$uid,'selbuy'=>'sel'))->getField('xid',true);
        if ($selarr) {
            $xidarr = $selarr;
        }
        $buyarr= $blacklist->where(array('xid'=>$uid,'selbuy'=>'buy'))->getField('uid',true);
        if ($buyarr) {
            $xidarr = array_merge($xidarr,$buyarr);
        }
        return $xidarr;
    }else{
        return array();
    }
    
    // 如果登陆了获取屏蔽的卖家uid】
}
// 生成拍品订单
  function create_order($info){
    $order = M('Goods_order');
    $auction = M('Auction');
    if($info['nowprice']>=$info['price']&&$info['uid']!=0){
        // 拍卖状态设置
        $aData = array(
        'pid'=>$info['pid'],
        'endstatus'=>1
        );
        $info['endstatus']=1;
        
        if($auction->save($aData)){
            do {
                $order_no = createNo('BID');
                $reno = $order->where(array('order_no'=>$order_no))->count();
            } while ($reno != 0);
            // 生成不重复的定代号$order_no】
            $oData = array(
                'order_no'=>$order_no,
                'type'=>$info['type'],
                'gid'=>$info['pid'],
                'uid'=>$info['uid'],
                'sellerid'=>$info['sellerid'],
                'price'=>$info['nowprice'],
                'freight'=>$info['freight'],
                'broker'=>perbroker($info['broker_type'],$info['nowprice'],$info['broker']),
                'broker_buy'=>perbrokerbuy($info['broker_buy_type'],$info['nowprice'],$info['broker_buy']),
                'time'=>time(),
                'status'=>0,
                'remark'=>serialize(array('0'=>'竞拍成功，请在订单有效期内进行支付！'))
            );
            // 设置订单支付过期时间
            if(C('Order.losetime1')==0||C('Order.losetime1')==''){
                $oData['deftime1'] = 0;
            }else{
                $losetime1=C('Order.losetime1');
                $oData['deftime1'] = time()+(60*60*24*$losetime1);
            }
            $yn = $order->where(array('gid'=>$oData['gid'],'uid'=>$oData['uid']))->count();
            if($yn==0){
                // 生成订单
                if($order->add($oData)){
                    // 退还保证金
                    return_pledge($info['pid'],$info['uid']);
                    if($info['sid']!=0){
                        $special= M('special_auction')->where(array('sid'=>$info['sid']))->find();
                        $succmsg = '恭喜您以'.$info['nowprice'].'元拍到【<a target="_blank" href="'.U('Special/speul',array('sid'=>$special['sid'],'aptitude'=>1)).'">'.$special['sname']. '</a>】专场下【<a target="_blank" href="'.U('Auction/details',array('pid'=>$info['pid'],'aptitude'=>1)).'">'.$info['pname'].'</a>】请在'.date('Y-m-d H:i',$oData['deftime1']).'之前支付完成支付。否则将扣除您参与该拍卖所缴纳的保证金或信用额度！';
                    }else{
                        $succmsg = '恭喜您以'.$info['nowprice'].'元拍到[【<a target="_blank" href="'.U('Auction/details',array('pid'=>$info['pid'],'aptitude'=>1)).'">'.$info['pname'].'</a>】请在'.date('Y-m-d H:i',$oData['deftime1']).'之前支付完成支付。否则将扣除您参与该拍卖所缴纳的保证金或信用额度！';
                    }
                    // 发送邮件通知
                    sendSms($info['uid'],'系统提示',$succmsg);
                // 竞拍成功提醒【
                    // 微信模板消息
                    $weimsg['tpl'] = 'success';
                    $weimsg['msg']=array(
                        "url"=>U('Member/mysucc','','html',true), 
                        "first"=>'您好，恭喜您竞拍成功！',
                        "remark"=>'请在'.date('Y-m-d H:i',$oData['deftime1']).'前支付订单！',
                        "keyword"=>array($info['pname'],$info['nowprice'])
                    );
                    // 短信提醒内容
                    if(mb_strlen($info['pname'],'utf-8')>15){
                        $newname = mb_substr($info['pname'],0,15,'utf-8').'...';
                    }else{
                        $newname = $info['pname'];
                    }
                    $notemsg = '恭喜您拍到“'.$newname.'”，请在'.date('Y-m-d H:i',$oData['deftime1']).'前支付订单！';
                    // 邮箱提醒内容
                    $mailmsg['title'] = "【竞拍成功】";
                    $mailmsg['msg'] = '您好：<br/><p>恭喜您以'.$info['nowprice'].'元拍到“<a target="_blank" href="'.U('Home/Auction/details',array('pid'=>$info['pid']),'html',true).'">'.$info['pname'].'</a>”，请在'.date('Y-m-d H:i',$oData['deftime1']).'前支付订单！</p><p>请<a target="_blank" href="'.U('Home/Login/index','','html',true).'">登陆</a>网站进行交易！</p>';
                    
                    // 提醒函数
                    sendRemind(M('Member'),M('Member_weixin'),$info,array($info['uid']),$webmsg,$weimsg,$notemsg,$mailmsg);
                // 竞拍成功提醒】
                // 订单状态提醒【
                    sendOrderRemind($order_no);
                // 订单状态提醒【
                }
            }
        }
        // 流拍处理
    }else{
        // 拍卖状态设置
        $aData['pid']=$info['pid'];
        if($info['uid']){
            // 未达到目标价流拍
            $aData['endstatus']=2;
            $info['endstatus']=2;
        }else{
            // 无人出价流拍
            $aData['endstatus']=3;
            $info['endstatus']=3;
        }
        $auction->save($aData);
        // 返还买家保证金
        return_pledge($info['pid'],0);
        // 返还卖家保证金
        unfreeze_seller_pledge($info['sellerid'],$info['pid'],'abortive');
      // echojson(array('status'=>2,'pname'=>$info['pname']));
    }
  }

  // 佣金显示转换
  function brokerShow($type,$brok){
    if($type==0){
        $barr = explode('/', $brok);
        $broker = $brok.'%';
    }else{
        $broker = $brok;
    }
    return $broker;
  }
  // 不进行跳转的地址
  function noback(){
    return array(
            U('Login/index','','html',true),
            U('Login/register','','html',true),
            U('Login/register',array('registerType'=>'email'),'html',true),
            U('Login/register',array('registerType'=>'mobile'),'html',true),
            U('Login/index','','',true),
            U('Login/register','','',true),
            U('Login/register',array('registerType'=>'email'),'html',true),
            U('Login/register',array('registerType'=>'mobile'),'html',true)
        );
  }
    function  log_result($file,$word){
        $fp = fopen($file,"a");
        flock($fp, LOCK_EX) ;
        fwrite($fp,"执行日期：".strftime("%Y-%m-%d-%H：%M：%S",time())."\n".$word."\n\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }

// 绑定账号、
    function bound($uid,$newuid,$how){
        $weixin = M('member_weixin');
        // 获取当前微信openid
        $openid = $weixin->where(array('uid'=>$uid))->getField('openid');
        // 绑定web版账号
        if($weixin->where(array('openid'=>$openid))->setField('uid',$newuid)){
            // 退出当前登陆后重新发送cookie
            $systemConfig = include APP_PATH . '/Common/Conf/systemConfig.php';
            $loginMarked = md5($systemConfig['TOKEN']['member_marked']);
            $shell = $newuid . md5($newacc['pwd'] . C('AUTH_CODE'));
            $_SESSION[$loginMarked] = $shell;
            $shell.= "_" . time();
            switch ($how) {
                case 'email':
                    $how = '邮箱';
                    break;
                case 'mobile':
                    $how = '手机号';
                    break;
            }
            // 发送cookie
            setcookie($loginMarked, $shell, time()+$systemConfig['TOKEN']['member_timeout'], "/");
            return array('status' => 1, 'info' => "绑定成功！您可以使用账号或".$how."登录网页版！",'url'=>U('Member/index'));
        }else{
           return array('status' => 0, 'info' => "绑定失败，请与管理员联系"); 
        }
    }
    // 出价方法拍卖会拍品操作 $succ：是否即时成交1：即时成交条件成立 0：即时成交不成立
    function meetingOperation($bidObj,$succ){
        //如果拍品属于拍卖会--------------------------------------------------------------
        if($bidObj['mid']!=0){
            $meeting_auction = M('Meeting_auction');
            $record = M('Auction_record');
            $bidMap = M('Auction');
            // 拍卖会出价次数加1
            $meeting_auction->where(array('mid'=>$bidObj['mid']))->setInc('mcount',1);
            $mtInfo = $meeting_auction->where(array('mid'=>$bidObj['mid']))->find();
            // 拍卖会中该拍品以后的拍品
            $mAuction = $bidMap->where(array('mid'=>$bidObj['mid'],'msort'=>array('gt',$bidObj['msort'])))->select();
            // 剩余结束时间
            $lSurplus = $bidObj['endtime']-time();
            $drive = array();
            // if流拍时间出价,拍品出价记录没有出价记录的话属于流拍时间出价
            if($record->where(array('pid'=>$bidObj['pid']))->count()==0){
                // $lSurplus流拍剩余时间
                // 即时成交条件不成立
                if($succ==0){
                    // 如果不是最后一件拍品
                    if($mAuction){
                        // 即时成交条件不成立，后边的拍品减去【剩余流拍时间】加上【拍卖时间】
                        foreach ($mAuction as $mak => $mav) {
                            $tmchange = array(
                                'pid' => $mav['pid'],
                                'starttime' => $mav['starttime']-$lSurplus+$mtInfo['bidtime'],
                                'endtime' => $mav['endtime']-$lSurplus+$mtInfo['bidtime']
                            );
                            // 检查下一件拍品是否有缓存如果有更新缓存
                            $redata = S(C('CACHE_FIX').'bid'.$mav['pid']);
                            if($redata){
                                $redata['endtime'] = $tmchange['endtime'];
                                $redata['starttime'] = $tmchange['starttime'];
                                S(C('CACHE_FIX').'bid'.$mav['pid'],$redata);
                            }
                            $bidMap->save($tmchange);
                            $tmchange['action'] = 'uptime';
                            $drive[]= $tmchange;
                        }
                    }
                    // 即时成交条件不成立，当前拍品设置为拍卖时间
                    $data['endtime']=  time()+$mtInfo['bidtime'];
                // 即时成交条件成立
                }else{
                    // 如果不是最后一件拍品
                    if($mAuction){
                        // 即时成交条件成立，后面拍品减去【剩余流拍时间】
                        foreach ($mAuction as $mak => $mav) {
                            $tmchange = array(
                                'pid' => $mav['pid'],
                                'starttime' => $mav['starttime']-$lSurplus,
                                'endtime' => $mav['endtime']-$lSurplus
                            );
                            // 检查下一件拍品是否有缓存如果有更新缓存
                            $redata = S(C('CACHE_FIX').'bid'.$mav['pid']);
                            if($redata){
                                $redata['endtime'] = $tmchange['endtime'];
                                $redata['starttime'] = $tmchange['starttime'];
                                S(C('CACHE_FIX').'bid'.$mav['pid'],$redata);
                            }
                            $bidMap->save($tmchange);
                            $tmchange['action'] = 'uptime';
                            $drive[]= $tmchange;
                        }
                    }
                    // 即时成交条件成立设置当前拍卖结束
                    $data['endtime']=time();
                }
            //else不是流拍时间出价，检查是否符合延时条件
            }else{
                // 即时成交条件不成立
                if($succ==0){
                    // 设定秒数
                    $noecha = $bidObj['endtime']-microtime(true);
                    // if符合延时条件 and 设定描述大于0
                    if($bidObj['steptime'] >= $noecha and $noecha>0){
                        // 如果不是最后一件拍品
                        if($mAuction){
                            foreach ($mAuction as $mak => $mav) {
                                $tmchange = array(
                                  'pid' => $mav['pid'],
                                  'starttime' => $mav['starttime']+$bidObj['deferred'],
                                  'endtime' => $mav['endtime']+$bidObj['deferred']
                                  );
                                // 检查下一件拍品是否有缓存如果有更新缓存
                                $redata = S(C('CACHE_FIX').'bid'.$mav['pid']);
                                if($redata){
                                    $redata['endtime'] = $tmchange['endtime'];
                                    $redata['starttime'] = $tmchange['starttime'];
                                    S(C('CACHE_FIX').'bid'.$mav['pid'],$redata);
                                }
                                // 写入当前循环的拍品
                                $bidMap->save($tmchange);
                                $tmchange['action'] = 'uptime';
                                $drive[]= $tmchange;
                            }
                        }
                        $data['endtime']=$bidObj['endtime']+$bidObj['deferred'];
                    //else不如和延时条件 
                    }else{
                        $data['endtime']=$bidObj['endtime'];  
                    }
                // 即时成交条件成立
                }else{
                    // $lSurplus结束剩余时间
                    // if不是最后一件拍品设置以后拍品开始时间结束时间都减去该拍品的结束【剩余时间】
                    if($mAuction){
                        foreach ($mAuction as $mak => $mav) {
                            $tmchange = array(
                              'pid' => $mav['pid'],
                              'starttime' => $mav['starttime']-$lSurplus,
                              'endtime' => $mav['endtime']-$lSurplus
                              );
                            // 检查下一件拍品是否有缓存如果有更新缓存
                            $redata = S(C('CACHE_FIX').'bid'.$mav['pid']);
                            if($redata){
                                $redata['endtime'] = $tmchange['endtime'];
                                $redata['starttime'] = $tmchange['starttime'];
                                S(C('CACHE_FIX').'bid'.$mav['pid'],$redata);
                            }
                            // 写入当前循环的拍品
                            $bidMap->save($tmchange);
                            $tmchange['action'] = 'uptime';
                            $drive[]= $tmchange;
                        }
                    }
                    $data['endtime']=time();
                }
            }
            // 更新拍卖会结束时间【
            if($mAuction){
                $mtendtime = $bidMap->where(array('mid'=>$bidObj['mid']))->order('endtime desc')->getField('endtime');
            }else{
                $mtendtime = $data['endtime'];
            }
            $meeting_auction->where(array('mid'=>$bidObj['mid']))->setField(array('endtime'=>$mtendtime));
            // 更新拍卖会结束时间】
        // 循环更新拍卖会后面拍品的缓存
        // 不是拍卖会的操作---------------------------------------------------------------
        }else{
            // 判断是否符合延时条件
            $noecha = $bidObj['endtime']-microtime(true);
            // 非即时成交
            if($succ==0){
                // 符合延时条件
                if($bidObj['steptime'] >= $noecha and $noecha>0){
                    $data['endtime']=$bidObj['endtime']+$bidObj['deferred'];
                }else{
                    $data['endtime']=$bidObj['endtime'];
                }
            // 即时成交
            }else{
                $data['endtime']=time();
            }
        }
        $rtdata=array('endtime'=>$data['endtime']);
        if(!empty($drive)){
            $rtdata['drive'] = $drive;
        }
        return $rtdata;
        
    //如果拍品属于拍卖会——end--------------------------------------------------------------
    }
 /*函数名称:get_code()
    *作用:取得随机字符串
    * 参数:
    1、(int)$length = 32 #随机字符长度
    2、(int)$mode = 0    #随机字符类型，
    0为大小写英文和数字,1为数字,2为小写字母,3为大写字母,
    4为大小写字母,5为大写字母和数字,6为小写字母和数字
    *返回:取得的字符串
*/
function get_code($length=32,$mode=0){
    switch ($mode){
            case '1':
                    $str='123456789';
                    break;
            case '2':
                    $str='abcdefghijklmnopqrstuvwxyz';
                    break;
            case '3':
                    $str='ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    break;
            case '4':
                    $str='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
                    break;
            case '5':
                    $str='ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
                    break;
            case '6':
                    $str='abcdefghijklmnopqrstuvwxyz1234567890';
                    break;
            default:
                    $str='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
                    break;
    }
    $checkstr='';
    $len=strlen($str)-1;
    for ($i=0;$i<$length;$i++){
        //$num=rand(0,$len);//产生一个0到$len之间的随机数
        $num=mt_rand(0,$len);//产生一个0到$len之间的随机数
        $checkstr.=$str[$num];  
    }
    return $checkstr;
}

// 验证手机号和邮箱是否符合固定条件
function verifyME($tp,$how,$adr,$uid){
    if($tp=='email'){
        $vid = M('member')->where(array('email'=>$adr,'verify_email'=>1))->getField('uid');
        if($how=='findpwd'){
            if($vid){
                return array('status'=>1);
            }else{
                return array('status' => 0, 'info' => "该邮箱未进行过认证，无法进行找回密码操作！");
            }
        }
        if ($how=='verify') {
            if (empty($vid) || $vid==$uid) {
                 return array('status'=>1);
            }else{
                return array('status' => 0, 'info' => "该邮箱已被占用请更换！");
            }
        }
        if($how=='register'){
            if($vid){
                return array('status' => 0, 'info' => "您的邮箱已被占用请更换！");
            }else{
                return array('status'=>1);
            }
        }
    }
    if($tp=='mobile'){
        $vid = M('member')->where(array('mobile'=>$adr,'verify_mobile'=>1))->getField('uid');
        if($how=='findpwd'){
            if($vid){
                return array('status'=>1);
            }else{
                return array('status' => 0, 'info' => "该手机号未进行过认证，无法进行找回密码操作！");
            }
        }
        if ($how=='verify') {
            if (empty($vid) || $vid==$uid) {
                 return array('status'=>1);
            }else{
                return array('status' => 0, 'info' => "手机号已被占用请更换！");
            }
        }
        if($how=='register'){
            if($vid){
                return array('status' => 0, 'info' => "您的手机号已被占用请更换！");
            }else{
                return array('status'=>1);
            }
        }
    }
}

// 出价昵称隐藏处理
function nickdis($str){
    if(C('Auction.nikdis')){
        return cut_str($str, 1, 0).'**';
    }else{
        return $str;
    }
    
}
// 字符串截取函数$string【字符】, $sublen【截取长度】, $start = 0【起始位】, $code = 'UTF-8'【编码格式】
function cut_str($string, $sublen, $start = 0, $code = 'UTF-8'){
    if($code == 'UTF-8'){
        $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
        preg_match_all($pa, $string, $t_string);

        if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen));
        return join('', array_slice($t_string[0], $start, $sublen));
    }else{
        $start = $start*2;
        $sublen = $sublen*2;
        $strlen = strlen($string);
        $tmpstr = '';

        for($i=0; $i< $strlen; $i++){
            if($i>=$start && $i< ($start+$sublen)){
                if(ord(substr($string, $i, 1))>129){
                    $tmpstr.= substr($string, $i, 2);
                }else{
                    $tmpstr.= substr($string, $i, 1);
                }
            }
            if(ord(substr($string, $i, 1))>129) $i++;
        }
        //if(strlen($tmpstr)< $strlen ) $tmpstr.= "...";
        return $tmpstr;
    }
}
 




?>
