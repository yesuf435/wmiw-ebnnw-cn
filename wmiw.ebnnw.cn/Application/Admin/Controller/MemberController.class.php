<?php
namespace Admin\Controller;
use Think\Controller;
class MemberController extends CommonController {

    public function index() {
        $M = M("Member");
        $evaluate = M('goods_evaluate');
        $goods = M('goods');
        $auction = D('Auction');
        $deliver_address = M('deliver_address');
        if (I('get.sourceuid')) {
            $where = array('sourceuid'=>I('get.sourceuid'));
            $this->saccount =$M->where(array('uid'=>I('get.sourceuid')))->getField('account');
        }
        $count = $M->where($where)->count();
        $pConf = page($count,C('PAGE_SIZE')); // 分页
        $list=$M->where($where)->order('uid desc')->limit($pConf['first'], $pConf['list'])->select();
        $this->page = $pConf['show'];
        foreach ($list as $lk => $lv) {
            $list[$lk]['adcount'] = $deliver_address->where(array('uid'=>$lv['uid']))->count();
            $list[$lk]['leval'] = getlevel($lv['score']);
            $list[$lk]['levalbuy'] = getlevel($lv['scorebuy'],1);
            $list[$lk]['evaluate'] = getstarval($evaluate,array('sellerid'=>$lv['uid']));
            $list[$lk]['goods_count'] = $goods->where(array('sellerid'=>$lv['uid']))->count();
            $list[$lk]['auction_count'] = $auction->where(array('sellerid'=>$lv['uid']))->count();
            if ($lv['sourceuid']) {
                $list[$lk]['saccount'] = $M->where(array('uid'=>$lv['sourceuid']))->getField('account');
            }else{
                $list[$lk]['saccount'] = '无';
            }
        }
        $this->list=$list;
        $this->display();
    }
    // 实名认证的操作
    public function realname() {
        $member = M("Member");
        $status = I('get.status');
        if (!empty($status)) {
            $where = array('idcard_check'=>I('get.status'));
        }else{
            $where = array('idcard_check'=>array('in','1,2,3'));
        }
        $count = $member->where($where)->count();
        $pConf = page($count,C('PAGE_SIZE')); // 分页
        $list = $member->where($where)->limit($pConf['first'], $pConf['list'])->select();
        $this->page = $pConf['show'];
        $this->list=$list;
        $this->display();
    }
    // 实名认证搜索
    public function search_realname(){
        $member = M("Member");
        $status = I('get.status');
        if (!empty($status)) {
            $where = array('idcard_check'=>I('get.status'));
        }else{
            $where = array('idcard_check'=>array('in','1,2,3'));
        }
        $keys = I('get.');
        $where[$keys[field]] = array('LIKE','%'.$keys['keyword'].'%');
        $count = $member->where($where)->count();
        $pConf = page($count,C('PAGE_SIZE')); // 分页
        $list = $member->where($where)->select();
        $this->list=$list;
        $keys['count']=$count;
        $this->keys=$keys;
        $this->page = $pConf['show'];
        $this->display('realname');
    }
    // 实名认证编辑
    public function realname_edit() {
        if(IS_POST){
            echojson(D('Member')->addEditMember('edit'));
        }else{
            $uid=I('get.uid');
            $m_member=M('Member');
            $map['uid']=$uid;
            $info=$m_member->where($map)->find();
            $this->info = $info;
            $this->display();
        }
    }
    // 地址列表
    public function deliver_address(){
        $uid = I('get.uid');
        $info = M('Member')->where(array('uid'=>$uid))->find();
        $list = M('deliver_address')->where(array('uid'=>$uid))->select();
        $this->list = $list;
        $this->info = $info;
        $this->display();
    }
    public function search(){
        $M = M("Member");
        $evaluate = M('goods_evaluate');
        $goods = M('goods');
        $auction = D('Auction');
        $deliver_address = M('deliver_address');
        $keys = I('get.');
        $where = array($keys[field]=>array('LIKE','%'.$keys['keyword'].'%'));
        $count = $M->where($where)->count();
        $pConf = page($count,C('PAGE_SIZE')); // 分页
        $list=$M->where($where)->order('uid desc')->limit($pConf['first'], $pConf['list'])->select();
        foreach ($list as $lk => $lv) {
            $list[$lk]['adcount'] = $deliver_address->where(array('uid'=>$lv['uid']))->count();
            $list[$lk]['leval'] = getlevel($lv['score']);
            $list[$lk]['levalbuy'] = getlevel($lv['scorebuy'],1);
            $list[$lk]['evaluate'] = getstarval($evaluate,array('sellerid'=>$lv['uid']));
            $list[$lk]['goods_count'] = $goods->where(array('sellerid'=>$lv['uid']))->count();
            $list[$lk]['auction_count'] = $auction->where(array('sellerid'=>$lv['uid']))->count();
            if ($lv['sourceuid']) {
                $list[$lk]['saccount'] = $M->where(array('uid'=>$lv['sourceuid']))->getField('account');
            }else{
                $list[$lk]['saccount'] = '无';
            }
        }
        $this->list=$list;
        $keys['count']=$count;
        $this->keys=$keys;
        $this->page = $pConf['show'];
        C('TOKEN_ON',false);
        if($keys['page']=='getUser'){
            $this->display('getUser');
        }else{
            $this->display('index');
        }
    }
    // 添加用户
    public function add(){
        if(IS_POST){
            $this->checkToken();
            echojson(D('Member')->addEditMember('add'));
        }else{
            $this->display();
        }
    }
    // 记账单
    public function walletbill(){
        if(IS_POST){
            
        }else{
            $where = array();
            if (I('get.wallet')=='limsum') {
                $wallet = 'limsum';
                $wallet_bill = D('MemberLimsumBill');
            }else{
                $wallet_bill = D('MemberPledgeBill');
                $wallet = 'pledge';
            }
            $wstar = '';
            $keys=I('get.');
            $keys['start_time'] = str_replace('+', ' ', $keys['start_time']);
            $keys['end_time'] = str_replace('+', ' ', $keys['end_time']);
            if($keys['start_time']!=''){
                $wstar .= "time >= ".strtotime($keys['start_time'])." and ";
            }
            if($keys['end_time']!=''){
                $wstar .= "time <= ".strtotime($keys['end_time']);
            }
            if($wstar!=''){
                $where['_string'] = $wstar;
            }
            if($keys['changetype']!=''){
                $where['changetype'] = $keys['changetype'];
            }
            if($keys['account']!=''){
                $where['account'] = $keys['account'];
            }
            if($keys['order_no']!=''){
                $where['order_no'] = $keys['order_no'];
            }
            $this->keys=$keys;
            $count = $wallet_bill->where($where)->count();
            $pConf = page($count,C('PAGE_SIZE')); // 分页
            $list=$wallet_bill->where($where)->order('time desc')->limit($pConf['first'], $pConf['list'])->select();
            $this->list=$list;
            $this->wallet=$wallet;
            $this->changetype = changetype('all');
            $this->page = $pConf['show'];
            $this->display();
        }
    }


