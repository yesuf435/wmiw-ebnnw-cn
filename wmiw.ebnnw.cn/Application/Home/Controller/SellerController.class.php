<?php
namespace Home\Controller;
use Think\Controller;
class SellerController extends CommonController {
    public function _initialize() {
        parent::_initialize();
        if (!$this->cUid) {
            $this->redirect("Login/index");
            exit;
        }
        if ($this->isbc && !$this->myidentity) {
            $this->error('不存在的页面！');
        }
    }
    public function index(){
        // 卖家信息【
        $info=M('Member')->where(array('uid'=>$this->cUid))->find();
        $info['leval'] = getlevel($info['score']);
        $credit_score = getstarval(M('goods_evaluate'),array('sellerid'=>$this->cUid));
        $this->credit_score=$credit_score;
        $this->info=$info;
        // 卖家信息】
        // 日历显示【
        $showdate['ym'] = date('Y-m',time());
        $showdate['d'] = date('d',time());
        $charr = array('天','一','二','三','四','五','六');
        $showdate['week'] = '星期'.$charr[date('w',time())];
        $this->showdate=$showdate;
        // 日历显示】

        // 卖家订单【
        // 支付状态 0：待支付 1：已支付待发货 2：已发货待收货3：已收货待评价 4：已评价卖家 5：申请退货 6：同意退货 7：不同意退货 8：买家已发货 9：卖家确认收货 10：已互评
            $Dorder = D('GoodsOrder');
            $orderlist = array();
            $mywhere = array('sellerid'=>$this->cUid);
            // 待付款
            $fukuai = $mywhere;
            $fukuai['status'] = 0;
            $fukuai['deftime1st'] = 0;
            $ocount['fukuai']=$Dorder->where($fukuai)->count();
            // 待发货
            $fahuo = $mywhere;
            $fahuo['status'] = 1;
            $fahuo['deftime2st'] = 0;
            $ocount['fahuo']=$Dorder->where($fahuo)->count();
            // 待收货
            $shouhuo = $mywhere;
            $shouhuo['status'] = 2;
            $shouhuo['deftime3st'] = 0;
            $ocount['shouhuo']=$Dorder->where($shouhuo)->count();
            // 待评价
            $pingjia = $mywhere;
            $pingjia['status'] = 4;
            $shouhuo['deftime10st'] = 0;
            $ocount['pingjia']=$Dorder->where($pingjia)->count();
            // 退货
            $tuihuo = $mywhere;
            $tuihuo['status'] = 5;
            $ocount['tuihuo']=$Dorder->where($tuihuo)->count();
            $this->ocount = $ocount;
        // 卖家订单【

        // 模板控制
            $this->avg=C('Auction.meeting_page')+C('Auction.special_page')+1;
        $this->display();
    }

