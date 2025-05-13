<?php
namespace Admin\Model;
use Think\Model\ViewModel;
class ConsultationModel extends ViewModel {
    Protected $viewFields = array(
        'Consultation' => array(
            '*',
            '_type' => 'LEFT'
            ),
        'Member' => array(
            'account','organization','nickname',
            '_on' => 'Consultation.uid = Member.uid',
            '_type' => 'LEFT'
            ),
        'Admin' => array(
            'email','nickname'=>'sel_nickname',
            '_on' => 'Consultation.aid = Admin.aid',
            '_type' => 'LEFT'
            ),
    );
    public function listConsultation($where,$od = "`time` DESC",$size) {
        $count = $this->count();
        $pConf = page($count,$size);
        $this->page = $pConf['show'];
        $list = $this->order($od)->limit($pConf['first'].','.$pConf['list'])->where($where)->select();
        $statusArr = array("审核状态", "已发布状态");
        return array('list'=>$list,'page'=>$pConf['show'],'count'=>$count);
    }
}

?>