    // 用户账户管理
    public function wallet(){
        if(IS_POST){
            $data = I('post.data');
            if($data['item'] == 'pledge'){
                echojson(D('Member')->recharge_pledge($data));
            }elseif ($data['item'] == 'limsum') {
                echojson(D('Member')->recharge_limsum($data));
            }else{
                echojson(array('status'=>0, 'info'=>'不存在的充值项'));
            }
        }else{
            $uid=I('get.uid');
            
            $m_member=M('Member');
            $map['uid']=$uid;
            $info=$m_member->where($map)->find();
            if (!$info) {
                $this->error('操作不存在的用户！',U('Member/index'));
            }
            // 保证金
            $available = $info['wallet_pledge'] - $info['wallet_pledge_freeze'];

            $info['available'] = $available>=0 ? sprintf("%.2f", $available): 0;
            // 信用额度
            $available_limsum = $info['wallet_limsum'] - $info['wallet_limsum_freeze'];
            $info['available_limsum'] = $available_limsum>=0 ? sprintf("%.2f", $available_limsum) : 0;
            $this->info = $info;
            $this->display();
        }
    }
    // 推广项反馈
    public function feedback(){
        $M = M('feedback');
        $this->list = $M->order('count desc')->select();
        $this->display();
    }
    // 推广项添加
    public function feedback_add(){
        if(IS_POST){
            $this->checkToken();
            $data = I('post.data');
            $M = M('feedback');
            if ($M->where($data)->count() == 0) {
                echojson ($M->add($data) ? array('status' => 1, 'info' => '分类 ' . $data['name'] . ' 已经成功添加到系统中', 'url' => U('Member/feedback', array('time' => time()))) : array('status' => 0, 'info' => '推广类型 ' . $data['name'] . ' 添加失败'));
            } else {
                echojson (array('status' => 0, 'info' => '系统中已经存在推广类型 ' . $data['name']));
            }
        }else{
            $this->display();
        }
    }
    // 推广项添加
    public function feedback_del(){
        if (M('feedback')->delete(I('get.id'))) {
            $this->success('删除成功！',U('Member/feedback', array('time' => time())));
        }else{
            $this->error('删除失败，请重试！',U('Member/feedback', array('time' => time())));
        }
    }

