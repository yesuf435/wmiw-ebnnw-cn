<?php
namespace Admin\Controller;
use Think\Controller;
class AdvertisingController extends CommonController {
//=========================广告操作=============================
    // 广告列表
    public function index() {
        $Advertising = M('Advertising');
        $Position = M('Advertising_position');
        $field = array('id','name');
        $search = $Position->field($field)->select();

        $this->assign('search',$search);
        $count = $Advertising->count();
        $pConf = page($count,C('PAGE_SIZE'));
        $this->page = $pConf['show'];
        $this->list = D('Advertising')->listAdvertising($pConf['first'],$pConf['list']);
        C('TOKEN_ON',false);
        $this->display('advertising');
    }
    //添加广告
    public function add_advertising(){
        if (IS_POST) {
            $this->checkToken();
            echojson(D("Advertising")->addAdvertising());
        } else {
            $this->posName = D("Advertising")->getPosName();
            $this->display();
        }
    }
    // 编辑广告
    public function edit_advertising() {
        $M = M('Advertising');
        if (IS_POST) {
            $this->checkToken();
            echojson(D("Advertising")->editAdvertising());
        } else {
             $info = $M->where("id=" . (int) $_GET['id'])->find();
            if ($info['id'] == '') {
                $this->error("不存在该记录");
            }
            $this->posName = D("Advertising")->getPosName();
            $this->assign("info", $info);
            $this->display('add_advertising');
        }
    }
    //删除广告
    public function del_advertising() {
        if (M("Advertising")->where("id=" . (int) $_GET['id'])->delete()) {
            $this->success("成功删除");
            //echojson(array("status"=>1,"info"=>""));
        } else {
            $this->error("删除失败，可能是不存在该ID的记录");
        }
    }
    //广告异步排序
    public function order_advertising() {
        if (IS_POST) {
            $getInfo = I('post.');
            $M = M('Advertising');
            $where=array('id'=>$getInfo['odid']);
            if($getInfo['odType'] == 'rising'){
                if($M->where($where)->setInc('sort')){
                    echojson(array('status'=>'1','msg'=>'排序写入数据库成功'));
                }
            }elseif($getInfo['odType'] == 'drop'){
                if($M->where($where)->setDec('sort')){
                    echojson(array('status'=>'1','msg'=>'排序写入数据库成功'));
                }
            }
        } else {
            echojson(array('status'=>'0','msg'=>'什么情况'));
        }
    }
    //搜索
    public function search(){
            $keyW = I('get.');
            if($keyW['id'] != '') $where['pid'] = $keyW['id'];
            if($keyW['type'] != '') $where['type'] = $keyW['type'];
            //显示广告位
            $Position = M('Advertising_position');
            $field = array('id','name');
            $search = $Position->field($field)->select();
            $this->search = $search;
            //显示条件
            $keyS = array('count' =>$count,'id'=>$keyW['id'],'type' => $keyW['type']);
            $this->keys = $keyS;

            //显示搜索结果
            $D = D("Advertising");
            $count = $D->where($where)->count();
            $pConf = page($count,C('PAGE_SIZE'));
            $this->list = $D->listAdvertising($pConf['first'],$pConf['list'],$where);
            $this->page = $pConf['show'];
            C('TOKEN_ON',false);
            $this->display('advertising');
    }
    //禁用开启广告位
    public function forbid(){
        $getAjx = I('post.');
        $M = M('Advertising');
        $where = array('id' => $getAjx['forAid']);
        if($getAjx['forType']){
            if($M->where($where)->setField('status',0)){
                echojson(array('status'=>'1','type'=>$getAjx['forType'],'msg'=>'禁用成功'));
            }
        }else{
            if($M->where($where)->setField('status',1)){
                echojson(array('status'=>'1','type'=>$getAjx['forType'],'msg'=>'恢复成功'));
            }
        }
        

    }
//==========================广告位操作============================

    // 广告位列表
    public function position() {
        $M = M('Advertising_position');
        $count = $M->count();
        $pConf = page($count,C('PAGE_SIZE'));
        $this->page = $pConf['show'];
        $this->list = D('Advertising')->listPosition($pConf['first'], $pConf['list']);
        C('TOKEN_ON',false);
        $this->display();
    }
    
    // 添加广告位
    public function add_position(){
        if (IS_POST) {
            $this->checkToken();
            echojson(D("Advertising")->addPosition());
        } else {
            $this->display();
        }
    }
    // 编辑广告位
    public function edit_position() {
        $M = M('advertising_position');
        if (IS_POST) {
            $this->checkToken();
            echojson(D("Advertising")->editPosition());
        } else {
             $info = $M->where("id=" . (int) $_GET['id'])->find();
            if ($info['id'] == '') {
                $this->error("不存在该记录");
            }
            $this->assign("info", $info);
            $this->display('add_position');
        }
    }
    //删除广告位
    public function del_position() {
        if (M("advertising_position")->where("id=" . (int) $_GET['id'])->delete()) {
            $this->success("成功删除");
            //echojson(array("status"=>1,"info"=>""));
        } else {
            $this->error("删除失败，可能是不存在该ID的记录");
        }
    }
        //异步删除广告图片
    public function del_pic() {
        $imgUrl = I('post.imgUrl');
        $imgDelUrl = C('UPLOADS_PICPATH').I('post.imgUrl'); //要删除图片地址
        $advId = I('post.advId');
        $M = M('Advertising');
        $data = array(
            'id' => $advId,
            'code' =>''
        );
        if($advId){
            if($M->save($data)){
                if(@unlink($imgDelUrl)){
                    echojson(array(
                    'status' => 1,
                    'msg' => '已从数据库删除成功!'
                    ));
                }else{
                    echojson(array(
                    'status' => 0,
                    'msg' => '删除失败，刷新页面重试!'
                    ));
                }
            }
        }else{
            if(@unlink($imgDelUrl)){
                echojson(array(
                'status' => 1,
                'msg' => '已从磁盘删除成功!'
                ));
            }else{
                echojson(array(
                'status' => 0,
                'msg' => '磁盘文件删除失败，请检查文件是否存在或磁盘权限!'
                ));
            }
            
        }
    }
}