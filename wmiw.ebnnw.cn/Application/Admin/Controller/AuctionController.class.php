<?php
namespace Admin\Controller;
use Think\Controller;
class AuctionController extends CommonController {
    /**
     * 正在拍卖列表
     * @return [type] [description]
     */
    public function index() {
        $channel = M('goods_category')->where('pid=0')->select(); //读取频道
        $this->channel=$channel; //分配频道
        $ws = I('get.typ')?bidType(I('get.typ')):bidType('biding');
        $where = array();
        if (I('get.typ')) {
            $ws = bidType(I('get.typ'));
            $where = $ws['bidType'];
            $this->saytyp=$ws['saytyp'];
        }
        $auction = D("Auction");
        $rdata = $auction->listAuction($where,'pid desc',C('PAGE_SIZE'));
        $this->page = $rdata['page'];
        $this->list = $rdata['list'];

        $this->display(); 
    }
    /**
     +----------------------------------------------------------
     * 搜索商品
     +----------------------------------------------------------
     */
    public function search(){
        $where = array();
        if(!I('get.gid')){
            if (I('get.typ')) {
                $ws = bidType(I('get.typ'));
                $where = $ws['bidType'];
                $this->saytyp=$ws['saytyp'];
            }
        }else{
           $where['gid']= I('get.gid');
           $this->ginfo=M('goods')->where(array('id'=>I('get.gid')))->field('id,title')->find();
        }
        $keyW = I('get.');
        $encode = mb_detect_encoding($keyW['keyword'], array("ASCII","UTF-8","GB2312","GBK","BIG5"));
        $keyW['keyword'] = iconv($encode,"utf-8//IGNORE",$keyW['keyword']);
        $category = M('Goods_category');
        if($keyW['type']!=''){
            $where['type'] = $keyW['type'];
            $tname = $keyW['type']==0 ?'竞拍模式':'竞价模式';
        }else{
            $tname = '所有模式';
        }
        if($keyW['pid']!=''){
            $cat = new \Org\Util\Category();
            $chname=  $category->where('cid='.$keyW['pid'])->getField('name');
            if($keyW['cid']==''){
                $catecid = $cat->getList($category,'cid',$keyW['pid']);
                $catecid = array_reduce($catecid, create_function('$v,$w', '$v[$w["cid"]]=$w["cid"];return $v;'));
                $catecid[] = $keyW['pid'];
                $where['cid'] = array('in',$catecid);
                $catname = '所有'; 
            }else{
                $catecid = $cat->getList($category,'cid',$keyW['cid']);
                $catecid = array_reduce($catecid, create_function('$v,$w', '$v[$w["cid"]]=$w["cid"];return $v;'));
                $catecid[] = $keyW['cid'];
                $where['cid'] = array('in',$catecid);
                $catname = $category->where('cid='.$keyW['cid'])->getField('name');
            }
        }else{
            $chname = '所有';
            $catname = '所有'; 
        }
        if($keyW['keyword'] != '') $where['pname'] = array('LIKE', '%' . $keyW['keyword'] . '%');
        $channel = $category->where('pid=0')->select(); //读取频道
        $this->channel=$channel; //分配频道
        $rdata = D("Auction")->listAuction($where,'pid desc',C('PAGE_SIZE'));
        $this->page = $rdata['page'];
        $this->list = $rdata['list'];
        $keyS = array('count' =>$rdata['count'],'keyword'=>$keyW['keyword'],'type'=>$keyW['type'],'tname'=>$tname,'chname' => $chname,'catname' => $catname,'pid'=>$keyW['pid'],'cid'=>$keyW['cid']);
        $this->keys = $keyS;
        $this->display('index');
    }
    /**
     * 发布拍卖
     * @return [type] [description]
     */
    public function add() {
        if (IS_POST) {
            $this->checkToken();
            // 冻结卖家保证金
            echojson(D('Auction')->addEdit('add',$this->cUid));
        }else{
            // 分配选择的开始时间
            $this->start_date = date('Y-m-d H:i',time());
            $goods= M('Goods');
            $info['to'] = I('get.to')?I('get.to'):'js';
            $info['gid'] = I('get.gid');
            if (M('Auction')->where(array('gid'=>$info['gid'],'endstatus'=>array('in',array(0,1))))->count()) {
                $this->error('该商品正在拍卖或已成交，不允许再次拍卖！<br/>只有流拍和撤拍的商品才能重新发布拍卖！',U('Auction/index'));
                exit;
            }
            $gdata=$goods->where(array('id'=>$info['gid']))->field('title,price,description,sellerid')->find();
            if($gdata){
                $bidcof=C('Auction');
                $info['pname'] = $gdata['title'];
                $info['onset'] = $gdata['price'];
                $info['price'] = $gdata['price'];
                // 专场数据分配到模板
                if($info['to']=='zc'){
                    $biding = bidType('biding',1);
                    $future = bidType('future',1);
                    $special = M('special_auction');
                    $bidingList = $special->where($biding['bidType'])->order('sid desc')->select();
                    $futureList = $special->where($future['bidType'])->order('sid desc')->select();
                    if (empty($bidingList)&&empty($futureList)) {
                        $this->error('没有可供发布的专场，请先添加专场',U('Auction/special_add'));
                    }
                    $this->bidingList=$bidingList;
                    $this->futureList=$futureList;
                }
                // 拍卖会数据分配到模板
                if($info['to']=='pmh'){
                    $biding = bidType('biding',2);
                    $future = bidType('future',2);
                    $meeting = M('meeting_auction');
                    $bidingList = $meeting->where($biding['bidType'])->order('mid desc')->select();
                    $futureList = $meeting->where($future['bidType'])->order('mid desc')->select();
                    if (empty($futureList)) {
                        $this->error('没有可供发布的拍卖会，请先添加拍卖会',U('Auction/meeting_add'));
                    }
                    $this->bidingList=$bidingList;
                    $this->futureList=$futureList;
                }
                $info = array_merge($info,$bidcof);
                $this->info=$info;
                // 微信推送数据【
                $this->weixin=array('name'=>$gdata['title'],'comment'=>$gdata['description']);
                // 微信推送数据】
                $this->display();
            }else{
                $this->error('商品不存在！');
            }
        }
        
    }
    /**
     * 编辑拍卖
     * @return [type] [description]
     */
    public function edit() {
        if (IS_POST) {
            echojson(D('Auction')->addEdit('edit',$this->cUid));
        }else{
            $auction = M('Auction');
            $uid = $this->cUid;
            $info = $auction->where(array('pid'=>I('get.pid')))->find();
            if(!$info){
                $this->error('拍品ID不存在！');
                exit;
            }
            if($info['bidcount']!=0){
                $this->error('当前拍品已有人出价，您不能进行编辑！');
                exit;
            }
            $bidcof=C('Auction');
            // 处理保证金
            if($info['pledge_type'] == 'ratio'){
                $info['pledge_ratio'] = $info['pledge'];
                //分配定额默认设置
                $info['pledge_fixation'] = $bidcof['pledge_fixation'];
            }elseif ($info['pledge_type'] == 'fixation') {
                $info['pledge_fixation'] = $info['pledge'];
                //分配比例默认设置
                $info['pledge_ratio'] = $bidcof['pledge_ratio'];
            }
            // 处理价格浮动
            if($info['stepsize_type'] == 0){
                $stepsize = explode(',', $info['stepsize']);
                $info['stepsize_ratio'] = $stepsize[0];
                $info['stepsize_ratio_r'] = $stepsize[1];
                $info['stepsize_ratio_s'] = $stepsize[2];
                $info['stepsize_ratio_t'] = $stepsize[3];
                //分配定额默认设置
                $info['step_fixation'] = $bidcof['step_fixation'];
            }elseif ($info['stepsize_type'] == 1) {
                $info['step_fixation'] = $info['stepsize'];
                //分配比例默认设置
                $info['stepsize_ratio'] = $bidcof['stepsize_ratio'];
            }
            unset($info['stepsize']);
            if($info['sid']!=0){
                $special = M('special_auction');
                $specfind = $special->where(array('sid'=>$info['sid']))->find();

                $ntm = time();
                if($specfind['starttime']<=$ntm && $specfind['endtime']>=$ntm){
                    $info['sse']=1;
                }elseif ($specfind['starttime']> $ntm) {
                    $info['sse']=0;
                }
                $biding = bidType('biding',1);
                $future = bidType('future',1);
                $bidingList = $special->where($biding['bidType'])->order('sid desc')->select();
                $futureList = $special->where($future['bidType'])->order('sid desc')->select();
                $this->bidingList=$bidingList;
                $this->futureList=$futureList;
                $info['to']='zc';
            }elseif ($info['mid']!=0) {
                $meeting = M('meeting_auction');
                $meetfind = $meeting->where(array('mid'=>$info['mid']))->find();
                $ntm = time();
                if($meet['starttime']<=$ntm && $meet['endtime']>=$ntm){
                    $info['mse']=1;
                }elseif ($meet['starttime']> $ntm) {
                    $info['mse']=0;
                }
                $biding = bidType('biding',2);
                $future = bidType('future',2);
                $bidingList = $meeting->where($biding['bidType'])->order('mid desc')->select();
                $futureList = $meeting->where($future['bidType'])->order('mid desc')->select();
                $this->bidingList=$bidingList;
                $this->futureList=$futureList;
                $info['to']='pmh';
            }else{
                $info['to']='js';
            }

            // 微信推送数据【
            $weixin = M('weiurl')->where(array('rid'=>I('get.pid'),'type'=>'auction'))->find();
            if (!$weixin) {
                $gdata=M('goods')->where(array('id'=>$info['gid'],'sellerid'=>$uid))->field('title,description')->find();
                $weixin=array('name'=>$gdata['title'],'comment'=>$gdata['description']);
            }
            $this->weixin=$weixin;
            // 微信推送数据】
            $this->info=$info;
            $this->display('add'); 
        }
    }
    // 拍卖信息
    public function info(){
            $auction = D('Auction');
            $info = $auction->where(array('pid'=>I('get.pid')))->find();
            if(!$info){
                $this->error('拍品ID不存在！');
                exit;
            }
            $bidcof=C('Auction');
            // 处理保证金
            if($info['pledge_type'] == 'ratio'){
                $info['pledge_ratio'] = $info['pledge'];
                //分配定额默认设置
                $info['pledge_fixation'] = $bidcof['pledge_fixation'];
            }elseif ($info['pledge_type'] == 'fixation') {
                $info['pledge_fixation'] = $info['pledge'];
                //分配比例默认设置
                $info['pledge_ratio'] = $bidcof['pledge_ratio'];
            }
            // 处理价格浮动
            if($info['stepsize_type'] == 0){
                $stepsize = explode(',', $info['stepsize']);
                $info['stepsize_ratio'] = $stepsize[0];
                $info['stepsize_ratio_r'] = $stepsize[1];
                $info['stepsize_ratio_s'] = $stepsize[2];
                $info['stepsize_ratio_t'] = $stepsize[3];
                //分配定额默认设置
                $info['step_fixation'] = $bidcof['step_fixation'];
            }elseif ($info['stepsize_type'] == 1) {
                $info['step_fixation'] = $info['stepsize'];
                //分配比例默认设置
                $info['stepsize_ratio'] = $bidcof['stepsize_ratio'];
            }
            $this->info=$info;
            $this->display(); 
    }
    // 拍品显示和隐藏
    public function hideshow(){
        if (IS_POST) {
            $auction = M('Auction');
            $where = array('pid'=>I('post.pid'));
            if (I('post.hid')) {
                $sten = $auction->where($where)->setField('hide',0);
                $stch = '显示';
            }else{
                $sten = $auction->where($where)->setField('hide',1);
                $stch = '隐藏';
            }
            if ($sten) {
                echojson(array('status' => 1, 'msg'=>'已设置'.$stch));
            }else {
                echojson(array('status' => 0, 'msg'=>'设置'.$stch.'失败请刷新页面重试！'));
            }
        }
    }
    // 拍品推荐和取消
    public function recommend(){
        if (IS_POST) {
            $auction = M('Auction');
            $where = array('pid'=>I('post.pid'));
            if (I('post.tj')) {
                $sten = M('Auction')->where($where)->setField('recommend',0);
                $stch = '推荐';
            }else{
                $sten = M('Auction')->where($where)->setField('recommend',1);
                $stch = '取消推荐';
            }
            if ($sten) {
                echojson(array('status' => 1, 'msg'=>'已设置'.$stch));
            }else {
                echojson(array('status' => 0, 'msg'=>'设置'.$stch.'失败请刷新页面重试！'));
            }
        }
    }

