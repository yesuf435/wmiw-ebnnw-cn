<?php
namespace Home\Controller;
use Think\Controller;
use Com\WechatAuth;
class LoginController extends Controller {
    public $loginMarked;
    // get方式进入该该控制器的方法如已登录将跳转至用户中心页面
    public function _initialize() {
        $systemConfig = include APP_PATH . '/Common/Conf/systemConfig.php';
        $this->site=$systemConfig;
        $this->loginMarked = md5(C("TOKEN.member_marked"));
    }
    public function index(){
        if(IS_POST){
            $member = M('member');
            $mbcof = C('Member');
            $openid = I('post.openid');
            $access_token = I('post.access_token');
            if (I('post.suname')) {
                $referer = S(I('post.suname'));
            }else{
                $referer = U('Member/index','','html',true);
            }
            // 验证码检测
            if (check_verify(I('post.verify_code'))!=1) {
                if (!is_weixin()) {
                    echojson(array('status' => 0, 'info' => '验证码错了,重新输入吧'));
                    exit;
                }
            }

            // 密码错误达到5次
            $pas_err = S(I('post.account'))?S(I('post.account')):0;
            if($pas_err>=100){
                echojson(array('status' => 0, 'info' => '由于您今天连续输入100次错误密码！请24小时后在进行登录操作。','url'=>__SELF__));
                exit;
            }
            if(preg_match('/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/', I('post.account'))&&$mbcof['register']['email']=='on'){
                $where=array('email'=>I('post.account'),array('verify_email'=>1));
                $msg = '邮箱';
            }elseif(preg_match('/^1\d{10}$/', I('post.account'))&&$mbcof['register']['mobile']=='on'){
                $where=array('mobile'=>I('post.account'),array('verify_mobile'=>1));
                $msg = '手机号';
            }else{
                $where=array('account'=>I('post.account'));
                $msg = '账号';
            }
            $info = $member->field(array('uid','pwd','status','alerttype'))->where($where)->find();
            if(empty($info)){
                echojson(array('status' => 0, 'info' => I('post.account').$msg.'不存在'));
                exit;
            }
            if (I('post.pwd')!=''&&($info['pwd']!=encryptPwd(I('post.pwd')))) {
                // 密码错误次数加1
                S(I('post.account'),$pas_err+1,86400);
                if(100-S(I('post.account'))==0){
                    echojson(array('status' => 0, 'info' => '由于您今天连续输入100次错误的密码！请24小时后在进行登录操作。','url'=>__SELF__));
                }else{
                    echojson(array('status' => 0, 'info' => '密码错误,请检查！错误'.(100-S(I('post.account'))).'次后，请24小时后在进行登录操作。','url'=>__SELF__));
                }
                exit;
            }
            if ($info['status']==0) {
                echojson(array('status' => 0, 'info' => '账号被禁用了，请和管理员联系'));
                exit;
            }
            // 更新用户数据
            $data = array(
                'uid'=>$info['uid'],
                'login_time'=>time(),
                'login_ip'=>get_client_ip()
                );
            if ($openid!='') {
                $data['weiauto'] = I('post.weiauto');
            }
            if($member->save($data)){
                // 如果勾选绑定微信且微信openid存在
                if (I('post.bound')==1 && $openid!='') {
                    $member_weixin = M('member_weixin');
                    // 获取本地微信用户信息
                    $wuser_info = $member_weixin->where(array('openid'=>$openid))->find();
                    // 如果存在实行保存操作
                    if ($wuser_info) {
                        // 微信数据uid和当前登陆uid不同
                        if ($wuser_info['uid']!=$data['uid']) {
                            // 如果该账号绑定过其他微信号进行解绑操作
                            $member_weixin->where(array('uid'=>$data['uid']))->setField('uid',0);
                            // 绑定当前微信用户
                            $wuser_info['uid'] = $data['uid'];
                        }
                        $wuser['weitime'] =  time()+172800;
                        if(!$member_weixin->where(array('openid'=>$openid))->save($wuser)){
                            echojson(array('status' => 0, 'info' => '微信数据更新失败，请重试！','url' => $referer));
                            exit();
                        }else{
                            if (I('post.alertadd')==1) {
                                // 更新提醒字段
                                upalerttype($member,$info['uid'],'weixin');
                            }
                        }
                    // 不存在实行添加操作
                    }else{
                        // 获取用户信息【
                        $accessToken = getJson(U('Home/Common/setAccessToken@'.$_SERVER['SERVER_NAME']));
                        $WechatAuth = new WechatAuth(C('Weixin.appid'),C('Weixin.appsecret'),$accessToken);
                        $userInfo = $WechatAuth->userInfo($openid);
                        // 如果已关注
                        if ($userInfo['subscribe']) {
                            // 整合信息写入数据库
                            $wuser['subscribe'] = $userInfo['subscribe'];
                            $wuser['subscribe_time']=$userInfo['subscribe_time'];
                            $wuser['groupid']=$userInfo['groupid'];
                            $wuser['remark']=$userInfo['remark'];
                        }else{
                        // 如果未关注
                            $userInfo = getJson("https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN");
                        }
                    // 获取用户信息】
                        if (!$userInfo) {
                            echojson(array('status' => 0, 'info' => '获取用户微信信息失败，请重试！','url' => $referer));
                            exit();
                        }
                        $wuser['openid'] = $openid;
                        $wuser['nickname'] = $userInfo['nickname'];
                        $wuser['sex'] = $userInfo['sex'];
                        $wuser['language'] = $userInfo['language'] ;
                        $wuser['city'] = $userInfo['city'] ;
                        $wuser['province'] = $userInfo['province'] ;
                        $wuser['country'] = $userInfo['country'] ;
                        $wuser['headimgurl'] = $userInfo['headimgurl'] ;
                        $wuser['weitime'] =  time()+172800;
                        // $wuser['tagid_list'] = $userInfo['tagid_list'];
                        // 只有在用户将公众号绑定到微信开放平台帐号后，才会出现该字段。
                        if($userInfo['unionid']){
                            $wuser['unionid']=$userInfo['unionid'];
                        }
                        $wuser['uid']=$info['uid'];
                        // 添加并绑定微信
                        if(M('member_weixin')->add($wuser)){
                            if (I('post.alertadd')==1) {
                                // 更新提醒字段
                                upalerttype($member,$info['uid'],'weixin');
                            }
                        }else{
                            echojson(array('status' => 0, 'info' => '微信数据保存失败，请重试！','url' => $referer));
                            exit();
                        }
                    }
                }
                // 发送cookie
                $ckdata = array('uid'=>$info['uid']);
                $this->systemSetCookie($ckdata);
                S(I('post.suname'),null);
                // 返回注册成功信息
                echojson(array('status' => 1, 'info' => '登录成功','url' => $referer));
            }else{
                echojson(array('status' => 0, 'info' => '登录失败，请重试！','url' => $referer));
            }
        }else{
            // 系统维护
            if (C('SITE_INFO.maintenance')==1) {
                $this->redirect('Login/maintenance', null, 0, '页面跳转中...');
            }
            // 判断登陆状态
            if ($this->checkLogin()) {$this->referer(I('get.suname'));}
            $gol = I('get.gol');
            $mbcof=C('Member');
            foreach ($mbcof['register'] as $rek => $rev) {
                if($rek=='account'){$ltr='账号  ';}
                if ($rek=='email') {$ltr.='邮箱  ';}
                if ($rek=='mobile') {$ltr.='手机号';}
            }
            $this->ltr=$ltr;
            if ($openid = I('get.openid')) {
                $uid = M('member_weixin')->where(array('openid'=>I('get.openid')))->getField('uid');
                $this->account = M('member')->where(array('uid'=>$uid))->getField('account');
                $this->openid = $openid;
            }
            
            $this->bound = I('get.bound');
            $this->diversity = I('get.diversity');
            $this->access_token = I('get.access_token');
            $this->gol = $gol;
            if (I('get.suname')) {
                $this->suname = $suname;
            }
            // 不显示弹窗二维码
            $this->showcodemap = 0;
        	$this->display();
        }
    }
    public function weioauth(){
        $openid = I('get.openid');
        $access_token = I('get.access_token');
        if (I('get.suname')) {
            $referer = S(I('get.suname'));
            S(I('get.suname'),null);
        }else{
            $referer = U('Member/index','','',true);
        }
        // 获取微信用户信息【 
        $member_weixin = M('member_weixin');
        $member = M('member');
        // 查询本地存在用户
        $wuser_info = $member_weixin->where(array('openid'=>$openid))->find();
        if($wuser_info){
            $info = $member->where(array('uid'=>$wuser_info['uid']))->field('uid,pwd,weiauto')->find();
            // 更新用户数据
            $member->where(array('uid'=>$wuser_info['uid']))->setField(array('login_time'=>time(),'login_ip'=>get_client_ip()));
            // 更新微信数据
            $member_weixin->where(array('openid'=>$openid))->setField('weitime',time()+172800);
            // 发送cookie
            $ckdata = array('uid'=>$info['uid']);
            $this->systemSetCookie($ckdata);
            Header("Location: ".$referer);
            exit();
        }else{
            // 添加该用户到本站
            if (I('get.create')=='auto') {
                // 获取用户信息【
                    $accessToken = getJson(U('Home/Common/setAccessToken@'.$_SERVER['SERVER_NAME']));
					$WechatAuth = new WechatAuth(C('Weixin.appid'),C('Weixin.appsecret'),$accessToken);
                    $userInfo = $WechatAuth->userInfo($openid);
                    // 如果已关注
                    if ($userInfo['subscribe']) {
                        // 整合信息写入数据库
                        $wuser['subscribe'] = $userInfo['subscribe'];
                        $wuser['subscribe_time']=$userInfo['subscribe_time'];
                        $wuser['groupid']=$userInfo['groupid'];
                        $wuser['remark']=$userInfo['remark'];
                    }else{
                    // 如果未关注
                        $userInfo = getJson("https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN");
                    }
                // 获取用户信息】
                if (!$userInfo) {
                    echojson(array('status' => 0, 'info' => '获取用户微信信息失败，请重试！','url' => $referer));
                    exit();
                }
                $wuser['openid'] = $openid;
                $wuser['nickname'] = $userInfo['nickname'];
                $wuser['sex'] = $userInfo['sex'];
                $wuser['language'] = $userInfo['language'] ;
                $wuser['city'] = $userInfo['city'] ;
                $wuser['province'] = $userInfo['province'] ;
                $wuser['country'] = $userInfo['country'] ;
                $wuser['headimgurl'] = $userInfo['headimgurl'] ;
                $wuser['weitime'] =  time()+172800;
                // $wuser['tagid_list'] = $userInfo['tagid_list'];
                // 只有在用户将公众号绑定到微信开放平台帐号后，才会出现该字段。
                if($userInfo['unionid']){
                    $wuser['unionid']=$userInfo['unionid'];
                }
                $data = array(
                    'nickname'=>$wuser['nickname'],
                    'sex'=>$wuser['sex'],
                    'prov'=>$wuser['province'],
                    'city'=>$wuser['city'],
                    'reg_date'=>time(),
                    'reg_ip' => get_client_ip(),
                    'login_time'=>time(),
                    'login_ip'=>get_client_ip(),
                    'avatar'=>downWeihead($userInfo['headimgurl']),
                    'weiauto'=>1
                    );
                // 存储推广人uid
                $ckdata = cookie($this->loginMarked);
                if ($ckdata['tid']) {
                    $data['sourceuid'] =$ckdata['tid'];
                }
                if($uid = $member->add($data)){
                    // 设置不重复的账号【
                    $i = '';
                    do {
                        $nb = $uid.$i;
                        if(strlen($nb)<4){
                            $nb = sprintf("%04d", $nb);
                        }
                        $account = 'wx'.$nb;
                        if(!$member->where(array('account'=>$account))->find()){
                            $data['account'] = $account;
                            $member->where(array('uid'=>$uid))->setField('account',$account);
                            $i = 1;
                        }else{
                            $i = 0;
                        }
                    } while ( $i = 0);
                    // 设置不重复的账号】
                    $wuser['uid'] = $uid;
                    // 设置微信登陆时间
                    if($member_weixin->add($wuser)){
                        // 设置微信提醒
                        upalerttype($member,$uid,'weixin');
                        // 发送cookie
                        $ckdata = array('uid'=>$uid);
                        $this->systemSetCookie($ckdata);
                        // 奖励推广用户信誉额度
                        if (isset($data['sourceuid'])) {
                            $this->reward($data['account'],$data['sourceuid']);
                        }
                        Header("Location: ".$referer);
                        exit();
                    }else{
                        $this->error('微信登陆失败，请返回重试！');
                    }
                }else{
                    $this->error('微信注册失败，请返回重试！');
                }
            }else{
                $this->error('没有读取到本地微信数据！');
            }
        }
        
    }
    // public function creatpwd(){
    //     pre(md5('HVbUPg' . md5('admin')));
    // }