    // 用户配置
    public function set_member() {
        if (IS_POST) {
            $this->checkToken();
            $config = APP_PATH . "Common/Conf/SetMember.php";

            $config = file_exists($config) ? include "$config" : array();
            $config = is_array($config) ? $config : array();
            $data['Member'] = I('post.');
            if (set_config("SetMember", $data, APP_PATH . "Common/Conf/")) {
                delDirAndFile(WEB_CACHE_PATH . "Cache/Admin/");
                echojson(array('status' => 1, 'info' => '设置成功','url'=>__ACTION__));
            } else {
                echojson(array('status' => 0, 'info' => '设置失败，请检查'));
            }
        } else {
            $mbcof = include APP_PATH . 'Common/Conf/SetMember.php';
            $this->mbcof=$mbcof['Member'];
            $this->display(); 
        }
    }
    // 编辑用户
    public function edit(){
        if(IS_POST){
            $this->checkToken();
            echojson(D('Member')->addEditMember('edit'));
        }else{
            $uid=I('get.uid');
            $m_member=M('Member');
            $map['uid']=$uid;
            $info=$m_member->where($map)->find();
            $this->info = $info;
            $this->display('add');
        }
    }
    // 删除用户
    public function del(){
        $uid=I('get.uid');
        if($uid){
            $m_member=M('Member');
            $map['uid']=$uid;
            if($m_member->where($map)->delete()){
                // 删除用户微信表
                M('member_weixin')->where($map)->delete();
                $this->success('删除成功');
            }else{
                $this->error('删除失败');
            }
        }else{
            return false;
        }
    }
    // 禁用用户
    public function prohibit(){
        $m_member=M('Member');
        $status = I('post.status')?0:1;
        if($m_member->where(array('uid'=>I('post.uid')))->setField('status',$status)){
            echojson(array("status" => 1, "info" => '操作成功'));
        }else{
            echojson(array("status" => 1, "info" => '操作失败，请刷新页面重试！'));
        }
    }
    // 验证title是否重复
    public function checkUserAccount() {
        $M = M("Member");
        $where = "account='" .I('get.account') . "'";
        if (!empty($_GET['uid'])) {
            $where.=" And uid !=" . (int) $_GET['uid'];
        }
        if ($M->where($where)->count() > 0) {
            echojson(array("status" => 0, "info" => "已经存在，请修改账号"));
        } else {
            echojson(array("status" => 1, "info" => "可以使用"));
        }
    }
    // 发送站内信
    public function sendsms(){
        if (IS_POST) {
            $data = I('post.data');
            if($data['uid']){
                $count = 0;
                $mysms = M('mysms');
                foreach ($data['uid'] as $k => $v) {
                    $dt=array(
                        'uid'=>$v,
                        'type'=>'管理员发送',
                        'aid'=>$this->cAid,
                        'content'=>$data['content'],
                        'time'=>time()
                        );
                    if($mysms->add($dt)){
                        $count +=1; 
                    }
                }
                if($count>0){
                    echojson(array("status" => 1, "info" => '成功发送'.$count.'条站内信！','url'=>U('Member/webmail')));
                }else{
                   $this->error('发送失败请重试！'); 
                }
            }else{
                echojson(array("status" => 0, "info" => "至少需要选择一个接受用户！",'url'=>__SELF__));
            }
        }else{
            $uidstr = I('get.uid');
            $uidarr = explode('_', $uidstr);
            if($uidarr[0]!=''){
                $where['uid'] = array('in',$uidarr);
            }else{
                $this->error('不存在的用户');
            }
            $list = M('member')->where($where)->field('uid,account,nickname')->select();
            $this->list = $list;
            $this->display();
        }
        
    }
    // 站内信管理
    public function webmail(){
        $mysms = M('mysms');
        $member = M('member');
        $admin = M('admin');
        $auction = D('Auction');
        $where = array();
        $keys = I('get.');
        $user = I('get.user');
        $send = I('get.send');
        switch (I('get.tp')) {
            // 系统发送给用户
            case '0':
                $where['aid'] = 0;
                $where['sendid'] = 0;
                if ($keys['user']) {
                    if($userid = $member->where(array('account'=>$keys['user']))->getField('uid')){
                        $where['uid']=$userid;
                    };
                }
                break;
            // 管理员发送给用户
            case '1':
                $where['aid'] = array('neq',0);
                if ($send) {
                    if($aid = $admin->where(array('email'=>$keys['send']))->getField('aid')){
                        $where['aid']=$aid;
                    };
                }
                $where['sendid'] = 0;
                if ($user) {
                    if($userid = $member->where(array('account'=>$keys['user']))->getField('uid')){
                        $where['uid']=$userid;
                    };
                }
                break;
            // 用户发送给用户
            case '2':
                if ($send) {
                    if($sendid = $member->where(array('account'=>$keys['send']))->getField('uid')){
                        $where['sendid']=$sendid;
                    };
                }
                if ($user) {
                    if($userid = $member->where(array('account'=>$keys['user']))->getField('uid')){
                        $where['uid']=$userid;
                    };
                }
                break;
        }
        if ($where) {
            $this ->howpage = 'search';
        }
        $count = $mysms->where($where)->count();
        $pConf = page($count,C('PAGE_SIZE')); // 分页
        $list = $mysms->where($where)->limit($pConf['first'], $pConf['list'])->order('time desc')->select();
        foreach ($list as $k => $v) {
            if($v['sendid']==0){
                if($v['aid']==0){
                    $list[$k]['send'] = '系统发送';
                }else{
                    $adma = $admin->where(array('aid'=>$v['aid']))->field('email')->find();
                    $list[$k]['send'] = '管理员：'.$adma['email'];
                }
            }else{
                $user = $member->where(array('uid'=>$v['sendid']))->field('uid,account,nickname,mobile,avatar')->find();
                $list[$k]['send'] = $user;
            }
            if($v['uid']==0){
                if($v['aid']!=0){
                    $adma = $admin->where(array('aid'=>$v['aid']))->field('email')->find();
                    $list[$k]['user'] = '管理员：'.$adma['email'];
                }
            }else{
                $user = $member->where(array('uid'=>$v['uid']))->field('uid,account,nickname,mobile,avatar')->find();
                $list[$k]['user'] = $user;
            }
            if($v['pid']){
                $list[$k]['auction'] = $auction->where(array('pid'=>$v['pid']))->field('pid,pname,pictures')->find();
                $list[$k]['auction']['pname'] = mb_substr($list[$k]['auction']['pname'],0,20,'utf-8').'...';
            }
        }
        $this->page = $pConf['show'];
        $this->list = $list;
        $this->keys=$keys;
        $this->display();
    }
    // 站内信设置删除状态
    public function setdelsms(){
        $sid=I('post.sid');
        if(!$sid){return false;}
        $mysms=M('mysms');
        $map['sid']=$sid;
        if(I('post.delmark')==0){
            if($mysms->where($map)->setField('delmark',1)){
                echojson(array('status' => 1, 'msg' => '已设置删除')); 
            }else{
                echojson(array('status' => 0, 'msg' => '设置删除失败')); 
            }
        }else{
           if($mysms->where($map)->setField('delmark',0)){
                echojson(array('status' => 1, 'msg' => '已取消删除')); 
            }else{
                echojson(array('status' => 0, 'msg' => '取消删除失败')); 
            } 
        }
    }
    // 删除站内信
    public function delsms(){
        if(M('mysms')->delete(I('get.sid'))){
            $this->success('删除成功！');
        }else{
            $this->error('删除失败，请重试！');
        }
    }
}