    // 重复拍卖列表
    public function repeat() {
        $redata = D("Auction")->listRepeat(null,C('PAGE_SIZE'));
        $this->list = $redata['list'];
        $this->page = $redata['page'];
        $this->display(); 
    }
    // 重复拍卖搜索
    public function search_repeat() {
        $keyW = I('get.data');
        $where = array();
        $where['type'] = $keyW['type'];
        if ($keyW['stop']!='') {$where['stop'] = $keyW['stop'];}
        switch ($keyW['type']) {
            case 0:
                $gidarr = M('goods')->where(array('title'=>array('LIKE', '%' . $keyW['keyword'] . '%')))->getField('id',true);
                $where['rid'] = array('in',$gidarr);
                break;
            case 1:
                $sidarr = M('special_auction')->where(array('sname'=>array('LIKE', '%' . $keyW['keyword'] . '%')))->getField('sid',true);
                $where['rid'] = array('in',$sidarr);
                break;
            case 2:
                $midarr = M('meeting_auction')->where(array('mname'=>array('LIKE', '%' . $keyW['keyword'] . '%')))->getField('mid',true);
                $where['rid'] = array('in',$midarr);
                break;
        }
        
        $redata = D("Auction")->listRepeat($where,C('PAGE_SIZE'));
        $this->list = $redata['list'];
        $this->page = $redata['page'];
        $this->keys = $keyW;
        $this->display('repeat'); 
    }
    // 添加重复拍卖
    public function repeat_add() {
        if (IS_POST) {
            $this->checkToken();
            $data = I('post.data');
            if ($data['prg']>0) {
                $data['time'] = time();
                if (M('auction_repeat')->add($data)) {
                    echojson(array('status' => 1, 'info' => "添加成功！", 'url' => U('Auction/repeat')));
                } else {
                    echojson(array("status" => 0, "info" => "添加失败，请重试"));
                }
            }else{
                echojson(array('status' => 0,'info' => '重复次数至少为1次！'));
            }
        } else {
            $get = I('get.');
            switch (I('get.type')) {
                case 0:
                    $goods = M('goods');
                    $sellerid = $goods->where(array('id'=>$get['rid']))->getField('sellerid');
                    break;
                case 1:
                    $special = M('special_auction');
                    $sellerid = $special->where(array('sid'=>$get['rid']))->getField('sellerid');
                    break;
                case 2:
                    $meeting = M('meeting_auction');
                    $sellerid = $meeting->where(array('mid'=>$get['rid']))->getField('sellerid');
                    break;
            }
            // 非一次性缴纳保证金用户禁止设置循环拍卖，因为重复拍的商品不扣除保证金
            if(M('seller_pledge')->where(array('type'=>'disposable','status'=>1,'sellerid'=>$sellerid))->find()){
                if ($id = M('auction_repeat')->where(array('type'=>$get['type'],'rid'=>$get['rid']))->getField('id')) {
                    $this->error('该商品已配置重复拍，现在进入编辑模式！',U('Auction/repeat_edit',array('type'=>$get['rid'],'id'=>$id)),3);
                }
            }else{
                $this->error('商品所有者非一次性缴纳保证金用户，不能设置重复拍！');
            }
            switch (I('get.type')) {
                case 0:
                    $info = $goods->where(array('id'=>$get['rid']))->field('title,pictures')->find();
                    $info=array(
                        'title'=>$info['title'],
                        'pictures'=>getPicUrl($info['pictures'],3,0),
                        'idnm'=>'id',
                        'tnm'=>'商品'
                        );
                    break;
                case 1:
                    $sinfo = $special->where(array('sid'=>$get['rid']))->field('sname,spicture')->find();
                    $info=array(
                        'title'=>$sinfo['sname'],
                        'pictures'=>$sinfo['spicture'],
                        'idnm'=>'sid',
                        'tnm'=>'专场'
                        );
                    break;
                case 2:
                    $minfo = $meeting->where(array('mid'=>$get['rid']))->field('mname,mpicture')->find();
                    $info=array(
                        'title'=>$minfo['mname'],
                        'pictures'=>$minfo['mpicture'],
                        'idnm'=>'mid',
                        'tnm'=>'拍卖会'
                        );
                    break;
            }
            $info['rid']=$get['rid'];
            $info['type']=$get['type'];
            $this->info=$info;
            $this->display();

        }
    }
    // 编辑重复拍卖
    public function repeat_edit() {
        if (IS_POST) {
            $this->checkToken();
            $data = I('post.data');
            if ($data['prg']>0) {
                $data['time'] = time();
                if (M('auction_repeat')->save($data)) {
                    echojson(array('status' => 1, 'info' => "更新成功！", 'url' => U('Auction/repeat')));
                } else {
                    echojson(array("status" => 0, "info" => "更新失败，请重试"));
                }
            }else{
                echojson(array('status' => 0,'info' => '重复次数至少为1次！'));
            }
        } else {
            $info = M('auction_repeat')->where(array('id'=>I('get.id')))->find();
            if ($info) {
                switch ($info['type']) {
                    case 0:
                        $ginfo = M('goods')->where(array('id'=>$info['rid']))->field('title,pictures')->find();
                        $info['title']=$ginfo['title'];
                        $info['pictures']=getPicUrl($ginfo['pictures'],3,0);
                        $info['idnm']='id';
                        $info['tnm']='商品';
                        break;
                    case 1:
                        $sinfo = M('special_auction')->where(array('sid'=>$info['rid']))->field('sname,spicture')->find();
                        $new=array(
                            'title'=>$sinfo['sname'],
                            'pictures'=>$sinfo['spicture'],
                            'idnm'=>'sid',
                            'tnm'=>'专场'
                            );
                        $info = array_merge($info,$new);
                        break;
                    case 2:
                        $sinfo = M('meeting_auction')->where(array('mid'=>$info['rid']))->field('mname,mpicture')->find();
                        $new=array(
                            'title'=>$sinfo['mname'],
                            'pictures'=>$sinfo['mpicture'],
                            'idnm'=>'mid',
                            'tnm'=>'拍卖会'
                            );
                        $info = array_merge($info,$new);
                        break;
                }
                $this->info=$info;
                $this->display('repeat_add');
            }else{
                $this->error('页面不存在！');
            }
        }
    }
    // 删除重复拍设置
    public function repeat_del() {
        if (M("auction_repeat")->where("id=" . (int) $_GET['id'])->delete()) {
            $this->success("成功删除");
        } else {
            $this->error("删除失败，可能是不存在该ID的记录");
        }
    }
    /**
     * 拍卖配置
     * @return [type] [description]
     */
    public function set_auction() {
        if (IS_POST) {
            $this->checkToken();
            $config = APP_PATH . "Common/Conf/SetAuction.php";
            $config = file_exists($config) ? include "$config" : array();
            $config = is_array($config) ? $config : array();
            $data['Auction'] = I('post.Auction');
            if (set_config("SetAuction", $data, APP_PATH . "Common/Conf/")) {
                delDirAndFile(WEB_CACHE_PATH . "Cache/Admin/");
                echojson(array('status' => 1, 'info' => '设置成功','url'=>__SELF__));
            } else {
                echojson(array('status' => 0, 'info' => '设置失败，请检查'));
            }
        } else {
            $this->bidcof=C('Auction');
            $this->display(); 
        }
    }
// 撤拍操作
    public function cancelPai(){
        if (IS_POST) {
            $drive = array();
            $auction = M("Auction");
            $pid = I('post.pid');
            $cpinfo = D("Auction")->where("pid=" . $pid)->find();
            // 验证
            if($cpinfo['starttime']>time()){
                $this->error('未开始拍卖请执行删除操作！');
                exit;
            }
            // 设置牌品当前状态为撤拍
            $data = array(
                'pid'=>$pid,
                'endtime'=>time(),
                'endstatus'=>4
                );

            // 整合撤拍传入workerman变更数据【
            $drive[0] = array(
                'pid'=>$pid,
                'action'=>'cancel',
                'endtime'=>time()
                );
            // 整合撤拍传入workerman变更数据】

            if($auction->save($data)){
                // 解冻卖家保证金
                $rtmsg = unfreeze_seller_pledge($cpinfo['sellerid'],$cpinfo['pid'],'cancel');
                // 更新这个拍品的缓存
                $redata = S(C('CACHE_FIX').'bid'.$pid);
                if($redata){
                    $redata['endtime'] = $data['endtime'];
                    $redata['endstatus'] = $data['endstatus'];
                    S(C('CACHE_FIX').'bid'.$pid,$redata);
                }
                // 拍卖会
                if ($cpinfo['pattern']==4) {
                   $chazhi = $cpinfo['endtime']- $data['endtime'];
                   $mplist = $auction->where(array('mid'=>$cpinfo['mid'],'msort'=>array('gt',$cpinfo['msort']),'endstatus'=>0))->select();
                   $ct = count($mplist);
                   $meeting = M('meeting_auction');
                   // 如果撤拍不是最后一个
                   if($ct!=0){
                        foreach ($mplist as $mpk => $mpv) {
                          $updata = array(
                            'pid'=>$mpv['pid'],
                            'starttime'=>$mpv['starttime']-$chazhi,
                            'endtime'=>$mpv['endtime']-$chazhi
                            );
                          // 更新这个拍品的缓存【
                            $redata = S(C('CACHE_FIX').'bid'.$updata['pid']);
                            if($redata){
                                $redata['starttime'] = $updata['starttime'];
                                $redata['endtime'] = $updata['endtime'];
                                S(C('CACHE_FIX').'bid'.$updata['pid'],$redata);
                            }
                        // 更新这个拍品的缓存】
                          $auction->save($updata);
                          // 整合撤拍传入workerman变更数据【
                          $drive[]=array(
                            'pid'=>$mpv['pid'],
                            'action'=>'uptime',
                            'endtime'=>$updata['endtime']
                            );
                          // 整合撤拍传入workerman变更数据】
                          if($ct==$mpk+1){
                            $meeting->save(array('mid'=>$cpinfo['mid'],'endtime'=> $updata['endtime']));
                          }
                        }
                    // 如果是最后一个拍品撤拍拍卖会结束时间为当前
                   }else{
                        $meeting->save(array('mid'=>$cpinfo['mid'],'endtime'=>$data['endtime']));
                   }
                }
                // 循环退还保证金
                $uidarr = M('Goods_user')->where(array('gid'=>$cpinfo['pid']))->getField('uid',true);
                foreach ($uidarr as $uk => $uv) {
                    return_pledge($cpinfo['pid'],$uv['uid']);
                }
                echojson(array('status' => 1, 'msg'=>'撤拍成功' ,'result' => $drive));
            }else {
                echojson(array('status' => 0, 'msg'=>'撤拍失败请刷新页面重试！'));
            }
        }else{
            E('页面不存在！');
        }
    }
// 删除操作
    public function del(){
        if (IS_POST) {
            $pid = I('post.pid');
            $auction = M("Auction");
            $where = array('pid'=>$pid);
            $cpinfo = $auction->where($where)->find();
            // 正在进行拍卖不能删除
            if($cpinfo['starttime']<=time()&&$cpinfo['endtime']>time()){
                echojson(array('status' => 0, 'msg'=>'已开始拍卖请执行撤拍操作！'));
                exit;
            }
            // 成交的不能删除
            if($cpinfo['endstatus']==1){
                echojson(array('status' => 0, 'msg'=>'成交拍卖不能删除！'));
                exit;
            }
            // 已结束的需要等拍卖会结束了才可以删除
            if($cpinfo['endtime']<time()){
                if($cpinfo['mid']){
                    $endyn = $auction->where(array('mid'=>$cpinfo['mid']))->order('endtime desc')->getField('endtime');
                    if($endyn>time()){
                        echojson(array('status' => 0, 'msg'=>'需要等拍卖会结束后才能删除！'));
                        exit;
                    }
                }
            }
            // 如果是拍卖会
            if ($cpinfo['mid']!=0) {
                $meeting = M('meeting_auction');
                // 检查是否开始如果开始将不允许删除
                $themt = $meeting->where(array('mid'=>$cpinfo['mid']))->find();
                // 已开始和未结束拍卖会不允许删除里面拍品
                if ($themt['starttime']<=time()&&$themt['endtime']>time()) {
                    echojson(array('status' => 0, 'msg'=>'已开始且未结束拍卖会内的拍品不允许删除！'));
                    exit;
                }
            }
            // 整合变动数据传入workerman变更数据【
            $drive[0] = array(
                'pid'=>$pid,
                'action'=>'delete',
                'starttime'=>0,
                'endtime'=>0
                );
            // 解冻卖家保证金
                $rtmsg = unfreeze_seller_pledge($sellerid,$cpinfo['pid'],'del');
            // 整合变动数据传入workerman变更数据】
            if ($auction->where($where)->delete()) {
                
                // 删除拍品的缓存
                S(C('CACHE_FIX').'bid'.$pid,null);
                // 专场拍品数量字段减去1
                if ($cpinfo['sid']!=0) {
                    M('special_auction')->where(array('sid'=>$cpinfo['sid']))->setDec('bcount',1);
                }
                // 未开始拍卖会做才能操作
                if ($cpinfo['mid']!=0) {
                    // 拍卖会拍品数量字段减去1
                   $meeting->where(array('mid'=>$cpinfo['mid']))->setDec('bcount',1);
                   // 按照编号读取拍卖会内的商品
                   $mplist = $auction->where(array('mid'=>$cpinfo['mid'],'msort'=>array('gt',$cpinfo['msort']),'endstatus'=>0))->select();
                   $ct = count($mplist);
                   $thetime = $themt['losetime']+$themt['intervaltime'];
                   // 如果删除不是最后一个，该拍品后拍品开始结束时间变更
                   if($ct!=0){
                        foreach ($mplist as $mpk => $mpv) {
                          $updata = array(
                            'pid'=>$mpv['pid'],
                            'starttime'=>$mpv['starttime']-$thetime,
                            'endtime'=>$mpv['endtime']-$thetime
                            );
                            // 更新这个拍品的缓存【
                            $redata = S(C('CACHE_FIX').'bid'.$updata['pid']);
                            if($redata){
                                $redata['starttime'] = $updata['starttime'];
                                $redata['endtime'] = $updata['endtime'];
                                S(C('CACHE_FIX').'bid'.$updata['pid'],$redata);
                            }
                            // 更新这个拍品的缓存】

                          $auction->save($updata);
                          // 整合撤拍传入workerman变更数据【
                          $drive[]=array(
                            'pid'=>$mpv['pid'],
                            'action'=>'uptime',
                            'starttime'=>$updata['starttime'],
                            'endtime'=>$updata['endtime']
                            );
                          // 整合撤拍传入workerman变更数据】

                          if($ct==$mpk+1){
                            $meeting->save(array('mid'=>$cpinfo['mid'],'endtime'=> $updata['endtime']));
                          }
                        }
                    // 如果删除是最后一个拍品，拍卖会结束时间减去拍品占用时间
                   }else{
                        $meeting->save(array('mid'=>$cpinfo['mid'],'endtime'=>$themt['endtime']-$thetime));
                   }
                }
                echojson(array('status' => 1, 'msg'=>'删除成功!'.$rtmsg ,'result' => $drive));
            } else {
                echojson(array('status' => 0, 'msg'=>'删除失败，可能是不存在该PID的记录'));
            }
        }else{
            E('页面不存在！');
        }
    }
//以下专场相关-----------------------------------------------------------------------------------------
    /**
     * 专场管理
     * @return [type] [description]
     */
    public function special(){
        $where = array();
        if (I('get.typ')) {
           $ws = bidType(I('get.typ'),1);
           $where = $ws['bidType'];
           $this->saytyp=$ws['saytyp'];
        }
        $redata = D("SpecialAuction")->listSpecial($where,'sid desc');
        $this->list=$redata['list'];
        $this->page=$redata['page'];
        $this->display();
    }
    /**
     +----------------------------------------------------------
     * 搜索专场
     +----------------------------------------------------------
     */
    public function search_special(){
        $keyW = I('get.');
        $where = array();
        if (I('get.typ')) {
           $ws = bidType(I('get.typ'),1);
           $where = $ws['bidType'];
           $this->saytyp=$ws['saytyp'];
        } 
        if($keyW['keyword'] != ''){
            $encode = mb_detect_encoding($keyW['keyword'], array("ASCII","UTF-8","GB2312","GBK","BIG5"));
            $keyW['keyword'] = iconv($encode,"utf-8//IGNORE",$keyW['keyword']);
            $where['sname'] = array('LIKE', '%' . $keyW['keyword'] . '%');
        }
        if($keyW['special_pledge_type']!='') $where['special_pledge_type'] = $keyW['special_pledge_type'];
        $redata = D("SpecialAuction")->listSpecial($where,'sid desc');
        $this->list=$redata['list'];
        $this->page=$redata['page'];
        $this->saytyp=$ws['saytyp'];
        $keyS = array('count' =>$redata['count'],'keyword'=>$keyW['keyword'],'type'=>$keyW['special_pledge_type']);
        $this->keys = $keyS;
        $this->display('special');
    }
    /**
     * 添加专场
     * @return [type] [description]
     */
    public function special_add(){
        if (IS_POST) {
            $this->checkToken();
            $data = I('post.data');
            $data['starttime']=strtotime($data['starttime']);
            $data['endtime']=strtotime($data['endtime']);
            if($data['endtime']<time()){
                echojson(array('status' => 0, 'info' => '结束时间应该大于当前时间'));
                exit;
            }
            if($data['endtime']<$data['starttime']){
                echojson(array('status' => 0, 'info' => '结束时间应大于开始时间'));
                exit;
            }
            // 发布者id
            $data['aid'] = $this->cAid;
            unset($data['sid']);
            if(M('special_auction')->add($data)){
                echojson(array('status' => 1, 'info' => '添加成功','url'=>U('Auction/special')));
            }else{
                echojson(array('status' => 0, 'info' => '添加失败，请重试'));
            }
        }else{
            // 分配选择的开始时间
            $this->start_date = date('Y-m-d H:i',time());
            $this->display();  
        }
    }
    /**
     * 编辑专场
     * @return [type] [description]
     */
    public function special_edit() {
        if (IS_POST) {
            $this->checkToken();
            $data = I('post.data');
            $special = M('special_auction');
            $auction = M('auction');
            if ($data['starttime']) {
                $data['starttime']=strtotime($data['starttime']);
                $auction->where(array('sid'=>$data['sid']))->setField('starttime',$data['starttime']);
            }else{
                unset($data['starttime']);
            }
            if ($data['endtime']) {
                $data['endtime']=strtotime($data['endtime']);
                $auction->where(array('sid'=>$data['sid']))->setField('endtime',$data['endtime']);
            }else{
                unset($data['endtime']);
            }
            if($special->save($data)){
                $data = $special->where(array('sid'=>$data['sid']))->field(array('starttime','endtime'))->find();
                if ($data['starttime']<=time() && $data['endtime']>=time()) {
                    $typ = 'biding';
                }elseif ($data['endtime']<time()) {
                    $typ = 'bidend';
                }elseif ($data['starttime']>time()) {
                    $typ = 'future';
                }
                echojson(array('status' => 1, 'info' => '更新成功','url'=>U('Auction/special',array('typ'=>$typ))));
            }else{
                echojson(array('status' => 0, 'info' => '更新失败，请重试'));
            }
        }else{
            $info = D('SpecialAuction')->where(array('sid'=>I('get.sid')))->find();
            // 未开始专场可以编辑
            $this->edit = $info['starttime']>time() ? 0:1;
            $this->info=$info;
            $this->display('special_add'); 
        }
        
    }
    public function special_del(){
        if (M("Special_auction")->where("sid=" . (int) $_GET['sid'])->delete()) {
            $auction = M('Auction');
            // 如果有拍卖将移除拍卖关联专场字段
            $pidarr = $auction->where("sid=" . (int) $_GET['sid'])->getField('pid',true);
            if (!empty($pidarr)) {
                $setfield = array('sid'=>0,'stepsize_type'=>'fixation','pledge_type'=>'fixation','pattern'=>0);
                $auction->where(array('pid'=>array('in',$pidarr)))->setField($setfield);
            }
            $this->success("成功删除，专场内拍卖已解散！");
        } else {
            $this->error("删除失败，可能是不存在该ID的记录");
        }
    }
    // 专场显示和隐藏
    public function special_hideshow(){
        if (IS_POST) {
            $special = M('Special_auction');
            $where = array('sid'=>I('post.sid'));
            if (I('post.hid')) {
                $sten = $special->where($where)->setField('hide',0);
                $stch = '显示';
            }else{
                $sten = $special->where($where)->setField('hide',1);
                $stch = '隐藏';
            }
            if ($sten) {
                echojson(array('status' => 1, 'msg'=>'已设置'.$stch));
            }else {
                echojson(array('status' => 0, 'msg'=>'设置'.$stch.'失败请刷新页面重试！'));
            }
        }
    }

