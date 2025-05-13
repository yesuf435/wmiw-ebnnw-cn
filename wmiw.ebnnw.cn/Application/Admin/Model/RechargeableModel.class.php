<?php
namespace Admin\Model;
use Think\Model;
class RechargeableModel extends Model {
    public function listRechargeable($firstRow = 0, $listRows = 20, $where) {
        $list = M('rechargeable')->order('time desc')->limit($firstRow .','. $listRows)->where($where)->select();
        $member = M('member');
        $admin = M('admin');
        foreach ($list as $lk => $lv) {
            if ($lv['uid']) {
                $list[$lk]['member'] = $member->where(array('uid'=>$lv['uid']))->field('account,nickname')->find();
            }
            $list[$lk]['admin'] = $admin->where(array('aid'=>$lv['aid']))->field('email,nickname')->find();
            if ($lv['status']==0) {
                $list[$lk]['stach'] = '未使用';
            }elseif ($lv['status']==1) {
                $list[$lk]['stach'] = '已使用';
            }else{
                $list[$lk]['stach'] = '已过期';
            }
        }
        return $list;
    }
}

?>
