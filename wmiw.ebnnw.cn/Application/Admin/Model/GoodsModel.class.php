<?php
namespace Admin\Model;
use Think\Model\ViewModel;
class GoodsModel extends ViewModel {
    Protected $viewFields = array(
        'Goods' => array(
            'id','cid','aid','title','filtrate','price','pictures','prov','city','district','sellerid','published','description','content',
            '_type' => 'LEFT'
            ),
        'Member' => array(
            'account','organization','intro','score',
            '_on' => 'Goods.sellerid = Member.uid',
            '_type' => 'LEFT'
            )
    );
    /**
     * [listGoods description]
     * @param  integer $firstRow [分页起始]
     * @param  integer $listRows [分页结束]
     * @param  [type]  $where    [筛选条件]
     * @return [type]            [商品列表]
     */
    public function listGoods($firstRow = 0, $listRows = 20, $where) {
        $auction = M('Auction');
        $list = $this->order("`published` DESC")->limit($firstRow.','.$listRows)->where($where)->select();
        $aidArr = M("Admin")->field("`aid`,`email`,`nickname`")->select();
        foreach ($aidArr as $k => $v) {
            $aids[$v['aid']] = $v;
        }
        unset($aidArr);
        $cate = M("Goods_category")->select();
        $cids = array_reduce($cate, create_function('$v,$w', '$v[$w["cid"]]=$w["name"];return $v;'));

        $filt = M("goods_filtrate")->select();
        $fids = array_reduce($filt, create_function('$v,$w', '$v[$w["fid"]]=$w["name"];return $v;'));
        $cat = new \Org\Util\Category();
        $repeat=M('auction_repeat');
        foreach ($list as $k => $v) {
            $list[$k]['cidName'] = $cids[$v['cid']];
            $uPath = $cat->getPath($cate,$v['cid']);
            $list[$k]['pidName'] = $uPath[0]['name'];
            // 拍品统计
            $list[$k]['bidcount'] = $auction->where(array('gid'=>$v['id']))->count();
            if (!empty($v['filtrate'])) {
                $filtarr = explode('_', $v['filtrate']);
                foreach ($filtarr as $fk => $fv) {
                    $filtarr[$fk]=$fids[$fv];
                }
                $list[$k]['filtrate'] = $filtarr;
            }
            // 是否设置重复拍
            $list[$k]['repeat'] = $repeat->where(array('type'=>0,'rid'=>$v['id']))->getField('id');
        }
        return $list;
    }
    /**
     * 分类操作
     * @return [type] [分类结构]
     */
    public function category() {
        if (IS_POST) {
            $act = I('post.act');
            $data = I('post.data');
            $data['name'] =addslashes($data['name']) ;
            $nameArr = explode(',', addslashes($data['name'])) ;
            $M = M("Goods_category");
            $this->cacheNull();
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
                $rst = $M->save($data);
                if($rst){
                    return array('status' => 1, 'info' => '分类 ' . $data['name'] . ' 已经成功更新', 'url' => U('Goods/category', array('time' => time())));
                }else{
                    if ($rst==0) {
                        return array('status' => 0, 'info' => '未做修改');
                    }else{
                        return array('status' => 0, 'info' => '分类 ' . $data['name'] . ' 更新失败','url'=>U('Goods/category', array('time' => time())));
                    }
                }          
            }
        } else {
            $cat = new \Org\Util\Category();
            return $cat->getList(M('Goods_category'));
        }
    }
    /**
     * 条件操作
     * @return [type] [条件结构]
     */
    public function filtrate() {
        if (IS_POST) {
            $act = $_POST[act];
            $data = $_POST['data'];
            $data['name'] = addslashes($data['name']);
            $nameArr = explode(',', addslashes($data['name']));
            $M = M("Goods_filtrate");
            if ($act == "add") { //添加条件
                foreach ($nameArr as $nk => $nv) {
                    if($nv !=''){
                          $newData = array(
                            'pid'=>$data['pid'],
                            'name'=>$nv
                            );
                        if ($M->where($newData)->count() == 0) {
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
                    return array('status' => 1, 'info' => $info, 'url' => U('Goods/filtrate', array('time' => time())));
                }else{
                    if($errorName !='') {
                        $info .= $errorName.'添加失败<br/>';
                    }elseif($reName !=''){
                       $info .= $reName.'已存在并跳过<br/>';
                    }
                    return array('status' => 0, 'info' => $info );
                }
            } else if ($act == "edit") { //修改条件
                $rst = $M->save($data);
                if($rst){
                    return array('status' => 1, 'info' => '条件 ' . $data['name'] . ' 已经成功更新', 'url' => U('Goods/category', array('time' => time())));
                }else{
                    if ($rst==0) {
                        return array('status' => 0, 'info' => '未做修改');
                    }else{
                        return array('status' => 0, 'info' => '条件 ' . $data['name'] . ' 更新失败','url'=>U('Goods/category', array('time' => time())));
                    }
                }
            } else if ($act == "del") { //删除条件
                unset($data['pid'], $data['name']);
                return ($M->where($data)->delete()) ? array('status' => 1, 'info' => '条件 ' . $data['name'] . ' 已经成功删除', 'url' => U('Goods/filtrate', array('time' => time()))) : array('status' => 0, 'info' => '条件 ' . $data['name'] . ' 删除失败');
            }
        } else {
            $cat = new \Org\Util\Category();
            return $cat->getList(M('Goods_filtrate'),'fid',0,null,'sort desc');
        }
    }
    /**
     * 分类条件关联
     * @return [type] [description]
     */
    public function cate_filt(){
        $data = I('post.data');
        $cate = M("Goods_category");
        $filt =  M("Goods_filtrate");
        $cName = $data['cid'] != 0 ? $cate->where('cid='. $data['cid'])->order('sort desc')->getField('name') : '顶级分类';
        $fName = $data['fid'] != 0 ? $filt->where('fid='. $data['fid'])->order('sort desc')->getField('name') : '顶级条件';
        $M = M("Goods_category_filtrate");
        if ($M->where($data)->count() == 0) {
            if($data['cid'] ==0 || $data['fid'] ==0){
                // 顶级分类对应顶级条件循环写入关联
                if($data['cid'] ==0 & $data['fid'] ==0){
                    $cateMap = $cate->where('pid=0')->select();
                    $filtMap = $filt->where('pid=0')->order('sort desc')->select();
                    $repCount = 0;
                    foreach ($cateMap as $ck => $cv) {
                        foreach ($filtMap as $fk => $fv) {
                            $autoData = array();
                            $autoData = array(
                                'cid'=>$cv['cid'],
                                'fid'=>$fv['fid']
                                );
                            if($M->where($autoData)->count() == 0){
                                $M->add($autoData);
                            }else{
                               $repCount +=1; 
                            }
                        }
                        if($repCount != 0){
                          return array('status' => 1, 'info' => '关联成功，'.$repCount.'个重复关联已跳过', 'url' => U('Goods/cate_filt', array('time' => time())));  
                        }
                    }
                }
                // 某分类对应顶级条件循环写入关联
                if($data['cid'] !=0 & $data['fid'] ==0){
                    $filtMap = $filt->where('pid=0')->order('sort desc')->select();
                    $repCount = 0;
                    foreach ($filtMap as $fk => $fv) {
                        $autoData = array();
                        $autoData = array(
                            'cid'=>$data['cid'],
                            'fid'=>$fv['fid']
                            );
                        if($M->where($autoData)->count() == 0){
                            $M->add($autoData);
                        }else{
                           $repCount +=1; 
                        }
                    }
                    if($repCount != 0){
                        return array('status' => 1, 'info' => '关联成功，'.$repCount.'个重复关联已跳过', 'url' => U('Goods/cate_filt', array('time' => time())));  
                    }
                }
                // 顶级分类对应某条件循环写入关联
                if($data['cid'] ==0 & $data['fid'] !=0){
                    $cateMap = $cate->where('pid=0')->select();
                    $repCount = 0;
                    foreach ($cateMap as $ck => $cv) {
                        $autoData = array();
                        $autoData = array(
                            'cid'=>$cv['cid'],
                            'fid'=>$data['fid']
                            );
                        if($M->where($autoData)->count() == 0){
                            $M->add($autoData);
                        }else{
                           $repCount +=1; 
                        }
                    }
                    if($repCount != 0){
                        return array('status' => 1, 'info' => '关联成功，'.$repCount.'个重复关联已跳过','url' => U('Goods/cate_filt', array('time' => time())));
                    }
                }
                return array('status' => 1, 'info' => $cName . '<---->'.$fName.'——关联成功', 'url' => U('Goods/cate_filt', array('time' => time())));
            }
            return ($M->add($data)) ? array('status' => 1, 'info' => $cName . '<---->'.$fName.'——关联成功', 'url' => U('Goods/cate_filt', array('time' => time()))) : array('status' => 0, 'info' => $cName . '<---->'.$fName.'——关联失败');
        } else {
            return array('status' => 0, 'info' => $cName . '<---->'.$fName.'已关联，无需重复');
        }
    }
    /**
     * 添加编辑商品
     */
    public function addEdit($act,$sellerid,$pid=0) {
        $M = M("Goods");
        $data = $_POST['data'];
        if (!$data['sellerid']) {
            return array('status' => 0, 'info' => "请选择所属用户！");
        }
        if (!$data['title']) {
            return array('status' => 0, 'info' => "标题不能为空！");
        }
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
                $url = U('Goods/edit',array('pid'=>$pid));
            }else{
                if(I('post.to')!=''){
                    $url = U('Goods/add',array('to'=>'js','gid'=>$gid));
                    $msgA = '进入发布拍卖！';
                }else{
                    $url = U('Goods/index');
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
     * 扩展字段添加
     * @return [type] [description]
     */
    public function fields_add(){
        $M = M('goods_extend');
        $info = I('post.info');
        if(empty($info['eid'])){
            unset($info['eid']);
            if ($M->add($info)) {
                return array('status' => 1, 'info' => "添加成功", 'url' => U('Goods/fields_list'));
            } else {
                return array('status' => 0, 'info' => "添加失败，请刷新页面尝试操作");
            }
        }else{
            if ($M->save($info)) {
                return array('status' => 1, 'info' => "已经更新", 'url' => U('Goods/fields_list'));
            } else {
                return array('status' => 0, 'info' => "更新失败，请刷新页面尝试操作");
            }
        }
    }
    /**
     * 频道分类扩展字段关联
     * @return [type] [description]
     */
    public function cate_extend(){
        $data = I('post.data');
        $cate = M("Goods_category");
        $extend =  M("Goods_extend");

        $cName = $data['cid'] != 0 ? $cate->where('cid='. $data['cid'])->getField('name') : '频道';
        if($data['eid']!=''){
            if($data['eid']!=0){
                $eName =$extend->where('eid='. $data['eid'])->getField('name');
            }else{
                $eName ='地区';
            }
        }else{
            $eName ='所有字段';
        };

        $M = M("Goods_category_extend");
        if ($M->where($data)->count() == 0) {
            if($data['cid'] ==0 || $data['eid'] ==''){
                // 所有频道对应所有字段循环写入关联
                if($data['cid'] ==0 & $data['eid'] ==''){
                    $cateMap = $cate->where('pid=0')->select();
                    $extendMap = $extend->select();
                    $repCount = 0;
                    foreach ($cateMap as $ck => $cv) {
                        //关联用户扩展字段
                        foreach ($extendMap as $fk => $fv) {
                            $autoData = array();
                            $autoData = array(
                                'cid'=>$cv['cid'],
                                'eid'=>$fv['eid']
                                );
                            if($M->where($autoData)->count() == 0){
                                $M->add($autoData);
                            }else{
                               $repCount +=1; 
                            }
                            
                        }
                        //关联内置字段-地区的关联为0
                        $region = array('cid'=>$cv['cid'],'eid'=>0);
                        if($M->where($region)->count() == 0){
                            $M->add($region);
                        }else{
                               $repCount +=1; 
                        }
                    }
                }
                // 某频道或分类对应所有字段循环写入关联
                if($data['cid'] !=0 && $data['eid'] ==''){
                    $extendMap = $extend->select();
                    $repCount = 0;
                    foreach ($extendMap as $fk => $fv) {
                        $autoData = array();
                        $autoData = array('cid'=>$data['cid'],'eid'=>$fv['eid']);
                        if($M->where($autoData)->count() == 0){
                            $M->add($autoData);
                        }else{
                           $repCount +=1; 
                        }
                    }
                    //关联内置字段-地区的关联为0
                    $region = array('cid'=>$data['cid'],'eid'=>0);
                    if($M->where($region)->count() == 0){
                        $M->add($region);
                    }else{
                        $repCount +=1; 
                    }
                }
                // 顶级分类对应某条件循环写入关联
                if($data['cid'] ==0 && $data['eid'] !=''){
                    $cateMap = $cate->where('pid=0')->select();
                    $repCount = 0;
                    foreach ($cateMap as $ck => $cv) {
                        $autoData = array();
                        $autoData = array(
                            'cid'=>$cv['cid'],
                            'eid'=>$data['eid']
                            );
                        if($M->where($autoData)->count() == 0){
                            $M->add($autoData);
                        }else{
                           $repCount +=1; 
                        }
                    }
                }
                if($repCount != 0){
                    return array('status' => 1, 'info' => '关联成功，'.$repCount.'个重复关联已跳过', 'url' => U('Goods/cate_extend', array('time' => time())));  
                }else{
                    return array('status' => 1, 'info' => $cName . '<---->'.$eName.'——关联成功', 'url' => U('Goods/cate_extend', array('time' => time())));
                }
            }else{
                return ($M->add($data)) ? array('status' => 1, 'info' => $cName . '<---->'.$eName.'——关联成功', 'url' => U('Goods/cate_extend', array('time' => time()))) : array('status' => 0, 'info' => $cName . '<---->'.$eName.'——关联失败');
            }
        } else {
            return array('status' => 0, 'info' => $cName . '<---->'.$eName.'已关联，无需重复');
        }
    }

    // 清除分类的缓存
    private function cacheNull() {
        // 清楚前台全部分类的S缓存
        S(C('CACHE_FIX').'_allcate',null);
    }
}

?>
