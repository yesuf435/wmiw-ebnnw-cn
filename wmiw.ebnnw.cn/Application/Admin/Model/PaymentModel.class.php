<?php
namespace Admin\Model;
use Think\Model;
class PaymentModel extends Model {
    public function payList($firstRow = 0, $listRows = 20, $where){
        $list = M("payorder")->order('update_time desc')->limit($firstRow .','. $listRows)->where($where)->select();
        $member = M('Member');
        $payment = C('payment.list');
        // pre($payment);
        // die;
        foreach ($list as $ok => $ov) {
            $list[$ok]['account'] = $member->where(array('uid'=>$ov['uid']))->getField('account');
            $list[$ok]['purpose'] = getUse($ov['purpose']);
            $list[$ok]['paytype'] = $payment[$ov['paytype']]['chname'];
            $list[$ok]['update_time'] = date('Y-m-d H:i',$ov['update_time']);
        }
        return $list;
    }
}

?>
