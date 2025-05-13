<?php
namespace Home\Model;
use Think\Model\ViewModel;
class GoodsModel extends ViewModel {
    /**
     * [listGoods description]
     * @param  integer $firstRow [分页起始]
     * @param  integer $listRows [分页结束]
     * @param  [type]  $where    [筛选条件]
     * @return [type]            [商品列表]
     */
    public function listGoods($firstRow = 0, $listRows = 20, $where) {
        $M = M("Goods");
        $member=M('member');
        $auction = M('Auction');
        $list = $M->order("`published` DESC")->limit($firstRow.','.$listRows)->where($where)->select();
        $aidArr = M("Admin")->field("`aid`,`email`,`nickname`")->select();
        foreach ($aidArr as $k => $v) {
            $aids[$v['aid']] = $v;
        }
        unset($aidArr);
        $cate = M("Goods_category")->select();
        $cids = array_reduce($cate, create_function('$v,$w', '$v[$w["cid"]]=$w["name"];return $v;'));
        $cat = new \Org\Util\Category();
        foreach ($list as $k => $v) {
            $list[$k]['cidName'] = $cids[$v['cid']];
            $uPath = $cat->getPath($cate,$v['cid']);
            $list[$k]['pidName'] = $uPath[0]['name'];
            // 拍品统计
            $list[$k]['bidcount'] = $auction->where(array('gid'=>$v['id']))->count();
            // 属性中文显示
            $list[$k]['filtrate'] = filtrateToch($v['filtrate'],'_');
        }
        return $list;
    }
    
    /**
     * 添加编辑商品
     */
    public function addEdit($act,$sellerid,$pid=0) {
        $M = M("Goods");
        $data = $_POST['data'];
        $data['sellerid'] = $sellerid;
        if (!$data['cid']) {
            $data['cid'] = $data['pid'];
        }
        unset($data['pid']);
        // 如果筛选条件为空  设为该分类下不限筛选条件
        if(!$data['filtrate']){
            $data['filtrate'] = getTopField($data['cid']);
        }
        $e_data=I('post.extend');
        $data['pictures'] = implode('|', I('post.pic'));//组合上传图片字段
        if($act=='add'){
            $data['published'] = time();
            $suc = $M->add($data);
            $gid = $suc;
            $msg = '添加';
        }else{
            // 如果上传视频为空则设置为空
            if (!$data['viedo']) {$data['viedo']='';}
            $data['update_time'] = time();
            $suc=$M->save($data);
            $gid = $data['id'];
            $msg = '编辑';
        }
        if ($suc) {
            $goods_fields = M('Goods_fields');
            if($act=='add'){
                foreach ($e_data as $edk => $edv) {
                    $goods_fields->data(array('gid'=>$gid,'eid'=>$edk,'default'=>$edv))->add();
                }
            }else{
                foreach ($e_data as $edk => $edv) {
                    $edataArr = array('gid'=>$data['id'],'eid'=>$edk);
                    // 判断是否有该值，进行添加或修改
                    if($goods_fields->where($edataArr)->count()){
                        $goods_fields->where($edataArr)->setField('default',$edv);
                    }else{
                        $edataArr['default']=$edv;
                        $goods_fields->add($edataArr);
                    }
                }
            }
            if($pid){
                $url = U('Seller/auction_list',array('pid'=>$pid));
            }else{
                if(I('post.gto')!=''){
                    $url = U('Seller/auction_add',array('to'=>'js','gid'=>$gid));
                    $msgA = '进入发布拍卖！';
                }else{
                    $url = U('Seller/goods_list');
                    $msgA = '';
                }
            }
            
            return array('status' => 1, 'info' => $msg."成功".$msgA, 'url' => $url);
        }elseif ($suc === 0) {
            return array('status' => 1, 'info' => "未做修改", 'url' => $url);
        } else {
            return array('status' => 0, 'info' => $msg."失败，请刷新页面尝试操作");
        }
    }
    /**
     * 分类操作
     * @return [type] [分类结构]
     */
    public function category() {
        if (IS_POST) {
            $act = $_POST[act];
            $data = $_POST['data'];
            $data['name'] =addslashes($data['name']) ;
            $nameArr = explode(',', addslashes($data['name'])) ;
            $M = M("Goods_category");
            if ($act == "add") { //添加分类
                foreach ($nameArr as $nk => $nv) {
                        if($nv !=''){
                          $newData = array(
                            'pid'=>$data['pid'],
                            'name'=>$nv
                            );
                        if ($M->where($newData)->count() == 0) {
                            $newData['ico']=$data['ico'];
                            ($M->add($newData)) ? $successName .=$nv.',': $errorName .= $nv.',';
                        } else {
                            $reName .= $nv.',';
                        }  
                    }
                }
                if($successName !=''){
                    $info = $successName.'已经成功添加到系统中<br/>';
                    if($errorName !='') {
                        $info .= $errorName.'添加失败<br/>';
                    }elseif($reName !=''){
                       $info .= $reName.'已存在并跳过<br/>' ;
                    }
                    return array('status' => 1, 'info' => $info, 'url' => U('Goods/category', array('time' => time())));
                }else{
                    if($errorName !='') {
                        $info .= $errorName.'添加失败<br/>';
                    }elseif($reName !=''){
                       $info .= $reName.'已存在并跳过<br/>';
                    }
                    return array('status' => 0, 'info' => $info );
                }
                
            } else if ($act == "edit") { //修改分类
                if (empty($data['name'])) {
                    unset($data['name']);
                }
                if ($data['pid'] == $data['cid']) {
                    unset($data['pid']);
                }
                return ($M->save($data)) ? array('status' => 1, 'info' => '分类 ' . $data['name'] . ' 已经成功更新', 'url' => U('Goods/category', array('time' => time()))) : array('status' => 0, 'info' => '分类 ' . $data['name'] . ' 更新失败');
            } else if ($act == "del") { //删除分类
                unset($data['pid'], $data['name']);
                return ($M->where($data)->delete()) ? array('status' => 1, 'info' => '分类 ' . $data['name'] . ' 已经成功删除', 'url' => U('Goods/category', array('time' => time()))) : array('status' => 0, 'info' => '分类 ' . $data['name'] . ' 删除失败');
            }
        } else {
            $cat = new \Org\Util\Category();
            return $cat->getList(M('Goods_category'));
        }
    }
}
?>