    // 用户注册
    public function register(){
    	if(IS_POST){
            // 微信openid
            $openid = I('post.openid');
            $access_token = I('post.access_token');
            $valid = valid_pass(I('post.pwd'));

            if (!$valid['status']) {echojson($valid); exit();}
            if (I('post.pwd')!=I('post.pwded')) {
                echojson(array('status' => 0, 'info' => '两次密码不一致，再输一遍吧'));
                exit;
            }
            if (!check_verify(I('post.verify_code'))&&!is_weixin()) {
                echojson(array('status' => 0, 'info' => '验证码错了,重新输入吧'));
                exit;
            }
            if (!I('post.isAgree')) {
                echojson(array('status' => 0, 'info' => '请阅读并同意网站协议'));
                exit;
            }
            if (I('post.suname')) {
                $referer = S(I('post.suname'));
            }else{
                $referer = U('Member/index','','html',true);
            }
            // 整合数据
            $data = array(
                'truename' => I('post.truename'),
                'pwd' => encryptPwd(I('post.pwd')),
                'reg_date'=>time(),
                'reg_ip' => get_client_ip(),
                'login_time'=>time(),
                'login_ip'=>get_client_ip()
                );
            // 微信注册
            if ($openid == '') {
                $data['nickname'] = I('post.nickname');
            }
            $member = M('member');
            // 写入前验证一次
            switch (I('post.registerType')) {
                case 'account':
                    if (!preg_match("/^[a-zA-Z]{1}([a-zA-Z0-9]|[._]){3,19}$/",I('post.account'))) {
                        echojson(array('status' => 0, 'info' => '账号格式不正确，必须字母开头，字母、数字、下划线组合的3至19个字符'));
                        exit;
                    }
                    if($member->where(array('account'=>I('post.account')))->count()!=0){
                        echojson(array('status' => 0, 'info' => I('post.account').'账号已存在，换一个吧'));
                        exit;
                    }
                    $data['account']=I('post.account');
                    // 微信注册
                    if ($openid == '') {
                        $data['mobile'] = I('post.mobile');
                    }
                    break;
                case 'email':
                    if(!S(I('post.email'))||S(I('post.email'))!=I('post.email_verify')){
                        echojson(array('status' => 0, 'info' => '邮箱验证码错误，请确认'));
                        exit;
                    }
                    $data['account']=substr('on'.'_'.strstr(I('post.email'), '@', TRUE),0,16);
                    $data['email'] = I('post.email');
                    $data['verify_email'] = 1;
                    // 微信注册
                    if ($openid == '') {
                        $data['mobile'] = I('post.mobile');
                        $data['alerttype'] = 'email';
                    }
                    break;
                case 'mobile':
                    if(!S(I('post.mobile'))||S(I('post.mobile'))!=I('post.mobile_verify')){
                        echojson(array('status' => 0, 'info' => '短信验证码错误，请确认'));
                        exit;
                    }
                    $data['account']=substr('on'.'_'.I('post.mobile'),0,16);
                    
                    $data['mobile'] = I('post.mobile');
                    $data['verify_mobile'] = 1;
                    // 微信注册
                    if ($openid == '') {
                        $data['email'] = I('post.email');
                        $data['alerttype'] = 'mobile';
                    }
                    break;
                default:
                    echojson(array('status' => 0, 'info' => '不存在的注册方式'));
                    break;
            }
            // 如果勾选绑定微信且微信openid存在
            if (I('post.bound')==1 && $openid!='') {
                $member_weixin = M('member_weixin');
                // 获取本地微信用户信息
                $wuser_info = $member_weixin->where(array('openid'=>$openid))->find();
                //不存在实行添加操作 
                if (!$wuser_info) {
                    // 获取用户信息【
                        $accessToken = getJson(U('Home/Common/setAccessToken@'.$_SERVER['SERVER_NAME']));
						$WechatAuth = new WechatAuth(C('Weixin.appid'),C('Weixin.appsecret'),$accessToken);
                        $userInfo = $WechatAuth->userInfo($openid);
                        // 如果已关注
                        if ($userInfo['subscribe']) {
                            // 整合信息写入数据库
                            $wuser['subscribe'] = $userInfo['subscribe'];
                            $wuser['subscribe_time']=$userInfo['subscribe_time'];
                            $wuser['groupid']=$userInfo['groupid'];
                            $wuser['remark']=$userInfo['remark'];
                        }else{
                        // 如果未关注
                            $userInfo = getJson("https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN");
                        }
                    // 获取用户信息】
                    if (!$userInfo) {
                        echojson(array('status' => 0, 'info' => '获取用户微信信息失败，请重试！','url' => $referer));
                        exit();
                    }
                    $wuser['openid'] = $userInfo['openid'];
                    $wuser['nickname'] = $userInfo['nickname'];
                    $wuser['sex'] = $userInfo['sex'];
                    $wuser['language'] = $userInfo['language'] ;
                    $wuser['city'] = $userInfo['city'] ;
                    $wuser['province'] = $userInfo['province'] ;
                    $wuser['country'] = $userInfo['country'] ;
                    $wuser['headimgurl'] = $userInfo['headimgurl'] ;
                    // $wuser['tagid_list'] = $userInfo['tagid_list'];
                    // 只有在用户将公众号绑定到微信开放平台帐号后，才会出现该字段。
                    if($userInfo['unionid']){
                        $wuser['unionid']=$userInfo['unionid'];
                    }
                    $act = 'add';
                    $city = $wuser['city'];
                    $data['nickname'] = $wuser['nickname'];
                    $data['sex'] = $wuser['sex'];
                }else{
                    $act = 'save';
                    $city = $wuser_info['city'];
                    $data['nickname'] = $wuser_info['nickname'];
                    $data['sex'] = $wuser_info['sex'];
                }
                // member表数据整合
                $data['prov'] = $wuser['province'];
                $data['city'] = $wuser['city'];
                $data['avatar'] = downWeihead($userInfo['headimgurl']);
                $data['weiauto'] = I('post.weiauto');
                
            }
            // 存储推广人uid
            $ckdata = cookie($this->loginMarked);
            if ($ckdata['tid']) {
                $data['sourceuid'] =$ckdata['tid'];
            }
            if($uid = $member->add($data)){
                if (I('post.bound')==1 && $openid!='') {
                    $wuser['uid'] = $uid;
                    $wuser['weitime'] =  time()+172800;
                    if ($act == 'save') {
                        $suc = M('member_weixin')->where(array('openid'=>$openid))->save($wuser);
                    }
                    if ($act == 'add') {
                        $suc = M('member_weixin')->add($wuser);
                    }
                    if($suc){
                        if (I('post.alertadd')==1) {
                            // 更新提醒字段
                            upalerttype($member,$uid,'weixin');
                        }
                    }else{
                        echojson(array('status' => 0, 'info' => '微信数据添加失败，请重试！','url' => $referer));
                        exit;
                    }
                    if (I('post.alertadd')==1) {
                        // 更新提醒字段
                        upalerttype($member,$uid,'weixin');
                    }
                }
                // 推广统计
                M('feedback')->where(array('id'=>I('post.feedback')))->setInc('count');
                // 发送cookie
                $ckdata = array('uid'=>$uid);
                $this->systemSetCookie($ckdata);

                S(I('post.suname'),null);
                // 奖励推广用户信誉额度
                if (isset($data['sourceuid'])) {
                    $this->reward($data['account'],$data['sourceuid']);
                }
                // 返回注册成功信息
                echojson(array('status' => 1, 'info' => '注册成功','url' => U('Member/index')));
            }else{
                echojson(array('status' => 0, 'info' => '注册失败，请与网站管理员联系'));
            }
        }else{
            // 系统维护
            if (C('SITE_INFO.maintenance')==1) {
                $this->redirect('Login/maintenance', null, 0, '页面跳转中...');
            }
            // 判断登陆状态
            if ($this->checkLogin()) {$this->referer(I('get.suname'));}
            $gol = I('get.gol');
            // if ($gol==1) {
            //     if (!is_weixin()) {
            //         $this->error('请在微信内打开页面');
            //     }
            // }
            $mf = M('feedback');
            $mbcof=C('Member');
            $this->rtype = $mbcof['register'];
	    	$this->feedback = $mf->select();
            // 有哪些注册方式
            $ltype = array('account','email','mobile','');
            // 开启了哪些注册方式【
            foreach ($mbcof['register'] as $mck => $mcv) {
                $mkarr[]=$mck;
            }
            // 开启了哪些注册方式【
            // 设置默认注册方式
            if(in_array(I('get.registerType'), $ltype)){
                if(I('get.registerType')==''){
                    $registerType = $mkarr[0];
                }
                if(in_array(I('get.registerType'),$mkarr)){
                    $registerType = I('get.registerType');
                }
            }else{
                $this->error('页面不存在！');
            }
            $this->openid = I('get.openid');
            $this->bound = I('get.bound');
            $this->access_token = I('get.access_token');
            $this->registerType=$registerType;
            $this->gol = $gol;
            if (I('get.suname')) {
                $this->suname = $suname;
            }
            // 不显示弹窗二维码
            $this->showcodemap = 0;
	    	$this->display();
        }
    }
    public function reward($account,$sourceuid){
        $member = M('member');
        $mbcfg = C('Member');
        $generalize = $mbcfg['reward']['generalize'];
        $wr = array('uid'=>$sourceuid);
        $wallet = $member->where(array('uid'=>$sourceuid))->field('wallet_limsum,wallet_limsum_freeze')->find();
        $usable = sprintf("%.2f",$wallet['wallet_limsum']-$wallet['wallet_limsum_freeze']);
        $remark = '用户【'.$account.'】通过推广链接完成注册奖励信用额度';
        if($member->where(array('uid'=>$sourceuid))->setInc('wallet_limsum',$generalize)){
            // 信誉额度增加
            $limsum_data = array(
                'order_no'=>createNo('rew'),
                'uid'=>$sourceuid,
                'changetype'=>'reward_add',
                'time'=>time(),
                'annotation'=>$remark,
                'income'=>$generalize,
                'usable'=>sprintf("%.2f",$usable+$info['money']),
                'balance'=>sprintf("%.2f",$wallet['wallet_limsum']+$info['money'])
                );
            $usable = $usable+$generalize;
        }
        if (M('member_limsum_bill')->add($limsum_data)) {
            // 提醒通知冻结保证金【
                // 微信提醒内容
                // $wei_limsum_freeze['tpl'] = 'walletchange';
                // $wei_limsum_freeze['msg']=array(
                //     "url"=>U('Home/Member/wallet',array('option'=>'limsum'),'html',true), 
                //     "first"=>'您好，'.$remark,
                //     "remark"=>'查看账户记录>>',
                //     "keyword"=>array('信用额度账户','奖励信用额度','管理员'.$ac,$limsum_data['income'].'元',$limsum_data['usable'].'元')
                // );
                // 账户类型，操作类型、操作内容、变动额度、账户信用额度
                // 站内信提醒内容
                $web_limsum_freeze = array(
                    'title'=>'推广奖励',
                    'content'=>$remark.'【'.$limsum_data['income'].'元】，单号'.$limsum_data['order_no']
                    );
                // 短信提醒内容
                // $note_limsum_freeze = $remark.'【'.$limsum_data['income'].'元】，'.'单号'.$limsum_data['order_no'].'，您可以登陆平台查看账户记录。';
                // 邮箱提醒内容
                // $mail_limsum_freeze['title'] = $remark.'【'.$limsum_data['income'].'元】';
                // $mail_limsum_freeze['msg'] = '您好：<br/><p>'.$remark.'【'.$limsum_data['income'].'元】'.'</p><p>您可以<a target="_blank" href="'.U('Home/Member/wallet',array('option'=>'limsum'),'html',true).'">查看账户记录</a></p>';
                sendRemind($member,M('Member_weixin'),array(),array($sourceuid),$web_limsum_freeze,$wei_limsum_freeze,$note_limsum_freeze,$mail_limsum_freeze,'buy');
            // 提醒通知冻结保证金【
        }
    }
    
