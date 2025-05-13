<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class SpecialController extends CommonController {
    /**
    * 专场首页
    * @return [type] [description]
    */
    public function index(){
        $typ = I('get.typ');
        $special = D('SpecialAuction');
        $armg = array('bcount'=>array('gt',0));
        // 读取全部在拍和即将开拍的专场，专场内拍品数不能为0
        if (!$typ) {
            // 在拍专场
            $ingData = array();
            $ingType = bidType('biding',1);
            $ingData = $special->listSpecial(array_merge($ingType['bidType'],$armg),'endtime asc',100,1);
            // 未开始的专场
            $futData = array();
            $futType = bidType('future',1);
            $futData = $special->listSpecial(array_merge($futType['bidType'],$armg),'starttime asc',100,1);
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
            $redata = $special = $special->listSpecial(array_merge($bidTp['bidType'],$armg),'endtime desc',20,1);
            $this->saytyp=$bidTp['saytyp'];
            $this->page=$redata['page'];
            $count = $redata['count'];
            $list = $redata['list'];
        }
        $this->list=$list;
        // 为了页面美观，仅获取专场与显示专场数量相同的热拍商品
        $this->limit = count($list);
        $this->display();
    }
    // 专场拍品列表---------------------------------------------------
    public function speul(){
        $sid = I('get.sid');
        $auction = D('Auction');
        // 专场信息【
        $special = D('special_auction');
        $info = $special->where(array('sid'=>$sid))->find();
        // 微信分享的图片
        $this->shimg = C('WEB_ROOT'). str_replace('./', '', C('UPLOADS_PICPATH').$info['spicture']);
        if ($info['endtime']<=time()) {
            // 结束
            $info['status']='bidend';
            // 成交总额统计
            $info['countprc'] = array_sum($auction->where(array('sid'=>$sid,'endstatus'=>1))->getField('nowprice',true));
            // 微信分享的标题
            $share_title = $info['sname'].' '.'总成交价:'.wipezero($info['countprc']);
        }else{ 
            // 拍品数量统计
            $info['count'] = $auction->where(array('sid'=>$sid))->count();
            // 围观次数统计
            $info['clcount'] = $auction->where(array('sid'=>$sid))->sum('clcount');
            // 出价次数统计
            $info['bidcount'] = $auction->where(array('sid'=>$sid))->sum('bidcount',true);
            if ($info['starttime']>time()) {
                // 未开始
                $info['status']='future';
                // 微信分享标题
                $share_title = $info['sname'].' '.$info['clcount'].'次围观'.' '.date('m月d日 H:i',$info['starttime']).'开拍';
            }elseif($info['endtime']>time()&&$info['starttime']<=time()){
                // 进行中
                $info['status']='biding';
                // 微信分享的标题
                $share_title = $info['sname'].' '.$info['bidcount'].'次出价'.' '.'至'.date('m月d日 H:i',$info['endtime']).'结束';
            }
        }
        $this->share_title=$share_title;
        
        // 专场信息】
        // 专场内拍品列表【
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
        $sbid_od .='endtime desc ,pid desc';
        // 分页配置
        $where['sid']=$sid;
        $where['hide']=0;

        $redata = $auction->auctionList($where,$sbid_od);
        $this->list = $redata['list'];
        $this->page = $redata['page'];
        // 专场内拍品列表】
        $this->info=$info;
        $this->display();
    }


    // 获取当前专场时间-------------------------------------------------
    public function ajaxspecialgettime(){
        session_write_close();
        $rtime = M('special_auction')->where(array('sid'=>I('post.sid')))->field(array('starttime','endtime'))->find();
        if(I('post.ck')=='cke'){
            $cdata['cktime'] = $rtime['endtime'];
        }elseif(I('post.ck')=='cks'){
            $cdata['cktime'] = $rtime['starttime'];
        }
        $cdata['nowtime'] = time();
        echojson($cdata);
    }

    // --------专场时间结束提交地址
    public function checkspecialsucc(){
        session_write_close();
        $sai = M('special_auction')->where('sid ='.I('post.sid'))->find();
        if(I('post.ck')=='cke'){
            $cktime = $sai['endtime'];
        }elseif(I('post.ck')=='cks'){
            $cktime = $sai['starttime'];
        }
        if($cktime<time()){
            echojson(array('status'=>1,'info'=>'专场《'.$sai['sname'].'》已结束！'));
        }else{
            echojson(array('status'=>0,'nowtime'=>time(),'cktime'=>$cktime));
        }
    }
}