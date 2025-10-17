<?php
/**
 * 首页控制器
 * 负责处理首页显示、搜索等核心功能
 * 
 * @package Home\Controller
 * @author System
 * @version 2.0
 * 
 * 功能说明：
 * 1. 首页数据加载和展示（拍卖会、专场、商品等）
 * 2. 商品搜索功能
 * 3. 分类浏览
 * 4. 用户个性化数据（订单状态、足迹等）
 */
namespace Home\Controller;
use Think\Controller;

class IndexController extends CommonController {

    /**
     * 测试方法（用于调试）
     * 显示缓存数据和时间信息
     * 
     * @return void
     */
    public function testpre(){
        pre(date('Y-m-d H:i','1578319204'));
        pre(S('test1'));
        pre(S('post'));
        pre(S('content'));
        pre(S('imgnames'));
    }

    /**
     * 首页主方法
     * 加载并显示首页所需的所有数据
     * 
     * 数据加载顺序：
     * 1. 本站头条新闻
     * 2. 用户个人数据（登录用户）
     * 3. 专场和拍卖会列表
     * 4. 频道分类商品
     * 5. 即将开拍和最近成交商品
     * 6. 友情链接
     * 
     * @return void
     */
    public function index(){
        // 初始化分类工具
        $cat = new \Org\Util\Category();
        
        // 加载本站头条新闻（带缓存）
        $this->newtop = $this->getTopNews($cat);
        
        // 获取当前用户ID
        $uid = $this->cUid;
        
        // 加载用户个人数据（仅登录用户）
        if ($uid) {
            // 加载用户订单统计
            $this->ocount = $this->getUserOrderCount($uid);
            // 加载用户浏览足迹
            $this->footprint = $this->getUserFootprint($uid);
        }
        

        // 不显示被屏蔽用户的拍品、专场、拍卖会
        $notinUid = array();
        if ($xUidarr = blackuser($uid)) {
            $notinUid = array('sellerid'=>array('not in',$xUidarr));
        }
        // 开始的拍品、专场、拍卖会条件
        $stWhere = bidSection();
        // 专场、拍卖会内拍品必须大于0才显示
        $bcount = array('bcount'=>array('gt',0));
        // 专场拍卖【
        $special = M('special_auction');
        $slist = M('special_auction')->where(array_merge($stWhere,$notinUid,$bcount))->limit(3)->select();
        $this->slist=$slist;
        // 专场拍卖】
        // 拍卖会【
        $ftWhere = array(
            'starttime'=>array('lt',time()),
            'endtime'=>array('gt',time())
            );
        $meeting = M('meeting_auction');
        $mlist = M('meeting_auction')->where(array_merge($ftWhere,$notinUid,$bcount))->limit(3)->select();
        $this->mlist=$mlist;
        // 拍卖会】
        // 默认显示的拍品数量
        $limit = 12;
        // 显示的字段
        $field = 'pid,pname,nowprice,clcount,pictures,onset,bidcount';
        // 频道板块【
        $gCategory = M("goods_category");
        $auction = D('Auction');
        $gCate = $gCategory->order('sort desc')->select();
        $channel = $gCategory->where(array('pid'=>0,'hot'=>1))->field('cid,name,remark,modelno')->order('sort desc')->select();
        foreach ($channel as $ck => $cv) {
            // 获取需要查询的CID集合
            $gCidarr = $cat->getChildsId($gCate,$cv['cid']);
            //获取分类下推荐分类
            $channel[$ck]['hotcate'] = $gCategory->where(array('cid'=>array('in',$gCidarr),'hot'=>1))->field('cid,name')->select();

            array_unshift($gCidarr,$cv['cid']);//包含主分类
            $inCid = array('cid'=>array('in',$gCidarr));
            // 组合条件
            $tWhere = array_merge($stWhere,$notinUid,$inCid);
            $tCount = $auction->where($tWhere)->count();
            $channel[$ck]['count'] = $tCount;
            if ($cv['modelno'] == 1) {
                // 效果一必须大于7个商品
                if ($tCount>=7) {$channel[$ck]['modelno'] =1;$limit = 7;}else{$channel[$ck]['modelno'] =0;}
            }elseif ($cv['modelno'] == 2) {
                // 效果一必须大于9个商品
                if ($tCount>=9) {$channel[$ck]['modelno'] =2;$limit = 9;}else{$channel[$ck]['modelno'] =0;}
            }
            // 按照结束时间倒叙获取该频道需要显示商品
            $channel[$ck]['list'] = $auction->where($tWhere)->where($notinUid)->field($field)->limit($limit)->order('endtime desc')->select();
        }

        $this->channel = $channel;
        // 频道板块】

        // 即将开拍【
        $fuTime = foreshow(0);
        $fuWhere = array_merge($notinUid,$fuTime);
        $fulist = D('Auction')->where($fuWhere)->order('starttime desc')->field($field)->limit($limit)->select();
        $this->fulist=$fulist;
        // 即将开拍】

        //最近成交拍品列表处理-----------------------------------------------start
        $edTime = endbid(0);
        $edWhere = array_merge($notinUid,$edTime);
        $edlist = D('Auction')->where($edWhere)->order('endtime desc')->field($field)->limit($limit)->select();
        $this->edlist=$edlist;
        //最近成交拍品列表处理-----------------------------------------------end
        // 友情链接
        $link = M('link');
        $lklist = array(
            'txt'=>$link->order('sort desc')->where('rec = 0')->select(),
            'pic'=> $link->order('sort desc')->where('rec = 1')->select()
            );
        $this->lklist=$lklist;
        // 显示全部分类
        $this->cateshow=1;
        // 计算小导航限时数量
        $sm_avg = 2; 
        if (C('Auction.sort_time') == 1) {$sm_avg +=1;}
        if (C('Auction.yikoujia') == 1) {$sm_avg +=1;}
        if (C('Auction.jianlou') == 1) {$sm_avg +=1;}
        if (C('Auction.special_page') == 1) {$sm_avg +=1;}
        if (C('Auction.meeting_page') == 1) {$sm_avg +=1;}
        $this->sm_avg=$sm_avg;
        $this->display();
     }

