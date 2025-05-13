<?php
namespace Admin\Model;
use Think\Model\ViewModel;
class AdvertisingModel extends ViewModel {

    Protected $viewFields = array(
        'Advertising' => array(
            'id', 'name','type','status','sort',
            '_type' => 'LEFT'
            ),
        'Advertising_position' => array(
            'name' => 'position','id'=>'pid',
            '_on' => 'Advertising.pid = Advertising_position.id'
            )
    );
    //广告列表
    public function listAdvertising($firstRow = 0, $listRows = 20,$where = array()){
        $list = $this->order("`pid` DESC , `sort` DESC")->where($where)->limit("$firstRow , $listRows")->select();
        return $list;
    }
    // 显示广告位
    public function listPosition($firstRow = 0, $listRows = 20){
        $M = M('Advertising_position');
        $field = array('id','tagname','name','width','height');
        $list = $M->field($field)->order("`id` DESC")->limit("$firstRow , $listRows")->select();
        return $list;
    }
    // 添加广告
    public function addAdvertising() {
        $M = M("Advertising");
        $data = I('post.info');
        $data['desc'] = str_replace(array("\r\n", "\r", "\n"), "", $data['desc']);
        $data['code'] = str_replace(array("\r\n", "\r", "\n"), "", $data['code']);
        $data['adv_start_time'] = strtotime($data['adv_start_time']);
        $data['adv_end_time'] = strtotime($data['adv_end_time']);
        
        if ($M->add($data)) {
            return array('status' => 1, 'info' => "已经添加", 'url' => U('Advertising/index'));
        } else {
            return array('status' => 0, 'info' => "发布失败，请刷新页面尝试操作",'url' => U('Advertising/add_advertising'));
        }
    }
    // 编辑广告
    public function editAdvertising() {
        $M = M("Advertising");
        $data = I('post.info');
        $data['desc'] = str_replace(array("\r\n", "\r", "\n"), "", $data['desc']);
        $data['code'] = str_replace(array("\r\n", "\r", "\n"), "", $data['code']);
        $data['adv_start_time'] = strtotime($data['adv_start_time']);
        $data['adv_end_time'] = strtotime($data['adv_end_time']);
        
        if ($M->save($data)) {
            return array('status' => 1, 'info' => "已经更新", 'url' => U('Advertising/index'));
        } else {
            return array('status' => 0, 'info' => "更新失败，请刷新页面尝试操作",'url' => U('Advertising/edit_advertising',array('id'=>$data['id'])));
        }
    }

    //添加广告位调用
    public function addPosition() {
        $M = M("Advertising_position");
        $data = $_POST['info'];
        if ($M->add($data)) {
            return array('status' => 1, 'info' => "已经添加", 'url' => U('Advertising/position'));
        } else {
            return array('status' => 0, 'info' => "发布失败，请刷新页面尝试操作", 'url' => U('Advertising/add_position'));
        }
    }
    //编辑广告位调用
    public function editPosition() {
        $M = M("Advertising_position");
        $data = $_POST['info'];
        if ($M->save($data)) {
            return array('status' => 1, 'info' => "已经更新", 'url' => U('Advertising/position'));
        } else {
            return array('status' => 0, 'info' => "更新失败，请刷新页面尝试操作", 'url' => U('Advertising/edit_position',array('id'=>$data['id'])));
        }
    }
    //获取广告位名称
    public function getPosName() {
        $M = M('Advertising_position');
        $field = array('id','name','width','height');
        return($M->field($field)->select());
    }


}

?>