    public function seller_info(){
        if(IS_POST){
            $data = I('post.data');
            $source = I('get.source')?I('get.source'):'index';
            // 去除字符串两边空格
            $data['organization'] = trim(html_entity_decode($data['organization']),chr(0xc2).chr(0xa0));
            $member = M('Member');
            $uid = $this->cUid;
            $req = $member->where(array('organization'=>$data['organization'],'uid'=>array('neq',$uid)))->find();
            if ($req) {
                 echojson(array('status' => 0, 'info' => '卖家名称被占用，请更换'));
                 exit;
            }
            $rst = $member->save($data);
            if($rst){
                S('nickname'.$this->cUid,NULL);
                echojson(array('status' => 1, 'info' => '已修改','url'=>U('Seller/'.$source)));
            }else{
                if ($rst==0) {
                    echojson(array('status' => 0, 'info' => '未做修改'));
                }else{
                    echojson(array('status' => 0, 'info' => '修改失败','url'=>U('Seller/index')));
                }
            }
        }else{
            $info = M('Member')->where(array('uid'=>$this->cUid))->field('uid,account,avatar_sel,organization,intro')->find();
            $this->info = $info;
            $this->display();
        }
    }
    // 我的粉丝
    public function myfensi(){
        $bluidarr = M('blacklist')->where(array('uid'=>$this->cUid,'selbuy'=>'buy'))->getField('xid',true);
        $uidarr = M('attention_seller')->where(array('sellerid'=>$this->cUid))->order('time desc')->getField('uid',true);
        $member=M('member');
        $where = array('uid'=>array('in',$uidarr));
        $count = $member->where($where)->count();
        $pConf = page($count,C('PAGE_SIZE'));
        $list = $member->where($where)->limit($pConf['first'].','.$pConf['list'])->select();
        foreach ($list as $lk => $lv) {
            if(in_array($lv['uid'], $bluidarr)){
                $list[$lk]['black'] = 1;
            }else{
                $list[$lk]['black'] = 0;
            }
        }
        $this->page = $pConf['show'];
        $this->list = $list;
        $this->display('Common:userbox');
    }
    // 屏蔽买家
    public function blacklist(){
        $uidarr = M('blacklist')->where(array('uid'=>$this->cUid,'selbuy'=>'buy'))->order('time desc')->getField('xid',true);
        $member=M('member');
        $where = array('uid'=>array('in',$uidarr));
        $count = $member->where($where)->count();
        $pConf = page($count,C('PAGE_SIZE'));
        $list = $member->where($where)->limit($pConf['first'].','.$pConf['list'])->select();
        $this->page = $pConf['show'];
        $this->list = $list;
        $this->display('Common:userbox');
    }
     // 待发布拍卖
     public function goods_list(){
        $channel = M('goods_category')->where('pid=0')->select(); //读取频道
        $this->channel=$channel; //分配频道
        $M = M("Goods");

        $where=array('sellerid'=>$this->cUid);
        $gidarr = D("Auction")->where($where)->getField('gid',true);
        if($gidarr){
            $where['id']=array('not in',$gidarr);
        }
        $count = $M->where($where)->count();
        $pConf = page($count,C('PAGE_SIZE'));
        $this->page = $pConf['show'];
        $this->list = D("Goods")->listGoods($pConf['first'], $pConf['list'],$where);
        C('TOKEN_ON',false);
        $this->display();
     }
     //------删除商品
    public function goods_del() {
        $goods = M("Goods");
        $where = array('id'=>I('get.id'));
        if($goods->where($where)->getField('sellerid')==$this->cUid){
            $pictures = $goods->where($where)->getField('pictures');
            $picarr = explode('|', $pictures);
            $fixct = count(explode(',', C('GOODS_PIC_PREFIX')));
            $imgDelUrl = C('UPLOADS_PICPATH');
            foreach ($picarr as $pk => $pv) {
                $fixkey = 0;
                for ($i=0; $i < $fixct; $i++) { 
                    @unlink($imgDelUrl.picRep($pv,$i));
                }
                @unlink($imgDelUrl.$pv);
            }
            if ($goods->where($where)->delete()) {
                $this->success("成功删除");
               // echojson(array("status"=>1,"info"=>""));
            } else {
                $this->error("删除失败，可能是不存在该ID的记录");
            }
        }else{
            $this->error("删除失败，请刷新页面重试！");
        }
    }
     //异步获取频道下分类
    public function getcate(){
        $pid=I('post.pid');
        $cid=I('post.cid');
        $required=I('post.required');
        $cateHtml='';
        if($pid!=''){
            $cat = new \Org\Util\Category();
            $cate = $cat->getList(M('Goods_category'),'cid',$pid);
            if (empty($cate)) {
                echojson(array("status" => 0));
            }else{
                $cateHtml='<select id="Js-cate" class="catesel" name="data[cid]"';
                if ($required!=0) {
                    $cateHtml.=' required';
                }
                $cateHtml.='><option value="">所有分类</option>';
                foreach ($cate as $ck => $cv) {
                    $cateHtml.='<option value="'.$cv['cid'].'"';
                    if ($cid==$cv['cid']) {
                        $cateHtml.=' selected="selected"';
                    }
                    $cateHtml.='>'.$cv['fullname'].'</option>';
                }
                $cateHtml.='</select>';
                echojson(array("status" => 1, "htm" => $cateHtml));
            }
        }else{
            echojson(array("status" => 0));
        }
        
    }
    // 搜索商品
    public function goods_search(){
        $where=array('sellerid'=>$this->cUid);
        $keyW = I('get.');
        $encode = mb_detect_encoding($keyW['keyword'], array("ASCII","UTF-8","GB2312","GBK","BIG5"));
        $keyW['keyword'] = iconv($encode,"utf-8//IGNORE",$keyW['keyword']);
        $category = M('Goods_category');
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
        if($keyW['keyword'] != '') $where['title'] = array('LIKE', '%' . $keyW['keyword'] . '%');
        $M = M("Goods");
        $count = $M->where($where)->count();
        $pConf = page($count,C('PAGE_SIZE'));
        
        $channel = $category->where('pid=0')->select(); //读取频道
        $keyS = array('count' =>$count,'keyword'=>$keyW['keyword'],'chname' => $chname,'catname' => $catname,'pid'=>$keyW['pid']);
        $this->keys = $keyS;
        $this->page = $pConf['show'];
        
        $this->channel=$channel; //分配频道

        $this->list = D("Goods")->listGoods($pConf['first'], $pConf['list'],$where);
        C('TOKEN_ON',false);
        $this->display('goods_list');
    }

     // 发布商品
    public function goods_add() {
        if (IS_POST) {
            // 验证Token
            $this->checkToken();
            echojson(D("Goods")->addEdit('add',$this->cUid));
        } else {
            $uinfo = M('Member')->where(array('uid'=>$this->cUid))->find();
            if ($uinfo['organization']=='') {
                $this->error('请先完善卖家信息！',U('Seller/seller_info',array('source'=>'goods_add')));
            }
            $describe = include APP_PATH . 'Common/Conf/FieldsDescribe.php';
            $info=array(
                'province'=>$uinfo['province'],
                'city'=>$uinfo['city'],
                'area'=>$uinfo['area'],
                'content'=>$describe['FIELDS_DESCRIBE']
            ); //获取地区的层数并分配
            $this->info=$info;
            $channel = M('goods_category')->where('pid=0')->select(); //读取频道
            $this->channel=$channel; //分配频道
            $this->display('goods_add');
        }

    }
    // 编辑商品
    public function goods_edit() {
        $M = M("Goods");
        $pid=I('get.pid');
        if (IS_POST) {
            // 验证Token
            $this->checkToken();
            echojson(D("Goods")->addEdit('edit',$this->cUid,$pid));
        } else {
            $info = $M->where("id=" . (int) $_GET['id'])->find();
            if ($info['id'] == '') {
                $this->error("不存在该记录");
            }
            if ($info['pictures']) {
                $info['pictures'] = explode('|', $info['pictures']);
            }
            $info['seller'] = M('member')->where(array('uid'=>$info['sellerid']))->field('account,nickname,avatar')->find();
            $info['content']=$info['content'];
            $info['layer']=C('goods_region'); //获取地区的层数并分配sss
            
            
            $category = M('goods_category');
            $cat = new \Org\Util\Category();
            $channel = $category->where('pid=0')->order('sort desc')->select(); //读取频道
            $this->channel=$channel; //分配频道
            $cate = $category->order('sort desc')->select();
            $uPath = $cat->getPath($cate,$info['cid']);
            $info['pid'] = $uPath[0]['cid'];
            $this->pid=$pid;
            $this->info=$info;
            
            $this->display("goods_add");
        }
    }
    //------异步排序商品图片
    public function goodPicOrder(){
        C('TOKEN_ON',false);
        if (IS_POST) {
            $data = array(
                'id' => I('post.goodsId'),
                'pictures' => I('post.imgArr')
                );
            if(M('Goods')->save($data)){
                echojson(array('status' => 1, 'msg' => "排序成功，已保存到数据库"));
            }else{
                echojson(array('status' => 0, 'msg' => "排序失败，请刷新页面尝试操作"));
            }
        }
    }
    //------获取组合后的下级条件
    public function getChild(){
        if (IS_POST) {
            if(I('post.fid') != ''){
                echojson(array('status' => 1, 'msg' => getChildHtml(I('post.fid'))));
            }
        } else {
            E('哎哟！怎么到这里了?');
        }
    }
    // ------通过分类cid获取对应筛选条件
    public function getFilt(){
        echojson(array("status" => 1, "html" => getFiltrateHtmlSeller(I('post.cid'),I('post.filtStr'))));
    }
    // ------通过分类cid获取对应扩展字段
    public function getExtends(){
        $rtdata=getExtendsHtml(I('post.cid'),I('post.gid'));
        echojson(array("status" => 1, "ulhtml" => $rtdata['eUrlHtml'],"divhtml" => $rtdata['eDivHtml'],'textarr'=>$rtdata['textarea'],'region'=>$rtdata['region']));
    }
    //------异步删除商品图片
    public function goods_delpic() {
        $imgUrl = I('post.imgUrl');
        if ($imgUrl) {
            $imgDelUrl = C('UPLOADS_PICPATH').I('post.imgUrl');
        }else{
            $imgDelUrl = C('UPLOADS_PICPATH').I('post.savepath').I('post.savename');
            @unlink(C('UPLOADS_PICPATH').I('post.savepath').'thumb'.I('post.savename'));
        }
        if(@unlink($imgDelUrl)){
            $goods = M('Goods');
            $gd_pic = $goods->where(array('id'=>$goodsId))->find();
            //组合要写入数据
            $newPic = str_replace('||','|',trim(str_replace($imgUrl, '', $gd_pic['pictures']),'|'));
            $data = array(
                'id' => I('post.goodsId'),
                'pictures' => $newPic
                );
            $goods->save($data);
            @unlink($imgDelUrl);
            //循环删除缩略图
            $picFix = explode(',',C('GOODS_PIC_PREFIX'));
            foreach ($picFix as $pfK => $pfV) {
                @unlink( C('UPLOADS_PICPATH').picRep($imgUrl,$pfK));
            }
            echojson(array('status' => 1,'msg' => '已从数据库删除成功!'));
        }else{
            echojson(array('status' => 0,'msg' => '删除失败，刷新页面重试!'));
        }
    }