    // 专场信息
    public function special_info(){
        $auction = M('auction');
        $info = M('special_auction')->where(array('sid'=>I('get.sid')))->find();
        $info['count'] = $auction->where(array('sid'=>I('get.sid')))->count();
        if($info['starttime']<=$ntime&&$info['endtime']>=$ntime){
            $info['st'] = '在拍';
        }elseif ($info['endtime']<$ntime) {
            $info['st'] = '结束';
            // 结束专场获取状态统计
            $info['chengjiao'] = $auction->where(array('sid'=>$info['sid'],'endstatus'=>1))->count();
            $info['liupai'] = $auction->where(array('sid'=>$info['sid'],'endstatus'=>array('in',array(2,3))))->count();
            $info['chepai'] = $auction->where(array('sid'=>$info['sid'],'endstatus'=>4))->count();
        }elseif ($info['starttime']>$ntime) {
            $info['st'] = '待拍';
        }
        $this->info=$info;
        $this->display(); 
    }



//以下拍卖会相关-----------------------------------------------------------------------------------------
    /**
     * 拍卖会管理
     * @return [type] [description]
     */
    public function meeting(){
        $where = array();
        if (I('get.typ')) {
           $ws = bidType(I('get.typ'),2);
           $where = $ws['bidType'];
           $this->saytyp=$ws['saytyp'];
        }
        $redata = D("MeetingAuction")->listMeeting($where,'mid desc');
        $this->list=$redata['list'];
        $this->page=$redata['page'];
        $this->saytyp=$ws['saytyp'];
        $this->display();
    }
    /**
     +----------------------------------------------------------
     * 搜索拍卖会
     +----------------------------------------------------------
     */
    public function search_meeting(){
        $keyW = I('get.');
        $where = array();
        if (I('get.typ')) {
           $ws = bidType(I('get.typ'),2);
           $where = $ws['bidType'];
           $this->saytyp=$ws['saytyp'];
        }
        if($keyW['keyword'] != ''){
            $encode = mb_detect_encoding($keyW['keyword'], array("ASCII","UTF-8","GB2312","GBK","BIG5"));
            $keyW['keyword'] = iconv($encode,"utf-8//IGNORE",$keyW['keyword']);
            $where['mname'] = array('LIKE', '%' . $keyW['keyword'] . '%');
        }
        if($keyW['meeting_pledge_type']!='') $where['meeting_pledge_type'] = $keyW['meeting_pledge_type'];
        $redata = D("MeetingAuction")->listMeeting($where,'mid desc');
        $this->list=$redata['list'];
        $this->page=$redata['page'];
        $this->saytyp=$ws['saytyp'];
        $keyS = array('count' =>$redata['count'],'keyword'=>$keyW['keyword'],'type'=>$keyW['meeting_pledge_type']);
        $this->keys = $keyS;
        $this->display('meeting');
    }
    /**
     * 添加拍卖会
     * @return [type] [description]
     */
    public function meeting_add(){
        if (IS_POST) {
            $this->checkToken();
            $data = I('post.data');
            $data['starttime']=strtotime($data['starttime']);
            if($data['starttime']<=time()){
               echojson(array('status' => 0, 'info' => '您不能设置开始时间为已开始！<br/>因为新拍卖会下没有拍品'));
               exit; 
            }
            // 发布者id
            $data['aid'] = $this->cAid;
            unset($data['mid']);
            if(M('meeting_auction')->add($data)){
                echojson(array('status' => 1, 'info' => '添加成功','url'=>U('Auction/meeting',array('typ'=>'future'))));
            }else{
                echojson(array('status' => 0, 'info' => '添加失败，请重试'));
            }
        }else{
            // 分配选择的开始时间
            $this->start_date = date('Y-m-d H:i',time());
            $this->display();  
        }
    }
    /**
     * 编辑拍卖会
     * @return [type] [description]
     */
    public function meeting_edit() {
        if (IS_POST) {
            $this->checkToken();
            $data = I('post.data');
            if(M('Auction')->where(array('mid'=>$data['mid']))->count()){
                echojson(array('status' => 0, 'info' => '拍卖会下有拍品，系统禁止编辑！'));
                exit;
            }
            $data['starttime']=strtotime($data['starttime']);
            if(M('meeting_auction')->save($data)){
                echojson(array('status' => 1, 'info' => '更新成功','url'=>U('Auction/meeting')));
            }else{

                echojson(array('status' => 0, 'info' => '更新失败，请重试'));
            }
        }else{
            $info = D('MeetingAuction')->where(array('mid'=>I('get.mid')))->find();
            $this->edit = $info['starttime']>time() ? 0:1;
            $this->info=$info;
            $this->display('meeting_add'); 
        }
        
    }
    public function meeting_del(){
        if (M("meeting_auction")->where("mid=" . (int) $_GET['mid'])->delete()) {
            $auction = M('Auction');
            $alist = $auction->where("mid=" . (int) $_GET['mid'])->select();
            foreach ($alist as $ak => $av) {
                if($av['endtime']>time()){
                    $auction->save(array('pid'=>$av['pid'],'endtime'=>time(),'mid'=>0,'sid'=>0,'pattern'=>0));
                }
            }
            $this->success("删除成功，拍卖会内拍卖全部结束！");
        } else {
            $this->error("删除失败，可能是不存在该ID的记录");
        }
    }

