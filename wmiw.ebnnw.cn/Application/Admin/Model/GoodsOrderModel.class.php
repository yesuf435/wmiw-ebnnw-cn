<?php
namespace Admin\Model;
use Think\Model\ViewModel;
class GoodsOrderModel extends ViewModel {
    Protected $viewFields = array(
        'Goods_order' => array(
            '*',
            '_type' => 'LEFT'
            ),
        'Goods_order_return' => array(
            'cause','money','`explain`','`evidence`','selcause',
            'express'=>'sel_express','express_other'=>'sel_express_other','express_no'=>'sel_express_no','address'=>'sel_address',
            'mediate','defmediate','mediate_time',
            'time5','time6','deftime6','deftime6st','time7','time8','deftime8','deftime8st','time9','deftime9','deftime9st',
            '_on' => 'Goods_order.order_no = Goods_order_return.order_no',
            '_type' => 'LEFT'
            ),
        'Auction' => array(
            'pid','sid','mid','bidnb','type','pname','onset','price'=>'retain','nowprice','pattern','pledge_type','pledge','starttime','endtime','endstatus',
            '_on' => 'Auction.pid = Goods_order.gid',
            '_type' => 'LEFT'
            ),
        'Goods' => array(
            'cid','pictures',
            '_on' => 'Auction.gid = Goods.id',
            '_type' => 'LEFT'
            ),
        'Member' => array(
            'uid'=>'sellerid','account'=>'sel_account','organization',
            '_on' => 'Goods_order.sellerid = Member.uid',
            '_type' => 'LEFT'
            ),
        'Bmember' => array(
            'nickname','account'=>'buy_account',
            '_table'=>'on_member',
            '_on' => 'Goods_order.uid = Bmember.uid',
            '_type' => 'LEFT'
            )
    );
    public function listOrder($where) {
        $special = M('special_auction');
        $seller_pledge = M('seller_pledge');
        $goods_user = M('goods_user');
        $seller_pledge_type = C('Auction.seller_pledge_type');
        $count = $this->where($where)->count();
        $pConf = page($count,C('PAGE_SIZE'));
        $list = $this->where($where)->limit($pConf['first'].','.$pConf['list'])->order('time desc')->select();
        // 分配订单金额
        $mct['odmn']= $this->where($where)->sum('price');
        // 分配佣金金额
        $mct['bkmn'] = $this->where($where)->sum('broker');
        foreach ($list as $lk => $lv) {
            $list[$lk]['prcsum'] = $lv['price']+$lv['freight']+$lv['broker_buy'];
            // 卖家保证金
            $thesell = $seller_pledge->where(array('sellerid'=>$lv['sellerid'],'pid'=>$lv['gid']))->find();
            if(!$thesell){
                $thesell = $seller_pledge->where(array('sellerid'=>$lv['sellerid'],'type'=>'disposable'))->find();
            }
            $list[$lk]['sell_type'] = sellpledgetype($thesell['type']);
            $list[$lk]['sell_pledge'] = $thesell['pledge'];
            $list[$lk]['sell_limsum'] = $thesell['limsum'];
            $list[$lk]['sell_freeze'] = $thesell['status'];
            // 买家保证金缴纳
            if($lv['sid']){
                $sinfo = $special->where(array('sid'=>$sid))->find();
                if($sinfo['special_pledge_type']==0){
                    $g_uW = array('g-u'=>'s-u','gid'=>$sid,'uid'=>$lv['uid']);
                    $pledge_type = '专场[专场缴纳]';
                }else{
                    $g_uW = array('g-u'=>'p-u','gid'=>$lv['gid'],'uid'=>$lv['uid']);
                    $pledge_type = '专场[单品缴纳]';
                }
            }else{
                $g_uW = array('g-u'=>'p-u','gid'=>$lv['gid'],'uid'=>$lv['uid']);
                $pledge_type = '单品缴纳';
            }
            $g_uInfo = $goods_user->where($g_uW)->field('pledge,limsum')->find();
            $list[$lk]['pledge_type'] = $pledge_type;
            $list[$lk]['pledge'] = floatval($g_uInfo['pledge']);
            $list[$lk]['limsum'] = floatval($g_uInfo['limsum']);
            $picarr = explode('|',$lv['pictures']);
            $list[$lk]['pimg'] = $picarr[0];
        }
        $order = M('goods_order');
        // 指定条件订单总额
        $moneysum['price'] = $order->where($where)->sum('price');
        $moneysum['freight'] = $order->where($where)->sum('freight');
        $moneysum['total'] = $moneysum['price']+$moneysum['freight'];
        // 指定条件佣金
        $moneysum['broker'] = $order->where($where)->sum('broker');
        // 指定条件手续费
        $moneysum['broker_buy'] = $order->where($where)->sum('broker_buy');
        return $data = array('list'=>$list,'moneysum'=>$moneysum,'page'=>$pConf['show'],'mct'=>$mct);
    }
}

?>
