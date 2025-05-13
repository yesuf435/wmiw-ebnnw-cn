<?php
namespace Home\Widget;
use Think\Controller;
class OncooWidget extends Controller {
    // 主导航上全部分类实力商家
    public function allcate($cid,$size){ 
        // 全部分类和实例商家的获取【
            // 读取缓存
            if (!S(C('CACHE_FIX').'_allcate')) { 
                $auction = D('Auction');
                $gCategory = M("goods_category");
                $member = M('Member');
                $cat = new \Org\Util\Category();
                $clist = $gCategory->where(array('pid'=>0))->order('sort desc')->select();
                foreach ($clist as $clk => $clv) {
                    $clist[$clk]['children'] = $gCategory->where(array('pid'=>$clv['cid']))->order('sort desc')->select();
                    foreach ($clist[$clk]['children'] as $clck => $clcv) {
                        $clist[$clk]['children'][$clck]['children'] = $gCategory->where(array('pid'=>$clcv['cid']))->order('sort desc')->select();
                    }
                    // 实力商家的获取【实力商家为该频道下商品成交数量最多的用户集合
                    // 获取该频道下的子类cid
                    $cate = $gCategory->select();
                    $gCidarr = $cat->getChildsId($cate,$clv['cid']);
                    //加入频道cid
                    array_unshift($gCidarr,$clv['cid']);
                    // 查询成交拍品的sellerid集合
                    $selUidarr = $auction->where(array('cid'=>array('in',$gCidarr)))->getField('sellerid',true);
                    // 统计每个用户的成交数量;[此函数为统计某值在数组中的数量]
                    $arsortSuid = array_count_values($selUidarr);
                    // 根据成交数量从多到少排序用户sellerid,[此函数为根据值对数组进行排序，不改变键值]
                    arsort($arsortSuid);
                    // 去除重复
                    $selUidarr = array_flip(array_flip($selUidarr));
                    // 获取20个实力商家
                    $seller =$member->where(array('uid'=>array('in',$selUidarr)))->field('uid,organization')->limit(20)->select();
                    // 给商家进行排序
                    foreach ($seller as $sk => $sv) {
                        $seller[$sk]['count'] = $arsortSuid[$sv['uid']];
                    }
                    // 按照降序根据成交拍品数量排序商家
                    $seller = my_sort($seller,'count',SORT_DESC,SORT_NUMERIC);
                    // 获取频道内20个实例商家的信息
                    $clist[$clk]['strength'] = $seller;
                    // 实力商家的获取】
                }
                // 后台编辑分类或者缓存过期会清空该缓存
                S(C('CACHE_FIX').'_allcate',$clist,7200);
            }else{
                $clist = S(C('CACHE_FIX').'_allcate');
            }
        // 全部分类和实例商家的获取】
        $this->clist=$clist;
        $this->display('Common:navigation_allcate');
    }

    // 用户中心右侧今日新品和热拍推荐
    public function member_seller_right(){
        $auction = D('Auction');
        // 今日新品【
            // 开始时间大于今天未结束的拍品按照开始时间排序读取5条
            $nwhere = array(
                'starttime'=>array('gt',strtotime(date('Y-m-d',time()))),
                'endtime'=>array('gt',time()),
                'hide'=>0);
            $newbid = $auction->where($nwhere)->limit(5)->order('starttime asc')->field('pid,nowprice,pictures')->select();
            $this->newbid=$newbid;
        // 今日新品】
        // 热拍推荐【
            $ws = bidType('biding');
            $htchere = $ws['bidType'];
            $htcbid = $auction->where($htchere)->limit(5)->order('recommend desc, bidcount desc')->field('pid,nowprice,pictures')->select();
            $this->htcbid=$htcbid;
        // 热拍推荐】
            $this->display('Common:member_seller_right');
    }

    // 专场热拍
    public function special_hot($limit){
        // 为了页面美观，仅获取专场与显示专场数量相同的热拍商品
        $hwhere = bidType('biding',1);
        $ingid = M('special_auction')->where($hwhere['bidType'])->getField('sid',true);
        $hotbid = D('Auction')->where(array('sid'=>array('in',$ingid)))->order('bidcount desc,clcount desc')->limit($limit)->select();
        $this->hotbid=$hotbid;
        $this->display('Special:special_hot');
    }
}