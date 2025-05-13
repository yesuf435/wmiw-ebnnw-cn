<?php
namespace Admin\Controller;
use Think\Controller;
class OrderController extends CommonController {
    /**
     * 有效订单
     * @return [type] [description]
     */
    public function index() {
        $where = array();
        if (I('get.field')) {
            $where[I('get.field')] = I('get.val');
        }
        $this->where = $where;
        // 网站发布条件
        $data = D('GoodsOrder')->listOrder($where);

        $this->mct = $data['mct'];
        $this->list = $data['list'];
        $this->moneysum = $data['moneysum'];
        $this->page = $data['page'];
        $this->display(); 
    }
    /**
     * 订单搜索
     * @return [type] [description]
     */
    public function search(){
        $keys = I('get.');
        $where = array();
        if(!empty($keys['start_time']) && !empty($keys['end_time'])){
            $where['time'] = array(array('egt',strtotime($keys['start_time'])),array('elt',strtotime($keys['end_time'])), 'and');
        }elseif(!empty($keys['start_time'])){
            $where['time'] = array('elt',strtotime($keys['end_time']));
        }elseif(!empty($keys['end_time'])){
            $where['time'] = array('egt',strtotime($keys['start_time']));
        }
        if($keys['account']!=''){
            $uidarr = M('member')->where(array('account'=>array('LIKE', '%' . $keys['account'] . '%')))->getField('uid',true);
            $where['uid'] = array('in',$uidarr);
        }
        if($keys['sel_account']!=''){
            $uidarr = M('member')->where(array('account'=>array('LIKE', '%' . $keys['sel_account'] . '%')))->getField('uid',true);
            $where['sellerid'] = array('in',$uidarr);
        }
        // 订单号
        if($keys['order_no']!=''){
            $where['order_no']=array('LIKE', '%' . $keys['order_no'] . '%');
        }
        // 类型
        if($keys['type']!=''){
            $where['type']=$keys['type'];
        }
        // 状态
        if($keys['status']!=''){
            $where['status']=$keys['status'];
        }
        $data = D('GoodsOrder')->listOrder($where);
        $this->mct = $data['mct'];
        $this->list = $data['list'];
        $this->moneysum = $data['moneysum'];
        $this->page = $data['page'];
        $this->keys = $keys;
        $this->display('index');
            
    }
    // 订单配置
    public function set_order(){
        if (IS_POST) {
            $this->checkToken();
            $data['Order'] = I('post.order');
            if (set_config("SetOrder", $data, APP_PATH . "Common/Conf/")) {
                delDirAndFile(WEB_CACHE_PATH . "Cache/Admin/");
                echojson(array('status' => 1, 'info' => '设置成功','url'=>U('Order/set_order')));
            } else {
                echojson(array('status' => 0, 'info' => '设置失败，请检查','url'=>U('Order/set_order')));
            }
        } else {
            $this->order=C('Order');
            $this->display();
        }
    }
    // 订单删除
    public function del() {
        if (M("Goods_order")->where(array('order_no'=>$_GET['order_no']))->delete()) {
            $this->success("成功删除");
        } else {
            $this->error("删除失败，可能是不存在该订单号的记录");
        }
    }


}