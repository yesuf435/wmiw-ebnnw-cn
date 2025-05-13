<?php
namespace Admin\Model;
use Think\Model;
class PublicModel extends Model {

    public function auth($datas) {
        $datas = $_POST;
        // 验证码验证
        if (check_verify($_POST['verify_code'])==false) {
            die(json_encode(array('status' => 0, 'info' => "验证码错误啦，再输入吧")));
        }
        // 登录验证
        $admin = M("Admin");

        // 密码错误达到5次
        $pas_err = S($datas['email'])?S($datas['email']):0;
        if($pas_err>=100){
            return(array('status' => 0, 'info' => '由于您今天连续输入100次错误密码！请24小时后在进行登录操作。','url'=>__SELF__));
            exit;
        }
        if ($admin->where(array('email'=>$datas['email']))->count() >= 1) {
            $info = $admin->where(array('email'=>$datas['email']))->find();
            if ($info['status'] == 0) {
                return array('status' => 0, 'info' => "你的账号被禁用，有疑问联系管理员吧");
            }
            if ($datas['op_type'] == 2) {
                $rc = randCode(5);
                $code = $info['aid'] . md5($rc);
                $url = str_replace(C("webPath"),"",C("WEB_ROOT")) . U("Public/findPwd", array("code" => $code));
                $body = "请在浏览器上打开地址：<a href='$url'>$url</a> 进行密码重置操作                            ";
                $return = send_mail($datas["email"], "", "找回密码", $body);
                if ($return == 1) {
                    $info['find_code'] = $rc;
                    $admin->save($info);
                    return array('status' => 1, 'info' => "重置密码邮件已经发往你的邮箱" . $_POST['email'] . "中，请注意查收");
                } else {
                    return array('status' => 0, 'info' => "$return");
                }
                exit;
            }
            if ($info['pwd'] == encryptPwd($datas['pwd'])) {
                $ckdata = array('aid'=>$info['aid']);
                $this->systemSetCookie($ckdata);
                return array('status' => 1, 'info' => "登录成功", 'url' => U("Index/index"));
            } else {
                // 密码错误次数加1
                S($datas['email'],$pas_err+1,86400);
                if(5-S($datas['email'])==0){
                    return(array('status' => 0, 'info' => '由于您今天连续输入5次错误的密码！请24小时后在进行登录操作。','url'=>__SELF__));
                }else{
                    return(array('status' => 0, 'info' => '密码错误,请检查！错误'.(5-S($datas['email'])).'次后，请24小时后在进行登录操作。','url'=>__SELF__));
                }
            }
        } else {
            return array('status' => 0, 'info' => "不存在邮箱为：" . $datas["email"] . '的管理员账号！');
        }
    }

    public function findPwd() {
        $datas = $_POST;
        $admin = M("Admin");
        if (check_verify($_POST['verify_code'])==false) {
            die(json_encode(array('status' => 0, 'info' => "验证码错误啦，再输入吧")));
        }
//        $this->check_verify_code();
        if (trim($datas['pwd']) == '') {
            return array('status' => 0, 'info' => "密码不能为空");
        }
        if (trim($datas['pwd']) != trim($datas['pwd1'])) {
            return array('status' => 0, 'info' => "两次密码不一致");
        }
        $data['aid'] = $_SESSION['aid'];
        $data['pwd'] = md5(C("AUTH_CODE") . md5($datas['pwd']));
        $data['find_code'] = NULL;
        if ($admin->save($data)) {
            return array('status' => 1, 'info' => "你的密码已经成功重置", 'url' => U('Access/index'));
        } else {
            return array('status' => 0, 'info' => "密码重置失败");
        }
    }

    private function systemSetCookie($user=''){
        if(is_array($user) && !empty($user)){
            $loginMarked = md5(C('TOKEN.admin_marked'));
            $encryptStr = encrypt(serialize($user),C('AUTH_CODE'));
            cookie($loginMarked,$encryptStr,C('TOKEN.admin_timeout'));

        }
    }




}

?>
