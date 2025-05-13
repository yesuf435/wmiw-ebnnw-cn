<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class AuctionController extends CommonController {
  /**
   * 正在拍卖
   * @return [type] [description]
   */
    public function index(){
        $ism = $this->ism;
        // $gt[0]：频道分类  
        // $gt[1]:分页显示条数 
        // $gt[2]：结束时间段 (0.正在进行的拍卖 1.今天结束2.明天结束3.其他 )
        // $gt[3]：城市(省——市——区) 
        // $gt[4]：筛选属性(属性1,属性2.....)
        // $gt[5]：拍卖模式(0：竞拍1，：竞标) 
        // $gt[6]：是否出价(a：全部，y：已出价，n：未出价) 
        //设置默认条件 
        $gt = explode('-', I('get.gt'));
        // 拍卖阶段
        $stage = I('get.stage');
        if (empty($stage)) {$stage='biding';}
        $this->stage=$stage;
        // 分类下不限的筛选
        $topfilt = getTopField($gt[0]);
        $gtCount = count($gt);
        if($gtCount==1){
            if($gt[0]!=''){
                $gt = array($gt[0],'12','0','0_0_0',$topfilt,'i','a');
            }else{
                $gt = array('0','12','0','0_0_0',getTopField(0),'i','a');
            }
        }elseif ($gtCount!=7) {
            $this->error('页面不存在');
        }
        $bidMap = D('Auction');
        $where = array();
        // 加入筛选
        $screen = I('get.screen');
        if (!empty($screen)) {
            switch ($screen) {
                // 即时成交
                case 'jscj':
                    $where['succtype']=array('eq',1);
                    break;
                // 0元起拍
                case 'jianlou':
                    $where['onset']=array('eq',0);
                    break;
            }
        }
        $this->screen=$screen;
        // 排序
        if (I('get.sort')=='time') {
            $sort = 'time desc';
        }else{
            $sort = 'endtime';
        }
        $this->sort=$sort;
        // 是否已有人出价出价条件
        $bid_yn = array();
        if($gt[6]=='y'){
            $where['uid']=array('neq',0);
        }elseif ($gt[6]=='n') {
            $where['uid']=array('eq',0);
        }
        // 生成地区条件[
        $regArr=explode('_', $gt[3]);
        if ($regArr[0]) {$where['prov'] = $regArr[0];}
        if ($regArr[1]) {$where['city'] = $regArr[1];}
        if ($regArr[2]) {$where['district'] = $regArr[2];}

        // 获取包含频道的分类条件[
        $category = M('Goods_category');
        $cat = new \Org\Util\Category();
        $cate = $category->select();
        $path = $cat->getPath($cate,$gt[0]);
        $pid = 0;
        $ncid = end($path);
        if(count($path)>0){$pid=$ncid['cid'];}
        $catArr = $cat->getList($category,'cid',$pid);
        $catArr = array_reduce($catArr, create_function('$v,$w', '$v[$w["cid"]]=$w["cid"];return $v;'));
        $catArr[] = $pid;
        $where['cid'] = array('in',$catArr);
        //$section 生成正在拍卖、未开始、已结束查询条件[
        switch ($stage) {
            case 'biding':
                $section = bidSection($gt[2]);
                // 结束时间段条件
                $section_link =array(
                array('key'=>0,'name'=>'全部','href'=>U('Auction/index',array('stage'=>$stage,'screen'=>$screen,'sort'=>$sort, 'gt'=>$pid.'-'.$gt[1].'-'.'0'.'-'.'0_0_0'.'-'.$topfilt.'-'.$gt[5].'-'.$gt[6])),
                  'count'=>$bidMap->where(array_merge(bidSection(0),$where))->count()),
                array('key'=>1,'name'=>'今天结束','href'=>U('Auction/index',array('stage'=>$stage,'screen'=>$screen,'sort'=>$sort,'gt'=>$pid.'-'.$gt[1].'-'.'1'.'-'.'0_0_0'.'-'.$topfilt.'-'.$gt[5].'-'.$gt[6])),
                  'count'=>$bidMap->where(array_merge(bidSection(1),$where))->count()),
                array('key'=>2,'name'=>'明天结束','href'=>U('Auction/index',array('stage'=>$stage,'screen'=>$screen,'sort'=>$sort,'gt'=>$pid.'-'.$gt[1].'-'.'2'.'-'.'0_0_0'.'-'.$topfilt.'-'.$gt[5].'-'.$gt[6])),
                  'count'=>$bidMap->where(array_merge(bidSection(2),$where))->count()),
                array('key'=>3,'name'=>'后天结束','href'=>U('Auction/index',array('stage'=>$stage,'screen'=>$screen,'sort'=>$sort,'gt'=>$pid.'-'.$gt[1].'-'.'3'.'-'.'0_0_0'.'-'.$topfilt.'-'.$gt[5].'-'.$gt[6])),
                  'count'=>$bidMap->where(array_merge(bidSection(3),$where))->count()),
                array('key'=>4,'name'=>'其他结束','href'=>U('Auction/index',array('stage'=>$stage,'screen'=>$screen,'sort'=>$sort,'gt'=>$pid.'-'.$gt[1].'-'.'4'.'-'.'0_0_0'.'-'.$topfilt.'-'.$gt[5].'-'.$gt[6])),
                  'count'=>$bidMap->where(array_merge(bidSection(4),$where))->count())
                );
                break;
            case 'future':
                $section = foreshow($gt[2]); 
                // 开拍时间段
                $section_link = array(
                    array('key'=>0,'name'=>'全部','href'=>U('Auction/index',array('stage'=>$stage,'screen'=>$screen,'sort'=>$sort,'gt'=>$pid.'-'.$gt[1].'-'.'0'.'-'.'0_0_0'.'-'.$topfilt.'-'.$gt[5].'-'.$gt[6])),
                      'count'=>$bidMap->where(array_merge(foreshow(0),$where))->count()),
                    array('key'=>1,'name'=>'即将开拍','href'=>U('Auction/index',array('stage'=>$stage,'screen'=>$screen,'sort'=>$sort,'gt'=>$pid.'-'.$gt[1].'-'.'1'.'-'.'0_0_0'.'-'.$topfilt.'-'.$gt[5].'-'.$gt[6])),
                      'count'=>$bidMap->where(array_merge(foreshow(1),$where))->count()),
                    array('key'=>2,'name'=>'明天开拍','href'=>U('Auction/index',array('stage'=>$stage,'screen'=>$screen,'sort'=>$sort,'gt'=>$pid.'-'.$gt[1].'-'.'2'.'-'.'0_0_0'.'-'.$topfilt.'-'.$gt[5].'-'.$gt[6])),
                      'count'=>$bidMap->where(array_merge(foreshow(2),$where))->count()),
                    array('key'=>3,'name'=>'后天开拍','href'=>U('Auction/index',array('stage'=>$stage,'screen'=>$screen,'sort'=>$sort,'gt'=>$pid.'-'.$gt[1].'-'.'3'.'-'.'0_0_0'.'-'.$topfilt.'-'.$gt[5].'-'.$gt[6])),
                      'count'=>$bidMap->where(array_merge(foreshow(3),$where))->count()),
                    array('key'=>4,'name'=>'其他开拍','href'=>U('Auction/index',array('stage'=>$stage,'screen'=>$screen,'sort'=>$sort,'gt'=>$pid.'-'.$gt[1].'-'.'4'.'-'.'0_0_0'.'-'.$topfilt.'-'.$gt[5].'-'.$gt[6])),
                      'count'=>$bidMap->where(array_merge(foreshow(4),$where))->count())
                );

                break;
            case 'bidend':
                $section = endbid($gt[2]); 
                // 成交时间段
                $section_link =  array(
                    array('key'=>0,'name'=>'全部','href'=>U('Auction/index',array('stage'=>$stage,'screen'=>$screen,'sort'=>$sort,'gt'=>$pid.'-'.$gt[1].'-'.'0'.'-'.'0_0_0'.'-'.$topfilt.'-'.$gt[5].'-'.$gt[6])),
                      'count'=>$bidMap->where(array_merge(endbid(0),$where))->count()),
                    array('key'=>1,'name'=>'今天成交','href'=>U('Auction/index',array('stage'=>$stage,'screen'=>$screen,'sort'=>$sort,'gt'=>$pid.'-'.$gt[1].'-'.'1'.'-'.'0_0_0'.'-'.$topfilt.'-'.$gt[5].'-'.$gt[6])),
                      'count'=>$bidMap->where(array_merge(endbid(1),$where))->count()),
                    array('key'=>2,'name'=>'昨天成交','href'=>U('Auction/index',array('stage'=>$stage,'screen'=>$screen,'sort'=>$sort,'gt'=>$pid.'-'.$gt[1].'-'.'2'.'-'.'0_0_0'.'-'.$topfilt.'-'.$gt[5].'-'.$gt[6])),
                      'count'=>$bidMap->where(array_merge(endbid(2),$where))->count()),
                    array('key'=>3,'name'=>'前天成交','href'=>U('Auction/index',array('stage'=>$stage,'screen'=>$screen,'sort'=>$sort,'gt'=>$pid.'-'.$gt[1].'-'.'3'.'-'.'0_0_0'.'-'.$topfilt.'-'.$gt[5].'-'.$gt[6])),
                      'count'=>$bidMap->where(array_merge(endbid(3),$where))->count()),
                    );
                break;
        }
        $this->section_link=$section_link;
        $where = array_merge($where,$section);
        //$section 生成正在拍卖、未开始、已结束查询条件]

        // 拍卖模式[
        if ($gt[5]!='i') {$where['type']=$gt[5];}
        // 拍卖模式]
        // 屏蔽的商家[
        if ($xUidarr = blackuser($this->cUid)) {
            $where['sellerid'] = array('not in',$xUidarr);
        }
        // 屏蔽的商家]

        // 通过筛选属性获取拍品pid集合[
        $filtParMap=$bidMap->where($where)->field('pid,filtrate')->select();
        if($gt[4]){
            $filtSear =explode('_', $gt[4]);
        }else{
            $filtSear = array();
        }
        $unlimited = getTopField($gt[0],'arr'); //该分类不限条件fid
        //筛选条件加进去“不限”条件
        $newFiltSear = array_unique(array_merge($unlimited,$filtSear)); 
        $inSuitFiltPid = array();
        foreach ($filtParMap as $fpk => $fpv) {
            // 当前商品条件加进去“不限”条件
            $newFilt = array_unique(array_merge($unlimited,explode('_', $fpv['filtrate']))); //新商品条件
            if(count($newFiltSear) == count(array_intersect($newFilt,$newFiltSear))){
                $inSuitFiltPid[]=$fpv['pid']; //符合筛选条件的拍品pid集合
            }
        }
        // 通过筛选属性获取拍品pid集合[
        // 符合条件的拍品pid条件
        // if(!empty($inSuitFiltPid)){
            $listwhere = array('pid'=>array('in',$inSuitFiltPid));
        // }
        // 分页配置
        $rdata = $bidMap->auctionList($listwhere,$sort,$gt[1]);
        // 分配分页到模板
        $this->page = $rdata['page']; 
        // 分配正在拍卖拍品到模板
        $this->list = $rdata['list'];

        // 今日拍卖【
        $todayCateWhere = $section;
        // 当前拍卖的分类集合
        $todayCateWhere['cid'] = array('in',array_column($rdata['list'], 'cid'));
        $todayCate = $bidMap->where($todayCateWhere)->getField('cid',true);
        $clist = array();
        $cate_arr = array();
        if ($todayCate) {
            foreach ($todayCate as $bidk => $bcid) {
                $catPath = $cat->getPath($cate ,$bcid);
                $edpt = end($catPath);
                if ($edpt['cid'] !=$gt[0]) {
                    if(!in_array($catPath[1],$cate_arr)&&count($catPath)!=1){
                        $clist[] = array_merge($catPath[1],array('gt'=>$catPath[1]['cid'].'-'.$gt[1].'-'.$gt[2].'-'.$gt[3].'-'.getTopField($catPath[1]['cid']).'-'.$gt[5].'-'.$gt[6]));
                    }
                    $cate_arr[] = $catPath[1]; //如果获取过该分类的顶级分类id，就不在循环获取
                }
            }
        }
        $this->clist=$clist;
        // 今日拍卖】

        // 分配商品属性筛选条件到模板
        $this->filt_html=getFiltrateHtml($stage,$screen,$sort,$gt);
        // 分配条件到模板
        $this->gt=$gt;  
        // 分配地区条件到模板---------------------------------------------------------------------
        $this->reg_gt = explode('_', $gt[3]);
        // 分配当前频道ID到模板-------------------------------------------------------------------
        $this->cpid=$pid;
        // 生成分页显示的数量[
        $this->set_page = array(
        array('key'=>12,'sz'=>'12','href'=>U(ACTION_NAME,array('stage'=>$stage,'screen'=>$screen,'sort'=>$sort,'gt'=>$gt[0].'-'.'12'.'-'.$gt[2].'-'.$gt[3].'-'.$gt[4].'-'.$gt[5].'-'.$gt[6]))),
        array('key'=>16,'sz'=>'16','href'=>U(ACTION_NAME,array('stage'=>$stage,'screen'=>$screen,'sort'=>$sort,'gt'=>$gt[0].'-'.'16'.'-'.$gt[2].'-'.$gt[3].'-'.$gt[4].'-'.$gt[5].'-'.$gt[6]))),
        array('key'=>20,'sz'=>'20','href'=>U(ACTION_NAME,array('stage'=>$stage,'screen'=>$screen,'sort'=>$sort,'gt'=>$gt[0].'-'.'20'.'-'.$gt[2].'-'.$gt[3].'-'.$gt[4].'-'.$gt[5].'-'.$gt[6]))),
        array('key'=>40,'sz'=>'40','href'=>U(ACTION_NAME,array('stage'=>$stage,'screen'=>$screen,'sort'=>$sort,'gt'=>$gt[0].'-'.'40'.'-'.$gt[2].'-'.$gt[3].'-'.$gt[4].'-'.$gt[5].'-'.$gt[6]))),
        );
        // 生成分页显示的数量]

        // 分配未出价已出价地址
        $this->ynUrl = array(
            'y'=>U('index',array('stage'=>'biding','screen'=>$screen,'sort'=>$sort,'gt'=>$pid.'-12'.'-0'.'-0_0_0'.'-'.$topfilt.'-i'.'-y')),
            'n'=>U('index',array('stage'=>'biding','screen'=>$screen,'sort'=>$sort,'gt'=>$pid.'-12'.'-0'.'-0_0_0'.'-'.$topfilt.'-i'.'-n'))
        );
        // 分配竞拍、竞标地址
        $this->typeUrl = array(
            'pai'=>U('index',array('stage'=>$stage,'screen'=>$screen,'sort'=>$sort,'gt'=>$pid.'-12'.'-0'.'-0_0_0'.'-'.$topfilt.'-0'.'-a')),
            'biao'=>U('index',array('stage'=>$stage,'screen'=>$screen,'sort'=>$sort,'gt'=>$pid.'-12'.'-0'.'-0_0_0'.'-'.$topfilt.'-1'.'-a'))
        );
        // 分配频道名称
        $this->channelName = $category->where('cid='.$gt[0])->getField('name');
        // 上级分类【
        $pid = $category->where(array('cid'=>$gt[0]))->getField('pid');
        $precate = $category->where(array('cid'=>$pid))->find();
        $this->precate=$precate;
        // 上级分类【
        $catli = $category->where(array('pid'=>$gt[0]))->order('sort desc')->select();
        if(empty($catli)){
            $pid=$category->where(array('cid'=>$gt[0]))->getField('pid');
            $catli = $category->where(array('pid'=>$pid))->order('sort desc')->select();
        }
        $this->catli=$catli;
        $this->chalist= $cat->getPath($cate,$gt[0]);
        // $content = $this->fetch();
        $this->display();
    }
     /**
      * 所有成交的拍品
      */
     public function allend(){
        $bidMap=D('Auction');
        $eWstr = array();
        if(I('get.type')!=''){
            $eWstr['type']=I('get.type');
            $this->type = I('get.type');
        }
        // 屏蔽的商家
        if ($xUidarr = blackuser($this->cUid)) {
            $eWstr['sellerid'] = array('not in',$xUidarr);
        }
        $count = $bidMap->where($eWstr)->where(endbid(0))->count();
        $pConf = page($count,20);
        $endlist[0]['elistA'] = $bidMap->where($eWstr)->where(endbid(0))->limit($pConf['first'].','.$pConf['list'])->order('endtime desc')->select();
        $endlist[0]['abc'] = 2;

        // 分配分页到模板
        $this->endlist=$endlist;
        $this->page = $pConf['show'];
        $this->display();
     }
    // 频道分类页面
    public function channel(){
        $goods_category = M('goods_category');
        $channel = $goods_category->where(array('pid'=>0))->order('sort desc')->select();
        foreach ($channel as $ck => $cv) {
            $cate[$cv['cid']] = $goods_category->where(array('pid'=>$cv['cid']))->order('sort desc')->select();
        }
        $hot = $goods_category->where(array('pid'=>array('neq',0),'hot'=>"1"))->order('sort desc')->select();
        $this->channel=$channel;
        $this->cate=$cate;
        $this->hot=$hot;

        $this->display();
    }
    /**
    * 竞拍商品详情页面
    * @return [type] [description]
    */
    public function details(){
        // 为了预防拍卖会中拍品被删除导致轮拍失败
        $M = M('Auction');
        if(I('get.mid')){
            if($M->where(array('pid'=>I('get.pid')))->count()){
                $gtpid = I('get.pid');
            }else{
                $gtpid = $M->where(array('mid'=>I('get.mid'),'endstatus'=>0,'hide'=>0))->order('msort asc')->getField('pid');
            }
        }else{
            if(!$M->where(array('pid'=>I('get.pid'),'hide'=>0))->count()){
                $this->error('商品不存在或已被删除！',U('Index/index'));
                exit;
            }
            $gtpid = I('get.pid');
        }
        // 为了预防拍卖会中拍品被删除导致轮拍失败_end
        // 实例化用户表
        $member = M('Member');
        $aRecord = M('Auction_record');
        $goods_user = M('Goods_user');
        $D = D('Auction');
        $uid = $this->cUid;
        // 增加浏览一次【
        $M->where(array('pid'=>$gtpid))->setInc('clcount',1);
        $info = $D->where(array('pid'=>$gtpid))->find();
        // 增加浏览一次】

        // 分类路径【
        $category = M('Goods_category');
        $cat = new \Org\Util\Category();
        $cate = $category->order('sort desc')->select();
        $chalist = $cat->getPath($cate,$info['cid']);
        $this->chalist=$chalist;
        $gt[0]=$chalist[0]['cid'];
        // 分类路径】
        // 在拍条件
        $ingws = bidType('biding');
        $ingwhere = $ingws['bidType'];

        // 登陆用户需要的操作
        if ($this->cUid) {
            $footprint = M('member_footprint');
            $pidstr = $footprint->where(array('uid'=>$this->cUid))->getField('pidstr');
            if($pidstr){
                $pidarr = unserialize($pidstr);
                // 记录足迹【
                // 刷新访问的不进行记录
                if ($pidarr[0]!=$info['pid']) {
                    // 该次访问的拍品pid插入到前面
                    array_unshift($pidarr,$info['pid']); 
                    $footprint->where(array('uid'=>$this->cUid))->setField('pidstr',serialize($pidarr));
                }
                // 记录足迹】
 
                // 看了又看（只有登录的会读取显示正在拍卖的10个,30秒缓存【
                if (S('kan'.'_'.$uid.'_'.$info['pid'])) {
                    $kanlist = S('kan'.'_'.$uid.'_'.$info['pid']);
                }else{
                    // 从足迹中按照浏览数量从高到底进行排序
                    $kcount = array_count_values ($pidarr);
                    // 按照值进行排序
                    arsort($kcount);
                    // 获取键的集合
                    $goods = M('goods');
                    $kcount = array_keys($kcount);
                    $kanwnere = array('starttime'=>array('elt',time()),'endtime'=>array('egt',time()),'hide'=>0);
                    $kanlist = M('Auction')->where("pid in (".implode(',', $kcount).")")->where($kanwnere)->order("field(pid,".implode(',', $kcount).")")->field('pid,pname,gid,nowprice')->limit(5)->select();
                    foreach ($kanlist as $klkey => $klvl) {
                        $kanlist[$klkey]['pictures']=$goods->where(array('id'=>$klvl['gid']))->getField('pictures');
                    }
                    // 30秒缓存
                    S('kan'.'_'.$uid.'_'.$info['pid'],$kanlist,30);
                }
                $this->leftlist = $kanlist;
                // 看了又看（只有登录的会读取显示正在拍卖的10个，30秒缓存】
            }else{
                // 记录足迹【
                $fdata = array('uid'=>$this->cUid,'pidstr'=>serialize(array(I('get.pid'))));
                $footprint->add($fdata);
                // 记录足迹】
            }
            // 成交者进入该页面显示订单信息
            if ($info['uid'] == $this->cUid) {
                $this->oinfo = M('goods_order')->where(array('gid'=>$info['pid']))->find();
            }
        }else{
            // 没有登录显示相同分类的热门拍品,30秒缓存【
            if (S('similar'.'_'.$info['pid'])) {
                    $kanlist = S('similar'.'_'.$info['pid']);
            }else{
                $hotpidarr = $cat->getChildsId($cate,$chalist[0]['cid']);
                // 在拍条件
                $simwhere = $ingwhere;
                $simwhere['pid'] = array('in',$hotpidarr);
                $similar = $D->where($simwhere)->order('bidcount desc,endtime asc')->field('pid,pname,nowprice,pictures')->select();
                S('similar'.'_'.$info['pid'],$similar,30);
            }
            $this->leftlist = $similar;
            // 没有登录显示相同分类的热门拍品,30秒缓存】
        }

        // 推荐拍卖缓存30秒，读取12条【
        if (S('recdetails')) {
            $recommend = S('recdetails');
        }else{
            $tjwhere = $ingwhere;
            $tjwhere['recommend'] = 1;
            $recommend = $D->where($tjwhere)->order('bidcount desc,endtime asc')->field('pid,pname,nowprice,pictures')->limit(12)->select();
            S('recdetails',$recommend,30);
        }
        $this->recommend=$recommend;
        // 推荐拍卖缓存30秒，读取12条】

        // 分配屏蔽状态【
        $blacklist = M('blacklist');
        if ($blacklist->where(array('uid'=>$uid,'xid'=>$info['sellerid'],'selbuy'=>'sel'))->count()>0) {
            $black['sel'] = 1;
        }else{
            $black['sel'] = 0;
        }
        if ($blacklist->where(array('uid'=>$uid,'xid'=>$info['sellerid'],'selbuy'=>'buy'))->count()>0) {
            $black['buy'] = 1;
        }else{
            $black['buy'] = 0;
        }
        $this->black=$black;
        // 分配屏蔽状态】

        // 处理浮动价格
        $stepsize_type = $info['stepsize_type'];
        $stepsize = $info['stepsize'];
        $info['stepsize'] = setStep($info['stepsize_type'],$info['stepsize'],$info['nowprice']);
        // 首次出价不能小于起拍价，如果起拍价是0不能小于步长，其他不能小于当前价加步长
        if($info['uid']){
            $info['stepsized'] = $info['nowprice']+$info['stepsize'];
        }else{
            if($info['onset']>0){
                $info['stepsized'] = $info['onset'];
            }else{
                $info['stepsized'] = $info['stepsize'];
            }
            
        }

        // 图片字段数组化输出
        if ($info['pictures']) {
            $info['pictures'] = explode('|', $info['pictures']);
        }

        // 微信分享的图片地址
        $this->shimg = C('WEB_ROOT'). str_replace('./', '', C('UPLOADS_PICPATH').picRep($info['pictures'][0],1));

        // 分配拍品状态
        if($info['starttime']<=time()&&$info['endtime'] >= time()){
            $info['nstatus'] = 'ing';
            // 微信分享名称
            $share_title = $info['pname'].' '.'当前价:￥'.wipezero($info['nowprice']).' '.'至'.date('m月d日 H:i',$info['endtime']).'结束';
        }elseif ($info['endtime']<time()){
            $info['nstatus'] = 'end';
            // 微信分享名称
            $share_title = $info['pname'].' '.'成交价:'.wipezero($info['nowprice']);
        }elseif ($info['starttime']>time()) {
            $info['nstatus'] = 'fut';
            // 微信分享名称
            $share_title = $info['pname'].' '.'起拍价:'.wipezero($info['onset']).' '.date('m月d日 H:i',$info['starttime']).'开拍';
        }
        $this->share_descContent = DeleteHtml($info['description']);
        $this->share_title=$share_title;
        // 如果是拍卖会内拍品整合信息【
        $ntm = time();
        $mtdata = array();
        if($info['mid']!=0){
            $gpwr = array('mid'=>$info['mid']);
            $gpod = 'msort asc';
            $mtinfo = M('meeting_auction')->where($gpwr)->find();
            // 拍卖会状态
            if($mtinfo['starttime']<=$ntm && $mtinfo['endtime']>=$ntm){
                $mtstatus = 'ing';
            }elseif ($mtinfo['endtime']<$ntm) {
                $mtstatus = 'end';
            }elseif($mtinfo['starttime']>$ntm){
                $mtstatus = 'fut';
            }
            // 拍卖会内正在拍卖的
            $mbidw = array('mid'=>$info['mid'],'endtime'=>array('gt',$ntm));
            $mtnowpid = $M->where($mbidw)->order('msort asc')->getField('pid');
            // 下一件拍品信息
            $nexbid = $M->where(array('mid'=>$info['mid'],'msort'=>array('gt',$info['msort'])))->order('msort asc')->find();
            $mtdata=array(
                'mname'=>$mtinfo['mname'],
                'mtnowpid'=>$mtnowpid,
                'nexbid'=>$nexbid,
                'mtstatus'=>$mtstatus
            );
            $this->mtdata=$mtdata;
        }
        // 如果是拍卖会内拍品整合信息】
        // 结束生成订单
        // create_order($info);
        
        // 最后出价人
        $info['nickname']= nickdis($member->where('uid='. $info['uid'])->getField('nickname'));

        // 全部出价记录
        $recWhere = array('pid'=>$gtpid);
        $bidRecord = $aRecord->where($recWhere)->order('time desc,bided desc')->select();
        if($bidRecord){
            foreach ($bidRecord as $mk => $mv) {
                $bidRecord[$mk]['nickname'] = nickdis($member->where('uid='.$mv['uid'])->getField('nickname'));
                $bidRecord[$mk]['avatar'] = getUserpic($mv['uid'],2);
            }
        }

        // 分配出价记录和统计到模板
        $this->bidRecord=$bidRecord;

        // 领先人状态
        if ($info['endstatus']==0) {$this->lastRecord = 'lingxian';}elseif ($info['endstatus']==1) {$this->lastRecord = 'chengjiao';}

        //单品缴纳保证金
        $p_l_w = array('gid'=>$info['pid'],'uid'=>$uid,'g-u'=>'p-u');
        // 读取专场信息
        if($info['sid']!=0){
            $gpwr = array('sid'=>$info['sid']);
            $gpod = 'endtime desc';
            $special = M('special_auction')->where($gpwr)->find();
            if ($special['special_pledge_type']==0) {
                // 专场缴纳保证金条件
                $p_l_w = array('gid'=>$info['sid'],'uid'=>$uid,'g-u'=>'s-u');
            }
        }
        // 专场或者拍卖会显示上一件下一件拍卖
        if ($info['sid'] || $info['mid']) {
            $splist = $D->where($gpwr)->where(array('hide'=>0))->field('pid,pname,nowprice,pictures,pattern,pledge_type,onset,pledge,spledge,mpledge')->order($gpod)->select();
            $pnarr = array();
            foreach ($splist as $pk => $pv) {
                if ($pv['pid']==$info['pid']) {
                    $prek = $pk-1;
                    $nexk = $pk+1;
                    if ($prek>=0) {$pnarr['prev'] =  $splist[$pk-1];}
                    if ($nexk<count($splist)) {$pnarr['next'] =  $splist[$pk+1];}
                }
                $splist[$pk]['pledge']=pledgeShow($pv['pattern'],$pv['pledge_type'],$pv['onset'],$pv['pledge'],$pv['spledge'],$pv['mpledge']);
            }
            $this->splist=$splist;
        }
        $this->pnarr=$pnarr;
        $uFrezze = $goods_user->where($p_l_w)->field('pledge , limsum')->find();

        // 判断对该商品是否有权限【
        // 无权限操作
        $uLimit = array();
        if(!$uFrezze){
            if($uid){
                // 用户账户输出
                $uLimit = getwallet($uid);
                $uLimit['yn']=0;
                // 判断拍品所在模式--------------------------------------------
                if($info['sid']&&$special['special_pledge_type']==0){
                    // 专场模式且缴纳为专场缴纳
                    if($special['spledge'] <= $uLimit['count']){
                        $uLimit['yn']=1;
                    }else{
                        // 需要多少才能有权限
                        $uLimit['diff'] = round($special['spledge'] - $uLimit['count'],2);
                    }
                }else{
                  // 单品拍模式 
                  if(percent($info['pledge_type'],$info['onset'],$info['pledge']) <= $uLimit['count']){
                      $uLimit['yn']=1;
                  }else{
                    // 需要多少才能有权限
                    $uLimit['diff'] = round(percent($info['pledge_type'],$info['onset'],$info['pledge']) - $uLimit['count'],2);
                  }
                }
            }else{
                // 没有登录
            }
            
        // 有权限操作-------------------------------------------------------------------
        }else{
            $this->uFrezze=$uFrezze;
            // 我的出价记录
            $recWhere['uid'] = $uid;
            $myRecord = $aRecord->where($recWhere)->limit('10')->order('time desc,bided desc')->select();
            $this->myRecord=$myRecord;
            //我的出价次数
            $myCount=$aRecord->where(array('pid'=>$info['pid'],'uid'=>$uid))->count();
            $this->myCount=$myCount;
            $uLimit['yn']=1;
        }
        // 分配用户账户信息
        $this->uLimit=$uLimit;
        // 判断对该商品是否有权限【
        

        // 统计多少人出价
        $recordlist = M('Auction_record')->where(array('pid'=>$info['pid']))->getField('uid',true);
        $this->dsr = $info['caiyu']+count(array_flip($recordlist));
        // 统计多少人关注
        $this->tcount = $info['guanzhu']+M('attention')->where(array('gid'=>$info['pid'],'rela'=>'p-u'))->count();
        $this->gt=$gt;
        // 转换佣金显示
        $info['broker'] = brokerShow($info['broker_type'],$info['broker']);
        // 分配扩展字段
        $this->extends_con=getExtendsCon($info['cid'],$info['gid']);
        $info['content']=$info['content'];
        $info['steptime']=conversionM_S($info['steptime']);
        $info['deferred']=conversionM_S($info['deferred']);


        // 当前用户是否设置代理出价【
        if($uid){
            $agency = M('auction_agency');
            if($myagency = $agency->where(array('pid'=>$gtpid,'uid'=>$uid))->find()){
                $setagency = $myagency['status'];
                $myagprice=$myagency['price'];
            }else{
              // 3为未设置代理出价
               $setagency = 3; 
               $myagprice=$info['nowprice']+thricebid($stepsize_type,$stepsize,$info['nowprice'],0);
            }
            $myname = nickdis($member->where(array('uid'=>$uid))->getField('nickname'));
        }else{
            $setagency = 3;
            $myagprice=$info['nowprice']+thricebid($stepsize_type,$stepsize,$info['nowprice'],0);
            $mycode = get_code(6,0);
            $myname = '游客_'.$mycode;
            $myaccount = $mycode;
        }

        $this->setagency=$setagency;
        $this->myagprice=$myagprice;
        // 分配当前用户名
        $this->myname=$myname;
        // 当前用户是否设置代理出价】
        // 分配关注商品状态
        if(M('attention')->where(array('uid'=>$uid,'gid'=>$gtpid))->count()){$usgz=1;}else{$usgz=0;}
        $this->usgz=$usgz;

        // 分配拍品提醒状态
        if(M('scheduled')->where(array('uid'=>$uid,'pid'=>$gtpid,'stype'=>$info['nstatus']))->count()){$ustx=1;}else{$ustx=0;}
        $this->ustx=$ustx;

        // 分配关注卖家状态
        if(M('attention_seller')->where(array('uid'=>$uid,'sellerid'=>$info['sellerid']))->count()){$gzuser=1;}else{$gzuser=0;}
        $this->gzuser=$gzuser;

        // 卖家信息读取
        $seller = $member->where(array('uid'=>$info['sellerid']))->field('uid,qq,organization,avatar,avatar_sel,score')->find();
        $seller['leval'] = getlevel($seller['score']);
        // 卖家信誉
        $seller['credit_score'] = getstarval(M('goods_evaluate'),array('sellerid'=>$info['sellerid']));
        $this->seller=$seller;
        // 必要数据写入缓存【
        $redata = array(
            'uid' => $info['uid'],
            'pname' => $info['pname'],
            'price' => $info['price'],
            'nowprice' => $info['nowprice'],
            'endtime' => $info['endtime'],
            'starttime' => $info['starttime'],
            'nickname' =>$info['nickname'],
            'endstatus' =>$info['endstatus']
            );
        S(C('CACHE_FIX').'bid'.$gtpid,$redata);
        // 必要数据写入缓存】
        
        // 提醒方式设置【
        $alerttype = M('member')->where(array('uid'=>$uid))->getField('alerttype');
        if($alerttype){$this->alerttype = explode(',', $alerttype);}
        // 提醒方式设置】
        // 保证金处理
        $info['pledge'] = pledgeShow($info['pattern'],$info['pledge_type'],$info['onset'],$info['pledge'],$info['spledge'],$info['mpledge']);
        $this->uid=$uid;
        $this->info=$info;
        $this->display();
    }
    // 出价记录
    public function record(){
        $gtpid = I('get.pid');
        $aRecord = M('Auction_record');
        $member = M('member');
        $recWhere = array('pid'=>$gtpid);
        $count = $aRecord->where($recWhere)->count();
        $pConf = page($count,C('PAGE_SIZE'));
        $bidRecord = $aRecord->where($recWhere)->limit($pConf['first'].','.$pConf['list'])->order('time desc,bided desc')->select();
        foreach ($bidRecord as $mk => $mv) {
          $bidRecord[$mk]['nickname'] = nickdis($member->where('uid='.$mv['uid'])->getField('nickname'));
        }
        $this->info = M('Auction')->where(array('pid'=>$gtpid))->find();
        $this->bidRecord=$bidRecord;
        $this->page = $pConf['show']; 
        $this->display();
     }



     // 图片放大列表
     public function details_img(){
        $info = D('Auction')->where(array('pid'=>I('get.pid')))->find();
        if ($info['pictures']) {
            $info['pictures'] = explode('|', $info['pictures']);
        }
        $this->info=$info;
        $this->display();
     }
     /**
      * -------ajax出价
      */
    public function bid(){
        if (IS_POST) {
            $bidMap = D('Auction');
            $m = M('Member');
            $uid = $this->cUid;
            $postData = I('post.');
            if($postData['uid']!=$uid){
                echojson(array("status" => 2, "msg" => '请刷新页面重试！','url'=>get_url()));
                exit;
            }
            $bidObj=$bidMap->where(array('pid'=>$postData['pid']))->find();
            if($uid==$bidObj['sellerid']){
                echojson(array("status" => 2, "msg" => '您不能参拍自己的拍品！'));
                exit;
            }
            // 检查系统配置是否需要认证手机号
            $verify = $m->where(array('uid'=>$uid))->getField('verify_mobile');
            if(C('Auction.verify_mobile')==1 && $verify==0){
                echojson(array("status" => 4,'title'=>'手机认证', "msg" => '<strong>您的手机号未认证，请先认证后进行出价！</strong><br/>现在去认证...','skipurl'=>U('Member/safety',array('option'=>'bindphone','pid'=>$postData['pid']),'html',true)));
                exit;
            }
            // 检查系统配置是否需要实名认证
            $idcheck = $m->where(array('uid'=>$uid))->getField('idcard_check');
            if(C('Auction.realname')==1 && $idcheck!=2){
                $url = U('Member/safety',array('option'=>'idcard'),'html',true);
                if ($idcheck==1) {
                    $msg = '<strong>请等待实名认证后再参与拍卖！</strong>';
                    $url = '';
                }elseif ($idcheck==3) {
                    $msg = '<strong>您提交的实名认证未通过，请先实名认证后在参与拍卖！</strong><br/>现在去重新提交...';
                }else{
                    $msg = '<strong>请先实名认证后在参与拍卖！</strong><br/>现在去认证...';
                }
                echojson(array("status" => 4,'title'=>'实名认证', "msg" => $msg,'skipurl'=>$url));
                exit;
            }
            $auth = 0;
            //冻结用户保证金
            $g_uMap=M('Goods_user');
            // 单品缴纳的查询条件
            $g_uW = array('gid'=>$postData['pid'],'uid'=>$uid,'g-u'=>'p-u');
            // 需要缴纳单品保证金的金额
            $freeze = percent($bidObj['pledge_type'],$bidObj['onset'],$bidObj['pledge']);
            $jntp = '拍品';
            // 保证金冻结方式【
            if ($bidObj['sid']) {
                // 专场冻结
                $special = M('special_auction')->where(array('sid'=>$bidObj['sid']))->find();
                if($special['special_pledge_type']==0){
                    $g_uW = array('gid'=>$bidObj['sid'],'uid'=>$uid,'g-u'=>'s-u');
                    $freeze = $special['spledge'];
                    $gp = 1;
                    $jntp = '专场';
                }
            }elseif ($bidObj['mid']) {
                // 拍卖会冻结
                $meeting = M('meeting_auction')->where(array('mid'=>$bidObj['mid']))->find();
                if($meeting['meeting_pledge_type']==0){
                    $g_uW = array('gid'=>$bidObj['mid'],'uid'=>$uid,'g-u'=>'m-u');
                    $freeze = $meeting['mpledge'];
                    $gp = 1;
                    $jntp = '拍卖会';
                }
            }
            // 保证金冻结方式】
            if(!$g_uMap->where($g_uW)->count()){
                // 如果保证金为0元，直接写入出价权限进行出价
                if($freeze==0){
                    $auth = D('Auction')->freeze_pledge_buy($m,$g_uMap,$bidObj,'',0,$g_uW,$jntp);
                }else{
                    // 用户账户信息----------------------------------
                    $ufield=array('uid','wallet_pledge','wallet_pledge_freeze','wallet_limsum','wallet_limsum_freeze');
                    $uLimit = $m->where('uid='.$uid)->field($ufield)->find();
                    // 首先冻结信用额度
                    $live_limsum = $uLimit['wallet_limsum']-$uLimit['wallet_limsum_freeze'];//可用信用额度
                    $live_pledge = $uLimit['wallet_pledge']-$uLimit['wallet_pledge_freeze'];//可用保证金
                    // 判断保证金是否足够缴纳保证金
                    if (($live_limsum+$live_pledge)<$freeze) {
                        echojson(array("status" => 5, "msg" => '保证金不足，请充值！'));
                        exit;
                    }
                    // 信誉额度足够缴纳保证金
                    $chazhi = $live_limsum - $freeze;
                    if($chazhi>=0){
                        // 先冻结信用额度
                        $strlst = '缴纳保证金后您可以参与该'.$jntp.'内所有拍品的出价格！<br>是否继续？';
                        // 提示用户是否同意冻结保证金
                        if(empty($postData['agr'])){
                            $strper = '参与竞价需缴纳'.$jntp.'保证金<strong>￥'.$freeze.'</strong>（冻结信用额度￥'.$freeze.'）！<br>';
                            // 专场和拍卖会缴纳扣除提醒
                            if($gp){
                                echojson(array("status" => 3, "msg" => $strper.$strlst));
                                exit;
                            // 单品拍扣除提醒
                            }else{
                              echojson(array("status" => 3, "msg" => $strper.'是否继续？'));
                              exit;
                            }
                        }else{
                            $auth = D('Auction')->freeze_pledge_buy($m,$g_uMap,$bidObj,'',$freeze,$g_uW,$jntp);
                        }
                    // 可用信用额度不足以缴纳保证金
                    }else{
                        // 可用信誉额度大于0小于需要缴纳的保证金，则冻结信誉额度后冻结保证金
                        if($live_limsum>0){
                            // 提示用户是否同意冻结保证金
                            if(empty($postData['agr'])){
                                $strper = '参与竞价需缴纳'.$jntp.'保证金<strong>￥'.$freeze.'</strong>（冻结信用额度￥'.$freeze.'；保证金￥'.abs($chazhi).'）！<br>';
                                // 专场和拍卖会缴纳扣除提醒
                                if($gp){
                                    echojson(array("status" => 3, "msg" => $strper.$strlst));
                                    exit;
                                // 单品拍扣除提醒
                                }else{
                                    echojson(array("status" => 3, "msg" => $strper.'是否继续？'));
                                    exit;
                                }
                            }else{
                                $auth = D('Auction')->freeze_pledge_buy($m,$g_uMap,$bidObj,abs($chazhi),$live_limsum,$g_uW,$jntp);
                            }
                        // 可用余额小于等于0时候冻结保证金
                        }else{
                        // 提示用户是否同意冻结保证金
                            if(empty($postData['agr'])){
                                $strper = '参与竞价需缴纳'.$jntp.'保证金<strong>￥'.$freeze.'</strong>（冻结保证金￥'.$freeze.'）！<br>';
                                // 专场和拍卖会缴纳扣除提醒
                                if($gp){
                                    echojson(array("status" => 3, "msg" => $strper.$strlst));
                                    exit;
                                // 单品拍扣除提醒
                                }else{
                                    echojson(array("status" => 3, "msg" => $strper.'是否继续？'));
                                    exit;
                                }
                            }else{
                                $auth = D('Auction')->freeze_pledge_buy($m,$g_uMap,$bidObj,$freeze,'',$g_uW,$jntp);
                            }
                        }
                        // 002end
                    }
                }
              // 有出价权限操作
              // 001else
            }else{
                $auth = 1;    
            }
            if($auth){
                // 判断出价是否合法-------------------------------------
                if(!preg_match('/^[0-9][0-9]*(\.[0-9]{0,2})?$/', $postData['bidPric'])){
                  echojson(array("status" => 2, "msg" => '必须为正数、数字且小数位最多两位'));
                  exit;
                }
                // 判断拍卖是否已开始
                if($bidObj['starttime']>microtime(true)){
                    echojson(array("status" => 3, "msg" => '当前拍品未开始！请刷新页面！'));
                    exit;
                }
                // 判断拍卖是否已结束
                if($bidObj['endtime']<microtime(true)){
                    echojson(array("status" => 3, "msg" => '当前拍品已结束！请刷新页面！'));
                    exit;
                }
                echojson(D('Auction')->bidprc($postData));
            }  
        }//结束post
    }
    // 冻结卖家保证金提醒
    public function freeze_remind(){
        if(IS_POST){
            $pid = I('post.pid');
            $uid = I('post.uid');
            $member = M('Member');
            $g_uMap=M('Goods_user');
            $bidObj = M('Auction')->where(array('pid'=>$pid))->find();

            $g_uW = array('gid'=>$pid,'uid'=>$uid,'g-u'=>'p-u');
            $jntp = '拍品';
            $jecstr = '参拍“'.$bidObj['pname'].'”';
            $jecstrlink = '参拍“<a href="'.U('Home/Auction/details',array('pid'=>$bidObj['pid'],'aptitude'=>1),'html',true).'">'.$bidObj['pname'].'</a>”';
            
            if ($bidObj['sid']) {
                // 专场冻结
                $special = M('special_auction')->where(array('sid'=>$bidObj['sid']))->find();
                if($special['special_pledge_type']==0){
                    $jntp = '专场';
                    // 冻结专场保证金站内信息
                    $g_uW = array('gid'=>$bidObj['sid'],'uid'=>$uid,'g-u'=>'s-u');
                    $jecstr = '参拍专场“'.$special['sname'].'”下拍品“'.$bidObj['pname'].'”';
                    $jecstrlink = '参拍专场“<a href="'.U('Home/Special/speul',array('sid'=>$special['sid'],'aptitude'=>1),'html',true).'">'.$special['sname'].'</a>”下拍品“<a href="'.U('Auction/details',array('pid'=>$bidObj['pid'],'aptitude'=>1),'html',true).'">'.$bidObj['pname'].'</a>”';
                }
            }elseif ($bidObj['mid']) {
                // 拍卖会冻结
                $meeting = M('meeting_auction')->where(array('mid'=>$bidObj['mid']))->find();
                if($meeting['meeting_pledge_type']==0){
                    $jntp = '拍卖会';
                    // 冻结专场保证金站内信息
                    $g_uW = array('gid'=>$bidObj['mid'],'uid'=>$uid,'g-u'=>'m-u');
                    $jecstr = '参拍专场“'.$meeting['mname'].'”下拍品“'.$bidObj['pname'].'”';
                    $jecstrlink = '参拍专场“<a href="'.U('Home/meeting/meetul',array('mid'=>$meeting['mid'],'aptitude'=>1),'html',true).'">'.$meeting['mname'].'</a>”下拍品“<a href="'.U('Auction/details',array('pid'=>$bidObj['pid'],'aptitude'=>1),'html',true).'">'.$bidObj['pname'].'</a>”';
                }
            }
            $finfo = $g_uMap->where($g_uW)->find();

            $wallet = $member->where(array('uid'=>$finfo['uid']))->field('wallet_pledge,wallet_pledge_freeze')->find();
            $usable = $wallet['wallet_pledge']-$wallet['wallet_pledge_freeze'];
            if($finfo['pledge']>0){
            // 提醒通知冻结保证金【
                // 微信提醒内容
                $wei_pledge_freeze['tpl'] = 'walletchange';
                $wei_pledge_freeze['msg']=array(
                    "url"=>U('Member/wallet','','html',true), 
                    "first"=>'您好，参与'.$jntp.'竞价缴纳保证金！',
                    "remark"=>'查看账户记录>>',
                    "keyword"=>array('余额账户','冻结保证金',$jecstr,'-'.$finfo['pledge'].'元',$usable.'元')
                );
                // 账户类型，操作类型、操作内容、变动额度、账户余额
                // 站内信提醒内容
                $web_pledge_freeze = array(
                    'title'=>'参与'.$jntp.'拍竞价冻结保证金',
                    'content'=>$jecstrlink.'冻结保证金【'.$finfo['pledge'].'元】'
                    );
                // 短信提醒内容
                if(mb_strlen($bidObj['pname'],'utf-8')>10){
                    $newname = mb_substr($bidObj['pname'],0,10,'utf-8').'...';
                }else{
                    $newname = $bidObj['pname'];
                }
                $note_pledge_freeze = '参与拍卖“'.$newname.'”'.$jecstr.'保证金【'.$finfo['pledge'].'元】，订单号'.$pledge_data['order_no'].'货款，您可以登陆平台查看账户记录。';
                // 邮箱提醒内容
                $mail_pledge_freeze['title'] = $jecstr.'冻结保证金';
                $mail_pledge_freeze['msg'] = '您好：<br/><p>'.$jecstrlink.'冻结保证金【'.$finfo['pledge'].'】</p><p>您可以<a target="_blank" href="'.U('Home/Member/wallet','','html',true).'">查看账户记录</a></p>';
                sendRemind($member,M('Member_weixin'),array(),array($finfo['uid']),$web_pledge_freeze,$wei_pledge_freeze,$note_pledge_freeze,$mail_pledge_freeze,'buy');
            // 提醒通知冻结保证金【
            }
            if($finfo['limsum']>0){
            // 提醒通知冻结信誉额度【
                // 微信提醒内容
                $wei_limsum_freeze['tpl'] = 'walletchange';
                $wei_limsum_freeze['msg']=array(
                    "url"=>U('Home/Member/wallet',array('option'=>'limsum'),'html',true), 
                    "first"=>'您好，参与'.$jntp.'拍卖已缴纳信誉额度！',
                    "remark"=>'查看账户记录>>',
                    "keyword"=>array('信誉额度账户','冻结信誉额度',$jecstr,'-'.$finfo['limsum'].'元',$usable.'元')
                );
                // 账户类型，操作类型、操作内容、变动额度、账户余额
                // 站内信提醒内容
                $web_limsum_freeze = array(
                    'title'=>'参与'.$jntp.'竞价冻结信誉额度',
                    'content'=>$jecstrlink.'冻结信誉额度【'.$finfo['limsum'].'元】'
                    );
                // 短信提醒内容
                if(mb_strlen($bidObj['pname'],'utf-8')>10){
                    $newname = mb_substr($bidObj['pname'],0,10,'utf-8').'...';
                }else{
                    $newname = $bidObj['pname'];
                }
                $note_limsum_freeze = '参与拍卖“'.$newname.'”'.$jecstr.'信誉额度【'.$finfo['limsum'].'元】，订单号'.$limsum_data['order_no'].'货款，您可以登陆平台查看账户记录。';
                // 邮箱提醒内容
                $mail_limsum_freeze['title'] = $jecstr.'信誉额度';
                $mail_limsum_freeze['msg'] = '您好：<br/><p>'.$jecstrlink.'冻结信誉额度【'.$finfo['limsum'].'】</p><p>您可以<a target="_blank" href="'.U('Home/Member/wallet',array('option'=>'limsum'),'html',true).'">查看账户记录</a></p>';

                sendRemind($member,M('Member_weixin'),array(),array($finfo['uid']),$web_limsum_freeze,$wei_limsum_freeze,$note_limsum_freeze,$mail_limsum_freeze,'buy');
            // 提醒通知冻结信誉额度【
            }
        }else{
            $this->error('页面不存在');
        }

    }

    // 出价成功异步提醒用户
    public function send_remind(){
        if(IS_POST){
            $pid = I('post.pid');
            $member = M('member');
            $where = array('pid'=>$pid);
            $info = D('Auction')->where($where)->find();
            $record = M('Auction_record')->where($where)->order('time desc,bided desc')->limit(2)->select();
            $recordA = $record[0];
            $recordB = $record[1];

        // 出价被超越提醒用户/出价成功提醒【
            if(mb_strlen($info['pname'],'utf-8')>15){
                $newname = mb_substr($info['pname'],0,15,'utf-8').'...';
            }else{
                $newname = $info['pname'];
            }
            $link = '拍品：“<a target="_blank" href="'.U('Home/Auction/details',array('pid'=>$info['pid'],'aptitude'=>1),'html',true).'">'.$info['pname'].'</a>”';

            // 出价被超越【
            if (!empty($recordB)) {
                // 微信提醒内容
                $wei_surpass['tpl'] = 'surpass';
                $wei_surpass['msg']=array(
                    "url"=>U('Auction/details',array('pid'=>$info['pid']),'html',true), 
                    "first"=>"您好，您的出价【".$recordB['bided']."元】已被超过。",
                    "remark"=>'立即前往加价',
                    "keyword"=>array($info['pname'],$info['nowprice'].'元'),
                );
                // 站内信提醒内容
                $web_surpass = array(
                    'title'=>'竞拍出价被超越',
                    'content'=>'您参拍'.$link.'出价【'.$recordB['bided'].'元】已被超过。'
                );
                // 短信提醒内容
                $note_surpass = '您参拍“'.$newname.'”的出价【'.$recordB['bided'].'元】已被超越，您可以登陆网站继续加价';
                // 邮箱提醒内容
                $mail_surpass['title'] = '竞拍出价被超越通知';
                $mail_surpass['msg'] = '您好：<br/><p>您参与竞拍'.$link.'的出价【'.$recordB['bided'].'元】被超越。</p><p>请<a target="_blank" href="'.U('Home/Login/index','','html',true).'">登陆</a>网站继续加价！</p>';

                sendRemind(M('Member'),M('Member_weixin'),array(),array($recordB['uid']),$web_surpass,$wei_surpass,$note_surpass,$mail_surpass,'buy');
            }
            // 出价被超越】

            // 提醒卖家拍品出价更新【
                // 微信提醒内容
                $wei_youren['tpl'] = 'surpass';
                $wei_youren['msg']=array(
                    "url"=>U('Auction/details',array('pid'=>$info['pid']),'html',true), 
                    "first"=>"您好，拍品当前价【".$info['nowprice']."元】，目前领先。",
                    "remark"=>'查看拍品详情',
                    "keyword"=>array($info['pname'],$info['nowprice'].'元')
                );
                // 站内信提醒内容
                $web_youren = array(
                    'title'=>'拍品出价更新',
                    'content'=>$link.'当前价【'.$info['nowprice'].'元】，目前领先'
                    );
                // 短信提醒内容
                $note_youren = '拍品“'.$newname.'”当前价【'.$info['nowprice'].'元】成功！';
                // 邮箱提醒内容
                $mail_youren['title'] = '拍品出价更新';
                $mail_youren['msg'] = '您好：<br/><p>'.$web_youren['content'].'</p><p>请<a target="_blank" href="'.U('Home/Login/index','','html',true).'">登陆</a>网站查看详情！</p>';

                sendRemind(M('Member'),M('Member_weixin'),array(),array($info['sellerid']),$web_youren,$wei_youren,$note_youren,$mail_youren,'sel');
            // 提醒卖家拍品出价更新】
            // 出价成功【
                // 微信提醒内容
                $wei_success['tpl'] = 'bidsuccess';
                $wei_success['msg']=array(
                    "url"=>U('Auction/details',array('pid'=>$info['pid']),'html',true), 
                    "first"=>"您好，您已成功出价【".$recordA['bided']."元】，目前领先。",
                    "remark"=>'查看拍品详情',
                    "keyword"=>array($info['pname'],date('Y年m月d日 H:i',$info['endtime']))
                );
                // 站内信提醒内容
                $web_success = array(
                    'title'=>'竞拍出价成功',
                    'content'=>'您参拍'.$link.'出价【'.$recordA['bided'].'元】成功！'
                    );
                // 短信提醒内容
                $note_success = '您参拍“'.$newname.'”出价【'.$recordA['bided'].'元】成功，当出价被超越将提醒您继续出价！';
                // 邮箱提醒内容
                $mail_success['title'] = '竞拍出价成功';
                $mail_success['msg'] = '您好：<br/><p>您参与竞拍'.$link.'已成功出价【'.$recordA['bided'].'元】，目前领先。</p><p>请<a target="_blank" href="'.U('Home/Login/index','','html',true).'">登陆</a>网站查看详情！</p>';

                sendRemind(M('Member'),M('Member_weixin'),array(),array($recordA['uid']),$web_success,$wei_success,$note_success,$mail_success,'buy');
            // 出价成功】
        // 出价被超越提醒用户/出价成功提醒】
        }else{
            $this->error('页面不存在');
        }
    }




    // public function deltest(){
    //     M('auction_agency')->where(array('uid'=>89,'pid'=>238))->delete();
    //     M('auction_record')->where(array('pid'=>238))->delete();
    //     M('auction')->where(array('pid'=>238))->setField('uid',0);
    //     S(C('CACHE_FIX').'bid'.'139',null);
    //     if(M('goods_user')->where(array('uid'=>89,'gid'=>238))->delete()){
    //       $this->success('删除成功');
    //     }

    // }
    // 取消代理出价
    public function cancel_agency(){
        if(IS_POST){
            $data = array('agency_uid'=>0,'agency_price'=>0);
            $auction_agency = M('auction_agency');
            M('auction')->where(array('agency_uid'=>I('post.uid'),'pid'=>I('post.pid')))->setField($data);
            $where = array('pid'=>I('post.pid'),'uid'=>I('post.uid'));
            if($auction_agency->where($where)->count()){
                if($auction_agency->where($where)->delete()){
                    echojson(array('status'=>1));
                }else{
                    echojson(array('status'=>0));
                }
            }else{
                echojson(array('status'=>1));
            }
        }else{
            $this->error('页面不存在');
        }

    }
    // 客户已知代理出价结束状态
    public function iknow(){
        if(IS_POST){
            $auction_agency = M('auction_agency');
            $where = array('pid'=>I('post.pid'),'uid'=>I('post.uid'));
            if(M('auction_agency')->where($where)->count()){
                if(M('auction_agency')->where($where)->delete()){
                    echojson(array('status'=>1));
                }else{
                    echojson(array('status'=>0));
                }
            }else{
                echojson(array('status'=>1));
            }
            
        }else{
            $this->error('页面不存在');
        }

    }
     // 获取当前拍品时间
     public function ajaxGetTime(){
        if(IS_POST){
          session_write_close();
          $pid=I('post.pid');
          $bidS = S(C('CACHE_FIX').'bid'.$pid);
          $rtime = array('starttime'=>$bidS['starttime'],'endtime'=>$bidS['endtime'],'nowtime'=>time());
          echojson($rtime);
        }else{
            $this->error('页面不存在');
        }
     }
    // --------时间结束提交地址
    public function checksucc(){
        if(IS_POST){
            session_write_close();
            $pid=I('post.pid');
            $bidS = S(C('CACHE_FIX').'bid'.$pid);
            if($bidS['endtime']<time()){
                if($bidS['nowprice']>=$bidS['price']&&$bidS['uid']!=0){
                    if($bidS['endstatus']!=4){
                        $nickname = $bidS['nickname'];
                        echojson(array('status'=>1,'uid'=>$bidS['uid'],'nickname'=>$nickname,'money'=>$bidS['nowprice'],'pname'=>$bidS['pname']));
                    }else{
                        echojson(array('status'=>3,'pname'=>$bidS['pname']));
                    }
                // 流拍处理
                }else{
                    if($bidS['endstatus']!=4){
                        echojson(array('status'=>2,'pname'=>$bidS['pname']));
                    }else{
                        echojson(array('status'=>3,'pname'=>$bidS['pname']));
                    }
                }
            }else{
                echojson(array('status'=>0,'now_time'=>time(),'end_time'=>$bidS['endtime']));
            }
        }else{
            $this->error('页面不存在');
        }

    }
    // 关注
    public function attention(){
        if(IS_POST){
        if(I('post.uid')){
            $att=M('attention');
            $data = array(
                'gid'=>I('post.pid'),
                'uid'=>I('post.uid'),
                'rela'=>'p-u'
                );
            if(!$att->where($data)->count()){
              if($att->add($data)){
                echojson(array('status'=>1,'msg'=>'已关注拍卖'));
              }
            }else{
              if($att->where($data)->delete()){
                echojson(array('status'=>1,'msg'=>'已取消关注拍卖'));
              }
            }
        }else{
          echojson(array('status'=>0,'msg'=>'您没有登陆，请登录后进行设置！'));
        }
        }else{
            $this->error('页面不存在');
        }
    }
    // 提醒
    public function scheduled(){
        if(IS_POST){
        if(I('post.uid')){
            if(I('post.stype')!='end'){
                if(I('post.stype')=='fut'){$rep="开拍";}else{$rep="结束";}

                $att=M('scheduled');
                $data = array(
                    'pid'=>I('post.pid'),
                    'uid'=>I('post.uid'),
                    'stype'=>I('post.stype')
                    );
                if(!$att->where($data)->count()){
                  if($att->add($data)){
                    echojson(array('status'=>1,'info'=>'已设置'.$rep.'提醒！<br>系统会在<strong>'.$rep.'前5分钟</strong>内提醒您参与拍卖！'));
                  }
                }else{
                  if($att->where($data)->delete()){
                    echojson(array('status'=>1,'info'=>'已取消'.$rep.'提醒'));
                  }
                }
            }else{
                echojson(array('status'=>0,'info'=>'商品已结束，不支持设置提醒！'));
            }
            
        }else{
          echojson(array('status'=>0,'info'=>'您没有登陆，请登录后进行设置！'));
        }
        }else{
            $this->error('页面不存在');
        }
    }

}