    // 拍卖会信息
    public function meeting_info(){
        $auction = M('auction');
        $info = M('meeting_auction')->where(array('mid'=>I('get.mid')))->find();
        $info['count'] = $auction->where(array('mid'=>I('get.mid')))->count();
        if($info['starttime']<=$ntime&&$info['endtime']>=$ntime){
            $info['st'] = '在拍';
        }elseif ($info['endtime']<$ntime) {
            $info['st'] = '结束';
            // 结束拍卖会获取状态统计
            $info['chengjiao'] = $auction->where(array('mid'=>$info['mid'],'endstatus'=>1))->count();
            $info['liupai'] = $auction->where(array('mid'=>$info['mid'],'endstatus'=>array('in',array(2,3))))->count();
            $info['chepai'] = $auction->where(array('mid'=>$info['mid'],'endstatus'=>4))->count();
        }elseif ($info['starttime']>$ntime) {
            $info['st'] = '待拍';
        }
        $this->info=$info;
        $this->display(); 
    }

    // 拍卖会显示和隐藏
    public function meeting_hideshow(){
        if (IS_POST) {
            $meeting = M('Meeting_auction');
            $where = array('mid'=>I('post.mid'));
            if (I('post.hid')) {
                $sten = $meeting->where($where)->setField('hide',0);
                $stch = '显示';
            }else{
                $sten = $meeting->where($where)->setField('hide',1);
                $stch = '隐藏';
            }
            if ($sten) {
                echojson(array('status' => 1, 'msg'=>'已设置'.$stch));
            }else {
                echojson(array('status' => 0, 'msg'=>'设置'.$stch.'失败请刷新页面重试！'));
            }
        }
    }