    /**
     * 获取本站头条新闻（带缓存）
     * 使用2小时缓存以提高性能
     * 
     * @param \Org\Util\Category $cat 分类工具实例
     * @return array 返回头条新闻数据
     */
    private function getTopNews($cat) {
        $cacheKey = C('CACHE_FIX').'_newtop';
        
        // 尝试从缓存获取
        if ($newtop = S($cacheKey)) {
            return $newtop;
        }
        
        // 缓存未命中，从数据库加载
        $category = M("Category");
        $newtop['cname'] = $category->where(array('cid'=>2))->getField('name');
        $cate = $category->order('sort desc')->select();
        $cidarr = $cat->getChildsId($cate, 2);
        
        // 包含主分类下的新闻
        array_unshift($cidarr, 2);
        
        // 查询最新6条已发布的新闻
        $newtop['list'] = D('News')
            ->where(array(
                'cid' => array('in', $cidarr),
                'status' => 1
            ))
            ->limit(6)
            ->field('id,title,name')
            ->order('published desc')
            ->select();
        
        // 保存到缓存（2小时）
        S($cacheKey, $newtop, 7200);
        
        return $newtop;
    }

    /**
     * 获取用户订单统计数据
     * 统计各个状态的订单数量
     * 
     * @param int $uid 用户ID
     * @return array 返回订单统计数组
     */
    private function getUserOrderCount($uid) {
        $order = M('Goods_order');
        $baseWhere = array('uid' => $uid);
        $ocount = array();
        
        // 定义订单状态配置
        $orderStatus = array(
            'fukuai' => array('status' => 0, 'deftime1st' => 0),  // 待付款
            'fahuo' => array('status' => 1, 'deftime2st' => 0),   // 待发货
            'shouhuo' => array('status' => 2, 'deftime3st' => 0), // 待收货
            'pingjia' => array('status' => 3, 'deftime4st' => 0)  // 待评价
        );
        
        // 循环统计各状态订单数量
        foreach ($orderStatus as $key => $condition) {
            $where = array_merge($baseWhere, $condition);
            $ocount[$key] = $order->where($where)->count();
        }
        
        return $ocount;
    }

