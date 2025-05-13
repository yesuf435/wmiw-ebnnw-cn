<?php
namespace Admin\Controller;
use Think\Controller;
// 本类设置项目一些常用信息
class WebinfoController extends CommonController {
    /**
      +----------------------------------------------------------
     * 配置网站信息
      +----------------------------------------------------------
     */
    public function index() {

        $this->display();
    }

    /**
      +----------------------------------------------------------
     * 配置网站信息
      +----------------------------------------------------------
     */
    public function steWebConfig() {
        // if (IS_POST) {
        //   echojson(array('status' => 0, 'info' => '演示站禁止修改网站配置', 'url' => __ACTION__));
        //   die;
        // }
        $display = 'steWebConfig';
        $this->checkSystemConfig('SITE_INFO',$display);
    }
    /**
      +----------------------------------------------------------
     * 配置网站邮箱信息
      +----------------------------------------------------------
     */
    public function setEmailConfig() {
        $display = 'setEmailConfig';
        $this->checkSystemConfig("SYSTEM_EMAIL",$display);
    }
    /**
      +----------------------------------------------------------
     * 配置网站短信
      +----------------------------------------------------------
     */
    public function setNoteConfig() {
        $display = 'setNoteConfig';
        $this->checkSystemConfig("SYSTEM_NOTE",$display);
    }
    /**
      +----------------------------------------------------------
     * 配置网站安全信息
      +----------------------------------------------------------
     */
    public function setSafeConfig() {
        $display = 'setSafeConfig';
        $this->checkSystemConfig("TOKEN",$display);
    }
    /**
      +----------------------------------------------------------
     * 编辑用户协议
      +----------------------------------------------------------
     */
    public function setUserAgreement() {
        if (IS_POST) {
            $this->checkToken();
            $config = APP_PATH . "Common/Conf/UserAgreement.php";
            $config = file_exists($config) ? include "$config" : array();
            $config = is_array($config) ? $config : array();
            if (set_config("UserAgreement", I('post.'), APP_PATH . "Common/Conf/")) {
                delDirAndFile(WEB_CACHE_PATH . "Cache/Admin/");
                echojson(array('status' => 1, 'info' => '用户协议已更新','url'=>U('Webinfo/setUserAgreement')));
            } else {
                echojson(array('status' => 0, 'info' => '用户协议失败，请检查','url'=>U('Webinfo/setUserAgreement')));
            }
        } else {
            $this->UserAgreement = include APP_PATH . 'Common/Conf/UserAgreement.php';
            $this->display('setUserAgreement');
        }
    }
    // 卖家等级管理
    public function setSellerLevel(){
      if (IS_POST) {

            if (set_config("SetSellerLevel", I('post.'), APP_PATH . "Common/Conf/")) {
                delDirAndFile( WEB_CACHE_PATH . "common~runtime.php");
                delDirAndFile(WEB_CACHE_PATH . "Cache/Admin/");
                echojson(array('status' => 1, 'info' => '用户等级已更新！','url'=>U('Webinfo/setSellerLevel')));
            } else {
                echojson(array('status' => 0, 'info' => '用户等级失败，请检查', 'url' => U('Webinfo/setSellerLevel')));
            }
        } else {
            $levelFile = include APP_PATH . 'Common/Conf/SetSellerLevel.php';
            if(empty($levelFile['level'])){
              $level = array(
              array('score_lt'=>15,'ico'=>'Public/Img/level/s_red_0.gif'),

              array('score_gt'=>14,'score_lt'=>30,'ico'=>'Public/Img/level/s_red_1.gif'),
              array('score_gt'=>29,'score_lt'=>45,'ico'=>'Public/Img/level/s_red_2.gif'),
              array('score_gt'=>44,'score_lt'=>60,'ico'=>'Public/Img/level/s_red_3.gif'),
              array('score_gt'=>59,'score_lt'=>75,'ico'=>'Public/Img/level/s_red_4.gif'),
              array('score_gt'=>74,'score_lt'=>90,'ico'=>'Public/Img/level/s_red_5.gif'),

              array('score_gt'=>89,'score_lt'=>105,'ico'=>'Public/Img/level/s_blue_1.gif'),
              array('score_gt'=>104,'score_lt'=>120,'ico'=>'Public/Img/level/s_blue_2.gif'),
              array('score_gt'=>119,'score_lt'=>135,'ico'=>'Public/Img/level/s_blue_3.gif'),
              array('score_gt'=>134,'score_lt'=>150,'ico'=>'Public/Img/level/s_blue_4.gif'),
              array('score_gt'=>149,'score_lt'=>165,'ico'=>'Public/Img/level/s_blue_5.gif'),

              array('score_gt'=>164,'score_lt'=>180,'ico'=>'Public/Img/level/s_cap_1.gif'),
              array('score_gt'=>179,'score_lt'=>195,'ico'=>'Public/Img/level/s_cap_2.gif'),
              array('score_gt'=>194,'score_lt'=>210,'ico'=>'Public/Img/level/s_cap_3.gif'),
              array('score_gt'=>209,'score_lt'=>225,'ico'=>'Public/Img/level/s_cap_4.gif'),
              array('score_gt'=>224,'score_lt'=>240,'ico'=>'Public/Img/level/s_cap_5.gif'),

              array('score_gt'=>239,'score_lt'=>255,'ico'=>'Public/Img/level/s_crown_1.gif'),
              array('score_gt'=>254,'score_lt'=>270,'ico'=>'Public/Img/level/s_crown_2.gif'),
              array('score_gt'=>269,'score_lt'=>285,'ico'=>'Public/Img/level/s_crown_3.gif'),
              array('score_gt'=>284,'score_lt'=>300,'ico'=>'Public/Img/level/s_crown_4.gif'),
              array('score_gt'=>399,'score_lt'=>'','ico'=>'Public/Img/level/s_crown_5.gif')
            );
            }else{
              $level = $levelFile['level'];
            }
            $this->level=$level;
            $this->display('setSellerLevel');
        }
    }
    // 买家等级管理
    public function setBuyLevel(){
      if (IS_POST) {
            if (set_config("SetBuyLevel", I('post.'), APP_PATH . "Common/Conf/")) {
                delDirAndFile( WEB_CACHE_PATH . "common~runtime.php");
                delDirAndFile(WEB_CACHE_PATH . "Cache/Admin/");
                echojson(array('status' => 1, 'info' => '用户等级已更新！','url'=>U('Webinfo/setBuyLevel')));
            } else {
                echojson(array('status' => 0, 'info' => '用户等级失败，请检查','url'=>U('Webinfo/setBuyLevel')));
            }
        } else {
            $level_default=

            $levelFile = include APP_PATH . 'Common/Conf/SetBuyLevel.php';
            if(empty($levelFile['buylevel'])){
              $buylevel = array(
                array('score_lt'=>15,'ico'=>'Public/Img/level/b_red_0.gif'),

                array('score_gt'=>14,'score_lt'=>30,'ico'=>'Public/Img/level/b_red_1.gif'),
                array('score_gt'=>29,'score_lt'=>45,'ico'=>'Public/Img/level/b_red_2.gif'),
                array('score_gt'=>44,'score_lt'=>60,'ico'=>'Public/Img/level/b_red_3.gif'),
                array('score_gt'=>59,'score_lt'=>75,'ico'=>'Public/Img/level/b_red_4.gif'),
                array('score_gt'=>74,'score_lt'=>90,'ico'=>'Public/Img/level/b_red_5.gif'),

                array('score_gt'=>89,'score_lt'=>105,'ico'=>'Public/Img/level/b_blue_1.gif'),
                array('score_gt'=>104,'score_lt'=>120,'ico'=>'Public/Img/level/b_blue_2.gif'),
                array('score_gt'=>119,'score_lt'=>135,'ico'=>'Public/Img/level/b_blue_3.gif'),
                array('score_gt'=>134,'score_lt'=>150,'ico'=>'Public/Img/level/b_blue_4.gif'),
                array('score_gt'=>149,'score_lt'=>165,'ico'=>'Public/Img/level/b_blue_5.gif'),

                array('score_gt'=>164,'score_lt'=>180,'ico'=>'Public/Img/level/b_cap_1.gif'),
                array('score_gt'=>179,'score_lt'=>195,'ico'=>'Public/Img/level/b_cap_2.gif'),
                array('score_gt'=>194,'score_lt'=>210,'ico'=>'Public/Img/level/b_cap_3.gif'),
                array('score_gt'=>209,'score_lt'=>225,'ico'=>'Public/Img/level/b_cap_4.gif'),
                array('score_gt'=>224,'score_lt'=>240,'ico'=>'Public/Img/level/b_cap_5.gif'),

                array('score_gt'=>239,'score_lt'=>255,'ico'=>'Public/Img/level/b_crown_1.gif'),
                array('score_gt'=>254,'score_lt'=>270,'ico'=>'Public/Img/level/b_crown_2.gif'),
                array('score_gt'=>269,'score_lt'=>285,'ico'=>'Public/Img/level/b_crown_3.gif'),
                array('score_gt'=>284,'score_lt'=>300,'ico'=>'Public/Img/level/b_crown_4.gif'),
                array('score_gt'=>399,'score_lt'=>'','ico'=>'Public/Img/level/b_crown_5.gif')
              );
            }else{
              $buylevel = $levelFile['buylevel'];
            }
            $this->buylevel=$buylevel;
            $this->display('setBuyLevel');
        }
    }


    
    /**
     * 导航链接管理
     * @return [type] [description]
     */
    public function navigation() {
        if (IS_POST) {
            echojson(D("Webinfo")->navigation());
        } else {
            $this->assign("list", D("Webinfo")->navigation());
            $this->display();
        }
    }
    //---导航链接异步排序
    public function order_navigation() {
        if (IS_POST) {
            $getInfo = I('post.');
            $M = M('navigation');
            $where=array('lid'=>$getInfo['odAid']);
            if($getInfo['odType'] == 'rising'){
                if($M->where($where)->setInc('sort')){

                    echojson(array('status'=>'1','msg'=>'排序写入数据库成功'));
                }
            }elseif($getInfo['odType'] == 'drop'){
                if($M->where($where)->setDec('sort')){
                    echojson(array('status'=>'1','msg'=>'排序写入数据库成功'));
                }
            }
        } else {
            echojson(array('status'=>'0','msg'=>'什么情况'));
        }
    }
    /**
      +----------------------------------------------------------
     * 网站配置信息保存操作等
      +----------------------------------------------------------
     */
    private function checkSystemConfig($obj = "SITE_INFO",$display) {
        if (IS_POST) {
            $this->checkToken();
            $config = APP_PATH . "Common/Conf/systemConfig.php";
            $config = file_exists($config) ? include "$config" : array();
            $config = is_array($config) ? $config : array();
            $config = array_merge($config, array("$obj" => $_POST));
            if($obj == "SITE_INFO"){
              $str = "站点配置信息";
              $url = U('Webinfo/steWebConfig');
            }elseif ($obj == "SYSTEM_EMAIL") {
              $str = "系统邮箱配置";
              $url = U('Webinfo/setEmailConfig');
            }elseif($obj == 'TOKEN'){
              $str = "安全设置";
              $url = U('Webinfo/setSafeConfig');
            }elseif($obj == 'SYSTEM_NOTE'){
              $str = "系统短信配置";
              $url = U('Webinfo/setNoteConfig');
            }
            if (set_config("systemConfig", $config, APP_PATH . "Common/Conf/")) {
                delDirAndFile( WEB_CACHE_PATH . "common~runtime.php");
                delDirAndFile(WEB_CACHE_PATH . "Cache/Admin/");
                if ($obj == "TOKEN") {
                    unset($_SESSION, $_COOKIE);
                    echojson(array('status' => 1, 'info' => $str . '已更新，你需要重新登录', 'url' => __APP__ . '?' . time()));
                } else {
                    echojson(array('status' => 1, 'info' => $str . '已更新', 'url' => __ACTION__));
                }
            } else {
                echojson(array('status' => 0, 'info' => $str . '失败，请检查', 'url' => __ACTION__));
            }
        } else {
            $this->display($display);
        }
    }

