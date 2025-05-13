<?php
namespace Home\Controller;
use Think\Controller;
class MemberController extends CommonController {
	public function _initialize() {
		parent::_initialize();
		if (!$this->cUid) {
            $this->redirect("Login/index");
            exit;
        }
	}
    public function index(){
        $info=M('Member')->where(array('uid'=>$this->cUid))->find();
        // 账户安全等级【
        $score = 0;
        if ($info['verify_mobile']==1) {$score+=20;}
        if ($info['verify_email']==1) {$score+=20;}
        if (!empty($info['paypwd'])) {$score+=20;}
        if ($info['idcard_check']==2) {$score+=20;}
        if (!empty($info['question'])) {$score+=20;}
        $this->score=$score;
        $info['pledge_usable']=$info['wallet_pledge']-$info['wallet_pledge_freeze'];
        $info['limsum_usable']=$info['wallet_limsum']-$info['wallet_limsum_freeze'];
        $info['smsc']=M('mysms')->where(array('uid'=>$this->cUid,'delmark'=>0))->count();
        // 账户安全等级】
        
        // 每日新鲜事【
        if (!S(C('CACHE_FIX').'_fresh')) {
            $category = M("Category");
            $fresh['cname'] = $category->where(array('cid'=>3))->getField('name');
            $cat = new \Org\Util\Category();
            $cate = $category->order('sort desc')->select();
            $cidarr = $cat->getChildsId($cate,3);
            array_unshift($cidarr,3);//包含主分类下的新闻
            $fresh['list'] = D('News')->where(array('cid'=>array('in',$cidarr)))->limit(6)->field('id,title,name')->order('published desc')->select();
            // 后台添加文章或缓存过期会删除该缓存
            S(C('CACHE_FIX').'_fresh',$fresh,7200);
        }else{
            $fresh = S(C('CACHE_FIX').'_fresh');
        }
        $this->fresh=$fresh;
        // 每日新鲜事】

        // 我的订单【
        // 支付状态 0：待支付 1：已支付待发货 2：已发货待收货3：已收货待评价 4：已评价卖家 5：申请退货 6：同意退货 7：不同意退货 8：买家已发货 9：卖家确认收货 10：已互评
            $Dorder = D('GoodsOrder');
            $orderlist = array();
            $mywhere = array('uid'=>$this->cUid);
            // 待付款
            $fukuai = $mywhere;
            $fukuai['status'] = 0;
            $fukuai['deftime1st'] = 0;
            $ocount['fukuai']=$Dorder->where($fukuai)->count();
            $fklist = $Dorder->listOrder($fukuai);
            if ($fklist['list']) {$orderlist = $fklist['list'];}
            // 待发货
            $fahuo = $mywhere;
            $fahuo['status'] = 1;
            $fahuo['deftime2st'] = 0;
            $ocount['fahuo']=$Dorder->where($fahuo)->count();
            $fhlist=$Dorder->listOrder($fahuo);
            if (!empty($orderlist)&&!empty($fhlist['list']) ) {
                $orderlist = array_merge($orderlist,$fhlist['list']);
            }elseif (!empty($fhlist['list'])) {
                $orderlist = $fhlist['list'];
            }
            // 待收货
            $shouhuo = $mywhere;
            $shouhuo['status'] = 2;
            $shouhuo['deftime3st'] = 0;
            $ocount['shouhuo']=$Dorder->where($shouhuo)->count();
            $shlist=$Dorder->listOrder($shouhuo);
            if (!empty($orderlist)&&!empty($shlist['list']) ) {
                $orderlist = array_merge($orderlist,$shlist['list']);
            }elseif (!empty($shlist['list'])) {
                $orderlist = $shlist['list'];
            }
            // 待评价
            $pingjia = $mywhere;
            $pingjia['status'] = 3;
            $pingjia['deftime4st'] = 0;
            $ocount['pingjia']=$Dorder->where($pingjia)->count();
            $pjlist=$Dorder->listOrder($pingjia);
            if (!empty($orderlist)&&!empty($pjlist['list']) ) {
                $orderlist = array_merge($orderlist,$pjlist['list']);
            }elseif (!empty($pjlist['list'])) {
                $orderlist = $pjlist['list'];
            }
            // 退货
            $tuihuo = $mywhere;
            $tuihuo['status'] = 5;
            $ocount['tuihuo']=$Dorder->where($tuihuo)->count();
            $this->ocount = $ocount;
            $this->orderlist = $orderlist;
        // 我的订单【
        // 我关注的【
            $atinPid = M('attention')->where(array('rela'=>'p-u','uid'=>$this->cUid))->getField('gid',true);
            $attention = D('Auction')->where(array('pid'=>array('in',$atinPid),'hide'=>0))->limit(50)->field('pid,nowprice,pictures')->select();
            $this->attention=$attention;
        // 我关注的】
        // 我的足迹【
            $goods = M('goods');
            $pidstr = M('member_footprint')->where(array('uid'=>$this->cUid))->getField('pidstr');
            // 字符串转换数组
            $pidarr = unserialize($pidstr);
            // 删除重复浏览的拍品
            $pidarr = array_flip(array_flip($pidarr));
            // 转换成字符串方便查询
            $pidstr = implode(',', $pidarr);
            $footprint = M('Auction')->where("pid in (".$pidstr.")")->where(array('hide'=>0))->order("field(pid,".$pidstr.")")->field('pid,gid,nowprice')->limit(50)->select();
            foreach ($footprint as $ftkey => $ftvl) {
                $footprint[$ftkey]['pictures']=$goods->where(array('id'=>$ftvl['gid']))->getField('pictures');
            }
            $this->footprint=$footprint;
        // 我的足迹】
        // 卖家待处理数量计算
            // 卖家待发货
            $sel_count['fahuo']=$Dorder->where(array('sellerid'=>$this->cUid,'status'=>1,'deftime2st'=>0))->count();
            // 卖家待同意退货
            $sel_count['tuihuo']=$Dorder->where(array('sellerid'=>$this->cUid,'status'=>5,'deftime6st'=>0))->count();
            $sel_count['sum'] = $sel_count['fahuo']+$sel_count['tuihuo'];
            $this->sel_count=$sel_count;

        $this->info=$info;
        $this->display();
    }
    /**
     * 我的足迹
     * @return [type] [description]
     */
    public function footprint(){
        $goods = M('goods');
        $auction = M('Auction');
        $option = I('get.option');
        $pidstr = M('member_footprint')->where(array('uid'=>$this->cUid))->getField('pidstr');
        // 字符串转换数组
        $pidarr = unserialize($pidstr);
        // 删除重复浏览的拍品
        $pidarr = array_flip(array_flip($pidarr));
        // 转换成字符串方便查询
        $pidstr = implode(',', $pidarr);
        $nowTime = time();
        if($option=='fut'){
            // 未开始
            $swhere['starttime'] = array('gt',$nowTime);
        }elseif ($option=='end') {
            // 已结束
            $swhere['endtime']=array('elt',$nowTime);
        }elseif ($option=='ing'){
            // 正在拍
            $swhere['starttime']=array('elt',$nowTime);
            $swhere['endtime']=array('egt',$nowTime);
        }
        $count = $auction->where(array('pid'=>array('in',$pidstr)))->where($swhere)->count();
        $pConf = page($count,20);
        $alist = $auction->where("pid in (".$pidstr.")")->where($swhere)->limit($pConf['first'].','.$pConf['list'])->order("field(pid,".$pidstr.")")->select();
        foreach ($alist as $ftkey => $ftvl) {
            $alist[$ftkey]['pictures']=$goods->where(array('id'=>$ftvl['gid']))->getField('pictures');
            if($ftvl['starttime']<=$nowTime&&$ftvl['endtime']>=$nowTime){
                $alist[$ftkey]['stage'] = 'ing';
            }elseif ($ftvl['endtime']<$nowTime) {
                $alist[$ftkey]['stage'] = 'end';
            }elseif ($ftvl['starttime']>$nowTime) {
                $alist[$ftkey]['stage'] = 'fut';
            }
        }
        $this->alist=$alist;
        $this->option=$option;
        $this->page = $pConf['show'];
        $this->display('myattorbid');

    }

	/**
	 * 个人信息
	 * @return [type] [description]
	 */
    public function my_info(){
        if(IS_POST){
            $data = I('post.data');
            $data['birthday'] = strtotime($data['birthday']);
            $rst = M('Member')->save($data);
            if($rst){
                S('us'.$this->cUid,NULL);
            	echojson(array('status' => 1, 'info' => '已修改','url'=>__SELF__));
            }else{
                if ($rst==0) {
                    echojson(array('status' => 0, 'info' => '未做修改'));
                }else{
                    echojson(array('status' => 0, 'info' => '修改失败','url'=>__SELF__));
                }
            }
        }else{
            $where=array('uid'=>$this->cUid);
            $info = M('Member')->where($where)->find();
            $info['alerttype'] = explode(',', $info['alerttype']);
            $info['birthday']=date('Y-m-d',$info['birthday']);
            $where['default'] = 1;
            $address = M('deliver_address')->where($where)->find();
            $this->address = $address;

        	$this->info = $info;
        	$this->display();
        }
    }
/**
     * 验证邮箱和手机号
     * @return [type] [description]
     */
    public function check(){
        $member = M('Member');
        $uid =$this->cUid; 
        if(IS_POST){
            // 设置好后跳转的页面
            if(I('post.pid')){
                $url=U('Auction/details',array('pid'=>I('post.pid')));
            }else{
                $url = U('Member/index');
            }
            // 邮箱提交表单
            if(I('post.type')=='email'){
                if(S(I('post.email'))&&S(I('post.email'))==I('post.email_verify')){
                    $svdata = array('uid'=>$uid,'email'=>I('post.email'),'verify_email'=>1);
                    if($member->save($svdata)){
                        // 更新提醒字段
                        upalerttype($member,$uid,'email');
                        echojson(array('status' => 1, 'info' => "认证成功！",'url'=>$url));
                    }else{
                       echojson(array('status' => 0, 'info' => "验证保存失败，请与管理员联系")); 
                    }
                }else{
                    echojson(array('status' => 0, 'info' => "验证码错误，请检查"));
                }
            }
            // 手机验证码提交表单
            if(I('post.type')=='mobile'){
                if(S(I('post.mobile'))&&S(I('post.mobile'))==I('post.mobile_verify')){
                    $svdata = array('uid'=>$uid,'mobile'=>I('post.mobile'),'verify_mobile'=>1);
                    if($member->save($svdata)){
                        // 更新提醒字段
                        upalerttype($member,$uid,'mobile');
                        echojson(array('status' => 1, 'info' => "认证成功！",'url'=>$url));
                    }else{
                       echojson(array('status' => 0, 'info' => "验证保存失败，请与管理员联系")); 
                    }
                }else{
                    echojson(array('status' => 0, 'info' => "验证码错误，请检查"));
                }
            }
        }else{
            $info = $member->where(array('uid'=>$uid))->find();
            if(I('get.type')=='email'){
                $type ='email';
            }
            if(I('get.type')=='mobile'){
                $type ='mobile';
            }
            $this->pid = I('get.pid');
            $this->info=$info;
            $this->type=$type;
            $this->iswx=$iswx;
            $this->display();
        }
    }



