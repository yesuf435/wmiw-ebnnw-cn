<?php
namespace Home\Model;
use Think\Model\ViewModel;
class GoodsEvaluateModel extends ViewModel {
    Protected $viewFields = array(
        'Goods_evaluate' => array(
            '*',
            '_type' => 'LEFT'
            ),
        'Auction' => array(
            'pid','pname',
            '_on' => 'Auction.pid = Goods_evaluate.pid',
            '_type' => 'LEFT'
            ),
        'Goods' => array(
            'pictures'=>'goods_pictures','filtrate',
            '_on' => 'Auction.gid = Goods.id',
            '_type' => 'LEFT'
            ),
        'Member' => array(
            'uid'=>'sellerid','organization',
            '_on' => 'Goods_evaluate.sellerid = Member.uid',
            '_type' => 'LEFT'
            ),
        'Bmember' => array(
            'nickname','truename','mobile','verify_mobile',
            '_table'=>'on_member',
            '_on' => 'Goods_evaluate.uid = Bmember.uid',
            '_type' => 'LEFT'
            )
    );
    // 输出列表
    public function listEvaluate($where = array(), $order= 'time desc',$size = 20){
        $count = $this->where($where)->count();
        $pConf = page($count,$size);
        $list = $this->where($where)->order($order)->select();
        foreach ($list as $lk => $lv) {
            $preg = '/'.C('EVIDENCE_PICPATH').'\/\d+?\//is';
            $pictures = explode('|', $lv['pictures']);
            foreach ($pictures as $pk => $pv) {
                preg_match($preg, $pv, $gdPicPath);
                $newpath = $gdPicPath[0].'thumb';
                $nowpicarr[$pk]['thumb']=preg_replace($preg ,$newpath, $pv);
                $nowpicarr[$pk]['maxpic']=$pv;
            }
            $list[$lk]['pictures']=$nowpicarr;
            $list[$lk]['filtrate']=filtrateToch($lv['filtrate'],'_');
        }
        return array('list'=>$list,'page'=>$pConf['show'],'count'=>$count);
    }
    // 输出一条
    public function findOrder($where = array()){
        $express = M('express');
        $info = $this->where($where)->find();
        $info['total'] = $info['price']+$info['freight']+$info['broker_buy'];
        // 判断该字段是否序列化存储
        if (is_serialized($info['remark'])) {
            $info['remark'] = unserialize($info['remark']);
            $info['remark'] = $info['remark'][$info['status']];
        }else{
            $info['remark'] = $info['remark'];
        }
        return $info;
    }
}
?>
