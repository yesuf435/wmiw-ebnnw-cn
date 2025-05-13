<?php
namespace Admin\Model;
use Think\Model;
class MemberModel extends Model {
    public function addEditMember($act){
        $member=M('Member');
        $data=I('post.data');
        if($data['pwd']){
            if(strlen($data['pwd'])<6){
                return array("status" => 0, 'field'=>'pwd' , "info" => "密码少于6位！");
                exit;
            }
            $data['pwd']=encryptPwd( $data['pwd']);
        }else{
            unset($data['pwd']);
        }
        if($data['paypwd']){
            if(strlen($data['paypwd'])<6){
                return array("status" => 0, 'field'=>'paypwd' , "info" => "密码少于6位！");
                exit;
            }
            $data['paypwd']=encryptPwd( $data['paypwd']);
        }else{
            unset($data['paypwd']);
        }
        if (ACTION_NAME=='edit') {
            if($data['account']!=''){
                if($member->where(array('uid'=>array('neq'=>$data['uid'],'account'=>$data['account'])))->count()){
                    return array("status" => 0, 'field'=>'account' , "info" => "账号已存在，请更换！");
                    exit;
                }
                if(!preg_match('/^[a-zA-Z][\w]{3,16}$/', $data['account'])){
                    return array("status" => 0, 'field'=>'account' , "info" => "账号格式错误！");
                    exit;
                }
            }
            if($data['email']!=''){
                if($member->where(array('uid'=>array('neq'=>$data['uid'],'email'=>$data['email'])))->count()){
                    return array("status" => 0, 'field'=>'email' , "info" => "邮箱地址已存在，请更换！");
                    exit;
                }
                if(!is_email($data['email'])){
                    return array("status" => 0, 'field'=>'email' , "info" => "邮箱格式错误！");
                    exit;
                }
            }
            if($data['mobile']!=''){
                if($member->where(array('uid'=>array('neq'=>$data['uid'],'mobile'=>$data['mobile'])))->count()){
                    return array("status" => 0, 'field'=>'mobile' , "info" => "手机号已存在，请更换！");
                    exit;
                }
                if(!preg_match('/^1\d{10}$/', $data['mobile'])){
                    return array("status" => 0, 'field'=>'mobile' , "info" => "手机号格式错误！");
                    exit;
                }
            }
            if($data['nickname']!=''){
                if($member->where(array('uid'=>array('neq'=>$data['uid'],'nickname'=>$data['nickname'])))->count()){
                    return array("status" => 0, 'field'=>'nickname' , "info" => "昵称已存在，请更换！");
                    exit;
                }
            }else{
                return array("status" => 0, 'field'=>'nickname' , "info" => "昵称不能为空！");
            }
            if($data['organization']!=''){
                if($member->where(array('uid'=>array('neq'=>$data['uid'],'organization'=>$data['organization'])))->count()){
                    return array("status" => 0, 'field'=>'organization' , "info" => "卖家名称已存在，请更换！");
                    exit;
                }
            }
            
            $url = U('Member/index');
        }
        // 编辑认证的话跳转到认证用户列表
        if (ACTION_NAME=='realname_edit') {
            $url = U('Member/realname');
        }
        if($data['uid']){
            $data['reg_date'] = time();
            // 更新实名认证操作时间
            if ($data['idcard_check']!=$member->where(array('uid'=>$data['uid']))->getField('idcard_check')) {
                $data['idcard_check_time'] = time();
            }
            if($member->save($data)){
                return array("status" => 1, "info" => "修改成功",'url'=>$url);
                exit;
            }else{
                return array("status" => 0, "info" => "修改失败");
                exit;
            }
        }else{
            if(empty($data['pwd'])){
                return array("status" => 0, "info" => "请输入密码！");
                exit;
            }
            if($member->add($data)){
                return array("status" => 1, "info" => "添加成功",'url'=>U('Member/index'));
                exit;
            }else{
                return array("status" => 0, "info" => "添加失败");
                exit;
            }
        }
    }
    // 保证金充值
    public function recharge_pledge($info){
        $member = M('member');
        $wr = array('uid'=>$info['uid']);
        $wallet = $member->where($wr)->field('account,wallet_pledge,wallet_pledge_freeze')->find();
        $usable = sprintf("%.2f",$wallet['wallet_pledge']-$wallet['wallet_pledge_freeze']);
        switch ($info['act']) {
            case 'add':
                if($member->where(array('uid'=>$info['uid']))->setInc('wallet_pledge',$info['money'])){
                    $pledge_data = array(
                        'order_no'=>createNo('aad'),
                        'uid'=>$info['uid'],
                        'changetype'=>'admin_deposit',
                        'time'=>time(),
                        'annotation'=>$info['remark'],
                        'income'=>$info['money'],
                        'usable'=>$usable+$info['money'],
                        'balance'=>sprintf("%.2f",$wallet['wallet_pledge']+$info['money'])
                        );
                    $ac = '充值';
                    
                }
                break;
            case 'minus':
                if($usable>=$info['money']){ //判断账户资金是否大于要扣除资金
                    if($member->where(array('uid'=>$info['uid']))->setDec('wallet_pledge',$info['money'])){
                        $pledge_data = array(
                            'order_no'=>createNo('ami'),
                            'uid'=>$info['uid'],
                            'changetype'=>'admin_deduct',
                            'time'=>time(),
                            'annotation'=>$info['remark'],
                            'expend'=>$info['money'],
                            'usable'=>$usable-$info['money'],
                            'balance'=>sprintf("%.2f",$wallet['wallet_pledge']-$info['money'])
                            );
                        $ac = '扣除';
                        
                    }
                }else{
                    return array('status' => 0, 'info' => '账户可扣除（可用资金）不足，扣除失败','url'=>U('Member/walletbill',array('wallet'=>'pledge','account'=>$wallet['account'])));
                }
                break;
            case 'freeze':
                if($usable>=$info['money']){ //判断账户资金是否大于要扣除资金
                    if($member->where($wr)->setInc('wallet_pledge_freeze',$info['money'])){
                        $pledge_data = array(
                            'order_no'=>createNo('afr'),
                            'uid'=>$info['uid'],
                            'changetype'=>'admin_freeze',
                            'time'=>time(),
                            'annotation'=>$info['remark'],
                            'expend'=>$info['money'],
                            'usable'=>$usable-$info['money'],
                            'balance'=>$wallet['wallet_pledge']
                            );
                        $ac = '冻结';
                        
                    }
                }else{
                    return array('status' => 0, 'info' => '账户可冻结（可用资金）不足，冻结失败','url'=>U('Member/walletbill',array('wallet'=>'pledge','account'=>$wallet['account'])));
                }
                break;
            case 'unfeeze':
                if($wallet['wallet_pledge_freeze']>=$info['money']){ //判断账户资金是否大于要扣除资金
                    if($member->where($wr)->setDec('wallet_pledge_freeze',$info['money'])){
                        $pledge_data = array(
                            'order_no'=>createNo('auf'),
                            'uid'=>$info['uid'],
                            'changetype'=>'admin_unfreeze',
                            'time'=>time(),
                            'annotation'=>$info['remark'],
                            'income'=>$info['money'],
                            'usable'=>$usable+$info['money'],
                            'balance'=>$wallet['wallet_pledge']
                            );
                        $ac = '解冻';
                    }
                }else{
                    return array('status' => 0, 'info' => '账户可解冻资金不足，解冻失败','url'=>U('Member/walletbill',array('wallet'=>'pledge','account'=>$wallet['account'])));
                }
                break;
            default:
                # code...
                break;
        }
        if ($pledge_data) {
            if (M('member_pledge_bill')->add($pledge_data)) {
                // 提醒通知冻结保证金【
                    // 微信提醒内容
                    $wei_pledge_freeze['tpl'] = 'walletchange';
                    $wei_pledge_freeze['msg']=array(
                        "url"=>U('Home/Member/wallet','','html',true), 
                        "first"=>'您好，管理员后台'.$ac.'余额！',
                        "remark"=>'查看账户记录>>',
                        "keyword"=>array('余额账户',$ac.'余额','管理员'.$ac,$info['money'].'元',$limsum_data['usable'].'元')
                    );
                    // 账户类型，操作类型、操作内容、变动额度、账户余额
                    // 站内信提醒内容
                    $web_pledge_freeze = array(
                        'title'=>'管理员'.$ac,
                        'content'=>'管理员'.$ac.'余额【'.$info['money'].'元】，单号'.$pledge_data['order_no']
                        );
                    // 短信提醒内容
                    $note_pledge_freeze = '管理员'.$ac.'余额【'.$info['money'].'元】，'.'单号'.$pledge_data['order_no'].'，您可以登陆平台查看账户记录。';
                    // 邮箱提醒内容
                    $mail_pledge_freeze['title'] = '管理员'.$ac.'余额【'.$info['money'].'元】';
                    $mail_pledge_freeze['msg'] = '您好：<br/><p>'.'管理员'.$ac.'余额【'.$info['money'].'元】'.'</p><p>您可以<a target="_blank" href="'.U('Home/Member/wallet','','html',true).'">查看账户记录</a></p>';
                    sendRemind($member,M('Member_weixin'),array(),array($info['uid']),$web_pledge_freeze,$wei_pledge_freeze,$note_pledge_freeze,$mail_pledge_freeze,'buy');
                // 提醒通知冻结保证金【
                return array('status' => 1, 'info' => '已成功'.$ac.'账户余额'.$info['money'],'url'=>U('Member/walletbill',array('wallet'=>'pledge','account'=>$wallet['account'])));
            }else{
                return array('status' => 0, 'info' => '更新数据失败，请联系管理员解决！','url'=>U('Member/index'));
            }
        }
    }
    // 信用充值
    public function recharge_limsum($info){
        $member = M('member');
        $wr = array('uid'=>$info['uid']);
        $wallet = $member->where(array('uid'=>$info['uid']))->field('account,wallet_limsum,wallet_limsum_freeze')->find();
        $usable = sprintf("%.2f",$wallet['wallet_limsum']-$wallet['wallet_limsum_freeze']);
        switch ($info['act']) {
            case 'add':
                if($member->where(array('uid'=>$info['uid']))->setInc('wallet_limsum',$info['money'])){
                    $limsum_data = array(
                        'order_no'=>createNo('aad'),
                        'uid'=>$info['uid'],
                        'changetype'=>'admin_deposit',
                        'time'=>time(),
                        'annotation'=>$info['remark'],
                        'income'=>$info['money'],
                        'usable'=>$usable+$info['money'],
                        'balance'=>sprintf("%.2f",$wallet['wallet_limsum']+$info['money'])
                        );
                    $ac = '充值';
                    
                }
                break;
            case 'minus':
                if($usable>=$info['money']){ //判断账户资金是否大于要扣除资金
                    if($member->where(array('uid'=>$info['uid']))->setDec('wallet_limsum',$info['money'])){
                        $limsum_data = array(
                            'order_no'=>createNo('ami'),
                            'uid'=>$info['uid'],
                            'changetype'=>'admin_deduct',
                            'time'=>time(),
                            'annotation'=>$info['remark'],
                            'expend'=>$info['money'],
                            'usable'=>$usable-$info['money'],
                            'balance'=>sprintf("%.2f",$wallet['wallet_limsum']-$info['money'])
                            );
                        $ac = '扣除';
                        
                    }
                }else{
                    return array('status' => 0, 'info' => '账户可扣除（可用资金）不足，扣除失败','url'=>U('Member/walletbill',array('wallet'=>'limsum','account'=>$wallet['account'])));
                }
                
                break;
            case 'freeze':
                if($usable>=$info['money']){ //判断账户资金是否大于要扣除资金
                    if($member->where($wr)->setInc('wallet_limsum_freeze',$info['money'])){
                        $limsum_data = array(
                            'order_no'=>createNo('afr'),
                            'uid'=>$info['uid'],
                            'changetype'=>'admin_freeze',
                            'time'=>time(),
                            'annotation'=>$info['remark'],
                            'expend'=>$info['money'],
                            'usable'=>$usable-$info['money'],
                            'balance'=>$wallet['wallet_limsum']
                            );
                        $ac = '冻结';
                        
                    }
                }else{
                    return array('status' => 0, 'info' => '账户可冻结（可用资金）不足，冻结失败','url'=>U('Member/walletbill',array('wallet'=>'limsum','account'=>$wallet['account'])));
                }
                break;
            case 'unfeeze':
                if($wallet['wallet_limsum_freeze']>=$info['money']){ //判断账户资金是否大于要扣除资金
                    if($member->where($wr)->setDec('wallet_limsum_freeze',$info['money'])){
                        $limsum_data = array(
                            'order_no'=>createNo('auf'),
                            'uid'=>$info['uid'],
                            'changetype'=>'admin_unfreeze',
                            'time'=>time(),
                            'annotation'=>$info['remark'],
                            'income'=>$info['money'],
                            'usable'=>$usable+$info['money'],
                            'balance'=>$wallet['wallet_limsum']
                            );
                        $ac = '解冻';
                        
                    }
                }else{
                    return array('status' => 0, 'info' => '账户可解冻资金不足，解冻失败','url'=>U('Member/walletbill',array('wallet'=>'limsum','account'=>$wallet['account'])));
                }
                break;
            default:
                # code...
                break;
        }
        if ($limsum_data) {
            if (M('member_limsum_bill')->add($limsum_data)) {
                // 提醒通知冻结保证金【
                    // 微信提醒内容
                    $wei_limsum_freeze['tpl'] = 'walletchange';
                    $wei_limsum_freeze['msg']=array(
                        "url"=>U('Home/Member/wallet',array('option'=>'limsum'),'html',true), 
                        "first"=>'您好，管理员后台'.$ac.'信用额度！',
                        "remark"=>'查看账户记录>>',
                        "keyword"=>array('信用额度账户',$ac.'信用额度','管理员'.$ac,$info['money'].'元',$limsum_data['usable'].'元')
                    );
                    // 账户类型，操作类型、操作内容、变动额度、账户信用额度
                    // 站内信提醒内容
                    $web_limsum_freeze = array(
                        'title'=>'管理员'.$ac,
                        'content'=>'管理员'.$ac.'信用额度【'.$info['money'].'元】，单号'.$limsum_data['order_no']
                        );
                    // 短信提醒内容
                    $note_limsum_freeze = '管理员'.$ac.'信用额度【'.$info['money'].'元】，'.'单号'.$limsum_data['order_no'].'，您可以登陆平台查看账户记录。';
                    // 邮箱提醒内容
                    $mail_limsum_freeze['title'] = '管理员'.$ac.'信用额度【'.$info['money'].'元】';
                    $mail_limsum_freeze['msg'] = '您好：<br/><p>'.'管理员'.$ac.'信用额度【'.$info['money'].'元】'.'</p><p>您可以<a target="_blank" href="'.U('Home/Member/wallet',array('option'=>'limsum'),'html',true).'">查看账户记录</a></p>';
                    sendRemind($member,M('Member_weixin'),array(),array($info['uid']),$web_limsum_freeze,$wei_limsum_freeze,$note_limsum_freeze,$mail_limsum_freeze,'buy');
                // 提醒通知冻结保证金【
                return array('status' => 1, 'info' => '已成功'.$ac.'信誉额度'.$info['money'],'url'=>U('Member/walletbill',array('wallet'=>'limsum','account'=>$wallet['account'])));
            }else{
                return array('status' => 0, 'info' => '更新数据失败，请联系管理员解决！','url'=>U('Member/index'));
            }
        }
    }
}
?>
