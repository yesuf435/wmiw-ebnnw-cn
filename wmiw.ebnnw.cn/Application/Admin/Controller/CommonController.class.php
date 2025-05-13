<?php
namespace Admin\Controller;
use Think\Controller;
use Org\Util\Rbac;
class CommonController extends Controller {
    public $cUid;
    public $loginMarked;
    /**
      +----------------------------------------------------------
     * 初始化
     * 如果 继承本类的类自身也需要初始化那么需要在使用本继承类的类里使用parent::_initialize();
      +----------------------------------------------------------
     */
    public function _initialize() {
        $systemConfig = include APP_PATH . 'Common/Conf/systemConfig.php';
        if (empty($systemConfig['TOKEN']['admin_marked'])) {
            $systemConfig['TOKEN']['admin_marked'] = "admin.oncoo.net";
            $systemConfig['TOKEN']['admin_timeout'] = 3600;
            $systemConfig['TOKEN']['member_marked'] = "home.oncoo.net";
            $systemConfig['TOKEN']['member_timeout'] = 3600;
            set_config("systemConfig", $systemConfig, APP_PATH . "Common/Conf/");
            if (is_dir(WEB_ROOT . "install/")) {
                //delDirAndFile(WEB_ROOT . "install/", TRUE);
            }
        }
        //系统默认所需id数组，防止用户删除或编辑
        $this->lock_id = C('LOCK_ID'); 
        $this->loginMarked = md5(C('TOKEN.admin_marked'));
        $ckdata = $this->checkLogin();
        $this->cAid=$ckdata['aid'];
        $my_info = M('admin')->where(array('aid'=>$ckdata['aid']))->find();
        // 用户权限检查
        if (C('USER_AUTH_ON') && !in_array(CONTROLLER_NAME, explode(',', C('NOT_AUTH_MODULE')))) {
           // import('ORG.Util.RBAC');
            if (!RBAC::AccessDecision()) {
                //检查认证识别号
                if (!$_SESSION [C('USER_AUTH_KEY')]) {
                    //过期session后清除已登录标志
                    cookie(null,$this->loginMarked);
                    //跳转到认证网关
                    redirect(C('USER_AUTH_GATEWAY'));
//                    redirect(PHP_FILE . C('USER_AUTH_GATEWAY'));
                }
                // 没有权限 抛出错误
                if (C('RBAC_ERROR_PAGE')) {
                    // 定义权限错误页面
                    redirect(C('RBAC_ERROR_PAGE'));
                } else {
                    if (C('GUEST_AUTH_ON')) {
                        $this->assign('jumpUrl', C('USER_AUTH_GATEWAY'));
                    }
                    // 提示错误信息
//                     echo L('_VALID_ACCESS_');
                    $this->error(L('_VALID_ACCESS_'));
                }
            }
        }
        $this->upWholeUrl = __ROOT__.trim(C('UPLOADS_PICPATH'),'.');
        $this->my_info = $my_info;
        $this->site = $systemConfig;
        // 待实名认证
        $realname['count'] = M('Member')->where(array('idcard_check'=>1))->count();
        $idcard_check_time = M('Member')->where(array('idcard_check'=>1))->order('idcard_check_time desc')->getField('idcard_check_time');
        $realname['time'] = timediff($idcard_check_time,time(),'str');
        $this->realname=$realname;
        $this->operated = $realname['count'];
      //  $this->getQRCode();
    }

    protected function getQRCode($url = NULL) {
        if (IS_POST) {
            $this->assign("QRcodeUrl", "");
        } else {
//            $url = empty($url) ? C('WEB_ROOT') . $_SERVER['REQUEST_URI'] : $url;
            $url = empty($url) ? C('WEB_ROOT') . U(CONTROLLER_NAME . '/' . ACTION_NAME) : $url;
            /*import('QRCode');
            $QRCode = new QRCode('', 80);
            $QRCodeUrl = $QRCode->getUrl($url);
            $this->assign("QRcodeUrl", $QRCodeUrl);*/
        }
    }
    // 登陆验证
    protected final function checkLogin(){
        //取得cookie内容，解密，和系统匹配
        $Xxtea = new \Org\Util\Xxtea();
        $decryptStr = cookie($this->loginMarked);
        $ckdata = unserialize(decrypt($decryptStr,C('AUTH_CODE')));

        if ($ckdata['aid']) {
            $this->systemSetCookie($ckdata);
        }else{
            $this->redirect("Public/index");
        }
        return $ckdata;
    }
    // 设置Cookie
    private function systemSetCookie($user=''){
        if(is_array($user) && !empty($user)){
            $encryptStr = encrypt(serialize($user),C('AUTH_CODE'));
            cookie($this->loginMarked,$encryptStr,C('TOKEN.admin_timeout'));
        }
    }





    /**
      +----------------------------------------------------------
     * 验证token信息
      +----------------------------------------------------------
     */
    protected function checkToken() {
        if (IS_POST) {
            if (!M("Admin")->autoCheckToken($_POST)) {
                die(echojson(array('status' => 0,'msg'=>'重复提交数据，请刷新页面重试！', 'info' => '重复提交数据，请刷新页面重试！','url'=>__SELF__)));
            }
            unset($_POST[C("TOKEN_NAME")]);
        }
    }
}