    /**
     * 获取用户浏览足迹
     * 获取用户最近浏览的商品列表
     * 
     * @param int $uid 用户ID
     * @return array 返回足迹商品列表
     */
    private function getUserFootprint($uid) {
        $goods = M('goods');
        
        // 获取用户足迹记录
        $pidstr = M('member_footprint')
            ->where(array('uid' => $uid))
            ->getField('pidstr');
        
        if (empty($pidstr)) {
            return array();
        }
        
        // 字符串反序列化为数组
        $pidarr = unserialize($pidstr);
        
        // 去重：通过数组翻转两次来删除重复的拍品ID
        $pidarr = array_flip(array_flip($pidarr));
        
        // 转换成字符串方便SQL查询
        $pidstr = implode(',', $pidarr);
        
        // 查询足迹对应的拍卖商品
        $footprint = M('Auction')
            ->where("pid in (".$pidstr.")")
            ->where(array('hide' => 0))
            ->order("field(pid,".$pidstr.")")
            ->field('pid,gid,nowprice')
            ->limit(50)
            ->select();
        
        // 补充商品图片信息
        foreach ($footprint as $ftkey => $ftvl) {
            $footprint[$ftkey]['pictures'] = $goods
                ->where(array('id' => $ftvl['gid']))
                ->getField('pictures');
        }
        
        return $footprint;
    }

    /**
     * 商品搜索
     * 根据关键词搜索拍品，支持按类型和状态筛选
     * 
     * @return void
     */
     public function search(){
        // 获取搜索参数
        $keywords = I('get.keywords');
        $type = I('get.type');
        
        // 构建搜索条件
        $listwhere = $this->buildSearchConditions($keywords, $type);
        
        // 确定排序方式
        $orderBy = $this->getSearchOrderBy($type);
        
        // 执行搜索并分页
        $rdata = D('Auction')->auctionList($listwhere, $orderBy, C('PAGE_SIZE'));
        
        // 分配数据到模板
        $this->page = $rdata['page']; 
        $this->list = $rdata['list'];
        $this->saytyp = array('get' => $type, 'keywords' => $keywords);
        
        $this->display();
     }

    /**
     * 构建搜索条件
     * 根据关键词和类型构建查询条件数组
     * 
     * @param string $keywords 搜索关键词
     * @param string $type 搜索类型（biding/bidend/future）
     * @return array 返回查询条件数组
     */
    private function buildSearchConditions($keywords, $type) {
        // 初始化搜索条件
        $listwhere = array();
        
        // 关键词复合搜索条件（名称或关键词字段）
        $conplex = array(
            'pname' => array('LIKE', '%' . $keywords . '%'),
            'keywords' => array('LIKE', '%' . $keywords . '%'),
            '_logic' => 'or'
        );
        
        // 根据拍卖类型添加时间条件
        if (in_array($type, array('biding', 'bidend', 'future'))) {
            $ws = bidType($type);
            $listwhere = $ws['bidType'];
        }
        
        // 基础条件：不显示隐藏商品
        $listwhere['hide'] = array('eq', 0);
        $listwhere['_complex'] = $conplex;
        
        // 过滤黑名单用户的商品
        if ($xUidarr = blackuser($this->cUid)) {
            $listwhere['sellerid'] = array('not in', $xUidarr);
        }
        
        // 是否只显示成交商品
        if (I('get.cj')) {
            $listwhere['endstatus'] = 1;
            $this->cj = 1;
        }
        
        return $listwhere;
    }

    /**
     * 获取搜索排序方式
     * 根据不同的搜索类型返回对应的排序规则
     * 
     * @param string $type 搜索类型
     * @return string 返回排序字段和方式
     */
    private function getSearchOrderBy($type) {
        $orderMapping = array(
            'biding' => 'endtime',           // 正在拍卖：按结束时间升序
            'bidend' => 'endtime desc',      // 已结束：按结束时间降序
            'future' => 'starttime asc'      // 即将开拍：按开始时间升序
        );
        
        // 返回对应排序方式，默认按开始时间降序
        return isset($orderMapping[$type]) ? $orderMapping[$type] : 'starttime desc';
    }

    /**
     * IE浏览器升级提示页
     * 针对旧版IE浏览器显示升级提示
     * 
     * @return void
     */
    public function iealert(){
        $this->display();
    }

    /**
     * 商品分类页面
     * 显示完整的商品分类树形结构
     * 
     * @return void
     */
    public function cate(){
        $this->cateshow = 1;
        $this->catepage = 1;
        $this->display();
    }

    /**
     * 更多内容页面
     * 显示更多相关信息
     * 
     * @return void
     */
    public function more(){
        $this->display();
    }

    /**
     * 测试方法A（用于调试）
     * 测试POST请求发送功能
     * 
     * @return void
     */
    public function testa(){
        $url = U('Index/testb', '', true);
        $datas = array(
            'gol' => 1,
            'openid' => '123456',
            'access_token' => '654321',
            'create' => 'auto'
        );
        pre(sendPost($url, $datas));
    }

}