    // 安全设置
    public function safety(){
        if(IS_POST){

            // 验证Token
            $this->checkToken();
            $option = I('post.option');
            $data = I('post.data');
            $member = M('Member');
            $uid = $this->cUid;
            $where = array('uid'=>$uid);
            switch ($option) {
                // 登陆密码
                case 'password':
                    if (I('post.verify_code')&&check_verify(I('post.verify_code'))!=1) {
                        echojson(array('status' => 0, 'info' => '验证码错了,重新输入吧!','url'=>__SELF__));
                        exit;
                    }
                    // 密码是否符合规则
                    $valid = valid_pass($data['new_pwd']);
                    if (!$valid['status']) {echojson($valid); exit();}

                    // 原始密码错误达到5次
                    $pas_err = S('password_'.$uid)?S('password_'.$uid):0;
                    if($pas_err>=5){
                        echojson(array('status' => 0, 'info' => '由于您今天连续输入5次错误的原始密码！请24小时后在进行找回密码操作。','url'=>__SELF__));
                        exit;
                    }
                    $pwd = $member->where($where)->getField('pwd');
                    if(!empty($pwd) && ($pwd != encryptPwd($data['pwd']))){
                        // 密码错误次数加1
                        S('password_'.$uid,$pas_err+1,86400);
                        if(5-S('password_'.$uid)==0){
                            echojson(array('status' => 0, 'info' => '由于您今天连续输入5次错误的原始密码！请24小时后在进行找回密码操作。','url'=>__SELF__));
                        }else{
                            echojson(array('status' => 0, 'info' => '原始密码错误,请检查！错误'.(5-S('password_'.$uid)).'次后，请24小时后在进行找回密码操作。','url'=>__SELF__));
                        }
                        
                        exit;
                    }elseif ($data['new_pwd'] != $data['new_pwded']) {
                        echojson(array('status' => 0, 'info' => '两次密码不一致'));
                        exit;
                    }
                    $rst = $member->where($where)->setField('pwd',encryptPwd($data['new_pwd']));
                    // 设置密码
                    if($rst){
                        // 如果重置密码
                        if(!empty($pwd)){
                            echojson(array('status' => 1, 'info' => '修改成功，下次请用新密码进行登陆','url' => U("Member/safety")));
                        // 如果重置密码
                        }else{
                            echojson(array('status' => 1, 'info' => '设置成功，您可以在电脑版用该密码登陆','url' => U("Member/safety")));
                        }
                    }else{
                        if ($rst==0) {
                            echojson(array('status' => 0, 'info' => '未做修改'));
                        }else{
                            echojson(array('status' => 0, 'info' => '修改失败，请重试','url'=>__SELF__));
                        }
                    }
                    break;
                // 支付密码
                case 'setpay':
                    $mobile = $member->where($where)->getField('mobile');
                    if ($mobile) {

                        // 验证码错误达到5次
                        $setpay_err = S('setpay_'.$uid)?S('setpay_'.$uid):0;
                        if($setpay_err>=5){
                            echojson(array('status' => 0, 'info' => '由于您今天连续输入5次错误的手机验证码！请24小时后在进行设置支付密码的操作。','url'=>__SELF__));
                            exit;
                        }
                        if(!S($mobile)||S($mobile)!=$data['verify_mobile']){

                            // 验证码次数加1
                            S('setpay_'.$uid,$setpay_err+1,86400);
                            if(5-S('setpay_'.$uid)==0){
                                echojson(array('status' => 0, 'info' => '由于您今天连续输入5次错误的手机验证码！请24小时后在进行设置支付密码的操作。','url'=>__SELF__));
                            }else{
                                echojson(array('status' => 0, 'info' => '手机验证码错误,请检查！错误'.(5-S('setpay_'.$uid)).'次后，请24小时后在进行设置支付密码的操作。','url'=>__SELF__));
                            }
                            exit;
                        }
                        if ($data['paypwd']!=$data['paypwded']) {
                            echojson(array('status' => 0, 'info' => '两次密码不一致'));
                            exit;
                        }
                        $svdata = array('uid'=>$where['uid'],'paypwd'=>encryptPwd($data['paypwd']));
                        $rst = $member->save($svdata);
                        if ($rst) {
                            echojson(array('status' => 1, 'info' => '修改成功，支付订单时候请输入支付密码','url' => U("Member/safety")));
                        }else{
                            if ($rst==0) {
                                echojson(array('status' => 0, 'info' => '未做修改'));
                            }else{
                                echojson(array('status' => 0, 'info' => '修改失败，请重试','url'=>__SELF__));
                            }
                        }
                    }else{
                        echojson(array('status' => 0, 'info' => "请先绑定手机号，在设置支付密码！",'url'=>U('Member/safety',array('option'=>'bindphone'))));
                    }
                    break;
                // 手机验证
                case 'bindphone':

                    // 验证码错误达到5次
                    $bindphone_err = S('bindphone_'.$uid)?S('bindphone_'.$uid):0;
                    if($bindphone_err>=5){
                        echojson(array('status' => 0, 'info' => '由于您今天连续输入5次错误的手机验证码！请24小时后在进行绑定手机的操作。','url'=>__SELF__));
                        exit;
                    }
                    // 设置好后跳转的页面
                    if(I('post.pid')){
                        $url=U('Auction/details',array('pid'=>I('post.pid')));
                    }else{
                        $url = U('Member/safety');
                    }
                    
                    $mobile = $member->where($where)->getField('mobile');
                    // 如果原手机号存在为更换手机号【
                    if ($mobile) {
                        if(!S($mobile)||S($mobile)!=$data['verify_mobile']){
                            // 验证码次数加1
                            S('bindphone_'.$uid,$bindphone_err+1,86400);
                            if(5-S('bindphone_'.$uid)==0){
                                echojson(array('status' => 0, 'info' => '由于您今天连续输入5次错误的手机验证码！请24小时后在进行绑定手机的操作。','url'=>__SELF__));
                            }else{
                                echojson(array('status' => 0, 'info' => '手机验证码错误,请检查！错误'.(5-S('bindphone_'.$uid)).'次后，请24小时后在进行绑定手机的操作。','url'=>__SELF__));
                            }
                            exit;
                        }
                    }
                    // 如果原手机号存在为更换手机号】
                    if(S($data['new_mobile'])&&S($data['new_mobile'])==$data['new_verify_mobile']){
                        $svdata = array('uid'=>$where['uid'],'mobile'=>$data['new_mobile'],'verify_mobile'=>1);
                        if($member->save($svdata)){
                            // 更新提醒字段
                            upalerttype($member,$where['uid'],'mobile');
                            echojson(array('status' => 1, 'info' => "认证成功！",'url'=>$url));
                        }else{
                           echojson(array('status' => 0, 'info' => "验证保存失败，请与管理员联系")); 
                           exit;
                        }
                    }
                    break;
                // 邮箱认证
                case 'bindemail':

                    // 验证码错误达到5次
                    $bindphone_err = S('bindemail_'.$uid)?S('bindemail_'.$uid):0;
                    if($bindemail_err>=5){
                        echojson(array('status' => 0, 'info' => '由于您今天连续输入5次错误的邮箱验证码！请24小时后在进行绑定邮箱的操作。','url'=>__SELF__));
                        exit;
                    }
                    if(S($data['email'])&&S($data['email'])==$data['verify_email']){
                        $svdata = array('uid'=>$where['uid'],'email'=>$data['email'],'verify_email'=>1);
                        if($member->save($svdata)){
                            // 更新提醒字段
                            upalerttype($member,$uid,'email');
                            echojson(array('status' => 1, 'info' => "认证成功！",'url'=>U('Member/safety')));
                        }else{
                           echojson(array('status' => 0, 'info' => "验证保存失败，请与管理员联系")); 
                        }
                    }else{
                        // 验证码次数加1
                        S('bindemail_'.$uid,$bindemail_err+1,86400);
                        if(5-S('bindemail_'.$uid)==0){
                            echojson(array('status' => 0, 'info' => '由于您今天连续输入5次错误的邮箱验证码！请24小时后在进行绑定邮箱的操作。','url'=>__SELF__));
                        }else{
                            echojson(array('status' => 0, 'info' => '邮箱验证码错误,请检查！错误'.(5-S('bindemail_'.$uid)).'次后，请24小时后在进行绑定邮箱的操作。','url'=>__SELF__));
                        }
                    }
                    break;
                // 身份验证
                case 'idcard':
                    if (empty($data['idcard_front'])) {
                        echojson(array('status' => 0, 'info' => "请上传身份证正面"));
                        exit;
                    }
                    if (empty($data['idcard_behind'])) {
                        echojson(array('status' => 0, 'info' => "请上传身份证背面"));
                        exit;
                    }
                    $data['uid']=$where['uid'];
                    $data['idcard_check']=1;
                    $data['idcard_check_time']=time();
                    $rst = $member->save($data);
                    if ($rst) {
                        echojson(array('status' => 1, 'info' => '已提交认证信息，等待认证！','url' => U("Member/safety")));
                    }else{
                        if ($rst==0) {
                            echojson(array('status' => 0, 'info' => '未做修改'));
                        }else{
                            echojson(array('status' => 0, 'info' => '验证保存失败，请重试或者与管理员联系','url'=>__SELF__));
                        }
                    }
                    # code...
                    break;
                // 安全问题
                case 'question':
                    $svdata['question'] = serialize($data['question']);
                    $svdata['uid'] = $where['uid'];
                    $rst = $member->save($svdata);
                    if ($rst) {
                        echojson(array('status' => 1, 'info' => '已设置安全问题！','url' => U("Member/safety")));
                    }else{
                        if ($rst==0) {
                            echojson(array('status' => 0, 'info' => '未做修改'));
                        }else{
                            echojson(array('status' => 0, 'info' => '验证保存失败，请重试或者与管理员联系','url'=>__SELF__));
                        }
                    }
                    break;
            }
        }else{
            $option = I('get.option');
            $where=array('uid'=>$this->cUid);
            $info = M('Member')->where($where)->find();
            if ($option=='setpay'&&empty($info['mobile'])&&$info['verify_mobile']==0) {
                $this->error('请先绑定和验证手机号，在设置支付密码！',U('Member/safety',array('option'=>'bindphone')));
            }
            if ($option=='idcard'&&($info['idcard_check']==1||$info['idcard_check']==2)) {
                $this->error('您无权进行该操作！',U('Member/safety'));
            }
            if ($option=='question') {
                if (empty($info['question'])) {
                    // 读取配置问题【
                    $question = array();
                    $arr = C('Member.question');
                    foreach ($arr as $ak => $av) {
                        $question[$ak] = explode(';', $av);
                    }
                    $this->question = $question;
                    // 读取配置问题】
                }else{
                    $this->error('您无权进行该操作！',U('Member/safety'));
                }
            }
            // 手机认证加20
            $score = 0;
            if ($info['verify_mobile']==1) {$score+=20;}
            if ($info['verify_email']==1) {$score+=20;}
            if (!empty($info['paypwd'])) {$score+=20;}
            if ($info['idcard_check']==2) {$score+=20;}
            if (!empty($info['question'])) {$score+=20;}
            $this->score=$score;
            $this->info = $info;
            $this->option = $option;
            $this->display();
        }

    }
    
