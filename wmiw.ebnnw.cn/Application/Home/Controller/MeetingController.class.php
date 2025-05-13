<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class MeetingController extends CommonController {
    /**
    * 拍卖会首页
    * @return [type] [description]
    */
    public function index(){
        $typ = I('get.typ');
        $meeting = D('MeetingAuction');
        $armg = array('bcount'=>array('gt',0));
        // 读取全部在拍和即将开拍的拍卖会，拍卖会内拍品数不能为0
        if (!$typ) {
            // 在拍拍卖会
            $ingData = array();
            $ingType = bidType('biding',1);
            $ingData = $meeting->listMeeting(array_merge($ingType['bidType'],$armg),'endtime asc',100,1);
            // 未开始的拍卖会
            $futData = array();
            $futType = bidType('future',1);
            $futData = $meeting->listMeeting(array_merge($futType['bidType'],$armg),'starttime asc',100,1);
            $count = $ingData['count']+$futData['count'];
            if (!empty($ingData['list']) && !empty($futData['list'])) {
                $list = array_merge($ingData['list'],$futData['list']);
            }elseif (!empty($ingData['list'])) {
                $list = $ingData['list'];
            }elseif (!empty($futData['list'])) {
                $list = $futData['list'];
            }
        }else{
            $bidTp = bidType(I('get.typ'),1);
            $redata = $meeting = $meeting->listMeeting(array_merge($bidTp['bidType'],$armg),'endtime desc',20,1);
            $this->saytyp=$bidTp['saytyp'];
            $this->page=$redata['page'];
            $count = $redata['count'];
            $list = $redata['list'];
        }
        $this->list=$list;
        // 为了页面美观，仅获取拍卖会与显示拍卖会数量相同的热拍商品
        $limit = count($list);
        $hwhere = bidType('biding',1);
        $ingid = M('meeting_auction')->where($hwhere['bidType'])->getField('mid',true);
        $hotbid = D('Auction')->where(array('mid'=>array('in',$ingid)))->order('bidcount desc,clcount desc')->limit($hotlim )->select();
        $this->hotbid=$hotbid;
        $this->display();
    }
    // 拍卖会拍品列表---------------------------------------------------
    public function meetul(){
        $mid = I('get.mid');
        $auction = D('Auction');
        // 拍卖会信息【
        $meeting = D('meeting_auction');
        $info = $meeting->where(array('mid'=>$mid))->find();
        // 分享的图片
        $this->shimg = C('WEB_ROOT'). str_replace('./', '', C('UPLOADS_PICPATH').$info['mpicture']);
        if ($info['endtime']<=time()) {
            // 结束
            $info['status']='bidend';
            // 成交总额统计
            $info['countprc'] = array_sum($auction->where(array('mid'=>$mid,'endstatus'=>1))->getField('nowprice',true));
            // 微信分享的标题
            $share_title = $info['mname'].' '.'总成交价:'.wipezero($info['countprc']);
        }else{ 
            // 拍品数量统计
            $info['count'] = $auction->where(array('mid'=>$mid))->count();
            // 围观次数统计
            $info['clcount'] = $auction->where(array('mid'=>$mid))->sum('clcount');
            // 出价次数统计
            $info['bidcount'] = $auction->where(array('mid'=>$mid))->sum('bidcount',true);
            if ($info['starttime']>time()) {
                // 未开始
                $info['status']='future';
                // 微信分享标题
                $share_title = $info['mname'].' '.$info['clcount'].'次围观'.' '.date('m月d日 H:i',$info['starttime']).'开拍';
            }elseif($info['endtime']>time()&&$info['starttime']<=time()){
                // 进行中
                $info['status']='biding';
                // 微信分享的标题
                $share_title = $info['mname'].' '.$info['bidcount'].'次出价'.' '.date('m月d日 H:i',$info['starttime']).'开拍';
            }
        }
        $this->share_title=$share_title;
        // 拍卖会信息】
        // 拍卖会内拍品列表【
        // 筛选条件处理
        if(I('get.bc')!=''){
            if(I('get.bc')==1){
                $sbid_od = 'nowprice desc ,';
                $info['bc']=0;
            }elseif (I('get.bc')==0) {
                $sbid_od = 'nowprice asc ,';
                $info['bc']=1;
            }
            $info['pd']='bc';
        }else{
            $info['bc']=0;
        }
        if(I('get.po')!=''){
            if(I('get.po')==1){
                $sbid_od = 'bidcount desc ,';
                $info['po']=0;
            }elseif (I('get.po')==0) {
                $sbid_od = 'bidcount asc ,';
                $info['po']=1;
            }
            $info['pd']='po';
        }else{
            $info['po']=0;
        }
        $sbid_od .='msort';
        // 分页配置
        $where['mid']=$mid;
        $where['hide']=0;
        $redata = $auction->auctionList($where,$sbid_od);
        $this->list = $redata['list'];
        $this->page = $redata['page'];
        // 拍卖会内拍品列表】
        
        $this->info=$info;
        $this->display();
    }
    // 结论书
    public function conclusion(){
        $mid = I('get.mid');
        $auction =D('Auction');
        $member = M('member');
        $meet = M('meeting_auction')->where(array('mid'=>$mid))->find();
        // 拍品数量
        $meet['acount'] = $auction->where(array('mid'=>$mid))->count();
        // 成交数量
        $meet['acccount'] = $auction->where(array('mid'=>$mid,'endstatus'=>1))->count();
        // 流拍数量
        $meet['loscount'] = $meet['acount']-$meet['acccount'];
        $meet['accprice'] = array_sum($auction->where(array('mid'=>$mid,'endstatus'=>1))->getField('nowprice',true)) ;

        $alist = $auction->where(array('mid'=>$mid))->order('msort asc')->select();
        foreach ($alist as $ak => $av) {
            $alist[$ak]['nickname'] = $member->where(array('uid'=>$av['uid']))->getField('nickname');
            $alist[$ak]['premium'] = sprintf("%.2f", ($av['nowprice']-$av['price'])/$av['price']*100);
        }
        $this->meet=$meet;
        $this->alist=$alist;
        $this->display();
    }


    // 获取当前拍卖会时间-------------------------------------------------
    public function ajaxmeetinggettime(){
        session_write_close();
        $rtime = M('meeting_auction')->where(array('mid'=>I('post.mid')))->field(array('starttime','endtime'))->find();
        if(I('post.ck')=='cke'){
            $cdata['cktime'] = $rtime['endtime'];
        }elseif(I('post.ck')=='cks'){
            $cdata['cktime'] = $rtime['starttime'];
        }
        $cdata['nowtime'] = time();
        echojson($cdata);
    }

    // --------拍卖会时间结束提交地址
    public function checkmeetingsucc(){
        session_write_close();
        $sai = M('meeting_auction')->where('mid ='.I('post.mid'))->find();
        if(I('post.ck')=='cke'){
            $cktime = $sai['endtime'];
        }elseif(I('post.ck')=='cks'){
            $cktime = $sai['starttime'];
        }
        if($cktime<time()){
            echojson(array('status'=>1,'info'=>'拍卖会《'.$sai['sname'].'》已结束！'));
        }else{
            echojson(array('status'=>0,'nowtime'=>time(),'cktime'=>$cktime));
        }
    }
}