    // 忘记密码
    public function findpwd(){
        if(IS_POST){
            $findtype = I('post.findtype');
            $sname = I('post.'.$findtype);
            // 验证码验证，统计验证码错误次数。错误达到5次，24小时候才能进行找回
            $vererr = S($sname.'_err')?S($sname.'_err'):0;
            if($vererr>=100){
                $this->error('由于您今天连续输入100次错误的验证码！请24小时后在进行找回密码操作。');
                exit;
            }
            if(S($sname)!=I('post.'.$findtype.'_verify')){
                S($sname.'_err',$vererr+1,86400);
                if(100-S($sname.'_err')==0){
                    $this->error('由于您今天连续输入100次错误的验证码！请24小时后在进行找回密码操作。');
                }else{
                    $this->error('验证码错误,请检查！错误'.(100-S($sname.'_err')).'次后，请24小时后在进行找回密码操作。');
                }
                exit;
            }
            // 密码验证
            if(I('post.pwd')!=I('post.pwded')){$this->error('两次密码不一致，请检查');exit; }
            
            $member = M('member');
            if($findtype=='email'){
                $sname = I('post.email');
                $info = $member->where(array('email'=>$sname,'verify_email'=>1))->field('uid,pwd')->find();
            }elseif($findtype=='mobile'){
                $sname = I('post.mobile');
                $info = $member->where(array('mobile'=>$sname,'verify_mobile'=>1))->field('uid,pwd')->find();
            }
            if(!$info){
                $this->error('没有找到通过'.$sname.'绑定或验证的账号！');
                exit;
            }
            $pwd = encryptPwd(I('post.pwd'));
            if($info['pwd']!=$pwd){
                if(M('member')->where(array('uid'=>$info['uid']))->setField('pwd',$pwd)){
                    // 删除缓存
                    S($sname,null);
                    S($sname.'_err',null);
                    $this->success('修改密码成功，请登录',U('Login/index'));
                }else{
                    $this->error('修改密码失败，请与管理员联系');
                }
            }else{
                $this->error('设置的密码不能和以前的密码一样');
            }
        }else{
            if(I('get.findtype')==''){
                $findtype='email';
            }else{
                $findtype=I('get.findtype');
            }
            $this->findtype=$findtype;
            $this->display();
        }
    }
    // 邮箱注册发送验证码
    public function sendCode(){
        if(IS_POST){
            $checkadr = I('post.checkadr');
            if(I('post.checktp')=='email'){
                if(is_email(I('post.checktp'))){
                    echojson(array('status' => 0, 'info' => "邮箱格式不正确"));
                    exit;
                }
                $mwhere['email'] = $checkadr;
                $ckname='邮箱';
                $rc = randCode(5);
            }elseif(I('post.checktp')=='mobile'){
               $mwhere['mobile'] = $checkadr;
               $ckname='手机号';
               $rc = randCode(5,1);
            }
            if(I('post.checktp')=='email'){
                $verifyME  = verifyME('email',I('post.how'),$checkadr,I('post.uid'));
                if($verifyME['status']){
                    $body = "您的验证码为:".$rc."<br/>验证码1小时内有效！<br/>".C('SITE_INFO.name').'-'.C('SITE_INFO.summary');
                    $sendReturn = send_mail($checkadr, "", "验证邮箱-".C('SITE_INFO.name'), $body);
                    $return = array('status'=>$sendReturn,'info'=>$sendReturn);
                }else{
                    echojson($verifyME);
                    exit;
                }
            }elseif(I('post.checktp')=='mobile'){
                $verifyME  = verifyME('mobile',I('post.how'),$checkadr,I('post.uid'));
                if($verifyME['status']){
                    $body = "您的验证码为:".$rc."，验证码1小时内有效！";
                    $sendReturn = sendNote($checkadr,$body);
                    $return = array('status'=>$sendReturn['status'],'info'=>$sendReturn['info']);
                }else{
                    echojson($verifyME);
                    exit;
                }
            }
            if ($return['status'] == 1) {
                // 删除缓存
                S($checkadr,null);
                // 写入缓存
                S($checkadr,$rc,3600);
                // 验证缓存
                if(S($checkadr)!=''){
                    echojson(array('status' => 1, 'info' => "验证码已发送到您的".$ckname .":". $checkadr . "中，请注意查收"));
                    exit;
                }
            }else{
                echojson($return['info']);
                exit;
            }
        }else{
            
        }
    }
    // 异步检测微信是否扫码授权同步登录
    public function checkscan(){
        if(IS_POST){
            $key = I('post.key');
            $key =decrypt($key,C('AUTH_CODE'));
            $data = S($key);
            if (is_array($data)) {
                if ($data['uid']) {
                    $ckdata = array('uid'=>$data['uid']);
                    $this->systemSetCookie($ckdata);
                    S($key,null);
                    echojson(array('status' => 1,'info'=>'登录成功！正在跳转...','url'=>U('Member/index',null,'html',true)));
                }
                if ($data['url']) {
                    echojson(array('status' => 1,'info'=>'登录成功！正在跳转...','url'=>$data['url']));
                }
                
            }else{
                echojson(array('status' => 0));
            }
        }
    }
    // 服务器维护
    public function maintenance() {
        pre('asdfasdf');
        if (C('SITE_INFO.maintenance')!=1 or I('get.maintenance')==1) {
            $this->redirect('Index/index', null, 0, '页面跳转中...');
        }
        $this->display();
    }
    // 版本提示
    public function prompt() {
        $close = array();
        if (C('SITE_INFO.close_web')==1) {
            $close['web'] = 1;
        }
        // 关闭手机网页版
        if (C('SITE_INFO.close_webapp')==1) {
            $close['webapp'] = 1;
        }
        // 关闭微信版
        if (C('SITE_INFO.close_weixin')==1) {
            $close['weixin'] = 1;
        }
        $this->codemapimg = C('WEB_ROOT'). str_replace('./', '', C('UPLOADS_PICPATH')).C('Weixin.codemapimg');
        $this->close=$close;
        $this->display('index');
    }

