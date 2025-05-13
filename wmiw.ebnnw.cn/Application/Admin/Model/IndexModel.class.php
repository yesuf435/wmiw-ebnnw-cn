<?php
namespace Admin\Model;
use Think\Model;
class IndexModel extends Model {

    public function my_info($loginMarked,$aid) {
        $datas = I('post.data');
        // 密码错误达到5次
        $pas_err = S('admin'.$aid)?S('admin'.$aid):0;
        if($pas_err>=5){
            echojson(array('status' => 0, 'info' => '由于您今天连续输入5次错误旧密码错误！请24小时后在进行登录操作。','url'=>__SELF__));
            exit;
        }
        $admin = M("Admin");
        $odpwd = $admin->where(array('aid'=>$aid))->getField('pwd');
        if (md5(C("AUTH_CODE") . md5($datas['pwd0'])) != $odpwd) {
            // 旧密码错误错误次数加1
            S('admin'.$aid,$pas_err+1,86400);
            if(5-S('admin'.$aid)==0){
                return array('status' => 0, 'info' => '由于您今天连续输入5次错误的旧密码错误！请24小时后在进行登录操作。','url'=>__SELF__);
            }else{
                return array('status' => 0, 'info' => '旧密码错误错误,请检查！错误'.(5-S('admin'.$aid)).'次后，请24小时后在进行登录操作。','url'=>__SELF__);
            }
        }
        if (trim($datas['pwd']) == '') {
            return array('status' => 0, 'info' => "密码不能为空");
        }
        if (trim($datas['pwd']) != trim($datas['pwd1'])) {
            return array('status' => 0, 'info' => "两次密码不一致");
        }
        $data['aid'] = $aid;
        $data['pwd'] = encryptPwd($datas['pwd']);
        if ($admin->save($data)) {
            cookie($loginMarked,null);
            return array('status' => 1, 'info' => "你的密码已经成功修改，请重新登录",'url'=>U('Index/index'));
        } else {
            return array('status' => 0, 'info' => "密码修改失败");
        }
    }

}

?>