    // 发布拍卖
    public function auction_add() {
        if (IS_POST) {
            // 冻结卖家保证金
            echojson(D('Auction')->addEdit('add',$this->cUid));
        }else{
            $uid = $this->cUid;
            $where = array('sellerid'=>$uid);

            // 检查系统配置是否需要实名认证
            $idcheck = M('Member')->where(array('uid'=>$uid))->getField('idcard_check');
            if(C('Auction.realname_sel')==1 && $idcheck!=2){
                $url = U('Member/safety',array('option'=>'idcard'),'html',true);
                if ($idcheck==1) {
                    $msg = '<strong>请等待实名认证后再发布拍卖！</strong>';
                    $url = '';
                }elseif ($idcheck==3) {
                    $msg = '<strong>您提交的实名认证未通过，请先实名认证后在发布拍卖！</strong><br/>现在去重新提交...';
                }else{
                    $msg = '<strong>请先实名认证后在发布拍卖！</strong><br/>现在去认证...';
                }
                $this->error($msg,$url);
                exit;
            }


            // 验证保证金是否足够发布拍卖
            $uLimit = D('auction')->check_freeze_pledge($uid);
            $this->uLimit=$uLimit;
            if(!$uLimit['yn']){
                // 没有发布的权限显示账户余额信息
                $this->display('ulimit');
            }else{
                // 分配选择的开始时间
                $this->start_date = date('Y-m-d H:i',time());

                $goods= M('Goods');
                $info['to'] = I('get.to')?I('get.to'):'js';
                $info['gid'] = I('get.gid');
                $gdata=$goods->where(array('id'=>$info['gid'],'sellerid'=>$uid))->field('title,price,description,sellerid')->find();
                if(!$gdata){
                    $this->error("页面不存在！");
                    exit;
                }
                if (M('Auction')->where(array('gid'=>$info['gid'],'endstatus'=>array('in',array(0,1))))->count()) {
                    $this->error('该商品正在拍卖或已成交，不允许再次拍卖！<br/>只有流拍和撤拍的商品才能重新发布拍卖！',U('Seller/auction_list'));
                    exit;
                }
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
                        $bidingList = $special->where($where)->where($biding['bidType'])->order('sid desc')->select();
                        $futureList = $special->where($where)->where($future['bidType'])->order('sid desc')->select();
                        if (empty($bidingList)&&empty($futureList)) {
                            $this->error('没有可供发布的专场，请先添加专场',U('Seller/special_add'));
                        }
                        $this->bidingList=$bidingList;
                        $this->futureList=$futureList;
                        
                    }
                    // 拍卖会数据分配到模板
                    if($info['to']=='pmh'){
                        $biding = bidType('biding',2);
                        $future = bidType('future',2);
                        $meeting = M('meeting_auction');
                        $bidingList = $meeting->where($where)->where($biding['bidType'])->order('mid desc')->select();
                        $futureList = $meeting->where($where)->where($future['bidType'])->order('mid desc')->select();
                        if (empty($bidingList)&&empty($futureList)) {
                            $this->error('没有可供发布的拍卖会，请先添加拍卖会',U('Seller/meeting_add'));
                        }
                        $this->bidingList=$bidingList;
                        $this->futureList=$futureList;
                    }
                    $info = array_merge($info,$bidcof);
                    $this->info=$info;
                    // 微信推送数据【
                    $this->weixin=array('name'=>$gdata['title'],'comment'=>$gdata['description']);
                    // 微信推送数据】
                    $this->display('auction_add');
                }else{
                    $this->error('商品不存在！');
                }
            }
        }
    }
    public function auction_edit() {
        if (IS_POST) {
            echojson(D('Auction')->addEdit('edit',$this->cUid));
        }else{
            // 分配选择的开始时间
            $this->start_date = date('Y-m-d H:i',time());

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
            $this->display('auction_add'); 
        }
        
    }
        // 撤拍操作
    public function cancelPai(){
        if (IS_POST) {
            $pid = I('post.pid');
            $sellerid = D("Auction")->where("pid=" . $pid)->getField('sellerid');
            // 判断是否有删除权限
            if($sellerid!=$this->cUid){
                echojson(array('status' => 0, 'msg'=>'您无权撤拍该拍卖！'));
                exit;
            }

            $drive = array();
            $auction = M("Auction");
            $cpinfo = $auction->where("pid=" . $pid)->find();
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
                $rtmsg = unfreeze_seller_pledge($sellerid,$cpinfo['pid'],'cancel');
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
    // 删除拍卖
    public function del_auction(){
        if (IS_POST) {
            $pid = I('post.pid');
            $sellerid = D("Auction")->where("pid=" . $pid)->getField('sellerid');
            // 判断是否有删除权限
            if($sellerid!=$this->cUid){
                echojson(array('status' => 0, 'msg'=>'您无权删除该拍卖！'));
                exit;
            }
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

    // 拍卖列表
    public function auction_list(){
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
        $where['sellerid']=$this->cUid;
        $rdata = $auction->listAuction($where,'pid desc',C('PAGE_SIZE'));
        $this->page = $rdata['page'];
        $this->list = $rdata['list'];
        
        $this->display('auction_list'); 
    }
    // 拍卖信息
    public function auction_info(){
            $auction = D('Auction');
            $info = $auction->where(array('pid'=>I('get.pid'),'sellerid'=>$this->cUid))->find();
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
    // 搜索拍卖
    public function auction_search(){
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
        $where['sellerid']=$this->cUid;
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
        $this->display('auction_list');
    }
    // 发布专场
    public function special_add(){
        if (IS_POST) {
            // 验证Token
            $this->checkToken();
            $data = I('post.data');
            $data['sellerid'] = $this->cUid;
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
            unset($data['sid']);
            if(M('special_auction')->add($data)){
                echojson(array('status' => 1, 'info' => '添加成功','url'=>U('Seller/special_list')));
            }else{
                echojson(array('status' => 0, 'info' => '添加失败，请重试'));
            }
        }else{
            // 分配选择的开始时间
            $this->start_date = date('Y-m-d H:i',time());
            $uinfo = M('Member')->where(array('uid'=>$this->cUid))->find();
            if ($uinfo['organization']=='') {
                $this->error('请先完善卖家信息！',U('Seller/seller_info',array('source'=>'special_add')));
            }
            $this->display();  
        }

    }
    // 专场编辑
    public function special_edit(){
        if (IS_POST) {
            // 验证Token
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
                echojson(array('status' => 1, 'info' => '更新成功','url'=>U('Seller/special_list')));
            }else{
                echojson(array('status' => 0, 'info' => '更新失败，请重试'));
            }
        }else{
            $info = D('SpecialAuction')->where(array('sid'=>I('get.sid'),'sellerid'=>$this->cUid))->find();
            if ($info) {
                // 未开始专场可以编辑
                $this->edit = $info['starttime']>time() ? 0:1;
                $this->info=$info;
                $this->display('special_add'); 
            }else{
                $this->error('页面不存在',U('Seller/special_list'));
            }
            
        }

    }
    // 专场列表
    public function special_list(){
        $where['sellerid'] = $this->cUid;
        $redata = D("SpecialAuction")->listSpecial($where,'sid desc');
        $this->list=$redata['list'];
        $this->page=$redata['page'];
        $this->display();
    }
    // 专场搜索
    public function special_search(){
        $keyW = I('get.');
        $where['sellerid'] = $this->cUid;
        if (!empty($keyW['type'])) {
            $ws = bidType($keyW['type'],1);
            $where =$ws['bidType']; 
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
        $keyS = array('count' =>$redata['count'],'keyword'=>$keyW['keyword'],'type'=>$ws['saytyp'],'pledge_type'=>$keyW['special_pledge_type']);
  
        $this->keys = $keyS;
        $this->display('special_list');
    }
    public function special_del(){
        if (IS_POST) {
            $sid = I('post.sid');
            if (M("Special_auction")->where(array('sid'=>$sid,'sellerid'=>$this->cUid))->delete()) {
                $auction = M('Auction');
                // 如果有拍卖将移除拍卖关联专场字段,并设置拍卖为单独缴纳保证金，且缴纳方式为定额
                $pidarr = $auction->where(array('sid'=>$sid))->getField('pid',true);
                if (!empty($pidarr)) {
                    $setfield = array('sid'=>0,'stepsize_type'=>'fixation','pledge_type'=>'fixation','pattern'=>0);
                    $auction->where(array('pid'=>array('in',$pidarr)))->setField($setfield);
                }
                echojson(array('status' => 1, 'msg'=>'删除成功!，专场内拍品已解散！'));
            } else {
                echojson(array('status' => 0, 'msg'=>'删除失败!'));
            }
        }else{
            $this->error("页面不存在！");
        }
        
    }
    // 专场信息
    public function special_info(){
        $auction = M('auction');
        $info = M('special_auction')->where(array('sid'=>I('get.sid'),'sellerid'=>$this->cUid))->find();
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
    // 发布拍卖会
    public function meeting_add(){
        if (IS_POST) {
            $data = I('post.data');
            $data['sellerid'] = $this->cUid;
            $data['starttime']=strtotime($data['starttime']);
            if($data['starttime']<=time()){
               echojson(array('status' => 0, 'info' => '您不能设置开始时间为已开始！<br/>因为新拍卖会下没有拍品'));
               exit; 
            }
            // 设定拍卖会结束时间为开始时间
            $data['endtime'] = $data['starttime'];
            if(M('meeting_auction')->add($data)){
                echojson(array('status' => 1, 'info' => '添加成功','url'=>U('Seller/meeting_list')));
            }else{
                echojson(array('status' => 0, 'info' => '添加失败，请重试'));
            }
        }else{
            // 分配选择的开始时间
            $this->start_date = date('Y-m-d H:i',time());
            $uinfo = M('Member')->where(array('uid'=>$this->cUid))->find();
            if ($uinfo['organization']=='') {
                $this->error('请先完善卖家信息！',U('Seller/seller_info',array('source'=>'meeting_add')));
            }
            $this->display();  
        }

    }
    // 拍卖会编辑
    public function meeting_edit(){
        if (IS_POST) {
            $data = I('post.data');
            $special = M('meeting_auction');
            if ($data['starttime']) {$data['starttime']=strtotime($data['starttime']);}else{unset($data['starttime']);}
            if($special->save($data)){
                echojson(array('status' => 1, 'info' => '更新成功','url'=>U('Seller/meeting_list')));
            }else{
                echojson(array('status' => 0, 'info' => '更新失败，请重试'));
            }
        }else{
            $info = D('MeetingAuction')->where(array('mid'=>I('get.mid'),'sellerid'=>$this->cUid))->find();
            if ($info) {
                // 未开始拍卖会可以编辑
                $this->edit = $info['starttime']>time() ? 0:1;
                $this->info=$info;
                $this->display('meeting_add'); 
            }else{
                $this->error('页面不存在',U('Seller/meeting_list'));
            }
            
        }
    }
    // 拍卖会列表
    public function meeting_list(){
        $where['sellerid'] = $this->cUid;
        $redata = D("MeetingAuction")->listMeeting($where,'mid desc');
        $this->list=$redata['list'];
        $this->page=$redata['page'];
        $this->display();
    }
    // 拍卖会搜索
    public function meeting_search(){
        $keyW = I('get.');
        $where['sellerid'] = $this->cUid;
        if (!empty($keyW['type'])) {
            $ws = bidType($keyW['type'],1);
            $where =$ws['bidType']; 
        }
        if($keyW['keyword'] != ''){
            $encode = mb_detect_encoding($keyW['keyword'], array("ASCII","UTF-8","GB2312","GBK","BIG5"));
            $keyW['keyword'] = iconv($encode,"utf-8//IGNORE",$keyW['keyword']);
            $where['sname'] = array('LIKE', '%' . $keyW['keyword'] . '%');
        }
        if($keyW['meeting_pledge_type']!='') $where['meeting_pledge_type'] = $keyW['meeting_pledge_type'];
        $redata = D("MeetingAuction")->listMeeting($where,'mid desc');
        $this->list=$redata['list'];
        $this->page=$redata['page'];
        $keyS = array('count' =>$redata['count'],'keyword'=>$keyW['keyword'],'type'=>$ws['saytyp'],'pledge_type'=>$keyW['meeting_pledge_type']);
  
        $this->keys = $keyS;
        $this->display('meeting_list');
    }
    // 拍卖会删除
    public function meeting_del(){
        if (IS_POST) {
            $mid = I('post.mid');
            if (M("meeting_auction")->where(array('mid'=>$mid,'sellerid'=>$this->cUid))->delete()) {
                $auction = M('Auction');
                // 如果有拍卖将移除拍卖关联拍卖会字段,并设置拍卖为单独缴纳保证金，且缴纳方式为定额
                $pidarr = $auction->where(array('mid'=>$mid))->getField('pid',true);
                if (empty($pidarr)) {
                    $setfield = array('mid'=>0,'stepsize_type'=>'fixation','pledge_type'=>'fixation','pattern'=>0);
                    $auction->where(array('pid'=>array('in',$pidarr)))->setField($setfield);
                }
                echojson(array('status' => 1, 'msg'=>'删除成功，拍卖会内拍卖全部结束'));
            } else {
                echojson(array('status' => 0, 'msg'=>'删除失败!'));
            }
        }else{
            $this->error("页面不存在！");
        }
        
    }
    // 拍卖会信息
    public function meeting_info(){
        $auction = M('auction');
        $info = M('meeting_auction')->where(array('mid'=>I('get.mid'),'sellerid'=>$this->cUid))->find();
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






    // 拍卖订单
    public function myorder(){
        $Dorder = D('GoodsOrder');
        $where = array('sellerid'=>$this->cUid);
        // 接收查看是否退货
        if (I('get.rstatus')==1) {$where['rstatus']=1;}else{$where['rstatus']=0;}
        // 接收查看订单状态
        if(I('get.st')!=''){$where['status']=I('get.st');}
        $rdata = $Dorder->listOrder($where,'time desc',C('PAGE_SIZE'));
        $this->alist=$rdata['list'];
        $this->page = $rdata['page'];
        $this->whopage=array('name'=>'拍卖订单','action'=>'myorder','seller'=>1,'type'=>'sel');
        $this->rstatus = $where['rstatus'];
        $this->st = I('get.st');
        $this->display('Member:order');
    }

    public function nodeliver(){
        pre(M("Goods_order")->where(array('order_no'=>'BID149922090246152'))->find());
        die;
        // M("Goods_order")->where(array('order_no'=>'BID149922090246152'))->setField('status',1);
    }
    // 卖家发货处理
    public function deliver(){
        $goodsorder = M("Goods_order");
        if (IS_POST) {
            $data =I('post.info');
            // 计算收货过期时间
            if(C('Order.losetime3')==0||C('Order.losetime3')==''){
                $deftime3 = 0;
            }else{
                $losetime3=C('Order.losetime3');
                $deftime3 = time()+(60*60*24*$losetime3);
            }
            // 设置收货过期时间
            $data['deftime3'] = $deftime3;
            $data['status'] = 2;
            $data['time2'] = time();
            if($goodsorder->where(array('order_no'=>$data['order_no']))->save($data)){
                // 订单状态提醒【
                    sendOrderRemind($data['order_no']);
                // 订单状态提醒【
                echojson(array('status' => 1, 'info' => '已提交发货'.$rs,'url' => U('Seller/myorder',array('st'=>2)))); 
            }else{
                echojson(array('status' => 0, 'info' => '提交发货失败，请检查'));
            }
        } else {
            $where= array('order_no'=>I('get.order_no'),'status'=>1);
            if (!$info = M('goods_order')->where($where)->find()) {
                $this->error('已提交发货无需重复提交！');
            }

            $this->address = unserialize($info['address']);
            // 快递选择
            $express = C('Express');
            $this->express_list=$express['comarr'];
            $this->info=$info;
            $this->whopage=array('name'=>'给买家发货','action'=>'deliver','seller'=>1,'type'=>'sel');
            $this->display();
        }
    }
        // 卖家确认收货
    public function receipt(){
        if (IS_POST) {
            $data = I('post.data');
            $paypwd = M('Member')->where(array('uid'=>$this->cUid))->getField('paypwd');
            if (empty($paypwd)) {
                echojson(array('status' => 0, 'info' => '请设置支付密码后在确认收货！','url'=>U('Member/safety',array('option'=>'setpay'))));
                exit;
            }else{
                if ($paypwd!=encryptPwd($data['paypwd'])) {
                    echojson(array('status' => 0, 'info' => '支付密码错误！'));
                    exit;
                }else{
                    $goods_order = M('goods_order');
                    if($oinfo = $goods_order->where(array('order_no'=>$data['order_no'],'sellerid'=>$this->cUid,'status'=>8))->find()){
                        // 买家默认好评过期时间
                        if(C('Order.losetime4')==0||C('Order.losetime4')==''){
                            $deftime4 = 0;
                        }else{
                            $losetime4=C('Order.losetime4');
                            $deftime4 = time()+(60*60*24*$losetime4);
                        }
                        // 订单数据
                        $order_data = array('status'=>9,'deftime4'=>$deftime4);
                        // 退货数据
                        $return_data=array('time9'=>time());
                        // 设置已收货、和评价时间
                        if($goods_order->where(array('order_no'=>$data['order_no']))->save($order_data)&&M('goods_order_return')->where(array('order_no'=>$data['order_no']))->save($return_data)){
                            // 将退款转入买家账户，退还卖家保证金。
                            income_send_buy($data['order_no']);
                            echojson(array('status' => 1, 'info' => '已确认收货','url'=>U('Seller/myorder',array('rstatus'=>1,'st'=>9))));
                        }else{
                            echojson(array('status' => 0, 'info' => '操作失败请重试','url'=>U('Seller/receipt',array('order_no'=>$data['order_no']))));
                        }
                    }else{
                        echojson(array('status' => 0, 'info' => '操作失败请重试，请刷新页面重试','url'=>U('Seller/receipt',array('order_no'=>$data['order_no']))));
                    }
                }
            }
        }else{
            $order_no = I('get.order_no');
            $Dorder = D('GoodsOrder');
            // 状态为退货流程且买家已发货
            $where = array('order_no'=>$order_no,'sellerid'=>$this->cUid,'rstatus'=>1,'status'=>8,'deftime9st'=>0);
            $alist['oinfo'] = $Dorder->findOrder($where);
            $this->whopage=array('name'=>'退货详情','action'=>'order_details','seller'=>1,'type'=>'sel');
            $this->alist=$alist;
            $this->display('Member/order_details');
        }
    }
    // 评价买家
    public function evaluate(){
        $evaluate = M('goods_evaluate');
        if (IS_POST) {
            $data = I('post.data');
            $where = array('order_no'=>$data['order_no']);
            if($data['member_evaluate']==''){
                echojson(array('status' => 0, 'info' => '请填写评价内容！'));
                exit;
            }
            if($evaluate->where($where)->getField('member_evaluate')!=''){
                echojson(array('status' => 0, 'info' => '您已做过评价！','url'=>U('Seller/evaluate_list')));
                exit;
            } 
            $goods_order = M('goods_order');
            $oinfo = $goods_order->where($where)->find();
            $data['rtime'] = time();
            unset($data['order_no']);
            if($evaluate->where($where)->save($data)){
                if ($goods_order->where($where)->setField(array('status'=>'10','time10'=>time()))) {
                    // 订单状态提醒【
                        sendOrderRemind($data['order_no']);
                    // 订单状态提醒【
                }
                M('member')->where(array('uid'=>$data['uid']))->setInc('scorebuy',$data['score']);
                echojson(array('status' => 1, 'info' => '评价成功','url'=>U('Seller/myorder',array('st'=>10))));
            }else{
                echojson(array('status' => 0, 'info' => '评价失败,请重试','url'=>__SELF__));
            }   
        }else{
            // 支付状态0：待支付1：已支付待发货 2：已发货待收货3：已收货待评价 4：已评价卖家 5：申请退货 6：同意退货 7：不同意退货 8：买家已发货 9：卖家确认收货 10：已互评
            $order_no = I('get.order_no');
            $Dorder = D('GoodsOrder');
            $where = array('order_no'=>$order_no,'sellerid'=>$this->cUid,'status'=>4);
            $oinfo = $Dorder->findOrder($where);
            if ($oinfo) {
                $this->oinfo=$oinfo;
                $this->display();
            }else{
                $this->error('页面不存在或已过期！',U('Seller/myorder'));
            }
        }
    }
    // 评价管理
    public function evaluate_list(){
        $where = array('sellerid'=>$this->cUid);
        if (I('get.order_no')) {
            $where['order_no']=I('get.order_no');
        }
        $rdata = D('GoodsEvaluate')->listEvaluate($where, 'rtime desc',C('PAGE_SIZE'));
        // 分配分页到模板
        $this->page = $rdata['page']; 
        // 分配正在拍卖拍品到模板
        $this->list = $rdata['list'];
        $this->display('Member:evaluate_list');
    }
// 我收到的评价
    public function evaluate_tome(){
        $evaluate = M('goods_evaluate');
        $member = M('member');
        $auction = D('auction');
        $order = M('goods_order');
        $where = array('sellerid'=>$this->cUid);
        $einfo['credit_score'] = getstarval($evaluate,$where);
        $elist = $evaluate->where($where)->select();
        $ntm = time();
        $watm = $ntm+(3600*7);
        $wbtm = $ntm+(3600*30);
        $wctm = $ntm+(3600*186);
        $einfo['scoreZC'] = 0;$einfo['scoreZZ'] = 0;$einfo['scoreZH'] = 0;
        $einfo['scoreAC'] = 0;$einfo['scoreAZ'] = 0;$einfo['scoreAH'] = 0;
        $einfo['scoreBC'] = 0;$einfo['scoreBZ'] = 0;$einfo['scoreBH'] = 0;
        $einfo['scoreCC'] = 0;$einfo['scoreCZ'] = 0;$einfo['scoreCH'] = 0;
        $einfo['scoreDC'] = 0;$einfo['scoreDZ'] = 0;$einfo['scoreDH'] = 0;
        foreach ($elist as $ek => $ev) {
            $zf = $ev['conform']+$ev['service']+$ev['express'];
            if($ev['time']<$watm){
                if($zf<=6){
                    $einfo['scoreAC']+=1; $einfo['scoreZC']+=1; 
                }elseif($zf>6&&$zf<=12){
                    $einfo['scoreAZ']+=1; $einfo['scoreZZ']+=1; 
                }elseif($zf>12&&$zf<=15){
                    $einfo['scoreAH']+=1; $einfo['scoreZH']+=1;
                }
            }elseif($ev['time']<$watm&&$ev['time']>$wbtm){
                if($zf<=6){
                    $einfo['scoreBC']+=1; $einfo['scoreZC']+=1; 
                }elseif($zf>6&&$zf<=12){
                    $einfo['scoreBZ']+=1; $einfo['scoreZZ']+=1; 
                }elseif($zf>12&&$zf<=15){
                    $einfo['scoreBH']+=1; $einfo['scoreZH']+=1;
                }
            }elseif($ev['time']<$wbtm&&$ev['time']>$wctm){
                if($zf<=6){
                    $einfo['scoreCC']+=1; $einfo['scoreZC']+=1; 
                }elseif($zf>6&&$zf<=12){
                    $einfo['scoreCZ']+=1; $einfo['scoreZZ']+=1; 
                }elseif($zf>12&&$zf<=15){
                    $einfo['scoreCH']+=1; $einfo['scoreZH']+=1;
                }
            }elseif($ev['time']<$wctm){
                if($zf<=6){
                    $einfo['scoreDC']+=1; $einfo['scoreZC']+=1;
                }elseif($zf>6&&$zf<=12){
                    $einfo['scoreDZ']+=1; $einfo['scoreZZ']+=1; 
                }elseif($zf>12&&$zf<=15){
                    $einfo['scoreDH']+=1; $einfo['scoreZH']+=1; 
                }
            }
        }
        $einfo['scoreALZ'] = $einfo['scoreZC']+$einfo['scoreZZ']+$einfo['scoreZH'];
        $einfo['scoreALA'] = $einfo['scoreAC']+$einfo['scoreAZ']+$einfo['scoreAH'];
        $einfo['scoreALB'] = $einfo['scoreBC']+$einfo['scoreBZ']+$einfo['scoreBH'];
        $einfo['scoreALC'] = $einfo['scoreCC']+$einfo['scoreCZ']+$einfo['scoreCH'];
        $einfo['scoreALD'] = $einfo['scoreDC']+$einfo['scoreDZ']+$einfo['scoreDH'];
        $einfo['scorePER'] = wipezero($einfo['scoreZH']/$einfo['scoreALZ']*100)."%";
        $count = $evaluate->where($where)->count();
        $pConf = page($count,C('PAGE_SIZE'));
        $list = $evaluate->where($where)->limit($pConf['first'].','.$pConf['list'])->select();
        foreach ($list as $lk => $lv) {
            $list[$lk]['nickname'] = $member->where(array('uid'=>$lv['uid']))->getField('nickname');
            $list[$lk]['pname'] = $auction->where(array('pid'=>$lv['pid']))->getField('pname');
            $list[$lk]['pictures'] = $auction->where(array('pid'=>$lv['pid']))->getField('pictures');
            $list[$lk]['price'] =  $order->where(array('order_no'=>$lv['order_no']))->getField('price');
            $zongfen = $lv['conform']+$lv['service']+$lv['express'];
            if($zongfen>=0&&$zongfen<=6){
                $list[$lk]['pingjia'] = 0;
            }elseif($zongfen>=7&&$zongfen<=12){
                $list[$lk]['pingjia'] = 1;
            }elseif($zongfen>=13){
                $list[$lk]['pingjia'] = 2;
            }
        }
        $this->page = $pConf['show']; 
        $this->einfo=$einfo;
        $this->list=$list;
        $this->display();
    }
    // 微信图文
    public function weisend(){
        $weiurl = M('Weiurl');
        $member = M('member');
        $where = array('sellerid'=>$this->cUid);
        $count = $weiurl->where($where)->count();
        $pConf = page($count,C('PAGE_SIZE'));
        $this->page = $pConf['show'];
        $list = $weiurl->where($where)->order('id desc')->select();
        $this->list=$list;
        $this->display();
    }
    // 选择推送
    public function weipush(){
        if(C('Weixin.appid')&&C('Weixin.appsecret')){
            $uid = $this->cUid;
            if (I('post.type')=='image-text') {
                $widarr = I('post.wid');
                if(count($widarr)>4){
                    echojson(array('status' => 1, 'info' => '最多可选择4条图文进行发送！<br/>'.$resultmsg));
                    exit;
                }
                $weiurl = M('Weiurl');
                $wlist = $weiurl->where(array('id'=>array('in',$widarr)))->select();
                // 生成图文数据
                $news_arr = array();
                $lk = 0;
                $webroot = C('WEB_ROOT');
                if (__ROOT__=='/') {$root = '';}else{$root = __ROOT__;}
                foreach ($wlist as $wk => $wv) {
                    $news_arr[$lk][0] = $wv['name'];
                    $news_arr[$lk][1] = $wv['comment'];
                    // 地址替换
                    $news_arr[$lk][2] = $wv['url'];
                    // 图片选择
                    if($lk==0){
                        $news_arr[$lk][3] = $webroot.$root.str_replace('./', '', C('UPLOADS_PICPATH')).$wv['toppic'];
                    }else{
                        $news_arr[$lk][3] = $webroot.$root.str_replace('./', '', C('UPLOADS_PICPATH')).$wv['picture'];
                    }
                    $lk+=1;
                }
                $uidarr = eligibility($uid,1);
                if(!empty($uidarr)){
                    $retmsg = D('Weixin')->sendNews(array('uid'=>array('in',$uidarr)),$news_arr,$widarr);
                    echojson(array('status'=>1,'info'=>$retmsg));
                }else{
                    echojson(array('status'=>0,'info'=>'没有符合条件的推送用户。'));
                }
            }
        }else{
            echojson(array('status'=>1,'info'=>'没有配置微信，请先配置微信。'));
        }
    }
    // 编辑图文消息
    public function editweisend() {
        $M = M('Weiurl');
        if (IS_POST) {
            echojson(D('Weixin')->addEdit('edit'));
        } else {
            $weixin = $M->where("id=" . (int) $_GET['id'])->find();
            if ($weixin['id'] == '') {
                $this->error("不存在该记录");
            }
            $this->assign("weixin", $weixin);
            C('TOKEN_ON',false);
            $this->display();
        }
    }
    //删除图文消息
    public function delweisend() {

        if (M("Weiurl")->where(array('id'=>I('get.id'),'sellerid'=>$this->cUid))->delete()) {
            $this->success("成功删除");
            //echojson(array("status"=>1,"info"=>""));
        } else {
            $this->error("删除失败，可能是不存在该ID的记录");
        }
    }

   
    /**
      +----------------------------------------------------------
     * 黑名单相关
      +----------------------------------------------------------
     */
    // 卖家黑名单
    public function blacksel(){
        $data = D('Member')->black($this->cUid,'sel');
        $this->selbuy = 'sel';
        $this->page = $data['page'];
        $this->list = $data['list'];
        $this->display('black');
    }

// 同意退货
    public function return_reply(){
        if (IS_POST) {
            $data = I('post.data');
            $order_return = M('goods_order_return');
            $goods_order = M('goods_order');
            $oinfo = M('goods_order')->where(array('status'=>5,'order_no'=>$data['order_no'],'sellerid'=>$this->cUid))->find();
            if($oinfo){
                // 卖家同意退货
                if ($data['status']==7) {
                    // 设置收货地址
                    $region = M('region');
                    $address = M('deliver_address')->where(array('adid'=>$data['adid'],'uid'=>$this->cUid))->find();
                    if($address){
                        $return_data = array(
                            'address'=>serialize($address),
                            'time7'=>time()
                        );
                        $order_data = array('status'=>$data['status']);
                        // 买家发货期限【
                        if(C('Order.losetime8')==0||C('Order.losetime8')==''){
                            $deftime8 = 0;
                        }else{
                            $losetime8=C('Order.losetime8');
                            $deftime8 = time()+(60*60*24*$losetime8);
                        }
                        $return_data['deftime8'] = $deftime8;
                        // 买家发货期限】
                        $order_data['remark'] = order_remark($oinfo['remark'],7,'卖家同意退货，请在'.date('Y-m-d H:i',$deftime8).'前按照卖家收货地址提交发货！如过期将默认您已收货，货款将转入卖家账户，交易结束。');
                    }else{
                        echojson(array('status' => 0, 'info' => '地址被删除，或不可用的地址！','url'=>__SELF__));
                    }
                // 卖家拒绝退货
                }elseif ($data['status']==6) {
                    $order_data = array('status'=>$data['status']);
                    $return_data = array('time6'=>time(),'selcause'=>$data['selcause']);
                    // 买家申请调解期限【
                    if(C('Order.losemediate')==0||C('Order.losemediate')==''){
                        $defmediate = 0;
                    }else{
                        $losemediate=C('Order.losemediate');
                        $defmediate = time()+(60*60*24*$losemediate);
                    }
                    $return_data['defmediate'] = $defmediate;
                    $order_data['remark'] = order_remark($oinfo['remark'],6,'卖家拒绝退货，请在'.date('Y-m-d H:i',$defmediate).'前申请平台介入！如过期将默认您同意收货，货款将转入卖家账户，交易结束。');
                    // 买家申请调解期限】
                }
            }
            if ($order_return->where(array('order_no'=>$data['order_no']))->save($return_data)&&$goods_order->where(array('order_no'=>$data['order_no']))->save($order_data)) {
                echojson(array('status' => 1, 'info' => '已回复退货！等待买家发货','url'=>U('Seller/myorder',array('rstatus'=>1,'st'=>$data['status']))));
            }else{
                echojson(array('status' => 0, 'info' => '回复退货失败','url'=>__SELF__));
            }
        }else{
            $Dorder = D('GoodsOrder');
            $oinfo = $Dorder->findOrder(array('order_no'=>I('get.order_no'),'status'=>5,'sellerid'=>$this->cUid));
            if($oinfo){
                // 地址列表【
                $address = M('deliver_address')->where(array('uid'=>$oinfo['sellerid']))->order('`default` desc')->select();
                if (!$address) {
                    $this->error('请完善您的地址信息！',U('Member/deliver_address',array('type'=>'add','source'=>'Seller-return_reply-order_no-'.I('get.order_no'))));
                }
                $this->address=$address;
                // 地址列表】
                $this->whopage=array('name'=>'回复退货','action'=>'return_reply','seller'=>1,'type'=>'sell');
                $this->oinfo = $oinfo;
                $this->display();
            }else{
                $this->error('不存在的退货申请或已恢复！');
            }
        }
    }
}