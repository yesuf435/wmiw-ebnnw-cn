<?php
namespace Admin\Model;
use Think\Model\ViewModel;
use Com\Wechat;
use Com\WechatAuth;
class AuctionModel extends ViewModel {
    Protected $viewFields = array(
        'Auction' => array(
            '*',
            '_type' => 'LEFT'
            ),
        'Goods' => array(
            'cid','aid','keywords','filtrate','pictures','prov','city','district','description','content','sellerid',
            '_on' => 'Auction.gid = Goods.id',
            '_type' => 'LEFT'
            ),
        'Member' => array(
            'organization','intro','score','account',
            '_on' => 'Goods.sellerid = Member.uid',
            '_type' => 'LEFT'
            ),
        'Special_auction' => array(
            'spledge','sname',
            '_on' => 'Auction.sid = Special_auction.sid',
            '_type' => 'LEFT'
            ),
        'Meeting_auction' => array(
            'mpledge','mname',
            '_on' => 'Auction.mid = Meeting_auction.mid'
            )
    );
    
    /**
     * [listAuctionMore description]
     * @param  integer $firstRow [分页起始]
     * @param  integer $listRows [分页结束]
     * @param  [type]  $where    [筛选条件]
     * @return [type]            [拍品列表]
     */
    public function listAuction($where,$od,$size) {
        $count = $this->where($where)->count();
        $pConf = page($count,$size);
        $list = $this->limit($pConf['first'].','.$pConf['list'])->order($od)->where($where)->select();
        $member = M('member');
        $goods_order = M('goods_order');
        $auction_record = M('auction_record');
         
        $cate = M("Goods_category")->select();
        $cids = array_reduce($cate, create_function('$v,$w', '$v[$w["cid"]]=$w["name"];return $v;'));

        $filt = M("goods_filtrate")->select();
        $fids = array_reduce($filt, create_function('$v,$w', '$v[$w["fid"]]=$w["name"];return $v;'));
        $cat = new \Org\Util\Category();
        foreach ($list as $k => $v) {
            $list[$k]['cidName'] = $cids[$v['cid']];
            $uPath = $cat->getPath($cate,$v['cid']);
            $list[$k]['pidName'] = $uPath[0]['name'];
            // 检查成交的数量如果不为0，则允许重新发布拍卖
            $repai = $this->where(array('gid'=>$v['gid'],'endstatus'=>array('in',array(0,1))))->count();
            $list[$k]['repai'] = $repai;
            // 拍品状态
            $ntime = time();
            if($v['starttime']<=$ntime&&$v['endtime']>=$ntime){
                $list[$k]['st'] = '在拍';
                $list[$k]['sten'] = 'biding';
            }elseif ($v['endtime']<$ntime) {
                $list[$k]['sten'] = 'bidend';
                if($v['endstatus']==1){
                    $list[$k]['st'] = '成交';
                    $list[$k]['endst'] = 'chengjiao';
                    $list[$k]['order_no'] = $goods_order->where(array('gid'=>$v['pid'],'sellerid'=>$v['sellerid'],'uid'=>$v['uid']))->getField('order_no');
                }elseif ($v['endstatus']==2) {
                    $list[$k]['endst'] = 'liupai';
                    $list[$k]['st'] = '流拍';
                }elseif ($v['endstatus']==3){
                    $list[$k]['endst'] = 'wurenliupai';
                    $list[$k]['st'] = '无人出价流拍';
                }elseif ($v['endstatus']==4) {
                    $list[$k]['endst'] = 'chepai';
                    $list[$k]['st'] = '撤拍';
                }
            }elseif ($v['starttime']>$ntime) {
                $list[$k]['st'] = '待拍';
                $list[$k]['sten'] = 'future';
            }
            if ($auction_record->where(array('pid'=>$v['pid']))->count()||$v['endstatus']==0) {
                $list[$k]['bid'] = 0;
            }else{
                $list[$k]['bid'] = 1;
            }
            if (!empty($v['filtrate'])) {
                $filtarr = explode('_', $v['filtrate']);
                foreach ($filtarr as $fk => $fv) {
                    $filtarr[$fk]=$fids[$fv];
                }
                $list[$k]['filtrate'] = $filtarr;
            }
            
        }
        return array('list'=>$list,'page'=>$pConf['show'],'count'=>$count);
    }
    // 添加编辑拍品
    public function addEdit($act){
        $data = I('post.data');

        $auction = M('Auction');
        if($data['type']==1&&$data['stepsize_type']==0){
            return array('status' => 0, 'info' => '（竞标模式）下《价格浮动》应该设置为《定额》');
            exit;
        }
        if($data['succtype']==1){
            if($data['type']==1){
                return array('status' => 0, 'info' => '（竞标模式）下，《成交模式》必须为《普通模式》');
                exit;
            }
            if($data['succprice']<$data['price']){
                return array('status' => 0, 'info' => '即时成交模式，成交价格必须大于等于保留价');
                exit;
            }
        }

        // if($act=='edit'){
        //     $end = $auction->where(array('pid'=>$data['pid']))->find();

        //     if(($end['endtime']<time()&&$end['endstatus']!=3)||$end['starttime']<time()){
        //         return array('status' => 0, 'info' => '只有无人出价流拍、和未开始的拍卖才允许编辑！');
        //         exit;
        //     }
        // }

        // 价格浮动方式
        if($data['stepsize_type'] == 0){
            if ($data['stepsize_ratio_r']<=0) {
                return array('status' => 0, 'info' => '【初始浮动】最低不能小于等于0哦！');
                exit;
            }
            if ($data['stepsize_ratio_r']<=0) {
                return array('status' => 0, 'info' => '【最高浮动】最低不能小于等于0哦！');
                exit;
            }
            $data['stepsize'] = $data['stepsize_ratio'].','.$data['stepsize_ratio_r'].','.$data['stepsize_ratio_s'].','.$data['stepsize_ratio_t'];
            
        }elseif ($data['stepsize_type'] == 1) {
            if ($data['step_fixation']<=0) {
                return array('status' => 0, 'info' => '【价格浮动】最低不能小于等于0哦！');
                exit;
            }else{
                $data['stepsize'] = $data['step_fixation'];
                unset($data['step_fixation']);
            }
            
        }
        unset($data['stepsize_ratio'],$data['stepsize_ratio_r'],$data['stepsize_ratio_s'],$data['stepsize_ratio_t'],$data['step_fixation']);
        $bidcof=C('Auction');
        // 卖家佣金
        $data['broker'] =$bidcof['broker_'.$bidcof['broker_type']];
        if ($data['broker']==''||$data['broker']<0) {
            $data['broker'] = 0;
        }
        $data['broker_type'] = $bidcof['broker_type'];
        if ($bidcof['broker_type']=='') {
            $data['broker_type'] = 'fixation';
        }
        // 买家佣金
        $data['broker_buy'] =$bidcof['broker_buy_'.$bidcof['broker_buy_type']];
        if ($data['broker_buy']==''||$data['broker_buy']<0) {
            $data['broker_buy'] = 0;
        }
        $data['broker_buy_type'] = $bidcof['broker_buy_type'];
        if ($bidcof['broker_buy_type']=='') {
            $data['broker_buy_type'] = 'fixation';
        }
        $data['nowprice']=$data['onset'];
        $data['endstatus']=0;
        // 转存并删除该字段
        $to = $data['to'];
        unset( $data['to']);
        // 发布到专场----------------------------------------------------------
        if($to=='zc'){
            // 判断发布商品的状态进入相应版块
            if(!$data['sid']){
                return array('status' => 0, 'info' => '请选择专场','url'=>__SELF__);
                exit;
            }
            $special = M('special_auction');
            $stat = $special->where(array('sid'=>$data['sid']))->find();
            if(!$stat){
                return array('status' => 0, 'info' => '专场不存在','url'=>__SELF__);
                exit;
            }
            $data['starttime']=$stat['starttime'];
            $data['endtime']=$stat['endtime'];
            if($data['starttime']<=time()){
                $typ='biding';
            }else{
                $typ='future';
            }
            if($stat['special_pledge_type']==1){
                $data['pattern']=2;
            }else{
                $data['pattern']=1;
            }
        // 发布到拍卖会------------------------------------------------------------------------------
        }elseif($to=='pmh'){
            // 选择和判断专场不存在
            if(!$data['mid']){
                return array('status' => 0, 'info' => '请选择拍卖会','url'=>__SELF__);
                exit;
            }
            $meeting = M('meeting_auction');
            // 发布到拍卖会--------------------------------------------------------
            $stat =$meeting->where(array('mid'=>$data['mid']))->find();
            if(!$stat){
                return array('status' => 0, 'info' => '拍卖会不存在','url'=>__SELF__);
                exit;
            }
            $typ='future';
            // 处理编号
            if($act=='add'){
                $mpidarr = $auction->where(array('mid'=>$data['mid']))->order('pid asc')->getField('pid',true);
                if ($mpidarr) {
                    foreach ($mpidarr as $mpk => $mpv) {
                        $auction->where(array('pid'=>$mpv))->setField('msort',$mpk+1);
                    }
                }
                $data['msort'] = count($mpidarr)+1;
            }
            // 拍品最早开拍/结束时间
            if($data['msort']==1){
                $data['starttime'] = $stat['starttime'];
                $data['endtime'] = $stat['starttime']+$stat['losetime'];
            }else{
                $lastbid = $auction->where(array('mid'=>$data['mid']))->order('msort desc')->find();
                $data['starttime'] = $lastbid['endtime'] + $stat['intervaltime'];
                $data['endtime'] =$data['starttime']+$stat['losetime'];

            }           
            if($stat['meeting_pledge_type']==1){
                $data['pattern']=4;
            }else{
                $data['pattern']=3;
            }
        }else{
        // 发布到单品拍------------------------------------------------------------------------
            $data['starttime']=strtotime($data['starttime']);
            $data['endtime']=strtotime($data['endtime']);
            // 判断拍品时间和当前时间
            if($data['endtime']<time()){
                return array('status' => 0, 'info' => '拍品结束时间应该大于当前时间','url'=>__SELF__);
                exit;
            }
            // 判断发布商品的状态进入相应版块
            if($data['starttime']<=time()){
                $typ='biding';
            }else{
                $typ='future';
            }
        }
        $data['time'] = time();
        if($act=='edit'){
            if (M('auction_record')->where(array('pid'=>$data['pid']))->count()==0) {
                if($yn = $auction->save($data)){
                    // 更新这个拍品的缓存【
                    $redata = S(C('CACHE_FIX').'bid'.$data['pid']);
                    if($redata){
                        $redata['pname'] = $data['pname'];
                        $redata['price'] = $data['price'];
                        $redata['nowprice'] = $data['nowprice'];
                        $redata['starttime'] = $data['starttime'];
                        $redata['endtime'] = $data['endtime'];
                        S(C('CACHE_FIX').'bid'.$data['pid'],$redata);
                    }
                    // 更新这个拍品的缓存】
                    $pid = $data['pid'];
                    $admsg = '更新';
                }
            }else{
                return array('status' => 0, 'info' => '已有人出价禁止编辑拍卖','url'=>__SELF__);
            }
        }
        if($act=='add'){
            // 冻结保证金后添加拍卖
            unset($data['pid']);
            if($yn = $auction->add($data)){
                if ($to=='zc') {
                    //更新专场内拍品数量字段
                    $bcount = $auction->where(array('sid'=>$data['sid']))->count();
                    $special->where(array('sid'=>$data['sid']))->setField('bcount',$bcount);
                }elseif ($to=='pmh') {
                    // 设置拍卖会最快结束时间
                    $meeting->save(array('mid'=>$data['mid'],'endtime'=>$data['endtime']));
                    // 更新拍卖会内拍品数量字段
                    $bcount = $auction->where(array('mid'=>$data['mid']))->count();
                    $meeting->where(array('mid'=>$data['mid']))->setField('bcount',$bcount);
                }
                $bidObj = $auction->where('pid ='.$data['pid'])->find();
                $admsg = '添加';
            }
        }
        if($yn || $yn === 0){
            // 微信推送新品发布【
            if(C('Weixin.appid')&&C('Weixin.appsecret')){
                // 拍卖上架提醒【
                auction_putaway($uid,$data);
                // 拍卖上架提醒】
                // 图文推送
                $post_weixin = I('post.weixin');
                $send = $post_weixin['send'];

                // 是否设置推送信息 设置推送则保存或者直接推送
                if($send){
                    unset($post_weixin['send']);
                    $gdata = M('Goods')->where('id ='.$data['gid'])->field('description,pictures')->find();
                    $pictures = explode('|', $gdata['pictures']);
                    $webroot = C('WEB_ROOT');
                    if($post_weixin['name']==''){
                        $post_weixin['name'] = $data['pname'];
                    }
                    if($post_weixin['comment']==''){
                        $post_weixin['comment'] = $gdata['description'];
                    }
                    if($post_weixin['toppic']==''){
                        $post_weixin['toppic'] = picRep($pictures[0],1);
                    }
                    if($post_weixin['picture']==''){
                        $post_weixin['picture'] = picRep($pictures[0],1);
                    }

                    $post_weixin['type'] = 'auction';
                    $post_weixin['rid'] = $data['pid'];
                    $post_weixin['url'] = U('Home/Auction/details',array('pid'=>$data['pid']),'html',true);
                    $post_weixin['sellerid'] = $uid;
                    if($post_weixin['id']){
                        $wid = $post_weixin['id'];
                        $wyn = M('weiurl')->save($post_weixin);
                    }else{
                        $wyn = M('weiurl')->add($post_weixin);
                        $wid = $wyn;
                    }
                    if($wyn){
                        if($send==2){
                            $senddata = array($post_weixin['name'],$post_weixin['comment'],$post_weixin['url'],$webroot.trim(C('UPLOADS_PICPATH'),'./').'/'.$post_weixin['toppic']);
                            // 获取符合条件的推送用户
                            $uidarr = eligibility($uid,1);
                            if(!empty($uidarr)){
                                $wresult = D('Weixin')->sendNews(array('uid'=>array('in',$uidarr)),array($senddata),$wid);
                            }else{
                                $wresult = '没有符合推动条件的用户！';
                            }
                        }
                    }
                }
            }
            return array('status' => 1, 'info' => $admsg.'成功<br/>'.$wresult,'url'=>U('index',array('typ'=>$typ)));
            // 微信推送新品发布】
        }else{
            return array('status' => 0, 'info' => $admsg.'失败，请重试','url'=>__SELF__);
        }
        
    }
    /**
     * [listlistRepeat description]
     * @param  integer $firstRow [分页起始]
     * @param  integer $listRows [分页结束]
     * @param  [type]  $where    [筛选条件]
     * @return [type]            [拍品列表]
     */
    public function listRepeat($where,$size,$od = "time DESC") {
        $repeat = M('auction_repeat');
        $count = $repeat->count();
        $pConf = page($count,$size);
        $this->page = $pConf['show'];
        $list = $repeat->order($od)->limit($pConf['first'].','.$pConf['list'])->where($where)->select();
        $goods = M('goods');
        $special = M('special_auction');
        $meeting = M('meeting_auction');
        foreach ($list as $k => $v) {
            switch ($v['type']) {
                case 0:
                    $ginfo = $goods->where(array('id'=>$v['rid']))->field('title,pictures')->find();
                    $list[$k]['title'] = $ginfo['title'];
                    $list[$k]['pictures'] = getPicUrl($ginfo['pictures'],3,0);
                    break;
                case 1:
                    $sinfo = $special->where(array('sid'=>$v['rid']))->field('sname,spicture')->find();
                    $list[$k]['title'] = $sinfo['sname'];
                    $list[$k]['pictures'] = $sinfo['spicture'];
                    break;
                case 2:
                    $minfo = $meeting->where(array('mid'=>$v['rid']))->field('mname,mpicture')->find();
                    $list[$k]['title'] = $minfo['mname'];
                    $list[$k]['pictures'] = $minfo['mpicture'];
                    break;
            }
        }
        return array('list'=>$list,'page'=>$pConf['show'],'count'=>$count);
    }
}

?>
