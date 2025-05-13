<?php
namespace Home\Controller;
use Think\Controller;
class ShopController extends CommonController {
    public function index(){
        $sellerid = I('get.sellerid');
        
        if($sellerid){
            $type = I('get.type')?I('get.type'):'all';
            // 频道
            $channel = I('get.channel')?I('get.channel'):'auction';
            $this->channel = $channel;
            $bidMap = D('Auction');
            $attention = M('attention_seller');
            $member = M('member');
            $nowTime = time();
            // 商家信息
            $seller = $member->where(array('uid'=>$sellerid))->field('uid,account,organization,avatar,reg_date,score')->find();
            $seller['leval'] = getlevel($seller['score']);
            $credit_score = getstarval(M('goods_evaluate'),array('sellerid'=>$sellerid));
            $this->seller=$seller;
            $this->credit_score=$credit_score;

            // 登录用户进行验证
            $uid = $this->cUid;
            if ($uid) {
                // 分配关注状态
                if($attention->where(array('uid'=>$uid,'sellerid'=>$sellerid))->count()){$usgz=1;}else{$usgz=0;}
                $this->usgz=$usgz;
                // 分配屏蔽状态
                $blacklist = M('blacklist');
                if ($blacklist->where(array('uid'=>$uid,'xid'=>$sellerid,'selbuy'=>'sel'))->count()>0) {
                    $black['sel'] = 1;
                }else{
                    $black['sel'] = 0;
                }
                if ($blacklist->where(array('uid'=>$uid,'xid'=>$sellerid,'selbuy'=>'buy'))->count()>0) {
                    $black['buy'] = 1;
                }else{
                    $black['buy'] = 0;
                }
                $this->black=$black;
                // 买家拉黑卖家的话不显示
                $xUidarr = blackuser($uid);
                if (in_array($sellerid,$xUidarr)) {
                    $this->error('卖家不存在');
                }
            }
            
            // fans统计
            $fanscount = $attention->where(array('sellerid'=>$sellerid))->count();
            $this->fanscount=$fanscount;
            // 读取拍品信息
            $where = array();
            // 获取状态的查询条件和分配
            if (in_array($type, array('future','bidend','biding'))) {
                $ws = bidType($type);
                $where = $ws['bidType'];
            }
            $this->type=$type;

            $selw = array('sellerid'=>$sellerid);
            // 未开拍条件
            $wtws = bidType('future');
            $wait_where = array_merge($wtws['bidType'],$selw);
            // 已结束统计
            $edws = bidType('bidend');
            $end_where = array_merge($edws['bidType'],$selw);
            // 开拍统计
            $igws = bidType('biding');
            $ing_where = array_merge($igws['bidType'],$selw);
            // 根据频道获取板块数据

            switch ($channel) {
                case 'special':
                    $special = D('SpecialAuction');
                    // 未开拍统计
                    $count['wait'] = $special->where($wait_where)->count();
                    // 结束统计
                    $count['end'] = $special->where($end_where)->count();
                    // 开拍统计
                    $count['ing'] = $special->where($ing_where)->count();
                    // 统计全部
                    $count['all'] = $count['wait']+$count['end']+$count['ing'];
                    // 拍品列表
                    $rdata = $special->listSpecial(array_merge($where,$selw),'endtime desc,starttime,sid desc',C('PAGE_SIZE'),1);
                    $this->limit = $rdata['count'];
                    break;
                case 'meeting':
                    $meeting = D('MeetingAuction');
                    // 未开拍统计
                    $count['wait'] = $meeting->where($wait_where)->count();
                    // 结束统计
                    $count['end'] = $meeting->where($end_where)->count();
                    // 开拍统计
                    $count['ing'] = $meeting->where($ing_where)->count();
                    // 统计全部
                    $count['all'] = $count['wait']+$count['end']+$count['ing'];
                    // 拍品列表
                    $rdata = $meeting->listMeeting(array_merge($where,$selw),'endtime desc,starttime,mid desc',C('PAGE_SIZE'),1);
                    break;
                default:
                    // 未开拍统计
                    $count['wait'] = $bidMap->where($wait_where)->count();
                    // 结束统计
                    $count['end'] = $bidMap->where($end_where)->count();
                    // 开拍统计
                    $count['ing'] = $bidMap->where($ing_where)->count();
                    // 统计全部
                    $count['all'] = $count['wait']+$count['end']+$count['ing'];
                    // 拍品列表
                    $rdata = $bidMap->auctionList(array_merge($where,$selw),'endtime desc,starttime,pid desc',C('PAGE_SIZE'));
                    break;
            }
            // 结束统计计算成交率
            $cjc = $bidMap->where(array('sellerid'=>$sellerid,'endstatus'=>1,'hide'=>0))->count();
            $yjs = $bidMap->where(array('sellerid'=>$sellerid,'endstatus'=>array('neq',0),'hide'=>0))->count();
            $count['cjl'] = round($cjc/$yjs*100).'%';
            $this->count=$count;
            // 分配正在拍卖拍品到模板
            $this->list = $rdata['list'];
            // 分配分页到模板
            $this->page = $rdata['page']; 

            $this->sellerid=$sellerid;
            $this->display();
        }else{
            $this->error('不存在的卖家');
        }
        
    }

    // 优质好店
    public function haodian(){
        $member = M('Member');
        $auction = D('Auction');
        $gt=bidType('biding');
        // 未结束的商品
        $sidarr = $auction->where($gt['bidType'])->getField('sellerid',true);
        $sidshow = array_count_values ($sidarr);
        foreach ($sidshow as $sk => $sv) {
            if ($sv>=3) {
                $sechsid[] = $sk;
            }
        }
        // 卖家名称不为空
        $where['organization']=array('neq','');
        // 不显示屏蔽的卖家
        if ($xUidarr = blackuser($this->cUid)) {
            $sechsid = array_diff($sechsid,$xUidarr);
        }
        $where['uid']=array('in',$sechsid);
        $count = $member->where($where)->count();
        $pConf = page($count,C('PAGE_SIZE'));
        $list = $member->where($where)->limit($pConf['first'].','.$pConf['list'])->order('score desc')->field('organization,uid,score,avatar_sel')->select();
        foreach ($list as $lk => $lv) {
            $list[$lk]['leval'] = getlevel($lv['score']);
            $list[$lk]['auction'] = $auction->where($gt['bidType'])->where(array('sellerid'=>$lv['uid']))->limit(3)->field('pid,pname,pictures')->select();
        }
        $this->list=$list;
        $this->page=$pConf['show'];
        $this->display();
    }



    // 商家页面关注商家
    public function attention(){
        if(IS_POST){
            if($this->cUid){
                if($this->cUid!=I('post.sellerid')){
                    $att=M('attention_seller');
                    $data = array(
                        'sellerid'=>I('post.sellerid'),
                        'uid'=>$this->cUid
                        );
                    if(!$att->where($data)->count()){
                        $data['time'] = time();
                        if($att->add($data)){
                            echojson(array('status'=>1,'msg'=>'已关注店铺'));
                        }
                    }else{
                        if($att->where($data)->delete()){
                            echojson(array('status'=>1,'msg'=>'已取消关注店铺'));
                        }
                    }
                }else{
                    echojson(array('status'=>0,'msg'=>'您不能关注您自己'));
                }
                
            }else{
                echojson(array('status'=>0,'msg'=>'您没有登陆，请登录后进行设置！'));
            }
        }
    }
}