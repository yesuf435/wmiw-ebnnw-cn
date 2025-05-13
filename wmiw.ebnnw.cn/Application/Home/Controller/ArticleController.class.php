<?php
namespace Home\Controller;
use Think\Controller;
class ArticleController extends CommonController {
    /**
     * 帮助页面
     * @return [type] [description]
     */
    public function help(){
        $cate = M("Category");
        $news = M('news');
        $list = $cate->where(array('pid'=>1))->order('sort desc')->select();
        $id = I('get.id');
        if ($id) {
            $info = $news->where(array('id'=>I('get.id')))->find();
        }else{
            $info = $news->where(array('cid'=>$list[0]['cid'],'status'=>1))->find();
        }
        foreach ($list as $lk => $lv) {
            $list[$lk]['notice'] = $news->where(array('cid'=>$lv['cid'],'status'=>1))->field('id,title')->select();
        }
        $this->id=$id;
        $this->info=$info;
        $this->list = $list;
        $this->display();
        
    }
    /**
     * 公告/咨询列表
     * @return [type] [description]
     */
    public function notice(){
        $cid=I('get.cid');
        $news = M('news');
        $cate = M("Category");
        $caname = $cate->where(array('cid'=>2))->getField('name');
        $cbname = $cate->where(array('cid'=>3))->getField('name');

        $count = $news->where('cid = '.$cid)->count();
        $pConf = page($count,C('PAGE_SIZE'));
        $this->page = $pConf['show']; //分页分配
        $where = array('status'=>1,'cid'=>$cid); //文章发布状态公告分类
        $noticemap = $news->where($where)->limit($pConf['first'].','.$pConf['list'])->field('id,title,keywords,description,update_time,picture,published')->order('published desc')->select();
        $this->cname=($cid==2)?$caname:$cbname;
        $this->cid=$cid;
        $this->list=$noticemap;
        $this->caname = $caname;
        $this->cbname = $cbname;
        $this->display();
    }
    public function details(){
        $news = D('News');
        $where = array('id'=>I('get.id'));
        $data = $news->where($where)->find();
        $data['content']=stripslashes($data['content']);
        $list = $news->where(array('cid'=>$data['cid'],'status'=>1))->limit(10)->field('id,title,name')->order('published desc')->select();
        $this->data=$data;
        $this->list=$list;
        $this->display();
    }
}