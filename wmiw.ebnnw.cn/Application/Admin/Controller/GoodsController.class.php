<?php
namespace Admin\Controller;
use Think\Controller;
class GoodsController extends CommonController {
    /**
     +----------------------------------------------------------
     * 商品列表
     +----------------------------------------------------------
     */
    public function index() {
        $channel = M('goods_category')->where('pid=0')->select(); //读取频道
        $this->channel=$channel; //分配频道
        $M = M("Goods");
        $count = $M->count();
        $pConf = page($count,C('PAGE_SIZE'));
        $this->page = $pConf['show'];
        $this->list = D("Goods")->listGoods($pConf['first'], $pConf['list']);
        C('TOKEN_ON',false);
        $this->display();
    }
     //异步获取频道下分类
    public function getcate(){
        $pid=I('post.pid');
        $cid=I('post.cid');
        $required=I('post.required');
        $cateHtml='';
        if($pid!=''){
            $cat = new \Org\Util\Category();
            $cate = $cat->getList(M('Goods_category'),'cid',$pid);
            if (empty($cate)) {
                echojson(array("status" => 0));
            }else{
                $cateHtml='<select id="Js-cate" class="catesel" name="data[cid]"';
                if ($required!=0) {
                    $cateHtml.=' required';
                }
                $cateHtml.='><option value="">所有分类</option>';
                foreach ($cate as $ck => $cv) {
                    $cateHtml.='<option value="'.$cv['cid'].'"';
                    if ($cid==$cv['cid']) {
                        $cateHtml.=' selected="selected"';
                    }
                    $cateHtml.='>'.$cv['fullname'].'</option>';
                }
                $cateHtml.='</select>';
                echojson(array("status" => 1, "htm" => $cateHtml));
            }
        }else{
            echojson(array("status" => 0));
        }
        
    }