    /**
    * 我的收货地址
    * @return [type] [description]
    */
    function deliver_address(){
        if(IS_POST){
            $data = I('post.data');
            // 获取来源页面设置后直接调转至来源页面【
            if (I('get.source')) {
                $urlarr = explode('-', I('get.source'));
                $url = U($urlarr[0].'/'.$urlarr[1],array($urlarr[2]=>$urlarr[3]));
            }else{
                $url = U('Member/deliver_address');
            }
            // 获取来源页面设置后直接调转至来源页面】
            $deliver_address = M('deliver_address');
            $data['uid'] = $this->cUid;
            
            if(!$data['adid']){

                if($deliver_address->where(array('uid'=>$data['uid']))->count()>20){
                    echojson(array('status' => 0, 'info' => '添加失败，您添加的地址数量已达到20个，请选择编辑','url'=>U('Member/deliver_address')));
                    exit;
                }
                // 必须有一个默认地址
                if($deliver_address->where(array('uid'=>$data['uid']))->count()==0){
                    $data['default'] = 1;
                }elseif($data['default']==1){
                    $deliver_address->where(array('uid'=>$data['uid']))->setField('default',0);
                }
                $st = $deliver_address->add($data);
                $ts = '新增';
            }else{
                // 验证修改的是否为该用户的地址
                if (!$deliver_address->where(array('adid'=>$data['adid'],'uid'=>$data['uid']))->find()) {
                    echojson(array('status' => 0, 'info' => '无权操作,请刷新页面再次尝试！','url'=>U('Member/deliver_address')));
                    exit;
                }
                // 必须有一个默认地址
                if($data['default']==1){
                    $deliver_address->where(array('uid'=>$data['uid']))->setField('default',0);
                }
                $st = $deliver_address->save($data);
                $ts = '修改';
            }
            if($st){
                echojson(array('status' => 1, 'info' => $ts.'成功','url'=>$url));
            }else{
                echojson(array('status' => 0, 'info' => $ts.'失败','url'=>U('Member/deliver_address')));
            }
        }else{
            $data = I('get.');
            $info = array();
            $deliver_address = M('deliver_address');
            if ($data['type']=='edit') {
                $info =$deliver_address->where(array('adid'=>$data['adid'],'uid'=>$this->cUid))->find();
                if(!$info){
                    $this->error('页面错误！',U('Member/deliver_address'));
                    exit;
                }
                $data['name'] = '编辑';
            }else{
                $data['name'] = '新增';
            }
            $list = $deliver_address->where(array('uid'=>$this->cUid))->select();
            $data['count'] = $deliver_address->where(array('uid'=>$this->cUid))->count();
            $data['yu'] = 20-$data['count'];
            $this->data = $data;
            $this->info = $info;
            $this->list=$list;
            $this->display();
        }
    }
    /**
     * 地址删除
     * @return [type] [description]
     */
    public function del_deliver_address(){
        $deliver_address = M('deliver_address');
        if($deliver_address->where(array('uid'=>$this->cUid,'adid'=>I('post.adid')))->delete()){
            echojson(array('status' => 1, 'info' => '删除成功'));
        }else{
            echojson(array('status' => 0, 'info' => '删除失败，可能不存在的地址','url'=>__SELF__));
        }
    }
    /**
     * 默认地址设置
     * @return [type] [description]
     */
    public function default_deliver_address(){
        $adid = I('post.adid');
        $deliver_address = M('deliver_address');
        $deliver_address->where(array('uid'=>$this->cUid))->setField('default',0);
        if($deliver_address->where(array('uid'=>$this->cUid,'adid'=>I('post.adid')))->setField('default',1)){
            
            echojson(array('status' => 1, 'info' => '已设置默认地址'));
        }else{
            echojson(array('status' => 0, 'info' => '默认地址设置失败'));
        }
    }
    // 账户记录
    public function wallet(){
        $option = I('get.option');
        $where = array('uid'=>$this->cUid);
        switch ($option) {
            case 'limsum':
                $limsum_bill = M('member_limsum_bill');
                $count = $limsum_bill->where($where)->count();
                $pConf = page($count,10);
                $bill = $limsum_bill->where($where)->limit($pConf['first'].','.$pConf['list'])->order('id desc')->select();

                $limsum=M('Member')->where($where)->field('wallet_limsum,wallet_limsum_freeze')->find();
                $wallet['usable'] = $limsum['wallet_limsum'] - $limsum['wallet_limsum_freeze'];
                $wallet['total'] = $limsum['wallet_limsum'];
                $wallet['freeze'] = $limsum['wallet_limsum_freeze'];
                break;
            default:
                $option = 'cash';
                $pledge_bill = M('member_pledge_bill');
                $count = $pledge_bill->where($where)->count();
                $pConf = page($count,10);
                $bill = $pledge_bill->where($where)->limit($pConf['first'].','.$pConf['list'])->order('id desc')->select();

                $pledge=M('Member')->where($where)->field('wallet_pledge,wallet_pledge_freeze')->find();
                $wallet['usable'] = $pledge['wallet_pledge'] - $pledge['wallet_pledge_freeze'];
                $wallet['total'] = $pledge['wallet_pledge'];
                $wallet['freeze'] = $pledge['wallet_pledge_freeze'];
                break;
        }
        $this->option=$option;
        $this->bill=$bill;
        $this->wallet=$wallet;
        $this->page = $pConf['show'];
        $this->display('wallet');  
    }
    // 账单详情
    public function walletdetails(){
        $option = I('get.option');
        $where = array('uid'=>$this->cUid,'order_no'=>I('get.order_no'));
        switch ($option) {
            case 'limsum':
                $limsum_bill = M('member_limsum_bill');
                $info = $limsum_bill->where($where)->find();
                break;
            default:
                $pledge_bill = M('member_pledge_bill');
                $info = $pledge_bill->where($where)->find();
                break;
        }
        $this->info=$info;
        $this->display();
  
    }
    // 保证金取款申请
    public function wallettake(){
        if(IS_POST){
            if (I('post.verify_code')&&check_verify(I('post.verify_code'))!=1) {
                echojson(array('status' => 0, 'info' => '验证码错了,重新输入吧!'));
                exit;
            }
            // 验证Token
            $this->checkToken();
            $data = I('post.data');
            $data['uid']=$this->cUid;
            $member = M('Member');
            $pledge = $member->where('uid='.$data['uid'])->field('wallet_pledge,wallet_pledge_freeze')->find();
            $takeMoney = $pledge['wallet_pledge'] - $pledge['wallet_pledge_freeze'];
            if($takeMoney<=0){
                echojson(array("status" => 0, "info" => "可提现金额为0元，不支持提现！",'url' => U('Member/wallettake')));
                exit;
            }
            if($data['money']<=0){
                echojson(array("status" => 0, "info" => "请输入正数！",'url' => U('Member/wallettake')));
                exit;
            }
            if ($takeMoney>=$data['money']) {
                $data['time'] =  time();
                $wallet = $member->where(array('uid'=>$data['uid']))->field('wallet_pledge,wallet_pledge_freeze')->find();
                $usable = sprintf("%.2f",$wallet['wallet_pledge']-$wallet['wallet_pledge_freeze']);
                if($tid = M('member_pledge_take')->add($data)){
                    // 冻结账户提现金额
                    if($member->where('uid='.$data['uid'])->setInc('wallet_pledge_freeze',$data['money'])){
                        // 变动方式changetype 竞拍冻结bid_freeze 竞拍解冻bid_unfreeze 后台充值admin_deposit 管理员扣除 admin_deduct 后台冻结admin_freeze 支付充值pay_deposit 支付扣除pay_deduct  提现extract  
                        $pledge_data = array(
                            'order_no'=>createNo('sfr'),
                            'uid'=>$data['uid'],
                            'changetype'=>'extract_freeze',
                            'time'=>time(),
                            'annotation'=>'提现暂时冻结可用余额，等待提现完成扣除！',
                            'expend'=>$data['money'],
                            'usable'=>sprintf("%.2f",$usable-$data['money']),
                            'balance'=>$wallet['wallet_pledge']
                            );
                        if(M('member_pledge_bill')->add($pledge_data)){
                            $pledge = $member->where('uid='.$data['uid'])->field('wallet_pledge,wallet_pledge_freeze')->find();
                            $usable = $pledge['wallet_pledge'] - $pledge['wallet_pledge_freeze'];
                            // 提醒通知卖家账户增加【
                                // 微信提醒内容
                                $wei_extract['tpl'] = 'walletchange';
                                $wei_extract['msg']=array(
                                    "url"=>U('Home/Member/wallet','','html',true), 
                                    "first"=>"您好，".'申请提现冻结可用余额！',
                                    "remark"=>$pledge_data['annotation'].'查看账户记录>>',
                                    "keyword"=>array('余额账户','交易收入','订单:'.$pledge_data['order_no'],'-'.$pledge_data['expend'].'元',$usable.'元')
                                );
                                // 账户类型，操作类型、操作内容、变动额度、账户余额
                                // 站内信提醒内容
                                $web_extract = array(
                                    'title'=>'提现冻结',
                                    'content'=>$pledge_data['annotation']
                                    );
                                // 短信提醒内容
                                if(mb_strlen($data['pname'],'utf-8')>10){
                                    $newname = mb_substr($data['pname'],0,10,'utf-8').'...';
                                }else{
                                    $newname = $data['pname'];
                                }
                                $note_extract = $pledge_data['annotation'].'单号'.$pledge_data['order_no'].'。冻结【'.$pledge_data['expend'].'元】，可用余额【'.$usable.'元】，您可以登陆平台查看账户记录。';
                                // 邮箱提醒内容
                                $mail_extract['title'] = '买家确认收货“'.$newname.'”';
                                $mail_extract['msg'] = '您好：<br/><p>'.$pledge_data['annotation'].'单号'.$pledge_data['order_no'].'。冻结【'.$pledge_data['expend'].'元】，可用余额【'.$usable.'元】。'.'</p><p>您可以<a target="_blank" href="'.U('Home/Member/wallet',array('option'=>'limsum'),'html',true).'">查看账户记录</a></p>';

                                sendRemind($member,M('Member_weixin'),array(),array($data['uid']),$web_extract,$wei_extract,$note_extract,$mail_extract,'buy');
                            // 提醒通知卖家账户增加【
                            echojson(array("status" => 1, "info" => "已提交申请，等待退款",'url' => U('Member/wallettake')));
                        } //写入用户账户记录
                    }else{
                        echojson(array("status" => 0, "info" => "冻结提现金额失败，请重试",'url' => U('Member/wallettake')));
                    }
                    
                }else{
                    echojson(array("status" => 0, "info" => "提交申请失败",'url' => U('Member/wallettake')));
                }
            }else{
                echojson(array("status" => 0, "info" => "可提现金额不足！请检查！",'url' => U('Member/wallettake')));
            }
            
        }else{
            $where = array('uid'=>$this->cUid);
            // 保证金账户余额
            $info=M('Member')->where($where)->field('wallet_pledge,wallet_pledge_freeze')->find();
            $info['usable'] = $info['wallet_pledge'] - $info['wallet_pledge_freeze'];
            $this->info=$info;
            if(I('get.take')=='form'){
                $this->display('wallettakeform');
            }else{
                $pledge_take = M('member_pledge_take');
                // 我的提现记录
                $count = $pledge_take->where($where)->count();
                $pConf = page($count,10);
                $this->take_list = $pledge_take->where($where)->limit($pConf['first'].','.$pConf['list'])->order('time desc')->select();
                $this->page = $pConf['show'];
                $this->display();
            }
            
        }
        
    }
    // 账单详情
    public function takedetails(){
        $tid = I('get.tid');
        $where = array('uid'=>$this->cUid,'tid'=>$tid);
        $info=M('member_pledge_take')->where($where)->find();
        if (!$info) {
            $this->error('您无权查看该页面！');
        }
        $this->info=$info;
        $this->display();
  
    }
    /**
     * 订单支付
     * @return [type] [description]
     */
    public function payment_order(){
        if(I('get.order_no')!=''){
        // 订单信息读取【
            $Dorder = D('GoodsOrder');
            $goods_user = M('goods_user');
            $uid = $this->cUid;
            $oinfo = $Dorder->findOrder(array('order_no'=>I('get.order_no'),'status'=>0,'uid'=>$uid));
            if(!$oinfo){
                $this->error('订单号已失效或不存在的订单号！');
            }
            // 地址列表【
            $address = M('deliver_address')->where(array('uid'=>$oinfo['uid']))->order('`default` desc')->select();
            if (!$address) {
                $this->error('请完善您的地址信息！',U('Member/deliver_address',array('type'=>'add','source'=>'Member-payment_order-order_no-'.I('get.order_no'))));
            }
            $this->address=$address;
            // 地址列表】
        // 订单信息读取】
        // 是否可用缴纳的保证金支付【
            // 专场还是单品拍的条件
            if($oinfo['sid']!=0){
                $special = M('special_auction')->where(array('sid'=>$oinfo['sid']))->find();
                // 专场扣除模式且专场已结束
                if($special['special_pledge_type']==0&&$special['endtime']<=time()){
                    // 该用户拍到多少拍品
                    $cbidw = array('g-u'=>'s-u','uid'=>$oinfo['uid'],'gid'=>$oinfo['sid'],'status'=>0);
                    $frezze = $goods_user->where($cbidw)->field('limsum,pledge')->find();
                    if($frezze['pledge']>0){
                        // 用户在专场拍到的拍品id
                        $spidarr = M('Auction')->where(array('sid'=>$oinfo['sid']))->getField('pid',true);
                        // 是否全部支付
                        $paystaw = array(
                            'uid'=>$oinfo['uid'],
                            'gid'=>array('in',$spidarr),
                            'status'=>0
                        );
                        // 全部支付的话进行退还
                        $paysize = M('goods_order')->where($paystaw)->count();
                        // 剩余最后一个未支付的话可以用保证金支付
                        if($paysize==1){
                           $paypledge = 1;
                        }else{
                            $paypledge = 0;
                        }
                    }else{
                        $paypledge = 0;
                    }
                }else{
                   // 获取支付拍品保证金金额
                    $frezze = $goods_user->where(array('g-u'=>'p-u','uid'=>$oinfo['uid'],'gid'=>$oinfo['gid'],'status'=>0))->field('limsum,pledge')->find();
                    if($frezze['pledge']>0){
                        $paypledge = 1;
                    }else{
                        $paypledge = 0;
                    }
                }
            }else{
                // 获取支付拍品保证金金额
                $frezze = $goods_user->where(array('g-u'=>'p-u','uid'=>$oinfo['uid'],'gid'=>$oinfo['gid'],'status'=>0))->field('limsum,pledge')->find();
                if($frezze['pledge']>0){
                    $paypledge = 1;
                }else{
                    $paypledge = 0;
                }
            }
            $this->frezze=$frezze;
            // 保证金足够抵货款，仅能使用保证抵货款
            if ($paypledge == 1 && $frezze['pledge']>=$oinfo['total']) {
                $onlypledge = 1;
            }else{
                $onlypledge = 0;
            // 是否可用缴纳的保证金支付】
            // 用户可用余额【
                $member = M('Member');
                $ufield=array('wallet_pledge','wallet_pledge_freeze');
                $uLimit = $member->where(array('uid'=>$uid))->field($ufield)->find();
                // 可用余额
                $uLimit['usable'] = $uLimit['wallet_pledge']-$uLimit['wallet_pledge_freeze'];
                $uLimit['usable'] = $uLimit['usable']>0?sprintf("%.2f", $uLimit['usable']):0;
            // 用户可用余额】
            // 余额是否足够支付【
                if($oinfo['total']<=$uLimit['usable']){
                    $uLimit['yfmn'] = sprintf("%.2f",$oinfo['total']);
                    $uLimit['satisfy'] = 1;
                }else{
                    $uLimit['yfmn'] = sprintf("%.2f",$uLimit['usable']);
                    $uLimit['satisfy'] = 0;
                }
                $this->uLimit=$uLimit;
                // pre($uLimit);
                // die;
            // 余额是否足够支付】
                //读取支付配置
                foreach (C('payment.list') as $pk => $pv) {
                    if($pv['status']){
                        $pv['value'] = $pk;
                        if(is_weixin()){
                            if($pv['arena']=='jsapi'){
                                $channel[] = $pv;
                            }
                        }else{
                            if(ismobile()){
                                if($pv['arena']=='wap'||$pv['arena']=='all'){
                                    $channel[] = $pv;
                                }
                            }else{
                                if ($pv['arena']=='pc'||$pv['arena']=='all') {
                                    $channel[] = $pv;
                                }
                            }
                        }
                    }
                }
                $this->channel=$channel;
                //读取支付配置】
            }
            $this->oinfo=$oinfo;
            $this->onlypledge=$onlypledge;
            $this->paypledge=$paypledge;
            $this->display();
        }else{
            $this->error('不存在的订单号！');
        }
    }
    /**
     * 充值保证金
     * @return [type] [description]
     */
    public function payment(){
        // 获取支付类型
        $use=I('get.use');
        $this->od_url=U('Home/Member/wallet');
        //读取支付配置
        foreach (C('payment.list') as $pk => $pv) {
            if($pv['status']){
                $pv['value'] = $pk;
                if(is_weixin()){
                    if($pv['arena']=='jsapi'){
                        $channel[] = $pv;
                    }
                }else{
                    if(ismobile()){
                        if($pv['arena']=='wap'||$pv['arena']=='all'){
                            $channel[] = $pv;
                        }
                    }else{
                        if ($pv['arena']=='pc'||$pv['arena']=='all') {
                            $channel[] = $pv;
                        }
                    }
                }
            }
        }
        $this->channel=$channel;
        $this->display();
    }
// 充值卡充值
    public function rechargeable(){
        if (IS_POST) {
            if (I('post.verify')&&check_verify(I('post.verify_code'))!=1) {
                echojson(array('status' => 0, 'info' => '验证码错了,重新输入吧!'));
                exit;
            }
            $data = I('post.data');
            // 每天错误三次锁定卡号，提示第二天在试【
            $cache = S('cardno'.$data['cardno'])?S('cardno'.$data['cardno']):0;
            if($cache>=3){
               echojson(array('status' => 0, 'info' => '该充值卡已被锁定，请明天在试吧！'));
                exit; 
            }
            $rechargeable = M('rechargeable');
            if($info = $rechargeable->where(array('cardno'=>$data['cardno']))->find()){
                if ($data['pwd']!=$info['pwd']) {
                    S('cardno'.$data['cardno'],$cache+1);
                    echojson(array('status'=>0,'info'=>'账号或密码不对，请检查！'));
                    exit();
                }
            }
            // 每天错误三次锁定卡号，提示第二天在试】
            if ($info) {
                if ($info['status']==0) {
                    if($info['pasttime']){
                        if ($info['pasttime']<time()) {
                            echojson(array('status'=>0,'info'=>'充值卡已过期，无法进行充值！'));
                            exit();
                        }
                        $info['pasttime'] = date('Y-m-d H:i',$info['pasttime']);
                    }else{
                        $info['pasttime'] = '永久有效';
                    }
                    // 验证充值卡
                    if (I('post.verify')) {

                        $msg = '';
                        if ($info['pledge']>0) {
                            $msg .= '账户充值：<strong>'.$info['pledge'].'</strong>元<br/>';
                        }
                        if ($info['limsum']>0) {
                            $msg .= '信誉充值：<strong>'.$info['limsum'].'</strong>元<br/>';
                        }
                        if ($info['pasttime']) {
                            $msg .= '过期时间：<strong>'.$info['pasttime'].'</strong><br/>';
                        }
                        $msg .='<strong>是否立即充值？</strong>';
                        echojson(array('status'=>1,'info'=>$msg));
                    }else{
                        // 充值卡充值【
                        $member = M('member');
                        $uid = $this->cUid;
                        $wr = array('uid'=>$uid);
                        $wallet = $member->where($wr)->field('wallet_limsum,wallet_limsum_freeze,wallet_pledge,wallet_pledge_freeze')->find();
                        $p_usable = sprintf("%.2f",$wallet['wallet_pledge']-$wallet['wallet_pledge_freeze']);
                        $l_usable = sprintf("%.2f",$wallet['wallet_limsum']-$wallet['wallet_limsum_freeze']);
                        if ($info['pledge']>0) {
                            if($member->where($wr)->setInc('wallet_pledge',$info['pledge'])){
                                // 账户余额增加
                                $pledge_data = array(
                                    'order_no'=>createNo('cad'),
                                    'uid'=>$uid,
                                    'changetype'=>'card_deposit',
                                    'time'=>time(),
                                    'annotation'=>'使用充值卡充值充值余额【'.$info['pledge'].'元】',
                                    'income'=>$info['pledge'],
                                    'usable'=>sprintf("%.2f",$p_usable+$info['pledge']),
                                    'balance'=>sprintf("%.2f",$wallet['wallet_limsum']+$info['pledge'])
                                    );
                            }
                        }
                        if ($info['limsum']>0) {
                            if($member->where($wr)->setInc('wallet_limsum',$info['limsum'])){
                                // 信誉额度增加
                                $limsum_data = array(
                                    'order_no'=>createNo('cad'),
                                    'uid'=>$uid,
                                    'changetype'=>'card_deposit',
                                    'time'=>time(),
                                    'annotation'=>'使用充值卡充值充值信誉额度【'.$info['limsum'].'元】',
                                    'income'=>$info['limsum'],
                                    'usable'=>sprintf("%.2f",$l_usable+$info['limsum']),
                                    'balance'=>sprintf("%.2f",$wallet['wallet_limsum']+$info['limsum'])
                                    );
                                $l_usable = $l_usable+$info['limsum'];
                            }
                        }
                        if ($pledge_data) {
                            if (M('member_pledge_bill')->add($pledge_data)) {
                                // 提醒通知冻结保证金【
                                    // 微信提醒内容
                                    $wei_pledge_freeze['tpl'] = 'walletchange';
                                    $wei_pledge_freeze['msg']=array(
                                        "url"=>U('Home/Member/wallet','','html',true), 
                                        "first"=>'您好，使用充值卡充值充值余额！',
                                        "remark"=>'查看账户记录>>',
                                        "keyword"=>array('余额账户',$ac.'余额','充值卡充值',$pledge_data['income'].'元',$pledge_data['usable'].'元')
                                    );
                                    // 账户类型，操作类型、操作内容、变动额度、账户余额
                                    // 站内信提醒内容
                                    $web_pledge_freeze = array(
                                        'title'=>'充值卡充值',
                                        'content'=>'充值卡充值余额【'.$pledge_data['income'].'元】，单号'.$pledge_data['order_no']
                                        );
                                    // 短信提醒内容
                                    $note_pledge_freeze = '充值卡充值余额【'.$pledge_data['income'].'元】，'.'单号'.$pledge_data['order_no'].'，您可以登陆平台查看账户记录。';
                                    // 邮箱提醒内容
                                    $mail_pledge_freeze['title'] = '充值卡充值余额【'.$pledge_data['income'].'元】';
                                    $mail_pledge_freeze['msg'] = '您好：<br/><p>'.'充值卡充值余额【'.$pledge_data['income'].'元】'.'</p><p>您可以<a target="_blank" href="'.U('Home/Member/wallet','','html',true).'">查看账户记录</a></p>';
                                    sendRemind($member,M('Member_weixin'),array(),array($uid),$web_pledge_freeze,$wei_pledge_freeze,$note_pledge_freeze,$mail_pledge_freeze,'buy');
                                // 提醒通知冻结保证金【
                                    $pstatus = 1;
                            }else{
                                $pstatus = 0;
                            }
                        }
                        if ($limsum_data) {
                            if (M('member_limsum_bill')->add($limsum_data)) {
                                // 提醒通知冻结保证金【
                                    // 微信提醒内容
                                    $wei_limsum_freeze['tpl'] = 'walletchange';
                                    $wei_limsum_freeze['msg']=array(
                                        "url"=>U('Home/Member/wallet',array('option'=>'limsum'),'html',true), 
                                        "first"=>'您好，使用充值卡充值充值信用额度！',
                                        "remark"=>'查看账户记录>>',
                                        "keyword"=>array('信用额度账户','冻结信用额度','充值卡充值',$limsum_data['income'].'元',$limsum_data['usable'].'元')
                                    );
                                    // 账户类型，操作类型、操作内容、变动额度、账户信用额度
                                    // 站内信提醒内容
                                    $web_limsum_freeze = array(
                                        'title'=>'充值卡充值',
                                        'content'=>'充值卡充值信用额度【'.$limsum_data['income'].'元】，单号'.$limsum_data['order_no']
                                        );
                                    // 短信提醒内容
                                    $note_limsum_freeze = '充值卡充值信用额度【'.$limsum_data['income'].'元】，'.'单号'.$limsum_data['order_no'].'，您可以登陆平台查看账户记录。';
                                    // 邮箱提醒内容
                                    $mail_limsum_freeze['title'] = '充值卡充值信用额度【'.$limsum_data['income'].'元】';
                                    $mail_limsum_freeze['msg'] = '您好：<br/><p>'.'充值卡充值信用额度【'.$limsum_data['income'].'元】'.'</p><p>您可以<a target="_blank" href="'.U('Home/Member/wallet',array('option'=>'limsum'),'html',true).'">查看账户记录</a></p>';
                                    sendRemind($member,M('Member_weixin'),array(),array($uid),$web_limsum_freeze,$wei_limsum_freeze,$note_limsum_freeze,$mail_limsum_freeze,'buy');
                                // 提醒通知冻结保证金【
                                    $lstatus = 1;
                            }else{
                                $lstatus = 0;
                            }
                        }
                        if ($lstatus==1||$pstatus==1) {
                            $rechargeable->where(array('cardno'=>$data['cardno']))->setField('status',1);
                            echojson(array('status' => 1, 'info' => '充值成功！','url'=>U('Home/Member/wallet','','html',true)));
                        }else{
                            echojson(array('status' => 0, 'info' => '更新数据失败，请联系管理员解决！','url'=>__SELF__));
                        }
                        // 充值卡充值】
                    }
                }elseif ($info['status']==1) {
                    echojson(array('status'=>0,'info'=>'该充值卡已使用，请更换！'));
                }else{
                    echojson(array('status'=>0,'info'=>'该充值卡已过期，请更换！'));
                }
            }else{
                S('cardno'.$data['cardno'],$cache+1);
                echojson(array('status'=>0,'info'=>'未查询到该充值卡信息！'));
            }
        }else{
            $this->display('payment');
        }
        
    }
    // ------地区标签使用
    public function region(){
        if (IS_POST) {
            $region = M('region');
            $field = array('region_id','region_name');
            if (I('post.tier') == 1) {
                $tier = 2;
                $selected = '——选择城市——';
            }elseif (I('post.tier') == 2) {
                $tier = 3;
                $selected = '——选择区、县——';
            }
            $option = $region->field($field)->where(array('parent_id'=>I('post.pid')))->select();
            $optionHtml = '<option selected="selected" tier="'.$tier.'" value="0">'.$selected.'</option>';
            foreach ($option as $ok => $ov) {
                $optionHtml .= '<option tier="'.$tier.'" value="'.$ov['region_id'].'">'.$ov['region_name'].'</option>';
            }
            echojson(array('status' => 1, 'msg' => $optionHtml)); 
        }
    }
    // --------关注和取消关注处理
    public function attention(){
        if (IS_POST) {
            $att = M('attention');
            $data = array(
                'gid'=>I('post.gid'),
                'rela'=>I('post.rela'),
                'uid'=>$this->cUid
            );
            if(I('post.yn')=='n'){
                if($att->add($data)){
                    echojson(array('status' => 1, 'info' => '关注成功'));  
                }else{
                    echojson(array('status' => 0, 'info' => '关注失败，请刷新页面重试'));
                } 
            }elseif(I('post.yn')=='y'){
                if($att->where($data)->delete()){
                    echojson(array('status' => 1, 'info' => '已取消关注'));  
                }else{
                    echojson(array('status' => 0, 'info' => '取消关注失败，请刷新页面重试'));
                } 
            }
        }
    }
    // 消息站内信
    public function mysms(){
        if (IS_POST) {
            $mysms = M('mysms');
            $sid=I('post.sid');
            $where = array('sid'=>array('in',$sid));
            if(I('post.ac')=='del'){
                $count = M('mysms')->where($where)->setField('delmark',1);
                $t = '删除';
            }elseif(I('post.ac')=='read'){
                $count = $mysms->where($where)->setField('status',1);
                $t = '设置已读';
            }
            if($count){
                echojson(array("status" => 1, "info" => $t."成功",'url' => __SELF__));
            }else{
                echojson(array("status" => 0, "info" => $t."失败，请重试",'url' => __SELF__));
            }
        }else{
            $mysms = M('mysms');
            $auction = D('Auction');
            // 用户消息
            $ucwer['_string']="(aid != 0 and uid=".$this->cUid.") or (uid=".$this->cUid." and sendid != 0)";
            // 系统消息
            $scwer = array('uid'=>$this->cUid,'sendid'=>0,'aid'=>0);
            $where = $scwer;
            if(I('get.tp')=='usend'){
                $where = $ucwer;
            }else{
                $where = $scwer;
            }
            // 统计未读
            $ucwer['status'] = 0;
            $ucwer['delmark'] = 0;
            $scwer['status'] = 0;
            $scwer['delmark'] = 0;
            $this->sc = $mysms->where($scwer)->count();
            $this->uc = $mysms->where($ucwer)->count();

            // 读取列表到页面
            $where['delmark']=0;
            $count = $mysms->where($where)->count();

            $pConf = page($count,20);
            $slist = $mysms->where($where)->limit($pConf['first'].','.$pConf['list'])->order('time desc')->select();
            $member = M('member');
            foreach ($slist as $k => $v) {
                $slist[$k]['user']=$member->where(array('uid'=>$v['sendid']))->field('account,nickname')->find();
                $slist[$k]['content'] = preg_replace("/<a[^>]*>(.*?)<\/a>/is", "$1", $slist[$k]['content']);
                if($v['pid']){
                    $slist[$k]['auction'] = $auction->where(array('pid'=>$v['pid']))->field('pid,pname,pictures')->find();
                    $slist[$k]['auction']['pname'] = mb_substr($slist[$k]['auction']['pname'],0,20,'utf-8').'...';
                }
            }
            $this->tp=I('get.tp');
            $this->slist=$slist;
            $this->page = $pConf['show'];
            $this->display(); 
        }
    }
    /**
     * 消息详情
     * @return [type] [description]
     */
    public function mysms_default(){
        $sid = I('get.sid');
        $uid = $this->cUid;
        $member = M('member');
        $mysms = M('mysms');
        // 设置已读
        $mysms->where(array('uid'=>$uid,'sid'=>$sid))->setField('status',1);
        // 消息以及商品关联信息
        $info = $mysms->where(array('_string'=>"(uid=".$uid." or sendid=".$uid.") and sid=".$sid))->find();
        if($info['pid']){
            $this->auction = D('Auction')->where(array('pid'=>$info['pid']))->field('pid,pname,pictures,bidnb,nowprice,endtime')->find();
        }
        if ($info['uid']==$uid) {
            $heid = $info['sendid'];
        }else{
            $heid = $uid;
        }
        if($heid==0){
            if ($info['aid']==0) {
                $guest = array('nickname'=>'系统','avatar'=>'');
                $list = $mysms->where(array('uid'=>$uid,'sid'=>$sid))->select();
            }else{
                $guest = M('admin')->where(array('aid'=>$info['aid']))->find();
                $list = $mysms->where(array('uid'=>$uid,'aid'=>$info['aid']))->select();
            }
        }else{
            $guest = $member->where(array('uid'=>$heid))->field('uid,account,nickname,avatar')->find();
            $this->guest=$guest;
            // 会话列表
            $where['_string'] = "((uid = ".$info['uid']." and sendid=".$info['sendid'].") or (uid=".$info['sendid']." and sendid = ".$info['uid'].") and (status != 2))";
            $list = $mysms->where($where)->order('time asc')->select();
        }

        $myinfo = $member->where(array('uid'=>$uid))->field('uid,account,nickname,avatar')->find();
        $this->myinfo=$myinfo;
        

        $this->info=$info;
        $this->list = $list;
        $this->display();
    }
    /**
     * 消息删除
     * @return [type] [description]
     */
    public function mysms_reply(){
        if (IS_POST) {
            $sid = I('post.sid');
            $uid = $this->cUid;
            $content = I('post.content');
            $url = U('Member/mysms_default',array('sid'=>$sid));
            if ($content=='') {
                echojson(array('status' => 0, 'info' => '发送消息不能为空','url'=>$url));
            }
            $mysms = M('mysms');
            // 消息以及商品关联信息
            $info = $mysms->where(array('uid'=>$uid,'sid'=>$sid))->find();
            if ($uid != $info['sendid']) {
                $data=array(
                    'sendid'=>$uid,
                    'uid'=>$info['sendid'],
                    'rsid'=>$sid,
                    'pid'=>$info['pid'],
                    'time'=>time(),
                    'content'=>$content
                );
                if ($nsid = $mysms->add($data)) {
                     echojson(array('status' => 1, 'info' => '已发送！','url'=>U('Member/mysms_default',array('sid'=>$nsid))));
                }else{
                     echojson(array('status' => 0, 'info' => '发送失败！','url'=>$url));
                }
            }else{
                echojson(array('status' => 0, 'info' => '您不能回复自己的消息！','url'=>$url));
            }
        }else{}
    }

