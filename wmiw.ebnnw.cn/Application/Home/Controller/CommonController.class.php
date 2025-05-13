<?php
namespace Home\Controller;
use Think\Controller;
use Com\WechatAuth;
use Com\JsSdk;
class CommonController extends Controller {
    public $loginMarked;
    /**
      +----------------------------------------------------------
     * 初始化
     * 如果 继承本类的类自身也需要初始化那么需要在使用本继承类的类里使用parent::_initialize();
      +----------------------------------------------------------
     */
    public function _initialize() {
        error_reporting(0);
        ini_set("error_reporting","E_ALL & ~E_NOTICE"); 
        header('Access-Control-Allow-Origin:*');
        $systemConfig = include APP_PATH . '/Common/Conf/systemConfig.php';
        if (empty($systemConfig['TOKEN']['member_marked'])) {
            $systemConfig['TOKEN']['admin_marked'] = "admin.oncoo.net";
            $systemConfig['TOKEN']['admin_timeout'] = 3600;
            $systemConfig['TOKEN']['member_marked'] = "home.oncoo.net";
            $systemConfig['TOKEN']['member_timeout'] = 3600;
            set_config("systemConfig", $systemConfig, APP_PATH . "/Common/Conf/");
            if (is_dir(APP_PATH . "install/")) {
                //delDirAndFile(WEB_ROOT . "/install/", TRUE);
            }
        } 
        if (CONTROLLER_NAME!='Payment') {
            // 网站维护
            if ($systemConfig['SITE_INFO']['maintenance']==1) {
                $this->redirect('Login/maintenance', null, 0, '页面跳转中...');
            }
            // 关闭电脑版
            if ($systemConfig['SITE_INFO']['close_web']==1&&!ismobile()) {
                $this->redirect('Login/prompt', null, 0, '页面跳转中...');
            }
            // 关闭手机网页版
            if ($systemConfig['SITE_INFO']['close_webapp']==1&&ismobile()) {
                $this->redirect('Login/prompt', null, 0, '页面跳转中...');
            }
        }
        
        $this->site=$systemConfig;
        // checkKey();
        $this->loginMarked = md5($systemConfig['TOKEN']['member_marked']);
        // 获取当前用户id
        // 登录信息分配到模板
        $ckdata = $this->checkLogin();
        $login = 0;
        if ($ckdata['uid']) {
            $this->cUid = $ckdata['uid'];
            // 缓存当前用户信息
            if (S('us'.$this->cUid)) {
                $usinfo = S('us'.$this->cUid);
            }else{
                $usinfo = M('Member')->where('uid ='.$this->cUid)->field('uid,account,nickname,organization,avatar,avatar_sel')->find();
                $usinfo['avatar'] = getUserpic($this->cUid,1);
                $usinfo['avatar_sel'] = getUserpic($this->cUid,1,'sel');
                S('us'.$this->cUid,$usinfo,C('TOKEN.member_timeout'));
            }
            $this->usinfo = $usinfo;
            // 获取未读信息
            $this->smsc = M('mysms')->where(array('uid'=>$this->cUid,'status'=>0,'delmark'=>0))->count();
            $login = 1;
        }else{
            if (I('get.m')) {
                // 记录该访问者推广用户
                $source = explode('_', I('get.m'));
                if ($source[1]) {
                    if(M('member')->where(array('uid'=>$source[1]))->find()){
                        $source = array('tid'=>$source[1]);
                        cookie($this->loginMarked,$source);
                    }
                }
            }
        }
        // 是否B2C运营【
        $identity = 0;
        $isbc = 0;
        if (C('Auction.jurisdiction')==1) {
            // 是否为一次性缴纳保证金用户
            if ($login && M('seller_pledge')->where(array('sellerid'=>$this->cUid,'type'=>'disposable','status'=>'1'))->count()) {
                $identity = 1;
            }
            $isbc = 1;
        }else{
            $identity = 1;
        }
        $this->isbc =$isbc;
        $this->myidentity = $identity;
        // 是否B2C运营】
        // 微信浏览器需要关注微信公众号，或者直接登陆【
        $iswei = 0;
        if(is_weixin()&&$systemConfig['SITE_INFO']['close_weixin']!=1&&C('Weixin.appid')!=''&&C('Weixin.appsecret')!=''&&I('get.key')==''){
            $iswei = 1;
            $this->showcodemap = 1;
            // 获取accessToken
            $accessToken = getJson(U('Home/Common/setAccessToken@'.$_SERVER['SERVER_NAME']));
            $jssdk = new JSSDK(C('Weixin.appid'),C('Weixin.appsecret'),$accessToken);
            $jssdk->debug = false;
            $signPackage = $jssdk->GetSignPackage();
            $shareUrlArr = explode('?from=', $signPackage['url']);
            $this->sharelink = $shareUrlArr[0];
            $this->shareimg = C('WEB_ROOT'). str_replace('./', '',C('UPLOADS_PICPATH')).C('Weixin.shareimg');
            $this->signPackage = $signPackage;
            // 未登录跳转到登陆绑定页面
            if(!$this->checkLogin()){
                // 如果get方式访问进入登录页面
                if (IS_GET && I('get.gol')!=1) {
                    if(I('get.state')=='winlogin'){
                        $this->winlogin();
                    }else{
                        $winoauth = 'https://wxht.xakaiku.cn/connect/oauth2/authorize?appid='.C('Weixin.appid').'&redirect_uri='.urlencode($signPackage['url']).'&response_type=code&scope=snsapi_userinfo&state=winlogin#wechat_redirect';
                        header("Location:".$winoauth);
                        exit();
                    }
                }
            }else{
                $member_weixin = M('member_weixin');
                $member = M('member');
                // 更新微信登陆时间和登陆方式
                $member_weixin->where(array('uid'=>$this->cUid))->setField('weitime',time()+172800);
                // 分配是否已经关注拍卖系统
                $subscribe = $member_weixin->where(array('uid'=>$this->cUid))->getField('subscribe');
                // 分配公众号二维码地址
                $this->codemapimg = C('WEB_ROOT'). str_replace('./', '', C('UPLOADS_PICPATH')).C('Weixin.codemapimg');
            }
        }
        $this->iswei = $iswei;
        // 微信浏览器需要关注微信公众号，或者直接登陆】

        $this->uid = $this->cUid;

        // 关注提醒状态开启状态
        $this->mapstate = $subscribe;

        // 后台关注提醒状态
        $this->codemap =C('Weixin.codemap');
        
        // 为结束的生成订单
        $this->foreach_order();

        // 当前时间分配到模板
        $this->nowtime = time();

        // 图片上传路径
        $this->upWholeUrl = __ROOT__.trim(C('UPLOADS_PICPATH'),'.');
        // 频道导航
        $cate = M('Goods_category');
        $this->channelMenu=$cate->where(array('pid'=>0,'hot'=>1))->order('sort desc')->select();
        $this->login = $login;
    }

