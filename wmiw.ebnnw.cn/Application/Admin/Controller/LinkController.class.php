<?php
namespace Admin\Controller;
use Think\Controller;
class LinkController extends CommonController {
//=========================友情链接操作=============================
    // 友情链接列表
    public function index() {
        $link = M('Link');
        $count = $link->count();
        $pConf = page($count,C('PAGE_SIZE'));
        $this->page = $pConf['show'];
        $this->list = $link->limit($pConf['first'], $pConf['list'])->order("`sort` DESC")->select();
        $this->display();
    }
    /**
     * 搜索
     * @return [type] [description]
     */
    public function search(){
            $keyW = I('get.');
            if($keyW['rec'] != '') $where['rec'] = $keyW['rec'];
            if($keyW['keyword'] != '') $where['name'] = array('LIKE', '%' . $keyW['keyword'] . '%');
            $link = M("Link");
            $count = $link->where($where)->count();
            $pConf = page($count,C('PAGE_SIZE'));
            $keyS = array('count' =>$count,'keyword'=>$keyW['keyword'],'ico' => $keyW['ico']);
            $this->keys = $keyS;
            $this->page = $pConf['show'];
            $this->list = $link->where($where)->limit($pConf['first'], $pConf['list'])->order("`sort` DESC")->select();
            C('TOKEN_ON',false);
            $this->display('index');
    }
    //添加友情链接
    public function add(){
        if (IS_POST) {
            $this->checkToken();
            $data = I('post.info');
            if(M('Link')->add($data)){
                echojson(array('status' => 1, 'info' => '添加成功', 'url' => U('Link/index', array('time' => time()))));
            }else{
                echojson(array('status' => 0, 'info' => '添加失败！请重试'));
            }
        } else {
            $this->display();
        }
    }
    // 编辑友情链接
    public function edit() {
        $M = M('Link');
        if (IS_POST) {
            $this->checkToken();
            $data = I('post.info');
            $rst = M('Link')->save($data);
            if($rst){
                echojson(array('status' => 1, 'info' => '已修改','url'=>__SELF__));
            }else{
                if ($rst==0) {
                    echojson(array('status' => 0, 'info' => '未做修改'));
                }else{
                    echojson(array('status' => 0, 'info' => '修改失败','url'=>__SELF__));
                }
            }
        } else {
            $info = $M->where("id=" . (int) $_GET['id'])->find();
            if ($info['id'] == '') {
                $this->error("不存在该记录");
            }
            $this->assign("info", $info);
            $this->display('add');
        }
    }
    //删除友情链接
    public function del() {
        if (M("Link")->where("id=" . (int) $_GET['id'])->delete()) {
            $this->success("成功删除");
        } else {
            $this->error("删除失败，可能是不存在该ID的记录");
        }
    }
    //友情链接异步排序
    public function sort() {
        if (IS_POST) {
            $getInfo = I('post.');
            $M = M('Link');
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
    //异步删除商品图片
    public function del_pic() {
        $imgUrl = I('post.imgUrl');
        if ($imgUrl) {
            $imgDelUrl = C('UPLOADS_PICPATH').I('post.imgUrl');
        }else{
            $imgDelUrl = C('UPLOADS_PICPATH').I('post.savepath').I('post.savename');
        }
        if(@unlink($imgDelUrl)){
            $thisid = I('post.thisid');
            if($thisid){
                $M = M('Link');
                $data = array('id' => $thisid,'ico' =>'');
                $M->save($data);}
            echojson(array('status' => 1,'msg' => '已从数据库删除成功!'));
        }else{
            echojson(array('status' => 0,'msg' => '删除失败，刷新页面重试!'));
        }
    }
}