    /**
      +----------------------------------------------------------
     * 测试邮件账号是否配置正确
      +----------------------------------------------------------
     */
    public function testEmailConfig() {
        C('TOKEN_ON', false);
        $return = send_mail($_POST['test_email'], "", "测试配置是否正确", "这是一封测试邮件，如果收到了说明配置没有问题", "", $_POST);
        if ($return == 1) {
            echojson(array('status' => 1, 'info' => "测试邮件已经发往你的邮箱" . $_POST['test_email'] . "中，请注意查收", 'url' => U('Webinfo/setEmailConfig')));
        } else {
            echojson(array('status' => 0, 'info' => "$return", 'url' => U('Webinfo/setEmailConfig')));
        }
    }
    /**
      +----------------------------------------------------------
     * 测试邮件账号是否配置正确
      +----------------------------------------------------------
     */
    public function testNoteConfig() {
        C('TOKEN_ON', false);
        echojson(sendNote(C('SYSTEM_NOTE.test'),$content='如果收到了说明没有问题'));
    }
    // 快递管理
    public function express(){
        $express = C('Express');

        if (I('get.type')=='getcom') {
            $result = getComs($express['appkey']);
            if ($result['resultcode']==200) {
                $config = APP_PATH . "Common/Conf/SetExpress.php";
                $config = file_exists($config) ? include "$config" : array();
                $config = is_array($config) ? $config : array();
                $config['Express']['comarr'] = $result['result'];
                if (set_config("SetExpress", $config, APP_PATH . "Common/Conf/")) {
                    delDirAndFile(WEB_CACHE_PATH . "Cache/Admin/");
                    $this->success('已经获取支持快递公司！',U('Webinfo/express'));
                }else{
                    $this->error('写入文件失败！');
                }
            }else{
                $this->error('获取支持快递公司，错误代码：'.$result['resultcode'].';描述：'.$result['reason']);
            }
        }else{
            $this->list=$express['comarr'];
            $this->display();
        }
    }
    // 快递管理
    public function express_set(){
        if (IS_POST) {
            $config = APP_PATH . "Common/Conf/SetExpress.php";
            $config = file_exists($config) ? include "$config" : array();
            $config = is_array($config) ? $config : array();
            $config['Express']['appkey'] = I('post.appkey');
            if (set_config("SetExpress", $config, APP_PATH . "Common/Conf/")) {
                delDirAndFile(WEB_CACHE_PATH . "Cache/Admin/");
                echojson(array('status' => 1, 'info' => '设置成功', 'url'=>U('Webinfo/express',array('type'=>'getcom'))) );
            } else {
                echojson(array('status' => 0, 'info' => '设置失败，请检查', 'url'=>U('Webinfo/express_set')) );
            }
        }else{
            $express['Express']['appkey']=C('Express.appkey');
            $this ->appkey=$express['Express']['appkey'];
            $this->display();
        }
    }
// 快递查询测试
    public function express_test(){
        $express = include APP_PATH . 'Common/Conf/SetExpress.php';
        if (IS_POST) {
            $com = I('post.com');
            $exno = I('post.exno');
            $result = getExpress($com,$exno);
            $this->ereturn =$result;
            $this->type='result';        
        }else{
            $this->list=$express['Express']['comarr'];
            $this->display();
        }
        $this->display();
        
    }

}

?>