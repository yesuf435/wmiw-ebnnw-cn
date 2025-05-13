<?php
namespace Admin\Controller;
use Think\Controller;
class NewsController extends CommonController {
    /**
     * 文章列表
     * @return [type] [description]
     */
    public function index() {
        
        $this->cate = D("News")->category();
        $redata = D("News")->listNews($where,'`published` DESC',C('PAGE_SIZE'));
        $this->list = $redata['list'];
        $this->page = $redata['page'];
        $this->display(); 
    }
    /**
     * 搜索
     * @return [type] [description]
     */
    public function search(){
            $keyW = I('get.');
            if($keyW['cid'] != '') $where['cid'] = $keyW['cid'];
            if($keyW['keyword'] != '') $where['title'] = array('LIKE', '%' . $keyW['keyword'] . '%');
            if($keyW['cid'] != ''){
                $catname = M('Category')->where('cid='.$keyW['cid'])->getField('name');
            }else{
               $catname = '所有'; 
            }
            $keyS = array('count' =>$count,'keyword'=>$keyW['keyword'],'name' => $catname,'cid' => $keyW['cid']);
            $this->keys = $keyS;
            $this->cate = D("News")->category();
            $redata = D("News")->listNews($where,'`published` DESC',C('PAGE_SIZE'));
            $this->list = $redata['list'];
            $this->page = $redata['page'];
            $this->display('index');
    }
    /**
     * 文章分类
     * @return [type] [description]
     */
    public function category() {
        $this->list = D("News")->category();
        $this->display();
    }
    public function category_add() {
        if (IS_POST) {
            echojson(D("News")->category());
        } else {
            $info = M('category')->where(array('cid'=>I('get.cid')))->find();
            $this->list = D("News")->category();
            $info['act']='add';
            $this->info=$info;
            $this->display();
        }
    }
    public function category_edit() {
        if (IS_POST) {
            echojson(D("News")->category());
        } else {
            $info = M('category')->where(array('cid'=>I('get.cid')))->find();
            $this->list = D("News")->category();
            $info['act']='edit';
            $this->info=$info;
            $this->display('category_add');
        }
    }
    // 分类删除
    public function category_del() {
        if (IS_POST) {
            $category = M('category');
            $cat = new \Org\Util\Category();
            $cate = $category->select();
            $cidarr=$cat->getChildsId($cate,I('post.cid'));
            $count = count($cidarr);
            if ($count==0||I('post.yn')==1) {
                $cidarr[]=I('post.cid');
                $where = array('cid'=>array('in',$cidarr));
                $rst = $category->where($where)->delete();
                if ($rst) {
                    echojson(array('status'=>1,'info'=>'已删除'.count($cidarr).'条分类记录！','url'=>U('News/category')));
                }else{
                    echojson(array('status'=>0,'info'=>'删除失败！','url'=>U('News/category')));
                }
            }else{
                echojson(array('status'=>2,'info'=>'该分类下存在子类会一并删除。是否继续？'));
            }
            
        }
    }
    //---分类异步排序
    public function order_category() {
        if (IS_POST) {
            $getInfo = I('post.');

            $M = M('category');
            $where=array('cid'=>$getInfo['odid']);
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
    /**
     * 添加文章
     */
    public function add() {
        if (IS_POST) {
            $this->checkToken();
            echojson(D("News")->addNews($this->cAid));
        } else {
            $this->assign("list", D("News")->category());
            $this->display();
        }
    }
    // 验证title是否重复
    public function checkNewsTitle() {
        $M = M("News");
        $where = "title='" .I('get.title') . "'";
        if (!empty($_GET['id'])) {
            $where.=" And id !=" . (int) $_GET['id'];
        }
        if ($M->where($where)->count() > 0) {
            echojson(array("status" => 0, "info" => "已经存在，请修改标题"));
        } else {
            echojson(array("status" => 1, "info" => "可以使用"));
        }
    }
    /**
     * 编辑文章
     * @return [type] [description]
     */
    public function edit() {
        $M = M("News");
        if (IS_POST) {
            $this->checkToken();
            echojson(D("News")->edit());
        } else {
            $info = $M->where("id=" . (int) $_GET['id'])->find();
            if ($info['id'] == '') {
                $this->error("不存在该记录");
            }
            $info['content']=stripslashes($info['content']);
            $this->assign("info", $info);
            $this->assign("list", D("News")->category());
            $this->display("add");
        }
    }
    // 删除文章
    public function del() {
        if (M("News")->where("id=" . (int) $_GET['id'])->delete()) {
            $this->success("成功删除");
//            echojson(array("status"=>1,"info"=>""));
        } else {
            $this->error("删除失败，可能是不存在该ID的记录");
        }
    }


    //异步删除文章图片
    public function del_pic() {
        $imgUrl = I('post.imgUrl');
        if ($imgUrl) {
            $imgDelUrl = C('UPLOADS_PICPATH').I('post.imgUrl');
        }else{
            $imgDelUrl = C('UPLOADS_PICPATH').I('post.savepath').I('post.savename');
            @unlink(C('UPLOADS_PICPATH').I('post.savepath').'thumb'.I('post.savename'));
        }
        if(@unlink($imgDelUrl)){
            $newsId = I('post.newsId');
            if($newsId){
                $data = array('id' => $newsId,'picture' =>'');
                M('News')->save($data);
            }
            echojson(array('status' => 1,'msg' => '已从数据库删除成功!'));
        }else{
            echojson(array('status' => 0,'msg' => '删除失败，刷新页面重试!'));
        }
    }

}