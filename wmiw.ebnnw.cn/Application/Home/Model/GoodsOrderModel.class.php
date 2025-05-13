<?php
namespace Home\Model;
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
            'cid','pictures','sellerid','filtrate',
            '_on' => 'Auction.gid = Goods.id',
            '_type' => 'LEFT'
            ),
        'Special_auction' => array(
            'sname','spledge',
            '_on' => 'Auction.sid = Special_auction.sid',
            '_type' => 'LEFT'
            ),
        'Meeting_auction' => array(
            'mname','mpledge',
            '_on' => 'Auction.mid = Meeting_auction.mid',
            '_type' => 'LEFT'
            ),
        'Member' => array(
            'account'=>'sel_account','avatar','uid'=>'sellerid','organization',
            '_on' => 'Goods_order.sellerid = Member.uid',
            '_type' => 'LEFT'
            ),
        'Bmember' => array(
            'account','nickname','avatar_sel','mobile','verify_mobile',
            '_table'=>'on_member',
            '_on' => 'Goods_order.uid = Bmember.uid',
            '_type' => 'LEFT'
            )
    );
    // 输出列表
    public function listOrder($where = array(), $order= 'time desc',$size = 20){
        $count = $this->limit($pConf['first'].','.$pConf['list'])->where($where)->count();
        $pConf = page($count,$size);
        $express = M('express');
        $list = $this->where($where)->limit($pConf['first'].','.$pConf['list'])->order($order)->select();
        foreach ($list as $k => $v) {
            $list[$k]['total'] = $v['price']+$v['freight']+$v['broker_buy'];
            if ($info['rstatus']==1) {
                // 卖家快递
                if($list[$k]['sel_express']!=''){
                    $list[$k]['sel_express']= $express->where(array('en'=>$list[$k]['sel_express']))->getField('ch');
                }else{
                    $list[$k]['sel_express'] = $list[$k]['sel_express_other'];
                }
            }else{
                if($list[$k]['express']!=''){
                    $list[$k]['express']= $express->where(array('en'=>$list[$k]['express']))->getField('ch');
                }else{
                    $list[$k]['express'] = $list[$k]['express_other'];
                }
            }
            // 判断该字段是否序列化存储
            if (is_serialized($v['remark'])) {
                $v['remark'] = unserialize($v['remark']);
                $list[$k]['remark'] = $v['remark'][$v['status']];
            }else{
                $list[$k]['remark'] = $v['remark'];
            }
        }
        return array('list'=>$list,'page'=>$pConf['show'],'count'=>$count);
    }
    // 输出一条
    public function findOrder($where = array()){
        $express = M('express');
        $info = $this->where($where)->find();
        if ($info) {
            $info['total'] = $info['price']+$info['freight']+$info['broker_buy'];
            // 判断该字段是否序列化存储
            if (is_serialized($info['remark'])) {
                $info['remark'] = unserialize($info['remark']);
                $info['remark'] = $info['remark'][$info['status']];
            }else{
                $info['remark'] = $info['remark'];
            }
            // 退货流程
            if ($info['rstatus']==1) {
                // 卖家地址
                $info['sel_address']=unserialize($info['sel_address']);
                // 卖家快递
                if($info['sel_express']!=''){
                    $info['sel_express_ch']= getExpressCh($info['sel_express']);
                }else{
                    $info['sel_express_ch'] = $info['sel_express_other'];
                }
                // 快递查询
                if(in_array($info['status'], array(8,9))){
                    $info['sel_exparr'] = getExpress($info['sel_express'],$info['sel_express_no']);
                }
                if ($info['evidence']!='') {
                    $info['evidence'] = explode('|', $info['evidence']);
                }
            // 收货流程
            }else{
                // 买家地址
                $info['address']=unserialize($info['address']);
               // 买家快递
                if($info['express']!=''){
                    $info['express_ch']= getExpressCh($info['express']);
                }else{
                    $info['express_ch'] = $info['express_other'];
                } 
                // 快递查询
                if(in_array($info['status'], array(2,3))){
                    $info['exparr'] = getExpress($info['express'],$info['express_no']);
                }
            }
            return $info;
        }
    }
}
?>
