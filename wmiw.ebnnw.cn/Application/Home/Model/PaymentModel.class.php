<?php
namespace Home\Model;
use Think\Model\ViewModel;
class PaymentModel extends ViewModel {
    // 余额支付
    public function yuepaySend($ainfo,$money,$order_no,$uid){
        $member = M('Member');
        $wallet = $member->where(array('uid'=>$uid))->field('wallet_pledge,wallet_pledge_freeze')->find();
        $usable = $wallet['wallet_pledge']-$wallet['wallet_pledge_freeze'];
        if ($wallet['wallet_pledge']<$money) {
            return array('status'=>0,'info'=>'账户余额有误，请联系管理员！');
            exit;
        }
        if($member->where(array('uid'=>$uid))->setDec('wallet_pledge',$money)){
            $mod = '商品：“<a href="'.U('Home/Auction/details',array('pid'=>$ainfo['pid'],'aptitude'=>1)).'">'.$ainfo['pname'].'</a>”';
            $omode = '订单号：“<a href="'.U('Home/Member/order_details',array('order_no'=>$order_no,'aptitude'=>1)).'">'.$order_no.'</a>”';
            $pledge_data = array(
                'order_no'=>createNo('yef'),
                'uid'=>$uid,
                'changetype'=>'pay_deduct',
                'time'=>time(),
                'annotation'=>'支付'.$mod.$omode.'，支付成功！',
                'expend'=>$money,
                'usable'=>sprintf("%.2f",$usable-$money),
                'balance'=>sprintf("%.2f",$wallet['wallet_pledge']-$money)
                );
            //写入用户账户记录
            if(M('member_pledge_bill')->add($pledge_data)){
            // 提醒通知支付成功【
                // 微信提醒内容
                $wei_yupay['tpl'] = 'walletchange';
                $wei_yupay['msg']=array(
                    "url"=>U('Member/wallet','','html',true), 
                    "first"=>"您好，".'使用【账户余额】支付拍卖订单成功！',
                    "remark"=>'查看账户记录>>',
                    "keyword"=>array('余额账户','支付拍品订单扣除余额','商品订单:'.$order_no,'-'.$money.'元',$pledge_data['usable'].'元')
                );
                // 账户类型，操作类型、操作内容、变动额度、账户余额
                // 站内信提醒内容
                $web_yupay = array(
                    'title'=>'支付订单',
                    'content'=>'支付'.$mod.$omode.'扣除余额'.$money.'元'
                    );
                // 短信提醒内容
                if(mb_strlen($ainfo['pname'],'utf-8')>10){
                    $newname = mb_substr($ainfo['pname'],0,10,'utf-8').'...';
                }else{
                    $newname = $ainfo['pname'];
                }
                $note_yupay = '使用余额支付商品“'.$newname.'”订单号'.$order_no.'扣除【'.$money.'元】，账户可用余额【'.$pledge_data['usable'].'元】您可以登陆平台查看账户记录。';
                // 邮箱提醒内容
                $mail_yupay['title'] = '支付订单成功';
                $mail_yupay['msg'] = '您好：<br/><p>'.$pledge_data['annotation'].'</p><p>您可以<a target="_blank" href="'.U('Home/Member/wallet','','html',true).'">查看账户记录</a></p>';
                sendRemind($member,M('Member_weixin'),array(),array($uid),$web_yupay,$wei_yupay,$note_yupay,$mail_yupay,'buy');
                
                return array('status'=>1,'info'=>'支付成功！');
            // 提醒通知支付成功【
            }else{
                return array('status'=>0,'info'=>'写入余额记录失败，请联系管理员！');
            }
        }else{
            return array('status'=>0,'info'=>'扣除余额失败，请联系管理员！');
        }
    }
    // 保证金抵货款支付
    public function pledgepaySend($bidinfo,$pledge,$order_no,$uid){
        $member = M('Member');
        // 获取支付拍品保证金金额
        $regp = getpledge($bidinfo);
        $wallet = $member->where(array('uid'=>$uid))->field('wallet_pledge,wallet_pledge_freeze')->find();
        $usable = $wallet['wallet_pledge']-$wallet['wallet_pledge_freeze'];
        if ($wallet['wallet_pledge_freeze']<$pledge) {
            return array('status'=>0,'info'=>'冻结的余额有误，请联系管理员！');
            exit;
        }
        if ($wallet['wallet_pledge']<$pledge) {
            return array('status'=>0,'info'=>'账户余额有误，请联系管理员！');
            exit;
        }
        // 扣除保证金
        if($member->where(array('uid'=>$uid))->setDec('wallet_pledge_freeze',$pledge)){
            // 扣除保证金
            if($member->where(array('uid'=>$uid))->setDec('wallet_pledge',$pledge)){
                $mod = '商品：“<a href="'.U('Home/Auction/details',array('pid'=>$bidinfo['pid'],'aptitude'=>1)).'">'.$bidinfo['pname'].'</a>”';
                $omode = '订单号：“<a href="'.U('Home/Member/order_details',array('order_no'=>$order_no,'aptitude'=>1)).'">'.$order_no.'</a>”';
                // 变动方式changetype 竞拍冻结bid_freeze 竞拍解冻bid_unfreeze 后台充值admin_deposit 管理员扣除 admin_deduct 支付充值pay_deposit 支付扣除pay_deduct  提现extract  
                $pledge_data = array(
                    'order_no'=>createNo('dhk'),
                    'uid'=>$uid,
                    'changetype'=>'pay_pledge',
                    'time'=>time(),
                    'annotation'=>$regp['ptype'].'抵'.$mod.'货款【'.$pledge.'元】！'.$omode,
                    'expend'=>$pledge,
                    'usable'=>$usable,
                    'balance'=>sprintf("%.2f",$wallet['wallet_pledge']-$pledge)
                    );
                //写入用户账户记录
                if(M('member_pledge_bill')->add($pledge_data)){
                    // 提醒通知保证金抵货款成功【
                        // 微信提醒内容
                        $wei_pledgepay['tpl'] = 'walletchange';
                        $wei_pledgepay['msg']=array(
                            "url"=>U('Member/wallet','','html',true), 
                            "first"=>'您好，【'.$regp['ptype'].'抵货款】抵款成功！',
                            "remark"=>'查看账户记录>>',
                            "keyword"=>array('冻结的保证金',$regp['ptype'].'抵货款','商品订单:'.$order_no,'-'.$pledge.'元',$pledge_data['usable'].'元')
                        );
                        // 账户类型，操作类型、操作内容、变动额度、账户余额
                        // 站内信提醒内容
                        $web_pledgepay = array(
                            'title'=>$regp['ptype'].'抵货款',
                            'content'=>$pledge_data['annotation']
                            );
                        // 短信提醒内容
                        if(mb_strlen($bidinfo['pname'],'utf-8')>10){
                            $newname = mb_substr($bidinfo['pname'],0,10,'utf-8').'...';
                        }else{
                            $newname = $bidinfo['pname'];
                        }
                        $note_pledgepay = '保证金抵商品“'.$newname.'”货款【'.$pledge.'元】，订单号'.$order_no.'货款，您可以登陆平台查看账户记录。';
                        // 邮箱提醒内容
                        $mail_pledgepay['title'] = $regp['ptype'].'抵货款';
                        $mail_pledgepay['msg'] = '您好：<br/><p>'.$pledge_data['annotation'].'</p><p>您可以<a target="_blank" href="'.U('Home/Member/wallet','','html',true).'">查看账户记录</a></p>';

                        sendRemind($member,M('Member_weixin'),array(),array($uid),$web_pledgepay,$wei_pledgepay,$note_pledgepay,$mail_pledgepay,'buy');
                    // 提醒通知保证金抵货款成功【
                        M('goods_user')->where($regp['where'])->setDec('pledge',$pledge);
                        return array('status'=>1,'info'=>'抵货款成功');
                }else{
                    return array('status'=>0,'info'=>'写入余额记录失败，请联系管理员！');
                }
            }else{
                return array('status'=>0,'info'=>'扣除余额失败，请联系管理员！');
            }
        }else{
            return array('status'=>0,'info'=>'扣除冻结的余额失败，请联系管理员！');
        }
    }
    // 余额支付
    public function onlinepaySend(){

    }
}
?>