    // 卖家保证金
    public function seller_pledge(){
        $seller_pledge = D('SellerPledge');
        $member = M('Member');
        if(I('get.')){
            if(I('get.start_time')!=''){
                $wstar .= "time >= ".strtotime(I('get.start_time'))." and ";
            }
            if(I('get.end_time')!=''){
                $wstar .= "time <= ".strtotime(I('get.end_time'));
            }
            if($wstar!=''){
                $where['_string'] = $wstar;
            }
            if(I('get.account')!=''){
                $user = $member->where(array('account'=>I('get.account')))->field('uid,nickname,account')->find();
                if ($user) {
                    $where['sellerid'] = $user['uid'];
                }else{
                    $this->error('用户不存在！',U('Index/statistics'));
                }
            }
            if(I('get.sellpledgetype')!=''){
                $where['type'] = I('get.sellpledgetype');
            }
            if(I('get.status')!=''){
                $where['status'] = I('get.status');
            }
            $keys= I('get.');
            $this->keys = $keys;
        }
        $count = $seller_pledge->where($where)->count();
        $pConf = page($count,C('PAGE_SIZE'));
        $this->page = $pConf['show'];
        $list = $seller_pledge->where($where)->limit($pConf['first'].','.$pConf['list'])->order('time desc')->select();
        $this->sellpledgetype = sellpledgetype('all');
        $this->list=$list;
        $this->display();
    }
    // 添加一次性缴纳保证金用户
    public function add_jurisdiction(){
        if (IS_POST) {
            $data = I('post.data');
            if (!$data['sellerid']) {
                echojson(array('status'=>0,'info'=>'请选择用户！','url'=>__SELF__));
                exit;
            }
            // 判断发布商品的权限[seller_pledge_disposable]一次性缴纳；[seller_pledge_every]每件缴纳；[seller_pledge_proportion]按照起拍比例缴纳
            $seller_pledge = M('seller_pledge');
            $spid = $seller_pledge->where(array('sellerid'=>$data['sellerid'],'type'=>'disposable','status'=>'1'))->find();
            // 检查是否已经缴纳过一次性保证金；如果缴纳过一次性保证金通过验证
            if($spid){
                echojson(array('status'=>0,'info'=>'该用户已属于【一次性缴纳保证金用户】！','url'=>__SELF__));
                exit;
            // 读取后台设置缴纳方式
            }else{
                // 读取保证金收取方式和金额
                $uLimit = getwallet($data['sellerid']);
                if($uLimit['count']<$data['pledge']){
                    echojson(array('status'=>0,'info'=>'该用户账户余额不足，请先充值！','url'=>__SELF__));
                    exit;
                }else{
                    // 用户账户信息
                    $member = M('member');
                    $where = array('uid'=>$data['sellerid']);
                    $uLimit['alert'] = '发布拍卖冻结您保证金<span>'.$data['pledge'].'元</span>，冻结后将不限制您发布拍卖的数量！';
                    $annotation = '管理员添加为【一次性缴纳保证金】用户';
                    $fpledge = $data['pledge'];
                    // 优先冻结信誉额度
                    $chazhi = $uLimit['limsum'] - $fpledge;
                    $authdata=array(
                            'sellerid'=>$data['sellerid'],
                            'type'=>$data['type'],
                            'time'=>time(),
                            'status'=>1
                            );
                    if($chazhi>=0){
                        $authdata['limsum'] = $fpledge;
                        $authdata['pledge'] = 0;
                    }else{
                        if($uLimit['limsum']>0){
                            $authdata['pledge'] = abs($chazhi);
                            $authdata['limsum'] = $uLimit['limsum'];
                            // 冻结保证金
                        }else{
                            $authdata['pledge'] = $fpledge;
                            $authdata['limsum'] = 0;
                        }
                    }
                    // 冻结卖家信誉额度
                    if($authdata['limsum']>0||$authdata['limsum']==0){
                        if ($authdata['limsum']>0) {
                            $member->where($where)->setInc('wallet_limsum_freeze',$authdata['limsum']);
                        }
                        $limsum_data = array(
                            'order_no'=>createNo('add'),
                            'uid'=>$data['sellerid'],
                            'changetype'=>'add_freeze',
                            'time'=>time(),
                            'expend'=>$authdata['limsum'],
                            'annotation'=>$annotation.'冻结信誉额度【'.$authdata['limsum'].'元】！',
                            'usable'=>sprintf("%.2f",$uLimit['limsum']-$authdata['limsum']),
                            'balance'=>$uLimit['wallet_limsum']
                            );
                        $limsum_bill = M('member_limsum_bill')->add($limsum_data);
                    }
                    // 冻结卖家保证金
                    if($authdata['pledge']>0||$authdata['pledge']==0){
                        if ($authdata['pledge']>0) {
                            $member->where($where)->setInc('wallet_pledge_freeze',$authdata['pledge']);
                        }
                        $pledge_data = array(
                            'order_no'=>createNo('add'),
                            'uid'=>$data['sellerid'],
                            'changetype'=>'add_freeze',
                            'time'=>time(),
                            'expend'=>$authdata['pledge'],
                            'annotation'=>$annotation.'冻结保证金【'.$authdata['pledge'].'元】！',
                            'usable'=>sprintf("%.2f",$uLimit['pledge']-$authdata['pledge']),
                            'balance'=>$uLimit['wallet_pledge']
                            );
                        $pledge_bill = M('member_pledge_bill')->add($pledge_data);
                    }
                    if($pledge_bill||$limsum_bill){
                        if ($spid = M('seller_pledge')->add($authdata)) {
                            // 发送提醒【
                            // 是否记录有保证金
                            if($limsum_data['order_no']){
                                // 提醒通知冻结信誉额度【
                                    // 微信提醒内容
                                    $wei_limsum_freeze['tpl'] = 'walletchange';
                                    $wei_limsum_freeze['msg']=array(
                                        "url"=>U('Home/Member/wallet',array('option'=>'limsum'),'html',true), 
                                        "first"=>"您好，".'发布拍卖“'.$bidObj['pname'].'”冻结信誉额度！',
                                        "remark"=>'查看账户记录>>',
                                        "keyword"=>array('信誉额度账户','发布拍卖冻结信誉额度','订单:'.$limsum_data['order_no'],'-'.$authdata['limsum'].'元',$limsum_data['usable'].'元')
                                    );
                                    // 账户类型，操作类型、操作内容、变动额度、账户余额
                                    // 站内信提醒内容
                                    $web_limsum_freeze = array(
                                        'title'=>'发布拍卖冻结信誉额度',
                                        'content'=>$annotation.'冻结信誉额度【'.$authdata['limsum'].'元】'
                                        );
                                    // 短信提醒内容
                                    if(mb_strlen($bidObj['pname'],'utf-8')>10){
                                        $newname = mb_substr($bidObj['pname'],0,10,'utf-8').'...';
                                    }else{
                                        $newname = $bidObj['pname'];
                                    }
                                    $note_limsum_freeze = '发布拍卖“'.$newname.'”冻结信誉额度【'.$authdata['limsum'].'元】，单号'.$limsum_data['order_no'].'，您可以登陆平台查看账户记录。';
                                    // 邮箱提醒内容
                                    $mail_limsum_freeze['title'] = '发布拍卖冻结信誉额度';
                                    $mail_limsum_freeze['msg'] = '您好：<br/><p>'.$limsum_updata.'</p><p>您可以<a target="_blank" href="'.U('Home/Member/wallet',array('option'=>'limsum'),'html',true).'">查看账户记录</a></p>';
                                    sendRemind($member,M('Member_weixin'),array(),array($authdata['sellerid']),$web_limsum_freeze,$wei_limsum_freeze,$note_limsum_freeze,$mail_limsum_freeze,'sel');
                                // 提醒通知冻结信誉额度【

                            }   
                            // 是否记录有信誉额度
                            if($pledge_data['order_no']){
                                $wallet = $member->where(array('uid'=>$authdata['sellerid']))->field('wallet_pledge,wallet_pledge_freeze')->find();
                                $usable = $wallet['wallet_pledge']-$wallet['wallet_pledge_freeze'];
                                // 提醒通知冻结保证金【
                                    // 微信提醒内容
                                    $wei_pledge_freeze['tpl'] = 'walletchange';
                                    $wei_pledge_freeze['msg']=array(
                                        "url"=>U('Member/wallet','','html',true), 
                                        "first"=>"您好，".'发布拍卖“'.$bidObj['pname'].'”冻结保证金！',
                                        "remark"=>'查看账户记录>>',
                                        "keyword"=>array('余额账户','发布拍卖冻结信誉额度','订单:'.$pledge_data['order_no'],'-'.$authdata['pledge'].'元',$pledge_data['usable'].'元')
                                    );
                                    // 账户类型，操作类型、操作内容、变动额度、账户余额
                                    // 站内信提醒内容
                                    $web_pledge_freeze = array(
                                        'title'=>'发布拍卖冻结保证金',
                                        'content'=>$annotation.'冻结保证金【'.$authdata['pledge'].'元】'
                                        );
                                    // 短信提醒内容
                                    if(mb_strlen($bidObj['pname'],'utf-8')>10){
                                        $newname = mb_substr($bidObj['pname'],0,10,'utf-8').'...';
                                    }else{
                                        $newname = $bidObj['pname'];
                                    }
                                    $note_pledge_freeze = '发布拍卖冻结“'.$newname.'”冻结保证金【'.$authdata['pledge'].'元】，单号'.$pledge_data['order_no'].'，您可以登陆平台查看账户记录。';
                                    // 邮箱提醒内容
                                    $mail_pledge_freeze['title'] = '发布拍卖冻结保证金';
                                    $mail_pledge_freeze['msg'] = '您好：<br/><p>'.$pledge_updata.'</p><p>您可以<a target="_blank" href="'.U('Home/Member/wallet','','html',true).'">查看账户记录</a></p>';

                                    sendRemind($member,M('Member_weixin'),array(),array($authdata['sellerid']),$web_pledge_freeze,$wei_pledge_freeze,$note_pledge_freeze,$mail_pledge_freeze,'sel');
                                // 提醒通知冻结保证金【
                            }
                            // 发送提醒】
                            echojson(array('status'=>1,'info'=>'已添加该用户为【一次性缴纳保证金】！','url'=>U('Auction/seller_pledge')));
                        }
                        
                    }else{
                        echojson(array('status'=>0,'info'=>'冻结保证金失败，请联系管理员进行解决！'));
                    }
                }
            }
        }else{
            $this->display();
        }
    }


}