    // 单品拍卖订单
    public function foreach_order() {
        $ncow = array(
            'endtime'=>array('lt',time()),
            'endstatus'=>0,
        );
        $nco = D('Auction')->where($ncow)->select();
        // 查找数组内相同值的保留一个
        if(is_array($nco)){
            // 生成订单进入开关
            foreach ($nco as $n => $nv) {
                create_order($nv);
            }
        }
    }
    // 微信扫码授权
    Public function scancode() {
        if(IS_POST){
            // 加密
            $key = time().substr(microtime(), 2,5);
            S($key,array('type'=>1),3600);
            $key = encrypt($key,C('AUTH_CODE'));
            $redirect = U('Common/scancode',array('key'=>$key),'html',true);
            $url = 'https://wxht.xakaiku.cn/connect/oauth2/authorize?appid='.C('Weixin.appid').'&redirect_uri='.urlencode($redirect).'&response_type=code&scope=snsapi_userinfo&state=winlogin#wechat_redirect';
            echojson(array('status' => 1, 'url' => $url,'key'=>$key,'html',true));
        }else{
            $key = I('get.key');
            $key =decrypt($key,C('AUTH_CODE'));
            $keyData = S($key);
            if (is_array($keyData)&&$keyData['type']) {
                $this->winlogin($key);
            }else{
                $this->error('二维码已过期！');
            }
        }
    }
    // 微信浏览器打开公众号关注用户登陆提醒关注公众号
    public function winlogin($key=0){
            // 微信登录【
            if(I('get.code')) {
                $systemConfig = include APP_PATH . '/Common/Conf/systemConfig.php';
                // 获取openid、access_token【
                $code = I('get.code');
                $accessToken = getJson(U('Home/Common/setAccessToken@'.$_SERVER['SERVER_NAME']));
                $WechatAuth = new WechatAuth(C('Weixin.appid'),C('Weixin.appsecret'),$accessToken);

                $codeReturn = $WechatAuth->getAccessToken('code',$code);
                // 获取openid、access_token】
                if ($codeReturn['access_token']) {
                     $userInfo = $WechatAuth->userInfo($codeReturn['openid']);
                }else{
                    $this->error('获取openid、access_token失败！');
                    exit();
                }
                // 如果获取到openid【
                if($codeReturn['openid']){
                    // 获取微信用户信息【 
                    $member_weixin = M('member_weixin');
                    $member = M('member');
                    // 查询本地存在用户
                    $wuser_info = $member_weixin->where(array('openid'=>$codeReturn['openid']))->find();
                    // GET方式传递地址会出错所以写入缓存调用缓存名称【
                    $suname = time().substr(microtime(), 2,3);
                    $uarr = explode('?code=', get_url());
                    S($suname,$uarr[0],3600);
                    // GET方式传递地址会出错所以写入缓存调用缓存名称】
                    if($wuser_info){
                        $info = $member->where(array('uid'=>$wuser_info['uid']))->field('pwd,weiauto')->find();
                        // 如果设置了微信自动登陆，或者执行的是授权电脑版登录，则进行登陆操作
                        if($info['weiauto']||$key){
                            // 更新用户数据
                            $member->where(array('uid'=>$wuser_info['uid']))->setField(array('login_time'=>time(),'login_ip'=>get_client_ip()));
                            // 更新微信数据
                            $member_weixin->where(array('openid'=>$userInfo['openid']))->setField('weitime',time()+172800);
                            // 扫码登录后更新缓存数据，电脑端异步检测缓存进行同步登录
                            if ($key) {
                                $keyData = S($key);
                                if (is_array($keyData)&&$keyData['type']==1) {
                                    $keyData['uid'] = $wuser_info['uid'];
                                    S($key,$keyData,3600);
                                    $this->display('scancode');
                                }else{
                                    $this->error('二维码已过期！');
                                }
                            }else{
                                // 发送cookie
                                $ckdata = array('uid'=>$wuser_info['uid'],'timeout'=>C('TOKEN.member_timeout')+time());
                                $this->systemSetCookie($ckdata);
                                redirect(S($suname),0,'登陆中...');
                                exit();
                            }
                        }else{
                            // 扫码授权进入该操作
                            if ($key) {
                                $keyData = S($key);
                                if (is_array($keyData)&&$keyData['type']==1) {
                                    $keyData['url'] = U("Login/index",array('gol'=>1,'openid'=>$codeReturn['openid'],'access_token'=>$codeReturn['access_token'],'suname'=>$suname,'diversity'=>1),'html',true);
                                    S($key,$keyData,3600);
                                    $this->display('scancode');
                                }else{
                                    $this->error('二维码已过期！');
                                }
                            }else{
                                $this->redirect("Login/index",array('gol'=>1,'openid'=>$codeReturn['openid'],'access_token'=>$codeReturn['access_token'],'suname'=>$suname,'diversity'=>1),0,'跳转中...');
                            }
                        }
                    // 添加该用户到本站
                    }else{
                        // 进入微信绑定电脑账号页面操作
                        if (C('Weixin.diversity')==1||C('Weixin.diversity')!=0) {
                            $gourl = U("Login/register",array('gol'=>1,'openid'=>$codeReturn['openid'],'access_token'=>$codeReturn['access_token'],'suname'=>$suname,'bound'=>'checked'),'html',true);
                        }else{
                            $gourl = U("Login/weioauth",array('gol'=>1,'openid'=>$codeReturn['openid'],'access_token'=>$codeReturn['access_token'],'suname'=>$suname,'create'=>'auto'),'html',true);
                        }
                        // 扫码登录后更新缓存数据，电脑端异步检测缓存进行同步登录
                        if ($key) {
                            $keyData = S($key);
                            if (is_array($keyData)&&$keyData['type']==1) {
                                $keyData['url'] = $gourl;
                                S($key,$keyData,3600);
                                $this->display('scancode');
                            }else{
                                $this->error('二维码已过期！');
                            }
                        }else{
                            Header("Location: ".$gourl);
                        }
                    }
                }else{
                    $this->error('获取openid失败！');
                }
                // 如果获取到openid】
            }else{
                $this->error('获取code失败！');
            }
            // 微信登录】
    }
// 获取accessToken 使用方法getJson(U('Home/Common/setAccessToken@'.$_SERVER['SERVER_NAME']))
    Public function setAccessToken() {
        if(!S('S_accessToken')){
            $WechatAuth = new WechatAuth(C('Weixin.appid'),C('Weixin.appsecret'));
            $S_accessToken=$WechatAuth->getAccessToken();
            if ($S_accessToken && is_array($S_accessToken)) {
                S('S_accessToken',$S_accessToken['access_token'],7200);//2小时过期
            }
        }
        $this->ajaxReturn(S('S_accessToken'));
    }
    // 登陆验证 $type:1跳转，0返回登录状态
    protected final function checkLogin(){
        //取得cookie内容，解密，和系统匹配
        $decryptStr = cookie($this->loginMarked);
        $decryptArr = unserialize(decrypt($decryptStr,C('AUTH_CODE')));
        if (is_array($decryptArr)&&!empty($decryptArr)) {
            $this->systemSetCookie($decryptArr);
        }
        return $decryptArr;
    }
    // 设置Cookie
    private function systemSetCookie($data=''){
        if(is_array($data) && !empty($data)){
            $encryptStr = encrypt(serialize($data),C('AUTH_CODE'));
            cookie($this->loginMarked,$encryptStr,C('TOKEN.member_timeout'));
        }
    }
    /**
      +----------------------------------------------------------
     * 验证token信息
      +----------------------------------------------------------
     */
    protected function checkToken($model) {
        if (IS_POST) {
            if (!M()->autoCheckToken($_POST)) {
                if ($model) {
                    return(array('status' => 0,'msg'=>'重复提交数据，请刷新页面重试！', 'info' => '重复提交数据，请刷新页面重试！','url'=>__SELF__));
                    die;
                }else{
                    die(echojson(array('status' => 0,'msg'=>'重复提交数据，请刷新页面重试！', 'info' => '重复提交数据，请刷新页面重试！','url'=>__SELF__)));
                }
                
            }
            unset($_POST[C("TOKEN_NAME")]);
        }
    }
}