    /**
     +----------------------------------------------------------
     * 搜索商品
     +----------------------------------------------------------
     */
    public function search(){
        $keyW = I('get.');
        $encode = mb_detect_encoding($keyW['keyword'], array("ASCII","UTF-8","GB2312","GBK","BIG5"));
        $keyW['keyword'] = iconv($encode,"utf-8//IGNORE",$keyW['keyword']);
        $category = M('Goods_category');
         if($keyW['pid']!=''){
            $cat = new \Org\Util\Category();
            $chname=  $category->where('cid='.$keyW['pid'])->getField('name');
            if($keyW['cid']==''){
                $catecid = $cat->getList($category,'cid',$keyW['pid']);
                $catecid = array_reduce($catecid, create_function('$v,$w', '$v[$w["cid"]]=$w["cid"];return $v;'));
                $catecid[] = $keyW['pid'];
                $where['cid'] = array('in',$catecid);
                $catname = '所有'; 
            }else{
                $catecid = $cat->getList($category,'cid',$keyW['cid']);
                $catecid = array_reduce($catecid, create_function('$v,$w', '$v[$w["cid"]]=$w["cid"];return $v;'));
                $catecid[] = $keyW['cid'];
                $where['cid'] = array('in',$catecid);
                $catname = $category->where('cid='.$keyW['cid'])->getField('name');
            }
        }else{
            $chname = '所有';
            $catname = '所有'; 
        }
        if($keyW['keyword'] != '') $where['title'] = array('LIKE', '%' . $keyW['keyword'] . '%');
        $M = M("Goods");
        $count = $M->where($where)->count();
        $pConf = page($count,C('PAGE_SIZE'));
        $channel = $category->where('pid=0')->select(); //读取频道
        $keyS = array('count' =>$count,'keyword'=>$keyW['keyword'],'chname' => $chname,'catname' => $catname,'pid'=>$keyW['pid']);
        $this->keys = $keyS;
        $this->page = $pConf['show'];
        
        $this->channel=$channel; //分配频道

        $this->list = D("Goods")->listGoods($pConf['first'], $pConf['list'],$where);
        C('TOKEN_ON',false);
        $this->display('index');
    }
    /**
     +----------------------------------------------------------
     * 添加商品
     +----------------------------------------------------------
     */
    public function add() {
        if (IS_POST) {
            $this->checkToken();
            echojson(D("Goods")->addEdit('add',$this->cUid));
        } else {
            $describe = include APP_PATH . 'Common/Conf/FieldsDescribe.php';
            $info=array(
                'province'=>$uinfo['province'],
                'city'=>$uinfo['city'],
                'area'=>$uinfo['area'],
                'content'=>stripslashes($describe['FIELDS_DESCRIBE'])
            ); //获取地区的层数并分配
            $this->info=$info;
            $channel = M('goods_category')->where('pid=0')->select(); //读取频道
            $this->channel=$channel; //分配频道
            $this->display();
        }

    }
    // -------搜索用户
    public function search_user(){
        if (IS_POST) {
            $M = M("Member");
            $data = I('post.');
            $where = array($data[field]=>array('LIKE','%'.$data['keyword'].'%'));
            $where['organization']=array('neq','');
            $count = $M->where($where)->count();
            if ($count) {
                $list=$M->where($where)->field('uid,account,organization,avatar_sel')->order('uid desc')->select();
                $html = '';
                foreach ($list as $lk => $lv) {
                    if ($lv['avatar_sel']) {
                        $avatar_sel = C('WEB_ROOT').str_replace('./', '', C('UPLOADS_PICPATH')).picRep($lv['avatar_sel'],2,'user');
                    }else{
                        $avatar_sel = C('WEB_ROOT').'Public/Home/Img/default_2.gif';
                    }
                    $html.='<li><label class="am-checkbox">';
                    $html.='<input name="data[sellerid]" value="'.$lv['uid'].'" data-am-ucheck="" class="am-ucheck-checkbox" type="radio"><span class="am-ucheck-icons"><i class="am-icon-unchecked"></i><i class="am-icon-checked"></i></span>';
                    $html.='<div class="user-label-txt"><img class="ulb-img" src="'.$avatar_sel.'">';
                    $html.='<div class="ulb-txt">账号：'.$lv['account'].'<br>商家名：'.$lv['organization'];
                    $html.='</div></div></label></li>';
                }
                echojson(array("status" => 1,'count'=>$count, "html" => $html));
            }else{
                echojson(array("status" => 0,'count'=>$count, "html" => '<li>没有查询到用户</li>'));
            }
        }
    }
    //------异步删除商品图片
    public function goods_delpic() {

        $imgUrl = I('post.imgUrl');
        if ($imgUrl) {
            $imgDelUrl = C('UPLOADS_PICPATH').I('post.imgUrl');
        }else{
            $imgDelUrl = C('UPLOADS_PICPATH').I('post.savepath').I('post.savename');
            @unlink(C('UPLOADS_PICPATH').I('post.savepath').'thumb'.I('post.savename'));
        }
        if(@unlink($imgDelUrl)){
            $goods = M('Goods');
            $gd_pic = $goods->where(array('id'=>$goodsId))->find();
            //组合要写入数据
            $newPic = str_replace('||','|',trim(str_replace($imgUrl, '', $gd_pic['pictures']),'|'));
            $data = array(
                'id' => I('post.goodsId'),
                'pictures' => $newPic
                );
            $goods->save($data);
            @unlink($imgDelUrl);
            //循环删除缩略图
            $picFix = explode(',',C('GOODS_PIC_PREFIX'));
            foreach ($picFix as $pfK => $pfV) {
                @unlink( C('UPLOADS_PICPATH').picRep($imgUrl,$pfK));
            }
            echojson(array('status' => 1,'msg' => '已从数据库删除成功!'));
        }else{
            echojson(array('status' => 0,'msg' => '删除失败，刷新页面重试!'));
        }
    }
    // ------通过分类cid获取对应商品属性
    public function getFilt(){
        echojson(array("status" => 1, "html" => getFiltrateHtmlSeller(I('post.cid'),I('post.filtStr'))));
    }
    // ------通过分类cid获取对应扩展字段
    public function getExtends(){
        $rtdata=getExtendsHtml(I('post.cid'),I('post.gid'));
        echojson(array("status" => 1, "ulhtml" => $rtdata['eUrlHtml'],"divhtml" => $rtdata['eDivHtml'],'textarr'=>$rtdata['textarea'],'region'=>$rtdata['region']));
    }
    // ------检查标题是否重复
    public function checkNewsTitle() {
        $M = M("Goods");
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
     +----------------------------------------------------------
     * 编辑商品
     +----------------------------------------------------------
     */
    public function edit() {
        $pid=I('get.pid');
        if (IS_POST) {
            $this->checkToken();
            echojson(D("Goods")->addEdit('edit',$this->cUid,$pid));
        } else {
            $goods = D('Goods');
            $info = $goods->where("id=" . (int) $_GET['id'])->find();
            if ($info['id'] == '') {
                $this->error("不存在该记录");
            }
            if ($info['pictures']) {
                $info['pictures'] = explode('|', $info['pictures']);
            }
            $info['content']=stripslashes($info['content']);
            $info['layer']=C('goods_region'); //获取地区的层数并分配sss
            $category = M('goods_category');
            $cat = new \Org\Util\Category();
            $channel = $category->where('pid=0')->order('sort desc')->select(); //读取频道
            $this->channel=$channel; //分配频道
            $cate = $category->order('sort desc')->select();
            $uPath = $cat->getPath($cate,$info['cid']);
            $info['pid'] = $uPath[0]['cid'];
            $this->pid=$pid;
            $this->info=$info;

            
            $this->display("add");
        }
    }
    //------异步排序商品图片
    public function goodPicOrder(){
        C('TOKEN_ON',false);
        if (IS_POST) {
            $data = array(
                'id' => I('post.goodsId'),
                'pictures' => I('post.imgArr')
                );
            if(M('Goods')->save($data)){
                echojson(array('status' => 1, 'msg' => "排序成功，已保存到数据库"));
            }else{
                echojson(array('status' => 0, 'msg' => "排序失败，请刷新页面尝试操作"));
            }
        }
    }
    //------删除商品
    public function del_goods() {
        $goods = M("Goods");
        $where = array('id'=>I('get.id'));
        $pictures = $goods->where($where)->getField('pictures');
        $picarr = explode('|', $pictures);
        $fixct = count(explode(',', C('GOODS_PIC_PREFIX')));
        $imgDelUrl = C('UPLOADS_PICPATH');
        foreach ($picarr as $pk => $pv) {
            $fixkey = 0;
            for ($i=0; $i < $fixct; $i++) { 
                @unlink($imgDelUrl.picRep($pv,$i));
            }
            @unlink($imgDelUrl.$pv);
        }
        if ($goods->where($where)->delete()) {
            $this->success("成功删除");
           // echojson(array("status"=>1,"info"=>""));
        } else {
            $this->error("删除失败，可能是不存在该ID的记录");
        }
    }
    //------异步删除商品图片
    public function del_pic() {
        $imgUrl = I('post.imgUrl');
        $imgDelUrl = C('UPLOADS_PICPATH').I('post.imgUrl'); //要删除图片地址
        $goodsId = I('post.goodsId'); //商品ID
        if($goodsId){
            $goods = M('Goods');
            $gd_pic = $goods->where(array('id'=>$goodsId))->find();
            //组合要写入数据
            $newPic = str_replace('||','|',trim(str_replace($imgUrl, '', $gd_pic['pictures']),'|'));
            $data = array(
                'id' => I('post.goodsId'),
                'pictures' => $newPic
                );

            if($goods->save($data)){
                $ecJson = array(
                    'status' => 1,
                    'msg' => '删除成功!'
                    );
                @unlink($imgDelUrl);
                //循环删除缩略图
                $picFix = explode(',',C('GOODS_PIC_PREFIX'));
                foreach ($picFix as $pfK => $pfV) {
                    @unlink( C('UPLOADS_PICPATH').picRep($imgUrl,$pfK));
                }
                //输出结果
                echojson($ecJson);
            }else{
                $ecJson = array(
                    'status' => 0,
                    'msg' => '删除失败，刷新页面重试!'
                    );
                echojson($ecJson);
            }
        }else{
            if(@unlink($imgDelUrl)){
                echojson(array(
                'status' => 1,
                'msg' => '已从服务器删除成功!'
                ));
            }else{
                echojson(array(
                'status' => 0,
                'msg' => '删除失败，请检查文件权限!'
                ));
            }
            
        }
    }
/**
 +----------------------------------------------------------
 * 分类
 +----------------------------------------------------------
 */
 // 分类列表
    public function category() {
        if (IS_POST) {
            echojson(D("Goods")->category());
        } else {
            $this->assign("list", D("Goods")->category());
            $this->display();
        }
    }
    // 分类添加
    public function category_add() {
        if (IS_POST) {
            $this->checkToken();
            echojson(D("Goods")->category());
        } else {
            $info = M('Goods_category')->where(array('cid'=>I('get.cid')))->find();
            $this->list = D("Goods")->category();
            $info['act']='add';
            $this->info=$info;
            $this->display();
        }
    }
    // 分类编辑
    public function category_edit() {
        if (IS_POST) {
            $this->checkToken();
            echojson(D("Goods")->category());
        } else {
            $info = M('Goods_category')->where(array('cid'=>I('get.cid')))->find();
            $this->list = D("Goods")->category();
            $info['act']='edit';
            $this->info=$info;
            $this->display('category_add');
        }
    }
    // 分类删除
    public function category_del() {
        if (IS_POST) {
            $category = M('Goods_category');
            $cat = new \Org\Util\Category();
            $cate = $category->select();
            $cidarr=$cat->getChildsId($cate,I('post.cid'));
            $count = count($cidarr);
            if ($count==0||I('post.yn')==1) {
                $cidarr[]=I('post.cid');
                $where = array('cid'=>array('in',$cidarr));
                $rst = $category->where($where)->delete();
                if ($rst) {
                    echojson(array('status'=>1,'info'=>'已删除'.count($cidarr).'条分类记录！','url'=>U('Goods/category')));
                }else{
                    echojson(array('status'=>0,'info'=>'删除失败！','url'=>U('Goods/category')));
                }
            }else{
                echojson(array('status'=>2,'info'=>'该分类下存在子类会一并删除。是否继续？'));
            }
            
        }
    }
    // 商品属性
    public function filtrate(){
        if (IS_POST) {
            echojson(D("Goods")->filtrate());
        } else {
            $this->list = D("Goods")->filtrate();
            $this->display();
        }
    }

    // 商品属性添加
    public function filtrate_add() {
        if (IS_POST) {
            $this->checkToken();
            echojson(D("Goods")->filtrate());
        } else {
            $info = M('Goods_filtrate')->where(array('fid'=>I('get.fid')))->find();
            $this->list = D("Goods")->filtrate();
            $info['act']='add';
            $this->info=$info;
            $this->display();
        }
    }
    // 商品属性编辑
    public function filtrate_edit() {
        if (IS_POST) {
            $this->checkToken();
            echojson(D("Goods")->filtrate());
        } else {
            $info = M('Goods_filtrate')->where(array('fid'=>I('get.fid')))->find();
            $this->list = D("Goods")->filtrate();
            $info['act']='edit';
            $this->info=$info;
            $this->display('filtrate_add');
        }
    }
    // 商品属性删除
    public function filtrate_del() {
        if (IS_POST) {
            $filtrate = M('Goods_filtrate');
            $cat = new \Org\Util\Category();
            $cate = $filtrate->select();
            $fidarr=$cat->getChildsId($cate,I('post.fid'));
            $count = count($fidarr);
            if ($count==0||I('post.yn')==1) {
                $fidarr[]=I('post.fid');
                $where = array('fid'=>array('in',$fidarr));
                $rst = $filtrate->where($where)->delete();
                if ($rst) {
                    echojson(array('status'=>1,'info'=>'已删除'.count($fidarr).'条分类记录！','url'=>U('Goods/filtrate')));
                }else{
                    echojson(array('status'=>0,'info'=>'删除失败！','url'=>U('Goods/filtrate')));
                }
            }else{
                echojson(array('status'=>2,'info'=>'该分类下存在子类会一并删除。是否继续？'));
            }
            
        }
    }

    //---分类异步排序
    public function order_cate() {
        if (IS_POST) {
            $getInfo = I('post.');
            $M = M('goods_category');
            $where=array('cid'=>$getInfo['odid']);
            // 清楚前台全部分类的S缓存
            S(C('CACHE_FIX').'_allcate',null);
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
    //---扩展字段异步排序
    public function order_filtrate() {
        if (IS_POST) {
            $getInfo = I('post.');
            $M = M('goods_filtrate');
            $where=array('fid'=>$getInfo['odid']);
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
 +----------------------------------------------------------
 * 分类和属性关联
 +----------------------------------------------------------
 */
    public function cate_filt(){
        $c_f = M('Goods_category_filtrate');
        $cfMap = $c_f->select();
        $cMap = $c_f->getField('cid',true);
        $cMap = array_unique($cMap); //去除重复的Cid
        sort($cMap); //对数组进行排序
        // 根据分类输出关联关系
        $newMap = array();
        $i = 0;
        foreach ($cMap as $cK => $cV) {
           foreach ($cfMap as $fK => $fV) {
                if($cV ==$fV['cid']){
                    $newMap[$i]['cid']=$cV;
                    $newMap[$i]['fid'][]=$fV['fid'];
                }
            } 
            $i +=1;
        }
        // 通过父级条件查询到下二级子条件
        $filtMap = M('Goods_filtrate')->order('sort desc')->select();
        foreach ($newMap as $mK => $mV) {
            foreach ($mV['fid'] as $sfk => $sfv) {
                foreach($filtMap as $v){
                  if($v['pid']==$sfv){
                    $newMap[$mK]['sid'][$sfk][]=$v['fid'];
                  }
                }
            }
        }
        $this->map=$newMap;
        $this->display();
    }
    // 添加分类与属性关联
    public function cate_filt_add(){
        if (IS_POST) {
            echojson(D("Goods")->cate_filt());
        } else {
            $this->cate = D("Goods")->category();
            $this->filt = D("Goods")->filtrate();
            $this->display();
        }
    }
    //ajax获取组合后的下级条件
    public function getChild(){
        if (IS_POST) {
            if(I('post.fid') != ''){
                echojson(array('status' => 1, 'msg' => getChildHtmlSeller(I('post.fid'))));
            }
        } else {
            E('哎哟！怎么到这里了?');
        }
    }
    // ajax解除关联
    public function delLink(){
        if (IS_POST) {
            $where = array('cid'=>I('post.cid'));
            if(I('post.fid') != 0){
                $where['fid'] = I('post.fid');
            }
            if(M('Goods_category_filtrate')->where($where)->delete()){
               echojson(array('status' => 1, 'msg' => '解除关联成功')); 
            }else{
               echojson(array('status' => 0, 'msg' => '解除关联失败，请刷新重试')); 
            }
        } else {
            E('哎哟！怎么到这里了?');
        }
    }
    /**
     +----------------------------------------------------------
     * 商品扩展字段配置
     +----------------------------------------------------------
     */
    public function fields_list(){
        $this->gdcof = include APP_PATH . 'Common/Conf/SetExtend.php';
        $list = M('goods_extend')->order('rank desc')->select();
        $this->list=$list;
        $this->display();
    }
    // 添加/编辑扩展字段
    public function fields_add(){
        if (IS_POST) {
            $this->checkToken();
            echojson(D('goods')->fields_add());
        }else{
            $info = M('Goods_extend')->where(array('eid'=>I('get.eid')))->find();
            $info['default']=stripslashes($info['default']);
            $this->info=$info;
            $this->display();
        }
    }

    // 商品详情默认值编辑
    public function fields_describe(){
        if (IS_POST) {
            $this->checkToken();
            $config = APP_PATH . "Common/Conf/FieldsDescribe.php";
            $config = file_exists($config) ? include "$config" : array();
            $config = is_array($config) ? $config : array();
            if (set_config("FieldsDescribe", I('post.'), APP_PATH . "Common/Conf/")) {
                delDirAndFile(WEB_CACHE_PATH . "Cache/Admin/");
                echojson(array('status' => 1, 'info' => '商品详情默认值已更新','url'=>U('Goods/fields_list')));
            } else {
                echojson(array('status' => 0, 'info' => '商品详情默认值更新失败，请检查', 'url' => __ACTION__));
            }
        } else {
            $FieldsDescribe = include APP_PATH . 'Common/Conf/FieldsDescribe.php';
            $this->describe=stripslashes($FieldsDescribe['FIELDS_DESCRIBE']);
            $this->display();
        }

    }

    // 删除扩展字段
    public function delField(){
        if (M("Goods_extend")->where("eid=" . (int) $_GET['id'])->delete()) {
            $this->success("成功删除");
        } else {
            $this->error("删除失败，可能是不存在该ID的记录");
        }
    }
    // ------异步字段排序
    public function order_fields() {
        if (IS_POST) {
            $getInfo = I('post.');
            $M = M('Goods_extend');
            $where=array('eid'=>$getInfo['odid']);
            if($getInfo['odType'] == 'rising'){
                if($M->where($where)->setInc('rank')){
                    echojson(array('status'=>'1','msg'=>'排序写入数据库成功'));
                }
            }elseif($getInfo['odType'] == 'drop'){
                if($M->where($where)->setDec('rank')){
                    echojson(array('status'=>'0','msg'=>'排序写入数据库失败'));
                }
            }
        } else {
            E('页面不存在');
        }
    }
    /**
     +----------------------------------------------------------
     *分类与扩展字段关联
     +----------------------------------------------------------
     */
     public function cate_extend(){
            $extend = M('goods_extend');
            $c_e = M('Goods_category_extend');
            $ceMap = $c_e->select();
            $cMap = $c_e->getField('cid',true);
            $cMap = array_unique($cMap); //去除重复的Cid
            sort($cMap); //对数组进行排序
            // 根据分类输出关联关系
            $newMap = array();
            $i = 0;
            foreach ($cMap as $cK => $cV) {
               foreach ($ceMap as $fK => $fV) {
                    if($fV['eid']!=0){  //判断字段是不是地区 不是地区读数据库
                        if($cV ==$fV['cid']){
                            $newMap[$i]['cid']=$cV;
                            $newMap[$i]['extend'][$fV['eid']]=$extend->where('eid='.$fV['eid'])->getField('name');
                        }  
                    }else{
                        if($cV ==$fV['cid']){
                            $newMap[$i]['cid']=$cV;
                            $newMap[$i]['extend'][0]='地区';
                        }
                    }
                } 
                $i +=1;
            }
            $this->map=$newMap;
            $this->display();
     }
     // 添加分类字段关联
    public function cate_extend_add(){
        if (IS_POST) {
            $this->checkToken();
            echojson(D("Goods")->cate_extend());
        } else {
            $this->cate = D("Goods")->category();
            $this->extend = M('goods_extend')->field('eid,name')->select(); 
            $this->display();
        }
    }
    // ------删除关联
    public function delExtend(){
        if (IS_POST) {
            $where = array('cid'=>I('post.cid'));
            if(I('post.eid') != ''){
                $where['eid'] = I('post.eid');
            }
            if(M('Goods_category_extend')->where($where)->delete()){
               echojson(array('status' => 1, 'msg' => '解除关联成功')); 
            }else{
               echojson(array('status' => 0, 'msg' => '解除关联失败，请刷新重试')); 
            }
        } else {
            E('哎哟！怎么到这里了?');
        }
    }
}