    /**
     * 消息删除
     * @return [type] [description]
     */
    public function del_mysms(){
        $mysms = M('mysms');
        if($mysms->where(array('uid'=>$this->cUid,'sid'=>I('post.sid')))->setField('delmark',1)){
            echojson(array('status' => 1, 'info' => '删除成功'));
        }else{
            echojson(array('status' => 0, 'info' => '删除失败，可能不存在的地址','url'=>__SELF__));
        }
    }
        // 发送消息
    public function sendmsg(){
        if (IS_POST) {
            $mysms = M('mysms');
            $info = I('post.info');
            // 群组发送
            if(I('post.tp')=='gp'){
                $scount = 0;
                $sendst = 0;
                switch (I('post.gp')) {
                    case '0':
                        $uidarr = array_unique(M('attention_seller')->where(array('sellerid'=>$this->cUid))->getField('uid',true));
                        break;
                    case '1':
                        $uidarr = array_unique(M('goods_order')->where(array('sellerid'=>$this->cUid))->getField('uid',true));
                        # code...
                        break;
                    case '2':
                        $uidarr = M('member')->getField('uid',true);
                        break;
                }
                $data['content'] = $info['content'];
                $data['sendid']=$this->cUid;
                $data['time'] = time();
                $data['type'] = '用户发送';
                if(count($uidarr)>0){
                    foreach ($uidarr as $k => $v) {
                        if($v!=$this->cUid){
                            $data['uid'] = $v;
                            if($mysms->add($data)){
                                $scount+=1;
                            }
                        }
                    }
                    if($scount>0){
                        $alert = '成功发送'.$scount.'条站内信';
                        $sendst = 1;
                    } 
                }else{
                    echojson(array("status" => 0, "info" => "没有接受的用户，发送消息失败",'url'=>__SELF__));
                    exit;
                }
            }else{
            // 一对一发送
                if(I('get.sid')!=0){
                    $sms = $mysms->where(array('sid'=>I('get.sid')))->find();
                    $data['rsid'] = $sms['sid'];
                    $data['uid'] = $sms['sendid'];
                    $data['pid'] = $sms['pid'];
                    if($info['aid']!=0){
                        $data['aid'] = $sms['aid'];
                    }
                }elseif(I('get.uid')!=''){
                    $data['uid'] = I('get.uid');
                    if(I('post.topid')==1){
                        $data['pid'] = $info['pid'];
                    }
                }
                $data['content'] = $info['content'];
                $data['sendid']=$this->cUid;
                $data['time'] = time();
                $data['type'] = '用户发送';
                $sendst = $mysms->add($data);
                $alert = '已发送消息';
            }
            if($sendst){
                echojson(array("status" => 1, "info" => $alert,'url'=>U('Member/mysms_default',array('sid'=>$sendst))));
            }else{
                echojson(array("status" => 0, "info" => "发送失败请重试",'url'=>__SELF__));
            }
        }else{
            // 回复或发送信息
            $get = I('get.');
            if($get['sid']!='' || $get['uid']!=''){
                $mysms = M('mysms');
                if($get['sid']!=''){
                    $uid = $mysms->where(array('sid'=>$get['sid']))->getField('sendid');
                    $pid = $mysms->where(array('sid'=>$get['sid']))->getField('pid');
                    $auction = D('Auction')->where(array('pid'=>$pid))->find();
                    $mysms->where(array('sid'=>$get['sid']))->setField('status',1);
                    $sid=$get['sid'];
                }elseif($get['uid']!=''){
                    $uid = $get['uid'];
                    $sid = 0;
                    $pid = I('get.pid');
                }
                $auction = D('Auction')->where(array('pid'=>$pid,'sellerid'=>$uid))->find();
                // 商品存在
                if($auction){
                    $this->auction=$auction;
                }
                $info = M('Member')->where(array('uid'=>$uid))->field('uid,account,nickname,organization')->find();
                $info['rsid'] = $sid;
                $this->info = $info;
            // 群组发送消息
            }else{
                $sendct[0] = count(array_unique(M('attention_seller')->where(array('sellerid'=>$this->cUid))->getField('uid',true)));
                $sendct[1] = count(array_unique(M('goods_order')->where(array('sellerid'=>$this->cUid,'uid'=>array('neq',$this->cUid)))->getField('uid',true)));
                $sendct[2] = count(M('member')->getField('uid',true))-1;
                $this->sendct=$sendct;
                $this->tp='gp';
            }
            $this->how = $get['how'];
            $this->display();
        }
    }
    // 已发送站内信
    public function sendlist(){
        $mysms = M('mysms');
        $member = M('member');
        $auction = D('Auction');
        $where = array('sendid'=>$this->cUid,'status'=>array('neq',2));
        $count = $mysms->where($where)->count();
        $pConf = page($count,C('PAGE_SIZE'));
        $list = $mysms->where($where)->limit($pConf['first'].','.$pConf['list'])->order('time desc')->select();
        foreach ($list as $k => $v) {
            $list[$k]['user']=$member->where(array('uid'=>$v['uid']))->field('account,nickname')->find();
            if($v['pid']){
                $list[$k]['auction'] = $auction->where(array('pid'=>$v['pid']))->field('pid,pname')->find();
            }
        }
        $this->list=$list;
        $this->page = $pConf['show']; 
        $this->display('sendlist');
    }
// 会话记录
    public function exchange(){
        $member = M('member');
        $mysms = M('mysms');
        $idarr = $mysms->where(array('sid'=>I('get.sid')))->field('uid,sendid,pid')->find();
        // 获取对话人id
        if($idarr['uid']==$this->cUid){
            $sellerid = $idarr['sendid'];
        }
        if($idarr['sendid']==$this->cUid){
            $sellerid = $idarr['uid'];
        }
        if($idarr['pid']){
            $this->auction = M('auction')->where(array('pid'=>$idarr['pid']))->field('pname,pid')->find();
        }
        $guest = $member->where(array('uid'=>$sellerid))->field('account,nickname')->find();
        $this->guest=$guest;

        // 会话列表
        $where['_string'] = "((uid = ".$idarr['uid']." and sendid=".$idarr['sendid'].") or (uid=".$idarr['sendid']." and sendid = ".$idarr['uid'].") and (status != 2))";
        // 如果后台发的私信需要添加该条件
        if($sellerid==0){
            $where['aid'] = array('neq',0);
        }
        $list = $mysms->where($where)->order('time desc')->select();

        $this->myid = $this->cUid;
        $this->list = $list;
        $this->display();
    }



