<?php
namespace Admin\Model;
use Think\Model;
class NewsModel extends Model {
    public function listNews($where,$od = "`published` DESC",$size) {
        $count = $this->where($where)->count();
        $pConf = page($count,$size);
        $this->page = $pConf['show'];
        $list = $this->field("`id`,`title`,`status`,`published`,`cid`,`aid`")->order($od)->limit($pConf['first'].','.$pConf['list'])->where($where)->select();
        $statusArr = array("审核状态", "已发布状态");
        $aidArr = M("Admin")->field("`aid`,`email`,`nickname`")->select();
        foreach ($aidArr as $k => $v) {
            $aids[$v['aid']] = $v;
        }
        unset($aidArr);
        $cidArr = M("Category")->field("`cid`,`name`")->select();
        foreach ($cidArr as $k => $v) {
            $cids[$v['cid']] = $v;
        }
        unset($cidArr);
        foreach ($list as $k => $v) {
            $list[$k]['aidName'] =$aids[$v['aid']]['nickname'] == '' ? $aids[$v['aid']]['email'] : $aids[$v['aid']]['nickname'];
            $list[$k]['status'] = $statusArr[$v['status']];
            $list[$k]['cidName'] = $cids[$v['cid']]['name'];
        }
        return array('list'=>$list,'page'=>$pConf['show'],'count'=>$count);
    }

    public function category() {
        if (IS_POST) {
            $act = I('post.act');
            $data = I('post.data');
            $data['name'] = addslashes($data['name']);
            $M = M("Category");
            $this->cacheNull();
            if ($act == "add") { //添加分类
                unset($data['cid']);
                if ($M->where($data)->count() == 0) {
                    return ($M->add($data)) ? array('status' => 1, 'info' => '分类 ' . $data['name'] . ' 已经成功添加到系统中', 'url' => U('News/category', array('time' => time()))) : array('status' => 0, 'info' => '分类 ' . $data['name'] . ' 添加失败');
                } else {
                    return array('status' => 0, 'info' => '系统中已经存在分类' . $data['name']);
                }
            } else if ($act == "edit") {
                $rst = $M->save($data);
                if($rst){
                    
                    return array('status' => 1, 'info' => '分类 ' . $data['name'] . ' 已经成功更新', 'url' => U('News/category', array('time' => time())));
                }else{
                    if ($rst==0) {
                        return array('status' => 0, 'info' => '未做修改');
                    }else{
                        return array('status' => 0, 'info' => '分类 ' . $data['name'] . ' 更新失败','url'=>U('News/category', array('time' => time())));
                    }
                }
            }
        } else {
            $cat = new \Org\Util\Category();
            return $cat->getList(M('Category'),'cid',0,null,'sort desc');
        }
    }

    public function addNews($aid) {
        $M = M("News");
        $data = $_POST['info'];
        $data['published'] = time();
        $data['update_time'] = $data['published'];
        $data['aid'] = $aid;
        if (empty($data['summary'])) {
            $data['summary'] = cutStr($data['content'], 200);
        }
        if ($M->add($data)) {
            $this->cacheNull();
            return array('status' => 1, 'info' => "已经发布", 'url' => U('News/index'));
        } else {
            return array('status' => 0, 'info' => "发布失败，请刷新页面尝试操作");
        }
    }

    public function edit() {
        $M = M("News");
        $data = $_POST['info'];
        $data['update_time'] = time();
        $rst = $M->save($data);
        if($rst){
            $this->cacheNull();
            return array('status' => 1, 'info' => '已修改','url'=>U('News/index'));
        }else{
            if ($rst==0) {
                return array('status' => 0, 'info' => '未做修改');
            }else{
                return array('status' => 0, 'info' => '修改失败','url'=>U('News/index'));
            }
        }
    }
    // 清除新闻和新闻分类的缓存
    private function cacheNull() {
        S(C('CACHE_FIX').'_newtop',null);
    }

}

?>