    // 测试专用
    public function ceshi(){
        $member = M('member')->where(array('nickname'=>'ONcoo Service'))->find();
        $weixin = M('member_weixin')->where(array('nickname'=>'ONcoo Service'))->find();
        M('member')->where(array('nickname'=>'ONcoo Service'))->delete();
        M('member_weixin')->where(array('nickname'=>'ONcoo Service'))->delete();
        pre($member);
        pre($weixin);
        die;
    }

    // 退出登录
    Public function loginOut() {
        $systemConfig = include APP_PATH . '/Common/Conf/systemConfig.php';
        $loginMarked = md5($systemConfig['TOKEN']['member_marked']);
        setcookie($loginMarked, NULL, -$systemConfig['TOKEN']['member_timeout'], "/");
        unset($_SESSION[$loginMarked], $_COOKIE[$loginMarked]);
        session_destroy();
        $this->redirect("Index/index");
    }
    // 验证码
    public function verify_code(){
        ob_clean();
        $Verify = new \Think\Verify();
        $Verify->fontSize = 17;
        $Verify->length   = 4;
        $Verify->codeSet = '0123456789';
        $Verify->entry();
    }

    protected final function checkLogin(){
        //取得cookie内容，解密，和系统匹配
        $decryptStr = cookie($this->loginMarked);
        $ckdata = unserialize(decrypt($decryptStr,C('AUTH_CODE')));
        return $ckdata['uid'];
    }
    // 跳转页面
    protected final function referer($suname){
        if ($suname==U('Login/index','','html',true)||$suname==U('Login/register','','html',true)||!$suname) {
            $referer = U('Member/index','','html',true);
        }else{
            $referer = $suname;
        }
        $this->success('已登录，页面正在跳转...',$referer);
    }
    // 设置COOKIE
    private function systemSetCookie($data=''){
        if(is_array($data) && !empty($data)){
            $encryptStr = encrypt(serialize($data),C('AUTH_CODE'));
            cookie($this->loginMarked,$encryptStr,C('TOKEN.member_timeout'));
        }
    }

}