    public function mysmssdf(){
        $mysms = M('mysms');
        if (IS_POST) {
            $sid=I('post.sid');
            foreach ($sid as $pk => $pv) {
                if($mysms->where('sid='.$pv)->delete()){
                   $dcount+=1; 
                }
            }
            if($dcount==count($sid)){
                echojson(array("status" => 1, "info" => "删除成功",'url' => U('Member/mysms', array('time' => time()))));
            }else{
                echojson(array("status" => 0, "info" => "删除失败，请重试",'url' => U('Member/mysms', array('time' => time()))));
            }
        }else{
            // 设置为已读
            $smsid=$mysms->where(array('uid'=>$this->cUid))->getField('sid',true);
            foreach ($smsid as $smk => $smv) {
                $mysms->where('sid='.$smv)->setField('status',1);
            }
            // 读取列表到页面
            $count = $mysms->where(array('uid'=>$this->cUid))->count();
            $pConf = page($count,20);
            $slist = $mysms->where(array('uid'=>$this->cUid))->limit($pConf['first'].','.$pConf['list'])->order('time desc')->select();
            $this->slist=$slist;
            $this->page = $pConf['show'];
            $this->display(); 
        }
    }
    // 关注卖家
    public function attseller(){
        $bluidarr = M('blacklist')->where(array('uid'=>$this->cUid,'selbuy'=>'sel'))->getField('xid',true);
        $uidarr = M('attention_seller')->where(array('uid'=>$this->cUid))->order('time desc')->getField('sellerid',true);
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
    // 屏蔽卖家
    public function blacklist(){
        $uidarr = M('blacklist')->where(array('uid'=>$this->cUid,'selbuy'=>'sel'))->order('time desc')->getField('xid',true);
        $member=M('member');
        $where = array('uid'=>array('in',$uidarr));
        $count = $member->where($where)->count();
        $pConf = page($count,C('PAGE_SIZE'));
        $list = $member->where($where)->limit($pConf['first'].','.$pConf['list'])->select();
        $this->page = $pConf['show'];
        $this->list = $list;
        $this->display('Common:userbox');
    }
    // 关注列表
    public function myatt(){
        $att = M('attention');
        $bidmap = D('Auction');
        $member =M('member');
        $option = I('get.option');
        $nowTime=time();
           
        $inPid = $att->where(array('rela'=>'p-u','uid'=>$this->cUid))->getField('gid',true);
        $swhere = array('pid'=>array('in',$inPid));
        if($option=='fut'){
            // 未开始
            $swhere['starttime'] = array('gt',$nowTime);
        }elseif ($option=='end') {
            // 已结束
            $swhere['endtime']=array('elt',$nowTime);
        }elseif ($option=='ing'){
            // 正在拍
            $swhere['starttime']=array('elt',$nowTime);
            $swhere['endtime']=array('egt',$nowTime);
        }
        if ($xUidarr = blackuser($this->cUid)) {
            $swhere['sellerid'] = array('not in',$xUidarr);
        }
        // 分页配置
        $count = $bidmap->where($swhere)->count();
        $pConf = page($count,20);
        $alist = $bidmap->where($swhere)->limit($pConf['first'].','.$pConf['list'])->select();
        foreach ($alist as $ak => $av) {
            $alist[$ak]['nickname']=nickdis($member->where(array('uid'=>$av['uid']))->getField('nickname'));
            if ($av['starttime']>$nowTime) {
                $alist[$ak]['stage'] = 'fut';
            }elseif ($av['starttime']<=$nowTime&&$av['endtime']>$nowTime) {
                $alist[$ak]['stage'] = 'ing';
            }elseif ($av['endtime']<$nowTime){
                $alist[$ak]['stage'] = 'end';
            }
        }
        $this->alist=$alist;
        $this->option=$option;
        $this->page = $pConf['show'];
        $this->display('myattorbid');
    }
    // 我的出价
    public function mybid(){
        $auction_record = M('auction_record');
        $bidmap = D('Auction');
        $member =M('member');
        $option = I('get.option');
        $nowTime=time();
        $inPid = $auction_record->where(array('uid'=>$this->cUid))->getField('pid',true);
        $inPid = array_flip(array_flip($inPid));
        $swhere = array('pid'=>array('in',$inPid));
        if($option=='fut'){
            // 未开始
            $swhere['starttime'] = array('gt',$nowTime);
        }elseif ($option=='end') {
            // 已结束
            $swhere['endtime']=array('elt',$nowTime);
        }elseif ($option=='ing'){
            // 正在拍
            $swhere['starttime']=array('elt',$nowTime);
            $swhere['endtime']=array('egt',$nowTime);
        }
        if ($xUidarr = blackuser($this->cUid)) {
            $swhere['sellerid'] = array('not in',$xUidarr);
        }
        // 分页配置
        $count = $bidmap->where($swhere)->count();
        $pConf = page($count,20);
        $alist = $bidmap->where($swhere)->limit($pConf['first'].','.$pConf['list'])->order('endtime desc')->select();
        foreach ($alist as $ak => $av) {
            $alist[$ak]['nickname']=nickdis($member->where(array('uid'=>$av['uid']))->getField('nickname'));
            if ($av['starttime']>$nowTime) {
                $alist[$ak]['stage'] = 'fut';
            }elseif ($av['starttime']<=$nowTime&&$av['endtime']>$nowTime) {
                $alist[$ak]['stage'] = 'ing';
            }elseif ($av['endtime']<$nowTime){
                $alist[$ak]['stage'] = 'end';
            }
        }
        $this->alist=$alist;
        $this->option=$option;
        $this->page = $pConf['show'];
        $this->display('myattorbid');
    }
    // -----我的出价记录
    public function mybid_list(){
        $record = M('auction_record');
        $where = array('pid'=>I('get.pid'),'uid'=>$this->cUid);
        $count = $record->where($where)->count();
        $pConf = page($count,10);
        $list = $record->where($where)->limit($pConf['first'].','.$pConf['list'])->select();
        $this->list=$list;
        $this->page = $pConf['show'];
        $this->display();
    }
    // 我的拍到的
    public function mysucc(){
        $Dorder = D('GoodsOrder');
        $where = array('uid'=>$this->cUid);
        // 接收查看是否退货
        if (I('get.rstatus')==1) {$where['rstatus']=1;}else{$where['rstatus']=0;}
        // 接收查看订单状态
        if(I('get.st')!=''){$where['status']=I('get.st');}
        
        $redata = $Dorder->listOrder($where);
        $this->alist=$redata['list'];
        $this->page = $redata['page'];
        $this->whopage=array('name'=>'我拍到的','action'=>'mysucc','seller'=>0,'type'=>'buy');
        $this->rstatus=$where['rstatus'];
        $this->st = I('get.st');
        $this->display('order');
    }
    // 订单详情
    public function order_details(){
        $order_no = I('get.order_no');
        $Dorder = D('GoodsOrder');
        $where = array('order_no'=>$order_no);
        $alist['oinfo'] = $Dorder->findOrder($where);
        // 该订单属于卖家或买家【
        if ($alist['oinfo']['sellerid']==$this->cUid || $alist['oinfo']['uid']==$this->cUid) {
            if($alist['oinfo']['sellerid']==$this->cUid){
                $whopage=array('name'=>'订单详情','action'=>'order_details','seller'=>1,'type'=>'sel');
            }else{
                $whopage=array('name'=>'订单详情','action'=>'order_details','seller'=>0,'type'=>'buy');
            }
        }else{
            $this->error('页面不存在！');
        }

        $this->whopage=$whopage;
        // 该订单属于卖家或买家】
        $this->alist=$alist;
        $this->display();
    }
    // 快递查询
    public function logistics(){
        $order_no = I('get.order_no');
        $Dorder = D('GoodsOrder');
        $where = array('order_no'=>$order_no);
        $oinfo = $Dorder->findOrder($where);
        // 该订单属于卖家或买家【
        if ($oinfo['sellerid']==$this->cUid || $oinfo['uid']==$this->cUid) {
            if($alist['oinfo']['sellerid']==$this->cUid){
                $whopage=array('name'=>'订单详情','action'=>'order_details','seller'=>1,'type'=>'sel');
            }else{
                $whopage=array('name'=>'订单详情','action'=>'order_details','seller'=>0,'type'=>'buy');
            }
        }else{
            $this->error('页面不存在！');
        }
        $this->whopage=$whopage;
        // 该订单属于卖家或买家】
        $this->oinfo=$oinfo;
        $this->display();
    }
    
    // 买家确认收货
    public function receipt(){
        if (IS_POST) {
            $data = I('post.data');
            $paypwd = M('Member')->where(array('uid'=>$this->cUid))->getField('paypwd');
            if (empty($paypwd)) {
                echojson(array('status' => 0, 'info' => '请设置支付密码后进行支付！','url'=>U('Member/safety',array('option'=>'setpay'))));
                exit;
            }else{
                if ($paypwd!=encryptPwd($data['paypwd'])) {
                    echojson(array('status' => 0, 'info' => '支付密码错误！'));
                    exit;
                }else{
                    $goods_order = M('goods_order');
                    if($oinfo = $goods_order->where(array('order_no'=>$data['order_no'],'uid'=>$this->cUid,'status'=>2))->find()){
                        // 买家默认好评过期时间
                        if(C('Order.losetime4')==0||C('Order.losetime4')==''){
                            $deftime4 = 0;
                        }else{
                            $losetime4=C('Order.losetime4');
                            $deftime4 = time()+(60*60*24*$losetime4);
                        }
                        $order_data = array('status'=>3,'time3'=>time(),'deftime4'=>$deftime4);
                        // 设置已收货、和评价时间
                        if($goods_order->where(array('order_no'=>$data['order_no']))->save($order_data)){
                            // 账户收入增加并给卖家发送提醒
                            income_send_sell($data['order_no']);

                            // 订单状态提醒【
                            sendOrderRemind($data['order_no']);
                            // 订单状态提醒【
                            echojson(array('status' => 1, 'info' => '已确认收货','url'=>U('Member/mysucc',array('st'=>3))));
                        }else{
                            echojson(array('status' => 0, 'info' => '操作失败请重试','url'=>U('Member/receipt',array('order_no'=>$data['order_no']))));
                        }
                    }else{
                        echojson(array('status' => 0, 'info' => '操作失败请重试，请刷新页面重试','url'=>U('Member/receipt',array('order_no'=>$data['order_no']))));
                    }
                }
            }
        }else{
            $order_no = I('get.order_no');
            $Dorder = D('GoodsOrder');
            $where = array('order_no'=>$order_no,'uid'=>$this->cUid,'status'=>2);
            $alist['oinfo'] = $Dorder->findOrder($where);
            // 该订单属于卖家或买家【
            if (!$alist['oinfo']) {
                $this->error('页面不存在！');
            }
            $this->whopage=array('name'=>'订单详情','action'=>'order_details','seller'=>0,'type'=>'buy');
            // 该订单属于卖家或买家】
            $this->alist=$alist;
            $this->display('order_details');
        }
    }

    // 账号管理

    public function set_account(){
        $this->display();
    }
    // 绑定web版账号
    public function bound_web(){
        $member = M('Member');
        $uid =$this->cUid; 
        if(IS_POST){
            // 邮箱验证码提交表单
            if(I('post.type')=='email'){
                if(S(I('post.email'))&&S(I('post.email'))==I('post.email_verify')){
                    // 获取要绑定的账户id
                    $newacc = $member->where(array('email'=>I('post.email')))->field('uid,pwd')->find();
                }else{
                    echojson(array('status' => 0, 'info' => "验证码错误，请检查"));
                }
            }
            // 手机验证码提交表单
            if(I('post.type')=='mobile'){
                if(S(I('post.mobile'))&&S(I('post.mobile'))==I('post.mobile_verify')){
                    // 获取要绑定的账户id
                    $newacc = $member->where(array('mobile'=>I('post.mobile')))->field('uid,pwd')->find();
                }else{
                    echojson(array('status' => 0, 'info' => "验证码错误，请检查"));
                }
            }
            $weixin = M('member_weixin');
            // 获取当前微信openid
            $openid = $weixin->where(array('uid'=>$uid))->getField('openid');
            // 绑定web版账号
            if($weixin->where(array('openid'=>$openid))->setField('uid',$newacc['uid'])){
                // 发送cookie
                $ckdata = array('uid'=>$newacc['uid'],'timeout'=>C('TOKEN.member_timeout')+time());
                $this->systemSetCookie($ckdata);
                echojson(array('status' => 1, 'info' => "绑定成功！",'url'=>U('Member/index')));
            }else{
               echojson(array('status' => 0, 'info' => "绑定失败，请与管理员联系")); 
            }

        }else{
            $info = $member->where(array('uid'=>$uid))->find();
            if(I('get.type')!=''){
                $type=I('get.type');
            }else{
                $type = 'mobile';
            }
            $this->info=$info;
            $this->type=$type;
            $this->display();
        }
    }
    // 快递查询
    public function showExpress(){
        if(I('get.type')=='deliver'){
            $odinfo = M('goods_order')->where(array('order_no'=>I('get.order_no')))->find();
        }elseif(I('get.type')=='return'){
            $odinfo = M('goods_order_return')->where(array('order_no'=>I('get.order_no')))->find();
        }else{
            $this->error('页面不存在！');
        }
        if($odinfo){
            if($odinfo['express']!='*'){
                $info=getExpressHtml($odinfo['express'],$odinfo['express_no']);
            }else{
                $info=array('status'=>2,'html'=>'<div class="buneng">快递不支持物流跟踪，请联系卖家获取物流动态！</div>');
            }
            $this->info=$info;
        }else{
            $this->error('订单不存在！');
        }
        $this->display('showExpress');
    }
    /**
     +----------------------------------------------------------
     * 卖家操作
     +----------------------------------------------------------
     */
// 商品评价
    public function evaluate(){
        $evaluate = M('goods_evaluate');
        if (IS_POST) {
            $data = I('post.data');
            if (I('post.pic')) {
                $data['pictures'] = implode('|', I('post.pic'));
            }
            $where = array('order_no'=>$data['order_no']);
            if($evaluate->where($where)->find()){
                echojson(array('status' => 0, 'info' => '订单已经评价过了！','url'=>__SELF__));
                exit;
            }
            if(!$data['conform_evaluate']){echojson(array('status' => 0, 'info' => '请填写【对宝贝的感受】')); exit;}
            if($data['conform']==''){echojson(array('status' => 0, 'info' => '请评价商品')); exit;}
            $goods_order = M('goods_order');
            $oinfo = $goods_order->where($where)->find();

            if($oinfo&&$oinfo['uid']==$this->cUid&&$oinfo['status']==3){
                $data['uid']=$oinfo['uid'];
                $data['pid']=$oinfo['gid'];
                $data['sellerid']=$oinfo['sellerid'];
                $data['time']=time();
                if($evaluate->add($data)){
                    // 卖家默认好评过期时间
                    if(C('Order.losetime10')==0||C('Order.losetime10')==''){
                        $deftime10 = 0;
                    }else{
                        $losetime10=C('Order.losetime10');
                        $deftime10 = time()+(60*60*24*$losetime10);
                    }
                    // 设置已评价和卖家默认评价时间
                    if($goods_order->where($where)->setField(array('status'=>'4','time4'=>time(),'deftime10'=>$deftime10))){
                        // 订单状态提醒【
                            sendOrderRemind($data['order_no']);
                        // 订单状态提醒【
                    }
                    // 为用户等级加分数
                    M('member')->where(array('uid'=>$data['sellerid']))->setInc('score',$data['conform']);
                    echojson(array('status' => 1, 'info' => '评价成功','url'=>U('Member/mysucc',array('st'=>4))));
                }else{
                    echojson(array('status' => 0, 'info' => '评价失败','url'=>__SELF__));
                }
            }else{
                echojson(array('status' => 0, 'info' => '不存在的订单或该订单不接收您的评价','url'=>U('Member/mysucc',array('st'=>3))));
            }
            
        }else{
            $order_no = I('get.order_no');
            $Dorder = D('GoodsOrder');
            $where = array('order_no'=>$order_no,'uid'=>$this->cUid,'status'=>3);
            $oinfo = $Dorder->findOrder($where);
            if ($oinfo) {
                $this->oinfo=$oinfo;
                $this->display();
            }else{
                $this->error('页面不存在或已过期！',U('Member/mysucc'));
            }
        }
    }

    // 删除评价图片
    public function del_evaluate_pic(){
        if (IS_POST) {
            @unlink(C('UPLOADS_PICPATH').I('post.savepath').I('post.savename'));
            @unlink(C('UPLOADS_PICPATH').I('post.savepath').'thumb'.I('post.savename'));
            echojson(array('status' => 1, 'info' => '删除成功'));
        }
    }
    // 查看评价
    public function evaluate_list(){
        $where = array('uid'=>$this->cUid);
        if (I('get.order_no')) {
            $where['order_no']=I('get.order_no');
        }
        $rdata = D('GoodsEvaluate')->listEvaluate($where,'time desc',C('PAGE_SIZE'));
        // 分配分页到模板
        $this->page = $rdata['page']; 
        // 分配正在拍卖拍品到模板
        $this->list = $rdata['list'];
        $this->display();
    }
    public function settixing(){
        if (IS_POST) {
            $data = I('post.');
            $dt = array();
            if($data){
                foreach ($data as $dk => $dv) {
                    if($dv!=0){
                        $dt[]=$dk;
                    }
                }
                if($dt){
                    $str = implode($dt, ',');
                }
                
            }else{
                $str = '';
            }
            if(M('Member')->save(array('uid'=>$this->cUid,'alerttype'=>$str))!==false){
                echojson(array('status' => 1, 'info' => '设置成功！','url'=>U('Member/index')));
            }else{
                echojson(array('status' => 0, 'info' => '设置失败！','url'=>__SELF__));
            }
        } else {
            $myinfo = M('Member')->where(array('uid'=>$this->cUid))->field('email,verify_email,mobile,verify_mobile')->find();
            // 是否关联有微信【
            $myinfo['verify_weixin'] = 0;
            if(C('Weixin.appid')&&C('Weixin.appsecret')){
                if(M('member_weixin')->where(array('uid'=>$this->cUid))->find()){
                    $myinfo['verify_weixin'] = 1;
                }
            }
            // 提醒方式设置【
            $alerttype = M('member')->where(array('uid'=>$this->cUid))->getField('alerttype');
            if(!empty($alerttype)){$this->alerttype = explode(',', $alerttype);}
            // 提醒方式设置】
            if(I('get.pid')){$this->pid = I('get.pid');}else{$this->pid = 0;}
            // 是否关联有微信】
            $this->myinfo=$myinfo;
            C('TOKEN_ON',false);
            $this->display();
        }
    }
    /**
      +----------------------------------------------------------
     * 黑名单相关
      +----------------------------------------------------------
     */
    // 买家黑名单
    public function blackbuy(){
        $data = D('Member')->black($this->cUid,'buy');
        $this->selbuy = 'buy';
        $this->page = $data['page'];
        $this->list = $data['list'];
        $this->display('black');
    }
    /**
      +----------------------------------------------------------
     * 分享相关
      +----------------------------------------------------------
     */
    // 分享推广
    public function generalize(){
        $url = U('Index/index',array('m'=>'s_'.$this->cUid),'html',true);
        $this->avatar = getUserpic($this->cUid,1);
        $mbcfg = C('Member');
        $this->generalize = $mbcfg['reward']['generalize'];
        $this->url=$url;
        $this->display();
    }
    // 分享用户列表
    public function generalize_list(){
        $member=M('member');
        $where = array('sourceuid'=>$this->cUid);
        $count = $member->where($where)->count();
        $pConf = page($count,C('PAGE_SIZE'));
        $list = $member->where($where)->limit($pConf['first'].','.$pConf['list'])->select();
        $this->page = $pConf['show'];
        $this->list = $list;
        $this->display('generalize');
    }
    /**
      +----------------------------------------------------------
     * 退货相关
      +----------------------------------------------------------
     */
// 申请退货
    public function order_return(){
        $Dorder = D('GoodsOrder');
        $oinfo = $Dorder->findOrder(array('order_no'=>I('get.order_no'),'uid'=>$this->cUid));
        // 退款金额为商品价格加手续费
        $oinfo['rmoney'] = $oinfo['price'];
        $oinfo['rmntxt'] = C('Auction.broker_buy_name').'：￥'.$oinfo['broker_buy'].' 会在退款完成后自动退回到您的账户内！';
        if (IS_POST) {
            $data = I('post.data');
            $data['money'] = $oinfo['price'];

            if(I('post.pic')){
                $picstr =  I('post.pic');
                $data['evidence'] = implode('|', $picstr);
            }
            $data['time5'] = time();
            // 卖家默认拒绝时间【
            if(C('Order.losetime6')==0||C('Order.losetime6')==''){
                $deftime6 = 0;
            }else{
                $losetime6=C('Order.losetime6');
                $deftime6 = time()+(60*60*24*$losetime6);
            }
            $data['deftime6'] = $deftime6;
            // 卖家默认拒绝时间】

            if(I('get.type')=='repet'){
                $st = M('goods_order_return')->save($data);
            }else{
                $st = M('goods_order_return')->add($data);
            }
            if($st){
                M('goods_order')->where(array('order_no'=>$data['order_no']))->setField(array('status'=>5,'rstatus'=>1));
                echojson(array('status' => 1, 'info' => '已提交退货！等待卖家确认退货','url'=>U('Member/mysucc',array('rstatus'=>1,'st'=>5))));
            }else{
                echojson(array('status' => 0, 'info' => '提交退货失败','url'=>__SELF__));
            }
        }else{
            if($oinfo){
                $this->oinfo = $oinfo;
                // 获取退货原因
                $this->cause = explode(',', C('order.cause'));
                $this->display();
            }else{
                $this->error('不存在的订单！');
            }
        }
    }
// 买家发货处理
    public function delivertosel(){
        $order_return = M('goods_order_return');
        $goods_order = M('goods_order');
        if (IS_POST) {
            $info = I('post.info');
            $return_data =array('express'=>$info['express'],'express_no'=>$info['express_no']);
            // 卖家收获过期时间【
            if(C('Order.losetime9')==0||C('Order.losetime9')==''){
                $deftime9 = 0;
            }else{
                $losetime9=C('Order.losetime9');
                $deftime9 = time()+(60*60*24*$losetime9);
            }
            // 卖家收获过期时间】
            $return_data['deftime9'] = $deftime9;
            $return_data['time8'] = time();
            $remark = $goods_order->where(array('order_no'=>$info['order_no']))->getField('remark');
            $order_data['status'] = 8;
            $order_data['remark'] = order_remark($remark,8,$info['remark']);
            if ($order_return->where(array('order_no'=>$info['order_no']))->save($return_data)&&$goods_order->where(array('order_no'=>$info['order_no']))->save($order_data)) {
                // 订单状态提醒【
                sendOrderRemind($info['order_no']);
                // 订单状态提醒【
                echojson(array('status' => 1, 'info' => '已提交发货'.$rs,'url' => U('Member/mysucc',array('rstatus'=>1,'st'=>8)))); 
            }else{
                echojson(array('status' => 0, 'info' => '提交发货失败，请检查'));
            }
        } else {
            // 卖家同意退货的
            $where= array('order_no'=>I('get.order_no'),'status'=>7,'rstatus'=>1);
            $order = $goods_order->where($where)->find();
            if ($order) {
                $info = $order_return->where(array('order_no'=>I('get.order_no')))->find();
                if (!$info) {
                    $this->error('已提交发货无需重复提交！');
                }
                $this->address = unserialize($info['address']);
                // 快递选择
                $express = C('Express');
                $this->express_list=$express['comarr'];
                $this->info=$info;
                $this->whopage=array('name'=>'给卖家发货','action'=>'delivertosel','seller'=>0,'type'=>'buy');
                $this->display('Seller:deliver');
            }else{
                $this->error('不存在的退货待发货订单或已发货！',U('Member/mysucc',array('rstatus'=>1,'st'=>7)));
            }            
        }
    }
    // 意见反馈
    public function consultation(){
        if (IS_POST) {
            $data = I('post.data');
            $data['uid']=$this->cUid;
            $data['time']=time();
            if(M('consultation')->add($data)){
                echojson(array('status' => 1, 'info' => '感谢您对我们的支持，我们会尽快作出反馈！','url'=>U('Member/consultation',array('pag'=>'list'))));
            }else{
                echojson(array('status' => 0, 'info' => '提交失败，请重新提交！','url'=>U('Member/consultation')));
            }
        }else{
            $pag = I('get.pag');
            if ($pag=='list') {
                $list = M('consultation')->where(array('uid'=>$this->cUid))->order('time desc')->select();
                $this->pag='list';
                $this->list = $list;
            }
            $this->pag = I('get.pag');
            $this->display();
        }
    }
    public function consultation_del(){
        if (IS_POST) {
            $consultation = M('consultation');
            if($consultation->where(array('uid'=>$this->cUid,'id'=>I('post.id')))->delete()){
                echojson(array('status' => 1, 'info' => '删除成功'));
            }else{
                echojson(array('status' => 0, 'info' => '删除失败，可能不存在的反馈记录！','url'=>U('Member/consultation',array('pag'=>'list'))));
            }
        }else{
            E('页面不存在！');
        }
    }

    








}